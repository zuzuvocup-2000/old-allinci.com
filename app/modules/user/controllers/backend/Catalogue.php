<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogue extends MY_Controller {

	public $module;
	function __construct() {
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library(array('configbie'));
		$this->module = 'user_catalogue';
	}
	
	public function view($page = 1){
		$this->commonbie->permission("user/backend/catalogue/view", $this->auth['permission']);
		$data['script']='user';
		$page = (int)$page;
		$data['from'] = 0;
		$data['to'] = 0;
		//Tính tổng số bản ghi của trang danh mục
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 2;
		$config['total_rows'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id',
			'table' => 'user_catalogue',
			'count' => TRUE,
		));
		// echo $config['total_rows'];die();
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('user/backend/catalogue/view');
			$config['suffix'] = $this->config->item('url_suffix').(!empty($_SERVER['QUERY_STRING'])?('?'.$_SERVER['QUERY_STRING']):'');
			$config['first_url'] = $config['base_url'].$config['suffix'];
			$config['per_page'] = $perpage;
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
		
			$data['listCatalogue'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id, title, created, updated, publish, (SELECT COUNT(id) FROM user WHERE user.catalogueid = user_catalogue.id AND user.id != 2) as total_user',
				'table' => 'user_catalogue',
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'order_by' => 'title asc, created desc',
			), TRUE);
		}
		$data['script'] = 'user_catalogue';
		$data['config'] = $config;
		$data['template'] = 'user/backend/catalogue/view';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function create(){
		$data['script']='user';
		$this->commonbie->permission("user/backend/catalogue/create", $this->auth['permission']);
		if($this->input->post('create')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('', ' / ');
			$this->form_validation->set_rules('title','Tên nhóm người dùng','trim|required|max_length[100]|callback__CheckCondition');
			if($this->form_validation->run($this)){
				$_insert = array(
					'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'slug' => slug($this->input->post('title')),
					'permission' => json_encode($this->input->post('permission')),
					'publish' => $this->input->post('publish'),
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
					'userid_created' => $this->auth['id'],
				);
				if(isset($_insert) && is_array($_insert) && count($_insert)){
					$resultid = $this->Autoload_Model->_create(array(
						'table' => 'user_catalogue',
						'data' => $_insert,
					));
					if($resultid > 0){
						$this->session->set_flashdata('message-success','Thêm nhóm người dùng mới thành công');
						redirect(base_url('user/backend/catalogue/view'));
					}
				}
			}
		}
		
		$data['script'] = 'user_catalogue';
		$data['template'] = 'user/backend/catalogue/create';
		$this->load->view('dashboard/backend/layout/dashboard', ((isset($data)) ? $data : ''));
	}
	
	public function update($id = 0){
		$this->commonbie->permission("user/backend/catalogue/update", $this->auth['permission']);
		$id = (int)$id;
		$data['script']='user';
		$data['detailCataloge'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, slug, permission, publish',
			'table' => $this->module,
			'where' => array('id' => $id),
		));
		if(!isset($data['detailCataloge']) || is_array($data['detailCataloge']) == false || count($data['detailCataloge']) == 0){
			$this->session->set_flashdata('message-danger', 'Nhóm người dùng không tồn tại');
			redirect('user/backend/catalogue/view');
		}
		if($this->input->post('update')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('', ' / ');
			$this->form_validation->set_rules('title','Tên nhóm người dùng','trim|required|max_length[100]|callback__CheckCondition');
			if ($this->form_validation->run($this)){
				$_update =  array(
					'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'slug' => slug($this->input->post('title')),
					'permission' => json_encode($this->input->post('permission')),
					'publish' => $this->input->post('publish'),
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);
				
				$flag = $this->Autoload_Model->_update(array(
					'where' => array('id' => $id),
					'table' => 'user_catalogue',
					'data' => $_update,
				));
				if($flag > 0){
					$this->session->set_flashdata('message-success', 'Cập nhật nhóm người dùng thành công');
					redirect('user/backend/catalogue/view');
				}
			}
		}
		$data['template'] = 'user/backend/catalogue/update';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}


	public function _CheckCondition($title = ''){
		$slug = slug($title);
		$slug_original = $this->input->post('slug_original');
		if($slug != $slug_original){
			$count = $this->Autoload_Model->_get_where(array(
				'select' => 'id',
				'table' => $this->module,
				'where' => array('trash' => 0,'slug' => $slug),
				'count' => TRUE
			));
			if($count > 0){
				$this->form_validation->set_message('_CheckCondition','Tên nhóm người dùng đã tồn tại');
				return false;
			}
			return true;
		}
		return true;
	}
	
	
}
