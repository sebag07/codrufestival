<?php
$video_mp4 = get_field("mp4_video");
$buttonURL = get_field("button_url");
$buttonText = get_field("button_text");
$image = get_field("image");
$backgroundType = get_field("hero_background_type");

$display_lineup_section = get_field('display_lineup');
$countdownDaysText = get_field('days_text', 'options');
$countdownHoursText = get_field('hours_text', 'options');
$countdownMinutesText = get_field('minutes_text', 'options');
$countdownSecondsText = get_field('seconds_text', 'options');
$countdownText = get_field('target_text', 'options');
$countdownExpiredText = get_field('expired_text', 'options');
$countdown_end_date = get_field('countdown_end_date', 'options');

$normalize_ticket_title = static function ($title) {
    $title = preg_replace('/\s*-\s*\d+(?:[.,]\d+)?\s*EUR(?:\s*\+\s*taxes)?\s*$/i', '', (string) $title);
    $title = remove_accents(html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    $title = strtolower($title);
    $title = preg_replace('/[^a-z0-9]+/', ' ', $title);

    return trim((string) preg_replace('/\s+/', ' ', (string) $title));
};

$live_ticket_prices = [];
$live_tickets_json_path = get_stylesheet_directory() . '/data/tickets-live.json';

if (file_exists($live_tickets_json_path)) {
    $live_tickets_json = file_get_contents($live_tickets_json_path);
    $live_tickets_payload = json_decode($live_tickets_json, true);

    if (json_last_error() === JSON_ERROR_NONE && !empty($live_tickets_payload['tickets']) && is_array($live_tickets_payload['tickets'])) {
        foreach ($live_tickets_payload['tickets'] as $live_ticket) {
            if (empty($live_ticket['display_price'])) {
                continue;
            }

            $ticket_key = !empty($live_ticket['match_key'])
                ? (string) $live_ticket['match_key']
                : $normalize_ticket_title($live_ticket['title'] ?? $live_ticket['name'] ?? '');

            if ($ticket_key !== '') {
                $live_ticket_prices[$ticket_key] = (string) $live_ticket['display_price'];
            }
        }
    }
}
?>

<section class="relative mt-20 flex h-auto min-h-0 max-h-[1000px] w-full flex-col items-center justify-center gap-[25px] overflow-x-hidden overflow-y-hidden pt-10 pb-15 sm:h-[80vh] md:pt-25 md:pb-30">
    <picture class="z-[9]">
        <img class="z-[9] max-h-[40vh] w-full max-w-[300px] sm:max-w-[600px] xl:max-w-[800px]" src="<?php echo get_stylesheet_directory_uri(); ?>/images/codru-logo-header.png" alt="Hero Title">
    </picture>
    <div class="event-meta z-[9] flex flex-col items-center justify-center">
            <div class="event-date rounded-lg bg-[#61d72f] px-3.5 py-2 text-lg font-extrabold leading-none tracking-[0.5px] text-[#0b1c25]">
                28-30 AUGUST 2026
            </div>
            <div class="event-location mt-2 rounded-lg bg-[#61d72f] px-3.5 py-2 text-lg font-extrabold leading-none tracking-[0.5px] text-[#0b1c25]">
                PADUREA VERDE, TIMISOARA
            </div>
        </div>
    <div class="buttons-container z-[4] flex flex-col gap-2.5 sm:flex-row">
        <a class="homepage-info-button codru-general-button z-[4]" href="https://bilete.codrufestival.ro/">
            <?php echo get_multilingual_text('BILETE CODRU', 'CODRU TICKETS', 'ro'); ?>
        </a>
    </div>
    <div class="absolute left-0 top-0 z-[1] h-full w-full">
        <img class="h-full w-full object-cover object-center" src="<?php echo get_stylesheet_directory_uri(); ?>/images/codru-hero-background.jpg" alt="Hero Banner">
    </div>
</section>

<?php
// Define the order and labels for levels
$artist_levels = [
    'level1' => ['label' => 'Headliners',      'class' => 'artistsLevel1'],
    'level2' => ['label' => 'Main Acts',       'class' => 'artistsLevel2'],
    'level3' => ['label' => 'Supporting Acts', 'class' => 'artistsLevel3'],
    'level4' => ['label' => 'Level 4',         'class' => 'artistsLevel4'],
    'level5' => ['label' => 'Level 5',         'class' => 'artistsLevel5'],
    'level6' => ['label' => 'Level 6',         'class' => 'artistsLevel6'],
];
$artists_json_path = get_stylesheet_directory() . '/data/artists.json';
$artists = [];

if (file_exists($artists_json_path)) {
    $artists_json = file_get_contents($artists_json_path);
    $artists_payload = json_decode($artists_json, true);

    if (json_last_error() === JSON_ERROR_NONE && !empty($artists_payload['artists']) && is_array($artists_payload['artists'])) {
        $artists = $artists_payload['artists'];
    }
}

$render_artist_name = static function ($artist_name) {
    echo wp_kses((string) $artist_name, [
        'br' => [],
        'small' => [],
    ]);
};

$grouped_artists = [];
foreach ($artists as $artist) {
    if (empty($artist['name'])) {
        continue;
    }

    $level_key = $artist['level'] ?? 'level3';
    if (!isset($artist_levels[$level_key])) {
        $level_key = 'level3';
    }

    if (!isset($grouped_artists[$level_key])) {
        $grouped_artists[$level_key] = [];
    }
    $grouped_artists[$level_key][] = $artist;
}

$artist_cards = [];
$has_artist_card_media = false;
foreach ($artists as $artist) {
    if (empty($artist['name'])) {
        continue;
    }

    $level_key = $artist['level'] ?? 'level3';
    $spotify_id = $artist['spotify_id'] ?? '';
    $spotify_url = !empty($artist['spotify_url']) ? $artist['spotify_url'] : ($spotify_id ? "https://open.spotify.com/artist/{$spotify_id}" : '');
    $spotify_embed_url = !empty($artist['spotify_embed_url']) ? $artist['spotify_embed_url'] : ($spotify_id ? "https://open.spotify.com/embed/artist/{$spotify_id}?utm_source=generator" : '');
    $genres = isset($artist['genres']) && is_array($artist['genres']) ? $artist['genres'] : [];
    $socials = isset($artist['socials']) && is_array($artist['socials']) ? $artist['socials'] : [];
    if ($spotify_url && empty($socials['spotify'])) {
        $socials['spotify'] = $spotify_url;
    }

    $has_artist_card_media = $has_artist_card_media || !empty($artist['image']) || !empty($spotify_embed_url);
    $artist_cards[] = [
        'id' => $artist['id'] ?? sanitize_title($artist['name']),
        'title' => $artist['name'],
        'image' => $artist['image'] ?? '',
        'level' => $artist_levels[$level_key]['label'] ?? '',
        'day' => $artist['day'] ?? $artist['day_label'] ?? 'Day TBA',
        'stage' => $artist['stage'] ?? '',
        'schedule' => $artist['schedule'] ?? '',
        'details' => $artist['description'] ?? $artist['details'] ?? (!empty($genres) ? implode(', ', $genres) : ''),
        'link' => $spotify_url,
        'spotifyUrl' => $spotify_url,
        'spotifyEmbedUrl' => $spotify_embed_url,
        'socials' => $socials,
        'genres' => $genres,
        'followers' => $artist['followers'] ?? null,
        'popularity' => $artist['popularity'] ?? null,
    ];
}

?>


<?php if ($display_lineup_section) : ?>
    <section id="lineup">
        <div class="container sectionPadding text-center">
            <?php foreach ($artist_levels as $level_key => $level_info): ?>
                <?php if (!empty($grouped_artists[$level_key])): ?>
                    <div class="<?php echo esc_attr($level_info['class']); ?> pt-3 pb-3">
                        <?php
                        $lastKey = array_key_last($grouped_artists[$level_key]);
                        foreach ($grouped_artists[$level_key] as $key => $artist):
                            $artist_name = $artist['name'] ?? '';
                            $artist_name_class = stripos($artist_name, '<small') !== false ? ' has-small-text' : '';
                        ?>
                            <div class='artists-name'>
                                <h4 class='m-0 pb-0<?php echo esc_attr($artist_name_class); ?>' style='color: var(--artist-level-color-secondary);'>
                                    <?php $render_artist_name($artist_name); ?>
                                </h4>
                            </div>
                            <?php if ($key !== $lastKey): ?>
                                <div class='artists-bullet'><span style='margin-left: 5px; margin-right: 5px;'>&bull;</span></div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php
            if (get_current_language_code() === 'ro') {
                $button_text = 'Vezi toți artiștii';
                $button_link = '/artisti'; // Romanian artists page
            } else {
                $button_text = 'See all artists';
                $button_link = '/en/artists'; // English artists page
            }
            ?>
            <?php /* ?>
            <div class="col-lg-12 col-md-12 col-sm-12 pt-5 text-align-center general-button-container">
                <a class="codru-general-button"
                    href="<?php echo esc_url($button_link); ?>"
                    >
                    <?php echo esc_html($button_text); ?>
                </a>
            </div>
            <?php */ ?>
            <div class="text-center text-lg text-white">&nbsp;<?php echo get_multilingual_text('and many more', 'and many more', 'ro'); ?></div>
        </div>
    </section>
<?php endif; ?>


<section id="codru-advent-calendar" class="sectionPadding container">
    <?php if (!empty($artist_cards) && function_exists('codrufestival_react_island')): ?>
        <?php
        codrufestival_react_island('ArtistExpandableCards', [
            'artists' => $artist_cards,
            'eyebrow' => 'CODRU Festival',
            'emptyText' => 'Artists will be announced soon.',
            'showPerformanceMeta' => false,
        ], [
            'class' => 'codru-advent-calendar__artist-cards',
        ]);
        ?>
    <?php endif; ?>
</section>

<?php if (have_rows('ticket_cards_repeater', 'options')): ?>
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
                    $liveTicketPrice = $live_ticket_prices[$normalize_ticket_title($cardTitle)] ?? '';
                    $displayCardPrice = $liveTicketPrice ?: $cardPrice;
                ?>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 ticket-card">
                        <div class="card-inner">
                            <div class="card-header display-flex flex-direction-row align-items-center justify-content-between gap-3">
                                <h3 class="m-0 flex-auto"><?php echo $cardTitle; ?></h3>
                                <svg class='h-10 w-10 flex-[0_0_40px]' id='ticket-image' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' viewBox='0 0 512.005 512.005' style='enable-background:new 0 0 512.005 512.005; color: #076708;' xml:space='preserve'>
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
                                    <p class="card-price"><?php echo esc_html($displayCardPrice); ?></p>
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
                        <?php if (!empty($repeaterButtonURL)) : ?>
                            <a class="homepage-info-button codru-general-button" href="<?php echo esc_url($repeaterButtonURL); ?>"
                                target="_blank"><?php echo esc_html($repeaterButtonText); ?></a>
                        <?php endif; ?>
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

<section id="partnersAnchor">
    <div class="container-fluid sectionPadding">
        <?php get_template_part('template-parts/components/partners-carousel'); ?>
    </div>
</section>

<!---->
<?php
//
//$args = array('posts_per_page' => 3, 'orderby' => 'post_date', 'category_name' => 'povestea-codru');
//$povesticodru = get_posts($args);
//
//
?>
<!---->
<?php //if (!empty($povesticodru)): 
?>
<!--    <section id="povesteaCodru">-->
<!--        <div class="container sectionPadding">-->
<!--            <h2 class="sectionTitle">--><?php //echo get_field('codru_story_title', 'options'); 
                                            ?><!--</h2>-->
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
                        //                
                        ?>
<!---->
<!--            </div>-->
<!--        </div>-->
<!--    </section>-->
<?php //endif; 
?>
<!---->
<?php
//
//$args = array('posts_per_page' => 3, 'orderby' => 'post_date', 'category_name' => 'apeluri-artisti');
//$apeluri_artisti = get_posts($args);
//
//
?>
<!---->
<?php //if (!empty($apeluri_artisti)): 
?>
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
                        //                
                        ?>
<!---->
<!--            </div>-->
<!--        </div>-->
<!--    </section>-->
<?php //endif; 
?>

