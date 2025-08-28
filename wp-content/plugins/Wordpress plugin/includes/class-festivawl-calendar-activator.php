<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 */
class Festivawl_Calendar_Activator {

    /**
     * Plugin activation tasks.
     */
    public static function activate() {
        // Set default options
        $default_options = array(
            'default_festival_id' => '',
            'cache_duration' => 3600, // 1 hour in seconds
            'theme_style' => 'default',
            'enable_mobile_view' => true
        );

        // Only add options if they don't exist
        foreach ($default_options as $option_name => $option_value) {
            if (get_option('festivawl_calendar_' . $option_name) === false) {
                add_option('festivawl_calendar_' . $option_name, $option_value);
            }
        }

        // Create a scheduled event to clean up expired transients
        if (!wp_next_scheduled('festivawl_calendar_cleanup_transients')) {
            wp_schedule_event(time(), 'daily', 'festivawl_calendar_cleanup_transients');
        }

        // Flush rewrite rules in case we add custom endpoints later
        flush_rewrite_rules();
    }
} 