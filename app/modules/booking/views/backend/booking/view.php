<div id="page-wrapper" class="gray-bg dashbard-1">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Quản lý lịch</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Quản lý lịch</strong></li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
	                <div class="ibox-title">
	                    <h5>Bảng quản lí lịch theo tuần </h5>
	                    <div class="ibox-tools">
	                        <a class="collapse-link">
	                            <i class="fa fa-chevron-up"></i>
	                        </a>
	                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
	                            <i class="fa fa-wrench"></i>
	                        </a>
	                        <ul class="dropdown-menu dropdown-user">
	                        </ul>
	                        <a class="close-link">
	                            <i class="fa fa-times"></i>
	                        </a>
	                    </div>
	                </div>
	                <div class="ibox-content">
	                			<div class="uk-flex uk-flex-middle uk-flex-space-between">
	                				<a href="<?php echo site_url('booking/backend/catalogue/view') ?>" type="button" class="btn btn-info btn-lg">
										<i class="fa fa-list-ul" aria-hidden="true"></i>
	                					 Danh sách lịch
	                				</a>
	                			</div>
	                    <div id="calendar">
	                    	
	                    </div>
	                </div>
	            </div>
			</div>
		</div>
		<?php $this->load->view('dashboard/backend/common/footer'); ?>
	</div>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
	    var calendarEl = document.getElementById('calendar');

	    var calendar = new FullCalendar.Calendar(calendarEl, {
	    	locale: 'vi',
	    	plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],
	    	header: {
	    		left: 'prev,next today',
	    		center: 'title',
	    		right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
	    	},
	    	defaultDate: '<?php echo gettime($this->currentTime, 'Y-m-d') ?>',
		    navLinks: true, // can click day/week names to navigate views
		    editable: false,
	        droppable: false,
	        // height:10,
		    events: [
			   	<?php echo $temp ?>
	      	]
	    });

	    calendar.render();
	});

	$(document).on('click','.fc-next-button, .fc-prev-button', function(){
  		month = 7;
  		year = 2019;
  		// lấy dữ liệu tháng hiện tại
  		console.log(month);
  		console.log(year);
		let ajaxUrl = 'booking/ajax/booking/get_data_month';
		$.post(ajaxUrl, {
			month: month, year: year},
			function(data){
				console.log(data);
				let events1 = '['+data+']';
				var calendarEl = document.getElementById('calendar');
				var calendar = new FullCalendar.Calendar(calendarEl, {
  					events: events1
				});
			});
  		return false;
	});
</script>
<!-- 
<script>
    $(document).ready(function() {
        /* initialize the external events
         -----------------------------------------------------------------*/
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        $('#external-events div.external-event').each(function() {

            // store data so the calendar knows to render an event upon drop
            $(this).data('event', {
                title: $.trim($(this).text()), // use the element's text as the event title
                stick: true // maintain when user navigates (see docs on the renderEvent method)
            });

            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 1111999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });

        });
        /* initialize the calendar
         -----------------------------------------------------------------*/
        
        $('#calendar').fullCalendar({
            
            defaultView: 'agenda',
			visibleRange: {
			    start: '2019-03-22',
			    end: '2019-11-25'
			},
            views: {
			  week: {
			    titleFormat: '[Từ ngày] D MMMM YYYY',
			    titleRangeSeparator: ' đến ngày ',
			  }
			},
            editable: false,
            droppable: false, // this allows things to be dropped onto the calendar
            drop: function() {
                // is the "remove after drop" checkbox checked?
                if ($('#drop-remove').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    $(this).remove();
                }
            },
            events: [
	            <?php echo isset($temp) ? $temp : '' ?>
            ]
        });
    });
</script> -->