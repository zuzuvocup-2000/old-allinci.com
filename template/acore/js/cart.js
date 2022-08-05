
 $(window).load(function() {
 	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	// +++++++++++++++++++++++++++XỬ LI GIỎ HÀNG CART++++++++++++++++++++++++++++++
 	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


	// // +++++++++++++++++++++++xóa sản phẩm+++++++++++++++++++++++
	
	// +++++++++++++++++++++++Cập nhật số lượng+++++++++++++++++++++++
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
            			toastr.success('Cập nhật số lượng thành công','');
						resultResfeshCart(json);
						return false;
            		}
            		if(json.result == 'false'){
            			toastr.error('Có lỗi sảy ra','');
            		}
            		
            }
        });
	})
	// +++++++++++++++++++++++cập nhập số lượng về 0+++++++++++++++++++++++
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
            			toastr.success('Cập nhật số lượng thành công','');
						resultResfeshCart(json);
						return false;
            		}
            		if(json.result == 'false'){
            			toastr.error('Có lỗi sảy ra','');
            		}

            }
        });
	})

	// +++++++++++++++++++++++Thêm mã coupn+++++++++++++++++++++++
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

	// +++++++++++++++++++++++Xóa mã coupn+++++++++++++++++++++++
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
	// +++++++++++++++++XỬ LÝ TRANG THANH TOÁN PAYMENT+++++++++++++++++
	// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++



	// +++++++++++++++++++Xóa mã Coupon+++++++++++++++++++
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


	//  +++++++++++++++++++++Thêm mã Coupon+++++++++++++++++++++
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


	// ++++++++++++++++++++++++Cập nhật số lượng++++++++++++++++++++++++
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
                    console.log(1);
                    return false;
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
                    console.log(1);
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



	// +++++++++++++++tăng giảm số lượng thêm 1 đơn vị+++++++++++++++
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
    console.log(json);
	$('.js_list_prd').html(json.list_prd);
	$('.js_total_prd').html(json.total_quantity);
	$('.js_total_cart').html(json.total_cart);
	$('.js_cart_promo').html(json.cart_promo);
	$('.js_cart_coupon').html(json.cart_coupon);
	$('.js_list_promo').html(json.list_promo);
	$('.js_discount_promo').html(json.discount_promo);
	$('.js_discount_coupon').html(json.discount_coupon);
	$('.js_list_coupon').html('');
	$('.js_list_coupon').html(json.list_coupon);
    $('.js_total_item_cart').html(json.total_quantity)
	return true;
}

						