<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Media extends MY_Controller {

	public $module;
	function __construct() {
		parent::__construct();
		$this->load->library('nestedsetbie', array('table' => 'media_catalogue'));
		$this->module = 'media';
	}
	
	public function view($id = 0){
		$id = (int)$id;
		$detailMedia = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, slug, canonical, catalogueid, description, image, video_link, video_iframe, viewed, (SELECT fullname FROM user WHERE user.id = media.userid_created) as fullname, created, image_json',
			'table' => 'media',
			'where' => array('id' => $id),
		));
		if(!isset($detailMedia) || is_array($detailMedia) == false || count($detailMedia) == 0){
			$this->session->set_flashdata('message-danger', 'Bài viết không tồn tại');
			redirect(BASE_URL);
		}
		$data = comment(array('id' => $id, 'module' => $this->module));
		$detailCatalogue = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, canonical, image, lft, rgt, layoutid',
			'table' => 'media_catalogue',
			'where' => array('id' => $detailMedia['catalogueid']),
		));
		$data['breadcrumb'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, slug, canonical, lft, rgt',
			'table' => 'media_catalogue',
			'where' => array('lft <=' => $detailCatalogue['lft'],'rgt >=' => $detailCatalogue['lft']),
			'limit' => 1,
			'order_by' => 'lft ASC, order ASC'
		), TRUE);
		
		/* CẬP NHẬT LƯỢT XEM TỰ NHIÊN */
		$update['viewed'] = $detailMedia['viewed'] + 1;
		$update['viewed'] = $detailMedia['viewed'] + 1;
		$flag = $this->Autoload_Model->_update(array(
			'table' => 'media',
			'where' => array('id' => $id),
			'data' => $update,
		));
		
		/* OBJECT đã xem */
		$objectSee = isset($_COOKIE[CODE.'mediaCookie'])?$_COOKIE[CODE.'mediaCookie']:NULL;
		$objectid = json_decode($objectSee, TRUE);
		if (!isset($objectSee) || empty($objectSee)) {
			setcookie(CODE.'mediaCookie', json_encode(array(
				0 => $id
			)), time() + (86400 * 30), '/');
		}else{
			foreach ($objectid as $key) {
				$objectid[] = $id;
			}
			$objectid = array_values(array_unique($objectid));
			setcookie(CODE.'mediaCookie', json_encode($objectid), time() + (86400 * 30), '/');
		}
		
		$data['relatedmedia'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, slug, canonical, image',
			'table' => 'media',
			'where' => array('id!= ' => $detailMedia['id']),
			'order_by' => 'order desc, id desc'
		), TRUE);
		
		
		
		$data['module'] = 'media';
		$data['moduleid'] = $detailMedia['id'];
		$data['meta_title'] = !empty($detailMedia['meta_title'])?$detailMedia['meta_title']:$detailMedia['title'];
		$data['meta_description'] = html_entity_decode(htmlspecialchars_decode(!empty($detailMedia['meta_description'])?$detailMedia['meta_description']:cutnchar(strip_tags($detailMedia['description']), 300)));
		$data['meta_image'] = !empty($detailMedia['image'])?base_url($detailMedia['image']):'';
		$data['detailMedia'] = $detailMedia;
		$data['detailCatalogue'] = $detailCatalogue;
		$data['canonical'] = rewrite_url($detailMedia['canonical'], TRUE, TRUE);
		$data['og_type'] = 'media';
		
	
		if($detailCatalogue['layoutid'] == 2){
			$data['template'] = 'media/frontend/media/gallery';
		}else{
			$data['template'] = 'media/frontend/media/video';
		}
		
		$this->load->view('homepage/frontend/layout/home', isset($data)?$data:NULL);
	}
	
	
}
