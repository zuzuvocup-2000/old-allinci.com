<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends MY_Controller {

	public $module;
	function __construct() {
		parent::__construct();
		$this->load->helper(array('myfrontendcart'));
		$this->load->helper(array('myfrontendcommon'));
		$this->load->library('nestedsetbie', array('table' => 'product_catalogue'));
	}
	public function payment(){
		$this->load->library(array('configbie'));
		$data = renderDataProductInCart(array('coupon' => true));

		if($this->input->post('create')){

			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('fullname', 'Họ và tên', 'trim|required');
			// $this->form_validation->set_rules('first_name', 'Họ', 'trim|required');
			// $this->form_validation->set_rules('last_name', 'Tên', 'trim|required');
			$this->form_validation->set_rules('phone', 'Số điện thoại', 'trim|required');
			// $this->form_validation->set_rules('cityid', 'Tỉnh/Thành phố', 'trim|required');
			$this->form_validation->set_rules('address_detail', 'Địa chỉ chi tiết', 'trim|required');
			$this->form_validation->set_rules('delivery-time', 'Thời gian giao hàng', 'trim|required');
			$this->form_validation->set_rules('payment', 'Hình thức thanh toán', 'trim|required');
			if($this->form_validation->run($this)){
				$extend['mst'] = $this->input->post('mst');
				$extend['company'] = $this->input->post('company');
				$extend['company_address'] = $this->input->post('company-address');
				$extend['fullname_receive'] = $this->input->post('fullname_receive');
				$extend['phone_receive'] = $this->input->post('phone_receive');
				$extend['phone_2_receive'] = $this->input->post('phone_2_receive');
				$extend['address_receive'] = $this->input->post('address_receive');
				$extend['email_receive'] = $this->input->post('email_receive');

				$extend['cityid_receive'] = get_location_name(array('provinceid' => $this->input->post('cityid_receive')), 'vn_province');
				$extend['districtid_receive'] = get_location_name(array('districtid' => $this->input->post('districtid_receive')), 'vn_district');
				$extend['wardid_receive'] = get_location_name(array('wardid' => $this->input->post('wardid_receive')), 'vn_ward');

				$cart = $data['cart'];
				$total_cart = (isset($cart['total_cart'])) ? $cart['total_cart'] : 0;
                $total_cart_promo = (isset($cart['total_cart_promo'])) ? $cart['total_cart_promo'] : $total_cart;
                $total_cart_coupon = (isset($cart['total_cart_coupon'])) ? $cart['total_cart_coupon'] : $total_cart_promo;

                // tính giá ship
                $cityid = $this->input->post('cityid');
		        $districtid = $this->input->post('districtid');
		        $shipVal = render_ship(array(
		            'cityid' => $cityid,
		            'districtid' => $districtid,
		        ));
		        $qty = $data['cart']['total_quantity'];
		        $listId = [];
		        if(isset($data) && check_array($data)){
		            foreach ($data['list_product'] as $key => $val) {
		                $listId[] = $val['option']['id'];
		            }
		        }
		        $discount_value = render_discount_ship(array(
		            'listId' => $listId,
		            'cityid' => $cityid,
		            'districtid' => $districtid,
		            'qty' => $qty,
		        ));

		        $totalShip = ($shipVal > $discount_value) ? ($shipVal - $discount_value) : 0;
		        $extend['totalShip'] = $this->input->post('totalShip');
		        $extend['shipDiscount'] = $this->input->post('discount_value');
		        $extend['shipVal'] = $this->input->post('shipVal');

				$_insert = array(
					'ship' => $totalShip,
					'code' => CodeRender('order'),
					'fullname' => $this->input->post('fullname'),
					// 'fullname' => $this->input->post('first_name').' '.$this->input->post('last_name'),
					'paymentCataId' => $this->input->post('payment'),
					'delivery_time' => $this->input->post('delivery-time'),
					'phone' => $this->input->post('phone'),
					'phone_other' => $this->input->post('phone_other'),
					'email' => $this->input->post('email'),
					'note' => $this->input->post('note'),
					'cityid' => $this->input->post('cityid'),
					'districtid' => $this->input->post('districtid'),
					'wardid' => $this->input->post('wardid'),
					'address_detail' => $this->input->post('address_detail'),

					'quantity' => $data['cart']['total_quantity'],
					'total_cart_final' => $total_cart_coupon,
					'cart_json' => base64_encode(json_encode($data)),
					'extend_json' =>  base64_encode(json_encode($extend)),
					'promotional_detail' => isset($data['cart']['promotion']) ? json_encode($data['cart']['promotion']) : '',
					'coupon_json' => isset($data['list_promotion_coupon']) ? json_encode($data['list_promotion_coupon']) : '',

					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
					'status' => 'pending',
				);

				// print_r($extend); exit;

				$resultid = $this->Autoload_Model->_create(array(
					'table' => 'order',
					'data' => $_insert,
				));

				if($resultid > 0){
					// trừ số lượng mã cuopon
					if(isset($data['list_promotion_coupon']) && is_array($data['list_promotion_coupon']) && count($data['list_promotion_coupon'])){
						foreach ($data['list_promotion_coupon'] as $key => $value) {
							updateInt(array(
								'module' => 'promotional',
								'field' => 'limmit_code',
								'where' => array('code' => $key),
								'minus' => 1,
							));
						}
					}
					foreach ($data['list_product'] as $key => $product) {
						$_insert_relationship[] = array(
							'orderid' => $resultid,
							'module' => 'product',
							'title' => $product['detail']['title'],
							'moduleid' => $product['detail']['id'],
							'title' => $product['detail']['title'],
							'image' => $product['detail']['image'],
							'attrids' => $product['option']['attrids'],
							'quantity' => $product['qty'],
							'price_final' => getPriceFinal($product['detail']),
							'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
						);
					}
					$this->Autoload_Model->_create_batch(array(
						'table' => 'order_relationship',
						'data' => $_insert_relationship,
					));




					// GỬI MAIL
					$this->load->helper('mymail');
					$this->load->library(array('mailbie'));
					$detailorder = $this->Autoload_Model->_get_where(array(
						'select' => 'id,code, fullname, created, updated, order, total_cart_final, status,	quantity, promotional_detail, fullname , phone, email, note, address_detail, (SELECT name FROM vn_province WHERE order.cityid = vn_province.provinceid) as address_city, (SELECT name FROM vn_district WHERE order.districtid = vn_district.districtid) as address_distric, extend_json',
						'table' => 'order',
						'where' => array('id' => $resultid),
					));
					// pre($data['list_product']);
					$image = site_url($product['detail']['image']);
					$image = substr( $image, 0, strlen($image) - 5);
    				$productListDes = '';
    				foreach ($data['list_product'] as $key => $product) {
    					$content = isset($product['detail']['content']) ? $product['detail']['content'] :'';
    					$productListDes = $productListDes.'<tr>
    						<td style="padding:5px 9px"><img style="width:40px ; height: 40px" src="'.$image.'"></td>
    						<td style=" padding:5px 9px">'.$product['detail']['title'].$content.'</td>
    						<td style="text-align:right ; padding:5px 9px">'.addCommas($product['detail']['price']).' đ</td>
    						<td style="text-align:center ; padding:5px 9px">'.$product['qty'].'</td>
    						<td style="text-align:right ; padding:5px 9px">'.addCommas($product['detail']['price'] - getPriceFinal($product['detail'])).' đ</td>
    						<td style="text-align:right ; padding:5px 9px">'.addCommas(getPriceFinal($product['detail'])).' đ</td>
    					</tr>';
					}

					if(!empty($detailorder['address_receive']))
						$address = $detailorder['address_receive'].' - '.$detailorder['wardid_receive'].' - '.$detailorder['districtid_receive'].' - '.$detailorder['cityid_receive'];
					else{
						$address = $detailorder['address_detail'].' - '.$detailorder['address_detail'].' - '.$detailorder['address_distric'].' - '.$detailorder['address_city'];
					}

					$cc = '';
					for ($i=1; $i < 5 ; $i++) {
						$email = isset($this->general['contact_email_'.$i]) ? $this->general['contact_email_'.$i] : '';
						if(!empty($email)){
							$cc = $cc.', '.$email;
						}
					}


					$this->mailbie->sent(array(
						'to' => $detailorder['email'],
						// 'cc' => 'lehungit229@gmail.com'.$cc ,
						'cc' => $this->general['contact_email'] ,
						'subject' => 'Xác nhận đặt hàng thành công tại hệ thống website: '.$this->general['contact_website'],
						'message' => mail_html(array(
							'header' => 'Thông tin đặt hàng',
							'fullname' => $detailorder['fullname'],
							'email' => $detailorder['email'],
							'p_phone' => $detailorder['phone'],
							'address' => $address,
							'total_price' => $detailorder['total_cart_final'],
							'payment_code' => $detailorder['code'],
							'payment_created' => $detailorder['created'],
							'fee' => $totalShip,
							'product' => $productListDes,
							'web' => $this->general['contact_website'],
							'hotline' => $this->general['contact_hotline'],
							'phone' => $this->general['contact_hotline'],
							'logo' => base_url($this->general['homepage_logo']),
							'brandname' => $this->general['contact_website'],
							'system_email' => $this->general['contact_email'],
							'system_address' => $this->general['contact_address'],
						))
					));
					if($this->input->post('payment') == 10){
						redirect('payment/frontend/paypal');
						$module = modules::run($routers['uri'], $routers['param']['id'], $page);
					}


					$this->cart->destroy();
					$this->session->set_flashdata('message-success', 'Bạn đã tạo đơn hàng thành công, vui lòng kiểm tra email');
					redirect('');
				}
			}
		};
		// pre($data);
		$data['meta_title'] = 'Thanh toán';

		$data['template'] = 'cart/payment';
		$this->load->view('homepage/frontend/layout/home', isset($data)?$data:NULL);
	}
	public function cart(){
		// pre($_SESSION,1);
		$data = renderDataProductInCart(array('coupon' => true));

		$data['meta_title'] = 'Giỏ hàng';
		$data['template'] = 'cart/cart';
		$this->load->view('homepage/frontend/layout/home', isset($data)?$data:NULL);
	}

}
