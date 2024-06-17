<?php /*  Template Name: Artisti  */ ?>
<?php get_header(); ?>

<div class="container-fluid single-page header-padding">

    <h1 class="text-center" style="font-weight: 600;"><?php echo get_the_title(); ?></h1>
    <section>
        <div class="sectionPadding container">
            <div class="artistCardContainer">
                <?php
                $args = array('posts_per_page' => -1, 'orderby' => 'title', 'suppress_filters' => false, 'order' => 'ASC', 'post_type' => 'artist');
                $postslist = get_posts($args);
                foreach ($postslist as $post) :
                    $artistName = get_the_title();
                    $artistPage = get_the_permalink();
                    $artistImage = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail');
                    if ($artistImage) {
                        $imageUrl = $artistImage[0];
                    } else {
                        $imageUrl = "/wp-content/themes/Divi-child/images/logo.png";
                    }
                    ?>
                    <a href='<?php echo $artistPage; ?>' class='artistInnerContainer'>
                        <div class='artistImageContainer'>
                            <img class='artistImg' loading='lazy' src='<?php echo $imageUrl; ?>'>
                            <div class='imageOverlay'></div>
                        </div>
                        <div class='artistContent'>
                            <div class='artistContentBG'></div>
                            <div class='artistContentMeta'>
                            <span class='artistContentName'>
                                <?php echo $artistName; ?>
                            </span>
                                <span class='artistContentDayStage'>
                                    test
                                </span>
                            </div>
                        </div>
                        <div class='artistCardHoverOverlay'></div>

                        <div class='artistCardReadMoreBtn'><?php echo get_field('artists_card_button', 'options'); ?></div>

                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <script>

            jQuery(".artistInnerContainer").hover(
                function () {
                    jQuery(this).find('.artistCardReadMoreBtn').addClass('readMoreBtnHover');
                    jQuery(this).find('.artistContent').addClass('artistContentTop');
                    jQuery(this).find('.artistCardHoverOverlay').addClass('onHoverOverlayOpacity');
                    jQuery(this).addClass('borderOnHover');
                },
                function () {
                    jQuery(this).find('.artistCardReadMoreBtn').removeClass('readMoreBtnHover');
                    jQuery(this).find('.artistContent').removeClass('artistContentTop');
                    jQuery(this).find('.artistCardHoverOverlay').removeClass('onHoverOverlayOpacity');
                    jQuery(this).removeClass('borderOnHover');
                }
            );
        </script>
    </section>
</div>


<?php get_footer(); ?>
