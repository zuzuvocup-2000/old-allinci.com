<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Slide extends MY_Controller{

	public function __construct(){
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library(array('configbie'));
	}

	public function View(){
		$this->commonbie->permission("general/backend/slide/view", $this->auth['permission']);
		
		$slide = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title',
			'table' => 'slide_catalogue',
			'order_by' => 'title asc, id desc'
		), TRUE);
		$data['slideCatalogue'][0] = '[Chọn nhóm slide]';
		if(isset($slide) && is_array($slide) && count($slide)){
			foreach($slide as $key => $val){
				$data['slideCatalogue'][$val['id']] = $val['title'];
			}
		}
		
		$data['script'] = 'slide';
		$data['template'] = 'general/backend/slide/view';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	

}
