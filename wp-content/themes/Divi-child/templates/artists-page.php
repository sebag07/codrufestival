<?php /*  Template Name: Artisti  */ ?>
<?php get_header(); ?>

<div class="container-fluid single-page artists-page header-padding">
    <h1 class="text-center sectionTitle" style="font-weight: 600;"><?php echo get_the_title(); ?></h1>
    <section>
        <div class="sectionPadding container">
        <span id="category-filter">
            <label class="activitiesCheckbox activeCategory" for="all"><input class="allcat" id="all" type="checkbox" value="all"><span>Toate</span></label>
            <label class="activitiesCheckbox" for="ziua1"><input class="ziua1" id="ziua1" type="checkbox" value="ziua1"><span>Ziua 1</span></label>
            <label class="activitiesCheckbox" for="ziua2"><input class="ziua2" id="ziua2" type="checkbox" value="ziua2"><span>Ziua 2</span></label>
            <label class="activitiesCheckbox" for="ziua3"><input class="ziua3" id="ziua3" type="checkbox" value="ziua3"><span>Ziua 3</span></label>

        </span>
            <div class="artistCardContainer">
                <?php
                $args = array('posts_per_page' => -1, 'orderby' => 'date', 'suppress_filters' => false, 'order' => 'ASC', 'post_type' => 'artist');
                $postslist = get_posts($args);
                foreach ($postslist as $post) :
                    $artistName = get_the_title();
                    $artistPage = get_the_permalink();
                    $artistImage = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'medium_large');
                    if ($artistImage) {
                        $imageUrl = $artistImage[0];
                    } else {
                        $imageUrl = "/wp-content/themes/Divi-child/images/artist-placeholder.png";
                    };
                    $categories = get_the_category($post->ID);

                    foreach ($categories as $cat) :

                    ?>
                    <a href='<?php echo $artistPage; ?>' class='artistInnerContainer' data-category="<?php echo $cat->slug ?>">
                        <div class='artistImageContainer'>
                            <img class='artistImg' loading='lazy' src='<?php echo $imageUrl; ?>' alt="<?php echo $artistName; ?>">
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
