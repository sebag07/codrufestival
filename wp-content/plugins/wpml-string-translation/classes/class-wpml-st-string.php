<?php

/**
 * WPML_ST_String class
 *
 * Low level access to string in Database
 *
 * NOTE: Don't use this class to process a large amount of strings as it doesn't
 * do any caching, etc.
 */
class WPML_ST_String {

	protected $wpdb;

	private $string_id;

	/** @var  string $language */
	private $language;

	/** @var  int $status */
	private $status;

	/** @var array|null */
	private $string_properties;

	/**
	 * @param int  $string_id
	 * @param wpdb $wpdb
	 */
	public function __construct( $string_id, wpdb $wpdb ) {
		$this->wpdb = $wpdb;

		$this->string_id = $string_id;
	}

	/**
	 * @return int
	 */
	public function string_id() {

		return $this->string_id;
	}

	/**
	 * @return string|null
	 */
	public function get_language() {
		$this->language = $this->language
			? $this->language
			: $this->wpdb->get_var(
				'SELECT language ' . $this->from_where_snippet() . ' LIMIT 1'
			);

		return $this->language;
	}

	/**
	 * @return string
	 */

	public function get_value() {
		return $this->wpdb->get_var( 'SELECT value ' . $this->from_where_snippet() . ' LIMIT 1' );
	}

	/**
	 * @return int
	 */
	public function get_status() {

		$this->status = $this->status !== null
			? $this->status
			: (int) $this->wpdb->get_var(
				'SELECT status ' . $this->from_where_snippet() . ' LIMIT 1'
			);

		return $this->status;
	}

	/**
	 * @param string $language
	 */
	public function set_language( $language ) {
		if ( $language !== $this->get_language() ) {
			$this->language = $language;
			$this->set_property( 'language', $language );
			$this->update_status();
		}
	}

	/**
	 * @return stdClass[]
	 */
	public function get_translation_statuses() {

		/** @var array<\stdClass> $statuses */
		$statuses = $this->wpdb->get_results( 'SELECT language, status, mo_string ' . $this->from_where_snippet( true ) );
		foreach ( $statuses as &$status ) {
			if ( ! empty( $status->mo_string ) ) {
				$status->status = ICL_TM_COMPLETE;
			}
			unset( $status->mo_string );
		}

		return $statuses;
	}

	public function get_translations() {

		return $this->wpdb->get_results( 'SELECT * ' . $this->from_where_snippet( true ) );
	}

	/**
	 * For a bulk update of all strings:
	 *
	 * @see WPML_ST_Bulk_Update_Strings_Status::run
	 */
	public function update_status() {
		global $sitepress;

		/**
		 * If the translation has a `mo_string`, the status of this
		 * translation will be set to `WPML_TM_COMPLETE`
		 */
		$st = $this->get_translation_statuses();

		if ( $st ) {

			$string_language = $this->get_language();
			foreach ( $st as $t ) {
				if ( $string_language != $t->language ) {
					$translations[ $t->language ] = $t->status;
				}
			}

			$active_languages = $sitepress->get_active_languages();

			// If has no translation or all translations are not translated
			if ( empty( $translations ) || max( $translations ) == ICL_TM_NOT_TRANSLATED ) {
				$status = ICL_TM_NOT_TRANSLATED;
			} elseif ( in_array( ICL_TM_WAITING_FOR_TRANSLATOR, $translations ) ) {
				$status = ICL_TM_WAITING_FOR_TRANSLATOR;
			} elseif ( in_array( ICL_TM_NEEDS_UPDATE, $translations ) ) {
				$status = ICL_TM_NEEDS_UPDATE;
			} elseif ( $this->has_less_translations_than_secondary_languages( $translations, $active_languages, $string_language ) ) {
				if ( in_array( ICL_TM_COMPLETE, $translations ) ) {
					$status = ICL_STRING_TRANSLATION_PARTIAL;
				} else {
					$status = ICL_TM_NOT_TRANSLATED;
				}
			} else {
				if ( $this->areAllTranslationsComplete( $translations ) ) {
					$status = ICL_TM_COMPLETE;
				} elseif ( in_array( ICL_TM_COMPLETE, $translations ) ) {
					$status = ICL_STRING_TRANSLATION_PARTIAL;
				} else {
					$status = ICL_TM_NOT_TRANSLATED;
				}
			}
		} else {
			$status = ICL_TM_NOT_TRANSLATED;
		}
		if ( $status !== $this->get_status() ) {
			$this->status = $status;
			$this->set_property( 'status', $status );
		}

		return $status;
	}


	/**
	 * @param int[] $translations
	 *
	 * @return bool
	 */
	private function areAllTranslationsComplete( array $translations ) {
		foreach ( $translations as $translation ) {
			if ( $translation != ICL_TM_COMPLETE ) {
				return false;
			}
		}

		return true;
	}


	/**
	 * @param array  $translations
	 * @param array  $active_languages
	 * @param string $string_language
	 *
	 * @return bool
	 */
	private function has_less_translations_than_secondary_languages( array $translations, array $active_languages, $string_language ) {
		$active_lang_codes            = array_keys( $active_languages );
		$translations_in_active_langs = array_intersect( $active_lang_codes, array_keys( $translations ) );
		return count( $translations_in_active_langs ) < count( $active_languages ) - intval( in_array( $string_language, $active_lang_codes, true ) );
	}

	/**
	 * @param string           $language
	 * @param string|null|bool $value
	 * @param int|bool|false   $status
	 * @param int|null         $translator_id
	 * @param string|int|null  $translation_service
	 * @param int|null         $batch_id
	 *
	 * @return bool|int id of the translation
	 */
	public function set_translation( $language, $value = null, $status = false, $translator_id = null, $translation_service = null, $batch_id = null ) {
		if ( ! $this->exists() ) {
			return false;
		}

		/** @var \stdClass $res */
		$res = $this->wpdb->get_row(
			$this->wpdb->prepare(
				'SELECT id, value, status
                                          ' . $this->from_where_snippet( true )
																	. ' AND language=%s',
				$language
			)
		);

		if (
			isset( $res->status ) &&
			$res->status == ICL_TM_WAITING_FOR_TRANSLATOR &&
			is_null( $value ) &&
			! in_array( $status, [ ICL_TM_IN_PROGRESS, ICL_TM_NOT_TRANSLATED ] )
			&& ! $res->value
		) {
			return false;
		}

		$translation_data = array();
		if ( $translation_service ) {
			$translation_data['translation_service'] = $translation_service;
		}
		if ( $batch_id ) {
			$translation_data['batch_id'] = $batch_id;
		}
		if ( ! is_null( $value ) ) {
			if ( is_string( $value ) ) {
				$value = $this->normalize_line_breaks( $value );
			}

			$translation_data['value'] = $value;
		}
		if ( $translator_id ) {
			$translation_data['translator_id'] = $translator_id;
		}

		$translation_data = apply_filters( 'wpml_st_string_translation_before_save', $translation_data, $language, $this->string_id );

		if ( $res ) {
			$st_id = $res->id;
			if ( $status ) {
				$translation_data['status'] = $status;
			} elseif ( $status === ICL_TM_NOT_TRANSLATED ) {
				$translation_data['status'] = ICL_TM_NOT_TRANSLATED;
			}

			if ( ! empty( $translation_data ) ) {
				$this->wpdb->update( $this->wpdb->prefix . 'icl_string_translations', $translation_data, array( 'id' => $st_id ) );
				$this->wpdb->query(
					$this->wpdb->prepare( "UPDATE {$this->wpdb->prefix}icl_string_translations SET translation_date = NOW() WHERE id = %d", $st_id )
				);
			}
		} else {
			$translation_data = array_merge(
				$translation_data,
				array(
					'string_id' => $this->string_id,
					'language'  => $language,
					'status'    => ( $status ? $status : ICL_TM_NOT_TRANSLATED ),
				)
			);

			$this->wpdb->insert( $this->wpdb->prefix . 'icl_string_translations', $translation_data );
			$st_id = $this->wpdb->insert_id;
		}

		/** @var $ICL_Pro_Translation WPML_Pro_Translation */
		global $ICL_Pro_Translation;
		if ( $ICL_Pro_Translation ) {
			// Early stage link fixing in string translations.
			// Keeping this for 3rd party page-builders compatibilty. Some of
			// them do not use the post_content field to store the post content.
			$ICL_Pro_Translation->fix_links_to_translated_content(
				$st_id,
				$language,
				'string',
				[
					'value'     => $value,
					'string_id' => $this->string_id,
				]
			);
		}

		icl_update_string_status( $this->string_id );
		/**
		 * @deprecated Use wpml_st_add_string_translation instead
		 */
		do_action( 'icl_st_add_string_translation', $st_id );
		do_action( 'wpml_st_add_string_translation', $st_id );

		return $st_id;
	}

	public function set_location( $location ) {
		$this->set_property( 'location', $location );
	}

	/**
	 * Set string wrap tag.
	 * Used for SEO significance, can contain values as h1 ... h6, etc.
	 *
	 * @param string $wrap_tag Wrap tag.
	 */
	public function set_wrap_tag( $wrap_tag ) {
		$this->set_property( 'wrap_tag', $wrap_tag );
	}

	/**
	 * @param string $property
	 * @param mixed  $value
	 */
	protected function set_property( $property, $value ) {
		$this->wpdb->update( $this->wpdb->prefix . 'icl_strings', array( $property => $value ), array( 'id' => $this->string_id ) );

		// Action called after string is updated.
		do_action( 'wpml_st_string_updated' );
	}

	/**
	 * @param bool $translations sets whether to use original or translations table
	 *
	 * @return string
	 */
	protected function from_where_snippet( $translations = false ) {

		if ( $translations ) {
			$id_column = 'string_id';
			$table     = 'icl_string_translations';
		} else {
			$id_column = 'id';
			$table     = 'icl_strings';
		}

		return $this->wpdb->prepare( "FROM {$this->wpdb->prefix}{$table} WHERE {$id_column}=%d", $this->string_id );
	}

	public function exists() {
		$sql = $this->wpdb->prepare( "SELECT id FROM {$this->wpdb->prefix}icl_strings WHERE id = %d", $this->string_id );

		return $this->wpdb->get_var( $sql ) > 0;
	}

	/** @return string|null */
	public function get_context() {
		return $this->get_string_properties()->context;
	}

	/** @return string|null */
	public function get_gettext_context() {
		return $this->get_string_properties()->gettext_context;
	}

	/** @return string|null */
	public function get_name() {
		return $this->get_string_properties()->name;
	}

	private function get_string_properties() {

		if ( ! $this->string_properties ) {
			$row = $this->wpdb->get_row( 'SELECT name, context, gettext_context ' . $this->from_where_snippet() . ' LIMIT 1' );

			$this->string_properties = $row ? $row : (object) [
				'name'            => null,
				'gettext_context' => null,
				'context'         => null,
			];
		}

		return $this->string_properties;
	}

	/**
	 * @param string $translation_string
	 * @return string
	 */
	public function normalize_line_breaks( $translation_string ) {
		$original_string = $this->get_value();
		/**
		 * If the original string has \r\n character, replace \n with \r\n in the translation string to display line break in emails, HTTP requests and some text-based protocols.
		 */
		if ( is_string( $original_string ) && strpos( $original_string, "\r\n" ) !== false ) {
			$translation_string = preg_replace( '/(?<!\r)\n/', "\r\n", $translation_string );
		}

		return $translation_string;
	}
}
