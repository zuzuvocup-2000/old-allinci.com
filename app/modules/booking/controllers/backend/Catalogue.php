<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogue extends MY_Controller {

	public $module;
	function __construct() {
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library(array('configbie'));
		$this->load->library('nestedsetbie', array('table' => 'booking_catalogue'));
		$this->module = 'booking_catalogue';
	}
	public function view($page = 1){
		$this->commonbie->permission("booking/backend/catalogue/view", $this->auth['permission']);
		$page = (int)$page;
		$data['from'] = 0;
		$data['to'] = 0;
		
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 20;
		$keyword = $this->input->get('keyword');
		if(!empty($keyword)){
			$keyword = '(title LIKE \'%'.$keyword.'%\' OR description LIKE \'%'.$keyword.'%\')';
		}
		$config['total_rows'] = $this->Autoload_Model->_get_where(array(
			'table' => $this->module,
			'keyword' => $keyword,
			'count' => TRUE,
		));
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('article/backend/catalogue/view');
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
			$data['bookingList'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id, date, time_start, time_end, publish, (SELECT fullname FROM user WHERE user.id = booking_catalogue.userid_created) as user_created',
				'table' => $this->module,
			), TRUE);
		}

		$data['script'] = 'booking';
		$data['config'] = $config;
		$data['template'] = 'booking/backend/catalogue/view';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function Create(){
		$this->commonbie->permission("booking/backend/catalogue/create", $this->auth['permission']);
		if($this->input->post('create')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('post_date', 'Ngày đặt lịch', 'trim|required|callback__CheckDate');
			$this->form_validation->set_rules('post_time_start', 'Thời gian bắt đầu', 'trim|required');
			$this->form_validation->set_rules('post_time_end', 'Thời gian kết thúc', 'trim|required');
			if($this->form_validation->run($this)){
				$time_start = $this->input->post('post_time_start');
				$time_end = $this->input->post('post_time_end');
				
				$post_date = $this->input->post('post_date');
				if(strpos($post_date , ',')){
					$array_date = explode($post_date, ',');
				}else{
					$array_date = array($post_date);
				}
				if(isset($array_date) && check_array($array_date)){
					foreach ($array_date as $keyDate => $valDate) {
						$_insert = array(
							'date' => convert_time($valDate),
							'time_start' => $time_start,
							'time_end' => $time_end,
							'step' => $this->input->post('step'),
							'userid_created' => $this->auth['id'],
							'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
						);
						if(isset($_insert) && check_array($_insert)){
							$resultid = $this->Autoload_Model->_create(array(
								'table' => $this->module ,
								'data' => $_insert,
							));
						}
						if($resultid > 0){
							$listStart = $this->input->post('input_time_start[]');
							$listEnd = $this->input->post('input_time_end[]');
							if(isset($listStart) && is_array($listStart) && count($listStart)){
								foreach ($listStart as $key => $value) {
									$_insert_rela[] = array(
										'catalogueid' => $resultid,
										'start' => $value,
										'end' => $listEnd[$key],
										'status' => 0,
									);
								}
								$this->Autoload_Model->_create_batch(array(
									'table' => 'booking',
									'data' => $_insert_rela,
								));
							}
						}
					}
				}
				

				$this->session->set_flashdata('message-success', 'Thêm lịch ngày mới thành công');
				redirect('booking/backend/catalogue/view');
			}
		}
		$data['script'] = 'booking';
		$data['isMultiDatesPicker'] = true;
		$data['template'] = 'booking/backend/catalogue/create';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	public function Update($id = 0){
		$this->commonbie->permission("booking/backend/catalogue/update", $this->auth['permission']);
		$id = (int)$id;
		$bookingCata = $this->Autoload_Model->_get_where(array(
			'select' => 'id, date, time_start, time_end, step',
			'table' => $this->module,
			'where' => array('id' => $id),
		));
		$bookingDetail = $this->Autoload_Model->_get_where(array(
			'select' => 'id, start, end, status',
			'table' => 'booking',
			'where' => array('catalogueid' => $id),
		), true);
		$listIdRela = getColumsInArray($bookingDetail, 'id');
		$data['bookingCata'] = $bookingCata;
		$data['bookingDetail'] = $bookingDetail;
		if(!isset($bookingCata) || is_array($bookingCata) == false || count($bookingCata) == 0){
			$this->session->set_flashdata('message-danger', 'Lịch ngày không tồn tại');
			redirect('booking/backend/catalogue/view');
		}
		if($this->input->post('update')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('post_date', 'Ngày đặt lịch', 'trim|required');
			$this->form_validation->set_rules('post_time_start', 'Thời gian bắt đầu', 'trim|required');
			$this->form_validation->set_rules('post_time_end', 'Thời gian kết thúc', 'trim|required');
			if($this->form_validation->run($this)){
				$time_start = $this->input->post('post_time_start');
				$time_end = $this->input->post('post_time_end');
				$_update = array(
					'date' => convert_time($this->input->post('post_date')),
					'time_start' => $time_start,
					'time_end' => $time_end,
					'step' => $this->input->post('step'),
					'userid_updated' => $this->auth['id'],
					'updated' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);
				$flag = $this->Autoload_Model->_update(array(
					'where' => array('id' => $id),
					'table' => $this->module ,
					'data' => $_update,
				));

				if($flag > 0){
					$listStart = $this->input->post('input_time_start[]');
					$listEnd = $this->input->post('input_time_end[]');

					//lấy dữ liệu trùng trong csdl
					if(isset($listStart) && check_array($listStart)){
						$query = '';
						foreach ($listStart as $keyStart => $valStart)	 {
							$query = $query.' OR (start = "'.$valStart.'" AND end = "'.$listEnd[$keyStart].'")';
							$postTime[] = array('start' => $valStart, 'end' => $listEnd[$keyStart]) ;
						}
						$query = substr( $query, 4, strlen($query));
						$query = 'catalogueid = '.$id." AND (".$query.')';
						$postCommonRela = $this->Autoload_Model->_get_where(array(
							'select' => 'id, start, end',
							'table' => 'booking',
							'query' => $query,
						), true);

						if(isset($postCommonRela) && check_array($postCommonRela) == false){
							if(isset($postTime) && check_array($postTime)){
								foreach ($postTime as $keyTime => $valTime) {
									$_insert_rela[] = array(
										'catalogueid' => $id,
										'start' => $valTime['start'],
										'end' => $valTime['end'],
										'status' => 0,
									);
								}
									
								$this->Autoload_Model->_create_batch(array(
									'table' => 'booking',
									'data' => $_insert_rela,
								));
							}
						}else{
							if(isset($postTime) && check_array($postTime)){
								// id trùng trong csdl( sẽ được dữ lại)
								$postCommonId = getColumsInArray($postCommonRela, 'id');
								// xóa bản ghi mà không nằm trong postCommonId gửi lên
								$idRemove = array_diff($listIdRela, $postCommonId);
								if(isset($idRemove) && check_array($idRemove)){
									$this->Autoload_Model->_delete(array(
										'table' => 'booking',
										'where_in' => $idRemove,
										'where_in_field' => 'id',
									), true);
								}

								// thêm bản ghi mà không nằm trong listID trong csdl
								foreach ($postCommonRela as $keyRela => $valRela) {
									foreach ($postTime as $keyTime => $valTime) {
										if($valRela['start'] == $valTime['start'] && $valRela['end'] == $valTime['end']){
											unset($postTime[$keyTime]);
										}
									}
								}
								if(isset($postTime) && check_array($postTime)){
									foreach ($postTime as $keyTime => $valTime) {
										$_insert_rela[] = array(
											'catalogueid' => $id,
											'start' => $valTime['start'],
											'end' => $valTime['end'],
											'status' => 0,
										);
									}
										
									$this->Autoload_Model->_create_batch(array(
										'table' => 'booking',
										'data' => $_insert_rela,
									));
								}
							}
						}
					}


					$this->session->set_flashdata('message-success', 'Cập nhật lịch ngày thành công');
					redirect('booking/backend/catalogue/view');
				}

			}
		}
		$data['script'] = 'booking';
		$data['isMultiDatesPicker'] = true;
		$data['template'] = 'booking/backend/catalogue/update';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}

	public function _CheckDate($date = ''){
		$originaldate = $this->input->post('original_date');
		if($date != $originaldate){
			$date = convert_time($date);
			$booking = $this->Autoload_Model->_get_where(array(
				'select' => 'id',
				'table' => 'booking_catalogue',
				'where' => array('date' => $date),
				'count' => TRUE
			));
			if($booking > 0){
				$this->form_validation->set_message('_CheckDate','Ngày bạn chọn đã được tạo lịch, hãy chọn một ngày khác');
				return false;
			}
		}
		return true;
	}
	

}
