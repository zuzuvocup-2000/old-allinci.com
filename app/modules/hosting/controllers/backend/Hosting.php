<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hosting extends MY_Controller {

	public $module;
	function __construct() {
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library(array('configbie'));
		$this->load->library('nestedsetbie', array('table' => 'hosting_catalogue'));
	}
	
	public function view($page = 1){
		$this->commonbie->permission("hosting/backend/hosting/view", $this->auth['permission']);
		$page = (int)$page;
		$data['from'] = 0;
		$data['to'] = 0;
		
		$extend = (!in_array('hosting/backend/hosting/viewall', json_decode($this->auth['permission'], TRUE))) ? 'hostingid_created = '.$this->auth['id'].'' : '';
		
		
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 20;
		$keyword = $this->db->escape_like_str($this->input->get('keyword'));
		$catalogueid = (int)$this->input->get('catalogueid');
		if($catalogueid > 0){
			$config['total_rows'] = $this->Autoload_Model->_condition(array(
				'module' => 'hosting',
				'select' => '`object`.`id`',
				'where' => ((!empty($extend)) ? '`object`.`hostingid_created` = '.$this->auth['id'].'' : ''),
				'keyword' => '(`object`.`title` LIKE \'%'.$keyword.'%\' )',
				'catalogueid' => $catalogueid,
				'count' => TRUE
			));
		}else{
			$config['total_rows'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id',
				'table' => 'hosting',
				'where_extend' => $extend,
				'keyword' => '(title LIKE \'%'.$keyword.'%\')',
				'count' => TRUE,
			));
		}
		
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('hosting/backend/hosting/view');
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
				$data['listhosting'] = $this->Autoload_Model->_condition(array(
					'module' => 'hosting',
					'select' => '`object`.`id`, `object`.`title`, `object`.`slug`, `object`.`capacity`, `object`.`bandwidth`,`object`.`canonical`, `object`.`catalogueid`, `object`.`catalogue`,  `object`.`image`, `object`.`created`, `object`.`order`, `object`.`viewed`, (SELECT fullname FROM user WHERE user.id = object.hostingid_created) as hosting_created, (SELECT title FROM user_catalogue WHERE user_catalogue.id = object.catalogueid) as catalogue_title',
					'where' => ((!empty($extend)) ? '`object`.`hostingid_created` = '.$this->auth['id'].'' : ''),
					'keyword' => '(`object`.`title` LIKE \'%'.$keyword.'%\' )',
					'catalogueid' => $catalogueid,
					'limit' => $perpage,
					'order_by' => '`object`.`order` desc, `object`.`title` asc, `object`.`id` desc',
				));
			}else{
				$data['listhosting'] = $this->Autoload_Model->_get_where(array(
					'select' => 'id, title, catalogueid,price, capacity, bandwidth, order,canonical, publish,  created, (SELECT fullname FROM user WHERE user.id = user.userid_created) as user_created, (SELECT title FROM hosting_catalogue WHERE hosting_catalogue.id = hosting.catalogueid) as catalogue_title',
					'table' => 'hosting',
					'where_extend' => $extend,
					'limit' => $config['per_page'],
					'start' => $page * $config['per_page'],
					'keyword' => $keyword,
					'order_by' => ' order desc, id desc',
				), TRUE);	
			}
		}
		$data['script'] = 'hosting';
		$data['config'] = $config;
		$data['template'] = 'hosting/backend/hosting/view';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function Create(){
		$this->commonbie->permission("hosting/backend/hosting/create", $this->auth['permission']);
		if($this->input->post('create')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('title', 'Tiêu đề hosting', 'trim|required');
			// $this->form_validation->set_rules('catalogueid', 'Danh mục chính', 'trim|is_natural_no_zero');
			if($this->form_validation->run($this)){
				$_insert = array(
					'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'slug' => slug(htmlspecialchars_decode(html_entity_decode($this->input->post('title')))),
					'canonical' => slug($this->input->post('canonical')),
					'catalogueid' => $this->input->post('catalogueid'),
					'price' => (int)str_replace('.','',$this->input->post('price')),
					'capacity' => $this->input->post('capacity'),
					'bandwidth' => $this->input->post('bandwidth'),
					'FPT_account' => $this->input->post('FPT_account'),
					'mysql' => $this->input->post('mysql'),
					'domain' => $this->input->post('domain'),
					'sub_domain' => $this->input->post('sub_domain'),
					'addon_domain' => $this->input->post('addon_domain'),
					'park_domain' => $this->input->post('park_domain'),
					'contract_time' => $this->input->post('contract_time'),
					'userid_created' => $this->auth['id'],
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);
				
			
				$resultid = $this->Autoload_Model->_create(array(
					'table' => 'hosting',
					'data' => $_insert,
				));
				if($resultid > 0){
					$canonical = slug($this->input->post('canonical'));
					if(!empty($canonical)){
						$router = array(
							'canonical' => $canonical,
							'crc32' => sprintf("%u", crc32($canonical)),
							'uri' => 'hosting/frontend/hosting/view',
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
						'module' => 'hosting',
						'moduleid' => $resultid,
						'catalogueid' => $this->input->post('catalogueid'),
					);
					if(isset($catalogue) && is_array($catalogue) && count($catalogue)){
						foreach($catalogue as $key => $val){
							if($val == $this->input->post('catalogueid')) continue;
							$_catalogue_relation_ship[] = array(
								'module' => 'hosting',
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
								'module' => 'hosting',
								'moduleid' => $resultid,
								'tagid' => $val
							);
						}
						$this->Autoload_Model->_create_batch(array(
							'table' => 'tag_relationship',
							'data' => $_tag_relation_ship,
						));
					}
					
					$this->session->set_flashdata('message-success', 'Thêm hosting mới thành công');
					redirect('hosting/backend/hosting/view');
				}
			}
		}
		$data['script'] = 'hosting';
		$data['template'] = 'hosting/backend/hosting/create';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function Update($id = 0){
		$this->commonbie->permission("hosting/backend/hosting/update", $this->auth['permission']);
		$id = (int)$id;
		$detailhosting = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, slug, canonical, price, capacity, bandwidth, FPT_account, mysql, sub_domain, domain, addon_domain, contract_time, park_domain, catalogueid',
			'table' => 'hosting',
			'where' => array('id' => $id),
		));
		if(!isset($detailhosting) || is_array($detailhosting) == false || count($detailhosting) == 0){
			$this->session->set_flashdata('message-danger', 'hosting không tồn tại');
			redirect('hosting/backend/hosting/view');
		}
		if($this->input->post('update')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('title', 'Tiêu đề hosting', 'trim|required');
			if($this->form_validation->run($this)){
				$_update = array(
					'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'slug' => slug(htmlspecialchars_decode(html_entity_decode($this->input->post('title')))),
					'canonical' => slug($this->input->post('canonical')),
					'price' => (int)str_replace('.','',$this->input->post('price')),
					'catalogueid' => $this->input->post('catalogueid'),
					'capacity' => $this->input->post('capacity'),
					'bandwidth' => $this->input->post('bandwidth'),
					'FPT_account' => $this->input->post('FPT_account'),
					'mysql' => $this->input->post('mysql'),
					'domain' => $this->input->post('domain'),
					'sub_domain' => $this->input->post('sub_domain'),
					'addon_domain' => $this->input->post('addon_domain'),
					'park_domain' => $this->input->post('park_domain'),
					'contract_time' => $this->input->post('contract_time'),
					'userid_created' => $this->auth['id'],
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);
				$flag = $this->Autoload_Model->_update(array(
					'where' => array('id' => $id),
					'table' => 'hosting',
					'data' => $_update,
				));
				if($flag > 0){
					$canonical = slug($this->input->post('canonical'));
					if(!empty($canonical)){
						$this->Autoload_Model->_delete(array(
							'where' => array('canonical' => $detailhosting['canonical'],'uri' => 'hosting/frontend/hosting/view','param' => $id),
							'table' => 'router',
						));
						$router = array(
							'canonical' => $canonical,
							'crc32' => sprintf("%u", crc32($canonical)),
							'uri' => 'hosting/frontend/hosting/view',
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
						'where' => array('module' => 'hosting','moduleid' => $id),
						'table' => 'catalogue_relationship',
					));
					
					$catalogue = $this->input->post('catalogue');
					$_catalogue_relation_ship[] = array(
						'module' => 'hosting',
						'moduleid' => $id,
						'catalogueid' => $this->input->post('catalogueid'),
					);
					if(isset($catalogue) && is_array($catalogue) && count($catalogue)){
						foreach($catalogue as $key => $val){
							if($val == $this->input->post('catalogueid')) continue;
							$_catalogue_relation_ship[] = array(
								'module' => 'hosting',
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
						'where' => array('module' => 'hosting','moduleid' => $id),
						'table' => 'tag_relationship',
					));
					if(isset($tag) && is_array($tag) && count($tag)){
						foreach($tag as $key => $val){
							$_tag_relation_ship[] = array(
								'module' => 'hosting',
								'moduleid' => $id,
								'tagid' => $val
							);
						}
						$this->Autoload_Model->_create_batch(array(
							'table' => 'tag_relationship',
							'data' => $_tag_relation_ship,
						));
					
					}
					
					$this->session->set_flashdata('message-success', 'Cập nhật hosting thành công');
					redirect('hosting/backend/hosting/view');
				}
			}
		}
		
		
		$data['script'] = 'hosting';
		$data['detailhosting'] = $detailhosting;
		$data['template'] = 'hosting/backend/hosting/update';
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
