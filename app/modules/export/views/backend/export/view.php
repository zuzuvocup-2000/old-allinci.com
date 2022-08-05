<div id="ajax_loading_data" data-module='export'>
<div id="page-wrapper" class="gray-bg dashbard-1">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/header'); ?>
	</div>
	 <div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Quản lý đơn xuất hàng</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Quản lý đơn xuất hàng</strong></li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Danh sách đơn xuất hàng</h5>
					<div class="ibox-tools">
						<a class="collapse-link">
							<i class="fa fa-chevron-up"></i>
						</a>
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">
							<i class="fa fa-wrench"></i>
						</a>
						<ul class="dropdown-menu dropdown-customer">
							
						</ul>
						<a class="close-link">
							<i class="fa fa-times"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content" style="position:relative;">
					<div class="table-responsive">
						<form action="" method="get" id="form">
						<div class="uk-flex uk-flex-middle uk-flex-space-between">
							<div class="perpage">
								<div class="uk-flex uk-flex-middle m-b-sm">
									<?php echo form_dropdown('perpage', $this->configbie->data('perpage'), set_value('perpage',$this->input->get('perpage')) ,'class="form-control input-sm "id="perpage"  data-url="'.site_url('customer/backend/catalogue/view').'"'); ?>
								</div>
							</div>
							<div class="toolbox">
								<div class="uk-flex uk-flex-middle uk-flex-space-between">
									<div class="uk-search uk-flex uk-flex-middle m-r-sm">
										
										<div style="width:145px" class="mr10">Tìm kiếm</div>
										<?php echo form_dropdown('construction', dropdown_list('construction','chọn công trình'), set_value('construction'), 'id="stock" class="form-control select3  m-r-sm form-select input-sm"');?>
										<input type="search" id="keyword" name="keyword"  class="form-control input-sm" placeholder="Tìm theo mã sản phẩm" value="<?php echo $this->input->get('keyword'); ?>" aria-controls="DataTables_Table_0" data-toggle="popover" autocomplete="off" data-placement="auto top" data-content="Nhập từ khóa muốn tìm kiếm sau đó ấn Enter" data-original-title="" aria-describedby="popover871828">
									</div>
									<a href="<?php echo site_url('export/backend/export/excel'); ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Trích xuất Excel</a>
									<button type="text" class="hidden submit"></button>
								</div>
							</div>
						</div>
						<?php echo form_input('page', set_value('page'), ' id="page" class="hidden"');?>
						</form>
						<div class="text-small mb10">Hiển thị từ <?php echo $from; ?> đến <?php echo $to ?> trên tổng số <?php echo $config['total_rows']; ?> bản ghi</div>
						<table class="table table-striped table-bordered table-hover dataTables-example" >
							<thead>
								<tr>
									<th>
										<input type="checkbox" id="checkbox-all">
										<label for="check-all" class="labelCheckAll"></label>
									</th>
									<th class="text-center">ID</th>
									<th class="text-center">Mã công trình</th>
									<th class="text-center">Tên Công trình</th>
									<th>NV KD</th>
									<th>Loại hàng</th>
									<th>Thợ</th>
									<th class="text-center">Trạng thái</th>
									<th class="text-center">Thao tác</th>
								</tr>
							</thead>
							<tbody id="listData">
								<?php if(isset($listData) && is_array($listData) && count($listData)){ ?>
								<?php foreach($listData as $key => $val){ ?>
								<tr class="gradeX choose">
									<td>
										<input type="checkbox" name="checkbox[]" value="<?php echo $val['id']; ?>" class="checkbox-item">
										<label for="" class="label-checkboxitem"></label>
									</td>
									<?php 
										$construction = $this->Autoload_Model->_get_where(array(
											'table' => 'construction',
											'select' => 'code, worker, title, (SELECT fullname FROM customer WHERE customer.id = construction.customerid) as customer,(SELECT fullname FROM user WHERE user.id = construction.userid_charge) as userid_charge',
											'where'=> array('id'=> $val['constructionid'])
										));
										$worker=json_decode($construction['worker']);
										$fullname_worker= $this->Autoload_Model->_get_where(array(
											'table'=>'user',
											'select'=>'id, fullname,',
											'where_in_field'=>'id',
											'where_in'=>($worker==0) ? array(''):$worker,
										),true);
										
									 ?>
									<td class="text-center"><?php echo $val['id']; ?></td>
									<td class="text-center">
										<?php  
											echo '<a href="'.site_url("construction/backend/construction/update/".$val['constructionid']).'">'.$construction['code'].'</a>';
										?></td>
									<td class="text-center">
										<?php echo $construction['title']; ?>
									</td>
									<td><?php echo $construction['userid_charge']; ?></td>
									<td><?php echo $val['stock']; ?></td>
									<td>
										<?php 
										foreach( $fullname_worker as $key =>$sub ){
											echo '<span class="label label-success-light pull-left m-r-xs ">'.$sub['fullname'].'</span>';
										}; ?>
									</td>
									<td class="text-center" id="status"><?php echo ($val['status']==0) ? '<span class=" label label-warning-light  m-r-xs m-">Chờ xuất hàng</span>' : '<span class="label label-success-light m-r-xs m-">Đã xuất hàng</span>' ; ?></td>
									<td class="text-center">
										<a type="button" href="<?php echo site_url('export/backend/export/update/'.$val['id']) ?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
									</td>
								</tr>
								<?php }}else{ ?>
								<tr>
									<td colspan="10"><small class="text-danger">Không có dữ liệu phù hợp</small></td>
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
</div>