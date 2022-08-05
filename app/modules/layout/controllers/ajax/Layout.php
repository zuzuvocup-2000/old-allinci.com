<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Layout extends MY_Controller {

	public function __construct(){
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library('nestedsetbie', array('table' => 'navigation'));
	}
}


