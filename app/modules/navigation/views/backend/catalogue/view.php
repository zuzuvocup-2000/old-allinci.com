<div id="page-wrapper" class="gray-bg dashbard-1 fix-wrapper">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Thêm mới thành viên</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Thêm mới thành viên</strong></li>
			</ol>
		</div>
	</div>
	<form method="post" action="" class="form-horizontal box" >
		<div class="wrapper wrapper-content animated fadeInRight">
			<div class="row">
				<div class="box-body">
					<?php $error = validation_errors(); echo !empty($error)?'<div class="alert alert-danger">'.$error.'</div>':'';?>
				</div><!-- /.box-body -->
			</div>
			<div class="row">
				<div class="col-lg-4">
					<div class="panel-head">
						<h2 class="panel-title">Danh sách menu</h2>
						<div class="panel-description">
							<p>Danh sách menu giúp khách hàng dễ dàng xem trang web của bạn. Bạn có thể thêm menu vào giao diện trong phần thiết lập giao diện</p>
							<p>Bạn có thể sử dụng biểu tượng  để sắp xếp thứ tự menu.</p>
							<p>
								Menu còn được dùng để
								Tạo "menu xổ xuống" cho trang web của bạn.
							</p>
							<div class="uk-flex uk-flex-middle">
								<a href="<?php echo site_url('navigation/backend/navigation/create'); ?>" title="" class="btn btn-default block full-width m-b" style="margin-right:10px;">Thêm Menu</a>
								<a href="<?php echo site_url('navigation/backend/catalogue/create'); ?>" title="" class="btn btn-default block full-width m-b">Thêm Vị trí</a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-8">
					<div class="ibox m0">
						<div class="ibox-title" style="padding: 9px 15px 0px;">
							<h5 style="margin:0;">Thông tin cơ bản</h5>
						</div>
						<div class="ibox-content">
							<div class="row mb15">
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Tài khoản <b class="text-danger">(*)</b></span>
										</label>
										<?php echo form_input('account', htmlspecialchars_decode(html_entity_decode(set_value('account'))), 'class="form-control " placeholder="" autocomplete="off"');?>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Họ tên <b class="text-danger">(*)</b></span>
										</label>
										<?php echo form_input('fullname', set_value('fullname'), 'class="form-control " placeholder="" autocomplete="off"');?>
									</div>
								</div>
							</div>
							<div class="row mb15">
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Email <b class="text-danger">(*)</b></span>
										</label>
										<?php echo form_input('email', set_value('email'), 'class="form-control " placeholder="" autocomplete="off"');?>
									</div>
								</div>
								<?php 
									$dropdown = dropdown(array(
										'select' => 'id, title',
										'table' => 'user_catalogue',
										'order_by' => 'id asc',
										'text' => 'Chọn Nhóm Thành Viên',
										'field' => 'id',
										'value' => 'title'
									));
								?>
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Nhóm Thành viên <b class="text-danger">(*)</b></span>
										</label>
										<?php echo form_dropdown('catalogueid', $dropdown, set_value('catalogueid'), 'class="form-control m-b city"');?>
									</div>
								</div>
							</div>
							<div class="row mb15">
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Ngày sinh <b class="text-danger"></b></span>
										</label>
										<?php echo form_input('birthday', set_value('birthday'), 'class="form-control datetimepicker" placeholder="" autocomplete="off"');?>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Mật khẩu <b class="text-danger">(*)</b></span>
										</label>
										<?php echo form_password('password', set_value(''), 'class="form-control " placeholder="" autocomplete="off"');?>
									</div>
								</div>
							</div>
							<div class="row mb15">
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Giới tính <b class="text-danger"></b></span>
										</label>
										<div class="uk-flex uk-flex-middle">
											<div class="i-checks mr30"><label> <input <?php echo ($this->input->post('gender') == 1) ? 'checked' : '' ?> class="popup_gender_1 gender" type="radio" value="1"  name="gender"> <i></i> Nam</label></div>
											<div class="i-checks"><label> <input type="radio" <?php echo ($this->input->post('gender') == 0) ? 'checked' : '' ?> class="popup_gender_0 gender" required value="0" name="gender"> <i></i> Nữ </label></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</form>
	<?php $this->load->view('dashboard/backend/common/footer'); ?>
</div>
