<?php

use WPML\LIB\WP\Gutenberg;

class WPML_PB_Update_Shortcodes_In_Content {

	const LONG_STRING_THRESHOLD = 5000;

	/** @var  WPML_PB_Shortcode_Strategy $strategy */
	private $strategy;
	/** @var WPML_PB_Shortcode_Encoding $encoding */
	private $encoding;
	/** @var string */
	private $new_content;
	/** @var array */
	private $string_translations;
	/** @var string */
	private $lang;

	public function __construct( WPML_PB_Shortcode_Strategy $strategy, WPML_PB_Shortcode_Encoding $encoding ) {
		$this->strategy = $strategy;
		$this->encoding = $encoding;
	}

	public function update( $translated_post_id, $original_post, $string_translations, $lang ) {
		if ( Gutenberg::hasBlock( $original_post->post_content ) ) {
			return;
		}

		$original_content = $original_post->post_content;
		$original_content = apply_filters( 'wpml_pb_shortcode_content_for_translation', $original_content, $original_post->ID );

		$new_translation = $this->update_content( $original_content, $string_translations, $lang );

		$translated_post     = get_post( $translated_post_id );
		$current_translation = isset( $translated_post->post_content ) ? $translated_post->post_content : '';
		$current_translation = apply_filters( 'wpml_pb_shortcode_content_for_translation', $current_translation, $translated_post_id );

		$translation_saved = apply_filters( 'wpml_pb_shortcodes_save_translation', false, $translated_post_id, $new_translation );

		if ( ! $translation_saved ) {
			if ( $new_translation !== $original_content || '' === $current_translation ) {
				wpml_update_escaped_post(
					[
						'ID'           => $translated_post_id,
						'post_content' => $new_translation,
					]
				);
			}
		}
	}

	public function update_content( $original_content, $string_translations, $lang ) {
		$original_content          = WPML_PB_Shortcode_Content_Wrapper::maybeWrap( $original_content, $this->strategy->get_shortcodes() );
		$this->new_content         = $original_content;
		$this->string_translations = $string_translations;
		$this->lang                = $lang;

		$shortcode_parser = $this->strategy->get_shortcode_parser();
		$shortcodes       = $shortcode_parser->get_shortcodes( $original_content );

		foreach ( $shortcodes as $shortcode ) {
			$this->update_shortcodes( $shortcode );
			$this->update_shortcode_attributes( $shortcode );
		}

		return WPML_PB_Shortcode_Content_Wrapper::unwrap( $this->new_content );
	}

	private function update_shortcodes( $shortcode_data ) {
		$encoding    = $this->strategy->get_shortcode_tag_encoding( $shortcode_data['tag'] );
		$translation = $this->get_translation( $shortcode_data['content'], $encoding );
		$this->replace_string_with_translation( $shortcode_data['block'], $shortcode_data['content'], $translation );
	}

	private function update_shortcode_attributes( $shortcode_data ) {

		$shortcode_attribute = $shortcode_data['attributes'];

		$attributes              = (array) shortcode_parse_atts( $shortcode_attribute );
		$translatable_attributes = $this->strategy->get_shortcode_attributes( $shortcode_data['tag'] );
		if ( ! empty( $attributes ) ) {
			foreach ( $attributes as $attr => $attr_value ) {
				if ( in_array( $attr, $translatable_attributes, true ) ) {
					$encoding            = $this->strategy->get_shortcode_attribute_encoding( $shortcode_data['tag'], $attr );
					$translation         = $this->get_translation( $attr_value, $encoding );
					$translation         = $this->filter_attribute_translation( $translation, $encoding );
					$shortcode_attribute = $this->replace_string_with_translation( $shortcode_attribute, $attr_value, $translation, true, $attr );
				}
			}
		}
	}

	private function replace_string_with_translation( $block, $original, $translation, $is_attribute = false, $attr = '' ) {
		$translation  = apply_filters( 'wpml_pb_before_replace_string_with_translation', $translation, $is_attribute );
		$used_wrapper = false;
		$new_block    = $block;

		if ( $translation ) {

			if ( $this->is_string_too_long_for_regex( $original ) || $this->is_string_too_long_for_regex( $block ) ) {
				$block             = WPML_PB_Shortcode_Content_Wrapper::unwrap( $block );
				$new_block         = str_replace( $original, $translation, $block );
				$this->new_content = str_replace( $block, $new_block, $this->new_content );
			} else {
				if ( $is_attribute && $attr ) {
					// Quotes needs to be converted to entities, otherwise they can match with
					// the shortcode attributes delimiters and break the attributes.
					$translation = str_replace( [ "'", '"' ], [ '&apos;', '&quot;' ], $translation );
					$pattern     = '/' . $attr . '=(["\'])' . preg_quote( $original, '/' ) . '(["\'])/';
					$replacement = $attr . '=${1}' . $this->escape_backward_reference_on_replacement_string( $translation ) . '${2}';
				} else {
					$used_wrapper = false !== strpos( $new_block, '[' . WPML_PB_Shortcode_Content_Wrapper::WRAPPER_SHORTCODE_NAME . ']' );
					$pattern      = '/(]\s*)' . preg_quote( trim( $original ), '/' ) . '(\s*\[)/';
					$replacement  = '${1}' . $this->escape_backward_reference_on_replacement_string( trim( $translation ) ) . '${2}';
				}

				$new_block   = preg_replace( $pattern, $replacement, $block );
				$block       = WPML_PB_Shortcode_Content_Wrapper::unwrap( $block );
				$new_block   = WPML_PB_Shortcode_Content_Wrapper::unwrap( $new_block );
				$replacement = $this->escape_backward_reference_on_replacement_string( $new_block );

				if ( $used_wrapper ) {
					$this->new_content = $this->replace_content_without_delimiters( $block, $replacement );
				} else {
					$this->new_content = preg_replace( '/' . preg_quote( $block, '/' ) . '/', $replacement, $this->new_content, 1 );
				}
			}
		}

		return $new_block;
	}

	/**
	 * We need to escape backward references that could be included in the replacement text
	 * e.g. '$1999.each' => '$19' is considered as a backward reference
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	private function escape_backward_reference_on_replacement_string( $string ) {
		return preg_replace( '/\$([\d]{1,2})/', '\\\$${1}', $string );
	}

	private function replace_content_without_delimiters( $block, $replacement ) {
		return preg_replace( '/(]\s*)' . preg_quote( $block, '/' ) . '(\s*\[)/', '${1}' . $replacement . '${2}', $this->new_content, 1 );
	}

	/**
	 * @param string $string
	 *
	 * @return bool
	 */
	private function is_string_too_long_for_regex( $string ) {
		return mb_strlen( $string ) > self::LONG_STRING_THRESHOLD;
	}

	private function get_translation( $original, $encoding = false ) {
		$decoded_original = $this->encoding->decode( $original, $encoding );

		$translation = null;

		if ( is_array( $decoded_original ) ) {

			$translation = array();
			foreach ( $decoded_original as $key => $data ) {
				if ( $data['translate'] ) {
					$translated_data = $this->get_translation( $data['value'], '' );
					if ( $translated_data ) {
						$translation[ $key ] = $translated_data;
					} else {
						$translation[ $key ] = $data['value'];
					}
				} else {
					$translation[ $key ] = $data['value'];
				}
			}
		} else {

			$string_name = md5( $decoded_original );
			if ( isset( $this->string_translations[ $string_name ][ $this->lang ] ) && ICL_TM_COMPLETE === (int) $this->string_translations[ $string_name ][ $this->lang ]['status'] ) {
				$translation = $this->string_translations[ $string_name ][ $this->lang ]['value'];
			}
		}

		if ( $translation ) {
			$translation = $this->encoding->encode( $translation, $encoding );
		}

		return $translation;
	}

	/**
	 * @param string|null $translation
	 * @param string      $encoding
	 *
	 * @return string
	 */
	private function filter_attribute_translation( $translation, $encoding ) {
		if ( is_null( $translation ) ) {
			return '';
		}

		if ( 'allow_html_tags' !== $encoding ) {
			$translation = htmlspecialchars( htmlspecialchars_decode( $translation ) );
		}

		$translation = str_replace( array( '[', ']' ), array( '&#91;', '&#93;' ), $translation );

		return $translation;
	}
}

