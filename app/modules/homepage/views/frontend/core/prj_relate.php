<?php if(isset($relatedproject) && is_array($relatedproject) && count($relatedproject)){ ?>
<div class="prj-relate style-1 mt30 _relate_panel">
	<div class="panel-head">
		<div class="heading-1"><span>Dự án liên quan</span></div>
	</div>
	<div class="panel-body">
		<?php 
			$data = '';
			$data = array(
				'projectList' => $relatedproject,
				'style' => 'style-1'
			);
		 ?>
		<?php $this->load->view('homepage/frontend/core/projectList', $data); ?>

	</div>
	
</div>
<?php } ?>
