<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Catalogue extends MY_Controller {

	public function __construct(){
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
	}

	public function status(){
		$id = $this->input->post('objectid');
		$object = $this->Autoload_Model->_get_where(array(
			'select' => 'id, publish',
			'table' => 'contact_catalogue',
			'where' => array('id' => $id),
		));
		
		$_update['publish'] = (($object['publish'] == 1)?0:1);
		$this->Autoload_Model->_update(array(
			'where' => array('id' => $id),
			'table' => 'contact_catalogue',
			'data' => $_update,
		));
	}

	public function listCatalogue(){
		$page = (int)$this->input->get('page');
		$data['from'] = 0;
		$data['to'] = 0;

		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 5;
		$keyword = $this->db->escape_like_str($this->input->get('keyword'));
		$config['total_rows'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id',
			'table' => 'contact_catalogue',
			'count' => TRUE,
			'keyword' => '(title LIKE \'%'.$keyword.'%\')',
		));
		$html = '';
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('contact/backend/catalogue/view');
			$config['suffix'] = $this->config->item('url_suffix').(!empty($_SERVER['QUERY_STRING'])?('?'.$_SERVER['QUERY_STRING']):'');
			$config['first_url'] = $config['base_url'].$config['suffix'];
			$config['per_page'] = $perpage;
			$config['cur_page'] = $page;
			$config['page'] = $page;
			$config['uri_segment'] = 5;
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
				'select' => 'id, title, publish, created,(SELECT fullname FROM user WHERE user.id = contact_catalogue.userid_created) as user_created',
				'table' => 'contact_catalogue',
				'start' => $page * $config['per_page'],
				'limit' => $config['per_page'],
				'order_by' => ' title asc',
				'keyword' => '(title LIKE \'%'.$keyword.'%\')',
			), TRUE);	
			foreach ($listCatalogue as $key => $val) {
				$listCatalogue[$key]['countContact'] = $this->Autoload_Model->_get_where(array(
					'select' => 'id',
					'table' => 'contact',
					'count' => TRUE,
					'where' => array('catalogueid' => $val['id']),
				));
			}
			if(isset($listCatalogue) && is_array($listCatalogue) && count($listCatalogue)){ 
				foreach($listCatalogue as $key => $val){ 


					$html = $html .'<tr class="gradeX" id="">';
					$html = $html .'<td class="pd-top text-center" >';
					$html = $html .'<input type="checkbox" name="checkbox[]" value="'.$val['id'].'" class="checkbox-item">';
					$html = $html .'<label for="" class="label-checkboxitem"></label>';
					$html = $html .'</td>';
					$html = $html .'<td class="pd-top text-center"> '.$val['id'].'</td>';
					$html = $html .'<td class="pd-top"><a class="maintitle" style="" href="'.site_url('contact/backend/contact/view?catalogueid='.$val['id'].'').'" title="">'.$val['title'].'</a></td>';
					$html = $html .'<td class="pd-top text-center"> '.$val['countContact'].'</td> ';
					$html = $html .'<td class="pd-top text-center"> '. $val['user_created'].'</td> ';
					$html = $html .'<td class="pd-top text-center">'.gettime($val['created'],'d/m/Y').'</td> ';
					$html = $html .'<td class="pd-top status">';
					$html = $html .'<div class="switch">';
					$html = $html .'<div class="onoffswitch">';
					$html = $html .'<input type="checkbox" '.(($val['publish'] == 1) ? 'checked=""' : '').' class="onoffswitch-checkbox publish" data-id="'.$val['id'].'" id="publish-'.$val['id'].'">';
					$html = $html .'<label class="onoffswitch-label" for="publish-'.$val['id'].'">';
					$html = $html .'<span class="onoffswitch-inner"></span>';
					$html = $html .'<span class="onoffswitch-switch"></span>';
					$html = $html .'</label>';
					$html = $html .'</div>';
					$html = $html .'</div>';
					$html = $html .'</td>';
					$html = $html .'<td class="text-center actions">';
					$html = $html .'<a type="button" href="'.site_url('contact/backend/catalogue/update/'.$val['id'].'').'" class="btn btn-primary btn-update"><i class="fa fa-edit"></i></a>';
					$html = $html .'<a type="button" class="btn btn-danger btn-delete ajax-delete"  data-id="'.$val['id'].'" data-title="Lưu ý: Khi bạn xóa nhóm, toàn bộ liên hệ trong nhóm này sẽ bị xóa. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!" data-router="" data-module="contact_catalogue" data-child=""><i class="fa fa-trash"></i></a>';
					$html = $html .'</td>';
					$html = $html .'</tr>';

				}
			}
		}
		else{ 
			$html = $html .'<tr>
			<td colspan="9"><small class="text-danger">Không có dữ liệu phù hợp</small></td>
			</tr> ';
		}
		echo json_encode(array(
			'pagination' => (isset($listPagination)) ? $listPagination : '',
			'html' => (isset($html)) ? $html : '',
			'total' => $config['total_rows'],
		));die();
	}

	/* ================ delete ======================= */
	public function ajax_delete(){
		$param['module'] = $this->input->post('module');
		$param['id'] = (int)$this->input->post('id');
		$param['child'] = (int)$this->input->post('child');

		$flag = $this->Autoload_Model->_delete(array(
			'where' => array('id' => $param['id']),
			'table' => $param['module']
		));
		if($flag >0){
			$flagChil = $this->Autoload_Model->_delete(array(
				'where' => array('catalogueid' => $param['id']),
				'table' => 'contact',
			));
			echo $flag; die();
		}
	}

	public function ajax_group_delete(){
		$param = $this->input->post('param');
// print_r($param);die();
		if(isset($param['list']) && is_array($param['list']) && count($param['list'])){
			foreach($param['list'] as $key => $val){
				$result = $this->Autoload_Model->_delete(array(
					'where' => array('id' => $val),
					'table' => $param['module'],
				));
// echo $result; die();
				if($result > 0){
					$countChild = $this->Autoload_Model->_get_where(array(
						'where' => array('catalogueid' => $val),
						'table' => 'contact',
						'count' => TRUE,
					));
					if($countChild >0){
						$resultChild = $this->Autoload_Model->_delete(array(
							'where' => array('catalogueid' => $val),
							'table' => 'contact',
						));
						if($resultChild <= 0){
							$error = array(
								'flag' => 1,
								'message' => 'Xóa không thành công phần tử con trong nhóm',
							);
							echo json_encode(array(
								'error' => $error,
							));die;
						}
					}}else{
						$error = array(
							'flag' => 1,
							'message' => 'Xóa không thành công',
						);
						echo json_encode(array(
							'error' => $error,
						));die;
					}
				}
				$error = array(
					'flag' => 0,
					'message' => '',
				);
				echo json_encode(array(
					'error' => $error,
				));die;
			}
		}

	}