<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Layout extends MY_Controller {

	public $module;
	function __construct() {
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library(array('configbie'));
	}
	public function refreshSidemap(){
		refreshSidemap();
		$this->session->set_flashdata('message-success', 'Cập nhật SiteMap thành công');
		redirect('dashboard/home/index');
	}
	public function view($page = 1){
		$this->commonbie->permission("layout/backend/layout/view", $this->auth['permission']);
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
			'table' => 'layout',
			'keyword' => $keyword,
			'count' => TRUE,
		));
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('layout/backend/layout/view');
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
			$data['listLayout'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id, title, publish, (SELECT title FROM layout_catalogue WHERE layout_catalogue.id = layout.catalogueid) as catalogue_title',
				'table' => 'layout',
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'keyword' => $keyword,
				'order_by' => 'id asc',
			), TRUE);
		}
		
		
		$data['script'] = 'layout';
		$data['config'] = $config;
		$data['template'] = 'layout/backend/layout/view';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function Create(){
		$parentid = (int)$this->input->get('parentid');
		$this->commonbie->permission("layout/backend/layout/create", $this->auth['permission']);
		if($this->input->post('create')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('catalogueid', 'Vị trí', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('title', 'Tiêu đề', 'trim|required');
			
			if($this->form_validation->run($this)){
				$layout = $this->input->post('layout');
				$_insert = '';
				$temp = '';
				$resultid = 0;
				if(isset($layout['title']) && is_array($layout['title']) && count($layout['title']) && $layout['title'][0] != '' && isset($layout['object']) && is_array($layout['object']) && count($layout['object']) && $layout['object'][0] != '' ){
					foreach($layout['title'] as $key => $val){
						$temp[] = array(
							'title' => $val,
							'module' => $layout['module'][$key],
							'object' => $layout['object'][$key][$layout['module'][$key]],
						);
					}
				}
				if(isset($temp) && is_array($temp) && count($temp)){
					$_insert = array(
						'title' => $this->input->post('title'),
						'catalogueid' => $this->input->post('catalogueid'),
						'data_json' => json_encode($temp),
						'data_original' => json_encode($layout),
					);	
				}else{
					$_insert = array(
						'title' => $this->input->post('title'),
						'catalogueid' => $this->input->post('catalogueid'),
					);	
				}
				if(isset($_insert) && is_array($_insert) && count($_insert)){
					$resultid = $this->Autoload_Model->_create(array(
						'data' => $_insert,
						'table' => 'layout',
					));
				}
				if($resultid > 0){
					$this->session->set_flashdata('message-success', 'Cập nhật layout mới thành công');
					redirect('layout/backend/layout/view');
				}
			}
		}
		$data['script'] = 'layout';
		$data['template'] = 'layout/backend/layout/create';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	
	public function Update($id = 0){
		$this->commonbie->permission("layout/backend/layout/create", $this->auth['permission']);
		
		$id = (int)$id;
		$detailLayout = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, catalogueid, data_json, data_original',
			'table' => 'layout',
			'where' => array('id' => $id),
		));
		
		if(!isset($detailLayout) || is_array($detailLayout) == false || count($detailLayout) == 0){
			$this->session->set_flashdata('message-danger', 'Vị trí Menu không tồn tại');
			redirect('layout/backend/layout/view');
		}
		
		if($this->input->post('update')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('catalogueid', 'Vị trí', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('title', 'Tiêu đề', 'trim|required');
			if($this->form_validation->run($this)){
				$layout = $this->input->post('layout');
				$_update = '';
				$temp = '';
				$resultid = 0;
				if(isset($layout['title']) && is_array($layout['title']) && count($layout['title']) && $layout['title'][0] != '' && isset($layout['object']) && is_array($layout['object']) && count($layout['object']) && $layout['object'][0] != '' ){
					foreach($layout['title'] as $key => $val){
						$object = array_values($layout['object']);
						$layout['object'] = $object;
						$temp[] = array(
							'title' => $val,
							'module' => $layout['module'][$key],
							'object' => $object[$key][$layout['module'][$key]],
						);
						
					}
				}
				
				if(isset($temp) && is_array($temp) && count($temp)){
					$_update = array(
						'title' => $this->input->post('title'),
						'catalogueid' => $this->input->post('catalogueid'),
						'data_json' => json_encode($temp),
						'data_original' => json_encode($layout),
					);	
				}else{
					$_update = array(
						'title' => $this->input->post('title'),
						'catalogueid' => $this->input->post('catalogueid'),
						'data_json' => '',
						'data_original' => '',
					);	
				}
				
				if(isset($_update) && is_array($_update) && count($_update)){
					$resultid = $this->Autoload_Model->_update(array(
						'where' => array('id' => $id),
						'data' => $_update,
						'table' => 'layout',
					));
				}
				if($resultid > 0){
					$this->session->set_flashdata('message-success', 'Cập nhật layout mới thành công');
					redirect('layout/backend/layout/view');
				}
			}
		}
		$data['detailLayout'] = $detailLayout;
		$data['script'] = 'layout';
		$data['template'] = 'layout/backend/layout/update';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	
}
