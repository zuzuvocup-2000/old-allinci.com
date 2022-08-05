<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

	public $module;
	function __construct() {
		parent::__construct();
		$this->load->library(array('configbie'));
		if(!isset($this->auth)) redirect('admin');
	}
	
	public function view($page = 1){
		$this->commonbie->permission("user/backend/user/view", $this->auth['permission']);
		$page = (int)$page;
		$data['from'] = 0;
		$data['to'] = 0;
		$config['total_rows'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id',
			'table' => 'user',
			'where' => array('id !=' => 2),
			'count' => TRUE,
		));
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('user/backend/user/view');
			$config['suffix'] = $this->config->item('url_suffix').(!empty($_SERVER['QUERY_STRING'])?('?'.$_SERVER['QUERY_STRING']):'');
			$config['first_url'] = $config['base_url'].$config['suffix'];
			$config['per_page'] = 20;
			$config['uri_segment'] = 5;
			$config['use_page_numbers'] = TRUE;
			$config['full_tag_open'] = '<ul class="pagination no-margin">';
			$config['full_tag_close'] = '</ul>';
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="active"><a class="btn-primary">';
			$config['cur_tag_close'] = '</a></li>';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$this->pagination->initialize($config);
			$data['PaginationList'] = $this->pagination->create_links();
			$totalPage = ceil($config['total_rows']/$config['per_page']);
			$page = ($page <= 0)?1:$page;
			$page = ($page > $totalPage)?$totalPage:$page;
			$page = $page - 1;
			$data['from'] = ($page * $config['per_page']) + 1;
			$data['to'] = ($config['per_page']*($page+1) > $config['total_rows']) ? $config['total_rows']  : $config['per_page']*($page+1);
			$data['listUser'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id, catalogueid, fullname, account, avatar, email, publish, address, gender, phone, last_login, (SELECT title FROM user_catalogue WHERE user_catalogue.id = user.catalogueid) as group_title',
				'table' => 'user',
				'where' => array('id !=' => 2),
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'order_by' => 'fullname asc, created desc',
			), TRUE);
		}
		
		
		
		$data['config'] = $config;
		$data['script']='user';
		$data['template'] = 'user/backend/user/view';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	public function create(){
		$this->commonbie->permission("user/backend/user/create", $this->auth['permission']);
		if($this->input->post('create')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_rules('account','Tài khoản','trim|required');
			$this->form_validation->set_rules('fullname','Họ tên','trim|required');
			$this->form_validation->set_rules('catalogueid','Nhóm thành viên','trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('password','Mật khẩu','trim|required|min_length[6]');
			$this->form_validation->set_rules('email','Email','trim|required|valid_email|callback__CheckRegister');
			if($this->form_validation->run($this)){
				$salt = random();
				$_insert = array(
					'catalogueid' => $this->input->post('catalogueid'),
					'account' => $this->input->post('account'),
					'fullname' => htmlspecialchars_decode(html_entity_decode($this->input->post('fullname'))),
					'email' => $this->input->post('email'),
					'birthday' => convert_time($this->input->post('birthday')),
					'salt' => $salt,
					'password' => password_encode($this->input->post('password'), $salt),
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
				
				
				$insertId = $this->Autoload_Model->_create(array(
					'table' => 'user',
					'data' => $_insert,
				));
				if($insertId > 0){
					$this->session->set_flashdata('message-success', 'Tạo tài khoản thành công'); 
					redirect(site_url('user/backend/user/create')); 
				}
			}
		}
		
		$data['script'] = 'user';
		$data['template'] = 'user/backend/user/create';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
		
	public function update($id = 0){
		$this->commonbie->permission("user/backend/user/update", $this->auth['permission']);
		$id = (int)$id;
		$detailUser = $this->Autoload_Model->_get_where(array(
			'select' => 'id, catalogueid, account, fullname, email, birthday, gender, address, phone, cityid, districtid, wardid, description',
			'table' => 'user',
			'where' => array('id' => $id)
		));
		if(!isset($detailUser) || is_array($detailUser) == FALSE || count($detailUser) == 0){
			$this->session->set_flashdata('message-danger', 'Tài khoản thành viên không tồn tại'); 
			redirect(site_url('user/backend/user/view')); 
		}
		
		if($this->input->post('update')){
			$data['cityPost'] = $this->input->post('cityid');
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
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
					$this->session->set_flashdata('message-success', 'Cập nhật tài khoản khoản thành công'); 
					redirect(site_url('user/backend/user/update/'.$id.'')); 
				}
			}
		}
		
		$data['detailUser'] = $detailUser;
		$data['script'] = 'user';
		$data['template'] = 'user/backend/user/update';
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
