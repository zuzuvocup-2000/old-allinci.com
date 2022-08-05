<?php if(isset($articleList) && is_array($articleList) && count($articleList)){ ?>

	<?php if(isset($style) && $style == 'style-1'){ ?>
		<ul class="uk-grid uk-grid-medium uk-grid-width-1-2 uk-grid-width-medium-1-3 uk-grid-width-large-1-3 _list_article" data-uk-grid-match="{target: '.ht2109_grid_match'}">
			<?php foreach ($articleList as $keyPrj => $valPrj) { ?>
				<?php if(isset($limit) && (int)$limit > 0){ ?>
					<?php if($keyPrj > ($limit - 1)) break; ?>
				<?php } ?>
			 	<?php        
			        $title = $valPrj['title'];
			        $href = rewrite_url($valPrj['canonical'], true, false);
			        $image = $valPrj['image'];
			        // $description = $valPrj['description'];
			        $description = cutnchar(strip_tags($valPrj['description']), 250);

			        // $video_iframe = $valPrj['video_iframe'];
                    $created = gettime($valPrj['created'], 'd/m/Y');
			    ?>
		    	
                <li class="mb20">
                    <div class="uk-clearfix _article">
                    	<div class="thumb"><a class="image img-cover" href="<?php echo $href ?>" title="<?php echo $title ?>"><img src="<?php echo $image ?>" alt="<?php echo $title; ?>"></a></div>
                        <div class="info">
                            <h3 class="title mb5"><a class="ht2109_grid_match line-2" href="<?php echo $href; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a></h3>

                            <?php /*
                            <div class="meta uk-clearfix mb10">
								<i class="fa fa-clock-o" aria-hidden="true"></i>
                                <span class="post-date"><?php echo $created; ?></span>
                            </div>
                            */ ?>
                            <div class="description line-3 mb20">
                            	<?php echo $description ?>
                            </div>
			    			<div class="ht2109_readmore"><a href="<?php echo $href ?>">Xem chi tiết</a></div>
                        </div>
                    </div>
                </li>
			<?php } ?>
		</ul>

		<?php }else if(isset($style) && $style == 'scroll' ){ ?>
		<?php 
			$owlInit = array(
		        'margin' => 10,
		        'lazyload' => true,
		        'nav' => false,
		        'navText' => ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>'],
		        'autoplay' => true,
		        'smartSpeed' => 1000,
		        'autoplayTimeout' => 3000,
		        'autoplayHoverPause' => true,
		        'rewind' => true,
		        'dots' => false,
		        'loop' => false,
		        'responsive' => array(
		            0 => array(
		                'items' => 2,
		            ),
		            600 => array(
		                'items' => 3,
		            ),
		            1000 => array(
		                'items' => 4,
		            ),
		        )
		    );
		 ?>

	 	<section class="owl-slide mb-common">
			<div class="uk-container uk-container-center">
				<div class="owl-carousel owl-theme _list_article style-2" data-option="<?php echo base64_encode(json_encode($owlInit)); ?>">
					<?php foreach ($articleList as $keyPost => $valPost) {?>
						<?php 								
							$title = $valPost['title'];
							$href = rewrite_url($valPost['canonical'], true, true);
							$image = getthumb($valPost['image']);
							// $excerpt = $valPost['excerpt'];
							$description = cutnchar(strip_tags($valPost['description']), 100);
						?>
					<div class="item">
						<div class="article">
							<div class="thumb">
								<a class="image img-scaledown" href="<?php echo $href; ?>" title="<?php echo $title; ?>"><img src="<?php echo $image; ?>" alt="<?php echo $title; ?>" /></a>
							</div>
							<div class="info uk-clearfix ht2109_grid_match">
								<div class="post-detail">
									<h2 class="title "><a href="<?php echo $href; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a></h2>
									<div class="description line-3"><?php echo $description; ?></div>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</section>
	<?php }else{ ?>
		<ul class="uk-grid uk-grid-medium uk-grid-width-1-2 uk-grid-width-medium-1-3 uk-grid-width-large-1-4 _list_article" data-uk-grid-match="{target: '.ht2109_grid_match'}">

			<?php foreach ($articleList as $keyPost => $valPost) {?>
				<?php 								
					$title = $valPost['title'];
					$href = rewrite_url($valPost['canonical'], true, false);
					$image = $valPost['image'];
					$description = cutnchar(strip_tags($valPost['description']), 250);
					$created = gettime($valPost['created'], 'd/m');
					$weekday = get_day_of_week(gettime($valPost['created'], 'Y-m-d'), 'En');
				?>
                <li class="mb20">
					<div class="article">
						<div class="thumb">
							<a class="image img-fit-fill img-zoomin img-shine" href="<?php echo $href; ?>" title="<?php echo $title; ?>"><img src="<?php echo $image; ?>" alt="<?php echo $title; ?>" /></a>
							<?php /*
							<div class="post-date uk-hidden-small">
								<span><?php echo $weekday; ?></span>
								<span><?php echo $created; ?></span>
							</div>
							*/ ?>
						</div>
						<div class="info uk-clearfix ht2109_grid_match">
							<div class="post-detail">
								<h2 class="title "><a href="<?php echo $href; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a></h2>
								<div class="description"><?php echo $description; ?></div>
							</div>
						</div>
					</div>
				</li>
			<?php } ?>
		</ul>
	<?php } ?>
<?php } ?>