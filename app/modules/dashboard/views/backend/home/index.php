<div id="page-wrapper" class="gray-bg">
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-success pull-right">Tháng</span>
                        <h5>Tổng số đơn hàng mới</h5>
                    </div>
                    <div class="ibox-content">
                    	<?php 
                    		$firstDateInMonth = date('Y-m-01 00:00:00');
							$dateNow = date('Y-m-d H:i:s');
                    		$totalOrderInMonthOld = $this->Autoload_Model->_get_where(array(
								'select' => 'id',
								'table' => 'order',
								'query' => ' ( `created` >= "'.operatotime($firstDateInMonth, 1, 'm').' " ) AND ( created` <= "'.operatotime($dateNow, 1, 'm').' " ) ',
								'count' => TRUE,
							));

                    		$totalOrderInMonth = $this->Autoload_Model->_get_where(array(
								'select' => 'id',
								'table' => 'order',
								'query' => ' ( `created` >= "'.$firstDateInMonth.' " ) AND ( created` <= "'.$dateNow.' " ) ',
								'count' => TRUE,
							));
                            $percent = (!empty($totalOrderInMonthOld)) ? (100*$totalOrderInMonth/$totalOrderInMonthOld) : 100;
                    	 ?>
                        <h1 class="no-margins"><?php echo $totalOrderInMonth ?></h1>
                        <div class="stat-percent font-bold text-success"><?php echo $percent ?>% <i class="fa fa-bolt"></i></div>
                        <small>Đơn hàng</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Tổng số đơn hàng</h5>
                    </div>
                    <div class="ibox-content">
                    	<?php 
							$totalOrder = $this->Autoload_Model->_get_where(array(
								'select' => 'id',
								'table' => 'order',
								'query' => ' ( `created` >= "'.$firstDateInMonth.' " ) AND ( created` <= "'.$dateNow.' " ) ',
								'count' => TRUE,
							));
                    	 ?>
                        <h1 class="no-margins"><?php echo $totalOrder ?></h1>
                        <small>Đơn hàng</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Tổng số sản phẩm</h5>
                    </div>
                    <div class="ibox-content">

						<?php 
    						$totalProduct = $this->Autoload_Model->_get_where(array(
    							'select' => 'id',
    							'table' => 'product',
    							'where' => array('publish' => 0),
    							'count' => TRUE,
						)); ?>
                        <h1 class="no-margins"><?php echo $totalProduct ?></h1>
                        <small>Sản phẩm</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Tổng số liên hệ</h5>
                    </div>
                    <div class="ibox-content">
                        <?php 
                            $totalContact = $this->Autoload_Model->_get_where(array(
                                'select' => 'id',
                                'table' => 'contact',
                                'count' => TRUE,
                        )); ?>
                        <h1 class="no-margins"><?php echo $totalContact ?></h1>
                        <small>Liên hệ</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Orders</h5>
                        
                        <div class="pull-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-xs btn-white active">Today</button>
                                <button type="button" class="btn btn-xs btn-white">Monthly</button>
                                <button type="button" class="btn btn-xs btn-white">Annual</button>
                            </div>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                        <div class="col-lg-9">
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="flot-dashboard-chart"></div>
                            </div>
                        </div>
                        <?php 
                            $firstDateInMonth = date('Y-m-01 00:00:00');
                            $dateNow = date('Y-m-d H:i:s');
                            $lastDate = date("t", strtotime(date('Y-m-d'))) ;
                            $lastDateInMonth = date('Y-m-'.$lastDate.' 00:00:00');
                            $totalOrderInMonthOldOld = $this->Autoload_Model->_get_where(array(
                                'select' => 'id',
                                'table' => 'order',
                                'query' => ' ( `created` >= "'.operatotime($firstDateInMonth, 1, 'm').' " ) AND ( created` <= "'.operatotime($lastDateInMonth, 2, 'm').' " ) ',
                                'count' => TRUE,
                            ));
                            $totalOrderInMonthOld = $this->Autoload_Model->_get_where(array(
                                'select' => 'id',
                                'table' => 'order',
                                'query' => ' ( `created` >= "'.operatotime($firstDateInMonth, 1, 'm').' " ) AND ( created` <= "'.operatotime($lastDateInMonth, 1, 'm').' " ) ',
                                'count' => TRUE,
                            ));

                            $totalOrderInMonth = $this->Autoload_Model->_get_where(array(
                                'select' => 'id',
                                'table' => 'order',
                                'query' => ' ( `created` >= "'.$firstDateInMonth.' " ) AND ( created` <= "'.$dateNow.' " ) ',
                                'count' => TRUE,
                            ));
                            $percent = (!empty($totalOrderInMonthOld)) ? (100*$totalOrderInMonth/$totalOrderInMonthOld) : 100;
                            $percentOld = (!empty($totalOrderInMonthOldOld)) ? (100*$totalOrderInMonthOld/$totalOrderInMonthOldOld) : 100;

                            $totalPriceOrder = $this->Autoload_Model->_get_where(array(
                                'select' => 'SUM(price_final*quantity) as totalPriceOrder',
                                'table' => 'order_relationship',
                                'query' => ' ( `created` >= "'.$firstDateInMonth.' " ) AND ( created` <= "'.$dateNow.' " ) ',
                                'order_by' => 'created ASC'
                            ));
                            $totalPriceOrderOld = $this->Autoload_Model->_get_where(array(
                                'select' => 'SUM(price_final*quantity) as totalPriceOrder',
                                'table' => 'order_relationship',
                                'query' => ' ( `created` >= "'.operatotime($firstDateInMonth, 1, 'm').' " ) AND ( created` <= "'.operatotime($lastDateInMonth, 2, 'm').' " ) ',
                                'order_by' => 'created ASC'
                            ));
                            $percentOrder = (!empty($totalPriceOrderOld['totalPriceOrder'])) ? (100*$totalPriceOrder['totalPriceOrder']/$totalPriceOrderOld['totalPriceOrder']) : 100;

                         ?>
                        <div class="col-lg-3">
                            <ul class="stat-list">
                                <li>
                                    <h2 class="no-margins"><?php echo $totalOrderInMonth ?></h2>
                                    <small>Tổng số đơn hàng trong tháng</small>
                                    <div class="stat-percent"><?php echo $percent ?>%<i class="fa fa-level-up text-navy"></i></div>
                                    <div class="progress progress-mini">
                                        <div style="width: <?php echo $percent ?>%;" class="progress-bar"></div>
                                    </div>
                                </li>
                                <li>
                                    <h2 class="no-margins "><?php echo $totalOrderInMonthOld ?></h2>
                                    <small>Tổng số đơn hàng trong tháng trước</small>
                                    <div class="stat-percent"><?php echo $percentOld ?>% <i class="fa fa-level-down text-navy"></i></div>
                                    <div class="progress progress-mini">
                                        <div style="width: <?php echo $percentOld ?>%;" class="progress-bar"></div>
                                    </div>
                                </li>
                                <li>
                                    <h2 class="no-margins "><?php echo addCommas($totalPriceOrder['totalPriceOrder']) ?></h2>
                                    <small>Thu nhập hàng tháng từ đơn hàng</small>
                                    <div class="stat-percent"><?php echo $percentOrder ?>% <i class="fa fa-bolt text-navy"></i></div>
                                    <div class="progress progress-mini">
                                        <div style="width: <?php echo $percentOrder ?>%;" class="progress-bar"></div>
                                    </div>
                                </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>




        <div class="row">
            <div class="col-lg-8">
                <?php $this->load->view('backend/common/comment_dashboard'); ?>
            </div>

            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Đơn hàng mới</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <?php 
                            $listOrder = $this->Autoload_Model->_get_where(array(
                                'select' => ' code, phone, id',
                                'table' => 'order',
                                'limit' => 10,
                                'order_by' => 'order desc, id desc, fullname asc',
                            ), TRUE);   
                         ?>
                        <?php if(isset($listOrder) && is_array($listOrder) && count($listOrder)){ ?>
                        <table class="table table-hover no-margins">
                            <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>SĐT</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            
                            <?php foreach ($listOrder as $key => $val) { ?>
                                <tr>
                                    <td><?php echo $val['code'] ?></td>
                                    <td><?php echo number_phone($val['phone']) ?></td>
                                    <td><a href="<?php echo site_url('order/backend/order/update/'.$val['id']); ?>">
                                        <span class="label label-primary">Xem chi tiết</span>
                                    </a></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Danh sách sp</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <?php 
                            $detailProduct = $this->Autoload_Model->_get_where(array(
                                'select' => 'title, price, price_sale, price_contact',
                                'table' => 'product',
                                'where' => array('publish' => 0),
                                'order_by' => 'created DESC'
                            ), true); 
                        ?>

                        <?php if(isset($detailProduct) && is_array($detailProduct) && count($detailProduct)){ ?>
                                <table class="table table-hover margin bottom">
                                    <thead>
                                    <tr>
                                        <th style="width: 1%" class="text-center">STT</th>
                                        <th>Tên SP</th>
                                        <th class="text-center">Giá</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($detailProduct as $key => $val) { ?>
                                        <?php 
                                           if($val['price_contact'] == 1 ){
                                                $price = 'Giá liên hệ';
                                            }else{
                                                $price = (!empty($val['price_sale']))? $val['price_sale'] : $val['price'];
                                                $price = addCommas($price);
                                            }
                                         ?>
                                        <tr>
                                            <td class="text-center"><?php echo $key ?></td>
                                            <td> <?php echo cutnchar($val['title'], 30) ?>
                                                </td>
                                            <td class="text-center"><?php echo $price ?>đ</td>
                                        </tr>
                                        <?php } ?>
                                   
                                    </tbody>
                                </table>

                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Danh sách bài viết</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">

                        <div class="row">
                            <div class="col-lg-12">
                            <?php 
                                $detailArticle = $this->Autoload_Model->_get_where(array(
                                    'select' => 'title,publish_time',
                                    'table' => 'product',
                                    'order_by' => 'publish_time DESC'
                                ), true); 
                            ?>

                            <?php if(isset($detailArticle) && is_array($detailArticle) && count($detailArticle)){ ?>

                                <table class="table table-hover margin bottom">
                                    
                                    <thead>
                                    <tr>
                                        <th style="width: 1%" class="text-center">STT</th>
                                        <th>Tiêu đề</th>
                                        <th class="text-center">Ngày đăng</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($detailArticle as $key => $val) { ?>
                                        <tr>
                                            <td class="text-center"><?php echo $key ?></td>
                                            <td> <?php echo $val['title'] ?>
                                                </td>
                                            <td class="text-center"><?php echo gettime($val['publish_time']) ?></td>
                                        </tr>
                                    <?php } ?>
                                   
                                    </tbody>
                                </table>
                                <?php } ?>
                            </div>
                        </div>
                            
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    // $time_start1 = microtime(true);
    // $time_end1 = microtime(true);
    // $getProfit = getProfitRevenue();
    // echo $time_start1 - $time_end1;
 ?>
<script>
    $(document).ready(function() {
        $('.chart').easyPieChart({
            barColor: '#f8ac59',
//                scaleColor: false,
            scaleLength: 5,
            lineWidth: 4,
            size: 80
        });

        var data2 = <?php echo $getProfit['profit'] ?>;
        var data3 = <?php echo $getProfit['revenue'] ?>;

        var dataset = [

            {
                label: "Doanh thu",
                data: data3,
                color: "#1ab394",
                bars: {
                    show: true,
                    align: "center",
                    barWidth: 24 * 60 * 60 * 600,
                    lineWidth:0
                }

            }, {
                label: "Lợi nhuận",
                data: data2,
                yaxis: 2,
                color: "#1C84C6",
                lines: {
                    lineWidth:1,
                        show: true,
                        fill: true,
                    fillColor: {
                        colors: [{
                            opacity: 0.2
                        }, {
                            opacity: 0.4
                        }]
                    }
                },
                splines: {
                    show: false,
                    tension: 0.6,
                    lineWidth: 1,
                    fill: 0.1
                },
            }
        ];


        var options = {
            xaxis: {
                mode: "time",
                tickSize: [3, "day"],
                tickLength: 0,
                axisLabel: "Date",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Arial',
                axisLabelPadding: 10,
                color: "#d5d5d5"
            },
            yaxes: [{
                position: "left",
                max: 20000000,
                color: "#d5d5d5",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Arial',
                axisLabelPadding: 67
            }, {
                max: 20000000,
                position: "right",
                clolor: "#d5d5d5",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: ' Arial',
                axisLabelPadding: 67
            }
            ],
            legend: {
                noColumns: 1,
                labelBoxBorderColor: "#000000",
                position: "nw"
            },
            grid: {
                hoverable: false,
                borderWidth: 0
            }
        };

        function gd(year, month, day) {
            return new Date(year, month - 1, day).getTime();
        }

        var previousPoint = null, previousLabel = null;

        $.plot($("#flot-dashboard-chart"), dataset, options);
        
    });
</script>
<?php
    // $time_end = microtime(true);
    // echo $time_start - $time_end;
 ?>