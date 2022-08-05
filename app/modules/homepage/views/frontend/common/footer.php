<?php /*
<section class="homepage-student ht2109_ft-mailto ft-mailto"
		style="background: url('<?php echo $this->general['homepage_bg_mail'] ?>') no-repeat center; 
			background-size: cover;
		"
	>

    <div class="uk-container uk-container-center">
        <div class="ft-mailto_inner">
        	<div class="ht2109_ft-mailto_title">Hotline: <?php echo $this->general['contact_hotline'] ?></div>
	        <div class="ht2109_ft-mailto_subtitle">Để lại email nhận ngay báo giá và chương trình ưu đãi mới nhất</div>
	        
	        <form action="" method="" class="form uk-form" id="form-register">
	            <div class="error hidden">
	                <div class="alert alert-danger"></div>
	            </div><!-- /.error -->
	            <div class="success hidden">
	                <div class="alert alert-success"></div>
	            </div><!-- /.success -->
	            <div class="form-row">
	                <input type="text" name="keyword" value="" required="" placeholder="Entered Email ..." class="input-text email">
	                <div class="submit">
	                    <div class="loading">
	                        <div class="bg-loader"></div>
	                        <button class="btn-submit" type="submit" value="" name="">Gửi <img src="template/frontend/resources/img/upload/btn-mailto.png" alt=""></button>
	                    </div>
	                </div>
	            </div>
	        </form>
        </div>
    </div>
</section>

<script>
	var time = 0;

	$(document).on('submit', '#form-register', function(){
		let _this = $(this);
		let loader = _this.find('.bg-loader');
		loader.show();

		let postData = _this.serializeArray();
		let email =  _this.find('.email').val();

		let ajaxUrl = 'contact/ajax/contact/contact_register';


		clearTimeout(time);

		// console.log(1);
		// return false;


		//gửi ajax
		time = setTimeout(function(){
			$.ajax({
				method: "POST",
				url: ajaxUrl,
				data: {
					data: postData,
					email: email, 
				},
				dataType: "json",
				cache: false,
				success: function(json){
					loader.hide();
					if(json.error.flag == 1){
						_this.find('.error').removeClass('hidden');
						_this.find('.error .alert').html(json.error.message);
					}else{
						_this.find('.error').addClass('hidden');
						_this.find('.input-reset').val('');
						location.reload();
					}
				}
			});
		}, 300);

		return false;
	});
</script>
*/ ?>
<footer class="pc-footer" style="" id="contact">
	<div class="uk-container uk-container-center">
		<div class="uk-grid uk-grid-medium uk-flex uk-flex-middle ft_content" data-uk-grid-match="{target: '.ht2109_grid_match'}">
			<div class="uk-width-large-1-3 uk-visible-large">
				<div class="mb10"><?php echo $this->general['homepage_ft']?></div>

		        <ul class="uk-list uk-clearfix uk-flex uk-flex-middle list-social order-1">
					<li>
						<div class="social fb"><a href="<?php echo $this->general['social_facebook']?>" title=""><i class="fa fa-facebook"></i></a></div>
					</li>
					<li>
						<div class="social twitter"><a href="<?php echo $this->general['social_google']?>" title="">
							<img src="template/frontend/resources/img/icon/google.jpg" alt="" style="width: 35px; height: 35px; border-radius: 6px">
						</a></div>
					</li>
					<li>
						<div class="social yt"><a href="<?php echo $this->general['social_youtube']?>" title=""><i class="fa fa-youtube"></i></a></div>
					</li>
					<?php if(isset($this->general['social_instagram'])){?>
					    <li>
					        <div class="social insta"><a target="_blank" href="<?php echo $this->general['social_instagram']?>" title="instagram"><i class="fa fa-instagram"></i></a></div>
					    </li>
				    <?php }?>
				</ul> <!-- list-social-->

				<section class="ft_open mt30">
				    <div class="uk-container uk-container-center">
				        <div class="panel-head">
				            <h2 class="ft_heading-1"><span>Opening hours</span></h2>
				        </div>
				        <div class="panel-body">
							<?php echo $this->general['homepage_time'] ?>
				        </div>  
				    </div>
				</section>
		    </div>
			<div class="uk-width-large-1-3">
		        <div class="uk-flex uk-flex-center">
		        	<div class="ft_logo">
		        		<a href="" title=""><img src="<?php echo $this->general['homepage_logo_ft'] ?>" alt=""></a>
		        	</div>
		        </div>
		    </div>
			<div class="uk-width-large-1-3">

		        <div class="ft-panel">
					<!-- <h3 class="heading-4">Store</h3> -->
					<ul class="uk-list uk-clearfix ft-list list-contact">
						<li class="website">
							<i class="fa fa-location-arrow" aria-hidden="true"></i>
							<a class="ml5" href="<?php echo $this->general['contact_map_link']; ?>" title="">
								<!-- <b>Address</b>:  -->
								<span><?php echo $this->general['contact_address']; ?></span></a>
						</li>
						<li class="phone">
							<i class="fa fa-phone" aria-hidden="true"></i>
							<a class="ml5" href="tel:<?php echo $this->general['contact_phone']; ?>" title="">
								<!-- <span><b>Phone</b>:</span> -->
								<span><?php echo $this->general['contact_phone']; ?></span>
							</a>
						</li>
						<?php /*
						<li class="hotline">
							<i class="fa fa-phone" aria-hidden="true"></i>
							<a class="ml5" href="tel:<?php echo $this->general['contact_hotline']; ?>" title=""><span><b>Hotline</b>:</span><span><?php echo $this->general['contact_hotline']; ?></span></a>
						</li>
						<li class="email">
							<i class="fa fa-envelope-o" aria-hidden="true"></i>
							<a class="ml5" href="https://mail.google.com/mail/?view=cm&fs=1&to=<?php echo $this->general['contact_email']; ?>" target="_blank" title=""><span><b>Email</b>:</span><span><?php echo $this->general['contact_email']; ?></span></a>
						</li>
						*/ ?>
						<li class="website">
							<i class="fa fa-globe" aria-hidden="true"></i>
							<a class="ml5" href="<?php echo $this->general['contact_website_link']; ?>" title="<?php echo $this->general['contact_website']; ?>">
								<!-- <span><b>Website</b>:</span> -->
								<span><?php echo $this->general['contact_website']; ?></span></a>
						</li>
					</ul>
				</div>
		    </div>
		    <div class="uk-width-large-1-3 uk-hidden-large">
				<div class="mb10"><?php echo $this->general['homepage_ft']?></div>
		        <ul class="uk-list uk-clearfix uk-flex uk-flex-middle list-social order-1 mb20">
					<li>
						<div class="social fb"><a href="<?php echo $this->general['social_facebook']?>" title=""><i class="fa fa-facebook"></i></a></div>
					</li>
					<li>
						<div class="social twitter"><a href="<?php echo $this->general['social_google']?>" title="">
							<img src="template/frontend/resources/img/icon/google.jpg" alt="" style="width: 35px; height: 35px; border-radius: 6px">
						</a></div>
					</li>
					<li>
						<div class="social yt"><a href="<?php echo $this->general['social_youtube']?>" title=""><i class="fa fa-youtube"></i></a></div>
					</li>
					<?php if(isset($this->general['social_instagram'])){?>
					    <li>
					        <div class="social insta"><a target="_blank" href="<?php echo $this->general['social_instagram']?>" title="instagram"><i class="fa fa-instagram"></i></a></div>
					    </li>
				    <?php }?>
				</ul> <!-- list-social-->
				<section class="ft_open">
				    <div class="uk-container uk-container-center">
				        <div class="panel-head">
				            <h2 class="ft_heading-1"><span>Opening hours</span></h2>
				        </div>
				        <div class="panel-body">
							<?php echo $this->general['homepage_time'] ?>
				        </div>  
				    </div>
				</section>
		    </div>
		</div>
	</div>
</footer>
<div class="copyright">
	<div class="uk-container uk-container-center">
		<a href="<?php echo $this->general['homepage_copyright_link']?>" title=""><?php echo $this->general['homepage_copyright']?></a>
		
	</div>
</div>


<script>
	$(document).ready(function(){
		$(document).on('click', '.dt_popup_image', function(event) {
			event.preventDefault();
			/* Act on the event */

			let src = $(this).find('img').attr('src');
			let caption = $(this).attr('data-caption');
			let url = '';
			url = $(this).attr('data-url');
			$('#popup_image').find('img').attr('src', src);
			$('#popup_image').find('.dt_caption_img').html(caption);
			if (url.length > 0) {
				$('#popup_image').find('.dt_btn_viewmore_2').attr('href', url).show();
			}else{
				$('#popup_image').find('.dt_btn_viewmore_2').hide();
			}
		});
	});
</script>

<div id="popup_image" class="uk-modal">
    <div class="uk-modal-dialog uk-modal-dialog-lightbox uk-clearfix">
        <a href="" class="uk-modal-close uk-close uk-close-alt"></a>
        <span class="img-cover">
    		<img src="" alt="">
        </span>
        <div class="dt_caption_img uk-text-center"></div>
        <div class="uk-flex uk-flex-center mb10">
        	<a href="" class="dt_btn_viewmore_2" style="display: none">Viewmore</a>
        </div>
    </div>
</div>