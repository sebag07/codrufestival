<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,600,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,60,700,800,900" rel="stylesheet">

    <link rel="icon" href="/wp-content/themes/Divi-child/images/logo.svg">
    <link rel="stylesheet" href="/wp-content/themes/Divi-child/css/owl.carousel.min.css">
    
       <link rel="stylesheet" href="/wp-content/themes/Divi-child/fonts/style.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.7/ScrollMagic.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.0.1/TweenLite.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.0.1/TimelineMax.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.7/plugins/animation.gsap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.0/TweenMax.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/wp-content/themes/Divi-child/css/bootstrap.min.css">
    
    <!-- Style -->
    <link rel="stylesheet" href="/wp-content/themes/Divi-child/styleCodru2023.css">
    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"
  />
  <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

    <title>Codrufestival 2023</title>
  </head>
  <body <?php post_class();?>>
  <div class="headerHalfCircle"><div class="text-center"><img src="/wp-content/themes/Divi-child/images/soare.png" alt=""></div></div>


    <div class="site-mobile-menu site-navbar-target">
      <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close">
          <span class="icon-close2 js-menu-toggle"></span>
        </div>
      </div>
      <div class="site-mobile-menu-body">
      </div>
    </div> <!-- .site-mobile-menu -->
    

    <!-- NAVBAR -->
    <header class="site-navbar">
      <div class="container-fluid row m-0 justify-content-center">
          <!-- <div class="site-logo col-lg-5 col-10"><a href="/"><img src="/wp-content/themes/Divi-child/images/logocodru.png" alt=""></a></div> -->
          <div class="mx-auto site-navigation right-cta-menu d-flex aligin-items-center leftMenu justify-content-left col-lg-4 col-2">
            <ul class="site-menu js-clone-nav d-none d-xl-block ml-0 pl-0">
              <?php
                  $menu = get_menu_with_children("Codru2023LeftMenu");
                  $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
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
            </ul>
            <a href="#" class="site-menu-toggle js-menu-toggle d-inline-block d-xl-none mt-lg-2 ml-3"><span class="icon-menu h3 m-0 p-0 mt-2"></span></a>
          </div>

          <div class="site-logo col-lg-4 col-10"><a href="/"><img src="/wp-content/themes/Divi-child/images/logo.svg" alt=""></a></div>



          <div class="mx-auto site-navigation right-cta-menu d-flex aligin-items-center rightMenu justify-content-right col-lg-4 col-2">
            <ul class="site-menu js-clone-nav d-none d-xl-block ml-0 pl-0">
              <?php
                  $menu = get_menu_with_children("Codru2023RightMenu");
                  $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
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
            <span class="headerSocials">
                <a href="https://www.facebook.com/codrufestival" target="_blank"><img src="/wp-content/themes/Divi-child/images/facebookcodru.svg" alt=""></a>
                <a href="https://www.instagram.com/codrufestival/" target="_blank"><img src="/wp-content/themes/Divi-child/images/instagramcodru.svg" alt=""></a>
                <a href="https://www.linkedin.com/company/codrufestival/" target="_blank"><img src="/wp-content/themes/Divi-child/images/linkedincodru.svg" alt=""></a>
                <a href="https://www.youtube.com/@codrufestival" target="_blank"><img src="/wp-content/themes/Divi-child/images/youtubecodru.svg" alt=""></a>
            </span>
                <a class="heroButtonMenu" href="https://www.entertix.ro/evenimente/12761/codru-festival-2023-25-27-august-2023-tba-timis.html" target="_blank">Pre-register NOW</a>
            </ul>
            <a href="#" class="site-menu-toggle js-menu-toggle d-inline-block d-xl-none mt-lg-2 ml-3"><span class="icon-menu h3 m-0 p-0 mt-2"></span></a>
          </div>

    </header>