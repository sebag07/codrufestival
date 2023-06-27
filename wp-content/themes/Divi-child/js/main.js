// Activity
jQuery(document).on("click", ".activity", function () {
    jQuery(".activity").removeClass("active");
    jQuery(this).addClass("active");
});

jQuery(document).ready(function () {
    jQuery(".preRegisterButton").hover(function () {
        jQuery(".preRegisterCenteredImage").addClass("centeredImageHover");
        jQuery(".preRegisterCenteredImage").removeClass("centeredImageHover2");
    });
    jQuery(".preRegisterButton").on("mouseleave", function () {
        jQuery(".preRegisterCenteredImage").removeClass("centeredImageHover");
        jQuery(".preRegisterCenteredImage").addClass("centeredImageHover2");
    });
});

jQuery(window).load(function () {
    var gallery = jQuery(".gallery-masonry");
    var category = ".item";
    console.log(category);
    function filterIsotope(filter) {
        var filter = filter || "*";
        gallery.isotope({
            filter: filter,
        });
    }

    var lightbox = jQuery(".gallery-masonry");
    function lightboxFilter(filter) {
        var filter = filter || "*";
        lightbox.magnificPopup({
            delegate: filter + ">a",
            type: "image",
            gallery: {
                enabled: true,
            },
        });
    }

    filterIsotope();
    lightboxFilter();

});
jQuery("[data-category]").on("click", function () {
    var category = $(this).data("category");
    filterIsotope(category);
    lightboxFilter(category);
});
