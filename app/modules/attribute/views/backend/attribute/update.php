<div id="page-wrapper" class="gray-bg dashbard-1 fix-wrapper">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Cập nhật thuộc tính</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Cập nhật thuộc tính</strong></li>
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
									<button type="submit" name="update" value="update" class="btn btn-success block full-width m-b">Lưu</button>
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
										<?php echo form_input('title', htmlspecialchars_decode(html_entity_decode(set_value('title', $detailattribute['title']))), 'class="form-control title" placeholder="" id="title" autocomplete="off"');?>
									</div>
								</div>
							</div>
							<div class="row mb15">
								<div class="col-lg-12">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Mô tả ngắn</span>
										</label>
										<?php echo form_textarea('description', htmlspecialchars_decode(html_entity_decode(set_value('description', $detailattribute['description']))), 'class="form-control ck-editor" id="ckDescription" placeholder="" autocomplete="off"');?>
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
										<div class="g-title"><?php echo ($this->input->post('meta_title')) ? $this->input->post('meta_title') : (($this->input->post('title')) ? $this->input->post('title') : (($detailattribute['meta_title'] != '') ? $detailattribute['meta_title'] : $detailattribute['title'])); ?></div>
										<div class="g-link"><?php echo ($this->input->post('canonical')) ? site_url($this->input->post('canonical')) : site_url($detailattribute['canonical']); ?></div>
										<div class="g-description" id="metaDescription">
											<?php echo ($this->input->post('meta_description')) ? $this->input->post('meta_description') : (($this->input->post('description')) ? strip_tags($this->input->post('description')) : ((!empty($detailattribute['meta_description'])) ? strip_tags($detailattribute['meta_description']) :((!empty($detailattribute['description'])) ? strip_tags($detailattribute['description']): 'List of all combinations of words containing CKEDT. Words that contain ckedt letters in them. Anagrams made from C K E D T letters.List of all combinations of words containing CKEDT. Words that contain ckedt letters in them. Anagrams made from C K E D T letters.'))); ?>
											
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
												<span style="color:#9fafba;"><span id="titleCount"><?php echo strlen($detailattribute['meta_title']) ?></span> trên 70 ký tự</span>
											</div>
											<?php echo form_input('meta_title', htmlspecialchars_decode(html_entity_decode(set_value('meta_title', $detailattribute['meta_title']))), 'class="form-control meta-title" placeholder="" autocomplete="off"');?>
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
												<span style="color:#9fafba;"><span id="descriptionCount"><?php echo strlen($detailattribute['meta_description']) ?></span> trên 320 ký tự</span>
											</div>
											<?php echo form_textarea('meta_description', htmlspecialchars_decode(html_entity_decode(set_value('meta_description', $detailattribute['meta_description']))), 'class="form-control meta-description" id="seoDescription" placeholder="" autocomplete="off"');?>
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
													<?php echo form_input('canonical', htmlspecialchars_decode(html_entity_decode(set_value('canonical', $detailattribute['canonical']))), 'class="form-control canonical" placeholder="" autocomplete="off" data-flag="1" ');?>
													<?php echo form_input('original_canonical', htmlspecialchars_decode(html_entity_decode(set_value('canonical', $detailattribute['canonical']))), 'class="form-control" placeholder="" style="display:none;" autocomplete="off"');?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<button type="submit" name="update" value="update" class="btn btn-success block m-b pull-right">Lưu</button>
					
				</div>
				<div class="col-lg-4">
					<div class="ibox mb20">
						<div class="ibox-title">
							<h5>Nhóm thuộc tính</h5>
						</div>
						<div class="ibox-content">
							<div class="row mb15">
								<div class="col-lg-12">
									<div class="form-row">
										<?php echo form_dropdown('catalogueid', 
													dropdown(array(
														'text'=>'---Chọn nhóm thuộc tính---',
														'select'=>'id, title',
														'table'=>'attribute_catalogue',
														'field'=>'id',
														'value'=>'title',
														'order_by'=>'id DESC'
													)),
													set_value('catalogueid',$detailattribute['catalogueid'],$this->input->get('catalogueid')) ,'class="form-control input-sm perpage select3 catalogueid" '); ?>
									</div>
								</div>
							</div>

							<div class="row mb15 price_range" <?php echo ($detailattribute['catalogueid']==7)?'':'style="display: none"' ?> >
								<div class="col-lg-12">
									<div class="form-row back-change">
										<div class="uk-flex uk-flex-middle uk-flex-space-between">	
											<span class="m-r" >Từ </span> 
				                            <input type="text" autocomplete="off"  class="form-control int m-r text-right" name="start_price" value="<?php echo addCommas($detailattribute['start_price'])  ?>" />
				                            <span class="m-r">đến </span> 
				                          	<input type="text" autocomplete="off"  class="form-control int text-right " name="end_price" value="<?php echo addCommas($detailattribute['end_price'])  ?>" />
										</div>
									</div>
								</div>
							</div>

							<div class="row mb15 color" <?php echo ($detailattribute['catalogueid']==2)?'':'style="display: none"' ?>>
								<div class="col-lg-12">
									<div class="form-row back-change">
										<label class="control-label text-left">
											<span>Màu<b class="text-danger">(*)</b></span>
										</label>
										<div class="uk-flex uk-flex-middle uk-flex-space-between">
				                            <input type="text" autocomplete="off" class="form-control demo1 m-r" name="color" value="<?php echo (!empty($detailattribute['color'])) ? $detailattribute['color'] :'' ?>" />
				                            <div style="background: <?php echo (!empty($detailattribute['color']))? $detailattribute['color'] :'' ?>; width: 13px; height: 34px" id="demo_apidemo" class="btn btn-white btn-block colorpicker-element" ></div>
				                            <!-- 
				                           	<div data-color="#ffffff" id="demo_apidemo" class="btn btn-white btn-block colorpicker-element" style="width: 10px; height: 10px" ></div> -->
										</div>
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
										<div class="avatar" style="cursor: pointer;"><img src="<?php echo ($this->input->post('image')) ? $this->input->post('image') : ((!empty($detailattribute['image'])) ? $detailattribute['image'] : 'template/not-found.png'); ?>" class="img-thumbnail" alt=""></div>
										<?php echo form_hidden('image', htmlspecialchars_decode(html_entity_decode(set_value('image', $detailattribute['image']))), 'class="form-control " placeholder="Đường dẫn của ảnh" onclick="openKCFinder(this)"  autocomplete="off"');?>
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
											<div class="i-checks mr30" style="width:100%;"><span style="color:#000;"> <input <?php echo ($this->input->post('publish') == 0) ? 'checked' : (($detailattribute['publish'] == 0) ? 'checked' : '') ?>  class="popup_gender_1 gender" type="radio" value="0"  name="publish"> <i></i>Cho phép hiển thị trên website</span></div>
										</div>
										<div class="block clearfix">
											<div class="i-checks" style="width:100%;"><span style="color:#000;"> <input type="radio" <?php echo ($this->input->post('publish') == 1) ? 'checked' : (($detailattribute['publish'] == 1) ? 'checked' : '') ?>  class="popup_gender_0 gender" required value="1" name="publish"> <i></i> Tắt chức năng hiển thị trên website. </span></div>
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

<script type="text/javascript">
	$(document).ready(function(){
		$('#parentid option').each(function(){
			let val = $(this).val();
			if(val == <?php echo $detailattribute['id'] ?>){
				$(this).attr('disabled','disabled');
			}
		});
		
	});
</script>