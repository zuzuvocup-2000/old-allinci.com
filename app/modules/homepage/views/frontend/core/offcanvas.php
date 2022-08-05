<?php $main_nav = navigation(array('keyword' => 'main', 'output' => 'array')); ?>
<div id="offcanvas" class="uk-offcanvas offcanvas">
	<div class="uk-offcanvas-bar">
        <?php if(isset($main_nav) && is_array($main_nav) && count($main_nav)) {?> 
		<ul class="l1 uk-nav uk-nav-offcanvas uk-nav uk-nav-parent-icon main-menu" data-uk-nav  data-uk-switcher="{connect:'#hp-prd-1',animation: 'uk-animation-fade, uk-animation-slide-left', swiping: true }">
			<?php /* foreach ($main_nav as $key => $val) { ?>
			<li class="l1 <?php echo (isset($val['children']) && is_array($val['children']) && count($val['children']))?'uk-parent uk-position-relative':''; ?>">
				<?php echo (isset($val['children']) && is_array($val['children']) && count($val['children']))?'<a href="#" title="" class="dropicon"></a>':''; ?>
				<a href="<?php echo $val['link']; ?>" title="<?php echo $val['title']; ?>" class="l1"><?php echo $val['title']; ?></a>
				<?php if(isset($val['children']) && is_array($val['children']) && count($val['children'])) { ?>
				<ul class="l2 uk-nav-sub">
					<?php foreach ($val['children'] as $keyItem => $valItem) { ?>
					<li class="l2"><a href="<?php echo $valItem['link']; ?>" title="<?php echo $valItem['title']; ?>" class="l2"><?php echo $valItem['title']; ?></a></li>
					<?php } ?>
				</ul>
				<?php } ?>
			</li>
			<?php } */ ?>

			<?php 
				$menuPrd = layout_control(array(
					'layoutid' => 2,
					'children' => array(
						'flag' => true,
						'post' => true,
						'limit' => 10,
					),
					'post' => array(
						'flag' => false,
						'limit' => 8,
					)
				), false);
		 	?>
    		<?php if(isset($menuPrd['catalogue']) && is_array($menuPrd['catalogue'])  && count($menuPrd['catalogue'])){ ?>
			<?php foreach ($menuPrd['catalogue'] as $keyCat => $valCat) {?>
		 		<?php if(isset($valCat['children']) && is_array($valCat['children'])  && count($valCat['children'])){ $post = 0; ?>
					<?php foreach ($valCat['children'] as $keyChild => $valChild) {?>
						<li class="l1">
							<a href="#menu" title="<?php echo $valChild['title'] ?>" class="va-offcanvas-li scroll-move" data-post= "#post-<?php echo $post?>"><?php echo $valChild['title'] ?></a>
						</li>
	   				<?php $post = $post+1;} ?>
   				<?php } ?>
   			<?php } ?>
   			<?php } ?>
			
		</ul>
		<?php } ?>
	</div>
</div><!-- #offcanvas -->