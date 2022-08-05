<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogue extends MY_Controller {

	public function __construct(){
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
	}
	public function ishome(){
		$id = $this->input->post('objectid');
		$object = $this->Autoload_Model->_get_where(array(
			'select' => 'id, ishome',
			'table' => 'article_catalogue',
			'where' => array('id' => $id),
		));
		
		$_update['ishome'] = (($object['ishome'] == 1)?0:1);
		$this->Autoload_Model->_update(array(
			'where' => array('id' => $id),
			'table' => 'article_catalogue',
			'data' => $_update,
		));
	}
	public function highlight(){
		$id = $this->input->post('objectid');
		$object = $this->Autoload_Model->_get_where(array(
			'select' => 'id, highlight',
			'table' => 'article_catalogue',
			'where' => array('id' => $id),
		));
		
		$_update['highlight'] = (($object['highlight'] == 1)?0:1);
		$this->Autoload_Model->_update(array(
			'where' => array('id' => $id),
			'table' => 'article_catalogue',
			'data' => $_update,
		));
	}
	
	public function status(){
		$id = $this->input->post('objectid');
		$object = $this->Autoload_Model->_get_where(array(
			'select' => 'id, publish',
			'table' => 'article_catalogue',
			'where' => array('id' => $id),
		));
		
		$_update['publish'] = (($object['publish'] == 1)?0:1);
		$this->Autoload_Model->_update(array(
			'where' => array('id' => $id),
			'table' => 'article_catalogue',
			'data' => $_update,
		));
	}
	
	public function listCatalogue(){
		$page = (int)$this->input->get('page');
		$data['from'] = 0;
		$data['to'] = 0;
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 20;
		$keyword = $this->db->escape_like_str($this->input->get('keyword'));
		$config['total_rows'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id',
			'table' => 'article_catalogue',
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
				'select' => 'id, title, publish, ishome, highlight, created, order, level, (SELECT fullname FROM user WHERE user.id = article_catalogue.userid_created) as user_created',
				'table' => 'article_catalogue',
				'keyword' => '(title LIKE \'%'.$keyword.'%\' OR description LIKE \'%'.$keyword.'%\')',
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'order_by' => 'lft asc',
			), TRUE);	
		}
		
		$html = '';
		 if(isset($listCatalogue) && is_array($listCatalogue) && count($listCatalogue)){ 
			 foreach($listCatalogue as $key => $val){ 
				$html = $html .'<tr class="gradeX">';
					$html = $html.'<td>';
						$html = $html.'<input type="checkbox" name="checkbox[]" value="'.$val['id'].'" class="checkbox-item">';
						$html = $html.'<label for="" class="label-checkboxitem"></label>';
					$html = $html.'</td>';
					$html = $html.'<td>'.$val['id'].'</td>';
					$html = $html.'<td><a class="maintitle" style="'.(($val['level'] == 1) ? 'font-weight:600;' : '').'" href="'.site_url('articles/backend/articles/view?cataloguesid='.$val['id'].'').'" title="">'.(str_repeat('|----', (($val['level'] > 0)?($val['level'] - 1):0)).$val['title']).' (0)</a></td>';
					$html = $html.'<td>';
						$html = $html.'<input type="text" name="order['.$val['id'].']" value="'.$val['order'].'" class="form-control" placeholder="Vị trí" style="width:50px;">';
					$html = $html.'</td>';
					$html = $html.'<td>'.$val['user_created'].'</td>';
					$html = $html.'<td>'.gettime($val['created'],'d/m/Y').'</td>';
					
					$html = $html.'<td class="tb_ishome">';
						$html = $html.'<div class="switch">';
							$html = $html.'<div class="onoffswitch">';
								$html = $html.'<input type="checkbox" '.(($val['ishome'] == 1) ? 'checked=""' : '').' class="onoffswitch-checkbox ishome" data-id="'.$val['id'].'" id="ishome-'.$val['id'].'">';
								$html = $html.'<label class="onoffswitch-label" for="ishome-'.$val['id'].'">';
									$html = $html.'<span class="onoffswitch-inner"></span>';
									$html = $html.'<span class="onoffswitch-switch"></span>';
								$html = $html.'</label>';
							$html = $html.'</div>';
						$html = $html.'</div>';
					$html = $html.'</td>';

					$html = $html.'<td class="tb_highlight">';
						$html = $html.'<div class="switch">';
							$html = $html.'<div class="onoffswitch">';
								$html = $html.'<input type="checkbox" '.(($val['highlight'] == 1) ? 'checked=""' : '').' class="onoffswitch-checkbox highlight" data-id="'.$val['id'].'" id="highlight-'.$val['id'].'">';
								$html = $html.'<label class="onoffswitch-label" for="highlight-'.$val['id'].'">';
									$html = $html.'<span class="onoffswitch-inner"></span>';
									$html = $html.'<span class="onoffswitch-switch"></span>';
								$html = $html.'</label>';
							$html = $html.'</div>';
						$html = $html.'</div>';
					$html = $html.'</td>';

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
						$html = $html.'<a type="button" href="'.(site_url('article/backend/catalogue/update/'.$val['id'].'')).'" class="btn btn-sm btn-primary mr5"><i class="fa fa-edit"></i></a>';
						$html = $html.'<a type="button" class="btn btn-sm btn-danger ajax-delete" data-title="Lưu ý: Khi bạn xóa danh mục, toàn bộ bài viết trong nhóm này sẽ bị xóa. Hãy chắc chắn bạn muốn thực hiện chức năng này!" data-id="'.$val['id'].'" data-module="article_catalogue"><i class="fa fa-trash"></i></a>';
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
