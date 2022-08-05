<?php     
defined('BASEPATH') OR exit('No direct script access allowed');

class Location extends MY_Controller {

	private $module;
	public function __construct() {
        parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == false || count($this->auth) == 0) redirect(BACKEND_DIRECTORY);
		$this->module = 'location';
    }
	 public function getmodule(){
    	$supplierid  = $this->input->get('supplierid');
    	if(isset($supplierid)){
    		$moduleList = $this->Autoload_Model->_get_where(array(
				'select' => 'id, title, type',
				'table' => $this->module,
				'where' => array('supplierid' => $supplierid),
			), true);
			if(isset($moduleList) && check_array($moduleList)){
				echo json_encode($moduleList);die;
			}
    	}else{
    		$moduleList = $this->Autoload_Model->_get_where(array(
				'select' => 'id, title, type',
				'table' => $this->module,
			), true);
			if(isset($moduleList) && check_array($moduleList)){
				echo json_encode($moduleList);die;
			}
    	}
    	echo false;
    }
	public function moduleList(){
		$page = (int)$this->input->get('page');
		$data['from'] = 0;
		$data['to'] = 0;

		$type  = ($this->input->get('type')) ? $this->input->get('type') :  '';
		$keyword  = ($this->input->get('keyword')) ? $this->input->get('keyword') :  '';
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 20;
		$keyword = $this->db->escape_like_str($keyword);
		$config['total_rows'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id',
			'table' => $this->module,
			'keyword' => '(title LIKE \'%'.$keyword.'%\' )',
			'count' => TRUE,
		));

		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('location/backend/location/view');
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
			$data['moduleList'] = $this->Autoload_Model->_get_where(array(
				'table'=>$this->module,
				'where' => array('type' => $type),
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'keyword' => '(title LIKE \'%'.$keyword.'%\' )',
				'select'=>'id, status, type, title, (SELECT title FROM supplier WHERE location.supplierid = supplier.id) as supplier,  (SELECT fullname FROM user WHERE location.userid_created = user.id) as user_created,  created, updated',
			),true);
		}
		$html = '';
		if(isset($data['moduleList']) && is_array($data['moduleList']) && count($data['moduleList'])){
			foreach($data['moduleList'] as $key => $val){

				$html=$html.'<tr class="gradeX" id="post-'.$val['id'].'">';
					$html=$html.'<td>';
						$html=$html.'<input type="checkbox" name="checkbox[]" value="'.$val['id'].'" class="checkbox-item">';
						$html=$html.'<label for="" class="label-checkboxitem"></label>';
					$html=$html.'</td>';
					$html=$html.'<td>'.$val['title'].'</td>';
					$html=$html.'<td>'.$val['supplier'].'</td>';
					$html=$html.'<td class="text-center">'.$val['user_created'].'</td>';
					$html=$html.'<td class="text-center">'.gettime($val['created'],'d/m/Y').'</td>';
					$html=$html.'<td>';
						$html=$html.'<div class="switch">';
							$html=$html.'<div class="onoffswitch">';
								$html=$html.'<input type="checkbox" '.(($val['status'] == 1) ? 'checked=""' : '').' class="onoffswitch-checkbox status" data-id="'.$val['id'].'" id="status-'.$val['id'].'">';
								$html=$html.'<label class="onoffswitch-label" for="status-'.$val['id'].'">';
									$html=$html.'<span class="onoffswitch-inner"></span>';
									$html=$html.'<span class="onoffswitch-switch"></span>';
								$html=$html.'</label>';
							$html=$html.'</div>';
						$html=$html.'</div>';
					$html=$html.'</td>';
					$html=$html.'<td class="text-center">';
						$html=$html.'<a type="button" href="'.site_url('location/backend/location/update/'.$val['id'].'').'" class="btn btn-primary  m-r-sm"><i class="fa fa-edit"></i></a>';
						$html=$html.'<a type="button" class="btn btn-danger ajax-delete" data-id="'.$val['id'].'" data-title="Lưu ý: Dữ liệu sẽ không thể khôi phục. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!"  data-module="location"><i class="fa fa-trash"></i></a>';
					$html=$html.'</td>';
				$html=$html.'</tr>';
			}
		}else{
			$html = '<tr>
						<td colspan="100">
							<small class="text-danger">Không có dữ liệu phù hợp</small>
						</td>
					</tr>';
		}
		echo json_encode(array(
			'html' => (isset($html)) ? $html : '',
			'pagination' => (isset($listPagination)) ? $listPagination : '',
			'total' => $config['total_rows'],
		));die();
	}

	public function defaul(){
		$id = $this->input->post('objectid');
		$detail = $this->Autoload_Model->_get_where(array(
			'table' => $this->module,
			'select'=>'id, status, supplierid',
			'where'=> array('id'=>$id),
		));
		if($detail['status'] == 0){
			if(isset($detail) && check_array($detail)){
				foreach ($detail as $key => $val) {
					$_update['status'] = 0;
					$this->Autoload_Model->_update(array(
						'where' => array('supplierid' => $detail['supplierid']),
						'table' => $this->module,
						'data' => $_update,
					));
				}

				$_update['status'] = 1;
				$this->Autoload_Model->_update(array(
					'where' => array('id' => $id),
					'table' => $this->module,
					'data' => $_update,
				));
			}
		}else{
			$_update['status'] = 0;
			$this->Autoload_Model->_update(array(
				'where' => array('id' => $id),
				'table' => $this->module,
				'data' => $_update,
			));
		}

		
		

	}
}
