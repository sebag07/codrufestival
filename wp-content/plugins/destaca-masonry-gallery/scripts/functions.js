jQuery(function($) {
	var updateOnScroll = false;
	$('.dmg-gallery').each(function(i, el) {
		var $gallery = $(el);
		$gallery.isotope({
			itemSelector: 	'.dmg_masonry_gallery_item',
			masonry : {
				columnWidth: 	'.dmg-gallery-sizer',
				gutter : 		'.dmg-gutter-sizer',
				percentPosition: true
			}
		});

		$gallery.imagesLoaded(function() {
			$gallery.isotope('layout');
		});

		$gallery.imagesLoaded().progress( function() {
			$gallery.isotope('layout');
		});

		$gallery.on('layoutComplete', function(){
	        $(window).trigger("scroll");
	    });

	    $('img.lazyload').load(function() {
		    $gallery.isotope('layout');
		});

		if ($gallery.hasClass('dmg-modal-gallery')) {
			var legend = $gallery.hasClass('dmg-modal-gallery-with-title');
			$gallery.magnificPopup({
				delegate: 'a.dmg_masonry_gallery_item',
				type: 'image',
				closeOnContentClick: false,
				closeBtnInside: false,
				mainClass: 'mfp-with-zoom mfp-img-mobile',
				image : {
					titleSrc : function(item) {
						if ( legend ) {
							var title = item.el.attr('data-title') || '' ;
							var caption = item.el.attr('data-caption') || '' ;
							if ( $gallery.hasClass('dmg-modal-title__title_caption')) {
								return title + '<br><small>' + caption + '</small>'; 
							} else if ( $gallery.hasClass('dmg-modal-title__in_title')) {
								return title + '<br><small>' + caption + '</small>'; 
							} else if ( $gallery.hasClass('dmg-modal-title__in_title_caption')) {
								return title + '<br><small>' + caption + '</small>'; 
							} else
								return title;
						} 
					}
				},
				gallery: {
					enabled: true,
					tPrev: '',
					tNext: '',
					tCounter: ''
				},
				callbacks: {
					open: function() {
						if ( $gallery.hasClass('dmg-modal-title__in_title') || $gallery.hasClass('dmg-modal-title__in_title_caption') ) {
							$(this.container).addClass('dmg-modal-in-data');
						}
					}
				}
			});
		}

		/*if ($gallery.find('.dmg_masonry_gallery_item').length > 12)
			updateOnScroll = true;*/

		$gallery.on('mfpMarkupParse', function() {
			var items = [],
				$container = jQuery.magnificPopup.instance.ev,
				withFilter = false,
				withPage = false;
			
			if ($container.find('.dmg-in-filter').length)
				withFilter = true;
			if ($container.find('.dmg-out-page').length)
				withPage = true;

			var images = $container.find('.dmg_masonry_gallery_item')
							
			for (var i = 0; i < images.length; i++) {
				var item = images[i],
					$i = jQuery(item),
					add = true;
				if ($i.hasClass('pinit-button')) {
					add = false;
				}

				if (withFilter && !$i.hasClass('dmg-in-filter')) {
					add = false;
				}
				if (withPage && $i.hasClass('dmg-out-page')) {
					add = false;
				}
				if (add)
					items.push(item);
			}

			if (jQuery.magnificPopup.instance.index > items.length) {
				var index = 0;
				for (var i = 0; i < items.length; i++) {
					if (jQuery.magnificPopup.instance.currItem.src == items[i].href)
						index = i;
				}
				jQuery.magnificPopup.instance.index = index;
			}
			
			jQuery.magnificPopup.instance.items = items;
		});
	});

	if (updateOnScroll) {
		var scrollTO = null;
		$(window).scroll(function() { 
			clearTimeout(scrollTO);
			scrollTO = setTimeout(function() {
				$('.dmg-gallery').isotope('layout');
			}, 300);
		});
	}

	$(document).on('lazyloaded', function(e) {
		var $gallery = $(e.target).parents('.dmg-gallery');
		if ($gallery.length) {
			$gallery.isotope('layout');
		}
	});

	$('.dmg-gallery-filter').on('click', function() {
		var $this = $(this),
			value = $this.attr('data-value'),
			$wrapper = $this.parents('.dmg-gallery-wrapper'),
			$filter = $wrapper.find('.dmg-gallery-filter-categories');
			
		if ($this.hasClass('dmg-filter-active'))
			return;

		$filter.attr('data-value', value);
		$filter.find('.dmg-filter-active').removeClass('dmg-filter-active');
		$this.addClass('dmg-filter-active');

		executeFilter('category', $wrapper);
		
	});

	$('.dmg-gallery-page-button').on('click', function() {
		var $this = $(this),
			page = $this.attr('data-value'),
			$wrapper = $this.parents('.dmg-gallery-wrapper'),
			$paginator = $wrapper.find('.dmg-gallery-pagination');

		
		if (page == $paginator.attr('data-value'))
			return;

		$paginator.attr('data-value', page);
		$paginator.find('.dmg-gallery-page-button-active').removeClass('dmg-gallery-page-button-active');
		$this.addClass('dmg-gallery-page-button-active');

		executeFilter('page', $wrapper);
	});


	function executeFilter(type, $wrapper) {
		var $filter = $wrapper.find('.dmg-gallery-filter-categories'),
			withFilter = $filter.length > 0,
			category = null,
			
			$paginator = $wrapper.find('.dmg-gallery-pagination'),
			withPagination = $paginator.length > 0,
			page = 0,
			pageSize = 0,
			
			$gallery = $wrapper.find('.dmg-gallery'),
			items = $gallery.find('.dmg_masonry_gallery_item');

		$gallery.find('.dmg-out-page').removeClass('dmg-out-page');
		items.addClass('dmg-in-filter');

		if (withFilter) {
			category = $filter.attr('data-value');
			$.each(items, function(index, item) {
				var $item = $(item);
				var categories = $item.attr('data-categories')
				if (category && categories.split(',').indexOf(category) < 0)
					$item.removeClass('dmg-in-filter');
			});
		}

		if (withPagination) {

			pageSize = $paginator.attr('data-page-size');

			if (type == 'category') {
				$paginator.attr('data-value', 1);
				$paginator.find('.dmg-page-disabled').removeClass('dmg-page-disabled');
				$paginator.find('.dmg-gallery-page-button-active').removeClass('dmg-gallery-page-button-active');
				$paginator.find('.dmg-gallery-page-button').eq(0).addClass('dmg-gallery-page-button-active');

				var categoryTotal = $gallery.find('.dmg-in-filter').length,
					categoryPages = Math.ceil(categoryTotal/pageSize);
				$paginator.find('.dmg-gallery-page-button').each(function(i, button) {
					if (i >= categoryPages)
						$(button).addClass('dmg-page-disabled');
				});
			}

			page = $paginator.attr('data-value');
			var min = pageSize * (page - 1),
				max = pageSize * page;
				filteredItems = [];
			if (category) {
				filteredItems = items.filter('.dmg-in-filter');
			} else {
				filteredItems = items;
			}

		 	$.each(filteredItems, function(index, item) {
				if (index < min || index >= max)
					$(item).removeClass('dmg-in-filter');
			});
		}	

		$([document.documentElement, document.body]).animate({
	        scrollTop: $gallery.offset().top - 100
	    }, 750);

	    $gallery.addClass('execute-filter');	

		$gallery.isotope({ filter: function() {
				return $(this).hasClass('dmg-in-filter');
			} 
		});
	}

	lazyload();
});