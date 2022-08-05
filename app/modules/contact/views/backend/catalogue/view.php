<div id="page-wrapper" class="gray-bg dashboard-1">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar');?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-12">
			<h2>Liên hệ</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Nhóm liên hệ</strong></li>
			</ol>
		</div>
	</div>
	<!-- --------------------------- -->
	<div class="wrapper wrapper-content animated fadeInRight">
		<form method="post" action="" class="form-horizontal box">
			<div class="row">
				<div class="col-lg-12">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5>DANH SÁCH NHÓM LIÊN HỆ</h5>
							<div class="ibox-tools">
								<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-wrench"></i></a>
								<ul class="dropdown-menu dropdown-support">
									<li>
										<a type="button" class="ajax-group-delete" data-title="Lưu ý: Khi bạn thực hiện thao tác này, toàn bộ dữ liệu sẽ không thể khôi phục được. Hãy chắc chắn rằng bạn muốn thực hiện chức năng này!" data-module="contact_catalogue">Xóa tất cả</a>
									</li>
								</ul>
								<a class="close-link"><i class="fa fa-times"></i></a>
							</div>
						</div>
						<div class="ibox-content" style="position:relative;">
							<div class="table-responsive">
								<div class=" mb10 uk-flex uk-flex-middle uk-flex-space-between">
									<div class="row-left">
										<?php echo form_dropdown('perpage', $this->configbie->data('perpage'), set_value('perpage') ,'class="form-control input-sm perpage filter"  data-url="'.site_url('contact/backend/catalogue/view').'"'); ?>
									</div>
									<div class="row-right">
										<div class="uk-flex uk-flex-middle uk-flex-space-between">
											<div class="uk-search uk-flex uk-flex-middle mr10">
												<form class="uk-form" id="search">
													<input type="search" name="keyword"  class="keyword form-control input-sm filter" placeholder="Nhập từ khóa tìm kiếm ..." autocomplete="off" value="<?php echo $this->input->get('keyword'); ?>" >
												</form>
											</div>
											<div class="uk-button">
												<a href="<?php echo site_url('contact/backend/catalogue/create'); ?>" class="btn btn-create btn-danger"><i class="fa fa-plus"></i> Thêm mới nhóm liên hệ</a>
											</div>
										</div>
									</div>
								</div>
								<div class="uk-flex uk-flex-middle uk-flex-space-between mb10">
									<div class="text-small">Hiển thị từ <?php echo $from;?> đến <?php echo $to;?> trên tổng số <?php echo $config['total_rows']; ?> bản ghi</div>
									<div class="text-small text-danger">*Sắp xếp Vị trí hiển thị theo quy tắc: Số lớn hơn được ưu tiên hiển thị trước.</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTables-example" >
									<thead>
										<tr>
											<th style="width:40px;" class="text-center">
												<input type="checkbox" id="checkbox-all">
												<label for="check-all" class="labelCheckAll"></label>
											</th>
											<th style="width:40px;" class="text-center">ID</th>
											<th style="">Nhóm liên hệ</th>
											<th style="width:80px;">Số liên hệ</th>
											<th style="width:175px;" class="text-center">Người tạo</th>
											<th style="width:100px;" class="text-center">Ngày tạo</th>
											<th style="width:85px;">Trạng thái</th>
											<th style="width:110px;" class="text-center">Thao tác</th>
										</tr>
									</thead>
									<tbody id="ajax-content">
										<?php if(isset($listCatalogue) && is_array($listCatalogue) && count($listCatalogue)){ ?>
											<?php foreach($listCatalogue as $key => $val){ ?>

												<tr class="gradeX" id="">
													<td class="pd-top text-center" >
														<input type="checkbox" name="checkbox[]" value="<?php echo $val['id']; ?>" class="checkbox-item">
														<label for="" class="label-checkboxitem"></label>
													</td>
													<td class="pd-top text-center"> <?php echo $val['id']; ?></td>
													<td class="pd-top"><a class="maintitle" style="" href="<?php echo site_url('contact/backend/contact/view?catalogueid='.$val['id'].''); ?>" title=""><?php echo $val['title'];?></a></td>
													<td class="pd-top text-center"> <?php echo $val['countContact'] ?></td> 
													<td class="pd-top text-center"> <?php echo $val['user_created'];?></td> 
													<td class="pd-top text-center"><?php echo gettime($val['created'],'d/m/Y'); ?></td> 
													<td class="pd-top status">
														<div class="switch">
															<div class="onoffswitch">
																<input type="checkbox" <?php echo ($val['publish'] == 1) ? 'checked=""' : ''; ?> class="onoffswitch-checkbox publish" data-id="<?php echo $val['id']; ?>" id="publish-<?php echo $val['id']; ?>">
																<label class="onoffswitch-label" for="publish-<?php echo $val['id']; ?>">
																	<span class="onoffswitch-inner"></span>
																	<span class="onoffswitch-switch"></span>
																</label>
															</div>
														</div>
													</td>
													<td class="text-center actions">
														<a type="button" href="<?php echo site_url('contact/backend/catalogue/update/'.$val['id'].''); ?>" class="btn btn-primary btn-update"><i class="fa fa-edit"></i></a>
														<a type="button" class="btn btn-danger btn-delete ajax-delete"  data-id="<?php echo $val['id'];?>" data-title="Lưu ý: Khi bạn xóa nhóm, toàn bộ liên hệ trong nhóm này sẽ bị xóa. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!" data-router="" data-module="contact_catalogue" data-child=""><i class="fa fa-trash"></i></a>
													</td>
												</tr>
											<?php }} else{ ?>
												<tr>
													<td colspan="9"><small class="text-danger">Không có dữ liệu phù hợp</small></td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
								<div id="pagination" class="p-pagination">
									<?php echo (isset($PaginationList))? $PaginationList :'';?>
								</div>
							</div>
						</div>

					</div>
				</div>

				<!-- --------------------------- -->

			</div>
		</form>
		<?php $this->load->view('dashboard/backend/common/footer'); ?>
	</div>