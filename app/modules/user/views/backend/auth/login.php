<!DOCTYPE html>
<html>

<head>
	<base href="<?php echo BASE_URL; ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AIC CMS | Đăng nhập</title>

	<link href="template/backend/css/bootstrap.min.css" rel="stylesheet">
	<link href="template/backend/font-awesome/css/font-awesome.css" rel="stylesheet">
	<link href="template/backend/css/plugins/toastr/toastr.min.css" rel="stylesheet">
	<link href="template/backend/css/animate.css" rel="stylesheet">
	<link href="template/backend/css/style.css" rel="stylesheet">
	<link href="template/backend/css/customize.css" rel="stylesheet">
	<script src="plugin/jquery-3.3.1.min.js"></script>
	<script>
		var BASE_URL = '<?php echo BASE_URL; ?>';
	</script>
</head>

<body class="gray-bg">

    <div class="loginColumns animated fadeInDown">
        <div class="row">

            <div class="col-md-6 company-infomation">
                <h2 class="font-bold">AIC CMS SYSTEM V.1.0+</h2>
                <p>
                    +5,000 doanh nghiệp và chủ shop đã chọn để bán hàng từ Online đến Offline.
                </p>
                <p>
                    Sản phẩm của AIC luôn có tốc độ xử lý rất nhanh(~2 giây) giúp đem lại trải nghiệm tốt cho người dùng.
                </p>
                <p>
                   Với công nghệ mới, khách hàng sẽ luôn được sử dụng sản phẩm tốt nhất với mức giá ưu đãi nhất.
                </p>
                <p>
                   Website được xây dựng đơn giản rõ ràng, tinh tế, cùng chế độ bảo hành bảo trì thường xuyên..
                </p>
            </div>
            <div class="col-md-6">
                <div class="ibox-content">
					<?php 
						$error = validation_errors();
						echo (!empty($error) && isset($error)) ? '<div class="alert alert-danger">'.$error.'</div>' : '';
					?>
                    <form class="m-t" role="form" method="post" action="">
                        <div class="form-group">
                            <input type="text" class="form-control" name="account" placeholder="Tài khoản" >
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="Mật khẩu" >
                        </div>
                        <button type="submit" name="login" value="create" class="btn btn-primary block full-width m-b">Đăng nhập</button>

                        <a href="<?php echo base_url('user/backend/auth/recovery'); ?>">
                            <small>Quên mật khẩu?</small>
                        </a>
                    </form>
                    <p class="m-t">
                        <small>Hệ thống quản trị nội dung AIC <?php echo date("Y"); ?> Version 1.0+</small>
                    </p>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-6">
                Copyright AIC Company
            </div>
            <div class="col-md-6 text-right">
               <small>© <?php echo date("Y"); ?>-<?php echo date("Y") + 1; ?> </small>
            </div>
        </div>
    </div>
	<?php $this->load->view('dashboard/backend/common/notification'); // Show cái hiển thị thông báo ?>
	<script src="template/backend/js/plugins/toastr/toastr.min.js"></script>
</body>

</html>
