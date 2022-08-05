<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogue extends MY_Controller {

	public $module;
	function __construct() {
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library(array('configbie'));
		$this->load->library('nestedsetbie', array('table' => 'product_catalogue'));
	}
	
	public function view($page = 1){
		$this->commonbie->permission("product/backend/catalogue/view", $this->auth['permission']);
		$page = (int)$page;
		$data['from'] = 0;
		$data['to'] = 0;
		
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 300;
		$keyword = $this->input->get('keyword');
		if(!empty($keyword)){
			$keyword = '(title LIKE \'%'.$keyword.'%\' OR description LIKE \'%'.$keyword.'%\')';
		}
		$config['total_rows'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id',
			'table' => 'product_catalogue',
			'keyword' => $keyword,
			'count' => TRUE,
		));
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('product/backend/catalogue/view');
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
				'select' => 'id, title, canonical, image, icon, publish, ishome, highlight, created, order, level, lft, rgt, (SELECT fullname FROM user WHERE user.id = product_catalogue.userid_created) as user_created',
				'table' => 'product_catalogue',
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'keyword' => $keyword,
				'order_by' => 'lft asc',
			), TRUE);
		}
		
		
		$data['script'] = 'product_catalogue';
		$data['config'] = $config;
		$data['template'] = 'product/backend/catalogue/view';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function Create(){
		$data['script'] = 'product_catalogue';
		$this->commonbie->permission("product/backend/catalogue/create", $this->auth['permission']);
		$data['brand'] = json_encode($this->input->post('brand[]'));
		$data['article'] = json_encode($this->input->post('article[]'));
		if($this->input->post('create')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('title', 'Tiêu đề', 'trim|required');
			$this->form_validation->set_rules('canonical', 'Đường dẫn', 'trim|required|callback__CheckCanonical');
			if($this->form_validation->run($this)){
				$_insert = array(
					'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'slug' => slug(htmlspecialchars_decode(html_entity_decode($this->input->post('title')))),
					'canonical' => slug($this->input->post('canonical')),
					'description' => $this->input->post('description'),
					'meta_title' => $this->input->post('meta_title'),
					'meta_description' => $this->input->post('meta_description'),
					'parentid' => $this->input->post('parentid'),
					'image' => $this->input->post('image'),
					'image_json' => json_encode($this->input->post('album')),
					'publish' => $this->input->post('publish'),
					'userid_created' => $this->auth['id'],
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
					// 'brand_json' => $data['brand'],
					'article_json' =>$data['article'],
					'icon' => $this->input->post('icon'),
					'landing_link' => $this->input->post('landing_link'),
				);
				$resultid = $this->Autoload_Model->_create(array(
					'table' => 'product_catalogue',
					'data' => $_insert,
				));
				if($resultid > 0){
					// thêm vào catalogue_relationship
					$brandid = $this->input->post('brand');

					if(isset($brandid) && is_array($brandid) && count($brandid)){
						foreach ($brandid as $key => $value) {
							createCatalogue_relationship(array(
								'module' => 'product_brand',
								'resultid' => $value ,
								'catalogueid' => $resultid,
							));
						}
					}

					$canonical = slug($this->input->post('canonical'));
					createCanonical(array(
						'module' => 'product_catalogue',
						'canonical' => $canonical,
						'resultid' => $resultid,
					));

					$this->nestedsetbie->Get('level ASC, order ASC');
					$this->nestedsetbie->Recursive(0, $this->nestedsetbie->Set());
					$this->nestedsetbie->Action();
					$this->session->set_flashdata('message-success', 'Thêm loại sản phẩm mới thành công');
					redirect('product/backend/catalogue/view');
				}
			}
		}
		
		$data['template'] = 'product/backend/catalogue/create';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function Update($id = 0){
		$data['script'] = 'product_catalogue';
		$this->commonbie->permission("product/backend/catalogue/update", $this->auth['permission']);
		$id = (int)$id;
		$detailCatalogue = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, slug, canonical, description, meta_title, meta_description, parentid, image, image_json, publish, brand_json, icon,article_json, landing_link',
			'table' => 'product_catalogue',
			'where' => array('id' => $id),
		));
		$brand = $this->input->post('brand');
		if(isset($brand) && is_array($brand) && count($brand)){
			$data['brand'] = json_encode($brand);
		}else{
			$data['brand'] = $detailCatalogue['brand_json'];
		}
		$article = $this->input->post('article');
		if(isset($article) && is_array($article) && count($article)){
			$data['article'] = json_encode($article);
		}else{
			$data['article'] = $detailCatalogue['article_json'];
		}

		if(!isset($detailCatalogue) || is_array($detailCatalogue) == false || count($detailCatalogue) == 0){
			$this->session->set_flashdata('message-danger', 'Danh mục sản phẩm không tồn tại');
			redirect('product/backend/catalogue/view');
		}
		if($this->input->post('update')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('title', 'Tiêu đề', 'trim|required');
			$this->form_validation->set_rules('canonical', 'Đường dẫn', 'trim|required|callback__CheckCanonical');
			if($this->form_validation->run($this)){
				$_update = array(
					'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'slug' => slug(htmlspecialchars_decode(html_entity_decode($this->input->post('title')))),
					'canonical' => slug($this->input->post('canonical')),
					'description' => $this->input->post('description'),
					'meta_title' => $this->input->post('meta_title'),
					'meta_description' => $this->input->post('meta_description'),
					'parentid' => $this->input->post('parentid'),
					'image' => $this->input->post('image'),
					'image_json' => json_encode($this->input->post('album')),
					'publish' => $this->input->post('publish'),
					'userid_created' => $this->auth['id'],
					'updated' => gmdate('Y-m-d H:i:s', time() + 7*3600),
					// 'brand_json' => $data['brand'],
					'article_json' => $data['article'],
					'icon' => $this->input->post('icon'),
					'landing_link' => $this->input->post('landing_link'),
				);
				$flag = $this->Autoload_Model->_update(array(
					'where' => array('id' => $id),
					'table' => 'product_catalogue',
					'data' => $_update,
				));
				if($flag > 0){

					$brandid = $this->input->post('brand');
					$this->Autoload_Model->_delete(array(
						'where' => array('module' => 'product_brand', 'catalogueid' => $id),
						'table' => 'catalogue_relationship',
					));
					if(isset($brandid) && is_array($brandid) && count($brandid)){
						foreach ($brandid as $key => $value) {
							createCatalogue_relationship(array(
								'module' => 'product_brand',
								'resultid' => $value ,
								'catalogueid' => $id,
							));
						}
					}


					$canonical = slug($this->input->post('canonical'));
					if(!empty($canonical)){
						$this->Autoload_Model->_delete(array(
							'where' => array('canonical' => $detailCatalogue['canonical'],'param' => $id),
							'table' => 'router',
						));
						createCanonical(array(
							'module' => 'product_catalogue',
							'canonical' => $canonical,
							'resultid' => $id,
						));
					}
					$this->nestedsetbie->Get('level ASC, order ASC');
					$this->nestedsetbie->Recursive(0, $this->nestedsetbie->Set());
					$this->nestedsetbie->Action();
					$this->session->set_flashdata('message-success', 'Cập nhật loại sản phẩm thành công');
					redirect('product/backend/catalogue/view');
				}
			}
		}
		
		
		
		$data['detailCatalogue'] = $detailCatalogue;
		$data['template'] = 'product/backend/catalogue/update';
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
