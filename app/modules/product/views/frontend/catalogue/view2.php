<div id="prdcatalog" class="page-body">
	<?php $this->load->view('homepage/frontend/common/breadcrumb'); ?>

		<section class="homepage-product catalog-panel">
			<?php /*
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
			*/ ?>
			<?php if(isset($subCat) && is_array($subCat) && count($subCat)){ ?>
				<?php foreach ($subCat as $key => $val) { ?>
				<section class="subcat_panel <?php echo (($key % 2) != 0)? 'order-1': ''?>">
					<div class="uk-container uk-container-center">
						<div class="panel-head">
							<h2 class="heading-1"><span><?php echo $val['title']; ?></span></h2>
							<div class="description">
								<?php echo $val['description'] ?>
							</div>
						</div><!-- .panel-head -->

						<?php if(isset($val['post']) && is_array($val['post']) && count($val['post'])){ ?>
						<div class="panel-body">
							<?php 
								$data = '';
								$data = array(
									'productList' => $val['post'],
									'style' => 'style-1',
								);
							 ?>
							 <?php $this->load->view('homepage/frontend/core/productList', $data, FALSE); ?>
						</div> <!-- .panel-body -->
						<?php } ?>
					</div>
				</section>
				<?php } ?>
			<?php } ?>
		</section> <!-- .homepage-product -->
</div><!-- .page-body -->
