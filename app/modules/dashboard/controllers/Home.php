<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {


	public function __construct(){
		parent::__construct();
		if(!isset($this->auth)) redirect('admin');
	}

	public function index(){
		$data['time_start'] = microtime(true);
		

		$data['load_extend_script'] = $this->load_extend_script();
		$data['template'] = 'dashboard/backend/home/index';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
		
}
