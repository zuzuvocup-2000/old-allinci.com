<div id="page-wrapper" class="gray-bg dashbard-1 fix-wrapper">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Cập nhật dự án</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Cập nhật dự án</strong></li>
			</ol>
		</div>
	</div>
	<form method="post" action="" class="form-horizontal box" >
		<script type="text/javascript">
			var submit = '<?php echo $this->input->post('update'); ?>';
			var catalogueid = '<?php
					if($this->input->post('update')){
						echo json_encode($this->input->post('catalogue')); 
					}else{
						echo $detailproject['catalogue'];
					}
				?>';
			var tag = '<?php
				if($this->input->post('update')){
					echo json_encode($this->input->post('tag')); 
				}else{
					echo $detailproject['tag'];
				}
			?>';
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
						<div class="ibox-title" style="padding: 9px 15px 0px;">
							<div class="uk-flex uk-flex-middle uk-flex-space-between">
								<h5>Thông tin cơ bản <small class="text-danger">Điền đầy đủ các thông tin được mô tả dưới đây</small></h5>
								<div class="ibox-tools">
									<button type="submit" name="update" value="update" class="btn btn-success block full-width m-b">Lưu</button>
								</div>
							</div>
						</div>
						<div class="ibox-content">
							<div class="row mb15">
								<div class="col-lg-12">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Tiêu đề dự án <b class="text-danger">(*)</b></span>
										</label>
										<?php echo form_input('title', htmlspecialchars_decode(html_entity_decode(set_value('title', $detailproject['title']))), 'class="form-control title" placeholder="" id="title" autocomplete="off"');?>
									</div>
								</div>
							</div>
							<div class="row mb15">
								<div class="col-lg-12 mb10">
									<div class="form-row">
										<div class="uk-flex uk-flex-middle uk-flex-space-between">
											<label class="control-label text-left">
												<span>Mô tả ngắn</span>
											</label>
											<a href="" title="" class="uploadMultiImage" onclick="openKCFinderMulti(this);return false;">Upload hình ảnh</a>
										</div>
										<?php echo form_textarea('excerpt', htmlspecialchars_decode(html_entity_decode(set_value('excerpt', $detailproject['excerpt'] ))), 'class="form-control ck-editor" id="ckDescription0" data-height="150" placeholder="" autocomplete="off"');?>
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
											<a href="" title="" class="uploadMultiImage" onclick="openKCFinderMulti(this);return false;">Upload hình ảnh</a>
										</div>
										<?php echo form_textarea('description', htmlspecialchars_decode(html_entity_decode(set_value('description', $detailproject['description']))), 'class="form-control ck-editor" id="ckDescription" placeholder="" autocomplete="off"');?>
									</div>
								</div>
								<div class="col-lg-12">	
									<div class="uk-flex uk-flex-middle uk-flex-space-between ">
										<label class="control-label text-left ">
											<span>Nội dung mở rộng</span>
										</label>
										<a href="" title="" class="add-attr" onclick="return false;">Thêm Nội dung +</a>
									</div>
								</div>
							</div>
							<div class="row attr-more">
								
								<?php 	
								$extend_description = $this->input->post('content'); 
								if(isset($extend_description) && is_array($extend_description) && count($extend_description)){
									$content = $extend_description;
								}else{
									$content = json_decode($detailproject['extend_description'], TRUE);
								}
								if (isset($content['title'])&& is_array($content['title']) && count($content['title'])){ ?>
									<?php foreach($content['title'] as $key => $val){ ?>
										<?php if($val == '' ){continue;} ?>
								<div class="col-lg-12 desc-more">
									<div class="row m-b">
										<div class="col-lg-8">
											<input type="text" name="content[title][]" value="<?php echo $val ?>" class="form-control" placeholder="Tiêu đề">
										</div>
										<div class="col-lg-4">
											<div class="uk-flex uk-flex-middle uk-flex-space-between">
												<a href="" title="" class="uploadMultiImage" onclick="openKCFinderDescExtend('<?php echo 'editor_'.$key ?>');return false;">Upload hình ảnh</a>
												<button class="btn btn-danger delete-attr" type="button"><i class="fa fa-trash"></i></button>
											</div>
										</div>
									</div>
									<div class="row m-b">
										<div class="col-lg-12" >
											<input type="text" name="content[image][]" value="<?php echo $content['image'][$key]; ?>" class="form-control" placeholder="Icon/ Ảnh đại diện" onclick="openKCFinder(this)">
										</div>
									</div>
									<div class="row m-b">
										<div class="col-lg-12">
											<?php echo form_textarea('content[description][]', htmlspecialchars_decode(html_entity_decode($content['description'][$key])), 'class="form-control ck-editor" id="editor_'.$key.'" placeholder="" autocomplete="off"');?>
										</div>
									</div>
								</div>	
								<?php }} ?>
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
										<div class="g-title"><?php echo ($this->input->post('meta_title')) ? $this->input->post('meta_title') : (($this->input->post('title')) ? $this->input->post('title') : (($detailproject['meta_title'] != '') ? $detailproject['meta_title'] : $detailproject['title'])); ?></div>
										<div class="g-link"><?php echo ($this->input->post('canonical')) ? site_url($this->input->post('canonical')) : site_url($detailproject['canonical']); ?></div>
										<div class="g-description" id="metaDescription">
											<?php echo cutnchar(($this->input->post('meta_description')) ? $this->input->post('meta_description') : (($this->input->post('description')) ? strip_tags($this->input->post('description')) : ((!empty($detailproject['meta_description'])) ? strip_tags($detailproject['meta_description']) :((!empty($detailproject['description'])) ? strip_tags($detailproject['description']): 'List of all combinations of words containing CKEDT. Words that contain ckedt letters in them. Anagrams made from C K E D T letters.List of all combinations of words containing CKEDT. Words that contain ckedt letters in them. Anagrams made from C K E D T letters.'))), 360); ?>
											
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
												<span style="color:#9fafba;"><span id="titleCount"><?php echo strlen($detailproject['meta_title']) ?></span> trên 70 ký tự</span>
											</div>
											<?php echo form_input('meta_title', htmlspecialchars_decode(html_entity_decode(set_value('meta_title', $detailproject['meta_title']))), 'class="form-control meta-title" placeholder="" autocomplete="off"');?>
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
												<span style="color:#9fafba;"><span id="descriptionCount"><?php echo strlen($detailproject['meta_description']) ?></span> trên 320 ký tự</span>
											</div>
											<?php echo form_textarea('meta_description', htmlspecialchars_decode(html_entity_decode(set_value('meta_description', $detailproject['meta_description']))), 'class="form-control meta-description" id="seoDescription" placeholder="" autocomplete="off"');?>
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
													<?php echo form_input('canonical', htmlspecialchars_decode(html_entity_decode(set_value('canonical', $detailproject['canonical']))), 'class="form-control canonical" placeholder="" autocomplete="off" data-flag="1" ');?>
													<?php echo form_input('original_canonical', htmlspecialchars_decode(html_entity_decode(set_value('canonical', $detailproject['canonical']))), 'class="form-control" placeholder="" style="display:none;" autocomplete="off"');?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php $this->load->view('dashboard/backend/common/comment'); ?>
					<button type="submit" name="update" value="update" class="btn btn-success block m-b pull-right">Lưu</button>
					<a href="<?php echo site_url('project/backend/project/view'); ?>"  class="btn btn-danger block m-b pull-right" style="margin-right:10px;">Hủy bỏ</a>
				</div>
				<div class="col-lg-4">
					<div class="ibox mb20">
						<div class="ibox-title uk-flex uk-flex-middle uk-flex-space-between">
							<h5>Phân loại </h5>
						</div>
						<div class="ibox-content">
							<div class="row mb15">
								<div class="col-lg-12">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Danh mục chính <b class="text-danger">(*)</b></span>
										</label>
										<div class="form-row">
											<?php echo form_dropdown('catalogueid', $this->nestedsetbie->dropdown(), set_value('catalogueid', $detailproject['catalogueid']), 'class="form-control m-b select3"');?>
										</div>
									</div>
								</div>
							</div>
							<div class="row mb15">
								<div class="col-lg-12">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Danh mục phụ</span>
										</label>
										<div class="form-row">
											<?php echo form_dropdown('catalogue[]', '', (isset($catalogue)?$catalogue:NULL), 'class="form-control selectMultipe" multiple="multiple" data-title="Nhập 2 kí tự để tìm kiếm.."  style="" id="project_catalogue"'); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<?php 
						/*
							<div class="ibox mb20">
						<div class="ibox-title">
							<h5>Chọn kiểu hiển thị </h5>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="col-lg-12">
									<div class="form-row mb10">
										<div class="mb5">
											<small class="text-danger">- Kiểu 1 (Mặc định): Kiểu hiển thị tin tức</small>
										</div>
										<div class="mb5">
										    <small class="text-danger mr10">- Kiểu 2 : Ảnh trái text phải</small>
											<?php echo renderHtmlTooltip('template/backend/detail_view_type_2.png'); ?>
										</div>
										<div class="mb5">
										    <small class="text-danger mr10">- Kiểu 3 : Ảnh giữa text xung quanh</small>
											<?php echo renderHtmlTooltip('template/backend/detail_view_type_3.png'); ?>
										</div>
										<div class="mb5">
										    <small class="text-danger mr10">- Kiểu 4 :</small>
											<?php echo renderHtmlTooltip('template/backend/detail_view_type_4.png'); ?>
										</div>
										<div class="mb5">
										    <small class="text-danger mr10">- Kiểu 5 :</small>
											<?php echo renderHtmlTooltip('template/backend/detail_view_type_5.png'); ?>
										</div>
									</div>
									<div class="form-row">
										<?php echo form_dropdown('url_view', $this->configbie->data('url_detail_view'), set_value('url_view', $detailproject['url_view']), 'class="form-control m-b select3"');?>
									</div>
								</div>
							</div>
						</div>
					</div>
						*/

					 ?>
					
					<!-- fix upload anh update -->

					<div class="ibox mb20">
						<div class="ibox-title">
							<div class="uk-flex uk-flex-middle uk-flex-space-between">
								<h5>Ảnh đại diện </h5>
								<div class="edit">
									<a onclick="openKCFinderSingleImage(this); return false;" href="" title="" class="upload-picture">Chọn hình</a>
								</div>
							</div>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="col-lg-12">
									<div class="form-row">
										<div class="avatar local_result" style="cursor: pointer;"><img src="<?php echo ($this->input->post('image')) ? $this->input->post('image') : ((!empty($detailproject['image'])) ? $detailproject['image'] : 'template/not-found.png'); ?>" class="img-thumbnail" alt=""></div>
										<?php echo form_hidden('image', htmlspecialchars_decode(html_entity_decode(set_value('image', $detailproject['image']))), 'class="form-control" placeholder="Đường dẫn của ảnh" onclick="openKCFinder(this)"  autocomplete="off"');?>
									</div>
								</div>
							</div>
						</div>
					</div>


					<div class="ibox mb20">
						<div class="ibox-title uk-flex uk-flex-middle uk-flex-space-between">
							<h5>Tags </h5>
							<a type="button" data-toggle="modal" data-target="#myModal6">+ Thêm mới tag</a>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="col-lg-12">
									<div class="form-row">
										<?php echo form_dropdown('tag[]', '', (isset($tag)?$tag:NULL), 'class="form-control selectMultipe" multiple="multiple" data-title="Nhập 2 kí tự để tìm kiếm.."  style="width: 100%;" id="tag"'); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ibox mb20">
						<div class="ibox-title">
							<h5>Giao diện </h5>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="col-lg-12">
									<div class="form-row">
										<?php echo form_dropdown('amp', $this->configbie->data('amp'), set_value('amp', $detailproject['amp']), 'class="form-control m-b select3"');?>
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
											<div class="i-checks mr30" style="width:100%;"><span style="color:#000;"> <input <?php echo ($this->input->post('publish') == 0) ? 'checked' : (($detailproject['publish'] == 0) ? 'checked' : '') ?>  class="popup_gender_1 gender" type="radio" value="0"  name="publish"> <i></i>Cho phép hiển thị trên website</span></div>
										</div>
										<div class="block clearfix">
											<div class="i-checks" style="width:100%;"><span style="color:#000;"> <input type="radio" <?php echo ($this->input->post('publish') == 1) ? 'checked' : (($detailproject['publish'] == 1) ? 'checked' : '') ?>  class="popup_gender_0 gender" required value="1" name="publish"> <i></i> Tắt chức năng hiển thị trên website. </span></div>
										</div>
									</div>
									<?php 
										$publish_time = gettime($detailproject['publish_time'],'d/m/Y H:i');
										$publish_time = explode(' ', $publish_time);
									?>
									<div class="post-setting">
										<a href="" title="" class="setting-button mb5" data-flag="1">Xóa thiết lập</a>
										<div class="setting-group">
											<div class="uk-flex uk-flex-middle uk-flex-space-between">
												<?php echo form_input('post_date', htmlspecialchars_decode(html_entity_decode(set_value('post_date', $publish_time[0]))), 'class="form-control datetimepicker" placeholder=""  autocomplete="off"');?> 
												<span style="margin-right:5px;color:#141414;">lúc</span>
												<?php echo form_input('post_time', htmlspecialchars_decode(html_entity_decode(set_value('post_time', $publish_time[1]))), 'class="form-control" placeholder="" id="input-clock" autocomplete="off" data-default="20:48"');?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php $this->load->view('dashboard/backend/common/statistical-rating'); ?>
				</div>
			</div>
		</div>
	</form>
	
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
							<input type="text" name="popup-tag" value="" class="form-control popup-tag" placeholder="" autocomplete="off">
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
	<?php $this->load->view('dashboard/backend/common/footer'); ?>
</div>