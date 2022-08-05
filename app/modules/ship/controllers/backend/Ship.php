<?php     
defined('BASEPATH') OR exit('No direct script access allowed');

class Ship extends MY_Controller {

	public $module;
	function __construct() {
        parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == false || count($this->auth) == 0) redirect(BACKEND_DIRECTORY);
		$this->module = 'ship';
		$this->load->library(array('configbie'));
	}
	
	public function view($page = 0){
		$this->commonbie->permission("ship/backend/ship/view", $this->auth['permission']);
		$data['from'] = 0;
		$data['to'] = 0;

		$data['type']  = ($this->input->get('type')) ? $this->input->get('type') :  0;
		if($data['type'] > 1 ){
			$data['type'] = 0;
		}

		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 20;
		$keyword = $this->db->escape_like_str($this->input->get('keyword'));

		$config['total_rows'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id',
			'table' => $this->module,
			'count' => TRUE,
		));

		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('ship/backend/ship/view');
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
			$data['moduleList'] = $this->Autoload_Model->_get_where(array(
				'table'=>$this->module,
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'select'=>'id, value, (SELECT fullname FROM user WHERE ship.userid_created = user.id) as user_created,  created, updated, (SELECT name FROM vn_province WHERE vn_province.provinceid = ship.cityid) as city, (SELECT name FROM vn_district WHERE vn_district.districtid = ship.districtid) as district, ',
			),true);
		}
		$data['script'] = 'ship';
		$data['config'] = $config;
		$data['template'] = 'ship/backend/ship/view';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function getConditionLocal( $localNot = array('', '')){
		// lấy danh sách thành phố đã chọn
		$cityList = $this->Autoload_Model->_get_where(array(
			'table'=>$this->module,
			'select'=>'id, cityid',
			'where_not_in' => array("", $localNot[0]),
			'where_in_field' => 'cityid',
		),true);
		$cityidList = getColumsInArray($cityList, 'cityid');
		$temp = '';
		foreach ($cityidList as $key => $val) {
			$temp = $temp."'".$val."'".(($key == count($cityidList) - 1) ? '' : ',');
		}
		$data['cityCondition'] = '" AND ( provinceid NOT IN ('.$temp.') )"';

		$districtList = $this->Autoload_Model->_get_where(array(
			'table'=>$this->module,
			'select'=>'id, districtid',
			'where_not_in' => array("", $localNot[1]),
			'where_in_field' => 'districtid',
		),true);
		$districtidList = getColumsInArray($districtList, 'districtid');
		$temp = '';
		foreach ($districtidList as $key => $val) {
			$temp = $temp."'".$val."'".(($key == count($districtidList) - 1) ? '' : ',');
		}
		$data['districtCondition'] = '" AND ( provinceid NOT IN ('.$temp.') )"';
		return $data;
	}
	public function create(){
		$data = $this->getConditionLocal();
		$data['script'] = 'ship';
		$this->commonbie->permission("ship/backend/ship/create", $this->auth['permission']);
		
		$post['district']  = ($this->input->post('district[]')) ? $this->input->post('district[]') : '';
		$post['city']  = ($this->input->post('city[]')) ? $this->input->post('city[]') : '';
		$post['value']  = ($this->input->post('value[]')) ? $this->input->post('value[]') : '';
		$data['post']= $post;
		if($this->input->post('create')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('', ' / ');
			$this->form_validation->set_rules('city','Địa chỉ','trim|callback__CheckLocal');
			$this->form_validation->set_rules('value','Giá ship','trim|required');
			if($this->form_validation->run($this)){
				$localList = [];
				if(isset($post['city']) && check_array($post['city']) ){
					$localList = $post['city'];
					$local = 'cityid';
				}
				if(isset($post['district']) && check_array($post['district']) ){
					$localList = $post['district'];
					$local = 'districtid';
				}
				$_insert_relationship_batch = [];
				if(isset($localList) && check_array($localList) ){
					foreach ($localList as $keyLocal => $valLocal) {
						$_insert_relationship = array(
							'value' => (int)str_replace('.','',$post['value']),
							'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
							'userid_created' => $this->auth['id'],
						);
						$_insert_relationship[$local] = $valLocal;
						$_insert_relationship_batch[] = $_insert_relationship ;
					}
				}
				if(isset($_insert_relationship_batch) && is_array($_insert_relationship_batch) && count($_insert_relationship_batch)){
					$this->Autoload_Model->_create_batch(array(
						'table' => $this->module,
						'data' => $_insert_relationship_batch,
					));
					$this->session->set_flashdata('message-success','Thêm Phí ship mới thành công');
					redirect(base_url('ship/backend/ship/view'));
				}
			}
		}

		$data['template'] = 'ship/backend/ship/create';
		$this->load->view('dashboard/backend/layout/dashboard', ((isset($data)) ? $data : ''));
	}
	public function update($id = 0){
		$this->commonbie->permission("ship/backend/ship/create", $this->auth['permission']);
		$id = (int)$id;
		$moduleDetail = $this->Autoload_Model->_get_where(array(
			'table' => $this->module,
			'select'=>'id, value, cityid, districtid',
			'where'=> array('id'=>$id),
		));
		$data = $this->getConditionLocal(array($moduleDetail['cityid'], $moduleDetail['districtid']));
		$data['script'] = 'ship';
		$data['moduleDetail'] = $moduleDetail;

		if(!isset($data['moduleDetail']) || check_array($data['moduleDetail']) == false ){
			$this->session->set_flashdata('message-danger', 'địa chỉ không tồn tại hoặc bạn không có quyền truy cập');
			redirect('ship/backend/ship/view');
		}
		$post['city']  = ($this->input->post('city[]')) ? $this->input->post('city[]') : $data['moduleDetail']['cityid'];
		$post['district']  = ($this->input->post('district[]')) ? $this->input->post('district[]') : $data['moduleDetail']['districtid'];
		$post['value']  = ($this->input->post('value[]')) ? $this->input->post('value[]') : $data['moduleDetail']['value'];
		$data['post']= $post;
		if($this->input->post('update')){
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('', ' / ');
			$this->form_validation->set_rules('city','Địa chỉ','trim|callback__CheckLocal');
			$this->form_validation->set_rules('value','Giá ship','trim|required');
			if($this->form_validation->run($this)){
				$localList = [];
				if(isset($post['city']) && check_array($post['city']) ){
					$localList = $post['city'];
					$local = 'cityid';
				}
				if(isset($post['district']) && check_array($post['district']) ){
					$localList = $post['district'];
					$local = 'districtid';
				}
				$_insert_relationship_batch = [];
				if(isset($localList) && check_array($localList) ){
					foreach ($localList as $keyLocal => $valLocal) {
						$_insert_relationship = array(
							'value' => (int)str_replace('.','',$post['value']),
							'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
							'userid_created' => $this->auth['id'],
						);
						$_insert_relationship[$local] = $valLocal;
						$_insert_relationship_batch[] = $_insert_relationship ;
					}
				}
				if(isset($_insert_relationship_batch) && is_array($_insert_relationship_batch) && count($_insert_relationship_batch)){
					$this->Autoload_Model->_create_batch(array(
						'table' => $this->module,
						'data' => $_insert_relationship_batch,
					));
					$this->Autoload_Model->_delete(array(
						'table' => $this->module,
						'where' => array('id' => $id),
					));
					$this->session->set_flashdata('message-success','Cập nhật Phí ship mới thành công');
					redirect(base_url('ship/backend/ship/view'));
				}
			}
		}

		$data['template'] = 'ship/backend/ship/update';
		$this->load->view('dashboard/backend/layout/dashboard', ((isset($data)) ? $data : ''));
	}

	public function _CheckTitle($title){
		$title_original = $this->input->post('title_original');
		if($title != $title_original){
			$count = $this->Autoload_Model->_get_where(array(
				'table'=>$this->module,
				'select'=>'id',
				'count'=>true,
				'where'=>array('title'=>$title)
			));
			if($count ==1){
				$this->form_validation->set_message('_CheckTitle','Tên địa chỉ đã tồn tại');
				return false;
			}
			return true;
		}
		return true;
	}
	public function _CheckLocal(){
		$city = $this->input->post('city[]');
		$district = $this->input->post('district[]');
		if($city == null &&  $district == null){
			$this->form_validation->set_message('_CheckLocal','Bạn chưa chọn địa chỉ');
			return false;
		}
		return true;
	}
}

