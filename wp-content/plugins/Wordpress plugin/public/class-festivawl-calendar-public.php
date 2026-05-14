<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for enqueuing
 * the public-facing stylesheet and JavaScript.
 */
class Festivawl_Calendar_Public {

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
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            $this->plugin_name,
            FESTIVAWL_CALENDAR_PLUGIN_URL . 'public/css/festivawl-calendar-public.css',
            array(),
            $this->version,
            'all'
        );
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            $this->plugin_name,
            FESTIVAWL_CALENDAR_PLUGIN_URL . 'public/js/festivawl-calendar-public.js',
            array('jquery'),
            $this->version,
            false
        );

        // Localize script for AJAX and translations
        wp_localize_script(
            $this->plugin_name,
            'festivawl_calendar_ajax',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('festivawl_calendar_nonce'),
                'strings' => array(
                    'loading' => __('Loading festival data...', 'festivawl-calendar'),
                    'error' => __('Error loading data', 'festivawl-calendar'),
                    'no_events' => __('No events scheduled', 'festivawl-calendar')
                )
            )
        );
    }
} 