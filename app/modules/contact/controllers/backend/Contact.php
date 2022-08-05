<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends MY_Controller {

	public $module;
	public function __construct(){
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library(array('configbie'));
		// $this->load->library('nestedsetbie', array('table' => 'support'));
	}

	public function View($page = 1){
		$page = (int)$page;
		$data['from'] = 0;
		$data['to'] = 0;

		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 5;
		$keyword = $this->input->get('keyword');
		$catalogueid = (int)$this->input->get('catalogueid');
		if(!empty($keyword)){
			$keyword = '(title LIKE \'%'.$keyword.'%\')';
		}
		$config['total_rows'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id',
			'table' => 'contact',
			'count' => TRUE,
			'where' => ($catalogueid ==0) ? '' : array( 'catalogueid' => $catalogueid) ,
			'keyword' => '(fullname LIKE \'%'.$keyword.'%\')',
		));
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('contact/backend/contact/view');
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

			$data['listContact'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id, file, viewed, catalogueid, fullname, email, phone, created, subject, message, bookmark, (SELECT title FROM contact_catalogue WHERE contact_catalogue.id = contact.catalogueid) as catalogueTitle',
				'table' => 'contact',
				'where' => ($catalogueid ==0) ? '' : array( 'catalogueid' => $catalogueid) ,
				'keyword' => '(fullname LIKE \'%'.$keyword.'%\')',
				'start' => $page * $config['per_page'],
				'limit' => $config['per_page'],
				'order_by' => 'viewed asc, created desc',
			), TRUE);
		}

		$data['script'] = 'contact';
		$data['config'] = $config;
		$data['template'] = 'contact/backend/contact/view';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}

	public function ViewDetail($id = 0){
		$data['contact'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id, fullname, email, phone, created, subject, message, file',
			'table' => 'contact',
			'order_by' => 'created asc',
			'where' => array('id' => $id),
		), TRUE);

		foreach ($data['contact'] as $key => $val) {
			$data['contact'][$key]['file'] = json_decode(base64_decode($val['file']), TRUE);
		}

		
		$data['script'] = 'contact';
		$data['template'] = 'contact/backend/contact/viewDetail';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}

	
}