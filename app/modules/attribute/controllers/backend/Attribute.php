<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attribute extends MY_Controller {

	public $module;
	function __construct() {
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library(array('configbie'));
		$this->load->library('nestedsetbie', array('table' => 'attribute_catalogue'));
	}
	
	public function view($page = 1){
		$this->commonbie->permission("attribute/backend/attribute/view", $this->auth['permission']);
		$page = (int)$page;
		$data['from'] = 0;
		$data['to'] = 0;
		
		$extend = (!in_array('attribute/backend/attribute/viewall', json_decode($this->auth['permission'], TRUE))) ? 'userid_created = '.$this->auth['id'].'' : '';
		
		
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 20;
		$keyword = $this->db->escape_like_str($this->input->get('keyword'));
		$catalogueid = (int)$this->input->get('catalogueid');
		if($catalogueid > 0){
			$config['total_rows'] = $this->Autoload_Model->_condition(array(
				'module' => 'attribute',
				'select' => '`object`.`id`',
				'where' => ((!empty($extend)) ? '`object`.`userid_created` = '.$this->auth['id'].'' : ''),
				'keyword' => '(`object`.`title` LIKE \'%'.$keyword.'%\' AND `object`.`description` LIKE \'%'.$keyword.'%\')',
				'catalogueid' => $catalogueid,
				'count' => TRUE
			));
		}else{
			$config['total_rows'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id',
				'table' => 'attribute',
				'where_extend' => $extend,
				'keyword' => '(title LIKE \'%'.$keyword.'%\' OR description LIKE \'%'.$keyword.'%\')',
				'count' => TRUE,
			));
		}
		
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('attribute/backend/attribute/view');
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
			if($catalogueid > 0){
				$data['listattribute'] = $this->Autoload_Model->_condition(array(
					'module' => 'attribute',
					'select' => '`object`.`id`, `object`.`title`, `object`.`color`, `object`.`slug`,`object`.`canonical`, `object`.`catalogueid`, `object`.`catalogue`, `object`.`publish`, `object`.`image`, `object`.`created`, `object`.`order`, (SELECT fullname FROM user WHERE user.id = object.userid_created) as user_created, (SELECT title FROM attribute_catalogue WHERE attribute_catalogue.id = object.catalogueid) as catalogue_title',
					'where' => ((!empty($extend)) ? '`object`.`userid_created` = '.$this->auth['id'].'' : ''),
					'keyword' => '(`object`.`title` LIKE \'%'.$keyword.'%\' AND `object`.`description` LIKE \'%'.$keyword.'%\')',
					'catalogueid' => $catalogueid,
					'limit' => $perpage,
					'order_by' => '`object`.`order` desc, `object`.`title` asc, `object`.`id` desc',
				));
			}else{
				$data['listattribute'] = $this->Autoload_Model->_get_where(array(
					'select' => 'id, catalogueid,color,canonical,  title, publish, created, order, image, (SELECT fullname FROM user WHERE user.id = attribute.userid_created) as user_created, (SELECT title FROM attribute_catalogue WHERE attribute_catalogue.id = attribute.catalogueid) as catalogue_title',
					'table' => 'attribute',
					'where_extend' => $extend,
					'limit' => $config['per_page'],
					'start' => $page * $config['per_page'],
					'keyword' => $keyword,
					'order_by' => 'order desc, id desc, title asc',
				), TRUE);	
			}
		}
		$data['script'] = 'attribute';
		$data['config'] = $config;
		$data['template'] = 'attribute/backend/attribute/view';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function Create(){
		$this->commonbie->permission("attribute/backend/attribute/create", $this->auth['permission']);
		if($this->input->post('create')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('title', 'Tiêu đề thuộc tính', 'trim|required');
			$this->form_validation->set_rules('catalogueid', 'Danh mục chính', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('canonical', 'Đường dẫn thuộc tính', 'trim|required|callback__CheckCanonical');
			if($this->input->post('catalogueid') == 7){
				$this->form_validation->set_rules('start_price', 'Giá bắt đầu', 'trim|required');
				$this->form_validation->set_rules('end_price', 'Giá cuối', 'trim|required');
			}
			if($this->form_validation->run($this)){
				$_insert = array(
					'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'slug' => slug(htmlspecialchars_decode(html_entity_decode($this->input->post('title')))),
					'canonical' => slug($this->input->post('canonical')),
					'description' => $this->input->post('description'),
					'color' => $this->input->post('color'),
					'start_price' => (int)str_replace('.','',$this->input->post('start_price')),
					'end_price' => (int)str_replace('.','',$this->input->post('end_price')),
					'catalogueid' => $this->input->post('catalogueid'),
					'publish' => $this->input->post('publish'),
					'image' => $this->input->post('image'),
					'meta_title' => $this->input->post('meta_title'),
					'meta_description' => $this->input->post('meta_description'),
					'userid_created' => $this->auth['id'],
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);
				$resultid = $this->Autoload_Model->_create(array(
					'table' => 'attribute',
					'data' => $_insert,
				));
				if($resultid > 0){
					$canonical = slug($this->input->post('canonical'));
					if(!empty($canonical)){
						$router = array(
							'canonical' => $canonical,
							'crc32' => sprintf("%u", crc32($canonical)),
							'uri' => 'attribute/frontend/attribute/view',
							'param' => $resultid,
							'type' => 'number',
							'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
						);
						$routerid = $this->Autoload_Model->_create(array(
							'table' => 'router',
							'data' => $router,
						));
					}
					
					$this->session->set_flashdata('message-success', 'Thêm thuộc tính mới thành công');
					redirect('attribute/backend/attribute/view');
				}
			}
		}
		$data['script'] = 'attribute';
		$data['template'] = 'attribute/backend/attribute/create';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function Update($id = 0){
		$this->commonbie->permission("attribute/backend/attribute/update", $this->auth['permission']);
		$id = (int)$id;
		$detailattribute = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, slug, color, canonical, description, meta_title, catalogueid, meta_description, image, publish, start_price, end_price',
			'table' => 'attribute',
			'where' => array('id' => $id),
		));
		if(!isset($detailattribute) || is_array($detailattribute) == false || count($detailattribute) == 0){
			$this->session->set_flashdata('message-danger', 'thuộc tính không tồn tại');
			redirect('attribute/backend/attribute/view');
		}
		if($this->input->post('update')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('title', 'Tiêu đề thuộc tính', 'trim|required');
			$this->form_validation->set_rules('catalogueid', 'Danh mục chính', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('canonical', 'Đường dẫn thuộc tính', 'trim|required|callback__CheckCanonical');
			if($this->input->post('catalogueid') == 7){
				$this->form_validation->set_rules('start_price', 'Giá bắt đầu', 'trim|required');
				$this->form_validation->set_rules('end_price', 'Giá cuối', 'trim|required');
			}
			if($this->form_validation->run($this)){
				$_update = array(
					'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'slug' => slug(htmlspecialchars_decode(html_entity_decode($this->input->post('title')))),
					'canonical' => slug($this->input->post('canonical')),
					'description' => $this->input->post('description'),
					'color' => $this->input->post('color'),
					'start_price' => (int)str_replace('.','',$this->input->post('start_price')),
					'end_price' => (int)str_replace('.','',$this->input->post('end_price')),
					'catalogueid' => $this->input->post('catalogueid'),
					'meta_title' => $this->input->post('meta_title'),
					'meta_description' => $this->input->post('meta_description'),
					'publish' => $this->input->post('publish'),
					'image' => $this->input->post('image'),
					'userid_created' => $this->auth['id'],
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);
				$flag = $this->Autoload_Model->_update(array(
					'where' => array('id' => $id),
					'table' => 'attribute',
					'data' => $_update,
				));
				if($flag > 0){
					$canonical = slug($this->input->post('canonical'));
					if(!empty($canonical)){
						$this->Autoload_Model->_delete(array(
							'where' => array('canonical' => $detailattribute['canonical'],'uri' => 'attribute/frontend/attribute/view','param' => $id),
							'table' => 'router',
						));
						$router = array(
							'canonical' => $canonical,
							'crc32' => sprintf("%u", crc32($canonical)),
							'uri' => 'attribute/frontend/attribute/view',
							'param' => $id,
							'type' => 'number',
							'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
						);
						$routerid = $this->Autoload_Model->_create(array(
							'table' => 'router',
							'data' => $router,
						));
					}
					
					$this->session->set_flashdata('message-success', 'Cập nhật thuộc tính thành công');
					redirect('attribute/backend/attribute/view');
				}
			}
		}
		
		$data['script'] = 'attribute';
		$data['detailattribute'] = $detailattribute;
		$data['template'] = 'attribute/backend/attribute/update';
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
