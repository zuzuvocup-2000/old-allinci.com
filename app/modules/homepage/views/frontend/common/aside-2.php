<aside class="aside uk-height-1-1">
	<div class="aside-panel aside-tool uk-height-1-1">
		<div class="panel-head">
			<h3 class="title">
				<span class="title-content">
					<a href="<?php echo site_url('thanh-toan'); ?>" title="">Giỏ hàng của bạn</a>
				</span>
			</h3>
		</div><!-- panel-head -->
		<div class="panel-body">
			<ul class="uk-list uk-clearfix list-tool">
				<li class="child">
					<div class="child-head">

					</div>
					<div class="child-body">
						<div class="cart-info">
							<div class="uk-flex uk-flex-middle">
								<span class="icon">
									<img src="templates/frontend/resources/img/cart.png" alt="">
								</span>
								<span class="info">
									<div class="total-prodcut">Số sản phẩm : <span id="ajx_qty"><?php echo $this->cart->total_items(); ?></span></div>
									<div class="total-money">Thành tiền : <?php echo number_format($this->cart->total()); ?> VND</div>
								</span>
							</div>
							<a href="<?php echo site_url('thanh-toan'); ?>" title="" class="see-more">
								Xem giỏ hàng
							</a>
						</div>
					</div>
				</li><!-- .child -->

				<li class="child">
					<div class="child-head">
						Nhận email khuyến mại
					</div>
					<div class="child-body">
						<div class="tool">
							<span class="info">
								<form class="uk-form" action="" method="" id="emailForm">
									<label class="uk-form-row">
										<p style="font-size:15px;">Điền email bạn muốn nhận khuyến mại và các bản tin mới nhất</p>
										<input type="text" name="" style="width:100%;" id="email" value="" placeholder="">
									</label>
									<input type="submit" style="font-size:12px;padding:0 25px;border-radius:25px;background:#ed1847;color:#fff;text-transform:capitalize;display:inline-block;" class="uk-button button-main" value="Gửi" />
									<style>
										.button-main{
											font-size: 12px;
											padding: 0 25px;
											border-radius: 25px;
											background: #ed1847;
											color: #fff;
											text-transform: capitalize;
										}
									</style>
								</form>
							</span>
						</div>

					</div>
				</li><!-- .child -->
				<li class="child">
					<div class="child-head">
						Tra cứu đơn hàng
					</div>
					<div class="child-body">
						<div class="tool">
							<span class="info">
								<form class="uk-form" action="<?php echo site_url('quan-ly-don-hang'); ?>" method="get" accept-charset="utf-8">
									<label class="uk-form-row">
										<p style="font-size:15px;">Điện thoại hoặc mã đơn hàng</p>
										<input type="text" style="width:100%;" name="" value="" placeholder="">
									</label>
									<button type="submit" name="submit" class="uk-button button-main">Gửi</button>
								</form>
							</span>
						</div>

					</div>
				</li><!-- .child -->

			</ul>
		</div><!-- panel-body -->
		<?php $banner = slide(array('keyword' => 'banner-parent'));
		 ?>
		 <?php if(isset($banner) && is_array($banner) && count($banner)){ ?>
		<div class="panel-qc" data-uk-sticky="{boundary: true}">
			<ul class="uk-list uk-clearfix list-qc">
				<?php foreach($banner as $key => $val){  ?>
				<li>
					<a href="<?php echo $val['link'] ?>" title="<?php echo $val['link'] ?>" class="image img-cover"><img src="<?php echo $val['src'] ?>" alt="<?php echo $val['link'] ?>"></a>
				</li>
				<?php } ?>
			</ul>
		</div>
		<?php } ?>
	</div><!-- aside-tool -->

</aside><!-- .aside -->
