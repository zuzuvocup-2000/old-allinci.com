$(document).ready(function(){
	var time;


	$('.alert').hide();
	$('.bg-loader').hide();
	$('.upload-list').hide();
	let addGroupSlide = $('#myModal');
	$(document).on('click','.add-group', function(){
		$('#myModal .bg-loader').show();
		let slideTitle = $('#title').val();
		let slideKeyword = $('#keyword').val();
		let formURL = 'general/ajax/slide/add_group';
		
		$.post(formURL, {
				title: slideTitle,keyword:slideKeyword},
				function(data){
					let json = JSON.parse(data);
					if(json.error.flag == 1){
						$('#myModal .alert').html(json.error.message).show(); 
					}else{
						addGroupSlide.modal('hide');
						$('#myModal .alert').hide();
						location.reload();
					}
					$('#myModal .bg-loader').hide();
				});
		return false;
	});
	
	
	$(document).on('click','.delete-slide', function(){
		let _this = $(this);
		_this.parents('.col-md-3').remove();
		if($('.upload-list .row .col-md-3').length <= 0){
			$('.click-to-upload').show();
		}
		return false;
	});
	
	$(document).on('click','.slide-catalogue', function(){
		let _this = $(this);
		let id = _this.attr('data-id');
		let ajax_url = 'general/ajax/slide/slide_catalogue';
		$.get(ajax_url, {
			id: id},
			function(data){
				let json = JSON.parse(data);
				$('#listData').html(json.html);
			});
		return false;
	});
	
	$(document).on('click','.add-slide', function(){
		let img_title = [];
		let img_content = [];
		let img_src = [];
		let img_link = [];
		let img_order = [];
		let slideModal = $('#myModal2');
		slideModal.find('.bg-loader').show();
		$('.image-title').each(function() {
			img_title.push($(this).val());
		});
		$('.image-content').each(function() {
			img_content.push($(this).val());
		});
		$('.image-src').each(function() {
			img_src.push($(this).val());
		});
		$('.image-link').each(function() {
			img_link.push($(this).val());
		});
		$('.image-order').each(function() {
			img_order.push($(this).val());
		});
		let object = {
			'title' : img_title,
			'content' : img_content,
			'src'	: img_src,
			'link'	: img_link,
			'order' : img_order
		};

		
		let catalogueid = $('.catalogueid').val();
		let formURL = 'general/ajax/slide/add_slide';
		$.post(formURL, {
			object: object,catalogueid:catalogueid},
			function(data){
				let json = JSON.parse(data);
				if(json.error.flag == 1){
					slideModal.find('.alert').html(json.error.message).show(); 
				}else{
					location.reload();
				}
				slideModal.find('.bg-loader').hide();
			});
	
		
		
		return false;
	});
	
	$(document).on('click','.edit-slide', function(){
		let _this = $(this);
		let data  = _this.attr('data-json');
		let id = _this.attr('data-id')
		data = window.atob(data);
		let json = JSON.parse(data);
		
		let html = slide_update(json, id);
		
		
		$('.update-group').html(html);
		
		return false;
	});
		
	$(document).on('click','.update-slide', function(){
		let _this = $(this);
		let _form = $('.update-group').serializeArray();
		let formURL = 'general/ajax/slide/update_slide';
		
		swal({
			title: "B???n mu???n c???p nh???t h???ng m???c n??y?",
			text: 'D??? li???u s??? thay ?????i khi b???n th???c hi???n thao t??c n??y. B???m Th???c hi???n ????? ti???p t???c',
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Th???c hi???n!",
			cancelButtonText: "H???y b???!",
			closeOnConfirm: false,
			closeOnCancel: false },
			function (isConfirm) {
				if (isConfirm) {
						$('#myModalEdit').find('.bg-loader').show();
						$.post(formURL, {
							data: _form},
							function(data){
								let json = JSON.parse(data);
								console.log(json);
								if(json.flag == 1){
									sweet_error_alert('C?? v???n ????? x???y ra','Vui l??ng th??? l???i');
								}else if(json.flag == 0){
									$('#slide-'+_form[1].value).find('.img-responsive').attr('src', json.src);
									$('#slide-'+_form[1].value).find('.name').html('<span style="font-weight:bold;">Ch?? th??ch</span>: '+json.title);
									$('#slide-'+_form[1].value).find('.link').html('<span style="font-weight:bold;">Link</span> <i style="color:blue;">'+json.link+'</i>: ');
									
									$('#myModalEdit').modal('hide');

									swal({
										title: "C???p nh???t th??nh c??ng!", 
										text: "D??? li???u ???? ???????c c???p nh???t th??nh c??ng, h??y th???c hi???n ti???p c??c thao t??c kh??c.",
										type: "success"}, function(){
											clearTimeout(time);
											time = setTimeout(function(){
												location.reload();
											}, 500);
										});

									
								}
								
								$('#myModalEdit').find('.bg-loader').hide();
							});
				} else {
					swal("H???y b???", "Thao t??c b??? h???y b???", "error");
				}
			});
		return false;
		
	});
	
});




function slide_render(src = ''){
	let html = '<div class="col-md-3">';
		html = html + '<div class="ibox">';
			html = html + '<div class="ibox-content product-box">';
				html = html  + '<div class="product-imitation">';
					html = html + '<span class="image img-scaledown"><img src="'+src+'" alt="" /></span>';
					html = html + '<input type="text" name="slide[image][]" value="'+src+'" class="image-src" style="display:none;" />';
				html = html + '</div>';
				html = html + '<div class="product-desc">';
					html = html + '<input type="text" name="slide[title][]" value="" class="form-control image-title" style="margin-bottom:10px;" placeholder="Ch?? th??ch ???nh" autocomplete="off">';
					html = html + '<textarea name="slide[content][]" class="form-textarea input-reset image-content" placeholder="N???i dung ???nh" style="width:100%;padding: 5px 10px;height: 80px;"></textarea>';
					html = html + '<input type="text" name="slide[link][]" value="" class="form-control image-link" style="margin-bottom:10px;" placeholder="???????ng d???n" autocomplete="off">';
					html = html + '<div class="uk-flex uk-flex-middle uk-flex-space-between">';
						html = html + '<span class="small-text" style="width:100px;">V??? tr??</span>';
						html = html + '<input type="text" name="slide[order][]" value="0" class="form-control image-order" style="margin-bottom:10px;" placeholder="" autocomplete="off">';
					html = html + '</div>';
					html = html + '<div class="m-t text-righ">';
						html = html + '<a href="#" class="btn btn-xs btn-outline btn-danger delete-slide">X??a <i class="fa fa-ban"></i> </a>';
					html = html + '</div>';
				html = html + '</div>';
			html = html + '</div>'
		html = html + '</div>';
	html = html + '</div>';
	return html;
}


function slide_update(object, id){
	let html = '';
	html = html + '<div class="row">';
		html = html + '<div class="col-lg-6">';
			html = html + '<div class="form-row">';
				html = html + '<small class="text-danger mb5">Click v??o ???nh ????? thay ?????i.</small>';
				html = html + '<div class="avatar slide-image image-scaledown" style="cursor: pointer;"><img src="'+object.src+'" class="img-thumbnail" alt=""></div>';
				html = html + '<input type="hidden" name="src" value="'+object.src+'">';
				html = html + '<input type="hidden" name="id" value="'+id+'">';
			html = html + '</div>';
		html = html + '</div>';
		html = html + '<div class="col-lg-6">';
			html = html + '<div class="form-row">';
				html = html + '<div class="form-group">';
					html = html + '<label>Ch?? th??ch</label> ';
					html = html + '<input type="text" placeholder="" name="title" class="form-control" value="'+object.title+'">';
				html = html + '</div>';
			html = html + '<div class="form-row">';
				html = html + '<div class="form-group">';
					html = html + '<label>N???i dung</label> ';
					html = html + '<textarea name="content" class="form-textarea input-reset image-content" placeholder="N???i dung ???nh" style="width:100%;padding: 5px 10px;height: 80px;">'+object.content+'</textarea>';
				html = html + '</div>';
				html = html + '<div class="form-group">';
					html = html + '<label>???????ng d???n</label>';
					html = html + '<input type="text" placeholder="" name="link" class="form-control" value="'+object.link+'">';
				html = html + '</div>';
				html = html + '<div class="form-group">';
					html = html + '<label>V??? tr??</label> ';
					html = html + '<input type="text" placeholder="" name="order" class="form-control" value="'+object.order+'">';
				html = html + '</div>';
			html = html + '</div>';
		html = html + '</div>';
	html = html + '</div>';
	
	
	return  html;
}
