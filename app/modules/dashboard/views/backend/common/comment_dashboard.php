<div class="ibox float-e-margins">
	<div class="ibox-title">
		<h5>Bình luận và đánh giá</h5>
		<div class="ibox-tools">
			<a class="collapse-link">
				<i class="fa fa-chevron-down"></i>
			</a>
		</div>
	</div>
	<div class="ibox-content" style="position:relative; display: ;">
		<div class="block-comment">
			<?php if(isset($listComment) && is_array($listComment) && count($listComment)){?>
			<ul class="list-comment uk-list uk-clearfix">
					<?php foreach($listComment as $key => $val){?>
						
						<li>
							<div class="comment">
								<div class="uk-flex uk-flex-middle uk-flex-space-between">
									<div class="cmt-profile">
										<div class="uk-flex uk-flex-middle">
											<div class="_cmt-avatar"><img src="template/avatar.png" alt="" class="img-sm"></div>
											<div class="_cmt-name"><?php echo $val['fullname']?></div>
											<div class="label label-primary _cmt-tag">Khách hàng</div>
										</div>
									</div>
									<div class="cmt-time">
										<i class="fa fa-clock-o"></i>
										<time class="timeago meta" datetime="<?php echo ($val['updated'] > $val['created']) ? $val['updated']: $val['created'];?>"></time>
									</div>
								</div>
								<div class="cmt-content">
									<div class="_cmt-reply">
										<div class="uk-flex uk-flex-middle">
											<span class="rating order-1 rt-cmt m-r" data-stars="5" data-default-rating="<?php echo $val['rate'];?>" disabled ></span>
											<span><?php echo $val['comment'];?></span>
										</div>
									</div>
								</div>
							</div>
						</li>
					<?php }?>
				</ul>
				<!-- <div class="loadmore-cmt"><a href="" title="" class="btn-loadmore" data-module="<?php echo $val['module'];?>" data-detailid="<?php echo $val['detailid'];?>" data-start="1" data-limit="2" data-total="<?php echo $statisticalRating['totalComment']?>">Xem thêm</a></div> -->
			<?php }else{?>
				<span>Chưa có bình luận</span>
			<?php }?>
		</div>
	</div>
</div>
<script src="template/backend/library/comment.js"></script>
