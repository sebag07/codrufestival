<footer>

<div class="container-fluid">
<img class="footerLeftImg" src="/wp-content/themes/Divi-child/images/footerleft.png" alt="">
<img class="footerRightImg" src="/wp-content/themes/Divi-child/images/footerright.png" alt="">

    <div id="contact" class="container-fluid contactFormSection p-0">
    <div class="contactSectionLeftSide container">
    <?php
    if (is_page_template('codrufestival-partners-template.php')) { 
        echo "<p class='pt-5 mt-5 partnersContactText'>Dacă dorești să afli mai multe detalii și să discutăm despre colaborarea noastră, te rugăm să completezi formularul de mai jos și te vom contacta noi.</p>";
        echo do_shortcode('[wpforms id="27465" title="false"]'); 
    } else {
        echo "<h2>" . get_field('contact_title', 'options') . "</h2>";
        echo "<p>" . get_field('contact_text', 'options') . "</p>";
        echo do_shortcode('[wpforms id="26912" title="false"]'); 
    }

?>
    </div>
    </div>

    <div class="row m-auto justify-content-center">
        <div class="footerLeftLogo col-lg-2 col-md-12 col-12 d-flex">
            <img class="footer-logo footer-logo-codru" src="/wp-content/themes/Divi-child/images/logo.png" alt="">
        </div>
        <div class="footerItems col-lg-8 col-xl-7 col-md-12 col-12">
            <span><img src="/wp-content/themes/Divi-child/images/mail.svg" alt="">OFFICE@CODRUFESTIVAL.RO</span>
            <a href="https://goo.gl/maps/CKWH5sGtU7W9PxNo8" target="_blank"><span><img src="/wp-content/themes/Divi-child/images/map-pin.svg" alt="">PĂDUREA BISTRA, JUDEȚUL TIMIȘ</span></a>
            <span><img src="/wp-content/themes/Divi-child/images/camera.svg" alt="">PRESS@CODRUFESTIVAL.RO</span>
            <span class="footerSocials">
                <a href="https://www.facebook.com/codrufestival" target="_blank"><img src="/wp-content/themes/Divi-child/images/facebookcodru.svg" alt=""></a>
                <a href="https://www.instagram.com/codrufestival/" target="_blank"><img src="/wp-content/themes/Divi-child/images/instagramcodru.svg" alt=""></a>
                <a href="https://www.linkedin.com/company/codrufestival/" target="_blank"><img src="/wp-content/themes/Divi-child/images/linkedincodru.svg" alt=""></a>
                <a href="https://www.youtube.com/@codrufestival" target="_blank"><img src="/wp-content/themes/Divi-child/images/youtubecodru.svg" alt=""></a>
                <a href="https://spotify.link/mN0Bq5T0dCb" target="_blank"><img src="/wp-content/themes/Divi-child/images/spotify.svg" alt=""></a>
            </span>
        </div>
        <div class="footerRightLogo col-lg-2 col-md-12 col-12 d-flex">
            <img class="footer-logo" src="/wp-content/themes/Divi-child/images/logoteg.png" alt="">
        </div>
        <div class="footerTermsMobile pt-4 d-flex justify-content-center col-md-12 col-12">
            <?php
            
            $mobile_menu = get_menu_with_children("footerMenu"); 
            $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            foreach ($mobile_menu as $item) {
                echo "<span><a href='$item->url' target='_blank'>$item->title</a></span>";
            }

            ?>
        </div>
        <div class="footerMobileLogo col-md-12 col-12 d-flex">
            <img class="footer-logo codruLogoFooter" src="/wp-content/themes/Divi-child/images/logo.png" alt="">
            <img class="footer-logo" src="/wp-content/themes/Divi-child/images/logoteg.png" alt="">
        </div>
    </div>
    <div class="row m-auto justify-content-center">
        <div class="col-lg-2 col-md-12 col-12 d-flex">
        </div>
        <div class="footerTerms pt-4 d-flex justify-content-center col-lg-8 col-xl-7 col-md-12 col-12">
            <?php
            
            $mobile_menu = get_menu_with_children("footerMenu"); 
            $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            foreach ($mobile_menu as $item) {
                echo "<span><a href='$item->url' target='_blank'>$item->title</a></span>";
            }

            ?>
        </div>
        <div class="col-lg-2 col-md-12 col-12 d-flex">
        </div>
    </div>
</div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="/wp-content/themes/Divi-child/js/popper.min.js"></script>
    <script src="/wp-content/themes/Divi-child/js/bootstrap.min.js"></script>
    <script src="/wp-content/themes/Divi-child/js/jquery.sticky.js"></script>
    <script src="/wp-content/themes/Divi-child/js/fslightbox.js"></script>
    <script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js" integrity="sha512-IsNh5E3eYy3tr/JiX2Yx4vsCujtkhwl7SLqgnwLNgf04Hrt9BT9SXlLlZlWx+OK4ndzAoALhsMNcCmkggjZB1w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="/wp-content/themes/Divi-child/js/util.js"></script>
    <script src="/wp-content/themes/Divi-child/js/schedule.js"></script>

</footer>
</body>
</html>