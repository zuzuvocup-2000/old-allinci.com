<script type="text/javascript">
	var submit = '<?php echo $this->input->post('update'); ?>';
	var worker = '<?php
		if($this->input->post('update')){
			echo json_encode($this->input->post('worker')); 
		}else{
			echo json_encode($worker);
		}
	?>';
</script>
<form method="post" role="form" action="" class="form-horizontal">	
<div id="page-wrapper" class="gray-bg dashbard-1">
	<div class="wrapper animated fadeInRight import">
		<div class="row">
            <div class="col-sm-9" style="padding-left:0;padding-right:0;">
                <div class="ibox">
					<div class="ibox-title">
						<h3>Cập nhật đơn xuất hàng
							<small class="text-danger">(Điền đầy đủ các thông tin được hướng dẫn dưới đây.)</small>
						</h3>
					</div>
					<div class="ibox-content">
						<div class="row m-b-xs">
							<div class="col-md-3">
								<div class="input-group icon-left">
									<i class="fa fa-user" aria-hidden="true"></i>
									 <?php echo form_input('customerid', set_value('customerid',$detail_construction['customerid']), 'class="form-control customerid hidden" ');?>
	                                <?php echo form_input('customer', set_value('customer','Khách hàng: '.$detail_construction['customer']), 'class="form-control" placeholder="Chọn khách hàng" id="customer" autocomplete="off" readonly');?>
	                                <ul id="list-customer">
	                                </ul>
	                            </div>
							</div>
							<div class="col-md-3">
								<div class="input-group">
	                                 <?php echo form_input('construction_title',set_value('construction_title','Nhóm công trình: '.$detail_construction['catalogue']), 'class="form-control" id="" autocomplete="off" placeholder="Tên công trình" readonly');?>
	                            </div>
							</div>
							<div class="col-md-3">
								<div class="input-group">
	                                <?php echo form_input('construction_title',set_value('construction_title','Tên công trình: '.$detail_construction['title']), 'class="form-control" id="" autocomplete="off" placeholder="Tên công trình" readonly');?>
	                            </div>
							</div>
								<div class="col-md-3">
								<div class="input-group">
	                                <?php echo form_input('code', 'Mã đơn: '.$detail_construction['code'], 'class="form-control" id="code" autocomplete="off" placeholder="Mã công trình" readonly');?>
	                            </div>
							</div>
						</div>
						<div class="row m-b-xs">
							<div class="col-md-3">
								<div class="input-group icon-left">
									<i class="fa fa-search"></i>
	                                <?php echo form_input('product', '', 'class="form-control" id="product" autocomplete="off" placeholder="Chọn sản phẩm" readonly');?>
	                                <ul id="list-product">
	                                </ul>
	                            </div>
							</div>
							<div class="col-md-3">
								<div class="input-group">
	                                <?php echo form_input('user_charge', 'NVKD: '.$detail_construction['userid_charge'], 'class="form-control" id="user_charge" autocomplete="off" readonly ');?>
	                            </div>
							</div>
							<div class="col-md-3">
								<div class="input-group">
	                                <?php echo form_input('date_start',set_value('date_start', 'Ngày khởi công: '.$detail_construction['date_start']), 'class="form-control"  autocomplete="off" readonly ');?>
	                            </div>
							</div>
							<div class="col-md-3">
								<?php echo form_dropdown('worker[]', '', '', 'class="form-control select2 " multiple="multiple" data-title="Nhập 2 kí tự để chọn thợ"  style="width: 100%;" id="user" readonly');?>
							</div>
						</div>
						<div style="min-height: 290px! important">
                        	<table class="table">
                        		<?php if($detailData['stockid'] == 1){ ?>
	                            <thead>
		                            <tr class="">
		                                <th>Mã SP</th>
		                                <th style="width:250px">Tên SP</th>
		                                <th style="width:90px">Đơn vị</th>
		                                <th class="text-right">SL mang đi</th>
		                                <th class="text-right">Đơn giá</th>
		                                <th class="text-right">SL trả</th>
		                                <th class="text-right">SL hỏng</th>
	                                	<th class="text-right hidden">Giá trả</th>
		                                <th class="text-right">Thành tiền</th>
		                            </tr>
	                            </thead>
	                            <tbody  id="table_product">
		                            <?php if(isset($list_product) && is_array($list_product) && count($list_product)) { ?>
	                            	<?php foreach($list_product as $key => $val){ ?>
		                            <tr>
		                            	<?php 
			                            	$deatail_product= $this->Autoload_Model->_get_where(array(
			                                	'table'=>'product',
			                                	'where'=>array('id'=>$val['id']),
			                                	'select'=>'id,code, title, measure, quantity_in_stock'
			                                ));
			                                $measure=$this->configbie->data('measure', (int)$deatail_product['measure']);
											
		                                 ?>
								        <td class="code">
								        	<?php echo $deatail_product['code'] ?>
								        	<?php echo form_input('product[code][]', $deatail_product['code'], 'class="form-control hidden"');?>
							        	</td>
								        <td class="title" style="width:250px"><?php echo cutnchar($deatail_product['title'],60) ?> 
								       		<?php echo form_input('product[id][]',(isset($deatail_product['id'])) ? $deatail_product['id'] : 0 , 'class="form-control hidden"');?>
								       		<?php echo form_input('product[quantity_in_stock][]',(isset($deatail_product['quantity_in_stock'])) ? $deatail_product['quantity_in_stock'] : 0 , 'class="form-control hidden"');?>
								       		<?php echo form_input('product[quantity_old][]',(isset($val['quantity'])) ? $val['quantity'] : 0, 'class="form-control hidden"');?>
								        </td>
								        <td class="measure">
								        	<?php echo $measure ?>
								        	<?php echo form_input('product[measure][]',$measure, 'class="form-control hidden"');?>
								        <td>
								        	<div class="input-group">
								        		<?php echo form_input('product[quantity][]',(isset($val['quantity'])) ? $val['quantity'] : 0 , ' data-quantity-old="" class="form-control text-right float" placeholder="" 	autocomplete="off"  style="height: 25px"');?>
								            </div>
								        </td>
								        <td>
								       		<div class="input-group">
								        		<input type="text" name="product[price][]" style="height: 25px " value="<?php echo number_format($val['price_output'],0,',','.') ?>" class="text-right form-control" readonly>
								        	</div>
								        </td>
								        <td class="quantity_paid">
								       		<?php echo form_input('product[quantity_paid][]',(isset($val['quantity_paid'])) ? $val['quantity_paid'] : 0 , 'class="form-control text-right float" placeholder="" autocomplete="off"  style="height: 25px" ');?>
								        </td>
								        <td class="quantity_error">
								       		<?php echo form_input('product[quantity_error][]',(isset($val['quantity_error'])) ? $val['quantity_error'] : 0 , 'class="form-control text-right float" placeholder="" 
										autocomplete="off"  style="height: 25px"');?>
								        </td>
								        <td class="money text-right"><input type="text"  name="price[]" value="" class="hidden form-control">
											<?php 
												$val['quantity_paid'] = (isset($val['quantity_paid'])) ? $val['quantity_paid'] : 0;
												$val['quantity'] = (isset($val['quantity'])) ? $val['quantity']: 0;
												$val['quantity_error'] = (isset($val['quantity_error'])) ? $val['quantity_error']: 0;
												echo number_format(($val['quantity']-$val['quantity_paid']-$val['quantity_error'])*$val['price_output'],0,',','.'); 
											?>
								        </td>
								    </tr>
									<?php }} ?>
	                            </tbody>
	                            <?php }else{ ?>
								<thead>
		                            <tr class="">
		                                <th>Mã SP</th>
		                                <th style="width:250px">Tên SP</th>
		                                <th style="width:90px">Đơn vị quy đổi</th>
		                                <th class="text-right">Số lượng</th>
		                                <th class="text-right">Đơn giá</th>
		                                <th class="text-right">Thành tiền</th>
		                            </tr>
	                            </thead>
	                            <tbody  id="table_prd_out">
		                            <?php if(isset($list_product) && is_array($list_product) && count($list_product)) { ?>
	                            	<?php foreach($list_product as $key => $val){ ?>
		                            <tr>
								        <td><?php echo $val['code'] ?>
								        	<?php echo form_input('prd_out[code][]',(isset($val['code'])) ? $val['code'] : 0 , 'class="form-control  hidden" ');?>
								        	<?php echo form_input('prd_out[supplierid][]',(isset($val['supplierid'])) ? $val['supplierid'] : 0 , 'class="form-control  hidden" ');?>
								        </td>
								        <td class="title" style="width:250px"><?php echo cutnchar($val['title'],60) ?> 
								       		<?php echo form_input('prd_out[title][]',(isset($val['title'])) ? $val['title'] : 0 , 'class="form-control hidden" ');?>
								        </td>
								       	<td>
								       		<?php echo $this->configbie->data('measure', $val['measure_exchange'])  ?>
								        	<?php echo form_input('prd_out[measure_exchange][]',$val['measure_exchange'] , 'class="form-control hidden" ');?>
								       	</td>
								        <td>
								        	<div class="input-group">
								        		<?php echo form_input('prd_out[quantity][]',(isset($val['quantity'])) ? $val['quantity'] : 0 , 'class="form-control text-right int" placeholder="" 	autocomplete="off"  style="height: 25px" ');?>
								            </div>
								        </td>
								        <td>
								       		<div class="input-group">
								        		<input type="text" name="prd_out[price][]" style="height: 25px " value="<?php echo number_format($val['price_output'],0,',','.') ?>" class="text-right form-control" readonly>
								        	</div>
								        </td>
						       			 </td>
								        <td class="money text-right"><input type="text"  name="price[]" value="" class="hidden form-control">
											<?php 
												$val['quantity'] = (isset($val['quantity'])) ? $val['quantity']: 0;
												echo number_format($val['quantity']*$val['price_output'],0,',','.'); 
											?>
								        </td>
								    </tr>
									<?php }} ?>
	                            </tbody>
	                            <?php } ?>
	                        </table>
                        </div>
                    </div>
                    <div class="ibox-title	">
                    	<div class="row">
                    		<div class="col-md-6">
                    			<?php echo form_textarea('note', set_value('note',$detailData['note']), 'class="form-control m-b-xs" placeholder="Ghi chú đơn xuất" style="height: 70px"  ');?>
                    		</div>
                    		<div class="col-md-6">
                    			<div class="uk-flex uk-flex-space-between font-bold">
                    				<span>Thành tiền</span> 
                    				<input type="text"  name="total_money" value="" class="hidden">
                    				<span class="total_money"><?php echo number_format($detailData['total_money'],0,',','.');  ?></span>
                    			</div>
                    		</div>
                    	</div>
                    </div>
                    <div class="ibox-title">
						<div class="uk-flex uk-flex-middle uk-flex-space-between">
							<div class="uk-flex uk-flex-middle">
								
							</div>
							<div class="uk-button">
								<button style="margin-right:2px;" class="btn btn-primary btn-sm" name="update" value="create" type="submit">Cập nhật đơn</button>
							</div>
						</div>
					</div>
                </div>
            </div>
            <div class="col-sm-3 m-b-xs" style="padding-right:0;">
        		<div class="ibox-title " >
            		<h3><i class="fa fa-file-text m-r-sm" aria-hidden="true"></i>Tạo phiếu xuất kho</h3>
            	</div>
            	<div class="ibox-content uk-clearfix">
        			<div class="m-b-sm">Thời gian xuất hàng: <?php echo $detailData['time_finish'] ?></div>
					<div class="uk-clearfix m-b-sm">
						<a type="button" class=" btn btn-sm btn-success pull-right"><i class="fa fa-sign-in m-r-xs"></i>
            			In phiếu
	            		</a>
					</div>
            	</div>
            </div>
        </div>
	</div>
</div>
</form>
