<?php


class WPML_CMS_Navigation {
	var $settings;
	private $cache;

	function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
	}

	function init() {
		global $cms_nav_ie_ver;

		// Check if WPML is active. If not display warning message and not load CMS Navigation
		if ( ! defined( 'ICL_SITEPRESS_VERSION' ) || ICL_PLUGIN_INACTIVE ) {
			if ( ! function_exists( 'is_multisite' ) || ! is_multisite() ) {
				add_action( 'admin_notices', array( $this, '_no_wpml_warning' ) );
			}
			return false;
		} elseif ( version_compare( ICL_SITEPRESS_VERSION, '2.0.5', '<' ) ) {
			add_action( 'admin_notices', array( $this, '_old_wpml_warning' ) );
			return false;
		}

		// Load plugin settings
		$this->settings = get_option( 'wpml_cms_nav_settings' );

		// Use WPML legacy. Read settings from WPML if they exist there.
		if ( empty( $this->settings ) && defined( 'ICL_SITEPRESS_VERSION' ) ) {
			require_once WPML_CMS_NAV_PLUGIN_PATH . '/inc/cms-navigation-schema.php';
			wpml_cms_nav_default_settings();
		}

		// Initialize cache
		$this->cache['offsite_url_cache'] = new wpml_cms_nav_cache( 'cms_nav_offsite_url', true );

		// Determine User agent to be used in rendering the menu correctly for IE
		$cms_nav_user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : false;
		if ( $cms_nav_user_agent && preg_match( '#MSIE ([0-9]+)\.[0-9]#', $cms_nav_user_agent, $matches ) ) {
			$cms_nav_ie_ver = $matches[1];
		}

		// Setup the WP-Admin resources
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_resources' ) );
		// Setup the WP-Admin menus
		add_action( 'wpml_admin_menu_configure', array( $this, 'menu' ) );

		// Clear cache hook
		add_action( 'wp_ajax_wpml_cms_nav_clear_nav_cache', array( $this, 'clear_cache' ) );
		// Save form(options) hook
		add_action( 'wp_ajax_wpml_cms_nav_save_form', array( $this, 'save_form' ) );

		// theme hooks
		add_action( 'icl_navigation_breadcrumb', array( $this, 'cms_navigation_breadcrumb' ) );
		add_action( 'icl_navigation_menu', array( $this, 'cms_navigation_menu_nav' ) );

		// more hooks
		add_action( 'save_post', array( $this, 'cms_navigation_update_post_settings' ), 4, 2 );
		add_action( 'admin_head', array( $this, 'cms_navigation_page_edit_options' ) );
		add_action( 'admin_head', array( $this, 'cms_navigation_js' ) );

		// offsite urls hooks
		add_filter( 'page_link', array( $this, 'rewrite_page_link' ), 15, 2 );
		add_action( 'parse_query', array( $this, 'redirect_offsite_urls' ) );

		// situations to clear the cache
		add_filter( 'permalink_structure_changed', array( $this, 'clear_cache' ) );
		add_filter( 'update_option_show_on_front', array( $this, 'clear_cache' ) );
		add_filter( 'update_option_page_on_front', array( $this, 'clear_cache' ) );
		add_filter( 'update_option_page_for_posts', array( $this, 'clear_cache' ) );
		add_action( 'delete_post', array( $this, 'clear_cache' ) );
		add_action( 'delete_category', array( $this, 'clear_cache' ) );
		add_action( 'create_category', array( $this, 'clear_cache' ) );
		add_action( 'edited_category', array( $this, 'clear_cache' ) );

		// add message to WPML dashboard widget
		add_action( 'icl_dashboard_widget_content', array( $this, 'icl_dashboard_widget_content' ) );

		return true;
	}

	function plugins_loaded() {
		$this->plugin_localization();

		// Load resources
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_styles' ) );
	}

	function enqueue_admin_resources( $hook ) {
		if ( self::get_menu_slug() === $hook ) {
			wp_enqueue_script(
				'wpml-cms-nav-js',
				WPML_CMS_NAV_PLUGIN_URL . '/res/js/navigation.js',
				array(),
				WPML_CMS_NAV_VERSION
			);

			if ( $this->should_load_css_styles() ) {
				wp_enqueue_style(
					'wpml-cms-nav-css',
					WPML_CMS_NAV_PLUGIN_URL . '/res/css/navigation.css',
					array(),
					WPML_CMS_NAV_VERSION
				);
			}
		}
	}

	function enqueue_frontend_styles() {
		if ( $this->should_load_css_styles() ) {
			wp_enqueue_style(
				'cms-navigation-style-base',
				WPML_CMS_NAV_PLUGIN_URL . '/res/css/cms-navigation-base.css',
				array(),
				WPML_CMS_NAV_VERSION,
				'screen'
			);

			wp_enqueue_style(
				'cms-navigation-style',
				WPML_CMS_NAV_PLUGIN_URL . '/res/css/cms-navigation.css',
				array(),
				WPML_CMS_NAV_VERSION,
				'screen'
			);
		}
	}

	/** @return bool */
	private function should_load_css_styles() {
		return ! defined( 'ICL_DONT_LOAD_NAVIGATION_CSS' ) || ! ICL_DONT_LOAD_NAVIGATION_CSS;
	}

	function _no_wpml_warning() {
		?>
		<div class="message error"><p>
		<?php
		printf(
			__( 'WPML CMS Navigation is enabled but not effective. It requires <a href="%s">WPML</a> in order to work.', 'wpml-cms-nav' ),
			'https://wpml.org/'
		);
		?>
			</p></div>
		<?php
	}

	function _old_wpml_warning() {
		?>
		<div class="message error"><p>
		<?php
		printf(
			__( 'WPML CMS Navigation is enabled but not effective. It is not compatible with  <a href="%s">WPML</a> versions prior 2.0.5.', 'wpml-cms-nav' ),
			'https://wpml.org/'
		);
		?>
			</p></div>
		<?php
	}


	function get_settings() {
		return $this->settings;
	}

	function save_settings() {
		update_option( 'wpml_cms_nav_settings', $this->settings );
	}

	/**
	 * @param string $menu_id
	 */
	function menu( $menu_id ) {
		if ( 'WPML' !== $menu_id ) {
			return;
		}

		$menu               = array();
		$menu['order']      = 1100;
		$menu['page_title'] = __( 'Navigation', 'sitepress' );
		$menu['menu_title'] = __( 'Navigation', 'sitepress' );
		$menu['capability'] = 'wpml_manage_navigation';
		$menu['menu_slug']  = self::get_menu_slug();

		do_action( 'wpml_admin_menu_register_item', $menu );
	}

	private static function get_menu_slug() {
		return basename( WPML_CMS_NAV_PLUGIN_PATH ) . '/menu/navigation.php';
	}

	function save_form() {
		global $wpdb;

		if ( ! wp_verify_nonce( filter_input( INPUT_POST, 'icl_cms_nav_nonce' ), 'icl_cms_nav_nonce' ) ) {
			return false;
		}

		$this->settings['page_order']    = $_POST['icl_navigation_page_order'];
		$this->settings['show_cat_menu'] = (int) $_POST['icl_navigation_show_cat_menu'];
		if ( $_POST['icl_navigation_cat_menu_title'] ) {
			$this->settings['cat_menu_title'] = stripslashes( $_POST['icl_navigation_cat_menu_title'] );
			if ( function_exists( 'icl_register_string' ) ) {
				icl_register_string( 'WPML', 'Categories Menu', stripslashes( $_POST['icl_navigation_cat_menu_title'] ) );
			}
		}
		$this->settings['cat_menu_page_order'] = $_POST['icl_navigation_cat_menu_page_order'];
		$this->settings['cat_menu_contents']   = $_POST['icl_blog_menu_contents'];
		$this->settings['heading_start']       = stripslashes( $_POST['icl_navigation_heading_start'] );
		$this->settings['heading_end']         = stripslashes( $_POST['icl_navigation_heading_end'] );

		$this->settings['cache'] = isset( $_POST['icl_navigation_caching'] ) ? $_POST['icl_navigation_caching'] : 0;

		$this->settings['breadcrumbs_separator'] = stripslashes( $_POST['icl_breadcrumbs_separator'] );

		$this->save_settings();

		$this->clear_cache();

		return true;
	}

	function clear_cache() {
		global $wpdb;

		if ( $this->should_use_cache() ) {
			// clear the cache.
			/** @var $offsite_url_cache wpml_cms_nav_cache */
			$offsite_url_cache = $this->cache['offsite_url_cache'];
			$offsite_url_cache->clear();
			$wpdb->query( "TRUNCATE {$wpdb->prefix}icl_cms_nav_cache" );
		}

		return true;
	}

	function cms_navigation_breadcrumb() {
		global $post, $wpdb, $wp_query;
		global $sitepress, $sitepress_settings;

		if ( func_num_args() ) {
			$args      = func_get_args();
			$separator = $args[0];
		}

		if ( ! empty( $separator ) && is_string( $separator ) && $separator != $this->settings['breadcrumbs_separator'] ) {
			$this->settings['breadcrumbs_separator'] = $separator;
		}

		$output    = null;
		$use_cache = $this->should_use_cache();
		$cache_key = false;

		if ( $use_cache ) {
			$cache_key = $_SERVER['REQUEST_URI'] . '-' . $sitepress->get_current_language();

			$output_prepared = $wpdb->prepare(
				"
                                SELECT data
                                FROM {$wpdb->prefix}icl_cms_nav_cache
                                WHERE cache_key=%s
                                AND type='nav_breadcrumb'
                                AND DATE_SUB(NOW(), INTERVAL " . WPML_CMS_NAV_CACHE_EXPIRE . ') < timestamp',
				$cache_key
			);
			$output          = $wpdb->get_var( $output_prepared );
		}

		if ( ! $output ) {

			// save the menu to a cache
			ob_start();

			if ( 0 === strpos( 'page', get_option( 'show_on_front' ) ) ) {
				$page_on_front  = (int) get_option( 'page_on_front' );
				$page_for_posts = (int) get_option( 'page_for_posts' );
			} else {
				$page_on_front  = 0;
				$page_for_posts = 0;
			}
			if ( isset( $post ) && $page_on_front != $post->ID ) {
				if ( $page_on_front ) {
					$permalink = $sitepress->language_url();
					if ( $sitepress_settings['language_negotiation_type'] != 3 ) {
						$permalink = trailingslashit( $permalink );
					}
					?>
					<a href="<?php echo $permalink; ?>"><?php echo get_the_title( $page_on_front ); ?></a>
					<?php
						echo $this->settings['breadcrumbs_separator'];
				} elseif ( ! is_home() || ( is_home() && ! $page_on_front && $page_for_posts ) ) {
					?>
					<a href="<?php echo $sitepress->language_url(); ?>"><?php _e( 'Home' ); ?></a>
					<?php
						echo $this->settings['breadcrumbs_separator'];
				}
			}

			$post_types = $sitepress->get_translatable_documents( true );
			$post_type  = (array) get_query_var( 'post_type' );
			$post_type  = reset( $post_type );

			unset( $post_types['post'], $post_types['page'] );
			if ( ( ( $pn = get_query_var( 'pagename' ) ) || ( ! $post_type && ! get_query_var( 'p' ) && ! get_query_var( $post_type ) ) ) && isset( $post_types[ $post_type ] ) ) {
				if ( empty( $pn ) ) {
					echo $post_type_name = $post_types[ $post_type ]->labels->name;
				} else {
					echo $post_type_name = $post_types[ $pn ]->labels->name;
				}
				if ( get_query_var( 'name' ) ) {
					echo $this->settings['breadcrumbs_separator'];
				}
			} elseif ( ( $post_type ) && get_query_var( $post_type ) ) {
				if ( isset( $post_types[ $post_type ]->has_archive )
							&& $post_types[ $post_type ]->has_archive
							&& function_exists( 'get_post_type_archive_link' ) ) {
					echo '<a href="' . get_post_type_archive_link( $post_type ) . '">'
						. $post_types[ $post_type ]->labels->name . '</a> '
						. $this->settings['breadcrumbs_separator'];
				} elseif ( isset( $post_types[ $post_type ]->taxonomies )
						&& ! empty( $post_types[ $post_type ]->taxonomies ) ) {
					$custom_post_tax = false;
					foreach ( $post_types[ $post_type ]->taxonomies as $temp_tax ) {
						$terms = wp_get_post_terms( $wp_query->get_queried_object_id(), $temp_tax );
						if ( ! empty( $terms ) ) {
							$custom_post_tax = $temp_tax;
							break;
						}
					}
					if ( empty( $terms ) ) {
						echo $post_types[ $post_type ]->labels->name
								. $this->settings['breadcrumbs_separator'];
					} else {
						$term_parents[] = array(
							'name' => $terms[0]->name,
							'url'  => get_term_link( $terms[0], $custom_post_tax ),
						);
						$term_parent    = $terms[0]->parent;
						while ( $term_parent ) {
							$term           = get_term( $term_parent, $custom_post_tax );
							$term_parent    = $term->parent;
							$term_parents[] = array(
								'name' => $term->name,
								'url'  => get_term_link( (int) $term->term_id, $custom_post_tax ),
							);
						}
						if ( ! empty( $term_parents ) ) {
							$term_parents = array_reverse( $term_parents );
							foreach ( $term_parents as $term ) {
								echo '<a href="' . $term['url'] . '">' . $term['name'] . '</a> ' . $this->settings['breadcrumbs_separator'];
							};
						}
					}
				} elseif ( isset( $post_types[ $post_type ]->labels->name ) ) {
					echo $post_types[ $post_type ]->labels->name
					. $this->settings['breadcrumbs_separator'];
				}
			} elseif ( is_archive() && isset( $post_types[ $post_type ]->labels->name ) ) {
				echo $post_types[ $post_type ]->labels->name;
			} elseif ( ! is_page() && ! is_home() && ! is_tax() && $page_for_posts ) {

				// get all custom post types
				$custom_post_types = get_post_types(
					array(
						'public'   => true,
						'_builtin' => false,
					),
					'objects',
					'and'
				);

				// if custom post type
				if ( isset( $custom_post_types[ $post_type ] ) ) {
					// if custom post type has archive
					if ( $custom_post_types[ $post_type ]->has_archive ) {
						echo '<a href="' . get_post_type_archive_link( $post_type ) . '">'
							 . $custom_post_types[ $post_type ]->labels->name . '</a>'
							 . $this->settings['breadcrumbs_separator'];
					}
				} else {
					?>
					<a href="<?php echo get_permalink( $page_for_posts ); ?>"><?php echo get_the_title( $page_for_posts ); ?></a>
					<?php
					echo $this->settings['breadcrumbs_separator'];
				}
			}

			$custom_breadcrumbs = apply_filters( 'icl_navigation_breadcrumb_custom', [], $post );

			if ( is_home() && $page_for_posts && ! isset( $post_type_name ) ) {
				echo get_the_title( $page_for_posts );
			} elseif ( !empty( $custom_breadcrumbs ) ) {
				foreach ( $custom_breadcrumbs as $title => $permalink ) {
					if ( $permalink ) {
						echo "<a href='$permalink'>$title</a>{$this->settings['breadcrumbs_separator']}";
					} else {
						echo $title;
					}
				}
			} elseif (
				! is_archive() &&
					(
							is_page() ||
							( isset( $post_types[ $post_type ]->hierarchical ) && $post_types[ $post_type ]->hierarchical )
					) && $page_on_front !== $post->ID
			) {
				the_post();
				if ( $this->post_has_ancestors( $post ) ) {
					$ancestors = array_reverse( $post->ancestors );

					foreach ( $ancestors as $anc ) {
						if ( $page_on_front == $anc ) {
							continue;}
						?>
						<a href="<?php echo get_permalink( $anc ); ?>"><?php echo get_the_title( $anc ); ?></a>
											<?php
											echo $this->settings['breadcrumbs_separator'];
					}
				}
				echo get_the_title();
				rewind_posts();
			} elseif ( ( $post_type ) && get_query_var( $post_type ) ) {
				the_post();
				echo get_the_title();
				rewind_posts();
			} elseif ( is_single() ) {
				the_post();
				$cat = get_the_category();
				if ( is_array( $cat ) && count( $cat ) ) {
					$cat_id  = $cat[0]->cat_ID;
					$parents = get_category_parents( $cat_id, true, $this->settings['breadcrumbs_separator'] );
					if ( is_string( $parents ) ) {
						echo $parents;
					}
				}
				the_title();
				rewind_posts();
			} elseif ( is_category() ) {
				$cat = get_term( (int) get_query_var( 'cat' ), 'category', OBJECT, 'display' );
				if ( ! empty( $cat->parent ) ) {
					$category_parent = get_category_parents( $cat->parent, true, $this->settings['breadcrumbs_separator'] );
					echo $category_parent;
				}
				single_cat_title();
			} elseif ( is_tag() ) {
				echo __( 'Articles tagged ', 'wpml-cms-nav' ) ,'&#8216;';
				single_tag_title();
				echo '&#8217;';
			} elseif ( is_tax() ) {
				$term        = get_term( $wp_query->get_queried_object_id(), get_query_var( 'taxonomy' ) );
				$term_name   = $term->name;
				$term_parent = $term->parent;
				while ( $term_parent ) {
					$term           = get_term( $term_parent, get_query_var( 'taxonomy' ) );
					$term_parent    = $term->parent;
					$term_parents[] = array(
						'name' => $term->name,
						'url'  => get_term_link( (int) $term->term_id, get_query_var( 'taxonomy' ) ),
					);
				}
				if ( ! empty( $term_parents ) ) {
					$term_parents = array_reverse( $term_parents );
					foreach ( $term_parents as $term ) {
						echo '<a href="' . $term['url'] . '">' . $term['name'] . '</a> ' . $this->settings['breadcrumbs_separator'];
					};
				}
				echo $term_name;
			} elseif ( is_month() ) {
				echo get_the_time( 'F, Y' );
			} elseif ( is_search() ) {
				echo __( 'Search for: ', 'wpml-cms-nav' ), strip_tags( get_query_var( 's' ) );
			}
			$output = ob_get_contents();
			ob_end_clean();

			if ( ! $output ) {
				$output = ' ';
			}

			if ( $use_cache ) {
				$delete_prepared = $wpdb->prepare(
					"DELETE FROM
                             {$wpdb->prefix}icl_cms_nav_cache
                             WHERE cache_key= %s
                             AND type='nav_breadcrumb'",
					$cache_key
				);
				$wpdb->query( $delete_prepared );
				$wpdb->insert(
					$wpdb->prefix . 'icl_cms_nav_cache',
					array(
						'cache_key' => $cache_key,
						'type'      => 'nav_breadcrumb',
						'data'      => $output,
					)
				);
			}
		}

		echo $output;
	}

	function cms_navigation_menu_nav() {
		global $wpdb, $post, $cms_nav_ie_ver, $wp_query;
		global $sitepress, $sitepress_settings;

		$current_language = $sitepress->get_current_language();
		$default_language = $sitepress->get_default_language();

		if ( function_exists( 'icl_t' ) ) {
			$cat_menu_title = $this->settings['cat_menu_title'] ? icl_t( 'WPML', 'Categories Menu', $this->settings['cat_menu_title'] ) : __( 'News', 'wpml-cms-nav' );
		} else {
			$cat_menu_title = $this->settings['cat_menu_title'] ? $this->settings['cat_menu_title'] : __( 'News', 'wpml-cms-nav' );
		}

		$use_cache = $this->settings['cache'] && ! ( defined( 'WPML_CMS_NAV_DISABLE_CACHE' ) && WPML_CMS_NAV_DISABLE_CACHE );

		$output    = null;
		$cache_key = false;
		if ( $use_cache ) {
			$cache_key = $_SERVER['REQUEST_URI'] . '-' . $current_language;

			if ( isset( $cms_nav_ie_ver ) ) {
				$cache_key .= '-ie-' . $cms_nav_ie_ver;
			}
			$output_prepared = $wpdb->prepare(
				"
                                SELECT data
                                FROM {$wpdb->prefix}icl_cms_nav_cache
                                WHERE cache_key = %s
                                AND type='nav_menu'
                                AND DATE_SUB(NOW(), INTERVAL " . WPML_CMS_NAV_CACHE_EXPIRE . ') < timestamp',
				$cache_key
			);
			$output          = $wpdb->get_var( $output_prepared );
		}

		if ( ! $output ) {

			// save the menu to a cache
			ob_start();

			$order         = esc_sql( isset( $this->settings['page_order'] ) ? $this->settings['page_order'] : 'menu_order' );
			$show_cat_menu = isset( $this->settings['show_cat_menu'] ) ? $this->settings['show_cat_menu'] : false;

			if ( 0 === strpos( 'page', get_option( 'show_on_front' ) ) ) {
				$page_on_front  = (int) get_option( 'page_on_front' );
				$page_for_posts = (int) get_option( 'page_for_posts' );
			} else {
				$page_on_front  = 0;
				$page_for_posts = 0;
			}

			$pages_query                    = new WPML_CMS_Nav_Pages( $wpdb, new WPML_Display_As_Translated_Posts_Query( $wpdb, 'p' ) );
			list( $pages, $excluded_pages ) = $pages_query->get_pages(
				$current_language,
				$default_language,
				$show_cat_menu,
				$page_for_posts,
				$sitepress_settings['existing_content_language_verified'],
				$order,
				$sitepress->is_display_as_translated_post_type( 'page' )
			);

			$sitepress->switch_lang( $default_language );
			$page_for_posts_abs = get_option( 'page_for_posts' );
			$sitepress->switch_lang();
			if ( $show_cat_menu && ( 0 !== strpos( 'page', get_option( 'show_on_front' ) ) || ! $page_for_posts_abs ) ) {
				$res = false;
				if ( $pages ) {
					$res_prepared = "SELECT ID, menu_order FROM {$wpdb->posts} WHERE ID IN (" . wpml_prepare_in( $pages, '%d' ) . ') ORDER BY menu_order';
					$res          = $wpdb->get_results( $res_prepared );
				}
				if ( $res ) {
					foreach ( $res as $row ) {
						$orders[ $row->ID ] = $row->menu_order;
					}
				}
				$blog_special_page_inserted = false;
				$incpages                   = array();
				foreach ( $pages as $p ) {
					if ( ! $blog_special_page_inserted && ( isset( $orders[ $p ] ) && $orders[ $p ] > $this->settings['cat_menu_page_order'] ) ) {
						$incpages[]                 = 0;
						$blog_special_page_inserted = true;
					}
					$incpages[] = $p;
				}
				if ( ! $blog_special_page_inserted ) {
					$pages[] = 0;
				} else {
					$pages = $incpages;
				}
			}

			if ( $pages ) {
				?>
				<div id="menu-wrap"><ul id="cms-nav-top-menu">
				<?php
				$incr = 0;
				foreach ( $pages as $p ) {
					$incr++;
					if ( $p === 0 ) {

						if ( $incr == 1 ) {
							$smain_li_classes[] = 'icl_first';
						} elseif ( $incr == count( $pages ) ) {
							$smain_li_classes[] = 'icl_last';
						}
						if ( ( is_category() && $this->settings['cat_menu_contents'] == 'categories' ) ||
							( is_single() && $this->settings['cat_menu_contents'] == 'posts' ) ) {
							$smain_li_classes[] = 'selected_page';
						}

						?>
						<li
						<?php
						if ( ! empty( $smain_li_classes ) ) :
							?>
  class="<?php echo join( ' ', $smain_li_classes ); ?>"<?php endif ?>><a href="<?php echo trailingslashit( get_option( 'home' ) ); ?>" class="
						<?php
						if ( $this->settings['cat_menu_contents'] != 'nothing' ) :
							?>
 trigger<?php endif ?>"><?php echo $cat_menu_title; ?>
						<?php
						if ( ! isset( $cms_nav_ie_ver ) || $cms_nav_ie_ver > 6 ) :
							?>
		</a><?php endif; ?>
						<?php
					} else {
						$sections = array();

						$subpages_prepared = $wpdb->prepare(
							"
                            SELECT p.ID, meta_value AS section
                            FROM {$wpdb->posts} p LEFT JOIN {$wpdb->postmeta} m ON p.ID=m.post_id AND (meta_key='_cms_nav_section' OR meta_key IS NULL)
                            WHERE p.post_parent=%d AND post_type='page' AND p.post_status='publish' AND p.ID NOT IN ({$excluded_pages}) ORDER BY " . $order,
							array( $p )
						);
						$subpages          = $wpdb->get_results( $subpages_prepared );
						foreach ( (array) $subpages as $s ) {
							$sections[ $s->section ][] = $s->ID;
						}
						ksort( $sections );

						if ( isset( $post ) && ( $p == $post->ID || $this->post_ancestor_exists( $post, $p ) || ( $p == $page_for_posts && is_home() ) ) ) {
							$sel = true;
						} else {
							$sel = false;
						}
						$page_name_html = apply_filters( 'icl_nav_page_html', $p, 0 );
						if ( $page_name_html == $p ) {
							$page_name_html = get_the_title( $p );
						}

						$main_li_classes = array();
						if ( $sel ) {
							$main_li_classes[] = 'selected_page';
						}
						if ( $incr == 1 ) {
							$main_li_classes[] = 'icl_first';
						} elseif ( $incr == count( $pages ) ) {
							$main_li_classes[] = 'icl_last';
						}
						$has_subages = $subpages || ( $page_for_posts == $p && $this->settings['cat_menu_contents'] != 'nothing' );
						if ( isset( $post ) && $p == $post->ID ) {
							$permalink = '#';
						} else {
							if ( $p == $page_on_front ) {
								$permalink = $sitepress->language_url();
								if ( $sitepress_settings['language_negotiation_type'] != 3 ) {
									$permalink = trailingslashit( $permalink );
								}
							} else {
								$permalink = get_permalink( $p );
							}
						}
						?>
						<li
						<?php
						if ( ! empty( $main_li_classes ) ) :
							?>
  class="<?php echo join( ' ', $main_li_classes ); ?>"<?php endif ?>><a href="<?php echo $permalink; ?>" class="
						<?php
						if ( $has_subages ) :
							?>
 trigger<?php endif ?>"><?php echo $page_name_html; ?>
						<?php
						if ( ! isset( $cms_nav_ie_ver ) || $cms_nav_ie_ver > 6 ) :
							?>
		</a><?php endif; ?>
					<?php } ?>
						<?php if ( ( ( $page_for_posts == $p || ( isset( $p->blog_page ) && $p->blog_page ) ) && $this->settings['cat_menu_contents'] != 'nothing' ) ) : ?>
							<?php
							if ( isset( $cms_nav_ie_ver ) && $cms_nav_ie_ver <= 6 ) :
								?>
								<table><tr><td><?php endif; ?>
							<ul>
							<?php if ( $this->settings['cat_menu_contents'] == 'categories' ) : ?>
								<?php
								$post_cats        = array();
								$post_in_this_cat = 0;
								if ( is_single() && ! is_page() ) {
									$cats = get_the_category();
									foreach ( (array) $cats as $cat ) {
										$post_cats[] = $cat->cat_ID;}
								}
								$categories = get_categories( 'child_of=0' );
								foreach ( $categories as $cat ) {
									echo '<li';
									if ( in_array( $cat->cat_ID, (array) $post_cats ) ) {
										$post_in_this_cat++; }
									if ( $wp_query->query_vars['cat'] == $cat->cat_ID || $post_in_this_cat == 1 ) {
										echo ' class="selected_subpage"';
									}
									echo '>';
									echo '<a href="' . get_category_link( $cat->cat_ID ) . '">';
									echo apply_filters( 'single_cat_title', $cat->cat_name );
									echo '</a>';
									echo '</li>';
								}
								?>
							<?php elseif ( $this->settings['cat_menu_contents'] == 'posts' ) : ?>
								<?php
									$postbk  = $post; // preserve $post
									$cmsnavq = new WP_Query();
									$cmsnavq->query( 'suppress_filters=0' );
								if ( $cmsnavq->have_posts() ) :
									while ( $cmsnavq->have_posts() ) :
										$cmsnavq->the_post();
										?>
									<li
										<?php
										if ( get_the_ID() == get_query_var( 'p' ) ) :
											?>
  class="selected_subpage"<?php endif ?>>
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
										<?php
									endwhile;
endif;
									$post = $postbk; // restore $post
								?>
							<?php endif; ?>
							</ul>
							<?php
							if ( isset( $cms_nav_ie_ver ) && $cms_nav_ie_ver <= 6 ) :
								?>
								</td></tr></table><?php endif; ?>
						<?php elseif ( isset( $subpages ) && $subpages ) : ?>
							<?php
							if ( isset( $cms_nav_ie_ver ) && $cms_nav_ie_ver <= 6 ) :
								?>
								<table><tr><td><?php endif; ?>
							<ul>
								<?php
								if ( isset( $sections ) ) {
									foreach ( $sections as $sec_name => $sec ) {

										if ( $sec_name ) {
											?>
											<li class="section icl-top-nav-section-<?php echo sanitize_title_with_dashes( $sec_name ); ?>"><?php echo $sec_name; ?></li>
											<?php
										}

										foreach ( $sec as $sp ) {
											$item_level = ( ! isset( $post ) || $sp == $post->ID ) ? ' class="selected_subpage"' : '';
											?>
											<li<?php echo $item_level; ?>>
												<?php
												$subpage_name_html = apply_filters( 'icl_nav_page_html', $sp, 1 );
												if ( $subpage_name_html == $sp ) {
													$subpage_name_html = get_the_title( $sp );
												}
												if ( ! isset( $post ) || $sp != $post->ID ) {
													$item_permalink = get_permalink( $sp );
													$item_selection = '';
													if ( $this->is_an_ancestor( $post, $sp ) ) {
														$item_selection = ' class="selected"';
													}
													?>
													<a href="<?php echo $item_permalink; ?>"<?php echo $item_selection; ?>><?php echo $subpage_name_html; ?></a>
													<?php
												} else {
													echo $subpage_name_html;
												}
												?>
											</li>
											<?php
										}
									}
								}
								?>
							</ul>
							<?php
							if ( isset( $cms_nav_ie_ver ) && $cms_nav_ie_ver <= 6 ) :
								?>
								</td></tr></table><?php endif; ?>
						<?php endif; ?>
					</li>
					<?php
				}
				?>
				</ul>
				</div>
				<br class="cms-nav-clearit" />
				<?php
			}

			$output = ob_get_contents();
			ob_end_clean();

			if ( $use_cache ) {
				$delete_prepared = $wpdb->prepare(
					"DELETE FROM
                             {$wpdb->prefix}icl_cms_nav_cache
                             WHERE cache_key=%s
                             AND type='nav_menu'",
					$cache_key
				);
				$wpdb->query( $delete_prepared );
				$wpdb->insert(
					$wpdb->prefix . 'icl_cms_nav_cache',
					array(
						'cache_key' => $cache_key,
						'type'      => 'nav_menu',
						'data'      => $output,
					)
				);
			}
		}

		echo $output;
	}

	function cms_navigation_update_post_settings( $post_id, $post ) {

		$this->clear_cache();

		if ( ( isset( $post->post_status ) && $post->post_status === 'auto-draft' )
			 || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			 || ( isset( $_POST['autosave'] ) && $_POST['autosave'] )
			 || ( isset( $_POST['post_type'] ) && $_POST['post_type'] !== 'page' )
			 || ( isset( $_POST['action'] ) && $_POST['action'] === 'inline-save' )
			 || ( isset( $_POST['post_type'] ) && $_POST['post_type'] === 'revision' )
		) {
			return;
		}

		if ( ! empty( $_POST['exclude_from_top_nav'] ) ) {
			update_post_meta( $post_id, '_top_nav_excluded', 1 );
		} else {
			delete_post_meta( $post_id, '_top_nav_excluded' );
		}
		if ( ! empty( $_POST['cms_nav_minihome'] ) ) {
			update_post_meta( $post_id, '_cms_nav_minihome', 1 );
		} else {
			delete_post_meta( $post_id, '_cms_nav_minihome' );
		}
		if ( ! empty( $_POST['cms_nav_section_new'] ) ) {
			update_post_meta( $post_id, '_cms_nav_section', $_POST['cms_nav_section_new'] );
		} else {
			delete_post_meta( $post_id, '_cms_nav_section' );
		}
		if ( isset( $_POST['cms_nav_section_new'] ) && ! trim( $_POST['cms_nav_section_new'] ) ) {
			if ( ! empty( $_POST['cms_nav_section'] ) ) {
				update_post_meta( $post_id, '_cms_nav_section', $_POST['cms_nav_section'] );
			} else {
				delete_post_meta( $post_id, '_cms_nav_section' );
			}
		}
		if ( ! empty( $_POST['_cms_nav_offsite_url'] ) ) {
			update_post_meta( $post_id, '_cms_nav_offsite_url', $_POST['_cms_nav_offsite_url'] );
		} else {
			delete_post_meta( $post_id, '_cms_nav_offsite_url' );
		}

	}

	function cms_navigation_page_edit_options() {
		global $sitepress;
		if ( function_exists( 'add_meta_box' ) && $sitepress->get_setting( 'setup_complete' ) ) {
			add_meta_box( 'cmsnavdiv', __( 'CMS Navigation', 'wpml-cms-nav' ), array( $this, 'cms_navigation_meta_box' ), 'page', 'normal', 'high' );
		}
	}

	function cms_navigation_meta_box( $post ) {
		global $wpdb, $sitepress;

		if ( ! $sitepress->get_setting( 'setup_complete' ) ) {
			return;
		}
		// if it's a new post copy some custom fields from the original post
		$cms_nav_section = false;
		if ( $post->ID == 0 && isset( $_GET['trid'] ) && $_GET['trid'] ) {
			$copied_custom_fields = array( '_top_nav_excluded', '_cms_nav_minihome' );
			foreach ( $copied_custom_fields as $k => $v ) {
				$copied_custom_fields[ $k ] = "'" . $v . "'";
			}
			$res_prepared = $wpdb->prepare(
				"
                SELECT meta_key, meta_value FROM {$wpdb->prefix}icl_translations tr
                JOIN {$wpdb->postmeta} pm ON tr.element_id = pm.post_id
                WHERE tr.trid=%d AND (source_language_code IS NULL OR source_language_code='')
                    AND meta_key IN (" . wpml_prepare_in( $copied_custom_fields ) . ')
            ',
				$_GET['trid']
			);
			$res          = $wpdb->get_results( $res_prepared );
			foreach ( $res as $r ) {
				$post_custom[ $r->meta_key ][0] = $r->meta_value;
			}
		} else {
			// get sections
			global $sitepress;
			$current_language = $sitepress->get_current_language();
			$sql              = $wpdb->prepare(
				"
					SELECT
					  DISTINCT meta_value
					FROM {$wpdb->postmeta} pm
					INNER JOIN {$wpdb->prefix}icl_translations t
					ON t.element_id = pm.post_id
					WHERE meta_key='_cms_nav_section'
					AND t.element_type = 'post_page'
					AND t.language_code = %s
					",
				$current_language
			);
			$sections         = $wpdb->get_col( $sql );

			$post_custom     = get_post_custom( $post->ID );
			$cms_nav_section = isset( $post_custom['_cms_nav_section'][0] ) ? $post_custom['_cms_nav_section'][0] : '';
		}
		$top_nav_excluded    = isset( $post_custom['_top_nav_excluded'][0] ) ? $post_custom['_top_nav_excluded'][0] : '';
		$cms_nav_minihome    = isset( $post_custom['_cms_nav_minihome'][0] ) ? $post_custom['_cms_nav_minihome'][0] : '';
		$cms_nav_offsite_url = isset( $post_custom['_cms_nav_offsite_url'][0] ) ? $post_custom['_cms_nav_offsite_url'][0] : '';
		if ( $top_nav_excluded ) {
			$top_nav_excluded = 'checked="checked"'; }
		if ( $cms_nav_minihome ) {
			$cms_nav_minihome = 'checked="checked"'; }
		?>
		<p>
		<label><input type="checkbox" value="1" name="exclude_from_top_nav" <?php echo $top_nav_excluded; ?> />&nbsp; <?php echo __( 'Exclude from the top navigation', 'wpml-cms-nav' ); ?></label> &nbsp;
		<label><input type="checkbox" value="1" name="cms_nav_minihome" <?php echo $cms_nav_minihome; ?> />&nbsp; <?php echo __( 'Mini home (don\'t list child pages for this page)', 'wpml-cms-nav' ); ?></label>
		</p>
		<p>
		<?php echo __( 'Section', 'wpml-cms-nav' ); ?>
		<?php if ( ! empty( $sections ) ) : ?>
			<select name="cms_nav_section">
			<option value=''><?php echo __( '--none--', 'wpml-cms-nav' ); ?></option>
			<?php foreach ( $sections as $s ) : ?>
			<option
				<?php
				if ( $s == $cms_nav_section ) {
					echo 'selected="selected"';}
				?>
			><?php echo $s; ?></option>
			<?php endforeach; ?>
			</select>
		<?php endif; ?>
		<input type="text" name="cms_nav_section_new" value=""
		<?php
		if ( ! empty( $sections ) ) :
			?>
			style="display:none"<?php endif; ?> />
		<?php if ( ! empty( $sections ) ) : ?>
		<a href="javascript:" id="cms_nav_add_section"><?php echo __( 'enter new', 'wpml-cms-nav' ); ?></a>
		<?php endif; ?>
		</p>
		<p>
		<label><?php echo __( 'Offsite page address', 'wpml-cms-nav' ); ?> <input type="text" style="width:100%" name="_cms_nav_offsite_url" value="<?php echo esc_attr( $cms_nav_offsite_url ); ?>" /></label>
		</p>
		<?php
	}

	function cms_navigation_js() {
		?>
		<script type="text/javascript">
		var wpml_cms_nav_ajxloaderimg_src = '<?php echo WPML_CMS_NAV_PLUGIN_URL; ?>/res/img/ajax-loader.gif';
		var wpml_cms_nav_ajxloaderimg = '<img src="'+wpml_cms_nav_ajxloaderimg_src+'" alt="loading" width="16" height="16" />';
		addLoadEvent(function(){
					jQuery('#cms_nav_add_section').click(cms_nav_switch_adding_section);
		});
		function cms_nav_switch_adding_section(){
			var cms_nav_section = jQuery("select[name='cms_nav_section']");
			var cms_nav_section_new = jQuery("input[name='cms_nav_section_new']");
			if('none'==cms_nav_section.css('display')){
				cms_nav_section.show();
				cms_nav_section_new.hide();
				cms_nav_section_new.attr('value','');
				jQuery(this).html('<?php echo wpml_cms_nav_js_escape( __( 'enter new', 'wpml-cms-nav' ) ); ?>');
			}else{
				cms_nav_section.hide();
				cms_nav_section_new.show();
				jQuery(this).html('<?php echo wpml_cms_nav_js_escape( __( 'cancel', 'wpml-cms-nav' ) ); ?>');
			}

		}
		</script>
		<?php
	}

	function rewrite_page_link( $url, $page_id ) {
		/** @var $offsite_url_cache wpml_cms_nav_cache */
		$offsite_url_cache = $this->cache['offsite_url_cache'];
		if ( $offsite_url_cache->has_key( $page_id . '_cms_nav_offsite_url' ) ) {
			// get from the cache.
			$offsite_url = $offsite_url_cache->get( $page_id . '_cms_nav_offsite_url' );
			if ( $offsite_url ) {
				$url = $offsite_url;
			}
			return $url;
		}
		$offsite_url = get_post_meta( $page_id, '_cms_nav_offsite_url', true );
		$offsite_url_cache->set( $page_id . '_cms_nav_offsite_url', $offsite_url );
		if ( $offsite_url ) {
			$url = $offsite_url;
		}
		return $url;
	}

	function redirect_offsite_urls( $q ) {
		if ( $q->is_page && ! empty( $q->queried_object_id ) && $offsite_url = get_post_meta( $q->queried_object_id, '_cms_nav_offsite_url', true ) ) {
			wp_redirect( $offsite_url, 301 );
		}
	}

	function icl_dashboard_widget_content() {
		?>

		<div><a href="javascript:void(0)" onclick="jQuery(this).parent().next('.wrapper').slideToggle();" style="display:block; padding:5px; border: 1px solid #eee; margin-bottom:2px; background-color: #F7F7F7;"><?php _e( 'Navigation', 'wpml-cms-nav' ); ?></a></div>

		<div class="wrapper" style="display:none; padding: 5px 10px; border: 1px solid #eee; border-top: 0; margin:-11px 0 2px 0;">
		<p><?php echo __( 'WPML provides advanced menus and navigation to go with your WordPress website, including drop-down menus, breadcrumbs and sidebar navigation.', 'wpml-cms-nav' ); ?></p>
		<p><a class="button secondary" href="<?php echo 'admin.php?page=' . basename( WPML_CMS_NAV_PLUGIN_PATH ) . '/menu/navigation.php'; ?>"><?php echo __( 'Configure navigation', 'wpml-cms-nav' ); ?></a></p>
		</div>
		<?php
	}

	function plugin_action_links( $links, $file ) {
		$this_plugin = basename( WPML_CMS_NAV_PLUGIN_PATH ) . '/plugin.php';
		if ( $file == $this_plugin ) {
			$links[] = '<a href="admin.php?page=' . basename( WPML_CMS_NAV_PLUGIN_PATH ) . '/menu/navigation.php">' .
				__( 'Configure', 'wpml-cms-nav' ) . '</a>';
		}
		return $links;
	}

	function plugin_activate() {
		require_once WPML_CMS_NAV_PLUGIN_PATH . '/inc/cms-navigation-schema.php';
		wpml_cms_nav_default_settings();
		wpml_cms_nav_db_setup();
	}

	function plugin_deactivate() {
		if ( ! empty( $this->cache ) ) {
			$this->clear_cache();
		}
	}

	// Localization
	function plugin_localization() {
		load_plugin_textdomain( 'wpml-cms-nav', false, WPML_CMS_NAV_PLUGIN_FOLDER . '/locale' );
	}

	/**
	 * @param $post
	 * @param $sp
	 *
	 * @return bool
	 */
	private function is_an_ancestor( $post, $sp ) {
		return isset( $post, $post->ancestors ) && in_array( $sp, (array) $post->ancestors, true );
	}

	/**
	 * @param $post
	 * @param $p
	 *
	 * @return bool
	 */
	private function post_ancestor_exists( $post, $p ) {
		return $this->post_has_ancestors( $post->ancestors ) && in_array( $p, $post->ancestors );
	}

	/**
	 * @param $post
	 *
	 * @return bool
	 */
	private function post_has_ancestors( $post ) {
		return isset( $post->ancestors ) && is_array( $post->ancestors ) && count( $post->ancestors ) > 0;
	}

	/**
	 * @return bool
	 */
	private function should_use_cache() {
		return (bool) isset( $this->settings['cache'] ) && $this->settings['cache'] && ! ( defined( 'WPML_CMS_NAV_DISABLE_CACHE' ) && WPML_CMS_NAV_DISABLE_CACHE );
	}
}
