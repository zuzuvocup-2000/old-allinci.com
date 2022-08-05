<div id="page-wrapper" class="gray-bg dashbard-1">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Thêm mới khách hàng</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Thêm mới khách hàng</strong></li>
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
				<div class="col-lg-5">
					<div class="panel-head">
						<h2 class="panel-title">Thông tin chung</h2>
						<div class="panel-description">
							Một số thông tin cơ bản của khách hàng.
						</div>
					</div>
				</div>
				<div class="col-lg-7">
					<div class="ibox m0">
						<div class="ibox-content">
							<div class="row mb15">
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Họ tên <b class="text-danger">(*)</b></span>
										</label>
										<?php echo form_input('fullname', set_value('fullname'), 'class="form-control " placeholder="" autocomplete="off"');?>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Ngày sinh <b class="text-danger"></b></span>
										</label>
										<?php echo form_input('birthday', set_value('birthday'), 'class="form-control datetimepicker" placeholder="" autocomplete="off"');?>
									</div>
								</div>
							</div>
							<div class="row mb15">
								<?php 
									$dropdown = dropdown(array(
										'select' => 'id, title',
										'table' => 'customer_catalogue',
										'order_by' => 'id asc',
										'text' => 'Chọn Nhóm khách hàng',
										'field' => 'id',
										'value' => 'title'
									));
								?>
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Email <b class="text-danger">(*)</b></span>
										</label>
										<?php echo form_input('email', set_value('email'), 'class="form-control " placeholder="" autocomplete="off"');?>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Nhóm khách hàng <b class="text-danger">(*)</b></span>
										</label>
										<?php echo form_dropdown('catalogueid', $dropdown, set_value('catalogueid'), 'class="form-control m-b city"');?>
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
											<div class="i-checks"><label> <input <?php echo ($this->input->post('gender') == 1) ? 'checked' : '' ?> class="popup_gender_1 gender" type="radio" value="1"  name="gender"> <i></i> Nam</label></div>
											<div class="i-checks"><label> <input type="radio" <?php echo ($this->input->post('gender') == 0) ? 'checked' : '' ?> class="popup_gender_0 gender" required value="0" name="gender"> <i></i> Nữ </label></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-lg-5">
					<div class="panel-head">
						<h2 class="panel-title">Địa chỉ</h2>
						<div class="panel-description">
							Các thông tin liên hệ chính với người sử dụng này.
						</div>
					</div>
				</div>
				<div class="col-lg-7">
					<div class="ibox m0">
						<div class="ibox-content">
							<div class="row mb15">
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Địa chỉ</span>
										</label>
										<?php echo form_input('address', set_value('address'), 'class="form-control " placeholder="" autocomplete="off"');?>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Số điện thoại</span>
										</label>
										<?php echo form_input('phone', set_value('phone'), 'class="form-control " placeholder="" autocomplete="off"');?>
									</div>
								</div>
							</div>
							
							<div class="row mb15">
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Tỉnh/Thành Phố</span>
										</label>
										<?php 
											$listCity = getLocation(array(
												'select' => 'name, provinceid',
												'table' => 'vn_province',
												'field' => 'provinceid',
												'text' => 'Chọn Tỉnh/Thành Phố'
											));
										?>
										<?php echo form_dropdown('cityid', $listCity, '', 'class="form-control m-b city"  id="city"');?>
									</div>
								</div>
								<script>
									var cityid = '<?php echo $this->input->post('cityid'); ?>';
									var districtid = '<?php echo $this->input->post('districtid');?>';
									var wardid = '<?php echo $this->input->post('wardid') ?>';
								</script>
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Quận/Huyện</span>
										</label>
										<select name="districtid" id="district" class="form-control m-b location">
											<option value="0">Chọn Quận/Huyện</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row mb15">
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Phường xã</span>
										</label>
										<select name="wardid" id="ward" class="form-control m-b location">
											<option value="0">Chọn Phường/Xã</option>
										</select>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Ghi chú</span>
										</label>
										<?php echo form_input('description', set_value('description'), 'class="form-control " placeholder="" autocomplete="off"');?>
									</div>
								</div>
							</div>
							<div class="toolbox action clearfix">
								<div class="uk-flex uk-flex-middle uk-button pull-right">
									<button class="btn btn-primary btn-sm" name="create" value="create" type="submit">Khởi tạo</button>
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
