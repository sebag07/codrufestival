<?php /*  Template Name: CODRU Festival 2024  */ ?>
<?php get_header(); ?>

<div class="container-fluid heroContainer p-0 m-0">
    <img class="heroBG" src="/wp-content/themes/Divi-child/images/codru-2024-hero-image.png" alt="">
    <img class="heroLeftLeaves" src="/wp-content/themes/Divi-child/images/L-Leaves-2024.png" alt="">
    <img class="heroRightLeaves" src="/wp-content/themes/Divi-child/images/R-Leaves-2024.png" alt="">
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
                    href="https://www.entertix.ro/bilete/18092/codru-festival-2024-tba-padurea-bistra-timisoara.html"><?php echo get_field('hero_button_text')?></a>
                <h2 class="heroFocusedText heroDescription"><?php echo get_field('hero_section_title')?></h2>
                <p class="anim">
                    <?php echo get_field('hero_section_text')?>
                </p>
                <!-- <a class="heroContentButton mobileButton mobileContentButton anim"
                    href="https://www.entertix.ro/bilete/18092/codru-festival-2024-tba-padurea-bistra-timisoara.html"><?php echo get_field('hero_button_text')?>
                  </a> -->
            </div>
        </div>
    </div>
</div>

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


<!-- <section id="faq">
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
</section> -->

<?php

    $args = array('posts_per_page' => 3, 'orderby' => 'post_date', 'category_name' => 'apeluri-artisti');
    $apeluri_artisti = get_posts($args); 

?>

<?php if(!empty($apeluri_artisti)): ?>
<section id="apeluriartisti">
  <div class="container sectionPadding">
      <h2 class="sectionTitle">APELURI ARTIȘTI</h2>
      <div class="newsContainer row">
          <?php
          foreach ($apeluri_artisti as $post) : {
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

<?php get_footer(); ?>