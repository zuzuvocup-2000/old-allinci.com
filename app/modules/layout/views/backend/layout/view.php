<div id="page-wrapper" class="gray-bg dashbard-1 fix-wrapper">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Quản lý giao diện</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Quản lý giao diện</strong></li>
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
						<h2 class="panel-title">Danh sách giao diện</h2>
						<div class="panel-description">
							<p>+ Danh sách giao diện giúp bạn dễ dàng kiểm soát bố cục trang web của mình. Bạn có thể thêm các khối vào giao diện trong phần thiết lập giao diện</p>
						</div>
					</div>
				</div>
				<div class="col-lg-8">
					<table class="table table-striped table-bordered table-hover dataTables-example" >
						<thead>
							<tr>
								<th style="width:45px;">ID</th>
								<th>Tiêu đề</th>
								<th>Vị trí</th>
								<th style="width:150px;" class="text-center">Thao tác</th>
							</tr>
						</thead>
						<tbody id="ajax-content">
							<?php if(isset($listLayout) && is_array($listLayout) && count($listLayout)){ ?>
								<?php foreach($listLayout as $key => $val){ ?>
									<tr class="gradeX" id="cat-<?php echo $val['id']; ?>">
										<td><?php echo $val['id']; ?></td>
										<td><a class="" href="<?php echo site_url('layout/backend/layout/update/'.$val['id'].''); ?>" title=""><?php echo $val['title']; ?></a></td>
										<td><?php echo $val['catalogue_title']; ?></td>
										<td class="text-center">
											<a type="button" href="<?php echo site_url('layout/backend/layout/update/'.$val['id'].'') ?>" class="btn btn-primary"><i class="fa fa-edit"></i></a>
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
			</div>
			
		</div>
	</form>
	<?php $this->load->view('dashboard/backend/common/footer'); ?>
</div>
