<?php

require get_template_directory() . '/includes/widgets/activities.php';
require get_template_directory() . '/includes/widgets/schedule.php';
require get_template_directory() . '/includes/widgets/partners.php';

function codrufestival_theme_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('menus');
    add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script'));

    register_nav_menus(array(
        'codru_2023_left_menu' => __('Codru 2023 Left Menu', 'codrufestival'),
        'codru_2023_right_menu' => __('Codru 2023 Right Menu', 'codrufestival'),
        'codru_2023_mobile_menu' => __('Codru 2023 Mobile Menu', 'codrufestival'),
    ));
}

add_action('after_setup_theme', 'codrufestival_theme_setup');

function my_theme_enqueue_styles()
{
    wp_enqueue_style('codrufestival-style', get_template_directory_uri() . '/style.css', array(), '162');
    wp_enqueue_style('activities', get_template_directory_uri() . '/assets/css/activities.css');
    wp_enqueue_style('partners', get_template_directory_uri() . '/assets/css/partners.css');
    wp_enqueue_style('magnificPopupCss', get_template_directory_uri() . '/assets/css/magnific-popup.min.css');
    wp_enqueue_style('owlCarouselCss', get_template_directory_uri() . '/css/owl.carousel.min.css');
}

add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');

function codrufestival_enqueue_scripts()
{
    wp_register_script('main', get_template_directory_uri() . '/js/main.js', array('jquery'), '4123315', true);
    wp_enqueue_script('main');;
    wp_register_script('freewall', get_template_directory_uri() . '/js/freewall.min.js', array('jquery'), true);
    wp_enqueue_script('freewall');
    wp_register_script('imagesloaded', get_template_directory_uri() . '/js/imagesloaded.pkgd.min.js', array('jquery'), true);
    wp_enqueue_script('imagesloaded');
    wp_register_script('isotope', get_template_directory_uri() . '/js/isotope.pkgd.min.js', array('jquery'), true);
    wp_enqueue_script('isotope');
    wp_register_script('magnificPopup', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', array('jquery'), true);
    wp_enqueue_script('magnificPopup');

    $react_islands_asset = get_template_directory() . '/assets/react-islands/dist/react-islands.js';
    if (file_exists($react_islands_asset)) {
        wp_enqueue_script(
            'codrufestival-react-islands',
            get_template_directory_uri() . '/assets/react-islands/dist/react-islands.js',
            array(),
            filemtime($react_islands_asset),
            true
        );
    }
}

add_action('wp_enqueue_scripts', 'codrufestival_enqueue_scripts');

function codrufestival_enqueue_react_island_styles()
{
    $react_islands_styles = get_template_directory() . '/assets/react-islands/dist/react-islands.css';
    if (file_exists($react_islands_styles)) {
        wp_enqueue_style(
            'codrufestival-react-islands',
            get_template_directory_uri() . '/assets/react-islands/dist/react-islands.css',
            array(),
            filemtime($react_islands_styles)
        );
    }
}

add_action('wp_enqueue_scripts', 'codrufestival_enqueue_react_island_styles');

function codrufestival_react_island($component, $props = array(), $attributes = array())
{
    $attributes = wp_parse_args($attributes, array(
        'class' => '',
    ));

    $attributes['data-react-island'] = $component;
    $attributes['data-props'] = wp_json_encode($props, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

    echo '<div';
    foreach ($attributes as $name => $value) {
        if ($value === null || $value === false) {
            continue;
        }

        echo ' ' . esc_attr($name) . '="' . esc_attr($value) . '"';
    }
    echo '></div>';
}

// Enqueue admin CSS for ACF fields
function enqueue_admin_styles($hook) {
    // Only load on admin pages
    if (is_admin()) {
        wp_enqueue_style('admin-acf-styles', get_template_directory_uri() . '/assets/css/admin.css', array(), '1.0.0');
    }
}
add_action('admin_enqueue_scripts', 'enqueue_admin_styles');

function codrufestival_disable_comments_post_types()
{
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
        }

        if (post_type_supports($post_type, 'trackbacks')) {
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}
add_action('init', 'codrufestival_disable_comments_post_types');

add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);
add_filter('comments_array', '__return_empty_array', 10, 2);

function codrufestival_disable_comments_admin_menu()
{
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'codrufestival_disable_comments_admin_menu');

function codrufestival_disable_comments_admin_redirect()
{
    global $pagenow;

    if ($pagenow === 'edit-comments.php') {
        wp_safe_redirect(admin_url());
        exit;
    }

    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('admin_init', 'codrufestival_disable_comments_admin_redirect');

function codrufestival_disable_comments_admin_bar($wp_admin_bar)
{
    $wp_admin_bar->remove_node('comments');
}
add_action('admin_bar_menu', 'codrufestival_disable_comments_admin_bar', 999);

function codrufestival_disable_comments_columns($columns)
{
    unset($columns['comments']);
    return $columns;
}
add_filter('manage_posts_columns', 'codrufestival_disable_comments_columns', 20);
add_filter('manage_pages_columns', 'codrufestival_disable_comments_columns', 20);

function codrufestival_block_rest_comments($prepared_comment, $request)
{
    return new WP_Error(
        'comments_disabled',
        __('Comments are disabled on this site.', 'codrufestival'),
        array('status' => 403)
    );
}
add_filter('rest_pre_insert_comment', 'codrufestival_block_rest_comments', 10, 2);

function codrufestival_disable_comments_rest_endpoints($endpoints)
{
    unset($endpoints['/wp/v2/comments']);
    unset($endpoints['/wp/v2/comments/(?P<id>[\d]+)']);
    return $endpoints;
}
add_filter('rest_endpoints', 'codrufestival_disable_comments_rest_endpoints');

function load_footer_scripts()
{
    wp_register_script('util', get_template_directory_uri() . '/js/util.js', array('jquery'), true);
    wp_enqueue_script('util');
    wp_register_script('program', get_template_directory_uri() . '/js/program.js', array('jquery'), true);
    wp_enqueue_script('program');
}

add_action('wp_footer', 'load_footer_scripts');


function get_menu_with_children($menu_name)
{
    $navbar_items = wp_get_nav_menu_items($menu_name);
    $menu_with_children = array();
    $child_items = [];

    // pull all child menu items into separate object
    foreach ((array)$navbar_items as $key => $item) {
        if ($item->menu_item_parent) {
            array_push($child_items, $item);
            unset($navbar_items[$key]);
        }
    }

    // push child items into their parent item in the original object
    foreach ((array)$navbar_items as $item) {
        foreach ($child_items as $key => $child) {
            if ($child->menu_item_parent == $item->ID) {
                if (!$item->child_items) {
                    $item->child_items = [];
                }

                array_push($item->child_items, $child);
                unset($child_items[$key]);
            }
        }
    }

    return $navbar_items;
}

function codrufestival_get_social_links()
{
    static $social_links = null;

    if ($social_links !== null) {
        return $social_links;
    }

    $social_links = array();
    $social_links_file = get_template_directory() . '/data/social-links.json';

    if (!file_exists($social_links_file)) {
        return $social_links;
    }

    $decoded_social_links = json_decode(file_get_contents($social_links_file), true);

    if (!is_array($decoded_social_links)) {
        return $social_links;
    }

    foreach ($decoded_social_links as $social_link) {
        if (empty($social_link['label']) || empty($social_link['url']) || empty($social_link['icon'])) {
            continue;
        }

        $icon = $social_link['icon'];
        $icon_url = preg_match('/^https?:\/\//', $icon)
            ? $icon
            : get_stylesheet_directory_uri() . '/' . ltrim($icon, '/');

        $social_links[] = array(
            'label' => $social_link['label'],
            'url' => $social_link['url'],
            'icon_url' => $icon_url,
            'alt' => $social_link['alt'] ?? $social_link['label'],
        );
    }

    return $social_links;
}

function codrufestival_render_social_links($args = array())
{
    $args = wp_parse_args($args, array(
        'id' => '',
        'class' => 'footerSocials',
        'show_labels' => false,
    ));

    $social_links = codrufestival_get_social_links();

    if (empty($social_links)) {
        return;
    }

    $id_attribute = $args['id'] ? ' id="' . esc_attr($args['id']) . '"' : '';
    echo '<div' . $id_attribute . ' class="' . esc_attr($args['class']) . '">';

    foreach ($social_links as $social_link) {
        echo '<a href="' . esc_url($social_link['url']) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr($social_link['label']) . '">';
        echo '<img src="' . esc_url($social_link['icon_url']) . '" alt="' . esc_attr($social_link['alt']) . '">';

        if ($args['show_labels']) {
            echo '<span>' . esc_html($social_link['label']) . '</span>';
        }

        echo '</a>';
    }

    echo '</div>';
}

if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title' => 'Options',
        'menu_title' => 'Options',
        'menu_slug' => 'general-options',
        'capability' => 'edit_posts',
        'redirect' => false
    ));

    acf_add_options_sub_page(array(
        'page_title' => 'Partners',
        'menu_title' => 'Partners',
        'menu_slug' => 'partners-options',
        'capability' => 'edit_posts',
        'redirect' => false,
        'parent_slug' => 'general-options',
    ));

    acf_add_options_sub_page(array(
        'page_title' => 'Stages',
        'menu_title' => 'Stages',
        'menu_slug' => 'stages-options',
        'capability' => 'edit_posts',
        'redirect' => false,
        'parent_slug' => 'general-options',
    ));

    acf_add_options_sub_page(array(
        'page_title' => 'Ticket Cards',
        'menu_title' => 'Ticket Cards',
        'menu_slug' => 'ticket-options',
        'capability' => 'edit_posts',
        'redirect' => false,
        'parent_slug' => 'general-options',
    ));

    acf_add_options_sub_page(array(
        'page_title' => 'Countdown',
        'menu_title' => 'Countdown',
        'menu_slug' => 'countdown-options',
        'capability' => 'edit_posts',
        'redirect' => false,
        'parent_slug' => 'general-options',
    ));

    acf_add_options_sub_page(array(
        'page_title' => 'FAQ',
        'menu_title' => 'FAQ',
        'menu_slug' => 'faq-options',
        'capability' => 'edit_posts',
        'redirect' => false,
        'parent_slug' => 'general-options',
    ));

}

add_filter('the_content', 'wpautop');

function add_custom_rewrite_rule()
{
    add_rewrite_rule(
        '^informatii/([^/]*)/?',
        'index.php?pagename=informatii&filter=$matches[1]',
        'top'
    );
}

add_action('init', 'add_custom_rewrite_rule');

function register_query_vars($vars)
{
    $vars[] = 'filter';
    return $vars;
}

add_filter('query_vars', 'register_query_vars');

function codrufestival_unregister_artist_post_type() {
    if (post_type_exists('artist')) {
        unregister_post_type('artist');
    }
}
add_action('init', 'codrufestival_unregister_artist_post_type', 100);

function codrufestival_disable_artist_rest_endpoints($endpoints) {
    foreach (array_keys($endpoints) as $route) {
        if (preg_match('#^/wp/v2/artists?(?:$|[/(])#', $route)) {
            unset($endpoints[$route]);
        }
    }

    return $endpoints;
}
add_filter('rest_endpoints', 'codrufestival_disable_artist_rest_endpoints');

function codrufestival_get_artist_level_labels() {
    return array(
        'level1' => 'Headliners',
        'level2' => 'Main Acts',
        'level3' => 'Supporting Acts',
        'level4' => 'Level 4',
        'level5' => 'Level 5',
        'level6' => 'Level 6',
    );
}

function codrufestival_normalize_artist_level_key($level_key) {
    $level_key = sanitize_key((string) $level_key);

    if (preg_match('/^level-?([0-9]+)$/', $level_key, $matches)) {
        return 'level' . $matches[1];
    }

    return $level_key;
}

function codrufestival_get_artists_from_json() {
    static $artists = null;

    if ($artists !== null) {
        return $artists;
    }

    $artists = array();
    $artists_json_path = get_stylesheet_directory() . '/data/artists.json';

    if (!file_exists($artists_json_path)) {
        return $artists;
    }

    $artists_json = file_get_contents($artists_json_path);
    $artists_payload = json_decode($artists_json, true);

    if (json_last_error() !== JSON_ERROR_NONE || empty($artists_payload['artists']) || !is_array($artists_payload['artists'])) {
        return $artists;
    }

    $artists = $artists_payload['artists'];
    return $artists;
}

function codrufestival_get_artists_grouped_by_level() {
    $artists_by_level = array_fill_keys(array_keys(codrufestival_get_artist_level_labels()), array());

    foreach (codrufestival_get_artists_from_json() as $artist) {
        if (empty($artist['name'])) {
            continue;
        }

        $level_key = codrufestival_normalize_artist_level_key($artist['level'] ?? 'level3');
        if (!array_key_exists($level_key, $artists_by_level)) {
            $level_key = 'level3';
        }

        $artists_by_level[$level_key][] = $artist;
    }

    return $artists_by_level;
}

function codrufestival_resolve_artist_image_url($artist) {
    $image = trim((string) (!empty($artist['image_override']) ? $artist['image_override'] : ($artist['image'] ?? '')));

    if ($image === '') {
        return '';
    }

    if (preg_match('#^(?:https?:)?//#i', $image) || strpos($image, '/') === 0 || strpos($image, 'data:') === 0) {
        return $image;
    }

    return trailingslashit(get_stylesheet_directory_uri()) . ltrim($image, '/');
}

function codrufestival_build_artist_card_from_json($artist) {
    if (empty($artist['name'])) {
        return null;
    }

    $artist_levels = codrufestival_get_artist_level_labels();
    $level_key = codrufestival_normalize_artist_level_key($artist['level'] ?? 'level3');
    if (!isset($artist_levels[$level_key])) {
        $level_key = 'level3';
    }

    $artist_image = codrufestival_resolve_artist_image_url($artist);
    $spotify_id = $artist['spotify_id'] ?? '';
    $spotify_url = !empty($artist['spotify_url']) ? $artist['spotify_url'] : ($spotify_id ? "https://open.spotify.com/artist/{$spotify_id}" : '');
    $spotify_embed_url = !empty($artist['spotify_embed_url']) ? $artist['spotify_embed_url'] : ($spotify_id ? "https://open.spotify.com/embed/artist/{$spotify_id}?utm_source=generator" : '');
    $genres = isset($artist['genres']) && is_array($artist['genres']) ? $artist['genres'] : array();
    $socials = isset($artist['socials']) && is_array($artist['socials']) ? $artist['socials'] : array();

    if ($spotify_url && empty($socials['spotify'])) {
        $socials['spotify'] = $spotify_url;
    }

    return array(
        'id' => $artist['id'] ?? sanitize_title($artist['name']),
        'title' => $artist['name'],
        'image' => $artist_image,
        'level' => $artist_levels[$level_key] ?? '',
        'details' => $artist['description'] ?? $artist['details'] ?? (!empty($genres) ? implode(', ', $genres) : ''),
        'link' => $spotify_url,
        'spotifyUrl' => $spotify_url,
        'spotifyEmbedUrl' => $spotify_embed_url,
        'socials' => $socials,
        'genres' => $genres,
        'followers' => $artist['followers'] ?? null,
        'popularity' => $artist['popularity'] ?? null,
    );
}

function display_artists_by_level($level_key, $exclude_post_id = null, $language_category = '', $special_category = '') {
    $level_key = codrufestival_normalize_artist_level_key($level_key);
    $artists_by_level = codrufestival_get_artists_grouped_by_level();
    $artists = $artists_by_level[$level_key] ?? array();
    $allowed_name_html = array(
        'br' => array(),
        'small' => array(),
    );

    foreach ($artists as $key => $artist) {
        $artist_name = wp_kses($artist['name'], $allowed_name_html);
        echo "<div class='artists-name'><h4 class='m-0 pb-0' style='color: var(--artist-level-color-secondary);'>{$artist_name}</h4></div>";

        if ($key !== array_key_last($artists)) {
            echo "<div class='artists-bullet'><span style='margin-left: 5px; margin-right: 5px;'>&bull;</span></div>";
        }
    }
}


function get_language_shortcode() {
    return apply_filters( 'wpml_current_language', null );
}
add_shortcode( 'language', 'get_language_shortcode' );

add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/homepage', [
        'methods'  => 'GET',
        'callback' => function () {
            $front_page_id = get_option('page_on_front');
            return get_post($front_page_id);
        },
    ]);
});

add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/options', [
        'methods'  => 'GET',
        'callback' => 'get_filtered_acf_options',
    ]);
});

function get_filtered_acf_options( $request ) {
    // Retrieve all options set in ACF from the options page.
    $options = get_fields('option');

    // Check for a query parameter, for example, 'group' or 'field'
    $field = $request->get_param('field');
    if ($field) {
        if ( isset($options[$field]) ) {
            return $options[$field];
        } else {
            return new WP_Error('no_field', 'The specified options field was not found.', ['status' => 404]);
        }
    }

    // If no specific field is requested, return all options.
    return $options;
}

if (!function_exists('get_current_language_code')) {
    function get_current_language_code() {
        // WPML sets this constant
        if (defined('ICL_LANGUAGE_CODE')) {
            return ICL_LANGUAGE_CODE;
        }
        // Fallback for Polylang or other plugins
        if (function_exists('pll_current_language')) {
            return pll_current_language();
        }
        // Default to 'en' if not set
        return 'en';
    }
}


/**
 * Multilingual Text Function
 * Returns the appropriate text based on current language
 * 
 * @param string $ro_text Romanian text
 * @param string $en_text English text
 * @param string $fallback_lang Fallback language if current language is not supported
 * @return string
 */
function get_multilingual_text($ro_text, $en_text, $fallback_lang = 'ro') {
    // Check if WPML is active
    if (function_exists('icl_get_current_language')) {
        $current_lang = icl_get_current_language();
    } elseif (function_exists('pll_current_language')) {
        $current_lang = call_user_func('pll_current_language');
    } else {
        // Fallback to WordPress locale
        $current_lang = get_locale();
        if (strpos($current_lang, 'en') === 0) {
            $current_lang = 'en';
        } else {
            $current_lang = 'ro';
        }
    }
    
    // Return appropriate text based on language
    switch ($current_lang) {
        case 'en':
        case 'en_US':
        case 'en_GB':
            return $en_text;
        case 'ro':
        case 'ro_RO':
        default:
            return $ro_text;
    }
}

/**
 * Echo version of get_multilingual_text
 * 
 * @param string $ro_text Romanian text
 * @param string $en_text English text
 * @param string $fallback_lang Fallback language
 */
function the_multilingual_text($ro_text, $en_text, $fallback_lang = 'ro') {
    echo get_multilingual_text($ro_text, $en_text, $fallback_lang);
}

/**
 * Get multilingual text with HTML support
 * 
 * @param string $ro_text Romanian text (can include HTML)
 * @param string $en_text English text (can include HTML)
 * @param string $fallback_lang Fallback language
 * @return string
 */
function get_multilingual_html($ro_text, $en_text, $fallback_lang = 'ro') {
    return get_multilingual_text($ro_text, $en_text, $fallback_lang);
}

/**
 * Echo version of get_multilingual_html
 * 
 * @param string $ro_text Romanian text (can include HTML)
 * @param string $en_text English text (can include HTML)
 * @param string $fallback_lang Fallback language
 */
function the_multilingual_html($ro_text, $en_text, $fallback_lang = 'ro') {
    echo get_multilingual_html($ro_text, $en_text, $fallback_lang);
}

if (defined('WP_CLI') && WP_CLI && class_exists('WP_CLI')) {
    call_user_func(array('WP_CLI', 'add_command'), 'codru cleanup-unused-acf-groups', function ($args, $assoc_args) {
        $wp_cli = function ($method) {
            $params = array_slice(func_get_args(), 1);
            call_user_func_array(array('WP_CLI', $method), $params);
        };

        $titles = array(
            'Ora artist',
            'Partner Values',
            'Post Extras',
            'Newsletter',
            'Headliners Swiper',
            'Festival Gallery',
            'Display Lineup',
            'Countdown',
            'Program',
            'Ticket Button',
        );

        $delete = isset($assoc_args['delete']);
        $groups = get_posts(array(
            'post_type' => 'acf-field-group',
            'post_status' => array('publish', 'acf-disabled', 'draft', 'private', 'pending', 'trash'),
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ));

        $matches = array();
        foreach ($groups as $group) {
            if (in_array($group->post_title, $titles, true)) {
                $matches[] = $group;
            }
        }

        $wp_cli('line', $delete ? 'DELETE MODE' : 'DRY RUN');
        $wp_cli('line', sprintf('Found %d matching ACF field groups.', count($matches)));

        if (!$matches) {
            $wp_cli('success', 'No matching ACF field groups found.');
            return;
        }

        if ($delete && !function_exists('acf_delete_field_group')) {
            $wp_cli('error', 'ACF is not loaded, so acf_delete_field_group() is unavailable.');
        }

        $deleted = 0;
        foreach ($matches as $group) {
            $message = sprintf('%d | %s | %s', $group->ID, $group->post_status, $group->post_title);

            if ($delete) {
                if (acf_delete_field_group($group->ID)) {
                    $deleted++;
                    $wp_cli('log', $message . ' | deleted');
                } else {
                    $wp_cli('warning', $message . ' | failed');
                }
                continue;
            }

            $wp_cli('line', $message);
        }

        if ($delete) {
            $wp_cli('success', sprintf('Deleted %d ACF field groups.', $deleted));
        } else {
            $wp_cli('success', 'Dry run complete. Re-run with --delete to delete these groups.');
        }
    });
}