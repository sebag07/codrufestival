<?php /*  Template Name: CODRU Festival 2024  */ ?>
<?php get_header(); ?>

    <div class="container-fluid heroContainer p-0 m-0">
        <img class="heroBG" src="/wp-content/themes/Divi-child/images/BG-2.png" alt="">
        <img class="heroLeftLeaves" src="/wp-content/themes/Divi-child/images/b-left.png" alt="">
        <img class="heroRightLeaves" src="/wp-content/themes/Divi-child/images/b-right.png" alt="">
        <div class="heroOverlayGradient"></div>
        <div class="heroContent row">
            <div class="heroContentDiv col-xl-12 col-lg-12 col-md-12 col-12">
                <img class="heroContentImage anim heroContentCodruLogo display-desktop"
                     src="/wp-content/themes/Divi-child/images/inima-gradina-zoo.png"
                     alt="Hero Heart Image">
                <img class="heroContentImage anim heroContentCodruLogo display-mobile"
                     src="/wp-content/themes/Divi-child/images/inima-gradina-zoo-ing.png"
                     alt="Hero Heart Image with ING icon">
                <div class="heroDescription">
                    <a class="heroContentButton desktopButton desktopContentButton anim"
                       href="https://bilete.codrufestival.ro/"
                       target="_blank"><?php echo get_field('hero_button_text') ?></a>
                    <h2 class="heroFocusedText heroDescription"><?php echo get_field('hero_section_title') ?></h2>
                    <p class="anim">
                        <?php echo get_field('hero_section_text') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <section id="lineup">
        <div class="container">
            <div class="container-fluid sectionPadding">
                <div class="col-12 text-center">
                    <div class="artistsLevel1 pt-3 pb-3">
                        <?php display_artists_by_level('level-1', get_the_ID()); ?>
                    </div>
                    <div class="artistsLevel2 pt-3 pb-3">
                        <?php display_artists_by_level('level-2', get_the_ID(), 'english'); ?>
                    </div>
                    <div class="artistsLevel2 pt-3 pb-3">
                        <div class='artists-name special-show-tag'><h4 class='m-0 pb-0'>SPECIAL SHOW </h4></div>
                        <?php display_artists_by_level('level-2', get_the_ID(),'roman', 'special'); ?>
                    </div>
                    <div class="artistsLevel2 pt-3 pb-3">
                        <?php display_artists_by_level('level-2', get_the_ID(), 'roman'); ?>
                    </div>
                    <div class="artistsLevel3 pt-3 pb-3">
                        <?php display_artists_by_level('level-3', get_the_ID()); ?>
                    </div>
                    <div class="artistsLevel4 pt-3 pb-3">
                        <?php display_artists_by_level('level-4', get_the_ID()); ?>
                    </div>
                    <div class="artistsLevel5 pt-3 pb-3">
                        <?php display_artists_by_level('level-5', get_the_ID()); ?>
                    </div>
                    <div class="artistsLevel6 pt-3 pb-3">
                        <?php display_artists_by_level('level-6', get_the_ID()); ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 pt-5 text-align-center general-button-container">
                    <a class="codru-general-button"
                       href="<?php echo get_field('see_all_artists_button_link') ?>"
                       target="_blank"><?php echo get_field('see_all_artists_button') ?></a>
                </div>
    </section>


<?php  if( have_rows('ticket_cards_repeater', 'options') ): ?>
<section id="tickets-sale-section">
    <div class="sectionPadding container">
        <div class="row">
        <?php

            while( have_rows('ticket_cards_repeater', 'options') ) : the_row();

                $cardDescription = get_sub_field('description', 'options');
                $cardPrice = get_sub_field('price', 'options');
                $cardButtonURL = get_sub_field('button_url', 'options');
                $cardButtonText = get_sub_field('button_text', 'options');

            echo "
            <div class='col-lg-4 col-xl-3 col-md-4 col-12 ticket-card'>
                <div class='card-inner'>
                <svg id='ticket-image' enable-background='new 0 0 128 128' height='512' viewBox='0 0 128 128' width='512' xmlns='http://www.w3.org/2000/svg'><path d='m121.5 64c1 0 1.7-.8 1.8-1.7v-21.8c0-1-.8-1.7-1.8-1.8h-4.8l-2.1-14.3c-.1-1-1-1.6-2-1.5l-27.1 4h-.1l-79.2 11.9s-1.5.3-1.5 1.7v21.7c0 1 .8 1.7 1.7 1.8 4.4 0 7.9 3.5 7.9 7.9s-3.5 7.9-7.9 7.9c-1 0-1.7.8-1.8 1.7v21.7c0 1 .8 1.7 1.8 1.8h115c1 0 1.7-.8 1.8-1.8v-21.6c0-1-.8-1.7-1.7-1.8-4.4 0-7.9-3.5-7.9-7.9s3.5-7.9 7.9-7.9zm-37.3-33.3.7 4.7c.1.9.9 1.5 1.7 1.5h.3c1-.1 1.6-1 1.5-2l-.7-4.7 23.7-3.5 1.8 12.1h-83.1zm35.6 52.5v18.4h-25.9v-4.8c0-1-.8-1.8-1.8-1.8s-1.8.8-1.8 1.8v4.8h-82v-18.4c6.2-1 10.5-6.8 9.5-13-.8-4.9-4.6-8.8-9.5-9.5v-18.4h82.1v4.7c0 1 .8 1.8 1.8 1.8s1.8-.8 1.8-1.8v-4.8h25.9v18.4c-6.2 1-10.5 6.8-9.5 13 .6 5 4.4 8.8 9.4 9.6z'/><path d='m92.1 74.6c-1 0-1.7.8-1.8 1.7v11.6c0 1 .8 1.8 1.8 1.8s1.8-.8 1.8-1.8v-11.5c0-1-.8-1.8-1.8-1.8z'/><path d='m92.1 54.2c-1 0-1.7.8-1.8 1.7v11.6c0 1 .8 1.8 1.8 1.8s1.8-.8 1.8-1.8v-11.6c0-.9-.8-1.7-1.8-1.7z'/><path d='m27.3 57h21.7c1 0 1.8-.8 1.8-1.8s-.8-1.7-1.8-1.7h-21.7c-1 0-1.8.8-1.8 1.8s.8 1.7 1.8 1.7z'/><path d='m70.7 64.6h-43.4c-1 0-1.8.8-1.8 1.8s.8 1.8 1.8 1.8h43.4c1 0 1.8-.8 1.8-1.8s-.8-1.8-1.8-1.8z'/><path d='m70.7 75.7h-43.4c-1 0-1.8.8-1.8 1.8s.8 1.8 1.8 1.8h43.4c1 0 1.8-.8 1.8-1.8s-.8-1.8-1.8-1.8z'/><path d='m70.7 86.8h-43.4c-1 0-1.8.8-1.8 1.8s.8 1.8 1.8 1.8h43.4c1 0 1.8-.8 1.8-1.8s-.8-1.8-1.8-1.8z'/></svg>
                    <div class='card-description'>
                        <p>$cardDescription</p>
                    </div>
                    <div class='info-container'>
                        <div class='card-price-info'>
                            <p>De la:</p>
                            <p class='card-price'>$cardPrice</p>
                        </div>
                        <div class='card-button'>
                            <a href='$cardButtonURL'>$cardButtonText</a>
                        </div>
                    </div>
                </div>
            </div>
            ";

            endwhile;

            ?>
        </div>
    </div>
</section>

<?php endif; ?>

<?php
wp_reset_postdata();
$post_id = get_the_ID(); // Get current post ID
?>

<?php if (have_rows('content-image-repeater', $post_id)): ?>
    <section id="homepage-info-section" style="overflow-x:hidden" class="dark-background">
        <div class="sectionPadding container homepage-info-section">
            <?php
            $count = 0;
            while (have_rows('content-image-repeater', $post_id)) : the_row();
                if ($count % 2 == 0) {
                    $class_name = "even";
                    $col_order = "order-md-0 order-1";
                } else {
                    $class_name = "odd";
                    $col_order = "order-md-1 order-1";
                }
                $repeaterTitle = get_sub_field('title');
                $repeaterContent = get_sub_field('content');
                $repeaterButtonURL = get_sub_field('button_url');
                $repeaterButtonText = get_sub_field('button_text');
                $repeaterImage = get_sub_field('image');
                $imageBGColor = get_sub_field('image_background_hex');
                ?>
                <div class="row pt-5 pb-5 <?php echo $class_name ?>">
                    <div class="col-md-6 align-items-start <?php echo $col_order ?> justify-content-center d-flex flex-column homepage-info-container">
                        <h2 class="homepage-info-title mb-4"><?php echo $repeaterTitle ?></h2>
                        <span class="homepage-info-content mb-4"><?php echo $repeaterContent ?></span>
                        <a class="homepage-info-button codru-general-button" href="<?php echo $repeaterButtonURL ?>"
                           target="_blank"><?php echo $repeaterButtonText ?></a>
                    </div>
                    <div class="homepage-info-section-image-container col-md-6 my-md-auto p-relative z-1 mb-5">
                        <img class="homepage-info-section-image" src="<?php echo $repeaterImage ?>" alt="Lineup">
                        <div class="homepage-info-section-image-underlay"
                             style="background-color:<?php echo $imageBGColor ?>"></div>
                    </div>
                </div>
                <?php
                $count++;
            endwhile;
            ?>
        </div>
    </section>
<?php endif; ?>

    <section id="brandCultureAnchor">
        <div class="container-fluid sectionPadding">
            <div class="container">
                <h2 class="sectionTitle"><?php echo get_field('values_section_title', 'options'); ?></h2>
                <div class="row">
                    <?php
                    $options = get_field("brand_culture", "options");
                    foreach ($options as $option):
                        $white = $option['black_text'] == '1' ? 'text-black' : 'text-white';
                        ?>
                        <a class="col-xl-4 col-lg-4 col-md-6 col-sm-6 brandCultureContainer"
                           data-fslightbox="custom-text"
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
                    echo "<div class='homepageNews col-lg-4 col-md-6 col-sm-6 col-12'>
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

<?php

$args = array('posts_per_page' => 3, 'orderby' => 'post_date', 'category_name' => 'povestea-codru');
$povesticodru = get_posts($args);

?>

<?php if (!empty($povesticodru)): ?>
    <section id="povesteaCodru">
        <div class="container sectionPadding">
            <h2 class="sectionTitle"><?php echo get_field('codru_story_title', 'options'); ?></h2>
            <div class="newsContainer row">
                <?php
                foreach ($povesticodru as $post) : {
                    $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                    $postURL = get_the_permalink($post->ID);
                    $read_more = get_field('news_read_more', 'options');
                    echo "<div class='homepageNews col-lg-4 col-md-6 col-sm-6 col-12'>
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

$args = array('posts_per_page' => 3, 'orderby' => 'post_date', 'category_name' => 'apeluri-artisti');
$apeluri_artisti = get_posts($args);

?>

<?php if (!empty($apeluri_artisti)): ?>
    <section id="apeluriartisti">
        <div class="container sectionPadding">
            <h2 class="sectionTitle">APELURI ARTIȘTI</h2>
            <div class="newsContainer row">
                <?php
                foreach ($apeluri_artisti as $post) : {
                    $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                    $postURL = get_the_permalink($post->ID);
                    $read_more = get_field('news_read_more', 'options');
                    echo "<div class='homepageNews col-lg-4 col-md-6 col-sm-6 col-12'>
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

<?php get_footer(); ?>