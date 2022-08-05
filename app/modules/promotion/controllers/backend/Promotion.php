<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promotion extends MY_Controller {

	public $module;
	function __construct() {
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library(array('configbie'));
		$this->module = 'promotion';
	}

	public function view($page = 1){
		$this->commonbie->permission("promotion/backend/promotion/view", $this->auth['permission']);
		$page = (int)$page;
		$data['from'] = 0;
		$data['to'] = 0;

		$extend = (!in_array('promotion/backend/promotion/viewall', json_decode($this->auth['permission'], TRUE))) ? 'userid_created = '.$this->auth['id'].'' : '';


		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 20;
		$keyword = $this->db->escape_like_str($this->input->get('keyword'));
		$catalogueid = (int)$this->input->get('catalogueid');
		if($catalogueid > 0){
			$config['total_rows'] = $this->Autoload_Model->_condition(array(
				'module' => 'promotion',
				'select' => '`object`.`id`',
				'where' => ((!empty($extend)) ? '`object`.`userid_created` = '.$this->auth['id'].'' : ''),
				'keyword' => '(`object`.`title` LIKE \'%'.$keyword.'%\' OR `object`.`description` LIKE \'%'.$keyword.'%\')',
				'catalogueid' => $catalogueid,
				'count' => TRUE
			));

		}else{
			$config['total_rows'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id',
				'table' => 'promotion',
				'where_extend' => $extend,
				'keyword' => '(title LIKE \'%'.$keyword.'%\' OR description LIKE \'%'.$keyword.'%\')',
				'count' => TRUE,
			));
		}

		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('promotion/backend/promotion/view');
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
			$data['listObject'] = $this->Autoload_Model->_get_where(array(
				'select' => '*',
				'table' => 'promotion',
				'where_extend' => $extend,
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'keyword' => $keyword,
				'order_by' => 'id desc, title asc',
			), TRUE);
		}
		$data['script'] = 'promotion';
		$data['config'] = $config;
		$data['template'] = 'promotion/backend/promotion/view';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}

	public function view2($page = 1){
		$this->commonbie->permission("promotion/backend/promotion/view", $this->auth['permission']);
		$page = (int)$page;
		$data['from'] = 0;
		$data['to'] = 0;

		$extend = (!in_array('promotion/backend/promotion/viewall', json_decode($this->auth['permission'], TRUE))) ? 'userid_created = '.$this->auth['id'].'' : '';


		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 20;
		$keyword = $this->db->escape_like_str($this->input->get('keyword'));
		$catalogueid = (int)$this->input->get('catalogueid');
		if($catalogueid > 0){
			$config['total_rows'] = $this->Autoload_Model->_condition(array(
				'module' => 'promotion',
				'select' => '`object`.`id`',
				'where' => ((!empty($extend)) ? '`object`.`userid_created` = '.$this->auth['id'].'' : ''),
				'keyword' => '(`object`.`title` LIKE \'%'.$keyword.'%\' OR `object`.`description` LIKE \'%'.$keyword.'%\')',
				'catalogueid' => $catalogueid,
				'count' => TRUE
			));

		}else{
			$config['total_rows'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id',
				'table' => 'promotion',
				'where_extend' => $extend,
				'keyword' => '(title LIKE \'%'.$keyword.'%\' OR description LIKE \'%'.$keyword.'%\')',
				'count' => TRUE,
			));
		}

		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('promotion/backend/promotion/view');
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
			$data['listObject'] = $this->Autoload_Model->_get_where(array(
				'select' => '*',
				'table' => 'promotion',
				'where_extend' => $extend,
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'keyword' => $keyword,
				'order_by' => 'start_date asc, title asc',
			), TRUE);
		}
		$data['script'] = 'promotion';
		$data['config'] = $config;
		$data['template'] = 'promotion/backend/promotion/view2';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}

	public function Create(){
		$this->commonbie->permission("promotion/backend/promotion/create", $this->auth['permission']);
		if($this->input->post('create')){
			$album = $this->input->post('album');


			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('title', 'Tiêu đề promotion', 'trim|required');
			$this->form_validation->set_rules('catalogueid', 'Danh mục chính', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('canonical', 'Đường dẫn promotion', 'trim|required|callback__CheckCanonical');
			if($this->form_validation->run($this)){
				$_insert = array(
					'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'meta_title' => $this->input->post('meta_title'),
					'meta_description' => $this->input->post('meta_description'),
					'publish' => $this->input->post('publish'),
					'userid_created' => $this->auth['id'],
					'start_date' => merge_time($this->input->post('start_date'), $this->input->post('start_time')),
					'end_date' => merge_time($this->input->post('end_date'), $this->input->post('end_time')),
					// 'end_date' => convert_time($this->input->post('end_date'), '-'),
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
					'album' => json_encode($album),
					'publish' => 0,
					'description' => $this->input->post('description'),
				);

				$resultid = $this->Autoload_Model->_create(array(
					'table' => 'promotion',
					'data' => $_insert,
				));
				if($resultid > 0){
					$this->session->set_flashdata('message-success', 'Thêm promotion mới thành công');
					redirect('promotion/backend/promotion/view2');
				}
			}
		}
		$data['script'] = 'promotion';
		$data['template'] = 'promotion/backend/promotion/create';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}

	public function Update($id = 0){
		$this->commonbie->permission("promotion/backend/promotion/update", $this->auth['permission']);
		$id = (int)$id;
		$detailObject = $this->Autoload_Model->_get_where(array(
			'select' => '*',
			'table' => 'promotion',
			'where' => array('id' => $id),
		));

		if(!isset($detailObject) || is_array($detailObject) == false || count($detailObject) == 0){
			$this->session->set_flashdata('message-danger', 'promotion không tồn tại');
			redirect('promotion/backend/promotion/view2');
		}
		if($this->input->post('update')){
			$album = $this->input->post('album');

			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('title', 'Tiêu đề promotion', 'trim|required');
			if($this->form_validation->run($this)){
				$_update = array(
					'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'meta_title' => $this->input->post('meta_title'),
					'meta_description' => $this->input->post('meta_description'),
					'publish' => $this->input->post('publish'),
					'userid_created' => $this->auth['id'],
					// 'start_date' => convert_time($this->input->post('start_date'), '-'),
					// 'end_date' => convert_time($this->input->post('end_date'), '-'),
					'start_date' => merge_time($this->input->post('start_date'), $this->input->post('start_time')),
					'end_date' => merge_time($this->input->post('end_date'), $this->input->post('end_time')),
					'updated' => gmdate('Y-m-d H:i:s', time() + 7*3600),
					'album' => json_encode($album),
					'publish' => 0,
					'description' => $this->input->post('description'),				);

				$flag = $this->Autoload_Model->_update(array(
					'where' => array('id' => $id),
					'table' => 'promotion',
					'data' => $_update,
				));
				if($flag > 0){

					$this->session->set_flashdata('message-success', 'Cập nhật promotion thành công');
					redirect('promotion/backend/promotion/view');
				}
			}
		}

		$data['script'] = 'promotion';
		$data['detailObject'] = $detailObject;
		$data['template'] = 'promotion/backend/promotion/update';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}

	public function _CheckCanonical($canonical = ''){

		$originalCanonical = $this->input->post('original_canonical');
		if($canonical != $originalCanonical){
			$crc32 = sprintf("%u", crc32(slug($canonical)));
			$router = $this->Autoload_Model->_get_where(array(
				'select' => 'id',
				'table' => 'router',
				'where' => array('crc32' => $crc32),
				'count' => TRUE
			));
			if($router > 0){
				$this->form_validation->set_message('_CheckCanonical','Đường dẫn đã tồn tại, hãy chọn một đường dẫn khác');
				return false;
			}
		}
		return true;
	}
}
