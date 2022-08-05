<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<base href="<?php echo BASE_URL; ?>">
    <title>AIC CMS | Dashboard</title>
    <link href="template/backend/css/bootstrap.min.css" rel="stylesheet">
    <link href="template/backend/font-awesome/css/font-awesome.css" rel="stylesheet">
    <!-- Toastr style -->
    <link href="template/backend/css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <!-- Gritter -->
    <link href="template/backend/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
    <link href="template/backend/css/animate.css" rel="stylesheet">
	<link href="template/backend/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
	<link href="template/backend/css/plugins/iCheck/custom.css" rel="stylesheet">
	<link href="template/backend/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
	<link href="plugin/jquery-ui.css" rel="stylesheet">
	
	<link rel="stylesheet" type="text/css" href="plugin/blueimp/css/blueimp-gallery.min.css">
<link rel="stylesheet" type="text/css" href="plugin/bootstrap-clockpicker.min.css">


    <link href="template/backend/css/plugins/colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet">
	<link href="plugin/select2/dist/css/select2.min.css" rel="stylesheet" />
	 
    <link href="plugin/rating/SimpleStarRating.css" rel="stylesheet">
    <link href="template/backend/css/animate.css" rel="stylesheet">
    <link href="template/backend/css/style.css" rel="stylesheet">
	<link rel="shortcut icon" href="template/favicon.png" type="image/x-icon">
    <link href="template/backend/css/customize.css" rel="stylesheet">



	<script src="plugin/jquery-3.3.1.min.js"></script>
	<script src="plugin/jquery.timeago.js"></script>
	<script src="plugin/ckeditor/ckeditor.js" charset="utf-8"></script>

	<?php if(isset($isMultiDatesPicker) && $isMultiDatesPicker == TRUE ){ ?>
		<link href="plugin/multidatepicker/jquery-ui.multidatespicker.css" rel="stylesheet"></link>
	<?php } ?>
	<?php if(isset($isFullCalendar) && $isFullCalendar == TRUE ){ ?>
		<link href='plugin/fullcalendar-4.2.0/packages/core/main.css' rel='stylesheet' />
		<link href='plugin/fullcalendar-4.2.0/packages/daygrid/main.css' rel='stylesheet' />
		<link href='plugin/fullcalendar-4.2.0/packages/timegrid/main.css' rel='stylesheet' />
		<link href='plugin/fullcalendar-4.2.0/packages/list/main.css' rel='stylesheet' />
		<script src='plugin/fullcalendar-4.2.0/packages/core/locales/vi.js'></script>
		<script src='plugin/fullcalendar-4.2.0/packages/core/main.js'></script>
		<script src='plugin/fullcalendar-4.2.0/packages/interaction/main.js'></script>
		<script src='plugin/fullcalendar-4.2.0/packages/daygrid/main.js'></script>
		<script src='plugin/fullcalendar-4.2.0/packages/timegrid/main.js'></script>
		<script src='plugin/fullcalendar-4.2.0/packages/list/main.js'></script>
	<?php } ?>
 	<script>
		var BASE_URL = '<?php echo BASE_URL; ?>';
	</script>
</head>

<body>
    <div id="wrapper">
    	<?php 
    		if(!isset($onlyContent)){
				$this->load->view('dashboard/backend/common/sidebar');
			}
		?>
		<?php $this->load->view((isset($template)) ? $template : ''); ?>
        <?php 
    		if(!isset($onlyContent)){
				$this->load->view('dashboard/backend/common/right-sidebar');
			}
		?>
       
    </div>
	





     <!-- GENERAL SCRIPT -->
	<script src="template/backend/js/plugins/iCheck/icheck.min.js"></script>
	<script>
		$(document).ready(function () {
			$('.i-checks').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green',
			});
		});
	</script>
	<script src="plugin/rating/SimpleStarRating.js"></script>
    <script src="template/backend/js/bootstrap.min.js"></script>
    <script src="template/backend/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="template/backend/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="template/backend/js/plugins/nestable/jquery.nestable.js"></script>
    <script src="template/backend/js/inspinia.js"></script>
    <script src="template/backend/js/plugins/pace/pace.min.js"></script>
	<script src="template/backend/js/plugins/toastr/toastr.min.js"></script>
	<script src="template/backend/js/plugins/sweetalert/sweetalert.min.js"></script>
	<script src="template/backend/js/plugins/tinycon/tinycon.min.js"></script>
	<script src="plugin/select2/dist/js/select2.min.js"></script>
	<?php if(isset($isMultiDatesPicker) && $isMultiDatesPicker == TRUE ){ ?>
		<script src="plugin/multidatepicker/jquery-ui.multidatespicker.js"></script>
	<?php } ?>
	<script src="template/backend/js/plugins/datapicker/bootstrap-datepicker.js"></script>

	
	<script src="template/backend/library/editor.js"></script>
	<script src="plugin/jquery-ui.js"></script>
	<script src="plugin/moment.min.js"></script>
<script src="plugin/clockpicker.js"></script>

<?php $this->load->view('dashboard/backend/common/notification'); ?>
	
	
	 <!-- jquery UI -->
    <script src="template/backend/js/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="template/backend/js/plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>
	<?php if(isset($isCheckBox) && $isCheckBox == TRUE ){ ?>
		<script src="template/backend/js/plugins/iCheck/icheck.min.js"></script>
	<?php } ?>
	
	<?php if(isset($load_extend_script) && $load_extend_script == true ){ ?>
    <script src="template/backend/js/plugins/flot/jquery.flot.js"></script>
    <script src="template/backend/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="template/backend/js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="template/backend/js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="template/backend/js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="template/backend/js/plugins/peity/jquery.peity.min.js"></script>
    <script src="template/backend/js/demo/peity-demo.js"></script>
    <script src="template/backend/js/plugins/gritter/jquery.gritter.min.js"></script>
    <script src="template/backend/js/plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="template/backend/js/plugins/chartJs/Chart.min.js"></script>

    <script src="template/backend/js/plugins/flot/jquery.flot.js"></script>
    <script src="template/backend/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="template/backend/js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="template/backend/js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="template/backend/js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="template/backend/js/plugins/flot/jquery.flot.symbol.js"></script>
    <script src="template/backend/js/plugins/flot/jquery.flot.time.js"></script>
    <script src="template/backend/js/plugins/easypiechart/jquery.easypiechart.js"></script>
	<?php } ?>
	<script type="text/javascript" src="plugin/blueimp/jquery.blueimp-gallery.min.js"></script>



	<script src="template/backend/library/function.js"></script>
	<?php if(isset($script)){ ?>
    	<script src="template/backend/library/<?php echo $script ?>.js"></script>
	<?php } ?>


	<?php (DEBUG == 1)?$this->output->enable_profiler(TRUE):'';?>
	<!-- Khối hiển thị lightbox -->
	<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" style="display: none;">
		<div class="slides" style="width: 98352px;"></div>
		<h3 class="title"></h3>
		<a class="prev">‹</a>
		<a class="next">›</a>
		<a class="close">×</a>
		<a class="play-pause"></a>
		<ol class="indicator"></ol>
	</div>

</body>
</html>
