<?php if(isset($relatedArticle) && is_array($relatedArticle) && count($relatedArticle)){ ?>
<div class="prj-relate style-1 mt30 _relate_panel">
	<div class="panel-head">
		<div class="heading-1"><span>Bài viết liên quan</span></div>
	</div>
	<div class="panel-body">
		<?php 
			$data = '';
			$data = array(
				'articleList' => $relatedArticle,
				'style' => 'style-1',
			);
		 ?>
		<?php $this->load->view('homepage/frontend/core/articleList', $data); ?>

	</div>
	
</div>
<?php } ?>
