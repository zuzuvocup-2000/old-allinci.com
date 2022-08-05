<?php $main_nav = navigation(array('keyword' => 'main', 'output' => 'array')); ?>
<?php if(isset($main_nav) && is_array($main_nav) && count($main_nav)){ ?>
<section class="lower">
	<div class="uk-container uk-container-center">
		<div class="uk-flex uk-flex-middle">
			<nav class="main-nav">
				<ul class="uk-navbar-nav uk-clearfix main-menu">
					<?php foreach($main_nav as $key => $val){ ?>
					<?php 
						$val['link'] = basename($val['link']);
					?>
					<li class="" data-url="<?php echo $val['link']; ?>"><a href="<?php echo $val['link']; ?>" title="<?php echo $val['title']; ?>">
						<span><?php echo $val['title'] ?></span>
					</a>
						<?php if(isset($val['children']) && is_array($val['children']) && count($val['children'])){ ?>
						<div class="dropdown-menu">
							<ul class="uk-list children">
								<?php foreach($val['children'] as $keyItem => $valItem){ ?>
								<li>
									<a href="<?php echo $valItem['link'] ?>" title="<?php echo $valItem['title'] ?>" class="">
										<span><?php echo $valItem['title'] ?></span>
									</a>
								</li>
								<?php } ?>
							</ul>
						</div>
						<?php } ?>
					</li>
					<?php } ?>
				</ul>
			</nav>
		</div>
	</div>
</section>
<?php } ?>


<script>
	var actual_link = $(location).attr('pathname');
	actual_link = actual_link.substr(1);
	
	$(document).on('click', '.open-search', function(event) {
	    event.preventDefault();
	    let _this = $(this);
	  	_this.toggleClass('active');
    	_this.siblings('.dropdown-search').toggleClass('active');

	    return false;
	});

	 /*body click*/
	$('body').click(function(e) {
	  	var target = $(e.target);
	  	if(((!target.is('.hd-menu-search') && $('.hd-menu-search').has(e.target).length === 0)))
	  	{	
		   	if ( $('.open-search').hasClass('active')){
			    $('.open-search').removeClass('active');
					e.preventDefault();
			  	}
			if ( $('.dropdown-search').hasClass('active')){
			    $('.dropdown-search').removeClass('active');
				e.preventDefault();
		  	}
	  	}
		   
	});

	$('.main-menu>li').each(function(){
		let _this = $(this);

		let url = _this.attr('data-url');
		if(actual_link == url){
			_this.addClass('active');
		}
	});
</script>	