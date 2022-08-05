<div id="contact_page">
    <section class="breadcrumb breadcrumb-expand" style="background: url('<?php echo $this->general['homepage_banner'] ?>') no-repeat top; background-size: cover;">
		<div class="uk-container uk-container-center">
            <div class="breadcrumb-content">
                <div class="breadcrumb-maintitle">Liên hệ</div>
            </div>
        </div>
    </section>


	<section class="main-content bg_white ">
		<div class="uk-container uk-container-center">
			<section class="uk-panel contact section-contact">
				<section class="panel-body">
					<div class="uk-grid uk-grid-medium">
						<div class="uk-width-large-1-3 uk-width-xlarge-2-4">
							<div class="contact-infomation">
								<div class="note">Cám ơn quý khách đã ghé thăm website chúng tôi.</div>
								<h2 class="company"><?php echo $this->general['homepage_company']; ?></h2>
								<section class="ft-panel order-2">
									<?php /*
									<header class="panel-head"><h4 class="heading-1 line-1"><span><?php echo $this->general['homepage_company']; ?></span></h4></header>
									*/ ?>
									<section class="panel-body">
										<ul class="uk-list uk-clearfix ft-list list-contact">
											
											<li class="phone">
												<i class="fa fa-phone" aria-hidden="true"></i>
												<a class="ml5" href="tel:<?php echo $this->general['contact_phone']; ?>" title="">
													<!-- <span><b>Điện thoại</b>: </span>-->
													<span>Phone: <?php echo $this->general['contact_phone']; ?></span>
												</a>
											</li>
											<li class="hotline">
												<i class="fa fa-phone" aria-hidden="true"></i>
												<a class="ml5" href="tel:<?php echo $this->general['contact_hotline']; ?>" title="">
													<!-- <span><b>Hotline</b>: </span>-->
													<span>Hotline: <?php echo $this->general['contact_hotline']; ?></span>
												</a>
											</li>
											<li class="email">
												<i class="fa fa-envelope-o" aria-hidden="true"></i>
												<a class="ml5" href="mailto:<?php echo $this->general['contact_email']; ?>" target="_blank" title="">
													<!-- <span><b>Email</b>:</span> -->
													<span><?php echo $this->general['contact_email']; ?></span>
												</a>
											</li>
											<li class="address">
												<i class="fa fa-home" aria-hidden="true"></i> 
												<!-- <b class="ml5">Địa chỉ</b>: -->
												<span class="ml5"><?php echo $this->general['contact_address']; ?></span>
											</li>
											<?php /*
											<li class="website">
												<i class="fa fa-globe" aria-hidden="true"></i>
												<a class="ml5" href="" title="">
													<!-- <span><b>Website</b>:</span> -->
													<span><?php echo $this->general['contact_website']; ?></span>
												</a>
												*/ ?>
											</li>
										</ul>
									</section>
								</section> <!-- .panel -->
							</div><!-- .contact-infomation -->
						</div>
						<div class="uk-width-large-1-3 uk-width-xlarge-2-4">
							<div class="contact-form">
								<div class="label">Mời bạn điền vào mẫu thư và gửi đi, chúng tôi sẽ trả lời bạn trong thời gian sớm nhất.</div>
								<form action="" method="post" class="uk-form form">
								<?php $error = validation_errors(); echo !empty($error)?'<div class="callout callout-danger" style="padding:10px;background:rgb(195, 94, 94);color:#fff;margin-bottom:10px;">'.$error.'</div>':'';?>
									<div class="uk-grid lib-grid-20 uk-grid-width-small-1-2 uk-grid-width-large-1-1">
										<div class="form-row">
											<input type="text" name="fullname" class="uk-width-1-1 input-text" placeholder="Họ &amp; tên *" />
										</div>
										<div class="form-row">
											<input type="text" name="email" class="uk-width-1-1 input-text" placeholder="Email *" />
										</div>
										<div class="form-row">
											<input type="text" name="phone" class="uk-width-1-1 input-text" placeholder="Phone *" />
										</div>
										<div class="form-row">
											<input type="text" name="title" class="uk-width-1-1 input-text" placeholder="Tiêu đề thư *" />
										</div>
									</div><!-- .uk-grid -->
									<div class="form-row">
										<textarea name="message" class="uk-width-1-1 form-textarea" placeholder="Nội dung *"></textarea>
									</div>
									<div class="form-row uk-text-right">
										<input type="submit" name="create" class="btn-submit" value="Gửi đi" />
									</div>
								</form><!-- .form -->
							</div><!-- .contact-form -->
						</div>
					</div><!-- .uk-grid -->
				</section><!-- .panel-body -->
			</section><!-- .contact -->
		</div><!-- .uk-container -->
		<div class="contact-map">
			<?php echo $this->general['contact_map']; ?>
		</div>
	</section><!-- .main-content -->
</div>