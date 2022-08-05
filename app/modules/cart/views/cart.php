<div class="main-breadcrumb">
	<div class="uk-container uk-container-center">
		<ul class="uk-breadcrumb uk-margin-remove">
			<li><a href="<?php echo BASE_URL ?>" title="Trang chủ">Trang chủ</a></li>
		    <li class="uk-active"><a href="<?php echo rewrite_url('cart/frontend/cart/cart') ?>" title="Giỏ hàng"> Giỏ hàng</a></li>
		</ul>
	</div>
</div>
<div id="cart-wrapper" class="page-body">

	<div class="uk-container uk-container-center fix-container">
		<section class="main-cart">

			<header class="panel-head">
				<div class="title"><h2>Giỏ hàng của bạn(<span class="js_total_prd"><?php echo (isset($cart['total_quantity'])) ? $cart['total_quantity'] : 0; ?></span>)SP</h2></div>
			</header>
			<section class="panel-body">
                <div class="table-responsive">
                    <table class="table_list_prd table-border table-width-100">
                        <thead>
                            <tr>
                                <td style="width: 32%">Ảnh sản phẩm</td>
                                <td style="width: 32%">Tên sản phẩm</td>
                                <td>Số lượng</td>
                                <td>Giá</td>
                                <td>Tổng tiền</td>
                            </tr>
                        </thead>
                        <tbody class="js_list_prd">
                            <?php if(isset($list_product) && is_array($list_product) && count($list_product)){ ?>
                                <?php foreach($list_product as $key => $val){ ?>
                                    <?php 
                                        $info = getPriceFrontend(array('productDetail' => $val['detail']));
                                        $image = getthumb((isset($val['version']['image']) && $val['version']['image'] != '' && $val['version']['image'] != 'template/not-found.png') ? $val['version']['image'] : $val['detail']['image']);

                                        $title =  $val['detail']['title'].' '.((isset($val['version']['title'])) ? $val['version']['title'] : '');

                                        $href = rewrite_url($val['detail']['canonical']);
                                        $content = $val['content'];
                                        $description_litter = cutnchar(strip_tags($val['detail']['description']),400);
                                     ?>
                                    <tr class="js_data_prd" data-rowid="<?php echo $val['rowid'] ?>" data-quantity="<?php echo $val['qty'] ?>">
                                        <td>
                                            <a href="single-product.html"><img src="<?php echo $image ?>" alt="<?php echo $title ?>" title="Cas Meque Metus" class="img-thumbnail"></a>
                                        </td>
                                        <td>
                                            <a href="<?php echo $href ?>"><?php echo $title ?></a>
                                            <?php echo $content ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <div class=" quantity">
                                                <div class="uk-flex">
                                                    <input type="text" name="quantity" value="<?php echo $val['qty'] ?>" size="1" class="form-control input-border js_update_quantity" autocomplete="off" >
                                                    <button type="submit" data-toggle="tooltip" data-direction="top" class="btn btn-primary js_refesh_quantity" data-original-title="Update"><i class="fa fa-refresh"></i></button>
                                                    <button type="button" data-toggle="tooltip" data-direction="top" class="btn btn-danger pull-right js_del_prd" data-original-title="Remove"><i class="fa fa-times-circle"></i></button>
                                                </div>
                                                
                                            </div>
                                        </td>
                                        <td>
                                            <div style ="text-decoration: line-through; color:#999"><?php echo $info['price_old'] ?></div>
                                             <?php echo $info['price_final'] ?>
                                        </td>
                                        <td>
                                             <?php echo addCommas(getPriceFinal($val['detail'])*$val['qty']) ?><sup>₫</sup>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php }else{ ?>
                                <tr>Không có sản phẩm trong giỏ hàng</tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <?php echo (isset($html_gift)) ? $html_gift :'' ?>

                <?php if(isset($cart['promotion']) && is_array($cart['promotion']) && count($cart['promotion'])){ ?>
                    <div class="js_list_promo">
                        <?php foreach ($cart['promotion'] as $key => $value){ ?>
                            <?php echo $value.'<br>' ?>
                        <?php } ?>
                    </div>
                <?php } ?>
			</section>

			<footer class="panel-foot">
                <div class="title"><h2>Tiến hành thanh toán</h2></div>

                <div class="uk-flex uk-flex-middle uk-flex-space-between mb20">
                    <div>
                        <div class="uk-flex uk-flex-middle mb10">
                            <b style=" min-width: 180px" class="main-title">Sử dụng mã giảm giá</b>
                            <input  type="text" class="form-control input-border input-coupon js_input_coupon m-r" placeholder="Nhập mã giả giá (nếu có)">
                            <button type="button" class="btn btn-w-m btn-success js_btn_coupon">Áp dụng</button>
                        </div>
                        <div class="mb10">Mã giảm giá đã thêm vào giỏ hàng</div>
                        <div class="js_list_coupon">
                            <?php if(isset($cart['list_coupon']) && is_array($cart['list_coupon']) && count($cart['list_coupon'])){ ?>
                                <?php foreach ($cart['list_coupon'] as $key => $value) { ?>
                                    <div><?php echo '<b>Mã '.$key.'</b>: '.$value['promo_detail'] ?> <span data-coupon="<?php echo $key ?>" class="js_del_coupon"><i class="fa fa-trash" aria-hidden="true"></i></span></div>
                            <?php }} ?>
                        </div>
                    </div>
                    <table class="table table-border">
                        <tbody>
                            <?php 
                                $total_cart = addCommas((isset($cart['total_cart'])) ? $cart['total_cart'] : 0);
                                $total_cart_promo = addCommas((isset($cart['total_cart_promo'])) ? $cart['total_cart_promo'] : $total_cart);
                                $total_cart_coupon = addCommas((isset($cart['total_cart_coupon'])) ? $cart['total_cart_coupon'] : $total_cart_promo);

                             ?>
                            <tr>
                                <td><strong>Tổng tiền:</strong></td>
                                <td><span class="js_total_cart"><?php echo $total_cart  ?></span><sup>₫</sup></td>
                            </tr>
                            <tr>
                                <td><strong>Tổng tiền sau KM:</strong></td>
                                <td><span class="js_cart_promo"><?php echo  $total_cart_promo ?></span><sup>₫</sup></td>
                            </tr>
                            <tr>
                                <td><strong>Tổng tiền sau CP:</strong></td>
                                <td><span class="js_cart_coupon"><?php echo $total_cart_coupon  ?></span><sup>₫</sup></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                    <a href="<?php echo site_url('') ?>" class="btn btn-continue btn-cart">Tiếp tục mua hàng</a>
                    <?php if(isset($list_product) && is_array($list_product) && count($list_product)){ ?>
                        <a href="<?php echo site_url('thanh-toan') ?>" class="btn btn-payment btn-cart">Thanh toán</a>
                    <?php } ?>
                </div>

			</footer>

		</section>
	</div>
</div>