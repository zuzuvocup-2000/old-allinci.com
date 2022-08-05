<section class="main-slide">
	<div class="uk-slidenav-position slide-show" data-uk-slideshow="{autoplay: true, autoplayInterval: 7500, animation: 'random-fx'}">
		<ul class="uk-slideshow">
			<?php for($i=1;$i<=2;$i++) { ?> 
			<li><img src="resources/img/upload/slide-<?php echo $i; ?>.jpg" alt="" /></li>
			<?php } ?>
		</ul>
		<a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-previous" data-uk-slideshow-item="previous"></a>
    	<a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-next" data-uk-slideshow-item="next"></a>
    	<ul class="uk-dotnav uk-dotnav-contrast uk-position-bottom uk-flex-center">
	        <li data-uk-slideshow-item="0"><a href=""></a></li>
	        <li data-uk-slideshow-item="1"><a href=""></a></li>
	        <li data-uk-slideshow-item="2"><a href=""></a></li>
	        <li data-uk-slideshow-item="3"><a href=""></a></li>
	        <li data-uk-slideshow-item="4"><a href=""></a></li>
	    </ul>
	</div>
</section><!-- .main-slide -->