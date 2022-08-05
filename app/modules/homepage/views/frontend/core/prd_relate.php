<?php if(isset($relaList) && is_array($relaList)  && count($relaList)){ ?>
	<section class="_relate_panel mb-common">
		<header class="panel-head">
			<h2 class="heading-1"><span>Sản phẩm liên quan</span></h2>
		</header>
		<section class="panel-body">
			<?php 
				$data = '';
				$data = array(
					'productList' => $relaList,
					'style' => 'style-2',
				);
			 ?>
			<?php $this->load->view('homepage/frontend/core/productList', $data); ?>
		</section>
	</section>
<?php } ?>
<!-- relalist -->