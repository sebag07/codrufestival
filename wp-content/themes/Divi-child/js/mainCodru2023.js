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


$(window).load(function () {

  var gallery = $('.gallery-masonry');
  var category = '.item';
  function filterIsotope(filter) {
    var filter = filter || '*';
    gallery.isotope({
      filter: filter,
    });
  }

  var lightbox = $('.gallery-masonry');
  function lightboxFilter(filter) {
    var filter = filter || '*';
    lightbox.magnificPopup({
      delegate: filter + '>a',
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
jQuery('[data-category]').on('click', function () {
  var category = $(this).data('category');
  filterIsotope(category);
  lightboxFilter(category);
});


jQuery(document).ready(function(){

  masterTL = gsap.timeline()
      .from('.heroContentCodruLogo', {duration: 1, opacity: 0, scale: 1, ease: "in"})
      .from('.heroContentPadureaBistra', {duration: 1, opacity: 0, scale: 1, ease: "in"})
      .from(".underLocDate", {duration: 1, opacity: 0, scale: 1, ease: "in"})
      .from('.heroDescription', {duration: 1, opacity: 0, ease: "in"})

});