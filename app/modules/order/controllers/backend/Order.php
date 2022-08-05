<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Controller {

	public $module;
	function __construct() {
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library(array('configbie'));
		$this->load->library('nestedsetbie', array('table' => 'order_catalogue'));
	}
	
	public function view($page = 1){
		$this->commonbie->permission("order/backend/order/view", $this->auth['permission']);
		$page = (int)$page;
		$data['from'] = 0;
		$data['to'] = 0;
		
		$extend = (!in_array('order/backend/order/viewall', json_decode($this->auth['permission'], TRUE))) ? 'userid_created = '.$this->auth['id'].'' : '';

		// print_r($extend); exit;
		
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 20;
		$keyword = $this->db->escape_like_str($this->input->get('keyword'));
		
		$config['total_rows'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id',
			'table' => 'order',
			'where_extend' => $extend,
			'keyword' => '(fullname LIKE \'%'.$keyword.'%\' )',
			'count' => TRUE,
		));
		
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('order/backend/order/view');
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
			$data['PaginationList'] = $this->pagination->create_links();
			$totalPage = ceil($config['total_rows']/$config['per_page']);
			$page = ($page <= 0)?1:$page;
			$page = ($page > $totalPage)?$totalPage:$page;
			$page = $page - 1;
			$data['from'] = ($page * $config['per_page']) + 1;
			$data['to'] = ($config['per_page']*($page+1) > $config['total_rows']) ? $config['total_rows']  : $config['per_page']*($page+1);
			$data['listorder'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id, fullname, created, updated, order, total_cart_final, status, phone',
				'table' => 'order',
				'where_extend' => $extend,
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'keyword' => $keyword,
				'order_by' => 'order desc, id desc, fullname asc',
			), TRUE);	
		}
		$data['script'] = 'order';
		$data['config'] = $config;
		$data['template'] = 'order/backend/order/view';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	
	public function Update($id = 0){
		$this->commonbie->permission("order/backend/order/update", $this->auth['permission']);
		$id = (int)$id;
		$detailorder = $this->Autoload_Model->_get_where(array(
			'select' => 'id, fullname, created, cart_json, updated, order, total_cart_final, status,	quantity, promotional_detail, fullname , phone, email, note, address_detail, cityid, , cart_json, extend_json, districtid, wardid,
				(SELECT name FROM vn_province WHERE order.cityid = vn_province.provinceid) as address_city, 
				(SELECT name FROM vn_district WHERE order.districtid = vn_district.districtid) as address_distric,
				(SELECT name FROM vn_ward WHERE order.wardid = vn_ward.wardid) as address_ward,
				',
			'table' => 'order',
			'where' => array('id' => $id),
		));
		$data['data_order'] = json_decode(base64_decode($detailorder['cart_json']), true);
		$detail_list_prd = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, created, price_final, moduleid, quantity, image, (SELECT code FROM product WHERE product.id = order_relationship.moduleid) as code',
			'table' => 'order_relationship',
			'where' => array('orderid' => $id),
		), true);
		if(!isset($detailorder) || is_array($detailorder) == false || count($detailorder) == 0){
			$this->session->set_flashdata('message-danger', 'bài viết không tồn tại');
			redirect('order/backend/order/view');
		}
		if($this->input->post('update')){
			$param = [];
			$param['status'] = $this->input->post('status');
			$param['fullname'] = $this->input->post('fullname');
			$param['phone'] = $this->input->post('phone');
			$param['email'] = $this->input->post('email');
			$param['cityid'] = $this->input->post('cityid');
			$param['districtid'] = $this->input->post('districtid');
			$param['wardid'] = $this->input->post('wardid');
			$param['note'] = $this->input->post('note');
			$change = $this->configbie->data('status_quantity', $detailorder['status']) - $this->configbie->data('status_quantity', $param['status']);
			if($change == -1){
				foreach ($detail_list_prd as $key => $val) {
					updateInt(array(
						'module' => 'product',
						'field' => 'quantity_cuoi_ki',
						'where' => array('id' => $val['moduleid']),
						'minus' => $val['quantity'],
					));
				}
			} 
			if($change == 1){
				foreach ($detail_list_prd as $key => $val) {
					updateInt(array(
						'module' => 'product',
						'field' => 'quantity_cuoi_ki',
						'where' => array('id' => $val['moduleid']),
						'plus' => $val['quantity'],
					));
				}
			} 

			$_update = array(
				'status'=> $param['status'],
				'fullname'=> $param['fullname'],
				'phone'=> $param['phone'],
				'email'=> $param['email'],
				'cityid'=> $param['cityid'],
				'districtid'=> $param['districtid'],
				'wardid'=> $param['wardid'],
				'note'=> $param['note'],
			);
			$resultid = $this->Autoload_Model->_update(array('data' => $_update,'table' => 'order','where' => array('id' => $id)));
			if($resultid > 0){
				echo "<script>window.close();</script>";
			}
		}
		
		
		$data['script'] = 'order';
		$data['detailorder'] = $detailorder;
		$data['detail_list_prd'] = $detail_list_prd;
		$data['onlyContent'] = true;
		$data['template'] = 'order/backend/order/update';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function _CheckCanonical($canonical = ''){
		$originalCanonical = $this->input->post('original_canonical');
		if($canonical != $originalCanonical){
			$crc32 = sprintf("%u", crc32(slug($canonical)));
			$router = $this->Autoload_Model->_get_where(array(
				'select' => 'id',
				'table' => 'router',
				'where' => array('crc32' => $crc32),
				'count' => TRUE
			));
			if($router > 0){
				$this->form_validation->set_message('_CheckCanonical','Đường dẫn đã tồn tại, hãy chọn một đường dẫn khác');
				return false;
			}
		}
		return true;
	}
}

