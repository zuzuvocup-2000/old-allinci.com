<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

	private $module;
	public function __construct(){
		parent::__construct();
		$this->module = 'user';
	}
	
	public function listUser(){
		$page = (int)$this->input->get('page');
		$data['from'] = 0;
		$data['to'] = 0;
		$perpage = 20;
		$keyword = $this->db->escape_like_str($this->input->get('keyword'));
		$config['total_rows'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id',
			'table' => 'user',
			'where' => array('id !=' => 2),
			'keyword' => '(fullname LIKE \'%'.$keyword.'%\' OR account LIKE \'%'.$keyword.'%\' OR email LIKE \'%'.$keyword.'%\' OR address LIKE \'%'.$keyword.'%\' OR description LIKE \'%'.$keyword.'%\' )',
			'count' => TRUE,
		));
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] ='#" data-page="';
			$config['suffix'] = $this->config->item('url_suffix');
			$config['first_url'] = $config['base_url'].$config['suffix'];
			$config['per_page'] = 20;
			$config['cur_page'] = $page;
			$config['page'] = $page;
			$config['uri_segment'] = 2;
			$config['use_page_numbers'] = TRUE;
			$config['reuse_query_string'] = TRUE;
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
			$listPagination = $this->pagination->create_links();
			$totalPage = ceil($config['total_rows']/$config['per_page']);
			$page = ($page <= 0)?1:$page;
			$page = ($page > $totalPage)?$totalPage:$page;
			$page = $page - 1;
			$data['from'] = ($page * $config['per_page']) + 1;
			$data['to'] = ($config['per_page']*($page+1) > $config['total_rows']) ? $config['total_rows']  : $config['per_page']*($page+1);
			$data['listUser'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id, fullname, account, avatar, email, publish, address, gender, phone, last_login',
				'table' => 'user',
				'where' => array('id !=' => 2),
				'keyword' => '(fullname LIKE \'%'.$keyword.'%\' OR account LIKE \'%'.$keyword.'%\' OR email LIKE \'%'.$keyword.'%\' OR address LIKE \'%'.$keyword.'%\' OR description LIKE \'%'.$keyword.'%\' )',
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'order_by' => 'fullname asc, created desc',
			), TRUE);	
		}
		
		$html = '';
		if(isset($data['listUser']) && is_array($data['listUser']) && count($data['listUser'])){
			foreach($data['listUser'] as $key => $val){
				$html = $html.'<tr style="cursor:pointer;" class="choose" data-info="'.base64_encode(json_encode($val)).'">';
				$html = $html.'<td>';
				$html = $html.'<input type="checkbox" name="checkbox[]" value="'.$val['id'].'" class="checkbox-item">
				<div for="" class="label-checkboxitem"></div>';
				$html = $html.'</td>';
				$html = $html.'<td><a data-toggle="tab" href="#contact-1" class="client-link">'.$val['fullname'].'</a></td>';
				$html = $html.'<td> '.$val['account'].'</td>';
				$html = $html.'<td class="client-email"> <i class="fa fa-envelope" style="margin-right:5px;"> </i>'.((!empty($val['email'])) ? $val['email'] : '-').'</td>';
				$html = $html.'<td class="client-status" style="text-align:center;">';
				$html = $html.'<a type="button" href="'.site_url('user/backend/user/update/'.$val['id'].'').'"   class="btn btn-sm btn-primary btn-update mr5"><i class="fa fa-edit"></i></a>';
				$html = $html.'<a type="button" class="btn btn-sm btn-danger ajax-recycle" data-title="Lưu ý: Khi bạn xóa thành viên, người này sẽ không thể truy cập vào hệ thống quản trị được nữa." data-id="'.$val['id'].'" data-module="user"><i class="fa fa-trash"></i></a>';
				$html = $html.'</td>';
				$html = $html.'</tr>';
			}
		}else{
			$html = '<tr>
			<td colspan="6">
			<small class="text-danger">Không có dữ liệu phù hợp</small>
			</td>
			</tr>';
		}
		
		echo json_encode(array(
			'pagination' => (isset($listPagination)) ? $listPagination : '',
			'html' => (isset($html)) ? $html : '',
			'total' => $config['total_rows'],
		));die();
	}
	
	public function reset_password(){
		$error['flag'] = 0;
		$error['message'] = '';
		$userID = $this->input->post('userID');
		$user =  $this->Autoload_Model->_get_where(array('select' => 'id, fullname','table' => 'user','where' => array('publish' => 1,'id' => $userID)));
		if(!isset($user) || is_array($user) == FALSE || count($user) == 0){
			$error['flag'] = 1;
			$error['message'] = 'Thao tác thất bại, Tài khoản không tồn tại';
			echo json_encode($error);die();
		}
		$salt = random();
		$password = password_encode('123456xyz', $salt);
		$_update['password'] = $password;
		$_update['salt'] = $salt;
		$_update['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
		$_update['userid_updated'] = $this->auth['id'];
		
		if(isset($_update) && is_array($_update) && count($_update)){
			$flag = $this->Autoload_Model->_update(array(
				'where' => array('id' => $userID),
				'table' => $this->module,
				'data' => $_update, 
			));
			if($flag <= 0){
				$error['flag'] = 1;
				$error['message'] = 'Reset mật khẩu thất bại, vui lòng thử lại';
				echo json_encode($error);die();
			}
		}
		
		echo json_encode($error);die();

	}
}
