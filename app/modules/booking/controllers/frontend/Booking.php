<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends MY_Controller {
	public $module;
	function __construct() {
		parent::__construct();
		$this->load->library(array('configbie'));
		$this->load->library('nestedsetbie', array('table' => 'booking_catalogue'));
		$this->module = 'booking';
	}
	public function index(){
		$query = 'tb2.date ="'.gettime($this->currentTime, 'Y-m-d').' 00:00:00"';
		$bookingList = $this->Autoload_Model->_get_where(array(
			'select' => 'tb1.id, tb1.start, tb1.end, tb1.status, tb2.date',
			'table' => 'booking as tb1',
			'where' => array('publish' => 0),
			'query' => $query,
			'join' => array(
				array('booking_catalogue as tb2', 'tb1.catalogueid = tb2.id', 'inner'),
			),
		), true);
		if($this->input->post('create')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('fullname', 'Họ và tên', 'trim|required');
			$this->form_validation->set_rules('phone', 'Số điện thoại', 'trim|required');
			$this->form_validation->set_rules('address_detail', 'Địa chỉ chi tiết', 'trim|required');
			$this->form_validation->set_rules('input_time', 'lịch hẹn', 'trim|required');
			if($this->form_validation->run($this)){
				$_insert = array(
					'code' => CodeRender('order'),
					'fullname' => $this->input->post('fullname'),
					'email' => $this->input->post('email'),
					'note' => $this->input->post('note'),
					'cityid' => $this->input->post('cityid'),
					'districtid' => $this->input->post('districtid'),
					'wardid' => $this->input->post('wardid'),
					'address_detail' => $this->input->post('address_detail'),
					'status' => '1',
				);
				$input_time = $this->input->post('input_time');

				// cập nhật bảng booking_relationship
				$_update = array(
					'status' => '1',
				);
				$flag = $this->Autoload_Model->_update(array(
					'where' => array('id' => $input_time),
					'table' => 'booking' ,
					'data' => $_update,
				));
				
				if($flag > 0){
					$this->session->set_flashdata('message-success', 'Bạn đã đặt lịch thành công');
					redirect('');
				}
			}
		};
		$data['bookingList'] = $bookingList;
		$this->load->helper(array('myfrontendcommon'));
		$data['template'] = 'booking/frontend/booking';
		$this->load->view('homepage/frontend/layout/home', isset($data)?$data:NULL);
	}
}
