<div id="page-wrapper" class="gray-bg dashbard-1">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Cập nhật mới kho</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Cập nhật mới kho</strong></li>
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
											<span>Tên kho <b class="text-danger">(*)</b></span>
										</label>
										<?php echo form_input('title', htmlspecialchars_decode(html_entity_decode(set_value('title', $detailstock['title']))), 'class="form-control" placeholder="Kho Hà Nam" autocomplete="off"');?>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Địa chỉ</span>
										</label>
										<?php echo form_input('address', set_value('address', $detailstock['address']), 'class="form-control" placeholder="119 Hài Bà" autocomplete="off"');?>
									</div>
								</div>
							</div>
							<div class="row mb15">
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Số điện thoại</span>
										</label>
										<?php echo form_input('phone', set_value('phone', $detailstock['phone']), 'class="form-control " placeholder="0999888776" autocomplete="off"');?>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Email </span>
										</label>
										<?php echo form_input('email', set_value('email', $detailstock['email']), 'class="form-control " placeholder="haiba_kho@gmail.com" autocomplete="off"');?>
									</div>
								</div>
							</div>
							<div class="row mb15">
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Nhân viên phụ trách </span>
										</label>
										<?php echo form_dropdown('userid_charge', dropdown(array(
	                                        'text'=>'Chọn nhân viên',
	                                        'select'=>'id, fullname',
	                                        'table'=>'user',
	                                        'field'=>'id',
	                                        'value'=>'fullname',
	                                        'order_by'=>'id DESC'
	                                    )), set_value('userid_charge', $detailstock['userid_charge']), 'class="form-control m-b select3 filter"');?>
	                                
									</div>
								</div>
							</div>
							<div class="toolbox action clearfix">
								<div class="uk-flex uk-flex-middle uk-button pull-right">
									<button class="btn btn-primary btn-sm" name="update" value="create" type="submit">Khởi tạo</button>
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
