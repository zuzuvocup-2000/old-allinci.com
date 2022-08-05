<div id="homepage" class="page-body">
	<?php $this->load->view('homepage/frontend/common/mainslide'); ?>
	
    <?php if(isset($article_1['catalogue']) && is_array($article_1['catalogue'])  && count($article_1['catalogue'])){ ?>
		<?php foreach ($article_1['catalogue'] as $keyCat => $valCat) {?>

		<section class="ht2109_panel art_1" id="">
		    <div class="uk-container uk-container-center">
		        <div class="panel-head uk-text-center">
		            <h2 class="heading-1 mb15"><span><?php echo $article_1['title'] ?></span></h2>
		            <div class="subtitle"><?php echo $valCat['title'] ?></div>
		        </div>
		        <div class="panel-body">
					<div class="desc">
						<?php echo $valCat['description'] ?>
					</div>
					<div class="thumb_ratio style-43">
						<div class="image img-cover">
							<img src="<?php echo $valCat['image'] ?>" alt="<?php echo $valCat['title'] ?>">
						</div>
		        	</div>
		        </div>
		    </div>
		</section>
		<?php } ?>
	<?php } ?>


	<?php if(isset($article_2['post']) && is_array($article_2['post'])  && count($article_2['post'])){ ?>
		<?php foreach ($article_2['post'] as $key => $val) {?>
			<?php if($key > 0) break ?>
			<div class="hp-banner"
				style="
				background: url('<?php echo $val['image']; ?>') no-repeat center; 
				background-size: cover;"
			>
				<div class="uk-container uk-container-center">
					<div class="uk-flex uk-flex-center">
						<div class="uk-width-large-2-3">
							<div class="desc"><?php echo $val['description'] ?></div>
							<div class="title"><?php echo $val['title'] ?></div>
						</div>
					</div>
   			 	</div>
			</div>
		<?php } ?>
	<?php } ?>

	<?php 
		// print_r($menuPrd['catalogue']); exit;
	 ?>

    <?php if(isset($menuPrd['catalogue']) && is_array($menuPrd['catalogue'])  && count($menuPrd['catalogue'])){ ?>
		<?php foreach ($menuPrd['catalogue'] as $keyCat => $valCat) {?>
			<section class="ht2109_panel" id="menu">
			    <div class="uk-container uk-container-center">
			        <div class="panel-head uk-text-center">
			            <h2 class="heading-1 mb15"><span><?php echo $menuPrd['title'] ?></span></h2>
			            <div class="subtitle"><?php echo $valCat['title'] ?></div>
			        </div>
			        <div class="panel-body">
						<?php if(isset($valCat['children']) && is_array($valCat['children'])  && count($valCat['children'])){ ?>
			        	<section class="mobile-hp-topprd hp-panel">
							<div class="uk-container uk-container-center">
								<?php /*
								<header class="panel-head">
									<div class="uk-overflow-container">
									   	<ul class="uk-list uk-clearfix uk-flex uk-flex-middle nav-tabs" data-uk-switcher="{connect:'#hp-prd-1',animation: 'uk-animation-fade, uk-animation-slide-left', swiping: true }">
										<?php foreach ($valCat['children'] as $keyChild => $valChild) {?>
								     		<li aria-expanded="true" class="<?php echo ($keyChild == 0)? 'uk-active':'' ?>"><a href="#menu" title="<?php echo $valChild['title'] ?>"><?php echo $valChild['title'] ?></a></li>
							   			<?php } ?>
									   </ul>
								   </div>
								</header>
								*/ ?>
								<section class="panel-body">
								<ul id="hp-prd-1" class="uk-switcher">
									<?php foreach ($valCat['children'] as $keyChild => $valChild) {?>
									<li>
										<?php if(isset($valChild['post']) && is_array($valChild['post'])  && count($valChild['post'])){ ?>

									   	<ul class="uk-list uk-clearfix list-art">
											<?php foreach ($valChild['post'] as $keyPost => $valPost) {?>
											<?php        
									            $title = $valPost['title'];
									            // $href = rewrite_url($valPost['canonical'], true, false);
									            $image = $valPost['image'];
									            // $description = $valPost['description'];
									            $description = cutnchar(strip_tags($valPost['description']), 150);

									            $price = $valPost['price'];
									        ?>
										   	<li>
											   <article class="uk-clearfix article-1">
												    <div class="thumb"><a href="#popup_image" data-uk-modal class="image img-cover dt_popup_image"
														data-caption="<?php echo $description ?>"
														data-url="<?php echo $valPost['landing_link'] ?>"
												    	><img src="<?php echo $image ?>" alt="<?php echo $title ?>"></a></div>
												    <div class="info">
												        <h3 class="title">
												        	<div class="uk-flex uk-flex-middle uk-flex-space-between">
												        		<a href="#" title="<?php echo $title ?>" class="line-2"><?php echo $title ?></a>
												        		<div class="price"><?php echo CURRENCY.$price ?></div>
												        	</div>
												        </h3>
												        <div class="description line-3"><?php echo $description ?></div>
												    </div>
												</article>
											</li>
										   	
											<?php } ?>
										</ul>
										<?php } ?>
										<div class="panel-foot">
											<div class="image img-cover">
												<img src="<?php echo $valChild['image'] ?>" alt="">
											</div>
											<?php if (isset($valChild['landing_link']) && !empty($valChild['landing_link'])): ?>
												<div class="uk-flex uk-flex-center">
													<a target="_blank" href="<?php echo $valChild['landing_link'] ?>" class="dt_btn_viewmore">Viewmore</a>
												</div>
											<?php endif ?>
										</div>
									</li>
									
									<?php } ?>
								</ul>
								</section>
							</div>
						</section>
					   <?php } ?>
			        </div>
			    </div>
			</section>
		<?php } ?>	
	<?php } ?>
	
	<?php
	
	if(isset($chef['post'][0]) && is_array($chef['post'][0]) && count($chef['post'][0])){ 
		$chef = $chef['post'][0];
    }

	?>
	<?php if(isset($chef) && is_array($chef) && count($chef)){ ?>
		<div class="about-chefs"
			style="
				background: url('<?php echo $chef['image']; ?>') no-repeat center; 
				background-size: cover;"
		>	
			<div class="uk-container uk-container-center">
				<div class="about-chefs_ct">
					<div class="heading-1 order-1 uk-text-center mb15">
						<span class="color_white"><?php echo $chef['title'] ?></span>
					</div>
					<div class="desc color_white">
						<?php echo $chef['description'] ?>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	
	
	
	<!-- customer -->

	<?php
	

	/*slide owl*/
	    $owlInit = array(
	        'margin' => 10,
	        'lazyload' => true,
	        'nav' => true,
	        'navText' => ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>'],
	        'autoplay' => true,
	        'smartSpeed' => 1000,
	        'autoplayTimeout' => 3000,
	        'autoplayHoverPause' => true,
	        'dots' => true,
	        'loop' => true,
	        'responsive' => array(
	            0 => array(
	                'items' => 1,
	            ),
	            600 => array(
	                'items' => 1,
	            ),
	            1000 => array(
	                'items' => 1,
	            ),
	        )
	    );
	?>

    <?php if(isset($customer['catalogue']) && is_array($customer['catalogue'])  && count($customer['catalogue'])){ ?>
		<?php foreach ($customer['catalogue'] as $keyCat => $valCat) {?>
			<?php if(isset($valCat['post']) && is_array($valCat['post'])  && count($valCat['post'])){ ?>

			<section class="ht2109_panel homepage-student owl-slide" id="price">
			    <div class="uk-container uk-container-center">
			        <div class="panel-head uk-text-center">
			            <h2 class="heading-1 mb15"><span><?php echo $customer['title'] ?></span></h2>
			            <div class="subtitle"><?php echo $valCat['title'] ?></div>
			        </div>
					<section class="panel-body">
						<div id="homepage-students" class="owl-carousel owl-theme" data-option="<?php echo base64_encode(json_encode($owlInit)); ?>">
							<?php foreach ($valCat['post'] as $key => $val) {?>
								<?php 								
									$title = $val['title'];
									$href = rewrite_url($val['canonical'], true, true);
									$image = $val['image'];
									$excerpt = $val['excerpt'];
									$description = cutnchar(strip_tags($val['description']), 250);

								?>
							<div class="item uk-flex uk-flex-center">
								<div class="uk-width-large-2-3">
									<div class="box">
										<article class="article">
											<div class="infor">
												<div class="description">
													<?php echo $description ?>							
												</div>
											</div>
											<div class="thumb">
												<div class="image img-cover"><img src="<?php echo $image ?>" alt="<?php echo $image ?>" /></div>
											</div>
											<div class="infor">
												<span class="title"><?php echo $title ?></span>
												 - 
												<span class="excerpt">
													<?php echo $excerpt ?>								
												</span>
											</div>
										</article><!-- .article -->
									</div>
								</div>
							</div>
							<?php } ?>
						</div>
					</section>
				</div>
			</section>
			<?php } ?>

	<?php } ?>
	<?php } ?>



	<?php
	
	if(isset($booking['post'][0]) && is_array($booking['post'][0]) && count($booking['post'][0])){ 
		$booking = $booking['post'][0];
    }

	?>
	<?php if(isset($booking) && is_array($booking) && count($booking)){ ?>
		<div class="about-chefs"
			style="
				background: url('<?php echo $booking['image']; ?>') no-repeat center; 
				background-size: cover;"
		>	
			<div class="uk-container uk-container-center">
				<div class="about-chefs_ct">
					<div class="heading-1 order-1 uk-text-center mb15">
						<span class="color_white"><?php echo $booking['title'] ?></span>
					</div>
					<div class="desc color_white">
						<?php echo $booking['description'] ?>
					</div>
					<div class="uk-flex uk-flex-center">
						<a href="<?php echo $this->general['homepage_book_link'] ?>" class="btn btn-white btn-rounded mt20"><?php echo $this->general['homepage_book'] ?></a>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	
	<!-- commit -->

	<?php 
		

	    // print_r($list_commit); exit;
	 ?>
	
	<?php if(isset($list_commit) && is_array($list_commit) && count($list_commit)){ ?>
	<div class="hp_commit">
		<div class="uk-container uk-container-center">
            <ul class="uk-list uk-clearfix list-commit">
            	<?php foreach ($list_commit as $key => $val) { ?>
		        <?php        
		        	if($val['title'] == '') continue;
		            $title = $val['title'];
		            $content = $val['content'];
		        ?>
				<li>
					<div class="commit">
						<div class="icon">
							<?php if($key != 2){ ?>
							<i class="fa fa-envelope-o" aria-hidden="true"></i>
						<?php }else{ ?>
							<i class="fa fa-map-o" aria-hidden="true"></i>
						<?php } ?>
						</div>
						<div class="info">
							<div class="title"><?php echo $title ?></div>
							<div class="subtitle"><?php echo $content ?></div>
						</div>
					</div>
				</li>
		    	<?php } ?>
            </ul>
        </div>
    </div>
	<?php } ?>  

</div><!-- .page-body -->
