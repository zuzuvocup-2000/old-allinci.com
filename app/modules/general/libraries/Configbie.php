<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ConfigBie{

	function __construct($params = NULL){
		$this->params = $params;
	}

	// meta_title là 1 row -> seo_meta_title
	// contact_address
	// chưa có thì insert
	// có thì update
	public function system(){
		$data['homepage'] =  array(
			'label' => 'Thông tin chung',
			'description' => 'Cài đặt đầy đủ thông tin chung của website. Tên thương hiệu website. Logo của website và icon website trên tab trình duyệt',
			'value' => array(
				'company' => array('type' => 'text', 'label' => 'Tên công ty'),
				'slogan' => array('type' => 'text', 'label' => 'Sologan'),
				'logo' => array('type' => 'images', 'label' => 'Logo'),
				'logo_ft' => array('type' => 'images', 'label' => 'Logo Chân trang'),
				'pickup' => array('type' => 'textarea', 'label' => 'Nội dung pickup'),
				'ft' => array('type' => 'textarea', 'label' => 'Text chân trang'),
				'copyright' => array('type' => 'text', 'label' => 'copyright'),
				'copyright_link' => array('type' => 'text', 'label' => 'copyright_link'),
				// 'store' => array('type' => 'text', 'label' => 'Tiêu đề cửa hàng'),
				// 'brand' => array('type' => 'text', 'label' => 'Tiêu đề brand'),
				// 'copyright_link' => array('type' => 'text', 'label' => 'copyright_link'),
				'time' => array('type' => 'editor', 'label' => 'Open time'),
				'book' => array('type' => 'text', 'label' => 'Tiêu đề nút book'),
				'book_link' => array('type' => 'text', 'label' => 'Link nút book'),
				// 'icon_slide' => array('type' => 'images', 'label' => 'Icon thương hiệu'),
				// 'bg_news' => array('type' => 'images', 'label' => 'Background Tin tức Home'),
				// 'bg_prd' => array('type' => 'images', 'label' => 'Background Sản Phẩm Home'),
				// 'bg_mail' => array('type' => 'images', 'label' => 'Background Contact Us'),
				// 'bg_ft' => array('type' => 'images', 'label' => 'Background Chân trang'),
				// 'bg_banner_1' => array('type' => 'images', 'label' => 'Background Banner 1'),
				// 'banner_1' => array('type' => 'images', 'label' => 'Banner 1'),
				// 'banner' => array('type' => 'images', 'label' => 'Banner Đầu Trang (Dùng chung)'),
				'favicon' => array('type' => 'images', 'label' => 'Favicon','title' => 'Favicon là gì?','link' => 'https://webchuanseoht.com/favicon-la-gi-tac-dung-cua-favicon-nhu-the-nao.html'),
				// 'ship' => array('type' => 'text', 'label' => 'Phí ship mặc định', 'class' => 'int'),
				// 'catalog' => array('type' => 'files', 'label' => 'Catalog'),
			),
		);

		$data['website'] =  array(
			'label' => 'Cấu hình website',
			'description' => 'Cài đặt đầy đủ Cấu hình của website. Trạng thái website, index google, ... ',
			'value' => array(
				'status' => array('type' => 'dropdown' , 'label' => 'Trạng thái website' , 'value' => array('open' => 'Mở cửa website' , 'close' => 'Đóng cửa website')),
				'index' => array('type' => 'dropdown' , 'label' => 'Index Google' , 'value' => array('yes' => 'Có','no' => 'Không')),
				// 'https' => array('type' => 'dropdown' , 'label' => 'HTTPS' , 'value' => array('no' => 'Không sử dụng', 'yes' => 'Sử dụng')),
			),
		);
		
		$data['contact'] =  array(
			'label' => 'Thông tin liên lạc',
			'description' => 'Cấu hình đầy đủ thông tin liên hệ giúp khách hàng dễ dàng tiếp cận với dịch vụ của bạn',
			'value' => array(
				'address' => array('type' => 'text', 'label' => 'Địa chỉ'),
				'phone' => array('type' => 'text', 'label' => 'Điện thoại'),
				'hotline' => array('type' => 'text', 'label' => 'Hotline'),
				'email' => array('type' => 'text', 'label' => 'Email'),
				'website' => array('type' => 'text', 'label' => 'Website'),
				'website_link' => array('type' => 'text', 'label' => 'Website Link'),
				'map_link' => array('type' => 'text', 'label' => 'Link bản đồ'),
				'map' => array('type' => 'textarea', 'label' => 'Bản đồ','title' => 'Hướng dẫn thiết lập bản đồ','link' => 'https://webchuanseoht.com/huong-dan-thiet-lap-ban-do-google-map.html'),
				'bct' => array('type' => 'text', 'label' => 'Link Bộ công thương'),
				'extend' => array('type' => 'editor', 'label' => 'Thông tin bổ sung'),
			),
		);

		$data['contact_1'] =  array(
			'label' => 'Cửa hàng 1',
			'description' => 'Cấu hình đầy đủ thông tin liên hệ giúp khách hàng dễ dàng tiếp cận với dịch vụ của bạn',
			'value' => array(
				'name' => array('type' => 'text', 'label' => 'Tên cửa hàng'),
				'image' => array('type' => 'images', 'label' => 'Hình ảnh'),
				'address' => array('type' => 'text', 'label' => 'Địa chỉ'),
				'phone' => array('type' => 'text', 'label' => 'Điện thoại'),
				'hotline' => array('type' => 'text', 'label' => 'Hotline'),
				'email' => array('type' => 'text', 'label' => 'Email'),
				'time' => array('type' => 'text', 'label' => 'Giờ mở cửa'),
				'map' => array('type' => 'textarea', 'label' => 'Bản đồ','title' => 'Hướng dẫn thiết lập bản đồ','link' => 'https://webchuanseoht.com/huong-dan-thiet-lap-ban-do-google-map.html'),
			),
		);


		$data['contact_2'] =  array(
			'label' => 'Cửa hàng 2',
			'description' => 'Cấu hình đầy đủ thông tin liên hệ giúp khách hàng dễ dàng tiếp cận với dịch vụ của bạn',
			'value' => array(
				'name' => array('type' => 'text', 'label' => 'Tên cửa hàng'),
				'image' => array('type' => 'images', 'label' => 'Hình ảnh'),
				'address' => array('type' => 'text', 'label' => 'Địa chỉ'),
				'phone' => array('type' => 'text', 'label' => 'Điện thoại'),
				'hotline' => array('type' => 'text', 'label' => 'Hotline'),
				'email' => array('type' => 'text', 'label' => 'Email'),
				'time' => array('type' => 'text', 'label' => 'Giờ mở cửa'),
				'map' => array('type' => 'textarea', 'label' => 'Bản đồ','title' => 'Hướng dẫn thiết lập bản đồ','link' => 'https://webchuanseoht.com/huong-dan-thiet-lap-ban-do-google-map.html'),
			),
		);

		$data['contact_3'] =  array(
			'label' => 'Cửa hàng 3',
			'description' => 'Cấu hình đầy đủ thông tin liên hệ giúp khách hàng dễ dàng tiếp cận với dịch vụ của bạn',
			'value' => array(
				'name' => array('type' => 'text', 'label' => 'Tên cửa hàng'),
				'image' => array('type' => 'images', 'label' => 'Hình ảnh'),
				'address' => array('type' => 'text', 'label' => 'Địa chỉ'),
				'phone' => array('type' => 'text', 'label' => 'Điện thoại'),
				'hotline' => array('type' => 'text', 'label' => 'Hotline'),
				'email' => array('type' => 'text', 'label' => 'Email'),
				'time' => array('type' => 'text', 'label' => 'Giờ mở cửa'),
				'map' => array('type' => 'textarea', 'label' => 'Bản đồ','title' => 'Hướng dẫn thiết lập bản đồ','link' => 'https://webchuanseoht.com/huong-dan-thiet-lap-ban-do-google-map.html'),
			),
		);


		$data['seo'] =  array(
			'label' => 'Cấu hình thẻ tiêu đề',
			'description' => 'Cài đặt đầy đủ Thẻ tiêu đề và thẻ mô tả giúp xác định cửa hàng của bạn xuất hiện trên công cụ tìm kiếm.',
			'value' => array(
				'meta_title' => array('type' => 'text', 'label' => 'Tiêu đề trang','extend' => ' trên 70 kí tự', 'class' => 'meta-title', 'id' => 'titleCount'),
				'meta_description' => array('type' => 'textarea', 'label' => 'Mô tả trang','extend' => ' trên 320 kí tự', 'class' => 'meta-description', 'id' => 'descriptionCount'),
			),
		);
		$data['analytic'] =  array(
			'label' => 'Google Analytics',
			'description' => 'Dán đoạn mã hoặc mã tài khoản GA được cung cấp bởi Google.',
			'value' => array(
				'google_analytic' => array('type' => 'textarea', 'label' => 'Mã Google Analytics','title' => 'Hướng dẫn thiết lập Google Analytic','link' => 'https://webchuanseoht.com/huong-dan-thiet-lap-google-analytics.html'),
			),
		);
		$data['script'] =  array(
			'label' => 'Script bên thứ 3',
			'description' => 'Nhúng script bên thứ 3 vào đây',
			'value' => array(
				'head' => array('type' => 'textarea', 'label' => 'Script đầu trang'),
				'body' => array('type' => 'textarea', 'label' => 'Script thân trang'),
			),
		);

		$data['facebook'] =  array(
			'label' => 'Facebook Pixel',
			'description' => 'Facebook Pixel giúp bạn tạo chiến dịch quảng cáo trên facebook để tìm kiếm khách hàng mới mua hàng trên website của bạn.',
			'value' => array(
				'facebook_pixel' => array('type' => 'text', 'label' => 'Facebook Pixel','title' => 'Hướng dẫn thiết lập Facebook Pixel','link' => 'https://webchuanseoht.com/huong-dan-su-dung-pixel-quang-cao-facebook-moi-cap-nhat.html'),
			),
		);
		$data['social'] =  array(
			'label' => 'Mạng xã hội',
			'description' => 'Cập nhật đầy đủ thông tin mạng xã hội giúp khách hàng dễ dàng tiếp cận với dịch vụ của bạn',
			'value' => array(
				'facebook' => array('type' => 'text', 'label' => 'Fanpage Facebook'),
				'twitter' => array('type' => 'text', 'label' => 'Twitter'),
				// 'messenger' => array('type' => 'text', 'label' => 'messenger'),
				'google' => array('type' => 'text', 'label' => 'Google Plus'),
				'youtube' => array('type' => 'text', 'label' => 'Youtube'),
				'instagram' => array('type' => 'text', 'label' => 'Instagram'),
				'pinterest' => array('type' => 'text', 'label' => 'Pinterest'),
				'linkedin' => array('type' => 'text', 'label' => 'Linkedin'),
				'whatsapp' => array('type' => 'text', 'label' => 'Whatsapp'),
				'telegram' => array('type' => 'text', 'label' => 'Telegram ( Username - not Phone )'),
			),
		);

		$data['another'] =  array(
			'label' => 'Các mục khác',
			'description' => 'Cập nhật đầy đủ thông tin giúp khách hàng dễ dàng tiếp cận với dịch vụ của bạn',
			'value' => array(
            	'bg' => array('type' => 'images', 'label' => 'Bg Giới thiệu'),
            	'img' => array('type' => 'images', 'label' => 'Ảnh Giới thiệu'),
				'intro' => array('type' => 'editor', 'label' => 'Giới thiệu ngắn'),
				// 'intro_link' => array('type' => 'text', 'label' => 'Đường link bài chi tiết'),
				// 'intro_video' => array('type' => 'textarea', 'label' => 'Video giới thiệu ( Mã nhúng Youtube )'),

				
			),
		);

		// $data['activity'] =  array(
		// 	'label' => 'Activity',
		// 	'description' => 'Cập nhật đầy đủ thông tin giúp khách hàng dễ dàng tiếp cận với dịch vụ của bạn',
		// 	'value' => array(
		// 		'intro' => array('type' => 'text', 'label' => 'Tên hiển thị'),
  //       		'img' => array('type' => 'images', 'label' => 'Ảnh Activity'),
		// 		'video' => array('type' => 'textarea', 'label' => 'Video Activity ( Mã nhúng Youtube )'),
				
		// 	),
		// );

		// $data['booking'] =  array(
		// 	'label' => 'Booking',
		// 	'description' => 'Cập nhật đầy đủ thông tin giúp khách hàng dễ dàng tiếp cận với dịch vụ của bạn',
		// 	'value' => array(
		// 		'link_1' => array('type' => 'text', 'label' => 'Link booking online'),
		// 		'link_2' => array('type' => 'text', 'label' => 'Link GET AN APPOINTMENT'),
  //       		// 'img' => array('type' => 'images', 'label' => 'Ảnh Activity'),
		// 		// 'video' => array('type' => 'textarea', 'label' => 'Video Activity ( Mã nhúng Youtube )'),
				
		// 	),
		// );


		$data['commit'] =  array(
	        'label' => 'Cam kết',
	        'description' => 'Cập nhật đầy đủ thông tin giúp khách hàng dễ dàng tiếp cận với dịch vụ của bạn',
	        'value' => array(
	            // 'head' => array('type' => 'text','label' => 'Tiêu đề chính'),
	            // 'img' => array('type' => 'images', 'label' => 'Ảnh chính'),

	            'title_1' => array('type' => 'text','label' => 'Tiêu đề 1'),
	            'icon_1' => array('type' => 'images', 'label' => 'Icon 1'),
	            'content_1' => array('type' => 'editor','label' => 'Nội dung 1'),

	            'title_2' => array('type' => 'text','label' => 'Tiêu đề 2'),
	            'icon_2' => array('type' => 'images', 'label' => 'Icon 2'),
	            'content_2' => array('type' => 'editor','label' => 'Nội dung 2'),

	            'title_3' => array('type' => 'text','label' => 'Tiêu đề 3'),
	            'icon_3' => array('type' => 'images', 'label' => 'Icon 3'),
	            'content_3' => array('type' => 'editor','label' => 'Nội dung 3'),

	            'title_4' => array('type' => 'text','label' => 'Tiêu đề 4'),
	            'icon_4' => array('type' => 'images', 'label' => 'Icon 4'),
	            'content_4' => array('type' => 'editor','label' => 'Nội dung 4'),
	        ),

	    );

		return $data;
	}
}
