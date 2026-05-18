<?php
/*
Template Name: Thank You Page
*/

get_header();

?>
<meta name="robots" content="noindex">


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
    background-image: url('/wp-content/themes/codrufestival/images/codru-hero-background.jpg');
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
    font-weight: 600;
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


<div id="thank-you-main-content">
    <div class="thank-you-hero">
        <div class="content-wrapper">
            <img class="thank-you-logo" src="/wp-content/themes/codrufestival/images/codru-logo-header.png" alt="CODRU Festival Logo">
            <h1><?php esc_html_e('Thank you for purchasing our tickets.', 'codrufestival'); ?></h1>
            <p><?php esc_html_e('The payment has been made. Please check your email for the tickets.', 'codrufestival'); ?></p>
            <div class="thank-you-button-container">
                <a href="/" class="thank-you-button"><?php esc_html_e('Back to Home', 'codrufestival'); ?></a>
            </div>
            <span id="thank-you-socials" class="footerSocials justify-content-center">
                <a href="https://www.facebook.com/codrufestival" target="_blank"><img src="/wp-content/themes/codrufestival/images/facebookcodru.svg" alt=""></a>
                <a href="https://www.instagram.com/codrufestival/" target="_blank"><img src="/wp-content/themes/codrufestival/images/instagramcodru.svg" alt=""></a>
                <a href="https://www.linkedin.com/company/codrufestival/" target="_blank"><img src="/wp-content/themes/codrufestival/images/linkedincodru.svg" alt=""></a>
                <a href="https://www.youtube.com/@codrufestival" target="_blank"><img src="/wp-content/themes/codrufestival/images/youtubecodru.svg" alt=""></a>
                <a href="https://open.spotify.com/playlist/0vePsGS7Ei7jA5hPUlvbxY?si=a6b26f14a4a94198" target="_blank"><img src="/wp-content/themes/codrufestival/images/socials/spotify.svg" alt=""></a>
            </span>
        </div>
    </div>
</div>

