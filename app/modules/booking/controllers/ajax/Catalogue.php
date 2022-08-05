<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogue extends MY_Controller {

	public function __construct(){
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
	}
	
	// ++++++++++++++++++++++++++++++++++++xóa SP++++++++++++++++++++++++++++++++++++
	public function ajax_delete_booking(){
		$this->commonbie->permission("booking/backend/catalogue/delete", $this->auth['permission']);
		$param['id'] = (int)$this->input->post('id');
		$flag = $this->Autoload_Model->_delete(array(
			'where' => array('id' => $param['id']),
			'table' => 'booking_catalogue'
		));
		$flag = $this->Autoload_Model->_delete(array(
			'table' => 'booking',
			'where' => array('catalogueid' => $param['id']),
		));
		echo $flag;die();
	}
	public function ajax_delete_booking_all(){
		$this->commonbie->permission("booking/backend/catalogue/delete", $this->auth['permission']);
		$param = $this->input->post('post');
		$id  = $param['id_checked'];
		$flag = $this->Autoload_Model->_delete(array(
			'table' => 'booking_catalogue',
			'where_in' => $id,
			'where_in_field' => 'id',
		));
		$flag = $this->Autoload_Model->_delete(array(
			'table' => 'booking',
			'where_in' => $id,
			'where_in_field' => 'catalogueid',
		));
		echo $flag;die();
	}

	public function status(){
		$id = $this->input->post('objectid');
		$object = $this->Autoload_Model->_get_where(array(
			'select' => 'id, publish',
			'table' => 'booking_catalogue',
			'where' => array('id' => $id),
		));
		
		$_update['publish'] = (($object['publish'] == 1)?0:1);
		$this->Autoload_Model->_update(array(
			'where' => array('id' => $id),
			'table' => 'booking_catalogue',
			'data' => $_update,
		));
	}

	public function bookingList(){
		$this->commonbie->permission("booking/backend/catalogue/view", $this->auth['permission']);
		$page = (int)$this->input->get('page');
		$data['from'] = 0;
		$data['to'] = 0;
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 20;
		$keyword = $this->db->escape_like_str($this->input->get('keyword'));
		$config['total_rows'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id',
			'table' => 'booking',
			'keyword' => '(date LIKE \'%'.$keyword.'%\' )',
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
			$bookingList = $this->Autoload_Model->_get_where(array(
				'select' => 'id, date, time_start, time_end, publish, (SELECT fullname FROM user WHERE user.id = booking.userid_created) as user_created',
				'table' => 'booking',
				'keyword' => '(date LIKE \'%'.$keyword.'%\' )',
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'order_by' => 'id asc',
			), TRUE);	
		}
		
		$html = '';
		 if(isset($bookingList) && is_array($bookingList) && count($bookingList)){ 
			 foreach($bookingList as $key => $val){ 
				$html = $html .'<tr class="gradeX">';
					$html = $html.'<td>';
						$html = $html.'<input type="checkbox" name="checkbox[]" value="'.$val['id'].'" class="checkbox-item">';
						$html = $html.'<label for="" class="label-checkboxitem"></label>';
					$html = $html.'</td>';
					$html = $html.'<td>'.$val['id'].'</td>';
					$html = $html.'<td>Chi tiết lịch ngày: '.gettime($val['date'], 'd-m-y').'</td>';
					
					$html = $html.'<td>'.$val['user_created'].'</td>';
					$html = $html.'<td>'.gettime($val['time_start'],'H:i').'</td>';
					$html = $html.'<td>'.gettime($val['time_end'],'H:i').'</td>';


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
						$html = $html.'<a type="button" href="'.(site_url('booking/backend/booking/update/'.$val['id'].'')).'" class="btn btn-sm btn-primary mr5"><i class="fa fa-edit"></i></a>';
						$html = $html.'<a type="button" class="btn btn-sm btn-danger ajax_delete_booking" data-title="Lưu ý: Hãy chắc chắn bạn muốn thực hiện chức năng này!" data-id="'.$val['id'].'" data-module="booking"><i class="fa fa-trash"></i></a>';
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
