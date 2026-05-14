<?php /*  Template Name: CODRU Festival 2024  */ ?>
<?php get_header(); ?>

<div class="container-fluid heroContainer p-0 m-0">
        <img class="heroBG" src="/wp-content/themes/codrufestival/images/BG-2.png" alt="">
        <img class="heroLeftLeaves" src="/wp-content/themes/codrufestival/images/b-left.png" alt="">
        <img class="heroRightLeaves" src="/wp-content/themes/codrufestival/images/b-right.png" alt="">
        <div class="heroOverlayGradient"></div>
        <div class="heroContent row">
            <div class="heroContentDiv col-xl-12 col-lg-12 col-md-12 col-12">
                <img class="heroContentImage anim heroContentCodruLogo display-desktop"
                     src="/wp-content/themes/codrufestival/images/inima-gradina-zoo.png"
                     alt="Hero Heart Image">
                <img class="heroContentImage anim heroContentCodruLogo display-mobile"
                     src="/wp-content/themes/codrufestival/images/inima-gradina-zoo-ing.png"
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

    <?php
        $codru_artists_2025 = [
            'headliners' => [
                'Akua Naru',
                'Trio Mandili',
                'Goran Bregović & Wedding and Funeral Band',
            ],
            'special_closing_show' => [
                'Subcarpați',
                'Irina Rimes',
            ],
            'main_acts' => [
                'Lupii lui Calancea & Surorile Osoianu',
                'Deliric x Silent Strike',
                'Vița de Vie',
                'Coma',
                'Dirty Shirt',
                'Phoenix',
                'Implant pentru Refuz',
                'DJ Mă-ta',
            ],
            'supporting_acts' => [
                'Paraziții',
                'Oscar',
                'Rava',
                'Erika Isac',
                'Azteca',
                'Sami G',
                'IDK',
                'Albert NBN',
                'Calinacho',
                '911',
            ],
            'level-4' => [
                'E-an-na',
                'Mircea Baniciu',
                'Emeric Imre',
                'Radu Guran',
                'Țapinarii',
                'Eligraf',
            ],
            'note' => 'and many more...',
        ];

        $codru_normalize_artist_name = static function ($name) {
            return strtolower(remove_accents(html_entity_decode(wp_strip_all_tags((string) $name), ENT_QUOTES, get_bloginfo('charset'))));
        };

        $codru_day_labels = [
            'day1' => 'Friday',
            'day2' => 'Saturday',
            'day3' => 'Sunday',
        ];

        $codru_format_artist_schedule = static function ($post_id) use ($codru_day_labels) {
            $day_keys = get_field('day', $post_id);
            $start_time = get_field('start_time', $post_id);
            $time_slots = $start_time ? array_map('trim', explode(',', $start_time)) : [];
            $schedule_lines = [];

            if (!empty($day_keys)) {
                $days = is_array($day_keys) ? $day_keys : [$day_keys];

                foreach ($days as $index => $day_key) {
                    $day_value = is_array($day_key) ? $day_key['value'] : $day_key;
                    $day_label = $codru_day_labels[$day_value] ?? '';

                    if (!$day_label) {
                        continue;
                    }

                    $time_for_day = $time_slots[$index] ?? ($time_slots[0] ?? '');
                    $schedule_lines[] = trim($day_label . ' ' . $time_for_day);
                }
            }

            if (!empty($schedule_lines)) {
                return implode(', ', $schedule_lines);
            }

            return $start_time ? trim($start_time) : '';
        };

        $codru_build_artist_card = static function ($artist_post, $level_label = '') use ($codru_format_artist_schedule) {
            $artist_id = $artist_post->ID;
            $artist_image = wp_get_attachment_image_src(get_post_thumbnail_id($artist_id), 'large');
            $stage = get_field('stage', $artist_id);
            $stage_label = is_array($stage) ? $stage['label'] : $stage;
            $details = has_excerpt($artist_post) ? wp_strip_all_tags(get_the_excerpt($artist_post)) : '';

            return [
                'id' => $artist_id,
                'title' => get_the_title($artist_id),
                'image' => $artist_image ? $artist_image[0] : '',
                'level' => $level_label,
                'stage' => $stage_label ?: '',
                'schedule' => $codru_format_artist_schedule($artist_id),
                'details' => $details,
                'link' => get_permalink($artist_id),
            ];
        };

        $codru_all_artist_posts = get_posts([
            'posts_per_page' => -1,
            'post_type' => 'artist',
            'post_status' => 'publish',
            'suppress_filters' => false,
        ]);
        $codru_artist_posts_by_name = [];

        foreach ($codru_all_artist_posts as $artist_post) {
            $codru_artist_posts_by_name[$codru_normalize_artist_name(get_the_title($artist_post->ID))] = $artist_post;
        }

        $codru_homepage_artist_cards = [];
        $codru_seen_artist_names = [];

        $codru_add_artist_card = static function ($artist_name, $level_label) use (&$codru_homepage_artist_cards, &$codru_seen_artist_names, $codru_artist_posts_by_name, $codru_normalize_artist_name, $codru_build_artist_card) {
            $artist_key = $codru_normalize_artist_name($artist_name);

            if (!$artist_key || isset($codru_seen_artist_names[$artist_key])) {
                return;
            }

            $codru_seen_artist_names[$artist_key] = true;

            if (isset($codru_artist_posts_by_name[$artist_key])) {
                $codru_homepage_artist_cards[] = $codru_build_artist_card($codru_artist_posts_by_name[$artist_key], $level_label);
                return;
            }

            $codru_homepage_artist_cards[] = [
                'id' => 'homepage-' . sanitize_title($artist_name),
                'title' => $artist_name,
                'image' => '',
                'level' => $level_label,
                'stage' => '',
                'schedule' => '',
                'details' => '',
                'link' => '',
            ];
        };

        foreach ([
            'headliners' => 'Headliner',
            'special_closing_show' => 'Special closing show',
            'main_acts' => 'Main act',
            'supporting_acts' => 'Supporting act',
            'level-4' => 'Lineup',
        ] as $artist_group => $level_label) {
            foreach ($codru_artists_2025[$artist_group] as $artist_name) {
                $codru_add_artist_card($artist_name, $level_label);
            }
        }

        $codru_get_artist_posts_by_level = static function ($category_name, $language_category = '', $special_category = '') {
            $args = [
                'posts_per_page' => -1,
                'orderby' => 'title',
                'order' => 'ASC',
                'post_type' => 'artist',
                'post_status' => 'publish',
                'suppress_filters' => false,
            ];

            if ($special_category === 'special') {
                $args['tax_query'] = [
                    'relation' => 'AND',
                    [
                        'taxonomy' => 'category',
                        'field' => 'slug',
                        'terms' => 'special',
                    ],
                    [
                        'taxonomy' => 'category',
                        'field' => 'slug',
                        'terms' => $category_name,
                    ],
                ];
            } elseif ($language_category !== '') {
                $args['tax_query'] = [
                    'relation' => 'AND',
                    [
                        'taxonomy' => 'category',
                        'field' => 'slug',
                        'terms' => $category_name,
                    ],
                    [
                        'taxonomy' => 'category',
                        'field' => 'slug',
                        'terms' => $language_category,
                    ],
                    [
                        'taxonomy' => 'category',
                        'field' => 'slug',
                        'terms' => 'special',
                        'operator' => 'NOT IN',
                    ],
                ];
            } else {
                $args['tax_query'] = [
                    [
                        'taxonomy' => 'category',
                        'field' => 'slug',
                        'terms' => $category_name,
                    ],
                    [
                        'taxonomy' => 'category',
                        'field' => 'slug',
                        'terms' => 'special',
                        'operator' => 'NOT IN',
                    ],
                ];
            }

            return get_posts($args);
        };

        $codru_add_artist_post_card = static function ($artist_post, $level_label) use (&$codru_homepage_artist_cards, &$codru_seen_artist_names, $codru_normalize_artist_name, $codru_build_artist_card) {
            $artist_key = $codru_normalize_artist_name(get_the_title($artist_post->ID));

            if (!$artist_key || isset($codru_seen_artist_names[$artist_key])) {
                return;
            }

            $codru_seen_artist_names[$artist_key] = true;
            $codru_homepage_artist_cards[] = $codru_build_artist_card($artist_post, $level_label);
        };

        foreach ([
            ['level-2', 'english', '', 'Main act'],
            ['level-2', 'roman', 'special', 'Special closing show'],
            ['level-2', 'roman', '', 'Main act'],
            ['level-3', '', '', 'Supporting act'],
            ['level-4', '', '', 'Lineup'],
            ['level-5', '', '', 'Lineup'],
            ['level-6', '', '', 'Lineup'],
        ] as $artist_query) {
            foreach ($codru_get_artist_posts_by_level($artist_query[0], $artist_query[1], $artist_query[2]) as $artist_post) {
                $codru_add_artist_post_card($artist_post, $artist_query[3]);
            }
        }
?>

    <section id="lineup">
        <div class="container">
            <div class="container-fluid sectionPadding">
                <div class="col-12 text-center">
                    <div class="artistsLevel1 pt-3 pb-3">
                        <?php
                        foreach ($codru_artists_2025['headliners'] as $key => $artistName) : ?>
                            <div class='artists-name'>
                                <h4 class='m-0 pb-0' style='color: var(--artist-level-color-secondary);'>$artistName </h4>
                            </div>
                            <div class='artists-name'>
                                <h4 class='m-0 pb-0' style='color: var(--artist-level-color-secondary);'>$artistName</h4>
                            </div><div class='artists-bullet'><span style='margin-left: 5px; margin-right: 5px;'>x</span></div>
                        <? endforeach; ?>
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
                <?php if (!empty($codru_homepage_artist_cards)) : ?>
                    <div class="codru-homepage-artist-cards pt-5">
                        <?php
                        codrufestival_react_island('ArtistExpandableCards', [
                            'artists' => $codru_homepage_artist_cards,
                            'eyebrow' => 'CODRU Festival',
                        ], [
                            'class' => 'codru-homepage-artist-cards__island',
                        ]);
                        ?>
                    </div>
                <?php endif; ?>
                <div class="col-lg-12 col-md-12 col-sm-12 pt-5 text-align-center general-button-container">
                    <a class="codru-general-button"
                       href="<?php echo get_field('see_all_artists_button_link') ?>"
                       target="_blank"><?php echo get_field('see_all_artists_button') ?></a>
                </div>
    </section>


<?php  if( have_rows('ticket_cards_repeater', 'options') ): ?>
<section id="tickets-sale-section">
    <div class="sectionPadding container">
        <h2 class="sectionTitle"><?php echo get_field('section_title', 'options') ?></h2>
        <div class="row">
    <?php while (have_rows('ticket_cards_repeater', 'options')): the_row();
        $cardDescription = get_sub_field('description', 'options');
        $cardPrice = get_sub_field('price', 'options');
        $cardReducedPrice = get_sub_field('reduced_price', 'options');
        $cardButtonURL = get_sub_field('button_url', 'options');
        $cardButtonText = get_sub_field('button_text', 'options');
        $cardTitle = get_sub_field('title', 'options');
        ?>
        <div class="col-xl-4 col-lg-4 col-md-6 col-12 ticket-card">
            <div class="card-inner">
                <div class="card-header display-flex flex-direction-row align-items-center justify-content-between">
                    <h3><?php echo $cardTitle; ?></h3>
                    <svg id='ticket-image' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' viewBox='0 0 512.005 512.005' style='enable-background:new 0 0 512.005 512.005; color: #076708;' xml:space='preserve'>
<g>
    <g>
        <g>
            <path d='M511.513,223.904L452.508,42.326c-1.708-5.251-7.348-8.125-12.602-6.42L6.912,176.612
				c-5.252,1.707-8.126,7.349-6.42,12.602l27.93,85.949c-0.008,0.168-0.025,0.333-0.025,0.503v190.925c0,5.522,4.478,10,10,10
				H493.68c5.522,0,10-4.478,10-10V275.666c0-5.522-4.478-10-10-10h-78.32l89.734-29.16
				C510.345,234.799,513.219,229.157,511.513,223.904z M483.679,285.666v170.925H48.396V285.666h55.392v111.408
				c0,5.522,4.478,10,10,10c5.522,0,10-4.478,10-10V285.666h228.441H483.679z M350.645,265.666H46.365l-23.762-73.123l52.711-17.129
				l20.162,61.276c1.385,4.208,5.296,6.877,9.497,6.877c1.036,0,2.09-0.162,3.128-0.504c5.246-1.727,8.1-7.378,6.373-12.625
				l-20.139-61.206L436.577,58.017l52.825,162.558L350.645,265.666z'></path>
            <path d='M421.405,101.849c-1.708-5.251-7.349-8.124-12.602-6.42l-260.728,84.727c-5.252,1.707-8.126,7.349-6.42,12.602
				c1.374,4.226,5.293,6.912,9.509,6.912c1.024,0,2.066-0.159,3.093-0.492l260.728-84.727
				C420.237,112.744,423.112,107.102,421.405,101.849z'></path>
            <path d='M377.434,166.804l49.352-16.037c5.252-1.707,8.126-7.349,6.42-12.602c-1.708-5.252-7.349-8.125-12.602-6.42
				l-49.352,16.037c-5.252,1.707-8.126,7.349-6.42,12.602c1.374,4.226,5.293,6.912,9.509,6.912
				C375.365,167.296,376.408,167.137,377.434,166.804z'></path>
            <path d='M419.143,212.741c1.374,4.226,5.293,6.912,9.509,6.912c1.023,0,2.066-0.159,3.093-0.492l15.617-5.075
				c5.252-1.707,8.127-7.349,6.42-12.602c-1.708-5.252-7.348-8.126-12.602-6.42l-15.617,5.075
				C420.311,201.846,417.436,207.488,419.143,212.741z'></path>
            <path d='M390.685,211.473l-15.618,5.075c-5.252,1.707-8.127,7.349-6.42,12.602c1.373,4.226,5.293,6.912,9.509,6.912
				c1.023,0,2.065-0.159,3.093-0.492l15.618-5.075c5.252-1.707,8.126-7.349,6.42-12.602
				C401.581,212.641,395.944,209.768,390.685,211.473z'></path>
            <path d='M251.132,186.817l-91.255,29.654c-5.252,1.707-8.127,7.349-6.42,12.602c1.374,4.226,5.293,6.912,9.509,6.912
				c1.023,0,2.066-0.159,3.093-0.492l91.255-29.654c5.252-1.707,8.126-7.349,6.42-12.602
				C262.025,187.985,256.384,185.112,251.132,186.817z'></path>
            <path d='M113.788,420.364c-5.522,0-10,4.478-10,10v3.916c0,5.522,4.478,10,10,10c5.522,0,10-4.478,10-10v-3.916
				C123.788,424.842,119.31,420.364,113.788,420.364z'></path>
            <path d='M161.554,322.663c0,5.522,4.478,10,10,10h274.148c5.522,0,10-4.478,10-10c0-5.522-4.478-10-10-10H171.554
				C166.032,312.663,161.554,317.14,161.554,322.663z'></path>
            <path d='M445.703,350.847H393.81c-5.522,0-10,4.478-10,10c0,5.522,4.478,10,10,10h51.893c5.522,0,10-4.478,10-10
				C455.703,355.325,451.225,350.847,445.703,350.847z'></path>
            <path d='M445.703,417.427h-16.422c-5.522,0-10,4.478-10,10c0,5.522,4.478,10,10,10h16.422c5.522,0,10-4.478,10-10
				C455.703,421.905,451.225,417.427,445.703,417.427z'></path>
            <path d='M392.608,417.427h-16.421c-5.522,0-10,4.478-10,10c0,5.522,4.478,10,10,10h16.421c5.522,0,10-4.478,10-10
				C402.608,421.905,398.131,417.427,392.608,417.427z'></path>
            <path d='M267.507,350.847h-95.952c-5.522,0-10,4.478-10,10c0,5.522,4.478,10,10,10h95.952c5.522,0,10-4.478,10-10
				C277.507,355.325,273.029,350.847,267.507,350.847z'></path>
        </g>
    </g>
</g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
</svg>
                </div>
                <div class="card-description">
                    <p><?php echo $cardDescription; ?></p>
                </div>
                <div class="info-container">
                    <div class="card-price-info">
                        <?php if ($cardReducedPrice): ?>
                            <p class="stroke-price"><?php echo $cardReducedPrice; ?></p>
                        <?php endif; ?>
                        <p class="card-price"><?php echo $cardPrice; ?></p>
                    </div>
                    <div class="card-button">
                        <a href="<?php echo $cardButtonURL; ?>" target="_blank"><?php echo $cardButtonText; ?></a>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
        </div>
    </div>
</section>

<?php endif; ?>

<?php
wp_reset_postdata();
$post_id = get_the_ID(); // Get current post ID
?>

<?php if (have_rows('content-image-repeater', $post_id)): ?>
    <section id="homepage-info-section" style="overflow-x:hidden;" class="dark-background">
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
            <div class='homepageNewsTitle'><h3>$post->post_title</h3><span><img src='/wp-content/themes/codrufestival/images/right-chevron.png' />$read_more</span></div>
            </a>
        </div>";
                }
                endforeach;
                ?>

            </div>
        </div>
    </section>

<?php
//
//$args = array('posts_per_page' => 3, 'orderby' => 'post_date', 'category_name' => 'povestea-codru');
//$povesticodru = get_posts($args);
//
//?>
<!---->
<?php //if (!empty($povesticodru)): ?>
<!--    <section id="povesteaCodru">-->
<!--        <div class="container sectionPadding">-->
<!--            <h2 class="sectionTitle">--><?php //echo get_field('codru_story_title', 'options'); ?><!--</h2>-->
<!--            <div class="newsContainer row">-->
<!--                --><?php
//                foreach ($povesticodru as $post) : {
//                    $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
//                    $postURL = get_the_permalink($post->ID);
//                    $read_more = get_field('news_read_more', 'options');
//                    echo "<div class='homepageNews col-lg-4 col-md-6 col-sm-6 col-12'>
//              <a href='$postURL' class='homepageNewsLink'>
//              <div class='homepageNewsImage text-center'><img src='$image[0]' alt=''></div>
//              <div class='homepageNewsTitle'><h3>$post->post_title</h3><span><img src='/wp-content/themes/codrufestival/images/right-chevron.png' />$read_more</span></div>
//              </a>
//          </div>";
//                }
//                endforeach;
//                ?>
<!---->
<!--            </div>-->
<!--        </div>-->
<!--    </section>-->
<?php //endif; ?>
<!---->
<?php
//
//$args = array('posts_per_page' => 3, 'orderby' => 'post_date', 'category_name' => 'apeluri-artisti');
//$apeluri_artisti = get_posts($args);
//
//?>
<!---->
<?php //if (!empty($apeluri_artisti)): ?>
<!--    <section id="apeluriartisti">-->
<!--        <div class="container sectionPadding">-->
<!--            <h2 class="sectionTitle">APELURI ARTIȘTI</h2>-->
<!--            <div class="newsContainer row">-->
<!--                --><?php
//                foreach ($apeluri_artisti as $post) : {
//                    $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
//                    $postURL = get_the_permalink($post->ID);
//                    $read_more = get_field('news_read_more', 'options');
//                    echo "<div class='homepageNews col-lg-4 col-md-6 col-sm-6 col-12'>
//          <a href='$postURL' class='homepageNewsLink'>
//          <div class='homepageNewsImage text-center'><img src='$image[0]' alt=''></div>
//          <div class='homepageNewsTitle'><h3>$post->post_title</h3><span><img src='/wp-content/themes/codrufestival/images/right-chevron.png' />$read_more</span></div>
//          </a>
//      </div>";
//                }
//                endforeach;
//                ?>
<!---->
<!--            </div>-->
<!--        </div>-->
<!--    </section>-->
<?php //endif; ?>

<?php get_footer(); ?>