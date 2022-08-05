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
	
	
	<div class="artcatalogue">
		<div class="uk-container uk-container-center">
			<h1 class="heading-1"><span><?php echo $detailCatalogue['title']; ?></span></h1>
			<?php if(!empty($detailCatalogue['description'])){ ?>
			<div class="description mb20">
				<?php echo $detailCatalogue['description']; ?>
			</div>
			<?php } ?>
			
			<div class="artcatalogue-list">
				<?php if(isset($mediaList) && is_array($mediaList) && count($mediaList)){ ?>
				<ul class="uk-grid uk-grid-medium uk-grid-width-medium-1-2 uk-grid-width-large-1-3 list-artdetail style-1">
					<?php foreach($mediaList as $key => $val){ ?>

					<?php 
						$href = rewrite_url($val['canonical'], true, true);
						$title = $val['title'];
					 ?>
					<li>
						<article class="article video">
							<div class="entry-video"><?php echo $val['video_iframe']; ?></div>
							<div class="info">
								<h3 class="title"><a href="<?php echo $href; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a></h3>
							</div>
						</article>
					</li>
					<?php } ?>
				</ul>
				<?php } ?>
				
				<?php echo  (isset($PaginationList)) ? $PaginationList : ''; ?>
				
			</div>
		</div>
	</div>
	
	
</div><!-- #prdcatalogue -->