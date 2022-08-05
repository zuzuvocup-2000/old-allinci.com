<?php 
    $slide = slide(array('keyword' => 'banner-prd'));
 ?>

<?php if(isset($slide) && is_array($slide) && count($slide)){ ?>
<section class="main-slide">
	<!-- <div class="uk-container uk-container-center uk-container-1"> -->
		<div class="uk-slidenav-position slide-show" data-uk-slideshow="{autoplay: true, autoplayInterval: 7500, animation: 'scroll'}">
			<ul class="uk-slideshow">
				<?php foreach($slide as $key => $val) { ?>
				<li>
					<a class="image img-cover" href="<?php echo $val['link']; ?>" title="<?php echo $val['title']; ?>"><img src="<?php echo $val['src']; ?>" alt="<?php echo $val['src']; ?>" /></a>
					<div class="title"><?php echo $val['title']; ?></div>
				</li>
				<?php } ?>
			</ul>
			<a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-previous" data-uk-slideshow-item="previous"></a>
			<a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-next" data-uk-slideshow-item="next"></a>
	    	<ul class="uk-dotnav uk-dotnav-contrast uk-position-bottom uk-flex-center">
			<?php for($i = 0; $i<count($slide); $i++){ ?>
		        <li data-uk-slideshow-item="<?php echo $i; ?>"><a href=""></a></li>
			<?php } ?>
		    </ul>
		</div>
	<!-- </div> -->
</section><!-- .main-slide -->
<?php } ?>
