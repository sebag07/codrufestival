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
                     src="/wp-content/themes/Divi-child/images/location-to-be-announced.png"
                     alt="Hero Heart Image">
                <img class="heroContentImage anim heroContentCodruLogo display-mobile"
                     src="/wp-content/themes/Divi-child/images/heroheart-ing.png"
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
                        <?php display_artists_by_level('level-2', get_the_ID()); ?>
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