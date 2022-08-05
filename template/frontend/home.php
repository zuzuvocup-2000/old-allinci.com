<!DOCTYPE html>
<html>
<head>
<?php require_once 'common/head.php'; ?>
</head>
<body>
	<?php require_once 'common/header.php'; ?>
	
	<div id="homepage" class="page-body">
		<?php require_once 'common/mainslide.php'; ?>
		<?php $category = array('Cameras','Game Pad','Laptop','Computers','Headphone','Mobile','TVs','Smart Watch'); ?>
		<section class="homepage-category page-panel">
			<div class="uk-container uk-container-center">
				<header class="panel-head"><h2 class="heading-1"><a href="" title="">Danh mục sản phẩm</a></h2></header>
				<section class="panel-body">
					<!-- Set up your HTML -->
					<div class="owl-carousel owl-theme">
						<div class="category-block">
							<?php for($i = 0; $i < count($category); $i++){ ?>
							<div class="category-item uk-clearfix">
								<div class="thumb"><a href="" title="" class="image img-scaledown"><img  src="resources/img/upload/cat-<?php echo $i+1; ?>.jpg" alt="" /></a></div>
								<div class="info">
									<h3 class="title"><a href="" title=""><?php echo $category[$i]; ?></a></h3>
									<ul class="uk-list uk-clearfix children">
										<li><a href="" title="">Digital Camera</a></li>
										<li><a href="" title="">DSLR Camera</a></li>
										<li><a href="" title="">Lighting & Studio</a></li>
										<li><a href="" title="">Xem thêm</a></li>
									</ul>
								</div>
							</div>
							<?php if(($i + 1) % 2 == 0 && ($i + 1) < count($category)){ echo '</div><div class="category-block">'; } ?>
							<?php } ?>
						</div>
					</div>
				</section>
			</div>
		</section><!-- .homepage-wrapper -->
		
		<?php $product = array('Máy ảnh mẫu 1','Máy ghi hình mẫu 1','Cameras mẫu 1','Tai nghe mẫu 1'); ?>
		<section class="homepage-deal page-panel">
			<div class="uk-container uk-container-center">
				<header class="panel-head"><h2 class="heading-2"><a href="" title=""><i class="pe-7s-compass"></i>Khuyến Mãi Hot </a></h2></header>
				<section class="panel-body">
					<!-- Set up your HTML -->
					<div class="owl-carousel owl-theme">
						<?php for($i = 0; $i < count($product); $i++){ ?>
						<div class="product-block">
							<div class="product uk-clearfix">
								<div class="thumb">
									<div class="percent">-60%</div>
									<a href="" title="" class="image img-scaledown img-shine"><img  src="resources/img/upload/promo-<?php echo $i+1; ?>.jpg" alt="" /></a>
									<div class="countdown-wrapper">
										<div class="countdown-timer instance-0<?php echo $i; ?>" data-countdown-year="2019" data-countdown-month="<?php echo 7+$i; ?>" data-countdown-day="10"></div>
									</div>
								</div>
								<div class="info">
									<h3 class="title"><a href="" title=""><?php echo $product[$i]; ?></a></h3>
									<div class="product-ratings">
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
									<div class="product-price">
										<div class="old-price">3.500.000<sup>đ</sup></div>
										<div class="new-price">2.800.000<sup>đ</sup></div>
									</div>
								</div>
							</div>
						</div>
						<?php } ?>
					</div>
				</section>
			</div>
		</section><!-- .deal -->
		
		<section class="homepage-banner">
			<div class="uk-container uk-container-center">
				<div class="promo-banner hover-effect-1 mt-half"><a href="" title="" class="image img-cover"><img src="resources/img/upload/banner-10.jpg" alt="" /></a></div>
			</div>
		</section>
		
		
		<?php for($e = 0; $e <= 3; $e++){ ?>
		<?php $product_2 = array('Máy ảnh mẫu 2','Máy ghi hình mẫu 2','Cameras mẫu 2','Tai nghe mẫu 2','Bàn Phím mẫu số 1','Tài nghe mẫu số 12'); ?>
		<section class="homepage-product">
			<div class="uk-container uk-container-center">
				<header class="panel-head"><h2 class="heading-1"><a href="" title="">Cameras </a></h2></header>
				<section class="panel-body">
					<!-- Set up your HTML -->
					<div class="owl-carousel owl-theme">
						
						<div class="product-block">
							<?php for($j = 0; $j < count($product_2); $j++){ ?>
							<div class="product uk-clearfix">
								<div class="thumb">
									<div class="percent">-60%</div>
									<a href="" title="" class="image img-scaledown img-shine"><img  src="resources/img/upload/product-<?php echo $j+1; ?>.jpg" alt="" /></a>
								</div>
								<div class="info">
									<h3 class="title"><a href="" title=""><?php echo $product_2[$i]; ?></a></h3>
									<div class="product-ratings">
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
									<div class="product-price">
										<div class="old-price">3.500.000<sup>đ</sup></div>
										<div class="new-price">2.800.000<sup>đ</sup></div>
									</div>
								</div>
							</div>
							<?php if(($j + 1) % 2 == 0 && ($j + 1) < count($product_2)){ echo '</div><div class="product-block">'; } ?>
							<?php } ?>
						</div>
						
					</div>
				</section>
			</div>
		</section>
		<section class="homepage-banner">
			<div class="uk-container uk-container-center">
				<div class="uk-flex uk-flex-middle uk-flex-space-between">
					<div class="promo-banner hover-effect-1 mt-half"><a href="" title="" class="image img-cover"><img src="resources/img/upload/banner-15.jpg" alt="" /></a></div>
					<div class="promo-banner hover-effect-1 mt-half"><a href="" title="" class="image img-cover"><img src="resources/img/upload/banner-16.jpg" alt="" /></a></div>
					<div class="promo-banner hover-effect-1 mt-half"><a href="" title="" class="image img-cover"><img src="resources/img/upload/banner-17.jpg" alt="" /></a></div>
				</div>
			</div>
		</section>
		<?php } ?>
		
		<section class="homepage-group">
			<div class="uk-container uk-container-center">
				<div class="uk-grid uk-grid-medium">
					<div class="uk-width-medium-1-2 uk-width-large-1-4">
						<div class="homepage-saleoff page-panel">
							<header class="panel-head"><h2 class="heading-1"><a href="" title="">Khuyến mãi </a></h2></header>
							<section class="panel-body">
								<!-- Set up your HTML -->
								<div class="owl-carousel owl-theme">
									<?php for($i = 0; $i < count($product); $i++){ ?>
									<div class="product-block">
										<div class="product uk-clearfix">
											<div class="thumb">
												<div class="percent">-60%</div>
												<a href="" title="" class="image img-scaledown img-shine"><img  src="resources/img/upload/promo-<?php echo $i+1; ?>.jpg" alt="" /></a>
											</div>
											<div class="info">
												<h3 class="title"><a href="" title=""><?php echo $product[$i]; ?></a></h3>
												<div class="product-ratings">
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
												<div class="product-price">
													<div class="old-price">3.500.000<sup>đ</sup></div>
													<div class="new-price">2.800.000<sup>đ</sup></div>
												</div>
											</div>
										</div>
									</div>
									<?php } ?>
								</div>
							</section>
						</div>
					</div>
					<div class="uk-width-medium-1-2 uk-width-large-1-4">
						<div class="homepage-banner">
							<div class="promo-banner hover-effect-1 mt-half"><a href="" title="" class="image img-cover"><img src="resources/img/upload/banner-5.jpg" alt="" /></a></div>
						</div>
					</div>
					<div class="uk-width-medium-1-1 uk-width-large-1-2">
						<section class="homepage-view page-panel">
							<header class="panel-head"><h2 class="heading-1"><a href="" title="">Xem nhiều nhất</a></h2></header>
							<section class="panel-body">
								<div class="owl-carousel owl-theme">
									<div class="product-block">
										<?php for($j = 0; $j < count($product_2); $j++){ ?>
										<div class="product-2 uk-clearfix">
											<div class="thumb">
												<a href="" title="" class="image img-scaledown img-shine"><img  src="resources/img/upload/product-<?php echo $j+1; ?>.jpg" alt="" /></a>
											</div>
											<div class="info">
												<div class="product-ratings">
													<div class="rating-box">
														<ul class="uk-list uk-clearfix uk-flex uk-flex-middle rating d-flex">
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star-o"></i></li>
														</ul>
													</div>
												</div>
												<h3 class="title"><a href="" title=""><?php echo $product_2[$i]; ?></a></h3>
												<div class="product-price uk-flex ">
													<div class="old-price">3.500.000<sup>đ</sup></div>
													<div class="new-price">2.800.000<sup>đ</sup></div>
												</div>
											</div>
										</div>
										<?php if(($j + 1) % 3 == 0 && ($j + 1) < count($product_2)){ echo '</div><div class="product-block">'; } ?>
										<?php } ?>
									</div>
									
								</div>
							</section>
						</section>
					</div>
				</div>
			</div>
		</section>
		
	</div><!-- #homepage -->
	<?php require_once 'common/footer.php'; ?>
	<?php require_once 'common/offcanvas.php'; ?>
	<?php require_once 'common/script.php'; ?>
</body>
</html>