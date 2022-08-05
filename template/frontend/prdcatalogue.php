<!DOCTYPE html>
<html>
<head>
<?php require_once 'common/head.php'; ?>
</head>
<body>
	<?php require_once 'common/header.php'; ?>
	
	<div id="prdcatalogue" class="page-body">
		<div class="main-breadcrumb">
			<div class="uk-container uk-container-center">
				<ul class="uk-breadcrumb uk-margin-remove">
					<li><a href="" title="Trang chủ">Trang chủ</a></li>
					<li class="uk-active"><a href="" title="">Sản phẩm</a></li>
				</ul>
			</div>
		</div>
		
		<section class="prdcatalogue shop-products-wrapper">
			<div class="uk-container uk-container-center">
				<div class="uk-grid uk-grid-medium">
					<div class="uk-width-large-1-4 uk-visible-large">
						<?php require_once 'common/aside.php'; ?>
					</div>
					<div class="uk-width-large-3-4">
						<section class="main-content">
							<header class="panel-head">
								<h1 class="main-title"><span>Cameras</span></h1>
								<div class="description">
									Our extensive and affordable range features the very latest electronics and gadgets including smart phones, tablets, smart watches, action cams, tv boxes, televisions, drones, 3d printers, car dvr, along with the latest cool toys like scooters, gaming accessories, doll houses, pretend play and high quality lifestyle products comprising vacuum cleaners, air purifier, kitchen tools, ceiling lights, flashlight, oil painting, etc.
								</div>
								<div class="shop-toolbar">
									<div class="toolbar-inner">
										<div class="toolbar-amount">
											<span>Hiển thị từ 10 đến 18 trên 27</span>
										</div>
									</div>
									<div class="product-select-box">
										<div class="product-show">
											<p>Show:</p>
											<select class="nice-select" name="showing">
												<option value="1">8</option>
												<option value="2">12</option>
												<option value="3">16</option>
												<option value="4">20</option>
												<option value="5">24</option>
											</select>
										</div>
										<div class="product-sort">
											<p>Sort By:</p>
											<select class="nice-select" name="sortby">
												<option value="trending">Default</option>
												<option value="sales">Name (A - Z)</option>
												<option value="sales">Name (Z - A)</option>
												<option value="rating">Price (Low > High)</option>
												<option value="date">Rating (Lowest)</option>
												<option value="price-asc">Model (A - Z)</option>
												<option value="price-asc">Model (Z - A)</option>
											</select>
										</div>
									</div>
								</div>
							</header>
							<?php $product_2 = array('Máy ảnh mẫu 2','Máy ghi hình mẫu 2','Cameras mẫu 2','Tai nghe mẫu 2','Bàn Phím mẫu số 1','Tài nghe mẫu số 12','Tài nghe mẫu số 12','Tài nghe mẫu số 12','Tài nghe mẫu số 12'); ?>
							<section class="panel-body">
								<ul class="uk-list uk-clearfix uk-grid uk-grid-width-1-1 uk-grid-width-medium-1-2 uk-grid-width-large-1-3 list-product">
									<?php for($i = 0; $i < count($product_2); $i++){ ?>
									<li>
										<div class="product uk-clearfix">
											<div class="thumb">
												<div class="percent">-60%</div>
												<a href="" title="" class="image img-scaledown img-shine"><img  src="resources/img/upload/prd-<?php echo $i+1; ?>.jpg" alt="" /></a>
												<div class="countdown-wrapper">
													<div class="countdown-timer instance-0<?php echo $j; ?><?php echo $i; ?>" data-countdown-year="2019" data-countdown-month="<?php echo 7+$i; ?>" data-countdown-day="10"></div>
												</div>
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
									</li>
									<?php } ?>
								</ul>
							</section>
							<section class="panel-foot">
								<div class="pagination-area">
									<div class="uk-flex uk-flex-middle uk-flex-space-between">
										<div class="col-1">
											<div class="pagination">
												<ul class="uk-pagination uk-pagination-left">
													<li class="uk-active"><span>1</span></li>
													<li><a href="">2</a></li>
													<li><a href="">3</a></li>
													<li><a href="">4</a></li>
												</ul>
											</div>
										</div>
										<div class="col2">
											<div class="page-amount d-flex">
												<p>Hiển thị từ 10 đến 18 trên 27 bản ghi</p>
											</div>
										</div>
									</div>
								</div>
							</section>
						</section>
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