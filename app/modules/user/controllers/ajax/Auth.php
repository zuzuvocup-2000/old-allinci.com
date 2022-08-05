<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {


	public function __construct(){
		parent::__construct();
	}
	
	public function validate_otp_code(){
		$otpNumber = $this->input->post('otpNumber');
		$userid = $this->input->post('userid');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('otpNumber[]','Mã OTP','trim|required|callback__CheckOtp');
		if($this->form_validation->run($this)){
			$user = $this->Autoload_Model->_get_where(array(
				'select' => 'id, account, email, otp, otp_time_live',
				'table' => 'user',
				'where' => array('id' => $userid)
			));
			$error['flag'] = 0;
			$error['message'] = ''; 
			echo json_encode(array(
				'error' => $error,
				'userid' => $user['id']
			));die();
		}else{
			$user = $this->Autoload_Model->_get_where(array(
				'select' => 'id, account, email',
				'table' => 'user',
				'where' => array('id' => $userid)
			));
			$error['flag'] = 1;
			$error['message'] = validation_errors(); 
			echo json_encode(array(
				'error' => $error,
			));die();
		}
	}
	public function get_otp_code(){
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email|callback__CheckEmail');
		if($this->form_validation->run($this)){
			
			$email = $this->input->post('email');
			$user = $this->Autoload_Model->_get_where(array(
				'select'=> 'id, account, email, fullname',
				'table' => 'user',
				'where' => array(
					'publish' => 1,
					'email' => $email
				)
			));
			
			
			$otpCode = otp_code_render();
			$timeToLive = $time = date("Y-m-d H:i:s", time() + 300); 
			$_update['otp'] = $otpCode;
			$_update['otp_time_live'] = $timeToLive;
			
			$flag = $this->Autoload_Model->_update(array(
				'where' => array('id' => $user['id']),
				'table' => 'user',
				'data' => $_update
			));
			
			
			if($flag > 0){
				$this->load->library('mailbie');
				$this->mailbie->sent(array(
					'to' => $user['email'],
					'cc' => '',
					'subject' => 'Mã xác thực reset mật khẩu', 
					'message' => recovery_template(array(
						'otpCode' => $otpCode,
						'fullname' => $user['fullname']
					)), 
				));
				
				
			
				$error['flag'] = 0;
				$error['message'] = '';
				
				echo json_encode(array(
					'error' => $error,
					'id' => $user['id'],
				));die();
				
			}else{
				
				$error['flag'] = 1;
				$error['message'] = 'Có lỗi xảy ra trong quá trình tạo mã xác thực. Vui lòng thử lại';

				echo json_encode(array(
					'error' => $error,
				));die();
			}
		}else{
			
			$error['flag'] = 1;
			$error['message'] = validation_errors(); 
		}
		
		
		echo json_encode(array(
			'error' => $error,
		));die();
		
	}
	
	//Viết function để thay đổi mật khẩu
	public function change_password(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules('password','Mật khẩu','trim|required|min_length[8]');
		$this->form_validation->set_rules('re_password','Nhập lại Mật khẩu','trim|required|matches[password]'); // 2 Mật khẩu 
		if($this->form_validation->run($this)){
			$password = $this->input->post('password');
			$salt = random();
			/* Cập nhật vào bảng User */
			$_update = array(
				'password' => password_encode($password, $salt), // Phải mã hóa mật khẩu
				'salt' => $salt,
				'updated' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				'user_agent' => $_SERVER['HTTP_USER_AGENT'],
				'remote_addr' => $_SERVER['REMOTE_ADDR'],
			);
			//Tiến thành cập nhật user để thay đổi dữ liệu.
			$flag = $this->Autoload_Model->_update(array(
				'where' => array('id' => $this->input->post('userid')),
				'table' => 'user',
				'data' => $_update,
			));
			if($flag > 0){
				
				//Hien thi thong bao neu mat khau duoc cap nhat thanh cong.
				$this->session->set_flashdata('message-success', 'Cập nhật mật khẩu thành công, Hãy đăng nhập lại');
				$error['flag'] = 0;
				$error['message'] = ''; 
				
			}else{
				$error['flag'] = 1;
				$error['message'] = 'Có lỗi xảy ra trong quá trình thay đổi mật khẩu'; 
			}
			
		}else{
			$error['flag'] = 1;
			$error['message'] = validation_errors(); 
		}
		
		echo json_encode(array(
			'error' => $error,
		));die();
		
	}
	
	public function _CheckOtp(){
		$otpNumber = $this->input->post('otpNumber');
		$userid = $this->input->post('userid');
		$user = $this->Autoload_Model->_get_where(array(
			'select' => 'id, account, email, otp, otp_time_live',
			'table' => 'user',
			'where' => array('id' => $userid)
		));
		$otp = '';
		if(isset($otpNumber) && is_array($otpNumber) && count($otpNumber)){
			foreach($otpNumber as $key => $val){
				$otp = $otp.$val;
			}
		}
		if(strlen($otp) != 6){
			$this->form_validation->set_message('_CheckOtp','Mã xác thực không đúng định dạng');
			return false;
		}
		if(!is_numeric($otp)){
			$this->form_validation->set_message('_CheckOtp','Mã xác thực phải là kiểu số');
			return false;
		}
		if($otp != $user['otp']){
			$this->form_validation->set_message('_CheckOtp','Mã xác thực không chính xác');
			return false;
		}
		$currenDate = date("Y-m-d H:i:s", time());
		if($currenDate > $user['otp_time_live']){
			$this->form_validation->set_message('_CheckOtp','Mã xác thực đã hết hạn, vui lòng lấy mã xác thực mới');
			return false;
		}
		return TRUE;
	}
	
	public function _CheckEmail($email = ''){
		$count = $this->Autoload_Model->_get_where(array(
			'select' => 'id',
			'table' => 'user',
			'where' => array('email' => $email,'publish' => 1),
		));
		if($count <= 0){
			$this->form_validation->set_message('_CheckEmail','Email không tồn tại');
			return false;
		}
		return true;
	}
}
