<?php /*  Template Name: Activitati  */ ?>
<?php get_header(); ?>

<div class="container activitiesContainer">
<h2 class="sectionTitle mb-5"><?php echo get_the_title(); ?></h2>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12 categoriesContainer">
                <span id="category-filter">
                <label class="activitiesCheckbox activeCategory" for="all"><input class="allcat" id="all" type="radio" name="activity-day" value="all" checked><span>Toate</span></label>
                        <?php

                    $days = [
                        ['id' => 'ziua-1', 'label' => 'Vineri'],
                        ['id' => 'ziua-2', 'label' => 'Sâmbătă'],
                        ['id' => 'ziua-3', 'label' => 'Duminică']
                    ];

                    $activities = [
                        ['id' => 'adventure', 'label' => 'Adventure'],
                        ['id' => 'healty', 'label' => 'Healty'],
                        ['id' => 'game-on', 'label' => 'Game ON!'],
                        ['id' => 'kids-family', 'label' => 'Kids & Family'],
                        ['id' => 'performance', 'label' => 'Performance'],
                        ['id' => 'eco-friendly', 'label' => 'EcoFriendly'],
                        ['id' => 'adolescenti-adulti', 'label' => 'Adolescenți & Adulți']
                    ];

                    foreach ($days as $day) {
                        echo '<label class="activitiesCheckbox" for="' . $day['id'] . '"><input class="catCheckbox" id="' . $day['id'] . '" type="radio" name="activity-day" value="' . $day['id'] . '"><span>' . $day['label'] . '</span></label>';
                    }

                    echo '<br>';

                    foreach ($activities as $activity) {
                        echo '<label class="activitiesCheckbox" for="' . $activity['id'] . '"><input class="catCheckbox" id="' . $activity['id'] . '" type="radio" name="activity-type" value="' . $activity['id'] . '"><span>' . $activity['label'] . '</span></label>';
                    }
                    ?>
            </span>
            </div>
            <div class="activitiesPosts">
                <div class="row">
                    <?php
                    $args = array(
                            'post_type' => 'activitati',
                            'post_status' => 'publish',
                            'posts_per_page' => '-1'
                            );
                    $postslist = get_posts($args);
                    foreach ($postslist as $post) : {
                        $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                        if ($image) {
                            $imageUrl = $image[0];
                        } else {
                            $imageUrl = "/wp-content/themes/Divi-child/images/logo.png";
                        }
                        $title = get_the_title();
                        // $categories = get_the_category($post->ID);
                        $shortDescription = get_field('short_description', $post->ID);
                        $date = get_field('date', $post->ID);
                        $type = get_field('type', $post->ID);
                        $terms = wp_get_post_terms( $post->ID, 'activitati_category');
                        $postURL = get_permalink($post->ID);
                        foreach ($terms as $cat) : {
                                echo "  <div class='col-lg-4 col-md-6 col-12 activitiesBlurb' data-category='$cat->slug all'>
                                    <div class='activitiesPost'>
                                        <div class='imageContainer'><img src='$imageUrl'>
                                        <div class='details'>
                                        <span class='type'>$type</span>
                                        <span class='date'>$date</span>
                                        </div>
                                        </div>
                                        <div class='postInfo'>
                                        <h4 class='mb-1'>$title</h4>
                                        <p >$shortDescription</p>
                                        <a class='readMore' href='$postURL'><span>Citește mai mult</span></a>
                                        </div>
                                    </div>
                                </div>
                            ";
                        }
                        endforeach;
                    }
                    endforeach;
                    ?>
                </div>
            </div>
        </div>
    </div>

<script>

            jQuery(document).ready(function () {
                function filterArtists() {
                    let selectedDays = [];
                    let selectedScenes = [];

                    jQuery("input[name='activity-day']:checked").each(function () {
                        selectedDays.push(jQuery(this).val());
                    });

                    jQuery("input[name='activity-type']:checked").each(function () {
                        selectedScenes.push(jQuery(this).val());
                    });

                    jQuery(".activitiesBlurb").each(function () {
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
                    jQuery("input[name='activity-day'], input[name='activity-type']").each(function () {
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
                jQuery("input[name='activity-day'], input[name='activity-type']").on('change', function () {
                    setCheckedAttributes();
                    filterArtists();
                });

                filterArtists();
            });




    
    
</script>

<?php get_footer(); ?>
