<section id="art-catalogue">
	<?php $this->load->view('homepage/frontend/common/breadcrumb'); ?>
	<div class="uk-container uk-container-center">
		<div class="uk-grid uk-grid-medium" data-uk-grid-match="{target: '.ht2109_grid_match'}">
			<div class="uk-width-large-3-4">
		        <div class="art-catalogue">
					<?php if(isset($articleList) && is_array($articleList) && count($articleList)){ ?>
						<?php 
							$data = '';
							$data = array(
								'articleList' => $articleList,
								'style' => 'style-1',
							);
						 ?>
						 <?php $this->load->view('homepage/frontend/core/articleList', $data); ?>
						<?php echo (isset($PaginationList)) ? $PaginationList : ''; ?>
					<?php } ?>
				</div>
		    </div>
		    <div class="uk-width-large-1-4">
				<?php $this->load->view('homepage/frontend/common/aside_art');?>
		    </div>
		</div>
		
	</div>
</section>
