$(document).ready(function(){
	//===================album ảnh===================
	//đoạn js này để kéo thả ảnh
	$( function() {
		$( "#sortable" ).sortable();
		$( "#sortable" ).disableSelection();
	});

	
	if($('#article_catalogue').length){
		select2($('#article_catalogue'));
	}
	if(typeof catalogueid !='undefined'  ){
		pre_select2('article_catalogue',catalogueid);
	}
	
	
	if($('#tag').length){
		select2($('#tag'));
	}
	
	
	if(typeof tag !='undefined'  ){
		clearTimeout(time);
		time = setTimeout(function(){
			pre_select2('tag',tag)
		},100);
	}


	$(document).on('click','.add-attr',function(){
		let _this = $(this);
		render_attr();
	})

	$(document).on('click','.delete-attr',function(){
		let _this = $(this);
		_this.parents('.desc-more').remove();
		
	});
	function render_attr(){
		let html ='';
		var microtime = (Date.now() % 1000) / 1000;
		var editorId = 'editor_' + microtime;
		html = html + '<div class="col-lg-12 m-b desc-more">'
			html = html + '<div class="row m-b">'
				html = html + '<div class="col-lg-8">'
					html = html + '<input type="text" name="content[title][]" class="form-control" placeholder="Tiêu đề">'
					html = html + '<input type="text"  value='+microtime+' name="content[microtime][]" class="hidden" >'
				html = html + '</div>'
				html = html + '<div class="col-lg-4">'
					html = html + '<div class="uk-flex uk-flex-middle uk-flex-space-between">'
						html = html + '<a href="" title="" data-id_editor="" class="uploadMultiImage" onclick="openKCFinderDescExtend(\'' + editorId + '\');return false;">Upload hình ảnh</a>'
						html = html + '<button class="btn btn-danger delete-attr" type="button"><i class="fa fa-trash"></i></button>'
					html = html + '</div>'
				html = html + '</div>'
			html = html + '</div>'
			html = html + '<div class="row m-b">'
				html = html + '<div class="col-lg-12">'
					html = html + '<input type="text" name="content[image][]" class="form-control" placeholder="Icon/ Ảnh đại diện" onclick="openKCFinder(this)">'
				html = html + '</div>'
			html = html + '</div>'
			html = html + '<div class="row">'
				html = html + '<div class="col-lg-12">'
					html = html + '<textarea name="content[description][]" class="form-control ck-editor" id="'+editorId+'" placeholder="Mô tả"></textarea>'
				html = html + '</div>'
			html = html + '</div>'
		html = html + '</div>';
		$('.attr-more').prepend(html);
		CKEDITOR.replace(editorId, { height: 100 });
		 //text box increment
	}
	
	
	// Cập nhật trạng thái
	
	$(document).on('click','.pagination li a', function(){
		let _this = $(this);
		let page = _this.attr('data-ci-pagination-page');
		let keyword = $('.keyword').val();
		let perpage = $('.perpage').val();
		let catalogueid = $('.catalogueid').val();
		let object = {
			'keyword' : keyword,
			'perpage' : perpage,
			'page'    : page,
			'catalogueid' : catalogueid,
		}
		
		clearTimeout(time);
		if(keyword.length > 2){
			time = setTimeout(function(){
				get_list_object(object);
			},500);
		}else{
			time = setTimeout(function(){
				get_list_object(object);
			},500);
		}
		return false;
	});
	
	$(document).on('change','.ishome',function(){
		let _this = $(this);
		let objectid = _this.attr('data-id');
		let formURL = 'article/ajax/article/ishome';
			$.post(formURL, {
				objectid: objectid},
				function(data){
					
				});
	});
	
	$(document).on('change','.highlight',function(){
		let _this = $(this);
		let objectid = _this.attr('data-id');
		let formURL = 'article/ajax/article/highlight';
			$.post(formURL, {
				objectid: objectid},
				function(data){
					
				});
	});
	
	$(document).on('change','.publish',function(){
		let _this = $(this);
		let objectid = _this.attr('data-id');
		let formURL = 'article/ajax/article/status';
			$.post(formURL, {
				objectid: objectid},
				function(data){
					
				});
	});
	var time;
	$(document).on('keyup change','.filter', function(){
		let keyword = $('.keyword').val();
		let perpage = $('.perpage').val();
		let catalogueid = $('.catalogueid').val();
		let object = {
			'keyword' : keyword,
			'perpage' : perpage,
			'catalogueid' : catalogueid,
			'page'    : 1,
		}
		keyword = keyword.trim();
		clearTimeout(time);
		if(keyword.length > 2){
			time = setTimeout(function(){
				get_list_object(object);
			},500);
		}else{
			time = setTimeout(function(){
				get_list_object(object);
			},500);
		}
	});
	
	
	
	
	
});

function get_list_object(param){
	let ajaxUrl = 'article/ajax/article/listArticle';
	$.get(ajaxUrl, {
		perpage: param.perpage, keyword: param.keyword, page: param.page, catalogueid: param.catalogueid},
		function(data){
			let json = JSON.parse(data);
			$('#ajax-content').html(json.html);
			$('#pagination').html(json.pagination);
			$('#total_row').html(json.total_row);
		});
}