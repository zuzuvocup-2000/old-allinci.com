<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {


	public function __construct(){
		parent::__construct();
		$this->load->model(array(
			'BackendAuth_Model',
		));
		
	}
	
	public function Login(){
		if(isset($this->auth)) redirect('dashboard/home/index');
		
		if($this->input->post('login')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('',' / '); 
			$this->form_validation->set_rules('account','Tài khoản','trim|required');
			$this->form_validation->set_rules('password','Mật khẩu','trim|required|callback__CheckAuth');
			if($this->form_validation->run($this)){
				$account = $this->input->post('account');
				
				$user = $this->Autoload_Model->_get_where(array('select' => 'id, account, fullname, email, password, salt','table' => 'user','where' => array('account' => $account,),));
				
				$_update = array(
					'last_login' => gmdate('Y-m-d H:i:s', time() + 7*3600),
					'user_agent' => $_SERVER['HTTP_USER_AGENT'],
					'remote_addr' => $_SERVER['REMOTE_ADDR']
				);
				
				$flag = $this->Autoload_Model->_update(array(
					'where' => array('id' => $user['id']),
					'table' => 'user',
					'data' => $_update,
				));
				
				if($flag > 0){
					$_SESSION[AUTH.'auth'] = json_encode(array(
						'id' => $user['id'],
						'account' => $user['account'],
						'email' => $user['email'],
						'password' => $user['password'],
						'folder_upload' => $user['account'],
					));
				}
				$this->session->set_flashdata('message-success', 'Đăng nhập thành công'); 
				redirect(base_url('dashboard/home/index')); 
			}
		}
		
		$this->load->view('user/backend/auth/login');
	}

	public function Recovery(){
		if(isset($this->auth)) redirect('dashboard/home/index');
		
		
		
		$this->load->view('user/backend/auth/recovery');
	}
	
	public function Logout(){
		if(!isset($this->auth)) redirect('admin');
		unset($_SESSION[AUTH.'auth']); 
		redirect('admin');
	}
	
	
	public function _CheckAuth(){
		$account = $this->input->post('account');
		//Kiểm tra xem cơ sở dữ liệu có tài khoản nào phù hợp không.
		$auth = $this->Autoload_Model->_get_where(array(
			'select' => 'id, account, fullname, email, password, salt',
			'table' => 'user',
			'where' => array(
				'account' => $account,
			),
		));
		if(!isset($auth) || is_array($auth) == FALSE || count($auth) == 0){
			$this->form_validation->set_message('_CheckAuth','Tài khoản hoặc mật khẩu không chính xác');
			return FALSE;
		}
		//Kiểm tra tiếp là mật khẩu có đúng hay không.
		$password = $this->input->post('password');
		$passwordCompare = password_encode($password, $auth['salt']);
		if($passwordCompare != $auth['password']){
			$this->form_validation->set_message('_CheckAuth','Tài khoản hoặc mật khẩu không chính xác');
			return FALSE;
		}
		return TRUE;
	}
	
}
