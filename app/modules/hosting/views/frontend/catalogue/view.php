<section id="main" class="pc-slide">
	<section class="mainslide">
		<div class="uk-container uk-container-center">
			<div class="slide-item">
				<div class="uk-grid uk-grid-small uk-grid-width-medium-1-2">
					<div class="slide-content">
						<h1 class="main-slide-title  wow fadeInLeft">Dịch vụ hosting chất lượng cao chỉ với </h1>
						<div class="slide-price  wow fadeInLeft" data-wow-delay=".20s">55.000đ / tháng</div>
						<ul class="check-list uk-clearfix uk-list uk-grid uk-grid-medium uk-grid-width-small-1-1 uk-grid-width-medium-1-2">
							<li class="wow fadeInLeft" data-wow-delay=".25s"><span>Tốc độ truy xuất vượt trội</span></li>
							<li class="wow fadeInLeft" data-wow-delay=".30s"><span>An toàn, bảo mật dữ liệu</span></li>
							<li class="wow fadeInLeft" data-wow-delay=".40s"><span>Khởi tạo nhanh, dễ dàng nâng cấp</span></li>
							<li class="wow fadeInLeft" data-wow-delay=".50s"><span>Backup dữ liệu định kỳ</span></li>
						</ul>
						<p class="wow bounceInDown" data-wow-delay=".70s">Đăng ký ngay hosting cho thương hiệu của bạn chỉ với 1 click:</p>
						<div class="support hosting-register wow bounceInDown" data-wow-delay=".75s">
							<a href="#callModal" data-uk-modal="" class="ring-alo-phone" href="tel: <?php echo $this->general['contact_hotline'] ?>" title="Hotline">
								<div class="animated infinite zoomIn ring-alo-ph-circle"></div>
								<div class="animated infinite pulse ring-alo-ph-circle-fill"></div>
								<div class=" infinite tada ring-alo-ph-img-circle"></div>
								<span>Đăng ký ngay</span>
							</a>
						</div>
					</div>
					<div class="slide-image  wow fadeInRight">
						<img src="template/frontend/resources/img/upload/hero-illustration-2.png" alt="" class="slide-image-item" />
					</div>
				</div>
			</div>
		</div>
	</section>
</section>
<div id="hosting-page" class="page-body">
	<section class="mainContent">
		<div class="uk-container uk-container-center">
			<section class="hostingContainer"><!-- Bảng giá hosting -->
				<header class="panel-head wow fadeInUp" data-wow-delay="1s">
					<h1 class="heading-1"><span>Bảng giá hosting</span></h1>
					<div class="description uk-text-center mb20"><?php echo $detailCatalogue['description']; ?></div>
				</header>
				<?php if(isset($hostingList) && is_array($hostingList) && count($hostingList)){ ?>
				<section class="panel-body">
					<div class="uk-grid lib-grid-20 uk-grid-width-small-1-2 uk-grid-width-medium-1-3 listHosting" data-uk-grid-match="{target: '.hosting .title'}">
					<?php foreach($hostingList as $key => $val){ ?>
						<div class="hostingItem fadeInDown wow" data-wow-delay=".<?php echo $key+1 ?>5s">
							<div class="hosting">
								<div class="title">
									<div class="name"><?php  echo $val['title']; ?></div>
									<div class="price"><span><?php echo str_replace(',','.',number_format($val['price'])); ?>đ</span> /tháng</div>
								</div>
								<div class="content">	
									<p>Dung lượng lưu trữ: <span style="color: #0a5495;font-weight: bold;font-size: 15px;text-transform: uppercase;"><?php echo $val['capacity']; ?></span></p>
									<p>Băng thông/ Tháng : <?php echo $val['bandwidth']; ?></p>
									<p>FTP Account : <?php echo $val['FPT_account']; ?></p>
									<p>MySQL : <?php echo $val['mysql']; ?></p>
									<p>Domains : <?php echo $val['domain']; ?></p>
									<p>Subdomain : <?php echo $val['sub_domain']; ?></p>
									<p>Addon domains : <?php echo $val['addon_domain']; ?></p>
									<p>Alias/Park Domain : <?php echo $val['park_domain']; ?></p>
									<p>Hợp đồng tối thiểu : <?php echo $val['contract_time']; ?></p>
								</div>
							</div><!-- .hosting -->
						</div>
					<?php } ?>
						
					</div><!-- .listHosting -->
				</section><!-- .panel-body -->
				<?php } ?>
			</section><!-- .hostingContainer -->
		</div>
	</section><!-- .mainContent -->
</div>