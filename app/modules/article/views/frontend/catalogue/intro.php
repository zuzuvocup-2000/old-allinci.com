<section id="art-catalogue">
	<div class="uk-container uk-container-center">
		<div class="uk-grid uk-grid-medium">
			<div class="uk-width-large-1-4 uk-visible-large">
				<?php $this->load->view('homepage/frontend/common/aside'); ?>
			</div>
			<div class="uk-width-large-3-4">
				<div class="art-detail">
					<h1 class="entry-title"><?php echo $detailCatalogue['title']; ?></h1>
					<div class="entry-description">
						<?php echo $detailCatalogue['description']; ?>
					</div>

					<?php $this->load->view('homepage/frontend/core/artrelate', array('style' => 1)); ?>
				</div>
			</div>
		</div>
	</div>
</section>
