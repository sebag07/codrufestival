<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 */
class Festivawl_Calendar_Deactivator {

    /**
     * Plugin deactivation cleanup.
     */
    public static function deactivate() {
        // Clear all scheduled events
        wp_clear_scheduled_hook('festivawl_calendar_cleanup_transients');

        // Delete all plugin transients to free up database space
        self::cleanup_transients();

        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Clean up all plugin-related transients.
     */
    private static function cleanup_transients() {
        global $wpdb;

        // Delete all transients that start with our prefix
        $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
            '_transient_festivawl_calendar_%',
            '_transient_timeout_festivawl_calendar_%'
        ));
    }
} 