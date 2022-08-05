<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Commonbie{
	
	function __construct($params = NULL){
		$this->CI =& get_instance();
		
	}


	// Hàm này trả về thông tin tài khoản của người đang đăng nhập. Nếu ko có tình trạng đăng nhập trả về NULL;
	public function CheckBackendAuthentication(){
		$auth = (isset($_SESSION[AUTH.'auth'])) ? $_SESSION[AUTH.'auth'] : '';
		if(!isset($auth) || empty($auth)) return NULL;
		$auth = json_decode($auth, TRUE);
		$user = $this->CI->Autoload_Model->_get_where(array(
			'select' => 'id, account, fullname, email, password, avatar, salt, (SELECT permission FROM user_catalogue WHERE user_catalogue.id = user.catalogueid ) as permission, (SELECT title FROM user_catalogue WHERE user.catalogueid = user_catalogue.id) as catalogue,',
			
			'table' => 'user',
			'where' => array(
				'id' => $auth['id'],
			),
		));
		
		if(!isset($user) || is_array($user) == FALSE || count($user) == 0){
			unset($_SESSION[AUTH.'auth']); 
			return NULL;
		}
		
		return $user;
		
	}
	
	public function permission($access = '', $permission = ''){
		$permission=json_decode($permission, true);
		if(!in_array($access, $permission)){
			$this->CI->session->set_flashdata('message-danger','Bạn không có quyền truy cập vào chức năng này');
			redirect('dashboard/home');
		}
	}
	

}
