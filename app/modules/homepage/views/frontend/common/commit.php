<?php
	/* layout control*/
	$why = layout_control(array(
		'layoutid' => 3,
		'children' => array(
			'flag' => FALSE,
			'post' => FALSE,
			// 'limit' => 6,
		),
		'post' => array(
			'flag' => true,
			'limit' => 4
		)
	), FALSE);
	// print_r($why); exit;
?>


<?php if(isset($why['catalogue']) && is_array($why['catalogue'])  && count($why['catalogue'])){ ?>
	<?php foreach ($why['catalogue'] as $key => $val) {?>
	<section class="ht2109_why mb-common lazyloading" style="background: url('<?php echo $val['image']; ?>') no-repeat center; background-size: cover;">
		<div class="uk-container uk-container-center">
			<h2 class="heading-1 uk-text-center" data-animation="zoomInZ" data-delay="0.4s"><span class="color-white"><?php echo $why['title'] ?></span></h2>
			
			<?php if(isset($val['post']) && is_array($val['post'])  && count($val['post'])){ ?>
			<ul class="uk-grid uk-grid-collapse uk-grid-width-1-2 uk-grid-width-medium-1-4 ht2109_why_item-list" data-animation="fadeInUpZ" data-delay="1.2s" data-uk-grid-match="{target:'.ht2109_grid_match'}">
				<?php foreach ($val['post'] as $keyPost => $valPost) {?>
					<?php 								
						$title = $valPost['title'];
						$href = rewrite_url($valPost['canonical'], true, false);
						$image = $valPost['image'];
						$description = cutnchar(strip_tags($valPost['description']), 250);
					?>
				<li class="">
					<div class="ht2109_why_item ht2109_grid_match">
						<div class="thumb"><div class="image img-scaledown"><img src="<?php echo $image; ?>" alt="<?php echo $title; ?>" /></div></div>
						<div class="info">
							<h2 class="ht2109_why_item_title"><span><?php echo $title; ?></span></h2>
							<div class="ht2109_why_item_desc"><?php echo $description; ?></div>
							<!-- <div class="reamore"><a href="<?php //echo $href; ?>" title="Xem thêm" class="ht2109_prdcatalog-panel_product_btn-readmore">Xem thêm</a></div> -->
						</div>
					</div>
				</li>
				<?php } ?>
			</ul>
			<?php } ?>
		</div>
	</section>
<?php } ?>
<?php } ?>

<style>
/* ===================.ht2109_why ===================*/
.color-white{
	color: #fff;
}

.ht2109_why{
	padding: 40px 0 50px;
	position: relative;
}
.ht2109_why:before{
	content: '';
    display: block;
    position: absolute;
    background-color: #1d1c1ded;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 1;
}

.ht2109_why >*{
	position: relative;
	z-index: 5;
}


.ht2109_why .heading-1{
	margin-bottom: var(--mg);
}
.ht2109_why_item {
    /*padding: 40px 20px 20px;*/
    /*background: #fff;*/
}

/*.ht2109_why_item-list >li:nth-child(odd) .ht2109_why_item {
	background: var(--color);
}

.ht2109_why_item-list >li:nth-child(odd) .ht2109_why_item .image{
	filter: brightness(0) invert(1);
}*/

.ht2109_why_item .thumb{}
.ht2109_why_item .thumb .image{
	height: 200px;
}
.ht2109_why_item .info{
	text-align: center;
}

.ht2109_why_item_title{
	margin-bottom: 15px;
}
.ht2109_why_item_title>*{
	display: block;
	text-transform: uppercase;
}

.ht2109_why_item_desc{
	display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/*.ht2109_why_item-list >li:nth-child(odd) .ht2109_why_item_title>*{
	color: #fff;
}
.ht2109_why_item-list >li:nth-child(odd) .ht2109_why_item_desc{
	color: #fff;
}*/

.ht2109_why_item-list >li .ht2109_why_item_title>*{
	color: #fff;
}
.ht2109_why_item-list >li .ht2109_why_item_desc{
	color: #fff;
}

@media (max-width: 959px) {
	.ht2109_why_item{
		padding: var(--pd) 20px 20px;
	}
}

</style>