<div class="block-comments">
	<div class="info-customer">
		<div class="ibox mb20">
			<div class="ibox-header">
				<h5>Đánh giá của Khách Hàng</h5>
			</div>
			<div class="ibox-content" style="position:relative; padding: 20px;">
				<form action="" method="" class="form uk-form" id="form-front-comment">
					<div class="uk-flex uk-flex-middle uk-grid uk-grid-medium mb20">
						<div class="uk-width-large-2-3">
							<section id="comment-front">
								<div class="uk-clearfix uk-flex uk-flex-middle uk-grid uk-grid-medium">
									<div class="uk-width-large-1-2 uk-width-medium-1-2 mb20">
										<div class="uk-flex uk-flex-center">
											<section class="wrap-total">
												<div class="uk-flex uk-flex-middle">
													<div class="number-average">
														<span class="big-number"><?php echo($statisticalRating['averagePoint']);?></span>/
														<span class="small-number">5</span>
													</div>
													<div class="star-average">
														<div class="text-left"><span class="rating" data-stars="5" data-default-rating="<?php echo($statisticalRating['averagePoint']);?>" disabled ></span></div>
														<p><?php echo($statisticalRating['totalComment']);?> đánh giá</p>
													</div>
												</div>
											</section>
										</div>
									</div>
									<div class="uk-width-large-1-2 uk-width-medium-1-2">
										<div class="uk-flex uk-flex-center">
											<section class="comment-statistic">
												<div class="uk-flex uk-flex-middle">
													<div class="wrap-star">
														<?php foreach($statisticalRating['arrayRate'] as $key => $val){?>
															<div class="uk-flex uk-flex-middle">
																<div class="five-star mr20 text-left"><span class="rating order-1" data-stars="5" data-default-rating="<?php echo $key;?>" disabled ></span></div>
																<div class="uk-flex uk-flex-middle">
																	<div class="uk-progress mr20">
																		<div class="uk-progress-bar" style="width: <?php echo ($statisticalRating['totalComment'] > 0)? $val/$statisticalRating['totalComment']*100 : 0;?>%"></div>
																	</div>
																	<div class="total-comment"><?php echo $val;?></div>
																</div>
															</div>
														<?php }?>
													</div>
												</div>
											</section>
										</div>
									</div>
									
								</div>
							</section>

						</div>
						<div class="uk-width-large-1-3">
							<div class="uk-text-center mb5">
								<input type="hidden" class="data-rate" name="data-rate" value="<?php echo ($this->input->post('data-rate'))? (int)$this->input->post('data-rate'): 5;?>">
								<span id="myRating" style="font-size: 25px;" class="rating" data-stars="5" data-default-rating="<?php echo ($this->input->post('data-rate'))? (int)$this->input->post('data-rate'): 5;?>" data-rating="<?php echo ($this->input->post('data-rate'))? (int)$this->input->post('data-rate'): 5;?>"></span>
							</div>
							<div class="title-rating"><?php echo ($this->input->post('data-rate'))? review_render((int)$this->input->post('data-rate')): 'Rất tốt';?></div>
						</div>
					</div> <!-- end .grid-->
					<div class="error hidden">
						<div class="alert alert-danger"></div>
					</div><!-- /.error -->
					<div class="success hidden">
						<div class="alert alert-success"></div>
					</div><!-- /.error -->

					<div class="uk-flex uk-flex-middle uk-flex-space-between mb10">
						<div class="form-row">
							<?php echo form_input('comment_name', htmlspecialchars_decode(html_entity_decode(set_value('comment_name'))), 'placeholder="Họ tên" class="input-text cmt-name" autocomplete="off"');?>
						</div>
					</div>
					<div class="uk-flex uk-flex-space-between mb5">
						<div class="form-row">
							<?php echo form_textarea('comment_note', htmlspecialchars_decode(html_entity_decode(set_value('comment_note'))), 'placeholder="Viết nhận xét" class="textarea cmt-content" autocomplete="off"');?>
						</div>
					</div>
					<div class="uk-flex uk-flex-left">
						<div class="btn-cmt sent-cmt loading">
							<div class="bg-loader"></div>
							<button type="submit" name="sent_comment" value="sent_comment" class="btn btn-success btn-submit" data-module="<?php echo $module;?>" data-detailid="<?php echo $moduleid;?>">Gửi</button>
						</div>
					</div>
					
				</form>
			</div>
		</div>
	</div>
	<div class="ibox">
	<div class="ibox-header">
		<h5>Danh sách đánh giá</h5>
	</div>
	<div class="ibox-content" style="position:relative;padding-right:10px;padding-left:10px;">
		<div class="block-comment">
			<?php if(isset($listComment) && is_array($listComment) && count($listComment)){?>
			<ul class="list-comment uk-list uk-clearfix">
					<?php foreach($listComment as $key => $val){?>
						<li>
							<div class="comment">
								<div class="uk-flex uk-flex-middle uk-flex-space-between">
									<div class="cmt-profile">
										<div class="uk-flex uk-flex-middle">
											<div class="_cmt-avatar"><img src="template/avatar-default.png" alt="" class="img-sm"></div>
											<div class="_cmt-name"><?php echo $val['fullname']?></div>
											<?php //<div class="label label-primary _cmt-tag">Khách hàng</div>?>
										</div>
									</div>
								</div>
								<div class="cmt-content">
									<p><?php echo $val['comment'];?></p>
									<div class="uk-flex uk-flex-middle _cmt-reply">
										<span class="rating order-1 rt-cmt" data-stars="5" data-default-rating="<?php echo $val['rate'];?>" disabled ></span>
										<span style="margin: 0 10px; height: 20px; line-height: 20px;">-</span>
										<div class="cmt-time">
											<i class="far fa-clock"></i>
											<time class="timeago meta" datetime="<?php echo ($val['updated'] > $val['created']) ? $val['updated']: $val['created'];?>"></time>
										</div>
									</div>
									<div class="show-reply">
										<!-- đổ cấu trúc comment vào đây -->
									</div>
									<div class="wrap-list-reply">
										<ul class="list-reply list-comment uk-list uk-clearfix" id="reply-to-<?php echo $val['id'];?>">
											<!-- hiển thị câu trả lời vào đây -->
											<?php if(isset($val['child']) && is_array($val['child']) && count($val['child'])){?>
												<?php foreach($val['child'] as $keyChild => $valChild){?>
													<li>
														<div class="comment">
															<div class="uk-flex uk-flex-middle uk-flex-space-between">
																<div class="cmt-profile">
																	<div class="uk-flex uk-flex-middle">
																		<div class="_cmt-avatar"><img src="template/avatar-admin.png" alt="" class="img-sm"></div>
																		<div class="_cmt-name"><?php echo $valChild['fullname'];?></div>
																		<i>QTV</i>
																	</div>
																</div>
															</div>
															<div class="cmt-content">
																<p><?php echo $valChild['comment'];?></p>
																<?php $albumReply = json_decode($valChild['image']);?>
																<?php if(isset($albumReply) && is_array($albumReply) && count($albumReply)){ ?>
																	<div class="gallery-block mb10">
																		<ul class="uk-list uk-flex uk-flex-middle clearfix lightBoxGallery">
																			<?php foreach($albumReply as $kR => $vR){?>
																				<li>
																					<div class="thumb">
																						<a href="<?php echo $vR;?>" title="" data-gallery="#blueimp-gallery-<?php echo $val['id'].'-'.$valChild['id'];?>"><img src="<?php echo $vR;?>" class="img-md"></a>
																					</div>
																				</li>
																			<?php }?>
																		</ul>
																	</div>
																<?php }?>
																<div class="cmt-time">
																	<i class="fa fa-clock-o"></i>
																	<time class="timeago meta" datetime="<?php echo ($valChild['updated'] > $valChild['created'])? $valChild['updated']:$valChild['created'];?>"></time>
																</div>
															</div>
														</div>
													</li>
												<?php }?>
											<?php }?>
										</ul>
									</div>
								</div>
							</div>
						</li>
					<?php }?>
				</ul>
				<div class="loadmore-cmt"><a href="" title="" class="btn-loadmore" data-module="<?php echo $module;?>" data-detailid="<?php echo $detailid;?>" data-start="1" data-limit="2" data-total="<?php echo $statisticalRating['totalComment']?>">Xem thêm</a></div>
			<?php }else{?>
				<span>Chưa có bình luận</span>
			<?php }?>
		</div>
	</div>
</div>

	
</div><!-- end .comments -->


<style>
.loadmore-cmt .btn-loadmore{
	margin-top:10px;
	color:red;
}
.block-comment .comment ._cmt-name{
	font-weight:bold;
}
.lightBoxGallery {
  text-align: center;
}
.lightBoxGallery img {
  margin: 5px;
}

.gallery-block{
	border: 1px solid #ededed;
	border-radius: 2px;
	overflow-x: auto;
	background: #fff;
}

.lightBoxGallery{
	text-align: left!important;
}


/*WEBKIT-SCROLL*/
.gallery-block::-webkit-scrollbar{
	height: 8px;
}


/* Track */
.gallery-block::-webkit-scrollbar-track {
	background: #f1f1f1; 
}

/* Handle */
.gallery-block::-webkit-scrollbar-thumb {
	background: #999;
	border-radius: 20px;
}

/* Handle on hover */
.gallery-block::-webkit-scrollbar-thumb:hover {
	background: #666; 
}

.lightBoxGallery li > .thumb{
	position: relative;
}
.lightBoxGallery li .thumb .overlay-img{
	display: none;
	position: absolute;
	top:0px;
	height:100%;
	width: 100%;
	background: #2c3e50;
	opacity: 0.6;
}
.lightBoxGallery li > .thumb:hover .overlay-img{
	display: block;
}

.lightBoxGallery li .thumb .delete-img{
	display: none;
	position: absolute;
	top: 10px;
	right: 10px;
	transform: translate(50% , -50%);
	color: #fff;
	font-size: 16px;
	cursor: pointer;
	opacity: 0.3;
}

.lightBoxGallery li .thumb:hover .delete-img{
	display: inline;
}

.lightBoxGallery li .thumb .delete-img:hover{
	opacity: 0.9;
}

.img-md {
    width: 64px;
    height: 64px;
}

/*bình luận và đánh giá*/


/*forntend*/
.block-comments{
	margin-top: 30px;
}

.ibox .ibox-header{}
.ibox .ibox-header h5{
	text-transform: uppercase;
	font-size: 15px;
}

.col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9 {
    float: left;
}

.col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
    padding-left: 10px;
    padding-right: 10px;
}

.col-lg-12 {
	width: 100%;
}
.col-lg-6 {
	width: 50%;
}



/* .ibox { */
  /* clear: both; */
  /* margin-bottom: 25px; */
  /* margin-top: 0; */
  /* padding: 0; */
/* } */
/* .ibox.collapsed .ibox-content { */
  /* display: none; */
/* } */
/* .ibox.collapsed .fa.fa-chevron-up:before { */
  /* content: "\f078"; */
/* } */
/* .ibox.collapsed .fa.fa-chevron-down:before { */
  /* content: "\f077"; */
/* } */
/* .ibox:after, */
/* .ibox:before { */
  /* display: table; */
/* } */

.ibox-title {
  -moz-border-bottom-colors: none;
  -moz-border-left-colors: none;
  -moz-border-right-colors: none;
  -moz-border-top-colors: none;
  background-color: #ffffff;
  border-color: #e7eaec;
  border-image: none;
  border-style: solid solid none;
  border-width: 2px 0 0;
  color: inherit;
  margin-bottom: 0;
  padding: 15px 15px 7px;
  min-height: 48px;
}
.ibox-content {
  background-color: #ffffff;
  color: inherit;
  padding: 20px 0;
  border-color: #e7eaec;
  border-image: none;
  border-style: solid solid none;
  border-width: 1px 0;
  
}

.row {
    margin-right: -15px;
    margin-left: -15px;
}
.rating.order-1{
	font-size: 15px;
}
.number-average {
    width: 90px;
	text-align: center;
}

.number-average .big-number {
    font-size: 40px;
    color: #202020;
}

.number-average .small-number {
    font-size: 20px;
    color: #9d9d9d;
}
.block-comment .list-reply>li:before, .block-comment .list-reply>li:after{
	top:-8px;
}

.star-average{
	padding-left: 7px;
}
.star-average p{
	font-size: 13px;
	margin: 0;
}


.total-comment{
	font-size: 14px;
	color: #333;
}


.uk-progress-bar{
	background: #ffd700;
	border-color: #ffd700;
	box-shadow: none;
}

.uk-progress {
    margin-bottom: 0;
    width: 110px;
    border-radius: 0;
}

.wrap-star>.uk-flex{
	margin-bottom: 5px;
}

/*form comment*/
.block-comments .form .md-label{
	font-size: 14px;
}

.block-comments .form .form-row{
	width: 100%;
}

.block-comments .form .form-row>*{	
	/* border-radius: 5px; */
}
.block-comments .form .form-row .input-text{
	width: 100%;
    height: 30px;
	border: 1px solid #d5d5d5;
    background: #fff;
    color: #333;
	border-radius: 0;
}
.block-comments .form .form-row .uk-form-select{
	width: 100%;
	padding: 6px 10px;
}

.block-comments .form .input-text::-webkit-input-placeholder {
	color: #888 !important;
	font-size: 13px;
}

.block-comments .form .uk-form-select.uk-active{
	border: 1px solid #d5d5d5;
}

.block-comments .form .textarea{
	width: 100%;
	height: 100px;
	padding: 10px;
}
.block-comments .form .btn{
	display: inline-block;
	padding: 8px 25px;
	color: #fff;
	background: #c0392b;
	border-style: none;
	cursor: pointer;
	outline: 0;
	line-height: initial;
}

.rating{
	font-size: 30px;
}



#form-front-comment .title-rating{
	font-size: 14px;
	color: #666;
	letter-spacing: 0.5px;
	text-align: center;
}
#form-front-comment .upload{}
#form-front-comment .upload > *{
	font-size: 14px;
}
#form-front-comment .upload > a{
	color: #012196;
}
#form-front-comment .upload > a:hover{
	text-decoration: underline;
}
#form-front-comment .bg-loader{
	background-size: 20%;
}




/*loading*/
.loading {
    position: relative;
}

.bg-loader {
	display: none;
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 5000;
    background: rgba(120, 120, 120, 0.5) url(template/frontend/resources/img/loading.gif) 50% 50% no-repeat;
    background-size: 5%;
}

/* thông báo */
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
	font-size: 12px;
}

.alert-danger {
    color: #a94442;
    background-color: #f2dede;
    border-color: #ebccd1;
}
.alert-success {
    color: #3c763d;
    background-color: #dff0d8;
    border-color: #d6e9c6;
}

.hidden{
	display: none;
}

@media (min-width: 960px) {
	#form-front-comment>*:first-child{
		-webkit-flex-direction: row-reverse;
		-moz-flex-direction: row-reverse;
		-ms-flex-direction: row-reverse;
		-o-flex-direction: row-reverse;
		flex-direction: row-reverse;
	}

	#comment-front>*{
		-webkit-flex-direction: row-reverse;
		-moz-flex-direction: row-reverse;
		-ms-flex-direction: row-reverse;
		-o-flex-direction: row-reverse;
		flex-direction: row-reverse;
	}

}

@media (max-width: 959px) {
	#comment-front{
		margin-bottom: 20px;
	}
}
@media (max-width: 479px) {
	#comment-front .wrap-total{
		margin-bottom: 20px;
	}
}

</style>

<script>
$(document).ready(function(){
	var time;
	rating();
	/* XÓA RECORD */
	
	$(document).on('click' , '#form-front-comment .sent-cmt .btn-submit', function(){
		//lấy thông tin comment: tên, nội dung
		let _this = $(this);
		
		let formCmt = $('#form-front-comment');
		let cmtName = formCmt.find('.cmt-name').val();
		let cmtContent = formCmt.find('.cmt-content').val();
		let dataRate = formCmt.find('input.data-rate').val();
		let loader = formCmt.find('.bg-loader');
		
		let param = {
			'fullname': cmtName,
			'comment': cmtContent,
			'rate': dataRate,
			'module': _this.attr('data-module'),
			'detailid': _this.attr('data-detailid'),
		};
		
		loader.show();
		
		clearTimeout(time);
		time = setTimeout(function(){
			let ajaxUrl = "comment/frontend/comment/sent_comment";
			$.ajax({
				method: "POST",
				url: ajaxUrl,
				data: {param: param, cmtName: cmtName, cmtContent: cmtContent,},
				dataType: "json",
				cache: false,
				success: function(json){
					loader.hide();
					if(json.error != ''){
						formCmt.find('.error').removeClass('hidden');
						formCmt.find('.alert-danger').html('').html(json.message);
					}else{
						formCmt.find('.error').addClass('hidden');
						formCmt.find('.success').removeClass('hidden');
						
						formCmt.find('.alert-success').html('').html(json.message);
						
						formCmt.find('.cmt-name').val('');
						formCmt.find('.cmt-content').val('');
					}
				}
			});
		} , 300);
		
		return false;
	});
	
	
	
	$(document).on('click' , '#pagination ul>li>a[data-ci-pagination-page]', function(){
		let _this = $(this);
		let page = _this.attr("data-ci-pagination-page");
		let param = {
			'module' : $('.module').val(),
			'keyword' : $('#keyword').val().trim(),
			'perpage' : $('#perpage').val(),
			'page'	  : page,
		};
		clearTimeout(time);
		time = setTimeout(function(){
			get_list_object(param);
		} , 300);
		return false;
	});
	
	//===================album ảnh===================
	//đoạn js này để kéo thả ảnh
	// $( function() {
		// $( "#sortable" ).sortable();
		// $( "#sortable" ).disableSelection();
	// });
	
	if(typeof(layoutid) != 'undefined' && layoutid != ''){
		let type = media_loading(layoutid, 'post');
	}
	
	$(document).on('change','.layout', function(){
		let _this = $(this);
		let catid = _this.val();
		media_loading(catid);
		return false;
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
	
	// ============================== COMMENT VÀ BÌNH LUẬN =====================================
	
	var widthImg = 0; // chiều rộng ảnh cmt
	var textLength = 0; // độ dài nội dung bình luận
	
	$(document).on('click','.btn-reply', function(e){
		let _this = $(this);
		let param = {
			'id' : _this.attr('data-id'),
			'module' : _this.attr('data-module'),
			'detailid' : _this.attr('data-detailid'),
		};
		let reply = get_comment_html(param);
		let replyName = _this.parent().parent().siblings().find('._cmt-name').text();
		let commentAttr = _this.attr('data-comment');
		
		if(commentAttr == 1){
			_this.parent().siblings('.show-reply').html(reply);
			let replyTo = _this.parent().siblings('.show-reply').find('.text-reply').text('@'+ replyName + ' : ');
			replyTo.focus();
			textLength = $.trim(_this.parent().siblings('.show-reply').find('.text-reply').val()).length;
			//ban đầu ta ẩn nút gửi cmt
			_this.parent().siblings('.show-reply').find('.btn-submit').attr('disabled' , '');
			
			_this.attr('data-comment', 0);
			_this.html('Bỏ comment');
		}else{
			_this.parent().siblings('.show-reply').html('');
			_this.attr('data-comment', 1);
			_this.html('Trả lời');
		}
		e.preventDefault();
	});
	
	//hiển thị thời gian ago
	time_ago();

	$(document).on('keyup' , '.text-reply', function(){
		let _this = $(this);
		
		let text = $.trim(_this.val()); //xóa khoảng trắng
		let galleryBlock = _this.closest('.box-reply').find('.gallery-block'); //khối hình ảnh ở phiên hiện tại
		let btnSubmit = _this.closest('.box-reply').find('.btn-submit'); //nút gửi cmt
		
		if(text.length <= 0 && galleryBlock.is(":hidden")){
			// ẩn nút gửi cmt
			btnSubmit.attr('disabled', '');
		}else{
			btnSubmit.removeAttr('disabled');
		}
		
		return false;
	});
	
	// ajax sửa bình luận
	$(document).on('click' , '.edit-cmt .btn-edit' , function(){
		let _this = $(this);
		let liComment = _this.closest('li');
		let dataInfo  = _this.attr('data-info');
		data = window.atob(dataInfo); //decode base64
		let json = JSON.parse(data); // chuyển string về object
		// console.log(json); return false;
		let param = {
			'id' : _this.attr('data-id'),
			'table' : _this.attr('data-table'),
			'parentid' : json.parentid,
			'fullname' : json.fullname,
			'comment' : json.comment,
			'image' : (json.image.length > 0)? JSON.parse(json.image) : json.image,
			'dataInfo' : dataInfo,
		};
		
		let htmlEdit = get_edit_comment_html(param);
		_this.closest('li').html('').html(htmlEdit);
		let textReply = liComment.find('.text-reply');
		textReply.val(textReply.val() + ' ').focus();
		return false;
	});
	
	//ajax cancel cmt
	$(document).on('click' , '.cancel-cmt .btn-cancel' , function(){
		let _this = $(this);
		
		let dataInfo  = _this.attr('data-info');
		data = window.atob(dataInfo); //decode base64
		let json = JSON.parse(data);
		
		let param = {
			'id' : _this.attr('data-id'),
			'table' : _this.attr('data-table'),
			'parentid' : json.parentid,
			'fullname' : json.fullname,
			'comment' : json.comment,
			'image' : (json.image.length)? JSON.parse(json.image) : json.image,
			'dataInfo' : dataInfo,
			'created' : json.created,
			'updated' : (typeof(json.updated) != "undefined")? json.updated : "0000-00-00 00:00:00",
		};
		
		let prevHtml = get_prev_html(param);
		_this.closest('li').html('').html(prevHtml);
		time_ago();
		return false;
	});

	// ajax update cmt
	$(document).on('click' , '.update-cmt .btn-submit:enabled' , function(){
		let _this = $(this);
		
		let comment = _this.closest('.box-reply').find('.text-reply').val();
		let album = []; // list ảnh
		
		_this.closest('.box-reply').find('.album').each(function(){
			album.push($(this).val());
		});
		
		let dataInfo = _this.closest('.comment').find('.btn-cancel').attr('data-info');
		data = window.atob(dataInfo); //decode base64
		let json = JSON.parse(data); // convert chuỗi thành object
		
		let param = {
			'comment' : comment,
			'image' : album,
			'id' : _this.attr('data-id'),
			'parentid' : json.parentid,
			'fullname' : json.fullname,
			'dataInfo' : json,
		};
		
		// console.log(param); return false;
		
		let ajaxUrl = "comment/ajax/comment/update_comment";
		$.ajax({
			method: "POST",
			url: ajaxUrl,
			data: {param: param, comment: param.comment},
			dataType: "json",
			cache: false,
			success: function(json){
				if(json.flagError == 0){
					swal("Cập nhật thành công!", "Bình luận đã được cập nhật.", "success");
					_this.closest('li').html('').html(json.html);
					time_ago();
				}else{
					swal("Cập nhật không thành công!", "Đã có lỗi xảy ra.", "error");
				}
			}
		});
		
		return false;
	});
	
	$(document).on('click' , '.delete-img' , function(){
		let _this = $(this);
		let boxReply = _this.closest('.box-reply'); // hộp thoại
		let listImg = _this.closest('ul.lightBoxGallery'); //album ảnh
		_this.closest('li').remove();
		
		let numImg = listImg.find('li').length; // số lượng ảnh còn lại trong album
		
		listImg.css('width', widthImg*numImg); // tính lại chiều rộng của khối hình ảnh
		
		//ẩn khối hình ảnh khi all ảnh xóa hết
		if(numImg <= 0){
			listImg.parent().hide();
			textLength = $.trim(boxReply.find('.text-reply').val().length);
			//kiểm tra cmt k có text => ẩn nút gửi
			if(textLength > 0){
				boxReply.find('.btn-submit').removeAttr('disabled');
			}else{
				boxReply.find('.btn-submit').attr('disabled', '')
			}
		}
		
		return false;
	});
	
	
	//xem thêm cmt
	$(document).on('click' , '.loadmore-cmt .btn-loadmore' , function(){
		let _this = $(this);
		let limit =  _this.attr('data-limit');
		let start =  parseInt(_this.attr('data-start')) * parseInt(_this.attr('data-limit'));
		let param = {
			'module': _this.attr('data-module'),
			'detailid': _this.attr('data-detailid'),
			'start': start,
			'limit': limit,
		};
		
		let totalComment = _this.attr('data-total'); //tổng số cmt
		let htmlListComment = _this.closest('.block-comment').children('.list-comment');
		
		let ajaxUrl = "comment/frontend/comment/loadmore_comment";
		$.ajax({
			method: "POST",
			url: ajaxUrl,
			data: {param: param, module: param.module, detailid: param.detailid, start: param.start, limit: param.limit},
			dataType: "json",
			cache: false,
			success: function(json){
				//khi load thêm thì cập nhật lại data-start
				if(json.html.length){
					_this.attr('data-start' , parseInt(_this.attr('data-start')) + 1);
					_this.closest('.block-comment').children('.list-comment').append(json.html);
					time_ago();
					rating(start, '.rating.rt-cmt');
				}
				//ẩn nút xem thêm khi lấy hết cmt
				if(totalComment == htmlListComment.children('li').length){
					_this.parent().hide();
				}
				// console.log(htmlListComment.children('li').length);
			}
		});
		
		return false;
	});
	

});


function object_render(param = ''){
	let html = '';
	
	html+='<label class="control-lable text-left"><span>Chi tiết</span></label>';
	html+='<div class="form-row">';
		html+='<select class="selectMultipe" id="detailid" name="detailid" data-title="Nhập ít nhất 2 ký tự để tìm kiếm" data-module="'+param.module+'">';
			html+='<option value="0">'+((param.module == 0)? param.text:'Chọn '+param.text+'')+'</option>';
		html+='</select>';
	html+='</div>';
	
	return html;
}


//hàm lấy dữ liệu trả về từ ajax
function get_list_object(object = ''){
	let ajaxUrl = 'comment/ajax/comment/view';
	$.get(ajaxUrl, {
		param: object, module: object.module, perpage: object.perpage, keyword: object.keyword, page: object.page},
		function(data){
			let json = JSON.parse(data);
			$('#display').html(json.display);
			$('#listComment').html(json.listComment);
			$('#pagination').html(json.paginationList);
			rating();
		}
	);
}

/*############################################ Tú's function JS ####################################*/
function time_ago(){
	if ($('.meta')){
		$("time.timeago").timeago();
	}
}


function thumb_render(src = '' , parentid = 0){
	let html = '';
		
		html += '<li>';
			html += '<div class="thumb">';
				html +='<a href="'+src+'" title="" data-gallery="#blueimp-gallery-'+parentid+'"><img src="'+src+'" class="img-md"></a>';
				html += '<input type = "hidden" class="album" value="'+src+'" name="album[]">';
				html += '<div class="overlay-img"></div>';
				html += '<div class="delete-img"><i class="fa fa-times-circle" aria-hidden="true"></i></div>';
			html += '</div>';
		html += '</li>'
		
	return html;
}

function openKCFinderThumb(button) {
	window.KCFinder = {
		callBackMultiple: function(files) {
			window.KCFinder = null; // reset kcfinder
			let numImage = $(button).closest('.box-reply').find('.lightBoxGallery img').length; // số lượng ảnh đã tồn tại ở lần upload trc
			// console.log(numImage);
			let $galleryBlock = $(button).closest('.box-reply').find('.gallery-block');
			let $lightBoxGallery = $(button).closest('.box-reply').find('.lightBoxGallery');
			let $parentid = $(button).closest('.cmt-content').find('.btn-reply').attr('data-id'); // lấy id của cmt đang đc tương tác
			
			$galleryBlock.show();
			$(button).parent().siblings('.btn-cmt').find('.btn-submit').removeAttr('disabled');
			for (var i = 0; i < files.length; i++){
				$lightBoxGallery.prepend(thumb_render(files[i] , $parentid));
			}
			let imgWidth = $('.lightBoxGallery img').outerWidth(true); // kích thước ảnh tính cả margin
			widthImg = imgWidth; // gán kích thước ảnh ra ngoài sau này còn dùng
			$lightBoxGallery.css('width' , imgWidth*(files.length + numImage));
		}
	};
	window.open(BASE_URL + 'plugin/kcfinder-3.12/browse.php?type=images&dir=images/public', 'kcfinder_image',
        'status=0, toolbar=0, location=0, menubar=0, directories=0, ' +
        'resizable=1, scrollbars=0, width=1080, height=800'
    );
}


// Hàm tính số sao đánh giá
	function rating(start = 0, selector = '.rating', inputForm = 'input.data-rate'){
		var input = $(inputForm);
		var ratings = $(selector);
		for (var i = start; i < ratings.length; i++) {
			var r = new SimpleStarRating(ratings[i]);
			ratings[i].addEventListener('rate', function(e) {
				var numStar = e.detail; // tính số sao
				input.val(numStar);
				get_title_rate(numStar);
			});
		}
	}

	function get_title_rate(numStar = 0){
		// console.log(typeof template); return false;
		let ajaxUrl = 'comment/frontend/comment/get_title_rate';
		$.ajax({
			method: "POST",
			url: ajaxUrl,
			data: {numStar: numStar},
			dataType: 'json',
			success: function(json){
				$('.title-rating').text(json.htmlReview);
			}
		});
	}
	
	
	function get_comment_html(param = ''){
		let comment = '';

		comment += '<div class="box-comment box-reply loading" style="margin-top: 10px;">';
			comment += '<div class="bg-loading"></div>';
			comment += '<form action="" class="form uk-form uk-clearfix">';
				comment += '<textarea name="text-reply" class="form-control text-reply " placeholder="Bạn hãy nhập ít nhất 1 ký tự để bình luận" autocomplete="off"></textarea>';
				comment += '<div class="gallery-block mt10" style="display: none;">';
					comment += '<ul class="uk-list uk-flex uk-flex-middle clearfix lightBoxGallery">';
						// list ảnh sẽ đc đổ ở đây
					comment += '</ul>';
				comment += '</div>';
				comment += '<div class="uk-flex uk-flex-middle uk-flex-space-between mt5">';
					comment += '<div class="upload">';
						comment += '<i class="fa fa-camera"></i> ';
						comment += '<a onclick="openKCFinderThumb(this);return false;" href="" title="" class="upload-picture">Chọn hình</a>';
					comment += '</div>';
					comment += '<div class="btn-cmt sent-cmt"><button type="submit" name="sent_comment" value="sent_comment" class="btn btn-success btn-submit" data-parentid = '+param.id+' data-module = '+param.module+' data-detailid = '+param.detailid+' >Gửi</button></div>';
				comment += '</div>';
			comment += '</form>';
		comment += '</div>';

	  return comment;
	}
	
	function get_edit_comment_html(param = ''){
		let comment = '';
		comment += '<div class="comment">';
			comment += '<div class="uk-flex uk-flex-middle uk-flex-space-between">';
				comment += '<div class="cmt-profile">';
					comment += '<div class="uk-flex uk-flex-middle">';
						comment += '<div class="_cmt-avatar"><img src="template/avatar.png" alt="" class="img-sm"></div>';
						comment += '<div class="_cmt-name">'+param.fullname+'</div>';
						comment += '<i>QTV</i>';
					comment += '</div>';
				comment += '</div>';
				comment += '<div class="uk-flex uk-flex-middle toolbox-cmt">';
					comment += '<div class="cancel-cmt"><a type="button" title="" class="btn-cancel" data-info="'+param.dataInfo+'" data-id="'+param.id+'" data-table="comment" data-closest="li" style="color: #e74c3c;">Hủy bỏ</a></div>';
				comment += '</div>';
			comment += '</div>';
			comment += '<div class="box-comment box-reply loading" style="margin-top: 10px; margin-left: 42px;">';
				comment += '<div class="bg-loading"></div>';
				comment += '<form action="" class="form uk-form uk-clearfix">';
					comment += '<textarea name="text-reply" class="form-control text-reply " placeholder="Bạn hãy nhập ít nhất 1 ký tự để bình luận" autocomplete="off">'+param.comment+'</textarea>';
					comment += '<div class="gallery-block mt10" style="'+((param.image.length > 0) ? '':"display: none")+'">';
						comment += '<ul class="uk-list uk-flex uk-flex-middle clearfix lightBoxGallery">';
							// list ảnh sẽ đc đổ ở đây
							if(param.image.length > 0){
								for(let i = 0; i < param.image.length ; i++){
									comment += thumb_render(param.image[i] , param.parentid);
								}
							}
						comment += '</ul>';
					comment += '</div>';
					comment += '<div class="uk-flex uk-flex-middle uk-flex-space-between mt5">';
						comment += '<div class="upload">';
							comment += '<i class="fa fa-camera"></i> ';
							comment += '<a onclick="openKCFinderThumb(this);return false;" href="" title="" class="upload-picture">Chọn hình</a>';
						comment += '</div>';
						comment += '<div class="btn-cmt update-cmt"><button type="submit" name="update_comment" value="update_comment" class="btn btn-success btn-submit" data-id='+param.id+' data-table = '+param.table+'>Cập nhật</button></div>';
					comment += '</div>';
				comment += '</form>';
			comment += '</div>';
		comment += '</div>';
		

	  return comment;
	}

	function get_prev_html(param = ''){
		$html = '';
			$html += '<div class="comment">';
				$html += '<div class="uk-flex uk-flex-middle uk-flex-space-between">';
					$html += '<div class="cmt-profile">';
						$html += '<div class="uk-flex uk-flex-middle">';
							$html += '<div class="_cmt-avatar"><img src="template/avatar.png" alt="" class="img-sm"></div>';
							$html += '<div class="_cmt-name">'+param.fullname+'</div>';
							$html += '<i>QTV</i>';
						$html += '</div>';
					$html += '</div>';
					$html += '<div class="uk-flex uk-flex-middle">';
						$html += '<div class="edit-cmt"><a type = "button" title="" class="btn-edit" data-info="'+param.dataInfo+'" data-id="'+param.id+'" data-table="comment">Sửa</a></div>';
						$html += '<div class="delete-cmt"><a type="button" title="" class="btn-delete ajax-delete" data-title="Lưu ý: Dữ liệu sẽ không thể khôi phục. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!" data-id = "'+param.id+'" data-table = "comment" data-closest="li" style="color: #e74c3c;">Xóa</a></div>';
					$html += '</div>';
				$html += '</div>';
				$html += '<div class="cmt-content">';
					$html += '<p>'+param.comment+'</p>';
					$html += '<div class="gallery-block mb10" style="'+((param.image.length > 0) ? '':"display: none")+'">';
						$html += '<ul class="uk-list uk-flex uk-flex-middle clearfix lightBoxGallery">';
							// list ảnh sẽ đc đổ ở đây
							if(param.image.length > 0){
								for(let i = 0; i < param.image.length ; i++){
									$html += '<li>';
										$html += '<div class="thumb">';
											$html +='<a href="'+param.image[i]+'" title="" data-gallery="#blueimp-gallery-'+param.parentid+'-'+param.id+'"><img src="'+param.image[i]+'" class="img-md"></a>';
											$html += '<input type = "hidden" class="album" value="'+param.image[i]+'" name="album[]">';
										$html += '</div>';
									$html += '</li>'
								}
							}
						$html += '</ul>';
					$html += '</div>';
					$html += '<i class="fa fa-clock-o"></i> <time class="timeago meta" datetime="'+((param.updated> param.created)? param.updated : param.created)+'"></time>';
				$html += '</div>';
			$html += '</div>';
		
		return $html;
	}
	
/*########################################## end Tú's function JS ####################################*/
	

</script>

