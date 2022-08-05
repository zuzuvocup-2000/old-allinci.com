<section id="art-catalogue">
	<?php $this->load->view('homepage/frontend/common/breadcrumb', array('breadcrumbStyle' => 'expand')); ?>

	<div class="uk-container uk-container-center">
		<div class="contact_page_content _page_content">
			<?php 
				// print_r($detailCatalogue); exit;
			 ?>
			<div class="excerpt">
				<?php echo $detailCatalogue['excerpt'] ?>
			</div>
			<div class="description">
				<?php echo $detailCatalogue['description'] ?>
			</div>
		</div>
	</div>
</section>
