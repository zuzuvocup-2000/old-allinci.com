<header class="pc-header uk-visible-large"><!-- HEADER -->
	<section class="top-bar">
		<div class="uk-container uk-container-center">
			<div class="uk-flex uk-flex-middle uk-flex-space-between">
				<?php $sitelink = navigation(array('keyword' => 'sidebar')); ?>
				<?php if(isset($sitelink) && is_array($sitelink) && count($sitelink)){ ?>
				<div class="top-link">
					<ul class="uk-list uk-flex uk-flex-middle">
						<?php foreach($sitelink as $key => $val){ ?>
						<li><a href="<?php echo $val['link']; ?>" title="<?php echo $val['title']; ?>"><?php echo $val['title']; ?></a></li>
						<?php }?>
					</ul>
				</div><!-- .top-link -->
				<?php } ?>
				<div class="uk-clearfix header-toolbox">
					<?php $customer = $this->config->item('fcCustomer');  ?>
					<?php if(!isset($customer) || !is_array($customer) || count($customer) == 0){ ?>
					<div class="uk-button-dropdown pc-user" data-uk-dropdown="{mode:'click', pos : 'bottom-center'}">
				        <div class="uk-dropdown box">
				        	<div class="uk-flex uk-flex-middle uk-flex-space-between box-head">
				        		<span class="label">Đăng nhập</span>
				        		<a href="#modal-register" title="" data-uk-modal="{target:'#modal-register'}">Đăng ký</a>
				        	</div>
				        	<div class="box-body">
							<div class="login-error uk-alert"></div>
				        		<form action="" method="post" class="uk-form form login_form">
				        			<div class="form-row">
				        				<input type="text" name="" class="uk-width-1-1 input-text input_email" placeholder="Mời bạn nhập Email" />
				        			</div>
				        			<div class="form-row">
				        				<input type="password" name="" class="uk-width-1-1 input-text input_password" placeholder="Mật khẩu" />
				        			</div>
				        			<div class="form-row forgotpass">
				        				<a href="" title="">Quên mật khẩu?</a>
				        			</div>
				        			<div class="action">
				        				<button type="submit" name="" class="uk-width-1-1 btn-submit">Đăng nhập</button>
				        			</div>
				        		</form>
				        	</div>
						<script type="text/javascript">
							$(document).ready(function(){
								$('.login-error').hide();
								$('.login_form').on('submit',function(){
									var _this = $(this);
									$('.btn-submit').val('Loading....');
									var email = _this.find('.input_email').val();
									var password = _this.find('.input_password').val();
									var formURL = 'customers/ajax/auth/login';
									$.post(formURL, {
										email: email,password:password},
										function(data){
											$('#submit').val('Đăng nhập');
											$('.login-error').show();
											var json = JSON.parse(data);
											if(json.flag == false){
												$('.login-error').addClass('uk-alert-danger');
												$('.login-error').removeClass('uk-alert-success');
												$('.login-error').html(json.message);
											}else{
												$('.login-error').addClass('uk-alert-success');
												$('.login-error').removeClass('uk-alert-danger');
												$('.login-error').html(json.message);
												window.location.href='<?php echo base_url(); ?>';
											}

										});

									return false;
								});
							});
						</script>
				        	<div class="box-foot">
				        		<div class="title"><span>Bạn có thể đăng nhập qua</span></div>
				        		<div class="uk-flex uk-flex-middle uk-flex-space-between social-login">
				        			<a class="facebook" href="" title=""><span>Đăng nhập qua Facebook</span></a>
				        			<a class="google" href="" title=""><span>Đăng nhập qua Google</span></a>
				        		</div>
				        	</div>
				        </div>
				    </div>
					<?php }else{ ?>
					<div class="uk-button-dropdown pc-user" >
				        <a class="uk-button btn open-user" href="<?php echo site_url('thong-tin-tai-khoan'); ?>" title="Thông tin tài khoản"><span>Xin chào: <?php echo $customer['email']; ?></span></a>
				    </div>
					<?php } ?>
				</div>
			</div>
		</div><!-- .uk-container -->
	</section><!-- .top-bar -->
	<nav class="uk-navbar">
		<div class="uk-container uk-container-center">
			<div class="uk-flex uk-flex-middle uk-flex-space-between">
				<?php echo logo(); ?>
				<?php $main_nav = navigation(array('keyword' => 'main')); ?>
				<?php $i = 0; if(isset($main_nav) && is_array($main_nav) && count($main_nav)){ ?>
				<div class="uk-navbar-nav main-menu">
					<ul class="uk-list uk-flex uk-flex-middle list-menu">
						<?php foreach($main_nav as $key => $val){ ?>
						<li>
							<?php if($i == 2){ ?>
							<span id="new">new</span>
							<?php }else if($i == 3){ ?>
							<span id="new">new</span>
							<?php } ?>
							<a href="<?php echo $val['link']; ?>" title="<?php echo $val['title']; ?>"><?php echo $val['title']; ?></a>
							<?php if(isset($val['children']) && is_array($val['children']) && count($val['children'])){ ?>
							<div class="drop-menu">
								<ul class="uk-list sub-menu">
									<?php foreach($val['children'] as $keyItems => $valItems){ ?>
									<li>
										<a href="<?php echo $valItems['link']; ?>" title="<?php echo $valItems['title']; ?>"><?php echo $valItems['title']; ?></a>
									</li>
									<?php } ?>
								</ul>
							</div>
							<?php } ?>
						</li>
						<?php $i++; } ?>

					</ul>
				</div><!-- .main-menu -->
				<?php } ?>
			</div>

		</div><!-- .uk-container -->
	</nav><!-- .uk-navbar -->
	<section class="top-search">
		<div class="uk-container uk-container-center">
			<div class="uk-flex uk-flex-middle uk-flex-space-between">
				<div></div>
				<div class="search">
					<form class="uk-form uk-grid uk-grid-collapse" method="get" action="<?php echo site_url('tim-kiem'); ?>">
						<input type="text" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" placeholder="">
						<button type="submit" value="" style="color:#fff;" name="submit" class="uk-button">Tìm kiếm</button>
					</form>
				</div>
				<div class="uk-flex uk-flex-middle hotline">
					<span id="icon-call">
						<i class="fa fa-2x fa-phone" aria-hidden="true"></i>
					</span>
					<div class="phone">
						Hotline: <a href="" title=""><?php echo $this->general['contact_hotline']; ?></a>
					</div>
				</div>
			</div>
		</div>
	</section>
</header><!-- .header -->
<header class="mobile-header uk-hidden-large">
	<section class="upper">
		<a class="moblie-menu-btn skin-1" href="#offcanvas" class="offcanvas" data-uk-offcanvas="{target:'#offcanvas'}">
			<span>Menu</span>
		</a>
		<div class="logo"><a href="" title="Logo"><img src="<?php echo $this->general['homepage_logo']; ?>" alt="Logo" /></a></div>
		<div class="mobile-hotline">
			<a class="value" href="tel:<?php echo $this->general['contact_hotline']; ?>" title="Tư vấn bán hàng"><?php echo $this->general['contact_hotline']; ?></a>
		</div>
	</section><!-- .upper -->
	<section class="lower">
		<div class="mobile-search">
			<form action="<?php echo site_url('tim-kiem'); ?>" method="" class="uk-form form">
				<input type="text" name="keyword" class="uk-width-1-1 input-text" placeholder="Bạn muốn tìm gì hôm nay?" />
				<button type="submit" name="" value="" class="btn-submit">Tìm kiếm</button>
			</form>
		</div>
	</section>
</header><!-- .mobile-header -->


<div id="modal-register" class="uk-modal">
    <div class="uk-modal-dialog">
        <div class="panel">
        	<a class="uk-modal-close uk-close"></a>
        	<div class="panel-head">
        		<h2 class="heading"><span>Tạo tài khoản mới</span></h2>
        	</div>
        	<div class="panel-body">
			<div class="reg-error uk-alert"></div>
        		<form action="" method="post" class="form" id="RegForm">
        			<div class="sex">
        				<label for="">
        					<input type="radio" name="type" value="0" class="type" />
        					<span>Giá sỉ</span>
        				</label>
        				<label for="">
        					<input type="radio" name="type" value="1" class="type" />
        					<span>Cộng tác viên</span>
        				</label>
        			</div>
        			<div class="form-row">
        				<input type="text" name="fullname" id="reg_fullname" class="uk-width-1-1 input-text" placeholder="Tên đầy đủ" />
        			</div>
        			<div class="form-row">
        				<input type="text" name="email" id="reg_email" class="uk-width-1-1 input-text" placeholder="Email" />
        			</div>
					<div class="form-row">
        				<input type="text" name="phone" id="reg_phone" class="uk-width-1-1 input-text" placeholder="Phone" />
        			</div>
        			<div class="form-row">
        				<input type="password" name="password" id="reg_password" class="uk-width-1-1 input-text" placeholder="Mật khẩu" />
        			</div>
        			<div class="form-row">
        				<input type="password" name="re_password" id="reg_re_password" class="uk-width-1-1 input-text" placeholder="Xác nhận mật khẩu" />
        			</div>
					<div class="form-row">
        				<input type="text" name="address" id="address" class="uk-width-1-1 input-text" placeholder="Thông tin liên hệ" />
        			</div>
        			<div class="action">
        				<button type="submit" class="btn-submit"><span>Đăng ký</span></button>
        			</div>
        		</form>
        	</div>
        </div>
    </div>
</div>



<script type="text/javascript">
	$(document).ready(function(){
		$('.reg-error').hide();
		$('#RegForm').on('submit',function(){
			var email = $('#reg_email').val();
			var phone = $('#reg_phone').val();
			var password = $('#reg_password').val();
			var re_password = $('#reg_re_password').val();
			var fullname = $('#reg_fullname').val();
			var address = $('#address').val();
			var type = $('.type:checked').val();
			$('#submit_reg').val('Sending ....');
			var formURL = 'customers/ajax/auth/register';
			$.post(formURL, {
				email: email,password:password, re_password: re_password, fullname:fullname,phone:phone,type:type,address:address},
				function(data){
					var json = JSON.parse(data);
					$('#submit_reg').val('Register');
					$('.reg-error').show();
					if(json.flag == false){
						$('.reg-error').addClass('uk-alert-danger');
						$('.reg-error').removeClass('uk-alert-success');
						$('.reg-error').html(json.message);
					}else{
						$('.reg-error').addClass('uk-alert-success');
						$('.reg-error').removeClass('uk-alert-danger');
						$('.reg-error').html(json.message);
						setTimeout(function(){ window.location.href='<?php echo base_url(); ?>'; }, 2000);
					}
				});
			return false;
		});
	});
</script>


<div id="social-float">
	<ul class="uk-list uk-margin-remove">
		<li><a href="<?php echo $this->general['social_facebook']; ?>" title="facebook" class="fc-transition facebook"><i class="fa fa-facebook"></i></a></li>
		<li><a href="<?php echo $this->general['social_twitter']; ?>" title="twitter" class="fc-transition twitter"><i class="fa fa-twitter"></i></a></li>
		<li><a href="<?php echo $this->general['social_linkedin']; ?>" title="linkedin" class="fc-transition linkedin"><i class="fa fa-linkedin"></i></a></li>
		<li><a href="<?php echo $this->general['social_google']; ?>" title="google" class="fc-transition google"><i class="fa fa-google"></i></a></li>
		<li><a href="<?php echo $this->general['social_pinterest']; ?>" title="pinterest" class="fc-transition pinterest"><i class="fa fa-pinterest"></i></a></li>
	</ul>
</div>
