<?php
/**
 * @package    wpml-core
 * @subpackage wpml-core
 */

use WPML\TM\TranslationProxy\Services\AuthorizationFactory;
use WPML\FP\Obj;

require_once WPML_TM_PATH . '/inc/translation-proxy/functions.php';
require_once WPML_TM_PATH . '/inc/translation-proxy/translationproxy-basket.class.php';
require_once WPML_TM_PATH . '/inc/translation-proxy/translationproxy-api.class.php';
require_once WPML_TM_PATH . '/inc/translation-proxy/translationproxy-project.class.php';
require_once WPML_TM_PATH . '/inc/translation-proxy/translationproxy-service.class.php';
require_once WPML_TM_PATH . '/inc/translation-proxy/translationproxy-popup.class.php';
require_once WPML_TM_PATH . '/inc/translation-proxy/translationproxy-translator.class.php';

define( 'CUSTOM_TEXT_MAX_LENGTH', 1000 );

class TranslationProxy {
	private static $tp_client;

	/**
	 * @param bool $reload
	 *
	 * @return WPML_TP_Service[]
	 */
	public static function services( $reload = true ) {
		return self::get_tp_client()->services()->get_all( $reload );
	}

	public static function get_tp_default_suid() {
		if ( defined( 'WPML_TP_DEFAULT_SUID' ) ) {
			return WPML_TP_DEFAULT_SUID;
		}

		return self::get_preferred_translation_service() ?: false;
	}

	/**
	 * @param string $suid
	 * @return 'wpml_list'|'config'|'account'
	 */
	public static function get_service_linked_by_suid( $suid ) {
		if ( defined( 'WPML_TP_DEFAULT_SUID' ) && WPML_TP_DEFAULT_SUID === $suid ) {
			return 'config';
		}
		if ( self::get_preferred_translation_service() === $suid ) {
			return 'account';
		}
		return 'wpml_list';
	}

	public static function has_preferred_translation_service() {
		return self::get_tp_default_suid() !== false;
	}

	public static function clear_preferred_translation_service() {
		WP_Installer_API::set_preferred_ts( 'clear' );
	}

	/**
	 * @param int $service_id
	 *
	 * @return stdClass
	 */
	public static function get_service( $service_id ) {
		// @todo: implement usage of WPML_TP_Service for the active service
		return (object) (array) self::get_tp_client()->services()->get_service( $service_id, true );
	}

	/**
	 * @param int $service_id
	 *
	 * @return TranslationProxy_Project|WP_Error
	 * @throws \WPMLTranslationProxyApiException
	 * @throws \InvalidArgumentException
	 */
	public static function select_service( $service_id, $credentials = null ) {
		global $sitepress;

		$service_selected = false;
		$error            = false;

		/** @var TranslationProxy_Service $service */
		$service = self::get_service( $service_id );

		if ( $service ) {
			self::deselect_active_service();

			$service          = self::build_and_store_active_translation_service( $service, $credentials );
			$result           = $service;
			$service_selected = true;

			// Force authentication if no user input is needed
			if ( ! self::service_requires_authentication( $service ) ) {
				( new AuthorizationFactory() )->create()->authorize( new \stdClass() );
			}
		} else {
			$result = new WP_Error(
				'0',
				'No service selected',
				array( 'service_id' => $service_id )
			);
		}

		// Do not store selected service if this operation failed;
		if ( $error || ! $service_selected ) {
			$sitepress->set_setting( 'translation_service', false, true );
		}
		$sitepress->save_settings();

		return $result;
	}

	public static function deselect_active_service() {
		global $sitepress;

		$sitepress->set_setting( 'translation_service', false );
		$sitepress->set_setting( 'translator_choice', false );
		$sitepress->set_setting( 'icl_lang_status', false );
		$sitepress->set_setting( 'icl_html_status', false );
		$sitepress->set_setting( 'icl_current_session', false );
		$sitepress->set_setting( 'last_icl_reminder_fetch', false );
		$sitepress->set_setting( 'translators_management_info', false );
		$sitepress->set_setting( 'language_pairs', false );
		$sitepress->save_settings();

		do_action( 'wpml_tp_service_dectivated', self::get_current_service() );
	}

	/**
	 * @param      $service
	 * @param bool    $custom_fields_data
	 *
	 * @return mixed
	 * @throws \WPMLTranslationProxyApiException
	 */
	public static function build_and_store_active_translation_service( $service, $custom_fields_data = false ) {
		global $sitepress;

		// set language map
		$service->languages_map = self::languages_map( $service );

		// set information about custom fields
		$service->custom_fields      = self::get_custom_fields( $service->id, true );
		$service->custom_fields_data = $custom_fields_data;

		$service->last_refresh = time();

		$sitepress->set_setting( 'translation_service', $service, true );

		return $service;
	}

	/**
	 * @return TranslationProxy_Project|false
	 */
	public static function get_current_project() {
		$translation_service = self::get_current_service();

		if ( $translation_service && ! is_wp_error( $translation_service ) ) {
			return new TranslationProxy_Project( $translation_service, 'xmlrpc', self::get_tp_client() );
		}
		return false;
	}

	public static function get_current_service_info( array $info = array() ) {
		global $sitepress;
		if ( ! $sitepress->get_setting( 'translation_service' ) ) {
			$sitepress->set_setting( 'translation_service', false, true );
		}
		$service = self::get_current_service();
		if ( $service ) {
			$service_info = array();
			if ( icl_do_not_promote() ) {
				$service_info['name']        = __( 'Translation Service', 'wpml-translation-management' );
				$service_info['logo']        = false;
				$service_info['header']      = __( 'Translation Service', 'wpml-translation-management' );
				$service_info['description'] = false;
				$service_info['contact_url'] = false;
			} else {
				$service_info['name']        = $service->name;
				$service_info['logo']        = $service->logo_url;
				$service_info['header']      = $service->name;
				$service_info['description'] = $service->description;
				$service_info['contact_url'] = $service->url;
			}
			$service_info['setup_url']                = TranslationProxy_Popup::get_link( '@select-translators;from_replace;to_replace@', array( 'ar' => 1 ), true );
			$service_info['has_quote']                = $service->quote_iframe_url !== '';
			$service_info['has_translator_selection'] = $service->has_translator_selection;

			$info[ $service->id ] = $service_info;
		}

		return $info;
	}

	public static function get_service_promo() {
		global $sitepress;

		if ( icl_do_not_promote() ) {
			return '';
		}

		$cache_key   = 'get_service_promo';
		$cache_found = false;

		$output = wp_cache_get( $cache_key, '', false, $cache_found );

		if ( $cache_found ) {
			return $output;
		}

		$icl_translation_services = apply_filters( 'icl_translation_services', array() );
		$icl_translation_services = array_merge( $icl_translation_services, self::get_current_service_info() );

		$output = '';

		if ( ! empty( $icl_translation_services ) ) {

			$sitepress_settings     = $sitepress->get_settings();
			$icl_dashboard_settings = isset( $sitepress_settings['dashboard'] ) ? $sitepress_settings['dashboard'] : array();

			if ( empty( $icl_dashboard_settings['hide_icl_promo'] ) ) {
				$exp_hidden = '';
				$col_hidden = ' hidden';
			} else {
				$exp_hidden = ' hidden';
				$col_hidden = '';
			}

			$output .= '<div class="icl-translation-services' . $exp_hidden . '">';
			foreach ( $icl_translation_services as $service ) {
				$output .= '<div class="icl-translation-services-inner">';
				$output .= '<p class="icl-translation-services-logo"><span><img src="' . $service['logo'] . '" alt="' . $service['name'] . '" /></span></p>';
				$output .= '<h3 class="icl-translation-services-header">  ' . $service['header'] . '</h3>';
				$output .= '<div class="icl-translation-desc"> ' . $service['description'] . '</div>';
				$output .= '</div>';
				$output .= '<p class="icl-translation-links">';
				$output .= '<a class="icl-mail-ico" href="' . $service['contact_url'] . '" target="_blank">' . __( 'Contact', 'wpml-translation-management' ) . " {$service['name']}</a>";
				$output .= '<a id="icl_hide_promo" href="#">' . __( 'Hide this', 'wpml-translation-management' ) . '</a>';
				$output .= '</p>';
			}
			$output .= '</div>';

			$output .= '<a class="' . $col_hidden . '" id="icl_show_promo" href="#">' . __( 'Need translators?', 'wpml-translation-management' ) . '</a>';
		}

		wp_cache_set( $cache_key, $output );

		return $output;
	}

	public static function get_service_dashboard_info() {
		global $sitepress;

		return self::get_custom_html(
			'dashboard',
			$sitepress->get_current_language(),
			array(
				'TranslationProxy_Popup',
				'get_link',
			)
		);
	}

	public static function get_service_translators_info() {
		global $sitepress;

		return self::get_custom_html(
			'translators',
			$sitepress->get_current_language(),
			array(
				'TranslationProxy_Popup',
				'get_link',
			)
		);
	}

	/**
	 * @param string   $location
	 * @param string   $locale
	 * @param callable $popup_link_callback
	 * @param int      $max_count
	 * @param bool     $paragraph
	 *
	 * @return string
	 */
	public static function get_custom_html(
		$location,
		$locale,
		$popup_link_callback,
		$max_count = 1000,
		$paragraph = true
	) {
		/** @var $project TranslationProxy_Project */
		$project = self::get_current_project();

		if ( ! $project ) {
			return '';
		}

		$cache_key   = $project->id . ':' . md5(
			serialize(
				array(
					$location,
					$locale,
					serialize( $popup_link_callback ),
					$max_count,
					$paragraph,
				)
			)
		);
		$cache_group = 'get_custom_html';
		$cache_found = false;

		$output = wp_cache_get( $cache_key, $cache_group, false, $cache_found );

		if ( $cache_found ) {
			return $output;
		}

		try {
			$text = $project->custom_text( $location, $locale );
		} catch ( Exception $e ) {

			return 'Error getting custom text from Translation Service: ' . $e->getMessage();
		}

		$count = 0;
		if ( $text ) {
			foreach ( $text as $string ) {
				$format_string = self::sanitize_custom_text( $string->format_string );

				if ( $paragraph ) {
					$format = '<p>' . $format_string . '</p>';
				} else {
					$format = '<div>' . $format_string . '</div>';
				}
				$links = array();
				/** @var array $string_links */
				$string_links = $string->links;
				foreach ( $string_links as $link ) {
					$url  = self::sanitize_custom_text( $link->url );
					$text = self::sanitize_custom_text( $link->text );
					if ( isset( $link->dismiss ) && (int) $link->dismiss === 1 ) {
						$links[] = '<a href="' . $url . '" class="wpml_tp_custom_dismiss_able">' . $text . '</a>';
					} else {
						$links[] = call_user_func(
							$popup_link_callback,
							$url
						) . $text . '</a>';
					}
				}

				$output .= vsprintf( $format, $links );

				$count ++;
				if ( $count >= $max_count ) {
					break;
				}
			}
		}

		return $output;
	}

	public static function get_current_service_name() {

		if ( icl_do_not_promote() ) {
			return __( 'Translation Service', 'wpml-translation-management' );
		}

		$translation_service = self::get_current_service();

		if ( $translation_service ) {
			return $translation_service->name;
		}

		return false;
	}

	public static function get_current_service_id() {

		$translation_service = self::get_current_service();

		if ( $translation_service ) {
			return $translation_service->id;
		}

		return false;
	}

	public static function get_current_service_batch_name_max_length() {
		$translation_service = self::get_current_service();

		if ( $translation_service && isset( $translation_service->batch_name_max_length )
			 && null
				!== $translation_service->batch_name_max_length ) {
			return $translation_service->batch_name_max_length;
		}

		return 40;
	}

	/**
	 * @param bool|stdClass|TranslationProxy_Service|WP_Error $service
	 *
	 * @return bool
	 * @throws \InvalidArgumentException
	 * @throws \WPMLTranslationProxyApiException
	 */
	public static function service_requires_authentication( $service = false ) {
		if ( ! $service ) {
			$service = self::get_current_service();
		}

		$custom_fields = false;
		if ( false !== (bool) $service ) {
			$custom_fields = self::get_custom_fields( $service->id );
		}

		return $custom_fields && isset( $custom_fields->custom_fields ) && count( $custom_fields->custom_fields ) > 0;
	}

	/**
	 * Return true if $service has been successfully authenticated
	 * Services that do not require authentication are by default authenticated
	 *
	 * @param bool|WP_Error|TranslationProxy_Service $service
	 *
	 * @return bool
	 * @throws \InvalidArgumentException
	 */
	public static function is_service_authenticated( $service = false ) {
		if ( ! $service ) {
			$service = self::get_current_service();
		}

		if ( ! $service ) {
			return false;
		}

		if ( ! self::service_requires_authentication( $service ) ) {
			return true;
		}

		$has_custom_fields  = self::has_custom_fields();
		$custom_fields_data = self::get_custom_fields_data();

		return $has_custom_fields && $custom_fields_data;
	}

	/**
	 * @return stdClass|WP_Error|false
	 */
	public static function get_current_service() {
		/** @var SitePress $sitepress */
		global $sitepress;

		/** @var TranslationProxy_Service $ts */
		$ts = $sitepress->get_setting( 'translation_service' );

		if ( is_array( $ts ) ) {
			return new WP_Error( 'translation-proxy-service-misconfiguration', 'translation_service is stored as array!', $ts );
		}

		return $ts;
	}

	/**
	 *
	 * @return bool
	 * @throws \InvalidArgumentException
	 */
	public static function is_current_service_active_and_authenticated() {
		$active_service = self::get_current_service();

		return $active_service && TranslationProxy_Service::is_authenticated( $active_service );
	}

	/**
	 * @return mixed
	 */
	public static function get_translation_projects() {
		global $sitepress;

		return $sitepress->get_setting( 'icl_translation_projects', null );
	}

	public static function get_service_name( $service_id = false ) {
		if ( $service_id ) {
			$name     = false;
			$services = self::services( false );

			foreach ( $services as $service ) {
				if ( $service->id === $service_id ) {
					$name = $service->name;
				}
			}
		} else {
			$name = self::get_current_service_name();
		}

		return $name;
	}

	public static function has_custom_fields( $service_id = false ) {
		$custom_fields = self::get_custom_fields( $service_id );

		if ( $custom_fields ) {
			return isset( $custom_fields->custom_fields ) && is_array( $custom_fields->custom_fields ) && count( $custom_fields->custom_fields );
		}

		return false;
	}

	/**
	 * @param int|bool $service_id If not given, will use the current service ID (if any)
	 * @param bool     $force_reload Force reload custom fields from Translation Service
	 *
	 * @throws WPMLTranslationProxyApiException
	 * @throws InvalidArgumentException
	 * @return array|mixed|null|string
	 */
	public static function get_custom_fields( $service_id = false, $force_reload = false ) {

		if ( ! $service_id ) {
			$service_id = self::get_current_service_id();
		}
		if ( ! $service_id ) {
			return false;
		}

		$translation_service = self::get_current_service();
		if ( $translation_service && ! $force_reload ) {
			return $translation_service->custom_fields ?: false;
		}

		return self::get_tp_client()->services()->get_custom_fields( $service_id );
	}

	/**
	 * @return array
	 */
	public static function get_extra_fields_local() {
		global $sitepress;
		$service                  = self::get_current_service();
		$icl_translation_projects = $sitepress->get_setting( 'icl_translation_projects' );

		if ( isset( $icl_translation_projects[ TranslationProxy_Project::generate_service_index( $service ) ]['extra_fields'] ) && ! empty( $icl_translation_projects[ TranslationProxy_Project::generate_service_index( $service ) ]['extra_fields'] ) ) {
			return $icl_translation_projects[ TranslationProxy_Project::generate_service_index( $service ) ]['extra_fields'];
		}

		return array();
	}

	/**
	 * @param $extra_fields
	 */
	public static function save_extra_fields( $extra_fields ) {
		global $sitepress;
		$service                    = self::get_current_service();
		$icl_translation_projects   = $sitepress->get_setting( 'icl_translation_projects' );
		$icl_translation_project_id = TranslationProxy_Project::generate_service_index( $service );

		if ( is_array( Obj::prop( $icl_translation_project_id, $icl_translation_projects ) ) ) {
			$icl_translation_projects[ $icl_translation_project_id ]['extra_fields'] = $extra_fields;
			$sitepress->set_setting( 'icl_translation_projects', $icl_translation_projects );
			$sitepress->save_settings();
		}
	}

	public static function maybe_convert_extra_fields( $extra_fields ) {
		$extra_fields_typed = array();

		if ( $extra_fields && is_array( $extra_fields ) ) {
			/** @var array $extra_fields */
			/** @var stdClass $extra_field */
			foreach ( $extra_fields as $extra_field ) {
				if ( $extra_field instanceof WPML_TP_Extra_Field ) {
					$extra_field_typed = $extra_field;
				} else {
					$extra_field_typed = new WPML_TP_Extra_Field();
					if ( isset( $extra_field->type ) ) {
						$extra_field_typed->type = $extra_field->type;
					}
					if ( isset( $extra_field->label ) ) {
						$extra_field_typed->label = $extra_field->label;
					}
					if ( isset( $extra_field->name ) ) {
						$extra_field_typed->name = $extra_field->name;
					}
					if ( isset( $extra_field->items ) ) {
						$extra_field_typed->items = $extra_field->items;
					}
				}
				$extra_fields_typed[] = $extra_field_typed;
			}
		}

		return $extra_fields_typed;
	}

	public static function get_custom_fields_data() {
		$service = self::get_current_service();

		return null !== $service->custom_fields_data ? $service->custom_fields_data : false;
	}

	/**
	 * @return bool true if the current translation service allows selection of specific translators
	 * @throws \InvalidArgumentException
	 */
	public static function translator_selection_available() {
		$res = false;

		$translation_service = self::get_current_service();
		if ( $translation_service && $translation_service->has_translator_selection && self::is_service_authenticated() ) {
			$res = true;
		}

		return $res;
	}

	private static function sanitize_custom_text( $text ) {
		$text = substr( $text, 0, CUSTOM_TEXT_MAX_LENGTH );
		$text = esc_html( $text );

		// Service sends html tags as [tag]
		$text = str_replace( array( '[', ']' ), array( '<', '>' ), $text );

		return $text;
	}

	private static function languages_map( $service ) {
		$languages_map = array();
		$languages     = self::get_tp_client()->services()->get_languages_map( $service->id );
		if ( ! empty( $languages ) ) {
			foreach ( $languages as $language ) {
				$languages_map[ $language->iso_code ] = $language->value;
			}
		}

		return $languages_map;
	}

	private static function get_preferred_translation_service() {
		$tp_default_suid                              = false;
		$preferred_translation_service_from_installer = self::get_preferred_translation_service_from_installer();
		if ( 'clear' !== $preferred_translation_service_from_installer ) {
			$tp_default_suid = $preferred_translation_service_from_installer;
		}

		return $tp_default_suid;
	}

	private static function get_preferred_translation_service_from_installer() {

		return WP_Installer_API::get_preferred_ts();
	}

	public static function get_tp_client() {
		if ( ! self::$tp_client ) {
			$tp_api_factory  = new WPML_TP_Client_Factory();
			self::$tp_client = $tp_api_factory->create();
		}

		return self::$tp_client;
	}
}
