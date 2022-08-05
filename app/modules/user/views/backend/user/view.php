<div id="page-wrapper" class="gray-bg dashbard-1">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Quản lý người dùng</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Quản lý người dùng</strong></li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row">
			<div class="col-sm-8" style="padding-left:0;padding-right:0;">
				<div class="ibox">
					<div class="ibox-title">
						<h5>Danh sách người dùng</h5>
						<div class="ibox-tools">
							<a class="collapse-link">
								<i class="fa fa-chevron-up"></i>
							</a>
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
								<i class="fa fa-wrench"></i>
							</a>
							<ul class="dropdown-menu dropdown-user">
								<li><a type="button" class="ajax-recycle-all" data-title="Lưu ý: Số thành viên bị xóa sẽ không thể truy cập vào hệ thống quản trị được nữa!" data-module="user">Xóa tất cả</a>
								</li>
							</ul>
							<a class="close-link">
								<i class="fa fa-times"></i>
							</a>
						</div>
					</div>
					
					
					<div class="ibox-content">
						<div class="uk-flex uk-flex-middle uk-flex-space-between">	
							<div class="uk-button">
								<a href="<?php echo site_url('user/backend/user/create'); ?>" class="btn btn-danger btn-sm"><i class="fa fa-plus"></i> Thêm tài khoản</a>
							</div>
							<form class="user-filter" method="get" action="">
								<div class="uk-flex uk-flex-middle uk-flex-space-between">
									<div class="input-group">
										<input type="text" style="width:250px;" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" placeholder="Nhập Email, số điện thoại, tài khoản ... " class="input keyword form-control">
									</div>
								</div>
							</form>
						</div>
						<div class="clients-list">
							<ul class="nav nav-tabs">
								<li class="active"><a data-toggle="tab" href="#tab-1"><i class="fa fa-user"></i> Danh sách người dùng</a></li>
							</ul>
							<div class="tab-content" id="userData">
								<div id="tab-1" class="tab-pane active">
									<div class="full-height-scroll">
										<div class="table-responsive">
											<table class="table table-striped table-hover">
												<thead>
													<tr>
														<th>
															<input type="checkbox" id="checkbox-all">
															<label for="check-all" class="labelCheckAll"></label>
														</th>
														<th class="text-left">Họ tên</th>
														<th class="text-left">Tài khoản</th>
														<th class="text-left">Email</th>
														<th class="text-center">Thao tác</th>
													</tr>
												</thead>
												<tbody id="listUser">
													<?php if(isset($listUser) && is_array($listUser) && count($listUser)){ ?>
														<?php foreach($listUser as $key => $val){ ?>
															<tr style="cursor:pointer;" class="choose" data-info="<?php echo base64_encode(json_encode($val)); ?>" >
																<td>
																	<input type="checkbox" name="checkbox[]" value="<?php echo $val['id']; ?>" class="checkbox-item">
																	<div for="" class="label-checkboxitem"></div>
																</td>
																<td><a data-toggle="tab" href="#contact-1" class="client-link"><?php echo $val['fullname']; ?></a></td>
																<td> <?php echo $val['account']; ?></td>
																<td class="client-email"> <i class="fa fa-envelope" style="margin-right:5px;"> </i><?php echo (!empty($val['email'])) ? $val['email'] : '-'; ?></td>
																<td class="client-status" style="text-align:center;">
																	<a type="button" href="<?php echo site_url('user/backend/user/update/'.$val['id'].''); ?>"   class="btn btn-sm btn-primary btn-update"><i class="fa fa-edit"></i></a>
																	<a type="button" class="btn btn-sm btn-danger ajax-delete" data-title="Lưu ý: Khi bạn xóa thành viên, người này sẽ không thể truy cập vào hệ thống quản trị được nữa." data-id="<?php echo $val['id'] ?>" data-module="user"><i class="fa fa-trash"></i></a>
																</td>
															</tr>
														<?php }}else{ ?>
															<tr>
																<td colspan="5">
																	<small class="text-danger">Không có dữ liệu phù hợp</small>
																</td>
															</tr>
														<?php } ?>
													</tbody>
												</table>
											</div>
											<div id="pagination">
												<?php echo (isset($PaginationList)) ? $PaginationList : ''; ?>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-4" style="padding-right:0;">
					<div class="ibox ">
						<div class="ibox-content">
							<div class="tab-content">
								<div id="contact-1" class="tab-pane active">
									<div class="row m-b-lg">
										<div class="col-lg-4 text-center">
											<div class="m-b-sm img-cover">
												<img alt="image" class="img-circle" id="image" src="template/not-found.png" style="width: 100px;height:100px;">
											</div>
										</div>
										<div class="col-lg-8">
											<h2 class="fullname">Noname</h2>
											<p class="group-title">-</p>
											<div class="uk-button">
												<a class="btn btn-warning btn-sm p-reset" data-userid="0"><i class="fa fa-key"></i> Reset mật khẩu</a>
											</div>
										</div>
									</div>
									<div class="client-detail">
										<div class="full-height-scroll">
											<strong>Thông tin cá nhân</strong>
											<ul class="list-group clear-list">
												<li class="list-group-item fist-item">
													<span class="pull-right fullname"> - </span>
													Họ tên:
												</li>
												<li class="list-group-item">
													<span class="pull-right phone"> - </span>
													Số điện thoại:
												</li>
												<li class="list-group-item">
													<span class="pull-right email"> - </span>
													Email:
												</li>
												<li class="list-group-item">
													<span class="pull-right address"> - </span>
													<span class="">Địa chỉ:</span>
												</li>
												<li class="list-group-item">
													<span class="pull-right last-login"> -</span>
													Đăng nhập lần cuối:
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php $this->load->view('dashboard/backend/common/footer'); ?>
	</div>
	