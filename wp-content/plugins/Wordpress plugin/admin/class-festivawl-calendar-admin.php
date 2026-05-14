<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for enqueuing
 * the admin-specific stylesheet and JavaScript.
 */
class Festivawl_Calendar_Admin {

    /**
     * The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        
        // Register AJAX handlers
        add_action('wp_ajax_festivawl_test_api', array($this, 'ajax_test_api'));
        add_action('wp_ajax_festivawl_clear_cache', array($this, 'ajax_clear_cache'));
        add_action('wp_ajax_festivawl_add_stage_color', array($this, 'ajax_add_stage_color'));
        add_action('wp_ajax_festivawl_remove_stage_color', array($this, 'ajax_remove_stage_color'));
        add_action('wp_ajax_festivawl_reset_stage_colors', array($this, 'ajax_reset_stage_colors'));
        
        // Hook dynamic CSS generation into wp_head
        add_action('wp_head', array($this, 'output_dynamic_css'));
    }

    /**
     * Register the stylesheets for the admin area.
     */
    public function enqueue_styles($hook) {
        if (strpos($hook, 'festivawl-calendar') !== false) {
            // Enqueue Pickr color picker CSS
            wp_enqueue_style(
                'pickr-css',
                'https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.8.2/dist/themes/nano.min.css',
                array(),
                '1.8.2'
            );
            
            wp_enqueue_style(
                $this->plugin_name,
                FESTIVAWL_CALENDAR_PLUGIN_URL . 'admin/css/festivawl-calendar-admin.css',
                array('pickr-css'),
                $this->version,
                'all'
            );
        }
    }

    /**
     * Register the JavaScript for the admin area.
     */
    public function enqueue_scripts($hook) {
        if (strpos($hook, 'festivawl-calendar') !== false) {
            // Enqueue Pickr color picker JS
            wp_enqueue_script(
                'pickr-js',
                'https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.8.2/dist/pickr.min.js',
                array('jquery'),
                '1.8.2',
                true
            );
            
            wp_enqueue_script(
                $this->plugin_name,
                FESTIVAWL_CALENDAR_PLUGIN_URL . 'admin/js/festivawl-calendar-admin.js',
                array('jquery', 'pickr-js'),
                $this->version,
                true  // Load in footer
            );

            wp_localize_script(
                $this->plugin_name,
                'festivawl_admin_ajax',
                array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('festivawl_admin_nonce')
                )
            );
        }
    }

    /**
     * Add admin menu.
     */
    public function add_admin_menu() {
        add_options_page(
            __('Festivawl Calendar Settings', 'festivawl-calendar'),
            __('Festivawl Calendar', 'festivawl-calendar'),
            'manage_options',
            'festivawl-calendar',
            array($this, 'admin_page')
        );
    }

    /**
     * Initialize admin settings.
     */
    public function settings_init() {
        // Register general settings
        register_setting('festivawl_calendar_settings', 'festivawl_calendar_default_festival_id');
        register_setting('festivawl_calendar_settings', 'festivawl_calendar_cache_duration');
        register_setting('festivawl_calendar_settings', 'festivawl_calendar_theme_style');
        register_setting('festivawl_calendar_settings', 'festivawl_calendar_enable_mobile_view');
        register_setting('festivawl_calendar_settings', 'festivawl_calendar_transparent_background');
        
        // Register theme settings
        register_setting('festivawl_calendar_settings', 'festivawl_calendar_use_light_theme');
        
        // Register stage colors array with validation
        register_setting('festivawl_calendar_settings', 'festivawl_calendar_stage_colors', array(
            'type' => 'array',
            'sanitize_callback' => array($this, 'sanitize_stage_colors')
        ));

        // General Settings Section
        add_settings_section(
            'festivawl_calendar_general',
            __('General Settings', 'festivawl-calendar'),
            array($this, 'settings_section_callback'),
            'festivawl_calendar_settings'
        );

        // Theme Settings Section
        add_settings_section(
            'festivawl_calendar_theme',
            __('Theme Settings', 'festivawl-calendar'),
            array($this, 'theme_section_callback'),
            'festivawl_calendar_settings'
        );

        // Stage Colors Section
        add_settings_section(
            'festivawl_calendar_stage_colors',
            __('Custom Stage Colors', 'festivawl-calendar'),
            array($this, 'stage_colors_section_callback'),
            'festivawl_calendar_settings'
        );

        // Add general settings fields
        add_settings_field(
            'default_festival_id',
            __('Default Festival ID', 'festivawl-calendar'),
            array($this, 'default_festival_id_callback'),
            'festivawl_calendar_settings',
            'festivawl_calendar_general'
        );

        add_settings_field(
            'cache_duration',
            __('Cache Duration (seconds)', 'festivawl-calendar'),
            array($this, 'cache_duration_callback'),
            'festivawl_calendar_settings',
            'festivawl_calendar_general'
        );

        add_settings_field(
            'enable_mobile_view',
            __('Enable Mobile View', 'festivawl-calendar'),
            array($this, 'enable_mobile_view_callback'),
            'festivawl_calendar_settings',
            'festivawl_calendar_general'
        );

        add_settings_field(
            'transparent_background',
            __('Transparent Background', 'festivawl-calendar'),
            array($this, 'transparent_background_callback'),
            'festivawl_calendar_settings',
            'festivawl_calendar_general'
        );

        // Add theme settings fields
        add_settings_field(
            'theme_style',
            __('Default Theme Style', 'festivawl-calendar'),
            array($this, 'theme_style_callback'),
            'festivawl_calendar_settings',
            'festivawl_calendar_theme'
        );

        add_settings_field(
            'use_light_theme',
            __('Use Light Theme', 'festivawl-calendar'),
            array($this, 'use_light_theme_callback'),
            'festivawl_calendar_settings',
            'festivawl_calendar_theme'
        );

        // Add stage colors field
        add_settings_field(
            'stage_colors',
            __('Stage Colors', 'festivawl-calendar'),
            array($this, 'stage_colors_callback'),
            'festivawl_calendar_settings',
            'festivawl_calendar_stage_colors'
        );
    }

    /**
     * Admin page callback.
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <div class="festivawl-admin-container">
                <div class="festivawl-admin-main">
                    <form action="options.php" method="post">
                        <?php
                        settings_fields('festivawl_calendar_settings');
                        do_settings_sections('festivawl_calendar_settings');
                        submit_button(__('Save Settings', 'festivawl-calendar'));
                        ?>
                    </form>

                    <div class="festivawl-shortcode-help">
                        <h3><?php _e('How to Use', 'festivawl-calendar'); ?></h3>
                        <p><?php _e('Use the following shortcode to display a festival calendar:', 'festivawl-calendar'); ?></p>
                        <code>[festivawl_calendar id="100"]</code>
                        
                        <h4><?php _e('Shortcode Parameters:', 'festivawl-calendar'); ?></h4>
                        <ul>
                            <li><strong>id</strong> - <?php _e('Festival ID (required)', 'festivawl-calendar'); ?></li>
                            <li><strong>theme</strong> - <?php _e('Theme style (default, dark, light)', 'festivawl-calendar'); ?></li>
                            <li><strong>mobile</strong> - <?php _e('Enable mobile view (true, false)', 'festivawl-calendar'); ?></li>
                            <li><strong>height</strong> - <?php _e('Calendar height (e.g., 600px)', 'festivawl-calendar'); ?></li>
                        </ul>

                        <h4><?php _e('Examples:', 'festivawl-calendar'); ?></h4>
                        <code>[festivawl_calendar id="100" theme="dark"]</code><br>
                        <code>[festivawl_calendar id="100" height="800px"]</code>
                    </div>
                </div>

                <div class="festivawl-admin-sidebar">
                    <div class="festivawl-admin-box">
                        <h3><?php _e('Cache Management', 'festivawl-calendar'); ?></h3>
                        <p><?php _e('Clear cached festival data if you need to refresh the display.', 'festivawl-calendar'); ?></p>
                        <button type="button" id="clear-cache-btn" class="button button-secondary">
                            <?php _e('Clear All Cache', 'festivawl-calendar'); ?>
                        </button>
                    </div>

                    <div class="festivawl-admin-box">
                        <h3><?php _e('Festival API', 'festivawl-calendar'); ?></h3>
                        <p><?php _e('This plugin fetches data from the Festivawl API. Make sure your festival ID is correct.', 'festivawl-calendar'); ?></p>
                        <p><strong><?php _e('API Endpoint:', 'festivawl-calendar'); ?></strong><br>
                        <code>https://api.festivawl.com/app/performance/{id}</code></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Settings section callback.
     */
    public function settings_section_callback() {
        echo '<p>' . __('Configure the default settings for your festival calendars.', 'festivawl-calendar') . '</p>';
    }

    /**
     * Theme section callback.
     */
    public function theme_section_callback() {
        echo '<p>' . __('Configure the default theme style for new calendars.', 'festivawl-calendar') . '</p>';
    }

    /**
     * Default festival ID callback.
     */
    public function default_festival_id_callback() {
        $value = get_option('festivawl_calendar_default_festival_id', '');
        echo '<input type="number" name="festivawl_calendar_default_festival_id" value="' . esc_attr($value) . '" class="regular-text" />';
        echo '<p class="description">' . __('Default festival ID to use when none is specified in the shortcode.', 'festivawl-calendar') . '</p>';
    }

    /**
     * Cache duration callback.
     */
    public function cache_duration_callback() {
        $value = get_option('festivawl_calendar_cache_duration', 3600);
        echo '<input type="number" name="festivawl_calendar_cache_duration" value="' . esc_attr($value) . '" class="regular-text" min="300" max="86400" />';
        echo '<p class="description">' . __('How long to cache festival data (300-86400 seconds). Default: 3600 (1 hour).', 'festivawl-calendar') . '</p>';
    }

    /**
     * Theme style callback.
     */
    public function theme_style_callback() {
        $value = get_option('festivawl_calendar_theme_style', 'default');
        $options = array(
            'default' => __('Default', 'festivawl-calendar'),
            'dark' => __('Dark', 'festivawl-calendar'),
            'light' => __('Light', 'festivawl-calendar')
        );

        echo '<select name="festivawl_calendar_theme_style" class="regular-text">';
        foreach ($options as $option_value => $option_label) {
            echo '<option value="' . esc_attr($option_value) . '"' . selected($value, $option_value, false) . '>' . esc_html($option_label) . '</option>';
        }
        echo '</select>';
        echo '<p class="description">' . __('Default theme style for new calendars.', 'festivawl-calendar') . '</p>';
    }

    /**
     * Enable mobile view callback.
     */
    public function enable_mobile_view_callback() {
        $value = get_option('festivawl_calendar_enable_mobile_view', true);
        echo '<input type="checkbox" name="festivawl_calendar_enable_mobile_view" value="1"' . checked(1, $value, false) . ' />';
        echo '<label for="festivawl_calendar_enable_mobile_view">' . __('Enable responsive mobile view by default', 'festivawl-calendar') . '</label>';
    }

    /**
     * Transparent background callback.
     */
    public function transparent_background_callback() {
        $value = get_option('festivawl_calendar_transparent_background', false);
        echo '<input type="checkbox" name="festivawl_calendar_transparent_background" value="1"' . checked(1, $value, false) . ' />';
        echo '<label for="festivawl_calendar_transparent_background">' . __('Use transparent background to inherit website\'s background color', 'festivawl-calendar') . '</label>';
        echo '<p class="description">' . __('When enabled, the calendar will have a transparent background and use your website\'s background color instead of the default dark theme.', 'festivawl-calendar') . '</p>';
    }

    /**
     * Use light theme callback.
     */
    public function use_light_theme_callback() {
        $value = get_option('festivawl_calendar_use_light_theme', false);
        echo '<input type="checkbox" name="festivawl_calendar_use_light_theme" value="1"' . checked(1, $value, false) . ' />';
        echo '<label for="festivawl_calendar_use_light_theme">' . __('Override default colors with light theme (white background, dark text, lighter grid lines)', 'festivawl-calendar') . '</label>';
        echo '<p class="description">' . __('When enabled, the calendar will use a light color scheme with improved visibility for light backgrounds.', 'festivawl-calendar') . '</p>';
    }

    /**
     * Stage colors section callback.
     */
    public function stage_colors_section_callback() {
        echo '<p>' . __('Customize the background and border colors for specific stages. Only add colors for stages you want to override from the defaults.', 'festivawl-calendar') . '</p>';
    }

    /**
     * Stage colors callback - renders the progressive stage color interface.
     */
    public function stage_colors_callback() {
        $stage_colors = $this->get_stage_colors_safe();
        
        // Sort stages by number to ensure proper order
        if (!empty($stage_colors)) {
            ksort($stage_colors, SORT_NUMERIC);
        }
        
        echo '<div id="festivawl-stage-colors-container">';
        
        // Show existing stages
        if (!empty($stage_colors)) {
            foreach ($stage_colors as $stage_num => $colors) {
                $this->render_progressive_stage_row($stage_num, $colors, true);
            }
        }
        
        // Show the "Add next stage" button
        $next_stage = $this->get_next_stage_number($stage_colors);
        $this->render_progressive_stage_row($next_stage, array(), false);
        
        echo '</div>';
        
        echo '<div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd;">';
        echo '<button type="button" id="reset-all-stage-colors" class="button button-secondary">';
        echo '🔄 Reset All Colors';
        echo '</button>';
        echo '<p class="description" style="margin-top: 10px;">Reset will remove all custom stage colors and restore defaults.</p>';
        echo '</div>';
    }

    /**
     * Get the next stage number to add.
     */
    private function get_next_stage_number($stage_colors) {
        if (empty($stage_colors)) {
            return 1;
        }
        
        $stage_numbers = array_keys($stage_colors);
        $max_stage = max($stage_numbers);
        return $max_stage + 1;
    }

    /**
     * Render a progressive stage row with hybrid color inputs.
     */
    private function render_progressive_stage_row($stage_num, $colors, $is_active) {
        $bg_color = isset($colors['bg']) ? $colors['bg'] : '#6B1954';
        $border_color = isset($colors['border']) ? $colors['border'] : '#ED1D1D';
        
        if ($is_active) {
            // Active stage with color controls
            echo '<div class="progressive-stage-row active" data-stage="' . esc_attr($stage_num) . '" style="background: #fff; border: 2px solid #0073aa; padding: 20px; margin-bottom: 15px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">';
            
            echo '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">';
            echo '<h4 style="margin: 0; color: #0073aa; font-size: 16px;">🎯 Stage ' . esc_html($stage_num) . ' Colors</h4>';
            echo '<button type="button" class="remove-progressive-stage" style="color: #d63638; background: #fff; border: 1px solid #d63638; border-radius: 3px; padding: 5px 10px; cursor: pointer; font-size: 12px;" title="Remove Stage ' . esc_attr($stage_num) . '">';
            echo '✕ Remove';
            echo '</button>';
            echo '</div>';
            
            echo '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">';
            
            // Background color
            echo '<div class="color-input-group" style="margin-bottom: 20px;">';
            echo '<label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Background Color</label>';
            echo '<div style="display: flex; gap: 10px; align-items: center;">';
            echo '<div class="pickr-color-trigger" data-target="bg" data-stage="' . esc_attr($stage_num) . '" ';
            echo 'style="width: 40px; height: 40px; border: 2px solid #ddd; border-radius: 6px; cursor: pointer; background: ' . esc_attr($bg_color) . ';"></div>';
            echo '<input type="text" name="festivawl_calendar_stage_colors[' . esc_attr($stage_num) . '][bg]" ';
            echo 'value="' . esc_attr($bg_color) . '" class="color-hex-input" readonly ';
            echo 'style="font-family: monospace; width: 120px; padding: 8px; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;" />';
            echo '</div>';
            echo '</div>';
            
            // Border color
            echo '<div class="color-input-group" style="margin-bottom: 20px;">';
            echo '<label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Border Color</label>';
            echo '<div style="display: flex; gap: 10px; align-items: center;">';
            echo '<div class="pickr-color-trigger" data-target="border" data-stage="' . esc_attr($stage_num) . '" ';
            echo 'style="width: 40px; height: 40px; border: 2px solid #ddd; border-radius: 6px; cursor: pointer; background: ' . esc_attr($border_color) . ';"></div>';
            echo '<input type="text" name="festivawl_calendar_stage_colors[' . esc_attr($stage_num) . '][border]" ';
            echo 'value="' . esc_attr($border_color) . '" class="color-hex-input" readonly ';
            echo 'style="font-family: monospace; width: 120px; padding: 8px; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;" />';
            echo '</div>';
            echo '</div>';
            
            echo '</div>';
            echo '</div>';
            
        } else {
            // Inactive stage - just the "Add" button
            echo '<div class="progressive-stage-row inactive" data-stage="' . esc_attr($stage_num) . '" style="background: #f9f9f9; border: 2px dashed #ccc; padding: 20px; margin-bottom: 15px; border-radius: 6px; text-align: center;">';
            echo '<button type="button" class="add-progressive-stage" data-stage="' . esc_attr($stage_num) . '" ';
            echo 'style="background: #0073aa; color: white; border: none; padding: 12px 20px; border-radius: 4px; font-size: 14px; cursor: pointer; font-weight: 500;">';
            echo '+ Add Stage ' . esc_html($stage_num) . ' Colors';
            echo '</button>';
            echo '<p style="margin: 8px 0 0 0; color: #666; font-size: 13px;">Customize colors for stage ' . esc_html($stage_num) . '</p>';
            echo '</div>';
        }
    }

    /**
     * AJAX handler for testing API connection.
     */
    public function ajax_test_api() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'festivawl_admin_nonce')) {
            wp_die(__('Security check failed.', 'festivawl-calendar'));
        }

        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions.', 'festivawl-calendar'));
        }

        $festival_id = isset($_POST['festival_id']) ? absint($_POST['festival_id']) : 0;

        if (empty($festival_id)) {
            wp_send_json_error(__('Invalid festival ID.', 'festivawl-calendar'));
        }

        // Test API connection
        $api_data = Festivawl_Calendar_API::get_festival_data($festival_id);

        if (is_wp_error($api_data)) {
            $error_message = $api_data->get_error_message();
            wp_send_json_error($error_message);
        }

        // Count events, stages, and days for response
        $event_count = 0;
        $stage_count = count($api_data['stages']);
        $day_count = count($api_data['days']);

        // Count total events across all days
        foreach ($api_data['completeEventSchedule'] as $day_events) {
            $event_count += count($day_events);
        }

        $response_data = array(
            'event_count' => $event_count,
            'stage_count' => $stage_count,
            'day_count' => $day_count,
            'festival_id' => $festival_id
        );

        wp_send_json_success($response_data);
    }

    /**
     * AJAX handler for clearing cache.
     */
    public function ajax_clear_cache() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'festivawl_admin_nonce')) {
            wp_die(__('Security check failed.', 'festivawl-calendar'));
        }

        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions.', 'festivawl-calendar'));
        }

        // Clear all festivawl calendar transients
        global $wpdb;
        
        $transients = $wpdb->get_results(
            "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '_transient_festivawl_calendar_%'"
        );

        $cleared_count = 0;
        foreach ($transients as $transient) {
            $transient_name = str_replace('_transient_', '', $transient->option_name);
            if (delete_transient($transient_name)) {
                $cleared_count++;
            }
        }

        wp_send_json_success(array(
            'message' => sprintf(
                __('Successfully cleared %d cached festival(s).', 'festivawl-calendar'),
                $cleared_count
            ),
            'cleared_count' => $cleared_count
        ));
    }

    /**
     * AJAX handler for adding a new stage color.
     */
    public function ajax_add_stage_color() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'festivawl_admin_nonce')) {
            wp_die(__('Security check failed.', 'festivawl-calendar'));
        }

        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions.', 'festivawl-calendar'));
        }

        $stage_num = isset($_POST['stage_num']) ? absint($_POST['stage_num']) : 0;
        
        if (empty($stage_num)) {
            wp_send_json_error(__('Invalid stage number.', 'festivawl-calendar'));
        }

        // Get existing stage colors
        $stage_colors = $this->get_stage_colors_safe();
        
        // Check if stage already exists
        if (isset($stage_colors[$stage_num])) {
            wp_send_json_error(__('Stage color already exists.', 'festivawl-calendar'));
        }

        // Add new stage with default colors
        $stage_colors[$stage_num] = array('bg' => '#6B1954', 'border' => '#ED1D1D');
        
        // Ensure we're saving an array
        if (is_array($stage_colors)) {
            update_option('festivawl_calendar_stage_colors', $stage_colors);
        } else {
            error_log('Festivawl Calendar: Attempted to save non-array stage_colors');
            wp_send_json_error(__('Invalid data format.', 'festivawl-calendar'));
        }

        // Return the HTML for the new row
        ob_start();
        $this->render_progressive_stage_row($stage_num, $stage_colors[$stage_num], true);
        $html = ob_get_clean();

        wp_send_json_success(array(
            'html' => $html,
            'stage_num' => $stage_num
        ));
    }

    /**
     * AJAX handler for removing a stage color.
     */
    public function ajax_remove_stage_color() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'festivawl_admin_nonce')) {
            wp_die(__('Security check failed.', 'festivawl-calendar'));
        }

        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions.', 'festivawl-calendar'));
        }

        $stage_num = isset($_POST['stage_num']) ? $_POST['stage_num'] : '';
        
        if (empty($stage_num)) {
            wp_send_json_error(__('Invalid stage number.', 'festivawl-calendar'));
        }

        // Get existing stage colors
        $stage_colors = $this->get_stage_colors_safe();
        
        // Remove the stage
        if (isset($stage_colors[$stage_num])) {
            unset($stage_colors[$stage_num]);
            
            // Ensure we're saving an array
            if (is_array($stage_colors)) {
                update_option('festivawl_calendar_stage_colors', $stage_colors);
            } else {
                error_log('Festivawl Calendar: Attempted to save non-array stage_colors after removal');
                wp_send_json_error(__('Invalid data format.', 'festivawl-calendar'));
                return;
            }
        }

        wp_send_json_success();
    }

    /**
     * AJAX handler for resetting all stage colors.
     */
    public function ajax_reset_stage_colors() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'festivawl_admin_nonce')) {
            wp_die(__('Security check failed.', 'festivawl-calendar'));
        }

        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions.', 'festivawl-calendar'));
        }

        // Reset stage colors by deleting the option
        $deleted = delete_option('festivawl_calendar_stage_colors');

        if ($deleted || !get_option('festivawl_calendar_stage_colors')) {
            wp_send_json_success(array(
                'message' => __('All stage colors have been reset successfully.', 'festivawl-calendar')
            ));
        } else {
            wp_send_json_error(__('Failed to reset stage colors.', 'festivawl-calendar'));
        }
    }

    /**
     * Output dynamic CSS based on admin settings.
     */
    public function output_dynamic_css() {
        // Check if we're on a page that might have the calendar
        if (!$this->should_output_css()) {
            return;
        }

        $css = $this->generate_dynamic_css();
        if (!empty($css)) {
            echo "<style id='festivawl-dynamic-css'>\n{$css}\n</style>\n";
        }
    }

    /**
     * Check if we should output CSS (only on pages that might have calendars).
     */
    private function should_output_css() {
        // Don't output on admin pages
        if (is_admin()) {
            return false;
        }
        
        // Output on all public pages since we can't easily detect shortcode usage
        return true;
    }

    /**
     * Generate dynamic CSS based on admin settings.
     */
    private function generate_dynamic_css() {
        $css = '';
        
        // Light theme overrides
        if (get_option('festivawl_calendar_use_light_theme', false)) {
            $css .= $this->get_light_theme_css();
        }
        
        // Stage color overrides
        $stage_css = $this->get_stage_color_overrides();
        if (!empty($stage_css)) {
            $css .= $stage_css;
        }
        
        return $css;
    }

    /**
     * Get light theme CSS overrides using CSS variables.
     */
    private function get_light_theme_css() {
        return '
/* Light Theme Overrides */
.festivawl-calendar-container {
    --festivawl-bg-primary: #ffffff;
    --festivawl-bg-secondary: #f8f9fa;
    --festivawl-bg-tertiary: #e9ecef;
    --festivawl-text-primary: #212529;
    --festivawl-text-secondary: #495057;
    --festivawl-text-muted: #6c757d;
    --festivawl-text-tertiary: #adb5bd;
    --festivawl-grid-color: #c0c0c0;
    --festivawl-border: #dee2e6;
}

/* Day Navigation Tabs */
.festivawl-day-tab {
    background: rgba(0, 0, 0, 0.05) !important;
    color: var(--festivawl-text-primary) !important;
}

.festivawl-day-tab:hover {
    background: rgba(0, 0, 0, 0.1) !important;
}

.festivawl-day-tab.active {
    background: rgba(0, 0, 0, 0.15) !important;
    color: var(--festivawl-text-primary) !important;
}

.festivawl-day-tab .day-date {
    color: var(--festivawl-text-primary) !important;
    opacity: 0.7;
}

/* Time Column - Make time labels dark */
.festivawl-time-slot .time-label {
    color: var(--festivawl-text-primary) !important;
}

/* Stage Headers - Make text dark for light theme */
.festivawl-stage-header .stage-name {
    color: var(--festivawl-text-primary) !important;
}

/* Event Blocks - Make ALL text dark for light theme */
.festivawl-event-block .event-stage {
    color: var(--festivawl-text-primary) !important;
}

.festivawl-event-block .event-time {
    color: var(--festivawl-text-primary) !important;
}

.festivawl-event-block .event-artist {
    color: var(--festivawl-text-primary) !important;
}

/* Grid Lines - Make darker for visibility on white background */
.festivawl-grid-line {
    background: var(--festivawl-grid-color) !important;
}

/* Scrollbar styling for light theme */
.festivawl-scrollable-area::-webkit-scrollbar-track {
    background: var(--festivawl-bg-secondary);
}

.festivawl-scrollable-area::-webkit-scrollbar-thumb {
    background: var(--festivawl-text-tertiary);
}

/* Day tab scrollbar */
.festivawl-day-tabs::-webkit-scrollbar-thumb {
    background: var(--festivawl-text-tertiary);
}
';
    }

    /**
     * Sanitize stage colors before saving to database.
     */
    public function sanitize_stage_colors($value) {
        // Always ensure we return an array
        if (!is_array($value)) {
            error_log('Festivawl Calendar: Sanitized non-array stage_colors value: ' . gettype($value));
            return array();
        }
        
        // Sanitize each stage color entry
        $sanitized = array();
        foreach ($value as $stage_num => $colors) {
            $stage_num = absint($stage_num);
            if ($stage_num > 0 && is_array($colors)) {
                $sanitized[$stage_num] = array(
                    'bg' => sanitize_hex_color($colors['bg'] ?? ''),
                    'border' => sanitize_hex_color($colors['border'] ?? '')
                );
            }
        }
        
        return $sanitized;
    }

    /**
     * Safely get stage colors option, ensuring it's always an array.
     */
    private function get_stage_colors_safe() {
        $stage_colors = get_option('festivawl_calendar_stage_colors', array());
        
        // Ensure we have an array - fix corrupted data
        if (!is_array($stage_colors)) {
            $stage_colors = array();
            update_option('festivawl_calendar_stage_colors', array());
            error_log('Festivawl Calendar: Fixed corrupted stage_colors option - was not an array');
        }
        
        return $stage_colors;
    }

    /**
     * Get stage color CSS overrides using CSS variables.
     */
    private function get_stage_color_overrides() {
        $stage_colors = $this->get_stage_colors_safe();
        
        $css = '';
        
        // Add custom stage colors from admin settings
        if (!empty($stage_colors)) {
            foreach ($stage_colors as $stage_num => $colors) {
                $bg_color = isset($colors['bg']) && !empty($colors['bg']) ? $colors['bg'] : null;
                $border_color = isset($colors['border']) && !empty($colors['border']) ? $colors['border'] : null;
                
                if ($bg_color || $border_color) {
                    $css .= "\n/* Stage {$stage_num} Custom Colors */\n";
                    
                    if ($bg_color) {
                        $css .= ".festivawl-stage-header[data-stage-priority=\"{$stage_num}\"],\n";
                        $css .= ".festivawl-event-block[data-stage-priority=\"{$stage_num}\"] {\n";
                        $css .= "    background: {$bg_color} !important;\n";
                        $css .= "}\n";
                    }
                    
                    if ($border_color) {
                        $css .= ".festivawl-stage-header[data-stage-priority=\"{$stage_num}\"],\n";
                        $css .= ".festivawl-event-block[data-stage-priority=\"{$stage_num}\"] {\n";
                        $css .= "    border-left-color: {$border_color} !important;\n";
                        $css .= "}\n";
                    }
                }
            }
        }
        
        // Add cycling colors for stages 16+ (up to 45 stages = 3 full cycles)
        // This covers any reasonable festival size without hardcoding specific numbers
        $css .= "\n/* Auto-cycling colors for stages 16+ */\n";
        for ($stage = 16; $stage <= 45; $stage++) {
            $cycle_stage = (($stage - 1) % 15) + 1; // Cycle through 1-15
            
            $css .= ".festivawl-stage-header[data-stage-priority=\"{$stage}\"] {\n";
            $css .= "    background: var(--festivawl-stage-{$cycle_stage}-bg);\n";
            $css .= "    border-left: 10px solid var(--festivawl-stage-{$cycle_stage}-border);\n";
            $css .= "}\n";
            
            $css .= ".festivawl-event-block[data-stage-priority=\"{$stage}\"] {\n";
            $css .= "    background: var(--festivawl-stage-{$cycle_stage}-bg);\n";
            $css .= "    border-left: 6px solid var(--festivawl-stage-{$cycle_stage}-border);\n";
            $css .= "}\n";
        }
        
        return $css;
    }
} 