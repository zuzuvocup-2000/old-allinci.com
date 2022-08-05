<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends MY_Controller {

	public $module;
	public function __construct(){
		parent::__construct();
		$this->load->helper(array("form", "url", "captcha"));
	}

	public function view(){
		if( $this->input->post('create') ){
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('', ' / ');
			$this->form_validation->set_rules('fullname', 'Tên đầy đủ', 'trim|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('phone', 'Số điện thoại', 'trim|required');
			$this->form_validation->set_rules('title', 'Tiêu đề', 'trim|required');
			$this->form_validation->set_rules('message', 'Nội dung', 'trim|required');
			// $this->form_validation->set_rules('receiverid', 'Nơi nhận', 'trim|is_natural_no_zero');
			if ($this->form_validation->run()){
				$insert = array(
					'catalogueid' => 1,
					'fullname' => $this->input->post('fullname'),
					'email' => $this->input->post('email'),
					'phone' => $this->input->post('phone'),
					'title' => $this->input->post('title'),
					'message' => $this->input->post('message'),
					'created' => $this->currentTime,
				);
				$flag = $this->Autoload_Model->_create(array(
					'table' => 'contact',
					'data' => $insert
				));
				if($flag > 0){
					$this->session->set_flashdata('message-success', 'Thêm liên hệ mới thành công');
					redirect('lien-he');
				}
			}
		}
		$data['header'] = FALSE;
		$data['meta_title'] = 'Liên hệ';
		$data['meta_keyword'] = '';
		$data['meta_description'] = '';
		$data['template'] = 'contact/frontend/contact/view';
		$this->load->view('homepage/frontend/layout/home', isset($data)?$data:NULL);
	}



}
