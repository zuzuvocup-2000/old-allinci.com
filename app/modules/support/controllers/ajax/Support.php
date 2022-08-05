<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Support extends MY_Controller {

	public function __construct(){
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);

	}

	public function status(){
		$id = $this->input->post('objectid');
		$object = $this->Autoload_Model->_get_where(array(
			'select' => 'id, publish',
			'table' => 'support',
			'where' => array('id' => $id),
		));
		
		$_update['publish'] = (($object['publish'] == 1)?0:1);
		$this->Autoload_Model->_update(array(
			'where' => array('id' => $id),
			'table' => 'support',
			'data' => $_update,
		));
	}

	public function listSupport(){
		$page = (int)$this->input->get('page');
		$data['from'] = 0;
		$data['to'] = 0;

		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 30;
		$keyword = $this->db->escape_like_str($this->input->get('keyword'));
		$catalogueid = (int)$this->input->get('catalogueid');
		if($catalogueid > 0){
			$config['total_rows'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id',
				'table' => 'support',
				'count' => TRUE,
				'where' => array('catalogueid' => $catalogueid),
				'keyword' => '(fullname LIKE \'%'.$keyword.'%\')',
			));
		}else{
			$config['total_rows'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id',
				'table' => 'support',
				'count' => TRUE,
				'keyword' => '(fullname LIKE \'%'.$keyword.'%\')',
			));
		}
		$html = '';
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('support/backend/support/view');
			$config['suffix'] = $this->config->item('url_suffix').(!empty($_SERVER['QUERY_STRING'])?('?'.$_SERVER['QUERY_STRING']):'');
			$config['first_url'] = $config['base_url'].$config['suffix'];
			$config['per_page'] = $perpage;
			$config['cur_page'] = $page;
			$config['page'] = $page;
			$config['uri_segment'] = 5;
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
			
			$listSupport = $this->Autoload_Model->_get_where(array(
				'select' => 'id, catalogueid, fullname, email, phone, zalo, skype, image, publish, (SELECT title FROM support_catalogue WHERE support_catalogue.id = support.catalogueid) as catalogueTitle',
				'table' => 'support',
				'where' => ($catalogueid ==0) ? '' : array( 'catalogueid' => $catalogueid) ,
				'keyword' => '(fullname LIKE \'%'.$keyword.'%\')',
				'start' => $page * $config['per_page'],
				'limit' => $config['per_page'],
				'order_by' => 'fullname asc',
			), TRUE);	
			

			
			if(isset($listSupport) && is_array($listSupport) && count($listSupport)){
				foreach($listSupport as $key => $val){
					$image = getthumb($val['image']);
					$html = $html .'<div class="col-lg-4">';
					$html = $html .'<div class="contact-box">';
					$html = $html .'<div class="col-sm-4">';
					$html = $html .'<div class="text-center">';
					$html = $html .'<a href="'.site_url('support/backend/support/update/'.$val['id'].'').'" title="" class="image img-cover">';
					$html = $html .'<img alt="" src="'.$image.'">';
					$html = $html .'</a>';
					$html = $html .'<div class="m-t-xs text-center">';
					$html = $html .'<div class="support-view switch">';
					$html = $html .'<div class="onoffswitch">';
					$html = $html .'<input type="checkbox" '.(($val['publish'] == 1) ? 'checked=""' : '').' class="onoffswitch-checkbox publish" data-id="'.$val['id'].'" id="publish-'.$val['id'].'">';
					$html = $html .'<label class="onoffswitch-label" for="publish-'.$val['id'].'">';
					$html = $html .'<span class="onoffswitch-inner"></span>';
					$html = $html .'<span class="onoffswitch-switch"></span>';
					$html = $html .'</label>';
					$html = $html .'</div>';
					$html = $html .'</div>';
					$html = $html .'</div>';
					$html = $html .'</div>';
					$html = $html .'</div>';
					$html = $html .'<div class="col-sm-8">';
					$html = $html .'<h3 class="name-support">'.$val['fullname'].'</h3>';
					$html = $html .'<address class="box-info">';
					$html = $html .'<p><strong class="text-blue">'. $val['catalogueTitle'].'</strong></p>';
					$html = $html .'<p><strong class="text-blue">SDT:</strong> '.$val['phone'].'</p>';
					$html = $html .'<p><strong class="text-blue">Email:</strong> '.$val['email'].'</p>';
					$html = $html .'<p><strong class="text-blue">Skype:</strong> '.$val['skype'].'</p>';
					$html = $html .'<p><strong class="text-blue">Zalo:</strong> '.$val['zalo'].'</p>';
					$html = $html .'<a type="button" title="chỉnh sửa thông tin" href="'.site_url('support/backend/support/update/'.$val['id'].'').'" class="text-edit">Chỉnh sửa</a>';
					$html = $html .'<a type="button" title="xóa" class="text-danger" data-title="Lưu ý: Khi bạn xóa danh mục, toàn bộ bài viết trong nhóm này sẽ bị xóa. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!" data-router="" data-id="" data-module="support_catalogue" data-child="1">Xóa</a>';
					$html = $html .'</address>';
					$html = $html .'</div>';
					$html = $html .'<div class="clearfix"></div>';
					$html = $html .'</div>';
					$html = $html .'</div>';
				}
			}
		}else{ 
			$html = $html .'<span class="col-lg-12"><small class="text-danger">Không có dữ liệu phù hợp</small></span>';
		} 
		echo json_encode(array(
			'pagination' => (isset($listPagination)) ? $listPagination : '',
			'html' => $html,
			'total' => $config['total_rows'],
		));die();

	}

	/* ================ RECYCLE ======================= */
	public function ajax_delete(){
		$param['module'] = $this->input->post('module');
		$param['id'] = (int)$this->input->post('id');
		$param['child'] = (int)$this->input->post('child');

		$flag = $this->Autoload_Model->_delete(array(
			'where' => array('id' => $param['id']),
			'table' => $param['module']
		));
		echo $flag; die();
	}

}