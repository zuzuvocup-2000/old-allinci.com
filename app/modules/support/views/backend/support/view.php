<div id="page-wrapper" class="gray-bg dashboard-1">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar');?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<h2>Hỗ trợ trực tuyến</h2>
		<ol class="breadcrumb">
			<li>
				<a href="<?php echo site_url('admin'); ?>">Home</a>
			</li>
			<li class="active"><strong>Danh sách người hỗ trợ</strong></li>
		</ol>
	</div>
	<!-- --------------------------- -->

	<div class="wrapper wrapper-content animated fadeInRight">
		<form method="post" action="" class="form-horizontal box">
			<div class="row">
				<div class="toolbox mb10 uk-flex uk-flex-middle uk-flex-space-between col-lg-12">
					<div class="perpage row-left">
						<?php echo form_dropdown('perpage', $this->configbie->data('perpage'), set_value('perpage') ,'class="form-control input-sm filter" id="perpage"  data-url="'.site_url('support/backend/support/view').'"'); ?>
					</div>
					<div class="row-right">
						<div class="uk-flex uk-flex-middle uk-flex-space-between">
							<?php $listGroup = get_list(array(
								'select' => 'id, title',
								'table' => 'support_catalogue',
								'field' => 'id',
								'text' => 'Chọn nhóm hỗ trợ',
								'value' => 'title',
								'order_by' => 'title asc',
							));
							?>
							<div class="perpage support-catalogueid uk-flex uk-flex-middle mr10">
								<?php echo form_dropdown('catalogueid', $listGroup , set_value('catalogueid',$this->input->get('catalogueid')) ,'class="form-control input-sm  select2 filter catalogueid" style="width:200px !important"'); ?>
							</div>
							<div class="uk-search uk-flex uk-flex-middle mr10">
								<form class="uk-form" id="search">
									<input type="search" name="keyword"  class="keyword form-control input-sm filter" placeholder="Nhập từ khóa tìm kiếm ..." autocomplete="off" value="<?php echo $this->input->get('keyword'); ?>" >
								</form>
							</div>
							<div class="uk-button">
								<a href="<?php echo site_url('support/backend/support/create'); ?>" class="btn btn-create btn-danger"><i class="fa fa-plus"></i> Thêm mới thành viên hỗ trợ</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row" id="ajax-content">
				<?php if(isset($listSupport) && is_array($listSupport) && count($listSupport)){ ?>
					<?php foreach($listSupport as $key => $val){ ?>
						<?php $image = getthumb($val['image']);?>
						<div class="col-lg-4">
							<div class="contact-box">
								<div class="col-sm-4">
									<div class="text-center">
										<a href="<?php echo site_url('support/backend/support/update/'.$val['id'].'') ?>" title="" class="image img-cover">
											<img alt="" src="<?php echo $image; ?>">
										</a>
										<div class="m-t-xs text-center">
											<div class="support-view switch">
												<div class="onoffswitch">
													<input type="checkbox" <?php echo ($val['publish'] == 1) ? 'checked=""' : ''; ?> class="onoffswitch-checkbox publish" data-id="<?php echo $val['id']; ?>" id="publish-<?php echo $val['id']; ?>">
													<label class="onoffswitch-label" for="publish-<?php echo $val['id']; ?>">
														<span class="onoffswitch-inner"></span>
														<span class="onoffswitch-switch"></span>
													</label>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="col-sm-8">
									<h3 class="name-support"><?php echo $val['fullname']; ?></h3>
									<address class="box-info">
										<p><strong class="text-blue"><?php echo $val['catalogueTitle']; ?></strong></p>
										<p><strong class="text-blue">SDT:</strong> <?php echo $val['phone']; ?></p>
										<p><strong class="text-blue">Email:</strong> <?php echo $val['email']; ?></p>
										<p><strong class="text-blue">Skype:</strong> <?php echo $val['skype']; ?></p>
										<p><strong class="text-blue">Zalo:</strong> <?php echo $val['zalo']; ?></p>
										<a type="button" title="chỉnh sửa thông tin" href="<?php echo site_url('support/backend/support/update/'.$val['id'].'') ?>" class="text-edit">Chỉnh sửa</a>
										<a type="button" title="xóa" class="text-danger ajax-delete" data-id="<?php echo $val['id'];?>" data-title="Lưu ý: Khi bạn xóa thành viên dữ liệu sẽ không thể khôi phục. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!" data-router=""  data-module="support" data-child="">Xóa</a>

									</address>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
					<?php }} else{ ?>
						<span class="col-lg-12"><small class="text-danger">Không có dữ liệu phù hợp</small></span>
					<?php } ?>
				</div>
				<div id="pagination" class="p-pagination">
					<?php echo (isset($PaginationList))? $PaginationList :'';?>
				</div>
			</form>

		</div>
		<!-- --------------------------- -->
		<?php $this->load->view('dashboard/backend/common/footer'); ?>
	</div>