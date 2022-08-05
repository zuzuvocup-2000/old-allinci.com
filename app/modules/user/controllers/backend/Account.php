<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends MY_Controller {


	public function __construct(){
		parent::__construct();
		if(!isset($this->auth)) redirect('admin');
	}
	
	public function Profile(){
		$id  = (int)$this->auth['id'];
		$detailUser = $this->Autoload_Model->_get_where(array(
			'select' => 'id, catalogueid, account, fullname, email, birthday, gender, address, phone, cityid, districtid, wardid, description',
			'table' => 'user',
			'where' => array('id' => $id)
		));
		
		if(!isset($detailUser) || is_array($detailUser) == FALSE || count($detailUser) == 0){
			$this->session->set_flashdata('message-danger', 'Tài khoản thành viên không tồn tại'); 
			redirect(site_url('user/backend/user/view')); 
		}
		
		$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
		if($this->input->post('reset')){
			$this->form_validation->set_rules('password','Mật khẩu','trim|required|min_length[6]');
			$this->form_validation->set_rules('re_password','Nhập lại mật khẩu','trim|required|matches[password]');
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
					'where' => array('id' => $id),
					'table' => 'user',
					'data' => $_update,
				));
				
				if($flag > 0){
					unset($_SESSION[AUTH.'auth']); 
					$this->session->set_flashdata('message-success', 'Thay đổi mật khẩu thành công, bạn cần đăng nhập lại');
					redirect(BACKEND_DIRECTORY);
				}
			}
		}
		
		if($this->input->post('update')){
			$data['cityPost'] = $this->input->post('cityid');
			$this->form_validation->set_rules('account','Tài khoản','trim|required');
			$this->form_validation->set_rules('fullname','Họ tên','trim|required');
			$this->form_validation->set_rules('catalogueid','Nhóm thành viên','trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('email','Email','trim|required|valid_email|callback__CheckRegister');
			if($this->form_validation->run($this)){
				$_update = array(
					'catalogueid' => $this->input->post('catalogueid'),
					'account' => $this->input->post('account'),
					'fullname' => htmlspecialchars_decode(html_entity_decode($this->input->post('fullname'))),
					'email' => $this->input->post('email'),
					'birthday' => convert_time($this->input->post('birthday')),
					'gender' => (int)$this->input->post('gender'),
					'address' => $this->input->post('address'),
					'phone' => $this->input->post('phone'),
					'cityid' => $this->input->post('cityid'),
					'districtid' => $this->input->post('districtid'),
					'wardid' => $this->input->post('wardid'),
					'description' => $this->input->post('description'),
					'publish' => 1,
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
					'userid_created' => $this->auth['id'],
				);
				$flag = $this->Autoload_Model->_update(array(
					'where' => array('id' => $id),
					'table' => 'user',
					'data' => $_update
				));
				if($flag > 0){
					$this->session->set_flashdata('message-success', 'Thay đổi thông tin tài khoản thành công'); 
					redirect(site_url('user/backend/account/profile')); 
				}
			}
		}
		
		$data['detailUser'] = $detailUser;
		$data['script'] = 'user';
		$data['template'] = 'user/backend/account/profile';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function _CheckRegister($email = ''){
		$account = $this->input->post('account');
		$accountOriginal = $this->input->post('account_original');
		if($account != $accountOriginal){
			if(!preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $account) ){
				$this->form_validation->set_message('_CheckRegister','Tài khoản không đúng định dạng. Tài khoản từ 6-32 ký tự, bắt đầu bằng chữ, và không chứa ký tự đặc biệt');
				return false;
			}
			$userByAccount = $this->Autoload_Model->_get_where(array(
				'select' => 'id, email',
				'table' => 'user',
				'where' => array(
					'account' => $account,
				)
			));
			if(isset($userByAccount) && is_array($userByAccount) && count($userByAccount)){
				$this->form_validation->set_message('_CheckRegister','Tài khoản đã tồn tại');
				return false;
			}
		}
		
		$emailOriginal = $this->input->post('email_original');
		if($email != $emailOriginal){
			$userByEmail = $this->Autoload_Model->_get_where(array(
				'select' => 'id, email',
				'table' => 'user',
				'where' => array(
					'email' => $email,
				)
			));
			if(isset($userByEmail) && is_array($userByEmail) && count($userByEmail)){
				$this->form_validation->set_message('_CheckRegister','Email đã tồn tại');
				return false;
			}
		}
		return true;
	}
	
}
