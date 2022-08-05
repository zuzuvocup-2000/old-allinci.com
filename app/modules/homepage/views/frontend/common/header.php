<?php
	$currentDay = date('Y-m-d H:i:s');
	$currentDayStart = $currentDay.' 00:00:00';
	$currentDayEnd = $currentDay.' 23:59:59';


	$promotion  = $this->Autoload_Model->_get_where([
		'select' => 'title, album, description',
		'table' => 'promotion',
		'where' => [
			'publish' => 1,
		],
	]);

	if(!isset($promotion) || is_array($promotion) == false || count($promotion) == 0){ 
		$promotion  = $this->Autoload_Model->_get_where([
			'select' => 'title, album, description',
			'table' => 'promotion',
			'where' => [
				'start_date <=' => $currentDay,
				'end_date >=' => $currentDay,
			],
		]);
    }


	$post  = $this->Autoload_Model->_get_where([
		'select' => 'id',
		'table' => 'article',
		'where' => [
			'created >=' => $currentDayStart,
			'created <=' => $currentDayEnd,
			'catalogueid' => 2,
		],
		'count' => TRUE
	]);
?>
<header class="pc-header uk-visible-large" id="#" data-uk-sticky>
	<section class="upper">
		<div class="uk-container uk-container-2 uk-container-center">
			<div class="uk-flex uk-flex-middle uk-flex-space-between">
				<?php echo logo(); ?>
				<div class="uk-flex uk-flex-middle">
 					<?php echo $this->load->view('homepage/frontend/core/navigation'); ?>
 					<div class="mb_toolbox uk-flex uk-flex-middle">
						<a class="hd-cart style-2 no-hover" href="#" onclick="return false" title="Giỏ hàng" data-promotion="<?php echo base64_encode(json_encode($promotion)) ?>">
							<img src="template/frontend/resources/img/icon/cart_black.png" alt="" style="height: 33px">
							<span class="quantity js_total_item_cart"><?php echo (isset($promotion) && is_array($promotion) && count($promotion)) ? '1' : ''; ?></span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</section> <!-- .upper -->
	<?php 
		$menuPrd = layout_control(array(
			'layoutid' => 2,
			'children' => array(
				'flag' => true,
				'post' => true,
				'limit' => 10,
			),
			'post' => array(
				'flag' => false,
				'limit' => 8
			)
		), false);
	 ?>

	 <?php if(isset($menuPrd['catalogue']) && is_array($menuPrd['catalogue'])  && count($menuPrd['catalogue'])){ ?>
		<?php foreach ($menuPrd['catalogue'] as $keyCat => $valCat) {?>
			<?php if(isset($valCat['children']) && is_array($valCat['children'])  && count($valCat['children'])){ ?>
	    	<section class="mobile-hp-topprd hp-panel mb_hd_menu">
				<!-- <div class="uk-container uk-container-center"> -->
					<header class="panel-head">
						<div class="uk-overflow-container">
						   	<ul class="uk-list uk-clearfix uk-flex uk-flex-middle nav-tabs" data-uk-switcher="{connect:'#hp-prd-1',animation: 'uk-animation-fade, uk-animation-slide-left', swiping: true }">
							<?php foreach ($valCat['children'] as $keyChild => $valChild) {?>
					     		<li aria-expanded="true" class="<?php echo ($keyChild == 0)? 'uk-active':'' ?>"><a href="#menu" title="<?php echo $valChild['title'] ?>"><?php echo $valChild['title'] ?></a></li>
				   			<?php } ?>
						   </ul>
					   </div>
					</header>
				<!-- </div> -->
			</section>
		   <?php } ?>
		<?php } ?>	
	<?php } ?>
</header>
<header class="mobile-header uk-clearfix uk-hidden-large" id="#" data-uk-sticky="">
	<section class="upper">
		<div class="uk-flex uk-flex-middle uk-flex-space-between">
			<div class="mr30">
				<a class="moblie-menu-btn skin-1" href="#offcanvas" class="offcanvas" data-uk-offcanvas="{target:'#offcanvas'}">
					<span>Menu</span>
				</a>
			</div>
			<div class="logo"><a href="" title="Logo"><img src="<?php echo $this->general['homepage_logo']; ?>" alt="Logo" /></a></div>
			<?php /*
			<a class="mobile-cart" href="<?php echo site_url('thanh-toan'); ?>" title="Giỏ hàng">
				<span class="quantity js_total_item_cart"><?php echo $this->cart->total_items(); ?></span>
			</a>
			*/ ?>
			<div class="mb_toolbox uk-flex uk-flex-middle">
				<a class="hd-cart style-2 no-hover" href="#" onclick="return false" title="Giỏ hàng" data-promotion="<?php echo base64_encode(json_encode($promotion)) ?>">
					<img src="template/frontend/resources/img/icon/cart_black.png" alt="" style="height: 33px;">
					<span class="quantity js_total_item_cart"><?php echo (isset($promotion) && is_array($promotion) && count($promotion)) ? '1' : ''; ?></span>
				</a>
			</div>
		</div>
	</section><!-- .upper -->
	<?php /*
	<section class="lower">
		<div class="mobile-search">
			<form action="<?php echo site_url('tim-kiem'); ?>" method="GET" class="uk-form form">
				<input type="text" name="keyword" class="uk-width-1-1 input-text" placeholder="Bạn muốn tìm gì hôm nay?" />
				<button type="submit" name="" value="" class="btn-submit"><i class="fa fa-search" aria-hidden="true"></i></button>
			</form>
		</div>
	</section>
	*/ ?>

	<?php 
		$menuPrd = layout_control(array(
			'layoutid' => 2,
			'children' => array(
				'flag' => true,
				'post' => true,
				'limit' => 10,
			),
			'post' => array(
				'flag' => false,
				'limit' => 8
			)
		), false);
	 ?>

	 <?php if(isset($menuPrd['catalogue']) && is_array($menuPrd['catalogue'])  && count($menuPrd['catalogue'])){ ?>
		<?php foreach ($menuPrd['catalogue'] as $keyCat => $valCat) {?>
			<?php if(isset($valCat['children']) && is_array($valCat['children'])  && count($valCat['children'])){ $post = 0;?>
	    	<section class="mobile-hp-topprd hp-panel mb_hd_menu">
				<!-- <div class="uk-container uk-container-center"> -->
					<header class="panel-head">
						<div id="div-scroll" class="uk-overflow-container">
						   	<ul class="uk-list uk-clearfix uk-flex uk-flex-middle nav-tabs va-list lta-list" data-uk-switcher="{connect:'#hp-prd-1',animation: 'uk-animation-fade, uk-animation-slide-left', swiping: true }">
							<?php foreach ($valCat['children'] as $keyChild => $valChild) {?>
					     		<li aria-expanded="true" class="<?php echo ($keyChild == 0)? 'uk-active':'' ?>"><a href="#menu" title="<?php echo $valChild['title'] ?>" id= "post-<?php echo $post?>"><?php echo $valChild['title'] ?></a></li>
				   			<?php $post = $post+1; } ?>
						   </ul>
					   </div>
					</header>
				<!-- </div> -->
			</section>
		   <?php } ?>
		<?php } ?>	
	<?php } ?>
	
</header><!-- .mobile-header -->
<div id="my-id" class="uk-modal promotion-modal">
    <div class="uk-modal-dialog uk-modal-dialog-lightbox uk-modal-dialog-large">
        <a href="" class="uk-modal-close uk-close uk-close-alt"></a>
        <span class="img-cover">
    		<img src="<?php echo isset($promotion['album'])? json_decode($promotion['album'], true)[0] : ''; ?>" alt="">
    		<?php if ($promotion['title'] != ''): ?>
    			<span class="dt_title_promotion"><?php echo $promotion['title'] ?></span>
    		<?php endif ?>
    		<?php if ($promotion['description'] != ''): ?>
    			<span class="dt_desc_promotion"><?php echo $promotion['description'] ?></span>
    		<?php endif ?>
        </span>
    </div>
</div>

<style>
	.dt_title_promotion{
		display: block;
		padding: 10px;
		text-align: center;
		text-transform: uppercase;
		font-weight: 600;
	}
	.dt_desc_promotion{
		display: block;
		text-align: center;
		padding: 10px;
		padding-top: 0;
	}
</style>

<script>
	var time = 3*60*1000;
	<?php if(isset($promotion) && is_array($promotion) && count($promotion)){ ?>
		$(window).load(function(){
			 var date = new Date();
			 var minutes = 5;
			 date.setTime(date.getTime() + (minutes * 60 * 1000));
			// set cookie
			// console.log(typeof $.cookie('popup'));

	    	if (typeof $.cookie('popup') === 'undefined'){
	    		setTimeout(function(){
	    			show_modal(); 
	    		}, 3000);
			 	$.cookie('popup', '1', { expires: date, path: '/' });
			} else {
				circle_time(time);
			}
			$('#my-id').on({
		        'show.uk.modal': function(){

		        },
		        'hide.uk.modal': function(){
		        	// $('#my-id').removeClass('active');
		        	circle_time(time);
		        }
		    });
		    function circle_time(time){
				setTimeout(function(){
					show_modal();
				}, time);
			}

			function show_modal(){
				var modal = UIkit.modal("#my-id");
				modal.show();
			}
		});
	<?php } ?>
	
</script>

<style>
	.hd-cart {
	    display: inline-block;
	    position: relative;
	    font-size: 18px;
	    color: #232323;
	    margin: 0 20px 0 15px;
	}

	.hd-cart .quantity {
	    position: absolute;
	    top: 5px;
	    margin-top: -15px;
	    right: -8px;
	    font-size: 12px;
	    /*padding: 2px 0 0 1px;*/
	    width: 20px;
	    height: 20px;
	    line-height: 20px;
	    color: #fff;
	    background: #e4405f;
	    border-radius: 50%;
	    font-weight: 400;
	    text-align: center;
	    white-space: nowrap;
	    vertical-align: baseline;
	}
	.hd-cart.style-2 .quantity {
	    right: -15px;
	}
</style>

<script>
	$(document).ready(function(){
		$('.hd-cart').on('click', function(){
			let _this = $(this);
			let promotion = JSON.parse(atob(_this.attr('data-promotion')));
			let image = JSON.parse(promotion.album);
			let promotionImage = image[0];
			$('.promotion-modal').find('img').attr('src', promotionImage);

			var modal = UIkit.modal(".promotion-modal");
			modal.show();

			return false;
		});
	});


	$(document).on('click','.scroll-move',function(e){
		let _this = $(this);
		let id = _this.attr('data-post');
		id = id.split("-");
		let distance = 0;
		 for(let i =0; i< id[1]; i++){
		 	id_part = '#post-'+i;
		 	distance = distance + $(id_part).outerWidth()
		 }
		let width = $('.lta-list').get(0).scrollWidth;
		
		$('#div-scroll').scrollLeft(distance);
	});
	
</script>




