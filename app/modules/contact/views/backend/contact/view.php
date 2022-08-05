<div id="page-wrapper" class="gray-bg dashboard-1">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar');?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-12">
			<h2>Liên hệ</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Danh sách liên hệ</strong></li>
			</ol>
		</div>
	</div>
	<!-- --------------------------- -->
	<div class="wrapper wrapper-content animated fadeInRight">
		<form method="post" action="" class="form-horizontal box">
			<div class="row">
				<div class="col-lg-12">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5>DANH SÁCH LIÊN HỆ</h5>
							<div class="ibox-tools">
								<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-wrench"></i></a>
								<ul class="dropdown-menu dropdown-contact">
									<li><a type="button" class="ajax-group-delete" data-title="Lưu ý: Khi bạn thực hiện thao tác này, toàn bộ dữ liệu sẽ không thể khôi phục được. Hãy chắc chắn rằng bạn muốn thực hiện chức năng này!" data-module="contact">Xóa tất cả</a></li>
									<li><a type="button" class="ajax-group-viewed" data-module="contact">Đánh dấu là đã đọc</a></li>
									<li><a type="button" class="ajax-group-bookmark" data-module="contact">Đánh dấu là quan trọng</a></li>
								</ul>
								<a class="close-link"><i class="fa fa-times"></i></a>
							</div>
						</div>
						<div class="ibox-content" style="position:relative;">
							<div class="table-responsive">
								<div class=" mb10 uk-flex uk-flex-middle uk-flex-space-between">
									<div class="row-left">
										<?php echo form_dropdown('perpage', $this->configbie->data('perpage'), set_value('perpage') ,'class="form-control input-sm perpage filter"  data-url="'.site_url('contact/backend/catalogue/view').'"'); ?>
									</div>
									<div class="row-right">
										<div class="uk-flex uk-flex-middle uk-flex-space-between">
											<?php $listGroup = get_list(array(
												'select' => 'id, title',
												'table' => 'contact_catalogue',
												'field' => 'id',
												'text' => 'Chọn nhóm liên hệ',
												'value' => 'title',
												'order_by' => 'title asc',
											));
											?>
											<div class="perpage contact-catalogueid uk-flex uk-flex-middle mr10">
												<?php echo form_dropdown('catalogueid', $listGroup , set_value('catalogueid',$this->input->get('catalogueid')) ,'class="form-control input-sm  select2 filter catalogueid" style="width:200px !important"'); ?>
											</div>

											<div class="uk-search uk-flex uk-flex-middle mr10">
												<form class="uk-form" id="search">
													<input type="search" name="keyword"  class="keyword form-control input-sm filter" placeholder="Nhập từ khóa tìm kiếm ..." autocomplete="off" value="<?php echo $this->input->get('keyword'); ?>" >
												</form>
											</div>
										</div>
									</div>
								</div>
								<div class="uk-flex uk-flex-middle uk-flex-space-between mb10">
									<div class="text-small">Hiển thị từ <?php echo $from;?> đến <?php echo $to;?> trên tổng số <?php echo $config['total_rows']; ?> bản ghi</div>
									<div class="text-small text-danger">*Sắp xếp Vị trí hiển thị theo thứ tự thời gian</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTables-example" >
									<thead>
										<tr>
											<th style="width:40px;" class="text-center">
												<input type="checkbox" id="checkbox-all">
												<label for="check-all" class="labelCheckAll"></label>
											</th>
											<th style="width:40px;"></th>
											<th style="width:40px;"></th>
											<th style="width:250px;">Thông tin người gửi</th>
											<th>Nội dung</th>
											<th style="width:100px;" class="text-center">Thời gian</th>
											<th style="width:110px;" class="text-center">Thao tác</th>
										</tr>
									</thead>
									<tbody id="ajax-content">
										<!-- <?php
									//	echo '<pre>';
										//print_r($listContact);die();

										?> -->
										<?php if(isset($listContact) && is_array($listContact) && count($listContact)){ ?>
											<?php foreach($listContact as $key => $val){?>
												<tr class="gradeX" id="">
													<td class="pd-top text-center pdt25" >
														<input type="checkbox" name="checkbox[]" value="<?php echo $val['id']; ?>" class="checkbox-item">
														<label for="" class="label-checkboxitem"></label>
													</td>
													<td class="pd-top text-center pdt25"><i class="fa fa-star <?php echo ($val['bookmark'] == 1) ? ' text-yellow' : '' ; ?>" data-id="<?php echo $val['id'];?>" data-bookmark="<?php echo $val['bookmark'] ?>"></i>
													</td>
													<td class="pd-top text-center pdt25">
														<?php if(strlen($val['file']) > 0){ ?>
															<i class="fa fa-paperclip"></i>
														<?php }?>
													</td>
													<td class="pd-top">
														<a data-id="<?php echo $val['id'];?>" data-viewed="<?php echo $val['viewed'] ?>" class="detail-contact title-1 <?php echo ($val['viewed'] == 0) ? ' text-blue' : '' ; ?>" href="" ><?php echo $val['fullname']; ?></a>
														<div class="">
															<a data-id="<?php echo $val['id'];?>" data-viewed="<?php echo $val['viewed'] ?>" href="" class="detail-contact subtitle-1 <?php echo ($val['viewed'] == 0) ? ' text-blue-1' : '' ; ?>" title="<?php echo $val['phone']; ?>" class="subtitle-1">
																<?php echo $val['phone']; ?>
															</a>
														</div>
													</td>
													<td class="pd-top">
														<a data-id="<?php echo $val['id'];?>" data-viewed="<?php echo $val['viewed'] ?>" class="maintitle detail-contact title-1 <?php echo ($val['viewed'] == 0) ? ' text-blue' : '' ; ?>" style="" href="" title="">
															<?php echo $val['subject']; ?>
														</a>
														<div class="">
															<a data-id="<?php echo $val['id'];?>" data-viewed="<?php echo $val['viewed'] ?>" href="" class="detail-contact subtitle-1 <?php echo ($val['viewed'] == 0) ? ' text-blue-1' : '' ; ?>" title="">
																<?php echo $val['message']; ?>
															</a>
														</div>
													</td>
													<td class="pd-top text-center"><?php echo gettime($val['created'],'d/m/Y h:i:s'); ?></td>
													<td class="text-center actions" style="padding-top:18px;">
														<a  type="button" class="btn btn-danger btn-delete ajax-delete"  data-id="<?php echo $val['id'];?>" data-title="Lưu ý: Khi bạn xóa nhóm, toàn bộ thành viên trong nhóm này sẽ bị xóa. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!" data-router="" data-module="contact" data-child=""><i class="fa fa-trash"></i></a>
													</td>
												</tr>
											<?php }} else{ ?>
												<tr>
													<td colspan="9"><small class="text-danger">Không có dữ liệu phù hợp</small></td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
								<div id="pagination" class="p-pagination">
									<?php echo (isset($PaginationList))? $PaginationList :'';?>
								</div>
							</div>
						</div>

					</div>
				</div>

				<!-- --------------------------- -->

			</div>
		</form>
		<?php $this->load->view('dashboard/backend/common/footer'); ?>
	</div>
