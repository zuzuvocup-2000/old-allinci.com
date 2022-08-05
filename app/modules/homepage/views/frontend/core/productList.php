<?php if(isset($productList) && is_array($productList) && count($productList)){ ?>
	<?php if(isset($style) && $style == 'product-1'){ ?>
		
		<ul class="uk-grid uk-grid-medium uk-grid-width-1-2 uk-grid-width-large-1-3 list-product" data-uk-grid-match="{target: '.ht2109_grid_match'}">
			<?php foreach ($productList as $keyPost => $valPost) { ?>
			 	<?php        
			        $title = $valPost['title'];
			        $href = rewrite_url($valPost['canonical'], true, true);
			        $image = $valPost['image'];
			        // $description = $valPost['description'];
			        $description = cutnchar(strip_tags($valPost['description']), 250);
			    ?>
		    <li class="">
		    	<div class="product">
		    		<div class="thumb">
			    		<a href="<?php echo $href ?>" title="<?php echo $title ?>" class="image img-cover"><img src="<?php echo $image ?>" alt="<?php echo $title ?>"></a>
			    	</div>
			    	<div class="info ht2109_grid_match">
			    		<h3 class="title"><a href="<?php echo $href ?>" title="<?php echo $title ?>" class=" line-3"><?php echo $title ?></a></h3>
			    		<div class="description line-3">
			    			<?php echo $description ?>
			    		</div>
			    	</div>
		    	</div>
		    </li>
			<?php } ?>
		</ul>
	<?php //}else if(isset($style) && $style == 'style-2' ){ ?>
		
	<?php }else{ ?>
		<ul class="uk-grid uk-grid-medium uk-grid-width-1-2 uk-grid-width-large-1-4 list-product" data-uk-grid-match="{target: '.ht2109_grid_match'}">
			<?php foreach ($productList as $keyPost => $valPost) { ?>
			 	<?php        
			        $title = $valPost['title'];
			        $href = rewrite_url($valPost['canonical'], true, false);
			        $image = $valPost['image'];
			        // $description = $valPost['description'];
			        $description = cutnchar(strip_tags($valPost['description']), 250);

		            $info = getPriceFrontend(array(
				        'productDetail' => $valPost
				    ), true);
			    ?>
		    <li class="">
		    	<div class="product">
		    		<div class="thumb">
			    		<a href="<?php echo $href ?>" title="<?php echo $title ?>" class="image img-scaledown"><img src="<?php echo $image ?>" alt="<?php echo $title ?>"></a>
			    	</div>
			    	<div class="info">
			    		<h3 class="title"><a href="<?php echo $href ?>" title="<?php echo $title ?>" class="ht2109_grid_match line-3"><?php echo $title ?></a></h3>
						<?php if(isset($info) && is_array($info) && count($info)){ ?>
						    <?php if(isset($info['flag']) && $info['flag'] == 1){ ?>
						        <div class="product-price uk-text-center">
						            <!-- <span>Giá: </span> -->
						            <span class="new-price"><?php echo $info['price_final'] ?></span>
						        </div>
						    <?php }else{ ?>
						        <div class="product-price uk-text-center">
						            <span class="old-price"><?php echo $info['price_old'] ?></span>
						            <span class="new-price"><?php echo $info['price_final'] ?></span>
						        </div>
						    <?php } ?>
						<?php } ?>

			    	</div>
		    	</div>
		    </li>
			<?php } ?>
		</ul>

	<?php } ?>
<?php } ?>
