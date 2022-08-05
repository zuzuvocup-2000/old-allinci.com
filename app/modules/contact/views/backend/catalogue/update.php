<div id="page-wrapper" class="gray-bg dashboard-1 fix-wrapper">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar');?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-12">
			<h2>Hỗ trợ liên hệ</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Chỉnh sửa nhóm liên hệ</strong></li>
			</ol>
		</div>
	</div>
	<!-- --------------------------- -->
	<form method="post" action="" class="form-horizontal box">
		<div class="wrapper wrapper-content animated fadeInRight">
			<div class="row">
				<div class="box-body">
					<?php $error = validation_errors(); echo !empty($error)?'<div class="alert alert-danger">'.$error.'</div>':''; ?>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6">
					<div class="panel-head">
						<h2 class="panel-title">Thông tin chung</h2>
						<div class="panel-description">
							Một số thông tin cơ bản của nhóm liên hệ.
						</div>
					</div>
				</div>

				<div class="col-lg-6">
					<div class="ibox-content">
						<div class="form-row mb10">
							<label class="control-label text-left panel-head-1">
								<span>Tên nhóm liên hệ <b class="text-danger">(*)</b></span>
							</label>
							<?php echo form_input('title', htmlspecialchars_decode(html_entity_decode(set_value('title', $detailCatalogue['title']))), 'class="form-control title" placeholder="" id="title" autocomplete="off"') ;?>
							<input type="hidden" name="canonical" class="canonical" value="<?php echo $detailCatalogue['canonical'] ;?>" data-flag="0">
							<input type="hidden" name="originalKeyword" class="originalKeyword" value="<?php echo $detailCatalogue['canonical'] ;?>" data-flag="0">

						</div>

						<div class="form-row">
							<label class="control-label text-left panel-head-1">
								<span>Quản lí thiết lập hiển thị cho blog này</span>
							</label>
							<div class="setup-display uk-clearfix col-lg-6 pdl0 mgb0">
								<div class="i-checks" style="width: 100%;">
									<span style="color:#000;"> 
										<input type="radio" <?php echo ($detailCatalogue['publish'] == 1) ? 'checked' : ''	;?>  class="popup_gender_1 gender"  value="1"  name="publish">Hiển thị
									</span>
								</div>
							</div>
							<div class="setup-display uk-clearfix col-lg-6 mgb0">
								<div class="i-checks" style="width:100%;">
									<span style="color:#000;"> 
										<input type="radio" <?php echo ($detailCatalogue['publish'] == 0) ? 'checked' : '' ;?>  class="popup_gender_0 gender" required value="0" name="publish">Tắt  
									</span>
								</div>
							</div>
						</div>
						<div class="toolbox action clearfix">
							<div class="uk-flex uk-flex-middle uk-button pull-right">
								<button class="btn btn-success btn-sm" name="update" value="update" type="submit">Lưu</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<!-- --------------------------- -->
	<?php $this->load->view('dashboard/backend/common/footer'); ?>
</div>