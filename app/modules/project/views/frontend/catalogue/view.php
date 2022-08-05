<section id="prj_catalogue" class="_page_body">
	<div class="uk-container uk-container-center">
		<div class="uk-grid uk-grid-medium">
			<div class="uk-width-large-3-4">
				<div class="prj-catalogue">
					<h1 class="heading-1 mb20"><span><?php echo $detailCatalogue['title']; ?></span></h1>
					<?php if(isset($projectList) && is_array($projectList) && count($projectList)){ ?>
						<?php 
							$data = '';
							$data = array(
								'projectList' => $projectList,
								'style' => 'style-1',
							);
						 ?>
						<?php $this->load->view('homepage/frontend/core/projectList', $data); ?>
					<?php } ?>
					<?php echo (isset($PaginationList)) ? $PaginationList : ''; ?>
				</div>
			</div>
			<div class="uk-width-large-1-4 uk-visible-large">
				<?php $this->load->view('homepage/frontend/common/aside'); ?>
			</div>
		</div>
	</div>
</section>
