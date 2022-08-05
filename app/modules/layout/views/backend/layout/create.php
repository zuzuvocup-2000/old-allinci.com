<div id="page-wrapper" class="gray-bg dashbard-1">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Thêm mới layout</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Thêm mới layout</strong></li>
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
				<div class="col-lg-4">
					<div class="panel-head">
						<h2 class="panel-title">Vị trí hiển thị của layout</h2>
						<div class="panel-description">
							<p>+ Website có các vị trí hiển thị riêng biệt cho từng layout</p>
							<p>+ Lựa chọn vị trí mà bạn muốn layout dưới đây hiển thị </p>
							
						</div>
					</div>
				</div>
				<div class="col-lg-8">
					<div class="ibox m0">
						<div class="ibox-content">
							<div class="row mb15">
								<div class="col-lg-12">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Tiêu đề</b></span>
										</label>
										<?php echo form_input('title', htmlspecialchars_decode(html_entity_decode(set_value('title'))), 'class="form-control " placeholder="" autocomplete="off"');?>
									</div>
								</div>
								<div class="col-lg-12">
									<div class="form-row">
										<label class="control-label text-left">
											<span><?php echo ($this->input->get('parentid') > 0) ? 'layout Cha' : 'Vị trí' ?> <b class="text-danger">(*)</b></span>
										</label>
										<?php 
											$catalogue = $this->Autoload_Model->_get_where(array(
												'select' => 'id, title, keyword',
												'table' => 'layout_catalogue',
												'order_by' => 'id asc'
											), TRUE);
											$listCat[] = 'Chọn vị trí hiển thị';
											if(isset($catalogue) && is_array($catalogue) && count($catalogue)){
												foreach($catalogue as $key => $val){
													$listCat[$val['id']] = $val['title'];
												}
											}
											echo form_dropdown('catalogueid', $listCat, set_value('catalogueid'), 'class="form-control m-b select3"');
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php 
						$layout = $this->input->post('layout'); 
					?>	
					<a style="color:#000;border-color:#c4cdd5;display:inline-block !important;margin-top:10px;" href="" title="" class="btn btn-default add-layout block m-b" data-item="<?php echo ($this->input->post('layout')) ? count($layout['title']) : 0; ?>">Thêm giao diện</a>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-lg-6">
					<div class="image-preview">
						<span class="image img-scaledown"><img src="<?php echo $this->general['preview_image'] ?>" alt="" /></span>
					</div>
				</div>
				<script type="text/javascript">
					var module = '<?php echo json_encode($this->configbie->data('connect')); ?>';
				</script>
				<div class="col-lg-6">
					<ul id="sortable" class="ui-sortable layout-list">
						
						<?php if(isset($layout['title']) && is_array($layout['title']) && count($layout['title'])){ ?>
						<?php foreach($layout['title'] as $key => $val){ ?>
						<li class="ui-state-default ui-sortable-handle" style="margin-bottom:15px;">
							<div class="ibox m0">
								<div class="ibox-title">
									<div class="row">
										<div class="col-lg-6">
											<div class="form-row">
												<input type="text" name="layout[title][]" value="<?php echo $val ?>" class="form-control title" style="font-weight:normal;" placeholder="Nhập Tiêu đề Block"  autocomplete="off">
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-row">
												<?php echo form_dropdown('layout[module][]', $this->configbie->data('connect'), $layout['module'][$key], 'class="select3 choose-module" data-item="'.$key.'" id="module-'.$key.'"');?>
											</div>
										</div>
									</div>
								</div>
								
								<?php $json = base64_encode(json_encode($layout['object'][$key][$layout['module'][$key]])); ?>
								<div class="ibox-content">
									<div class="row">
										<div class="col-lg-12">
											<div class="form-row">
												<?php echo form_dropdown('layout[object]['.$key.']['.$layout['module'][$key].'][]', '', (isset($catalogue)?$catalogue:NULL), 'class="form-control selectMultipe" data-json="'.$json.'" multiple="multiple" data-title="Nhập 2 kí tự để tìm kiếm.." data-item="'.$key.'" data-module="'.$layout['module'][$key].'"  style="width: 100%;" '); ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</li>
						<?php }}else{ ?>
						<li class="ui-state-default ui-sortable-handle layout-notification">
							<div class="ibox m0">
								<div class="ibox-content">
									<div class="" style="text-align:center;"><h4 style="font-weight:500;font-size:16px;color:#000">Danh sách này chưa có bất kì giao diện nào.</h4><p style="color:#555;margin-top:10px;">Hãy nhấn vào <span style="color:blue;">"Thêm giao diện"</span> để băt đầu thêm.</p></div>
								</div>
							</div>
						</li>
						<?php } ?>
					</ul>
				</div>
			</div>
			<hr>
			<div class="toolbox action clearfix">
				<div class="uk-flex uk-flex-middle uk-button pull-right">
					<button class="btn btn-primary btn-sm" name="create" value="create" type="submit">Lưu thay đổi</button>
				</div>
			</div>
		</div>
	</form>
	<?php $this->load->view('dashboard/backend/common/footer'); ?>
</div>
