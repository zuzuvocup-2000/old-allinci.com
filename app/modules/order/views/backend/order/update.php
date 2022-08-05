<div class="uk-container-center">
<div id="" class="gray-bg dashbard-1 ">
	<div class=" wrapper white-bg page-heading">
		<div class="row">
		<div class="col-lg-10">
			<h2>Thông tin đơn hàng</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Thông tin đơn hàng</strong></li>
			</ol>
		</div>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight block-order">
		<form method="post" action="" class="form-horizontal box" >
	        <div class="row">
	            <div class="col-md-9">

	                <div class="ibox">
	                    <div class="ibox-title">
	                        <span class="pull-right">(<strong><?php echo $detailorder['quantity'] ?></strong>) sản phẩm</span>
	                        <h5>Số lượng sản phẩm trong đơn hàng</h5>
	                    </div>
	                    <div class="ibox-content">
                			<div class="row m-b">
	                            <table class="table shoping-cart-table">
	                                <tbody>
                                	<?php if(isset($detail_list_prd) && is_array($detail_list_prd) && count($detail_list_prd)){ ?>
										<?php foreach($detail_list_prd as $key => $val){ ?>
			                                <tr>
			                                    <td width="70">
		                                        	<span class="img-scaledown image-post">
			                                        	<img src="<?php echo getthumb($val['image']) ?>">
		                                        	</span>
			                                    </td>
			                                    <td class="desc">
			                                        <h5><a href="#" class="">
			                                           <?php echo $val['title'] ?>
			                                        </a></h5>
			                                        <?php echo $val['code'] ?>
			                                    </td>

			                                    <td>
			                                    	<span>
			                                    		<span class="" data-val="<?php echo $val['quantity'] ?>">
			                                    		<?php echo $val['quantity'] ?>
				                                    	</span>
				                                    	<?php echo form_input('quantity', set_value('quantity'), 'class="form-control input-sm inline-block" style="width:40px; display:none" placeholder="" autocomplete="off"');?>
			                                    	</span>
			                                    	<span>x</span>
				                                    <?php echo addCommas($val['price_final']) ?>
													<span>đ</span>
			                                    </td>
			                                    <td>
			                                        <b class="nowrap">
			                                            <?php echo addCommas($val['price_final']*$val['quantity']).'đ' ?>

			                                        </b>
			                                    </td>
			                                </tr>
		                            <?php }} ?>
	                                </tbody>
	                            </table>
	                        </div>

	                        <div class="row m-b" style="border-top: 1px solid #ebeef0; padding-top: 10px">
	                        	<div class="col-sm-12 m-b">
	                        		<b>Thông tin hoá đơn</b>
	                        		<?php 
	                        			$extend = json_decode(base64_decode($detailorder['extend_json']), true);
	                        		?>

	                        		<?php //print_r($extend); exit; ?>
	                        	</div>
	                        	<div class="col-sm-12 m-b">
	                				<?php foreach ($extend as $keyEx => $valEx): ?>
	                					<?php 
	                					    $valEx = (!empty($valEx)) ? $valEx : '-';
	                						switch ($keyEx) {
											    case 'mst':
											       $title = 'Mã số thuế';
											       break;
											    case 'company':
											        $title = 'Tên công ty';
											        break;
											    case 'company_address':
											        $title = 'Địa chỉ công ty';
											        break;
											    default:
											        $title = '';
											        break;
											}
	                					?>
	                					<?php if($title != ''){ ?>
		                					<div class="col-sm-6">
												<div class="col-sm-6"><?php echo $title ?>:</div>
												<div class="col-sm-6">
													<span>
			                                    		<span class="js_change_input" data-val="<?php echo $valEx ?>">
			                                    		<?php echo $valEx ?>
				                                    	</span>
				                                    	<?php echo form_input($keyEx, set_value($keyEx), 'class="form-control input-sm inline-block" style="width:120px; display:none" placeholder="" autocomplete="off"');?>
			                                    	</span>	
												</div>
		                					</div>
	                					<?php } ?>
	                				<?php endforeach ?>
	                			</div>

                				<div class="col-sm-12 m-b">
	                        		<b>Thông tin địa chỉ nhận hàng khác</b>
	                        		<?php 
	                        			$extend = json_decode(base64_decode($detailorder['extend_json']), true);
	                        		?>

	                        		<?php 
	                        			// print_r($extend); exit; 
                        			?>
	                        	</div>
	                        	<div class="col-sm-12 m-b">
	                				<?php foreach ($extend as $keyEx => $valEx): ?>
	                					<?php 
	                					    $valEx = (!empty($valEx)) ? $valEx : '-';
	                						switch ($keyEx) {
											    case 'fullname_receive':
											        $title = 'Tên người nhận';
											        break;
											    case 'phone_receive':
											        $title = 'Sđt người nhận';
											        break;
											    case 'phone_2_receive':
											        $title = 'Sđt khác';
											        break;
											    case 'email_receive':
											        $title = 'Email';
											        break;
											    case 'address_receive':
											        $title = 'Đ/c chi tiết nhận hàng';
											        break;
											    case 'cityid_receive':
											        $title = 'Tỉnh/thành phố';
											        break;
											    case 'districtid_receive':
											        $title = 'Quận/huyện';
											        break;
											    case 'wardid_receive':
											        $title = 'Phường/xã';
											        break;
											    default:
											        $title = '';
											        break;
											}
	                					?>
	                					<?php if($title != ''){ ?>
		                					<div class="col-sm-6">
												<div class="col-sm-6"><?php echo $title ?>:</div>
												<div class="col-sm-6">
													<span>
			                                    		<span class="js_change_input" data-val="<?php echo $valEx ?>">
			                                    		<?php echo $valEx ?>
				                                    	</span>
				                                    	<?php echo form_input($keyEx, set_value($keyEx), 'class="form-control input-sm inline-block" style="width:120px; display:none" placeholder="" autocomplete="off"');?>
			                                    	</span>	
												</div>
		                					</div>
	                					<?php } ?>
	                				<?php endforeach ?>
	                			</div>
	                        </div>
	                        <div class="row m-b" style="border-top: 1px solid #ebeef0; padding-top: 10px">
		                        <div class="col-sm-6">
									<div class='m-b-sm'><b>Ghi chú</b></div>
									<?php echo form_textarea('note', htmlspecialchars_decode(html_entity_decode(set_value('note', $detailorder['note']))), 'class="form-control " placeholder="" autocomplete="off" ');?>
								</div>
								<div class="col-sm-6">
									<?php 
	                                    $total_cart = (isset($data_order['cart']['total_cart'])) ? $data_order['cart']['total_cart'] : 0;
	                                    $total_cart_promo = (isset($data_order['cart']['total_cart_promo'])) ? $data_order['cart']['total_cart_promo'] : $total_cart;
	                                    $total_cart_coupon = (isset($data_order['cart']['total_cart_coupon'])) ? $data_order['cart']['total_cart_coupon'] : $total_cart_promo;
	                                    $discount_promo = $total_cart_promo - $total_cart;
	                                    $discount_coupon = $total_cart_coupon - $total_cart_promo;

	                                    $total_cart = addCommas($total_cart);
	                                    $total_cart_promo = addCommas($total_cart_promo);
	                                    $total_cart_coupon = addCommas($total_cart_coupon);

	                                    $discount_promo = addCommas($discount_promo);
	                                    $discount_coupon = addCommas($discount_coupon);
	                                    $discount_other = (isset($data_order['cart']['discount_other'])) ? $data_order['cart']['discount_other'] : 0;

	                                 ?>
									<div class="row m-b-sm">
										<div class="col-sm-9">
											<div class="text-right">
												Tổng giá trị sản phẩm:
											</div>
										</div>
										<div class="col-sm-3">
											<div class="text-right">
												<?php echo $total_cart.'đ' ?>
											</div>
										</div>
									</div>
									<div class="row m-b-sm">
										<div class="col-sm-9">
											<div class="text-right">
												Giảm giá KM:
											</div>
										</div>
										<div class="col-sm-3">
											<div class="text-right">
												<?php echo $discount_promo.'đ' ?>
											</div>
										</div>
									</div>
									<div class="row m-b-sm">
										<div class="col-sm-9">
											<div class="text-right">
												Giảm giá coupon:
											</div>
										</div>
										<div class="col-sm-3">
											<div class="text-right">
		                                    	<?php echo addCommas($discount_coupon).'đ' ?>
											</div>
										</div>
									</div>
									<div class="row m-b-sm">
										<div class="col-sm-9">
											<div class="text-right">
												Giảm giá khác:
											</div>
										</div>
										<div class="col-sm-3">
											<div class="text-right">
												<span>
		                                    		<span class="js_change_input" data-val="<?php echo $discount_other ?>">
		                                    		<?php echo $discount_other.'đ' ?>
			                                    	</span>
			                                    	<?php echo form_input('discount_other', set_value('discount_other'), 'class="form-control int text-right input-sm inline-block" style="width:80px; display:none" placeholder="" autocomplete="off"');?>
		                                    	</span>	
											</div>
										</div>
									</div>
									<div class="row m-b-sm">
										<div class="col-sm-9">
											<div class="text-right">
												<b>Số tiền phải thanh toán:</b>
											</div>
										</div>
										<div class="col-sm-3">
											<div class="text-right">
												<b><?php echo $total_cart_coupon.'đ' ?></b>
											</div>
										</div>
									</div>

								</div>

	                        </div>
	                        <?php 
                            	$list_promotional_detail = json_decode($detailorder['promotional_detail'], true);
								if(isset($list_promotional_detail) && is_array($list_promotional_detail) && count($list_promotional_detail)){
							?>
		                        <div class="row m-b">
		                            <div class="col-sm-12">
		                            	<label>Chương trình khuyến mại được áp dụng:</label>
				                        <?php foreach ($list_promotional_detail as $key => $promotional_detail) { ?>
											<div class="text-muted small">
					                            <?php echo $promotional_detail; ?>
				                        	</div>
				                        <?php } ?>
		                            </div>
								</div>
	                        <?php } ?>

	                        <div class="row">
	                        	<div class="col-sm-12">
	                        		<div class="uk-flex uk-flex-middle uk-flex-space-between">
	                        			<div class="col-sm-3">
	                        				<label class="control-label m-r" for="status">Trạng thái</label>
	                        			</div>
	                        			<div class="col-sm-9">
			                				<?php echo form_dropdown('status', $this->configbie->data('status'), set_value('status' , $detailorder['status']) ,'class="form-control input-sm  m-r select3NotSearch"'); ?>
		                        		</div>
	                        		</div>
	                        	</div>
	                        </div>
	                    </div>
	                </div>

	            </div>
	            <div class="col-md-3">
	                <div class="ibox-title">
	                        <h5>Thông tin khách hàng</h5>
	                    </div>
	                    <div class="ibox-content">
	                    	<div class="row m-b">
								<div class="col-sm-4">
									<b>Tên:</b>
								</div>
								<div class="col-sm-8 js_change_input_1">
									<div class="text-right">
										<span class="js_change_input" data-val="<?php echo $detailorder['fullname'] ?>">
                                			<?php echo $detailorder['fullname']  ?>
                                    	</span>
                                    	<?php echo form_input('fullname', set_value('fullname', $detailorder['fullname']), 'class="form-control input-sm inline-block" style="width:120px; display:none" placeholder="" autocomplete="off"');?>
									</div>
								</div>
							</div>
							<div class="row m-b">
								<div class="col-sm-4">
									<b>SĐT:</b>
								</div>
								<div class="col-sm-8  js_change_input_1">
									<div class="text-right">
										<span class="js_change_input" data-val="<?php echo $detailorder['phone'] ?>">
                                			<?php echo number_phone($detailorder['phone'], $detailorder['phone']) ?>
                                    	</span>
                                    	<?php echo form_input('phone', set_value('phone'), 'class="form-control input-sm inline-block" style="width:120px; display:none" placeholder="" autocomplete="off"');?>
									</div>
								</div>
							</div>
							<div class="row m-b">
								<div class="col-sm-4">
									<b>Email:</b>
								</div>
								<div class="col-sm-8  js_change_input_1">
									<div class="text-right">
										<div class="text-right">
											<span class="js_change_input" data-val="<?php echo $detailorder['email'] ?>">
	                                			<?php echo $detailorder['email'] ?>
	                                    	</span>
	                                    	<?php echo form_input('email', set_value('email', $detailorder['email']), 'class="form-control input-sm inline-block" style="width:120px; display:none" placeholder="" autocomplete="off"');?>
										</div>
									</div>
								</div>
							</div>
							<div class="row m-b">
								<div class="col-sm-4">
									<b>Địa chỉ:</b>
								</div>
								<?php 
									// print_r($detailorder); exit;

								 ?>
								<div class="col-sm-8  js_change_input_1">
									<div class="text-right">
										<span class="js_change_input" data-val="">
                                			<?php echo $detailorder['address_detail'].' - '.$detailorder['address_ward'].' - '.$detailorder['address_city'].' - '.$detailorder['address_distric'] ?>
                                    	</span>
                                    	<span style="display: none">
                                    		<?php echo form_textarea('address_detail', set_value('address_detail', $detailorder['address_detail']), 'class="textarea" style="height:50px; width:159px" placeholder="Nhập địa chỉ đầy đủ: Số nhà, tên đường" autocomplete="off"');?>
	                                    	<?php 
	                                            $listCity = getLocation(array(
	                                                'select' => 'name, provinceid',
	                                                'table' => 'vn_province',
	                                                'field' => 'provinceid',
	                                                'text' => 'Chọn Tỉnh/Thành Phố'
	                                            ));
	                                        ?>
	                                        <?php echo form_dropdown('cityid', $listCity, $detailorder['cityid'], 'class="form_dropdown input-sm m-b-sm"  id="city" placeholder="" autocomplete="off" style="padding: 5px 4px;"');?>
											<select name="districtid" id="district" class="location form_dropdown input-sm  m-b-sm">
	                                            <option value="">Chọn Quận/Huyện</option>
	                                        </select>
	                                        <select name="wardid" id="ward" class="location form_dropdown input-sm  m-b-sm">
	                                            <option value="">Chọn Phường/Xã</option>
	                                        </select>
	                                         <script>
			                                    var cityid = '<?php echo $detailorder['cityid']; ?>';
			                                    var districtid = '<?php echo $detailorder['districtid']; ?>';
			                                    var wardid = '<?php echo $detailorder['wardid']; ?>';
			                                </script>
			                            </span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<button name="close" class="js_close_windown btn btn-danger pull-right">Đóng</button>
									<button type="submit" name="update" value="update" class="btn btn-success  m-r-sm  pull-right">Lưu</button>
								</div>
							</div>
	                    </div>
	                </div>
	            </div>
	        </div>
		</form>
	</div>
</div>
</div>