<!-- ASIDE PRODUCT -->
<aside class="aside uk-visible-large">
	<?php
		$aside =  $this->Autoload_Model->_get_where(array('select' => 'id, title, slug, canonical, image, parentid, icon, level','table' => 'article_catalogue','where' => array('publish' => 0),'order_by' => 'order desc, id desc'),TRUE);
		$aside = recursive($aside);

		// if(isset($aside[0]['children']) && is_array($aside[0]['children']) && count($aside[0]['children'])){
		// 	$aside = $aside[0]['children'];
		// }

		// print_r($aside);die;
	?>
	
	<?php /*
	<div class="aside-panel aside-category">
		<header class="panel-head">
			<h3 class="aside-heading"><span>Danh mục bài viết</span></h3>
		</header>
		<section class="panel-body">
			<?php if(isset($aside) && is_array($aside) && count($aside)){ ?>
			<ul class="uk-list uk-clearfix list-as-category">
				<?php foreach($aside as $key => $val){ ?>
				<?php
					$titleC = $val['title'];
					$hrefC = rewrite_url($val['canonical'], true , true);
					$image = getthumb($val['image']);
				?>
				<li>
					<div class="as-category">
						<h4 class="title"><a href="<?php echo $hrefC; ?>" title="<?php echo $titleC; ?>"><?php echo $titleC; ?></a></h4>
						<?php if(isset($val['children']) && is_array($val['children']) && count($val['children'])){ ?>
						<ul class="uk-list uk-clearfix list-subcategory">
							<?php foreach($val['children'] as $keySub => $valSub){ ?>
							<?php
								$titleS = $valSub['title'];
								$hrefS = rewrite_url($valSub['canonical'],true, true);
							?>

							<li>
								<div class="title"><a href="<?php echo $hrefS; ?>" title="<?php echo $titleS; ?>"><?php echo $titleS; ?></a></div>
							</li>
							<?php } ?>
						</ul>
						<?php } ?>	
					</div>
				</li>
				<?php }?>
			</ul>
			<?php }?>
		</section><!-- .panel-body -->
	</div><!-- .aside-panel -->
	*/ ?>

	<?php 
		$prd_hot = get_highlight_object('highlight', 'article');
		// print_r($prd_hot); exit;
 	?>

	<section class="aside-panel aside-category aside-product">
		<header class="panel-head">
			<h3 class="aside-heading"><span>Tin hot</span></h3>
		</header>
		<section class="panel-body">
			<ul class="uk-list uk-clearfix aside-list-product">
				<?php if(isset($prd_hot) && is_array($prd_hot) && count($prd_hot)){ ?>
				    <?php foreach ($prd_hot as $keyPost => $valPost) { ?>
				        <?php        
				            $title = $valPost['title'];
				            $href = rewrite_url($valPost['canonical'], true, false);
				            $image = $valPost['image'];
				            // $description = $valPost['description'];
				            $description = cutnchar(strip_tags($valPost['description']), 100);

				            $info = getPriceFrontend(array(
				            	'productDetail' => $valPost,
				            ), true);
				        ?>
						<li>
							<div class="uk-clearfix product-same">
								<div class="thumb">
									<a href="<?php echo $href ?>" title="<?php echo $title ?>" class="image img-cover img-shine"><img src="<?php echo $image ?>" alt=""></a>
								</div>
								<div class="info">
									<h4 class="title"><a href="<?php echo $href ?>" title="<?php echo $title ?>"><?php echo $title ?></a></h4>
									<div class="description line-2"><?php echo $description ?></div>
								</div>
							</div>
						</li>
				        
				    <?php } ?>
				<?php } ?>  
			</ul>
		</section>
	</section>
</aside><!-- .aside -->
