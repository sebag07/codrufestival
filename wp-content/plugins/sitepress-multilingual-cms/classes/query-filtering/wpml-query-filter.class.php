<?php

/**
 * Class WPML_Query_Filter
 *
 * @package    wpml-core
 * @subpackage post-translation
 */
class WPML_Query_Filter extends  WPML_Full_Translation_API {

	/** @var  WPML_Name_Query_Filter[] $page_name_filter */
	private $name_filter = array();

	/**
	 * @param string $post_type
	 *
	 * @return WPML_Name_Query_Filter
	 */
	public function get_page_name_filter( $post_type = 'page' ) {
		if ( ! isset( $this->name_filter[ $post_type ] ) ) {
			$this->name_filter[ $post_type ] = $post_type === 'page'
				? new WPML_Page_Name_Query_Filter( $this->sitepress, $this->post_translations, $this->wpdb )
				: ( $this->sitepress->is_translated_post_type( $post_type )
					? new WPML_Name_Query_Filter_Translated( $post_type, $this->sitepress, $this->post_translations, $this->wpdb )
					: new WPML_Name_Query_Filter_Untranslated( $post_type, $this->sitepress, $this->post_translations, $this->wpdb ) );
		}

		return $this->name_filter[ $post_type ];
	}

	/**
	 * @return WPML_404_Guess
	 */
	public function get_404_util() {

		return new WPML_404_Guess( $this->wpdb, $this->sitepress, $this );
	}

	/**
	 * @param string $join
	 * @param string $post_type
	 *
	 * @return string
	 */
	public function filter_single_type_join( $join, $post_type ) {
		if ( 'any' === $post_type ) {
			$join .= $this->any_post_type_join();
		} elseif ( $this->sitepress->is_translated_post_type ( $post_type ) ) {
			$join .= $this->any_post_type_join( false );
		}

		return $join;
	}

	/**
	 * Filters comment queries so that only comments in the current language are displayed for translated post types
	 *
	 * @param string[] $clauses
	 * @param WP_Comment_Query $obj
	 *
	 * @return string[]
	 */
	public function comments_clauses_filter( $clauses, $obj ) {
		if ( $this->is_comment_query_filtered ( $obj )
		     && ( $current_language = $this->sitepress->get_current_language () ) !== 'all'
		) {
			$join_part = $this->get_comment_query_join ( $obj );
			if ( strstr( $clauses['join'], "JOIN {$this->wpdb->posts}" ) !== false ) {
				$join_part = ' AND ';
			}
			$clauses[ 'join' ]
					.= "	JOIN {$this->wpdb->prefix}icl_translations icltr2
							ON icltr2.element_id = {$this->wpdb->comments}.comment_post_ID
							{$join_part} {$this->wpdb->posts}.ID = icltr2.element_id
							AND CONCAT('post_', {$this->wpdb->posts}.post_type) = icltr2.element_type
						LEFT JOIN {$this->wpdb->prefix}icl_translations icltr_comment
							ON icltr_comment.element_id = {$this->wpdb->comments}.comment_ID
								AND icltr_comment.element_type = 'comment'
					";
			$clauses[ 'where' ] .= " " . $this->wpdb->prepare ( " AND icltr2.language_code = %s
															AND (icltr_comment.language_code IS NULL
																	OR icltr_comment.language_code = icltr2.language_code
																) ", $current_language );
		}

		return $clauses;
	}

	/**
	 * @param string $join
	 * @param WP_Query $query
	 *
	 * @return string
	 */
	public function posts_join_filter( $join, $query ) {
		global $pagenow;

		if ( ! $this->is_join_filter_active( $query, $pagenow ) ) {
			return $join;
		}

		$post_type = $this->determine_post_type( 'posts_join' );
		$post_type = $post_type ? $post_type : ( $query->is_tax() ? $this->get_tax_query_posttype( $query ) : 'post' );
		$post_type = $query->is_posts_page ? 'post' : $post_type;
		if ( is_array( $post_type ) && $this->has_translated_type( $post_type ) === true ) {
			$join .= $this->any_post_type_join();
		} elseif ( $post_type ) {
			if ( is_string( $post_type ) ) {
				$join = $this->filter_single_type_join( $join, $post_type );
			}
		} else {
			$taxonomy_post_types = $this->tax_post_types_from_query( $query );
			$join                = $this->tax_types_join( $join, $taxonomy_post_types );
		}

		return $join;
	}

	/**
	 * @param string $where
	 * @param string | String[] $post_type
	 *
	 * @return string
	 */
	public function filter_single_type_where( $where, $post_type ) {
		if ( $this->posttypes_not_translated( $post_type ) === false ) {
			$where .= $this->specific_lang_where( $this->sitepress->get_current_language(), $this->sitepress->get_default_language() );
		}

		return $where;
	}

	/**
	 * @param string $where
	 * @param WP_Query $query
	 *
	 * @return string
	 */
	public function posts_where_filter( $where, $query ) {
		if ( $query === null || $this->where_filter_active( $query ) === false ) {
			return $where;
		}

		$requested_id = isset( $_REQUEST['attachment_id'] ) && $_REQUEST['attachment_id'] ? $_REQUEST['attachment_id'] : false;
		$requested_id = isset( $_REQUEST['post_id'] ) && $_REQUEST['post_id'] ? $_REQUEST['post_id'] : $requested_id;
		$requested_id = (int) $requested_id;

		$default_language = $this->sitepress->get_default_language();

		$post_language = $this->post_translations->get_element_lang_code( $requested_id );
		$current_language = $requested_id && $post_language ? $post_language : $this->sitepress->get_current_language();

		$condition = $current_language === 'all' ? $this->all_langs_where() : $this->specific_lang_where( $current_language, $default_language );
		$where     .= $condition;

		return $where;
	}

	/**
	 * @param bool|false        $not
	 * @param bool|false|string $posts_alias
	 *
	 * @return string
	 */
	public function in_translated_types_snippet( $not = false, $posts_alias = false ) {
		$not         = $not ? " NOT " : "";
		$posts_alias = $posts_alias ? $posts_alias : $this->wpdb->posts;

		$post_types = $this->sitepress->get_translatable_documents( false );
		if ( $post_types ) {
			return "{$posts_alias}.post_type {$not} IN (" . wpml_prepare_in( array_keys( $post_types ) ) . " ) ";
		} else {
			return '';
		}
	}

	/**
	 * @param bool|true $left if true the query will be filtered by a left join, allowing untranslated post types in it
	 *                        simultaneous with translated ones
	 *
	 * @return string
	 */
	private function any_post_type_join( $left = true ) {
		$left = $left ? " LEFT " : "";

		return $left . " JOIN {$this->wpdb->prefix}icl_translations wpml_translations
							ON {$this->wpdb->posts}.ID = wpml_translations.element_id
								AND wpml_translations.element_type = CONCAT('post_', {$this->wpdb->posts}.post_type) ";
	}

	private function has_translated_type($core_types){
		$res = false;
		foreach ( $core_types as $ptype ) {
			if ( $this->sitepress->is_translated_post_type ( $ptype ) ) {
				$res = true;
				break;
			}
		}

		return $res;
	}

	/**
	 * @param WP_Query $query
	 * @param String   $pagenow
	 *
	 * @return bool
	 */
	private function is_join_filter_active( $query, $pagenow ) {
		if ( isset( $query->query['suppress_wpml_where_and_join_filter'] ) && $query->query['suppress_wpml_where_and_join_filter'] ) {
			return false;
		}
		$is_attachment_and_cant_be_translated = $query->is_attachment() ? $this->is_media_and_cant_be_translated( 'attachment' ) : false;

		return $pagenow !== 'media-upload.php' && ! $is_attachment_and_cant_be_translated && ! $this->is_queried_object_root( $query );
	}

	/**
	 * Checks whether the currently queried for object is the root page.
	 *
	 * @param WP_Query $query
	 *
	 * @return bool
	 */
	private function is_queried_object_root( $query ) {
		$url_settings = $this->sitepress->get_setting( 'urls' );
		$root_id      = ! empty( $url_settings['root_page'] ) ? $url_settings['root_page'] : - 1;

		return isset( $query->queried_object )
		       && isset( $query->queried_object->ID )
		       && $query->queried_object->ID == $root_id;
	}

	/**
	 * @param string $query_type
	 *
	 * @return string|false
	 */
	private function determine_post_type( $query_type ) {
		$debug_backtrace = $this->sitepress->get_backtrace( 0, true, false ); //Limit to a maximum level?
		$post_type       = false;
		foreach ( $debug_backtrace as $o ) {
			if ( $o['function'] == 'apply_filters_ref_array' && $o['args'][0] === $query_type ) {
				$query_vars = $o['args'][1][1]->query_vars;
				$post_type  = esc_sql( $query_vars['post_type'] );

				if ( ! $post_type ) {
					if ( (bool) $query_vars['pagename'] ) {
						$post_type = 'page';
					} elseif ( isset( $query_vars['p'] ) ) {
						$post_type = $this->wpdb->get_var(
							$this->wpdb->prepare(
								"SELECT post_type from {$this->wpdb->posts} where ID=%d",
								$query_vars['p']
							)
						);
					}
				}

				break;
			}
		}

		return $post_type;
	}

	/**
	 * @param WP_Query $query
	 * @return String[]
	 */
	private function tax_post_types_from_query($query){
		if ( $query->is_tax () && $query->is_main_query () ) {
			$taxonomy_post_types = $this->get_tax_query_posttype($query);
		} else {
			$taxonomy_post_types = array_keys ( $this->sitepress->get_translatable_documents ( false ) );
		}

		return $taxonomy_post_types;
	}

	private function tax_types_join( $join, $tax_post_types ) {
		if ( !empty( $tax_post_types ) ) {
			foreach ( $tax_post_types as $k => $v ) {
				$tax_post_types[ $k ] = 'post_' . $v;
			}
			$join .=   $this->any_post_type_join() . " AND wpml_translations.element_type IN (" . wpml_prepare_in ( $tax_post_types ) . ") ";
		}

		return $join;
	}

	/**
	 * @param WP_Query $query
	 *
	 * @return string[]
	 */
	private function get_tax_query_posttype( $query ) {
		return WPML_WP_Taxonomy::get_linked_post_types( $query->get( 'taxonomy' ) );
	}

	/**
	 * @param string|string[] $post_types
	 *
	 * @return bool true if non of the input post types are translatable
	 */
	private function posttypes_not_translated( $post_types ) {
		$post_types      = is_array( $post_types ) ? $post_types : array( $post_types );
		$none_translated = true;
		foreach ( $post_types as $ptype ) {
			if ( $this->sitepress->is_translated_post_type( $ptype ) ) {
				$none_translated = false;
				break;
			}
		}

		return $none_translated;
	}

	private function all_langs_where() {

		return ' AND wpml_translations.language_code IN (' . wpml_prepare_in( array_keys( $this->sitepress->get_active_languages() ) ) . ') ';
	}

	private function specific_lang_where( $current_language, $fallback_language ) {

		return $this->wpdb->prepare (
			" AND ( ( ( wpml_translations.language_code = %s OR "
			. $this->display_as_translated_snippet( $current_language, $fallback_language )
			. " ) AND "
			. $this->in_translated_types_snippet ()
			. " ) OR " . $this->in_translated_types_snippet ( true ) . " )",
			$current_language
		);
	}


	private function display_as_translated_snippet( $current_language, $fallback_language ) {
		$content_types = null;
		$skip_content_check = true;

		/**
		 * Filter wpml_should_force_display_as_translated_snippet.
		 *
		 * Force the "display as translated" mode for all post types. Implemented for
		 * Toolset compatibility.
		 */
		if ( ! apply_filters( 'wpml_should_force_display_as_translated_snippet', false ) ) {
			$post_types = $this->sitepress->get_display_as_translated_documents();
			if ( ! $post_types || ! apply_filters( 'wpml_should_use_display_as_translated_snippet', ! is_admin(), $post_types ) ) {
				return '0';
			}
			$content_types = array_keys( $post_types );
			$skip_content_check = false;
		}

		$display_as_translated_query = new WPML_Display_As_Translated_Posts_Query( $this->wpdb );

		return $display_as_translated_query->get_language_snippet( $current_language, $fallback_language, $content_types, $skip_content_check );
	}

	/**
	 * @param WP_Query $query
	 *
	 * @return bool
	 */
	private function where_filter_active( $query ) {
		global $pagenow;

		if ( ! $this->is_join_filter_active( $query, $pagenow ) ) {
			return false;
		}

		$active = $this->is_queried_object_root( $query ) === false;
		if ( $active === true ) {
			$post_type = $this->determine_post_type( 'posts_where' );
			$post_type = empty( $post_type ) && $query->is_tax() ? $this->get_tax_query_posttype( $query )
				: $post_type;
			$post_type = $query->is_posts_page ? 'post' : $post_type;
			$post_type = $post_type ? $post_type : ( $query->is_attachment() ? 'attachment' : 'post' );
			$active    = $pagenow !== 'media-upload.php'
			             && $post_type && ( $post_type === 'any' || $this->posttypes_not_translated( $post_type ) === false )
			             && ! $this->is_media_and_cant_be_translated( $post_type );
		}

		return $active;
	}

	private function is_media_and_cant_be_translated( $post_type ) {
		$is_attachment_and_cant_be_translated = ( $post_type === 'attachment' && ! $this->sitepress->is_translated_post_type( 'attachment' ) );

		return $is_attachment_and_cant_be_translated;
	}

	/**
	 * @param \WP_Comment_Query $comment_query
	 *
	 * @return int|null
	 */
	private function get_post_id_from_comment_query( WP_Comment_Query $comment_query ) {
		if ( isset( $comment_query->query_vars['post_id'] ) ) {
			return $comment_query->query_vars['post_id'];
		} elseif ( isset( $comment_query->query_vars['post_ID'] ) ) {
			return $comment_query->query_vars['post_ID'];
		}

		return null;
	}

	/**
	 * Checks if the comment query applies to posts that are of a translated type.
	 *
	 * @param WP_Comment_Query $comment_query
	 *
	 * @return bool
	 */
	private function is_comment_query_filtered( $comment_query ) {
		$filtered = true;

		$post_id  = $this->get_post_id_from_comment_query( $comment_query );

		if ( $post_id ) {
			$post = get_post( $post_id );
			if ( (bool) $post === true && ! $this->sitepress->is_translated_post_type( $post->post_type ) ) {
				$filtered = false;
			}
		}

		/**
		 * Override when a WP_Comment_Query should be filtered by language.
		 *
		 * @param bool             $filtered
		 * @param int              $post_id
		 * @param WP_Comment_Query $comment_query
		 *
		 * @return bool
		 */
		return apply_filters( 'wpml_is_comment_query_filtered', $filtered, $post_id, $comment_query );
	}

	/**
	 * Adds a join with the posts table to the query only if necessary because the comment query is not filtered
	 * by post variables
	 *
	 * @param WP_Comment_Query $comment_query
	 * @return string
	 */
	private function get_comment_query_join( $comment_query ){
		$posts_params = array(
			'post_author',
			'post_name',
			'post_parent',
			'post_status',
			'post_type',
			'post_author__not_in',
			'post_author__in'
		);
		$query_vars   = isset( $comment_query->query_vars ) ? array_filter ( $comment_query->query_vars ) : array();
		$plucked      = wp_array_slice_assoc ( $query_vars, $posts_params );
		$post_fields  = array_filter ( $plucked );
		$posts_query  = !empty( $post_fields );

		$join_part = $posts_query ? " AND " : "JOIN {$this->wpdb->posts} ON ";

		return $join_part;
	}

	/**
	 * @param int $requested_id
	 *
	 * @return bool|mixed|null|string
	 */
	private function get_current_language( $requested_id ) {
		$current_language = null;
		if ( $requested_id ) {
			$current_language = $this->post_translations->get_element_lang_code( $requested_id );
		}
		if ( ! $current_language ) {
			$current_language = $this->sitepress->get_current_language();
		}
		if ( ! $current_language ) {
			$current_language = $this->sitepress->get_default_language();
		}

		return $current_language;
	}
}
