<script>
	var time = 3*60*1000;

	$(window).load(function(){
		 var date = new Date();
		 var minutes = 15;
		 date.setTime(date.getTime() + (minutes * 60 * 1000));
		// set cookie
    	if (typeof $.cookie('popup') === 'object'){
    		setTimeout(function(){
    			show_modal(); 
    		}, 3000);
		 	$.cookie('popup', '1', { expires: date, path: '/' });
		} else {
			circle_time(time);
		}

		$('#popup_01').on({
	        'show.uk.modal': function(){

	        },
	        'hide.uk.modal': function(){
	        	// $('#popup_01').removeClass('active');
	        	circle_time(time);
	        }
	    });
	    function circle_time(time){
			setTimeout(function(){
				show_modal();
			}, time);
		}

		function show_modal(){
			var modal = UIkit.modal("#popup_01");
			modal.show();
		}
	});
</script>

<div id="popup_01" class="uk-modal">
	<form action="" method="post" class="uk-form form loading" id="">
		<div class="uk-modal-dialog" style="padding: 0; width: 650px; margin: 50px auto auto auto;">
			<a class="uk-modal-close uk-close">
				<i class="fa fa-times"></i>
			</a>
			<div class="modal-content dt_modal-content">
				<div class="image"><img src="<?php echo $this->general['homepage_popup'] ?>" alt=""></div>
			</div>
		</div>
	</form>
</div>

<style>
	.uk-modal-header {
	    margin: 0 !important
	}
	.uk-modal-close.uk-close i{
	    font-style: normal !important;
	}
	.uk-modal-close.uk-close {
	    content: '\f00d';
	    display: block;
	    position: absolute;
	    top: 25px;
	    right: 25px;
	    transform: translate(0 , 0);
	    font-family: FontAwesome;
	    color: #363636;
	    font-size: 16px;
	    transition: all 0.3s ease-in-out;
	    font-style: normal !important;
	    background: transparent url('') !important;
	}

	.uk-modal-dialog>.uk-close:first-child {
	    margin: 0 !important;
	    float: right;
	    color: #898989;
	    line-height: 32px;
	    top: 0;
	    right: 0;
	}

	.dt_modal-heading{}
	.dt_modal-content {
	    /*padding: 20px;*/
	}
	@media (max-width: 767px){
		.uk-modal-dialog {
		    width: auto;
		    margin: 10px auto !important;
		}
	}
</style>