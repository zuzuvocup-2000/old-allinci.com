<div id="page-wrapper" class="gray-bg dashbard-1 fix-wrapper">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Thêm mới Menu</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Thêm mới Menu</strong></li>
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
						<h2 class="panel-title">Vị trí hiển thị của menu</h2>
						<div class="panel-description">
							<p>+ Website có các vị trí hiển thị riêng biệt cho từng menu</p>
							<p>+ Lựa chọn vị trí mà bạn muốn menu dưới đây hiển thị </p>
						</div>
					</div>
				</div>
				<div class="col-lg-7">
					<div class="ibox m0">
						<div class="ibox-content">
							<div class="row mb15">
								<div class="col-lg-12">
									<div class="form-row">
										<label class="control-label text-left">
											<span><?php echo ($this->input->get('parentid') > 0) ? 'Menu Cha' : 'Vị trí' ?> <b class="text-danger">(*)</b></span>
										</label>
										<?php 
											$parentid = (int)$this->input->get('parentid');
											if($parentid <= 0){
												$catalogue = $this->Autoload_Model->_get_where(array(
													'select' => 'id, title, keyword',
													'table' => 'navigation_catalogue',
												), TRUE);
												$listCat[] = 'Chọn vị trí hiển thị';
												if(isset($catalogue) && is_array($catalogue) && count($catalogue)){
													foreach($catalogue as $key => $val){
														$listCat[$val['id']] = $val['title'];
													}
												}
												echo form_dropdown('catalogueid', $listCat, set_value('catalogueid'), 'class="form-control m-b select3"');
											}else{
												echo form_dropdown('parentid', $this->nestedsetbie->dropdown(), set_value('parentid', $parentid), 'class="form-control m-b select3"');
											}
										?>
										<?php  ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-lg-5">
					<div class="panel-head">
						<h2 class="panel-title">Tạo menu</h2>
						<div class="panel-description">
							<p>+ Cài đặt Menu mà bạn muốn hiển thị</p>
							<p><small class="text-danger">* Khi khởi tạo menu bạn phải chắc chắn rằng đường dẫn của menu có hoạt động. Đường dẫn trên website được khởi tạo tại các module: Bài viết, Sản phẩm, Dự án, ...</small></p>
							<p><small class="text-danger">* Tiêu đề và đường dẫn của menu không được bỏ trống.</small></p>
							<p><small class="text-danger">* Hệ Thống chỉ hỗ trợ tối đa 5 cấp menu.</small></p>
							<a style="color:#000;border-color:#c4cdd5;display:inline-block !important;" href="" title="" class="btn btn-default add-menu m-b m-r">Thêm đường dẫn</a>
							<a style="" href="<?php echo site_url('navigation/backend/navigation/view') ?>" title="" class="btn btn-info m-b m-r">Trở lại</a>
						</div>
					</div>
				</div>
				<div class="col-lg-7">
					<div class="ibox m0">
						<div class="ibox-content">
							<div class="row">
								<div class="col-lg-4">
									<div class="form-row">
										<label style="margin:0;">Tiêu đề</label>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-row">
										<label style="margin:0;">Đường dẫn</label>
									</div>
								</div>
								<div class="col-lg-2">
									<div class="form-row">
										<label style="margin:0;">Thứ tự</label>
									</div>
								</div>
							</div>
							<div class="hr-line-dashed" style="margin:10px 0;"></div>
							<div class="menu-list">
								<?php $menu = $this->input->post('menu'); ?>
								<?php if(isset($menu['title']) && is_array($menu['title']) && count($menu['title'])){ ?>
								<?php foreach($menu['title'] as $key => $val){ ?>
								<div class="row mb15">
									<div class="col-lg-4">
										<div class="form-row">
											<input type="text" placeholder="" name="menu[title][]" value="<?php echo $val; ?>" class="form-control" >
											
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-row">
											<input type="text" placeholder="" name="menu[link][]" value="<?php echo $menu['link'][$key]; ?>" class="form-control" >
										</div>
									</div>
									<div class="col-lg-2">
										<div class="form-row">
											<input type="text" style="text-align:right;" placeholder="" name="menu[order][]" value="<?php echo $menu['order'][$key]; ?>" class="form-control" >
										</div>
									</div>
									<div class="col-lg-2">
										<div class="form-row" style="text-align:right;margin-top:10px;">
											<a class="delete-menu image img-scaledown" style="height:12px;"><img src="template/close.png" /></a>
										</div>
									</div>
								</div>
								<?php }} else{ ?>
								<?php 
									$listMenu = $this->Autoload_Model->_get_where(array(
										'select' => 'id, title, link, order',
										'table' => 'navigation',
										'where' => array('parentid' => (int)$this->input->get('parentid')),
										'order_by' => 'order desc',
									), TRUE);
								?>
								<?php if(isset($listMenu) && is_array($listMenu) && count($listMenu) && $parentid > 0){ ?>
								<?php foreach($listMenu as $key => $val){ ?>
								<div class="row mb15 menu-wrapper">
									<div class="col-lg-4">
										<div class="form-row">
											<input type="text" placeholder="" value="<?php echo $val['title']; ?>" name="menu[title][]" class="form-control" >
											<input type="hidden" placeholder="" value="<?php echo $val['id']; ?>" name="menu[id][]" class="form-control" >
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-row">
											<input type="text" placeholder="" value="<?php echo $val['link']; ?>" name="menu[link][]" class="form-control" >
										</div>
									</div>
									<div class="col-lg-2">
										<div class="form-row">
											<input type="text" style="text-align:right;" value="<?php echo $val['order']; ?>" placeholder="" name="menu[order][]" value="0"  class="form-control" >
										</div>
									</div>
									<div class="col-lg-2">
										<div class="form-row" style="text-align:right;margin-top:10px;">
											<a class="delete-menu image img-scaledown ajax-delete" data-module="navigation" data-parent="menu-wrapper" data-title="Menu sẽ bị xóa bỏ khi bạn đồng ý thực hiện thao tác này." data-id="<?php echo $val['id'] ?>" style="height:12px;"><img src="template/close.png" /></a>
										</div>
									</div>
								</div>
								<?php }}else{ ?>
									<div class="menu-notification" style="text-align:center;"><h4 style="font-weight:500;font-size:16px;color:#000">Danh sách liên kết này chưa có bất kì đường dẫn nào.</h4><p style="color:#555;margin-top:10px;">Hãy nhấn vào <span style="color:blue;">"Thêm đường dẫn"</span> để băt đầu thêm.</p></div>
								<?php } ?>
								<?php } ?>
							</div>
						</div>
					</div>
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
