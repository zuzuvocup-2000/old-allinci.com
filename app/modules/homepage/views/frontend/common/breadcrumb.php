<?php //if(isset($breadcrumbStyle) && $breadcrumbStyle == 'expand'){ ?>
<?php if(1){ ?>
    <section class="breadcrumb breadcrumb-expand" style="background: url(<?php echo (!empty($detailCatalogue['image'])) ? $detailCatalogue['image'] : $this->general['homepage_banner'] ?>) no-repeat top; background-size: cover;">

    <!-- <section class=" breadcrumb breadcrumb-<?php echo $breadcrumbStyle; ?>" style="background: url('template/frontend/resources/img/breadcrumb.jpg') no-repeat top; background-size: cover;"> -->
        <div class="uk-container uk-container-center">
            <div class="breadcrumb-content">
                <div class="breadcrumb-maintitle"><?php echo $detailCatalogue['title']; ?></div>
                <?php /*
                <?php if(isset($breadcrumb) && is_array($breadcrumb) && count($breadcrumb)){ ?>
                <div class="breadcrumb-subtitle">
                    <ul class="uk-breadcrumb uk-flex uk-flex-center">
                        <li><a href="." title="Trang chủ">Trang chủ</a></li>
                        <?php foreach($breadcrumb as $key => $val){ ?>
                        <?php
                            $title = $val['title'];
                            $href = rewrite_url($val['canonical'], TRUE, TRUE);
                        ?>
                        <li><a href="<?php echo $href; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
                <?php } ?>
                */ ?>
            </div>
        </div>
    </section><!--- breadcrumb-expand -->
<?php }else{ ?>
    <div class="breadcrumb">
        <div class="uk-container uk-container-center">
            <?php if(isset($breadcrumb) && is_array($breadcrumb) && count($breadcrumb)){ ?>
            <ul class="uk-breadcrumb">
                <li><a href="." title="<?php echo BREADCRUM_HOME ?>"><?php echo BREADCRUM_HOME ?></a></li>
                <?php foreach($breadcrumb as $key => $val){ ?>
                <?php
                    $title = $val['title'];
                    $href = rewrite_url($val['canonical'], TRUE, TRUE);
                ?>
                <li><a href="<?php echo $href; ?>" title="<?php echo $title; ?>"><?php //echo '<i class="fa fa-home"></i>'; ?> <?php echo $title; ?></a></li>
                <?php } ?>
            </ul>
            <?php } ?>
        </div>
    </div><!-- .breadcrumb -->
<?php } ?>

