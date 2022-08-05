(function($) {
	"use strict";
    var HT = {};
	
	
	/* MAIN VARIABLE */
	
    var $window            		= $(window),
		$document           	= $(document),
		$niceSelect        		= $(".nice-select"),
		$countDownTimer     	= $('.countdown-timer'),
		$homepageDeal			= $('.homepage-deal .owl-carousel'),
		$homepageCat			= $('.homepage-category .owl-carousel'),
		$homepagePromo			= $('.homepage-saleoff .owl-carousel'),
		$homepageMostViewd		= $('.homepage-view .owl-carousel'),
		$footerPost				= $('.widget-container .owl-carousel'),
		$homepageProduct		= $('.homepage-product .owl-carousel'),
		$galleryThumbs     		 = $(".gallery-with-thumbs"),
		$priceRange        	 	= $("#price_slider");
	
	
	// Check if element exists
    $.fn.elExists = function() {
        return this.length > 0;
    };
	
	

	
	// Check if element exists
    HT.niceInit = function() {
        $niceSelect.niceSelect();
    };

	/*
		COUNT DOWN SETTING
	*/
	HT.countDown = function() {
		if ($countDownTimer.elExists()) {

			var countInstances = [];
			$countDownTimer.each(function(index, element) {

				var $this = $(this);

				// Fetching from data attibutes
				var year    = $this.attr("data-countdown-year") ? $this.attr("data-countdown-year") : 2019;
				var month   = $this.attr("data-countdown-month") ? $this.attr("data-countdown-month") : 6;
				var day     = $this.attr("data-countdown-day") ? $this.attr("data-countdown-day") : 28;

				// Adding instances for multiple use
				$this.addClass("instance-0" + index);

				// Initializing the count down
				countInstances[index] = simplyCountdown(".instance-0" + index, {
					year: year,
					month: month,
					day: day,
					words: {                            // Words displayed into the countdown
						days: 'day',
						hours: 'hr',
						minutes: 'min',
						seconds: 'sec',
						pluralLetter: 's'
					},
					plural: true,                       // Use plurals
					inline: false,
					enableUtc: false,
					refresh: 1000,                      // Default refresh every 1s
					sectionClass: 'countdown-section',  // Section css class
					amountClass: 'countdown-amount',    // Amount css class
					wordClass: 'countdown-word'         // Word css class
				});
			});
		}
	};
	
	/************************************************************
        Product Gallery with Thumbnails
    *************************************************************/

    HT.galleryWithThumb = function() {
        if ($galleryThumbs.elExists()) {

            // Params
            var mainSliderSelector = '.main-slider',
                navSliderSelector = '.nav-slider';

            // Main Slider
            var mainSliderOptions = {
                effect: 'fade',
                loop: true,
                speed:1000,
                loopAdditionalSlides: 10,
                watchSlidesProgress: true,
                observer: true,
                observeParents: true
            };
            var mainSlider = new Swiper(mainSliderSelector, mainSliderOptions);

            // Navigation Slider
            var navSliderOptions = {
                loop: true,
                loopAdditionalSlides: 10,
                speed:1000,
                slidesPerView: 3,
                centeredSlides: true,
                spaceBetween: 15,
                autoplay: {
                    delay: 100000000
                },
                touchRatio: 0.2,
                grabCursor: true,
                slideToClickedSlide: true,

                navigation: {
                    nextEl: '.swiper-arrow.next',
                    prevEl: '.swiper-arrow.prev'
                },

                // Responsive breakpoints
                breakpoints: {
                    479: {
                        autoplay: {
                            delay: 5000
                        }
                    }
                }
            };
            var navSlider = new Swiper(navSliderSelector, navSliderOptions);

            // Connecting the sliders
            mainSlider.controller.control = navSlider;
            navSlider.controller.control = mainSlider;

            // Updating slider in modal
            $('body').on('show.bs.modal', '#product_quick_view', function() {
                setTimeout(function() {
                    navSlider.update();
                    mainSlider.update();
                }, 500);
            });
        }
    };

	
	/************************************************************
        Price Range Slider
    *************************************************************/

    HT.rangeSlider = function() {
        if ($priceRange.elExists()) {
        	let post_min_price = $( "#min_price" ).val();
        	post_min_price = parseInt(post_min_price)
        	let post_max_price = $( "#max_price" ).val();
        	post_max_price = parseInt(post_max_price)

        	let min_price = parseInt($( "#min_price" ).attr('data-min'));
        	let max_price = parseInt($( "#max_price" ).attr('data-max'));
            $priceRange.slider({
                range: true,
                min: min_price,
                max: max_price,
                values: [ post_min_price, post_max_price ],
                slide: function( event, ui ) {
                	console.log(ui.values[ 0 ]);
                    $( "#min_price" ).val(addCommas(ui.values[ 0 ]) + 'đ');
                    $( "#max_price" ).val(addCommas(ui.values[ 1 ]) + 'đ');

                    
                    $('.lds-css').removeClass('hidden');
                    console.log(1);
					let page = $('.pagination .uk-active span').text();
					get_list_object(page);
					$('.lds-css').addClass('hidden');
                }
            });
            $( "#min_price" ).val(addCommas(post_min_price) + 'đ'  );
		    $( "#max_price" ).val(addCommas(post_max_price) + 'đ');
        }
    };
	
	
	HT.catCarousel = function() {
		$homepageCat.owlCarousel({
			margin: 30,
			lazyLoad:true,
			nav: true,
			autoplay: true,
			smartSpeed: 1000,
			autoplayTimeout: 5000,
			dots: false,
			loop: true,
			responsive: {
			  0: {
				items: 1
			  },
			  600: {
				items: 2
			  },
			  1000: {
				items: 3
			  }
			}
		});
	}
	
	HT.productDeal = function() {
		$homepageDeal.owlCarousel({
			margin: 30,
			lazyLoad:true,
			nav: true,
			autoplay: true,
			smartSpeed: 1000,
			autoplayTimeout: 5000,
			dots: false,
			loop: true,
			responsive: {
			  0: {
				items: 1
			  },
			  600: {
				items: 2
			  },
			  1000: {
				items: 4
			  }
			}
		});
	}
	HT.Product = function() {
		$homepageProduct.owlCarousel({
			margin: 30,
			lazyLoad:true,
			nav: true,
			autoplay: true,
			smartSpeed: 1000,
			autoplayTimeout: 5000,
			dots: false,
			loop: true,
			responsive: {
			  0: {
				items: 1
			  },
			  600: {
				items: 2
			  },
			  1000: {
				items: 4
			  }
			}
		});
	}
	HT.ProductPromo = function() {
		$homepagePromo.owlCarousel({
			margin: 30,
			lazyLoad:true,
			nav: true,
			autoplay: true,
			smartSpeed: 1000,
			autoplayTimeout: 5000,
			dots: false,
			loop: true,
			responsive: {
			  0: {
				items: 1
			  },
			  600: {
				items: 1
			  },
			  1000: {
				items: 1
			  }
			}
		});
	}
	HT.ProductMostView = function() {
		$homepageMostViewd.owlCarousel({
			margin: 30,
			lazyLoad:true,
			nav: true,
			autoplay: true,
			smartSpeed: 1000,
			autoplayTimeout: 5000,
			dots: false,
			loop: true,
			responsive: {
			  0: {
				items: 1
			  },
			  600: {
				items: 2
			  },
			  1000: {
				items: 2
			  }
			}
		});
	}
	
	HT.footerPost = function() {
		$footerPost.owlCarousel({
			margin: 30,
			lazyLoad:true,
			nav: true,
			autoplay: true,
			smartSpeed: 1000,
			autoplayTimeout: 5000,
			dots: false,
			loop: true,
			responsive: {
			  0: {
				items: 1
			  },
			  600: {
				items: 1
			  },
			  1000: {
				items: 1
			  }
			}
		});
	}
	

  // Document ready functions
    $document.on('ready', function() {
        HT.niceInit(),    
        HT.countDown(),    
        HT.catCarousel(),     
        HT.productDeal(),     
        HT.Product(),     
        HT.ProductPromo(),     
        HT.ProductMostView(),     
        HT.footerPost(),
		HT.galleryWithThumb(),
		HT.rangeSlider();		
    });
	
})(jQuery);


function addCommas(nStr){
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + '.' + '$2');
    }
    return x1 + x2;
}

