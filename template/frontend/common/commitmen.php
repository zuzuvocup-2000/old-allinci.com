<section class="commitment-section">
	<div class="uk-container uk-container-center">
		<div class="uk-grid uk-grid-medium uk-grid-width-medium-1-3 container">
			<?php for($i=1;$i<=3;$i++) { ?> 
			<div class="item">
				<div class="box order-<?php echo $i; ?>">
					<span class="icon">
						<img src="resources/img/icon-0<?php echo $i; ?>.png" alt="" />
					</span>
					<div class="title"><span>Lorem ipsum dolor smet</span></div>
					<div class="description">
						Consectetur adipiscing elit sed eiusmod tempor incididunt...
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</section><!-- .commitment-section -->