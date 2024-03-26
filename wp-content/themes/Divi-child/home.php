<?php /*  Template Name: CODRU Festival 2024  */ ?>
<?php get_header(); ?>

<div class="container-fluid heroContainer p-0 m-0">
    <img class="heroBG" src="/wp-content/themes/Divi-child/images/codru2023hero.png" alt="">
    <!-- <img class="heroLeftLeaves" src="/wp-content/themes/Divi-child/images/L-Leaves-2024.png" alt="">
    <img class="heroRightLeaves" src="/wp-content/themes/Divi-child/images/R-Leaves-2024.png" alt=""> -->
    <div class="heroOverlayGradient"></div>
    <div class="heroContent row">
        <div class="heroContentDiv col-xl-6 col-lg-8 col-md-10 col-10">
            <img class="heroContentImage anim heroContentCodruLogo" src="/wp-content/themes/Divi-child/images/logo.svg"
                alt="">
            <img class="heroContentImage anim heroContentPadureaBistra"
                src="/wp-content/themes/Divi-child/images/locatie.svg" alt="">
            <h1 class="underLocDate"><?php echo get_field('hero_section_date')?></h1>
            <div class="heroDescription">
                <a class="heroContentButton desktopButton desktopContentButton anim"
                    href="https://bilete.codrufestival.ro/"><?php echo get_field('hero_button_text')?></a>
                <h2 class="heroFocusedText heroDescription"><?php echo get_field('hero_section_title')?></h2>
                <p class="anim">
                    <?php echo get_field('hero_section_text')?>
                </p>
                <!-- <a class="heroContentButton mobileButton mobileContentButton anim"
                    href="https://www.entertix.ro/bilete/18092/codru-festival-2024-tba-padurea-bistra-timisoara.html"><?php echo get_field('hero_button_text')?>
                  </a> -->
            </div>
        </div>
    </div>
</div>

<section id="preregister">
    <div class="container">
    <style>

    #preregister {
        background-color: #1e4b3a;
        text-align: center;
    }

    #preregister #sib-container {
        background: transparent;
    }

    #preregister .sectionSubtitle {
        font-size: 18px;
        color: #fff;
        margin: auto;
        text-align: center;
        font-weight: 300;
        padding: 30px 15px;
    }

    #preregister .sib-form-block__button {
        background: #efaa13;
        color: #fff !important;
        box-sizing: border-box !important;
        border-radius: 20px !important;
        border: none;
        padding-top: 10px;
        padding-right: 30px;
        padding-bottom: 10px;
        padding-left: 30px;
        transition: 0.7s;
        width: 100%;
        max-width: 500px;
    }

    #preregister .sib-form-block__button:hover {
        background-color: #569d88 !important;
    }

    #preregister .sib-form .input {
        border-radius: 20px;
        padding: 10px 15px;
        margin-bottom: 15px;
        border: 1px solid #143729;
        text-align: center;
        background: #143729;
        color: #fff;
        max-width: 470px;
        height: 25px;
    }

    #preregister .sib-form .entry__field:focus-within {
        box-shadow: none;
    }

    #preregister .sib-form .entry__field {
        background: none;
        border: none;
        border-radius: 0;
        text-align: center;
        display: block;
        margin: 0 auto;
        width: 100%;
    }

    @media screen and (max-width: 767px){
        #preregister .sib-form .entry__field {
            display: flex;
        }
    }

    #preregister .sib-form-message-panel--active {
        color: #fff;
        border: none;
        text-align: center;
    }

    #preregister .sib-form-message-panel__text {
        display: block;
    }

    #preregister .sib-form .entry__error {
        font-size: 18px;
        padding-bottom: 15px;
        max-width: 100%;
        text-align: center;
        display: none;
    }

    @media screen and (max-width: 767px){
        .container {
            width: 100%;
        }
    }

    </style>
    <link rel="stylesheet" href="https://sibforms.com/forms/end-form/build/sib-styles.css">
    <div class="sib-form">
        <div id="sib-form-container" class="sib-form-container">
            <div id="error-message" class="sib-form-message-panel">
                <div class="sib-form-message-panel__text sib-form-message-panel__text--center">
                    <svg viewBox="0 0 512 512" class="sib-icon sib-notification__icon">
                        <path
                            d="M256 40c118.621 0 216 96.075 216 216 0 119.291-96.61 216-216 216-119.244 0-216-96.562-216-216 0-119.203 96.602-216 216-216m0-32C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm-11.49 120h22.979c6.823 0 12.274 5.682 11.99 12.5l-7 168c-.268 6.428-5.556 11.5-11.99 11.5h-8.979c-6.433 0-11.722-5.073-11.99-11.5l-7-168c-.283-6.818 5.167-12.5 11.99-12.5zM256 340c-15.464 0-28 12.536-28 28s12.536 28 28 28 28-12.536 28-28-12.536-28-28-28z" />
                    </svg>
                    <span class="sib-form-message-panel__inner-text">
                        A fost o eroare cu transmiterea datelor din formular. Vă rugăm reîncercați.
                    </span>
                </div>
            </div>
            <div></div>
            <div id="success-message" class="sib-form-message-panel">
                <div class="sib-form-message-panel__text sib-form-message-panel__text--center">
                    <svg viewBox="0 0 512 512" class="sib-icon sib-notification__icon">
                        <path
                            d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 464c-118.664 0-216-96.055-216-216 0-118.663 96.055-216 216-216 118.664 0 216 96.055 216 216 0 118.663-96.055 216-216 216zm141.63-274.961L217.15 376.071c-4.705 4.667-12.303 4.637-16.97-.068l-85.878-86.572c-4.667-4.705-4.637-12.303.068-16.97l8.52-8.451c4.705-4.667 12.303-4.637 16.97.068l68.976 69.533 163.441-162.13c4.705-4.667 12.303-4.637 16.97.068l8.451 8.52c4.668 4.705 4.637 12.303-.068 16.97z" />
                    </svg>
                    <span class="sib-form-message-panel__inner-text">
                        Ați fost preînregistrat cu succes la CODRU Festival 2024!
                    </span>
                </div>
            </div>
            <div></div>
            <div id="sib-container" class="sib-container--large sib-container--vertical">
                <form id="sib-form" method="POST"
                    action="https://13c8d4bf.sibforms.com/serve/MUIFAO6205aujaG2OPLXy8jiTlXzNBOBF2jn8gsanAYYImUH7TBwrGxOb5eUx8lOn-UF8EPmr-8hFV4OHscoz19QKWJ_BujUVQ6PnOq0rRfIhsdeGzwTh2FgbPg6zJLRK-cLdlMwDSdoSYjQeYYWVLXLposuzx2IBV_YltRz-s7m72ipYOQag50ASmTiB3cOLUDGJqvHzIZrZRy8"
                    data-type="subscription">
                    <div class="sib-form-block">
                        <h2 class="sectionTitle">Preregister</h2>
                    </div>
                    <div>
                        <div class="sib-form-block">
                            <div class="sib-text-form-block">
                                <p class="sectionSubtitle">
                                    Completați formularul de mai jos pentru a vă preînregistra la CODRU Festival 2024!
                                </p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="sib-input sib-form-block">
                            <div class="form__entry entry_block">
                                <div class="form__label-row ">

                                    <div class="entry__field">
                                        <input class="input " type="text" id="EMAIL" name="EMAIL" autocomplete="off"
                                            placeholder="E-mail" data-required="true" required />
                                    </div>
                                </div>

                                <label class="entry__error entry__error--primary">
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- <div>
                        <div class="sib-captcha sib-form-block">
                            <div class="form__entry entry_block">
                                <div class="form__label-row ">
                                    <script>
                                    function handleCaptchaResponse() {
                                        var event = new Event('captchaChange');
                                        document.getElementById('sib-captcha').dispatchEvent(event);
                                    }
                                    </script>
                                    <div class="g-recaptcha sib-visible-recaptcha" id="sib-captcha"
                                        data-sitekey="6LedHFopAAAAAMKHCM_2pg_66EPI7gL1ONOSHCi1"
                                        data-callback="handleCaptchaResponse" style="direction:ltr"></div>
                                </div>
                                <label class="entry__error entry__error--primary"
                                    style="font-size:16px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#661d1d; background-color:#ffeded; border-radius:3px; border-color:#ff4949;">
                                </label>
                            </div>
                        </div>
                    </div> -->
                    <div>
                        <div class="sib-form-block" style="text-align: center">
                            <button class="sib-form-block__button sib-form-block__button-with-loader" form="sib-form" type="submit">
                                <svg class="icon clickable__icon progress-indicator__icon sib-hide-loader-icon"
                                    viewBox="0 0 512 512">
                                    <path
                                        d="M460.116 373.846l-20.823-12.022c-5.541-3.199-7.54-10.159-4.663-15.874 30.137-59.886 28.343-131.652-5.386-189.946-33.641-58.394-94.896-95.833-161.827-99.676C261.028 55.961 256 50.751 256 44.352V20.309c0-6.904 5.808-12.337 12.703-11.982 83.556 4.306 160.163 50.864 202.11 123.677 42.063 72.696 44.079 162.316 6.031 236.832-3.14 6.148-10.75 8.461-16.728 5.01z" />
                                </svg>
                                Preregister
                            </button>
                        </div>
                    </div>

                    <input type="text" name="email_address_check" value="" class="input--hidden">
                    <input type="hidden" name="locale" value="en">
                </form>
            </div>
        </div>
    </div>
    <script>
    window.REQUIRED_CODE_ERROR_MESSAGE = 'Please choose a country code';
    window.LOCALE = 'en';
    window.EMAIL_INVALID_MESSAGE = window.SMS_INVALID_MESSAGE =
        "Email-ul introdus nu este valabil. Vă rugăm reintroduceți email-ul.";

    window.REQUIRED_ERROR_MESSAGE = "Acest câmp nu poate sa fie gol. ";

    window.GENERIC_INVALID_MESSAGE = "Email-ul introdus nu este valabil. Vă rugăm reintroduceți email-ul.";

    window.translation = {
        common: {
            selectedList: '{quantity} list selected',
            selectedLists: '{quantity} lists selected'
        }
    };

    var AUTOHIDE = Boolean(1);
    </script>
    <script defer src="https://sibforms.com/forms/end-form/build/main.js"></script>

    <script src="https://www.google.com/recaptcha/api.js?hl=en"></script>

    <!-- END - We recommend to place the above code in footer or bottom of your website html  -->
    <!-- End Brevo Form -->
    </div>
</section>

<section id="brandCultureAnchor">
    <div class="container-fluid sectionPadding">
        <div class="container">
            <h2 class="sectionTitle"><?php echo get_field('values_section_title', 'options'); ?></h2>
            <div class="row">
                <?php 
            $options = get_field("brand_culture", "options"); 
            foreach($options as $option): 
              $white = $option['black_text'] == '1' ? 'text-black' : 'text-white';
            ?>
                <a class="col-xl-4 col-lg-6 col-md-6 col-sm-12 brandCultureContainer" data-fslightbox="custom-text"
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

<section id="galerieAnchor">
    <div class="masonryContainer container-fluid sectionPadding">
        <h2 class="text-center sectionPadding sectionTitle"><?php echo get_field('gallery_title', 'options'); ?></h2>
        <div class="gallery-items gallery-masonry image-gallery">
            <?php if ( have_rows( 'masonry_section' , 26897 ) ): ?>

            <?php while( have_rows( 'masonry_section', 26897 ) ) : the_row(); ?>

            <?php if( $masonryImage = get_sub_field( 'masonry_image', 26897 ) ) { 

                echo "	<div class='item gallery-image'><a href='$masonryImage'><img src='$masonryImage'/></a></div>";
                
                    } ?>

            <?php endwhile; ?>


            <?php endif; ?>
        </div>
    </div>
</section>


<!-- <section id="faq">
    <div class="container-fluid faq p-relative sectionPadding">
        <h2 class="sectionTitle">FAQ</h2>
        <div class="container">
            <div class="accordion" id="accordionExample">
                <?php 
            $questions = get_field("faq_repeater", "options"); 
            $index = 1;
            foreach($questions as $question): ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="<?php echo "heading" . $index; ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="<?php echo "#collapse" . $index; ?>" aria-expanded="false"
                            aria-controls="<?php echo "collapse" . $index; ?>">
                            <?php echo $question['question']; ?>
                        </button>
                    </h2>
                    <div id="<?php echo "collapse" . $index; ?>" class="accordion-collapse collapse"
                        aria-labelledby="<?php echo "heading" . $index; ?>" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <?php echo $question['answer']; ?>
                        </div>
                    </div>
                </div>
                <?php $index++; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section> -->

<?php

    $args = array('posts_per_page' => 3, 'orderby' => 'post_date', 'category_name' => 'apeluri-artisti');
    $apeluri_artisti = get_posts($args); 

?>

<?php if(!empty($apeluri_artisti)): ?>
<section id="apeluriartisti">
    <div class="container sectionPadding">
        <h2 class="sectionTitle">APELURI ARTIȘTI</h2>
        <div class="newsContainer row">
            <?php
          foreach ($apeluri_artisti as $post) : {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
            $postURL = get_the_permalink($post->ID);
            $read_more = get_field('news_read_more', 'options');
              echo "<div class='homepageNews col-lg-4 col-md-6 col-12'>
          <a href='$postURL' class='homepageNewsLink'>
          <div class='homepageNewsImage text-center'><img src='$image[0]' alt=''></div>
          <div class='homepageNewsTitle'><h3>$post->post_title</h3><span><img src='/wp-content/themes/Divi-child/images/right-chevron.png' />$read_more</span></div>
          </a>
      </div>";
          }
          endforeach;
          ?>

        </div>
    </div>
</section>
<?php endif; ?>

<?php

    $args = array('posts_per_page' => 3, 'orderby' => 'post_date', 'category_name' => 'povestea-codru');
    $povesticodru = get_posts($args); 

?>

<?php if(!empty($povesticodru)): ?>
<section id="povesteaCodru">
    <div class="container sectionPadding">
        <h2 class="sectionTitle"><?php echo get_field('codru_story_title', 'options'); ?></h2>
        <div class="newsContainer row">
            <?php
              foreach ($povesticodru as $post) : {
                $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                $postURL = get_the_permalink($post->ID);
                $read_more = get_field('news_read_more', 'options');
                  echo "<div class='homepageNews col-lg-4 col-md-6 col-12'>
              <a href='$postURL' class='homepageNewsLink'>
              <div class='homepageNewsImage text-center'><img src='$image[0]' alt=''></div>
              <div class='homepageNewsTitle'><h3>$post->post_title</h3><span><img src='/wp-content/themes/Divi-child/images/right-chevron.png' />$read_more</span></div>
              </a>
          </div>";
              }
              endforeach;
              ?>

        </div>
    </div>
</section>
<?php endif; ?>

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
                echo "<div class='homepageNews col-lg-4 col-md-6 col-12'>
            <a href='$postURL' class='homepageNewsLink'>
            <div class='homepageNewsImage text-center'><img src='$image[0]' alt=''></div>
            <div class='homepageNewsTitle'><h3>$post->post_title</h3><span><img src='/wp-content/themes/Divi-child/images/right-chevron.png' />$read_more</span></div>
            </a>
        </div>";
            }
            endforeach;
            ?>

        </div>
    </div>
</section>

<?php get_footer(); ?>