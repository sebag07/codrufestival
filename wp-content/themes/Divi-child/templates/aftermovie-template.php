<?php /*  Template Name: CODRU Festival 2024 Aftermovie  */ ?>
<?php get_header(); ?>
<?php
$video_mp4 = get_field("mp4_video");
$buttonURL = get_field("button_url");
$buttonText = get_field("button_text");
$image = get_field("image");
$backgroundType = get_field("hero_background_type");

$display_lineup_section = get_field('display_lineup');

?>
<style>
    @media (max-width: 576px) {
        .after-movie-container .buttons-container {
            flex-direction: column;
        }
    }
    @media (max-width: 576px){
        .after-movie-container {
            padding-top: 80px !important;
            padding-bottom: 80px !important;
            margin-top: 80px !important;
            min-height: initial;
        }
    }
</style>
<!-- Display the countdown timer in an element -->
<?php

$countdownDaysText = get_field('days_text', 'options');
$countdownHoursText = get_field('hours_text', 'options');
$countdownMinutesText = get_field('minutes_text', 'options');
$countdownSecondsText = get_field('seconds_text', 'options');
$countdownText = get_field('target_text', 'options');
$countdownExpiredText = get_field('expired_text', 'options');

$countdown_end_date = get_field('countdown_end_date', 'options');

?>
<!-- 
<div class="countdown-container row">
    <div id="countdown" class="col-xl-5 col-lg-12"></div>
    <div class="countdown-text col-xl-5 col-lg-12"><?php echo $countdownText ?></div>
</div>
 -->
<section class="after-movie-container heroContainer container-fluid p-0 m-0">
    <picture class="hero-section-title" style="z-index: 9;">
        <!-- For screens 1200px and above (desktop) -->
        <source media="(min-width: 1200px)" srcset="<?php echo get_stylesheet_directory_uri(); ?>/images/codru-hero-title.png">
        <!-- For screens smaller than 1200px (mobile) -->
        <img class="hero-section-title" src="<?php echo get_stylesheet_directory_uri(); ?>/images/codru-hero-title-mobile.png" alt="Hero Title">
    </picture>
    <div class="buttons-container" style="display: flex; gap: 10px;">
        <?php if ($display_lineup_section) : ?>
            <a class="homepage-info-button codru-general-button" href="/#lineup">Lineup</a>
        <?php endif; ?>
        <a class="homepage-info-button codru-general-button" href="<?php echo $buttonURL ?>"><?php echo $buttonText ?></a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const countDownDate = new Date("<?php echo date('Y-m-d\TH:i:s', strtotime($countdown_end_date)); ?>").getTime();

            const countdownElement = document.getElementById("countdown");
            const countdownContainer = document.querySelector(".countdown-container");

            const x = setInterval(function() {
                const now = new Date().getTime();
                const distance = countDownDate - now;

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                if (distance < 0) {
                    clearInterval(x);
                    countdownContainer.style.display = "none";
                } else {
                    countdownContainer.style.display = "flex";
                    countdownElement.innerHTML = `<span class="countdown-time-text">${days} <?php echo $countdownDaysText; ?></span> ` +
                        `<span class="countdown-time-text">${hours} <?php echo $countdownHoursText; ?></span> ` +
                        `<span class="countdown-time-text">${minutes} <?php echo $countdownMinutesText; ?></span> ` +
                        `<span class="countdown-time-text">${seconds} <?php echo $countdownSecondsText; ?></span>`;
                }
            }, 1000);
        });
    </script>
    <img class="heroLeftLeaves" src="/wp-content/themes/Divi-child/images/b-left.png" alt="">
    <img class="heroRightLeaves" src="/wp-content/themes/Divi-child/images/b-right.png" alt="">
    <div class="video-background">
        <img class="hero-image-bg" src="<?php echo get_stylesheet_directory_uri(); ?>/images/head.png" alt="Hero Banner">
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
// Query all artists
$args = [
    'post_type' => 'artist',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'ASC',
    'suppress_filters' => false,
];
$artists = get_posts($args);

$grouped_artists = [];
foreach ($artists as $artist) {
    $level = get_field('artist_level', $artist->ID); // Returns array with 'value' and 'label'
    if (!$level || empty($level['value'])) continue; // Skip if no level
    $level_key = $level['value'];
    if (!isset($grouped_artists[$level_key])) {
        $grouped_artists[$level_key] = [];
    }
    $grouped_artists[$level_key][] = $artist;
}

?>

<?php if ($display_lineup_section) : ?>
    <section id="lineup">
        <div class="container sectionPadding">
            <?php foreach ($artist_levels as $level_key => $level_info): ?>
                <?php if (!empty($grouped_artists[$level_key])): ?>
                    <div class="<?php echo esc_attr($level_info['class']); ?> pt-3 pb-3">
                        <?php
                        $lastKey = array_key_last($grouped_artists[$level_key]);
                        foreach ($grouped_artists[$level_key] as $key => $artist):
                        ?>
                            <div class='artists-name'>
                                <h4 class='m-0 pb-0' style='color: var(--artist-level-color-secondary);'>
                                    <?php echo esc_html(get_the_title($artist->ID)); ?>
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
            <div class="col-lg-12 col-md-12 col-sm-12 pt-5 text-align-center general-button-container">
                <a class="codru-general-button"
                    href="<?php echo esc_url($button_link); ?>"
                    >
                    <?php echo esc_html($button_text); ?>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

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
                        //              <div class='homepageNewsTitle'><h3>$post->post_title</h3><span><img src='/wp-content/themes/Divi-child/images/right-chevron.png' />$read_more</span></div>
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
                        //          <div class='homepageNewsTitle'><h3>$post->post_title</h3><span><img src='/wp-content/themes/Divi-child/images/right-chevron.png' />$read_more</span></div>
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

<?php get_footer(); ?>