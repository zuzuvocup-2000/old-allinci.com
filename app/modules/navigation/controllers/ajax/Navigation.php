<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Navigation extends MY_Controller {

	public function __construct(){
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library('nestedsetbie', array('table' => 'navigation'));
	}
	
	public function delete(){
		$id = (int)$this->input->post('id');
		$detailNavigation = $this->Autoload_Model->_get_where(array(
			'select' => 'id, lft, rgt',
			'table' => 'navigation',
			'where' => array('id' => $id)
		));
		
		if(isset($detailNavigation) && is_array($detailNavigation) && count($detailNavigation)){
			$flag = $this->Autoload_Model->_delete(array(
				'where' => array('lft >' => $detailNavigation['lft'],'rgt <' => $detailNavigation['rgt']),
				'table' => 'navigation',
			));
		}
		
	}
	
	public function drag(){
		$post = json_decode($this->input->post('post'), TRUE);
		$catalogueid = $this->input->post('catalogueid');
		if(isset($post) && is_array($post) && count($post)){
			foreach($post as $key => $val){
				$_update_1_st['order'] = count($post) - $key;
				$_update_1_st['catalogueid'] = $catalogueid;
				$_update_1_st['parentid'] = 0;
				$flag_1_st = $this->Autoload_Model->_update(array(
					'where' => array('id' => $val['id']),
					'table' => 'navigation',
					'data' => $_update_1_st,
				));
				
				if(isset($val['children']) && is_array($val['children']) && count($val['children'])){
					$this->navigation_recursive($val['children'], $val['id']);
				}
			}
		}
		$this->nestedsetbie->Get('level ASC, order ASC');
		$this->nestedsetbie->Recursive(0, $this->nestedsetbie->Set());
		$this->nestedsetbie->Action();
	}
	
	public function navigation_recursive($array = '', $parentid = 0){
		if(isset($array) && is_array($array) && count($array)){
			foreach($array as $key => $val){
				$_update_1_st['order'] = count($array) - $key;
				$_update_1_st['parentid'] = $parentid;
				$_update_1_st['catalogueid'] = 0;
				$flag_1_st = $this->Autoload_Model->_update(array(
					'where' => array('id' => $val['id']),
					'table' => 'navigation',
					'data' => $_update_1_st,
				));
				if(isset($val['children']) && is_array($val['children']) && count($val['children'])){
					$this->navigation_recursive($val['children'], $val['id']);
				}
			}
		}
		return 1;
	}
	
}


