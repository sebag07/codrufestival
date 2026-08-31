<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/ticket-live-data.php';

// Temporary 2027 shop URL until tariffs return to https://bilete.codrufestival.ro/
$source_url = 'https://bilete.codrufestival.ro/bilete-codru-festival-2027-130642/?ica_source=HPWhitelabel&ica_medium=whitelabel&ica_campaign=&ica_term=';
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
        $display_price = codru_ticket_display_price(
            $name,
            $node->getAttribute('data-tariff-sell-price'),
            $node->getAttribute('data-tariff-sell-currency')
        );

        if ($name === '' || $display_price === null) {
            continue;
        }

        $title = codru_ticket_title_from_tariff_name($name);
        $category_key = codru_ticket_category_key($title);

        $tickets[] = [
            'id' => $node->getAttribute('data-tariff-id'),
            'name' => $name,
            'title' => $title,
            'category_key' => $category_key,
            'match_key' => codru_normalize_ticket_text($title),
            'display_price' => $display_price,
            'sell_price' => $node->getAttribute('data-tariff-sell-price'),
            'sell_currency' => $node->getAttribute('data-tariff-sell-currency'),
        ];
    }

    return $tickets;
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

    if (codru_tickets_display_changed($previous_tickets, $tickets)) {
        try {
            codru_purge_litespeed_cache();
        } catch (Throwable $purge_exception) {
            fwrite(STDERR, 'LiteSpeed cache purge failed: ' . $purge_exception->getMessage() . PHP_EOL);
        }
    } else {
        printf("Ticket display data unchanged; skipped LiteSpeed cache purge.\n");
    }
} catch (Throwable $exception) {
    fwrite(STDERR, 'Ticket scrape failed: ' . $exception->getMessage() . PHP_EOL);
    exit(1);
}
