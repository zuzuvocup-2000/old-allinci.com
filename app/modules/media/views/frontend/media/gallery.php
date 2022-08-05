<div id="media_detail" class="page-body">
	<?php $this->load->view('homepage/frontend/common/breadcrumb'); ?>
	<div class="media_detail">
		<div class="uk-container uk-container-center">
			<div class="media_detail-content">
				<div class="gallery-section">
					<section class="panel-body">
						<?php $album = json_decode($detailMedia['image_json'], TRUE); ?>
						<?php if(isset($album) && is_array($album)  && count($album)){ ?>
							<?php 
								$data = '';
								$data = array(
									'album' => $album,
								);
							 ?>
							 <?php $this->load->view('homepage/frontend/core/galleryList', $data, FALSE); ?>
						<?php } ?>
					</section><!-- .panel-body -->
				</div>
			</div>
		</div>
	</div>
	
	
</div><!-- #prdcatalogue -->