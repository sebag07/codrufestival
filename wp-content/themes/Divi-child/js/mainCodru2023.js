jQuery(function () {

  var siteSticky = function () {
    jQuery(".js-sticky-header").sticky({ topSpacing: 0 });
  };
  siteSticky();

  var siteMenuClone = function () {

    jQuery('.js-clone-nav').each(function () {
      var $this = jQuery(this);
      $this.clone().attr('class', 'site-nav-wrap').appendTo('.site-mobile-menu-body');
    });


    setTimeout(function () {

      var counter = 0;
      jQuery('.site-mobile-menu .has-children').each(function () {
        var $this = jQuery(this);

        $this.prepend('<span class="arrow-collapse collapsed">');

        $this.find('.arrow-collapse').attr({
          'data-toggle': 'collapse',
          'data-target': '#collapseItem' + counter,
        });

        $this.find('> ul').attr({
          'class': 'collapse',
          'id': 'collapseItem' + counter,
        });

        counter++;

      });

    }, 1000);

    jQuery('body').on('click', '.arrow-collapse', function (e) {
      var $this = $(this);
      if ($this.closest('li').find('.collapse').hasClass('show')) {
        $this.removeClass('active');
      } else {
        $this.addClass('active');
      }
      e.preventDefault();

    });

    jQuery(window).resize(function () {
      var $this = jQuery(this),
        w = $this.width();

      if (w > 768) {
        if (jQuery('body').hasClass('offcanvas-menu')) {
          jQuery('body').removeClass('offcanvas-menu');
        }
      }
    })

    jQuery('body').on('click', '.js-menu-toggle', function (e) {
      var $this = jQuery(this);
      e.preventDefault();

      if (jQuery('body').hasClass('offcanvas-menu')) {
        jQuery('body').removeClass('offcanvas-menu');
        $this.removeClass('active');
      } else {
        jQuery('body').addClass('offcanvas-menu');
        $this.addClass('active');
      }
    })

    // click outisde offcanvas
    jQuery(document).mouseup(function (e) {
      var container = $(".site-mobile-menu");
      if (!container.is(e.target) && container.has(e.target).length === 0) {
        if (jQuery('body').hasClass('offcanvas-menu')) {
          jQuery('body').removeClass('offcanvas-menu');
        }
      }
    });
  };
  siteMenuClone();

});


$(function () {
  $('.has-children > a').on("click", function (e) {
    e.preventDefault();
  });
});

var gallery;
var lightbox;

function filterIsotope(filter, gallery) {
  var filter = filter || '*';
  gallery.isotope({
    filter: filter,
  });
}

function lightboxFilter(filter, lightbox) {
  var filter = filter || '*';
  lightbox.magnificPopup({
    delegate: filter + '>a',
    type: 'image',
    gallery: {
      enabled: true,
    }
  });
}


$(window).load(function () {

  gallery = $('.artist-gallery');
  lightbox = $('.image-gallery');
  image_gallery = $('.image-gallery');

  filterIsotope(".all", gallery);
  filterIsotope(".gallery-image", image_gallery);
  lightboxFilter("*", lightbox);

});

jQuery(document).on("click", ".masonry-filter-list li", function(){
  
  jQuery(".masonry-filter-list li").removeClass("active");
  jQuery(this).addClass("active");
  var category = "." + jQuery(this).data("filter");
  filterIsotope(category, gallery);
})



jQuery(".heroContentDiv").ready(function(){

  masterTL = gsap.timeline()
      // .from('.heroContentCodruLogo', {duration: 1, opacity: 0, scale: 1, ease: "in"})
      // .from('.heroContentPadureaBistra', {duration: 1, opacity: 0, scale: 1, ease: "in"})
      // .from(".underLocDate", {duration: 1, opacity: 0, scale: 1, ease: "in"})
      .from('.heroDescription', {duration: 1, opacity: 0, ease: "in"})

});

jQuery(document).ready(function(){
  var masterTL = gsap.timeline();

  jQuery(".valueRepeater").each(function(i) {
    var repeaterTL = gsap.timeline().from(this, {duration: 1, opacity: 0, scale: 1, ease: "in"});
    masterTL.add(repeaterTL);
  });

  // var ctrl = new ScrollMagic.Controller();

  // // Create scenes in jQuery each() loop
  // $(".valueRepeater").each(function(i) {
  //   var inner = $(this).find(".value-step");
  //   var outer = $(this).find(".value-text");
  //   var tl = new TimelineMax();
    
  //   tl.from(outer, 0.25, { scaleX: 0 });
  //   tl.from(inner, 0.65, { yPercent: 100, ease: Back.easeOut });
    
  //   new ScrollMagic.Scene({
  //     triggerElement: this,
  //     triggerHook: 0.15
  //   })
  //     .setTween(tl)
  //     .addIndicators({
  //       colorTrigger: "white",
  //       colorStart: "white",
  //       colorEnd: "white",
  //       indent: 40
  //     })
  //     .addTo(ctrl);
  // });
  

})



// var offset = jQuery(':target').offset();
// var scrollto = offset.top - 60; // minus fixed header height
// jQuery('html, body').animate({scrollTop:scrollto}, 0);