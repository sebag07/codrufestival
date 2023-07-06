<?php
/*
Plugin Name: Divi Masonry Gallery
Plugin URI:  
Description: Masonry Gallery for Divi
Version:     2.0.3
Author:      Destaca Imagen
Author URI:  https://www.destacaimagen.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: dmg-masonry-gallery
Domain Path: /languages
*/


if ( ! function_exists( 'dmg_initialize_extension' ) ):
/**
 * Creates the extension's main class instance.
 *
 * @since 1.0.0
 */
function dmg_initialize_extension() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/DiviMasonryGallery.php';

	load_plugin_textdomain( 'dmg-masonry-gallery', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	//Image sizes
	add_image_size( 'dmg_image_small', 480, 0, false );
	add_image_size( 'dmg_image_medium', 980, 0, false );
	add_image_size( 'dmg_image_large', 1400, 0, false );
	add_image_size( 'dmg_image_extra_large', 1920, 0, false );
}
add_action( 'divi_extensions_init', 'dmg_initialize_extension' );

/**
 * Load scripts
 */
function dmg_load_scripts() {
    wp_enqueue_script( 'dmg-isotope', plugin_dir_url( __FILE__ ) . '/scripts/isotope.pkgd.min.js' , array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script( 'dmg-imagesloaded', plugin_dir_url( __FILE__ ) . '/scripts/imagesloaded.pkgd.min.js' , array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script( 'dmg-lazyload', plugin_dir_url( __FILE__ ) . '/scripts/lazyload.min.js' , array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script( 'magnific-popup', plugin_dir_url( __FILE__ ) . '/scripts/magnific-popup.js', array( 'jquery' ), '1.3.0', true );
    if (! is_admin() ) {
	    wp_enqueue_script( 'dmg-functions', plugin_dir_url( __FILE__ ) . '/scripts/functions.js' , array( 'jquery' ), '2.0.1', true );
    	wp_localize_script( 'dmg-functions', 'dmg_ajax_obj', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		  	'security_nonce' => wp_create_nonce( 'dmg_security_nonce' ),
		));
	}
}
add_action( 'wp_enqueue_scripts', 'dmg_load_scripts', 20 );

/**
 * Load gallery images from the Divi Builder with an ajax call
 */
function dmg_load_gallery() {
	check_ajax_referer( 'dmg_security_nonce', 'security' );
	$images = [];
	if ( isset( $_POST['gallery_ids'] ) && isset( $_POST['images_size'] ) ) {
		//$image_size = sanitize_text_field( $_POST['images_size'] );
		$image_size = 'dmg_image_justify_large';
		$images_ids = sanitize_text_field( $_POST['gallery_ids'] );

		if ( $image_size != '' && $images_ids != '' ) {		
			foreach ( explode( ',', $images_ids ) as $image_id ) {
				$image_data = wp_get_attachment_image_src( $image_id , $image_size );
				if ( $image_data ) {
					$images[] = array('id' => $image_id, 'url' => $image_data[0]);
				}
			}
		}
	}
	
	$categories = [];
	if ( isset( $_POST['gallery_categories'] ) ) {
		$categories_ids = sanitize_text_field( $_POST['gallery_categories'] );
		if ( $categories_ids ) {
			$terms = get_terms( array( 
				'taxonomy' => 'media-categories',
				'include' => $categories_ids,
    			'hide_empty' => false,
			));
			
			if ( count( $terms ) ) {
				foreach ( $terms as $term ) {
					$categories[] = array( 'id' => $term -> term_id, 'name' => $term -> name );
				}
			}
		}
	}

	wp_send_json( array( 'images' => $images, 'categories' => $categories ) );
}

add_action( 'wp_ajax_dmg_load_gallery', 'dmg_load_gallery' );
 
 /**
  * Creates the custom taxonomy for the media attachments
  */
function create_dmg_media_categories() {
 
  $labels = array(
    'name' 				=> _x( 'Media Categories', 'taxonomy general name', 'dmg-masonry-gallery' ),
    'singular_name' 	=> _x( 'Media Category', 'taxonomy singular name', 'dmg-masonry-gallery' ),
    'search_items' 		=>  __( 'Search Categories', 'dmg-masonry-gallery' ),
    'all_items' 		=> __( 'All Categories', 'dmg-masonry-gallery' ),
    'parent_item' 		=> __( 'Parent Category', 'dmg-masonry-gallery' ),
    'parent_item_colon' => __( 'Parent Category:', 'dmg-masonry-gallery' ),
    'edit_item' 		=> __( 'Edit Category', 'dmg-masonry-gallery' ), 
    'update_item' 		=> __( 'Update Category', 'dmg-masonry-gallery' ),
    'add_new_item' 		=> __( 'Add New Media Category', 'dmg-masonry-gallery' ),
    'new_item_name' 	=> __( 'New Media Category Name', 'dmg-masonry-gallery' ),
    'menu_name' 		=> __( 'Media Categories' ),
  );    
 
  register_taxonomy('media-categories',array('attachment'), array(
    'hierarchical' 		=> true,
    'labels' 			=> $labels,
    'show_ui' 			=> true,
    'show_in_rest' 		=> true,
    'show_admin_column' => true,
    'query_var' 		=> true,
  ));
 
}

add_action( 'init', 'create_dmg_media_categories', 0 );

/**
 * Compatibility with Divi 4.10
 *
 * @return array
 */
function dmg_load_gallery_assets( $modules ) {
	array_push( $modules, 'et_pb_gallery' );
	return $modules;
}
add_filter( 'et_required_module_assets', 'dmg_load_gallery_assets' );

endif;