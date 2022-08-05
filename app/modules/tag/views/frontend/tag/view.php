<div id="prdtag" class="page-body" >
	<div class="uk-container uk-container-center">
		<div class="uk-grid uk-grid-medium">
			<div class="uk-width-large-1-5">
				<?php $this->load->view('homepage/frontend/common/aside-news'); ?>
			</div>
			<div class="uk-width-large-4-5">
				<section class="tagdetail">
					<div class="main-breadcrumb">
						<ul class="uk-breadcrumb">
							<li><a href="." title="Trang chủ">Trang Chủ</a></li>
							<li><a href="<?php echo $canonical; ?>" title="<?php echo $detailTag['title'] ?>"><?php echo $detailTag['title'] ?></a></li>
						</ul>
					</div>
					<div class="tag-wrapper">
						<div class="tag-description">
							<h1 class="tag-title"><?php echo $detailTag['title']; ?></h1>
							<?php echo $detailTag['description']; ?>
						</div>
						<?php if(isset($objectList) && is_array($objectList) && count($objectList)){ ?>
						<ul class="uk-list uk-clearfix tag-list">
							<?php foreach($objectList as $key => $val){ ?>
							<?php 															
								$title = $val['title'];
								$image = $val['image'];
								$href = rewrite_url($val['canonical'], TRUE, TRUE);
								$description = cutnchar(strip_tags($val['description']),300);	
								$created = gettime($val['created']);
							?>

							<li class="tag-item uk-clearfix">
								<div class="thumb"><a href="<?php echo $href; ?>" title="<?php echo $title; ?>" class="image img-cover"><img src="<?php echo $image; ?>" alt="<?php echo $title; ?>" /></a></div>
								<div class="info">
									<div class="meta"><?php echo $created; ?></div>
									<h3 class="title"><a href="<?php echo $href; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a></h3>
									<div class="description">
										<?php echo $description; ?>
									</div>
									<div class="readmore">
										<a href="<?php echo $href; ?>" class="uk-flex uk-flex-middle" title="<?php echo $title; ?>">
											<span class="mr10">Xem tiếp </span>
											<img src="template/frontend/resources/img/arrow.png" alt="Xem tiếp">
										</a>
									</div>
								</div>
							</li>
							<?php } ?>
						</ul>
						<?php } ?>
						<?php echo (isset($PaginationList)) ? $PaginationList : ''; ?>
					</div>
				</section>
			</div>
		</div>
	</div>
	
</div><!-- #prdtag -->