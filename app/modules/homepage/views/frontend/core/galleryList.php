<?php if(isset($album) && is_array($album) && count($album)){ ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#galleryPrd').lightGallery({
				thumbnail:true,
				// pager: false
			}); 
		})
	</script>
	<?php if(isset($style) && $style == 'style-1'){ ?>
		
	<?php }else{ ?>
		<ul class="uk-list uk-clearfix uk-grid uk-grid-small uk-grid-width-small-1-2 uk-grid-width-medium-1-3 list-photo" id="galleryPrd">
			<?php foreach($album as $key => $val){ ?>
				<li data-src="<?php echo $val; ?>" data-sub-html="">
					<div class="thumb">
						<a class="image img-cover" data-href="" href="<?php echo $val; ?>" title=""><img src="<?php echo $val; ?>" alt="" /></a>
					</div>
					<?php /*
					<div class="title"><a href="<?php echo $val; ?>" title="<?php echo $val; ?>"><?php echo $detailMedia['title']; ?></a></div>
					*/ ?>
				</li>
			<?php } ?>
		</ul><!-- .uk-grid -->
	<?php } ?>
<?php } ?>