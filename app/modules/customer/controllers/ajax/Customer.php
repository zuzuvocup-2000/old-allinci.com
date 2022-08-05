<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends MY_Controller {

	public function __construct(){
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
	}
	
	public function View(){
		$param = $this->input->get('param');
		$page = (int)$param['page'];
		$data['from'] = 0;
		$data['to'] = 0;
		
		//Tính tổng số bản ghi của trang danh mục
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 10;
		$config['total_rows'] = $this->Autoload_Model->_get_where(array( //trả lại all số bản ghi
			'select' => 'id',
			'table' => 'customer',
			'keyword' => '(fullname LIKE \'%'.$param['keyword'].'%\' OR email LIKE \'%'.$param['keyword'].'%\' OR catalogue_title LIKE \'%'.$param['keyword'].'%\')',
			'count' => TRUE,
		));
		
		$listCustomer = '';
		
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('customer/backend/customer/view');
			$config['suffix'] = $this->config->item('url_suffix').(!empty($_SERVER['QUERY_STRING'])?('?'.$_SERVER['QUERY_STRING']):'');
			$config['first_url'] = $config['base_url'].$config['suffix'];
			$config['per_page'] = $param['perpage'];
			$config['cur_page'] = $page;
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
			$data['paginationList'] = $this->pagination->create_links();
			
			$totalPage = ceil($config['total_rows']/$config['per_page']);
			$page = ($page <= 0)?1:$page;
			$page = ($page > $totalPage)?$totalPage:$page;
			$page = $page - 1;
			$data['from'] = ($page * $config['per_page']) + 1;
			$data['to'] = ($config['per_page']*($page+1) > $config['total_rows']) ? $config['total_rows']  : $config['per_page']*($page+1);
			$data['listCustomer'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id, fullname, email, phone, address, gender, updated, catalogue_title',
				'table' => 'customer',
				'keyword' => '(fullname LIKE \'%'.$param['keyword'].'%\' OR email LIKE \'%'.$param['keyword'].'%\' OR catalogue_title LIKE \'%'.$param['keyword'].'%\')',
				'where' => array('publish' => 1,),
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'order_by' => 'fullname asc, created desc',
			), TRUE);
			
			
			if(isset($data['listCustomer']) && is_array($data['listCustomer']) && count($data['listCustomer'])){
				foreach($data['listCustomer'] as $key => $val){
					$listCustomer .= '<tr style="cursor:pointer;" class="choose" data-info="'.(base64_encode(json_encode($val))).'" >';
						$listCustomer .= '<td style="width: 40px;"><input type="checkbox" name="checkbox[]" value="'.$val['id'].'" class="checkbox-item"><div for="" class="label-checkboxitem"></div></td>';
						$listCustomer .= '<td><a data-toggle="tab" href="#contact-1" class="client-link">'.$val['fullname'].'</a></td>';
						$listCustomer .= '<td class="client-email"> <i class="fa fa-envelope" style="margin-right:5px;"></i>'.((!empty($val['email'])) ? $val['email'] : '-').'</td>';
						$listCustomer .= '<td class="client-group">'.$val['catalogue_title'].'</td>';
						$listCustomer .= '<td class="client-status" style="text-align:center;">
							<a type="button" href="'.site_url('customer/backend/customer/update/'.$val['id']).'"   class="btn btn-sm btn-primary btn-update"><i class="fa fa-edit"></i></a>
							<a type="button" class="btn btn-sm btn-danger ajax-delete" data-title="Lưu ý: Khi bạn xóa thành viên, người này sẽ không thể truy cập vào hệ thống quản trị được nữa." data-id="'.$val['id'].'" data-module="customer"><i class="fa fa-trash"></i></a>
						</td>';
					$listCustomer .= '</tr>';
				}
				echo json_encode(array(
					'listCustomer' => $listCustomer,
					'paginationList' => isset($data['paginationList'])? $data['paginationList']:'',
				));die;
			}
		}else{
			$listCustomer .= '<tr>';
				$listCustomer .= '<td colspan="5">';
					$listCustomer .= '<small class="text-danger">Không có dữ liệu phù hợp</small>';
				$listCustomer .= '</td>';						
			$listCustomer .= '</tr>';
		}
		
		echo json_encode(array(
			'listCustomer' => $listCustomer,
			'paginationList' => isset($data['paginationList'])? $data['paginationList']:'',
		));die;
	}
	
	public function ajax_delete(){
		$id = (int)$this->input->post('id');
		$module = $this->input->post('module');
		
		//tiến hành xóa dữ liệu với id vừa lấy được
		$result = $this->Autoload_Model->_delete(array(
			'where' => array('id' => $id),
			'table' => $module,
		));
		
		//xóa all các khách hàng trong nhóm này
		//	...
		//	để lại
		//
		
		if($result > 0){
			$error = array(
				'flag' => 0,
				'message' => '',
			);
			
			echo json_encode(array(
				'error' => $error,
			));die;
		}else{
			$error = array(
				'flag' => 1,
				'message' => 'Xóa không thành công',
			);
		}
		
		echo json_encode(array(
			'error' => $error,
		));die;
	}
	
	//xóa nhiều
	//################  xóa nhóm => xóa all thành viên trong nhóm ################################
	public function ajax_group_delete(){
		
		$param = $this->input->post('param');
		
		//tiến hành xóa dữ liệu với danh sách id vừa lấy được
		if(isset($param['list']) && is_array($param['list']) && count($param['list'])){
			foreach($param['list'] as $key => $val){
				$result = $this->Autoload_Model->_delete(array(
					'where' => array('id' => $val),
					'table' => $param['module'],
				));
				
				if($result <= 0){
					$error = array(
						'flag' => 1,
						'message' => 'Xóa không thành công',
					);
					
					echo json_encode(array(
						'error' => $error,
					));die;
				}
			}
			//kết thúc quá trình delete dữ liệu
			$error = array(
				'flag' => 0,
				'message' => '',
			);
			
			echo json_encode(array(
				'error' => $error,
			));die;
		}
	}
}
