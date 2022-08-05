 <form class="uk-form form" method="post" action="">
<div id="prddetail" class="page-body">
    <div class="main-breadcrumb">
        <div class="uk-container uk-container-center">
            <ul class="uk-breadcrumb uk-margin-remove">
                <li><a href="" title="Trang chủ">Trang chủ</a></li>
                <li class="uk-active"><a href="" title="">Thông tin đặt lịch</a></li>
            </ul>
        </div>
    </div>
    
    <section id="cart" class="cart-wrapper">
        <div class="uk-container uk-container-center">
            <div class="uk-grid uk-grid-width-medium uk-grid-width-medium-1-1 uk-grid-width-large-1-2">
                <div class="cart-info">
                   
                    <section class="cart-panel cart-customer">
                        <header class="panel-head"><h2 class="heading"><span>Thông tin mua hàng</span></h2></header>
                        <section class="panel-body">
                            <?php $error = validation_errors(); echo !empty($error)?'<div class="alert alert-danger">'.$error.'</div>':'';?>
                            <div class="form-row uk-flex uk-flex-middle uk-clearfix">
                                <div class="col-1"><label>Họ và tên</label></div>
                                <div class="col-2">
                                    <?php echo form_input('fullname', set_value('fullname'), 'class="input-text" placeholder="Nhập họ tên" autocomplete="off"');?>
                                </div>
                            </div>
                            <div class="form-row uk-flex uk-flex-middle uk-clearfix">
                                <div class="col-1"><label>Số điện thoại</label></div>
                                <div class="col-2">
                                    <div class="uk-grid uk-grid-medium uk-grid-width-large-1-2">
                                        <div>
                                            <?php echo form_input('phone', set_value('phone'), 'class="input-text" placeholder="Số điện thoại" autocomplete="off"');?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row uk-flex uk-flex-middle uk-clearfix">
                                <div class="col-1"><label>Địa chỉ</label></div>
                                <div class="col-2">
                                    <?php echo form_textarea('address_detail', set_value('address_detail'), 'class="textarea" placeholder="Nhập địa chỉ đầy đủ: Số nhà, tên đường" autocomplete="off"');?>
                                </div>
                            </div>
                            <div class="form-row uk-flex uk-flex-middle uk-clearfix">
                                <div class="col-1"><label>Email</label></div>
                                <div class="col-2">
                                    <?php echo form_input('email', set_value('email'), 'class="input-text" placeholder="Nhập địa chỉ Email, không bắt buộc" autocomplete="off"');?>
                                </div>
                            </div>
                            <div class="form-row uk-flex uk-flex-middle uk-clearfix">
                                <div class="col-1"><label>Tỉnh/Thành Phố</label></div>
                                <div class="col-2">
                                    <?php 
                                        $listCity = getLocation(array(
                                            'select' => 'name, provinceid',
                                            'table' => 'vn_province',
                                            'field' => 'provinceid',
                                            'text' => 'Chọn Tỉnh/Thành Phố'
                                        ));
                                    ?>
                                    <?php echo form_dropdown('cityid', $listCity, '', 'class="form_dropdown"  id="city" placeholder="" autocomplete="off"');?>
                                </div>
                            </div>
                            <div class="form-row uk-flex uk-flex-middle uk-clearfix">
                                <div class="col-1"><label>Quận/Huyện</label></div>
                                <div class="col-2">
                                    <select name="districtid" id="district" class="location">
                                        <option value="">Chọn Quận/Huyện</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row uk-flex uk-flex-middle uk-clearfix">
                                <div class="col-1"><label>Phường/Xã</label></div>
                                <div class="col-2">
                                    <select name="wardid" id="ward" class="location">
                                        <option value="">Chọn Phường/Xã</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row uk-flex uk-flex-middle uk-clearfix">
                                <div class="col-1"><label>Ghi chú </label></div>
                                <div class="col-2">
                                     <?php echo form_textarea('note', set_value('note'), 'class="text-area" style="height:60px; width:100%" placeholder="Hãy để lại lời nhắn" autocomplete="off"');?>
                                </div>
                            </div>

                             <script>
                                var cityid = '<?php echo $this->input->post('cityid'); ?>';
                                var districtid = '<?php echo $this->input->post('districtid') ?>';
                                var wardid = '<?php echo $this->input->post('wardid') ?>';
                            </script>
                        

                            
                        </section><!-- .panel-body -->
                    </section><!-- .cart-customer -->
                   
                
                </div>


                <div>
                    <div class="cart-panel cart-product">
                        <section class="list-product">
                            <header class="panel-head"><h2 class="heading"><span>Lịch(</h2></header>
                            <section class="panel-body ">
                                <?php $currentDate = gmdate('d/m/Y', time() + 7*3600); ?>
                                <h3>Chọn ngày</h3>
                                <?php echo form_input('post_date', htmlspecialchars_decode(html_entity_decode(set_value('post_date', $currentDate))), 'class="input-text datetimepicker" placeholder=""  autocomplete="off"');?> 
                                <h4>Lịch trong ngày</h4>
                                <input class="hidden" name="input_time" value="">
                                <div class="uk-grid  list-time">
                                    <?php if(isset($bookingList) && check_array($bookingList) ){ ?>
                                        <?php foreach ($bookingList as $key => $val) { ?>
                                            <div class="uk-width-1-4">
                                                <div class="btn-time <?php echo ($val['status'] ==1 || $val['status'] ==2) ? 'disable' : '' ?>" data-id="<?php echo $val['id'] ?>">
                                                    Từ <?php echo $val['start'] ?> đến <?php echo $val['end'] ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </section>
                            <script>
                                $(document).ready(function() {
                                    $('.datetimepicker').datepicker({
                                        todayBtn: "linked",
                                        keyboardNavigation: false,
                                        forceParse: false,
                                        calendarWeeks: true,
                                        autoclose: true,
                                        dateFormat: "dd/mm/yy"
                                    });
                                });
                            </script>
                        </section>

                    </div>
                     <div class="uk-flex uk-flex-right cart-checkout">
                            <button type="submit" name="create" value="create" class="btn-checkout" value=""><i class="fa fa-shopping-cart"></i>Đặt lịch</button>
                        </div>
                </div>   
            </div>
        </div>
    </section>
    
</div><!-- #prdcatalogue -->
</form>