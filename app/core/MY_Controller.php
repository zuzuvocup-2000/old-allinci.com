<?php
(defined('BASEPATH')) or exit('No direct script access allowed');

class MY_Controller extends MX_Controller {
	
	public $url;
	public $auth;
	public $general;
	public $currentTime;
	public $search;
	public $replace;
	public $status;

	
	public function __construct(){
		$this->url = substr(APPPATH, 0, -4);
		// $cache_folder_file = glob($this->url.'cache\*');
		// if(count($cache_folder_file) >= 2){
		// 	foreach($cache_folder_file as $key => $val){
		// 		unlink($val);
		// 	}
		// }

		
		$this->search = array('/\n/', // replace end of line by a space
			'/\>[^\S ]+/s', // strip whitespaces after tags, except space
			'/[^\S ]+\</s', // strip whitespaces before tags, except space
			'/(\s)+/s' // shorten multiple whitespace sequences
		);
		$this->replace = array(
			' ',
			'>',
			'<',
			'\\1'
		);
		parent::__construct();
		$this->load->library(array(
			'commonbie','cart'
		));
		$this->load->model(array(
			'dashboard/Autoload_Model'
		));
		$this->auth = $this->commonbie->CheckBackendAuthentication();
		$this->currentTime =  gmdate('Y-m-d H:i:s', time() + 7*3600);
		
		/* LOAD TOÀN BỘ PHẦN CẤU HÌNH HỆ THỐNG RA */
		$system = $this->Autoload_Model->_get_where(array(
			'select' => 'keyword, content',
			'table' => 'general',
			'order_by' => 'keyword asc',
		), TRUE);
		
		if(isset($system) && is_array($system) && count($system)){
			foreach($system as $key => $val){
				$this->general[$val['keyword']] = $val['content'];
			}
		}

		// if($this->closed()){
		// 	echo modules::run('homepage/home/close_website');
		// 	die();
		// }

	}
	
	
	public function closed(){
		$this->status = $this->general['website_status'];
        if(isset($this->status) && $this->status == 'close'){
        	return true;
        }

        return false;
	}
	
	public function load_extend_script(){
		return true;
	}

}