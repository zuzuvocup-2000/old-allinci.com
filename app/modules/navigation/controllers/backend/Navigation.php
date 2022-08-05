<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Navigation extends MY_Controller {

	public $module;
	function __construct() {
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library(array('configbie'));
		$this->load->library('nestedsetbie', array('table' => 'navigation'));
	}
	
	public function view($page = 1){
		$this->commonbie->permission("navigation/backend/navigation/view", $this->auth['permission']);
		
		$data['script'] = 'nested';
		$data['template'] = 'navigation/backend/navigation/view';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function Create(){
		$parentid = (int)$this->input->get('parentid');
		$this->commonbie->permission("navigation/backend/navigation/create", $this->auth['permission']);
		if($this->input->post('create')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			if($parentid <= 0){
				$this->form_validation->set_rules('catalogueid', 'Vị trí tạo menu', 'trim|required|is_natural_no_zero');
			}else{
				$this->form_validation->set_rules('parentid', 'Menu cha', 'trim|required|is_natural_no_zero');
			}
			if($this->form_validation->run($this)){
				$resultid = 0;
				$menu = $this->input->post('menu');
				if(isset($menu['title']) && is_array($menu['title']) && count($menu['title'])){
					foreach($menu['title'] as $key => $val){
						if(empty($val)) continue;
						if(isset($menu['id'][$key]) && $menu['id'][$key] > 0){
							$_update[] = array(
								'id' => $menu['id'][$key],
								'title' => $val,
								'link' => $menu['link'][$key],
								'order' => $menu['order'][$key],
								'catalogueid' => (int)$this->input->post('catalogueid'),
								'parentid' => ($this->input->post('parentid')) ? $this->input->post('parentid') : $parentid,
							);
						}else{
							$_insert[] = array(
								'title' => $val,
								'link' => $menu['link'][$key],
								'order' => $menu['order'][$key],
								'catalogueid' => (int)$this->input->post('catalogueid'),
								'parentid' => ($this->input->post('parentid')) ? $this->input->post('parentid') : $parentid,
							);	
						}
						
					}
				}
				
				if(isset($_update) && is_array($_update) && count($_update)){
					$this->db->update_batch('navigation',$_update, 'id'); 
				}
				if(isset($_insert) && is_array($_insert) && count($_insert)){
					$resultid = $this->Autoload_Model->_create_batch(array(
						'data' => $_insert,
						'table' => 'navigation',
					));
				}
				
				
				if($resultid > 0){
					$this->nestedsetbie->Get('level ASC, order ASC');
					$this->nestedsetbie->Recursive(0, $this->nestedsetbie->Set());
					$this->nestedsetbie->Action();
				}
				$this->session->set_flashdata('message-success', 'Thêm menu mới thành công');
				redirect('navigation/backend/navigation/create?parentid='.$parentid);
			}
		}
		$data['script'] = 'navigation';
		$data['template'] = 'navigation/backend/navigation/create';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	
	public function Update($id = 0){
		$this->commonbie->permission("navigation/backend/navigation/create", $this->auth['permission']);
		
		$id = (int)$id;
		$detailNavigation = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, keyword',
			'table' => 'navigation_catalogue',
			'where' => array('id' => $id),
		));
		
		if(!isset($detailNavigation) || is_array($detailNavigation) == false || count($detailNavigation) == 0){
			$this->session->set_flashdata('message-danger', 'Vị trí Menu không tồn tại');
			redirect('navigation/backend/navigation/view');
		}
		
		if($this->input->post('update')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('catalogueid', 'Vị trí tạo menu', 'trim|required|is_natural_no_zero');
			if($this->form_validation->run($this)){
				$menu = $this->input->post('menu');
				$resultid = 0;
				if(isset($menu['title']) && is_array($menu['title']) && count($menu['title'])){
					foreach($menu['title'] as $key => $val){
						if(empty($val)) continue;
						if(isset($menu['id'][$key]) && $menu['id'][$key] > 0){
							$_update[] = array(
								'id' => $menu['id'][$key],
								'title' => $val,
								'link' => $menu['link'][$key],
								'order' => $menu['order'][$key],
								'catalogueid' => (int)$this->input->post('catalogueid'),
								'parentid' => 0,
							);
						}else{
							$_insert[] = array(
								'title' => $val,
								'link' => $menu['link'][$key],
								'order' => $menu['order'][$key],
								'catalogueid' => (int)$this->input->post('catalogueid'),
								'parentid' => 0,
							);
						}
					}
				}
				
				if(isset($_update) && is_array($_update) && count($_update)){
					$this->db->update_batch('navigation',$_update, 'id'); 
				}
				if(isset($_insert) && is_array($_insert) && count($_insert)){
					$resultid = $this->Autoload_Model->_create_batch(array(
						'data' => $_insert,
						'table' => 'navigation',
					));
				}
				
				if($resultid > 0){
					$this->nestedsetbie->Get('level ASC, order ASC');
					$this->nestedsetbie->Recursive(0, $this->nestedsetbie->Set());
					$this->nestedsetbie->Action();
				}
				$this->session->set_flashdata('message-success', 'Cập nhật menu thành công');
				redirect('navigation/backend/navigation/update/'.$id);
			}
		}
		$data['detailNavigation'] = $detailNavigation;
		$data['script'] = 'navigation';
		$data['template'] = 'navigation/backend/navigation/update';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	
}
