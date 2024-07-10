<?php

require get_template_directory() . '-child/includes/widgets/activities.php';
require get_template_directory() . '-child/includes/widgets/schedule.php';
require get_template_directory() . '-child/includes/widgets/partners.php';

function my_theme_enqueue_styles() { 
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array(), '150');
    wp_enqueue_style( 'activities', get_template_directory_uri() . '-child' . '/assets/css/activities.css');
    wp_enqueue_style( 'partners', get_template_directory_uri() . '-child' . '/assets/css/partners.css');
    wp_enqueue_style( 'magnificPopupCss', get_template_directory_uri() . '-child' . '/assets/css/magnific-popup.min.css');
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

function load_child_scripts(){
    wp_register_script('main', get_template_directory_uri() . "-child" . '/js/main.js', array('jquery'), '4123312', true);
    wp_enqueue_script('main');
    wp_register_script('play-song', get_template_directory_uri() . "-child" . '/js/playsong.js', array('jquery'), true);
    wp_enqueue_script('play-song');
    wp_register_script('freewall', get_template_directory_uri() . "-child" . '/js/freewall.min.js', array('jquery'), true);
    wp_enqueue_script('freewall');
    wp_register_script('imagesloaded', get_template_directory_uri() . "-child" . '/js/imagesloaded.pkgd.min.js', array('jquery'), true);
    wp_enqueue_script('imagesloaded');
    wp_register_script('isotope', get_template_directory_uri() . "-child" . '/js/isotope.pkgd.min.js', array('jquery'), true);
    wp_enqueue_script('isotope');
    wp_register_script('magnificPopup', get_template_directory_uri() . "-child" . '/js/jquery.magnific-popup.min.js', array('jquery'), true);
    wp_enqueue_script('magnificPopup');
}

add_action('wp_enqueue_scripts', 'load_child_scripts'); 

function load_footer_scripts(){
    wp_register_script('util', get_template_directory_uri() . "-child" . '/js/util.js', array('jquery'), true);
    wp_enqueue_script('util');
    wp_register_script('program', get_template_directory_uri() . "-child" . '/js/program.js', array('jquery'), true);
    wp_enqueue_script('program');
}

add_action('wp_footer', 'load_footer_scripts');



function get_menu_with_children($menu_name){
    $navbar_items = wp_get_nav_menu_items($menu_name);
    $menu_with_children = array();
    $child_items = [];

    // pull all child menu items into separate object
    foreach ( (array) $navbar_items as $key => $item) {
        if ($item->menu_item_parent) {
            array_push($child_items, $item);
            unset($navbar_items[$key]);
        }
    }

    // push child items into their parent item in the original object
    foreach ( (array) $navbar_items as $item) {
        foreach ($child_items as $key => $child) {
            if ($child->menu_item_parent == $item->ID) {
                if (!$item->child_items) {
                    $item->child_items = [];
                }

                array_push($item->child_items, $child);
                unset($child_items[$key]);
            }
        }
    }

    return $navbar_items;
}

if( function_exists('acf_add_options_page') ) {
    
    acf_add_options_page(array(
        'page_title'    => 'Options',
        'menu_title'    => 'Options',
        'menu_slug'     => 'general-options',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));
    
    acf_add_options_sub_page(array(
        'page_title'    => 'Partners',
        'menu_title'    => 'Partners',
        'menu_slug'     => 'partners-options',
        'capability'    => 'edit_posts',
        'redirect'      => false,
        'parent_slug'   => 'general-options',
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Newsletter',
        'menu_title'    => 'Newsletter',
        'menu_slug'     => 'newsletter-options',
        'capability'    => 'edit_posts',
        'redirect'      => false,
        'parent_slug'   => 'general-options',
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Stages',
        'menu_title'    => 'Stages',
        'menu_slug'     => 'stages-options',
        'capability'    => 'edit_posts',
        'redirect'      => false,
        'parent_slug'   => 'general-options',
    ));
    
  }

  add_filter( 'the_content', 'wpautop' );

function add_custom_rewrite_rule() {
    add_rewrite_rule(
        '^informatii/([^/]*)/?',
        'index.php?pagename=informatii&filter=$matches[1]',
        'top'
    );
}
add_action('init', 'add_custom_rewrite_rule');

function register_query_vars( $vars ) {
    $vars[] = 'filter';
    return $vars;
}
add_filter('query_vars', 'register_query_vars');


function display_artists_by_level($category_name) {
    $args = array('posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC', 'post_type' => 'artist', 'category_name' => $category_name,'post_status' => 'publish');
    $postslist = get_posts($args);
    foreach ($postslist as $key => $post) {
        setup_postdata($post); // Set up post data for use in the loop (important)
        $artistName = get_the_title($post->ID);
        if($key === array_key_last($postslist)){
            echo "<div class='artists-name'><h4 class='m-0 pb-0' style='color: var(--artist-level-color-secondary);'>$artistName </h4></div>";
        } else {
            echo "<div class='artists-name'><h4 class='m-0 pb-0' style='color: var(--artist-level-color-secondary);'>$artistName</h4></div><div class='artists-bullet'><span style='margin-left: 5px; margin-right: 5px;'>&bull;</span></div>";
        }
    }
    wp_reset_postdata(); // Reset the global post object so that the rest of the page works correctly.
}