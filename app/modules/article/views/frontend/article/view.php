<section id="artdetail" class="page-body">
	<?php $this->load->view('homepage/frontend/common/breadcrumb'); ?>
	<div class="art-detail bg_white">
		<div class="uk-container uk-container-center">
			<div class="uk-grid uk-grid-medium" data-uk-grid-match="{target: '.ht2109_grid_match'}">
				<div class="uk-width-large-3-4">
					<?php if($detailArticle['image'] != ''){ ?>
			        <div class="bigthumb">
						<div class="image"><img src="<?php echo $detailArticle['image'] ?>" alt="<?php echo $detailArticle['image'] ?>"></div>
					</div>
					<?php } ?>
					
				    <h1 class="entry-title"><?php echo $detailArticle['title']; ?></h1>
					<div class="entry-description">
						<?php echo $detailArticle['description']; ?>
					</div>
					<?php $this->load->view('homepage/frontend/core/art_relate'); ?>
			    </div>
			    <div class="uk-width-large-1-4">
					<?php $this->load->view('homepage/frontend/common/aside_art'); ?>
			    </div>
			</div>
			

		</div>
	</div>
</section>
