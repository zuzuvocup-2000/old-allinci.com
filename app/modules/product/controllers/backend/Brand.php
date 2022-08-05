<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brand extends MY_Controller {

	public $module;
	function __construct() {
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library(array('configbie'));
	}
	
	public function view($page = 1){
		$this->commonbie->permission("product/backend/brand/view", $this->auth['permission']);
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
			'table' => 'product_brand',
			'keyword' => $keyword,
			'count' => TRUE,
		));
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('product/backend/brand/view');
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
			$data['listBrand'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id, title, image, viewed, canonical, publish, created, order, (SELECT fullname FROM user WHERE user.id = product_brand.userid_created) as user_created',
				'table' => 'product_brand',
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'keyword' => $keyword,
				'order_by' => 'created asc',
			), TRUE);
		}
		
		
		$data['script'] = 'product_brand';
		$data['config'] = $config;
		$data['template'] = 'product/backend/brand/view';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function Create(){
		$data['script'] = 'product_brand';
		$this->commonbie->permission("product/backend/brand/create", $this->auth['permission']);
		if($this->input->post('create')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('title', 'Tiêu đề thương hiệu', 'trim|required');
			$this->form_validation->set_rules('canonical', 'Đường dẫn thương hiệu', 'trim|required|callback__CheckCanonical');
			if($this->form_validation->run($this)){
				$_insert = array(
					'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'slug' => slug(htmlspecialchars_decode(html_entity_decode($this->input->post('title')))),
					'canonical' => slug($this->input->post('canonical')),
					'description' => $this->input->post('description'),
					'meta_title' => $this->input->post('meta_title'),
					'meta_description' => $this->input->post('meta_description'),
					'image' => $this->input->post('image'),
					'image_json' => json_encode($this->input->post('album')),
					'publish' => $this->input->post('publish'),
					'userid_created' => $this->auth['id'],
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);
				$resultid = $this->Autoload_Model->_create(array(
					'table' => 'product_brand',
					'data' => $_insert,
				));
				if($resultid > 0){
					$canonical = slug($this->input->post('canonical'));
					if(!empty($canonical)){
						$router = array(
							'canonical' => $canonical,
							'crc32' => sprintf("%u", crc32($canonical)),
							'uri' => 'product/frontend/brand/view',
							'param' => $resultid,
							'type' => 'number',
							'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
						);
						$routerid = $this->Autoload_Model->_create(array(
							'table' => 'router',
							'data' => $router,
						));
					}
					$this->session->set_flashdata('message-success', 'Thêm thương hiệu sản phẩm mới thành công');
					redirect('product/backend/brand/create');
				}
			}
		}
		
		$data['template'] = 'product/backend/brand/create';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function Update($id = 0){
		$data['script'] = 'product_brand';
		$this->commonbie->permission("product/backend/brand/update", $this->auth['permission']);
		$id = (int)$id;
		$detailbrand = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, slug, canonical, description, meta_title, meta_description, image, image_json, publish',
			'table' => 'product_brand',
			'where' => array('id' => $id),
		));
		if(!isset($detailbrand) || is_array($detailbrand) == false || count($detailbrand) == 0){
			$this->session->set_flashdata('message-danger', 'Danh mục sản phẩm không tồn tại');
			redirect('product/backend/brand/view');
		}
		if($this->input->post('update')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('title', 'Tiêu đềthương hiệu', 'trim|required');
			$this->form_validation->set_rules('canonical', 'Đường dẫnthương hiệu', 'trim|required|callback__CheckCanonical');
			if($this->form_validation->run($this)){
				$_update = array(
					'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'slug' => slug(htmlspecialchars_decode(html_entity_decode($this->input->post('title')))),
					'canonical' => slug($this->input->post('canonical')),
					'description' => $this->input->post('description'),
					'meta_title' => $this->input->post('meta_title'),
					'meta_description' => $this->input->post('meta_description'),
					'image' => $this->input->post('image'),
					'image_json' => json_encode($this->input->post('album')),
					'publish' => $this->input->post('publish'),
					'userid_created' => $this->auth['id'],
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);
				$flag = $this->Autoload_Model->_update(array(
					'where' => array('id' => $id),
					'table' => 'product_brand',
					'data' => $_update,
				));
				if($flag > 0){
					$canonical = slug($this->input->post('canonical'));
					if(!empty($canonical)){
						$this->Autoload_Model->_delete(array(
							'where' => array('canonical' => $detailbrand['canonical'],'uri' => 'product/frontend/brand/view','param' => $id),
							'table' => 'router',
						));
						$router = array(
							'canonical' => $canonical,
							'crc32' => sprintf("%u", crc32($canonical)),
							'uri' => 'product/frontend/brand/view',
							'param' => $id,
							'type' => 'number',
							'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
						);
						$routerid = $this->Autoload_Model->_create(array(
							'table' => 'router',
							'data' => $router,
						));
					}
					$this->session->set_flashdata('message-success', 'Cập nhậtthương hiệu sản phẩm thành công');
					redirect('product/backend/brand/update/'.$id.'');
				}
			}
		}
		
		
		
		$data['detailbrand'] = $detailbrand;
		$data['template'] = 'product/backend/brand/update';
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
