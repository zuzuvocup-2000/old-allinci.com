<div id="artcatalogue" class="page-body media-catalogue">
	<?php 
		$banner = slide(array('keyword' => 'banner-parent'));
		$banner = $banner[0];
	 ?>

	<section class="banner">
	<?php if(isset($banner) && is_array($banner) && count($banner)){ ?>
		<div class="image img-cover"><img src="<?php echo $banner['src']; ?>" alt="<?php echo $banner['src']; ?>"></div>
		<div class="uk-container uk-container-center">
			<div class="breadcrumb">
				<div class="title"><?php echo $breadcrumb[0]['title']; ?></div>
				<ul class="uk-breadcrumb">
					<li><a href="" title=""><?php //echo '<i class="fa fa-home"></i>'; ?> Trang chủ</a></li>
					<?php foreach($breadcrumb as $key => $val){ ?>
					<?php
						$title = $val['title'];
						$href = rewrite_url($val['canonical'], true, true);
					?>
					<li class="<?php if($key == count($breadcrumb) - 1) echo 'uk-active'; ?>"><a href="<?php echo $href; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a></li>
					<?php } ?>
				</ul>
			</div>
		</div><!-- .breadcrumb -->
	<?php } ?>
	</section>
	

	<?php 
		$general = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, canonical, image, lft, rgt, meta_keyword, meta_title, meta_description, description, layoutid',
			'table' => 'media_catalogue',
			'where' => array('id' => 1),
		));

		if (isset($general) && is_array($general) && count($general)) {
			$general['catalogue'] =  $this->Autoload_Model->_get_where(array(
				'select' => 'id, title, canonical, image, lft, rgt, meta_keyword, meta_title, meta_description, description, layoutid',
				'table' => 'media_catalogue',
				'where' => array('parentid' => 1),
			), true);
		}

		if (isset($general['catalogue']) && is_array($general['catalogue']) && count($general['catalogue'])) {
			foreach ($general['catalogue'] as $key => $val) {
				$general['catalogue'][$key]['post'] =  $this->Autoload_Model->_get_where(array(
					'select' => 'id, title, canonical, image, video_link, video_iframe, description, created',
					'table' => 'media',
					'where' => array('catalogueid' => $val['id']),
					'limit' => 6,
					'order_by' => 'order desc, title asc, id desc',
				), true);
			}
		}

		// print_r($general);die;
	

	 ?>
	<div class="artcatalogue">
		<div class="uk-container uk-container-center">
			<h1 class="heading-1"><a href="<?php echo $canonical; ?>" title="<?php echo $detailCatalogue['title']; ?>"><?php echo $detailCatalogue['title']; ?></a></h1>
			<?php if(!empty($detailCatalogue['description'])){ ?>
			<div class="description mb20">
				<?php echo $detailCatalogue['description']; ?>
			</div>
			<?php } ?>
			

			<?php if(isset($general['catalogue']) && is_array($general['catalogue'])  && count($general['catalogue'])){ ?>
				<?php foreach ($general['catalogue'] as $key => $val) { ?>
					<?php if ($key == 0){ ?>
						
					<div class="mediacatalogue-list about-section gallery-section">
						<section class="panel-body">
							<?php if(isset($val['post']) && is_array($val['post'])  && count($val['post'])){ ?>
								<div class="uk-grid lib-grid-20 uk-grid-width-1-2 uk-grid-width-medium-1-3 list-photo">
								<?php foreach($val['post'] as $keypost => $valpost){ ?>
								<?php 															
									$title = $valpost['title'];
									$image = $valpost['image'];
									$href = rewrite_url($valpost['canonical'], TRUE, TRUE);
									$description = cutnchar(strip_tags($valpost['description']),100);			
								?>
									<div class="item">
										<div class="photo">
											<div class="thumb"><a class="image img-cover" href="<?php echo $href; ?>" title="<?php echo $title; ?>"><img src="<?php echo $image; ?>" alt="<?php echo $title; ?>" /></a></div>
											<div class="title"><a href="<?php echo $href; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a></div>
										</div>
										<h2 class="title uk-text-center"><a href="<?php echo $href; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a></h2>
									</div>
									<?php } ?>
								</div><!-- .uk-grid -->
							<?php } ?>
						</section><!-- .panel-body -->
					</div>
				</div>

				<?php }else{ ?>
				<div class="artcatalogue-list general-video">
					<div class="uk-container uk-container-center">
						<h2 class="heading-1"><a href="<?php echo rewrite_url($val['canonical'], TRUE, TRUE); ?>" title="<?php echo $val['title']; ?>"><?php echo $val['title']; ?></a></h2>
						<div class="uk-grid uk-grid-medium">
							
						<?php if(isset($val['post']) && is_array($val['post'])  && count($val['post'])){ ?>
							<?php foreach($val['post'] as $keyPost => $valPost){ ?>

								<?php 
									$href = rewrite_url($valPost['canonical'], true, true);
									$title = $valPost['title'];
								 ?>
								<?php if($keyPost == 0){ ?>
								<div class="uk-width-large-2-3">
									<div class="video" id="big-video">
										<div class="entry-video"><?php echo $valPost['video_iframe']; ?></div>
									</div>
								</div>
								<?php }continue; ?>
							<?php } ?>
							<div class="uk-width-large-1-3">
								<ul class="uk-list uk-clearfix list-video">
								<?php foreach($val['post'] as $keyPost => $valPost){ ?>
									<?php
										
										$title = $valPost['title'];
										$href = rewrite_url($valPost['canonical'], true, true);
										$image = getthumb($valPost['image']);
										$code = $valPost['video_iframe'];
										$description = cutnchar(strip_tags($valPost['description']), 250);
										$created = gettime($valPost['created'], 'd/m/Y');
									?>
									<?php if($keyPost != 0){ ?>
									<li>
										<div class="video uk-clearfix" data-iframe="<?php echo base64_encode(json_encode($code)); ?>">
											<div class="thumb">
												<a class="image img-cover" href="<?php echo $href; ?>" title="<?php echo $title; ?>"><img src="<?php echo $image; ?>" alt="<?php echo $title; ?>" /></a>
											</div>
											<div class="infor">
												<h4 class="title"><a href="<?php echo $href; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a></h4>
												<div class="meta"><i class="fa fa-clock-o"></i> <?php echo $created; ?></div>
											</div>
										</div>
									</li>
									<?php } ?>
								<?php } ?>
								</ul>
							</div>
						<?php } ?>
						</div>
					</div>
				</div>
				<?php } ?>
			<?php } ?>
		<?php } ?>
	</div>
	
	
</div><!-- #prdcatalogue -->

<script>
	$(document).ready(function() {
		$(document).on('click' , '.video a', function(){
			let _this = $(this);
			let data = _this.closest('.video').attr('data-iframe');
			data = JSON.parse(atob(data));
			
			let locateVideo = $('#big-video').find('.entry-video');
			locateVideo.html(data);
			return false;
		});
		// $(document).on('change' , '#video-list', function(){
		// 	let _this = $(this);
		// 	let data = _this.val();
		// 	data = JSON.parse(atob(data));
			
		// 	let locateVideo = _this.closest('.intro-video').find('.video-iframe');
		// 	locateVideo.html(data);
		// 	return false;
		// });
	});
</script>


