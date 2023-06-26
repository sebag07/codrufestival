// Activity
jQuery(document).on("click", ".activity", function(){
    jQuery(".activity").removeClass("active");
    jQuery(this).addClass("active");
});



jQuery(document).ready(function() {
    jQuery(".preRegisterButton").hover(function() {
        jQuery(".preRegisterCenteredImage").addClass("centeredImageHover");
        jQuery('.preRegisterCenteredImage').removeClass('centeredImageHover2');
    }, 
    
)
jQuery('.preRegisterButton').on('mouseleave', function() {
    jQuery('.preRegisterCenteredImage').removeClass('centeredImageHover');
    jQuery('.preRegisterCenteredImage').addClass('centeredImageHover2');
 });
})


jQuery(function () { // wait for document ready
    // init
    var controller = new ScrollMagic.Controller();

    // define movement of panels
    var wipeAnimation = new TimelineMax()
        .fromTo("section.panel.firstSlide", 1, {x: "0%"}, {x: "-99.9%", ease: Linear.easeNone}, "first")
        .fromTo("section.panel.secondSlide", 1, {x: "100%"}, {x: "0%", ease: Linear.easeNone}, "first")
        .fromTo("section.panel.secondSlide", 1, {x: "0%"}, {x: "-99.9%", ease: Linear.easeNone}, "second")
        .fromTo("section.panel.thirdSlide", 1, {x: "100%"}, {x: "0%", ease: Linear.easeNone}, "second");

    // create scene to pin and link animation
    new ScrollMagic.Scene({
            triggerElement: "#pinContainer",
            triggerHook: "onLeave",
            duration: "300%"
        })
        .setPin("#pinContainer")
        .setTween(wipeAnimation)
        .addTo(controller);
});

// jQuery(function () { // wait for document ready
// jQuery('.grid').masonry({
//     itemSelector: '.grid-item',
//     columnWidth: 160
//   });
// });
jQuery(window).load(function(){

var gallery = jQuery('.gallery-masonry');
var category = '.item';
console.log(category);
function filterIsotope( filter ) {
	var filter = filter || '*';
	gallery.isotope({
		filter: filter,
	});
}

var lightbox = jQuery('.gallery-masonry');
function lightboxFilter( filter ) {
	var filter = filter || '*';
	lightbox.magnificPopup({
		delegate: filter+'>a',
		type: 'image',
		gallery: {
			enabled: true,
		}
	});
}

filterIsotope();
lightboxFilter();

// jQuery(gallery).imagesLoaded().progress(function() {
// 	filterIsotope();
// });
});
jQuery('[data-category]').on('click', function() {
	var category = $(this).data('category');
	filterIsotope( category );
	lightboxFilter( category );
});