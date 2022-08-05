<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends MY_Controller {

	public $module;
	function __construct() {
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library(array('configbie'));
		$this->load->library('nestedsetbie', array('table' => 'project_catalogue'));
		$this->module = 'project';
	}
	
	public function view($page = 1){
		$this->commonbie->permission("project/backend/project/view", $this->auth['permission']);
		$page = (int)$page;
		$data['from'] = 0;
		$data['to'] = 0;
		
		$extend = (!in_array('project/backend/project/viewall', json_decode($this->auth['permission'], TRUE))) ? 'userid_created = '.$this->auth['id'].'' : '';
		
		
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 20;
		$keyword = $this->db->escape_like_str($this->input->get('keyword'));
		$catalogueid = (int)$this->input->get('catalogueid');
		if($catalogueid > 0){
			$config['total_rows'] = $this->Autoload_Model->_condition(array(
				'module' => 'project',
				'select' => '`object`.`id`',
				'where' => ((!empty($extend)) ? '`object`.`userid_created` = '.$this->auth['id'].'' : ''),
				'keyword' => '(`object`.`title` LIKE \'%'.$keyword.'%\' AND `object`.`description` LIKE \'%'.$keyword.'%\')',
				'catalogueid' => $catalogueid,
				'count' => TRUE
			));
		}else{
			$config['total_rows'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id',
				'table' => 'project',
				'where_extend' => $extend,
				'keyword' => '(title LIKE \'%'.$keyword.'%\' OR description LIKE \'%'.$keyword.'%\')',
				'count' => TRUE,
			));
		}
		
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('project/backend/project/view');
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
				$data['listproject'] = $this->Autoload_Model->_condition(array(
					'module' => 'project',
					'select' => '`object`.`id`, `object`.`title`, `object`.`slug`,`object`.`canonical`, `object`.`catalogueid`, `object`.`catalogue`, `object`.`publish`, `object`.`image`, `object`.`created`, `object`.`order`, `object`.`viewed`, (SELECT fullname FROM user WHERE user.id = object.userid_created) as user_created, (SELECT title FROM project_catalogue WHERE project_catalogue.id = object.catalogueid) as catalogue_title',
					'where' => ((!empty($extend)) ? '`object`.`userid_created` = '.$this->auth['id'].'' : ''),
					'keyword' => '(`object`.`title` LIKE \'%'.$keyword.'%\' AND `object`.`description` LIKE \'%'.$keyword.'%\')',
					'catalogueid' => $catalogueid,
					'limit' => $perpage,
					'order_by' => '`object`.`order` desc, `object`.`title` asc, `object`.`id` desc',
				));
			}else{
				$data['listproject'] = $this->Autoload_Model->_get_where(array(
					'select' => 'id, catalogueid, catalogue, title, canonical, publish, created, order, viewed, image, (SELECT fullname FROM user WHERE user.id = project.userid_created) as user_created, (SELECT title FROM project_catalogue WHERE project_catalogue.id = project.catalogueid) as catalogue_title',
					'table' => 'project',
					'where_extend' => $extend,
					'limit' => $config['per_page'],
					'start' => $page * $config['per_page'],
					'keyword' => $keyword,
					'order_by' => 'order desc, id desc, title asc',
				), TRUE);	
			}
		}
		$data['script'] = 'project';
		$data['config'] = $config;
		$data['template'] = 'project/backend/project/view';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function Create(){
		$this->commonbie->permission("project/backend/project/create", $this->auth['permission']);
		if($this->input->post('create')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('title', 'Tiêu đề dự án', 'trim|required');
			$this->form_validation->set_rules('catalogueid', 'Danh mục chính', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('canonical', 'Đường dẫn dự án', 'trim|required|callback__CheckCanonical');
			if($this->form_validation->run($this)){
				$_insert = array(
					'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'slug' => slug(htmlspecialchars_decode(html_entity_decode($this->input->post('title')))),
					'canonical' => slug($this->input->post('canonical')),
					'excerpt' => $this->input->post('excerpt'),
					'description' => $this->input->post('description'),
					'catalogueid' => $this->input->post('catalogueid'),
					'catalogue' => json_encode($this->input->post('catalogue')),
					'tag' => json_encode($this->input->post('tag')),
					'meta_title' => $this->input->post('meta_title'),
					'meta_description' => $this->input->post('meta_description'),
					'publish' => $this->input->post('publish'),
					'publish_time' => merge_time($this->input->post('post_date'), $this->input->post('post_time')),
					'image' => $this->input->post('image'),
					'amp' => $this->input->post('amp'),
					'userid_created' => $this->auth['id'],
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),

					'extend_description' =>json_encode( $this->input->post('content')),
					'url_view' => $this->input->post('url_view'),

				);
				
			
				$resultid = $this->Autoload_Model->_create(array(
					'table' => 'project',
					'data' => $_insert,
				));
				if($resultid > 0){
					$canonical = slug($this->input->post('canonical'));
					if(!empty($canonical)){
						$router = array(
							'canonical' => $canonical,
							'crc32' => sprintf("%u", crc32($canonical)),
							'uri' => 'project/frontend/project/view',
							'param' => $resultid,
							'type' => 'number',
							'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
						);
						$routerid = $this->Autoload_Model->_create(array(
							'table' => 'router',
							'data' => $router,
						));
					}
					$catalogue = $this->input->post('catalogue');
					$_catalogue_relation_ship[] = array(
						'module' => 'project',
						'moduleid' => $resultid,
						'catalogueid' => $this->input->post('catalogueid'),
					);
					if(isset($catalogue) && is_array($catalogue) && count($catalogue)){
						foreach($catalogue as $key => $val){
							if($val == $this->input->post('catalogueid')) continue;
							$_catalogue_relation_ship[] = array(
								'module' => 'project',
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
								'module' => 'project',
								'moduleid' => $resultid,
								'tagid' => $val
							);
						}
						$this->Autoload_Model->_create_batch(array(
							'table' => 'tag_relationship',
							'data' => $_tag_relation_ship,
						));
					}
					
					$this->session->set_flashdata('message-success', 'Thêm dự án mới thành công');
					redirect('project/backend/project/view');
				}
			}
		}
		$data['script'] = 'project';
		$data['template'] = 'project/backend/project/create';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function Update($id = 0){
		$data = comment(array('id' => $id, 'module' => $this->module));
		
		
		
		$this->commonbie->permission("project/backend/project/update", $this->auth['permission']);
		$id = (int)$id;
		$detailproject = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, slug, canonical, excerpt, description, meta_title, catalogueid, catalogue, tag, meta_description, image, publish, publish_time, amp, url_view, extend_description',
			'table' => 'project',
			'where' => array('id' => $id),
		));
		if(!isset($detailproject) || is_array($detailproject) == false || count($detailproject) == 0){
			$this->session->set_flashdata('message-danger', 'dự án không tồn tại');
			redirect('project/backend/project/view');
		}
		if($this->input->post('update')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('title', 'Tiêu đề dự án', 'trim|required');
			$this->form_validation->set_rules('canonical', 'Đường dẫn dự án', 'trim|required|callback__CheckCanonical');
			if($this->form_validation->run($this)){
				$_update = array(
					'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'slug' => slug(htmlspecialchars_decode(html_entity_decode($this->input->post('title')))),
					'canonical' => slug($this->input->post('canonical')),
					'excerpt' => $this->input->post('excerpt'),
					'description' => $this->input->post('description'),
					'catalogueid' => $this->input->post('catalogueid'),
					'catalogue' => json_encode($this->input->post('catalogue')),
					'tag' => json_encode($this->input->post('tag')),
					'meta_title' => $this->input->post('meta_title'),
					'meta_description' => $this->input->post('meta_description'),
					'publish' => $this->input->post('publish'),
					'publish_time' => merge_time($this->input->post('post_date'), $this->input->post('post_time')),
					'image' => $this->input->post('image'),
					'url_view' => $this->input->post('url_view'),
					'amp' => $this->input->post('amp'),
					'userid_created' => $this->auth['id'],
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),

					'extend_description' =>json_encode( $this->input->post('content')),
					'url_view' => $this->input->post('url_view'),
				);
				$flag = $this->Autoload_Model->_update(array(
					'where' => array('id' => $id),
					'table' => 'project',
					'data' => $_update,
				));
				if($flag > 0){
					$canonical = slug($this->input->post('canonical'));
					if(!empty($canonical)){
						$this->Autoload_Model->_delete(array(
							'where' => array('canonical' => $detailproject['canonical'],'uri' => 'project/frontend/project/view','param' => $id),
							'table' => 'router',
						));
						$router = array(
							'canonical' => $canonical,
							'crc32' => sprintf("%u", crc32($canonical)),
							'uri' => 'project/frontend/project/view',
							'param' => $id,
							'type' => 'number',
							'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
						);
						$routerid = $this->Autoload_Model->_create(array(
							'table' => 'router',
							'data' => $router,
						));
					}
					
					$this->Autoload_Model->_delete(array(
						'where' => array('module' => 'project','moduleid' => $id),
						'table' => 'catalogue_relationship',
					));
					
					$catalogue = $this->input->post('catalogue');
					$_catalogue_relation_ship[] = array(
						'module' => 'project',
						'moduleid' => $id,
						'catalogueid' => $this->input->post('catalogueid'),
					);
					if(isset($catalogue) && is_array($catalogue) && count($catalogue)){
						foreach($catalogue as $key => $val){
							if($val == $this->input->post('catalogueid')) continue;
							$_catalogue_relation_ship[] = array(
								'module' => 'project',
								'moduleid' => $id,
								'catalogueid' => $val
							);
						}
					}
					$this->Autoload_Model->_create_batch(array(
						'table' => 'catalogue_relationship',
						'data' => $_catalogue_relation_ship,
					));
					
					
					$tag = $this->input->post('tag');
					$this->Autoload_Model->_delete(array(
						'where' => array('module' => 'project','moduleid' => $id),
						'table' => 'tag_relationship',
					));
					if(isset($tag) && is_array($tag) && count($tag)){
						foreach($tag as $key => $val){
							$_tag_relation_ship[] = array(
								'module' => 'project',
								'moduleid' => $id,
								'tagid' => $val
							);
						}
						$this->Autoload_Model->_create_batch(array(
							'table' => 'tag_relationship',
							'data' => $_tag_relation_ship,
						));
					
					}
					
					$this->session->set_flashdata('message-success', 'Cập nhật dự án thành công');
					redirect('project/backend/project/view');
				}
			}
		}
		
		
		$data['script'] = 'project';
		$data['detailproject'] = $detailproject;
		$data['template'] = 'project/backend/project/update';
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
