<?php

declare(strict_types=1);

$source_url = 'https://bilete.codrufestival.ro/';
$theme_root = dirname(__DIR__);
$output_path = $theme_root . '/data/tickets-live.json';

function codru_fetch_ticket_page(string $url): string
{
    if (function_exists('curl_init')) {
        $curl = curl_init($url);

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_USERAGENT => 'CODRU Festival ticket scraper/1.0',
        ]);

        $body = curl_exec($curl);
        $error = curl_error($curl);
        $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);

        if ($body === false || $status >= 400) {
            throw new RuntimeException(sprintf('Failed to fetch ticket page. HTTP %d %s', $status, $error));
        }

        return (string) $body;
    }

    $context = stream_context_create([
        'http' => [
            'timeout' => 30,
            'header' => "User-Agent: CODRU Festival ticket scraper/1.0\r\n",
        ],
    ]);

    $body = file_get_contents($url, false, $context);

    if ($body === false) {
        throw new RuntimeException('Failed to fetch ticket page.');
    }

    return $body;
}

function codru_ticket_title_from_tariff_name(string $name): string
{
    $title = preg_replace('/\s*-\s*\d+(?:[.,]\d+)?\s*EUR(?:\s*\+\s*taxes)?\s*$/i', '', $name);

    return trim((string) $title);
}

function codru_ticket_display_price_from_tariff_name(string $name): ?string
{
    if (!preg_match('/-\s*(\d+(?:[.,]\d+)?)\s*EUR\b/i', $name, $matches)) {
        return null;
    }

    $price = str_replace(',', '.', $matches[1]);
    $price = rtrim(rtrim($price, '0'), '.');

    return $price . ' €';
}

function codru_normalize_ticket_title(string $title): string
{
    $title = codru_ticket_title_from_tariff_name($title);
    $title = html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $transliterated_title = function_exists('iconv') ? iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $title) : false;
    $title = $transliterated_title !== false ? $transliterated_title : $title;
    $title = function_exists('mb_strtolower') ? mb_strtolower($title, 'UTF-8') : strtolower($title);
    $title = preg_replace('/[^a-z0-9]+/u', ' ', $title);

    return trim((string) preg_replace('/\s+/', ' ', (string) $title));
}

function codru_parse_tickets(string $html): array
{
    $document = new DOMDocument();

    libxml_use_internal_errors(true);
    $document->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_NOERROR | LIBXML_NOWARNING);
    libxml_clear_errors();

    $xpath = new DOMXPath($document);
    $nodes = $xpath->query('//*[@data-is-tariff="1" and @data-tariff-name]');
    $tickets = [];

    foreach ($nodes ?: [] as $node) {
        if (!$node instanceof DOMElement) {
            continue;
        }

        $name = trim($node->getAttribute('data-tariff-name'));
        $display_price = codru_ticket_display_price_from_tariff_name($name);

        if ($name === '' || $display_price === null) {
            continue;
        }

        $title = codru_ticket_title_from_tariff_name($name);

        $tickets[] = [
            'id' => $node->getAttribute('data-tariff-id'),
            'name' => $name,
            'title' => $title,
            'match_key' => codru_normalize_ticket_title($title),
            'display_price' => $display_price,
            'sell_price' => $node->getAttribute('data-tariff-sell-price'),
            'sell_currency' => $node->getAttribute('data-tariff-sell-currency'),
        ];
    }

    return $tickets;
}

function codru_ticket_price_map(array $tickets): array
{
    $prices = [];

    foreach ($tickets as $ticket) {
        if (empty($ticket['match_key']) || empty($ticket['display_price'])) {
            continue;
        }

        $prices[(string) $ticket['match_key']] = (string) $ticket['display_price'];
    }

    ksort($prices);

    return $prices;
}

function codru_ticket_prices_changed(array $previous_tickets, array $new_tickets): bool
{
    return codru_ticket_price_map($previous_tickets) !== codru_ticket_price_map($new_tickets);
}

function codru_load_wordpress(): void
{
    static $loaded = false;

    if ($loaded) {
        return;
    }

    $wp_load = realpath(dirname(__DIR__) . '/../../../wp-load.php');

    if ($wp_load === false || !is_readable($wp_load)) {
        throw new RuntimeException('Could not locate wp-load.php for cache purge.');
    }

    require_once $wp_load;
    $loaded = true;
}

function codru_purge_litespeed_cache(): void
{
    codru_load_wordpress();

    if (!defined('LITESPEED_PURGE_SILENT')) {
        define('LITESPEED_PURGE_SILENT', true);
    }

    if (!class_exists('\LiteSpeed\Purge')) {
        fwrite(STDERR, "LiteSpeed Cache is not active; skipped cache purge.\n");

        return;
    }

    \LiteSpeed\Purge::purge_all_lscache('CODRU ticket price sync');

    printf("Queued LiteSpeed page cache purge.\n");
}

try {
    $previous_tickets = [];

    if (file_exists($output_path)) {
        $previous_payload = json_decode((string) file_get_contents($output_path), true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($previous_payload['tickets'] ?? null)) {
            $previous_tickets = $previous_payload['tickets'];
        }
    }

    $html = codru_fetch_ticket_page($source_url);
    $tickets = codru_parse_tickets($html);

    if (empty($tickets)) {
        throw new RuntimeException('No ticket tariffs were found in the ticket page markup.');
    }

    $payload = [
        'updated_at' => date(DATE_ATOM),
        'source' => $source_url,
        'tickets' => $tickets,
    ];

    $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    if ($json === false) {
        throw new RuntimeException('Failed to encode ticket data as JSON.');
    }

    $temporary_path = $output_path . '.tmp';

    if (file_put_contents($temporary_path, $json . PHP_EOL, LOCK_EX) === false) {
        throw new RuntimeException('Failed to write temporary ticket data file.');
    }

    if (!rename($temporary_path, $output_path)) {
        @unlink($temporary_path);
        throw new RuntimeException('Failed to replace ticket data file.');
    }

    printf("Wrote %d ticket(s) to %s\n", count($tickets), $output_path);

    if (codru_ticket_prices_changed($previous_tickets, $tickets)) {
        try {
            codru_purge_litespeed_cache();
        } catch (Throwable $purge_exception) {
            fwrite(STDERR, 'LiteSpeed cache purge failed: ' . $purge_exception->getMessage() . PHP_EOL);
        }
    } else {
        printf("Ticket prices unchanged; skipped LiteSpeed cache purge.\n");
    }
} catch (Throwable $exception) {
    fwrite(STDERR, 'Ticket scrape failed: ' . $exception->getMessage() . PHP_EOL);
    exit(1);
}
