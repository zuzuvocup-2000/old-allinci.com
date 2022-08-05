<!DOCTYPE html>
<html>

<head>
	<base href="<?php echo BASE_URL; ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>HT CMS | Đăng nhập</title>

    <link href="template/backend/css/bootstrap.min.css" rel="stylesheet">
    <link href="template/backend/font-awesome/css/font-awesome.css" rel="stylesheet">
	<link href="template/backend/css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link href="template/backend/css/animate.css" rel="stylesheet">
	<link href="template/backend/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
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
                <h2 class="font-bold">HT CMS SYSTEM V.1.0+</h2>
                <p>
                    +5,000 doanh nghiệp và chủ shop đã chọn để bán hàng từ Online đến Offline.
                </p>
                <p>
                    Sản phẩm của HT Việt Nam luôn có tốc độ xử lý rất nhanh(~2 giây) giúp đem lại trải nghiệm tốt cho người dùng.
                </p>
                <p>
                   Với công nghệ mới, khách hàng sẽ luôn được sử dụng sản phẩm tốt nhất với mức giá ưu đãi nhất.
                </p>
                <p>
                   Website được xây dựng đơn giản rõ ràng, tinh tế, cùng chế độ bảo hành bảo trì thường xuyên..
                </p>
            </div>
            <div class="col-md-6">
				
                <div class="ibox-content loading otp-email">
					<div class="bg-loader"></div>
					<p class="m-t" style="margin:0;">
						<small>Nhập email để nhận mã xác thực tài khoản</small>
					</p>
					
					<!-- Tạo ra 1 thẻ html để chứa lỗi của form -->
					<div class="alert alert-danger mt5"></div>
                    <form class="m-t recovery-form" role="form" method="post" action="">
                        <div class="form-group">
                            <input type="text" class="form-control email" name="email" placeholder="Nhập Email" autocomplete="off" >
                        </div>
                        <button type="submit" name="forgot" value="forgot" class="btn btn-primary block full-width m-b">Xác nhận</button>
						<a href="<?php echo base_url('admin') ?>">
                            <small>Đăng nhập?</small>
                        </a>
                    </form>
                    <p class="m-t">
                        <small>Hệ thống quản trị nội dung HT VIỆT NAM <?php echo date("Y"); ?> Version 1.0+</small>
                    </p>
                </div>
            </div>
        </div>
		
        <hr/>
        <div class="row">
            <div class="col-md-6">
                Copyright HT VIỆT NAM Company
            </div>
            <div class="col-md-6 text-right">
               <small>© <?php echo date("Y"); ?>-<?php echo date("Y") + 1; ?> </small>
            </div>
        </div>
    </div>
	<div class="modal inmodal fade" id="myModal6" tabindex="-1" role="dialog"  aria-hidden="true">
		<div class="modal-dialog" style="position:relative;">
			<div class="bg-loader"></div>
			
			<form class="otp-form" role="form" method="post" action="">
				<div class="alert alert-danger mt5"></div>	
				<input type="hidden" value="" name="id" class="userid">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<h4 class="modal-title" style="font-size:14px;">Nhập mã xác thực của bạn</h4>
					</div>
					<div class="modal-body">
						<?php for($i = 0; $i<=5; $i++){ ?>
							<div class="form-row clearfix col-sm-2">
								<input type="text" value="" name="otp[]" class="input-text otp-number" />
							</div>
						<?php } ?>
						 
							
						 
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-white" >Gửi lại</button>
						<button type="submit" value="submit" name="submit" class="btn btn-primary">Tiếp tục</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
	<!-- 
		Tạo ra 1 form để tiến hanh tao lai mat khau cho nguoi dung.
		Modal reset mật khẩu chỉ cần nhỏ. Thêm class modal-sm vào modal-dialog
	-->
	
	<div class="modal inmodal fade" id="recoveryForm" tabindex="-1" role="dialog"  aria-hidden="true">
		<div class="modal-dialog modal-sm" style="position:relative;">
		
			<!-- Trạng thái Loading khi gặp vấn đề -->
			<div class="bg-loader"></div>
			<form class="" role="form" method="post" action="">
			
			<!-- Chỗ này để đổ lỗi ra khi gặp -->
				
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<h4 class="modal-title" style="font-size:14px;">Tạo mật khẩu mới</h4>
					</div>
					<div class="modal-body">
						<div class="alert alert-danger mt5"></div>	
						<div class="form-group">
							<label>Mật khẩu mới</label> 
							<input type="password" placeholder="" name="password" class="form-control">
						</div>
						<div class="form-group">
							<label>Nhập lại Mật khẩu mới</label> 
							<input type="password" placeholder="" name="re_password" class="form-control">
						</div>
						
						 
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-white" >Gửi lại</button>
						<button type="submit" value="submit" name="submit" class="btn btn-primary">Tiếp tục</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<?php $this->load->view('dashboard/backend/common/notification'); // Show cái hiển thị thông báo ?>
	<script src="template/backend/js/plugins/toastr/toastr.min.js"></script>
	<script src="template/backend/js/plugins/sweetalert/sweetalert.min.js"></script>
	<script src="template/backend/library/auth.js"></script>
    <script src="template/backend/js/bootstrap.min.js"></script>
</body>

</html>


