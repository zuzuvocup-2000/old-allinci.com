<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BackendAuth_Model extends MY_Model {
	
	private $table;
	
	public function __construct(){
		parent::__construct();
		$this->table = 'user';
	}
	
	
	
	public function create_account($_insert = ''){
		$resultid = 0;
		if(isset($_insert) && is_array($_insert) && count($_insert)){
			$this->db->insert($this->table, $_insert);
			$this->db->flush_cache();
			$resultid = $this->db->insert_id();
		}
		return $resultid;
	}
	
	
	
	
	
	
	
}
