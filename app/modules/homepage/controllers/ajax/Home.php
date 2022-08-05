<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {


	public function __construct(){
		parent::__construct();
		
	}
	
	public function whois(){
		
		
		define('USERNAME','htwebvietnam');//Username đại lý
		define('API_KEY','a70b1976fa3f94c0df176071438b89e8');//API KEY
		define('API_URL','https://daily.pavietnam.vn/interface.php');//Link API (Mặc định đang là link API test)
		
		
		$error = '';
		
		$domain 	= $this->input->post('domain');
		
		$result 	= file_get_contents(API_URL."?cmd=check_whois&apikey=".API_KEY."&username=".USERNAME."&domain=".$domain);
		
		
		if($result == '0')//Tên miền đã được đăng ký
		{
			$error['flag'] = '1';
			$error['message'] = 'Không thể đăng ký';
		}
		else if($result == '1')//Tên miền chưa đăng ký
		{
			$error['flag'] = '0';
			$error['message'] = 'Có thể đăng ký';
		}
		else//Các trường hợp lỗi khi truy cập API
		{
			echo "<span style='color:#F00'>$result</span>";
		}
		
		
		echo json_encode($error);die();	
	}

}
