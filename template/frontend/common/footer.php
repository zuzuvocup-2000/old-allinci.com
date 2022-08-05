<section class="newsletter-section vpadding bgc-offset">
	<div class="uk-container uk-container-center">
		<div class="uk-grid uk-grid-medium">
			<div class="uk-width-large-3-5">
				<div class="newsletter-title d-lg-flex justify-content-lg-start uk-flex uk-flex-middle">
					<h6>Đăng ký nhận bản tin</h6>
					<h3>Theo dõi bản tin của chúng tôi để là người đầu tiên biết về tất cả các chương trình khuyến mãi, giảm giá và các xu hướng mới nhất.</h3>
				</div>
			</div>
			<div class="uk-width-large-2-5">
				<div class="newsletter-form-wrapper">
					<form action="index.html" method="post">
						<input type="email" name="email" placeholder="Nhập địa chỉ email..." value="" required=""> 
						<input type="submit" class="default-btn" name="contact" value="Subscribe">
					</form>
				</div>
			</div>
		</div> <!-- end of row -->
	</div> <!-- end of container -->
</section>

<footer class="footer">
	<section class="upper">
		<div class="uk-container uk-container-center">
			<div class="uk-grid uk-grid-medium">
				<div class="uk-width-large-1-4">
					<aside class="widget-container">
						<h4 class="widgettitle">Liên Hệ</h4>
						<div class="widget-content">
							<p>We are a team of designers and developers that create high quality Magento, Prestashop, Opencart.</p>
							<div class="footer-contact">
								<p class="footer-address">The Barn, Ullenhall, Henley in Arden B578 5CC, England</p>
								<p class="footer-phone"><a href="#">+1 123 456 7890</a></p>
								<p class="footer-email"><a href="#">support@example.com</a></p>
							</div>
						</div> <!-- end of widget-content -->
					</aside>
				</div>
				<?php $post = array('Aypi non habent claritatem insitam','Bypi non habent claritatem insitam','Cabent claritatem insitam','Cypi non habent claritatem insitam'); ?>
				<div class="uk-width-large-1-4">
					<aside class="widget-container">
						<h4 class="widgettitle">Tin mới</h4>
						<div class="widget-content">
							<!-- Set up your HTML -->
							<div class="owl-carousel owl-theme">
								
								<div class="post-block">
									<?php for($i = 0; $i < count($post); $i++){ ?>
									<div class="footer-post uk-clearfix">
										<div class="thumb">
											<a href="" title="" class="image img-scaledown img-shine"><img  src="resources/img/upload/blog-thumb-<?php echo $i+1; ?>.jpg" alt="" /></a>
										</div>
										<div class="info">
											<h3 class="title"><a href="" title=""><?php echo $post[$i]; ?></a></h3>
										</div>
									</div>
									<?php if(($i + 1) % 2 == 0 && ($i + 1) < count($post)){ echo '</div><div class="post-block">'; } ?>
									<?php } ?>
								</div>
								
							</div>
						</div> <!-- end of widget-content -->
					</aside>
				</div>
				<div class="uk-width-large-1-6">
					<aside class="widget-container">
						<h4 class="widgettitle">Thông Tin</h4>
						<div class="widget-content">
							<div class="widgetized-menu">
								<ul class="uk-list uk-clearfix list-unstyled">
									<li><a href="#">About Us</a></li>
									<li><a href="#">Delivery Information</a></li>
									<li><a href="#">Privacy Policy</a></li>
									<li><a href="#">Terms &amp; Conditions</a></li>
									<li><a href="#">Brands</a></li>
									<li><a href="#">Gift Certificates</a></li>
								</ul>
							</div>
						</div> <!-- end of widget-content -->
					</aside>
				</div>
				<div class="uk-width-large-1-3">
					<aside class="widget-container">
						<h4 class="widgettitle">Tags</h4>
						<div class="widget-content">
							<div class="tags-widget">
								<ul class="uk-list uk-clearfix">
									<li><a href="#">headphones</a></li>
									<li><a href="#">mobile</a></li>
									<li><a href="#">gamepad</a></li>
									<li><a href="#">cameras</a></li>
									<li><a href="#">drone</a></li>
									<li><a href="#">tvs</a></li>
									<li><a href="#">smartwatch</a></li>
								</ul>
							</div>
						</div> <!-- end of widget-content -->
					</aside>
				</div>
			</div>
			
			<div class="uk-container uk-container-center">
				<section class="lower">
					<h2>Online Shopping at Ororus.</h2>
					<p>Our extensive and affordable range features the very latest electronics and gadgets including smart phones, tablets, smart watches, action cams, tv boxes, televisions, drones, 3d printers, car dvr, along with the latest cool toys like scooters, gaming accessories, doll houses, pretend play and high quality lifestyle products comprising vacuum cleaners, air purifier, kitchen tools, ceiling lights, flashlight, oil painting, etc.</p>
				</section>
			</div>
		</div>
	</section>
	<div class="copyright">
		<div class="uk-container uk-container-center">
			<p class="copyright-text">Copyright © 2019 <a href="#" rel="nofollow">HT Việt Nam</a>. All Right Reserved.</p>
		</div>
	</div>
</footer>