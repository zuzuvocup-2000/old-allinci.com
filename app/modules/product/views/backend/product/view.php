
<div id="page-wrapper" class="gray-bg dashbard-1">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Quản lý sản phẩm</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Quản lý sản phẩm</strong></li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Danh sách sản phẩm</h5>
						<div class="ibox-tools">
							<a class="collapse-link">
								<i class="fa fa-chevron-up"></i>
							</a>
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
								<i class="fa fa-cog"></i>
							</a>
							<ul class="dropdown-menu dropdown-product">
								<li><a type="button" class="ajax_delete_product_all" data-title="Lưu ý: Khi bạn thực hiện thao tác này, toàn bộ dữ liệu sẽ không thể khôi phục được. Hãy chắc chắn rằng bạn muốn thực hiện chức năng này!" data-module="product">Xóa tất cả</a>
								</li>
								<li>
									<a data-toggle="modal" data-target="#update_catalogue"> Thêm danh mục phụ cho SP</a>
								</li>
								<li>
									<a data-toggle="modal" data-target="#update_attr"> Tạo thuộc tính cho SP</a>
								</li>
							</ul>
							<a class="close-link">
								<i class="fa fa-times"></i>
							</a>
						</div>
					</div>
					<div class="ibox-content" style="position:relative;">
						<div>
							<div class="block_filter">
								<div class="ibox" style="border: none !important; margin-bottom: 0px">
					                <div class="ibox-title">
					                	<div class="uk-flex uk-flex-middle uk-flex-space-between">
						                	<div class="uk-flex uk-flex-middle mb10">
												<?php echo form_dropdown('perpage', $this->configbie->data('perpage'), set_value('perpage',$this->input->get('perpage')) ,'class="form-control input-sm perpage filter m-r"  data-url="'.site_url('product/backend/product/view').'"'); ?>
											</div>
											<div class="toolbox">
												<div class="uk-flex uk-flex-middle uk-flex-space-between">
													<button type="button" id="update_price_product" class="btn btn-success btn-mb0 m-r">
														<i class="fa fa-upload"></i> Cập nhật
													</button>
													<div class="uk-button">
														<a href="<?php echo site_url('product/backend/product/create'); ?>" class="btn btn-danger btn-sm"><i class="fa fa-plus"></i> Thêm sản phẩm mới</a>
													</div>
												</div>
											</div>
						                </div>
					               	</div>
									<div class="title-filter uk-flex uk-flex-middle uk-flex-space-between">
										<h3><a class="full-search">Tìm kiếm nâng cao</a></h3>
									</div>

									<div class="uk-flex mb10">
										<div class="col-sm-3  p-l-none">
											<?php echo form_dropdown('catalogueid', $this->nestedsetbie->dropdown(), set_value('catalogueid',$this->input->get('catalogueid')) ,'class="form-control input-sm select3 filter catalogueid" '); ?>
										</div>
										<div class="col-sm-3">
											<?php echo form_dropdown('catalogue[]', '', (isset($catalogue)?$catalogue:NULL), 'class="form-control selectMultipe  filter" multiple="multiple" data-title="Nhập 2 kí tự để tìm nhóm danh mục.."  style="width: 100%;"'); ?>
										</div>
										<div class="col-sm-3">
											<?php echo form_dropdown('brandid', dropdown(array(
												'text'=>'Chọn thương hiệu',
												'select'=>'id, title',
												'table'=>'product_brand',
												'field'=>'id',
												'value'=>'title',
											)), set_value('brandid'), 'class="form-control m-b select3 filter"');?>
										</div>
										<div class="col-sm-3  p-r-none">
											<form class="uk-form" id="search">
												<input type="search" name="keyword"  class="keyword form-control input-sm filter" placeholder="Nhập từ khóa tìm kiếm theo tên..." autocomplete="off" value="<?php echo $this->input->get('keyword'); ?>" >
											</form>
										</div>
									</div>
									<div class="filter-more hidden">
										<div class="uk-flex mb10" style="position: relative;">
											<div class="col-sm-3  p-l-none">
												<?php echo form_dropdown('publish', array('-1'=>'Chọn hiển thị',0=>'Hiển thị trên website',1=>'Không thị trên website'), set_value('catalogueid',$this->input->get('catalogueid')) ,'class="form-control select3 input-sm filter catalogueid" '); ?>
											</div>

											<div class="col-sm-3">
												<select name="tag[]" data-json="" data-condition="" class="form-control selectMultipe filter" multiple="multiple" data-title="Nhập 2 kí tự để tìm kiếm theo tag.." data-module="tag"  style="width:100%"></select>
											</div>
											<div class="col-sm-3" id ="filter_price">
												<div class="form-control" style="width:100%">
													<span>Nhập khoảng giá</span>
												</div>
												<div>
													<div class="input-daterange input-group">
												        <input type="text" class="input-sm form-control int filter" name="start_price" value="0">
												        <span class="input-group-addon"> <i class="fa fa-arrow-right" aria-hidden="true"></i></span>
												        <input type="text" class="input-sm form-control int filter" name="end_price" value="0">
												    </div>
												</div>
											</div>

											<div class="col-sm-3 p-r-none">
												
											</div>
											
										</div>
										<div class="uk-flex mb10">
											<div class="col-sm-12 p-l-none p-r-none">
												<div id ="choose_attr">
													<div class="form-control" style="width:100%">
														<span>Chọn thuộc tính</span>
													</div>
													<input type="text" class="hidden filter" name="attr" value="">
													<ul class="list_attr_catalogue" style="display: none">
													<?php if(isset($attribute_catalogue) && check_array($attribute_catalogue)){ ?>
														<?php foreach($attribute_catalogue as $key => $val){ ?>
												        	<li class="catalogue m-b-xs" data-keyword = <?php echo $val['keyword_cata'] ?>>
																<div class="m-l-sm m-b-xs" style="color:#2c3e50"><b><?php echo $key ?></b></div>
																<div class="row no-margins" >
																	<?php if(check_array($val)){ ?>
																	<?php foreach($val as $sub => $subs){ ?>
																		<?php if($sub != 'keyword_cata'){ ?>
																		<div class="col-sm-3">
																			<div class="uk-flex uk-flex-middle m-b-xs attr">
																				<input  class="checkbox-item filter" type="checkbox" name="attr[]" value="<?php echo $sub ?>">
																				<label for="" class="label-checkboxitem m-r"></label>
																				<?php echo $subs ?>
																			</div>
																		</div>
																	<?php }}} ?>
																</div>
															</li>
												        <?php } ?>
											        <?php } ?>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
			                </div>
							
			

							<div class="uk-flex uk-flex-middle uk-flex-space-between">
								<div class="text-small mb10">Hiển thị từ <?php echo $from; ?> đến <?php echo $to ?> trên tổng số <?php echo $config['total_rows']; ?> bản ghi</div>
								<div class="text-small text-danger">*Sắp xếp Vị trí hiển thị theo quy tắc: Số lớn hơn được ưu tiên hiển thị trước. </div>
							</div>
							<table class="table table-striped table-bordered table-hover dataTables-example" >
								<thead>
									<tr>
										<th style="width:30px;">
											<input type="checkbox" id="checkbox-all">
											<label for="check-all" class="labelCheckAll"></label>
										</th>
										<th>Tiêu đề</th>
										<!-- <th style="width:85px;" class="text-center">Tồn đầu kì</th>
										<th style="width:85px;" class="text-center">Tồn cuối kì</th> -->
										<th style="width:85px;" class="text-center">Giá bán</th>
										<th style="width:67px;" class="text-center">Vị trí</th>
										<th style="width:100px;" class="text-center">Người tạo</th>
										<th style="width:81px;" class="tb_ishome">Trang chủ</th>
										<th style="width:81px;" class="tb_highlight">Nổi bật</th>
										<th style="width:81px;" class="tb_status">Trạng thái</th>
										<th style="width:136px;" class="text-center">Thao tác</th>
									</tr>
								</thead>
								<tbody id="ajax-content">
									<?php if(isset($listData) && is_array($listData) && count($listData)){ ?>
										<?php foreach($listData as $key => $val){ ?>
										<?php 
											$image = getthumb($val['image']);
											$_catalogue_list = '';
											$catalogue = json_decode($val['catalogue'], TRUE);
											if(isset($catalogue) && is_array($catalogue) && count($catalogue)){
												$_catalogue_list = $this->Autoload_Model->_get_where(array(
													'select' => 'id, title, slug, canonical',
													'table' => 'product_catalogue',
													'where_in' => json_decode($val['catalogue'], TRUE),
													'where_in_field' => 'id',
												), TRUE);
											}
										?>
											<tr class="gradeX" id="post-<?php echo $val['id']; ?>">
												<td>
													<input type="checkbox" name="checkbox[]" value="<?php echo $val['id']; ?>" data-router="<?php echo $val['canonical']; ?>" class="checkbox-item">
													<label for="" class="label-checkboxitem"></label>
												</td>
												<td>
													<div class="uk-flex uk-flex-middle uk-flex-space-between">
														<div class="uk-flex uk-flex-middle ">
															<div class="image mr5">
																<span class="image-post img-cover"><img src="<?php echo $image; ?>" alt="<?php echo $val['title']; ?>" /></span>
															</div>
															<div class="main-info">
																<div class="title"><a class="maintitle" href="<?php echo site_url('product/backend/product/update/'.$val['id']); ?>" title=""><?php echo $val['title']; ?> (<?php echo $val['viewed'].' lượt xem'; ?>) <?php echo ($val['version']>0)?"(có ".$val['version']." phiên bản)":'' ?></a></div>
																<div class="catalogue" style="font-size:10px">
																	<span style="color:#f00000;">Nhóm hiển thị: </span>
																	<a class="" style="color:#333;" href="<?php echo site_url('article/backend/article/view?catalogueid='.$val['catalogueid']); ?>" title=""><?php echo $val['catalogue_title']; ?></a><?php echo (isset($_catalogue_list) && is_array($_catalogue_list) && count($_catalogue_list) ) ? ' ,' :''; ?>
																	<?php if(isset($_catalogue_list) && is_array($_catalogue_list) && count($_catalogue_list)){ foreach($_catalogue_list as $keyCat => $valCat){ ?>
																	<a style="color:#333;" class="" href="<?php echo site_url('article/backend/article/view?catalogueid='.$valCat['id']); ?>" title=""><?php echo $valCat['title']; ?></a> <?php echo ($keyCat + 1 < count($_catalogue_list)) ? ', ' : ''; ?>
																	<?php }} ?>
																</div>
															</div>
														</div>
														<div>
															<a target="_blank" href="<?php echo site_url($val['canonical']); ?>" ><i class="fa fa-link" aria-hidden="true"></i></a>
														</div>
													</div>
												</td>
												<?php /*

												<td class="text-right"><?php echo $val['quantity_dau_ki']; ?></td>
												<td class="text-right"><?php echo $val['quantity_cuoi_ki']; ?></td>
												*/ ?>
												<td class="text-right price">
													<?php 
														if($val['price_contact'] == 1 ){
															echo '<span>Giá liên hệ</span>';
														}else{
															$price = (!empty($val['price_sale']))? $val['price_sale'] : $val['price'];
															$field =  (!empty($val['price_sale']))? 'price_sale' : 'price';
															if(!empty($val['price_sale'])){
																echo '<i class="fa fa-tag m-r-xs" aria-hidden="true"></i>';
															}
															echo '<span>'.$price.'</span>';
														    echo form_input('price',$price , 'data-id="'.$val['id'].'" data-field="'.$field.'"  class="form-control" style="text-align:right; padding:6px 3px; display:none"');
														}

													?>
												</td>
												
												<td>
													<?php echo form_input('order['.$val['id'].']', $val['order'], 'data-module="product" data-id="'.$val['id'].'"  class="form-control sort-order" placeholder="Vị trí" style="width:50px;text-align:right;"');?>
												</td>
												<td class="text-center"><?php echo $val['user_created']; ?></td>
												<td class="tb_ishome">
													<div class="switch">
														<div class="onoffswitch">
															<input type="checkbox" <?php echo ($val['ishome'] == 1) ? 'checked=""' : ''; ?> class="onoffswitch-checkbox ishome" data-id="<?php echo $val['id']; ?>" id="ishome-<?php echo $val['id']; ?>">
															<label class="onoffswitch-label" for="ishome-<?php echo $val['id']; ?>">
																<span class="onoffswitch-inner"></span>
																<span class="onoffswitch-switch"></span>
															</label>
														</div>
													</div>
												</td>
												<td class="tb_highlight">
													<div class="switch">
														<div class="onoffswitch">
															<input type="checkbox" <?php echo ($val['highlight'] == 1) ? 'checked=""' : ''; ?> class="onoffswitch-checkbox highlight" data-id="<?php echo $val['id']; ?>" id="highlight-<?php echo $val['id']; ?>">
															<label class="onoffswitch-label" for="highlight-<?php echo $val['id']; ?>">
																<span class="onoffswitch-inner"></span>
																<span class="onoffswitch-switch"></span>
															</label>
														</div>
													</div>
												</td>
												<td class="tb_status">
													<div class="switch">
														<div class="onoffswitch">
															<input type="checkbox" <?php echo ($val['publish'] == 0) ? 'checked=""' : ''; ?> class="onoffswitch-checkbox publish" data-id="<?php echo $val['id']; ?>" id="publish-<?php echo $val['id']; ?>">
															<label class="onoffswitch-label" for="publish-<?php echo $val['id']; ?>">
																<span class="onoffswitch-inner"></span>
																<span class="onoffswitch-switch"></span>
															</label>
														</div>
													</div>
												</td>
												<td class="text-center">
													<a type="button" href="<?php echo site_url('product/backend/product/update/'.$val['id'].'?page=1') ?>" class="btn btn-primary"><i class="fa fa-edit"></i></a>
													<a type="button" class="btn btn-danger ajax_delete_product" data-title="Lưu ý: Dữ liệu sẽ không thể khôi phục. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!" data-router="<?php echo $val['canonical']; ?>" data-id="<?php echo $val['id'] ?>" data-catalogueid="<?php echo $val['catalogueid'] ?>" data-module="product"><i class="fa fa-trash"></i></a>

													<a type="button" href="<?php echo site_url('product/backend/product/duplicate/'.$val['id'].'?page=1') ?>" class="btn btn-info"><i class="fa fa-files-o" aria-hidden="true"></i></a>
												</td>
											</tr>
										<?php }}else{ ?>
											<tr>
												<td colspan="100"><small class="text-danger">Không có dữ liệu phù hợp</small></td>
											</tr>
										<?php } ?>
								</tbody>
							</table>
						</div>
						<div id="pagination">
							<?php echo (isset($PaginationList)) ? $PaginationList : ''; ?>
						</div>
						<div class="loader"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view('dashboard/backend/common/footer'); ?>
</div>
<div class="modal inmodal fade" id="update_catalogue" tabindex="-1" role="dialog"  aria-hidden="true">
	<form class="" method="" action="" id="form_update_catalogue" >
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Thêm mới nhóm danh mục phụ</h4>
				</div>
				<div class="modal-body p-md">
					<div class="row">
						<div class="box-body error hidden">
							<div class="alert alert-danger"></div>
						</div><!-- /.box-body -->
					</div>
					
					<div class="form-group">
						<div class="row">
						<div class="col-md-4">
							<span ><b>Nhóm danh mục phụ</b> </span>
						</div>
						<div class="col-md-8">
							<?php echo form_dropdown('catalogue', 
								dropdown(array(
									'text'=>'---Chọn nhóm thuộc tính---',
									'select'=>'id, title',
									'table'=>'product_catalogue',
								)),
								set_value('catalogue',$this->input->get('catalogue')) ,'class="form-control " style="width:100% '); ?>
						</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Thêm mới</button>
				</div>
			</div>
		</div>
	</form>
</div>
<div class="modal inmodal fade" id="update_attr" tabindex="-1" role="dialog"  aria-hidden="true">
	<form class="" method="" action="" id="form_update_attr" >
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Thêm mới thuộc tính</h4>
				</div>
				<div class="modal-body p-md">
					<div class="row">
						<div class="box-body error hidden">
							<div class="alert alert-danger"></div>
						</div><!-- /.box-body -->
					</div>
					
					<div class="form-group">
						<div class="row">
							<div class="col-md-4">
								<span ><b>Nhóm thuộc tính</b> </span>
							</div>
							<div class="col-md-8">

								<?php echo form_dropdown('attribute_catalogue', 
									dropdown(array(
										'text'=>'---Chọn nhóm thuộc tính---',
										'select'=>'id, title',
										'table'=>'attribute_catalogue',
									)),
									set_value('attribute_catalogue',$this->input->get('attribute_catalogue')) ,'class="form-control " style="width:100%"'); ?>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-4">
								<span ><b>Thuộc tính</b> </span>
							</div>
							<div class="col-md-8 attribute">
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Thêm mới</button>
				</div>
			</div>
		</div>
	</form>
</div>