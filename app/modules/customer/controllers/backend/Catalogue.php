<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogue extends MY_Controller {

	public $module;
	function __construct() {
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library(array('configbie'));
		
	}
	
	public function Create(){
		//kiểm tra người dùng ấn submit hay chưa?
		if($this->input->post('create')){
			//validation form
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			
			//show cấu trúc đổ lỗi
			$this->form_validation->set_error_delimiters('- ' ,'<br>');
			$this->form_validation->set_rules('title' , 'Tên nhóm khách hàng' , 'trim|required');
			
			//validation thành công
			if($this->form_validation->run($this)){
				$_insert = array(
					'title' => $this->input->post('title'),
					'userid_created' => $this->auth['id'],
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);
				
				$resultid = $this->Autoload_Model->_create(array(
					'table' => 'customer_catalogue',
					'data' => $_insert,
				));
				
				if($resultid > 0){
					$this->session->set_flashdata('message-success' , 'Thêm mới nhóm khách hàng thành công');
					redirect(site_url('customer/backend/catalogue/create'));
				}else{
					$this->session->set_flashdata('message-error' , 'Thêm mới nhóm khách hàng không thành công');
					redirect(site_url('customer/backend/catalogue/create'));
				}
			}
		}
		
		$data['script'] = 'customer_catalogue';
		$data['template'] = 'customer/backend/catalogue/create';
		$this->load->view('dashboard/backend/layout/dashboard' , isset($data)? $data:null);
	}
	
	public function View($page = 1){
		$this->commonbie->permission("customer/backend/catalogue/view", $this->auth['permission']);
		$page = (int)$page;
		$data['from'] = 0; //khởi tạo
		$data['to'] = 0;
		//Tính tổng số bản ghi của trang danh mục
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 10;
		$config['total_rows'] = $this->Autoload_Model->_get_where(array( //hàm đếm tổng số bản ghi
			'select' => 'id',
			'table' => 'customer_catalogue',
			'count' => TRUE,
		));
		// echo $config['total_rows'];die();
		if($config['total_rows'] > 0){
			$this->load->library('pagination'); //gọi thư viện phân trang của ci
			$config['base_url'] = base_url('customer/backend/catalogue/view');
			$config['suffix'] = $this->config->item('url_suffix').(!empty($_SERVER['QUERY_STRING'])?('?'.$_SERVER['QUERY_STRING']):'');
			$config['first_url'] = $config['base_url'].$config['suffix'];
			$config['per_page'] = $perpage;
			$config['uri_segment'] = 5;
			$config['num_links'] = 1;
			$config['use_page_numbers'] = TRUE;
			$config['full_tag_open'] = '<ul class="pagination no-margin">';
			$config['full_tag_close'] = '</ul>';
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="active"><a class="btn-primary">';
			$config['cur_tag_close'] = '</a></li>';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$this->pagination->initialize($config);
			$data['PaginationList'] = $this->pagination->create_links();
			$totalPage = ceil($config['total_rows']/$config['per_page']);
			$page = ($page <= 0)?1:$page;
			$page = ($page > $totalPage)?$totalPage:$page;
			$page = $page - 1;
			$data['from'] = ($page * $config['per_page']) + 1;
			$data['to'] = ($config['per_page']*($page+1) > $config['total_rows']) ? $config['total_rows']  : $config['per_page']*($page+1);
		
			$data['listCatalogue'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id, title, created, updated, publish, userid_created, (SELECT fullname FROM user WHERE user.id = customer_catalogue.userid_created) as fullname, (SELECT COUNT(id) FROM customer WHERE customer.catalogueid = customer_catalogue.id) as total_customer',
				'table' => 'customer_catalogue',
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'order_by' => 'title asc, created desc',
			), TRUE);
			
		}
		$data['script'] = 'customer_catalogue';
		$data['config'] = $config;
		$data['template'] = 'customer/backend/catalogue/view';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function Update($id = 0){
		//lấy thông tin từ id
		$id = (int)$id;
		
		$detailCatalogue = $this->Autoload_Model->_get_where(array(
			'select' => 'title',
			'table' => 'customer_catalogue',
			'where' => array('id' => $id),
		));
		
		if(!isset($detailCatalogue) || !is_array($detailCatalogue) || count($detailCatalogue) == 0 ){ 
			$this->session->set_flashdata('message-error' , 'Không tồn tại nhóm khách hàng');
			redirect(site_url('customer/backend/catalogue/view'));
		}
		
		//kiểm tra người dùng ấn submit hay chưa?
		if($this->input->post('update')){
			//validation form
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			//show cấu trúc đổ lỗi
			$this->form_validation->set_error_delimiters('- ' ,'<br>' );
			$this->form_validation->set_rules('title' , 'Tên nhóm khách hàng' , 'trim|required');
			
			//validation thành công
			if($this->form_validation->run($this)){
				$_update = array(
					'title' => $this->input->post('title'),
					'userid_updated' => $this->auth['id'],
					'updated' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);
				
				$count = $this->Autoload_Model->_update(array(
					'where' => array('id' => $id),
					'table' => 'customer_catalogue',
					'data' => $_update,
				));
				
				if($count > 0){
					$this->session->set_flashdata('message-success' , 'Cập nhật nhóm khách hàng thành công');
					redirect(site_url('customer/backend/catalogue/view'));
				}else{
					$this->session->set_flashdata('message-error' , 'Cập nhật nhóm khách hàng không thành công');
					redirect(site_url('customer/backend/catalogue/view'));
				}
			}
		}
		
		$data['detailCatalogue'] = $detailCatalogue;
		$data['script'] = 'customer_catalogue';
		$data['template'] = 'customer/backend/catalogue/update';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function Delete($id = 0){
		//xác định id nào sẽ xóa
		$id = (int)$id;
		
		$detailCatalogue = $this->Autoload_Model->_get_where(array(
			'select' => 'id',
			'table' => 'customer_catalogue',
			'where' => array('id' => $id),
		));
		
		if(!isset($detailCatalogue) || !is_array($detailCatalogue) || count($detailCatalogue) == 0 ){ 
			$this->session->set_flashdata('message-error' , 'Không tồn tại nhóm khách hàng');
			redirect(site_url('customer/backend/catalogue/view'));
		}
		
	}
}
