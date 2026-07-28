<?php

declare(strict_types=1);

function codru_normalize_ticket_text(string $title): string
{
    $title = preg_replace('/\s*-\s*\d+(?:[.,]\d+)?\s*EUR(?:\s*\+\s*taxes)?\s*$/i', '', $title);
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

    if (preg_match('/\bunder\s*25\b/', $normalized)) {
        return 'under_25';
    }

    if (strpos($normalized, 'general access') !== false || preg_match('/\bga\b/', $normalized)) {
        return 'general_access';
    }

    return null;
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
        $category_key = !empty($ticket['category_key'])
            ? (string) $ticket['category_key']
            : codru_ticket_category_key((string) ($ticket['title'] ?? $ticket['name'] ?? ''));

        if ($category_key === null || $category_key === '' || empty($ticket['display_price'])) {
            continue;
        }

        $snapshot[$category_key] = [
            'title' => (string) ($ticket['title'] ?? ''),
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
