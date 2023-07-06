<?php

/**
 * Header file for the Twenty Twenty WordPress default theme.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

?>
<!DOCTYPE html>

<html class="no-js" <?php language_attributes(); ?>>

<head>

  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css?family=Quicksand:400,600,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>

  <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

  <?php
  wp_body_open();
  ?>

  <div class="site-mobile-menu site-navbar-target">
    <div class="site-mobile-menu-header">
      <div class="site-mobile-menu-close mt-3">
        <span class="icon-close2 js-menu-toggle"></span>
      </div>
    </div>
    <div class="site-mobile-menu-body"></div>
  </div> <!-- .site-mobile-menu -->

    <div class="site-navbar site-navbar-target js-sticky-header">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-4">
            <div class="my-0 site-logo site-header-logo"><a href="/home"><img src="/wp-content/themes/quicklegal-child/assets/images/quicklegal-logo.png" alt=""></a></div>
          </div>
          <div class="col-8">
            <nav class="site-navigation text-right" role="navigation">
              <div class="container py-3">
                <div class="d-inline-block d-lg-none ml-md-0 mr-auto py-3"><a href="#" class="site-menu-toggle js-menu-toggle"><span class="icon-menu h3"></span></a></div>

                <ul class="site-menu main-menu js-clone-nav d-none d-lg-block">
                  <?php
                  $menu = get_menu_with_children("2023Menu");

                  foreach ($menu as $item) {
                    if (isset($item->child_items)) {
                      echo "<li class='has-children'><a href='$item->url' class='nav-link'>$item->title</a>
                            <ul class='dropdown arrow-top'>";
                      foreach ($item->child_items as $child_item) {
                        echo "<li><a href='$child_item->url' class='nav-link'>$child_item->title</a></li>";
                      }
                      echo "</ul>
                            </li>";
                    } else {
                      echo "<li><a href='$item->url' class='nav-link'>$item->title</a></li>";
                    }
                  }


                  ?>
                  <a class="callALawyerButton" href="#">Autentifică-te</a></li>
                </ul>

              </div>

          </div>
          </nav>
        </div>
      </div>
    </div>
  </div>
  </div>


</body>