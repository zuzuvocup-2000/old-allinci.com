<div class="ibox mb20">
	<div class="ibox-title">
		<h5>Thống kê</h5>
		<div class="ibox-tools">
			<a class="collapse-link">
				<i class="fa fa-chevron-up"></i>
			</a>
		</div>
	</div>
	<div class="ibox-content" style="position:relative;">
		<div class="row">
			<div class="col-lg-12 mb20">
				<div class="uk-flex uk-flex-center">
					<section class="wrap-total">
						<div class="uk-flex uk-flex-middle">
							<div class="number-average">
								<span class="big-number"><?php echo($statisticalRating['averagePoint']);?></span>/
								<span class="small-number">5</span>
							</div>
							<div class="star-average">
								<div class="text-left"><span class="rating" data-stars="5" data-default-rating="<?php echo($statisticalRating['averagePoint']);?>" disabled ></span></div>
								<p><?php echo($statisticalRating['totalComment']);?> đánh giá</p>
							</div>
						</div>
					</section>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="uk-flex uk-flex-center">
					<section class="comment-statistic">
						<div class="uk-flex uk-flex-middle">
							<div class="wrap-star">
								<?php foreach($statisticalRating['arrayRate'] as $key => $val){?>
									<div class="uk-flex uk-flex-middle">
										<div class="five-star mr20 text-left"><span class="rating order-1" data-stars="5" data-default-rating="<?php echo $key;?>" disabled ></span></div>
										<div class="uk-flex uk-flex-middle">
											<div class="uk-progress mr20">
												<div class="uk-progress-bar" style="width: <?php echo ($statisticalRating['totalComment'] > 0)? $val/$statisticalRating['totalComment']*100 : 0;?>%"></div>
											</div>
											<div class="total-comment"><?php echo $val;?></div>
										</div>
									</div>
								<?php }?>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
	</div>
</div>
