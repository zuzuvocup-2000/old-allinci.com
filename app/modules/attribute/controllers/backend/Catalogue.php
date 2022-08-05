<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogue extends MY_Controller {

	public $module;
	function __construct() {
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library(array('configbie'));
		$this->load->library('nestedsetbie', array('table' => 'attribute_catalogue'));
	}
	
	public function view($page = 1){
		$this->commonbie->permission("attribute/backend/catalogue/view", $this->auth['permission']);
		$page = (int)$page;
		$data['from'] = 0;
		$data['to'] = 0;
		
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 20;
		$keyword = $this->input->get('keyword');
		if(!empty($keyword)){
			$keyword = '(title LIKE \'%'.$keyword.'%\' OR description LIKE \'%'.$keyword.'%\')';
		}
		$config['total_rows'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id',
			'table' => 'attribute_catalogue',
			'keyword' => $keyword,
			'count' => TRUE,
		));
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('attribute/backend/catalogue/view');
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
				'select' => 'id, title,  publish, created, order, (SELECT fullname FROM user WHERE user.id = attribute_catalogue.userid_created) as user_created',
				'table' => 'attribute_catalogue',
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'keyword' => $keyword,
			), TRUE);
		}
		
		
		$data['script'] = 'attribute_catalogue';
		$data['config'] = $config;
		$data['template'] = 'attribute/backend/catalogue/view';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function Create(){
		$data['script'] = 'attribute_catalogue';
		$this->commonbie->permission("attribute/backend/catalogue/create", $this->auth['permission']);
		if($this->input->post('create')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('title', 'Tiêu đề', 'trim|required');
			if($this->form_validation->run($this)){
				$_insert = array(
					'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'keyword' => slug(htmlspecialchars_decode(html_entity_decode($this->input->post('title')))),
					'description' => $this->input->post('description'),
					'image' => $this->input->post('image'),
					'publish' => $this->input->post('publish'),
					'userid_created' => $this->auth['id'],
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);
				$resultid = $this->Autoload_Model->_create(array(
					'table' => 'attribute_catalogue',
					'data' => $_insert,
				));
				if($resultid > 0){
					$this->session->set_flashdata('message-success', 'Thêm nhóm thuộc tính mới thành công');
					redirect('attribute/backend/catalogue/create');
				}
			}
		}
		
		$data['template'] = 'attribute/backend/catalogue/create';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function Update($id = 0){
		$data['script'] = 'attribute_catalogue';
		$this->commonbie->permission("attribute/backend/catalogue/update", $this->auth['permission']);
		$id = (int)$id;
		$detailCatalogue = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title,  description, image,  publish',
			'table' => 'attribute_catalogue',
			'where' => array('id' => $id),
		));
		if(!isset($detailCatalogue) || is_array($detailCatalogue) == false || count($detailCatalogue) == 0){
			$this->session->set_flashdata('message-danger', 'nhóm thuộc tính không tồn tại');
			redirect('attribute/backend/catalogue/view');
		}
		if($this->input->post('update')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('title', 'Tiêu đề', 'trim|required');
			if($this->form_validation->run($this)){
				$_update = array(
					'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'keyword' => slug(htmlspecialchars_decode(html_entity_decode($this->input->post('title')))),
					'description' => $this->input->post('description'),
					'image' => $this->input->post('image'),
					'publish' => $this->input->post('publish'),
					'userid_updated' => $this->auth['id'],
					'updated' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);
				$flag = $this->Autoload_Model->_update(array(
					'where' => array('id' => $id),
					'table' => 'attribute_catalogue',
					'data' => $_update,
				));
				if($flag > 0){
					$this->session->set_flashdata('message-success', 'Cập nhật nhóm thuộc tính thành công');
					redirect('attribute/backend/catalogue/update/'.$id.'');
				}
			}
		}
		
		$data['detailCatalogue'] = $detailCatalogue;
		$data['template'] = 'attribute/backend/catalogue/update';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
}
