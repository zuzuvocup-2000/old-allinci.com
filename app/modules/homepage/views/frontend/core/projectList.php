<?php if(isset($projectList) && is_array($projectList) && count($projectList)){ ?>

	<?php if(isset($style) && $style == 'style-1'){ ?>
		<ul class="uk-grid uk-grid-medium uk-grid-width-1-2 uk-grid-width-medium-1-3 uk-grid-width-large-1-3 _list_project list_project" data-uk-grid-match="{target: '.ht2109_grid_match'}">
			<?php foreach ($projectList as $keyPrj => $valPrj) { ?>
			 	<?php        
			        $title = $valPrj['title'];
			        $href = rewrite_url($valPrj['canonical'], true, false);
			        $image = $valPrj['image'];
			        // $description = $valPrj['description'];
			        $description = cutnchar(strip_tags($valPrj['description']), 250);

			        // $video_iframe = $valPrj['video_iframe'];
                    $created = gettime($valPrj['created'], 'd/m/Y');
			    ?>
		    	
                <li>
                    <div class="uk-clearfix _project project">
                        <div class="thumb">
                        	<a class="image img-cover" href="<?php echo $href ?>" title="<?php echo $title ?>"><img src="<?php echo $image ?>" alt="<?php echo $title; ?>"></a>
                            <h3 class="title"><a class="ht2109_grid_match line-2" href="<?php echo $href; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a></h3>
                        </div>
                        <div class="info">
                            <div class="description line-3">
                            	<?php echo $description ?>
                            </div>
                        </div>
                    </div>
                </li>
			<?php } ?>
		</ul>
	<?php }else{ ?>
		<ul class="uk-grid uk-grid-medium uk-grid-width-1-2 uk-grid-width-medium-1-3 uk-grid-width-large-1-4 _list_project list_project" data-uk-grid-match="{target: '.ht2109_grid_match'}">
			<?php foreach ($projectList as $keyPrj => $valPrj) { ?>
			 	<?php        
			        $title = $valPrj['title'];
			        $href = rewrite_url($valPrj['canonical'], true, false);
			        $image = $valPrj['image'];
			        // $description = $valPrj['description'];
			        $description = cutnchar(strip_tags($valPrj['description']), 250);

			        // $video_iframe = $valPrj['video_iframe'];
                    $created = gettime($valPrj['created'], 'd/m/Y');
			    ?>
		    	
                <li>
                    <div class="uk-clearfix _project project">
                        <div class="thumb">
                        	<a class="image img-cover" href="<?php echo $href ?>" title="<?php echo $title ?>"><img src="<?php echo $image ?>" alt="<?php echo $title; ?>"></a>
                            <h3 class="title"><a class="ht2109_grid_match line-2" href="<?php echo $href; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a></h3>
                        </div>
                        <div class="info">
                            <div class="description line-3">
                            	<?php echo $description ?>
                            </div>
                        </div>
                    </div>
                </li>
			<?php } ?>
		</ul>
	<?php } ?>
<?php } ?>