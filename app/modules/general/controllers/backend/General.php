<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class General extends MY_Controller{

	public function __construct(){
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library(array('configbie'));
	}
	

	public function View(){
		$this->commonbie->permission("general/backend/general/view", $this->auth['permission']);
		$data['tab'] = $this->configbie->system();
		
		$general = $this->Autoload_Model->_get_where(array(
			'select' => 'keyword, content',
			'table' => 'general',
			'order_by' => 'keyword asc'
		), TRUE);
		
		if(isset($general) && is_array($general) && count($general)){
			foreach($general as $key => $val){
				$data['systems'][$val['keyword']] = $val['content'];
			}
		}
		
		
		if($this->input->post('save')){
			$config = $this->input->post('config');
			if(isset($config) && is_array($config) && count($config)){
				foreach($config as $key => $val){
					$_update = NULL;
					$_update['keyword'] = $key;
					$_update['content'] = $val;
					$_update['userid_created'] = $this->auth['id'];
					$_update['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
					$flag = $this->_Check($key);
					if($flag == FALSE){
						$this->Autoload_Model->_create(array(
							'table' => 'general',
							'data' => $_update,
						));
					} else {
						$this->Autoload_Model->_update(array(
							'where' => array('keyword' => $key),
							'table' => 'general',
							'data' => $_update,
						));
					}
				}

				//cấu hình .htaccess
				if(isset($config['website_https'])){
					// mở file
					$file_path = $this->url.'.htaccess';
					$fp = @fopen($file_path, "w+");
					  
					// Kiểm tra file mở thành công không
					if (!$fp) {
					    echo 'Mở file '.$file_path.' không thành công';die();
					}else{
						if($config['website_https'] == 'yes'){
							$data = 'RewriteEngine On

									RewriteCond %{HTTPS} !=on
									RewriteRule ^.*$ https://%{SERVER_NAME}%{REQUEST_URI} [R,L]

									RewriteCond %{REQUEST_FILENAME} !-f
									RewriteCond %{REQUEST_FILENAME} !-d
									RewriteRule ^(.*)$ index.php/$1 [L]
									RewriteRule ^(.*)\/(\?.*)?$ $1$2 [R=301,L]';
						}else{
							$data = 'RewriteEngine On
									RewriteCond %{REQUEST_FILENAME} !-f
									RewriteCond %{REQUEST_FILENAME} !-d
									RewriteRule ^(.*)$ index.php/$1 [L]
									RewriteRule ^(.*)\/(\?.*)?$ $1$2 [R=301,L]';
						}
					    
					    fwrite($fp, $data);
					    fclose($fp);
					}
				}
				//cấu hình robot.txt
				if(isset($config['website_index'])){
					// mở file
					$file_path = $this->url.'robots.txt';
					$fp = @fopen($file_path, "w+");
					  
					// Kiểm tra file mở thành công không
					if (!$fp) {
					    echo 'Mở file '.$file_path.' không thành công';die();
					}else{
						if($config['website_index'] == 'yes'){
							$data = 'User-agent: * 
									Allow: /';
						}else{
							$data = 'User-agent: * 
									Disallow: /';
						}
					    
					    fwrite($fp, $data);
					    fclose($fp);
					}
				}
			}
			$this->session->set_flashdata('message-success', 'Lưu thông tin cấu hình hệ thống thành công');
			redirect('general/backend/general/view');
		}
		$data['template'] = 'general/backend/general/view';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function _Check($keyword = ''){
		$result = $this->Autoload_Model->_get_where(array(
			'select' => 'keyword',
			'table' => 'general',
			'where' => array('keyword' => $keyword),
			'count' => TRUE
		));
		return (($result >= 1) ? TRUE : FALSE);
	}
}
