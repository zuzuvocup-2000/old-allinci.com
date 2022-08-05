<?php if(isset($relatedArticle) && is_array($relatedArticle) && count($relatedArticle)){ ?>

<div class="artdetail-relate style-1 mt30">
	<div class="heading-1 mb20"><span>Bài viết liên quan</span></div>
	<?php if($style == 1){ ?>
	<ul class="uk-list uk-clearfix uk-grid uk-grid-medium uk-grid-width-1-2 uk-grid-width-medium-1-3 uk-grid-width-large-1-3 list-related">
		<?php foreach($relatedArticle as $key => $val){ ?>
		<?php
			$title = $val['title'];
			$image = $val['image'];
			$href = rewrite_url($val['canonical'], TRUE, TRUE);
			$description = cutnchar(strip_tags($val['description']),100);
			$created = gettime($val['created'], 'd/m/Y');
		?>

		<li class="mb10">
			<article class="article">
				<div class="thumb"><a href="<?php echo $href; ?>" title="<?php echo $title; ?>" class="image img-cover"><img src="<?php echo $image; ?>" alt="<?php echo $title; ?>" /></a></div>
				<div class="info">
					<h3 class="title"><a href="<?php echo $href; ?>" title="<?php echo $title; ?>" class="line-1"><?php echo $title; ?></a></h3>
					<div class="meta uk-clearfix mb10">
	                    <span class="post-date"><?php echo $created; ?></span>
	                </div>
					<div class="description">
						<?php echo $description; ?>
					</div>
				</div>
			</article>
		</li>
		<?php } ?>
	</ul>
	<?php } ?>
</div>
<?php } ?>
