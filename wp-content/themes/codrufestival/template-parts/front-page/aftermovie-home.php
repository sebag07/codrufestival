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

$live_display_tickets = codru_get_live_display_tickets(get_stylesheet_directory() . '/data/tickets-live.json');
$ticket_card_defaults = codru_get_ticket_card_defaults();
$ticket_section_title = get_field('section_title', 'options');
$has_ticket_cards = !empty($live_display_tickets) || have_rows('ticket_cards_repeater', 'options');
?>

<section class="relative mt-20 flex h-auto min-h-0 max-h-[1000px] w-full flex-col items-center justify-center gap-[25px] overflow-x-hidden overflow-y-hidden pt-10 pb-15 sm:h-[80vh] md:pt-25 md:pb-30">
    <picture class="z-[9]">
        <img class="z-[9] max-h-[40vh] w-full max-w-[300px] sm:max-w-[400px] xl:max-w-[500px]" src="<?php echo get_stylesheet_directory_uri(); ?>/images/codru-logo-header.png" alt="Hero Title">
    </picture>
    <div class="event-meta z-[9] flex flex-col items-center justify-center">
            <div class="event-date rounded-lg bg-[#61d72f] px-3.5 py-2 text-lg font-extrabold leading-none tracking-[0.5px] text-[#0b1c25]">
                28-30 AUGUST 2026
            </div>
            <div class="event-location mt-2 rounded-lg bg-[#61d72f] px-3.5 py-2 text-base md:text-lg font-extrabold leading-none tracking-[0.5px] text-[#0b1c25]">
                PĂDUREA VERDE, TIMIȘOARA
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
    if (empty($artist['name']) || !codrufestival_should_show_homepage_artist_card($artist)) {
        continue;
    }

    $level_key = $artist['level'] ?? 'level3';
    $spotify_id = $artist['spotify_id'] ?? '';
    $spotify_url = !empty($artist['spotify_url']) ? $artist['spotify_url'] : ($spotify_id ? "https://open.spotify.com/artist/{$spotify_id}" : '');
    $spotify_embed_url = !empty($artist['spotify_embed_url']) ? $artist['spotify_embed_url'] : ($spotify_id ? "https://open.spotify.com/embed/artist/{$spotify_id}?utm_source=generator" : '');
    $artist_image = function_exists('codrufestival_resolve_artist_image_url') ? codrufestival_resolve_artist_image_url($artist) : ($artist['image'] ?? '');
    $genres = isset($artist['genres']) && is_array($artist['genres']) ? $artist['genres'] : [];
    $socials = isset($artist['socials']) && is_array($artist['socials']) ? $artist['socials'] : [];
    if ($spotify_url && empty($socials['spotify'])) {
        $socials['spotify'] = $spotify_url;
    }

    $expandable = !empty($spotify_url) || !empty($socials['spotify']);

    $has_artist_card_media = $has_artist_card_media || !empty($artist_image) || !empty($spotify_embed_url);
    $artist_cards[] = [
        'id' => $artist['id'] ?? sanitize_title($artist['name']),
        'title' => $artist['name'],
        'image' => $artist_image,
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
        'expandable' => $expandable,
    ];
}

?>


<?php if ($display_lineup_section) : ?>
    <section id="lineup">
        <div class="container sectionPadding text-center">
            <?php codrufestival_render_lineup_levels($grouped_artists, $artist_levels); ?>
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
    <div class="col-lg-12 col-md-12 col-sm-12 pt-5 text-align-center general-button-container">
        <a class="codru-general-button" href="<?php echo esc_url(codrufestival_get_artists_page_url()); ?>">
            <?php echo esc_html(get_multilingual_text('Vezi toți artiștii', 'Show all artists', 'ro')); ?>
        </a>
    </div>
</section>

<?php if ($has_ticket_cards && $ticket_section_title): ?>
    <section id="tickets-sale-section">
        <div class="sectionPadding container">
            <h2 class="sectionTitle"><?php echo esc_html($ticket_section_title); ?></h2>
            <div class="row">
                <?php if (!empty($live_display_tickets)): ?>
                    <?php foreach ($live_display_tickets as $live_ticket):
                        $displayCardTitle = $live_ticket['title'];
                        $displayCardPrice = $live_ticket['display_price'];
                        $cardDescription = $ticket_card_defaults['description'];
                        $cardButtonURL = $ticket_card_defaults['button_url'];
                        $cardButtonText = $ticket_card_defaults['button_text'];
                        $cardReducedPrice = '';
                        include locate_template('template-parts/ticket-card.php');
                    endforeach; ?>
                <?php else: ?>
                    <?php while (have_rows('ticket_cards_repeater', 'options')): the_row();
                        $cardDescription = get_sub_field('description', 'options');
                        $cardPrice = get_sub_field('price', 'options');
                        $cardReducedPrice = get_sub_field('reduced_price', 'options');
                        $cardButtonURL = get_sub_field('button_url', 'options');
                        $cardButtonText = get_sub_field('button_text', 'options');
                        $displayCardTitle = get_sub_field('title', 'options');
                        $displayCardPrice = $cardPrice;
                        include locate_template('template-parts/ticket-card.php');
                    endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

<?php endif; ?>

<section id="homepage-about-volunteer" style="overflow-x:hidden;" class="dark-background">
    <div class="sectionPadding container homepage-info-section">
        <div class="row pt-5 pb-5 even">
            <div class="col-md-6 align-items-start order-md-0 order-1 justify-content-center d-flex flex-column homepage-info-container">
                <h2 class="homepage-info-title mb-4">
                    <?php echo esc_html(get_multilingual_text(
                        'CODRU, festivalul care plantează în România.',
                        'CODRU, the festival that plants trees in Romania.',
                        'ro'
                    )); ?>
                </h2>
                <div class="homepage-info-content mb-4">
                    <p><?php echo esc_html(get_multilingual_text(
                        'Fiecare bilet cumpărat înseamnă un copac plantat și un pas înainte pentru un viitor mai verde.',
                        'Every ticket purchased means a tree planted and a step toward a greener future.',
                        'ro'
                    )); ?></p>
                    <p><?php echo esc_html(get_multilingual_text(
                        'Prin muzică, artă, proiecte de regenerare urbană și dedicate comunității, transformăm energia unui festival într-o mișcare care crește pe tot parcursul anului.',
                        'Through music, art, urban regeneration and community projects, we turn festival energy into a movement that grows all year round.',
                        'ro'
                    )); ?></p>
                    <p><?php echo esc_html(get_multilingual_text(
                        'Suntem oameni care cred în puterea culturii de a inspira și de a construi.',
                        'We are people who believe in the power of culture to inspire and build.',
                        'ro'
                    )); ?></p>
                </div>
            </div>
            <div class="homepage-info-section-image-container col-md-6 my-md-auto p-relative z-1 mb-5">
                <img
                    class="homepage-info-section-image w-100"
                    src="<?php echo esc_url(get_stylesheet_directory_uri() . '/images/homepage/despre-codru-square.jpeg'); ?>"
                    alt="<?php echo esc_attr(get_multilingual_text('CODRU Festival - plantare de copaci', 'CODRU Festival - tree planting', 'ro')); ?>"
                >
                <div class="homepage-info-section-image-underlay" style="background-color:#61d72f"></div>
            </div>
        </div>

        <div class="row pt-5 pb-5 odd">
            <div class="col-md-6 align-items-start order-md-1 order-1 justify-content-center d-flex flex-column homepage-info-container">
                <h2 class="homepage-info-title mb-4">
                    <?php echo esc_html(get_multilingual_text(
                        'Hai în echipa noastră la CODRU6!',
                        'Join our team at CODRU6!',
                        'ro'
                    )); ?>
                </h2>
                <div class="homepage-info-content mb-4">
                    <p><?php echo esc_html(get_multilingual_text(
                        'Dacă vrei să fii parte din construirea unui festival și să simți emoțiile de a vedea cum lucrurile prind viață, aplică la CODRU Festival.',
                        'If you want to help build a festival and feel the thrill of seeing things come to life, apply to volunteer at CODRU Festival.',
                        'ro'
                    )); ?></p>
                    <p><?php echo esc_html(get_multilingual_text(
                        'Este și muncă — nu ascundem asta — dar vei învăța să lucrezi în echipă și vei dezvolta abilități pe care le vei putea folosi în viața de zi cu zi.',
                        'It is work too — we will not hide that — but you will learn teamwork and develop skills you can use in everyday life.',
                        'ro'
                    )); ?></p>
                    <p><strong><?php echo esc_html(get_multilingual_text('Ce îți oferim:', 'What we offer:', 'ro')); ?></strong></p>
                    <ul>
                        <li><?php echo esc_html(get_multilingual_text('Acces gratuit la concerte și activități în timpul festivalului', 'Free access to concerts and activities during the festival', 'ro')); ?></li>
                        <li><?php echo esc_html(get_multilingual_text('Mâncare și apă', 'Food and water', 'ro')); ?></li>
                        <li><?php echo esc_html(get_multilingual_text('Acces la training-uri și workshop-uri pre-eveniment', 'Access to pre-event training and workshops', 'ro')); ?></li>
                    </ul>
                    <p><?php echo esc_html(get_multilingual_text(
                        'Festivalul se desfășoară 28–30 august la Pădurea Verde, Timișoara. Transportul și cazarea sunt responsabilitatea voluntarului.',
                        'The festival takes place 28–30 August at Pădurea Verde, Timișoara. Transport and accommodation are the volunteer\'s responsibility.',
                        'ro'
                    )); ?></p>
                </div>
                <a
                    class="homepage-info-button codru-general-button"
                    href="https://forms.gle/tbHJPymA7wuo1vWX9"
                    target="_blank"
                    rel="noopener noreferrer"
                ><?php echo esc_html(get_multilingual_text('Aplică', 'Apply as volunteer', 'ro')); ?></a>
            </div>
            <div class="homepage-info-section-image-container col-md-6 my-md-auto p-relative z-1 mb-5 order-md-0 order-0">
                <img
                    class="homepage-info-section-image w-100"
                    src="<?php echo esc_url(get_stylesheet_directory_uri() . '/images/homepage/cta-voluntar.jpg'); ?>"
                    alt="<?php echo esc_attr(get_multilingual_text('Voluntari CODRU Festival', 'CODRU Festival volunteers', 'ro')); ?>"
                >
                <div class="homepage-info-section-image-underlay" style="background-color:#efaa13"></div>
            </div>
        </div>
    </div>
</section>

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
            <h2 class="sectionTitle pb-10"><?php echo get_multilingual_html('Valori', 'Values', 'ro'); ?></h2>
            <?php
            $options = get_field("brand_culture", "options");
            $brand_culture_values = [];

            if (is_array($options)) {
                foreach ($options as $option) {
                    $title = $option['title'] ?? '';

                    $brand_culture_values[] = [
                        'id' => sanitize_title($title),
                        'title' => wp_strip_all_tags($title),
                        'keywords' => wp_strip_all_tags($option['keywords'] ?? ''),
                        'description' => wp_kses_post($option['description'] ?? ''),
                        'image' => esc_url_raw($option['image'] ?? ''),
                        'useDarkText' => !empty($option['black_text']) && $option['black_text'] === '1',
                    ];
                }
            }

            if (function_exists('codrufestival_react_island')) {
                codrufestival_react_island('BrandCultureCards', [
                    'values' => $brand_culture_values,
                    'emptyText' => get_multilingual_text('Valorile vor fi adăugate în curând.', 'Values will be added soon.', 'ro'),
                ], [
                    'class' => 'codru-brand-culture-cards__island',
                ]);
            }
            ?>
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

