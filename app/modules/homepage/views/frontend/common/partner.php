<?php 
	$partner = slide(array('keyword' => 'banner-partner'));

	// print_r($partner); exit;
?>
<?php if(isset($partner) && is_array($partner) && count($partner)){ ?>
<section class="partner-section">
	<div class="uk-container uk-container-center">
		<!-- <h2 class="heading-1 uk-text-center"><span>We accept payment</span></h2> -->
		<ul id="partner" class="list">
			<?php foreach($partner as $key => $val) { ?>
				<?php 															
					$title = $val['title'];
					$image = $val['src'];
					$href = $val['link'];
				?>
				<li >	
					<div class="thumb">
						<a class="image img-scaledown" title="<?php echo $title; ?>"><img src="<?php echo $image; ?>" alt="<?php echo $title; ?>" /></a>
					</div>
				</li>
			<?php } ?>
		</ul>
	</div>
	
</section><!-- .partner-section -->
<?php } ?>

<script type="text/javascript">
	$(document).ready(function() {
		$("#partner").simplyScroll();
	});
</script>

<style>
	.partner-section{
		padding: 30px 0;
		margin-bottom: var(--mg);
	}
	.partner-section .heading-1{
		margin-bottom: var(--mg);
	}

	.simply-scroll, .simply-scroll .simply-scroll-clip {
	    width: 100%;
	    height: auto;
	    margin-bottom: 0;
	}

	.simply-scroll .simply-scroll-list li{
		width: 170px;
		height: auto;
		margin-right: 25px;
	}

	.list >li>.thumb{
	    /*margin: 3px 0;*/
	}
	.list >li>.thumb .image {
     	box-shadow: 0px 0px 0 0 #999; 
	    height: 120px;
	    position: relative;
	}
	.list >li>.thumb .image >img{
		max-width: 100%;
	    position: absolute;
	    top: 0px;
	    bottom: 0px;
	    left: 0px;
	    right: 0px;
	    margin: auto;
	    max-height: 100%;
	}

</style>
