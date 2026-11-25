<?php

declare(strict_types=1);

const CODRUFESTIVAL_PLANTATION_POST_TYPE = 'plantare';
const CODRUFESTIVAL_PLANTATIONS_SLUG = 'plantari';
const CODRUFESTIVAL_PLANTATIONS_REWRITE_VERSION = '1';
const CODRUFESTIVAL_PLANTATIONS_SEED_FILE = '/data/plantations.json';
const CODRUFESTIVAL_PLANTATIONS_SEED_KEY_META = '_codru_seed_key';
const CODRUFESTIVAL_PLANTATIONS_SEED_FILES_META = '_codru_seed_done_files';
const CODRUFESTIVAL_PLANTATIONS_DRIVE_FILE_META = '_codru_drive_file_id';
// Google serves public Drive images resized; s2000 caps the longest side at 2000px.
const CODRUFESTIVAL_PLANTATIONS_DRIVE_IMAGE_URL = 'https://lh3.googleusercontent.com/d/%s=s2000';
const CODRUFESTIVAL_PLANTATIONS_FIELD_KEYS = array(
    'plantation_date' => 'field_codru_plantation_date',
    'plantation_period' => 'field_codru_plantation_period',
    'plantation_location' => 'field_codru_plantation_location',
    'plantation_headline' => 'field_codru_plantation_headline',
    'plantation_trees' => 'field_codru_plantation_trees',
    'plantation_participants' => 'field_codru_plantation_participants',
    'plantation_signup_url' => 'field_codru_plantation_signup_url',
    'plantation_photos' => 'field_codru_plantation_photos',
);

function codrufestival_plantations_register_content_type(): void
{
    register_post_type(CODRUFESTIVAL_PLANTATION_POST_TYPE, array(
        'labels' => array(
            'name' => 'Plantări',
            'singular_name' => 'Plantare',
            'add_new' => 'Adaugă plantare',
            'add_new_item' => 'Adaugă plantare',
            'edit_item' => 'Editează plantarea',
            'new_item' => 'Plantare nouă',
            'view_item' => 'Vezi plantarea',
            'view_items' => 'Vezi plantările',
            'search_items' => 'Caută plantări',
            'not_found' => 'Nicio plantare găsită',
            'all_items' => 'Toate plantările',
            'featured_image' => 'Thumbnail',
            'set_featured_image' => 'Alege thumbnail',
        ),
        'public' => true,
        'has_archive' => CODRUFESTIVAL_PLANTATIONS_SLUG,
        'menu_icon' => 'dashicons-palmtree',
        'menu_position' => 24,
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
        'rewrite' => array(
            'slug' => CODRUFESTIVAL_PLANTATIONS_SLUG,
            'with_front' => false,
        ),
    ));
}

add_action('init', 'codrufestival_plantations_register_content_type');

/**
 * Deploys only pull files, so rewrite rules for the plantation URLs must refresh themselves.
 */
function codrufestival_plantations_refresh_rewrite_rules(): void
{
    if (get_option('codrufestival_plantations_rewrite_version') === CODRUFESTIVAL_PLANTATIONS_REWRITE_VERSION) {
        return;
    }

    flush_rewrite_rules(false);
    update_option('codrufestival_plantations_rewrite_version', CODRUFESTIVAL_PLANTATIONS_REWRITE_VERSION);
}

add_action('init', 'codrufestival_plantations_refresh_rewrite_rules', 20);

/**
 * The archive template renders every edition itself, so the main query only needs to resolve.
 */
function codrufestival_plantations_archive_query(WP_Query $query): void
{
    if (!is_admin() && $query->is_main_query() && $query->is_post_type_archive(CODRUFESTIVAL_PLANTATION_POST_TYPE)) {
        $query->set('posts_per_page', 1);
        $query->set('no_found_rows', true);
    }
}

add_action('pre_get_posts', 'codrufestival_plantations_archive_query');

function codrufestival_plantations_archive_url(): string
{
    $url = get_post_type_archive_link(CODRUFESTIVAL_PLANTATION_POST_TYPE);

    return $url ? (string) $url : home_url('/' . CODRUFESTIVAL_PLANTATIONS_SLUG . '/');
}

function codrufestival_plantation_meta(int $post_id, string $name)
{
    return get_post_meta($post_id, $name, true);
}

function codrufestival_plantations_format_number(int $number): string
{
    return number_format($number, 0, ',', '.');
}

function codrufestival_plantations_period_from_date(string $ymd): string
{
    $date = DateTimeImmutable::createFromFormat('Ymd', $ymd, wp_timezone());

    if (!$date) {
        return '';
    }

    $label = wp_date('F Y', $date->getTimestamp());

    return $label ? mb_strtoupper(mb_substr($label, 0, 1)) . mb_substr($label, 1) : '';
}

/**
 * @return list<array{src:string,full:string,alt:string,id:int}>
 */
function codrufestival_plantation_photos(int $post_id): array
{
    $ids = codrufestival_plantation_meta($post_id, 'plantation_photos');
    $photos = array();

    if (!is_array($ids)) {
        return $photos;
    }

    foreach ($ids as $id) {
        $id = (int) $id;
        $full = wp_get_attachment_image_url($id, 'full');

        if (!$full) {
            continue;
        }

        $alt = trim((string) get_post_meta($id, '_wp_attachment_image_alt', true));
        $photos[] = array(
            'id' => $id,
            'src' => (string) (wp_get_attachment_image_url($id, 'large') ?: $full),
            'full' => (string) $full,
            'alt' => $alt !== '' ? $alt : get_the_title($post_id),
        );
    }

    return $photos;
}

/**
 * The featured image is the thumbnail; without one, the first gallery photo stands in.
 */
function codrufestival_plantation_thumbnail_id(int $post_id): int
{
    $thumbnail_id = (int) get_post_thumbnail_id($post_id);

    if ($thumbnail_id) {
        return $thumbnail_id;
    }

    $ids = codrufestival_plantation_meta($post_id, 'plantation_photos');

    return is_array($ids) && $ids ? (int) reset($ids) : 0;
}

/**
 * Stands in for the thumbnail of an edition that has no photos yet: the CODRU logo on the hero artwork.
 */
function codrufestival_plantation_placeholder_image(): string
{
    return get_template_directory_uri() . '/images/plantari/placeholder.jpg';
}

/**
 * @return array<string,mixed>
 */
function codrufestival_plantation_data(int $post_id): array
{
    $date = (string) codrufestival_plantation_meta($post_id, 'plantation_date');
    $period = trim((string) codrufestival_plantation_meta($post_id, 'plantation_period'));
    $trees = (int) codrufestival_plantation_meta($post_id, 'plantation_trees');
    $participants = (int) codrufestival_plantation_meta($post_id, 'plantation_participants');
    $thumbnail_id = codrufestival_plantation_thumbnail_id($post_id);
    $image = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'large') : '';

    return array(
        'id' => $post_id,
        'title' => get_the_title($post_id),
        'url' => (string) get_permalink($post_id),
        'date' => $date,
        'period' => $period !== '' ? $period : codrufestival_plantations_period_from_date($date),
        'location' => trim((string) codrufestival_plantation_meta($post_id, 'plantation_location')),
        'headline' => trim((string) codrufestival_plantation_meta($post_id, 'plantation_headline')),
        'excerpt' => (string) get_the_excerpt($post_id),
        'trees' => $trees,
        'participants' => $participants,
        'signup_url' => (string) codrufestival_plantation_meta($post_id, 'plantation_signup_url'),
        'is_upcoming' => $date !== '' && $date >= current_time('Ymd'),
        'image' => $image ? (string) $image : codrufestival_plantation_placeholder_image(),
        'image_alt' => get_the_title($post_id),
    );
}

/**
 * Upcoming sessions come soonest first; past editions newest first.
 *
 * @param 'past'|'upcoming'|'all' $when
 * @return list<array<string,mixed>>
 */
function codrufestival_plantations(string $when = 'all', int $limit = -1, array $exclude = array()): array
{
    $args = array(
        'post_type' => CODRUFESTIVAL_PLANTATION_POST_TYPE,
        'post_status' => 'publish',
        'numberposts' => $limit,
        'post__not_in' => $exclude,
        'meta_key' => 'plantation_date',
        'orderby' => 'meta_value_num',
        'order' => $when === 'upcoming' ? 'ASC' : 'DESC',
        'suppress_filters' => false,
    );

    if ($when !== 'all') {
        $args['meta_query'] = array(array(
            'key' => 'plantation_date',
            'value' => current_time('Ymd'),
            'compare' => $when === 'upcoming' ? '>=' : '<',
            'type' => 'NUMERIC',
        ));
    }

    return array_map('codrufestival_plantation_data', wp_list_pluck(get_posts($args), 'ID'));
}

/**
 * Planting sessions that have already taken place, for the "sesiuni de plantare" figure.
 */
function codrufestival_plantations_count_past(): int
{
    return count(get_posts(array(
        'post_type' => CODRUFESTIVAL_PLANTATION_POST_TYPE,
        'post_status' => 'publish',
        'numberposts' => -1,
        'fields' => 'ids',
        'meta_query' => array(array(
            'key' => 'plantation_date',
            'value' => current_time('Ymd'),
            'compare' => '<',
            'type' => 'NUMERIC',
        )),
        'suppress_filters' => false,
    )));
}

/**
 * @return list<string>
 */
function codrufestival_plantation_facts(array $plantation): array
{
    $facts = array();

    if ($plantation['trees'] > 0) {
        $facts[] = sprintf(
            get_multilingual_text('%s arbori plantați', '%s trees planted'),
            codrufestival_plantations_format_number($plantation['trees'])
        );
    }

    if ($plantation['participants'] > 0) {
        $facts[] = sprintf(
            get_multilingual_text('peste %s participanți', 'over %s participants'),
            codrufestival_plantations_format_number($plantation['participants'])
        );
    }

    return $facts;
}

/* -------------------------------------------------------------------------
 * Initial content import
 *
 * Production only receives files on deploy, so the first editions ship as
 * data/plantations.json. An admin screen creates the posts and copies the
 * photos from the public Drive folders into the media library. It only adds
 * what is missing, so it is safe to run again and never overwrites edits.
 * ---------------------------------------------------------------------- */

/**
 * @return list<array<string,mixed>>
 */
function codrufestival_plantations_seed(): array
{
    $path = get_template_directory() . CODRUFESTIVAL_PLANTATIONS_SEED_FILE;
    $data = file_exists($path) ? json_decode((string) file_get_contents($path), true) : null;

    return is_array($data) && isset($data['editions']) && is_array($data['editions']) ? $data['editions'] : array();
}

function codrufestival_plantations_seeded_post_id(string $key): int
{
    $ids = get_posts(array(
        'post_type' => CODRUFESTIVAL_PLANTATION_POST_TYPE,
        'post_status' => 'any',
        'numberposts' => 1,
        'fields' => 'ids',
        'meta_key' => CODRUFESTIVAL_PLANTATIONS_SEED_KEY_META,
        'meta_value' => $key,
        'suppress_filters' => true,
    ));

    return $ids ? (int) $ids[0] : 0;
}

/**
 * Drive file IDs already handled for a seeded post, so photos removed later in the admin stay removed.
 *
 * @return list<string>
 */
function codrufestival_plantations_done_files(int $post_id): array
{
    $files = get_post_meta($post_id, CODRUFESTIVAL_PLANTATIONS_SEED_FILES_META, true);

    return is_array($files) ? array_values(array_map('strval', $files)) : array();
}

/**
 * Stores a value the way ACF does, so the admin fields show it even when ACF is inactive.
 */
function codrufestival_plantations_set_field(int $post_id, string $name, $value): void
{
    update_post_meta($post_id, $name, $value);
    update_post_meta($post_id, '_' . $name, CODRUFESTIVAL_PLANTATIONS_FIELD_KEYS[$name]);
}

function codrufestival_plantations_create_post(array $edition): int
{
    $post_id = wp_insert_post(array(
        'post_type' => CODRUFESTIVAL_PLANTATION_POST_TYPE,
        'post_status' => 'publish',
        'post_title' => (string) $edition['title'],
        'post_name' => (string) $edition['slug'],
        'post_excerpt' => (string) $edition['excerpt'],
        'post_content' => (string) $edition['content'],
    ), true);

    if (is_wp_error($post_id)) {
        return 0;
    }

    update_post_meta($post_id, CODRUFESTIVAL_PLANTATIONS_SEED_KEY_META, (string) $edition['key']);
    codrufestival_plantations_set_field($post_id, 'plantation_date', (string) $edition['date']);
    codrufestival_plantations_set_field($post_id, 'plantation_period', (string) $edition['period']);
    codrufestival_plantations_set_field($post_id, 'plantation_location', (string) $edition['location']);
    codrufestival_plantations_set_field($post_id, 'plantation_headline', (string) $edition['headline']);
    codrufestival_plantations_set_field($post_id, 'plantation_trees', $edition['trees'] ? (string) $edition['trees'] : '');
    codrufestival_plantations_set_field($post_id, 'plantation_participants', $edition['participants'] ? (string) $edition['participants'] : '');
    codrufestival_plantations_set_field($post_id, 'plantation_signup_url', '');
    codrufestival_plantations_set_field($post_id, 'plantation_photos', array());

    return (int) $post_id;
}

function codrufestival_plantations_attachment_for_drive_file(string $file_id): int
{
    $ids = get_posts(array(
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'numberposts' => 1,
        'fields' => 'ids',
        'meta_key' => CODRUFESTIVAL_PLANTATIONS_DRIVE_FILE_META,
        'meta_value' => $file_id,
        'suppress_filters' => true,
    ));

    return $ids ? (int) $ids[0] : 0;
}

/**
 * @return int|WP_Error
 */
function codrufestival_plantations_sideload_drive_photo(string $file_id, int $post_id, string $filename, string $alt)
{
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $tmp = download_url(sprintf(CODRUFESTIVAL_PLANTATIONS_DRIVE_IMAGE_URL, rawurlencode($file_id)), 90);

    if (is_wp_error($tmp)) {
        return $tmp;
    }

    $attachment_id = media_handle_sideload(array('name' => $filename, 'tmp_name' => $tmp), $post_id, $alt);

    if (is_wp_error($attachment_id)) {
        wp_delete_file($tmp);
        return $attachment_id;
    }

    update_post_meta($attachment_id, CODRUFESTIVAL_PLANTATIONS_DRIVE_FILE_META, $file_id);
    update_post_meta($attachment_id, '_wp_attachment_image_alt', $alt);

    return (int) $attachment_id;
}

/**
 * Creates every missing post, then copies photos until the time budget runs out.
 *
 * @return array{done:bool,created:int,imported:int,remaining:int,errors:list<string>}
 */
function codrufestival_plantations_import_step(float $budget_seconds): array
{
    $started = microtime(true);
    $result = array('done' => false, 'created' => 0, 'imported' => 0, 'remaining' => 0, 'errors' => array());
    $queue = array();

    foreach (codrufestival_plantations_seed() as $edition) {
        $post_id = codrufestival_plantations_seeded_post_id((string) $edition['key']);

        if (!$post_id) {
            $post_id = codrufestival_plantations_create_post($edition);

            if (!$post_id) {
                $result['errors'][] = sprintf('Nu am putut crea „%s”.', $edition['title']);
                continue;
            }

            $result['created']++;
        }

        $done_files = codrufestival_plantations_done_files($post_id);

        foreach (array_values((array) $edition['photos']) as $index => $file_id) {
            if (!in_array($file_id, $done_files, true)) {
                $queue[] = array($post_id, $edition, (string) $file_id, $index + 1);
            }
        }
    }

    foreach ($queue as $position => [$post_id, $edition, $file_id, $number]) {
        if (microtime(true) - $started > $budget_seconds) {
            $result['remaining'] = count($queue) - $position;
            return $result;
        }

        $attachment_id = codrufestival_plantations_attachment_for_drive_file($file_id);

        if (!$attachment_id) {
            $attachment_id = codrufestival_plantations_sideload_drive_photo(
                $file_id,
                $post_id,
                sprintf('%s-%02d.jpg', $edition['slug'], $number),
                sprintf('%s – %s', $edition['title'], $edition['location'])
            );

            if (is_wp_error($attachment_id)) {
                $result['errors'][] = sprintf('%s, foto %d: %s', $edition['title'], $number, $attachment_id->get_error_message());
                continue;
            }

            $result['imported']++;
        }

        $gallery = get_post_meta($post_id, 'plantation_photos', true);
        $gallery = is_array($gallery) ? array_map('strval', $gallery) : array();
        if (!in_array((string) $attachment_id, $gallery, true)) {
            $gallery[] = (string) $attachment_id;
            codrufestival_plantations_set_field($post_id, 'plantation_photos', $gallery);
        }

        if ($file_id === (string) $edition['thumbnail'] && !has_post_thumbnail($post_id)) {
            set_post_thumbnail($post_id, $attachment_id);
        }

        $done_files = codrufestival_plantations_done_files($post_id);
        $done_files[] = $file_id;
        update_post_meta($post_id, CODRUFESTIVAL_PLANTATIONS_SEED_FILES_META, array_values(array_unique($done_files)));
    }

    $result['done'] = true;
    return $result;
}

function codrufestival_plantations_import_menu(): void
{
    add_submenu_page(
        'edit.php?post_type=' . CODRUFESTIVAL_PLANTATION_POST_TYPE,
        'Import inițial',
        'Import inițial',
        'manage_options',
        'codru-plantations-import',
        'codrufestival_plantations_import_screen'
    );
}

add_action('admin_menu', 'codrufestival_plantations_import_menu');

function codrufestival_plantations_import_screen(): void
{
    $seed = codrufestival_plantations_seed();
    ?>
    <div class="wrap">
        <h1>Import inițial – Plantări</h1>
        <p>Creează edițiile din <code>data/plantations.json</code> și copiază fotografiile din folderele publice Google Drive în Media Library.
            Adaugă doar ce lipsește: edițiile și fotografiile deja importate sau editate nu sunt modificate.</p>
        <table class="widefat striped" style="max-width:900px">
            <thead><tr><th>Ediție</th><th>Articol</th><th>Fotografii</th></tr></thead>
            <tbody>
            <?php foreach ($seed as $edition) :
                $post_id = codrufestival_plantations_seeded_post_id((string) $edition['key']);
                $done = $post_id ? count(codrufestival_plantations_done_files($post_id)) : 0;
                ?>
                <tr>
                    <td><?php echo esc_html($edition['period'] . ' · ' . $edition['title']); ?></td>
                    <td><?php echo $post_id
                        ? '<a href="' . esc_url((string) get_edit_post_link($post_id)) . '">Creat</a>'
                        : 'Lipsește'; ?></td>
                    <td><?php echo esc_html($done . ' / ' . count((array) $edition['photos'])); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <p>
            <button type="button" class="button button-primary" id="codru-plantations-import">Importă</button>
            <span id="codru-plantations-import-status" style="margin-left:12px"></span>
        </p>
        <ul id="codru-plantations-import-errors" style="color:#b32d2e"></ul>
    </div>
    <script>
    (function () {
        const button = document.getElementById('codru-plantations-import');
        const status = document.getElementById('codru-plantations-import-status');
        const errors = document.getElementById('codru-plantations-import-errors');
        const nonce = <?php echo wp_json_encode(wp_create_nonce('codru_plantations_import')); ?>;
        let created = 0;
        let imported = 0;

        const step = async () => {
            const body = new URLSearchParams({ action: 'codru_plantations_import', _ajax_nonce: nonce });
            const response = await fetch(ajaxurl, { method: 'POST', body, credentials: 'same-origin' });
            const payload = await response.json();

            if (!payload.success) {
                throw new Error(payload.data || 'Importul a eșuat.');
            }

            const data = payload.data;
            created += data.created;
            imported += data.imported;
            data.errors.forEach((message) => {
                const item = document.createElement('li');
                item.textContent = message;
                errors.appendChild(item);
            });
            status.textContent = `Ediții create: ${created} · Fotografii importate: ${imported}` + (data.done ? ' · Gata.' : ` · Mai sunt ${data.remaining}…`);

            return data.done;
        };

        button.addEventListener('click', async () => {
            button.disabled = true;
            errors.innerHTML = '';
            try {
                while (!(await step())) { /* keep going until every photo is in */ }
                window.setTimeout(() => window.location.reload(), 1200);
            } catch (error) {
                status.textContent = error.message;
                button.disabled = false;
            }
        });
    })();
    </script>
    <?php
}

function codrufestival_plantations_import_ajax(): void
{
    check_ajax_referer('codru_plantations_import');

    if (!current_user_can('manage_options')) {
        wp_send_json_error('Nu ai drepturi pentru import.', 403);
    }

    if (function_exists('set_time_limit')) {
        set_time_limit(120);
    }

    wp_send_json_success(codrufestival_plantations_import_step(20.0));
}

add_action('wp_ajax_codru_plantations_import', 'codrufestival_plantations_import_ajax');
