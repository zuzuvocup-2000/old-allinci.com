<div id="page-wrapper" class="gray-bg dashbard-1">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Quản lý comment</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Quản lý comment</strong></li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<form method="post" action="" class="form-horizontal box" >
			<div class="row">
				<div class="col-lg-12">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5>Danh sách comment</h5>
							<div class="ibox-tools">
								<a class="collapse-link">
									<i class="fa fa-chevron-up"></i>
								</a>
								<a class="dropdown-toggle" data-toggle="dropdown" href="#">
									<i class="fa fa-wrench"></i>
								</a>
								<ul class="dropdown-menu dropdown-user">
									<li><a type="button" class="ajax-group-delete" data-title="Lưu ý: Khi bạn xóa comment, toàn bộ nội dung trong mục này sẽ bị xóa. Trong trường hợp khôi phục lại comment thì toàn bộ nội dung cũng sẽ không thể khôi phục được!" data-table="comment">Xóa tất cả</a>
									</li>
								</ul>
								<a class="close-link">
									<i class="fa fa-times"></i>
								</a>
							</div>
						</div>
						<div class="ibox-content" style="position:relative;">
							<div class="mb15">
								<div class="uk-flex uk-flex-middle uk-flex-space-between">
									<div class="wrap-select">
										<?php echo form_dropdown('perpage', $this->myconstant->list_data('perpage'), set_value('perpage'), 'class="form-control filter" id="perpage"');?>
									</div>
									<div class="uk-flex uk-flex-middle uk-flex-right">
										<div class="form-row wrap-select module-filter">
											<?php echo form_dropdown('module', $this->myconstant->list_data('module'), set_value('module'), 'class="form-control m-b module select3 filter"');?>
										</div>

										<div class="input-group wrap-select">
											<input type="text" class="form-control filter" id="keyword" name="search" value="" placeholder="Bạn muốn tìm gì?">
										</div>
										<div class="uk-button">
											<a href="<?php echo site_url('comment/backend/comment/create');?>" class="btn btn-danger btn-sm"><i class="fa fa-plus"></i> Thêm mới comment</a>
										</div>
									</div>
								</div>
							</div>
							<div id="display">
								<div class="text-small mb10">Hiển thị từ <?php echo $from;?> đến <?php echo $to;?> trên tổng số <?php echo $config['total_rows'];?> bản ghi</div>
							</div>
							<div class="table-responsive">
								<table class="table table-striped">
									<thead>
										<tr>
											<th class="text-left" style="width: 40px;">
												<input type="checkbox" id="checkbox-all">
												<label for="check-all" class="labelCheckAll"></label>
											</th>
											<th class="text-center">ID</th>
											<th class="text-left">Khách hàng</th>
											<th class="text-center">Điện thoại</th>
											<th class="text-left">Email</th>
											<th class="text-center">Module</th>
											<th class="text-center">Chi tiết</th>
											<th class="text-left text-comment">Comment</th>
											<th class="text-center">Đánh giá</th>
											<th class="text-center">Hiển thị</th>
											<th class="text-center">Thao tác</th>
										</tr>
									</thead>
									<tbody id='listComment'>
										<?php if(isset($listComment) && is_array($listComment) && count($listComment)){ ?>
											<?php  foreach($listComment as $key => $val){ ?>
												<?php 
													$detail = $this->Autoload_Model->_get_where(array(
														'select' => 'title',
														'table' => $val['module'],
														'where' => array('id' => $val['detailid']),
													));
												?>
											<tr>
												<td class="text-left" style="width: 40px;">
													<input type="checkbox" name="checkbox[]" value="<?php echo $val['id'];?>" class="checkbox-item">
													<label for="" class="label-checkboxitem"></label>
												</td>
												<td class="text-center"><?php echo $val['id'];?></td>
												<td class="text-left"><?php echo $val['fullname'];?></td>
												<td class="text-center"><?php echo ($val['phone'] != '')? $val['phone']:'-';?></td>
												<td class="text-left"><?php echo ($val['email'] != '')? $val['email']:'-';?></td>
												<td class="text-center"><?php echo $this->myconstant->get_data($val['module']);?></td>
												<td class="text-center"><a href="" target="_blank" title="<?php echo $detail['title'];?>"><i class="fa fa-link"></i></a><span>(0)</span></td>
												<td class="text-left text-comment"><span class="line-1"><?php echo ($val['comment'] != '')? $val['comment']:'-';?></span></td>
												<td class="text-center"><?php echo ($val['parentid'] > 0)? '-': '<span class="rating order-1" data-stars="5" data-default-rating="'.$val['rate'].'" disabled ></span>'?></td>
												<td>
													<div class="switch uk-flex uk-flex-center">
														<div class="onoffswitch">
															<input type="checkbox" <?php echo ($val['publish'] == '1')? 'checked':'';?> class="onoffswitch-checkbox publish" data-id="<?php echo $val['id'];?>" id="publish-<?php echo $val['id'];?>">
															<label class="onoffswitch-label" for="publish-<?php echo $val['id'];?>">
																<span class="onoffswitch-inner"></span>
																<span class="onoffswitch-switch"></span>
															</label>
														</div>
													</div>
												</td>
												<td class="uk-flex uk-flex-center">
													<a type="button" href="<?php echo site_url('comment/backend/comment/update/'.$val['id']);?>" class="btn btn-sm btn-edit btn-primary"><i class="fa fa-edit"></i></a>
													<a type="button" data-title="" data-name="" data-table="comment" data-id="<?php echo $val['id'];?>" class="btn btn-sm btn-trash btn-danger ajax-delete"><i class="fa fa-trash"></i></a>
												</td>
											</tr>
										<?php }} else{?>
												<tr>
													<td colspan="8">
														<small class="text-danger">Không có dữ liệu phù hợp</small>
													</td>
												</tr>
											<?php } ?>
									</tbody>
								</table>
							</div>
							<div id="pagination" class="uk-flex uk-flex-right">
								<?php echo (isset($paginationList)) ? $paginationList : ''; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
	<?php $this->load->view('dashboard/backend/common/footer'); ?>
</div>