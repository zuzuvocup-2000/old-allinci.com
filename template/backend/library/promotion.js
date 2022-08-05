$(document).ready(function(){
    $( function() {
		$( "#sortable" ).sortable();
		$( "#sortable" ).disableSelection();
	});

    $(document).on('click','.delete-image', function(){
        let _this = $(this);
        _this.parents('li').remove();
        if($('.upload-list li').length <= 0){
            $('.click-to-upload').show();
            $('.upload-list').hide();
        }
        return false;
    });


    $(document).on('change','.publish',function(){
        let _this = $(this);
        let objectid = _this.attr('data-id');
        let formURL = 'promotion/ajax/promotion/status';
            $.post(formURL, {
                objectid: objectid},
                function(data){
                    
                });
    });
});
