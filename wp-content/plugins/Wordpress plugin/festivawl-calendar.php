<?php
/**
 * Plugin Name: Festivawl Calendar
 * Description: Display beautiful festival schedules from Festivawl API with a professional calendar layout. Perfect for festival websites and event promotion.
 * Version: 1.3.3  
 * Author: Festivawl App
 * Author URI: https://festivawl.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: festivawl-calendar
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * Network: false
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('FESTIVAWL_CALENDAR_VERSION', '1.3.3');
define('FESTIVAWL_CALENDAR_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FESTIVAWL_CALENDAR_PLUGIN_URL', plugin_dir_url(__FILE__));
define('FESTIVAWL_CALENDAR_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * The code that runs during plugin activation.
 */
function activate_festivawl_calendar() {
    require_once FESTIVAWL_CALENDAR_PLUGIN_DIR . 'includes/class-festivawl-calendar-activator.php';
    Festivawl_Calendar_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_festivawl_calendar() {
    require_once FESTIVAWL_CALENDAR_PLUGIN_DIR . 'includes/class-festivawl-calendar-deactivator.php';
    Festivawl_Calendar_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_festivawl_calendar');
register_deactivation_hook(__FILE__, 'deactivate_festivawl_calendar');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require FESTIVAWL_CALENDAR_PLUGIN_DIR . 'includes/class-festivawl-calendar.php';

/**
 * Begins execution of the plugin.
 */
function run_festivawl_calendar() {
    $plugin = new Festivawl_Calendar();
    $plugin->run();
}

run_festivawl_calendar(); 