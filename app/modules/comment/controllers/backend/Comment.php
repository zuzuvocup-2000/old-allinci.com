<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment extends MY_Controller {

	public $module;
	function __construct() {
		parent::__construct();
		$this->load->library(array('configbie' , 'myconstant'));
		$this->load->helper(array('mycomment'));
		$this->load->helper(array('mydisplay'));
		if(!isset($this->auth)) redirect('admin');
	}
	
	public function view($page = 1){
		$this->commonbie->permission("comment/backend/comment/view", $this->auth['permission']);
		
		$page = (int)$page;
		$data['from'] = 0;
		$data['to'] = 0;
		//Tính tổng số bản ghi của trang danh mục
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 10;
		$config['total_rows'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id',
			'table' => 'comment',
			'count' => TRUE,
		));
		
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('comment/backend/comment/view');
			$config['suffix'] = $this->config->item('url_suffix').(!empty($_SERVER['QUERY_STRING'])?('?'.$_SERVER['QUERY_STRING']):'');
			$config['first_url'] = $config['base_url'].$config['suffix'];
			$config['per_page'] = $perpage;
			$config['uri_segment'] = 5;
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
			$data['paginationList'] = $this->pagination->create_links();
			$totalPage = ceil($config['total_rows']/$config['per_page']);
			$page = ($page <= 0)?1:$page;
			$page = ($page > $totalPage)?$totalPage:$page;
			$page = $page - 1;
			$data['from'] = ($page * $config['per_page']) + 1;
			$data['to'] = ($config['per_page']*($page+1) > $config['total_rows']) ? $config['total_rows']  : $config['per_page']*($page+1);
			$data['listComment'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id, fullname, email, phone, module, detailid, parentid, rate, comment, image, publish',
				'table' => 'comment',
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'order_by' => 'fullname asc, id desc',
			), TRUE);
		}
		$data['config'] = $config;
		
		$data['script']='comment';
		$data['template'] = 'comment/backend/comment/view';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function create($page = 1){
		$this->commonbie->permission("comment/backend/comment/create", $this->auth['permission']);
		
		//kiểm tra người dùng ấn nút tạo mói hay chưa
		if($this->input->post('create')){
			//validation dữ liệu
			$this->load->library('form_validation');
			
			//show cấu trúc đổ lỗi
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('- ' , '</br>');
			$this->form_validation->set_rules('module' , 'Danh mục' , 'trim|required');
			$this->form_validation->set_rules('detailid' , 'Chi tiết' , 'trim|callback__CheckModule');

			$this->form_validation->set_rules('fullname' , 'Họ tên' , 'trim|required');
			$this->form_validation->set_rules('email' , 'Email' , 'trim|valid_email');
			
			if($this->form_validation->run($this)){
				$_insert = array(
					'fullname' => $this->input->post('fullname'),
					'phone' => trim($this->input->post('phone')),
					'email' => $this->input->post('email'),
					'comment' => $this->input->post('comment'),
					'image' => json_encode($this->input->post('album')),
					'rate' => $this->input->post('data-rate'),
					'module' => $this->input->post('module'),
					'detailid' => $this->input->post('detailid'),
					'publish' => $this->input->post('publish'),
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);
				
				$resultid = $this->Autoload_Model->_create(array(
					'table' => 'comment',
					'data' => $_insert,
				));
				
				if($resultid > 0){
					$this->session->set_flashdata('message-success' , 'Thêm comment thành công');
					redirect(site_url('comment/backend/comment/view'));
				}else{
					$this->session->set_flashdata('message-error' , 'Thêm comment không thành công');
					redirect(site_url('comment/backend/comment/create'));
				}
			}
		}
		
		
		$data['script']='comment';
		$data['template'] = 'comment/backend/comment/create';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function update($id = 0){
		$this->commonbie->permission("article/backend/article/update", $this->auth['permission']);
		$id = (int)$id;
		
		//lấy dữ liệu dự vào id
		$detailComment = $this->Autoload_Model->_get_where(array(
			'select' => 'id, fullname, email, phone, module, detailid, rate, comment, image, publish',
			'table' => 'comment',
			'where' => array('id' => $id),
		));
		
		// nếu không tồn tại dữ liệu thì thông báo cho người dùng biết và chuyển về trang view
		if(!isset($detailComment) || !is_array($detailComment) || count($detailComment) == 0){
			$this->session->set_flashdata('message-error' , 'Comment không tồn tại. Xin vui lòng thử lại');
			redirect(site_url('comment/backend/comment/view'));
		}
		
		//người dùng ấn submit
		if($this->input->post('update')){
			//validate form
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('- ', '</br>');
			$this->form_validation->set_rules('module' , 'Danh mục' , 'trim|required');
			$this->form_validation->set_rules('detailid' , 'Chi tiết' , 'trim|callback__CheckModule');
			$this->form_validation->set_rules('fullname' , 'Họ tên' , 'trim|required');
			$this->form_validation->set_rules('email' , 'Email' , 'trim|valid_email');
			
			
			if($this->form_validation->run($this)){
				$_update = array(
					'fullname' => $this->input->post('fullname'),
					'phone' => trim($this->input->post('phone')),
					'email' => $this->input->post('email'),
					'comment' => $this->input->post('comment'),
					'image' => json_encode($this->input->post('album')),
					'rate' => $this->input->post('data-rate'),
					'module' => $this->input->post('module'),
					'detailid' => $this->input->post('detailid'),
					'publish' => $this->input->post('publish'),
					'updated' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);
				
				$result = $this->Autoload_Model->_update(array(
					'where' => array('id' => $id),
					'table' => 'comment',
					'data' => $_update,
				));
				
				if($result > 0){
					$this->session->set_flashdata('message-success' , 'Cập nhật comment thành công');
					redirect(site_url('comment/backend/comment/view'));
				}else{
					$this->session->set_flashdata('message-error' , 'Cập nhật comment không thành công');
					redirect(site_url('comment/backend/comment/update'));
				}
			}
		}
		
		$data['detailComment'] = $detailComment;
		
		$data['script']='comment';
		$data['template'] = 'comment/backend/comment/update';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function _CheckModule(){
		if($this->input->post('module') == '0'){
			$this->form_validation->set_message('_CheckModule' , 'Bạn hãy chọn Danh mục');
			return false;
		}
		if($this->input->post('detailid') == '0'){
			$this->form_validation->set_message('_CheckModule' , 'Bạn hãy chọn Chi tiết');
			return false;
		}
		return true;
	}
}
