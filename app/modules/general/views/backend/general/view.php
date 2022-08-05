<div id="page-wrapper" class="gray-bg dashbard-1 fix-wrapper">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar'); ?>
	</div>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Cấu hình hệ thống</h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo site_url('admin'); ?>">Home</a>
				</li>
				<li class="active"><strong>Cấu hình hệ thống</strong></li>
			</ol>
		</div>
	</div>
	<form method="post" action="" class="form-horizontal box" >
		<div class="wrapper wrapper-content animated fadeInRight">
			<div class="row">
				<div class="box-body">
					<?php $error = validation_errors(); echo !empty($error)?'<div class="alert alert-danger">'.$error.'</div>':'';?>
				</div><!-- /.box-body -->
			</div>
			<?php if(isset($tab) && is_array($tab) && count($tab)){ ?>
			<?php foreach($tab as $key => $val){ ?>
			<div class="row">
				<div class="col-lg-4">
					<div class="panel-head">
						<h2 class="panel-title"><?php echo $val['label']; ?></h2>
						<div class="panel-description">
							<?php echo $val['description']; ?>
						</div>
					</div>
				</div>
				<div class="col-lg-8">
					<?php if(isset($val['value']) && is_array($val['value']) && count($val['value'])){ ?>
					<div class="ibox m0">
						<div class="ibox-content">
							<?php foreach($val['value'] as $keyItem => $valItem){ ?>
							<div class="row mb15">
								<div class="col-lg-12">
									<div class="form-row">
										<div class="uk-flex uk-flex-middle uk-flex-space-between">
											<label class="control-label text-left">
												<span><?php echo $valItem['label']; ?> <?php echo (isset($valItem['title'])) ? '<a target="_blank" style="font-weight:normal;text-decoration:underline;font-size:12px;font-style:italic;" href="'.$valItem['link'].'" title="">('.$valItem['title'].')</a>' : ''; ?></span>
											</label>
											<?php if(isset($valItem['id'])){ ?>
												<span style="color:#9fafba;"><span id="<?php echo $valItem['id']; ?>"><?php echo strlen(slug($systems[$key.'_'.$keyItem])) ?></span> <?php echo (isset($valItem['extend'])) ? $valItem['extend'] : ''; ?></span>
											<?php } ?>
										</div>
										<?php 
										if($valItem['type'] == 'text'){ 
											echo form_input('config['.$key.'_'.$keyItem.']', htmlspecialchars_decode(html_entity_decode(set_value($key.'_'.$keyItem, isset($systems[$key.'_'.$keyItem]) ? $systems[$key.'_'.$keyItem]: ''))), 'class="form-control '.((isset($valItem['class'])) ? $valItem['class'] : '').'" placeholder=""');
										}
										else if($valItem['type'] == 'textarea'){
											echo form_textarea('config['.$key.'_'.$keyItem.']', (isset($systems[$key.'_'.$keyItem]) ? $systems[$key.'_'.$keyItem]: ''), 'class="form-control '.((isset($valItem['class'])) ? $valItem['class']: '').'" style="height:108px;" placeholder=""');
										}
										else if($valItem['type'] == 'images'){
											echo form_input('config['.$key.'_'.$keyItem.']', set_value($key.'_'.$keyItem, isset($systems[$key.'_'.$keyItem]) ? $systems[$key.'_'.$keyItem]: ''), 'class="form-control" placeholder="" onclick="openKCFinder(this)"');
										}
										else if($valItem['type'] == 'files'){
											echo form_input('config['.$key.'_'.$keyItem.']', set_value($key.'_'.$keyItem, isset($systems[$key.'_'.$keyItem]) ? $systems[$key.'_'.$keyItem]: ''), 'class="form-control" placeholder="" onclick="openKCFinder(this, \'files\')"');
										}
										else if($valItem['type'] == 'media'){
											echo form_input('config['.$key.'_'.$keyItem.']', set_value($key.'_'.$keyItem, isset($systems[$key.'_'.$keyItem]) ? $systems[$key.'_'.$keyItem]: ''), 'class="form-control" placeholder="" onclick="openKCFinder(this, \'media\')"');
										}
										else if($valItem['type'] == 'editor'){
											echo form_textarea('config['.$key.'_'.$keyItem.']', htmlspecialchars_decode(set_value($key.'_'.$keyItem, isset($systems[$key.'_'.$keyItem]) ? $systems[$key.'_'.$keyItem]: '')), 'id="'.$key.'_'.$keyItem.'" class="ck-editor" placeholder="'.$valItem['label'].'" style="height:60px;font-size:14px;line-height:18px;border:1px solid #ddd;padding:10px"');
										}else if($valItem['type'] == 'dropdown'){
											echo form_dropdown('config['.$key.'_'.$keyItem.']', $valItem['value'], set_value($key.'_'.$keyItem, isset($systems[$key.'_'.$keyItem]) ? $systems[$key.'_'.$keyItem]: ''), 'class="form-control" style="width: 100%;"');
										}
									?>
									</div>
								</div>
							</div>
							<?php } ?>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
			<hr>
			<?php }} ?>
			<div class="clearfix">
				<button type="submit" name="save" value="save" class="btn btn-success block m-b pull-right">Lưu thay đổi</button>
			</div>
		</div>
	</form>
	<?php $this->load->view('dashboard/backend/common/footer'); ?>
</div>
