

<script type="text/javascript">
	var submit = '<?php echo $this->input->post('create'); ?>';
	var condition_value = '<?php echo json_encode($this->input->post('condition_value')); ?>';
	var discount_moduleid = '<?php echo json_encode($this->input->post('discount_moduleid')); ?>';
	var city = '<?php echo $this->input->post('city[]'); ?>';
	var district = '<?php echo $this->input->post('district[]'); ?>';
	var module = '<?php echo json_encode($this->input->post('module[]')); ?>';
</script>
<div id="page-wrapper" class="gray-bg dashbard-1 fix-wrapper">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Cập nhật khuyến mại</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Cập nhật khuyến mại</strong></li>
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
				<div class="col-lg-8 clearfix">
					<div class="ibox mb20">
						<div class="ibox-title" style="padding: 9px 15px 0px;">
							<div class="uk-flex uk-flex-middle uk-flex-space-between">
								<h5>Thông tin cơ bản <small class="text-danger">Điền đầy đủ các thông tin được mô tả dưới đây</small></h5>
								<div class="ibox-tools">
									<button type="submit" name="update" value="update" class="btn btn-success  full-width">Cập nhật</button>
								</div>
							</div>
						</div>
						<div class="ibox-content">

							<div class="row mb15 " >
								<div class="col-lg-12">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Tiêu đề khuyến mại <b class="text-danger">(*)</b></span>
										</label>
										<?php echo form_input('title', htmlspecialchars_decode(html_entity_decode(set_value('title', $promotional['title']))), 'class="form-control title" placeholder="" id="title" autocomplete="off"');?>
									</div>
								</div>
							</div>

							<div class="row ">
								<div class="col-lg-6 m-b-xs">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Chọn chương trình khuyến mại <b class="text-danger">(*)</b></span>
										</label>
										<div class="form-row">
											<?php echo form_dropdown('catalogue', $this->configbie->data('catalogue') , set_value('catalogue', $promotional['catalogue']), 'class="form-control select3NotSearch"');?>
										</div>
									</div>
								</div>
								<div class="col-lg-6 m-b-xs">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Chọn loại đối tượng áp dụng<b class="text-danger">(*)</b></span>
										</label>
										<div class="form-row">
											<?php echo form_dropdown('module', $this->configbie->data('module'), set_value('module', $promotional['module']), 'class="form-control m-b select3NotSearch"');?>
										</div>
									</div>
								</div>
								<div class="col-lg-12">
									<div class="uk-flex uk-flex-middle m-b-sm use_common" style="cursor: pointer;">
										<?php 
											$use_common =$this->input->post('use_common');
											$use_common = (isset($use_common)) ? $use_common : $promotional['use_common']; 
										?>
										<input type="checkbox" name="use_common" value="1" <?php  echo ($use_common == 1) ? 'checked' : '' ; ?> class="checkbox-item">
										<div for="" class="label-checkboxitem m-r-xs <?php  echo ($use_common == 1) ? 'checked' : '' ; ?>"></div>
										Cho phép sử dụng chung với chương trình khuyến mãi
									</div>
								</div>
							</div>

							<?php 
								$catalogue =$this->input->post('catalogue');
								$catalogue = (isset($catalogue)) ? $catalogue : $promotional['catalogue']; 
							?>
							<div class="row mb15 coupon" <?php  echo ($catalogue == 'KM') ? 'style="display:none"' : 'style="display:block"' ; ?> >
								<div class="uk-flex uk-flex-middle m-b-sm">
									<div class="col-lg-6">
										<?php echo form_input('code', set_value('code', $promotional['code']), 'placeholder="Nhập Mã coupon" autocomplete="off" class="form-control"');?>
									</div>
									<div class="col-lg-3">
										<button type="button" class="btn btn-block  btn-primary render_code">Tạo mã tự động</button>
									</div>
								</div>
								
								<div class="col-sm-6">
									<div class="m-b-xs"><b >Nhập số lần sử dụng khuyễn mại</b></div>
									<div class="input-group limmit_code">
										<?php 
											$not_limmit =$this->input->post('not_limmit');
											$not_limmit = (isset($not_limmit)) ? $not_limmit : $promotional['limmit_code']; 
										?>
										<?php echo form_input('limmit_code', set_value('limmit_code',($promotional['limmit_code'] == '-1') ? 0 : $promotional['limmit_code'] ), 'placeholder="Nhập số lần sử dụng" autocomplete="off" class="form-control int" '.(($not_limmit == -1) ? 'readonly' : '' ));?>
	                                    <div class="input-group-btn">
	                                        <button style="font-size:13px; padding: 6px 20px; border: 1px solid #c4cdd5" tabindex="-1" class="btn btn-white " type="button">
	                                        	<span class="uk-flex uk-flex-middle">
	                                        		<input type="checkbox" <?php  echo ($not_limmit != -1) ? '' : 'checked' ; ?> name="not_limmit" value="-1" class="checkbox-item">
													<div for="" class="label-checkboxitem m-r-xs <?php  echo ($not_limmit != -1) ? '' : 'checked' ; ?>"></div>
	                                            	Không giới hạn
	                                        	</span>
	                                        </button>
	                                    </div>
	                                 </div>
								</div>
							</div>


							<div class="row mb15">
								<div class="col-lg-12">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Loại khuyến mại <b class="text-danger">(*)</b></span>
										</label>
										
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-row">
										<?php echo form_dropdown('discount_type', $this->configbie->data('discount_type') , set_value('discount_type', $promotional['discount_type']), 'class="form-control select3NotSearch"');?>
									</div>	
								</div>
								<?php 
									$discount_type =$this->input->post('discount_type');
									$discount_type = (isset($discount_type)) ? $discount_type : $promotional['discount_type'];
								 ?>

								<div class="col-lg-6 m-b discount_block">
									<div class="uk-flex uk-flex-middle uk-flex-space-between">
										<div class="m-r"> Giảm</div>
										<div class="uk-flex uk-flex-middle">
											
											<?php 
												$freeship =$this->input->post('freeship');
												$freeship = (isset($freeship)) ? $freeship : $promotional['freeship']; 

												$discount_value =$this->input->post('discount_value');
												$discount_value = (isset($discount_value)) ? $discount_value : $promotional['discount_value']; 
											 ?>
											<div style="font-size:13px;  border: 1px solid #c4cdd5;border-right:none; border-radius: 0px; display: <?php echo ($discount_type == 'ship' ) ? 'block' : 'none' ;  ?>" class="btn btn-white choose_freeship">
								            	<span class="uk-flex uk-flex-middle">
								            		<input type="checkbox" name="freeship" value='1' <?php  echo ($freeship == 1) ? 'checked' : '' ; ?> class="checkbox-item">
													<div for="" class="label-checkboxitem m-r <?php  echo ($freeship == 1) ? 'checked' : '' ; ?>"></div>
								                	Freeship
								            	</span>
								            </div>
											<?php echo form_input('discount_value', set_value('discount_value', addCommas($discount_value)), 'class="form-control int" autocomplete="off" placeholder="'.(($discount_type == 'percent' || $discount_type == 'object') ? 'Nhập phần trăm' : 'Nhập số tiền' )  .'" style="width:100%"'.(($freeship == 1) ? 'readonly' : '' ) );?>
											<div style="font-size:13px; width: 60px; border: 1px solid #c4cdd5;border-left:none;border-radius: 0px" class="btn btn-white extend">
	                                            	<?php echo (($discount_type == 'percent' || $discount_type == 'object') ? '%' : 'VNĐ' ) ?>
	                                        </div>

										</div>
									</div>
								</div>
								<div class="freeship" style="display:<?php echo ($discount_type == 'ship' ) ? 'block' : 'none' ;  ?>">
									<?php 
										$city = $this->input->post('city[]');
										$city = (isset($city)) ? $city :  json_decode($promotional['cityid']); 
										$district = $this->input->post('district[]');
										$district = (isset($district)) ? $district :  json_decode($promotional['districtid']); 
									?>
									<div class="col-lg-12 m-b">
										<div class="i-checks"><label> <input type="radio" value="city" name="ship_choose_local" <?php echo !empty($city) ? 'checked=""' : '' ;  ?>  > <i></i>Chọn tỉnh/ thành phố</label></div>
                                        <div class="i-checks"><label> <input type="radio" value="district" name="ship_choose_local" <?php echo !empty($district) ? 'checked=""' : '' ;  ?> > <i></i> Chọn quận huyện </label></div>
									</div>
									<?php 
										$freeshipAll =$this->input->post('freeshipAll');
										$freeshipAll = (isset($freeshipAll)) ? $freeshipAll : $promotional['freeshipAll']; 
									 ?>
									<div class="ship_city" style="display:<?php echo (!empty($city) || $freeshipAll == 1 )? 'block' : 'none' ;  ?>">
										<div class="col-lg-9">
											
											<div class="form-row">
												<?php echo form_dropdown('city[]', '', '', 'class="form-control  selectMultipe" data-module="vn_province" multiple="multiple" data-title="Nhập 2 kí tự để chọn tỉnh, TP.." data-select="name" data-key="provinceid" data-json='.base64_encode(json_encode($city)).' style="width: 100%;"  '.(($freeshipAll == 1) ? 'disabled' : '')); ?>
											</div>
										</div>
										<div class="col-lg-3 freeshipAll m-t-xs">
											<div class="uk-flex uk-flex-middle" style="cursor: pointer;">
												<input type="checkbox" <?php  echo ($freeshipAll == 1) ? 'checked' : '' ; ?> name="freeshipAll" value="1" class="checkbox-item">
												<div for="" class="label-checkboxitem m-r-xs <?php  echo ($freeshipAll == 1) ? 'checked' : '' ; ?>"></div>
												Freeship toàn quốc
											</div>
										</div>
										
									</div>
									<div class="ship_district col-lg-12"  style="display:<?php echo !empty($district) ? 'block' : 'none' ;  ?>">
										<div class="form-row">
											<?php echo form_dropdown('district[]', '', '', 'class="form-control  selectMultipe" data-module="vn_district" multiple="multiple" data-title="Nhập 2 kí tự để chọn quận huyên.." data-select="name" data-key="districtid" data-json='.base64_encode(json_encode($district)).'   style="width: 100%;"  '); ?>
										</div>
									</div>
								</div>
				
								<div class="col-lg-6 m-b-sm discount_moduleid" style="display: <?php echo ($discount_type == 'object' ) ? 'block' : 'none' ;  ?>">
									<div class="form-row">
										<?php echo form_dropdown('discount_moduleid[]', '', '', 'class="form-control selectMultipe" multiple="multiple" data-title ="nhập 2 kí tự để tìm kiếm"  data-json="'.base64_encode($promotional['discount_moduleid']).'"  data-module="'.$promotional['module'].'" '); ?>
									</div>	
								</div>
								<div class="list_discount_moduleid" style="display: <?php echo ($discount_type == 'object' ) ? 'block' : 'none' ;  ?>">
									<?php 
										$module = $this->input->post('module');
										$module = (isset($module)) ? $module : $promotional['module']; 

										$discount_moduleid = $this->input->post('discount_moduleid'); 
										$discount_moduleid = (isset($discount_moduleid)) ? $discount_moduleid : json_decode($promotional['discount_moduleid'],true);
										if($module == 'product'){ 

									?>
									<?php if(isset($discount_moduleid) && is_array($discount_moduleid) && count($discount_moduleid)){ ?>
									<div class="col-lg-12 ">
										<div class="m-b-sm">Các đối tượng được tặng kèm theo</div>
										<div class="form-row">
												<?php foreach ($discount_moduleid as $key => $val) { ?>
													<?php 
														$product = $this->Autoload_Model->_get_where(array(
															'select' => 'id, image, quantity_cuoi_ki, title, price',
															'table' => 'product',
															'where' => array('id' => $val),
														));
													 ?>
													<div class="p-xxs choose-moduleid m-b-sm" data-id=<?php echo $product['id'] ?>>
									    			<div class="uk-flex uk-flex-middle uk-flex-space-between">
									    				<div class="uk-flex uk-flex-middle">
									        				<div>
										        				<img class="img-sm m-r" src="<?php echo $product['image'] ?>" alt="ảnh">
										        			</div>
										        			<div>
										        				<div class="title"><?php echo $product['title'] ?></div>
																<div class="content">Giá bán :<b style="    text-decoration: line-through;"><?php echo addCommas($product['price']) ?></b> còn <b class="text-danger"><?php echo addCommas($product['price']*(100 - $discount_value)/100) ?></b> <sup> đ</sup>/ <span class="m-r-xs">Tồn cuối: <b> <?php echo $product['quantity_cuoi_ki'] ?></b></span></div>
															</div>
									    				</div>
									    				<div class="del m-r-sm" data-id="<?php echo $product['id'] ?>"><img src="template/close.png"></div>
									    			</div>
									    		</div>
												<?php } ?>
										</div>
									</div>
									<?php }} ?>
								</div>
							</div>

							<div class="row mb15">
								<div class="col-lg-12">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Điều kiện áp dụng <b class="text-danger">(*)</b></span>
										</label>
										
									</div>
								</div>
								<?php
									$condition_type =$this->input->post('condition_type');
									$condition_type = (isset($condition_type)) ? $condition_type : $promotional['condition_type']; 
								?>
								<div class="col-lg-6">
									<div class="form-row">
										<?php echo form_dropdown('condition_type', $this->configbie->data('condition_type') , set_value('condition_type', $promotional['condition_type']), 'class="form-control select3NotSearch"');?>
									</div>	
								</div>
								<div class="col-lg-6 condition_value" style="display: <?php echo ($condition_type == 'condition_moduleid' || $condition_type == 'condition_module_catalogue' ) ? 'block' : 'none' ;  ?>">
									<div class="form-row">
										<?php 
											$module = ($condition_type == 'condition_moduleid') ? $promotional['module'] : $promotional['module'].'_catalogue';
										?>
	                                    <?php echo form_dropdown('condition_value[]', '', '', 'class="form-control  selectMultipe" multiple="multiple" data-module="'.$module.'" multiple="multiple" data-title="Nhập 2 kí tự để tìm kiếm.." style="width: 100%;" data-json="'.base64_encode($promotional['condition_value']).'"'); ?>
									</div>	
								</div>
							</div>
							

							<div class="row mb15 list-moduleid" style="display: <?php echo ($condition_type == 'condition_moduleid' ) ? 'block' : 'none' ;  ?>">
								<?php 
									$module = $this->input->post('module');
									$module = (isset($module)) ? $module : $promotional['module']; 

									$condition_moduleid = $this->input->post('condition_value'); 

									$condition_moduleid = (isset($condition_moduleid)) ? $condition_moduleid : json_decode($promotional['condition_value'],true);

									if($module == 'product'){ 
										
								?>
								<?php if(isset($condition_moduleid) && is_array($condition_moduleid) && count($condition_moduleid)){ ?>
								<div class="col-lg-12">
									<div class="m-b-sm">Các đối tượng áp dụng</div>
									<div class="form-row">
											<?php foreach ($condition_moduleid as $key => $val) { ?>
												<?php 
													$product = $this->Autoload_Model->_get_where(array(
														'select' => 'id, image, quantity_cuoi_ki, title, price',
														'table' => 'product',
														'where' => array('id' => $val),
													));
												 ?>
												<div class="p-xxs choose-moduleid m-b-sm" data-id=<?php echo $product['id'] ?>>
								    			<div class="uk-flex uk-flex-middle uk-flex-space-between">
								    				<div class="uk-flex uk-flex-middle">
								        				<div>
									        				<img class="img-sm m-r" src="<?php echo $product['image'] ?>" alt="ảnh">
									        			</div>
									        			<div>
									        				<div class="title"><?php echo $product['title'] ?></div>
															<div class="content">Giá bán :<b> <?php echo addCommas($product['price']) ?></b><sup> đ</sup>/ <span class="m-r-xs">Tồn cuối: <b> <?php echo $product['quantity_cuoi_ki'] ?></b></span></div>
														</div>
								    				</div>
								    				<div class="del m-r-sm" data-id="<?php echo $product['id'] ?>"><img src="template/close.png"></div>
								    			</div>
								    		</div>
											<?php } ?>
									</div>
								</div>
								<?php }} ?>
							</div>



							<div class="row mb15 block_condition_1" <?php  echo ($catalogue == 'KM') ? 'style="display:block"' : 'style="display:none"' ; ?>>
								<div class="col-lg-6  "  >
									<div class="form-row">
										<?php echo form_dropdown('condition_type_1',$this->configbie->data('condition_type_1'), set_value('condition_type_1', $promotional['condition_type_1']), 'class="form-control select3NotSearch"');?>
									</div>	
								</div>
								<div class="col-lg-6 " >
									<div class="form-row">
										<?php echo form_input('condition_value_1', set_value('condition_value_1', addCommas($promotional['condition_value_1'])), 'class="form-control" style="width:100%"');?>
									</div>	
								</div>
							</div>
							
							
							<?php 
								$album = $this->input->post('album');
								$album = (isset($album)) ? $album : json_decode(base64_decode($promotional['image_json']),true); 
							 ?>
							<div class="row mb15">
								<div class="col-sm-12">
									<div class="uk-flex uk-flex-middle uk-flex-space-between">
									<b>Banner </b>
									
									<div class="uk-flex uk-flex-middle uk-flex-space-between">
										<div class="edit">
											<a onclick="openKCFinderImage(this);return false;" href="" title="" class="upload-picture">Chọn hình</a>
										</div>
									</div>
								</div>
								</div>
								<div class="col-lg-12">
									<div class="click-to-upload" <?php echo (isset($album))?'style="display:none"':'' ?>>
										<div class="icon">
											<a type="button" class="upload-picture" onclick="openKCFinderImage(this);return false;">
												<svg style="width:80px;height:80px;fill: #d3dbe2;margin-bottom: 10px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80"><path d="M80 57.6l-4-18.7v-23.9c0-1.1-.9-2-2-2h-3.5l-1.1-5.4c-.3-1.1-1.4-1.8-2.4-1.6l-32.6 7h-27.4c-1.1 0-2 .9-2 2v4.3l-3.4.7c-1.1.2-1.8 1.3-1.5 2.4l5 23.4v20.2c0 1.1.9 2 2 2h2.7l.9 4.4c.2.9 1 1.6 2 1.6h.4l27.9-6h33c1.1 0 2-.9 2-2v-5.5l2.4-.5c1.1-.2 1.8-1.3 1.6-2.4zm-75-21.5l-3-14.1 3-.6v14.7zm62.4-28.1l1.1 5h-24.5l23.4-5zm-54.8 64l-.8-4h19.6l-18.8 4zm37.7-6h-43.3v-51h67v51h-23.7zm25.7-7.5v-9.9l2 9.4-2 .5zm-52-21.5c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5zm0-8c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3zm-13-10v43h59v-43h-59zm57 2v24.1l-12.8-12.8c-3-3-7.9-3-11 0l-13.3 13.2-.1-.1c-1.1-1.1-2.5-1.7-4.1-1.7-1.5 0-3 .6-4.1 1.7l-9.6 9.8v-34.2h55zm-55 39v-2l11.1-11.2c1.4-1.4 3.9-1.4 5.3 0l9.7 9.7c-5.2 1.3-9 2.4-9.4 2.5l-3.7 1h-13zm55 0h-34.2c7.1-2 23.2-5.9 33-5.9l1.2-.1v6zm-1.3-7.9c-7.2 0-17.4 2-25.3 3.9l-9.1-9.1 13.3-13.3c2.2-2.2 5.9-2.2 8.1 0l14.3 14.3v4.1l-1.3.1z"></path></svg>
											</a>
										</div>
										<div class="small-text">Sử dụng nút <b>Chọn hình</b> để thêm hình.</div>
									</div>
									<div class="upload-list" <?php echo (isset($album))?'':'style="display:none"' ?> style="padding:5px;">
										<div class="row">
											<ul id="sortable" class="clearfix sortui">
												<?php if(isset($album) && is_array($album) && count($album)){ ?>
												<?php foreach($album as $key => $val){ ?>
													<li class="ui-state-default">
														<div class="thumb">
															<span class="image img-scaledown">
																<img src="<?php echo $val; ?>" alt="" /> <input type="hidden" value="<?php echo $val; ?>" name="album[]" />
															</span>
															<div class="overlay"></div>
															<div class="delete-image"><i class="fa fa-trash" aria-hidden="true"></i></div>
														</div>
													</li>
												<?php }} ?>
											</ul>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-12">
									<div class="form-row">
										<div class="uk-flex uk-flex-middle uk-flex-space-between">
											<label class="control-label text-left">
												<span>Nội dung</span>
											</label>
											<a href="" title="" class="uploadMultiImage" onclick="openKCFinderMulti(this);return false;">Upload hình ảnh</a>
										</div>
										<?php echo form_textarea('description', htmlspecialchars_decode(html_entity_decode(set_value('description', $promotional['description']))), 'class="form-control ck-editor" id="ckDescription" placeholder="" autocomplete="off"');?>
									</div>
								</div>
							</div>
						</div>
					</div>



					<div class="ibox mb20">
						<div class="ibox-title">
							<div class="uk-flex uk-flex-middle uk-flex-space-between">
								<h5>Tối ưu SEO <small class="text-danger">Thiết lập các thẻ mô tả giúp khách hàng dễ dàng tìm thấy bạn.</small></h5>
								
								<div class="uk-flex uk-flex-middle uk-flex-space-between">
									<div class="edit">
										<a href="#" class="edit-seo">Chỉnh sửa SEO</a>
									</div>
								</div>
							</div>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="col-lg-12">
									<div class="google">
										<div class="g-title"><?php echo ($this->input->post('meta_title')) ? $this->input->post('meta_title') : (($this->input->post('title')) ? $this->input->post('title') : (($promotional['meta_title'] != '') ? $promotional['meta_title'] : $promotional['title'])); ?></div>
										<div class="g-link"><?php echo ($this->input->post('canonical')) ? site_url($this->input->post('canonical')) : site_url($promotional['canonical']); ?></div>
										<div class="g-description" id="metaDescription">
											<?php echo cutnchar(($this->input->post('meta_description')) ? $this->input->post('meta_description') : (($this->input->post('description')) ? strip_tags($this->input->post('description')) : ((!empty($promotional['meta_description'])) ? strip_tags($promotional['meta_description']) :((!empty($promotional['description'])) ? strip_tags($promotional['description']): 'List of all combinations of words containing CKEDT. Words that contain ckedt letters in them. Anagrams made from C K E D T letters.List of all combinations of words containing CKEDT. Words that contain ckedt letters in them. Anagrams made from C K E D T letters.'))), 360); ?>
											
										</div>
									</div>
								</div>
							</div>
							
							<div class="seo-group hidden">
								<hr>
								<div class="row mb15">
									<div class="col-lg-12">
										<div class="form-row">
											<div class="uk-flex uk-flex-middle uk-flex-space-between">
												<label class="control-label ">
													<span>Tiêu đề SEO</span>
												</label>
												<span style="color:#9fafba;"><span id="titleCount"><?php echo strlen($promotional['meta_title']) ?></span> trên 70 ký tự</span>
											</div>
											<?php echo form_input('meta_title', htmlspecialchars_decode(html_entity_decode(set_value('meta_title', $promotional['meta_title']))), 'class="form-control meta-title" placeholder="" autocomplete="off"');?>
										</div>
									</div>
								</div>
								<div class="row mb15">
									<div class="col-lg-12">
										<div class="form-row">
											<div class="uk-flex uk-flex-middle uk-flex-space-between">
												<label class="control-label ">
													<span>Mô tả SEO</span>
												</label>
												<span style="color:#9fafba;"><span id="descriptionCount"><?php echo strlen($promotional['meta_description']) ?></span> trên 320 ký tự</span>
											</div>
											<?php echo form_textarea('meta_description', htmlspecialchars_decode(html_entity_decode(set_value('meta_description', $promotional['meta_description']))), 'class="form-control meta-description" id="seoDescription" placeholder="" autocomplete="off"');?>
										</div>
									</div>
								</div>
								<div class="row mb15">
									<div class="col-lg-12">
										<div class="form-row">
											<div class="uk-flex uk-flex-middle uk-flex-space-between">
												<label class="control-label ">
													<span>Đường dẫn <b class="text-danger">(*)</b></span>
												</label>
											</div>
											<div class="outer">
												<div class="uk-flex uk-flex-middle">
													<div class="base-url"><?php echo base_url(); ?></div>
													<?php echo form_input('canonical', htmlspecialchars_decode(html_entity_decode(set_value('canonical', $promotional['canonical']))), 'class="form-control canonical" placeholder="" autocomplete="off" data-flag="1" ');?>
													
													<?php echo form_input('original_canonical', htmlspecialchars_decode(html_entity_decode(set_value('canonical', $promotional['canonical']))), 'class="form-control" placeholder="" style="display:none;" autocomplete="off"');?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<button type="submit" name="update" value="update" class="btn btn-success block m-b pull-right">Cập nhật</button>
				</div>




				<div class="col-lg-4">
					<div class="ibox mb20">
						<div class="ibox-title">
							<h5>Thời gian áp dụng</h5>
						</div>

						<div class="ibox-content">
							<?php 
								$choose_date = $this->input->post('choose_date');
								$promotional['start_date'] = ($promotional['start_date'] == '0000-00-00 00:00:00') ? 1 : $promotional['start_date'];
								$choose_date = (isset($choose_date)) ? $choose_date : $promotional['start_date']; 
							?>
							<?php if($choose_date == '1') {?>
							 	<label>Từ ngày</label>
								<?php echo form_input('start_date', set_value('start_date'), 'autocomplete="off" placeholder="Từ ngày" class="form-control datetimepicker m-b" readonly ');?>

								<label>Đến ngày</label>
								<?php echo form_input('end_date', set_value('end_date'), 'autocomplete="off" placeholder="Đến ngày" class="form-control datetimepicker m-b" readonly ');?>
								<div class="uk-flex uk-flex-middle m-b choose_date" style="cursor: pointer;">
									<input type="checkbox" name="choose_date" value="1" checked class="checkbox-item">
									<div for="" class="label-checkboxitem m-r-xs checked"></div>
									CT khuyến mại không bao giờ hết hạn
								</div>
							<?php }else{?>
								<label>Từ ngày</label>
								<?php echo form_input('start_date', set_value('start_date', settime($promotional['start_date'], 'date')), 'autocomplete="off" placeholder="Từ ngày" class="form-control datetimepicker m-b"  ');?>

								<label>Đến ngày</label>
								<?php echo form_input('end_date', set_value('end_date',  settime($promotional['end_date'], 'date')), 'autocomplete="off" placeholder="Đến ngày" class="form-control datetimepicker m-b"  ');?>
								<div class="uk-flex uk-flex-middle m-b choose_date" style="cursor: pointer;">
									<input type="checkbox" name="choose_date" value="1"  class="checkbox-item">
									<div for="" class="label-checkboxitem m-r-xs "></div>
									CT khuyến mại không bao giờ hết hạn
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<?php $this->load->view('dashboard/backend/common/footer'); ?>
</div>

<div class="modal inmodal fade in" id="myModal6" tabindex="-1" role="dialog" aria-hidden="true" >
	<form class="" method="POST" action="" id="update-tag">
		<div class="modal-dialog modal-lg" style ="max-width: 400px ; margin : 50px auto">
			<div class="modal-content fadeInRight animated">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Thêm từ khóa</h4>
				</div>
				<div class="modal-body">
					<div class="alert alert-danger" style = "display: none"></div>
					<div class="form-group">
						<label>Tiêu đề tag</label>
						<input type="text" name="popup-tag" value="" class="form-control tag-title" placeholder="" autocomplete="off">
					</div>
					<div class="form-group">
						<label>Đường dẫn Tag</label>
						<input type="text" name="popup-tag" value="" class="form-control tag-link" placeholder="" autocomplete="off">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Cập nhật</button>
				</div>
			</div>
		</div>
	</form>
</div>