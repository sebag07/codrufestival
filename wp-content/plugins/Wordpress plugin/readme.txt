=== Festivawl Calendar ===
Contributors: yourusername
Tags: festival, calendar, events, schedule, timeline
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display beautiful festival schedules from Festivawl API with a professional calendar layout.

== Description ==

Festivawl Calendar is a powerful WordPress plugin that allows you to display stunning festival schedules directly on your website. The plugin fetches data from the Festivawl API and renders it in a beautiful, responsive calendar format that matches modern design standards.

**Key Features:**

* **Beautiful Design**: Professional festival calendar with dark theme and colorful stage representations
* **Responsive Layout**: Fully responsive design that works perfectly on desktop, tablet, and mobile devices
* **Easy to Use**: Simple shortcode implementation - just add `[festivawl_calendar id="100"]` to any page or post
* **Caching System**: Built-in caching to ensure fast loading times and reduce API calls
* **Multiple Stages**: Support for unlimited festival stages with unique color coding
* **Multi-Day Events**: Handle festivals spanning multiple days with easy day navigation
* **WordPress Standards**: Built following WordPress coding standards and security best practices

**Perfect For:**

* Festival websites
* Music venues
* Event promoters
* Conference organizers
* Any multi-stage, multi-day event

**Live API Integration:**

The plugin connects directly to the Festivawl API to fetch real-time festival data including:
* Artist lineup and schedules
* Stage information with priorities
* Multi-day event handling
* Automatic timezone conversion

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/festivawl-calendar` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the Settings -> Festivawl Calendar screen to configure your default settings.
4. Add the shortcode `[festivawl_calendar id="YOUR_FESTIVAL_ID"]` to any page or post.

== Frequently Asked Questions ==

= How do I get a festival ID? =

Festival IDs are provided by the Festivawl platform. Contact your festival organizer or the Festivawl team to obtain your unique festival ID.

= Can I customize the colors? =

The plugin uses a predefined color scheme that automatically assigns colors to different stages based on their priority. Future versions will include customization options.

= Does the plugin work on mobile devices? =

Yes! The plugin is fully responsive and provides an optimized experience for mobile users with touch-friendly navigation and appropriately sized elements.

= How often is the data updated? =

The plugin caches festival data for 1 hour by default (configurable in settings). This ensures fast loading while keeping the data reasonably fresh.

= Can I display multiple festivals on the same page? =

Yes, you can use multiple shortcodes with different festival IDs on the same page.

== Shortcode Usage ==

Basic usage:
`[festivawl_calendar id="100"]`

With custom parameters:
`[festivawl_calendar id="100" theme="dark" height="800px"]`

**Shortcode Parameters:**

* `id` (required) - Festival ID from Festivawl API
* `theme` - Theme style: default, dark, light (default: default)
* `mobile` - Enable mobile optimizations: true, false (default: true)
* `height` - Calendar height in CSS units (default: 600px)

== Screenshots ==

1. Festival calendar with multiple stages and color-coded events
2. Mobile responsive design with day navigation
3. Admin settings page with easy configuration
4. Stage headers with distinctive colors and styling

== Changelog ==

= 1.0.0 =
* Initial release
* Festival calendar display with Festivawl API integration
* Responsive design with mobile optimization
* Multi-day navigation with tab switching
* Stage color coding system
* Caching system for improved performance
* WordPress admin interface
* Shortcode implementation

== Upgrade Notice ==

= 1.0.0 =
Initial release of Festivawl Calendar plugin.

== Development ==

**Built With:**

* WordPress Plugin API
* Festivawl REST API
* Responsive CSS Grid/Flexbox
* jQuery for interactions
* WordPress coding standards

**API Endpoint:**
`https://api.festivawl.com/app/performance/{festival_id}`

**Requirements:**

* WordPress 5.0+
* PHP 7.4+
* Active internet connection for API access
* Modern web browser with JavaScript enabled

**Support:**

For support, feature requests, or bug reports, please contact the plugin developer or visit the plugin's GitHub repository.

== Privacy ==

This plugin connects to the external Festivawl API service to fetch festival data. No personal user data is transmitted to external services. The plugin may cache festival data locally in your WordPress database for performance optimization.

**External Service:**
* Service: Festivawl API
* URL: https://api.festivawl.com
* Purpose: Fetch festival schedule data
* Data transmitted: Festival ID only 