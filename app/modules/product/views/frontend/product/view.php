<div id="js_prd_info" data-info="<?php echo $data_info ?>"
	data-price="<?php echo $productDetail['price'] ?>"
	data-price_sale="<?php echo $productDetail['price_sale'] ?>"
	data-price_contact="<?php echo $productDetail['price_contact'] ?>"
	 data-id="<?php echo $productDetail['id'] ?>"  data-name= "<?php echo $productDetail['title'] ?>" ></div>
<div id="quantity" data-quantity="1"></div>
<?php
	$prd_title = $productDetail['title'];
	$prd_code = $productDetail['code'];
	$prd_info = $productDetail['info'];
	$list_image = json_decode(base64_decode($productDetail['image_json']), true);
	$prd_href = rewrite_url($productDetail['canonical']);
    $prd_description = $productDetail['description'];
    // $prd_extend_des = json_decode($productDetail['extend_description'], true);

    $prd_description_litter = $productDetail['description'];
    $commnet = comment(array('id' => $productDetail['id'], 'module' => 'product'));
    $prd_rate = '';
	if(isset($commnet) && is_array($commnet) && count($commnet)){
		$prd_rate = round($commnet['statisticalRating']['averagePoint']);
	}

	// print_r($prd_info);die;
?>
<div id="prdcatalog" class="page-body _page_body">
	<?php $this->load->view('homepage/frontend/common/breadcrumb'); ?>
	<?php
		$list_image = json_decode(base64_decode($productDetail['image_json']), TRUE);
		// print_r($gallery);die;
	?>
	<div class="uk-container uk-container-center">
		<section class="prd-detail"><!-- CHI TIẾT SẢN PHẨM -->
			<header class="panel-head">
				<div class="uk-grid uk-grid-medium">
					<div class="uk-width-medium-1-2">
						<div class="prd-gallerys">
							<div id="slider" class="flexslider">
								<script type="text/javascript">
									$(document).ready(function() {
										$('#galleryPrd').lightGallery({
											thumbnail:true
										}); 
									})
								</script>

								<ul class="slides" id="galleryPrd">
									<?php if(isset($list_image) && is_array($list_image) && count($list_image)){ ?>
									<?php foreach($list_image as $key => $val) { ?>
									<li data-src="<?php echo $val; ?>" data-sub-html="<?php echo $productDetail['title']; ?>">
										<div class="thumb">
											<a class="image img-scaledown" href="<?php echo $val; ?>" title="<?php echo $productDetail['title']; ?>"><img src="<?php echo $val; ?>" alt="<?php echo $productDetail['title']; ?>" /></a>
										</div>
									</li>
									<?php }} ?>
								</ul>
							</div>
							<div id="carousel" class="flexslider">
								<ul class="slides">
									<?php if(isset($list_image) && is_array($list_image) && count($list_image)){ ?>
									<?php foreach($list_image as $key => $val) { ?>
									<li>
										<div class="thumb">
											<span class="image img-scaledown"><img src="<?php echo $val;?>" alt="<?php echo $val;?>" /></span>
										</div>
									</li>
									<?php }} ?>
								</ul>
							</div>
							<!-- Không có slide ảnh
							<div class="cover"><a class="image img-cover" href="" title=""></a></div>
							-->
						</div> <!-- end of product-gallery -->
					</div>
					<div class="uk-width-medium-1-2">
						<div class="prd-infor">
							<h1 class="prd-title"><?php echo $productDetail['title']; ?></h1>
							<div class="detail-info">
								<div class="left">
									<?php
										// print_r($prd_info);die;
									?>
									<?php if($prd_info['flag'] == 0){ ?>
										<p class="uk-flex uk-flex-middle mb20">
											<span class="main-price mr20"><?php echo $prd_info['price_final'] ?></span>
											<span class="value"><s><?php echo $prd_info['price_old']; ?></s></span>
										</p>
									<?php }else{ ?>
										<?php if($prd_info['price_old'] == 0 && $prd_info['price_final'] == 0){ ?>
											<p>
												<span class="price-contact">Giá: Liên hệ</span>
											</p>
										<?php }else{ ?>
											<p>
												<span class="main-price"><?php echo $prd_info['price_final'] ?></span>
											</p>
										<?php }?>
									<?php } ?>
								</div>
								<div class="prd-excerpt">
									<?php echo $prd_description; ?>
								</div>

								<ul class="uk-list btn-groups uk-clearfix uk-flex uk-flex-middle mb30">
									<li class="count"><div class="uk-position-relative">
										<input type="text" name="qty[]" value="1" class="quantity ajax-quantity">
										<span class="_btn abate"></span><span class="_btn augment"></span></div>
									</li>
									<li><a class="btn btn-buy js_buy" data-quantity="1" data-redirect="true" href="" title="Mua hàng">Mua hàng</a></li>
									<!-- <li><a class="btn btn-contact" href="#order-form" data-uk-modal title="Liên hệ">Liên hệ</a></li> -->
								</ul>
							</div>
							<ul class="uk-list uk-clearfix prd-icon">
								<li>
									<!-- <i class="fa fa-gg"></i> -->
									<a href="<?php echo rewrite_url($detailCatalogue['canonical'], true, true); ?>"><b>Danh mục : </b><?php echo $detailCatalogue['title']; ?></a>
								</li>
								<li>
									<!-- <i class="fa fa-tags"></i> -->
									<span><b>Mã sản phẩm : </b><?php echo $productDetail['code']; ?></span>
								</li>
								<!-- <li> -->
									<!-- <i class="fa fa fa-codepen"></i> -->
								<!-- </li> -->
							</ul>
							<div class="prd-content uk-visible-large">
								<?php
									// print_r($extend_description);die;
								 ?>
								<?php if(isset($extend_description['title']) && is_array($extend_description['title']) && count($extend_description['title'])){ ?>
								<ul class="uk-list uk-clearfix nav-tabs" data-uk-switcher="{connect:'#tabContent'}">
										<?php foreach ($extend_description['title'] as $keyT => $valT) {?>
											<li class="<?php echo ($keyT == count($extend_description['title']) - 1)? 'uk-active' : '';?>"><?php echo $valT; ?></li>
										<?php } ?>
								</ul>
								<?php } ?>
								<?php if(isset($extend_description['description']) && is_array($extend_description['description']) && count($extend_description['description'])){ ?>
								<ul id="tabContent" class="uk-switcher tab-content">
									<?php foreach ($extend_description['description'] as $keyD => $valD) {?>
										<li>
											<article class="article detail-content">
												<?php echo $valD; ?>
											</article><!-- .article -->
										</li>
									<?php } ?>
							   </ul>
								<?php } ?>
							</div><!-- .prd-content -->
							
						</div><!-- .prd-infor -->
					</div>
				</div><!-- .uk-grid -->
			</header><!-- .panel-head -->

			<section class="panel-body">
				<div class="prd-content-mobile uk-hidden-large">
					<?php if(isset($prdExtendDesc) && is_array($prdExtendDesc) && count($prdExtendDesc)){ ?>
					<ul class="uk-list uk-accordion" data-uk-accordion="{showfirst: true}">
						<?php foreach($prdExtendDesc as $keyEx => $valEx){?>
							<li>
								<a class="uk-accordion-title accordion-label"><?php echo $valEx['title']; ?></a>
								<div class="uk-accordion-content accordion-content">
									<article class="article detail-content">
										<?php echo $valEx['desc']; ?>
									</article><!-- .article -->
								</div>
							</li>
						<?php } ?>
					</ul>
					<?php } ?>
				</div><!-- .mobile-prd-content -->
			</section><!-- .panel-body-->
			
			<?php //$this->load->view('homepage/frontend/core/comment', array('module' => $module,'moduleid' => $productDetail['id'])); ?>

			<?php if(isset($relaList) && is_array($relaList)  && count($relaList)){ ?>
				<section class="catalog-panel homepage-product rela-panel mb-common">
					<!-- <div class="uk-container uk-container-center"> -->
						<header class="panel-head">
							<h2 class="heading-1"><span>Sản phẩm liên quan</span></h2>
						</header>
						<section class="panel-body">
							<?php 
								$data = '';
								$data = array(
									'productList' => $relaList,
								);
							?>
							<?php $this->load->view('homepage/frontend/core/productList', $data, FALSE); ?>
						</section>
					<!-- </div> -->
				</section>
				<?php } ?>
				<!-- homepage-product -->
		</section><!-- .prd-detail -->

	</div>


</div><!-- .page-body -->

<!-- This is the modal -->
<div id="order-form" class="uk-modal">
	<form action="" method="" class="form uk-form" id="form-baogia">
		<div class="uk-modal-dialog" style="padding: 0;">
			<a class="uk-modal-close uk-close"></a>
			<div class="uk-modal-header">
				<h2 class="md-heading"><span>Liên hệ báo giá</span></h2>
				<div class="md-desc">( Hãy để lại thông tin cho chúng tôi. Chúng tôi sẽ liên lạc ngay với bạn )</div>
			</div>
			<div class="modal-content loading">
				<div class="uk-flex uk-flex-middle uk-flex-space-between mb20">
					<label class="md-label">Sản phẩm</label>
					<div class="form-row title"><?php echo $productDetail['title']; ?></div>
					<input type="hidden" name="order_prd_name" value="<?php echo $productDetail['title']; ?>" class="form-control order_prd_name" placeholder="" autocomplete="off">
				</div>
				<div class="bg-loader"></div>
				<div class="error hidden">
					<div class="alert alert-danger"></div>
				</div><!-- /.error -->

				<div class="uk-flex uk-flex-middle uk-flex-space-between mb10">
					<label class="md-label">Họ tên</label>
					<div class="form-row">
						<?php echo form_input('order_fullname', htmlspecialchars_decode(html_entity_decode(set_value('order_fullname'))), 'placeholder="Nhập họ tên" class="input-text order order-fullname" autocomplete="off"');?>
					</div>
				</div>
				<div class="uk-flex uk-flex-middle uk-flex-space-between mb10">
					<label class="md-label">Số điện thoại</label>
					<div class="form-row">
						<?php echo form_input('order_phone', htmlspecialchars_decode(html_entity_decode(set_value('order_phone'))), 'placeholder="Nhập số điện thoại" class="input-text order order-phone" autocomplete="off"');?>
					</div>
				</div>
				<div class="uk-flex uk-flex-middle uk-flex-space-between mb10">
					<label class="md-label">Email</label>
					<div class="form-row">
						<?php echo form_input('order_mail', htmlspecialchars_decode(html_entity_decode(set_value('order_mail'))), 'placeholder="Nhập địa chỉ email" class="input-text order order-email" autocomplete="off"');?>
					</div>
				</div>
				<div class="uk-flex uk-flex-middle uk-flex-space-between mb10">
					<label class="md-label">Nhu cầu cụ thể</label>
					<div class="form-row">
						<?php echo form_input('order_message', htmlspecialchars_decode(html_entity_decode(set_value('order_message'))), 'placeholder="Ví dụ: Gửi báo giá, tư vấn ..." class="input-text order order-message" autocomplete="off"');?>
					</div>
				</div>
				<div class="uk-text-center">
					<button type="submit" value="submit" class="btn order order-1">Gửi thông tin</button>
				</div>

			</div>
		</div>
	</form>
</div>

<script>
	$(window).load(function() {
		var wd_width = $(window).width();
		if(wd_width > 768){
			// $(".zoom_image").elevateZoom({
			// 	'responsive':true,
			// 	'zoomWindowWidth': 300,
			// 	'zoomWindowHeight': 300,
			// 	'borderSize': 2,
			// 	'borderColour': '#ccc',
			// 	'lensColour': 'transparent',
			// 	'lensSize': 10,
			// 	'scrollZoom': true,

			// });
			// $(".zoom_image").ezPlus();
		}

	});
</script>
<script type="text/javascript">
	$(window).load(function() {
	  $('#carousel').flexslider({
		animation: "slide",
		controlNav: false,
		directionNav: false,
		animationLoop: false,
		slideshow: false,
		itemWidth: 100,
		itemMargin: 5,
		prevText: '',
		nextText: '',
		asNavFor: '#slider'
	  });
	  $('#slider').flexslider({
		animation: "slide",
		directionNav: true,
		controlNav: false,
		animationLoop: false,
		slideshow: false,
		prevText: '',
		nextText: '',
		sync: "#carousel"
	  });
	});
</script>
