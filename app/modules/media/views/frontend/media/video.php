<div id="artdetail" class="page-body">
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
	

	<div class="artdetail">
		<div class="uk-container uk-container-center">
			<div class="artdetail-content">
				<h1 class="entry-title"><?php echo $detailMedia['title']; ?></h1>
				<div class="entry-video">
					<?php echo $detailMedia['video_iframe']; ?>
				</div>
			</div>
		</div>
	</div>
	
	
</div><!-- #prdcatalogue -->