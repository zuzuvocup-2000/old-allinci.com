<div id="page-wrapper" class="gray-bg dashbard-1 fix-wrapper">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Quản lý menu</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Quản lý menu</strong></li>
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
						<h2 class="panel-title">Danh sách menu</h2>
						<div class="panel-description">
							<p>+ Danh sách menu giúp khách hàng dễ dàng xem trang web của bạn. Bạn có thể thêm menu vào giao diện trong phần thiết lập giao diện</p>
							<p>+ Bạn có thể sử dụng biểu tượng  để sắp xếp thứ tự menu.</p>
							<p>
								+ Menu còn được dùng để
								Tạo "<a href="<?php echo site_url('http://webchuanseoht.com/menu-da-cap-la-gi.html'); ?>" title="">menu xổ xuống</a>" cho trang web của bạn.
							</p>
							<a style="color:#000;border-color:#c4cdd5;display:inline-block !important;" href="<?php echo site_url('navigation/backend/navigation/create'); ?>" title="" class="btn btn-default block m-b" style="margin-right:10px;">Thêm Menu</a>
						</div>
					</div>
				</div>
				<div class="col-lg-8">
					<?php 
						$navigationCat = $this->Autoload_Model->_get_where(array(
							'select' => 'id, title',
							'table' => 'navigation_catalogue',
							'order_by' => 'id asc',
						), TRUE);
						if(isset($navigationCat) && is_array($navigationCat) && count($navigationCat)){
							foreach($navigationCat as $key => $val){
								$navigationCat[$key]['post'] = $this->Autoload_Model->_get_where(array('select' => 'id, title, link, order','table' => 'navigation','where' => array('catalogueid' => $val['id']),'order_by' => 'order desc, id desc'),TRUE);
							}
						}
					?>
					<?php if(isset($navigationCat) && is_array($navigationCat) && count($navigationCat)){ ?>
					<?php foreach($navigationCat as $key => $val){ ?>
					<div class="ibox">
						<div class="ibox-title" style="padding: 9px 15px 0px;">
							<div class="uk-flex uk-flex-middle uk-flex-space-between">
								<h5><?php echo $val['title']; ?></h5>
								<a style="color:#000;border-color:#c4cdd5;display:inline-block !important;" href="<?php echo site_url('navigation/backend/navigation/update/'.$val['id']); ?>" title="" class="btn btn-default block m-b">Cập nhật menu</a>
							</div>
						</div>
						<?php if(isset($val['post']) && is_array($val['post']) && count($val['post'])){ ?>
						<div class="ibox-content">
							 <div class="dd" id="nestable<?php echo $key+2; ?>" data-catalogueid="<?php echo $val['id']; ?>">
                                <ol class="dd-list">
									<?php foreach($val['post'] as $keyPost => $valPost){ ?>
									<?php 
										$_1_st_child = $this->Autoload_Model->_get_where(array('select' => 'id, title','table' => 'navigation','where' => array('parentid' => $valPost['id']),'order_by' => 'order desc, id desc'),TRUE);
									?>
                                    <li class="dd-item" style="position:relative;" data-id="<?php echo $valPost['id']; ?>">
                                        <div class="dd-handle">
                                            <span class="label label-info"><i class="fa fa-arrows"></i></span> <?php echo $valPost['title']; ?>
                                        </div>
										 <span class="pull-right add-sub"> <a style="font-weight:normal;font-size:12px;" href="<?php echo site_url('navigation/backend/navigation/create?parentid='.$valPost['id']) ?>" title="" class="">Quản lý menu con</a> </span>
										<?php if(isset($_1_st_child) && is_array($_1_st_child) && count($_1_st_child)){ ?>
                                        <ol class="dd-list">
											<?php foreach($_1_st_child as $keyA => $valA){ ?>
											<?php 
												$_2_st_child = $this->Autoload_Model->_get_where(array('select' => 'id, title','table' => 'navigation','where' => array('parentid' => $valA['id']),'order_by' => 'order desc, id desc'),TRUE);
											?>
                                            <li class="dd-item" data-id="<?php echo $valA['id']; ?>">
                                                <div class="dd-handle">
                                                    <span class="label label-info"><i class="fa fa-arrows"></i></span> <?php echo $valA['title']; ?>
                                                </div>
												 <span class="pull-right add-sub"> <a style="font-weight:normal;font-size:12px;" href="<?php echo site_url('navigation/backend/navigation/create?parentid='.$valA['id']) ?>" title="" class="">Quản lý menu con</a> </span>
												<?php if(isset($_2_st_child) && is_array($_2_st_child) && count($_2_st_child)){ ?>
												<ol class="dd-list">
													<?php foreach($_2_st_child as $keyB => $valB){ ?>
													<?php 
														$_3_st_child = $this->Autoload_Model->_get_where(array('select' => 'id, title','table' => 'navigation','where' => array('parentid' => $valB['id']),'order_by' => 'order desc, id desc'),TRUE);
													?>
													<li class="dd-item" data-id="<?php echo $valB['id']; ?>">
														<div class="dd-handle">
															<span class="label label-info"><i class="fa fa-arrows"></i></span> <?php echo $valB['title']; ?>
														</div>
														<span class="pull-right add-sub"> <a style="font-weight:normal;font-size:12px;" href="<?php echo site_url('navigation/backend/navigation/create?parentid='.$valB['id']) ?>" title="" class="">Quản lý menu con</a> </span>
														<?php if(isset($_3_st_child) && is_array($_3_st_child) && count($_3_st_child)){ ?>
														<ol class="dd-list">
															<?php foreach($_3_st_child as $keyC => $valC){ ?>
															<?php 
																$_4_st_child = $this->Autoload_Model->_get_where(array('select' => 'id, title','table' => 'navigation','where' => array('parentid' => $valC['id']),'order_by' => 'order desc, id desc'),TRUE);
															?>
															<li class="dd-item" data-id="<?php echo $valC['id']; ?>">
																<div class="dd-handle">
																	<span class="label label-info"><i class="fa fa-arrows"></i></span> <?php echo $valC['title']; ?>
																</div>
																<span class="pull-right add-sub"> <a style="font-weight:normal;font-size:12px;" href="<?php echo site_url('navigation/backend/navigation/create?parentid='.$valC['id']) ?>" title="" class="">Quản lý menu con</a> </span>
																<?php if(isset($_4_st_child) && is_array($_4_st_child) && count($_4_st_child)){ ?>
																<ol class="dd-list">
																	<?php foreach($_4_st_child as $keyD => $valD){ ?>
																	<li class="dd-item" data-id="<?php echo $valD['id']; ?>">
																		<div class="dd-handle">
																			<span class="label label-info"><i class="fa fa-arrows"></i></span> <?php echo $valD['title']; ?>
																		</div>
																		<span class="pull-right add-sub"> <a style="font-weight:normal;font-size:12px;" href="<?php echo site_url('navigation/backend/navigation/create?parentid='.$valD['id']) ?>" title="" class="">Quản lý menu con</a> </span>
																	</li>
																	<?php } ?>
																</ol>
																<?php } ?>
															</li>
															<?php } ?>
														</ol>
														<?php } ?>
													</li>
													<?php } ?>
												</ol>
												<?php } ?>
                                            </li>
											<?php } ?>
                                        </ol>
										<?php } ?>
                                    </li>
									<?php } ?>
                                </ol>
                            </div>
						</div>
						<?php } ?>
					</div>
					<?php }} ?>
				</div>
			</div>
			
		</div>
	</form>
	<?php $this->load->view('dashboard/backend/common/footer'); ?>
</div>
