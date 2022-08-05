<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogue extends MY_Controller {

	public $module;
	public function __construct(){
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library(array('configbie'));
		// $this->load->library('nestedsetbie', array('table' => 'support_catalogue'));
	}

	public function View($page = 1){
		$page = (int)$page;
		$data['from'] = 0;
		$data['to'] = 0;
		
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 30;
		$keyword = $this->input->get('keyword');
		$config['total_rows'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id',
			'table' => 'support_catalogue',
			'count' => TRUE,
			// 'keyword' => '(title LIKE \'%'.$keyword.'%\')',
			'keyword' => $keyword,
		));
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('support/backend/catalogue/view');
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
				'select' => 'id, title, canonical, publish, created,(SELECT fullname FROM user WHERE user.id = support_catalogue.userid_created) as user_created',
				'table' => 'support_catalogue',
				'start' => $page * $config['per_page'],
				'limit' => $config['per_page'],
				'order_by' => ' title asc',
				// 'keyword' => '(title LIKE \'%'.$keyword.'%\')',
				'keyword' => $keyword,
			), TRUE);
			foreach ($data['listCatalogue']as $key => $val) {
				$data['listCatalogue'][$key]['countSupport'] = $this->Autoload_Model->_get_where(array(
					'select' => 'id',
					'table' => 'support',
					'count' => TRUE,
					'where' => array('catalogueid' => $val['id']),
				));
			}
		}
		$data['script'] = 'support_catalogue';
		$data['config'] = $config;
		$data['template'] = 'support/backend/catalogue/view';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}

	public function Create(){
		if($this->input->post('create')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('title', 'Nhóm hỗ trợ', 'trim|required');
			$this->form_validation->set_rules('canonical', 'Từ khóa', 'trim|required|callback__CheckCanonical');
			if($this->form_validation->run($this)){
				$_insert = array(
					'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'canonical' => slug(htmlspecialchars_decode(html_entity_decode($this->input->post('title')))),
					'publish' => $this->input->post('publish'),
					'userid_created' => $this->auth['id'],
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);	
				$resultid = $this->Autoload_Model->_create(array(
					'table' => 'support_catalogue',
					'data' => $_insert,
				));
				if($resultid > 0){
					$this->session->set_flashdata('message-success', 'Thêm mới nhóm hỗ trợ thành công');
					redirect('support/backend/catalogue/view');
				}
			}
		}
		$data['script'] = 'support_catalogue';
		$data['template'] = 'support/backend/catalogue/create';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}

	public function Update($id = 0){
		$id = (int)$id;
		$detailCatalogue = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, canonical, publish',
			'table' => 'support_catalogue',
			'where' => array('id' => $id),

		));
		if(!isset($detailCatalogue) || is_array($detailCatalogue) == false || count($detailCatalogue) == 0){
			$this->session->set_flashdata('message-danger', 'Nhóm hỗ trợ không tồn tại');
			redirect('support/backend/catalogue/view');
		}
		if($this->input->post('update')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('',' /');
			$this->form_validation->set_rules('title', 'Nhóm hỗ trợ', 'trim|required');
			$this->form_validation->set_rules('canonical', 'Từ khóa', 'trim|required|callback__CheckUpdate');
			if($this->form_validation->run($this)){
				$_update = array(
					'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'canonical' => $this->input->post('canonical'),
					'publish' => $this->input->post('publish'),
				);

				$flag = $this->Autoload_Model->_update(array(
					'where' => array('id' => $id),
					'table' => 'support_catalogue',
					'data' => $_update,
				));
				if($flag > 0){
					$this->session->set_flashdata('message-success', 'Cập nhật Nhóm hỗ trợ thành công');
					redirect('support/backend/catalogue/update/'.$id.'');
				}
			}
		}
		$data['detailCatalogue'] = $detailCatalogue;
		$data['template'] = 'support/backend/catalogue/update';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}

	public function _CheckCanonical($canonical =''){
		$count = $this->Autoload_Model->_get_where(array(
			'select' => 'id',
			'table' => 'support_catalogue',
			'where' => array('canonical' => $canonical),
			'count' => true,
		));
		if($count >0){
			$this->form_validation->set_message('_CheckCanonical', 'Từ khóa đã tồn tại, vui lòng thử lại');
			return false;
		}
		return true;
	}

	public function _CheckUpdate($canonical = ''){
		$originalKeyword = $this->input->post('originalKeyword');
		if($originalKeyword != $canonical){
			$count = $this->Autoload_Model->_get_where(array(
				'select' => 'id',
				'table' => 'support_catalogue',
				'where' => array('canonical' => $canonical),
				'count' => true,
			));
			if($count >0){
				$this->form_validation->set_message('_CheckUpdate', 'Từ khóa đã tồn tại, vui lòng thử lại');
				return false;
			}
		}
		return true;
	}
}