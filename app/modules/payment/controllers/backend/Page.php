<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class page extends MY_Controller {

	public $module;
	function __construct() {
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library(array('configbie'));
		$this->load->library('nestedsetbie', array('table' => 'page_catalogue'));
	}
	
	public function view($page = 1){
		$this->commonbie->permission("page/backend/page/view", $this->auth['permission']);
		$page = (int)$page;
		$data['from'] = 0;
		$data['to'] = 0;
		
		$extend = (!in_array('page/backend/page/viewall', json_decode($this->auth['permission'], TRUE))) ? 'userid_created = '.$this->auth['id'].'' : '';
		
		
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 20;
		$keyword = $this->db->escape_like_str($this->input->get('keyword'));
		$catalogueid = (int)$this->input->get('catalogueid');
		if($catalogueid > 0){
			$config['total_rows'] = $this->Autoload_Model->_condition(array(
				'module' => 'page',
				'select' => '`object`.`id`',
				'where' => ((!empty($extend)) ? '`object`.`userid_created` = '.$this->auth['id'].'' : ''),
				'keyword' => '(`object`.`title` LIKE \'%'.$keyword.'%\' AND `object`.`description` LIKE \'%'.$keyword.'%\')',
				'catalogueid' => $catalogueid,
				'count' => TRUE
			));
		}else{
			$config['total_rows'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id',
				'table' => 'page',
				'where_extend' => $extend,
				'keyword' => '(title LIKE \'%'.$keyword.'%\' OR description LIKE \'%'.$keyword.'%\')',
				'count' => TRUE,
			));
		}
		
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('page/backend/page/view');
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
				$data['listpage'] = $this->Autoload_Model->_condition(array(
					'module' => 'page',
					'select' => '`object`.`id`, `object`.`title`, `object`.`catalogueid`, `object`.`catalogue`, `object`.`publish`, `object`.`image`, `object`.`created`, `object`.`order`, `object`.`viewed`, (SELECT fullname FROM user WHERE user.id = object.userid_created) as user_created, (SELECT title FROM page_catalogue WHERE page_catalogue.id = object.catalogueid) as catalogue_title',
					'where' => ((!empty($extend)) ? '`object`.`userid_created` = '.$this->auth['id'].'' : ''),
					'keyword' => '(`object`.`title` LIKE \'%'.$keyword.'%\' AND `object`.`description` LIKE \'%'.$keyword.'%\')',
					'catalogueid' => $catalogueid,
					'limit' => $perpage,
					'order_by' => '`object`.`order` desc, `object`.`title` asc, `object`.`id` desc',
				));
			}else{
				$data['listpage'] = $this->Autoload_Model->_get_where(array(
					'select' => 'id, catalogueid, catalogue, title, publish, created, order, viewed, image, (SELECT fullname FROM user WHERE user.id = page.userid_created) as user_created, (SELECT title FROM page_catalogue WHERE page_catalogue.id = page.catalogueid) as catalogue_title',
					'table' => 'page',
					'where_extend' => $extend,
					'limit' => $config['per_page'],
					'start' => $page * $config['per_page'],
					'keyword' => $keyword,
					'order_by' => 'order desc, id desc, title asc',
				), TRUE);	
			}
		}
		$data['script'] = 'page';
		$data['config'] = $config;
		$data['template'] = 'page/backend/page/view';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function Create(){
		$this->commonbie->permission("page/backend/page/create", $this->auth['permission']);
		if($this->input->post('create')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('title', 'Tiêu đề nội dung tĩnh', 'trim|required');
			$this->form_validation->set_rules('catalogueid', 'Danh mục chính', 'trim|is_natural_no_zero');
			if($this->form_validation->run($this)){
				$_insert = array(
					'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'description' => $this->input->post('description'),
					'catalogueid' => $this->input->post('catalogueid'),
					'catalogue' => json_encode($this->input->post('catalogue')),
					'image' => $this->input->post('image'),
					'userid_created' => $this->auth['id'],
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);
				
			
				$resultid = $this->Autoload_Model->_create(array(
					'table' => 'page',
					'data' => $_insert,
				));
				if($resultid > 0){
					
					$catalogue = $this->input->post('catalogue');
					$_catalogue_relation_ship[] = array(
						'module' => 'page',
						'moduleid' => $resultid,
						'catalogueid' => $this->input->post('catalogueid'),
					);
					if(isset($catalogue) && is_array($catalogue) && count($catalogue)){
						foreach($catalogue as $key => $val){
							if($val == $this->input->post('catalogueid')) continue;
							$_catalogue_relation_ship[] = array(
								'module' => 'page',
								'moduleid' => $resultid,
								'catalogueid' => $val
							);
						}
					}
					
					$this->Autoload_Model->_create_batch(array(
						'table' => 'catalogue_relationship',
						'data' => $_catalogue_relation_ship,
					));
					
					
					$tag = $this->input->post('tag');
					if(isset($tag) && is_array($tag) && count($tag)){
						foreach($tag as $key => $val){
							$_tag_relation_ship[] = array(
								'module' => 'page',
								'moduleid' => $resultid,
								'tagid' => $val
							);
						}
						$this->Autoload_Model->_create_batch(array(
							'table' => 'tag_relationship',
							'data' => $_tag_relation_ship,
						));
					}
					
					$this->session->set_flashdata('message-success', 'Thêm nội dung tĩnh mới thành công');
					redirect('page/backend/page/view');
				}
			}
		}
		$data['script'] = 'page';
		$data['template'] = 'page/backend/page/create';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function Update($id = 0){
		$data = comment(array('id' => $id, 'module' => $this->module));
		$this->commonbie->permission("page/backend/page/update", $this->auth['permission']);
		$id = (int)$id;
		$detailpage = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, description, catalogueid, catalogue, image, publish, ',
			'table' => 'page',
			'where' => array('id' => $id),
		));
		if(!isset($detailpage) || is_array($detailpage) == false || count($detailpage) == 0){
			$this->session->set_flashdata('message-danger', 'nội dung tĩnh không tồn tại');
			redirect('page/backend/page/view');
		}
		if($this->input->post('update')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('title', 'Tiêu đề nội dung tĩnh', 'trim|required');
			if($this->form_validation->run($this)){
				$_update = array(
					'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'description' => $this->input->post('description'),
					'catalogueid' => $this->input->post('catalogueid'),
					'catalogue' => json_encode($this->input->post('catalogue')),
					'publish' => $this->input->post('publish'),
					'image' => $this->input->post('image'),
					'userid_created' => $this->auth['id'],
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);
				$flag = $this->Autoload_Model->_update(array(
					'where' => array('id' => $id),
					'table' => 'page',
					'data' => $_update,
				));
				if($flag > 0){
					
					
					$this->Autoload_Model->_delete(array(
						'where' => array('module' => 'page','moduleid' => $id),
						'table' => 'catalogue_relationship',
					));
					
					$catalogue = $this->input->post('catalogue');
					$_catalogue_relation_ship[] = array(
						'module' => 'page',
						'moduleid' => $id,
						'catalogueid' => $this->input->post('catalogueid'),
					);
					if(isset($catalogue) && is_array($catalogue) && count($catalogue)){
						foreach($catalogue as $key => $val){
							if($val == $this->input->post('catalogueid')) continue;
							$_catalogue_relation_ship[] = array(
								'module' => 'page',
								'moduleid' => $id,
								'catalogueid' => $val
							);
						}
					}
					$this->Autoload_Model->_create_batch(array(
						'table' => 'catalogue_relationship',
						'data' => $_catalogue_relation_ship,
					));
					
					$this->session->set_flashdata('message-success', 'Cập nhật nội dung tĩnh thành công');
					redirect('page/backend/page/view');
				}
			}
		}
		
		
		$data['script'] = 'page';
		$data['detailpage'] = $detailpage;
		$data['template'] = 'page/backend/page/update';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	
	
}
