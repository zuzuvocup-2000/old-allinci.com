<div id="page-wrapper" class="gray-bg dashboard-1 fix-wrapper">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar');?>
	</div>

	<!-- --------------------------- -->
	<form method="post" action="" class="form-horizontal box">
		<div class="wrapper wrapper-content animated fadeInRight">
			<div class="row">
				<div class="">
					<?php $error = validation_errors(); echo !empty($error)?'<div class="alert alert-danger">'.$error.'</div>':''; ?>
				</div>
			</div>
			<div class="row">

				<div class=" animated fadeInRight">
					<div class="mail-box-header">
						<div class="pull-right tooltip-demo">
							<a href="mailbox.html" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Move to draft folder"><i class="fa fa-pencil"></i> Draft</a>
							<a href="mailbox.html" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Discard email"><i class="fa fa-times"></i> Discard</a>
						</div>
						<h2>Phản hồi yêu cầu khách hàng</h2>
					</div>
					<div class="mail-box">
						<div class="mail-body">
							<form class="form-horizontal" method="get">
								<div class="form-group p-form-group"><label class="control-label panel-head-1">To:</label>
									<div class="">
										<!-- <input type="text" class="form-control" value=""> -->
										<input class="form-control" name="to" value="<?php echo set_value('to', ((isset($createMail['email'])) ? $createMail['email'] : '')) ?>" placeholder="">
									</div>
								</div>
								<div class="form-group p-form-group mgb15"><label class="control-label panel-head-1">Subject:</label>
									<div class="">
										<!-- <input type="text" class="form-control" value=""> -->
										<input class="form-control" name="subject" value="<?php echo set_value('subject') ?>" placeholder="">
									</div>
								</div>
								<div class="form-group p-form-group">
									<div class="mb15">
										<?php echo form_textarea('message', htmlspecialchars_decode(html_entity_decode(set_value('message'))), 'class="form-control ck-editor" id="ckDescription" placeholder="" autocomplete="off"');?>

										<!-- 	 <?php //echo form_textarea('message', htmlspecialchars_decode(set_value('message')), 'id="txtDescription" class="ckeditor-description" placeholder="Mô tả" style="width: 100%; height: 150px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"');?> -->
									</div>
								</div>
								<div class="form-group  p-form-group">
									<div class="btn btn-default col-lg-6">
										<input type="file" name="file[]" id="file" multiple class="file mgb0">
									</div>
									<div class="col-sm-3" style="display:none;">
										<a href="#" id="upload-btn" onclick="return false;" class="btn btn-success fileinput-exists btn-block margin-bottom"><i class="glyphicon glyphicon-open"></i> Upload</a>
									</div>
								</div>
								<table class="table table-hover table-striped mgb0">
									<tbody class="list-group-file">
									</tbody>
								</table>
								<div class="form-group mgb0  p-form-group">
									<div class="col-sm-6 pdl0 pdr0">
										<div class="progress" style="display:none;">
											<div id="progress-bar" class="progress-bar progress-bar-success progress-bar-striped " role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width: 30%;">20%
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
						<div class="mail-body text-right tooltip-demo">
							<a href="mailbox.html" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Discard email"><i class="fa fa-times"></i> Discard</a>
							<a href="mailbox.html" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Move to draft folder"><i class="fa fa-pencil"></i> Draft</a>
							<!-- <a href="" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Send" type="submit"><i class="fa fa-reply"></i> Send</a> -->
							<button type="submit" class="btn btn-primary" value="create" name="create" data-toggle="tooltip" data-placement="top" title="Send"><i class="fa fa-reply"></i> Send</button>
							<!-- <button class="btn btn-primary btn-sm contact-form-create" name="create" value="create" type="submit">Tạo mới</button> -->

						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<!-- </div> -->
			</div>
		</form>
		<?php $this->load->view('dashboard/backend/common/footer'); ?>
	</div>


	<script type="text/javascript">
		$(function(){
			var inputFile = $('input#file');
			var uploadURI = '<?php echo site_url('contacts/backend/contact/upload'); ?>';
			var progressBar = $('#progress-bar');
			listFilesOnServer();
			$(document).on('change','input#file',function(){
				$('#upload-btn').trigger('click');
			});
			$('#upload-btn').on('click',function(event){
				var filesToUpload = inputFile[0].files;
				if(filesToUpload.length > 0){
					var formData = new FormData();
					for(var i = 0; i < filesToUpload.length; i++){
						var file = filesToUpload[i];
						formData.append('file[]',file, file.name);
					}
					$.ajax({
						url : uploadURI,
						type: 'post',
						data: formData,
						processData: false,
						contentType: false,
						success: function(){
							listFilesOnServer();
						},
						xhr:function(){
							var xhr = new XMLHttpRequest();
							xhr.upload.addEventListener('progress',function(event) {
								if(event.lengthComputable){
									var percentCompele = Math.round((event.loaded / event.total)*100);
									$('.progress').show();
									progressBar.css({width:percentCompele + '%'});
									progressBar.text(percentCompele + '%');
								}
							}, false);
							return xhr;
						}
					});
				}
				return false;
			});
			$(document).on('click','.remove-file',function(){
				var me = $(this);
				$.ajax({
					url: uploadURI,
					type: 'post',
					data: {file_to_remove: me.attr('data-file')},
					success: function(){
						me.closest('tr').remove();
					}
				});
				return false;
			});
			function listFilesOnServer(){
				var items = [];
				$.getJSON(uploadURI,function(data){
					$.each(data,function(index, element){
						items.push('<tr><td class="mailbox-star" style="width:30px;"><a onclick="return false;" href="#" data-file="' + element + '" class="remove-file"><i class="fa fa-trash text-red"></i></a></td><td class="mailbox-name"><a  onclick="return false;" style="color:#333;" href="read-mail.html">' + element  + '</a></td></tr>');
					});
					console.log(items);
					$('.list-group-file').html('').html(items.join(''));
				});
			}
		});
	</script>