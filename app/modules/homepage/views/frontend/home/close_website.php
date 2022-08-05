<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo base_url();?></title>
    <base href="<?php echo base_url();?>"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>
    <div id="close-page">
        <div class="uk-container uk-container-center">
            <div class="main-title">Website đang bảo trì</div>
            <div class="image">
                <img src="template/acore/image/close-site.jpg" alt="">
            </div>
            <div class="sub-title">Xin vui lòng quay lại sau !</div>
        </div><!-- .uk-container -->
    </div>

    <style>
        #close-page{
            padding-top: 10%;
            background: #fff;
            text-align: center;
        }
        #close-page .main-title{
            font-size: 20px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        #close-page img{
            max-width: 100%;
        }

        @media (min-width: 1024px) {
            #close-page .main-title{
                font-size: 26px;
            }
            #close-page{
                padding-top: 65px;
            }
        }
    </style>
</body>
</html>

