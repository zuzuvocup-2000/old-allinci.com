<div id="prdcatalog" class="page-body">
	<?php $this->load->view('homepage/frontend/common/breadcrumb'); ?>
	<div class="uk-container uk-container-center">
		<div class="uk-grid uk-grid-medium">
			<div class="uk-width-large-4-5">
				<section class="homepage-product catalog-panel">
					<div class="panel-head">
						<div class="uk-flex uk-flex-middle uk-flex-space-between">
							<h1 class="heading-1"><span><?php echo $detailCatalogue['title']; ?></span></h1>
							<section class="filter">
								<form class="uk-form form uk-form-horizontal" method="get" action="">
									<select id="form-h-s" name="data-filter" class="select-page">
	                                    <option value="0">Lọc sản phẩm</option>
										<option value="price_2|asc" <?php echo ($this->input->get('filter') == 'price_2|asc') ? 'selected': ''; ?>>Giá tăng dần</option>
										<option value="price_2|desc" <?php echo ($this->input->get('filter') == 'price_2|desc') ? 'selected': ''; ?>>Giá giảm dần</option>
										<option value="id|desc" <?php echo ($this->input->get('filter') == 'id|desc') ? 'selected': ''; ?>>Sản phẩm mới nhất</option>
										<option value="id|asc" <?php echo ($this->input->get('filter') == 'id|asc') ? 'selected': ''; ?>> Sản phẩm cũ nhất</option>
	                                </select>
									<script type="text/javascript">
										$(document).ready(function(){
											$('.select-page').on('change',function(){
												var filter = $(this).val();
												if(filter != 0){
													var url = '<?php echo $canonical ?>' + '?filter='+filter;
													window.location.href=url;
												}else{
													window.location.href='<?php echo $canonical; ?>';
												}

											});
										});
									</script>

								</form>
							</section>
						</div>
					</div><!-- .panel-head -->
					<?php if(isset($productList) && is_array($productList) && count($productList)){ ?>
					<div class="panel-body">
						<?php 
							$data = '';
							$data = array(
								'productList' => $productList,
								'style' => 'style-1',
							);
						 ?>
					 	<?php $this->load->view('homepage/frontend/core/productList', $data, FALSE); ?>
						<?php echo (isset($PaginationList)) ? $PaginationList : ''; ?>
					</div> <!-- .panel-body -->
					<?php } ?>
				</section> <!-- .homepage-product -->
			</div>
			<div class="uk-width-1-5 uk-visible-large">
				<?php $this->load->view('homepage/frontend/common/aside');?>
			</div>
		</div>
	</div>
</div><!-- .page-body -->
