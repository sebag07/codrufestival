<?php

require get_template_directory() . '-child/includes/widgets/activities.php';
require get_template_directory() . '-child/includes/widgets/schedule.php';
require get_template_directory() . '-child/includes/widgets/partners.php';

function my_theme_enqueue_styles()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css', array(), '150');
    wp_enqueue_style('activities', get_template_directory_uri() . '-child' . '/assets/css/activities.css');
    wp_enqueue_style('partners', get_template_directory_uri() . '-child' . '/assets/css/partners.css');
    wp_enqueue_style('magnificPopupCss', get_template_directory_uri() . '-child' . '/assets/css/magnific-popup.min.css');
    wp_enqueue_style('owlCarouselCss', get_template_directory_uri() . '-child' . '/css/owl.carousel.min.css');
}

add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');

function load_child_scripts()
{
    wp_register_script('main', get_template_directory_uri() . "-child" . '/js/main.js', array('jquery'), '4123312', true);
    wp_enqueue_script('main');;
    wp_register_script('freewall', get_template_directory_uri() . "-child" . '/js/freewall.min.js', array('jquery'), true);
    wp_enqueue_script('freewall');
    wp_register_script('imagesloaded', get_template_directory_uri() . "-child" . '/js/imagesloaded.pkgd.min.js', array('jquery'), true);
    wp_enqueue_script('imagesloaded');
    wp_register_script('isotope', get_template_directory_uri() . "-child" . '/js/isotope.pkgd.min.js', array('jquery'), true);
    wp_enqueue_script('isotope');
    wp_register_script('magnificPopup', get_template_directory_uri() . "-child" . '/js/jquery.magnific-popup.min.js', array('jquery'), true);
    wp_enqueue_script('magnificPopup');
}

add_action('wp_enqueue_scripts', 'load_child_scripts');

// Enqueue admin CSS for ACF fields
function enqueue_admin_styles($hook) {
    // Only load on admin pages
    if (is_admin()) {
        wp_enqueue_style('admin-acf-styles', get_template_directory_uri() . '-child' . '/assets/css/admin.css', array(), '1.0.0');
    }
}
add_action('admin_enqueue_scripts', 'enqueue_admin_styles');

function load_footer_scripts()
{
    wp_register_script('util', get_template_directory_uri() . "-child" . '/js/util.js', array('jquery'), true);
    wp_enqueue_script('util');
    wp_register_script('program', get_template_directory_uri() . "-child" . '/js/program.js', array('jquery'), true);
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
        'page_title' => 'Newsletter',
        'menu_title' => 'Newsletter',
        'menu_slug' => 'newsletter-options',
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

    acf_add_options_sub_page(array(
        'page_title' => 'Artists',
        'menu_title' => 'Artists',
        'menu_slug' => 'artists-options',
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

function display_artists_by_level($category_name, $exclude_post_id = null, $language_category = '', $special_category = '') {
    $args = array(
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'post_type' => 'artist',
        'post_status' => 'publish',
        'suppress_filters' => false,
    );

    if ($special_category === 'special') {
        // If "special" is passed, only include posts from the "special" category
        $args['tax_query'] = array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => 'special',
            ),
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => $category_name,
            ),
        );
    } elseif ($language_category !== '') {
        // If another language_category is passed, include it and exclude "special"
        $args['tax_query'] = array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => $category_name,
            ),
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => $language_category,
            ),
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => 'special',
                'operator' => 'NOT IN',
            ),
        );
    } else {
        // If no language_category is passed, exclude "special"
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => $category_name,
            ),
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => 'special',
                'operator' => 'NOT IN',
            ),
        );
    }

    $postslist = get_posts($args);
    foreach ($postslist as $key => $post) {
        setup_postdata($post); // Set up post data for use in the loop (important)
        $artistName = get_the_title($post->ID);
        if ($key === array_key_last($postslist)) {
            echo "<div class='artists-name'><h4 class='m-0 pb-0' style='color: var(--artist-level-color-secondary);'>$artistName </h4></div>";
        } else {
            echo "<div class='artists-name'><h4 class='m-0 pb-0' style='color: var(--artist-level-color-secondary);'>$artistName</h4></div><div class='artists-bullet'><span style='margin-left: 5px; margin-right: 5px;'>&bull;</span></div>";
        }
    }
    wp_reset_postdata(); // Reset the global post object so that the rest of the page works correctly.
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

/**
 * Admin filtering for Artists post type
 * Adds dropdown filters for ACF fields in the WordPress admin
 */

// Add filter dropdowns to the admin Artists page
function add_artist_admin_filters() {
    global $typenow;
    
    // Only show on artist post type
    if ($typenow !== 'artist') {
        return;
    }
    
    // Get current filter values
    $current_artist_level = isset($_GET['artist_level']) ? $_GET['artist_level'] : '';
    $current_stage = isset($_GET['stage']) ? $_GET['stage'] : '';
    $current_day = isset($_GET['day']) ? $_GET['day'] : '';
    
    // Artist Level filter
    $artist_levels = array(
        'level1' => 'Level 1',
        'level2' => 'Level 2', 
        'level3' => 'Level 3',
        'level4' => 'Level 4',
        'level5' => 'Level 5',
        'level6' => 'Level 6'
    );
    
    // Stage filter
    $stages = array(
        'stage1' => 'The 5th Element - Main Stage',
        'stage2' => 'AIR Stage',
        'stage3' => 'EARTH Stage', 
        'stage4' => 'WATER Stage',
        'stage5' => 'FIRE Stage'
    );
    
    // Day filter
    $days = array(
        'day1' => 'Vineri',
        'day2' => 'Sambata',
        'day3' => 'Duminica'
    );
    
    ?>
    <select name="artist_level" id="artist_level_filter">
        <option value=""><?php _e('All Artist Levels', 'divi-child'); ?></option>
        <?php foreach ($artist_levels as $value => $label) : ?>
            <option value="<?php echo esc_attr($value); ?>" <?php selected($current_artist_level, $value); ?>>
                <?php echo esc_html($label); ?>
            </option>
        <?php endforeach; ?>
    </select>
    
    <select name="stage" id="stage_filter">
        <option value=""><?php _e('All Stages', 'divi-child'); ?></option>
        <?php foreach ($stages as $value => $label) : ?>
            <option value="<?php echo esc_attr($value); ?>" <?php selected($current_stage, $value); ?>>
                <?php echo esc_html($label); ?>
            </option>
        <?php endforeach; ?>
    </select>
    
    <select name="day" id="day_filter">
        <option value=""><?php _e('All Days', 'divi-child'); ?></option>
        <?php foreach ($days as $value => $label) : ?>
            <option value="<?php echo esc_attr($value); ?>" <?php selected($current_day, $value); ?>>
                <?php echo esc_html($label); ?>
            </option>
        <?php endforeach; ?>
    </select>
    
    <style>
        .tablenav-pages .tablenav-pages-navspan {
            margin-right: 10px;
        }
        #artist_level_filter,
        #stage_filter,
        #day_filter {
            margin-right: 10px;
            min-width: 150px;
        }
        .tablenav-pages {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
    </style>
    <?php
}
add_action('restrict_manage_posts', 'add_artist_admin_filters');

// Filter the posts query based on selected filters
function filter_artists_by_acf_fields($query) {
    global $pagenow, $typenow;
    
    // Only apply on admin artist post type page
    if (!is_admin() || $pagenow !== 'edit.php' || $typenow !== 'artist') {
        return;
    }
    
    // Don't apply filters on other queries (like AJAX)
    if (!$query->is_main_query()) {
        return;
    }
    
    $meta_query = array();
    
    // Filter by artist level
    if (!empty($_GET['artist_level'])) {
        $meta_query[] = array(
            'key' => 'artist_level',
            'value' => $_GET['artist_level'],
            'compare' => '='
        );
    }
    
    // Filter by stage
    if (!empty($_GET['stage'])) {
        $meta_query[] = array(
            'key' => 'stage',
            'value' => $_GET['stage'],
            'compare' => '='
        );
    }
    
    // Filter by day (handles multiple values)
    if (!empty($_GET['day'])) {
        $meta_query[] = array(
            'key' => 'day',
            'value' => '"' . $_GET['day'] . '"',
            'compare' => 'LIKE'
        );
    }
    
    // Apply meta query if we have filters
    if (!empty($meta_query)) {
        if (count($meta_query) > 1) {
            $meta_query['relation'] = 'AND';
        }
        $query->set('meta_query', $meta_query);
    }
}
add_action('pre_get_posts', 'filter_artists_by_acf_fields');

// Add custom columns to the Artists admin table
function add_artist_admin_columns($columns) {
    $new_columns = array();
    
    // Insert new columns after title
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'title') {
            $new_columns['artist_level'] = __('Artist Level', 'divi-child');
            $new_columns['stage'] = __('Stage', 'divi-child');
            $new_columns['day'] = __('Day', 'divi-child');
            $new_columns['start_time'] = __('Start Time', 'divi-child');
        }
    }
    
    return $new_columns;
}
add_filter('manage_artist_posts_columns', 'add_artist_admin_columns');

// Populate the custom columns with data
function populate_artist_admin_columns($column, $post_id) {
    switch ($column) {
        case 'artist_level':
            $artist_level = get_field('artist_level', $post_id);
            if ($artist_level && is_array($artist_level)) {
                echo esc_html($artist_level['label']);
            } elseif ($artist_level) {
                echo esc_html($artist_level);
            } else {
                echo '<span style="color: #999;">—</span>';
            }
            break;
            
        case 'stage':
            $stage = get_field('stage', $post_id);
            if ($stage && is_array($stage)) {
                echo esc_html($stage['label']);
            } elseif ($stage) {
                echo esc_html($stage);
            } else {
                echo '<span style="color: #999;">—</span>';
            }
            break;
            
        case 'day':
            $days = get_field('day', $post_id);
            if ($days && is_array($days)) {
                $day_labels = array();
                foreach ($days as $day) {
                    if (is_array($day)) {
                        $day_labels[] = $day['label'];
                    } else {
                        $day_labels[] = $day;
                    }
                }
                echo esc_html(implode(', ', $day_labels));
            } elseif ($days) {
                echo esc_html($days);
            } else {
                echo '<span style="color: #999;">—</span>';
            }
            break;
            
        case 'start_time':
            $start_time = get_field('start_time', $post_id);
            if ($start_time) {
                echo esc_html($start_time);
            } else {
                echo '<span style="color: #999;">—</span>';
            }
            break;
    }
}
add_action('manage_artist_posts_custom_column', 'populate_artist_admin_columns', 10, 2);

// Make columns sortable
function make_artist_columns_sortable($columns) {
    $columns['artist_level'] = 'artist_level';
    $columns['stage'] = 'stage';
    $columns['start_time'] = 'start_time';
    return $columns;
}
add_filter('manage_edit-artist_sortable_columns', 'make_artist_columns_sortable');

// Handle sorting for custom columns
function handle_artist_column_sorting($query) {
    global $pagenow, $typenow;
    
    if (!is_admin() || $pagenow !== 'edit.php' || $typenow !== 'artist') {
        return;
    }
    
    if (!$query->is_main_query()) {
        return;
    }
    
    $orderby = $query->get('orderby');
    
    switch ($orderby) {
        case 'artist_level':
            $query->set('meta_key', 'artist_level');
            $query->set('orderby', 'meta_value');
            break;
            
        case 'stage':
            $query->set('meta_key', 'stage');
            $query->set('orderby', 'meta_value');
            break;
            
        case 'start_time':
            $query->set('meta_key', 'start_time');
            $query->set('orderby', 'meta_value');
            break;
    }
}
add_action('pre_get_posts', 'handle_artist_column_sorting');

// Add bulk actions for quick editing
function add_artist_bulk_actions($bulk_actions) {
    global $typenow;
    
    if ($typenow === 'artist') {
        $bulk_actions['update_artist_level'] = __('Update Artist Level', 'divi-child');
        $bulk_actions['update_stage'] = __('Update Stage', 'divi-child');
        $bulk_actions['update_day'] = __('Update Day', 'divi-child');
    }
    
    return $bulk_actions;
}
add_filter('bulk_actions-edit-artist', 'add_artist_bulk_actions');

// Handle bulk actions
function handle_artist_bulk_actions($redirect_to, $doaction, $post_ids) {
    if ($doaction !== 'update_artist_level' && $doaction !== 'update_stage' && $doaction !== 'update_day') {
        return $redirect_to;
    }
    
    $updated = 0;
    $field_name = '';
    
    switch ($doaction) {
        case 'update_artist_level':
            $field_name = 'artist_level';
            $new_value = isset($_POST['artist_level_value']) ? $_POST['artist_level_value'] : '';
            break;
            
        case 'update_stage':
            $field_name = 'stage';
            $new_value = isset($_POST['stage_value']) ? $_POST['stage_value'] : '';
            break;
            
        case 'update_day':
            $field_name = 'day';
            $new_value = isset($_POST['day_value']) ? $_POST['day_value'] : '';
            break;
    }
    
    if (!empty($new_value) && !empty($field_name)) {
        foreach ($post_ids as $post_id) {
            update_field($field_name, $new_value, $post_id);
            $updated++;
        }
    }
    
    $redirect_to = add_query_arg('bulk_artists_updated', $updated, $redirect_to);
    return $redirect_to;
}
add_filter('handle_bulk_actions-edit-artist', 'handle_artist_bulk_actions', 10, 3);

// Show bulk action messages
function show_artist_bulk_action_messages() {
    if (!empty($_REQUEST['bulk_artists_updated'])) {
        $updated_count = intval($_REQUEST['bulk_artists_updated']);
        printf(
            '<div class="updated notice is-dismissible"><p>' .
            _n(
                '%s artist updated successfully.',
                '%s artists updated successfully.',
                $updated_count,
                'divi-child'
            ) . '</p></div>',
            $updated_count
        );
    }
}
add_action('admin_notices', 'show_artist_bulk_action_messages');

// Add JavaScript for enhanced filtering
function add_artist_admin_scripts($hook) {
    global $typenow;
    
    if ($hook !== 'edit.php' || $typenow !== 'artist') {
        return;
    }
    
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Auto-submit form when filters change
        $('#artist_level_filter, #stage_filter, #day_filter').on('change', function() {
            $(this).closest('form').submit();
        });
        
        // Add clear filters button
        if ($('.tablenav-pages').length) {
            $('.tablenav-pages').append(
                '<a href="<?php echo admin_url('edit.php?post_type=artist'); ?>" class="button">' +
                '<?php _e('Clear Filters', 'divi-child'); ?>' +
                '</a>'
            );
        }
        
        // Highlight filtered rows
        function highlightFilteredRows() {
            var artistLevel = $('#artist_level_filter').val();
            var stage = $('#stage_filter').val();
            var day = $('#day_filter').val();
            
            if (artistLevel || stage || day) {
                $('.wp-list-table tbody tr').addClass('filtered-row');
            } else {
                $('.wp-list-table tbody tr').removeClass('filtered-row');
            }
        }
        
        highlightFilteredRows();
    });
    </script>
    
    <style>
    .filtered-row {
        background-color: #f9f9f9;
    }
    .filtered-row:hover {
        background-color: #f0f0f0;
    }
    .tablenav-pages .button {
        margin-left: 10px;
    }
    </style>
    <?php
}
add_action('admin_footer', 'add_artist_admin_scripts');