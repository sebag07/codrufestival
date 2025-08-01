jQuery(document).ready(function($) {
    console.log('Owl Carousel init script running...');
    console.log('jQuery version:', $.fn.jquery);
    console.log('Owl Carousel available:', typeof $.fn.owlCarousel);
    
    if (typeof $.fn.owlCarousel === 'function') {
        $('.partners-owl').owlCarousel({
            loop: true,
            margin: 30,
            nav: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            items: 1, // Start with 1 item per view
            responsive: {
                0: { 
                    items: 1,
                    margin: 20
                },
                600: { 
                    items: 1,
                    margin: 25
                },
                900: { 
                    items: 2,
                    margin: 30
                },
                1200: { 
                    items: 3,
                    margin: 30
                },
                1400: { 
                    items: 4,
                    margin: 30
                }
            },
            navText: [
                '<i class="fa fa-chevron-left"></i>',
                '<i class="fa fa-chevron-right"></i>'
            ]
        });
        console.log('Owl Carousel initialized successfully');
    } else {
        console.error('Owl Carousel is not available');
    }
});