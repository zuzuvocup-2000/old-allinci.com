<div id="prddetail" class="page-body _page_body">
    <!-- <div class="breadcrumb">
        <div class="uk-container uk-container-center">
            <ul class="uk-breadcrumb uk-margin-remove">
                <li><a href="" title="Trang chủ">Trang chủ</a></li>
                <li class="uk-active"><a href="" title="">Thông tin thanh toán</a></li>
            </ul>
        </div>
    </div> -->

    <section class="breadcrumb breadcrumb-expand" style="background: url('template/frontend/resources/img/breadcrumb.jpg') no-repeat top; background-size: cover;">
        <div class="uk-container uk-container-center">
            <div class="breadcrumb-content">
                <div class="breadcrumb-maintitle">Thông tin thanh toán</div>
            </div>
        </div>
    </section>

    <section id="cart" class="cart-wrapper">
        <div class="uk-container uk-container-center">
            <div class="uk-grid uk-grid-width-medium uk-grid-width-medium-1-1 uk-grid-width-large-1-2 ht2109_reverse">
                    <div class="outer_cart-product">
                        <div class="cart-panel cart-product mb20">
                        <section class="list-product">
                            <header class="panel-head"><h2 class="heading"><span>Đơn hàng(<span class="js_total_prd"><?php echo (isset($cart['total_quantity'])) ? $cart['total_quantity'] : 0; ?></span>)</span></h2></header>
                            <section class="panel-body">
                                <div class="uk-overflow-container">
                                    <table class="table-list-product">
                                        <thead>
                                            <tr>
                                                <th>Sản phẩm</th>
                                                <th>Số lượng</th>
                                                <th class="text-right">Đơn giá (đ)</th>
                                                <th class="text-right">Thành tiền (đ)</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody class="js_list_prd">
                                        <?php if(isset($list_product) && is_array($list_product) && count($list_product)){ ?>
                                            <?php foreach($list_product as $key => $val){ ?>
                                            <?php
                                                $info = getPriceFrontend(array('productDetail' => $val['detail']));
                                                $quantity = $val['qty'];
                                                if(isset($val['version']['image']) && $val['version']['image'] !=''){
                                                    $versionImage = json_decode(base64_decode($val['version']['image']), true);
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
                                    
                                                $image = getthumb(
                                                    ($versionImage != '')
                                                    ? $versionImage
                                                    : $val['detail']['image']
                                                );
                                    
                                                // $title =  $val['detail']['title'].' '.((isset($val['version']['title'])) ? $val['version']['title'] : '');
                                                $title =  $val['detail']['title'];
                                    
                                                $href = rewrite_url($val['detail']['canonical']);
                                                $content = $val['content'];
                                                $description_litter = cutnchar(strip_tags($val['detail']['description']),400);
                                                $price_final = getPriceFinal($val['detail'], true);
                                                $money_row= $price_final*$quantity;
                                                $money_row= addCommas($money_row);
                                            ?>
                                            <tr class="js_data_prd" data-rowid="<?php echo $val['rowid'] ?>" data-quantity="<?php echo $val['qty'] ?>" >
                                                <td>
                                                    <div class="uk-flex uk-flex-middle">
                                                        <div class="thumb">
                                                            <div class="image img-cover"><img src="<?php echo $image ?>" alt="<?php echo $title ?>"></div>
                                                        </div>
                                                        <div class="title"><a href="<?php echo $href ?>"><?php echo $title ?></a></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="wrap-qty">
                                                        <input type="text" name="qty" value="<?php echo $quantity ?>" class="input-text qty js_update_quantity_payment">
                                    
                                                        <a  title="" class="btn-qty btn-abatement"><svg class="svg-inline--fa fa-caret-up fa-w-10" aria-hidden="true" data-prefix="fa" data-icon="caret-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M288.662 352H31.338c-17.818 0-26.741-21.543-14.142-34.142l128.662-128.662c7.81-7.81 20.474-7.81 28.284 0l128.662 128.662c12.6 12.599 3.676 34.142-14.142 34.142z"></path></svg><!-- <i class="fa fa-caret-up"></i> --></a>
                                                        <a  title="" class="btn-qty btn-augment"><svg class="svg-inline--fa fa-caret-down fa-w-10" aria-hidden="true" data-prefix="fa" data-icon="caret-down" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M31.3 192h257.3c17.8 0 26.7 21.5 14.1 34.1L174.1 354.8c-7.8 7.8-20.5 7.8-28.3 0L17.2 226.1C4.6 213.5 13.5 192 31.3 192z"></path></svg><!-- <i class="fa fa-caret-down"></i> --></a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="price-final text-right"><?php echo $info['price_final'] ?></div>
                                                    <div class="price-old text-right"><?php echo $info['price_old'] ?></div>
                                                    <div class="price-percent text-center">giảm <?php echo $info['percent'] ?></div>
                                                </td>
                                                <td>
                                                    <div class="text-right"><b><?php echo $money_row ?></b></div>
                                                </td>
                                                <td>
                                                    <div class="del-row js_del_prd_payment">
                                                        <i class="fa fa-trash-o"></i>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php }}else{ ?>
                                            <tr>
                                                <td>Không có sản phẩm trong giỏ hàng</td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        </section>
                        <section class="list-money">
                            <div class="uk-grid uk-grid-small mb10">
                                <div class="uk-width-2-5">
                                    <button class="return-home mb15"><a href="<?php echo base_url('fresh-coffee.html') ?>">Chọn thêm sản phẩm khác</a></button>
                                    <h4>Danh sách Coupon áp dụng</h4>
                                    <div class="js_list_coupon">
                                        <?php if(isset($cart['list_coupon']) && is_array($cart['list_coupon']) && count($cart['list_coupon'])){ ?>
                                            <?php foreach ($cart['list_coupon'] as $key => $value) { ?>
                                                <div><?php echo '<b>Mã '.$key.'</b>: '.$value['promo_detail'] ?> <span data-coupon="<?php echo $key ?>" class="js_del_coupon_payment"><i class="fa fa-trash" aria-hidden="true"></i></span></div>
                                        <?php }} ?>
                                    </div>
                                </div>
                                <div class="uk-width-3-5">
                                <?php
                                    $total_cart = (isset($cart['total_cart'])) ? $cart['total_cart'] : 0;
                                    $total_cart_promo = (isset($cart['total_cart_promo'])) ? $cart['total_cart_promo'] : $total_cart;
                                    $total_cart_coupon = (isset($cart['total_cart_coupon'])) ? $cart['total_cart_coupon'] : $total_cart_promo;

                                    $discount_promo = $total_cart_promo - $total_cart;
                                    $discount_coupon = $total_cart_coupon - $total_cart_promo;
                                    $total_cart = addCommas($total_cart);
                                    $total_cart_promo = addCommas($total_cart_promo);
                                    $total_cart_coupon = addCommas($total_cart_coupon);
                                    $discount_promo = addCommas($discount_promo);
                                    $discount_coupon = addCommas($discount_coupon);

                                 ?>
                                    <ul class="uk-list uk-clearfix uk-flex mb10">
                                        <li>
                                            <div class="text-right">Tổng giá trị đơn hàng:</div>
                                        </li>
                                        <li>
                                            <div class="text-right js_total_cart"><?php echo $total_cart ?>đ</div>
                                        </li>
                                    </ul>
                                    <ul class="uk-list uk-clearfix uk-flex mb10 mt10">
                                        <li>
                                            <div class="text-right ">Trừ khuyến mại</div>
                                        </li>
                                        <li>
                                            <div class="text-right js_discount_promo"><?php echo $discount_promo ?>đ</div>
                                        </li>
                                    </ul>
                                    <ul class="uk-list uk-clearfix uk-flex mb10 mt10">
                                        <li>
                                            <div class="text-right ">Trừ Coupon:</div>
                                        </li>
                                        <li>
                                            <div class="text-right js_discount_coupon"><?php echo $discount_coupon ?>đ</div>
                                        </li>
                                    </ul>


                                    <ul class="uk-list uk-clearfix uk-flex mb10 mt10">
                                        <li>
                                            <div class="text-right">Giảm giá ship theo CTKM:</div>
                                        </li>
                                        <li>
                                            <div class="text-right js_discount_ship">0đ</div>
                                        </li>
                                    </ul>
                                    <ul class="uk-list uk-clearfix uk-flex mb10 mt10">
                                        <li>
                                            <div class="text-right">Phí ship:</div>
                                        </li>
                                        <li>
                                            <div class="text-right js_ship">0đ</div>
                                        </li>
                                    </ul>
                                    <ul class="uk-list uk-clearfix uk-flex mb10 mt10">
                                        <li>
                                            <div class="text-right">Phí ship phải trả:</div>
                                        </li>
                                        <li>
                                            <div class="text-right js_total_ship">0đ</div>
                                        </li>
                                    </ul>


                                    <ul class="uk-list uk-clearfix uk-flex mb10 mt10">
                                        <li>
                                            <div class="text-right"><b>Tổng tiền:</b></div>
                                        </li>
                                        <li>
                                            <div class="text-right js_cart_coupon" data-val="<?php echo str_replace('.','',$total_cart_coupon) ?>"><b><?php echo $total_cart_coupon ?>đ</b></div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="uk-grid mb20">
                                <div class="uk-width-2-5">
                                </div>
                                <div class="uk-width-3-5">
                                    <div class="col-2 input-coupon"  style="width:100%">
                                        <input type="text" name="coupon" value="" class="input-text js_input_coupon_payment" placeholder="Nhập mã giảm gía"/>
                                        <button class="btn-coupon js_btn_coupon_payment">
                                            Áp dụng
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php if(isset($_list_product) && is_array($_list_product) && count($_list_product)){ ?>
                                <div class="uk-flex uk-flex-right">
                                    <button type="submit" name="create" value="create" class="btn-checkout" value="Gửi đơn hàng">Gửi đơn hàng</button>
                                </div>
                            <?php } ?>
                        </section>
                    </div>
                </div>
                <div class="cart-info mb20">
                    <form class="uk-form form" method="post" action="">
                        <section class="cart-panel cart-customer">
                            <header class="panel-head"><h2 class="heading"><span>Thông tin mua hàng</span></h2></header>
                            <section class="panel-body">
                                <?php $error = validation_errors(); echo !empty($error)?'<div class="alert alert-danger">'.$error.'</div>':'';?>
                                <div class="form-row uk-flex uk-flex-middle uk-clearfix">
                                    <div class="col-1"><label>Họ và tên</label></div>
                                    <div class="col-2">
                                        <?php echo form_input('fullname', set_value('fullname'), 'class="input-text" placeholder="Nhập họ tên" autocomplete="off"');?>
                                    </div>
                                </div>
                                <div class="form-row uk-flex uk-flex-middle uk-clearfix">
                                    <div class="col-1"><label>Số điện thoại</label></div>
                                    <div class="col-2">
                                        <div class="uk-grid uk-grid-medium uk-grid-width-large-1-2">
                                            <div>
                                                <?php echo form_input('phone', set_value('phone'), 'class="input-text" placeholder="Số điện thoại" autocomplete="off"');?>
                                            </div>
                                            <div>
                                                <?php echo form_input('phone_other', set_value('phone_other'), 'class="input-text" placeholder="Số khác - tùy chọn" autocomplete="off"');?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row uk-flex uk-flex-middle uk-clearfix">
                                    <div class="col-1"><label>Địa chỉ</label></div>
                                    <div class="col-2">
                                        <?php echo form_textarea('address_detail', set_value('address_detail'), 'class="textarea" placeholder="Nhập địa chỉ đầy đủ: Số nhà, tên đường" autocomplete="off"');?>
                                    </div>
                                </div>
                                <div class="form-row uk-flex uk-flex-middle uk-clearfix">
                                    <div class="col-1"><label>Email</label></div>
                                    <div class="col-2">
                                        <?php echo form_input('email', set_value('email'), 'class="input-text" placeholder="Nhập địa chỉ Email, không bắt buộc" autocomplete="off"');?>
                                    </div>
                                </div>
                                <div class="form-row uk-flex uk-flex-middle uk-clearfix">
                                    <div class="col-1"><label>Tỉnh/Thành Phố</label></div>
                                    <div class="col-2">
                                        <?php
                                            $listCity = getLocation(array(
                                                'select' => 'name, provinceid',
                                                'table' => 'vn_province',
                                                'field' => 'provinceid',
                                                'text' => 'Chọn Tỉnh/Thành Phố'
                                            ));
                                        ?>
                                        <?php echo form_dropdown('cityid', $listCity, '', 'class="form_dropdown"  id="city" placeholder="" autocomplete="off"');?>
                                    </div>
                                </div>
                                <div class="form-row uk-flex uk-flex-middle uk-clearfix">
                                    <div class="col-1"><label>Quận/Huyện</label></div>
                                    <div class="col-2">
                                        <select name="districtid" id="district" class="location">
                                            <option value="">Chọn Quận/Huyện</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row uk-flex uk-flex-middle uk-clearfix">
                                    <div class="col-1"><label>Phường/Xã</label></div>
                                    <div class="col-2">
                                        <select name="wardid" id="ward" class="location">
                                            <option value="">Chọn Phường/Xã</option>
                                        </select>
                                    </div>
                                </div>
                                 <script>
                                    var cityid = '<?php echo $this->input->post('cityid'); ?>';
                                    var districtid = '<?php echo $this->input->post('districtid') ?>';
                                    var wardid = '<?php echo $this->input->post('wardid') ?>';
                                </script>


                                <div class="extend-option">
                                    <div class="option-1">
                                        <div class="uk-flex uk-flex-middle check">
                                            <?php
                                                $checkbox_vat = $this->input->post('checkbox_vat');
                                                $classhidden = (isset($checkbox_vat) && $checkbox_vat==1) ? '' : 'uk-hidden';
                                            ?>
                                            <?php if(isset($checkbox_vat) && $checkbox_vat==1){ ?>
                                                <input type="checkbox" name="checkbox_vat" class="" checked value="1" />
                                                 <label class="label cart-label checked"></label>
                                            <?php }else{ ?>
                                                <input type="checkbox" name="checkbox_vat" class="" value="1" />
                                                 <label class="label cart-label"></label>
                                            <?php } ?>

                                            <span class="lb-title">Yêu cầu xuất hóa đơn VAT cho công ty hoặc tổ chức</span>
                                        </div>
                                        <div class="vat <?php echo $classhidden ?> extend">
                                            <div class="form-row uk-flex uk-flex-middle uk-clearfix">
                                                <div class="col-1"><label>Mã số thuế</label></div>
                                                <div class="col-2">
                                                    <?php echo form_input('mst', set_value('mst'), 'class="input-text" placeholder="Nhập Mã số thuế nếu có" autocomplete="off"');?>

                                                </div>
                                            </div>
                                            <div class="form-row uk-flex uk-flex-middle uk-clearfix">
                                                <div class="col-1"><label>Tên công ty</label></div>
                                                <div class="col-2">
                                                    <?php echo form_input('company', set_value('company'), 'class="input-text" placeholder="Tên công ty/tổ chức viết trên hóa đơn" autocomplete="off"');?>
                                                </div>
                                            </div>
                                            <div class="form-row uk-flex uk-flex-middle uk-clearfix">
                                                <div class="col-1"><label>Địa chỉ</label></div>
                                                <div class="col-2">
                                                    <?php echo form_input('company-address', set_value('company-address'), 'class="input-text" placeholder="Địa chỉ đăng ký của công ty/tổ chức với cơ quan thuế" autocomplete="off"');?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="option-1">
                                        <div class="uk-flex uk-flex-middle check">
                                            <?php
                                                $checkbox_receive = $this->input->post('checkbox_receive');
                                                $classhidden = (isset($checkbox_receive) && $checkbox_receive==1) ? '' : 'uk-hidden';
                                            ?>
                                            <?php if(isset($checkbox_receive) && $checkbox_receive==1){ ?>
                                                <input type="checkbox" name="checkbox_receive" class="" checked value="1" />
                                                 <label class="label cart-label checked"></label>
                                            <?php }else{ ?>
                                                <input type="checkbox" name="checkbox_receive" class="" value="1" />
                                                 <label class="label cart-label"></label>
                                            <?php } ?>
                                            <span class="lb-title">Nhận Hàng tại địa chỉ khác</span>
                                        </div>
                                        <div class="vat <?php echo $classhidden ?> extend">
                                            <div class="form-row uk-flex uk-flex-middle uk-clearfix">
                                                <div class="col-1"><label>Họ và tên</label></div>
                                                <div class="col-2">
                                                    <?php echo form_input('fullname_receive', set_value('fullname_receive'), 'class="input-text" placeholder="Nhập họ tên" autocomplete="off"');?>
                                                </div>
                                            </div>
                                            <div class="form-row uk-flex uk-flex-middle uk-clearfix">
                                                <div class="col-1"><label>Số điện thoại</label></div>
                                                <div class="col-2">
                                                    <div class="uk-grid uk-grid-medium uk-grid-width-large-1-2">
                                                        <div>
                                                            <?php echo form_input('phone_receive', set_value('phone_receive'), 'class="input-text" placeholder="Số điện thoại" autocomplete="off"');?>
                                                        </div>
                                                        <div>
                                                            <?php echo form_input('phone_2_receive', set_value('phone_2_receive'), 'class="input-text" placeholder="Số khác - tùy chọn" autocomplete="off"');?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row uk-flex uk-flex-middle uk-clearfix">
                                                <div class="col-1"><label>Địa chỉ</label></div>
                                                <div class="col-2">
                                                    <?php echo form_textarea('address_receive', set_value('address_receive'), 'class="textarea" placeholder="Nhập địa chỉ đầy đủ: Số nhà, tên đường" autocomplete="off"');?>
                                                </div>
                                            </div>
                                            <div class="form-row uk-flex uk-flex-middle uk-clearfix">
                                                <div class="col-1"><label>Email</label></div>
                                                <div class="col-2">
                                                    <?php echo form_input('email_receive', set_value('email_receive'), 'class="input-text" placeholder="Nhập địa chỉ Email, không bắt buộc" autocomplete="off"');?>
                                                </div>
                                            </div>


                                            <div class="form-row uk-flex uk-flex-middle uk-clearfix">
                                                <div class="col-1"><label>Tỉnh/Thành Phố</label></div>
                                                <div class="col-2">
                                                    <?php
                                                        $listCity = getLocation(array(
                                                            'select' => 'name, provinceid',
                                                            'table' => 'vn_province',
                                                            'field' => 'provinceid',
                                                            'text' => 'Chọn Tỉnh/Thành Phố'
                                                        ));
                                                    ?>
                                                    <?php echo form_dropdown('cityid_receive', $listCity, '', 'class=""  id="city_receive" placeholder="" autocomplete="off"');?>
                                                </div>
                                            </div>
                                            <div class="form-row uk-flex uk-flex-middle uk-clearfix">
                                                <div class="col-1"><label>Quận/Huyện</label></div>
                                                <div class="col-2">
                                                    <select name="districtid_receive" id="district_receive" class="location">
                                                        <option value="">Chọn Quận/Huyện</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-row uk-flex uk-flex-middle uk-clearfix">
                                                <div class="col-1"><label>Phường/Xã</label></div>
                                                <div class="col-2">
                                                   <select name="wardid_receive" id="ward_receive" class="location">
                                                        <option value="">Chọn Phường/Xã</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <script>
                                                var cityid_receive = '<?php echo $this->input->post('cityid_receive'); ?>';
                                                var districtid_receive = '<?php echo $this->input->post('districtid_receive') ?>';
                                                var wardid_receive = '<?php echo $this->input->post('wardid_receive') ?>';
                                            </script>



                                        </div>
                                    </div>
                                </div>
                            </section><!-- .panel-body -->
                        </section><!-- .cart-customer -->

                        <section class="cart-panel cart-payment">
                            <header class="panel-head"><h2 class="heading">Chọn hình thức thanh toán</h2></header>
                            <?php
                                $paycataList = $this->Autoload_Model->_get_where(array(
                                    'select' => 'id,title',
                                    'table' => 'page_catalogue',
                                ), TRUE);
                                $payList = $this->Autoload_Model->_get_where(array(
                                    'select' => 'id,title, catalogueid, description, image',
                                    'table' => 'page',
                                ), TRUE);
                                if(isset($paycataList) && check_array($paycataList)){
                                    foreach ($paycataList as $keyCata => $valCata) {
                                        if(isset($payList) && check_array($payList)){
                                            foreach ($payList as $keyPay => $valPay) {
                                                if($valPay['catalogueid'] == $valCata['id']){
                                                    $paycataList[$keyCata]['child'][] = $valPay;
                                                }
                                            }
                                        }
                                    }
                                }
                                // pre($paycataList);
                            ?>
                            <?php
                                $checkbox_payment = $this->input->post('payment');
                            ?>


                            <section class="panel-body">
                                <?php if(isset($paycataList) && check_array($paycataList) ){ ?>
                                    <?php foreach ($paycataList as $keyCata => $valCata) { ?>
                                        <div class="option-2">
                                            <div class="uk-flex uk-flex-middle check">
                                                <?php if(isset($checkbox_payment) && $checkbox_payment == $valCata['id']){ ?>
                                                    <input type="radio" name="payment" checked class="" value="<?php echo $valCata['id'] ?>">
                                                    <label class="label cart-radio checked"></label>
                                                <?php }else{ ?>
                                                    <input type="radio" name="payment" class="" value="<?php echo $valCata['id'] ?>">
                                                    <label class="label cart-radio"></label>
                                                <?php } ?>
                                                <span class="lb-title"><?php echo $valCata['title'] ?></span>
                                            </div>
                                            <?php if($valCata['id'] == 2){ ?>
                                                <div class="extend <?php echo (isset($checkbox_payment) && $checkbox_payment == 2) ? : 'uk-hidden' ?>">
                                                    <p style="margin-bottom: 15px;">Quý khách có thể đến một trong các địa chỉ sau để thanh toán và nhận hàng:</p>

                                                    <?php if(isset($valCata['child']) && check_array($valCata['child']) ){ ?>
                                                    <ul class="uk-list uk-clearfix list-payment-info">
                                                        <?php foreach ($valCata['child'] as $keyChild => $valChild) { ?>
                                                            <li>
                                                                <div class="payment-addr">
                                                                    <h3 class="title">
                                                                        <?php echo $valChild['title'] ?>
                                                                    </h3>
                                                                    <p><?php echo $valChild['description'] ?></p>
                                                                </div>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>

                                            <?php if($valCata['id'] == 3){ ?>
                                                <div class="extend <?php echo (isset($checkbox_payment) && $checkbox_payment == 3) ? : 'uk-hidden' ?>">
                                                    <p style="margin-bottom: 15px;">Quý khách có thể lựa chọn chuyển khoản tới 1 trong những ngân hàng sau:</p>
                                                    <?php if(isset($valCata['child']) && check_array($valCata['child']) ){ ?>
                                                    <ul class="uk-list uk-clearfix list-payment-info">
                                                        <?php foreach ($valCata['child'] as $keyChild => $valChild) { ?>
                                                            <li>
                                                                <div class="payment-bank uk-flex">
                                                                    <div class="thumb">
                                                                        <div class="image img-cover"><img src="<?php echo $valChild['image'] ?>" alt="<?php echo $valChild['title'] ?>"></div>
                                                                    </div>
                                                                    <div class="info">
                                                                        <h3 class="title"><?php echo $valChild['title'] ?></h3>
                                                                        <?php echo $valChild['description'] ?>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>


                                            <?php if($valCata['id'] == 4){ ?>
                                                <div class="extend <?php echo (isset($checkbox_payment) && $checkbox_payment == 4) ? : 'uk-hidden' ?>">
                                                    <p style="margin-bottom: 5px;">An toàn, tiện lợi, nhanh chóng - Chỉ cần thẻ ATM có kích hoạt thanh toán Online! </p>
                                                    <p style="margin-bottom: 15px;">Quý khách vui lòng chọn ngân hàng trong danh sách dưới đây:</p>
                                                    <ul class="uk-list uk-clearfix list-payment-online">
                                                        <?php if(isset($valCata['child']) && check_array($valCata['child']) ){ ?>
                                                        <?php foreach ($valCata['child'] as $keyChild => $valChild) { ?>
                                                        <li>

                                                            <div class="payment-online uk-flex uk-flex-middle">

                                                                <input type="radio" name="payment" class="" value="<?php echo $valChild['id'] ?>">
                                                                <label class="label cart-radio "></label>

                                                                <div class="thumb">
                                                                    <div class="image img-scaledown"><img src="<?php echo $valChild['image'] ?>" alt="<?php echo $valChild['title'] ?>"></div>
                                                                </div>
                                                                <div class="info"><?php echo $valChild['title'] ?></div>
                                                            </div>
                                                        </li>
                                                        <?php }} ?>

                                                    </ul>
                                                </div>
                                            <?php } ?>
                                        </div>  <!-- option2-->
                                <?php }} ?>
                            </section>
                        </section> <!-- cart-payment-->
                        <section class="cart-panel cart-transport">
                            <header class="panel-head"><h2 class="heading">Vận chuyển</h2></header>
                            <section class="panel-body">
                                <!-- <p>Tổng khối lượng các mặt hàng của quý khách là: <span style="color: #19abe0;">400 (g)</span></p>
                                <p>Quý khách vui lòng chọn hình thức nhận hàng:</p>
                                <div class="inner-option">
                                    <div class="option-2">
                                        <div class="uk-flex uk-flex-middle check">
                                            <input type="radio" name="transport" class="" value="">
                                            <label class="label checked"></label>
                                            <span class="lb-title">Muốn thỏa thuận với XPAND nhân viên chúng tôi sẽ gọi cho quý khách</span>
                                        </div>
                                    </div>
                                    <div class="option-2">
                                        <div class="uk-flex uk-flex-middle check">
                                            <input type="radio" name="transport" class="" value="">
                                            <label class="label"></label>
                                            <span class="lb-title">Muốn thỏa thuận với XPAND nhân viên chúng tôi sẽ gọi cho quý khách</span>
                                        </div>
                                    </div>
                                </div> -->

                                <p>Thời gian giao hàng:</p>
                                <section class="inner-option cart-payment">
                                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                        <?php $delivery_time = $this->configbie->data('delivery_time')  ?>
                                        <?php if(isset($delivery_time) && check_array($delivery_time) ){ ?>
                                            <?php foreach ($delivery_time as $keyTime => $valTime) { ?>
                                                <div class="option-2">
                                                    <div class="uk-flex uk-flex-middle check">
                                                    <?php $checkbox_time = $this->input->post('delivery-time') ?>
                                                    <?php if(isset($checkbox_time) && $checkbox_time==$keyTime){ ?>
                                                        <input type="radio" checked
                                                        name="delivery-time" class="" value="<?php echo $keyTime ?>" />
                                                        <label class="label cart-radio checked"></label>
                                                        <span class="lb-title"><?php echo $valTime ?></span>
                                                    <?php }else{ ?>
                                                        <input type="radio" name="delivery-time" class="" value="<?php echo $keyTime ?>" />
                                                        <label class="label cart-radio "></label>
                                                        <span class="lb-title"><?php echo $valTime ?></span>
                                                    <?php } ?>
                                                    </div>
                                                </div>
                                        <?php }} ?>
                                    </div>
                                </section>

                                <p>Ghi chú với hàng hóa đặt mua:</p>
                                <?php echo form_textarea('note', set_value('note'), 'class="text-area" placeholder="Hãy để lại lời nhắn" autocomplete="off"');?>
                            </section>
                        </section> <!-- cart-transport-->
                        <?php if(isset($list_product) && is_array($list_product) && count($list_product)){ ?>
                            <div class="uk-flex uk-flex-right cart-checkout">
                                <button type="submit" name="create" value="create" class="btn-checkout" value="Gửi đơn hàng"><i class="fa fa-shopping-cart"></i>Gửi đơn hàng</button>
                            </div>
                        <?php } ?>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <style>
        .ht2109_reverse {
            -webkit-flex-direction: row-reverse;
            -moz-flex-direction: row-reverse;
            -ms-flex-direction: row-reverse;
            -o-flex-direction: row-reverse;
            flex-direction: row-reverse;
        }
    </style>
</div><!-- #prdcatalogue -->
