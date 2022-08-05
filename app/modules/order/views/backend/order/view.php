<div id="page-wrapper" class="gray-bg dashbard-1">
    <div class="row border-bottom">
        <?php $this->load->view('dashboard/backend/common/navbar'); ?>
    </div>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Danh sách đơn hàng </h2>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo site_url('admin'); ?>">Home</a>
                </li>
                <li class="active"><strong>Danh sách đơn hàng </strong></li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight ecommerce block-order-view">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Danh sách đơn hàng</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-article">
                                <li><a type="button" class="ajax-recycle-all" data-title="Lưu ý: Khi bạn xóa danh mục bài viết, toàn bộ bài viết trong nhóm này sẽ bị xóa. Hãy chắc chắn rằng bạn muốn thực hiện chức năng này!" data-module="article_catalogue">Xóa tất cả</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content" style="position:relative;">
                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                <div>
                                    <div class="uk-flex uk-flex-middle mb10">
                                        <?php echo form_dropdown('perpage', $this->configbie->data('perpage'), set_value('perpage',$this->input->get('perpage')) ,'class="form-control input-sm perpage filter"  data-url="'.site_url('article/backend/catalogue/view').'"'); ?>
                                    </div>
                                </div>
                            </div>
                           
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" for="promotionalid">Chương trình khuyến mại</label>
                                        <?php echo form_dropdown('promotionalid', dropdown(array('table' => 'promotional', 'query' =>'catalogue = "KM"', 'text' => 'Chọn CTKM')), set_value('promotionalid',$this->input->get('promotionalid')) ,'class="form-control input-sm filter m-r select3NotSearch"'); ?>

                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" for="">Mã CP</label>
                                        <?php echo form_dropdown('couponid', dropdown(array('table' => 'promotional', 'query' =>'catalogue = "CP"', 'text' => 'Chọn CP')), set_value('couponid',$this->input->get('couponid')) ,'class="form-control input-sm filter m-r select3NotSearch"'); ?>

                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" for="status">Trạng thái</label>
                                        <?php echo form_dropdown('status', $this->configbie->data('state_order'), set_value('status',$this->input->get('status')) ,'class="form-control input-sm filter m-r select3NotSearch"'); ?>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" for="date_added">Ngày tạo đơn</label>
                                        <div class="input-group date">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <?php echo form_input('date_added', set_value('date_added'), 'autocomplete="off" placeholder="Chọn ngày" id="date_added" class="form-control filter datetimepicker m-b"');?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" for="date_modified">Ngày cập nhật đơn</label>
                                        <div class="input-group date">
                                            <?php echo form_input('date_modified', set_value('date_modified'), 'autocomplete="off" placeholder="Chọn ngày" id="date_modified" class="form-control filter datetimepicker m-b"');?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" for="keyword">Tìm kiếm</label>
                                        <input type="text" id="keyword"  name="keyword" value="" placeholder="Tìm kiếm theo tên, id, mã code, địa chỉ đơn hàng" class="form-control filter ">
                                    </div>
                                </div>
                            </div>

                             <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                <div class="text-small mb10">Hiển thị từ <?php echo $from; ?> đến <?php echo $to ?> trên tổng số <span id="total_row">
                                    <?php echo $config['total_rows']; ?>
                                </span> bản ghi</div>
                                <div class="text-small text-danger">*Sắp xếp Vị trí hiển thị theo quy tắc: Số lớn hơn được ưu tiên hiển thị trước. </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">

                                    <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="15">
                                        <thead>
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-left">Tên khách hàng</th>
                                            <th class="text-left">Số điện thoại</th>
                                            <th class="text-right">Tổng tiền</th>
                                            <th class="text-center">Ngày tạo</th>
                                            <th class="text-center">Ngày cập nhật</th>
                                            <th class="text-center">Trạng thái</th>
                                            <th class="text-center">Hành động</th>

                                        </tr>
                                        </thead>
                                        <tbody id="ajax-content">
                                        <?php if(isset($listorder) && is_array($listorder) && count($listorder)){ ?>
                                                <?php foreach($listorder as $key => $val){ ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $val['id']  ?></td>
                                                    <td class="text-left"><?php echo $val['fullname']  ?></td>
                                                    <td class="text-left"><?php echo number_phone($val['phone'], ' ')  ?></td>
                                                    <td class="text-right"><?php echo addCommas($val['total_cart_final'])  ?></td>
                                                    <td class="text-center"><?php echo gettime($val['created'],'d-m-Y')  ?></td>
                                                    <td class="text-center"><?php echo ($val['updated'] != '0000-00-00 00:00:00') ? gettime($val['updated'],'d-m-Y') : '-'  ?></td>
                                                    <td class="text-center">
                                                        <?php echo $this->configbie->data('state_order', $val['status']) ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <a type="button" href="<?php echo site_url('order/backend/order/update/'.$val['id'].'') ?> "  class="not-decoration js_open_windown"><span class="label  label-info">Cập nhật</span></a>
                                                        <a type="button" class="not-decoration   ajax-delete"  data-id="<?php echo $val['id'] ?>"  data-module="order"><span class="label label-warning">Xóa</span></a>
                                                    </td>
                                                </tr>
                                        <?php }} ?>
                                        </tbody>
                                        
                                    </table>
                                    <div id="pagination"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 