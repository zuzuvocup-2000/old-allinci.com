<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogue extends MY_Controller {

	public function __construct(){
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
	}
	
	public function ajax_delete(){
		$error['flag'] = 0;
		$error['message'] = '';
		$permission = 'user/backend/catalogue/delete';
		if(in_array($permission, json_decode($this->auth['permission'], TRUE)) == false){
			$error['flag'] = 1;
			$error['message'] = 'Bạn không có quyền sử dụng chức năng này';
			echo json_encode(array(
				'error' => $error,
			));die();
		}
		
		
		$module = $this->input->post('module');
		$id = $this->input->post('id');
		
		$flag = $this->Autoload_Model->_delete(array(
			'where' => array('catalogueid' => $id),
			'table' => 'user',
		));
		if($flag > 0){
			$flag_2 = $this->Autoload_Model->_delete(array(
				'where' => array('id' => $id),
				'table' => 'user_catalogue',
			));
			if($flag_2 > 0){
				$error['message'] = 'Xóa nhóm thành viên thành công';
			}
		}else {
			$error['flag'] = 1;
			$error['message'] = 'Có lỗi xảy ra trong quá trình xóa dữ liệu, vui lòng thử lại';
		}
		echo json_encode(array(
			'error' => $error,
		));die();
	}
}
