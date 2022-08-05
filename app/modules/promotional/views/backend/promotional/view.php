<div id="page-wrapper" class="gray-bg dashbard-1">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Quản lý chương trình khuyến mại</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Quản lý chương trình khuyến mại</strong></li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Danh sách chương trình khuyến mại</h5>
						<div class="ibox-tools">
							<a class="collapse-link">
								<i class="fa fa-chevron-up"></i>
							</a>
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
								<i class="fa fa-cog"></i>
							</a>
							<ul class="dropdown-menu dropdown-promotional">
								<li><a type="button" class="ajax_delete_promotional_all" data-title="Lưu ý: Khi bạn thực hiện thao tác này, toàn bộ dữ liệu sẽ không thể khôi phục được. Hãy chắc chắn rằng bạn muốn thực hiện chức năng này!" data-module="promotional">Xóa tất cả</a>
								</li>
							</ul>
							<a class="close-link">
								<i class="fa fa-times"></i>
							</a>
						</div>
					</div>
					<div class="ibox-content" style="position:relative;">
						<div class="table-responsive">
							<div class="uk-flex uk-flex-middle uk-flex-space-between">
								<div class="perpage">
									<div class="uk-flex uk-flex-middle mb10">
										<?php echo form_dropdown('perpage', $this->configbie->data('perpage'), set_value('perpage',$this->input->get('perpage')) ,'class="form-control input-sm perpage filter"  data-url="'.site_url('promotional/backend/promotional/view').'"'); ?>
									</div>
								</div>
								<div class="toolbox">
									<div class="uk-flex uk-flex-middle uk-flex-space-between">
										<div class="perpage cat-wrap">
											
										</div>
										<div class="uk-search uk-flex uk-flex-middle mr10">
											<form class="uk-form" id="search">
												
											</form>
										</div>
										<div class="uk-button">
											<a href="<?php echo site_url('promotional/backend/promotional/create'); ?>" class="btn btn-danger btn-sm"><i class="fa fa-plus"></i> Thêm chương trình khuyến mại mới</a>
										</div>
									</div>
								</div>
							</div>
							<div class="uk-flex uk-flex-middle uk-flex-space-between mt10">
								<div class="col-sm-3 p-l-none">
									<?php echo form_dropdown('catalogue',  array(
										'-1' => 'Chọn kiểu CTKM',
										'CP' => 'Mã khuyến mại ( Coupon )',
										'KM' => 'Chương trình khuyến mại',
									) , set_value('catalogue'), 'class="form-control m-b select3NotSearch filter"');?>
								</div>
								<div class="col-sm-3">
									<?php echo form_dropdown('publish', $this->configbie->data('publish') , set_value('publish'), 'class="form-control m-b select3NotSearch filter"');?>
								</div>
								<div class="col-sm-3">
									<?php echo form_dropdown('action', array(-1 =>'Chọn trạng thái hoạt động', 1 => ' Đang hoạt động', 0 => "Không hoạt động") , set_value('publish'), 'class="form-control m-b select3NotSearch filter"');?>
								</div>
								<div class="col-sm-3 p-r-none">
									<input type="search" name="keyword"  class="keyword form-control input-sm filter" placeholder="Nhập từ khóa tìm kiếm tên KM..." autocomplete="off" value="<?php echo $this->input->get('keyword'); ?>" >
								</div>
							</div>
							<div class="uk-flex uk-flex-middle uk-flex-space-between mt10">
								<div class="text-small">Hiển thị từ <?php echo $from; ?> đến <?php echo $to ?> trên tổng số <?php echo $config['total_rows']; ?> bản ghi</div>
								<div class="text-small text-danger">*Sắp xếp Vị trí hiển thị theo quy tắc: Số lớn hơn được ưu tiên hiển thị trước. </div>
							</div>
							<table class="table table-bordered table-hover " >
								<thead>
									<tr>
										<th style="width:40px;" class="text-center">
											<input type="checkbox" id="checkbox-all">
											<label for="check-all" class="labelCheckAll"></label>
										</th>
										<th style="">Tiêu đề</th>
										<th style="width:110px;" class="text-center">Từ ngày</th>
										<th style="width:110px;" class="text-center">Đến ngày</th>
										<th style="width:70px;" class="text-center">Nổi bật</th>
										<th style="width:80px;" class="text-center">Trạng thái</th>
										<th style="width:100px;" class="text-center">Thao tác</th>
									</tr>
								</thead>
								<tbody id="ajax-content">
									<?php if(isset($listpromotional) && is_array($listpromotional) && count($listpromotional)){ ?>
										<?php foreach($listpromotional as $key => $val){ ?>
											<?php 

												$current = gmdate('Y-m-d H:i:s', time() + 7*3600);
												
											?>
											<tr class="gradeX " id="post-<?php echo $val['id']; ?>">
												<td class="text-center">
													<input type="checkbox" name="checkbox[]" value="<?php echo $val['id']; ?>" data-router="<?php echo $val['canonical'] ?>" class="checkbox-item">
													<label for="" class="label-checkboxitem"></label>
												</td>

												<td>
													<div class="show-block-promotion" style="background: <?php echo ($val['catalogue'] == 'CP') ? '#4da9c1' : '#0a3d62' ; ?>">
														<div class="inner">
															<div class="title">
																<?php 
																	echo '<div>'.$val['title'].'</div>';
																	$limmit_code = ($val['limmit_code'] == -1)? ' Không giới hạn số lần ' : $val['limmit_code'];
																	echo (($val['catalogue'] == 'CP') ? ' Mã Coupon: '.$val['code'].'('.$limmit_code.')' : ''); 
																?>
															</div>
															<div class="detail">
																<?php echo $val['detail']; ?>
															</div>
															<div class="user_common">
																<?php echo $val['use_common']; ?>
															</div>
														</div>
													</div>
												</td>

												<td  class="text-center">
													<?php 
														if ($val['start_date'] == '0000-00-00 00:00:00'){
															echo settime($val['created'], 'date');
														}else{
															echo settime($val['start_date'], 'date');
														}
													 ?>
												</td>
												<td  class="text-center">
									 				<?php 
														if ($val['start_date'] == '0000-00-00 00:00:00'){
															echo ' Không hết hạn';
														}else{
															echo 'Đến '.settime($val['end_date'], 'date');
														}
													 ?>
												</td>
												<td class="text-center hightlight" data-id="<?php echo $val['id'] ?>">
													<?php 
														if($val['hightlight'] == 1){
															echo '<img src="template/acore/image/publish-check.png"  data-status="1" >';
														}else{
															echo '<img src="template/acore/image/publish-deny.png"  data-status="0" ">';
														}
													 ?>
												</td>

												<td>
													<div class="switch">
														<div class="onoffswitch">
															<input type="checkbox" <?php echo ($val['publish'] == 0) ? '' : 'checked=""'; ?> class="onoffswitch-checkbox publish" data-id="<?php echo $val['id']; ?>" id="publish-<?php echo $val['id']; ?>">
															<label class="onoffswitch-label" for="publish-<?php echo $val['id']; ?>">
																<span class="onoffswitch-inner"></span>
																<span class="onoffswitch-switch"></span>
															</label>
														</div>
													</div>
												</td>
												<td class="text-center">
													<a type="button" href="<?php echo site_url('promotional/backend/promotional/update/'.$val['id'].'') ?>" class="btn btn-primary"><i class="fa fa-edit"></i></a>
													<a type="button" class="btn btn-danger ajax_delete_promotional" data-title="Lưu ý: Dữ liệu sẽ không thể khôi phục. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!" data-router="<?php echo $val['canonical']; ?>" data-id="<?php echo $val['id'] ?>" data-module="<?php echo $val['module'] ?>"><i class="fa fa-trash"></i></a>
												</td>
											</tr>
										<?php }}else{ ?>
											<tr>
												<td colspan="9"><small class="text-danger">Không có dữ liệu phù hợp</small></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
							<div id="pagination">
								<?php echo (isset($PaginationList)) ? $PaginationList : ''; ?>
							</div>
							<div class="loader"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php $this->load->view('dashboard/backend/common/footer'); ?>
	</div>