<div id="page-wrapper" class="gray-bg dashbard-1">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Quản lý thương hiệu</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Quản lý thương hiệu</strong></li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Danh sách thương hiệu</h5>
						<div class="ibox-tools">
							<a class="collapse-link">
								<i class="fa fa-chevron-up"></i>
							</a>
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
								<i class="fa fa-cog"></i>
							</a>
							<ul class="dropdown-menu dropdown-article">
								<li><a type="button" class="ajax-delete-all" data-title="Lưu ý: Khi bạn thực hiện thao tác này, toàn bộ dữ liệu sẽ không thể khôi phục được. Hãy chắc chắn rằng bạn muốn thực hiện chức năng này!" data-module="product_brand">Xóa tất cả</a>
								</li>
							</ul>
							<a class="close-link">
								<i class="fa fa-times"></i>
							</a>
						</div>
					</div>
					<div class="ibox-content" style="position:relative;">
						<div class="table-responsive">
							<div class="uk-flex uk-flex-middle uk-flex-space-between">
								<div class="perpage">
									<div class="uk-flex uk-flex-middle mb10">
										<?php echo form_dropdown('perpage', $this->configbie->data('perpage'), set_value('perpage',$this->input->get('perpage')) ,'class="form-control input-sm perpage filter"  data-url="'.site_url('product/backend/brand/view').'"'); ?>
									</div>
								</div>
								<div class="toolbox">
									<div class="uk-flex uk-flex-middle uk-flex-space-between">
										<div class="uk-search uk-flex uk-flex-middle mr10">
											<form class="uk-form" id="search">
												<input type="search" name="keyword"  class="keyword form-control input-sm filter" placeholder="Nhập từ khóa tìm kiếm ..." autocomplete="off" value="<?php echo $this->input->get('keyword'); ?>" >
											</form>
										</div>
										<div class="uk-button">
											<a href="<?php echo site_url('product/backend/brand/create'); ?>" class="btn btn-danger btn-sm"><i class="fa fa-plus"></i> Thêm thương hiệu mới</a>
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
										<th style="width:40px;">
											<input type="checkbox" id="checkbox-all">
											<label for="check-all" class="labelCheckAll"></label>
										</th>
										<th style="width:485px;">Tiêu đề</th>
										<th style="width:67px;" class="text-center">Vị trí</th>
										<th style="width:125px;">Người tạo</th>
										<th style="width:78px;">Ngày tạo</th>
										<th style="width:70px;">Trạng thái</th>
										<th style="width:84px;" class="text-center">Thao tác</th>
									</tr>
								</thead>
								<tbody id="ajax-content">
									<?php if(isset($listBrand) && is_array($listBrand) && count($listBrand)){ ?>
										<?php foreach($listBrand as $key => $val){ ?>
											<tr class="gradeX" id="post-<?php echo $val['id']; ?>">
												<td>
													<input type="checkbox" name="checkbox[]" value="<?php echo $val['id']; ?>" class="checkbox-item">
													<label for="" class="label-checkboxitem"></label>
												</td>
												<td>
													<div class="uk-flex uk-flex-middle">
														<div class="image mr5">
															<span class="image-post img-scale">
																<img src="<?php echo getthumb($val['image']); ?>" alt="<?php echo $val['title']; ?>" />
															</span>
														</div>
														<div class="main-info">
															<div class="title"><a class="maintitle" href="<?php echo site_url('product/backend/brand/update/'.$val['id']); ?>" title=""><?php echo $val['title']; ?> (<?php echo $val['viewed']; ?>)</a></div>
														</div>
													</div>
												</td>
												<td>
													<?php echo form_input('order['.$val['id'].']', $val['order'], 'data-module="product_brand" data-id="'.$val['id'].'"  class="form-control sort-order" placeholder="Vị trí" style="width:50px;text-align:right;"');?>
												</td>
												<td><?php echo $val['user_created']; ?></td>
												<td><?php echo gettime($val['created'],'d/m/Y'); ?></td>
												<td>
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
													<a type="button" href="<?php echo site_url('product/backend/brand/update/'.$val['id'].'') ?>" class="btn btn-primary"><i class="fa fa-edit"></i></a>
													<a type="button" class="btn btn-danger ajax-delete" data-title="Lưu ý: Dữ liệu sẽ không thể khôi phục. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!" data-router="<?php echo $val['canonical']; ?>" data-id="<?php echo $val['id'] ?>" data-module="product_brand"><i class="fa fa-trash"></i></a>
												</td>
											</tr>
										<?php }}else{ ?>
											<tr>
												<td colspan="9"><small class="text-danger">Không có dữ liệu phù hợp</small></td>
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