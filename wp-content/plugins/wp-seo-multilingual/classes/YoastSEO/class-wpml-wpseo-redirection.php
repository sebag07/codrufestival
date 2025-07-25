<?php

use WPML\WPSEO\YoastSEO\Utils;

class WPML_WPSEO_Redirection {

	const OPTION = 'wpseo-premium-redirects-base';

	/**
	 * @return bool
	 */
	public function is_redirection() {
		if ( ! Utils::isPremium() ) {
			return false;
		}

		$redirections = $this->get_all_redirections();
		if ( is_array( $redirections ) ) {

			// Use same logic as WPSEO_Redirect_Util::strip_base_url_path_from_url.
			$url = trim( $_SERVER['REQUEST_URI'], '/' );

			add_filter( 'wpml_skip_convert_url_string', '__return_true' );
			$base_url_path = ltrim( (string) wp_parse_url( home_url(), PHP_URL_PATH ), '/' );
			remove_filter( 'wpml_skip_convert_url_string', '__return_true' );

			if ( stripos( trailingslashit( $url ), trailingslashit( $base_url_path ) ) === 0 ) {
				$url = substr( $url, strlen( $base_url_path ) );
			}

			foreach ( $redirections as $redirection ) {
				if ( $redirection['origin'] === $url || '/' . $redirection['origin'] === $url ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @return mixed
	 */
	private function get_all_redirections() {
		return get_option( self::OPTION );
	}
}
