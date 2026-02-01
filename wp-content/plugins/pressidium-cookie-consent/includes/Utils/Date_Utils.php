<?php
/**
 * Date Utilities.
 *
 * @author Konstantinos Pappas <konpap@pressidium.com>
 * @copyright 2025 Pressidium
 */

namespace Pressidium\WP\CookieConsent\Utils;

use DateTime;
use DateTimeZone;

use Exception;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Forbidden' );
}

/**
 * Date_Utils class.
 *
 * @since 1.0.0
 */
class Date_Utils {

    /**
     * Convert the given UTC date string to the WordPress configured timezone.
     *
     * @param string $utc_date_string Date string to be converted.
     * @param string $format          Date format to return. Defaults to 'Y-m-d H:i:s'.
     *
     * @return ?string Converted date string in the specified format, or `null` on failure.
     */
    public static function utc_to_wp_timezone( string $utc_date_string, string $format = 'Y-m-d H:i:s' ): ?string {
        if ( empty( $utc_date_string ) ) {
            return null;
        }

        try {
            $utc_date = new DateTime( $utc_date_string, new DateTimeZone( 'UTC' ) );
            $utc_date->setTimezone( wp_timezone() );

            return $utc_date->format( $format );
        } catch ( Exception $exception ) {
            return null;
        }
    }

}
