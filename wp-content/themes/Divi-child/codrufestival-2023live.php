<?php /*  Template Name: Codrufestival 2023 LIVE  */ ?>
<?php get_header('codru2023live'); ?>

<div class="container-fluid heroContainer p-0 m-0">
    <img class="heroBG" src="/wp-content/themes/Divi-child/images/codru2023hero.png" alt="">
    <img class="heroLeftLeaves" src="/wp-content/themes/Divi-child/images/L-Leaves.png" alt="">
    <img class="heroRightLeaves" src="/wp-content/themes/Divi-child/images/R-Leaves.png" alt="">
    <div class="heroOverlayGradient"></div>
    <div class="heroContent row">
        <div class="heroContentDiv col-xl-6 col-lg-7 col-md-10 col-10">
        <img class="heroContentImage anim heroContentCodruLogo" src="/wp-content/themes/Divi-child/images/logo.svg" alt="">
        <img class="heroContentImage anim" src="/wp-content/themes/Divi-child/images/locatie.svg" alt="">
        <h3 class="underLocDate">25-27 AUGUST</h3>
        <a class="heroContentButton desktopButton anim" href="https://bilete.codrufestival.ro/">Get Tickets</a>
        <p class="anim"><b><span class="heroFocusedText">Get ready for an unforgettable experience!</b></span><br>
After being ranked in the Top 10 Medium Festivals in Europe, CODRU Festival is returning in 2023, and it's more determined than ever to create an amazing experience that you don't want to miss. <br>
With more stages, international headliners and national artists, immersive art installation, a breathtaking location in the BISTRA Forest, and unique surprises, CODRU Festival is set to be even greater than before!<br>
<span class="heroFocusedText"><b>Are you ready to experience the most stunning sunset with your favorite beats pumping?</b></span><br>
<span class="heroFocusedText"><b>Grab your Tickets NOW.</b></span></p>
        <a class="heroContentButton mobileButton anim" href="https://bilete.codrufestival.ro/">Get Tickets</a>
        </div>
    </div>
</div>
<div id="lineupAnchor" class="firstSectionContainer pb-5 position-relative">
<h2 class="text-center sectionPadding sectionTitle">LINE-UP</h2>
<div class="swiper mySwiper container">
    <div class="swiper-wrapper">
        <?php if ( have_rows( 'headliners_repeater' ) ): ?>

            <?php while( have_rows( 'headliners_repeater' ) ) : the_row(); ?>

                    <?php if( $headlinersImgUrl = get_sub_field( 'headliners_image' ) ) { 

                        $headlinerName = get_sub_field ('headliners_name');

                        echo "<div class='swiper-slide slide'><img src='$headlinersImgUrl' /><span>$headlinerName</span></div>";
                        
                            } ?>

            <?php endwhile; ?>

        <?php endif; ?>  
    </div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>
<div class="swiper-pagination"></div>

  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

  <!-- Initialize Swiper -->
  <script>
    var swiper = new Swiper(".mySwiper", {
      grabCursor: true,
      centeredSlides: true,
      breakpoints: {
    // when window width is >= 320px
    320: {
      slidesPerView: 1,
    },
    991: {
      slidesPerView: 3,
    },
  },
      loop: true,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      pagination: {
        el: ".swiper-pagination",
      },
    });
  </script>

</div>

<div id="artistsAnchor" class="container-fluid sectionPadding">
  <div class="container d-flex">
    <div class="artistsLevel1 pt-3 pb-3">
           <?php
              $args = array('posts_per_page' => -1, 'orderby' => array( 'menu_order' => 'ASC' ), 'post_type' => 'artist', 'category_name' => 'level-1');
              $postslist = get_posts($args);
              foreach ($postslist as $post) : {
                $artistName = get_the_title();
                  echo "<div><h4 class='m-0'>$artistName <img src='/wp-content/themes/Divi-child/images/black-circle.png' /></h4> </div>";
              }
              endforeach;
            ?>

    </div>
    <div class="artistsLevel2 pt-3 pb-3">
           <?php
              $args = array('posts_per_page' => -1, 'orderby' => 'post_date', 'post_type' => 'artist', 'category_name' => 'level-2');
              $postslist = get_posts($args);
              foreach ($postslist as $post) : {
                $artistName = get_the_title();
                  echo "<div><h4 class='m-0'>$artistName  <img src='/wp-content/themes/Divi-child/images/black-circle.png' /></h4></div>";
              }
              endforeach;
            ?>

    </div>
    <div class="artistsLevel3 pt-3 pb-3">
           <?php
              $args = array('posts_per_page' => -1, 'orderby' => 'post_date', 'post_type' => 'artist', 'category_name' => 'level-3');
              $postslist = get_posts($args);
              foreach ($postslist as $post) : {
                $artistName = get_the_title();
                  echo "<div><h4 class='m-0'>$artistName <img src='/wp-content/themes/Divi-child/images/black-circle.png' /></h4> </div>";
              }
              endforeach;
            ?>

    </div>
    <div class="artistsLevel4 pt-3 pb-3">
           <?php
              $args = array('posts_per_page' => -1, 'orderby' => 'post_date', 'post_type' => 'artist', 'category_name' => 'level-4');
              $postslist = get_posts($args);
              foreach ($postslist as $post) : {
                $artistName = get_the_title();
                  echo "<div><h4 class='m-0'>$artistName  <img src='/wp-content/themes/Divi-child/images/black-circle.png' /></h4></div>";
              }
              endforeach;
            ?>

    </div>
    </div>
</div>

<div id="galerieAnchor" class="masonryContainer container-fluid sectionPadding">
<h2 class="text-center sectionPadding sectionTitle">GALLERY</h2>
    <div class="gallery-items gallery-masonry">
    <?php if ( have_rows( 'masonry_section' , 26897 ) ): ?>

    <?php while( have_rows( 'masonry_section', 26897 ) ) : the_row(); ?>

            <?php if( $masonryImage = get_sub_field( 'masonry_image', 26897 ) ) { 

                echo "	<div class='item'><a href='$masonryImage'><img src='$masonryImage'/></a></div>";
                
                    } ?>

    <?php endwhile; ?>


    <?php endif; ?>  
    </div>
</div>

<div id="noutatiAnchor" class="container-fluid sectionPadding">
  <h2 class="sectionTitle">NEWS</h2>
  <div class="newsContainer row">
<?php
            $args = array('posts_per_page' => 3, 'orderby' => 'post_date');
            $postslist = get_posts($args);
            foreach ($postslist as $post) :
                if (is_single ($post == $pinnedPost->ID)) {
                } else {
                if (has_post_thumbnail($post->ID)) {
                    $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                } else {
                    $image[0] = "";
                }
                echo "<div class='homepageNews col-lg-4 col-md-6 col-12'>
            <a href='$post->guid' class='homepageNewsLink'>
            <div class='homepageNewsImage text-center'><img src='$image[0]' alt=''></div>
            <div class='homepageNewsTitle'><h3>$post->post_title</h3><span><img src='/wp-content/themes/Divi-child/images/right-chevron.png' />READ MORE</span></div>
            </a>
        </div>";
            }
            endforeach;
            ?>

  </div>
</div>


<?php get_footer('codru2023'); ?>
