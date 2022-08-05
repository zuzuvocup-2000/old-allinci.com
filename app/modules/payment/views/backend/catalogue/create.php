<div id="page-wrapper" class="gray-bg dashbard-1 fix-wrapper">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Thêm mới nhóm nội dung tĩnh</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Thêm mới nhóm nội dung tĩnh</strong></li>
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
				<div class="col-lg-8 clearfix">
					<div class="ibox mb20">
						<div class="ibox-title" style="padding: 9px 15px 0px;">
							<div class="uk-flex uk-flex-middle uk-flex-space-between">
								<h5>Thông tin cơ bản <small class="text-danger">Điền đầy đủ các thông tin được mô tả dưới đây</small></h5>
								<div class="ibox-tools">
									<button type="submit" name="create" value="create" class="btn btn-primary block full-width m-b">Tạo mới</button>
								</div>
							</div>
						</div>
						<div class="ibox-content">
							<div class="row mb15">
								<div class="col-lg-12">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Tiêu đề danh mục <b class="text-danger">(*)</b></span>
										</label>
										<?php echo form_input('title', htmlspecialchars_decode(html_entity_decode(set_value('title'))), 'class="form-control title" placeholder="" id="title" autocomplete="off"');?>
									</div>
								</div>
							</div>
							<div class="row mb15">
								<div class="col-lg-12">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Mô tả ngắn</span>
										</label>
										<?php echo form_textarea('description', htmlspecialchars_decode(html_entity_decode(set_value('description'))), 'class="form-control ck-editor" id="ckDescription" placeholder="" autocomplete="off"');?>
									</div>
								</div>
							</div>
						</div>
					</div>
					
				
					
					
					<button type="submit" name="create" value="create" class="btn btn-primary block m-b pull-right">Tạo mới</button>
					
				</div>
				<div class="col-lg-4">
					<div class="ibox mb20">
						<div class="ibox-title">
							<h5>Lựa chọn danh mục cha </h5>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="col-lg-12">
									<div class="form-row mb10">
										<small class="text-danger">Chọn [Root] Nếu không có danh mục cha</small>
									</div>
									<div class="form-row">
										<?php echo form_dropdown('parentid', $this->nestedsetbie->dropdown(), set_value('parentid'), 'class="form-control m-b select3"');?>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="ibox mb20">
						<div class="ibox-title">
							<h5>Ảnh đại diện </h5>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="col-lg-12">
									<div class="form-row">
										<div class="avatar" style="cursor: pointer;"><img src="template/not-found.png" class="img-thumbnail" alt=""></div>
										<?php echo form_hidden('image', htmlspecialchars_decode(html_entity_decode(set_value('image'))), 'class="form-control " placeholder="Đường dẫn của ảnh" onclick="openKCFinder(this)"  autocomplete="off"');?>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="ibox mb20">
						<div class="ibox-title">
							<h5>Hiển thị </h5>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="col-lg-12">
									<div class="form-row">
										<span class="text-black mb15">Quản lý thiết lập hiển thị cho blog này.</span>
										<div class="block clearfix">
											<div class="i-checks mr30" style="width:100%;"><span style="color:#000;"> <input <?php echo ($this->input->post('publish') == 0) ? 'checked' : '' ?> class="popup_gender_1 gender" type="radio" value="0"  name="publish"> <i></i>Cho phép hiển thị trên website</span></div>
										</div>
										<div class="block clearfix">
											<div class="i-checks" style="width:100%;"><span style="color:#000;"> <input type="radio" <?php echo ($this->input->post('publish') == 1) ? 'checked' : '' ?>  class="popup_gender_0 gender" required value="1" name="publish"> <i></i> Tắt chức năng hiển thị trên website. </span></div>
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