<div id="page-wrapper" class="gray-bg dashbard-1">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Quản lý nhóm người dùng</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Quản lý nhóm người dùng</strong></li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Danh sách nhóm người dùng</h5>
						<div class="ibox-tools">
							<a class="collapse-link">
								<i class="fa fa-chevron-up"></i>
							</a>
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
								<i class="fa fa-wrench"></i>
							</a>
							<ul class="dropdown-menu dropdown-user">
								<li><a type="button" class="ajax-recycle-all" data-title="Lưu ý: Khi bạn xóa nhóm người dùng, toàn bộ thành viên trong nhóm này sẽ bị xóa. Trong trường hợp khôi phục lại nhóm người dùng thì toàn bộ thành viên trong nhóm cũng sẽ không thể khôi phục được!" data-module="user_catalogue">Xóa tất cả</a>
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
										<?php echo form_dropdown('perpage', $this->configbie->data('perpage'), set_value('perpage',$this->input->get('perpage')) ,'class="form-control input-sm perpage"  data-url="'.site_url('user/backend/catalogue/view').'"'); ?>
									</div>
								</div>
								<div class="toolbox">
									<div class="uk-flex uk-flex-middle uk-flex-space-between">
										<div class="uk-search uk-flex uk-flex-middle mr10">
											<form class="uk-form" id="search">
												<input type="search" name="keyword"  class="keyword form-control input-sm" placeholder="Nhập từ khóa tìm kiếm ..." value="<?php echo $this->input->get('keyword'); ?>" >
											</form>
										</div>
										<div class="uk-button">
											<a href="<?php echo site_url('user/backend/catalogue/create'); ?>" class="btn btn-danger btn-sm"><i class="fa fa-plus"></i> Thêm nhóm thành viên</a>
										</div>
									</div>
								</div>
							</div>
							<div class="text-small mb10">Hiển thị từ <?php echo $from; ?> đến <?php echo $to ?> trên tổng số <?php echo $config['total_rows']; ?> bản ghi</div>
							<table class="table table-striped table-bordered table-hover dataTables-example" >
								<thead>
									<tr>
										<th>
											<input type="checkbox" id="checkbox-all">
											<label for="check-all" class="labelCheckAll"></label>
										</th>
										<th>ID</th>
										<th style="width:175px;">Tiêu đề</th>
										<th class="text-center">Số thành viên</th>
										<th class="text-center">Khởi tạo</th>
										<th class="text-center">Thao tác</th>
									</tr>
								</thead>
								<tbody id="ajax-content">
									<?php if(isset($listCatalogue) && is_array($listCatalogue) && count($listCatalogue)){ ?>
										<?php foreach($listCatalogue as $key => $val){ ?>
											<tr class="gradeX">
												<td>
													<input type="checkbox" name="checkbox[]" value="<?php echo $val['id']; ?>" class="checkbox-item">
													<label for="" class="label-checkboxitem"></label>
												</td>
												<td><?php echo $val['id']; ?></td>
												<td><?php echo $val['title']; ?></td>
												<td class="text-center"><?php echo $val['total_user']; ?></td>
												<td class="text-center"><?php echo gettime($val['created'],'H:i d/m/Y'); ?></td>
												
												<td class="text-center">
													<a type="button" href="<?php echo site_url('user/backend/catalogue/update/'.$val['id'].'') ?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
													<a type="button" class="btn btn-sm btn-danger ajax-delete" data-title="Lưu ý: Khi bạn xóa nhóm người dùng, toàn bộ thành viên trong nhóm này sẽ bị xóa. Trong trường hợp khôi phục lại nhóm người dùng thì toàn bộ thành viên trong nhóm cũng sẽ không thể khôi phục được!" data-id="<?php echo $val['id'] ?>" data-module="user_catalogue"><i class="fa fa-trash"></i></a>
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