<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogue extends MY_Controller {

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
			'table' => 'customer_catalogue',
			'keyword' => isset($param['keyword'])? '(title LIKE \'%'.$param['keyword'].'%\')' : '',
			'count' => TRUE,
		));
		$listCatalogue = '';
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('customer/backend/catalogue/view');
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
			
			$data['listCatalogue'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id, title, created, updated, publish, userid_created, (SELECT fullname FROM user WHERE user.id = customer_catalogue.userid_created) as fullname, (SELECT COUNT(id) FROM customer WHERE customer.catalogueid = customer_catalogue.id AND customer.id != 2) as total_customer',
				'table' => 'customer_catalogue',
				'keyword' => '(title LIKE \'%'.$param['keyword'].'%\')',
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'order_by' => 'title asc, created desc',
			), TRUE);
			
			foreach($data['listCatalogue'] as $key => $val){
				$listCatalogue .= '<tr>';
					$listCatalogue .= '<td class="text-center" style="width: 40px;">';
						$listCatalogue .= '<input type="checkbox" name="checkbox[]" value="6" class="checkbox-item">';
						$listCatalogue .= '<label for="" class="label-checkboxitem"></label>';
					$listCatalogue .= '</td>';
					$listCatalogue .= '<td class="text-center">'.$val['id'].'</td>';
					$listCatalogue .= '<td class="text-left">'.$val['title'].'</td>';
					$listCatalogue .= '<td class="text-center">'.$val['total_customer'].'</td>';
					$listCatalogue .= '<td class="text-center">'.$val['fullname'].'</td>';
					$listCatalogue .= '<td class="text-center">'.$val['created'].'</td>';
					$listCatalogue .= '<td class="text-center">'.$val['updated'].'</td>';
					$listCatalogue .= '<td class="uk-flex uk-flex-center">';
						$listCatalogue .='<a type="button" href="" class="btn btn-sm btn-edit btn-primary"><i class="fa fa-edit"></i></a>';
						$listCatalogue .='<a type="button" href="" class="btn btn-sm btn-trash btn-danger"><i class="fa fa-trash"></i></a>';
					$listCatalogue .= '</td>';
				$listCatalogue .= '</tr>';
			}
			echo json_encode(array(
				'listCatalogue' => $listCatalogue,
				'paginationList' => isset($data['paginationList'])? $data['paginationList']:'',
			));die;
			
		}else{
			$listCatalogue .= '<tr>';
				$listCatalogue .= '<td colspan="8">';
					$listCatalogue .= '<small class="text-danger">Không có dữ liệu phù hợp</small>';
				$listCatalogue .= '</td>';						
			$listCatalogue .= '</tr>';
		}
		
		echo json_encode(array(
			'listCatalogue' => $listCatalogue,
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
		
		if($result > 0){
			//xóa thành công nhóm thì ta xóa all phần tử con của nó
			$resultChild = $this->Autoload_Model->_delete(array(
				'where' => array('catalogueid' => $id),
				'table' => 'customer',
			));
			if($resultChild > 0){
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
					'message' => 'Xóa không thành công phần tử con của nhóm',
				);
			}
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
				
				if($result > 0){
					//xóa all phần tử con trong nhóm
					$resultChild = $this->Autoload_Model->_delete(array(
						'where' => array('catalogueid' => $val),
						'table' => 'customer',
					));
					if($resultChild <= 0){
						$error = array(
							'flag' => 1,
							'message' => 'Xóa không thành công phần tử con trong nhóm',
						);
						
						echo json_encode(array(
							'error' => $error,
						));die;
				}}else{
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
