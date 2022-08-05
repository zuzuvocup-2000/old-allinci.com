function vh_init(){
	// First we get the viewport height and we multiple it by 1% to get a value for a vh unit
	var vh = window.innerHeight * 0.01;
	// Then we set the value in the --vh custom property to the root of the document
	document.documentElement.style.setProperty('--vh', `${vh}px`);
}

$(document).ready(function() {
	vh_init();
});


$(document).on('click', '.va-offcanvas-li', function() {
	let _this = $(this);
	let val = _this.attr('title');

	$('.va-list li').removeClass('uk-active');

	$('.va-list li').each(function(){
		let sub = $(this).find('a').attr('title');
		if(val == sub){
			$(this).addClass('uk-active');
		}
	})

	return false;
});

(function($) {
	"use strict";
	var HT = {};

	var time = 100;
	/* MAIN VARIABLE */

	var $window            		= $(window),
		$document           	= $(document),
		$niceSelect        		= $(".nice-select"),
		$countDownTimer     	= $('.countdown-timer'),
		$owl					= $('.owl-slide .owl-carousel'),
		$cart					= $('#cart');

	// Check if element exists
	$.fn.elExists = function() {
		return this.length > 0;
	};


	// Check if element exists
	HT.niceInit = function() {
		$niceSelect.niceSelect();
		$(document).on('mouseenter', '.nice-select .mCSB_scrollTools', function(event) {
		  var $dropdown = $(this).parents('.nice-select');
		  $dropdown.addClass('open_scroll');
		});
		$(document).on('click.nice_select', function(event) {
		  if ($(event.target).closest('.nice-select').length === 0) {
			$('.nice-select').removeClass('open_scroll');
			setTimeout(function() { $('.nice-select').removeClass('open'); }, 50);
		  }
		});
		$(document).on('click.nice_select', '.nice-select .option:not(.disabled)', function(event) {
		  $('.nice-select').removeClass('open_scroll open');
		  setTimeout(function() { $('.nice-select').removeClass('open'); }, 50);
		});
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
	   CART LABEL CHECKED
	*************************************************************/

	HT.CartLabel = function() {
		if ($cart.elExists()) {
			var $label = '.cart-label';
			var $cartPayment = '.cart-payment';
			var $cartTransport = '.cart-transport';
			var $radioLabel = '.cart-radio';
			var $lbTitle = '.lb-title';


			$(document).on('click', $label,function(){
				let _this = $(this);
				_this.toggleClass('checked');
				if(_this.parents('.option-1').find('input:checked').length){
					_this.parents('.option-1').find('input').prop( "checked", false );
				}else{
					_this.parents('.option-1').find('input').prop( "checked", true );
				}
				_this.parents('.option-1').find('.extend').toggleClass('uk-hidden');
			});

			$(document).on('click', $radioLabel,function(){
				let _this = $(this);
				_this.parents($cartPayment).find($radioLabel).removeClass('checked');
				_this.parents($cartPayment).find('.extend').addClass('uk-hidden');

				_this.addClass('checked');
				// if(_this.parents('.option-2').find('input:checked').length){
				// 	_this.parents('.option-2').find('input').prop( "checked", false );
				// }else{
					_this.parents('.option-2').find('input').prop( "checked", true );
				// }
				_this.parents('.option-2').find('.extend').removeClass('uk-hidden');
			});

			$(document).on('click', $lbTitle,function(){
				let _this = $(this);
				_this.siblings('.label').trigger('click');
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
					$( "#min_price" ).val(addCommas(ui.values[ 0 ]) + 'Ä‘');
					$( "#max_price" ).val(addCommas(ui.values[ 1 ]) + 'Ä‘');


					$('.lds-css').removeClass('hidden');
					
					let page = $('.pagination .uk-active span').text();
					get_list_object(page);
					$('.lds-css').addClass('hidden');
				}
			});
			$( "#min_price" ).val(addCommas(post_min_price) + 'Ä‘'  );
			$( "#max_price" ).val(addCommas(post_max_price) + 'Ä‘');
		}
	};

	HT.owl = function() {
		let owl = $(this);
		$owl.each(function(key, value){
			let _this = $(this);
			let owlInit = _this.attr('data-option');
			owlInit = atob(owlInit);
			owlInit = JSON.parse(owlInit);
			_this.owlCarousel(owlInit);
		});
	};


  // Document ready functions
	$document.on('ready', function() {
		HT.niceInit(),
		HT.CartLabel(),
		HT.owl();
	});

})(jQuery);


$(document).ready(function() {
	var wd_width = $(window).width();
	if(wd_width > 1220) {
		var wow = new WOW().init();
	}

	$('.lightBoxGallery').lightGallery({
		selector: '.light_image',
	});

	if($('.main-menu>li>a').length){
		  $('.main-menu>li>a').click(function(e) {
			e.preventDefault();
			let _this = $(this);
			var target = _this.attr('href');
			var offset = 0;
			var offsetLeft = 0;
			var targetLeft = _this.closest('li.uk-active');
			var time = 0;

			clearTimeout(time);

			time = setTimeout(function(){
				if(typeof $(target).offset() !== 'undefined'){
					offset = $(target).offset().top;
				}
				// offsetLeft = $(targetLeft).offset().left;
				console.log(targetLeft);

				$('html, body').animate({scrollTop: offset - 60 - 100}, 300);
				$('.mb_hd_menu').animate({scrollLeft: offsetLeft}, 300);
				// setTimeout(function(){
				// }, 500);
			}, 500);

			

			console.log(target);
			// console.log(offset);
		});
	}
	var scroll_left = 0;
	$('.mb_hd_menu .uk-overflow-container').scroll(function(event) {
		/* Act on the event */
		scroll_left = $(this).scrollLeft();
	});

	if($('.nav-tabs>li>a').length){
		$('.nav-tabs>li>a').click(function(e) {
			e.preventDefault();
			let _this = $(this);
			var target = _this.attr('href');
			var offset = 0;
			var offsetLeft = 0;
			var targetLeft = _this.closest('ul').find('li.uk-active');
			var time = 0;
			console.log($(targetLeft).offset().left);
			// console.log($(targetLeft).offset().left - scroll_left);
			clearTimeout(time);

			time = setTimeout(function(){
				if(typeof $(target).offset() !== 'undefined'){
					offset = $(target).offset().top;
				}
				offsetLeft = $(targetLeft).offset().left;
				// offsetLeft = scroll_left;
				// console.log(scroll_left);
				// console.log(offsetLeft);

				$('html, body').animate({scrollTop: offset - 60 - 100}, 300);
				// $('.mb_hd_menu .uk-overflow-container').animate({scrollLeft: offsetLeft - 30}, 300);
				// setTimeout(function(){
				// }, 500);
			}, 500);

			

			// console.log(target);
			// console.log(offset);
		});
	  }

	  

	$(document).on('click', '.abate', function(event) {
		event.preventDefault();

		let _this = $(this);
		let input = _this.parent().find('.quantity');

		let qty = parseInt(input.val());

		if(qty <= 1){
			qty = 1;
		}else{
			qty -= 1;
		}

		input.val(qty);
		$('.js_buy').attr('data-quantity', qty);


		return false;
	});

	$(document).on('click', '.augment', function(event) {
		event.preventDefault();
		let _this = $(this);
		let input = _this.parent().find('.quantity');

		let qty = parseInt(input.val());

		qty += 1;

		input.val(qty);
		$('.js_buy').attr('data-quantity', qty);

		return false;
	});

	var time;
	

	// $(document).on('click' , '.uk-slidenav.uk-slidenav-contrast' , function(e){
	// 	e.preventDefault();
	// 	let _this = $(this);

	// 	wow1 = new WOW(
	// 		{
	// 		boxClass:     'wow1',      // default
	// 		animateClass: 'animated', // default
	// 		offset:       0,          // default
	// 		mobile:       true,       // default
	// 		live:         true        // default
	// 		}
	// 	);
	// 	wow1.init();
 //    });
});


 $(window).load(function() {
	var time = 0;

	$(document).on('submit', '#form-baogia', function(){
		let _this = $(this);
		let loader = _this.find('.bg-loader');
		loader.show();

		let prd_name = _this.find('.order_prd_name').val();
		let fullname = _this.find('.order-fullname').val();
		let email = _this.find('.order-email').val();
		let phone = _this.find('.order-phone').val();
		let message = _this.find('.order-message').val();

		let data = $(this).serializeArray();
		let ajaxUrl = 'contact/ajax/contact/contact_baogia';


		clearTimeout(time);

		// console.log(1);
		// return false;


		//gửi ajax
		time = setTimeout(function(){
			$.ajax({
				method: "POST",
				url: ajaxUrl,
				data: {
					data: data,
					prd_name: prd_name, 
					fullname: fullname, 
					email: email, 
					phone: phone, 
					message: message,
				},
				dataType: "json",
				cache: false,
				success: function(json){
					loader.hide();
					if(json.error.flag == 1){
						_this.find('.error').removeClass('hidden');
						_this.find('.error .alert').html(json.error.message);
					}else{
						_this.find('.error').addClass('hidden');
						_this.find('.input-reset').val('');
						location.reload();
					}
				}
			});
		}, 300);

		return false;
	});


	var time = 0;

	$(document).on('submit', '#register_form', function(){
		let _this = $(this);
		let loader = _this.find('.bg-loader');
		loader.show();

		let postData = _this.serializeArray();
		let fullname =  _this.find('.fullname').val();
		let phone =  _this.find('.phone').val();
		let message =  _this.find('.message').val();

		let ajaxUrl = 'contact/ajax/contact/contact_register';


		clearTimeout(time);

		// console.log(1);
		// return false;


		//gửi ajax
		time = setTimeout(function(){
			$.ajax({
				method: "POST",
				url: ajaxUrl,
				data: {
					data: postData,
					fullname: fullname, 
					phone: phone, 
					message: message,
				},
				dataType: "json",
				cache: false,
				success: function(json){
					loader.hide();
					if(json.error.flag == 1){
						_this.find('.error').removeClass('hidden');
						_this.find('.error .alert').html(json.error.message);
					}else{
						_this.find('.error').addClass('hidden');
						_this.find('.input-reset').val('');
						location.reload();
					}
				}
			});
		}, 300);

		return false;
	});


	// var h_header = $('.pc-header').outerHeight();
	// var target;

	// $(window).scroll(function(){
	// 	let scroll = $(window).scrollTop();
	// 	if (scroll >= h_header) {
	// 		$('body').addClass('fixed-header');
	// 		$('.pc-header').addClass('fixed');
	// 	}else{
	// 		$('body').removeClass('fixed-header');
	// 		$('.pc-header').removeClass('fixed');
	// 	}
	
	 // 	$('.scroll-menu').each(function(i, e){
	 // 		let id = $(e).attr('id');
	 // 		target = $(e).offset().top;
	 // 		if(target <= scroll){
	 // 			$('.main-menu li> a').removeClass('active');
	 // 			$('.main-menu li> a[href="#'+id+'"]').addClass('active');
	 // 		}
	 // 	});
	// });

	owl_intilize(); //slide
	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//+++++++++++++++++++++++++++++++GENANER++++++++++++++++++++++++++++++++
	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

	$(document).on('change','#city',function(e, data){
		let _this = $(this);
		let param = {
			'parentid' : _this.val(),
			'select' : 'districtid',
			'table'  : 'vn_district',
			'trigger_district': (typeof(data) != 'undefined') ? true : false,
			'text'   : 'Chọn Quận/ Huyện',
			'parentField'  : 'provinceid',
		}
		getLocation(param, '#district');
	});
	if(typeof(cityid) != 'undefined' && cityid != ''){
		$('#city').val(cityid).trigger('change', [{'trigger':true}]);
	}
	$(document).on('change','#district', function(e, data){
		let _this = $(this);
		let param = {
			'parentid' : _this.val(),
			'select' : 'wardid',
			'trigger_ward': (typeof(data) != 'undefined') ? true : false,
			'table'  : 'vn_ward',
			'text'   : 'Chọn Phường/ Xã',
			'parentField'   : 'districtid',
		}
		getLocation(param, '#ward');
	});


	$(document).on('change','#city_receive',function(e, data){
		let _this = $(this);
		let param = {
			'parentid' : _this.val(),
			'select' : 'districtid',
			'table'  : 'vn_district',
			'trigger_district': (typeof(data) != 'undefined') ? true : false,
			'text'   : 'Chọn Quận/ Huyện',
			'parentField'  : 'provinceid',
			'district' : districtid_receive,
			'ward' : wardid_receive,
		}

		getLocation(param, '#district_receive');
	});
	if(typeof(cityid_receive) != 'undefined' && cityid_receive != ''){
		$('#city_receive').val(cityid_receive).trigger('change', [{'trigger':true}]);
	}
	$(document).on('change','#district_receive', function(e, data){
		let _this = $(this);
		let param = {
			'parentid' : _this.val(),
			'select' : 'wardid',
			'trigger_ward': (typeof(data) != 'undefined') ? true : false,
			'table'  : 'vn_ward',
			'text'   : 'Chọn Phường/ Xã',
			'parentField'   : 'districtid',
			'district' : districtid_receive,
			'ward' : wardid_receive,
		}
		getLocation(param, '#ward_receive');
	});


	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	// +++++++++++++++++Xá»¨ LĂ JS á» TRANG HOME++++++++++++++++++++++++++++
	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

	$(function() {
		$('.lazy').lazy();
	});

	//+++++++++láº¥y dá»¯ liá»‡u Ä‘á»• vĂ o popup chi tiáº¿t sp á»Ÿ trang home+++++++++
	$(document).on('click' ,'.js_prd_popup',function(){
		let _this = $(this);
		let id = _this.attr('data-id');
		let ajax_url = 'homepage/ajax/home/prdPopup';
		$.ajax({
			url : ajax_url,
			type : "post",
			cache: 	false ,
			dataType:"text",
			data : {
				id: id
			},
				success : function (result){
				let json = JSON.parse(result);
				setTimeout(function(){
					$('#sync3').attr('src',json.prd_list_image1);
					// $('#sync1').html('').html(json.prd_list_image);
					// $('#sync2').html('').html(json.prd_list_image);
					$('.prd-title').html('').html(json.prd_title);
					$('.js_result_attr').html(json.js_addtribute);
					$('.wrap-info').html('').html(json.js_info);
					if(json.js_block_promotional !=''){
						$('.prd-buy').after('<div class="js_block_promotional"></div>');
						$('.js_block_promotional').html('').html(json.js_block_promotional);
					}
					$('.js_wholesale').html('');
					$('.js_wholesale').after(json.js_wholesale);
					$('.js_addtribute').after('<div id="js_prd_info"></div>');
					$('#js_prd_info').attr('data-info', json.data_info);
					$('#js_prd_info').attr('data-price', json.price);
					$('#js_prd_info').attr('data-price_sale', json.price_sale);
					$('#js_prd_info').attr('data-id', json.id);
					$('#js_prd_info').attr('data-name', json.title);
					owl_intilize('#sync1', '#sync2');
					$('.js_block_promotional input:eq(0)').attr('checked', true);
				},100)
				owl_intilize('#sync1', '#sync2');

			}
		});
	})


	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//+++++++++++++++++++++Xá»¬ LĂ á» TRANG CHI TIáº¾T SP ++++++++++++++++++++++
	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

	// +++++++++++++hiá»ƒn thá»‹ láº¡i giĂ¡ cho sáº£n pháº©m+++++++++++++
	if($('#js_prd_info').length){
		render_price()
	}

	// khi thay Ä‘á»•i phiĂªn báº£n thuá»™c tĂ­nh, sá»‘ lÆ°á»£ng, chÆ°Æ¡ng trĂ¬nh khuyáº¿n máº¡i
	//  thĂ¬ cáº­p nháº­t láº¡i giĂ¡ cho sáº£n pháº©m
	$(document).on('click' ,'.js_addtribute .js_btn_choose' ,function(){
		let _this = $(this);
		_this.parent().find('.js_choose').removeClass('js_choose');
		_this.addClass('js_choose');
		render_price();
	})
	$(document).on('click' ,'.js_quantity_minus' ,function(){
		let _this = $(this);
		let qty = _this.parent().find('.js_quantity').val();
		if(qty == 0){
			return false;
		}
		_this.parent().find('.js_quantity').val(sub(qty, 1));
		render_price();
	})
	$(document).on('click' ,'.js_quantity_plus' ,function(){
		let _this = $(this);
		let qty = _this.parent().find('.js_quantity').val();
		_this.parent().find('.js_quantity').val(sum(qty, 1));
		render_price();
	})
	$(document).on('click change' ,'.js_quantity' ,function(){
		render_price()
	})
	$(document).on('change ' ,'.js_block_promotional input' ,function(){
		render_price()
	})

	// ++++++++++++++++Khi nháº¥n mua hĂ ng, thĂªm vĂ o giá» hĂ ng++++++++++++++++
	$(document).on('click touch' ,'.js_buy' ,function(){
		render_price()
		let _this = $(this);
		conditon = _this.attr('data-conditon');
		if(conditon == 'true'){
			let param = {
				'id' : _this.attr('data-id'),
				'quantity' : _this.attr('data-quantity'),
				'attrids'  : _this.attr('data-attrids'),
				'promotionalid': _this.attr('data-promotionalid'),
				'name': _this.attr('data-name'),
				'content': _this.attr('data-content'),
			}
			let ajax_url = 'cart/ajax/cart/addCart';
			$.ajax({
				url : ajax_url,
				type : "post",
				cache: 	false ,
				dataType:"text",
				data : {
					content: param.content, name: param.name, quantity: param.quantity, attrids: param.attrids, promotionalid: param.promotionalid, id: param.id
				},
					success : function (result){
					let json = JSON.parse(result);
					if(json.result == "true"){
						toastr.success('Thêm sản phẩm vào giỏ hàng thành công','');
						$('.js_total_item_cart').html(json.total_cart);

						if(_this.attr('data-redirect') == "true"){
							let time = setTimeout(function(){
								window.location = "/thanh-toan";
							}, 500);
						}
					}else{
						toastr.error('Đã xảy ra lỗi','');
					}
				}
			});
			
		}else{
			toastr.error('Bạn phải chọn chương trình khuyến mãi hoặc phiên bản (nếu có)','');
		}
		return false;
	});

	// +++++++++Khi thĂªm nhanh sáº£n pháº©m Ä‘Æ°á»£c táº·ng 100% vĂ o giá» hĂ ng+++++++++
	$(document).on('click' ,'.ajax_add_prd_gift' ,function(){
		let _this = $(this);
		let param = {
			'id' : _this.attr('data-id'),
			'quantity' : 1,
			'attrids'  : '',
			'promotionalid': '',
			'name': _this.attr('data-name'),
		}
		let ajax_url = 'cart/ajax/cart/addCart';
		$.ajax({
			url : ajax_url,
			type : "post",
			cache: 	false ,
			dataType:"text",
			data : {
				name: param.name,quantity: param.quantity, attrids: param.attrids, promotionalid: param.promotionalid, id: param.id
			},
				success : function (result){

				toastr.success('Thêm sản phẩm vào giỏ hàng thành công','');
				let ajax_url = 'cart/ajax/cart/refeshCart';
				$.ajax({
					url : ajax_url,
					type : "post",
					cache: 	false ,
					dataType:"text",
					data : {},
						success : function (data){
							let json = JSON.parse(data);
							if(json.result == true){
								$('.list-item').html(json.list_item);
								$('.total_quantity b').html(json.total_quantity);
								$('.cart-total .content').html(json.cart_total);
							}else{
								toastr.error('Có lỗi xảy ra, vui lòng thử lại','');
							}
					}
				});
			}
		});
	})
	$(document).on('change','select[name=cityid], select[name=districtid]' , function(){
		let _this = $(this);
		let ajax_url = 'cart/ajax/cart/render_discount_ship';
		clearTimeout(time);
		var time = setTimeout(function(){
			$.ajax({
				url : ajax_url,
				type : "post",
				cache: 	false ,
				dataType:"text",
				data : {
					cityid: $('select[name=cityid]').val(),
					districtid: $('select[name=districtid]').val(),
				},
					success : function (result){
						$('.js_discount_ship').html('-'+addCommas(result)+'đ')
						$('.js_discount_ship').attr('data-val', result)

						 // tính lại giá cuối cùng
						let discount_ship = $('.js_discount_ship').attr('data-val');
						let ship = $('.js_ship').attr('data-val');
						let totalCart = $('.js_cart_coupon').attr('data-val');
						let totalShip = sub(ship, discount_ship);
						if(totalShip < 0){
							totalShip = 0;
						}
						$('.js_total_ship').html('-'+addCommas(totalShip)+'đ');
						$('.js_cart_coupon').html('<b>'+addCommas(sum(totalCart, totalShip))+'đ</b>');


						// toastr.success('Bạn được giảm '+addCommas(result)+' tiền ship','');
				}
			});
		}, 500);


	})
	
	$(document).on('change','select[name=cityid], select[name=districtid]' , function(){
		let _this = $(this);
		let ajax_url = 'cart/ajax/cart/render_ship';
		clearTimeout(time);
		var time = setTimeout(function(){
			$.ajax({
				url : ajax_url,
				type : "post",
				cache: 	false ,
				dataType:"text",
				data : {
					cityid: $('select[name=cityid]').val(),
					districtid: $('select[name=districtid]').val(),
				},
					success : function (result){
						$('.js_ship').html(addCommas(result)+'đ')
						$('.js_ship').attr('data-val', result)

						 // tính lại giá cuối cùng
						let discount_ship = $('.js_discount_ship').attr('data-val');
						let ship = $('.js_ship').attr('data-val');
						let totalCart = $('.js_cart_coupon').attr('data-val');
						let totalShip = sub(ship, discount_ship);
						if(totalShip < 0){
							totalShip = 0;
						}
						$('.js_total_ship').html('-'+addCommas(totalShip)+'đ');
						$('.js_cart_coupon').html('<b>'+addCommas(sum(totalCart, totalShip))+'đ</b>');
				}
			});
		}, 500);
	})

	


	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//+++++++++++++++++++++Xá»¬ LĂ á» TRANG DANH Má»¤C SP ++++++++++++++++++++++
	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

	// khi chá»n thuá»™c tĂ­nh ta tiáº¿n hĂ nh load ra dá»¯ liá»‡u má»›i
	$(document).on('click','.attr' , function(){
		if($(this).find('input[name="attr[]"]:checked').length){
			$(this).find('input[name="attr[]"]').prop('js_choose', false);
			$(this).find('label a').addClass('js_choose');
		}else{
			$(this).find('input[name="attr[]"]').prop('js_choose', true);
			$(this).find('label a').removeClass('js_choose');
		}
		let attr = '';
		$('input[name="attr[]"]:checked').each(function(key, index){
			let id= $(this).val();
			let text= $(this).parent('div').text();
			let attr_id= $(this).attr('data-keyword');
			attr = attr + attr_id + ';' + id + ';';
		});
		// console.log(attr);
		$('#choose_attr > input').val(attr).change();
	})
	var time;
	$(document).on('change','.filter', function(){
		// $("html, body").animate({ scrollTop: 0 }, "400");
		// console.log(2);
		$('.lds-css').removeClass('hidden');
		let page = $('.pagination .uk-active span').text();

		clearTimeout(time);
		time = setTimeout(function(){
			get_list_object(page);
			$('.lds-css').addClass('hidden');
		},500);
		return false;
	});
	$(document).on('click','.pagination li span', function(){
		$("html, body").animate({ scrollTop: 0 }, "400");

		$('.lds-css').removeClass('hidden');
		let page = $(this).text();
		clearTimeout(time);
		time = setTimeout(function(){
			get_list_object(page);
			$('.lds-css').addClass('hidden');
		},500);
		return false;
	});

	$(document).on('click','.list-time .btn-time', function(){
		let _this = $(this);
		if(_this.hasClass('disable')){
			return false;
		}
		_this.parents('.list-time').find('.btn-time').removeClass('choose');
		_this.addClass('choose')
		let id = _this.attr('data-id');
		$("input[name=input_time]").val(id);
	});

 //    if($('.rating')){
	// 	rating();
	// }

});

// HĂ m tĂ­nh sá»‘ sao Ä‘Ă¡nh giĂ¡
function rating(start = 0, selector = '.rating', inputForm = 'input.data-rate'){
	var input = $(inputForm);
	var ratings = $(selector);
	for (var i = start; i < ratings.length; i++) {
		var r = new SimpleStarRating(ratings[i]);
		ratings[i].addEventListener('rate', function(e) {
			var numStar = e.detail; // tĂ­nh sá»‘ sao
			input.val(numStar);
			get_title_rate(numStar);
		});
	}
}

function get_title_rate(numStar = 0){
	let ajaxUrl = 'comment/ajax/comment/get_title_rate';
	$.ajax({
		method: "POST",
		url: ajaxUrl,
		data: {numStar: numStar},
		dataType: 'json',
		success: function(json){
			$('.title-rating').text(json.htmlReview);
		}
	});
}


function get_list_object(page){
	let keyword = $('.keyword').val();
	let perpage = $( ".js_perpage option:selected" ).text();
	if(perpage == 'undefined' || perpage == '' ){
		perpage = $('.js_perpage').val();
	}

	sort = $('.js_sort').val();
	if(sort == 'undefined' || sort == '' ){
		let sort = $( ".js_sort option:selected" ).text();
	}

	let catalogueid = $('#choose_attr').attr('data-catalogueid');

	let attr = $('input[name="attr"]').val();
	let brand = [];
	$('input[name="brand[]"]:checked').each(function(){
		brand.push($(this).val());
	});
	let min_price = $('#min_price').val();
	let length_min = min_price.length;
	min_price = min_price.substr(0, length_min - 1);
	min_price = min_price.replace(/\./gi, "");



	let max_price = $('#max_price').val();
	let length_max = max_price.length;
	max_price = max_price.substr(0, length_max - 1);
	max_price = max_price.replace(/\./gi, "");

	let param = {
		'page'    : page,
		'keyword' : keyword,
		'perpage' : perpage,
		'catalogueid' : catalogueid,
		'attr' : attr,
		'brand' : brand,
		'sort' : sort,
		'min_price' : min_price,
		'max_price' : max_price,
	}

	let pathname = window.location.pathname;
	// ?mod=course&view=main
	let href = pathname+'?';
	$.each( param, function( key, value ) {
		if(value != '' && value != undefined){
			href = href+key+'='+value+'&';
		}
	});
	history.pushState('', 'New URL: '+href, href);
	let ajaxUrl = 'product/ajax/frontend/get_list_prd_cat';
	$.get(ajaxUrl, {
		page: param.page,
		keyword: param.keyword,
		perpage: param.perpage,
		catalogueid: param.catalogueid,
		attr: param.attr,
		brand: param.brand,
		sort: param.sort,
		min_price: param.min_price,
		max_price: param.max_price,
		},
		function(data){
			let json = JSON.parse(data);
			$('#ajax-content').html(json.html);
			$('.pagination').html(json.pagination);
			$('.total_row').html(json.total_row);
			$('.from').html(json.from);
			$('.to').html(json.to);
			HT.countDown();
	});
};

function GetURLParameter(sParam){
	var sPageURL = window.location.search.substring(1);

	var sURLVariables = sPageURL.split('&');
	for (var i = 0; i < sURLVariables.length; i++) {
		var sParameterName = sURLVariables[i].split('=');
		if (sParameterName[0] == sParam) {
			return sParameterName[1];
		}
	}
}
function render_price(){
	let price = $('#js_prd_info').attr('data-price');
	let price_contact = $('#js_prd_info').attr('data-price_contact');
	let price_sale = $('#js_prd_info').attr('data-price_sale');
	let id = $('#js_prd_info').attr('data-id');
	let name = $('#js_prd_info').attr('data-name');
	var info = $('#js_prd_info').attr('data-info');
	info = window.atob(info);
	let json = JSON.parse(info);
	let quantity = $('.js_quantity').val();
	if(quantity == 'undefined' || quantity == '' ){
		quantity = 1;
	}

	attrids=new Array();
	let data_cart = '';
	let promotionalid = [];
	let conditionChoose = 1;
	let product_versionId = '';
	let wholesale = false;
	let content = '';
	if($('.js_addtribute .js_btn_choose').length){
		$('.js_addtribute .js_choose').each(function() {
			let attrid = $(this).attr('data-id');
			// console.log(attrid);
			let version = $(this).attr('data-version');
			if(typeof $(this).attr('data-content') != 'undefined' ){
				content = content + '</br>' + $(this).attr('data-content');
			}
			if(version == 0){
				return;
			}
			if(typeof attrid != 'undefined' ){
				if(attrid.indexOf('-') != -1){
					attrids = attrid.split('-');
				}else{
					attrids.push(attrid) ;
				}
			}

		});
		console.log(attrids);
		$('.js_addtribute option:selected').each(function() {
			let attrid = $(this).attr('data-id');
			let version = $(this).attr('data-version');
			content = content + '</br>' + $(this).attr('data-content');
			if(version == 0){
				return;
			}
			if(attrid.indexOf('-') != -1){
				attrids = attrid.split('-');
			}else{
				attrids.push(attrid);
			}
			// console.log(attrids);
		});

		if(attrids.length >=1){
			let product_version = json.product_version;
			if(product_version != ''){
				if(attrids.length == 1){
					product_version.forEach(function(item, index, array) {
						// console.log(item);
						// console.log(attrids);
						if(item.attribute1 == attrids[0] || item.attribute2 == attrids[0]	){
							price = item.price_version;
							product_versionId = item.id;
						}
					});
					price = price.trim();
				}else{
					product_version.forEach(function(item, index, array) {
						console.log(item);
						console.log(attrids);
						if((item.attribute1 == attrids[0] && item.attribute2 == attrids[1]) || (item.attribute2 == attrids[0] && item.attribute1 == attrids[1])){
							price = item.price_version;
							product_versionId = item.id;
						}
					});
					price = price.trim();
				}

			}
		}
	}
	if($('.js_wholesale .js_btn_choose').length){
		let quantity_start = '';
		json.product_wholesale.forEach(function(item, index, array) {
			if( parseFloat(quantity) >= item.quantity_start && parseFloat(quantity) <= item.quantity_end){
				price = item.price_wholesale;
				quantity_start = item.quantity_start
			}else{
				if(parseFloat(quantity) >= item.quantity_end){
					price = item.price_wholesale;
					quantity_start = item.quantity_start
				}
			}
			if(parseFloat(quantity) >= item.quantity_start){
				$('.js_block_promotional').find('input').prop('disabled', true);
				conditionChoose = 1;
				wholesale = true;
			}
		});
		$('.js_wholesale .js_btn_choose').removeClass('js_choose_wholesale')
		$('.js_wholesale .js_btn_choose').each(function() {
			let li_qty_start= $(this).attr('data-quantity_start');
			if(li_qty_start == quantity_start){
				$(this).addClass('js_choose_wholesale')
			}
		});
	}
	if(wholesale != true){
		if($('.js_block_promotional').length){
			let data = $('.js_block_promotional input:checked').attr('data-id');
			if(data != undefined){
				if(data.indexOf('-') != -1){
					promotionalid = data.split('-');
				}else{
					promotionalid.push(data)
				}
				let promotional = json.promotional;
				price_old = price.replace(/\./gi, "");
				price = price.replace(/\./gi, "");
				price = parseFloat(price);
				promotionalid.forEach(function(item, index, array) {
					promotional.forEach(function(item1, index1, array1) {
						if(item == item1.promotionalid){
							if(item1.condition_type_1 == 'condition_quantity'){
								let condition_quantity = parseFloat(item1.condition_value_1);
								if(parseFloat(quantity) >= condition_quantity){
									let discount_value = parseFloat(item1.discount_value);
									if(item1.discount_type == 'price'){

										price = price - discount_value;
									}
									if(item1.discount_type == 'same'){
										price = discount_value;
									}
									if(item1.discount_type == 'percent'){
										price = price - price_old*(discount_value)/100;
										price = Math.round(price);
									}
								}
							}

						}
					});
				});
				conditionChoose = 1;
			}else{
				conditionChoose = 0;
			}
		}
	}
	// console.log(conditionChoose);

	if( ( conditionChoose == 1) || ($('.js_addtribute ul ').length == 0 && $('.js_block_promotional').length == 0 )){
		$('.js_buy').attr('data-id', id);
		$('.js_buy').attr('data-conditon', 'true');
		$('.js_buy').attr('data-quantity', quantity);
		$('.js_buy').attr('data-attrids', attrids);
		$('.js_buy').attr('data-promotionalid', promotionalid);
		$('.js_buy').attr('data-name', name);
		$('.js_buy').attr('data-content', content);
	}
	if(price_contact == 1){
		$('.js_newprice').html('').html('Giá liên hệ');
	}else{
		if(price_sale == 0){
			// console.log(price);
			$('.js_newprice').html('').html(addCommas(price) + '<sup>đ</sup>');
		}else{
			$('.js_newprice').html('').html(addCommas(price_sale) + '<sup>đ</sup>');
		}
	}
};

function addCommas(nStr){
	nStr = String(nStr);

	nStr = nStr.replace(/\./gi, "");
	let str ='';
	for (i = nStr.length; i > 0; i -= 3){
		a = ( (i-3) < 0 ) ? 0 : (i-3);
		str= nStr.slice(a,i) + '.' + str;
	}
	str= str.slice(0,str.length-1);
	return str;
}
function getLocation(param, object){
	let tempWard = wardid;
	let tempDistrict = districtid;
	if(typeof param.district != 'undefined'){
		tempDistrict = param.district;
	}
	if(typeof param.ward != 'undefined'){
		tempWard = param.ward;
	}

	if(typeof tempDistrict == 'undefined' || tempDistrict == ''  || param.trigger_district == false) tempDistrict = 0;
	if(typeof tempWard == 'undefined' || tempWard == ''  || param.trigger_ward == false) tempWard = 0;

	let formURL = 'dashboard/ajax/dashboard/getLocation';
	$.post(formURL, {
		parentid: param.parentid, select: param.select, table: param.table, text: param.text, parentField: param.parentField},
		function(data){
			let json = JSON.parse(data);
			if(param.select == 'districtid'){
				if(param.trigger_district == true){
					$(object).html(json.html).val(tempDistrict).trigger('change', [{'trigger':true}]);
				}else{
					$(object).html(json.html).val(tempDistrict).trigger('change');
				}
			}else if(param.select == 'wardid'){
				$(object).html(json.html).val(tempWard);
			}
		});
}


function sum(a = 0 ,b = 0){
	return parseFloat(a) + parseFloat(b);
}
function sub(a = 0 ,b = 0){
	return parseFloat(a) - parseFloat(b);
}





 $(window).load(function() {

	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	// +++++++++++++++++++++++++++Xá»¬ LI GIá» HĂ€NG CART++++++++++++++++++++++++++++++
	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


	// +++++++++++++++++++++++xĂ³a sáº£n pháº©m+++++++++++++++++++++++
	$(document).on('click' ,'.js_del_prd' ,function(){
		let _this = $(this);
		let param = {
			'rowid' : _this.parents('.js_data_prd').attr('data-rowid'),
			'quantity' :0,
		}
		let ajax_url = 'cart/ajax/cart/refeshCart';
		$.ajax({
			url : ajax_url,
			type : "post",
			cache: 	false ,
			dataType:"text",
			data : {
				param: param
			},
				success : function (data){
					_this.val('');
					let json = JSON.parse(data);
					if(json.result == 'true'){
						toastr.success('Xoá sản phẩm thành công','');
						resultResfeshCart(json);
						return false;
					}
					if(json.result == 'false'){
						toastr.error('Có lỗi xảy ra','');
					}
			}
		});
	})

	// +++++++++++++++++++++++Cáº­p nháº­t sá»‘ lÆ°á»£ng+++++++++++++++++++++++
	$(document).on('change' ,'.js_update_quantity' ,function(){
		let _this = $(this);
		let param = {
			'rowid' : _this.parents('.js_data_prd').attr('data-rowid'),
			'quantity' : _this.val(),
		}
		let ajax_url = 'cart/ajax/cart/refeshCart';
		$.ajax({
			url : ajax_url,
			type : "post",
			cache: 	false ,
			dataType:"text",
			data : {
				param: param
			},
				success : function (data){
					_this.val('');
					let json = JSON.parse(data);
					if(json.result == 'true'){
						toastr.success('Cập nhật sản phẩm thành công','');
						resultResfeshCart(json);
						return false;
					}
					if(json.result == 'false'){
						toastr.error('Có lỗi xảy ra','');
					}

			}
		});
	})
	// +++++++++++++++++++++++cáº­p nháº­p sá»‘ lÆ°á»£ng vá» 0+++++++++++++++++++++++
	$(document).on('click' ,'.js_refesh_quantity' ,function(){
		let _this = $(this);
		let param = {
			'rowid' : _this.parents('.js_data_prd').attr('data-rowid'),
			'quantity' : 1,
		}
		let ajax_url = 'cart/ajax/cart/refeshCart';
		$.ajax({
			url : ajax_url,
			type : "post",
			cache: 	false ,
			dataType:"text",
			data : {
				param: param
			},
				success : function (data){
					_this.val('');
					let json = JSON.parse(data);
					if(json.result == 'true'){
						toastr.success('Cập nhật sản phẩm thành công','');
						resultResfeshCart(json);
						return false;
					}
					if(json.result == 'false'){
						toastr.error('Có lỗi xảy ra','');
					}

			}
		});
	})

	// +++++++++++++++++++++++ThĂªm mĂ£ coupn+++++++++++++++++++++++
	$(document).on('click' ,'.js_btn_coupon' ,function(){
		let _this = $(this);
		let code_cp = $('.js_input_coupon').val();
		let ajax_url = 'cart/ajax/cart/refeshCart';
		$.ajax({
			url : ajax_url,
			type : "post",
			cache: 	false ,
			dataType:"text",
			data : {
				type:'add', code_cp: code_cp
			},
				success : function (data){
					_this.val('');
					let json = JSON.parse(data);
					if(json.result == 'true'){
						toastr.success(json.notifi,'');
						resultResfeshCart(json);
						return false;
					}
					if(json.result == 'false'){
						toastr.error(json.notifi,'');
					}
				}
		});
	})

	// +++++++++++++++++++++++XĂ³a mĂ£ coupn+++++++++++++++++++++++
	$(document).on('click' ,'.js_del_coupon' ,function(){
		let _this = $(this);
		let code_cp = _this.attr('data-coupon');
		let ajax_url = 'cart/ajax/cart/refeshCart';
		$.ajax({
			url : ajax_url,
			type : "post",
			cache: 	false ,
			dataType:"text",
			data : {
				type:'del_coupon', code_cp: code_cp
			},
				success : function (data){
					_this.val('');
					let json = JSON.parse(data);
					if(json.result == 'true'){
						toastr.success(json.notifi,'');
						resultResfeshCart(json);
						return false;
					}
					if(json.result == 'false'){
						toastr.error(json.notifi,'');
					}
				}
		});
	})



	// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	// +++++++++++++++++Xá»¬ LĂ TRANG THANH TOĂN PAYMENT+++++++++++++++++
	// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++



	// +++++++++++++++++++XĂ³a mĂ£ Coupon+++++++++++++++++++
	$(document).on('click' ,'.js_del_coupon_payment' ,function(){
		let _this = $(this);
		let code_cp = _this.attr('data-coupon');
		let ajax_url = 'cart/ajax/cart/refeshPayment';
		$.ajax({
			url : ajax_url,
			type : "post",
			cache: 	false ,
			dataType:"text",
			data : {
				type:'del_coupon', code_cp: code_cp
			},
				success : function (data){
					_this.val('');
					let json = JSON.parse(data);
					if(json.result == 'true'){
						toastr.success(json.notifi,'');
						resultResfeshPayment(json);
						return false;
					}
					if(json.result == 'false'){
						toastr.error(json.notifi,'');
					}
				}
		});
	})


	//  +++++++++++++++++++++ThĂªm mĂ£ Coupon+++++++++++++++++++++
	$(document).on('click' ,'.js_btn_coupon_payment' ,function(){
		let _this = $(this);
		let code_cp = $('.js_input_coupon_payment').val();
		let ajax_url = 'cart/ajax/cart/refeshPayment';
		$.ajax({
			url : ajax_url,
			type : "post",
			cache: 	false ,
			dataType:"text",
			data : {
				type:'add', code_cp: code_cp
			},
				success : function (data){
					_this.val('');
					let json = JSON.parse(data);
					if(json.result == 'true'){
						toastr.success(json.notifi,'');
						resultResfeshPayment(json);
						return false;
					}
					if(json.result == 'false'){
						toastr.error(json.notifi,'');
					}
				}
		});
	})


	// ++++++++++++++++++++++++ cập nhật số lượng ++++++++++++++++++++++++
	$(document).on('change' ,'.js_update_quantity_payment' ,function(){
		let _this = $(this);
		let param = {
			'rowid' : _this.parents('.js_data_prd').attr('data-rowid'),
			'quantity' : _this.val(),
		}
		let ajax_url = 'cart/ajax/cart/refeshPayment';
		$.ajax({
			url : ajax_url,
			type : "post",
			cache: 	false ,
			dataType :"text",
			data : {
				param : param,
			},
				success : function (data){
					_this.val('');
					let json = JSON.parse(data);
					if(json.result == 'true'){
						resultResfeshPayment(json);
						return false;
					}
					if(json.result == 'false'){
						toastr.error(json.notifi,'');
					}
				}
		});
	})
	$(document).on('click' ,'.js_del_prd_payment' ,function(){
		let _this = $(this);
		let param = {
			'rowid' : _this.parents('.js_data_prd').attr('data-rowid'),
			'quantity' :0,
		}
		let ajax_url = 'cart/ajax/cart/refeshPayment';
		$.ajax({
			url : ajax_url,
			type : "post",
			cache:  false ,
			dataType :"text",
			data : {
				param : param,
			},
				success : function (data){
					_this.val('');
					
					let json = JSON.parse(data);
					if(json.result == 'true'){
						resultResfeshPayment(json);
						return false;
					}
					if(json.result == 'false'){
						toastr.error(json.notifi,'');
					}
				}
		});
	})


	// +++++++++++++++tÄƒng giáº£m sá»‘ lÆ°á»£ng thĂªm 1 Ä‘Æ¡n vá»‹+++++++++++++++
	$(document).on('click' ,'.btn-abatement' ,function(){
		let _this = $(this);
		let quantity = _this.parent().find('input').val();
		_this.parent().find('input').val(sum(quantity, 1)).trigger('change');
		return false;
	})
	$(document).on('click' ,'.btn-augment' ,function(){
		let _this = $(this);
		let quantity = _this.parent().find('input').val();
		_this.parent().find('input').val(sub(quantity, 1)).trigger('change');
		return false;
	})
	$(document).on('click' ,'.js_post_payment_1' ,function(){
		$('.js_post_payment').trigger('click');
	})

});


function resultResfeshCart(json =''){
	$('.js_list_prd').html(json.list_prd);
	$('.js_total_prd').html(json.total_quantity);
	$('.js_total_cart').html(json.total_cart);
	$('.js_cart_promo').html(json.cart_promo);
	$('.js_cart_coupon').html(json.cart_coupon);
	$('.js_list_promo').html(json.list_promo);
	$('.js_list_coupon').html('');
	$('.js_list_coupon').html(json.list_coupon);

	return true;
}


function resultResfeshPayment(json =''){
	$('.js_list_prd').html(json.list_prd);
	$('.js_total_prd').html(json.total_quantity);
	$('.js_total_cart').html(json.total_cart);
	$('.js_cart_promo').html(json.cart_promo);
	$('.js_cart_coupon').html(json.cart_coupon);
	$('.js_cart_coupon').attr('data-val', json.cart_coupon_val);
	$('.js_list_promo').html(json.list_promo);
	$('.js_discount_promo').html(json.discount_promo);
	$('.js_discount_coupon').html(json.discount_coupon);
	$('.js_list_coupon').html('');
	$('.js_list_coupon').html(json.list_coupon);
	$('.js_total_item_cart').html(json.total_quantity)
	$('select[name=cityid]').trigger('change');
	$('select[name=districtid]').trigger('change');
	return true;
}


function owl_intilize(sync1 = '#sync1', sync2 = '#sync2'){
	 //======================  MAINSLIDE ===========================================
	 var sync1 = $(sync1);
	 var sync2 = $(sync2);
	 sync1.owlCarousel({
	  // autoPlay: 3000,
	  singleItem : true,
	  center: true,
	  slideSpeed : 1000,
	  navigation: true,
	  pagination:false,
	  afterAction : syncPosition,
	  responsiveRefreshRate : 200,
	});
	 sync2.owlCarousel({
	  items : 5,
	  itemsDesktop      : [1199,5],
	  itemsDesktopSmall     : [979,5],
	  itemsTablet       : [600,4],
	  itemsMobile       : [479,4],

	  pagination:false,
	  responsiveRefreshRate : 100,
	  afterInit : function(el){
		el.find(".owl-item").eq(0).addClass("synced");
	  }

	});

	 function syncPosition(el){
	  var current = this.currentItem;
	  sync1.find('.owl-item').removeClass('active').eq(current).addClass('active');
	  sync2
	  .find(".owl-item")
	  .removeClass("synced")
	  .eq(current)
	  .addClass("synced")
	  if(sync2.data("owlCarousel") !== undefined){
		center(current)
	  }
	}

	sync2.on("click", ".owl-item", function(e){
	  e.preventDefault();
	  var number = $(this).data("owlItem");
	  sync1.trigger("owl.goTo",number);
	});


	function center(number){
	  var sync2visible = sync2.data("owlCarousel").owl.visibleItems;
	  var num = number;
	  var found = false;
	  for(var i in sync2visible){
		if(num === sync2visible[i]){
		  var found = true;
		}
	  }

	  if(found===false){
		if(num>sync2visible[sync2visible.length-1]){
		  sync2.trigger("owl.goTo", num - sync2visible.length+2)
		}else{
		  if(num - 1 === -1){
			num = 0;
		  }
		  sync2.trigger("owl.goTo", num);
		}
	  } else if(num === sync2visible[sync2visible.length-1]){
		sync2.trigger("owl.goTo", sync2visible[1])
	  } else if(num === sync2visible[0]){
		sync2.trigger("owl.goTo", num-1)
	  }

	}

}
$(document).ready(function () {
	var owl = $(".owl-carousel-top"); var checkautoPlay = owl.find(".item3").length; owl.owlCarousel({ navigation: true, singleItem: true, autoPlay: (checkautoPlay > 1 ? 2500 : false), transitionStyle: "fade" }); var owlhome = $(".slider-home"); var checkautoPlayHome = owlhome.find(".item3").length; owlhome.owlCarousel({ navigation: true, singleItem: true, autoPlay: (checkautoPlayHome > 1 ? 2500 : false), transitionStyle: "fade", beforeInit: function () { if ($(window).width() > 991) { $(".slider-home .img-cover").height($(".slider-home").width() / 3) } else { $(".slider-home .img-cover").height($(".slider-home").width() /1.8) }; } }); $("#transitionType").change(function () { var newValue = $(this).val(); owl.data("owlCarousel3").transitionTypes(newValue); owl.trigger("owl.next"); }); $("#promotion-slider").owlCarousel({ navigation: true, singleItem: true, autoPlay: true, pagination: false, transitionStyle: "fade", beforeInit: function () { $("#promotion-slider .img-cover").height($("#promotion-slider").width() * 0.3541912632821724); } });
}); $(document).ready(function () { var owl = $(".slyder-item"); owl.owlCarousel({ navigation: true, singleItem: true, autoPlay: false, transitionStyle: "fade" }); $("#transitionType").change(function () { var newValue = $(this).val(); owl.data("owlCarousel3").transitionTypes(newValue); owl.trigger("owl.next"); }); }); $(document).ready(function () { var owl = $(".slyder-customer"); owl.owlCarousel({ navigation: true, singleItem: true, autoPlay: 2500, transitionStyle: "fade" }); $("#transitionType").change(function () { var newValue = $(this).val(); owl.data("owlCarousel3").transitionTypes(newValue); owl.trigger("owl.next"); }); }); $(function () {
	var Accordion = function (el, multiple) { this.el = el || {}; this.multiple = multiple || false; var links = this.el.find('.link'); links.on('click', { el: this.el, multiple: this.multiple }, this.dropdown) }
Accordion.prototype.dropdown=function(e){var $el=e.data.el;$this=$(this),$next=$this.next();$next.slideToggle();$this.parent().toggleClass('open');if(!e.data.multiple){$el.find('.sub_menu').not($next).slideUp().parent().removeClass('open');};}
	var accordion = new Accordion($('.accordion'), false);
});
$(function () {
	$('#dg-container1').gallery();
	$('#dg-container2').gallery();
});

$(document).ready(function () {
	if ($(window).width() > 767) {
		$(".product-slider .slider").lightSlider({ item: 3, pager: false, slideMargin: 15, loop: false, slideMove: 1, easing: 'cubic-bezier(0.25, 0, 0.25, 1)', speed: 600, responsive: [{ breakpoint: 767, settings: { item: 2, slideMove: 1, slideMargin: 6, } }, { breakpoint: 480, settings: { item: 1, slideMove: 1 } }] });
	}
});

function add_loading(e){
	e.addClass('active');
	e.html(loading_html());
}
function del_loading(e){
	e.removeClass('active');
	e.html('');
}
function loading_html(){
	let html = '';

	html += '<div class="sk-spinner sk-spinner-wave">';
		html += '<div class="sk-rect1"></div>';
		html += '<div class="sk-rect2"></div>';
		html += '<div class="sk-rect3"></div>';
		html += '<div class="sk-rect4"></div>';
		html += '<div class="sk-rect5"></div>';
	html += '</div>';

	return html;
}