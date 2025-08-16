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
            ['id' => 'day1', 'label' => $is_english ? 'Friday' : 'Vineri'],
            ['id' => 'day2', 'label' => $is_english ? 'Saturday' : 'Sâmbătă'],
            ['id' => 'day3', 'label' => $is_english ? 'Sunday' : 'Duminică']
        ];

        $scenes = [
            ['id' => 'stage1', 'label' => 'The 5th Element - Main Stage'],
            ['id' => 'stage2', 'label' => 'Water by Rave'],
            ['id' => 'stage3', 'label' => 'Earth by 80\'s'],
            ['id' => 'stage4', 'label' => 'Air by Cramele Recaș'],
            ['id' => 'stage5', 'label' => 'Fire by Hell']
        ];

       foreach ($days as $day) {
           echo '<label class="activitiesCheckbox" for="' . $day['id'] . ($is_english ? '-en' : '') . '"><input class="catCheckbox" id="' . $day['id'] . ($is_english ? '-en' : '') . '" type="radio" name="artist-day" value="' . $day['id'] . ($is_english ? '-en' : '') . '"><span>' . $day['label'] . '</span></label>';
       }

               echo '<br>';

        echo '<label class="activitiesCheckbox activeCategory" for="all-stages"><input class="allcat" id="all-stages" type="radio" name="artist-scene" value="all" checked><span>' . ($is_english ? 'All Stages' : 'Toate Scenele') . '</span></label>';

        foreach ($scenes as $scene) {
            echo '<label class="activitiesCheckbox" for="' . $scene['id'] . ($is_english ? '-en' : '') . '"><input class="catCheckbox" id="' . $scene['id'] . ($is_english ? '-en' : '') . '" type="radio" name="artist-scene" value="' . $scene['id'] . ($is_english ? '-en' : '') . '"><span>' . $scene['label'] . '</span></label>';
        }
        ?>

        </span>
            <div class="artistCardContainer">
                <?php
                $days_labels = [];
                foreach ($days as $day_info) {
                    $days_labels[$day_info['id']] = $day_info['label'];
                }

                $args = array(
                    'posts_per_page' => -1,
                    'orderby' => 'date',
                    'order' => 'ASC',
                    'post_type' => 'artist',
                    'suppress_filters' => false,
                );

                $posts_list = get_posts($args);

                foreach ($posts_list as $post) :
                    setup_postdata($post);
                    $artistName = get_the_title($post->ID);
                    $artistImage = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'medium_large');
                    $artistBio = get_the_content($post->ID);
                    $spotifyLink = get_field('spotify_iframe', $post->ID);

                    // Get direct ACF fields
                    $artist_level = get_field('artist_level', $post->ID);
                    $stage = get_field('stage', $post->ID);
                    $start_time = get_field('start_time', $post->ID);
                    $end_time = get_field('end_time', $post->ID);
                    $day_keys = get_field('day', $post->ID);

                    $artist_stage = '';
                    $intervalOrar = '';
                    $stage_value = '';
                    $day_values = [];

                    // Get stage label and value if stage field exists
                    if (isset($stage)) {
                        $artist_stage = is_array($stage) ? $stage['label'] : $stage;
                        $stage_value = is_array($stage) ? $stage['value'] : $stage;
                    }

                    // Get day values for filtering
                    if (!empty($day_keys)) {
                        if (is_array($day_keys)) {
                            foreach ($day_keys as $day_key) {
                                $day_values[] = is_array($day_key) ? $day_key['value'] : $day_key;
                            }
                        } else {
                            $day_values[] = is_array($day_keys) ? $day_keys['value'] : $day_keys;
                        }
                    }

                    // Build schedule information
                    if (!empty($day_keys)) {
                        if (is_array($day_keys)) {
                            $schedule_lines = [];
                            foreach ($day_keys as $day_key) {
                                $day_value = is_array($day_key) ? $day_key['value'] : $day_key;
                                $translated_day = $days_labels[$day_value] ?? '';
                                if ($translated_day) {
                                    // Build schedule line with or without time
                                    if (!empty($start_time) && !empty($end_time)) {
                                        $schedule_lines[] = esc_html($translated_day) . ' ' . esc_html($start_time) . ' - ' . esc_html($end_time);
                                    } else {
                                        $schedule_lines[] = esc_html($translated_day);
                                    }
                                }
                            }
                            $intervalOrar = implode('<br>', $schedule_lines);
                        } else {
                            $day_value = is_array($day_keys) ? $day_keys['value'] : $day_keys;
                            $translated_day = $days_labels[$day_value] ?? '';
                            if ($translated_day) {
                                // Build schedule line with or without time
                                if (!empty($start_time) && !empty($end_time)) {
                                    $intervalOrar = esc_html($translated_day) . ' ' . esc_html($start_time) . ' - ' . esc_html($end_time);
                                } else {
                                    $intervalOrar = esc_html($translated_day);
                                }
                            }
                        }
                    }

                    $socials_data = [];
                    if (have_rows('social_repeater', $post->ID)) {
                        while (have_rows('social_repeater', $post->ID)) : the_row();
                            $social_platform = get_sub_field('social_selector');
                            $social_link = get_sub_field('social_link');

                            if ($social_link && $social_platform) {
                                $socials_data[] = [
                                    'platform' => $social_platform['value'],
                                    'label' => $social_platform['label'],
                                    'link' => $social_link
                                ];
                            }
                        endwhile;
                    }
                    $json_socials = json_encode($socials_data);

                    $imageURL = $artistImage ? $artistImage[0] : '/wp-content/uploads/2025/06/1x1-copy.jpg';
                    ?>

                    <div class="artistInnerContainer js-artist-popup"
                         tabindex="0"
                         role="button"
                         data-name="<?php echo esc_attr($artistName); ?>"
                         data-image="<?php echo esc_url($imageURL); ?>"
                         data-bio="<?php echo esc_attr($artistBio); ?>"
                         data-spotify="<?php echo esc_attr($spotifyLink); ?>"
                         data-interval="<?php echo esc_attr($intervalOrar); ?>"
                         data-socials='<?php echo esc_attr($json_socials); ?>'
                         data-stage="<?php echo esc_attr($artist_stage); ?>"
                         data-stage-value="<?php echo esc_attr($stage_value); ?>"
                         data-day-values="<?php echo esc_attr(implode(',', $day_values)); ?>"
                    >
                        <div class="artistImageContainer">
                            <img class="artistImg" loading="lazy" src="<?php echo esc_url($imageURL); ?>"
                                 alt="<?php echo esc_attr($artistName); ?>">
                            <div class="imageOverlay"></div>
                        </div>
                        <div class="artistContent">
                            <div class="artistContentBG"></div>
                            <div class="artistContentMeta">
                                <span class="artistContentName"><?php echo esc_html($artistName); ?></span>
                                <span class="artistContentDayStage"><?php echo esc_html($artist_stage); ?></span>
                                <span class="artistContentDayStage"><?php echo $intervalOrar; ?></span>
                            </div>
                        </div>
                        <div class="artistCardHoverOverlay"></div>
                        <div class="artistCardReadMoreBtn">
                            <?php echo esc_html(get_field('artists_card_button', 'options')); ?>
                        </div>
                    </div>
                <?php
                endforeach;
                wp_reset_postdata();
                ?>
            </div>

            <div id="artist-popup-template" class="mfp-hide">
                <div class="artist-popup-content row">
                    <div class="col-12 col-md-7 artist-popup-image-container position-relative">
                        <img id="artist-popup-image" class="singlePostMainImg" src="" alt="artist-image"
                             style="width:100%; display:block;">
                        <div id="artist-popup-name" class="artist-name-overlay">
                            <div class="artist-name">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-5 artist-popup-right">
                        <div class="spotifyIframeContainer" id="artist-popup-spotify"></div>
                        <div id="artist-popup-socials" class="socialLinksContainer"></div>
                    </div>
                    <button class="mfp-close" type="button" title="Close (Esc)">×</button>
                </div>
            </div>
            <script>
                // Defines a global JS variable pointing to your social icons directory.
                const socialIconBasePath = '<?php echo get_stylesheet_directory_uri() . '/images/socials/'; ?>';
            </script>

            <script>
                jQuery(document).ready(function ($) {
                    $('.js-artist-popup').on('click', function () {
                        // Grab data attributes
                        const name = $(this).data('name');
                        const image = $(this).data('image');
                        const bio = $(this).data('bio');
                        const spotify = $(this).data('spotify');
                        const interval = $(this).data('interval');
                        const stage = $(this).data('stage');
                        const socials = $(this).data('socials');

                        // Populate template
                        $('#artist-popup-image').attr('src', image).attr('alt', name);
                        $('.artist-name').text(name);
                        $('#artist-popup-bio').html(bio);
                        $('#artist-popup-spotify').html(spotify);
                        $('#artist-popup-interval').text(interval);
                        $('#artist-popup-stage').text(stage);

                        const socialsContainer = $('#artist-popup-socials');
                        socialsContainer.css("display", "none");
                        // Always clear any links from a previously opened artist
                        socialsContainer.empty();

                        // **Error Check 1: Verify the icon base path is defined**
                        if (typeof socialIconBasePath === 'undefined') {
                            console.error("FATAL: The 'socialIconBasePath' JavaScript variable is not defined. You must define it in your PHP template before this script runs.");
                            return; // Stop the function if the path is missing
                        }

                        // Proceed only if the social data attribute exists and seems valid
                        if (socials && socials.length > 0) {
                            try {
                                socialsContainer.css("display", "flex");

                                if (socials.length > 0) {
                                    const socialLinksHtml = socials.map(social => {
                                        // **Improvement: Use toLowerCase() for platform name to ensure filename match**
                                        // This handles cases like "Facebook" vs "facebook" from your CMS.
                                        const iconUrl = `${socialIconBasePath}${social.platform.toLowerCase()}.svg`;

                                        return `
                            <a href="${social.link}" target="_blank" rel="noopener noreferrer" class="social-icon-link" aria-label="Visit our ${social.label} page">
                                <img src="${iconUrl}" alt="${social.label} icon" class="social-icon">
                            </a>
                        `;
                                    }).join('');

                                    // Update the popup's HTML in a single operation
                                    socialsContainer.html(socialLinksHtml);
                                }

                            } catch (e) {
                                // **Error Check 2: Catch issues with parsing the JSON**
                                console.error("Error parsing or rendering social links. The JSON data from data-socials may be malformed.", e);
                            }
                        }


                        // Open popup
                        $.magnificPopup.open({
                            items: {
                                src: '#artist-popup-template',
                                type: 'inline'
                            },
                            closeBtnInside: true,
                        });
                    });
                });


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
                        let allDays = jQuery("input[name='artist-day'][value='all']").is(":checked");
                        let allScenes = jQuery("input[name='artist-scene'][value='all']").is(":checked");

                        // Only add if not "all"
                        jQuery("input[name='artist-day']:checked").each(function () {
                            if (jQuery(this).val() !== 'all') {
                                selectedDays.push(jQuery(this).val());
                            }
                        });

                        jQuery("input[name='artist-scene']:checked").each(function () {
                            if (jQuery(this).val() !== 'all') {
                                selectedScenes.push(jQuery(this).val());
                            }
                        });

                        jQuery(".artistInnerContainer").each(function () {
                            // If "all" is checked for both filters, show all artists
                            if (allDays && allScenes) {
                                jQuery(this).show();
                                return;
                            }

                            const stageValue = jQuery(this).attr('data-stage-value');
                            const dayValues = jQuery(this).attr('data-day-values');
                            const artistDays = dayValues ? dayValues.split(',') : [];

                            // Check if artist matches selected days
                            const matchesDay = allDays || selectedDays.length === 0 || 
                                selectedDays.some(selectedDay => {
                                    // Remove language suffix for comparison (day1-en -> day1)
                                    const dayId = selectedDay.replace(/-en$/, '');
                                    return artistDays.includes(dayId);
                                });

                            // Check if artist matches selected scenes/stages
                            const matchesScene = allScenes || selectedScenes.length === 0 || 
                                selectedScenes.some(selectedScene => {
                                    // Remove language suffix for comparison (stage1-en -> stage1)
                                    const stageId = selectedScene.replace(/-en$/, '');
                                    return stageValue === stageId;
                                });

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

            <style>
                .artist-popup-content {
                    max-width: 900px;
                    position: relative;
                    height: 600px;
                    width: 100%;
                    overflow-y: auto;
                    box-sizing: border-box;
                    margin: 0 auto;
                    background: var(--main-color);
                    padding: 45px 30px;
                    border-radius: 10px;
                }

                @media (max-width: 767px) {
                    .artist-popup-content {
                        max-width: 600px;
                        width: 100%;
                        display: flex;
                        flex-direction: row;
                        gap: 15px;
                        padding-bottom: 15px;
                    }
                }

                .artist-popup-image-container {
                    height: 100%;
                }

                @media (max-width: 767px) {
                    .artist-popup-image-container {
                        max-height: 350px;
                        height: auto;
                    }
                }

                .mfp-close-btn-in .mfp-close {
                    color: var(--button-color);
                }

                .artist-popup-right {
                    display: flex;
                    flex-direction: column;
                    gap: 10px;
                }

                #artist-popup-image {
                    display: block;
                    height: 100%;
                    width: auto;
                    object-fit: cover;
                    border-radius: 10px;
                }

                @media (max-width: 767px) {
                    #artist-popup-image {
                        max-height: 350px;
                    }
                }

                .artist-name-overlay {
                    position: absolute;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    padding: 15px 30px;
                    background: rgba(0, 0, 0, 0.7);
                    color: #fff;
                    font-size: 1.4rem;
                    font-weight: 600;
                    text-align: left;
                    letter-spacing: 1px;
                    margin-left: 12px;
                    margin-right: 12px;
                    border-bottom-left-radius: 10px;
                    border-bottom-right-radius: 10px;
                }

                .mfp-close {
                    position: absolute;
                    color: #333;
                    font-size: 32px;
                    background: none;
                    border: none;
                    opacity: 1;
                    cursor: pointer;
                    z-index: 2;
                    top: 0;
                    right: 0;
                }

                .spotifyIframeContainer {
                    height: 100%;
                }

                .spotifyIframeContainer iframe {
                    height: 100%;
                }

                @media (max-width: 767px){
                    .spotifyIframeContainer iframe {
                        min-height: 400px;
                    }
                }

                .socialLinksContainer {
                    display: none;
                    flex-direction: row;
                    gap: 15px;
                    align-items: center;
                    padding: 10px;
                    height: 60px;
                }

                .socialLinksContainer .social-icon-link {
                    display: flex;
                    align-items: center;
                }

                .socialLinksContainer .social-icon-link > img {
                    width: 30px;
                    height: auto;
                    filter: brightness(0) saturate(100%) invert(95%) sepia(25%) saturate(6752%) hue-rotate(34deg) brightness(91%) contrast(101%);
                }

            </style>
    </section>
</div>


<?php get_footer(); ?>
