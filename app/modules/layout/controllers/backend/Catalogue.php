<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogue extends MY_Controller {

	public $module;
	function __construct() {
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library(array('configbie'));
	}
	
	
	public function Create(){
		$this->commonbie->permission("layout/backend/catalogue/create", $this->auth['permission']);
		if($this->input->post('create')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('title', 'Tên vị trí', 'trim|required');
			$this->form_validation->set_rules('keyword', 'Từ khóa', 'trim|required|callback__CheckKeyword');
			if($this->form_validation->run($this)){
				$_insert = array(
					'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'keyword' => slug(htmlspecialchars_decode(html_entity_decode($this->input->post('keyword')))),
					'userid_created' => $this->auth['id'],
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);
				$resultid = $this->Autoload_Model->_create(array(
					'table' => 'layout_catalogue',
					'data' => $_insert,
				));
				if($resultid > 0){
					$this->session->set_flashdata('message-success', 'Tạo layout mới thành công');
					redirect('layout/backend/catalogue/create');
				}
			}
		}
		
		$data['template'] = 'layout/backend/catalogue/create';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function Update($id = 0){
		$this->commonbie->permission("layout/backend/catalogue/update", $this->auth['permission']);
		$id = (int)$id;
		$detailCatalogue = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, keyword',
			'table' => 'layout_catalogue',
			'where' => array('id' => $id),
		));
		if(!isset($detailCatalogue) || is_array($detailCatalogue) == false || count($detailCatalogue) == 0){
			$this->session->set_flashdata('message-danger', 'vị trí hiển thị không tồn tại');
			redirect('layout/backend/catalogue/view');
		}
		if($this->input->post('update')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('title', 'Tên vị trí', 'trim|required');
			$this->form_validation->set_rules('keyword', 'Từ khóa', 'trim|required|callback__CheckKeyword');
			if($this->form_validation->run($this)){
				$_update = array(
					'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'keyword' => slug(htmlspecialchars_decode(html_entity_decode($this->input->post('keyword')))),
					'userid_updated' => $this->auth['id'],
					'updated' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);
				$flag = $this->Autoload_Model->_update(array(
					'where' => array('id' => $id),
					'table' => 'layout_catalogue',
					'data' => $_update,
				));
				if($flag > 0){
					$this->session->set_flashdata('message-success', 'Cập nhật vị trí hiển thị thành công');
					redirect('layout/backend/catalogue/update/'.$id.'');
				}
			}
		}
		$data['detailCatalogue'] = $detailCatalogue;
		$data['template'] = 'layout/backend/catalogue/update';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function _CheckKeyword($keyword = ''){
		$keywordOriginal = $this->input->post('keyword_original');
		if($keyword != $keywordOriginal){
			$count = $this->Autoload_Model->_get_where(array(
				'select' => 'id',
				'table' => 'layout_catalogue`',
				'where' => array('keyword' => $keyword),
				'count' => TRUE
			));
			if($count > 0){
				$this->form_validation->set_message('_CheckCanonical','Đường dẫn đã tồn tại, hãy chọn một đường dẫn khác');
				return false;
			}
		}
		return true;
	}
	
	
}
