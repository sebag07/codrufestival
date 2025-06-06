<?php

class WPML_PB_Handle_Custom_Fields implements IWPML_Action {

	protected $data_settings;

	public function __construct( IWPML_Page_Builders_Data_Settings $data_settings ) {
		$this->data_settings = $data_settings;
	}

	public function add_hooks() {
		add_filter( 'wpml_pb_is_page_builder_page', array( $this, 'is_page_builder_page_filter' ), 10, 2 );
		add_action( 'wpml_pb_after_page_without_elements_post_content_copy', array(
			$this,
			'copy_custom_fields'
		), 10, 2 );
	}

	/**
	 * @param bool $is_page_builder_page
	 * @param WP_Post $post
	 *
	 * @return bool
	 */
	public function is_page_builder_page_filter( $is_page_builder_page, WP_Post $post ) {
		if ( $this->data_settings->is_handling_post( $post->ID ) ) {
			$is_page_builder_page = true;
		}

		return $is_page_builder_page;
	}

	/**
	 * @param int $new_post_id
	 * @param int $original_post_id
	 */
	public function copy_custom_fields( $new_post_id, $original_post_id ) {
		$fields = array_merge( $this->data_settings->get_fields_to_copy(), $this->data_settings->get_fields_to_save() );

		foreach ( $fields as $field ) {
			self::copy_field( $new_post_id, $original_post_id, $field );
		}
	}

	/**
	 * @param int $new_post_id
	 * @param int $original_post_id
	 * @param string $field
	 */
	public static function copy_field( $new_post_id, $original_post_id, $field ) {
		$original_field = get_post_meta( $original_post_id, $field, true );
		if ( $original_field ) {
			update_post_meta( $new_post_id, $field, self::slash_json( $original_field ) );
		}
	}

	/**
	 * @param mixed $data
	 *
	 * @return mixed string
	 */
	public static function slash_json( $data ) {
		if ( ! is_string( $data ) ) {
			return $data;
		}

 		json_decode( $data );
		if ( json_last_error() === JSON_ERROR_NONE ) {
			return wp_slash( $data );
		}
		return $data;

	}
}
