<?php /*  Template Name: Artisti  */ ?>
<?php get_header(); ?>

<div class="container-fluid single-page artists-page header-padding">
    <h1 class="text-center sectionTitle" style="font-weight: 600;"><?php echo get_the_title(); ?></h1>
    <section>
        <div class="sectionPadding container">
        <span id="category-filter">
        <?php
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $key = '/en/';
        $is_english = strpos($url, $key) !== false;

        echo '<label class="activitiesCheckbox activeCategory" for="all"><input class="allcat" id="all" type="radio" name="artist-day" value="all" checked><span>' . ($is_english ? 'All' : 'Toate') . '</span></label>';

        $days = [
            ['id' => 'ziua-1', 'label' => $is_english ? 'Friday' : 'Vineri'],
            ['id' => 'ziua-2', 'label' => $is_english ? 'Saturday' : 'Sâmbătă'],
            ['id' => 'ziua-3', 'label' => $is_english ? 'Sunday' : 'Duminică']
        ];

        $scenes = [
            ['id' => 'scena-1', 'label' => 'Sub Soare'],
            ['id' => 'scena-2', 'label' => 'Lumea Noua'],
            ['id' => 'scena-3', 'label' => 'Sub Codru'],
            ['id' => 'scena-4', 'label' => 'Sub Nori']
        ];

        foreach ($days as $day) {
            echo '<label class="activitiesCheckbox" for="' . $day['id'] . ($is_english ? '-en' : '') . '"><input class="catCheckbox" id="' . $day['id'] . ($is_english ? '-en' : '') . '" type="radio" name="artist-day" value="' . $day['id'] . ($is_english ? '-en' : '') . '"><span>' . $day['label'] . '</span></label>';
        }

        echo '<br>';

        foreach ($scenes as $scene) {
            echo '<label class="activitiesCheckbox" for="' . $scene['id'] . '"><input class="catCheckbox" id="' . $scene['id'] . '" type="radio" name="artist-scene" value="' . $scene['id'] . ($is_english ? '-en' : '') . '"><span>' . $scene['label'] . '</span></label>';
        }
        ?>

        </span>
            <div class="artistCardContainer">
                <?php
                $args = array('posts_per_page' => -1, 'orderby' => 'date', 'suppress_filters' => false, 'order' => 'ASC', 'post_type' => 'artist');
                $postslist = get_posts($args);
                foreach ($postslist as $post) :
                    $artistName = get_the_title();
                    $artistPage = get_the_permalink();
                    $artistImage = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'medium_large');
                    $intervalOrar = get_field('interval_orar', $post->ID);
                    if ($artistImage) {
                        $imageUrl = $artistImage[0];
                    } else {
                        $imageUrl = "/wp-content/themes/Divi-child/images/artist-placeholder.png";
                    };
                    $categories = get_categories($args);
                    foreach ($categories as $cat) :
                        $category = get_the_category($post->ID);

                        ?>
                    <?php endforeach; ?>

                    <a href='<?php echo $artistPage; ?>' class='artistInnerContainer'
                       data-category="<?php if (!empty ($category[0])) echo $category[0]->slug;
                       echo " ";
                       if (!empty ($category[1])) echo $category[1]->slug;
                       echo " ";
                       if (!empty ($category[2])) echo $category[2]->slug;
                       echo " ";
                       if (!empty ($category[3])) echo $category[3]->slug; ?> all">
                        <div class='artistImageContainer'>
                            <img class='artistImg' loading='lazy' src='<?php echo $imageUrl; ?>'
                                 alt="<?php echo $artistName; ?>">
                            <div class='imageOverlay'></div>
                        </div>
                        <div class='artistContent'>
                            <div class='artistContentBG'></div>
                            <div class='artistContentMeta'>
                            <span class='artistContentName'>
                                <?php echo $artistName; ?>
                            </span>
                                <span class='artistContentDayStage'>
                                    <?php echo $intervalOrar ?>
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

            jQuery(document).ready(function () {
                function filterArtists() {
                    let selectedDays = [];
                    let selectedScenes = [];

                    jQuery("input[name='artist-day']:checked").each(function () {
                        selectedDays.push(jQuery(this).val());
                    });

                    jQuery("input[name='artist-scene']:checked").each(function () {
                        selectedScenes.push(jQuery(this).val());
                    });

                    jQuery(".artistInnerContainer").each(function () {
                        const categories = jQuery(this).attr('data-category').split(' ');
                        const matchesDay = selectedDays.length === 0 || selectedDays.some(day => categories.includes(day));
                        const matchesScene = selectedScenes.length === 0 || selectedScenes.some(scene => categories.includes(scene));

                        if (matchesDay && matchesScene) {
                            jQuery(this).show();
                        } else {
                            jQuery(this).hide();
                        }
                    });
                }

                function setCheckedAttributes() {
                    jQuery("input[name='artist-day'], input[name='artist-scene']").each(function () {
                        if (jQuery(this).is(":checked")) {
                            jQuery(this).prop("checked", true);
                            jQuery(this).parent().addClass('activeCategory');
                        } else {
                            jQuery(this).prop("checked", false);
                            jQuery(this).parent().removeClass('activeCategory');
                        }
                    });
                }

                // Attach change event listener to checkboxes
                jQuery("input[name='artist-day'], input[name='artist-scene']").on('change', function () {
                    setCheckedAttributes();
                    filterArtists();
                });

                filterArtists();
            });


        </script>
    </section>
</div>


<?php get_footer(); ?>
