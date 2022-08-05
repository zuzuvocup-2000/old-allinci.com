<div class="block-comments">
	<div class="info-customer">
		<div class="ibox mb20">
			<div class="ibox-header">
				<h5>Form đánh giá</h5>
			</div>
			<div class="ibox-content" style="position:relative; padding: 20px 0;">
				<form action="" method="" class="form uk-form" id="form-front-comment">
					<div class="uk-flex uk-flex-middle uk-grid uk-grid-medium mb10">
						<div class="uk-width-large-1-3">
							<div class="uk-text-center mb5">
								<input type="hidden" class="data-rate" name="data-rate" value="<?php echo ($this->input->post('data-rate'))? (int)$this->input->post('data-rate'): 5;?>">
								<span id="myRating" style="font-size: 25px;" class="rating" data-stars="5" data-default-rating="<?php echo ($this->input->post('data-rate'))? (int)$this->input->post('data-rate'): 5;?>" data-rating="<?php echo ($this->input->post('data-rate'))? (int)$this->input->post('data-rate'): 5;?>"></span>
							</div>
							<div class="title-rating"><?php echo ($this->input->post('data-rate'))? review_render((int)$this->input->post('data-rate')): 'Rất tốt';?></div>
						</div>
						<div class="uk-width-large-2-3">
							<?php $this->load->view('homepage/frontend/common/statistical-rating-front');?>
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
							<button type="submit" name="sent_comment" value="sent_comment" class="btn btn-success btn-submit" data-module="<?php echo $module;?>" data-detailid="<?php echo $productDetail['id'];?>">Gửi</button>
						</div>
					</div>
					
				</form>
			</div>
		</div>
	</div>
	<?php $this->load->view('homepage/frontend/common/comment-front');?>
	
</div><!-- end .comments -->


<style>
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
  background: #fafafa;
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
	margin: 0;
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

.star-average{
	width: calc(100% - 90px);
	padding-left: 10px;
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
    background: rgba(120, 120, 120, 0.5) url(img/loading.gif) 50% 50% no-repeat;
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
});
	

</script>

