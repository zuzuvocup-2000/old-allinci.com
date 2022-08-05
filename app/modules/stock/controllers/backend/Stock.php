<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock extends MY_Controller {

	public $module;
	function __construct() {
		parent::__construct();
		$this->load->library(array('configbie'));
		if(!isset($this->auth)) redirect('admin');
	}
	public function view($page = 1){
		$this->commonbie->permission("stock/backend/stock/view", $this->auth['permission']);
		$data['liststock'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, phone, publish, address, email, (SELECT fullname FROM user WHERE stock.userid_charge = user.id) as user_charge',
			'table' => 'stock',
			'order_by' => 'created desc',
		), TRUE);
		$data['script']='stock';
		$data['template'] = 'stock/backend/stock/view';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	public function create(){
		$this->commonbie->permission("stock/backend/stock/create", $this->auth['permission']);
		if($this->input->post('create')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_rules('title','kho','trim|required');
			if($this->form_validation->run($this)){
				$salt = random();
				$_insert = array(
					'title' =>htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'phone' => $this->input->post('phone'),
					'address' => $this->input->post('address'),
					'email' => $this->input->post('email'),
					'userid_charge' => $this->input->post('userid_charge'),
					'publish' => 1,
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
					'userid_created' => $this->auth['id'],
				);
				$insertId = $this->Autoload_Model->_create(array(
					'table' => 'stock',
					'data' => $_insert,
				));
				if($insertId > 0){
					$this->session->set_flashdata('message-success', 'Tạo kho thành công'); 
					redirect(site_url('stock/backend/stock/create')); 
				}
			}
		}
		
		$data['script'] = 'stock';
		$data['template'] = 'stock/backend/stock/create';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
		
	public function update($id = 0){
		$this->commonbie->permission("stock/backend/stock/update", $this->auth['permission']);
		$id = (int)$id;
		$detailstock = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, phone, email, publish, address, userid_charge',
			'table' => 'stock',
			'where' => array('id' => $id)
		));
		if(!isset($detailstock) || is_array($detailstock) == FALSE || count($detailstock) == 0){
			$this->session->set_flashdata('message-danger', 'kho thành viên không tồn tại'); 
			redirect(site_url('stock/backend/stock/view')); 
		}
		if($this->input->post('update')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_rules('title','kho','trim|required');
			if($this->form_validation->run($this)){
				$_update = array(
					'title' =>htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'phone' => $this->input->post('phone'),
					'address' => $this->input->post('address'),
					'email' => $this->input->post('email'),
					'userid_charge' => $this->input->post('userid_charge'),
					'publish' => 1,
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
					'userid_created' => $this->auth['id'],
				);
				$flag = $this->Autoload_Model->_update(array(
					'where' => array('id' => $id),
					'table' => 'stock',
					'data' => $_update
				));
				if($flag > 0){
					$this->session->set_flashdata('message-success', 'Cập nhật kho khoản thành công'); 
					redirect(site_url('stock/backend/stock/update/'.$id.'')); 
				}
			}
		}
		$data['script'] = 'stock';
		$data['detailstock'] = $detailstock;
		$data['template'] = 'stock/backend/stock/update';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}	
}
