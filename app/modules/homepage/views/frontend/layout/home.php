<!DOCTYPE html>
<html lang="vi-VN" prefix="og: http://ogp.me/ns#">
<head>
	<base href="<?php echo base_url();?>" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="robots" content="index,follow" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="author" content="<?php echo (isset($this->general['homepage_company'])) ? $this->general['homepage_company'] : ''; ?>" />
	<meta name="copyright" content="<?php echo (isset($this->general['homepage_company'])) ? $this->general['homepage_company'] : ''; ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
	<meta http-equiv="refresh" content="1800" />
	<!--for Google -->
	<title><?php echo isset($meta_title)?htmlspecialchars($meta_title):'';?></title>
	<meta name="description" charset="UTF-8" content="<?php echo isset($meta_description)?htmlspecialchars($meta_description):'';?>" />
	<?php echo isset($canonical)?'<link rel="canonical" href="'.$canonical.'" />':'';?>
	<meta property="og:locale" content="vi_VN" />
	<!-- for Facebook -->
	<meta property="og:title" content="<?php echo (isset($meta_title) && !empty($meta_title))?htmlspecialchars($meta_title):'';?>" />
	<meta property="og:type" content="<?php echo (isset($og_type) && $og_type != '') ? $og_type : 'article'; ?>" />
	<meta property="og:image" content="<?php echo (isset($meta_image) && !empty($meta_image)) ? $meta_image : base_url($this->general['homepage_logo']); ?>" />
	<?php echo isset($canonical)?'<meta property="og:url" content="'.$canonical.'" />':'';?>
	<meta property="og:description" content="<?php echo (isset($meta_description) && !empty($meta_description))?htmlspecialchars($meta_description):'';?>" />
	<meta property="og:site_name" content="<?php echo (isset($this->general['homepage_company'])) ? $this->general['homepage_company'] : ''; ?>" />
	<meta property="fb:admins" content=""/>
	<meta property="fb:app_id" content="" />
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:title" content="<?php echo isset($meta_title)?htmlspecialchars($meta_title):'';?>" />
	<meta name="twitter:description" content="<?php echo (isset($meta_description) && !empty($meta_description))?htmlspecialchars($meta_description):'';?>" />
	<meta name="twitter:image" content="<?php echo (isset($meta_image) && !empty($meta_image))?$meta_image:base_url($this->general['homepage_logo']);?>" />
	<link rel="icon" href="<?php echo $this->general['homepage_favicon']; ?>"  type="image/png" sizes="30x30">
	<link href="plugin/lightGallery/dist/css/lightgallery.css" rel="stylesheet" />
    <link href="template/acore/css/core.css" rel="stylesheet">
	<?php $this->load->view('homepage/frontend/common/head'); ?>
	<script type="text/javascript">
	// disable right click
	document.addEventListener('contextmenu', event => event.preventDefault());
	// disable ctrl u
	document.onkeydown = function(e) {
        if (e.ctrlKey && 
            (e.keyCode === 67 || 
             e.keyCode === 86 || 
             e.keyCode === 85 || 
             e.keyCode === 117)) {
            return false;
        } else {
            return true;
        }
	};
	$(document).keypress("u",function(e) {
	  if(e.ctrlKey)
	  {
	return false;
	}
	else
	{
	return true;
	}
	});
	</script>
	<script type="text/javascript">
		var BASE_URL = '<?php echo base_url(); ?>';
	</script>
	
    <script>
		<?php echo $this->general['script_head'] ?>
	</script>
</head>
<body>
	<div class="lds-css ng-scope hidden"><div style="width:100%;height:100%" class="lds-eclipse"><div></div></div></div>
	<?php echo $this->general['analytic_google_analytic']; ?>
	<?php echo $this->general['facebook_facebook_pixel']; ?>
	<?php
		$this->load->view('homepage/frontend/common/header');
	?>
	<section id="body">
		<?php $this->load->view(isset($template) ? $template : ''); ?>
	</section><!-- #body -->
	<?php $this->load->view('homepage/frontend/common/footer'); ?>
	<?php $this->load->view('homepage/frontend/core/offcanvas'); ?>
	<?php $this->load->view('homepage/frontend/core/notification'); ?>
	<script src="plugin/lightGallery/dist/js/lightgallery-all.min.js"></script>
	<script src="plugin/jquery.countdown.min.js"></script>
	<script src="plugin/jquery.cookie.js"></script>
	
	<script src="template/frontend/resources/plugin.js" type="text/javascript"></script>
	<script src="template/frontend/resources/extend.js" type="text/javascript"></script>
	<script src="template/acore/js/core.js"  type="text/javascript"></script>

	<script>
		<?php echo $this->general['script_body'] ?>
	</script>

	<div id="fb-root"></div>
	<script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v6.0"></script>
	
	<div class="hotline-fixed">
		<a href="tel:  <?php echo $this->general['contact_hotline'] ?>" title="Hotline">
			<span class="label">Phone: </span>
			<span class="value"> <?php echo $this->general['contact_hotline'] ?></span>

			<div class="call-btn">
				<div class="zoomIn"></div>
				<div class="pulse"></div>
				<div class="tada">
					<span class="icon"></span>
				</div>
			</div>
		</a>
	</div>

<style>
	/* ######################## Mobile ########################  */
	.hotline-fixed {
		position: fixed;
	    left: 30px;
	    bottom: 30px;
	    background: rgba(213, 213, 213, .5);
	    border: 1px solid #d5d5d5;
	    -webkit-border-radius: 3px;
	    -moz-border-radius: 3px;
	    -ms-border-radius: 3px;
	    -o-border-radius: 3px;
	    border-radius: 3px;
	    z-index: 99999;
	}
	.hotline-fixed a {
		position: relative;
	    display: block;
	    padding: 10px 20px 10px 60px;
	    font-size: 18px;
	    line-height: 20px;
	    font-weight: bold;
	    color: #ff0000;
	    -webkit-background-size: 30px;
	    -moz-background-size: 30px;
	    -ms-background-size: 30px;
	    -o-background-size: 30px;
	    background-size: 30px;
	    -webkit-border-radius: 4px;
	    -moz-border-radius: 4px;
	    -ms-border-radius: 4px;
	    -o-border-radius: 4px;
	    border-radius: 4px;
	}
	@media (min-width: 960px) {
		.hotline-fixed {
			left: 50px;
			bottom: 50px;
		}
	}
	@media (max-width: 959px) {
		.hotline-fixed a{
			border-color: transparent;
			background: transparent;
			padding: 0;
		}
		.hotline-fixed a> span{
			display: none;
		}

		.hotline-fixed {
			left: 50px;
			bottom: 115px;
		}
	}


	.call-btn {
	    position: absolute;
	    margin: 0;
	    padding: 0;
	   	left: 0px;
	   	top: 50%;
	   	left: -40px;
	   	-webkit-transform: translate(0, -50%);
	   	-moz-transform: translate(0, -50%);
	   	-ms-transform: translate(0, -50%);
	   	-o-transform: translate(0, -50%);
	   	transform: translate(0, -50%);
	    background: #fff;
	    background-color: transparent;
	    cursor: pointer;
	    font-size: 0;
	    width: 110px;
	    height: 110px;
	    z-index: 1000;
	}

	.call-btn .tada {
	    background: #e4405f;
	    border-radius: 100px;
	    width: 40px;
	    height: 40px;
	    position: absolute;
	    left: 50%;
	    top: 50%;
	    margin-top: -20px;
	    margin-left: -20px;
	    animation-name: tada;
	    animation-duration: 0.5s;
	    animation-iteration-count: infinite;
	    animation-direction: alternate;
	}
	.call-btn:hover .tada {background: #ff0000;}

	.call-btn .tada .icon:before {
	    content: "\f095";
	    font-size: 25px;
	    font-family: FontAwesome;
	    text-decoration: none;
	    color: #fff;
	    margin-left: 10px;
	    position: absolute;
	    top: 50%;
	    margin-top: -10px;
	}


	@keyframes tada {
	    from {
	        transform: rotate(-20deg);
	    }
	    to {
	        transform: rotate(20deg);
	    }
	}

	.call-btn .pulse {
	    width: 60px;
	    height: 60px;
	    background: #e4405f38;
	    border-radius: 100px;
	    position: absolute;
	    top: 50%;
	    left: 50%;
	    margin-top: -30px;
	    margin-left: -30px;
	    animation-name: pulse;
	    animation-duration: 0.5s;
	    animation-iteration-count: infinite;
	    animation-direction: alternate;
	    animation-timing-function: ease-in-out;
	}
	.call-btn:hover .pulse {background: rgba(255, 0, 0, .5);}

	@keyframes pulse {
	    from {
	        width: 55px;
	        height: 55px;
	        margin-top: -27.5px;
	        margin-left: -27.5px;
	    }
	    to {
	        width: 60px;
	        height: 60px;
	        margin-top: -30px;
	        margin-left: -30px;
	    }
	}
	.call-btn .zoomIn {
	    width: 80px;
	    height: 80px;
	    border: 2px solid #e4405f;
	    border-radius: 100px;
	    position: absolute;
	    top: 50%;
	    left: 50%;
	    margin-top: -40px;
	    margin-left: -40px;
	    animation-name: zoomIn;
	    animation-duration: 1s;
	    animation-iteration-count: infinite;
	    animation-timing-function: ease-out;
	}
	.call-btn:hover .zoomIn {border: 2px solid #ff0000;}

	@keyframes zoomIn {
	    from {
	        width: 40px;
	        height: 40px;
	        margin-top: -20px;
	        margin-left: -20px;
	    }
	    to {
	        width: 80px;
	        height: 80px;
	        margin-top: -40px;
	        margin-left: -40px;
	    }
	}

	@media screen and (max-width: 549px) {
	    .call-btn {
	        width: 80px;
	        height: 80px;
	    }
	    /*.call-btn .pulse {left: -30px;}*/
	}
	
</style>


	<a id="backtop" href="" title="Về đầu trang" style="bottom: 90px;"><i class="fa fa-angle-double-up"></i></a>

	<style>
		
		#backtop {
		    position: fixed;
		    right: 60px;
		    clip: rect(auto, auto, auto, auto);
		    display: none;
		    width: 50px;
		    height: 50px;
		    background: #e4405f;
		    font-size: 20px;
		    color: #fff;
		    border-radius: 0;
		    text-align: center;
		    line-height: 50px;
		    z-index: 9999;
		    font-family: 'Roboto-Bold';

		    -webkit-box-shadow: 0px 0px 2px -0.5px #c6c6c6c6;
		    -moz-box-shadow: 0px 0px 2px -0.5px #c6c6c6c6;
		    -ms-box-shadow: 0px 0px 2px -0.5px #c6c6c6c6;
		    -o-box-shadow: 0px 0px 2px -0.5px #c6c6c6c6;
		    box-shadow: 0px 0px 2px -0.5px #c6c6c6c6;

		}

		#backtop.active {
			display: block;
		}

		@media (max-width: 959px) {
		    #backtop {
		        right: 10px;
		    }
		}

	</style>

	<script>
		
		$(window).scroll(function() {
			if($(this).scrollTop() > 50){
				$('#backtop').addClass('active');
				// $('#backtop').stop().animate({ bottom: '30px' }, 0);
			}else{
				$('#backtop').removeClass('active');
				// $('#backtop').stop().animate({ bottom: '-60px' }, 0);
			} 
		});
		$(document).ready(function() {
			$('#backtop').click(function(event) {
				event.preventDefault();
				$('.uk-slidenav-next').trigger('click');
				$('html, body').animate({scrollTop: 0},500);
			});

			$(document).on('click', '.uk-slidenav-next', function(event) {
				vh_init();
			});	
		});

	</script>
</body>
</html>
