
<div id="page-wrapper" class="gray-bg dashbard-1 fix-wrapper">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<form method="post" action="" class="form-horizontal box" >
		<div class="ibox-tools" id="create_product">
			<button type="submit" name="create" value="create" class="btn btn-success block full-width m-b">Tạo mới</button>
		</div>
		<div class="row wrapper border-bottom white-bg page-heading">
			<div class="col-lg-10">
				<h2>Thêm mới sản phẩm</h2>
				<ol class="breadcrumb">
					<li>
						<a href="<?php echo site_url('admin'); ?>">Home</a>
					</li>
					<li class="active"><strong>Thêm mới sản phẩm</strong></li>
				</ol>
			</div>
		</div>
		<script type="text/javascript">
			var submit = '<?php echo $this->input->post('create'); ?>';
			var catalogueid = '<?php echo json_encode($this->input->post('catalogue')); ?>';
			var tag = '<?php echo json_encode($this->input->post('tag')); ?>';
		</script>
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
									<button type="submit" name="create" value="create" class="btn btn-success  full-width">Tạo mới</button>
								</div>

							</div>
						</div>
						<div class="ibox-content">
							<div class="row mb15">
								<div class="col-lg-12">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Tiêu đề sản phẩm <b class="text-danger">(*)</b></span>
										</label>
										<?php echo form_input('title', htmlspecialchars_decode(html_entity_decode(set_value('title'))), 'class="form-control title" placeholder="" id="title" autocomplete="off"');?>
									</div>
								</div>
							</div>
							<div class="row mb15">
								<div class="col-lg-12 m-b">
									<div class="form-row">
										<div class="uk-flex uk-flex-middle uk-flex-space-between">
											<label class="control-label text-left">
												<span>Nội dung</span>
											</label>
											<a href="" title="" class="uploadMultiImage" onclick="openKCFinderMulti(this);return false;">Upload hình ảnh</a>
										</div>
										<?php echo form_textarea('description', htmlspecialchars_decode(html_entity_decode(set_value('description'))), 'class="form-control ck-editor" id="ckDescription" placeholder="" autocomplete="off" ');?>
									</div>
								</div>
								<div class="col-lg-12">	
									<div class="uk-flex uk-flex-middle uk-flex-space-between">
										<label class="control-label text-left ">
											<span>Nội dung mở rộng</span>
										</label>
										<a href="" title="" class="add-attr" onclick="return false;">Thêm nội dung +</a>
									</div>
								</div>
							</div>

							<div class="row attr-more">
								
								<?php $content  = $this->input->post('content') ;
								if (isset($content['title'])&& is_array($content['title']) && count($content['title'])){ ?>
									<?php foreach($content['title'] as $key => $val){ ?>
									<?php foreach($content['microtime'] as $sub => $subs){ ?>
										<?php if($val == '' ){continue;} ?>
										<?php if($key == $sub){ ?>
								<div class="col-lg-12 m-b desc-more">
									<div class="row m-b">
										<div class="col-lg-8">
											<input type="text" name="content[title][]" value="<?php echo $val ?>" class="form-control" placeholder="Tiêu đề">
											<input type="text" name="content[microtime][]" value="<?php echo $subs ?>" class="hidden">
										</div>
										<div class="col-lg-4">
											<div class="uk-flex uk-flex-middle uk-flex-space-between">
												<a href="" title="" class="uploadMultiImage" onclick="openKCFinderDescExtend('<?php echo 'editor_'.$subs ?>');return false;">Upload hình ảnh</a>
												<button class="btn btn-danger delete-attr" type="button"><i class="fa fa-trash"></i></button>
											</div>
										</div>
									</div>
									<div class="row m-b">
										<div class="col-lg-12">
											<?php echo form_textarea('content[description][]', htmlspecialchars_decode(html_entity_decode($content['description'][$key])), 'class="form-control ck-editor" id="editor_'.$subs.'" placeholder="" autocomplete="off"');?>
										</div>
									</div>
								</div>
								<?php }}}} ?>	
							</div>
						</div>
					</div>
					
					<div class="ibox mb20 album">
						<div class="ibox-title">
							<div class="uk-flex uk-flex-middle uk-flex-space-between">
								<h5>Album Ảnh </h5>

								<div class="uk-flex uk-flex-middle uk-flex-space-between">
									<!-- <form class="js_upload_now" action="" method="post" enctype="multipart/form-data">
										<input data-result="" class="m-r " type="file" name="a" multiple>
										<input type="submit" name="abx" value="Add">
									</form> -->
									<div class="edit">
										<a onclick="openKCFinderImage(this);return false;" href="" title="" class="upload-picture">Chọn hình</a>
									</div>
								</div>
							</div>
						</div>
						<div class="ibox-content">
							<?php $album = $this->input->post('album'); ?>
							<div class="row">
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
						</div>
					</div>
				
					<div class="ibox mb20 block-detail-product">
						<div class="ibox-title uk-flex uk-flex-middle uk-flex-space-between">
							<h5>Thông tin chi tiết</h5>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="col-lg-6 m-b">
									<label class="control-label ">
										<span>Giá<b class="text-danger">(*)</b></span>
									</label>
									<div class="form-row">
										<?php echo form_input('price', set_value('price',0), 'class=" m-b-sm form-control" placeholder="đ" autocomplete="off"');?>
										<div class="uk-flex uk-flex-middle">
											<div class="m-r-sm">
												<?php if(isset($price_contact)&&$price_contact==1){ ?>
													<input type="checkbox" checked name="price_contact" value="1" class="checkbox-item">
													<div for="" class="label-checkboxitem checked"></div>
												<?php }else{ ?>
													<input type="checkbox"  name="price_contact" value="1" class="checkbox-item">
													<div for="" class="label-checkboxitem "></div>
												<?php } ?>
											</div>
											<div>
												Liên hệ để biết giá
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-6 m-b">
									<label class="control-label ">
										<span>Giá khuyến mại</span>
									</label>
									<div class="form-row">
										<?php echo form_input('price_sale', set_value('price_sale',0), 'class="form-control int" placeholder="đ" autocomplete="off"');?>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6 mb15">
									<label class="control-label ">
										<span>Mã sản phẩm<b class="text-danger">(*)</b></span>
									</label>
									<div class="form-row">
										<?php $code= CodeRender('product') ?>
										<?php echo form_input('code', set_value('code',$code), 'class="form-control" placeholder="SP001" autocomplete="off"');?>
									</div>
								</div>
								<div class="col-lg-6 mb15">
									<label class="control-label ">
										<span>BarCode</span>
									</label>
									<div class="form-row">
										<?php echo form_input('barcode', set_value('barcode'), 'class="form-control" placeholder="VD:UPC,ISBN" autocomplete="off"');?>
									</div>
								</div>
								

								<div class="col-lg-6 mb15">
									<label class="control-label ">
										<span>Model</span>
									</label>
									<div class="form-row">
										<?php echo form_input('model', set_value('model'), 'class="form-control" placeholder="UA50NU7400KXXV" autocomplete="off"');?>
									</div>
								</div>

								<div class="col-lg-6 mb15">
									<label class="control-label ">
										<span>Xuất xứ</span>
									</label>
									<div class="form-row">
										<?php echo form_input('made_in', set_value('made_in'), 'class="form-control" placeholder="Việt Nam" autocomplete="off"');?>
									</div>
								</div>

								

								<div class="col-lg-6 mb15">
									<label class="control-label ">
										<span>Quản lí tồn kho</span>
									</label>
									<div class="form-row">
										<?php echo form_dropdown('inventory',array('0'=>'Chọn','1'=>'có','2'=>'không')	,set_value('inventory', (empty($inventory))?'0':$inventory ) ,'class="form-control" '); ?>
									</div>
								</div>


							</div>
							<div class="row block-inventory"<?php echo (!empty($inventory) && $inventory==1)?'':' style="display: none"' ?>>
								<div class="col-lg-6 mb15">
									<label class="control-label ">
										<span>Tồn đầu kì</span>
									</label>
									<div class="form-row">
										<?php echo form_input('quantity_dau_ki', set_value('quantity_dau_ki',0), 'class="form-control float" placeholder="" autocomplete="off"');?>
									</div>
								</div>
								<div class="col-lg-6 mb15">
									<label class="control-label ">
										<span>Tồn cuối kì</span>
									</label>
									<div class="form-row">
										<?php echo form_input('quantity_cuoi_ki', set_value('quantity_cuoi_ki',0), 'class="form-control float" placeholder="" autocomplete="off"');?>
									</div>
								</div>
								<div class="col-lg-12">
									<div class="uk-flex uk-flex-middle">
										<div class="m-r-sm">
											<?php if(isset($unlimited_sale)&&$unlimited_sale==1){ ?>
												<input type="checkbox" checked name="unlimited_sale" value="1" class="checkbox-item">
												<div for="" class="label-checkboxitem checked"></div>
											<?php }else{ ?>
												<input type="checkbox"  name="unlimited_sale" value="1" class="checkbox-item">
												<div for="" class="label-checkboxitem "></div>
											<?php } ?>
										</div>
										<div>
											Cho phép bán âm (Không giới hạn số lượng sản phẩm trong đơn hàng)
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ibox mb20 block-version" data-countattribute_catalogue = "<?php echo $countAttribute_catalogue ?>" >	
						<div class="ibox-title" style="padding-bottom: 15px;">
							<div class="uk-flex uk-flex-middle uk-flex-space-between">
								<h5>Sản phẩm có nhiều phiên bản </h5>
								<div>
									<a class="show-version" <?php echo (!empty($version) && $version>0)?'style="display:none"':'' ?>  >Thêm phiên bản</a>
									<div class="uk-flex uk-flex-middle hide-version"<?php echo (!empty($version) && $version>0)?'':'style="display:none"' ?>>
										<div class="m-r-sm">
											<?php if(isset($create_product)&&$create_product==-1){ ?>
												<input type="checkbox" checked name="create_product" value="-1" class="checkbox-item">
												<div for="" class="label-checkboxitem checked"></div>
											<?php }else{ ?>
												<input type="checkbox"  name="create_product" value="-1" class="checkbox-item">
												<div for="" class="label-checkboxitem "></div>
											<?php } ?>
										</div>
										<div class="m-r">
											Có tạo mới sản phẩm
										</div>
										<a type="button" class="btn btn-default">Hủy</a>
									</div>
								</div>
							</div>
							<small class="text-danger">Sản phẩm có các phiên bản dựa theo thuộc tính như kích thước hoặc màu sắc ? ( chọn tối đa 2 )</small>
						</div>
						<div class="ibox-content" style="background: #f5f6f7; <?php echo (!empty($attribute) )?'':'display:none"' ?>" >
							<?php if(isset($color) && is_array($color) && count($color)){ ?>
							<div class="row block-color">
								<div class="col-lg-12">
									<div  class="m-b-xs" ><b>Chọn ảnh cho màu sắc</b></div>
									<div class="row">
									<?php foreach($color as $key => $val){ ?>
										<div class="col-lg-3 ">
											<div class="image_color m-b-xs" style="cursor: pointer;">
												<div class="image_small" style=" background: <?php echo $val['color'] ?>">
													<img  src="<?php echo $image_color[$key] ?>"  alt="">
												</div>
												<input type="text" name="image_color[<?php echo $key ?>]" value="" class="hidden" onclick="openKCFinder(this)" >
											</div>
											<div class="title_color text-center">
												<?php echo  $val['title'] ?>
											</div>
										</div>
									<?php } ?>
									</div>
									<hr>
								</div>
							</div>
							<?php }else{ ?>
							<div class="row block-color" style="display: none">
								<div class="col-lg-12">
									<div  class="m-b-xs" ><b>Chọn ảnh cho màu sắc</b></div>
									<div class="row">

									</div>
									<hr>
								</div>
							</div>
							<?php } ?>


							<div class="row block-attribute">
								<div class="col-lg-12">
									<table class="table">
										<thead>
											<tr>
												<td></td>
												<td style="width: 200px;">Tên thuộc tính</td>
												<td>Giá trị thuộc tính (Các giá trị cách nhau bởi dấu phẩy)</td>
												<td style="width: 50px;"></td>
											</tr>
										</thead>
										<tbody>
											<?php  
												$dropdown= dropdown(array('text'=>'Chọn thuộc tính','select'=>'id, title','table'=>'attribute_catalogue','field'=>'id','value'=>'title','order_by'=>'id DESC')) 
											?>
											<?php if(isset($attribute_catalogue) && is_array($attribute_catalogue) && count($attribute_catalogue)){ ?>
												<?php foreach ($attribute_catalogue as $key => $value) { ?>
												<tr <?php echo (isset($checkbox[$key])&&$checkbox[$key]==1)?'class="bg-choose"':'' ?>>
													<td data-index="<?php echo $key ?>">
														<?php if(isset($checkbox[$key])&&$checkbox[$key]==1){ ?>
															<input type="checkbox" checked name="checkbox[]" value="1" class="checkbox-item">
															<input type="text" name="checkbox_val[]" value="1" class="hidden">
															<div for="" class="label-checkboxitem checked"></div>
														<?php }else{ ?>
															<input type="checkbox"  name="checkbox[]" value="1" class="checkbox-item">
															<input type="text" name="checkbox_val[]" value="0" class="hidden">
															<div for="" class="label-checkboxitem "></div>
														<?php } ?>
													</td>
													<td>
														<?php echo form_dropdown('attribute_catalogue[]', 
															$dropdown, $value,'class="form-control select3" style="width:100%" '); ?>
													</td>
													<td>
														<?php if($value==0){ ?>
															<input type="text" class="form-control" disabled="disabled">
														<?php }else{ ?>

															<select name="attribute[<?php echo $key ?>][]" data-json="<?php echo $attribute_json[$key]['json'] ?>" data-condition="<?php echo 'AND catalogueid ='.$value ?>" class="form-control selectMultipe" multiple="multiple" data-title="Nhập 2 kí tự để tìm kiếm.." data-module="attribute"  style="width: 100%;">
															</select>
														<?php } ?>
													</td>
													<td>
														<a type="button" class="btn btn-default delete-attribute"  data-id="" ><i class="fa fa-trash"></i></a>
													</td>
												</tr>
											<?php }}else{ ?>
												<tr>
													<td data-index="0">
														<input type="checkbox" name="checkbox[]" value="1" class="checkbox-item">
														<div for="" class="label-checkboxitem"></div>
														<input type="text" name="checkbox_val[]" value="0" class="hidden">
													</td>
													<td>
														<?php echo form_dropdown('attribute_catalogue[]', 
															$dropdown,
															set_value('attribute_catalogue',$this->input->get('attribute_catalogue')) ,'class="form-control select3" style="width:100%" '); ?>
													</td>
													<td>
														<input type="text" class="form-control" disabled="disabled">
													</td>
													<td>
														<a type="button" class="btn btn-default delete-attribute"  data-id="" ><i class="fa fa-trash"></i></a>
													</td>
												</tr>
											<?php } ?>

										</tbody>
									</table>
								</div>
								<div class="col-lg-12">
									<div class="uk-flex uk-flex-middle uk-flex-space-between">
										<a type="button" data-attribute = "<?php echo base64_encode(json_encode($dropdown)) ?>" style="" class="btn btn-default add-attribute m-l-sm"  data-id="" ><i class="fa fa-plus"></i> Thêm thuộc tính cho SP</a>
										<a data-toggle="modal" data-target="#create_attribute"> Tạo thuộc tính cho SP</a>
									</div>
								</div>
							</div>

							<?php if(!empty($version) && $version>0){ ?>
								<table class="table">
									<thead>
										<tr>
											<th></th>
											<th style="width:150px">Mẫu SP</th>
											<th>Giá cả</th>
											<th>Mã SP</th>
											<th>Barcode</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($title_version as $key => $value) {?>
											<tr>
												<td>
													<input type="text" name="attribute1[]" value="<?php echo $attribute1[$key] ?>" class="hidden">
													<input type="text" name="attribute2[]" value="<?php echo $attribute2[$key] ?>" class="hidden">
													<div class="uk-flex uk-flex-middle">
														<div class="image mr5">
															<div style="cursor: pointer;">
																<img src="<?php echo getthumb('template/not-found.png') ?>" class="js_choose_album" alt="">
															</div>
															<input type="text" name="image_version[]" value="<?php echo $image_version[$key] ?>" class="hidden" >
														</div>
													</div>
												</td>

												<td><input type="text" name="title_version[]" readonly value="<?php echo $title_version[$key] ?>" class="form-control"  autocomplete="off" ></td>

												<td><input type="text" name="price_version[]" value="<?php echo $price_version[$key] ?>" class="form-control int"  autocomplete="off" ></td>
												
												<td><input type="text" name="code_version[]" value="<?php echo $code_version[$key] ?>" class="form-control"  autocomplete="off" ></td>
												<td><input type="text" name="barcode_version[]" value="<?php echo $barcode_version[$key] ?>" class="form-control"  autocomplete="off" ></td>
											</tr>
											<?php if($image_version[$key] != '' ){ ?>
												<tr class="js_album_extend">
													<td colspan="100">
														<?php 
															$list_image = json_decode(base64_decode($image_version[$key], true));
															foreach ($list_image as $sub => $subs) {
																echo '<img src="'.getthumb($subs) .'" class="m-r" alt="">';
															}
														?>
														
													</td>
												</tr>
											<?php } ?>
										<?php } ?>
									</tbody>
								</table>
							<?php }else{ ?>
								<table class="table" style="display:none">
								<thead>
									<tr>
										<th></th>
										<th style="width:150px">Mẫu SP</th>
										<th>Giá cả</th>
										<th>Mã SP</th>
										<th>Barcode</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
							<?php } ?>
						</div>
					</div>

					<div class="ibox mb20 block-wholesale">
						<div class="ibox-title">
							<div class="uk-flex uk-flex-middle uk-flex-space-between">
								<h5>Bán buôn</h5>
							</div>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="col-lg-12">
									<?php if(isset($quantity_start) && is_array($quantity_start) && count($quantity_start)){ ?>
									<table class="table">
										<thead>
											<tr>
												<th>Từ (sản phẩm)</th>
												<th>Đến (sản phẩm)</th>
												<th class="text-right">Đơn Giá</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
												<?php foreach ($quantity_start as $key => $value) { ?>
													<tr>
														<td>
															<input type="text" name="quantity_start[]" value="<?php echo $quantity_start[$key] ?>" class="int form-control" placeholder="Từ (sản phẩm)" autocomplete="off" style="width:120px">
														</td>
														<td>
															<input type="text" name="quantity_end[]" value="<?php echo $quantity_end[$key] ?>" class="float form-control" placeholder="Đến (sản phẩm)" autocomplete="off" style="width:120px">
														</td>
														<td>
															<input type="text" name="price_wholesale[]" value="<?php echo $price_wholesale[$key] ?>" class="form-control float text-right" placeholder="0" autocomplete="off">
														</td>
														<td class="del_row">
															<i class="fa fa-trash"></i>
														</td>
													</tr>
												<?php } ?>
										</tbody>
									</table>
									<?php }else {?>
									<table class="table" style="display: none">
										<thead>
											<tr>
												<th>Từ (sản phẩm)</th>
												<th>Đến (sản phẩm)</th>
												<th class="text-right">Đơn Giá</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
									<?php } ?>
									<a type="button"  style="" class="btn btn-default add-wholesale"  data-id="" ><i class="fa fa-plus"></i> Thêm khoảng giá</a>
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
										<div class="g-title"><?php echo ($this->input->post('meta_title')) ? $this->input->post('meta_title') : (($this->input->post('title')) ? $this->input->post('title') : 'HT Việt Nam - Đơn vị thiết kế website hàng đầu Việt Nam'); ?></div>
										<div class="g-link"><?php echo ($this->input->post('canonical')) ? site_url($this->input->post('canonical')) : 'http://thegioiweb.org/kho-giao-dien-website.html'; ?></div>
										<div class="g-description" id="metaDescription">
											<?php echo cutnchar(($this->input->post('meta_description')) ? $this->input->post('meta_description') : (($this->input->post('description')) ? strip_tags($this->input->post('description')) : 'List of all combinations of words containing CKEDT. Words that contain ckedt letters in them. Anagrams made from C K E D T letters.List of all combinations of words containing CKEDT. Words that contain ckedt letters in them. Anagrams made from C K E D T letters.'), 360); ?>
											
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
												<span style="color:#9fafba;"><span id="titleCount">0</span> trên 70 ký tự</span>
											</div>
											<?php echo form_input('meta_title', htmlspecialchars_decode(html_entity_decode(set_value('meta_title'))), 'class="form-control meta-title" placeholder="" autocomplete="off"');?>
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
												<span style="color:#9fafba;"><span id="descriptionCount">0</span> trên 320 ký tự</span>
											</div>
											<?php echo form_textarea('meta_description', set_value('meta_description'), 'class="form-control meta-description" id="seoDescription" placeholder="" autocomplete="off"');?>
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
													<?php echo form_input('canonical', htmlspecialchars_decode(html_entity_decode(set_value('canonical'))), 'class="form-control canonical" placeholder="" autocomplete="off" data-flag="0" ');?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<button type="submit" name="create" value="create" class="btn btn-success block m-b pull-right">Tạo mới</button>
				</div>
				<div class="col-lg-4">
					<div class="ibox mb20">
						<div class="ibox-title uk-flex uk-flex-middle uk-flex-space-between">
							<h5>Phân loại </h5>
						</div>
						<div class="ibox-content">
							<div class="row mb15">
								<div class="col-lg-12">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Danh mục chính <b class="text-danger">(*)</b></span>
										</label>
										<div class="form-row">
											<?php echo form_dropdown('catalogueid', $this->nestedsetbie->dropdown(), set_value('catalogueid'), 'class="form-control m-b select3"');?>
										</div>
									</div>
								</div>
							</div>
							<div class="row mb15">
								<div class="col-lg-12">
									<div class="form-row">
										<label class="control-label text-left">
											<span>Danh mục phụ</span>
										</label>
										<div class="form-row">
											<?php echo form_dropdown('catalogue[]', '', (isset($catalogue)?$catalogue:NULL), 'class="form-control " multiple="multiple" data-title="Nhập 2 kí tự để tìm kiếm.."  style="width: 100%;" id="product_catalogue"'); ?>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<label class="control-label ">
										<span>Thương hiệu</span>
									</label>
									<div class="form-row">
										<?php echo form_dropdown('brandid', dropdown(array(
											'text'=>'Chọn thương hiệu',
											'select'=>'id, title',
											'table'=>'product_brand',
											'field'=>'id',
											'value'=>'title',
											'order_by'=>'id DESC'
										)), set_value('brandid'), 'class="form-control m-b select3"');?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ibox mb20">
						<div class="ibox-title">
							<h5>Landing view </h5>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="col-lg-12">
									<div class="form-row">
										<?php echo form_input('landing_view', htmlspecialchars_decode(html_entity_decode(set_value('landing_view'))), 'class="form-control" placeholder="" autocomplete="off"');?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php /*
					<div class="ibox mb20">
						<div class="ibox-title uk-flex uk-flex-middle uk-flex-space-between">
							<h5>File báo giá </h5>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="col-lg-12">
									<div class="form-row">
										<?php echo form_input('file_price', set_value('file_price'), 'class="form-control " placeholder="Click vào đây để upload file" autocomplete="off" onclick="openKCFinder(this, \'files\')"');?>

									</div>
								</div>
							</div>
						</div>
					</div>
					*/ ?>
					<div class="ibox mb20">
						<div class="ibox-title uk-flex uk-flex-middle uk-flex-space-between">
							<h5>Tags </h5>
							<a type="button" data-toggle="modal" data-target="#myModal6"> + Thêm mới tag</a>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="col-lg-12">
									<div class="form-row">
										<?php echo form_dropdown('tag[]', '', (isset($tag)?$tag:NULL), 'class="form-control " multiple="multiple" data-title="Nhập 2 kí tự để tìm kiếm.."  style="width: 100%;" id="tag"'); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ibox mb20">
						<div class="ibox-title">
							<h5>Video <small>Nhập mã nhúng video</small> </h5>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="col-lg-12">
									<div class="form-row">
										<?php echo form_textarea('video', set_value('video'), 'class="form-control"  rows="4"  placeholder="" autocomplete="off" ');?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ibox mb20">
						<div class="ibox-title">
							
							<h5>Icon nổi bật <small>(Icon giảm giá, khuyến mại ...)</small> </h5>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="col-lg-12">
									<div class="form-row uk-flex uk-flex-middle">
										<div class="icon_hot m-r" style="cursor: pointer;"><img src="<?php echo ($this->input->post('icon_hot')) ? $this->input->post('icon_hot') : 'template/not-found.png' ?>" class="img-thumbnail" alt=""></div>
										<?php echo form_hidden('icon_hot', htmlspecialchars_decode(html_entity_decode(set_value('icon_hot'))), 'class="form-control " placeholder="" onclick="openKCFinder(this)"  autocomplete="off"');?>
										<span>Ấn vào hình để chọn</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ibox mb20">
						<div class="ibox-title">
							
							<h5>Chọn sản phẩm kèm theo  </h5>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="col-lg-12">
									<div class="form-row">
										<?php echo form_dropdown('prd_rela[]', '', (isset($catalogue)?$catalogue:NULL), 'class="form-control selectMultipe" multiple="multiple" data-title="Nhập 2 kí tự để tìm kiếm.."  style="width: 100%;" data-module="product"'); ?>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="ibox mb20">
						<div class="ibox-title">
							<h5>Hiển thị </h5>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="col-lg-12">
									<div class="form-row">
										<span class="text-black mb15">Quản lý thiết lập hiển thị cho blog này.</span>
										<div class="block clearfix">
											<div class="i-checks mr30" style="width:100%;"><span style="color:#000;"> <input <?php echo ($this->input->post('publish') == 0) ? 'checked' : '' ?> class="popup_gender_1 gender" type="radio" value="0"  name="publish"> <i></i>Cho phép hiển thị trên website</span></div>
										</div>
										<div class="block clearfix">
											<div class="i-checks" style="width:100%;"><span style="color:#000;"> <input type="radio" <?php echo ($this->input->post('publish') == 1) ? 'checked' : '' ?>  class="popup_gender_0 gender" required value="1" name="publish"> <i></i> Không cho phép hiển thị trên website. </span></div>
										</div>
									</div>
									<?php $currentDate = gmdate('d/m/Y', time() + 7*3600); ?>
									<?php $currentTime = gmdate('H:i', time() + 7*3600); ?>
									<div class="post-setting">
										<a href="" title="" class="setting-button mb5" data-flag="0">Thiết lập ngày/giờ hiển thị</a>
										<div class="setting-group hidden">
											<div class="uk-flex uk-flex-middle uk-flex-space-between">
												<?php echo form_input('post_date', htmlspecialchars_decode(html_entity_decode(set_value('post_date', $currentDate))), 'class="form-control datetimepicker" placeholder=""  autocomplete="off"');?> 
												<span style="margin-right:5px;color:#141414;">lúc</span>
												<?php echo form_input('post_time', htmlspecialchars_decode(html_entity_decode(set_value('post_time', $currentTime))), 'class="form-control" placeholder="" id="input-clock" autocomplete="off" data-default="20:48"');?>
											</div>
										</div>
									</div>
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

<div class="modal inmodal fade in" id="myModal6" tabindex="-1" role="dialog" aria-hidden="true" >
	<form class="" method="POST" action="" id="create-tag">
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
					<button type="submit" class="btn btn-primary">Thêm mới</button>
				</div>
			</div>
		</div>
	</form>
</div>
<div class="modal inmodal fade" id="create_attribute" tabindex="-1" role="dialog"  aria-hidden="true">
	<form class="" method="" action="" id="form_create_attribute" >
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Thêm mới thuộc tính</h4>
					<small class="font-bold text-danger">Cập nhật đầy đủ thông tin người dùng giúp việc quản lý dễ dàng hơn</small>
				</div>
				<div class="modal-body p-md">
					<div class="row">
						<div class="box-body error hidden">
							<div class="alert alert-danger"></div>
						</div><!-- /.box-body -->
					</div>
					<div class="form-group">
						<div class="row">
						<label class="col-md-4">
							<div class=" control-label">
								<span class="m-r">Tên thuộc tính <b class="text-danger">(*)</b></span>
							</div>
						</label>
						<div class="col-md-8">
							<?php echo form_input('title', set_value('title'), 'class="form-control " placeholder="" autocomplete="off"');?>
						</div>
						</div>
					</div>
					
					<div class="form-group">
						<div class="row">
						<label class="col-md-4">
							<div class=" control-label">
								<span class="m-r">Nhóm thuộc tính <b class="text-danger">(*)</b></span>
							</div>
						</label>
						<div class="col-md-8">
								<?php echo form_dropdown('catalogueid', 
									dropdown(array(
										'text'=>'---Chọn nhóm thuộc tính---',
										'select'=>'id, title',
										'table'=>'attribute_catalogue',
										'field'=>'id',
										'value'=>'title',
										'order_by'=>'id DESC'
									)),
									set_value('catalogueid',$this->input->get('catalogueid1')) ,'class="form-control input-sm perpage  catalogueid" style="width:100% '); ?>
						</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Thêm mới</button>
				</div>
			</div>
		</div>
	</form>
</div>
