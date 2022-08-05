<div id="search_page" class="page-body _page_body">
    <section class="breadcrumb breadcrumb-expand" style="background: url('template/frontend/resources/img/breadcrumb.jpg') no-repeat top; background-size: cover;">
		<div class="uk-container uk-container-center">
            <div class="breadcrumb-content">
                <div class="breadcrumb-maintitle">Tìm kiếm</div>
            </div>
        </div>
    </section>
	<div class="uk-container uk-container-center">
		<section class="tagdetail">
			<div class="tag-wrapper">
				<div class="tag-description">
					<h1 class="tag-title">Từ khóa tìm kiếm: <?php echo $this->input->get('keyword'); ?></h1>
				</div>
				<?php if(isset($objectList) && is_array($objectList) && count($objectList)){ ?>
					<?php 
						$data = '';
						$data = array(
							'productList' => $objectList,
							'style' => 'style-1',
						);
					 ?>
					 <?php $this->load->view('homepage/frontend/core/productList', $data, FALSE); ?>
					<?php echo (isset($PaginationList)) ? $PaginationList : ''; ?>
				<?php } ?>
			</div>
		</section>
	</div>
	
</div><!-- #prdtag -->