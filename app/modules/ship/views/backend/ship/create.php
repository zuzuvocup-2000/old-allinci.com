<div id="page-wrapper" class="gray-bg dashbard-1">
	 <div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Thêm mới Phí ship</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Thêm mới Phí ship</strong></li>
			</ol>
		</div>
	</div>
	<form method="post" action="" class="form-horizontal">
		<div class="wrapper wrapper-content animated fadeInRight">
			<div class="row">
				<div class="box-body">
					<?php $error = validation_errors(); echo !empty($error)?'<div class="alert alert-danger">'.$error.'</div>':'';?>
				</div><!-- /.box-body -->
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<div class="toolbox">
								<div class="uk-flex uk-flex-middle uk-flex-space-between">
									<h4>Thông tin chung</h4>
									<div class="uk-button">
										<button style="margin-right:2px;" class="btn btn-primary btn-sm" name="create" value="create" type="submit">Thêm mới Phí ship</button>
									</div>
								</div>
							</div>
						</div>
						<div class="ibox-content profile-content">
							<div class="row">
								<div class="col-lg-12 m-b"><b>Chọn địa điểm tính phí</b></div>
								<div class="col-lg-12 m-b">
									<div class="col-lg-12 m-b">
										<div class="i-checks"><label> 
											<input type="radio" value="city" name="ship_choose_local" <?php echo (!empty($post['city']) || ($post['district'] =='')) ? 'checked=""' : '' ;  ?>  > <i></i>Chọn tỉnh/ thành phố
										</label></div>

		                                <div class="i-checks"><label> 
		                                	<input type="radio" value="district" name="ship_choose_local" <?php echo !empty($post['district']) ? 'checked=""' : '' ;  ?> > <i></i> Chọn quận huyện 
		                                </label></div>
									</div>
								
									<div class="ship_city" style="display:<?php echo (!empty($post['city']) || $post['district'] =='') ? 'block' : 'none' ;  ?>">
										<div class="col-lg-12">
											<?php $shipAll = $this->input->post('shipAll'); ?>
											<div class="form-row">
												<?php echo form_dropdown('city[]', '', '', 'class="form-control  selectMultipe" data-module="vn_province" multiple="multiple" data-title="Nhập 2 kí tự để chọn tỉnh, TP.." data-select="name" data-key="provinceid" data-json="'.base64_encode(json_encode($post['city'])).'" data-condition='.$cityCondition.' style="width: 100%;"  '); ?>
											</div>
										</div>
										
									</div>

									<div class="ship_district col-lg-12"  style="display:<?php echo !empty($post['district']) ? 'block' : 'none' ;  ?>">
										<?php $shipAll = $this->input->post('shipAll'); ?>
										<div class="form-row">
											<?php echo form_dropdown('district[]', '', '', 'class="form-control  selectMultipe" data-module="vn_district" multiple="multiple" data-title="Nhập 2 kí tự để chọn quận huyên.." data-select="name" data-key="districtid" data-json='.base64_encode(json_encode($post['district'])).' data-condition='.$districtCondition.'   style="width: 100%;"  '); ?>
										</div>
									</div>

								</div>
								
								<div class="col-lg-12 m-b"><b>Phí ship</b></div>
								<div class="col-lg-12">
									<div class="col-lg-4"><?php echo form_input('value', set_value('value',$this->general['homepage_ship']), 'class="form-control int" style="width:100%"');?></div>
								</div>
							</div>


						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<?php $this->load->view('dashboard/backend/common/footer'); ?>
</div>
