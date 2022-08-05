<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hosting extends MY_Controller {

	public function __construct(){
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
	}
	
	
	
	public function status(){
		$id = $this->input->post('objectid');
		$object = $this->Autoload_Model->_get_where(array(
			'select' => 'id, publish',
			'table' => 'hosting',
			'where' => array('id' => $id),
		));
		
		$_update['publish'] = (($object['publish'] == 1)?0:1);
		$this->Autoload_Model->_update(array(
			'where' => array('id' => $id),
			'table' => 'hosting',
			'data' => $_update,
		));
	}
	
	public function listhosting(){
		$page = (int)$this->input->get('page');
		$data['from'] = 0;
		$data['to'] = 0;
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 20;
		$keyword = $this->db->escape_like_str($this->input->get('keyword'));
		$catalogueid = $this->input->get('catalogueid');
		$extend = '';
		if(!in_array('hosting/backend/hosting/viewall', json_decode($this->auth['permission'], TRUE))){
			$extend = 'userid_created = '.$this->auth['id'].'';
		}
		
		if($catalogueid > 0){
			$config['total_rows'] = $this->Autoload_Model->_condition(array(
				'module' => 'hosting',
				'select' => '`object`.`id`',
				'where' => ((!empty($extend)) ? '`object`.`userid_created` = '.$this->auth['id'].'' : ''),
				'keyword' => '(`object`.`title` LIKE \'%'.$keyword.'%\' AND `object`.`description` LIKE \'%'.$keyword.'%\')',
				'catalogueid' => $catalogueid,
				'count' => TRUE
			));
		}else{
			$config['total_rows'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id',
				'table' => 'hosting',
				'where_extend' => $extend,
				'keyword' => '(title LIKE \'%'.$keyword.'%\' OR description LIKE \'%'.$keyword.'%\')',
				'count' => TRUE,
			));
		}
		
		
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
			if($catalogueid > 0){
				$listCatalogue = $this->Autoload_Model->_condition(array(
					'module' => 'hosting',
					'select' => '`object`.`id`, `object`.`title`, `object`.`slug`,`object`.`canonical`, `object`.`image`, `object`.`catalogueid`, `object`.`catalogue`, `object`.`publish`, `object`.`created`, `object`.`order`, `object`.`viewed`, (SELECT fullname FROM user WHERE user.id = object.userid_created) as user_created, (SELECT title FROM hosting_catalogue WHERE hosting_catalogue.id = object.catalogueid) as catalogue_title',
					'where' => ((!empty($extend)) ? '`object`.`userid_created` = '.$this->auth['id'].'' : ''),
					'keyword' => '(`object`.`title` LIKE \'%'.$keyword.'%\' AND `object`.`description` LIKE \'%'.$keyword.'%\')',
					'catalogueid' => $catalogueid,
					'limit' => $perpage,
					'order_by' => '`object`.`order` desc, `object`.`title` asc, `object`.`id` desc',
				));
			}else{
				$listCatalogue = $this->Autoload_Model->_get_where(array(
					'select' => 'id, catalogueid, catalogue,title, publish, created, order, viewed, image, (SELECT fullname FROM user WHERE user.id = hosting.userid_created) as user_created, (SELECT title FROM hosting_catalogue WHERE hosting_catalogue.id = hosting.catalogueid) as catalogue_title',
					'table' => 'hosting',
					'where_extend' => $extend,
					'keyword' => '(title LIKE \'%'.$keyword.'%\' OR description LIKE \'%'.$keyword.'%\')',
					'limit' => $config['per_page'],
					'start' => $page * $config['per_page'],
					'order_by' => 'order desc, title asc, id desc',
				), TRUE);	
			}
			
			
		}
		
		$html = '';
		 if(isset($listCatalogue) && is_array($listCatalogue) && count($listCatalogue)){ 
			foreach($listCatalogue as $key => $val){
				$image = getthumb($val['image']);
				$_catalogue_list = '';
				$catalogue = json_decode($val['catalogue'], TRUE);
				if(isset($catalogue) && is_array($catalogue) && count($catalogue)){
					$_catalogue_list = $this->Autoload_Model->_get_where(array(
						'select' => 'id, title, slug, canonical',
						'table' => 'hosting_catalogue',
						'where_in' => json_decode($val['catalogue'], TRUE),
						'where_in_field' => 'id',
					), TRUE);
				}	
				$html = $html .'<tr class="gradeX">';
					$html = $html.'<td>';
						$html = $html.'<input type="checkbox" name="checkbox[]" value="'.$val['id'].'" class="checkbox-item">';
						$html = $html.'<label for="" class="label-checkboxitem"></label>';
					$html = $html.'</td>';
					$html = $html.'<td>';
						$html = $html.'<div class="uk-flex uk-flex-middle">';
							$html = $html.'<div class="image mr5">';
								$html = $html.'<span class="image-post img-cover"><img src="'.$image.'" alt="'.$val['title'].'" /></span>';
							$html = $html.'</div>';
							$html = $html.'<div class="main-info">';
								$html = $html.'<div class="title"><a class="maintitle" href="'.site_url('hosting/backend/hosting/update/'.$val['id'].'').'" title="">'.$val['title'].' ('.$val['viewed'].')</a></div>';
								$html = $html.'<div class="catalogue" style="font-size:10px">';
									$html = $html.'<span style="color:#f00000;">Nhóm hiển thị: </span>';
									$html = $html.'<a class="" style="color:#333;" href="'.site_url('hosting/backend/hosting/view?catalogueid='.$val['catalogueid']).'" title="">'.$val['catalogue_title'].'</a>'.((isset($_catalogue_list) && is_array($_catalogue_list) && count($_catalogue_list) ) ? ' ,' :'').'';
									if(isset($_catalogue_list) && is_array($_catalogue_list) && count($_catalogue_list)){ foreach($_catalogue_list as $keyCat => $valCat){ 
										$html = $html.'<a style="color:#333;" class="" href="'.site_url('hosting/backend/hosting/view?catalogueid='.$valCat['id']).'" title="">'.$valCat['title'].'</a> '.(($keyCat + 1 < count($_catalogue_list)) ? ', ' : '').'';
									}} 
								$html = $html.'</div>';
							$html = $html.'</div>';
						$html = $html.'</div>';
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
						$html = $html.'<a type="button" href="'.(site_url('hosting/backend/catalogue/update/'.$val['id'].'')).'" class="btn btn-sm btn-primary mr5"><i class="fa fa-edit"></i></a>';
						$html = $html.'<a type="button" class="btn btn-sm btn-danger ajax-delete" data-title="Lưu ý: Khi bạn xóa danh mục, toàn bộ bài viết trong nhóm này sẽ bị xóa. Hãy chắc chắn bạn muốn thực hiện chức năng này!" data-id="'.$val['id'].'" data-module="hosting"><i class="fa fa-trash"></i></a>';
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
