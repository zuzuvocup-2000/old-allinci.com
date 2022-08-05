<div id="page-wrapper" class="gray-bg dashbard-1">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Quản lý Banner & Slide</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Banner & Slide</strong></li>
			</ol>
		</div>
	</div>
	
	
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-lg-3">
				<div class="ibox float-e-margins">
					<div class="ibox-content">
						<div class="file-manager">
							<h5>Hiển thị:</h5>
							<div class="hr-line-dashed"></div>
							<button class="btn btn-success btn-block btn-upload" data-catalogueid="0" data-toggle="modal" data-target="#myModal2">Upload hình ảnh</button>
							<div class="hr-line-dashed"></div>
							<div class="uk-flex uk-flex-middle uk-flex-space-between">
								<h5>Nhóm Slide</h5>
								<a style="font-size:11px;" href="" title="" data-toggle="modal"  data-target="#myModal">+ Thêm vị trí mới</a>
							</div>
						</div>
						<?php $slideGroup = $this->Autoload_Model->_get_where(array(
							'select' => 'id, title, keyword',
							'table' => 'slide_catalogue',
							'order_by' => 'id asc'
						), TRUE); ?>
						<?php if(isset($slideGroup) && is_array($slideGroup) && count($slideGroup)){ ?>
						<ul class="folder-list" id="folder-list" style="padding: 0">
							<?php foreach($slideGroup as $key => $val){ ?>
							<li>
								<div class="uk-flex uk-flex-middle uk-flex-space-between">
									<a href="" class="slide-catalogue" data-id="<?php echo $val['id']; ?>"><i class="fa fa-picture-o"></i> <?php echo $val['title']; ?></a>
									<a type="button" class="slide-group-delete ajax-delete" data-title="Lưu ý: Dữ liệu sẽ không thể khôi phục. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!" data-module="slide_catalogue" data-id="<?php echo $val['id']; ?>" style="color:#676a6c;font-size:11px;"> Xóa</a>
								</div>
							</li>
							<?php } ?>
						</ul>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="col-lg-9 animated fadeInRight" id="listData">
				<?php 
					$slideGroup = $this->Autoload_Model->_get_where(array(
						'select' => 'id, title',
						'table' => 'slide_catalogue',
						'order_by' => 'title asc'
					), TRUE);
					if(isset($slideGroup) && is_array($slideGroup) && count($slideGroup)){
						foreach($slideGroup as $key => $val){
							$slideGroup[$key]['slide'] = $this->Autoload_Model->_get_where(array(
								'select' => 'id, link, src, content, order, src, title',
								'table' => 'slide',
								'where' => array('catalogueid' => $val['id']),
								'order_by' => 'order desc, title asc, id desc'
							), TRUE);
						}
					}
				?>
				<?php if(isset($slideGroup) && is_array($slideGroup) && count($slideGroup)){ ?>
				<?php foreach($slideGroup as $key => $val){ ?>
				<div class="row" style="padding-left:5px;padding-right:5px;">
					<div class="col-lg-12">
						<h2 style="font-size:20px;font-weight:normal;margin: 0 0 10px 0;font-family:Segoe UI;"><?php echo $val['title']; ?></h2>
					</div>
					<?php if(isset($val['slide']) && is_array($val['slide']) && count($val['slide'])){ ?>
					<div class="col-lg-12">
						<?php foreach($val['slide'] as $key => $val){ ?>
						<div class="file-box" id="slide-<?php echo $val['id']; ?>">
							<div class="file">
								<div href="#">
									<span class="corner"></span>
									<div class="image">
										<img alt="image" class="img-responsive" src="<?php echo $val['src']; ?>">
									</div>
									<div class="file-name">
										<span style="font-size:10px;" class="name"><span style="font-weight:bold;">Chú thích</span>: <?php echo (!empty($val['title'])) ? $val['title'] : '<span class="text-danger">Chưa xác định</span>'; ?></span>
										<br>
										<a class="link" style="font-size:10px;color:#676a6c;" href=""><span style="font-weight:bold;">Link</span>: <?php echo (!empty($val['link'])) ? '<i style="color:blue;">'.$val['link'].'</i>' : '<span class="text-danger">Chưa xác định.</span>'; ?></a>
										<div class="file-action uk-flex uk-flex-middle uk-flex-space-between" style="margin-top:10px;">
											<a data-toggle="modal" data-json="<?php echo base64_encode(json_encode($val)) ?>"  data-target="#myModalEdit" href ="" title="" class="edit-slide" data-id="<?php echo $val['id']; ?>" style="font-size:10px;">Chỉnh sửa</a>
											<a type="button" class="ajax-delete" data-parent="file-box" data-title="Lưu ý: Dữ liệu sẽ không thể khôi phục. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!" data-module="slide" data-id="<?php echo $val['id']; ?>" style="color:red;font-size:10px;"> Xóa</a>
										</div>
									</div>
									
								</div>
							</div>
						</div>
						<?php } ?>
					</div>
					<?php } ?>
				</div>
				<hr>
				<?php }} ?>
				
			</div>
		</div>
	</div>
	<?php $this->load->view('dashboard/backend/common/footer'); ?>
</div>


<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content animated fadeIn">
			<div class="bg-loader"></div>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="fa fa-laptop modal-icon"></i>
				<h4 class="modal-title">Thêm mới nhóm Banner & Slide</h4>
				<small class="font-bold">Kích thước banner hiển thị tốt nhất 1920x760 pixel, các banner nên có kích thước bằng nhau.</small>
			</div>
			<div class="modal-body">
				<div class="alert alert-danger mt5"></div>
				<form class="m-t slide-group" role="form" method="post" action="">
					<div class="form-group">
						<label>Tên nhóm Slide</label> 
						<input type="text" placeholder="" id="title" class="form-control">
					</div>
					<div class="form-group">
						<label>Từ khóa</label> 
						<input type="text" placeholder="" id="keyword" class="form-control">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary add-group">Tạo mới</button>
			</div>
		</div>
	</div>
</div>

<div class="modal inmodal" id="myModal2" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog  modal-lg slide-container">
		<div class="modal-content animated fadeIn">
			<div class="bg-loader"></div>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="fa fa-laptop modal-icon"></i>
				<h4 class="modal-title">Upload hình ảnh</h4>
				<small class="font-bold">Kích thước banner hiển thị tốt nhất <span class="text-danger" style="font-size:16px;">1920x760</span> pixel, các banner nên có kích thước bằng nhau.</small>
			</div>
			<div class="modal-body">
				<div class="alert alert-danger mt5"></div>
				<form class="m-t slide-group" role="form" method="post" action="">
					<div class="form-group uk-flex uk-flex-middle">
						<label style="width:110px;margin-right:10px;">Chọn nhóm slide</label> 
						<div class="col-sm-6">
							<?php echo form_dropdown('catalogueid', $slideCatalogue, set_value('catalogueid'), 'class="form-control catalogueid" style=width:""');?>
						</div>
					</div>
					<div class="text-right" style="margin-bottom:5px;"><a onclick="openKCFinderSlide(this);return false;" href="" title="" class="upload-picture">Chọn hình</a></div>
					<div class="click-to-upload ">
						<div class="icon">
							<a type="button" class="upload-picture" onclick="openKCFinderSlide(this);return false;">
								<svg style="width:80px;height:80px;fill: #d3dbe2;margin-bottom: 10px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80"><path d="M80 57.6l-4-18.7v-23.9c0-1.1-.9-2-2-2h-3.5l-1.1-5.4c-.3-1.1-1.4-1.8-2.4-1.6l-32.6 7h-27.4c-1.1 0-2 .9-2 2v4.3l-3.4.7c-1.1.2-1.8 1.3-1.5 2.4l5 23.4v20.2c0 1.1.9 2 2 2h2.7l.9 4.4c.2.9 1 1.6 2 1.6h.4l27.9-6h33c1.1 0 2-.9 2-2v-5.5l2.4-.5c1.1-.2 1.8-1.3 1.6-2.4zm-75-21.5l-3-14.1 3-.6v14.7zm62.4-28.1l1.1 5h-24.5l23.4-5zm-54.8 64l-.8-4h19.6l-18.8 4zm37.7-6h-43.3v-51h67v51h-23.7zm25.7-7.5v-9.9l2 9.4-2 .5zm-52-21.5c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5zm0-8c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3zm-13-10v43h59v-43h-59zm57 2v24.1l-12.8-12.8c-3-3-7.9-3-11 0l-13.3 13.2-.1-.1c-1.1-1.1-2.5-1.7-4.1-1.7-1.5 0-3 .6-4.1 1.7l-9.6 9.8v-34.2h55zm-55 39v-2l11.1-11.2c1.4-1.4 3.9-1.4 5.3 0l9.7 9.7c-5.2 1.3-9 2.4-9.4 2.5l-3.7 1h-13zm55 0h-34.2c7.1-2 23.2-5.9 33-5.9l1.2-.1v6zm-1.3-7.9c-7.2 0-17.4 2-25.3 3.9l-9.1-9.1 13.3-13.3c2.2-2.2 5.9-2.2 8.1 0l14.3 14.3v4.1l-1.3.1z"></path></svg>
							</a>
						</div>
						<div class="small-text">Sử dụng nút <b>Chọn hình</b> để thêm hình.</div>
					</div>
					<div class="upload-list" style="padding:5px;margin-top:15px;">
						<div class="row">
							
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary add-slide">Tạo mới</button>
			</div>
		</div>
	</div>
</div>


<div class="modal inmodal" id="myModalEdit" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content animated bounceInRight">
			<div class="bg-loader"></div>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="fa fa-laptop modal-icon"></i>
				<h4 class="modal-title">Cập nhật Banner Slide</h4>
				<small class="font-bold">Kích thước banner hiển thị tốt nhất 1920x760 pixel, các banner nên có kích thước bằng nhau.</small>
			</div>
			<div class="modal-body">
				<div class="alert alert-danger mt5"></div>
				<form class="m-t update-group" role="form" method="post" action="">
					
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary update-slide">Cập nhật</button>
			</div>
		</div>
	</div>
</div>
