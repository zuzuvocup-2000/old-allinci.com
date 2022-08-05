<?php 

//trả về: $data để truyền sang frontend view trang thanh toán
//đầu vào: lấy dữ liệu từ cookie giỏ hàng

//trả về: $data để truyền sang frontend view trang giả h
//đầu vào: lấy dữ liệu từ cookie giỏ hàng
if(!function_exists('renderDataProductInCart')){
	function renderDataProductInCart($param = ''){
		$CI = & get_instance();
		$CI->load->library("cart");
		$cart=$CI->cart->contents();

		if(!isset($cart) || !is_array($cart) || count($cart) == 0){
			return null;
		}else{
			$data['cart']['total_quantity'] = $CI->cart->total_items();
			// lấy số lượng của phiên bản SP và id của khuyến mại tương ứng với từng sản phẩm ( $count, $promoid)
			foreach ($cart as $key => $value) {
				$array_id = $value['option']['id'];
				$attrid = (!empty($value['option']['attrids'])) ? $value['option']['attrids'] : 'none';
				$count[$attrid][$array_id] = $value['qty'];
				$promoid[$array_id]  = (!empty($value['option']['promotionalid'])) ? $value['option']['promotionalid'] : '';
	 		}

			$list_product = $CI->Autoload_Model->_get_where(array(
				'select' => 'id, image, title, price, price_sale, description, canonical',
				'table' => 'product',
				'where_in' => $array_id,
				'where_in_field' => 'id',
			), true);
			$product_version = $CI->Autoload_Model->_get_where(array(
				'select' => 'id, image, productid, title, attribute1, attribute2, price_version',
				'table' => 'product_version',
				'where_in' => $array_id,
				'where_in_field' => 'productid',
			), true);
			$product_wholesale = $CI->Autoload_Model->_get_where(array(
				'select' => 'id,productid, quantity_start, quantity_end, price_wholesale',
				'table' => 'product_wholesale',
				'where_in' => $array_id,
				'where_in_field' => 'productid',
			), TRUE);
			$promo_relationship = $CI->Autoload_Model->_get_where(array(
				'select' => 'id, discount_value, discount_type, discount_moduleid, use_common, promotionalid, condition_type, condition_value, condition_type_1, condition_value_1, module, freeship, freeshipAll, cityid, moduleid, start_date, end_date',
				'table' => 'promotional_relationship',
				'where' => array('module' => 'product', 'code' => ''),
				'where_in' => $array_id,
				'where_in_field' => 'moduleid',
			), true);

			$temp = '';
			$list_promo = '';
			
			foreach ($cart as $key => $val) {
				$val['content'] = isset($val['option']['content']) ? $val['option']['content'] : '';
				if(isset($list_product) && is_array($list_product) && count($list_product)){
					foreach ($list_product as $keyItem => $valItem) {
						$valItem['description'] = cutnchar(strip_tags($valItem['description']));
						foreach ($product_version as $keyVer => $valVer) {
							if($valItem['id'] == $valVer['productid']){
								$imageVer = $valVer['image'];
							}
						}
						if(isset($imageVer) && $imageVer !=''){
                            $versionImage = json_decode(base64_decode($imageVer), true);
                            if(isset($versionImage) && check_array($versionImage)){
                                foreach ($versionImage as $key => $value) {
                                    if( $value != '' && $value != 'template/not-found.png'){
                                        $versionImage = $value;
                                        break;
                                    }else{
                                        $versionImage = '';
                                    }
                                }
                            }
                        }else{
                            $versionImage = '';
                        }
                        $valItem['image'] = getthumb(
                            ($versionImage != '')
                            ? $versionImage 
                            : $valItem['image']
                        );


						if( $valItem['id'] == $val['option']['id']){
							$val['detail'] = $valItem ;
						}
					}
				}

				if(isset($product_wholesale) && is_array($product_wholesale) && count($product_wholesale)){
					foreach ($product_wholesale as $keyItem => $valItem) {
						if( $valItem['productid'] == $val['option']['id']){
							$quantity = 0;
							// lấy tổng số sản phẩm của từng phiên bản
							if(isset($count) && is_array($count) && count($count)){
								foreach ($count as $keyCount => $valCount) {
									if(isset( $valCount[$val['option']['id']])){
										$quantity = $quantity + $valCount[$val['option']['id']];
									}
								}
							}
							$val['option']['qty'] = $quantity;
							$val['wholesale'][] = $valItem;
						}
					}
				}

				if(isset($product_version) && is_array($product_version) && count($product_version)){
					foreach ($product_version as $keyItem => $valItem) {
						if( $valItem['productid'] == $val['option']['id']){
							$version = explode('-', $val['option']['attrids']);
							if(count($version) == 1){
								$version[] = 0;
							}
							if(in_array( $valItem['attribute1'], $version ) == false || in_array( $valItem['attribute2'], $version ) == false  ) continue;
							$val['version'] = $valItem;
						}
					}
				}

				// kiểm tra và lấy danh sách CTKM tương ứng với SP
				if(isset($promo_relationship) && is_array($promo_relationship) && count($promo_relationship)){
					$list_promo = getListPromo($promo_relationship, $list_promo , $val['option']['id'], $promoid);
				}


				$temp[] = $val;
			}
			$cart = $temp;
			if(isset($cart) && is_array($cart) && count($cart)){
				foreach ($cart as $key => $value) {
					$result = getPrice($value);
					if(isset($result) && is_array($result) && count($result)){
						$cart[$key]['detail'] = array_merge($cart[$key]['detail'],$result);
					}
				}
			}


			if(isset($cart) && is_array($cart) && count($cart)){
				$total_cart = getTotalCart($cart);
				$getPricepromo = getPricepromo($cart, $list_promo, $data['cart']['total_quantity']);
				if(isset($getPricepromo['result']) && $getPricepromo['result'] =='true'){
					$data_update_gift = $getPricepromo['data'];
					return $data_update_gift;
				}else{
					$data['cart']['total_cart'] = $total_cart;
					
					$data['list_product'] = $getPricepromo['cart'];

					$data['cart']['html_gift'] = $getPricepromo['html_gift'];
					$data['cart']['promo_ship'] = $getPricepromo['promo_ship'];
					$data['cart']['total_cart_promo'] = getTotalCart($getPricepromo['cart']);

					if(isset($list_promo) && is_array($list_promo) && count($list_promo)){
						foreach ($list_promo as $key => $value) {
							$promo_detail[] = $value['detail'];
						}
						$promo_detail = array_unique($promo_detail);
					}
					$data['cart']['promo'] = (isset($promo_detail)) ? $promo_detail : '';
				}
			}
			if(isset($param['coupon']) && $param['coupon'] == true){
				$data = getCoupon($data);
			}
			return $data;
		}
	}
}

if(!function_exists('getCoupon')){
	function getCoupon($data){
		$CI = & get_instance();
		$list_coupon = $CI->session->userdata("coupon");
	    // pre( $list_coupon);
	    if(isset($list_coupon) && is_array($list_coupon) && count($list_coupon)){
	        foreach ($list_coupon as $key => $coupon) {
	            if(isset($checkCoupon) && is_array($checkCoupon) && count($checkCoupon)){
	                $data = $checkCoupon['data'];
	            }
	            $checkCoupon = checkCoupon($coupon, $data);
	            if($checkCoupon['del_coupon'] != ''){
	                unset($list_coupon[$key]);
	            }
	        }
            $CI->session->set_userdata("coupon", $list_coupon);
	    }

	    //Tính tổng tiền sau khi giảm giá coupon
	    $total_cart = $data['cart']['total_cart'];
        $total_cart_coupon = 0;
	    if(isset($checkCoupon) && is_array($checkCoupon) && count($checkCoupon)){
	        foreach ($checkCoupon['data']['list_product'] as $key => $product) {
	        	$quantity = $product['qty'];
	        	$price_coupon = getPriceFinal($product['detail'], true);
	        	$total_cart_coupon = $total_cart_coupon + $price_coupon*$quantity;
	        }
			$data['cart']['total_cart_coupon'] = $total_cart_coupon;
			$data['cart']['list_coupon'] = isset($checkCoupon['data']['coupon']) ? $checkCoupon['data']['coupon'] : '';
			$data['list_product'] = $checkCoupon['data']['list_product'];
		}
		return $data;
	}
}
if(!function_exists('getListPromo')){
	function getListPromo($promo_relationship = '', $list_promo ='', $id = '',$promoid ){
		foreach ($promo_relationship as $keyPromo => $valPromo) {
			$option_promoid = explode('-', $promoid[$id]);
			if(isset($option_promoid) && is_array($option_promoid) && count($option_promoid)){
				foreach ($option_promoid as $keyId => $valId) {
					if( $valPromo['moduleid'] == $id && $valPromo['module'] == 'product' && $valId == $valPromo['promotionalid']){
						$promo_id[] = $valPromo;
					}
				}
			}
		}
		if(isset($promo_id) && is_array($promo_id) && count($promo_id)){
			if(isset($list_promo) && is_array($list_promo) && count($list_promo)){
				$promo_new = checkPromo($promo_id, $id);
				$list_promo = array_merge($list_promo, $promo_new);
			}else{
				$list_promo = checkPromo($promo_id, $id);
			}
		}
		return $list_promo;
	}
}

if(!function_exists('getPrice')){
	function getPrice($param = ''){
		$data = '';
		if(isset($param['detail']) && is_array($param['detail']) && count($param['detail'])){
			if($param['detail']['price_sale'] != 0){
				return array(
					'price_sale' => $param['detail']['price_sale'],
				);
			}
		}

		if(isset($param['wholesale']) && is_array($param['wholesale']) && count($param['wholesale'])){
			$price_wholesale = getPriceProductWholesale($param['option']['qty'] , $param['wholesale']);
			if($price_wholesale != 0){
				return array(
					'price_wholesale' => $price_wholesale,
				);
			}
		}

		if(isset($param['version']) && is_array($param['version']) && count($param['version'])){
			if($param['version']['price_version'] != 0){
				$data = array(
					'price_version' => $param['version']['price_version'],
				);
			}
		}else{
			$data = array(
				'price' => $param['detail']['price'],
			);
		}
		return $data;
	}
}

if(!function_exists('getTotalCart')){
	function getTotalCart($cart = ''){
		$total_cart = '';
		foreach ($cart as $key => $value) {
			$price_final = getPriceFinal($cart[$key]['detail']);
			$total_cart = $total_cart + $price_final*$cart[$key]['qty'];
		}
		return $total_cart;
	}
}

if(!function_exists('getPricepromo')){
	function getPricepromo($cart = [], $list_promo= [], $total_quantity, $payment = 'false'){
		$CI =& get_instance();
		$total_same = '';
		$html_gift ='';
		$list_promo = arrays_unique($list_promo);
		$total_cart = getTotalCart($cart);
		if(isset($list_promo) && is_array($list_promo) && count($list_promo)){
			foreach ($list_promo as $key => $promo) {
				foreach ($cart as $sub => $value) {
					if($value['option']['id'] == $promo['moduleid']){
						$price_old = getPriceOld($value['detail']);
						$price_final = getPriceFinal($value['detail'], true);
						if($promo['discount_type'] != 'object'){
							if(( $promo['condition_type_1'] == 'condition_quantity' && $promo['condition_value_1'] <= $total_quantity ) || ( $promo['condition_type_1'] == 'condition_money' && $promo['condition_value_1'] <= $total_cart ) ){
								$price_promo = '';
								if($promo['discount_type'] == 'price'){
									$price_promo = $price_final - $promo['discount_value'];
								}elseif($promo['discount_type'] == 'percent'){
									$price_promo = $price_final - ($price_old * $promo['discount_value'])/100;
									$price_promo = FLOOR($price_promo);
								}elseif($promo['discount_type'] == 'same'){
									$price_promo = $promo['discount_value'];
								}elseif($promo['discount_type'] == 'ship'){
									$promo_ship[] =  $promo;
								}
								if($payment == 'false'){
									$cart[$sub]['detail']['price_promo'] = $price_promo;
								}else{
									$cart[$sub]['detail']['price_coupon'] = $price_promo;
								}
							}
						}else{
							$list_object = json_decode($promo['discount_moduleid']);
							foreach ($list_object as $keyObject => $object) {
								
								$object_in_cart = 'false';
								foreach ($cart as $keyCart => $valCart) {
									if($object == $valCart['option']['id']){
										$price_final = getPriceFinal($valCart['detail']);
										if(( $promo['condition_type_1'] == 'condition_quantity' && $promo['condition_value_1'] <= $total_quantity ) || ( $promo['condition_type_1'] == 'condition_money' && $promo['condition_value_1'] <= $total_cart ) ){
											$price_promo = $price_final - ($price_old * $promo['discount_value'])/100;
											if($payment == 'false'){
												$cart[$keyCart]['detail']['price_promo'] = $price_promo;
											}else{
												$cart[$keyCart]['detail']['price_coupon'] = $price_promo;
											}

										}
										$object_in_cart = 'true';
									}
								}
								if($payment == 'false'){
									// nếu chưa có sản phẩm khuyến mãi trong giở hàng thì show ra cho chọn
									if($object_in_cart == 'false'){
										$product_gift = $CI->Autoload_Model->_get_where(array(
											'select' => 'id, title, image, title, price, price_sale, description',
											'table' => 'product',
											'where' => array('id' => $object),
										));
										// nếu khuyến mại là 100%
										if($promo['discount_value'] == 100){
											$data=array(
									            "id" => 'SKU-product-'.$product_gift['id'],
									            "name" =>cutnchar( $product_gift['title'], 20),
									            "qty" => 1,
									            "price" => "0",
									            "option" => array(
										        	"id" => $product_gift['id'], 
										        	"attrids" => 'none', 
										        ),
									        );
									        // Them san pham vao gio hang
									        if($CI->cart->insert($data)){
												$renderDataProductInCart = renderDataProductInCart();
												return array(
													'result' => 'true',
													'data' => $renderDataProductInCart,
												);
									        }
										}else{
											$html_gift = getHtmlGift($product_gift, $promo);
										}
									}
								}
							}
						}
					}
					
				}
			}
		}
		return array(
			'cart' => $cart,
			'promo_ship' => (isset($promo_ship)) ? $promo_ship : '',
			'html_gift' => $html_gift,
		);
	}
}



if(!function_exists('getPriceProductWholesale')){
	function getPriceProductWholesale($quantity = 0, $Wholesale = []){
		$price_wholesale = 0;
		if(isset($Wholesale) && is_array($Wholesale) && count($Wholesale)){
			foreach ($Wholesale as $key => $value) {
				if($quantity >= $value['quantity_start'] && $quantity <= $value['quantity_end']){
			   		$price_wholesale = $value['price_wholesale'];
			   	}else{
			   		if($quantity >= $value['quantity_end']){
			   			$price_wholesale = $value['price_wholesale'];
			   		}
			   	}
			}
		}
		return $price_wholesale ;
	}
}


if(!function_exists('getHtmlGift')){
	function getHtmlGift($product_gift = '', $promo = '' ){
		$price_gift = $product_gift['price'] *( 100 - $promo['discount_value']) / 100;
		$html_gift = '';
		$html_gift = $html_gift.'<div>';
		$html_gift = $html_gift.$product_gift['title'].' giá KM: '.addCommas($price_gift).' Giá gốc'.addCommas($product_gift['price']).'</br>';
		$html_gift = $html_gift.'<div data-id="'.$product_gift['id'].'" data-name="'.$product_gift['title'].'"  type="submit" name="create" value="create" class=" ajax_add_prd_gift checkout"><i class="fa fa-shopping-cart"></i>Thêm vào giỏ hàng</div>';
		$html_gift = $html_gift.'</div>';
		return $html_gift;
	}
}


if(!function_exists('checkCoupon')){
    function checkCoupon($code_cp = '', $data = []){
    	$CI = & get_instance();
        $notifi = '';
        $code_cp = trim($code_cp);
        $result = 'false';

        $time = gmdate('Y-m-d', time() + 7*3600);
        $time = $time.' 00:00:00';
        $query = ' (( start_date = "0000-00-00 00:00:00" && end_date = "0000-00-00 00:00:00" ) || ( start_date <= "'.$time.' " AND end_date >= "'.$time.'"))';
        $promo = $CI->Autoload_Model->_get_where(array(
            'select' => 'id, discount_value, discount_type, discount_moduleid, use_common, condition_type, condition_value, condition_type_1, condition_value_1, module, freeship, freeshipAll, cityid, code, module, limmit_code, start_date, end_date',
            'table' => 'promotional',
            
            'where' => array('module' => 'product', 'catalogue' => 'CP', 'code' => $code_cp),
            'query' =>  $query
        ));

        $promo_relationship = $CI->Autoload_Model->_get_where(array(
			'select' => 'tb2.id, tb2.discount_value, tb2.discount_type, tb2.discount_moduleid, tb2.use_common, tb2.promotionalid, tb2.condition_type, tb2.condition_value, tb2.condition_type_1, tb2.condition_value_1, tb2.module, tb2.freeship, tb2.freeshipAll, tb2.cityid, tb2.moduleid, tb2.start_date, tb2.end_date',
			'table' => 'promotional_relationship as tb2',
			'where' => array('tb2.code' => $code_cp),
		), true);

        if(!isset($promo) || !is_array($promo) || count($promo) == 0){
            $notifi = 'Mã coupon không đúng, vui lòng nhập lại';
        }else{
            if($promo['use_common'] == 0 && isset($data['cart']['promo']) && count($data['cart']['promo'])){
                $notifi = 'Mã Coupon không được cho phép dùng chung với chương trình khuyến mại';
            }else{
                if($promo['limmit_code'] == 0){
                    $notifi = 'Số lần sử dụng mã coupon đã hết';
                }else{
                	$total_quantity = $CI->cart->total_items();
			        $getPricepromo = getPricepromo($data['list_product'], $promo_relationship, $total_quantity, 'true');
			        $data['list_product'] = $getPricepromo['cart'];
			        $getpromo = json_decode(getPromotional($promo), true);
			        $data['coupon'][$code_cp]['promo_detail'] = $getpromo['detail'];
			        $result = 'true';
                }
                
            }
        }
        return array(
			'data' => $data,
			'del_coupon' => ($result == 'false') ? $code_cp : '',
			'notifi' => $notifi,
			'result' => $result,
		);
    }
}



 ?>