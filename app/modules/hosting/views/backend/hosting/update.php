<div id="page-wrapper" class="gray-bg dashbard-1">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Thêm mới hosting</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Thêm mới hosting</strong></li>
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
							Một số thông tin cơ bản của người sử dụng.
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
											<span>Tên gói hosting <b class="text-danger">(*)</b></span>
										</label>
										<?php echo form_input('title', htmlspecialchars_decode(html_entity_decode(set_value('title', $detailhosting['title']))), 'class="form-control " placeholder="" autocomplete="off"');?>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Giá tiền <b class="text-danger">(*)</b></span>
										</label>
										<?php echo form_input('price', set_value('price', addCommas($detailhosting['price'])), 'class="form-control int" placeholder="" autocomplete="off"');?>
									</div>
								</div>
							</div>
							<div class="row mb15">
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Dung lượng lưu trữ <b class="text-danger">(*)</b></span>
										</label>
										<?php echo form_input('capacity', set_value('capacity', $detailhosting['capacity']), 'class="form-control " placeholder="" autocomplete="off"');?>
									</div>
								</div>
								<?php 
									$dropdown = dropdown(array(
										'select' => 'id, title',
										'table' => 'hosting_catalogue',
										'order_by' => 'id asc',
										'text' => 'Chọn Nhóm hosting',
										'field' => 'id',
										'value' => 'title'
									));
								?>
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Nhóm hosting <b class="text-danger">(*)</b></span>
										</label>
										<?php echo form_dropdown('catalogueid', $dropdown, set_value('catalogueid', $detailhosting['catalogueid']), 'class="form-control m-b city"');?>
									</div>
								</div>
							</div>
							<div class="row mb15">
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Băng thông <b class="text-danger"></b></span>
										</label>
										<?php echo form_input('bandwidth', set_value('bandwidth', $detailhosting['bandwidth']), 'class="form-control " placeholder="" autocomplete="off"');?>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Fpt account <b class="text-danger">(*)</b></span>
										</label>
										<?php echo form_input('FPT_account', set_value('FPT_account', $detailhosting['FPT_account']), 'class="form-control " placeholder="" autocomplete="off"');?>
									</div>
								</div>
							</div>
							<div class="row mb15">
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Mysql <b class="text-danger"></b></span>
										</label>
										<?php echo form_input('mysql', set_value('mysql', $detailhosting['mysql']), 'class="form-control " placeholder="" autocomplete="off"');?>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Domain <b class="text-danger">(*)</b></span>
										</label>
										<?php echo form_input('domain', set_value('domain', $detailhosting['domain']), 'class="form-control " placeholder="" autocomplete="off"');?>
									</div>
								</div>
							</div>
							<div class="row mb15">
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Sub domains <b class="text-danger"></b></span>
										</label>
										<?php echo form_input('sub_domain', set_value('sub_domain', $detailhosting['sub_domain']), 'class="form-control" placeholder="" autocomplete="off"');?>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Addon doamins <b class="text-danger">(*)</b></span>
										</label>
										<?php echo form_input('addon_domain', set_value('addon_domain', $detailhosting['addon_domain']), 'class="form-control " placeholder="" autocomplete="off"');?>
									</div>
								</div>
							</div>
							<div class="row mb15">
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Park domains <b class="text-danger"></b></span>
										</label>
										<?php echo form_input('park_domain', set_value('park_domain', $detailhosting['park_domain']), 'class="form-control" placeholder="" autocomplete="off"');?>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Hợp đồng tối thiểu<b class="text-danger">(*)</b></span>
										</label>
										<?php echo form_input('contract_time', set_value('contract_time', $detailhosting['contract_time']), 'class="form-control " placeholder="" autocomplete="off"');?>
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
			<hr>
		</div>
	</form>
	<?php $this->load->view('dashboard/backend/common/footer'); ?>
</div>
