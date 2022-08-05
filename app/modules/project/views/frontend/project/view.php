<section id="prj_detail" class="_page_body">
	<div class="uk-container uk-container-center">
		<div class="uk-grid uk-grid-medium">
			<div class="uk-width-large-3-4">
				<div class="prj-detail">
					<h1 class="entry-title"><?php echo $detailproject['title']; ?></h1>
					<div class="entry-description">
						<?php echo $detailproject['description']; ?>
					</div>
					<?php $this->load->view('homepage/frontend/core/prj_relate'); ?>
						
				</div>
			</div>
			<div class="uk-width-large-1-4 uk-visible-large">
				<?php $this->load->view('homepage/frontend/common/aside'); ?>
			</div>
		</div>
	</div>
</section>
