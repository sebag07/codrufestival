<?php /*  Template Name: Codrufestival 2023  */ ?>
<?php get_header('codru2023live'); ?>

<div class="container-fluid heroContainer p-0 m-0">
    <img class="heroBG" src="/wp-content/themes/Divi-child/images/codru2023hero.png" alt="">
    <img class="heroLeftLeaves" src="/wp-content/themes/Divi-child/images/L-Leaves.png" alt="">
    <img class="heroRightLeaves" src="/wp-content/themes/Divi-child/images/R-Leaves.png" alt="">
    <div class="heroOverlayGradient"></div>
    <div class="heroContent row">
        <div class="heroContentDiv col-xl-6 col-lg-8 col-md-10 col-10">
            <img class="heroContentImage anim heroContentCodruLogo" src="/wp-content/themes/Divi-child/images/logo.svg"
                alt="">
            <img class="heroContentImage anim heroContentPadureaBistra"
                src="/wp-content/themes/Divi-child/images/locatie.svg" alt="">
            <h1 class="underLocDate"><?php echo get_field('hero_section_date')?></h1>
            <div class="heroDescription">
                <a class="heroContentButton desktopButton desktopContentButton anim"
                    href="https://bilete.codrufestival.ro/"><?php echo get_field('hero_button_text')?></a>
                <h2 class="heroFocusedText heroDescription"><?php echo get_field('hero_section_title')?></h2>
                <p class="anim">
                    <?php echo get_field('hero_section_text')?>
                </p>
                <a class="heroContentButton mobileButton mobileContentButton anim"
                    href="https://bilete.codrufestival.ro/"><?php echo get_field('hero_button_text')?></a>
            </div>
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
              foreach ($postslist as $key => $post) {
                $artistName = get_the_title();
                if($key === array_key_last($postslist)){
                  echo "<div><h4 class='m-0'>$artistName </h4></div>";
                } else {
                  echo "<div><h4 class='m-0'>$artistName <img src='/wp-content/themes/Divi-child/images/black-circle.png' /></h4></div>";
                }

              }
              ?>

        </div>
        <div class="artistsLevel2 pt-3 pb-3">
            <?php
              $args = array('posts_per_page' => -1, 'orderby' => 'post_date', 'post_type' => 'artist', 'category_name' => 'level-2');
              $postslist = get_posts($args);
              foreach ($postslist as $key => $post) {
                $artistName = get_the_title();
                if($key === array_key_last($postslist)){
                  echo "<div><h4 class='m-0'>$artistName </h4></div>";
                } else {
                  echo "<div><h4 class='m-0'>$artistName <img src='/wp-content/themes/Divi-child/images/black-circle.png' /></h4></div>";
                }

              }
            ?>

        </div>
        <div class="artistsLevel3 pt-3 pb-3">
            <?php
              $args = array('posts_per_page' => -1, 'orderby' => 'post_date', 'post_type' => 'artist', 'category_name' => 'level-3');
              $postslist = get_posts($args);
              foreach ($postslist as $key => $post) {
                $artistName = get_the_title();
                if($key === array_key_last($postslist)){
                  echo "<div><h4 class='m-0'>$artistName </h4></div>";
                } else {
                  echo "<div><h4 class='m-0'>$artistName <img src='/wp-content/themes/Divi-child/images/black-circle.png' /></h4></div>";
                }

              }
            ?>

        </div>
        <div class="artistsLevel4 pt-3 pb-3">
            <?php
              $args = array('posts_per_page' => -1, 'orderby' => 'post_date', 'post_type' => 'artist', 'category_name' => 'level-4');
              $postslist = get_posts($args);
              foreach ($postslist as $key => $post) {
                $artistName = get_the_title();
                if($key === array_key_last($postslist)){
                  echo "<div><h4 class='m-0'>$artistName </h4></div>";
                } else {
                  echo "<div><h4 class='m-0'>$artistName <img src='/wp-content/themes/Divi-child/images/black-circle.png' /></h4></div>";
                }

              }
            ?>

        </div>
    </div>
</div>

<section id="brandCultureAnchor">
    <div class="container-fluid sectionPadding">
        <div class="container">
            <h2 class="sectionTitle">BRAND'S CULTURE</h2>
            <div class="row">
                <?php 
            $options = get_field("brand_culture", "options"); 
            foreach($options as $option): 
            $white = $option['black_text'] == '1' ? 'text-black' : 'text-white';
            ?>
                <a class="col-xl-4 col-lg-6 col-md-6 col-sm-12 brandCultureContainer" data-fslightbox="custom-text"
                    data-class="d-block" href="#<?php echo $option['title']; ?>" class="col right-col">
                    <div class=" <?php echo $white; ?>">
                        <img class="brandCultureImage" src="<?php echo $option['image']; ?>">
                        <h4 class="brandCultureTitle"><?php echo $option['title']; ?></h4>
                        <h5 class="brandCultureValues"><?php echo $option['keywords']; ?></h5>
                    </div>
                </a>
                <div id="<?php echo $option['title']; ?>" class="d-none">
                    <div class="lightboxBrandCultureBox">
                        <h4 class="csh">
                            <?php echo $option['title']; ?>
                        </h4>
                        <h5 class="brandCultureValues"><?php echo $option['keywords']; ?></h5>
                        <p><?php echo $option['description']; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<section id="galerieAnchor">
    <div class="masonryContainer container-fluid sectionPadding">
        <h2 class="text-center sectionPadding sectionTitle">GALERIE</h2>
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
</section>

<section id="faq">
    <div class="container-fluid faq p-relative sectionPadding">
        <h2 class="sectionTitle">FAQ</h2>
        <div class="container">
            <div class="accordion" id="accordionExample">
                <?php 
            $questions = get_field("faq_repeater", "options"); 
            $index = 1;
            foreach($questions as $question): ?>
                <div class="card">
                    <div class="card-head" id="<?php echo "heading" . $index; ?>">
                        <h2 class="mb-0 collapsed" data-toggle="collapse"
                            data-target="<?php echo "#collapse" . $index; ?>" aria-expanded="false"
                            aria-controls="<?php echo "collapse" . $index; ?>">
                            <?php echo $question['question']; ?>
                        </h2>
                    </div>

                    <div id="<?php echo "collapse" . $index; ?>" class="collapse"
                        aria-labelledby="<?php echo "collapse" . $index; ?>" data-parent="#accordionExample">
                        <div class="card-body">
                            <?php echo $question['answer']; ?>
                        </div>
                    </div>
                </div>
                <?php $index++; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<section id="noutatiAnchor">
    <div class="container-fluid sectionPadding">
        <h2 class="sectionTitle">NOUTĂȚI</h2>
        <div class="newsContainer row">
            <?php
            $args = array('posts_per_page' => 3, 'orderby' => 'post_date', 'category_name' => 'noutati');
            $postslist = get_posts($args);
            foreach ($postslist as $post) : {
              $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
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
</section>

<script src="/wp-content/themes/Divi-child/js/fslightbox.js"></script>
<?php get_footer('codru2023live'); ?>