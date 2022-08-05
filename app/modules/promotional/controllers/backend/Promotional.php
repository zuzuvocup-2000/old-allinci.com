<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promotional extends MY_Controller {
	public $module;
	function __construct() {
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library(array('configbie'));
		$this->load->helper('mypromotional');

	}
	
	public function view($page = 1){
		$this->commonbie->permission("promotional/backend/promotional/view", $this->auth['permission']);
		$page = (int)$page;
		$data['from'] = 0;
		$data['to'] = 0;
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 20;
		$keyword = $this->db->escape_like_str($this->input->get('keyword'));
		$config['total_rows'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id',
			'table' => 'promotional',
			'keyword' => '(title LIKE \'%'.$keyword.'%\' OR description LIKE \'%'.$keyword.'%\')',
			'count' => TRUE,
		));
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('promotional/backend/promotional/view');
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
			
			$data['listpromotional'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id,  catalogue, title, canonical, created, image_json, (SELECT fullname FROM user WHERE user.id = promotional.userid_created) as user_created, module, start_date, end_date, publish, discount_type, discount_value, condition_value, condition_type, freeship, freeshipAll, condition_value_1, condition_type_1, use_common, code, limmit_code, cityid, discount_moduleid, hightlight',
				'table' => 'promotional',
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'keyword' => $keyword,
				'order_by' => ' id desc, title asc',
			), TRUE);	
			foreach ($data['listpromotional'] as $key => $value) {
				$promotional1 = json_decode(getPromotional($value), true);
				$data['listpromotional'][$key] = $value;
				$data['listpromotional'][$key]['use_common'] = $promotional1['use_common'];
				$data['listpromotional'][$key]['detail'] = $promotional1['detail'];
			}
		}
		$data['script'] = 'promotional';
		$data['config'] = $config;
		$data['template'] = 'promotional/backend/promotional/view';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function Create(){
		$this->commonbie->permission("promotional/backend/promotional/create", $this->auth['permission']);
		if($this->input->post('create')){
			
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$catalogue = $this->input->post('catalogue');
			if($catalogue == 'CP'){
				$this->form_validation->set_rules('code', 'Mã coupon', 'trim|required|exact_length[6]');
			}
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('title', 'Tiêu đề khuyến mại', 'trim|required');
			$this->form_validation->set_rules('canonical', 'Đường dẫn chương trình KM', 'trim|required|callback__CheckCanonical');
			if($this->form_validation->run($this)){
				$_insert = setPromotional($this->auth['id']);
				$resultid = $this->Autoload_Model->_create(array(
					'table' => 'promotional',
					'data' => $_insert,
				));
				if($resultid > 0){
					insertPromotionalRelationship($_insert , $resultid);
					if($catalogue == 'KM'){
						// tạo đường dẫn cho CT khuyến mại
						$canonical = slug($this->input->post('canonical'));
						createCanonical(array(
							'module' => 'promotional',
							'canonical' => $canonical,
							'resultid' => $resultid,
						));
					}
					$this->session->set_flashdata('message-success', 'Thêm chương trình KM mới thành công');
					redirect('promotional/backend/promotional/view');
				}
			}
		}
		$data['script'] = 'promotional';
		$data['template'] = 'promotional/backend/promotional/create';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function Update($id = 0){

		$this->commonbie->permission("promotional/backend/promotional/create", $this->auth['permission']);

		$id = (int)$id;
		$data['promotional'] = $this->Autoload_Model->_get_where(array(
			'table' => 'promotional',
			'where' => array('id' => $id),
			'select' => '*',
		));

		if(!isset($data['promotional']) || is_array($data['promotional']) == false || count($data['promotional']) == 0){
			$this->session->set_flashdata('message-danger', 'Chương trình KM không tồn tại');
			redirect('promotional/backend/promotional/view');
		}
		if($this->input->post('update')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$catalogue = $this->input->post('catalogue');
			if($catalogue == 'CP'){
				$this->form_validation->set_rules('code', 'Mã coupon', 'trim|required|exact_length[6]');
			}
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('title', 'Tiêu đề khuyến mại', 'trim|required');
			$this->form_validation->set_rules('canonical', 'Đường dẫn chương trình KM', 'trim|required|callback__CheckCanonical');
			if($this->form_validation->run($this)){
				$_update = setPromotional($this->auth['id']);
				$resultid = $this->Autoload_Model->_update(array(
					'where' => array('id' => $id),
					'table' => 'promotional',
					'data' => $_update,
				));
				if($resultid > 0){
					$this->Autoload_Model->_delete(array(
						'where' => array('promotionalid' => $id),
						'table' => 'promotional_relationship',
					));
					$this->Autoload_Model->_delete(array(
						'where' => array('promotionalid' => $id),
						'table' => 'promoship_relationship',
					));
					insertPromotionalRelationship($_update , $id);
					if($catalogue == 'KM'){
						// tạo đường dẫn cho sản phẩm
						$this->Autoload_Model->_delete(array(
							'where' => array('canonical' => $data['promotional']['canonical'],'uri' => 'promotional/frontend/promotional/view','param' => $id),
							'table' => 'router',
						));
						$canonical = slug($this->input->post('canonical'));
						createCanonical(array(
							'module' => 'promotional',
							'canonical' => $canonical,
							'resultid' => $id,
						));
					}
					
					$this->session->set_flashdata('message-success', 'Cập nhật chương trình KM mới thành công');
					redirect('promotional/backend/promotional/view');
				}
			}
		}
		$data['script'] = 'promotional';
		$data['template'] = 'promotional/backend/promotional/update';
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
