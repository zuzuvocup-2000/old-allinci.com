<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filenotfound extends MY_Controller {


	public function __construct(){
		parent::__construct();
	}
	public function index(){
		$data['template'] = 'homepage/frontend/home/filenotfound';
		$this->load->view('homepage/frontend/layout/home', isset($data)?$data:NULL);
	}
	
		
}
