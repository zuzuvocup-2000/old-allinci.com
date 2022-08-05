<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attribute extends MY_Controller {

	public function __construct(){
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
	}
	
	
	
	public function status(){
		$id = $this->input->post('objectid');
		$object = $this->Autoload_Model->_get_where(array(
			'select' => 'id, publish',
			'table' => 'attribute',
			'where' => array('id' => $id),
		));
		
		$_update['publish'] = (($object['publish'] == 1)?0:1);
		$this->Autoload_Model->_update(array(
			'where' => array('id' => $id),
			'table' => 'attribute',
			'data' => $_update,
		));
	}
	
	public function listAttribute(){
		$page = (int)$this->input->get('page');
		$data['from'] = 0;
		$data['to'] = 0;
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 20;
		$keyword = $this->db->escape_like_str($this->input->get('keyword'));
		$catalogueid = $this->input->get('catalogueid');
		$extend = '';
		if(!in_array('attribute/backend/attribute/viewall', json_decode($this->auth['permission'], TRUE))){
			$extend = 'userid_created = '.$this->auth['id'].'';
		}
		if($catalogueid>0){
			$extend = $extend.(!empty($extend))?' AND':''.' catalogueid = '.$catalogueid;
		}
		$config['total_rows'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id',
			'table' => 'attribute',
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
			
			$listCatalogue = $this->Autoload_Model->_get_where(array(
				'select' => 'id, catalogueid,color, title, publish, created, order,  image, (SELECT fullname FROM user WHERE user.id = attribute.userid_created) as user_created, (SELECT title FROM attribute_catalogue WHERE attribute_catalogue.id = attribute.catalogueid) as catalogue_title',
				'table' => 'attribute',
				'where_extend' => $extend,
				'where'=>array('catalogueid'=>$catalogueid),
				'keyword' => '(title LIKE \'%'.$keyword.'%\' OR description LIKE \'%'.$keyword.'%\')',
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'order_by' => 'order desc, title asc, id desc',
			), TRUE);	
		}
		
		$html = '';
		 if(isset($listCatalogue) && is_array($listCatalogue) && count($listCatalogue)){ 
			foreach($listCatalogue as $key => $val){
				$image = getthumb($val['image']);
				$_catalogue_list = '';
				if(isset($catalogue) && is_array($catalogue) && count($catalogue)){
					$_catalogue_list = $this->Autoload_Model->_get_where(array(
						'select' => 'id, title, slug, canonical',
						'table' => 'attribute_catalogue',
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
							$html = $html.'<div class="main-info">';
								$html = $html.'<div class="uk-flex uk-flex-middle">';
									$html = $html.'<div style="width:15px; height: 15px; background:'.((!empty($val['color']))? $val['color'] :'').'" class="m-r"></div>';
									$html = $html.'<div class="title m-r-sm"><a class="maintitle" href="'.site_url('attribute/backend/attribute/update/'.$val['id']).'" title="">'.$val['title'].'</a></div>';
								$html = $html.'</div>';
							$html = $html.'</div>';
						$html = $html.'</div>';
					$html = $html.'</td>';
					$html = $html.'<td class="text-center">';
					$html = $html.'</td>'.$val['catalogueid'];
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
						$html = $html.'<a type="button" href="'.(site_url('attribute/backend/catalogue/update/'.$val['id'].'')).'" class="btn btn-sm btn-primary mr5"><i class="fa fa-edit"></i></a>';
						$html = $html.'<a type="button" class="btn btn-sm btn-danger ajax-delete" data-title="Lưu ý: Khi bạn xóa danh mục, toàn bộ bài viết trong nhóm này sẽ bị xóa. Hãy chắc chắn bạn muốn thực hiện chức năng này!" data-id="'.$val['id'].'" data-module="attribute"><i class="fa fa-trash"></i></a>';
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
