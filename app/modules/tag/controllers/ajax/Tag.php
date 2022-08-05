<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tag extends MY_Controller {

	public function __construct(){
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
	}
	
	public function create(){
	 	$this->load->library('form_validation');
	 	$this->form_validation->set_error_delimiters('','/');
		$this->form_validation->set_rules('title', 'Từ khóa', 'trim|required');
		$this->form_validation->set_rules('canonical', 'Đường dẫn', 'trim|required|callback__CheckCanonical');
		if($this->form_validation->run($this)){
			$_insert = array(
				'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
				'slug' => slug(htmlspecialchars_decode(html_entity_decode($this->input->post('title')))),
				'canonical' => slug($this->input->post('canonical')),
				'publish' => 0,
				'userid_created' => $this->auth['id'],
				'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
			);
			$resultid = $this->Autoload_Model->_create(array(
				'table' => 'tag',
				'data' => $_insert,
			));
			if($resultid > 0){
					$canonical = slug($this->input->post('canonical'));
					if(!empty($canonical)){
						$router = array(
							'canonical' => $canonical,
							'crc32' => sprintf("%u", crc32($canonical)),
							'uri' => 'tag/frontend/tag/view',
							'param' => $resultid,
							'type' => 'number',
							'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
						);
						$routerid = $this->Autoload_Model->_create(array(
							'table' => 'router',
							'data' => $router,
						));
						if ($routerid > 0){
							echo json_encode(array(
								'flag' => true ,
								'message' => 'Thêm từ khóa thành công'
							));
						}
					}
				}
		}else{
			echo json_encode(array(
				'flag' => false ,
				'message' => validation_errors()
			));
		}
	}
	
	public function _CheckCanonical($title = ''){
		$canonical = $this->input->post('canonical');
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
		return true;
	}
	
	public function status(){
		$id = $this->input->post('objectid');
		$object = $this->Autoload_Model->_get_where(array(
			'select' => 'id, publish',
			'table' => 'tag',
			'where' => array('id' => $id),
		));
		
		$_update['publish'] = (($object['publish'] == 1)?0:1);
		$this->Autoload_Model->_update(array(
			'where' => array('id' => $id),
			'table' => 'tag',
			'data' => $_update,
		));
	}
	
	public function listTag(){
		$page = (int)$this->input->get('page');
		$data['from'] = 0;
		$data['to'] = 0;
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 20;
		$keyword = $this->db->escape_like_str($this->input->get('keyword'));
		$extend = '';
		if(!in_array('tag/backend/tag/viewall', json_decode($this->auth['permission'], TRUE))){
			$extend = 'userid_created = '.$this->auth['id'].'';
		}
		$config['total_rows'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id',
			'table' => 'tag',
			'where_extend' => $extend,
			'keyword' => '(title LIKE \'%'.$keyword.'%\' OR description LIKE \'%'.$keyword.'%\')',
			'count' => TRUE,
		));
		
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] ='#" data-page="';
			$config['suffix'] = $this->config->item('url_suffix').(!empty($_SERVER['QUERY_STRING'])?('?'.$_SERVER['QUERY_STRING']):'');
			$config['first_url'] = $config['base_url'].$config['suffix'];
			$config['per_page'] = $perpage;
			$config['cur_page'] = $page;
			$config['page'] = $page;
			$config['uri_segment'] = 2;
			$config['use_page_numbers'] = TRUE;
			$config['reuse_query_string'] = TRUE;
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
			$listPagination = $this->pagination->create_links();
			$totalPage = ceil($config['total_rows']/$config['per_page']);
			$page = ($page <= 0)?1:$page;
			$page = ($page > $totalPage)?$totalPage:$page;
			$page = $page - 1;
			$data['from'] = ($page * $config['per_page']) + 1;
			$data['to'] = ($config['per_page']*($page+1) > $config['total_rows']) ? $config['total_rows']  : $config['per_page']*($page+1);
			
			$listTag = $this->Autoload_Model->_get_where(array(
				'select' => 'id,title, publish, created, order, image, (SELECT fullname FROM user WHERE user.id = tag.userid_created) as user_created',
				'table' => 'tag',
				'where_extend' => $extend,
				'keyword' => '(title LIKE \'%'.$keyword.'%\' OR description LIKE \'%'.$keyword.'%\')',
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'order_by' => 'order desc, title asc, id desc',
			), TRUE);	
			
		}
		
		$html = '';
		 if(isset($listTag) && is_array($listTag) && count($listTag)){ 
			foreach($listTag as $key => $val){
				$html = $html .'<tr class="gradeX">';
					$html = $html.'<td>';
						$html = $html.'<input type="checkbox" name="checkbox[]" value="'.$val['id'].'" class="checkbox-item">';
						$html = $html.'<label for="" class="label-checkboxitem"></label>';
					$html = $html.'</td>';
					$html = $html.'<td>';
						$html = $html.$val['title'];
					$html = $html.'</td>';
					
					$html = $html.'<td>';
						$html = $html.'<input type="text" name="order['.$val['id'].']" value="'.$val['order'].'" class="form-control" placeholder="Vị trí" style="width:50px;">';
					$html = $html.'</td>';
					$html = $html.'<td>'.$val['user_created'].'</td>';
					$html = $html.'<td>'.gettime($val['created'],'d/m/Y').'</td>';
					$html = $html.'<td>';
						$html = $html.'<div class="switch">';
							$html = $html.'<div class="onoffswitch">';
								$html = $html.'<input type="checkbox" '.(($val['publish'] == 0) ? 'checked=""' : '').' class="onoffswitch-checkbox publish" data-id="'.$val['id'].'" id="publish-'.$val['id'].'">';
								$html = $html.'<label class="onoffswitch-label" for="publish-'.$val['id'].'">';
									$html = $html.'<span class="onoffswitch-inner"></span>';
									$html = $html.'<span class="onoffswitch-switch"></span>';
								$html = $html.'</label>';
							$html = $html.'</div>';
						$html = $html.'</div>';
					$html = $html.'</td>';
					$html = $html.'<td class="text-center">';
						$html = $html.'<a type="button" href="'.(site_url('tag/backend/tag/update/'.$val['id'].'')).'" class="btn btn-sm btn-primary mr5"><i class="fa fa-edit"></i></a>';
						$html = $html.'<a type="button" class="btn btn-sm btn-danger ajax-delete" data-id="'.$val['id'].'" data-module="tag"><i class="fa fa-trash"></i></a>';
					$html = $html.'</td>';
				$html = $html.'</tr>';
			 }
		}else{ 
			$html = $html.'<tr>
				<td colspan="9"><small class="text-danger">Không có dữ liệu phù hợp</small></td>
			</tr>';
		}
		echo json_encode(array(
			'pagination' => (isset($listPagination)) ? $listPagination : '',
			'html' => (isset($html)) ? $html : '',
			'total' => $config['total_rows'],
		));die();		
	}
}
