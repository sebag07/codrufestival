<?php

namespace WPML\ST;

use WPML\ST\MO\Hooks\LanguageSwitch;
use WPML\ST\MO\File\Manager;
use WPML\ST\StringsFilter\Provider;
use WPML_Locale;

class TranslateWpmlString {

	/** @var array $loadedDomains */
	private static $loadedDomains = [];

	/** @var Provider $filterProvider */
	private $filterProvider;

	/** @var LanguageSwitch $languageSwitch */
	private $languageSwitch;

	/** @var WPML_Locale $locale */
	private $locale;

	/** @var Manager $fileManager */
	private $fileManager;

	/** @var bool $lock */
	private $lock = false;

	public function __construct(
		Provider $filterProvider,
		LanguageSwitch $languageSwitch,
		WPML_Locale $locale,
		Manager $fileManager
	) {
		$this->filterProvider  = $filterProvider;
		$this->languageSwitch  = $languageSwitch;
		$this->locale          = $locale;
		$this->fileManager     = $fileManager;
	}

	public function init() {
		$this->languageSwitch->initCurrentLocale();
	}

	/**
	 * @param string|array $wpmlContext
	 * @param string       $name
	 * @param bool|string  $value
	 * @param bool         $allowEmptyValue
	 * @param null|bool    $hasTranslation
	 * @param null|string  $targetLang
	 *
	 * @return bool|string
	 */
	public function translate( $wpmlContext, $name, $value = false, $allowEmptyValue = false, &$hasTranslation = null, $targetLang = null ) {
		if ( $this->lock ) {
			return $value;
		}

		$this->lock = true;

		if ( wpml_st_is_requested_blog() ) {

			if ( self::canTranslateWithMO( $value, $name ) ) {
				$value = $this->translateByMOFile( $wpmlContext, $name, $value, $hasTranslation, $targetLang );
			} else {
				$value = $this->translateByDBQuery( $wpmlContext, $name, $value, $hasTranslation, $targetLang );
			}
		}

		$this->lock = false;

		return $value;
	}

	/**
	 * @param string|array $wpmlContext
	 * @param string       $name
	 * @param bool|string  $value
	 * @param null|bool    $hasTranslation
	 * @param null|string  $targetLang
	 *
	 * @return string
	 */
	private function translateByMOFile( $wpmlContext, $name, $value, &$hasTranslation, $targetLang ) {
		list ( $domain, $gettextContext ) = wpml_st_extract_context_parameters( $wpmlContext );

		$translateByName = function ( $locale ) use ( $name, $domain, $gettextContext ) {
			$this->loadTextDomain( $domain, $locale );

			if ( $gettextContext ) {
				return _x( $name, $gettextContext, $domain );
			} else {
				return __( $name, $domain );
			}
		};

		/*
		 * This function is called when icl_translate function from /inc/functions.php is called.
		 * Examples of such calls: from Admin_Texts class translate_single function, /src/Display/l18N.php function in NextGenGallery plugin.
		 * So, those are custom requests for translations from other plugins or our custom functionality(like admin texts).
		 * There is a difference between usual gettext calls from plugins and calls from here:
		 *      __( 'value', 'domain' ); // From plugins we pass only untranslated value from 'value' column from 'icl_strings' table.
		 *      __( 'name', 'domain'); // From this function we can pass also 'name' column from 'icl_strings' table and not value.
		 * That could lead to situation when we have autoregistered a string with name setup inside value column instead of the name column.
		 * That can also create 'phantom' strings in strings table which look like real ones but have value setup as a name and name is missing.
		 * To avoid this, we should disable autoregistration during next gettext call and autoregister string manually by passing both name and value.
		 */
		do_action( 'wpml_st_update_settings', 'disableAutoregistration' );
		$new_value      = $this->withMOLocale( $targetLang, $translateByName );
		$hasTranslation = $new_value !== $name;
		if ( $hasTranslation ) {
			$value = $new_value;
		} else {
			do_action('wpml_st_add_to_queue', $value, $domain, $gettextContext, $name);
		}
		do_action( 'wpml_st_update_settings', 'enableAutoregistration' );

		return $value;
	}

	/**
	 * @param string|array $wpmlContext
	 * @param string       $name
	 * @param bool|string  $value
	 * @param null|bool    $hasTranslation
	 * @param null|string  $targetLang
	 *
	 * @return string
	 */
	private function translateByDBQuery( $wpmlContext, $name, $value, &$hasTranslation, $targetLang ) {
		$filter = $this->filterProvider->getFilter( $targetLang, $name );

		if ( $filter ) {
			$value = $filter->translate_by_name_and_context( $value, $name, $wpmlContext, $hasTranslation );
		}

		return $value;
	}

	/**
	 * @param string $domain
	 * @param string $locale
	 */
	private function loadTextDomain( $domain, $locale ) {
		if (
			! isset( $GLOBALS['l10n'][ $domain ] )
			&& ! isset( $GLOBALS['l10n_unloaded'][ $domain ] )
			&& ! isset( self::$loadedDomains[ $locale ][ $domain ] )
		) {
			load_textdomain(
				$domain,
				$this->fileManager->getFilepath( $domain, $locale )
			);

			self::$loadedDomains[ $locale ][ $domain ] = true;
		}
	}

	/**
	 * @param string   $targetLang
	 * @param callable $function
	 *
	 * @return string
	 */
	private function withMOLocale( $targetLang, $function ) {
		$initialLocale = $this->languageSwitch->getCurrentLocale();

		if ( $targetLang ) {
			/** @var string $targetLocale */
			$targetLocale = $this->locale->get_locale( $targetLang );
			$this->languageSwitch->switchToLocale( $targetLocale );
			$result = $function( $targetLocale );
			$this->languageSwitch->switchToLocale( $initialLocale );
		} else {
			$result = $function( $initialLocale );
		}

		return $result;
	}

	/**
	 * We will allow MO translation only when
	 * the original is not empty.
	 *
	 * We also need to make sure we deal with a
	 * WPML registered string (not gettext).
	 *
	 * If those conditions are not fulfilled,
	 * we will translate from the database.
	 *
	 * @param string|bool $original
	 * @param string      $name
	 *
	 * @return bool
	 */
	public static function canTranslateWithMO( $original, $name ) {
		return $original && self::isWpmlRegisteredString( $original, $name );
	}

	/**
	 * This allows to differentiate WPML registered strings
	 * from gettext strings that have the default hash for
	 * the name.
	 *
	 * But it's still possible that WPML registered strings
	 * have a hash for the name.
	 *
	 * @param string|bool $original
	 * @param string      $name
	 *
	 * @return bool
	 */
	private static function isWpmlRegisteredString( $original, $name ) {
		return $name && md5( (string) $original ) !== $name;
	}

	public static function resetCache() {
		self::$loadedDomains = [];
	}
}
