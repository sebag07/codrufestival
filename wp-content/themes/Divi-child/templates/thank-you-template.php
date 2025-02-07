<?php
/*
Template Name: Thank You Page
*/

get_header();

?>
<meta name=“robots” content=“noindex”>


<style>
/* Thank You Page Styles */
header, .headerHalfCircle {
    display:none !important;
}

.thank-you-hero {
    background-size: cover;
    background-position: center;
    text-align: center;
    color: #ffffff;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.thank-you-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5); /* Adjust the opacity as needed */
    z-index: 1;
}

.content-wrapper {
    max-width: 1000px;
    margin: 0 auto;
    text-align: center;
    position: relative;
    z-index: 2;
    padding: 25px;
}

.thank-you-hero h1 {
    font-size: 48px;
    font-weight: bold;
    margin-bottom: 20px;
    color: #fff;
    padding-bottom: 0px;
}

.thank-you-hero h2 {
    font-size: 30px;
    font-weight: normal;
    margin-bottom:20px;
    color:#fff;
}

.content-wrapper p {
    margin-bottom: 40px;
    color: #fff;
    font-size: 18px;
}

.thank-you-button {
    background: #fff;
    color: #076708;
    box-sizing: border-box;
    border-radius: 50px;
    text-align: center;
    transition: all 0.5s;
    padding: 10px 50px;
    font-size: 20px;
}

.thank-you-button:hover {
    color: #fff;
    background: #6cbf02;
    cursor: pointer;
}

#thank-you-socials {
    margin-top: 30px;
}

#thank-you-socials a {
    display: flex;
    justify-content: center;
    align-items: center;
}

#thank-you-socials a img {
    max-height: 24px;
}

.thank-you-logo {
    max-width: 700px;
}

</style>


<?php

$hero_title = get_field('hero_title');
$hero_subtitle = get_field('hero_subtitle');
$main_content = get_field('main_content');
$cta_text = get_field('cta_text');
$cta_link = get_field('cta_link');
$background_image = get_field('background_image');

?>

<div id="thank-you-main-content">
    <div class="thank-you-hero" style="background-image: url('<?php echo esc_url($background_image['url']); ?>');">
        <div class="content-wrapper">
            <img class="thank-you-logo" src="/wp-content/themes/Divi-child/images/logo-2025-anniversary.png" alt="CODRU Festival Logo">
            <h1><?php echo esc_html($hero_title); ?></h1>
            <h2><?php echo esc_html($hero_subtitle); ?></h2>
                <?php echo wp_kses_post($main_content); ?>
                <?php if ($cta_text && $cta_link) : ?>
                    <a href="<?php echo esc_url($cta_link); ?>" class="thank-you-button"><?php echo esc_html($cta_text); ?></a>
                <?php endif; ?>
                <div class="thank-you-button-container">
                    <a href="/" class="thank-you-button">Back to Home</a>
                </div>
                <span id="thank-you-socials" class="footerSocials justify-content-center">
                    <a href="https://www.facebook.com/codrufestival" target="_blank"><img src="/wp-content/themes/Divi-child/images/facebookcodru.svg" alt=""></a>
                    <a href="https://www.instagram.com/codrufestival/" target="_blank"><img src="/wp-content/themes/Divi-child/images/instagramcodru.svg" alt=""></a>
                    <a href="https://www.linkedin.com/company/codrufestival/" target="_blank"><img src="/wp-content/themes/Divi-child/images/linkedincodru.svg" alt=""></a>
                    <a href="https://www.youtube.com/@codrufestival" target="_blank"><img src="/wp-content/themes/Divi-child/images/youtubecodru.svg" alt=""></a>
                    <a href="https://open.spotify.com/playlist/0vePsGS7Ei7jA5hPUlvbxY?si=a6b26f14a4a94198" target="_blank"><img src="/wp-content/themes/Divi-child/images/spotify.svg" alt=""></a>
                </span>
        </div>
    </div>
</div>

