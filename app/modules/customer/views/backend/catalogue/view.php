<div id="page-wrapper" class="gray-bg dashbard-1">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Quản lý nhóm khách hàng</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Quản lý nhóm khách hàng</strong></li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<form method="post" action="" class="form-horizontal box" >
			<div class="row">
				<div class="col-lg-12">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5>Danh sách nhóm khách hàng</h5>
							<div class="ibox-tools">
								<a class="collapse-link">
									<i class="fa fa-chevron-up"></i>
								</a>
								<a class="dropdown-toggle" data-toggle="dropdown" href="#">
									<i class="fa fa-wrench"></i>
								</a>
								<ul class="dropdown-menu dropdown-user">
									<li><a type="button" class="ajax-group-delete" data-title="Lưu ý: Khi bạn xóa nhóm khách hàng, toàn bộ thành viên trong nhóm này sẽ bị xóa. Trong trường hợp khôi phục lại nhóm khách hàng thì toàn bộ thành viên trong nhóm cũng sẽ không thể khôi phục được!" data-module="customer_catalogue">Xóa tất cả</a>
									</li>
								</ul>
								<a class="close-link">
									<i class="fa fa-times"></i>
								</a>
							</div>
						</div>
						<div class="ibox-content" style="position:relative;">
						
							<div class="uk-flex uk-flex-middle uk-flex-space-between">
								<div class="wrap-select mr5">
									<select class="form-control filter" id="perpage" name="perpage">
										<option value="10">Chọn 10 bản ghi</option>
										<option value="20">Chọn 20 bản ghi</option>
										<option value="30">Chọn 30 bản ghi</option>
										<option value="40">Chọn 40 bản ghi</option>
										<option value="50">Chọn 50 bản ghi</option>
										<option value="60">Chọn 60 bản ghi</option>
									</select>
									</div>
								<div class="uk-flex uk-flex-middle uk-flex-space-between">
									
									<div class="input-group mr5">
										<input type="text" class="form-control filter" id="keyword" name="search" value="" placeholder="Bạn muốn tìm gì?">
									</div>
									<div class="uk-button">
										<a href="<?php echo site_url('customer/backend/catalogue/create');?>" class="btn btn-danger btn-sm"><i class="fa fa-plus"></i> Thêm mới nhóm thành viên</a>
									</div>
								</div>
							</div>
							<div class="table-responsive mt10">
								<table class="table table-striped table-bordered table-hover dataTables-example">
									<thead>
										<tr>
											<th class="text-center">
												<input type="checkbox" id="checkbox-all">
												<label for="check-all" class="labelCheckAll"></label>
											</th>
											<th class="text-center">ID</th>
											<th class="text-left">Tên nhóm</th>
											<th class="text-center">Số thành viên</th>
											<th class="text-center">Người tạo</th>
											<th class="text-center">Khởi tạo</th>
											<th class="text-center">Cập nhật</th>
											<th class="text-center">Thao tác</th>
										</tr>
									</thead>
									
									<tbody id='listCatalogue'>
										
										<?php if(isset($listCatalogue) && is_array($listCatalogue) && count($listCatalogue)){ ?>
											<?php foreach($listCatalogue as $key => $val){?>
											<tr>
												<td class="text-center" style="width: 40px;">
													<input type="checkbox" name="checkbox[]" value="<?php echo $val['id'];?>" class="checkbox-item">
													<label for="" class="label-checkboxitem"></label>
												</td>
												<td class="text-center"><?php echo $val['id'];?></td>
												<td class="text-left"><?php echo $val['title'];?></td>
												<td class="text-center"><?php echo $val['total_customer'];?></td>
												<td class="text-center"><?php echo $val['fullname'];?></td>
												<td class="text-center"><?php echo $val['created'];?></td>
												<td class="text-center"><?php echo $val['updated'];?></td>
												<td class="uk-text-center">
													<a type="button" href="<?php echo site_url('customer/backend/catalogue/update/'.$val['id']);?>" data-customerid="<?php echo $val['id'];?>" class="btn btn-sm btn-edit mr5 btn-primary"><i class="fa fa-edit"></i></a>
													<a type="button" data-title="<?php echo $val['title'];?>" data-name="" data-module="customer_catalogue" data-id="<?php echo $val['id'];?>" class="btn btn-sm btn-trash btn-danger ajax-delete"><i class="fa fa-trash"></i></a>
												</td>
											</tr>
										<?php }} else{?>
												<tr>
													<td colspan="8">
														<small class="text-danger">Không có dữ liệu phù hợp</small>
													</td>
												</tr>
											<?php } ?>
									</tbody>
								</table>
							</div>
							<div id="pagination" class="uk-flex uk-flex-right">
								<?php echo (isset($PaginationList)) ? $PaginationList : ''; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
	<?php $this->load->view('dashboard/backend/common/footer'); ?>
</div>