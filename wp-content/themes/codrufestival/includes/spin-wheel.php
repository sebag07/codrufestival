<?php

declare(strict_types=1);

const CODRUFESTIVAL_SPIN_WHEEL_CAMPAIGN = 'v1';
const CODRUFESTIVAL_SPIN_WHEEL_ENABLED_DEFAULT = false;
const CODRUFESTIVAL_SPIN_WHEEL_COOKIE = 'codrufestival_spin_v1';
const CODRUFESTIVAL_SPIN_WHEEL_RESULT_KEY = 'codru_spin_result';
const CODRUFESTIVAL_SPIN_WHEEL_DISMISSED_KEY = 'codrufestival_spin_wheel_dismissed_v1';
const CODRUFESTIVAL_SPIN_WHEEL_NAMESPACE = 'codrufestival/v1';
const CODRUFESTIVAL_SPIN_WHEEL_ROUTE = '/spin-wheel';

function codrufestival_spin_wheel_is_enabled(): bool
{
    if (function_exists('get_field')) {
        $enabled = get_field('spin_wheel_enabled', 'options');

        if ($enabled !== null && $enabled !== '') {
            return (bool) $enabled;
        }
    }

    return CODRUFESTIVAL_SPIN_WHEEL_ENABLED_DEFAULT;
}

/**
 * @return list<string>
 */
function codrufestival_spin_wheel_hex_palette(): array
{
    return array(
        '#2ecc5a',
        '#28e069',
        '#1ff97a',
        '#12ff6e',
        '#22ff5c',
        '#00ff41',
        '#18d964',
        '#0ef07a',
        '#39ff7a',
        '#5dff90',
        '#7cffaa',
        '#9dffc4',
    );
}

/**
 * @param list<array{pct:int,code:string,weight:int}> $rows
 * @return list<array{id:int,pct:int,code:string,weight:int,hex:string}>
 */
function codrufestival_spin_wheel_normalize_prizes(array $rows): array
{
    $palette = codrufestival_spin_wheel_hex_palette();
    $prizes = array();
    $id = 0;

    foreach ($rows as $row) {
        $pct = isset($row['pct']) ? (int) $row['pct'] : 0;
        $code = isset($row['code']) ? strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) $row['code'])) : '';
        $weight = isset($row['weight']) ? (int) round((float) $row['weight']) : 0;

        if ($pct <= 0 || $code === '' || $weight <= 0) {
            continue;
        }

        $prizes[] = array(
            'id' => $id,
            'pct' => $pct,
            'code' => $code,
            'weight' => $weight,
            'hex' => $palette[$id % count($palette)],
        );
        $id++;
    }

    return $prizes;
}

/**
 * @return list<array{pct:int,code:string,weight:int}>
 */
function codrufestival_spin_wheel_default_prize_rows(): array
{
    return array(
        array('pct' => 20, 'code' => 'CDR3K7XCG', 'weight' => 49),
        array('pct' => 25, 'code' => 'CDR0D5B2A', 'weight' => 30),
        array('pct' => 30, 'code' => 'CDR41TLST', 'weight' => 10),
        array('pct' => 15, 'code' => 'CDRL3CEM1', 'weight' => 9),
        array('pct' => 50, 'code' => 'CDRJGLP2A', 'weight' => 1),
        array('pct' => 10, 'code' => 'CDRQMAVWW', 'weight' => 1),
    );
}

/**
 * @param list<array<string,mixed>> $rows
 * @return list<array{id:int,pct:int,code:string,weight:int,hex:string}>
 */
function codrufestival_spin_wheel_prizes_from_acf(array $rows): array
{
    $input = array();

    foreach ($rows as $row) {
        if (!is_array($row)) {
            continue;
        }

        $input[] = array(
            'pct' => isset($row['discount_pct']) ? (int) $row['discount_pct'] : 0,
            'code' => isset($row['discount_code']) ? sanitize_text_field((string) $row['discount_code']) : '',
            'weight' => isset($row['win_chance_pct']) ? (float) $row['win_chance_pct'] : 0,
        );
    }

    return codrufestival_spin_wheel_normalize_prizes($input);
}

/**
 * Campaign prizes stay on the server. Weights and codes must not be sent to the browser.
 *
 * @return list<array{id:int,pct:int,code:string,weight:int,hex:string}>
 */
function codrufestival_spin_wheel_prizes(): array
{
    static $cached = null;

    if ($cached !== null) {
        return $cached;
    }

    if (function_exists('get_field')) {
        $rows = get_field('spin_wheel_prizes', 'options');

        if (is_array($rows) && $rows !== array()) {
            $prizes = codrufestival_spin_wheel_prizes_from_acf($rows);

            if ($prizes !== array()) {
                $cached = $prizes;

                return $cached;
            }
        }
    }

    $cached = codrufestival_spin_wheel_normalize_prizes(codrufestival_spin_wheel_default_prize_rows());

    return $cached;
}

/**
 * @return list<array{pct:int,hex:string}>
 */
function codrufestival_spin_wheel_public_segments(): array
{
    $segments = array();

    foreach (codrufestival_spin_wheel_prizes() as $prize) {
        $segments[] = array(
            'pct' => $prize['pct'],
            'hex' => $prize['hex'],
        );
    }

    return $segments;
}

function codrufestival_spin_wheel_cookie_name(): string
{
    return CODRUFESTIVAL_SPIN_WHEEL_COOKIE;
}

function codrufestival_spin_wheel_sign(string $payload): string
{
    return hash_hmac('sha256', $payload, wp_salt('auth'));
}

function codrufestival_spin_wheel_encode_cookie(int $prize_id): string
{
    $payload = CODRUFESTIVAL_SPIN_WHEEL_CAMPAIGN . '.' . $prize_id;

    return $payload . '.' . codrufestival_spin_wheel_sign($payload);
}

function codrufestival_spin_wheel_decode_cookie(?string $cookie): ?int
{
    if (!is_string($cookie) || $cookie === '') {
        return null;
    }

    $parts = explode('.', $cookie);

    if (count($parts) !== 3) {
        return null;
    }

    [$campaign, $prize_id_raw, $signature] = $parts;

    if ($campaign !== CODRUFESTIVAL_SPIN_WHEEL_CAMPAIGN || !ctype_digit($prize_id_raw)) {
        return null;
    }

    $payload = $campaign . '.' . $prize_id_raw;

    if (!hash_equals(codrufestival_spin_wheel_sign($payload), $signature)) {
        return null;
    }

    $prize_id = (int) $prize_id_raw;
    $prizes = codrufestival_spin_wheel_prizes();

    return isset($prizes[$prize_id]) ? $prize_id : null;
}

function codrufestival_spin_wheel_set_cookie(int $prize_id): void
{
    $value = codrufestival_spin_wheel_encode_cookie($prize_id);
    $name = codrufestival_spin_wheel_cookie_name();

    $_COOKIE[$name] = $value;

    if (headers_sent()) {
        return;
    }

    $path = defined('COOKIEPATH') && COOKIEPATH !== '' ? COOKIEPATH : '/';
    $domain = defined('COOKIE_DOMAIN') ? (string) COOKIE_DOMAIN : '';

    setcookie($name, $value, array(
        'expires' => time() + YEAR_IN_SECONDS,
        'path' => $path,
        'domain' => $domain,
        'secure' => is_ssl(),
        'httponly' => true,
        'samesite' => 'Lax',
    ));
}

/**
 * @return list<string>
 */
function codrufestival_spin_wheel_allowed_hosts(): array
{
    return array_values(array_unique(array_filter(array(
        wp_parse_url(home_url(), PHP_URL_HOST),
        wp_parse_url(site_url(), PHP_URL_HOST),
    ))));
}

function codrufestival_spin_wheel_host_is_allowed(?string $url): bool
{
    if (!is_string($url) || $url === '') {
        return false;
    }

    $host = wp_parse_url($url, PHP_URL_HOST);

    if (!is_string($host) || $host === '') {
        return false;
    }

    foreach (codrufestival_spin_wheel_allowed_hosts() as $allowed_host) {
        if (strcasecmp($host, $allowed_host) === 0) {
            return true;
        }
    }

    return false;
}

function codrufestival_spin_wheel_is_same_origin(WP_REST_Request $request): bool
{
    $origin = $request->get_header('origin');

    if (is_string($origin) && $origin !== '') {
        return codrufestival_spin_wheel_host_is_allowed($origin);
    }

    $referer = $request->get_header('referer');

    return codrufestival_spin_wheel_host_is_allowed($referer);
}

/**
 * @return array{id:int,pct:int,code:string,weight:int,hex:string}|null
 */
function codrufestival_spin_wheel_prize_by_id(int $prize_id): ?array
{
    $prizes = codrufestival_spin_wheel_prizes();

    return $prizes[$prize_id] ?? null;
}

/**
 * @return array{id:int,pct:int,code:string,weight:int,hex:string}
 */
function codrufestival_spin_wheel_pick_prize(): array
{
    $prizes = codrufestival_spin_wheel_prizes();
    $total_weight = 0;

    foreach ($prizes as $prize) {
        $total_weight += $prize['weight'];
    }

    $roll = random_int(1, $total_weight);
    $running = 0;

    foreach ($prizes as $prize) {
        $running += $prize['weight'];

        if ($roll <= $running) {
            return $prize;
        }
    }

    return $prizes[0];
}

/**
 * @param array{id:int,pct:int,code:string,weight:int,hex:string} $prize
 * @return array{pct:int,code:string,index:int}
 */
function codrufestival_spin_wheel_public_result(array $prize): array
{
    return array(
        'pct' => $prize['pct'],
        'code' => $prize['code'],
        'index' => $prize['id'],
    );
}

function codrufestival_spin_wheel_handle_request(WP_REST_Request $request)
{
    if (!codrufestival_spin_wheel_is_enabled()) {
        return new WP_Error(
            'codrufestival_spin_wheel_disabled',
            'The spin wheel is currently unavailable.',
            array('status' => 403)
        );
    }

    if (!codrufestival_spin_wheel_is_same_origin($request)) {
        return new WP_Error(
            'codrufestival_spin_wheel_forbidden',
            'This spin request is not allowed.',
            array('status' => 403)
        );
    }

    $existing_id = codrufestival_spin_wheel_decode_cookie($_COOKIE[codrufestival_spin_wheel_cookie_name()] ?? null);

    if ($existing_id !== null) {
        $existing_prize = codrufestival_spin_wheel_prize_by_id($existing_id);

        if ($existing_prize !== null) {
            $response = new WP_REST_Response(codrufestival_spin_wheel_public_result($existing_prize), 200);
            $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');

            return $response;
        }
    }

    $prize = codrufestival_spin_wheel_pick_prize();
    codrufestival_spin_wheel_set_cookie($prize['id']);

    $response = new WP_REST_Response(codrufestival_spin_wheel_public_result($prize), 200);
    $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');

    return $response;
}

function codrufestival_spin_wheel_register_rest_route(): void
{
    register_rest_route(CODRUFESTIVAL_SPIN_WHEEL_NAMESPACE, CODRUFESTIVAL_SPIN_WHEEL_ROUTE, array(
        'methods' => 'POST',
        'callback' => 'codrufestival_spin_wheel_handle_request',
        'permission_callback' => '__return_true',
    ));
}

add_action('rest_api_init', 'codrufestival_spin_wheel_register_rest_route');

function codrufestival_spin_wheel_should_render(): bool
{
    if (!codrufestival_spin_wheel_is_enabled()) {
        return false;
    }

    if (is_admin() || wp_doing_ajax() || wp_is_json_request()) {
        return false;
    }

    if (defined('REST_REQUEST') && REST_REQUEST) {
        return false;
    }

    $excluded_templates = array(
        'templates/coming-soon.php',
        'templates/thank-you-template.php',
        'templates/politica-cookies.php',
        'templates/pre-register.php',
        'templates/termeni-si-conditii.php',
    );

    foreach ($excluded_templates as $template) {
        if (is_page_template($template)) {
            return false;
        }
    }

    return function_exists('codrufestival_react_island');
}

function codrufestival_get_spin_wheel_props(): array
{
    $text = static function (string $ro_text, string $en_text): string {
        return function_exists('get_multilingual_text')
            ? get_multilingual_text($ro_text, $en_text)
            : $en_text;
    };

    return array(
        'campaignVersion' => CODRUFESTIVAL_SPIN_WHEEL_CAMPAIGN,
        'endpoint' => esc_url_raw(rest_url(CODRUFESTIVAL_SPIN_WHEEL_NAMESPACE . CODRUFESTIVAL_SPIN_WHEEL_ROUTE)),
        'ticketUrl' => 'https://bilete.codrufestival.ro',
        'segments' => codrufestival_spin_wheel_public_segments(),
        'storageKeys' => array(
            'result' => CODRUFESTIVAL_SPIN_WHEEL_RESULT_KEY,
            'dismissed' => CODRUFESTIVAL_SPIN_WHEEL_DISMISSED_KEY,
        ),
        'copy' => array(
            'dates' => $text('28–30 AUGUST', '28–30 AUGUST'),
            'location' => $text('PĂDUREA VERDE, TIMIȘOARA', 'PĂDUREA VERDE, TIMIȘOARA'),
            'eyebrow' => $text('Te simți norocos?', 'Feeling lucky?'),
            'titleBefore' => $text('Învârte ', 'Spin the '),
            'titleAccent' => $text('Roata CODRU', 'CODRU Wheel'),
            'lede' => $text('Câștigă până la 50% reducere la biletul CODRU Festival.', 'Win up to 50% off your CODRU Festival ticket.'),
            'spinLabel' => $text('SPIN & WIN →', 'SPIN & WIN →'),
            'spinningLabel' => $text('SE ÎNVÂRTE…', 'SPINNING…'),
            'underWheel' => $text('O ÎNVÂRTIRE. O REDUCERE. NU O IROSI.', "ONE SPIN. ONE DISCOUNT. DON'T WASTE IT."),
            'resultBadge' => $text('AI CÂȘTIGAT', 'YOU GOT'),
            'codeLabel' => $text('CODUL TĂU DE REDUCERE CODRU', 'YOUR CODRU DISCOUNT CODE'),
            'copyLabel' => $text('COPIAZĂ CODUL', 'COPY CODE'),
            'copiedLabel' => $text('COPIAT ✓', 'COPIED ✓'),
            'copyFailedLabel' => $text('COPIERE EȘUATĂ', 'COPY FAILED'),
            'ctaLabel' => $text('IA-ȚI BILETUL', 'GET YOUR TICKET'),
            'validity' => $text('Introdu-l în câmpul de voucher la checkout.', 'Enter it in the voucher field at checkout.'),
            'closeLabel' => $text('Închide', 'Close'),
            'errorLabel' => $text('A apărut o eroare. Încearcă din nou.', 'Something went wrong. Try again.'),
            'offLabel' => $text('OFF', 'OFF'),
            'dialogLabel' => $text('Roata CODRU', 'CODRU Wheel'),
        ),
    );
}

function codrufestival_render_spin_wheel_popup(): void
{
    if (!codrufestival_spin_wheel_should_render()) {
        return;
    }

    codrufestival_react_island('SpinWheelPopup', codrufestival_get_spin_wheel_props(), array(
        'class' => 'codru-island codru-spin-wheel-root',
    ));
}

add_action('wp_footer', 'codrufestival_render_spin_wheel_popup', 20);
