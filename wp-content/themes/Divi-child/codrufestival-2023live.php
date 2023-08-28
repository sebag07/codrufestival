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
<!--<div id="lineupAnchor" class="firstSectionContainer pb-5 position-relative">-->
<!--    <h2 class="text-center sectionPadding sectionTitle">LINE-UP</h2>-->


<!--</div>-->

<div id="lineupAnchor" class="container-fluid sectionPadding">
    <h2 class="text-center sectionPadding sectionTitle">LINE-UP</h2>
    <div class="container line-up-container d-flex">
        <div class="artistsLevel1 pt-3 pb-3">
            <?php
              $args = array('posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC', 'post_type' => 'artist', 'category_name' => 'level-1');
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
              $args = array('posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC', 'post_type' => 'artist', 'category_name' => 'level-3');
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
              $args = array('posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC', 'post_type' => 'artist', 'category_name' => 'level-4');
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

        <div class="artistsLevel5 pt-3 pb-3">
            <?php
              $args = array('posts_per_page' => -1, 'orderby' => 'post_date', 'order' => 'ASC', 'post_type' => 'artist', 'category_name' => 'level-5');
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

        <div class="artistsLevel6 pt-3 pb-3">
            <?php
              $args = array('posts_per_page' => -1, 'orderby' => 'post_date', 'order' => 'ASC', 'post_type' => 'artist', 'category_name' => 'level-6');
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
        
        <div class="col-lg-12 text-center pt-4">
          <a class="heroContentButton desktopButton desktopContentButton" href="https://codrufestival.ro/program">Program</a>
        </div>

    </div>

    <div class="container">
      <div class="masonry-filter-container">
          <ul class="masonry-filter-list">
              <li data-filter="all" class="active"><?php echo get_field('all_artists', 'options'); ?></li>
              <li data-filter="ziua-1"><?php echo get_field('day_one', 'options'); ?></li>
              <li data-filter="ziua-2"><?php echo get_field('day_two', 'options'); ?></li>
              <li data-filter="ziua-3"><?php echo get_field('day_three', 'options'); ?></li>
          </ul>
      </div>
    </div>

    <div class="gallery-items gallery-masonry artist-gallery">

        <?php
              $args = array('posts_per_page' => -1, 'orderby' => array( 'post_date' => 'ASC' ), 'post_type' => 'artist', 'category_name' => 'level-1');
              $postslist = get_posts($args);
              foreach ($postslist as $post) : {
                $artistImageURL = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                $artistURL = get_the_permalink();
                $artistTitle = get_the_title();

                $day_category = "";
                $term_list = wp_get_post_terms($post->ID, 'category', ['fields' => 'all']);
                foreach($term_list as $term) {
                  if( get_post_meta($post->ID, '_yoast_wpseo_primary_category',true) !== $term->term_id ) {
                    $day_category = $term->slug;
                    $day_category = str_replace("-en", "", $day_category);
                  } 
                }

                if( !empty(get_the_post_thumbnail()) ) {
                  echo "<a class='item all artistItem $day_category' href='$artistURL' data-day='$day_category'><img src='$artistImageURL[0]' alt=''><span>$artistTitle</span></a>";
                };

              }
              endforeach;
              ?>
        <?php
              $args = array('posts_per_page' => -1, 'orderby' => 'post_date', 'post_type' => 'artist', 'category_name' => 'level-2');
              $postslist = get_posts($args);
              foreach ($postslist as $post) : {
                $artistImageURL = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                $artistURL = get_the_permalink();
                $artistTitle = get_the_title();

                $day_category = "";
                $term_list = wp_get_post_terms($post->ID, 'category', ['fields' => 'all']);
                foreach($term_list as $term) {
                  if( get_post_meta($post->ID, '_yoast_wpseo_primary_category',true) !== $term->term_id ) {
                    $day_category = $term->slug;
                    $day_category = str_replace("-en", "", $day_category);
                  } 
                }

                if( !empty(get_the_post_thumbnail()) ) {
                  echo "<a class='item all artistItem $day_category' href='$artistURL' data-day='$day_category'><img src='$artistImageURL[0]' alt=''><span>$artistTitle</span></a>";
                } else {
                  $thumbnailURL = get_template_directory_uri() . "-child/images/codru-2023.png";
                  echo "<a class='item all artistItem $day_category' href='$artistURL' data-day='$day_category'><img src='$thumbnailURL' alt=''><span>$artistTitle</span></a>";
                };

              }
              endforeach;

              ?>
        <?php
              $args = array('posts_per_page' => -1, 'orderby' => 'post_date', 'post_type' => 'artist', 'category_name' => 'level-3');
              $postslist = get_posts($args);
              foreach ($postslist as $post) : {
                $artistImageURL = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                $artistURL = get_the_permalink();
                $artistTitle = get_the_title();

                $day_category = "";
                $term_list = wp_get_post_terms($post->ID, 'category', ['fields' => 'all']);
                foreach($term_list as $term) {
                  if( get_post_meta($post->ID, '_yoast_wpseo_primary_category',true) !== $term->term_id ) {
                    $day_category = $term->slug;
                    $day_category = str_replace("-en", "", $day_category);
                  } 
                }

                if( !empty(get_the_post_thumbnail()) ) {
                  echo "<a class='item all artistItem $day_category' href='$artistURL' data-day='$day_category'><img src='$artistImageURL[0]' alt=''><span>$artistTitle</span></a>";
                } else {
                  $thumbnailURL = get_template_directory_uri() . "-child/images/codru-2023.png";
                  echo "<a class='item all artistItem $day_category' href='$artistURL' data-day='$day_category'><img src='$thumbnailURL' alt=''><span>$artistTitle</span></a>";
                };

              }
              endforeach;

              ?>
        <?php
              $args = array('posts_per_page' => -1, 'orderby' => 'post_date', 'post_type' => 'artist', 'category_name' => 'level-4');
              $postslist = get_posts($args);
              foreach ($postslist as $post) : {
                $artistImageURL = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                $artistURL = get_the_permalink();
                $artistTitle = get_the_title();

                $day_category = "";
                $term_list = wp_get_post_terms($post->ID, 'category', ['fields' => 'all']);
                foreach($term_list as $term) {
                  if( get_post_meta($post->ID, '_yoast_wpseo_primary_category',true) !== $term->term_id ) {
                    $day_category = $term->slug;
                    $day_category = str_replace("-en", "", $day_category);
                  } 
                }

                if( !empty(get_the_post_thumbnail()) ) {
                  echo "<a class='item all artistItem $day_category' href='$artistURL' data-day='$day_category'><img src='$artistImageURL[0]' alt=''><span>$artistTitle</span></a>";
                } else {
                  $thumbnailURL = get_template_directory_uri() . "-child/images/codru-2023.png";
                  echo "<a class='item all artistItem $day_category' href='$artistURL' data-day='$day_category'><img src='$thumbnailURL' alt=''><span>$artistTitle</span></a>";
                };

                }
              endforeach;
              ?>
                      <?php
              $args = array('posts_per_page' => -1, 'orderby' => 'post_date', 'post_type' => 'artist', 'category_name' => 'level-5');
              $postslist = get_posts($args);
              foreach ($postslist as $post) : {
                $artistImageURL = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                $artistURL = get_the_permalink();
                $artistTitle = get_the_title();

                $day_category = "";
                $term_list = wp_get_post_terms($post->ID, 'category', ['fields' => 'all']);
                foreach($term_list as $term) {
                  if( get_post_meta($post->ID, '_yoast_wpseo_primary_category',true) !== $term->term_id ) {
                    $day_category = $term->slug;
                    $day_category = str_replace("-en", "", $day_category);
                  } 
                }

                if( !empty(get_the_post_thumbnail()) ) {
                  echo "<a class='item all artistItem $day_category' href='$artistURL' data-day='$day_category'><img src='$artistImageURL[0]' alt=''><span>$artistTitle</span></a>";
                } else {
                  $thumbnailURL = get_template_directory_uri() . "-child/images/codru-2023.png";
                  echo "<a class='item all artistItem $day_category' href='$artistURL' data-day='$day_category'><img src='$thumbnailURL' alt=''><span>$artistTitle</span></a>";
                };

                }
              endforeach;
              ?>

            <?php
              $args = array('posts_per_page' => -1, 'orderby' => 'post_date', 'post_type' => 'artist', 'category_name' => 'level-6');
              $postslist = get_posts($args);
              foreach ($postslist as $post) : {
                $artistImageURL = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                $artistURL = get_the_permalink();
                $artistTitle = get_the_title();

                $day_category = "";
                $term_list = wp_get_post_terms($post->ID, 'category', ['fields' => 'all']);
                foreach($term_list as $term) {
                  if( get_post_meta($post->ID, '_yoast_wpseo_primary_category',true) !== $term->term_id ) {
                    $day_category = $term->slug;
                    $day_category = str_replace("-en", "", $day_category);
                  } 
                }

                if( !empty(get_the_post_thumbnail()) ) {
                  echo "<a class='item all artistItem $day_category' href='$artistURL' data-day='$day_category'><img src='$artistImageURL[0]' alt=''><span>$artistTitle</span></a>";
                } else {
                  $thumbnailURL = get_template_directory_uri() . "-child/images/codru-2023.png";
                  echo "<a class='item all artistItem $day_category' href='$artistURL' data-day='$day_category'><img src='$thumbnailURL' alt=''><span>$artistTitle</span></a>";
                };

                }
              endforeach;
              ?>

    </div>
</div>

<section id="gascazurli">
    <div class="container sectionPadding">
        <h2 class="sectionTitle">Invitați Speciali Gașca Zurli <br>27 august 16:30</h2>
        <div class="newsContainer row">
            <img src="<?php echo get_stylesheet_directory_uri() . "/images/gasca-zurli.png"; ?>">
        </div>
    </div>
</section>

<section id="brandCultureAnchor">
    <div class="container-fluid sectionPadding">
        <div class="container">
            <h2 class="sectionTitle"><?php echo get_field('values_section_title', 'options'); ?></h2>
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
                <div id="<?php echo $option['title']; ?>" style="display: none;">
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
        <h2 class="text-center sectionPadding sectionTitle"><?php echo get_field('gallery_title', 'options'); ?></h2>
        <div class="gallery-items gallery-masonry image-gallery">
            <?php if ( have_rows( 'masonry_section' , 26897 ) ): ?>

            <?php while( have_rows( 'masonry_section', 26897 ) ) : the_row(); ?>

            <?php if( $masonryImage = get_sub_field( 'masonry_image', 26897 ) ) { 

                echo "	<div class='item gallery-image'><a href='$masonryImage'><img src='$masonryImage'/></a></div>";
                
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
                <div class="accordion-item">
                    <h2 class="accordion-header" id="<?php echo "heading" . $index; ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="<?php echo "#collapse" . $index; ?>" aria-expanded="false"
                            aria-controls="<?php echo "collapse" . $index; ?>">
                            <?php echo $question['question']; ?>
                        </button>
                    </h2>
                    <div id="<?php echo "collapse" . $index; ?>" class="accordion-collapse collapse"
                        aria-labelledby="<?php echo "heading" . $index; ?>" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
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

<?php

    $args = array('posts_per_page' => 3, 'orderby' => 'post_date', 'category_name' => 'povestea-codru');
    $povesticodru = get_posts($args); 

?>

<?php if(!empty($povesticodru)): ?>
  <section id="povesteaCodru">
      <div class="container sectionPadding">
          <h2 class="sectionTitle"><?php echo get_field('codru_story_title', 'options'); ?></h2>
          <div class="newsContainer row">
              <?php
              foreach ($povesticodru as $post) : {
                $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                $postURL = get_the_permalink($post->ID);
                $read_more = get_field('news_read_more', 'options');
                  echo "<div class='homepageNews col-lg-4 col-md-6 col-12'>
              <a href='$postURL' class='homepageNewsLink'>
              <div class='homepageNewsImage text-center'><img src='$image[0]' alt=''></div>
              <div class='homepageNewsTitle'><h3>$post->post_title</h3><span><img src='/wp-content/themes/Divi-child/images/right-chevron.png' />$read_more</span></div>
              </a>
          </div>";
              }
              endforeach;
              ?>

          </div>
      </div>
  </section>
<?php endif; ?>

<section id="noutatiAnchor">
    <div class="container sectionPadding">
        <h2 class="sectionTitle"><?php echo get_field('news_title', 'options'); ?></h2>
        <div class="newsContainer row">
            <?php
            $args = array('posts_per_page' => 3, 'orderby' => 'post_date', 'category_name' => 'noutati');
            $postslist = get_posts($args);
            foreach ($postslist as $post) : {
              $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
              $postURL = get_the_permalink($post->ID);
              $read_more = get_field('news_read_more', 'options');
                echo "<div class='homepageNews col-lg-4 col-md-6 col-12'>
            <a href='$postURL' class='homepageNewsLink'>
            <div class='homepageNewsImage text-center'><img src='$image[0]' alt=''></div>
            <div class='homepageNewsTitle'><h3>$post->post_title</h3><span><img src='/wp-content/themes/Divi-child/images/right-chevron.png' />$read_more</span></div>
            </a>
        </div>";
            }
            endforeach;
            ?>

        </div>
    </div>
</section>

<?php get_footer('codru2023live'); ?>