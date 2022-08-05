<div id="artcatalogue" class="page-body media-catalogue">
	<div class="artcatalogue">
		<div class="uk-container uk-container-center">
			
			<h1 class="heading-1"><a href="<?php echo $canonical; ?>" title="<?php echo $detailCatalogue['title']; ?>"><?php echo $detailCatalogue['title']; ?></a></h1>
			<?php if(!empty($detailCatalogue['description'])){ ?>
			<div class="description mb20">
				<?php echo $detailCatalogue['description']; ?>
			</div>
			<?php } ?>
			
			<div class="mediacatalogue-list about-section gallery-section">
				<section class="panel-body">
					<?php if(isset($mediaList) && is_array($mediaList)  && count($mediaList)){ ?>
						<div class="uk-grid lib-grid-20 uk-grid-width-1-2 uk-grid-width-medium-1-3 list-photo">
						<?php foreach($mediaList as $key => $val){ ?>
						<?php 															
							$title = $val['title'];
							$image = $val['image'];
							$href = rewrite_url($val['canonical'], TRUE, TRUE);
							$description = cutnchar(strip_tags($val['description']),100);			
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
				<?php echo  (isset($PaginationList)) ? $PaginationList : ''; ?>
			</div>
		</div>
	</div>
	
	
</div><!-- #prdcatalogue -->