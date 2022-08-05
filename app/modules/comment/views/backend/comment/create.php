
<div id="page-wrapper" class="gray-bg dashbard-1 fix-wrapper">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<form method="post" action="" class="form-horizontal box" id="comment-create_form" >
		<div class="row wrapper border-bottom white-bg page-heading">
			<div class="col-lg-10">
				<h2>Thêm mới comment</h2>
				<ol class="breadcrumb">
					<li>
						<a href="<?php echo site_url('admin'); ?>">Home</a>
					</li>
					<li class="active"><strong>Thêm mới comment</strong></li>
				</ol>
			</div>
		</div>
		<script type="text/javascript">
			var module = "<?php echo ($this->input->post('module') != '0')? $this->input->post('module') : '';?>";
			var object = <?php echo (int)$this->input->post('detailid');?>;
		</script>
		<div class="wrapper wrapper-content animated fadeInRight">
			<div class="row">
				<div class="box-body">
					<?php $error = validation_errors(); echo !empty($error)?'<div class="alert alert-danger">'.$error.'</div>':'';?>
				</div><!-- /.box-body -->
			</div>
			
			<div class="row">
				<div class="col-lg-8 clearfix">
					<div class="ibox mb20">
						<div class="ibox-title order-1">
							<div class="uk-flex uk-flex-middle uk-flex-space-between">
								<h5>Nội dung bình luận và đánh giá</h5>
								<div class="ibox-tools">
									<button type="submit" name="create" value="create" class="btn btn-primary block full-width m-b">Tạo mới</button>
								</div>
							</div>
						</div>
						<div class="ibox-content">
							<div class="row mb15">
								<div class="col-lg-6">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Danh mục <b class="text-danger">(*)</b></span>
										</label>
										<div class="form-row">
											<?php echo form_dropdown('module', $this->myconstant->list_data('module'), set_value('module'), 'class="form-control m-b select3" id="module"');?>
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-row" id="object">
										
									</div>
								</div>
							</div>
							<hr>
							<div class="row mb15">
								<div class="col-lg-12">
									<div class="text-center">
										<input type="hidden" class="data-rate" name="data-rate" value="<?php echo (int)$this->input->post('data-rate');?>">
										<span id="myRating" class="rating" data-stars="5" data-default-rating="<?php echo (int)$this->input->post('data-rate');?>" data-rating="<?php echo (int)$this->input->post('data-rate');?>"></span>
									</div>
									
									<div class="text-center mt10"><span class="title-rating"><?php echo review_render((int)$this->input->post('data-rate'));?></span></div>
								</div>
							</div>
							<hr>
							<div class="row mb15">
								<div class="col-lg-12">
									<div class="form-row">
										<div class="uk-flex uk-flex-middle uk-flex-space-between">
											<label class="control-label text-left">
												<span>Câu hỏi / Nhận xét</span>
											</label>
										</div>
										<?php echo form_textarea('comment', htmlspecialchars_decode(html_entity_decode(set_value('comment'))), 'class="form-control c
									" placeholder="" autocomplete="off" ');?>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="ibox mb20 album">
						<div class="ibox-title">
							<div class="uk-flex uk-flex-middle uk-flex-space-between">
								<h5>Album Ảnh </h5>
								
								<div class="uk-flex uk-flex-middle uk-flex-space-between">
									<div class="edit">
										<a onclick="openKCFinderImage(this);return false;" href="" title="" class="upload-picture">Chọn hình</a>
									</div>
								</div>
							</div>
						</div>
						<div class="ibox-content">
							<?php $album = $this->input->post('album'); ?>
							<div class="row">
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
						</div>
					</div>
					<button type="submit" name="create" value="create" class="btn btn-primary block m-b pull-right">Tạo mới</button>
				</div>
				<div class="col-lg-4 clearfix">
					<div class="ibox mb20">
						<div class="ibox-title">
							<h5>Thông tin cơ bản</h5>
						</div>
						<div class="ibox-content">
							<div class="row mb15">
								<div class="col-lg-12">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Họ tên <b class="text-danger">(*)</b></span>
										</label>
										<?php echo form_input('fullname', htmlspecialchars_decode(html_entity_decode(set_value('fullname'))), 'class="form-control
										" placeholder="" autocomplete="off" ');?>
									</div>
								</div>
							</div>
							<div class="row mb15">
								<div class="col-lg-12">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Email</span>
										</label>
										<?php echo form_input('email', htmlspecialchars_decode(html_entity_decode(set_value('email'))), 'class="form-control
										" placeholder="" autocomplete="off" ');?>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Số điện thoại</span>
										</label>
										<?php echo form_input('phone', htmlspecialchars_decode(html_entity_decode(set_value('phone'))), 'class="form-control
										" placeholder="" autocomplete="off" ');?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ibox mb20">
						<div class="ibox-title">
							<h5>Hiển thị</h5>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="col-lg-12">
									<?php echo manage_display_html();?>
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