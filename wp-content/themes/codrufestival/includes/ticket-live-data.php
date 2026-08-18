<?php

declare(strict_types=1);

function codru_strip_ticket_price_segment(string $name): string
{
    $title = preg_replace('/\s*-\s*\d+(?:[.,]\d+)?\s*EUR(?:\s*\+\s*taxes)?(?:\/ticket)?/i', '', $name);
    $title = preg_replace('#/ticket#i', '', (string) $title);
    $title = preg_replace('/\s*-\s*$/', '', (string) $title);
    $title = preg_replace('/\s+/', ' ', (string) $title);

    return trim((string) $title);
}

function codru_normalize_ticket_text(string $title): string
{
    $title = codru_strip_ticket_price_segment($title);
    $title = html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    if (function_exists('remove_accents')) {
        $title = remove_accents($title);
    } else {
        $transliterated_title = function_exists('iconv') ? iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $title) : false;
        $title = $transliterated_title !== false ? $transliterated_title : $title;
    }

    $title = function_exists('mb_strtolower') ? mb_strtolower($title, 'UTF-8') : strtolower($title);
    $title = preg_replace('/[^a-z0-9]+/', ' ', $title);

    return trim((string) preg_replace('/\s+/', ' ', (string) $title));
}

function codru_ticket_category_key(string $title): ?string
{
    $normalized = codru_normalize_ticket_text($title);

    if ($normalized === '') {
        return null;
    }

    if (preg_match('/\b(?:under\s*25|u25)\b/', $normalized)) {
        return 'under_25';
    }

    if (strpos($normalized, 'general access') !== false || preg_match('/\bga\b/', $normalized)) {
        return 'general_access';
    }

    return null;
}

function codru_ticket_title_from_tariff_name(string $name): string
{
    return codru_strip_ticket_price_segment($name);
}

function codru_format_ticket_price_amount(string $raw_price): string
{
    $price = str_replace(',', '.', trim($raw_price));

    if ($price === '') {
        return '';
    }

    if (str_contains($price, '.')) {
        $price = rtrim(rtrim($price, '0'), '.');
    }

    return $price;
}

function codru_ticket_display_price_from_tariff_name(string $name): ?string
{
    if (!preg_match('/-\s*(\d+(?:[.,]\d+)?)\s*EUR\b/i', $name, $matches)) {
        return null;
    }

    $price = codru_format_ticket_price_amount($matches[1]);

    return $price !== '' ? $price . ' €' : null;
}

function codru_ticket_display_price_from_sell_data(string $sell_price, string $sell_currency): ?string
{
    $price = codru_format_ticket_price_amount($sell_price);
    $currency = strtoupper(trim($sell_currency));

    if ($price === '' || $currency === '') {
        return null;
    }

    if ($currency === 'RON') {
        return $price . ' RON';
    }

    return $price . ' ' . $currency;
}

function codru_ticket_display_price(string $name, string $sell_price = '', string $sell_currency = ''): ?string
{
    $display_price = codru_ticket_display_price_from_tariff_name($name);

    if ($display_price !== null) {
        return $display_price;
    }

    return codru_ticket_display_price_from_sell_data($sell_price, $sell_currency);
}

function codru_read_live_tickets_payload(string $json_path): array
{
    if (!file_exists($json_path)) {
        return [];
    }

    $payload = json_decode((string) file_get_contents($json_path), true);

    if (json_last_error() !== JSON_ERROR_NONE || empty($payload['tickets']) || !is_array($payload['tickets'])) {
        return [];
    }

    return $payload['tickets'];
}

function codru_get_live_display_tickets(string $json_path): array
{
    $tickets = codru_read_live_tickets_payload($json_path);
    $display_tickets = [];

    foreach ($tickets as $ticket) {
        if (empty($ticket['display_price'])) {
            continue;
        }

        $title = (string) ($ticket['title'] ?? codru_ticket_title_from_tariff_name((string) ($ticket['name'] ?? '')));

        if ($title === '') {
            continue;
        }

        $display_tickets[] = [
            'id' => (string) ($ticket['id'] ?? ''),
            'title' => $title,
            'display_price' => (string) $ticket['display_price'],
            'category_key' => codru_ticket_category_key($title),
        ];
    }

    usort($display_tickets, static function (array $left, array $right): int {
        $order = [
            'general_access' => 0,
            'under_25' => 1,
        ];

        $left_order = $order[$left['category_key'] ?? ''] ?? 99;
        $right_order = $order[$right['category_key'] ?? ''] ?? 99;

        if ($left_order !== $right_order) {
            return $left_order <=> $right_order;
        }

        return strcasecmp($left['title'], $right['title']);
    });

    return $display_tickets;
}

function codru_get_ticket_card_defaults(): array
{
    $defaults = [
        'description' => function_exists('get_multilingual_text')
            ? get_multilingual_text(
                '*prețul afișat nu include taxele și comisionul de ticketing.',
                '*The displayed price does not include taxes and the ticketing fee.',
                'ro'
            )
            : '*prețul afișat nu include taxele și comisionul de ticketing.',
        'button_url' => 'https://bilete.codrufestival.ro/',
        'button_text' => function_exists('get_multilingual_text')
            ? get_multilingual_text('Cumpără', 'Buy', 'ro')
            : 'Cumpără',
    ];

    $ticket_button_url = function_exists('get_field') ? get_field('ticket_button_url', 'options') : null;

    if (!empty($ticket_button_url)) {
        $defaults['button_url'] = (string) $ticket_button_url;
    }

    $acf_rows = function_exists('get_field') ? get_field('ticket_cards_repeater', 'options') : null;

    if (is_array($acf_rows) && !empty($acf_rows[0]) && is_array($acf_rows[0])) {
        $first_row = $acf_rows[0];

        if (!empty($first_row['description'])) {
            $defaults['description'] = (string) $first_row['description'];
        }

        if (!empty($first_row['button_url'])) {
            $defaults['button_url'] = (string) $first_row['button_url'];
        }

        if (!empty($first_row['button_text'])) {
            $defaults['button_text'] = (string) $first_row['button_text'];
        }
    }

    return $defaults;
}

function codru_get_live_tickets_by_category(array $tickets): array
{
    $lookup = [];

    foreach ($tickets as $ticket) {
        if (empty($ticket['display_price'])) {
            continue;
        }

        $category_key = !empty($ticket['category_key'])
            ? (string) $ticket['category_key']
            : codru_ticket_category_key((string) ($ticket['title'] ?? $ticket['name'] ?? ''));

        if ($category_key === null || $category_key === '') {
            continue;
        }

        $lookup[$category_key] = [
            'id' => (string) ($ticket['id'] ?? ''),
            'title' => (string) ($ticket['title'] ?? ''),
            'display_price' => (string) $ticket['display_price'],
            'category_key' => $category_key,
        ];
    }

    return $lookup;
}

function codru_resolve_live_ticket_for_card(string $card_title, array $live_tickets_by_category): ?array
{
    $category_key = codru_ticket_category_key($card_title);

    if ($category_key !== null && isset($live_tickets_by_category[$category_key])) {
        return $live_tickets_by_category[$category_key];
    }

    $match_key = codru_normalize_ticket_text($card_title);

    foreach ($live_tickets_by_category as $live_ticket) {
        $live_match_key = codru_normalize_ticket_text($live_ticket['title'] ?? '');

        if ($live_match_key !== '' && $live_match_key === $match_key) {
            return $live_ticket;
        }
    }

    return null;
}

function codru_ticket_display_snapshot(array $tickets): array
{
    $snapshot = [];

    foreach ($tickets as $ticket) {
        if (empty($ticket['display_price'])) {
            continue;
        }

        $title = (string) ($ticket['title'] ?? codru_ticket_title_from_tariff_name((string) ($ticket['name'] ?? '')));
        $id = (string) ($ticket['id'] ?? '');
        $snapshot_key = $id !== '' ? $id : codru_normalize_ticket_text($title);

        if ($snapshot_key === '' || $title === '') {
            continue;
        }

        $snapshot[$snapshot_key] = [
            'title' => $title,
            'display_price' => (string) $ticket['display_price'],
        ];
    }

    ksort($snapshot);

    return $snapshot;
}

function codru_tickets_display_changed(array $previous_tickets, array $new_tickets): bool
{
    return codru_ticket_display_snapshot($previous_tickets) !== codru_ticket_display_snapshot($new_tickets);
}
