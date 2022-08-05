<script type="text/javascript">
	var submit = '<?php echo $this->input->post('create'); ?>';
	var condition_value = '<?php echo json_encode($this->input->post('condition_value')); ?>';
	var discount_moduleid = '<?php echo json_encode($this->input->post('discount_moduleid')); ?>';
	var city = '<?php echo $this->input->post('city[]'); ?>';
	var district = '<?php echo $this->input->post('district[]'); ?>';
	var module = '<?php echo json_encode($this->input->post('module[]')); ?>';
</script>
<div id="page-wrapper" class="gray-bg dashbard-1 fix-wrapper">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Thêm mới khuyến mại</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Thêm mới khuyến mại</strong></li>
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
									<button type="submit" name="create" value="create" class="btn btn-success  full-width">Tạo mới</button>
								</div>
							</div>
						</div>
						<div class="ibox-content">
							<div class="row mb15 ">
								<div class="col-lg-12">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Tiêu đề khuyến mại <b class="text-danger">(*)</b></span>
										</label>
										<?php echo form_input('title', htmlspecialchars_decode(html_entity_decode(set_value('title'))), 'class="form-control title" placeholder="" id="title" autocomplete="off"');?>
									</div>
								</div>
							</div>

							<?php $album = $this->input->post('album'); ?>
							<div class="row mb15">
								<div class="col-sm-12">
									<div class="uk-flex uk-flex-middle uk-flex-space-between">
									<b>Banner </b>

									<div class="uk-flex uk-flex-middle uk-flex-space-between">
										<div class="edit">
											<a onclick="openKCFinderImage(this);return false;" href="" title="" class="upload-picture">Chọn hình</a>
										</div>
									</div>
								</div>
								</div>
								<div class="col-lg-12">
									<div class="click-to-upload" <?php echo (isset($album))?'style="display:none"':'' ?>>
										<div class="icon">
											<a type="button" class="upload-picture" onclick="openKCFinderImage(this);return false;">
												<svg style="width:80px;height:80px;fill: #d3dbe2;margin-bottom: 10px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80"><path d="M80 57.6l-4-18.7v-23.9c0-1.1-.9-2-2-2h-3.5l-1.1-5.4c-.3-1.1-1.4-1.8-2.4-1.6l-32.6 7h-27.4c-1.1 0-2 .9-2 2v4.3l-3.4.7c-1.1.2-1.8 1.3-1.5 2.4l5 23.4v20.2c0 1.1.9 2 2 2h2.7l.9 4.4c.2.9 1 1.6 2 1.6h.4l27.9-6h33c1.1 0 2-.9 2-2v-5.5l2.4-.5c1.1-.2 1.8-1.3 1.6-2.4zm-75-21.5l-3-14.1 3-.6v14.7zm62.4-28.1l1.1 5h-24.5l23.4-5zm-54.8 64l-.8-4h19.6l-18.8 4zm37.7-6h-43.3v-51h67v51h-23.7zm25.7-7.5v-9.9l2 9.4-2 .5zm-52-21.5c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5zm0-8c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3zm-13-10v43h59v-43h-59zm57 2v24.1l-12.8-12.8c-3-3-7.9-3-11 0l-13.3 13.2-.1-.1c-1.1-1.1-2.5-1.7-4.1-1.7-1.5 0-3 .6-4.1 1.7l-9.6 9.8v-34.2h55zm-55 39v-2l11.1-11.2c1.4-1.4 3.9-1.4 5.3 0l9.7 9.7c-5.2 1.3-9 2.4-9.4 2.5l-3.7 1h-13zm55 0h-34.2c7.1-2 23.2-5.9 33-5.9l1.2-.1v6zm-1.3-7.9c-7.2 0-17.4 2-25.3 3.9l-9.1-9.1 13.3-13.3c2.2-2.2 5.9-2.2 8.1 0l14.3 14.3v4.1l-1.3.1z"></path></svg>
											</a>
										</div>
										<div class="small-text">Sử dụng nút <b>Chọn hình</b> để thêm hình.</div>
									</div>
									<div class="upload-list" <?php echo (isset($album))?'':'style="display:none"' ?> style="padding:5px;">
										<div class="row">
											<ul id="sortable" class="clearfix sortui">
												<?php if(isset($album) && is_array($album) && count($album)){ ?>
												<?php foreach($album as $key => $val){ ?>
													<li class="ui-state-default">
														<div class="thumb">
															<span class="image img-scaledown">
																<img src="<?php echo $val; ?>" alt="" /> <input type="hidden" value="<?php echo $val; ?>" name="album[]" />
															</span>
															<div class="overlay"></div>
															<div class="delete-image"><i class="fa fa-trash" aria-hidden="true"></i></div>
														</div>
													</li>
												<?php }} ?>
											</ul>
										</div>
									</div>
								</div>
							</div>

							<div class="row mb15">
								<div class="col-lg-12 mb10">
									<div class="form-row">
										<div class="uk-flex uk-flex-middle uk-flex-space-between">
											<label class="control-label text-left">
												<span>Nội dung</span>
											</label>
										</div>
										<?php 
											$description = ($this->input->post('description')) ? $this->input->post('description') : (isset($detailObject['description'])?$detailObject['description']:'')
										 ?>
										<?php echo form_textarea('description', htmlspecialchars_decode(html_entity_decode(set_value('description', $description))), 'class="form-control ck-editor" id="ckDescription" placeholder="" autocomplete="off"');?>
									</div>
								</div>
							</div>
						</div>
					</div>



					<div class="ibox mb20">
						<div class="ibox-title">
							<div class="uk-flex uk-flex-middle uk-flex-space-between">
								<h5>Tối ưu SEO <small class="text-danger">Thiết lập các thẻ mô tả giúp khách hàng dễ dàng tìm thấy bạn.</small></h5>

								<div class="uk-flex uk-flex-middle uk-flex-space-between">
									<div class="edit">
										<a href="#" class="edit-seo">Chỉnh sửa SEO</a>
									</div>
								</div>
							</div>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="col-lg-12">
									<div class="google">
										<div class="g-title"><?php echo ($this->input->post('meta_title')) ? $this->input->post('meta_title') : (($this->input->post('title')) ? $this->input->post('title') : 'HT Việt Nam - Đơn vị thiết kế website hàng đầu Việt Nam'); ?></div>
										<div class="g-link"><?php echo ($this->input->post('canonical')) ? site_url($this->input->post('canonical')) : 'http://thegioiweb.org/kho-giao-dien-website.html'; ?></div>
										<div class="g-description" id="metaDescription">
											<?php echo ($this->input->post('meta_description')) ? $this->input->post('meta_description') : (($this->input->post('description')) ? strip_tags($this->input->post('description')) : 'List of all combinations of words containing CKEDT. Words that contain ckedt letters in them. Anagrams made from C K E D T letters.List of all combinations of words containing CKEDT. Words that contain ckedt letters in them. Anagrams made from C K E D T letters.'); ?>

										</div>
									</div>
								</div>
							</div>

							<div class="seo-group hidden">
								<hr>
								<div class="row mb15">
									<div class="col-lg-12">
										<div class="form-row">
											<div class="uk-flex uk-flex-middle uk-flex-space-between">
												<label class="control-label ">
													<span>Tiêu đề SEO</span>
												</label>
												<span style="color:#9fafba;"><span id="titleCount">0</span> trên 70 ký tự</span>
											</div>
											<?php echo form_input('meta_title', htmlspecialchars_decode(html_entity_decode(set_value('meta_title'))), 'class="form-control meta-title" placeholder="" autocomplete="off"');?>
										</div>
									</div>
								</div>
								<div class="row mb15">
									<div class="col-lg-12">
										<div class="form-row">
											<div class="uk-flex uk-flex-middle uk-flex-space-between">
												<label class="control-label ">
													<span>Mô tả SEO</span>
												</label>
												<span style="color:#9fafba;"><span id="descriptionCount">0</span> trên 320 ký tự</span>
											</div>
											<?php echo form_textarea('meta_description', set_value('meta_description'), 'class="form-control meta-description" id="seoDescription" placeholder="" autocomplete="off"');?>
										</div>
									</div>
								</div>
								<div class="row mb15">
									<div class="col-lg-12">
										<div class="form-row">
											<div class="uk-flex uk-flex-middle uk-flex-space-between">
												<label class="control-label ">
													<span>Đường dẫn <b class="text-danger">(*)</b></span>
												</label>
											</div>
											<div class="outer">
												<div class="uk-flex uk-flex-middle">
													<div class="base-url"><?php echo base_url(); ?></div>
													<?php echo form_input('canonical', htmlspecialchars_decode(html_entity_decode(set_value('canonical'))), 'class="form-control canonical" placeholder="" autocomplete="off" data-flag="0" ');?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<button type="submit" name="create" value="create" class="btn btn-success block m-b pull-right">Tạo mới</button>
				</div>
				<div class="col-lg-4">
					<div class="ibox mb20">
						<div class="ibox-title">
							<h5>Thời gian áp dụng</h5>
						</div>
						<div class="ibox-content">
							<?php $currentDate = gmdate('d/m/Y', time() + 7*3600); ?>
							<?php $currentTime = gmdate('H:i:s', time() + 7*3600); ?>

							<?php $choose_date = $this->input->post('choose_date')  ?>
							<label>Từ ngày</label>
							<?php //echo form_input('start_date', set_value('start_date'), 'autocomplete="off" placeholder="Từ ngày" class="form-control datetimepicker m-b"'.(($choose_date == '1' ) ? 'readonly' : ''));?>
							<div class="setting-group mb10">
								<div class="uk-flex uk-flex-middle uk-flex-space-between">
									<?php echo form_input('start_date', htmlspecialchars_decode(html_entity_decode(set_value('start_date', $currentDate))), 'class="form-control datetimepicker" placeholder=""  autocomplete="off"'.(($choose_date == '1' ) ? 'readonly' : ''));?> 
									<?php echo form_input('start_time', htmlspecialchars_decode(html_entity_decode(set_value('start_time', $currentTime))), 'class="form-control input-clock" placeholder="" autocomplete="off" data-default="20:48"'.(($choose_date == '1' ) ? 'readonly' : ''));?>
								</div>
							</div>
							<label>Đến ngày</label>
							<?php //echo form_input('end_date', set_value('end_date'), 'autocomplete="off" placeholder="Đến ngày" class="form-control datetimepicker m-b"'.(($choose_date == '1' ) ? 'readonly' : ''));?>
							<div class="setting-group">
								<div class="uk-flex uk-flex-middle uk-flex-space-between">
									<?php echo form_input('end_date', htmlspecialchars_decode(html_entity_decode(set_value('end_date', $currentDate))), 'class="form-control datetimepicker" placeholder=""  autocomplete="off"'.(($choose_date == '1' ) ? 'readonly' : ''));?> 
									<?php echo form_input('end_time', htmlspecialchars_decode(html_entity_decode(set_value('end_time', $currentTime))), 'class="form-control input-clock" placeholder="" autocomplete="off" data-default="20:48"'.(($choose_date == '1' ) ? 'readonly' : ''));?>
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
