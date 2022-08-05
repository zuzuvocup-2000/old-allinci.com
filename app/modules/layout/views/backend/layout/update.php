<div id="page-wrapper" class="gray-bg dashbard-1">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Cập nhật layout</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Cập nhật layout</strong></li>
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
										<?php echo form_input('title', htmlspecialchars_decode(html_entity_decode(set_value('title', $detailLayout['title']))), 'class="form-control " placeholder="" autocomplete="off"');?>
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
											echo form_dropdown('catalogueid', $listCat, set_value('catalogueid', $detailLayout['catalogueid']), 'class="form-control m-b select3"');
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php 
						$layout = $this->input->post('layout'); 
						if(isset($layout) && is_array($layout) && count($layout)){
							$listLayout = $layout;
						}else{
							$listLayout = json_decode($detailLayout['data_original'], TRUE);
						}
					?>	
					<?php if($this->auth['account'] == 'htvietnam'){ ?>
					<div class="uk-flex uk-flex-middle" style="margin-top:10px;">
						<a style="color:#000;border-color:#c4cdd5;display:inline-block !important;;margin-bottom:0;margin-right:10px;" href="" title="" class="btn btn-default add-layout block m-b" data-item="<?php echo count($listLayout['title']); ?>">Thêm giao diện</a>
						<div class="toolbox action clearfix">
							<div class="uk-flex uk-flex-middle uk-button pull-right">
								<button class="btn btn-primary btn-sm" name="update" value="update" type="submit">Lưu thay đổi</button>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-lg-6">
					<div class="image-preview">
						<span class="image img-cover"><img src="<?php echo ($detailLayout['catalogueid'] == 5) ? $this->general['preview_image'] : $this->general['preview_image_sidebar']; ?>" alt="" /></span>
					</div>
				</div>
				<script type="text/javascript">
					var module = '<?php echo json_encode($this->configbie->data('connect')); ?>';
				</script>
				<div class="col-lg-6">
					<ul id="sortable" class="ui-sortable layout-list">
						<?php if(isset($listLayout['title']) && is_array($listLayout['title']) && count($listLayout['title'])){ ?>
						<?php foreach($listLayout['title'] as $key => $val){ ?>
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
												<?php echo form_dropdown('layout[module][]', $this->configbie->data('connect'), $listLayout['module'][$key], 'class="select3 choose-module" data-item="'.$key.'" id="module-'.$key.'"');?>
											</div>
										</div>
									</div>
								</div>
								
								<?php 
									if(isset($listLayout['object'][$key][$listLayout['module'][$key]])){
										$json = $listLayout['object'][$key][$listLayout['module'][$key]] ;
										if(isset($json) && check_array($json)){
											 $json = base64_encode(json_encode($json));
										}
									}
									
								 ?>
								<?php  ?>
								<div class="ibox-content">
									<div class="row">
										<div class="col-lg-12">
											<div class="form-row">
												<?php echo form_dropdown('layout[object]['.$key.']['.$listLayout['module'][$key].'][]', '', (isset($catalogue)?$catalogue:NULL), 'class="form-control selectMultipe" data-json="'.$json.'" multiple="multiple" data-title="Nhập 2 kí tự để tìm kiếm.." data-item="'.$key.'" data-module="'.$listLayout['module'][$key].'"  style="width: 100%;" '); ?>
											</div>
										</div>
										<?php if($this->auth['account'] == 'htvietnam'){ ?>
											<div class="col-sm-2">
												<a class="delete-layout" style="margin-top:10px;color:red;display:inline-block;">Xóa Khối</a>
											</div>
										<?php } ?>
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
					<button class="btn btn-primary btn-sm" name="update" value="update" type="submit">Lưu thay đổi</button>
				</div>
			</div>
		</div>
	</form>
	<?php $this->load->view('dashboard/backend/common/footer'); ?>
</div>
