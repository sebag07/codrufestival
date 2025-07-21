jQuery(document).ready(function($) {
    $('.partners-owl').owlCarousel({
        loop: true,
        margin: 30,
        nav: false,
        dots: false,
        autoplay: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
        responsive: {
            0: { items: 1 },
            600: { items: 1 },
            900: { items: 2 },
            1200: { items: 3 },
            1400: { items: 4 }
        }
    });
});