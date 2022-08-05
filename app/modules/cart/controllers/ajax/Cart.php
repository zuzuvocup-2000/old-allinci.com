<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends MY_Controller {

	public function __construct(){
		parent::__construct();
	}
    public function render_ship(){
        $cityid = $this->input->post('cityid');
        $districtid = $this->input->post('districtid');
        $shipVal = render_ship(array(
            'cityid' => $cityid,
            'districtid' => $districtid,
        ));
        echo $shipVal;die;
    }


    public function render_discount_ship(){
        $this->load->helper(array('myfrontendcart'));
        $data= renderDataProductInCart();
        $qty = $data['cart']['total_quantity'];
        $total_cart = $data['cart']['total_cart'];
        $cityid = $this->input->post('cityid');
        $districtid = $this->input->post('districtid');
        // laasy danh sachs id sản phẩm
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
        echo $discount_value;die;
    }
   
	public function addCart(){

        $this->load->library("cart");
		$post = $this->input->post();

		$post['attrids'] = str_replace(',','-',$post['attrids']);
		$post['promotionalid'] = str_replace(',','-',$post['promotionalid']);
		$data=array(
            "id" => 'SKU-prd-'.$post['id'].'-attrids-'.$post['attrids'].'',
            "name" => cutnchar($post['name'], 15),
            "qty" => (!isset($post['quantity'])) ? 1 : $post['quantity'],
            "price" => "0",
            "option" => array(
            	"id" => $post['id'], 
            	"attrids" => $post['attrids'], 
                "content" => $post['content'], 
            	"promotionalid" => $post['promotionalid']
            ),
        );
        // Them san pham vao gio hang
        if($this->cart->insert($data)){
            
            $result = "true";
        }else{
            $result = "false";
        }
        echo json_encode(array(
            'total_cart' => $this->cart->total_items(),
            'result' => $result,
        ));die();   
	}
    
    public function refeshCart(){
        $result = 'true';
        $notifi = '';
        $param = $this->input->post('param');
        $this->load->library("cart");
        $this->load->helper(array('myfrontendcart'));
        if(isset($param)){
            $data = $this->cart->contents();
            foreach($data as $key => $item){
                if($key == $param['rowid']){
                    $update = array("rowid" => $item['rowid'], "qty" => $param['quantity']);
                }
            }
            if(isset($update)){
                $this->cart->update($update);
            }
        }
        
        // thêm CP vào session
        $type = $this->input->post('type');
        if($type == 'add'){
            $code_cp = trim($this->input->post('code_cp'));
            $data = renderDataProductInCart(array('coupon' => true));
            $checkCoupon = checkCoupon($code_cp, $data);
            $result = $checkCoupon['result'];
            $notifi = ($checkCoupon['notifi'] != '') ? $checkCoupon['notifi'] : 'Thêm mới mã Coupon thành công'; 
            if(isset($code_cp) && $code_cp != ''){
                $this->load->library("session");
                $coupon = $this->session->userdata("coupon");
                if(!isset($coupon) || !is_array($coupon) || count($coupon) == 0 ){
                    $coupon = array( 0 => $code_cp );
                }else{
                    array_push($coupon, $code_cp);
                    $coupon = array_unique($coupon);
                }
                $this->session->set_userdata("coupon", $coupon);
            }
        }
        
        if($type == 'del_coupon'){
            $code_cp = trim($this->input->post('code_cp'));
            if(isset($code_cp) && $code_cp != ''){
                $this->load->library("session");
                $list_coupon = $this->session->userdata("coupon");
                if(isset($list_coupon) && is_array($list_coupon) && count($list_coupon)){
                    foreach ($list_coupon as $key => $coupon) {
                        if($coupon == $code_cp){
                             unset($list_coupon[$key]);
                        }
                    }
                }
                $this->session->set_userdata("coupon", $list_coupon);
                $notifi = "Xóa mã coupon thành công";
            }
        }
        // lấy lại thông tin giỏ hàng
        $data = renderDataProductInCart(array('coupon' => true));
        // lấy ra danh sách sản phẩm mới
        $list_product = $data['list_product'];
        $cart = $data['cart'];
        $html = '';
        if(isset($list_product) && is_array($list_product) && count($list_product)){
            foreach($list_product as $key => $val){ 
                $image = getthumb((isset($val['version']['image']) && $val['version']['image'] != '' && $val['version']['image'] != 'template/not-found.png') ? $val['version']['image'] : $val['detail']['image']);

                $title =  $val['detail']['title'].' '.((isset($val['version']['title'])) ? $val['version']['title'] : '');

                $href = rewrite_url($val['detail']['canonical']);
                $content = $val['content'];
                $description_litter = cutnchar(strip_tags($val['detail']['description']),400);

                $html = $html.'<tr class="js_data_prd" data-rowid="'.$val['rowid'].'" data-quantity="'.$val['qty'].'">';
                     $html = $html.'<td>';
                         $html = $html.'<a href="single-product.html"><img src="'.$image.'" alt="'.$title.'" title="Cas Meque Metus" class="img-thumbnail"></a>';
                     $html = $html.'</td>';
                     $html = $html.'<td>';
                         $html = $html.'<a href="'.$href.'">'.$title.'</a>';
                        $html = $html.$content;
                     $html = $html.'</td>';
                     $html = $html.'<td style="text-align: center;">';
                         $html = $html.'<div class=" quantity">';
                             $html = $html.'<div class="uk-flex">';
                                 $html = $html.'<input type="text" name="quantity" value="'.$val['qty'].'" size="1" class="form-control input-border js_update_quantity" autocomplete="off" >';
                                 $html = $html.'<button type="submit" data-toggle="tooltip" data-direction="top" class="btn btn-primary js_refesh_quantity" data-original-title="Update"><i class="fa fa-refresh"></i></button>';
                                 $html = $html.'<button type="button" data-toggle="tooltip" data-direction="top" class="btn btn-danger pull-right js_del_prd" data-original-title="Remove"><i class="fa fa-times-circle"></i></button>';
                             $html = $html.'</div>';
                         $html = $html.'</div>';
                     $html = $html.'</td>';

                    $html = $html.'<td>';
                        $html = $html.'<div style ="text-decoration: line-through; color:#999">'.addCommas(getPriceOld($val['detail'])).'<sup>₫</sup></div>';
                        $html = $html.addCommas(getPriceFinal($val['detail'])).'<sup>₫</sup>';
                    $html = $html.'</td>';
                    $html = $html.'<td>';
                         $html = $html.addCommas(getPriceFinal($val['detail'])*$val['qty']).'<sup>₫</sup>';
                    $html = $html.'</td>';
                 $html = $html.'</tr>';
            }
        } 
        // lấy ra danh sách CTKM
        $html_promo = '';
        if(isset($cart['promotion']) && is_array($cart['promotion']) && count($cart['promotion'])){
            foreach ($cart['promotion'] as $key => $value){
                $html_promo = $html_promo.$value;
            }
        }
        // lấy ra danh sách cuopon hợp lệ
        $html_coupon = '';
        $list_coupon = isset($data['cart']['list_coupon']) ? $data['cart']['list_coupon'] : '';
        if(isset($list_coupon) && is_array($list_coupon) && count($list_coupon)){
           foreach ($list_coupon as $key => $value) {
                $html_coupon =  $html_coupon.'<div><b>Mã'.$key.'</b>: '.$value['promo_detail'].'<span class="js_del_coupon" data-coupon ="'.$key.'"><i class="fa fa-trash" aria-hidden="true"></i></span></div>';
            }
        }
        

        
        echo json_encode(array(
            'result' => $result,
            'notifi' => $notifi,
            'list_prd' => (isset($html))?$html:'',
            'total_quantity' => $cart['total_quantity'],
            'total_cart' => addCommas((isset($cart['total_cart'])) ? $cart['total_cart'] : 0),
            'cart_promo' => addCommas((isset($cart['total_cart_promo'])) ? $cart['total_cart_promo'] : $cart['total_cart']),
            'cart_coupon' => addCommas((isset($cart['total_cart_coupon'])) ? $cart['total_cart_coupon'] : $cart['total_cart_promo']),
            'list_promo' => $html_promo,
            'list_coupon' => $html_coupon,
        ));die();
        
    }



    public function refeshPayment(){

        $result = 'true';
        $notifi = '';
        $param = $this->input->post('param');
        $this->load->library("cart");
        $this->load->helper(array('myfrontendcart'));
        if(isset($param)){
            $data = $this->cart->contents();
            foreach($data as $key => $item){
                if($key == $param['rowid']){
                    $update = array("rowid" => $item['rowid'], "qty" => $param['quantity']);
                }
            }
            if(isset($update)){
                $this->cart->update($update);
            }
        }
        
        // thêm CP vào session
        $type = $this->input->post('type');
        if($type == 'add'){
            $code_cp = trim($this->input->post('code_cp'));
            $data = renderDataProductInCart(array('coupon' => true));
            $checkCoupon = checkCoupon($code_cp, $data);
            $result = $checkCoupon['result'];
            $notifi = ($checkCoupon['notifi'] != '') ? $checkCoupon['notifi'] : 'Thêm mới mã Coupon thành công'; 
            if(isset($code_cp) && $code_cp != ''){
                $this->load->library("session");
                $coupon = $this->session->userdata("coupon");
                if(!isset($coupon) || !is_array($coupon) || count($coupon) == 0 ){
                    $coupon = array( 0 => $code_cp );
                }else{
                    array_push($coupon, $code_cp);
                    $coupon = array_unique($coupon);
                }
                $this->session->set_userdata("coupon", $coupon);
            }
        }
        
        if($type == 'del_coupon'){
            $code_cp = trim($this->input->post('code_cp'));
            if(isset($code_cp) && $code_cp != ''){
                $this->load->library("session");
                $list_coupon = $this->session->userdata("coupon");
                if(isset($list_coupon) && is_array($list_coupon) && count($list_coupon)){
                    foreach ($list_coupon as $key => $coupon) {
                        if($coupon == $code_cp){
                             unset($list_coupon[$key]);
                        }
                    }
                }
                $this->session->set_userdata("coupon", $list_coupon);
                $notifi = "Xóa mã coupon thành công";
            }
        }
        // lấy lại thông tin giỏ hàng
        $data = renderDataProductInCart(array('coupon' => true));
        // lấy ra danh sách sản phẩm mới
        $list_product = $data['list_product'];
        $cart = $data['cart'];
        $html = '';
        if(isset($list_product) && is_array($list_product) && count($list_product)){
            foreach($list_product as $key => $val){ 
                $info = getPriceFrontend(array('productDetail' => $val['detail']));
                $quantity = $val['qty'];
                $image = getthumb((isset($val['version']['image']) && $val['version']['image'] != '' && $val['version']['image'] != 'template/not-found.png') ? $val['version']['image'] : $val['detail']['image']);

                // $title =  $val['detail']['title'].' '.((isset($val['version']['title'])) ? $val['version']['title'] : '');
                $title =  $val['detail']['title'];

                $href = rewrite_url($val['detail']['canonical']);
                $description_litter = cutnchar(strip_tags($val['detail']['description']),400);
                $price_final = getPriceFinal($val['detail'], true);
                $money_row= $price_final*$quantity;
                $money_row= addCommas($money_row);
                $html = $html.'<tr class="js_data_prd" data-rowid="'.$val['rowid'].'" data-quantity="'.$val['qty'].'">';
                    $html = $html.'<td>';
                        $html = $html.'<div class="uk-flex uk-flex-middle">';
                            $html = $html.'<div class="thumb">';
                                $html = $html.'<div class="image img-cover"><img src="'.$image.'" alt="'.$title.'"></div>';
                            $html = $html.'</div>';
                            $html = $html.'<div class="title"><a href="'.$href.'">'.$title.'</a></div>';
                        $html = $html.'</div>';
                    $html = $html.'</td>';
                    $html = $html.'<td>';
                        $html = $html.'<div class="wrap-qty">';
                            $html = $html.'<input type="text" name="qty" value="'.$quantity.'" class="input-text qty js_update_quantity_payment">';
                            $html = $html.'<a href="" title="" class="btn-qty btn-abatement"><svg class="svg-inline--fa fa-caret-up fa-w-10" aria-hidden="true" data-prefix="fa" data-icon="caret-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M288.662 352H31.338c-17.818 0-26.741-21.543-14.142-34.142l128.662-128.662c7.81-7.81 20.474-7.81 28.284 0l128.662 128.662c12.6 12.599 3.676 34.142-14.142 34.142z"></path></svg><!-- <i class="fa fa-caret-up"></i> --></a>';
                            $html = $html.'<a href="" title="" class="btn-qty btn-augment"><svg class="svg-inline--fa fa-caret-down fa-w-10" aria-hidden="true" data-prefix="fa" data-icon="caret-down" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M31.3 192h257.3c17.8 0 26.7 21.5 14.1 34.1L174.1 354.8c-7.8 7.8-20.5 7.8-28.3 0L17.2 226.1C4.6 213.5 13.5 192 31.3 192z"></path></svg><!-- <i class="fa fa-caret-down"></i> --></a>';
                        $html = $html.'</div>';
                    $html = $html.'</td>';
                    $html = $html.'<td>';
                        $html = $html.'<div class="price-final text-right">'.$info['price_final'].'</div>';
                        $html = $html.'<div class="price-old text-right">'.$info['price_old'].'</div>';
                        $html = $html.'<div class="price-percent text-center">giảm '.$info['percent'].'</div>';
                    $html = $html.'</td>';
                    $html = $html.'<td>';
                        $html = $html.'<div class="text-right"><b>'.$money_row.'</b></div>';
                    $html = $html.'</td>';
                    $html = $html.'<td>';
                        $html = $html.'<div class="del-row js_del_prd_payment">';
                            $html = $html.'<i class="fa fa-trash-o"></i>';
                        $html = $html.'</div>';
                    $html = $html.'</td>';
                $html = $html.'</tr>';
            }
        }else{
            $html = $html.'<tr>';
                $html = $html.'<td>Không có sản phẩm trong giỏ hàng</td>';
            $html = $html.'</tr>';
        } 
       
        // lấy ra danh sách cuopon hợp lệ
        $html_coupon = '';
        $list_coupon = isset($data['cart']['list_coupon']) ? $data['cart']['list_coupon'] : '';
        if(isset($list_coupon) && is_array($list_coupon) && count($list_coupon)){
           foreach ($list_coupon as $key => $value) {
                $html_coupon =  $html_coupon.'<div><b>Mã'.$key.'</b>: '.$value['promo_detail'].'<span class="js_del_coupon_payment" data-coupon ="'.$key.'"><i class="fa fa-trash" aria-hidden="true"></i></span></div>';
            }
        }
        $total_cart = (isset($cart['total_cart'])) ? $cart['total_cart'] : 0;
        $total_cart_promo = (isset($cart['total_cart_promo'])) ? $cart['total_cart_promo'] : $total_cart;
        $total_cart_coupon = (isset($cart['total_cart_coupon'])) ? $cart['total_cart_coupon'] : $total_cart_promo;

        $discount_promo = $total_cart_promo - $total_cart;
        $discount_coupon = $total_cart_coupon - $total_cart_promo;
        $total_cart = addCommas($total_cart);
        $total_cart_promo = addCommas($total_cart_promo);

        $total_cart_coupon_val = $total_cart_coupon;
        $total_cart_coupon = addCommas($total_cart_coupon);
        $discount_promo = addCommas($discount_promo);
        $discount_coupon = addCommas($discount_coupon);

        
        echo json_encode(array(
            'result' => $result,
            'notifi' => $notifi,
            'list_prd' => (isset($html))?$html:'',
            'total_quantity' => isset($cart['total_quantity']) ? $cart['total_quantity'] : 0,
            'total_cart' => $total_cart.'đ',
            'cart_promo' => $total_cart_promo.'đ',
            'cart_coupon' => $total_cart_coupon.'đ',
            'cart_coupon_val' => $total_cart_coupon_val.'đ',
            'discount_promo' => $discount_promo.'đ',
            'discount_coupon' => $discount_coupon.'đ',
        ));die();
        
    }
}
