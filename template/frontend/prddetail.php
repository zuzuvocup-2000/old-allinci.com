<!DOCTYPE html>
<html>
<head>
<?php require_once 'common/head.php'; ?>
</head>
<body>
	<?php require_once 'common/header.php'; ?>
	
	<div id="prddetail" class="page-body">
		<div class="main-breadcrumb">
			<div class="uk-container uk-container-center">
				<ul class="uk-breadcrumb uk-margin-remove">
					<li><a href="" title="Trang chủ">Trang chủ</a></li>
					<li><a href="" title="">Sản phẩm</a></li>
					<li class="uk-active"><a href="" title="">Aliquam Consequat</a></li>
				</ul>
			</div>
		</div>
		
		<section class="prddetail">
			<div class="uk-container uk-container-center">
				<div class="uk-grid uk-grid-medium uk-grid-width-medium-1-2 uk-grid-width-large-1-2">
					<div class="product-gallery">
						<div class="gallery-with-thumbs">
							<div class="product-full-image main-slider image-popup">

								<!-- Slides -->
								<div class="swiper-wrapper">
									<?php for($i = 1; $i<=9; $i++){ ?>
									<figure class="swiper-slide">
										<a href="resources/img/upload/prd-<?php echo $i ?>.jpg" data-size="800x800">
											<img src="resources/img/upload/prd-<?php echo $i ?>.jpg" alt="Product Image">
											<div class="image-overlay"><i class="pe-7s-expand1"></i></div>
										</a>
										<figcaption class="visually-hidden">
											<span>Product Image</span>
										</figcaption>
									</figure>
									<?php } ?>
								</div>
							</div> <!-- end of product-full-image -->

							<div class="product-thumb-image pos-r">
								<div class="nav-slider">

									<!-- Slides -->
									<div class="swiper-wrapper">
										<?php for($i = 1; $i<=9; $i++){ ?>
										<div class="swiper-slide">
											<img src="resources/img/upload/prd-<?php echo $i ?>.jpg" alt="Product Thumbnail Image">
										</div>
										<?php } ?>
									</div>

									<!-- Navigation -->
									<div class="swiper-arrow next"><i class="fa fa-angle-right"></i></div>
									<div class="swiper-arrow prev"><i class="fa fa-angle-left"></i></div>
								</div> <!-- end of nav-slider -->
							</div> <!-- end of product-thumb-image -->
						</div> <!-- end of gallery-with-thumbs -->
					</div> <!-- end of product-gallery -->

					<div class="product-info">
						<h1 class="prd-name">Điện thoại Samsung Galaxy M10 - Hàng phân phối chính hãng</h1>
						<div class="uk-flex uk-flex-middle">
							<div class="prd-code">Mã sản phẩm: PRD01</div>
							<div class="product-ratings m0">
								<div class="rating-box">
									<ul class="uk-list uk-clearfix uk-flex uk-flex-middle rating d-flex">
										<li><i class="fa fa-star"></i></li>
										<li><i class="fa fa-star"></i></li>
										<li><i class="fa fa-star"></i></li>
										<li><i class="fa fa-star"></i></li>
										<li><i class="fa fa-star"></i></li>
									</ul>
								</div>
							</div>
						</div>
						<div class="prd-price">
							<div class="uk-flex uk-flex-middle">
								<div class="old-price">2.800.000 <sup>đ</sup></div>
								<div class="new-price">2.000.000 <sup>đ</sup></div>
								<div class="percent">10%</div>
							</div>
						</div>
						
						<div class="prd-description">
							The best is yet to come! Give your walls a voice with a framed poster. This aesthethic, optimistic poster will look great in your desk or in an open-space office. Painted wooden frame with passe-partout for more depth.
						</div>
						
						<div class="prd-option">
							<div class="subtitle">Lựa chọn Options</div>
							<div class="option-block">
								<div class="product-size uk-flex uk-flex-middle uk-clearfix">
									<label>Size</label>
									<select class="nice-select">
										<option>S</option>
										<option>M</option>
										<option>L</option>
									</select>
								</div>
							</div>
							<div class="option-block">
								<div class="product-color uk-clearfix">
									<div class="mb10">Màu sắc</div>
									<ul class="color-list uk-list">
									   <li>
											<a class="white" href="#"></a>
										</li>
										<li>
											<a class="orange active" href="#"></a>
										</li>
										<li>
											<a class="paste" href="#"></a>
										</li>
									</ul>
								</div>
							</div>
							<div class="option-block">
								<div class="product-stock">
								   <label>Quantity</label>
									<ul class="uk-clearfix uk-list uk-flex uk-flex-middle">
										<li class="box-quantity">
											<form action="#">
												<input class="quantity" type="number" min="1" value="1">
											</form>
										</li>
										<li>
											<button type="button" class="default-btn">Thêm vào giỏ hàng</button>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		
	</div><!-- #prdcatalogue -->
	<?php require_once 'common/footer.php'; ?>
	<?php require_once 'common/offcanvas.php'; ?>
	<?php require_once 'common/script.php'; ?>
</body>
</html>