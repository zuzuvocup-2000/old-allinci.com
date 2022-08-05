<div id="page-wrapper" class="gray-bg dashbard-1">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	 <div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Cập nhật nhóm khách hàng</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Cập nhật nhóm khách hàng</strong></li>
			</ol>
		</div>
	</div>
	
	<div class="wrapper wrapper-content animated fadeInRight">
		<form method="post" action="" class="form-horizontal box">
			<div class="fix-wrapper">
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-5">
							<div class="panel-head">
								<h2 class="panel-title">Thông tin chung</h2>
								<div class="panel-description">
									Một số thông tin cơ bản của nhóm khách hàng.
								</div>
							</div>
						</div>
						<div class="col-lg-7">
							<div class="ibox m0">
								<div class="ibox-content">
									<div class="row">
										<div class="box-body">
											<?php $error = validation_errors(); echo !empty($error)?'<div class="alert alert-danger">'.$error.'</div>':'';?>
										</div><!-- /.box-body -->
									</div>
									<div class="row">
										<div class="col-lg-12">
											<div class="form-row mb15">
												<label class="control-label text-left">
													<span>Tên nhóm khách hàng <b class="text-danger">(*)</b></span>
												</label>
												<?php echo form_input('title', htmlspecialchars_decode(html_entity_decode(set_value('title' , $detailCatalogue['title']))), 'class="form-control " placeholder="" autocomplete="off"');?>
												
											</div>
											<div class="uk-flex uk-flex-right">
												<div class="uk-button">
													<button style="margin-right:2px;" class="btn btn-primary btn-sm" name="update" value="update" type="submit">Cập nhật nhóm khách hàng</button>
												</div>
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
	</div>
	<?php $this->load->view('dashboard/backend/common/footer'); ?>
</div>
