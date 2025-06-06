<?php

use WPML\FP\Fns;

class WPML_WPSEO_Categories implements IWPML_Action {

	const CATEGORY_SLUG = 'category';

	/**
	 * @var WPML_ST_Slug_Translation_Settings_Factory|null $slugTranslationSettingsFactory
	 */
	private $slugTranslationSettingsFactory;

	/**
	 * @param WPML_ST_Slug_Translation_Settings_Factory|null $slugTranslationSettingsFactory
	 */
	public function __construct( WPML_ST_Slug_Translation_Settings_Factory $slugTranslationSettingsFactory = null ) {
		$this->slugTranslationSettingsFactory = $slugTranslationSettingsFactory;
	}

	/**
	 * Add hooks.
	 */
	public function add_hooks() {
		if ( $this->is_stripping_category_base() ) {
			add_filter( 'category_rewrite_rules', array( $this, 'append_categories_hook' ), -PHP_INT_MAX );
			add_filter( 'category_rewrite_rules', array( $this, 'turn_off_get_terms_filter' ), PHP_INT_MAX );
			add_action( 'admin_init', [ $this, 'ensureCategoryBaseIsNotTranslated' ] );
		}
	}

	/**
	 * Are we stripping the category base?
	 *
	 * @return bool
	 */
	private function is_stripping_category_base() {
		$option = (array) get_option( 'wpseo_titles' );

		return array_key_exists( 'stripcategorybase', $option ) && $option['stripcategorybase'];
	}

	/**
	 * Turn on filter.
	 *
	 * @param array $rules
	 * @return array
	 */
	public function append_categories_hook( $rules ) {
		add_filter( 'get_terms', array( $this, 'append_categories_translations' ), 10, 2 );

		return $rules;
	}

	/**
	 * Turn off filter.
	 *
	 * @param array $rules
	 * @return array
	 */
	public function turn_off_get_terms_filter( $rules ) {
		remove_filter( 'get_terms', array( $this, 'append_categories_translations' ) );

		return $rules;
	}

	/**
	 * We need categories in all languages for 'stripcategorybase' to work.
	 *
	 * @param array $categories
	 * @param array $taxonomy
	 * @return array
	 */
	public function append_categories_translations( $categories, $taxonomy ) {
		if ( ! in_array( self::CATEGORY_SLUG, $taxonomy, true ) || ! $this->is_array_of_wp_term( $categories ) ) {
			return $categories;
		}

		global $wpdb;

		$sql = "
			SELECT t.term_id FROM {$wpdb->terms} t
			INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_id = t.term_id
			WHERE tt.taxonomy = '" . self::CATEGORY_SLUG . "'
		";

		return array_filter( array_map( array( $this, 'map_to_term' ), $wpdb->get_col( $sql ) ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	}

	/**
	 * @param array $terms
	 *
	 * @return bool
	 */
	private function is_array_of_wp_term( array $terms ) {
		return current( $terms ) instanceof WP_Term;
	}

	/**
	 * @param int $term_id
	 *
	 * @return false|WP_Term
	 */
	protected function map_to_term( $term_id ) {
		$disableAdjustId = [ 'wpml_disable_term_adjust_id', Fns::always( true ) ];

		add_filter( ...$disableAdjustId );
		$term = get_term( $term_id, self::CATEGORY_SLUG );
		remove_filter( ...$disableAdjustId );

		return $term;
	}

	public function ensureCategoryBaseIsNotTranslated() {
		if ( $this->slugTranslationSettingsFactory ) {
			$taxSettings = $this->slugTranslationSettingsFactory->createTaxSettings();

			if ( $taxSettings->is_translated( self::CATEGORY_SLUG ) ) {
				$taxSettings->set_type( self::CATEGORY_SLUG, false );
				$taxSettings->save();
			}
		}
	}
}
