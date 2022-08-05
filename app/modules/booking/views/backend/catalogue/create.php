<div id="page-wrapper" class="gray-bg dashbard-1 fix-wrapper">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Thêm mới lịch</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Thêm mới lịch</strong></li>
			</ol>
		</div>
	</div>
	<form method="post" action="" class="form-horizontal box" >
		<script type="text/javascript">
			var submit = '<?php echo $this->input->post('create'); ?>';
			var catalogueid = '<?php echo json_encode($this->input->post('catalogue')); ?>';
		</script>
		<div class="wrapper wrapper-content animated fadeInRight">
			<div class="row">
				<div class="box-body">
					<?php $error = validation_errors(); echo !empty($error)?'<div class="alert alert-danger">'.$error.'</div>':'';?>
				</div><!-- /.box-body -->
			</div>
			<div class="row">
				<div class="col-lg-12 clearfix">
					<div class="ibox mb20">
						<div class="ibox-title" style="padding: 9px 15px 0px;">
							<div class="uk-flex uk-flex-middle uk-flex-space-between">
								<h5>Thông tin cơ bản <small class="text-danger">Điền đầy đủ các thông tin được mô tả dưới đây</small></h5>
								<div class="ibox-tools">
									<button type="submit" name="create" value="create" class="btn btn-success block full-width m-b">Tạo mới</button>
								</div>
							</div>
						</div>
						<div class="ibox-content">
							<div class="row m-b">
								<?php $currentDate = gmdate('d/m/Y', time() + 7*3600); ?>
								<?php $currentTime = gmdate('H:i', time() + 7*3600); ?>
                                <div class="col-md-3">
                                    <p class="font-bold">
                                        Chọn ngày
                                    </p>
									<?php echo form_input('post_date', htmlspecialchars_decode(html_entity_decode(set_value('post_date', $currentDate))), 'class="form-control datetimepicker" placeholder=""  autocomplete="off"');?>
									 
                                </div>
                                <div class="col-md-2">
                                    <p class="font-bold">
                                        Chọn t/g bắt đầu
                                    </p>
                                    <?php echo form_input('post_time_start', htmlspecialchars_decode(html_entity_decode(set_value('post_time_start'))), 'class="form-control input-clock" placeholder="" autocomplete="off" data-default="08:00" ');?>
                                </div>
                                <div class="col-md-2">
                                    <p class="font-bold">
                                        Chọn t/g kết thúc
                                    </p>
                                    <?php echo form_input('post_time_end', htmlspecialchars_decode(html_entity_decode(set_value('post_time_end'))), 'class="form-control input-clock" placeholder="" autocomplete="off" data-default="10:00"');?>
                                </div>
                                <div class="col-md-2">
                                    <p class="font-bold">
                                        Bước nhảy (phút)
                                    </p>
                                    <?php echo form_input('step', set_value('step', 30), 'class="form-control" placeholder="(nhập số phút)" autocomplete="off" ');?>
                                </div>
                                <div class="col-md-3">
									<span class="btn btn-info js_render_time" style="margin-top: 30px"><i class="fa fa-arrow-down" aria-hidden="true"></i> Tạo lịch</span>
                                </div>
                            </div>
							
							<div class="row list-time">
								<div class="col-sm-12 m-b-sm">
									<h3>Danh sách lịch hẹn trong ngày</h3>
								</div>
								
							</div>
								

							<div class="row">
								<div class="col-sm-12">
									<button type="submit" name="create" value="create" class="btn btn-success block m-b pull-right">Tạo mới</button>
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

<div class="modal inmodal fade in" id="myModal6" tabindex="-1" role="dialog" aria-hidden="true" >
	<form class="" method="POST" action="" id="create-tag">
		<div class="modal-dialog modal-lg" style ="max-width: 400px ; margin : 50px auto">
			<div class="modal-content fadeInRight animated">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Thêm từ khóa</h4>
				</div>
				<div class="modal-body">
					<div class="alert alert-danger" style = "display: none"></div>
					<div class="form-group">
						<label>Tiêu đề tag</label>
						<input type="text" name="popup-tag" value="" class="form-control tag-title" placeholder="" autocomplete="off">
					</div>
					<div class="form-group">
						<label>Đường dẫn Tag</label>
						<input type="text" name="popup-tag" value="" class="form-control tag-link" placeholder="" autocomplete="off">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Thêm mới</button>
				</div>
			</div>
		</div>
	</form>
</div>