<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends MY_Controller {

	public $module;
	function __construct() {
		parent::__construct();
		$this->load->library('nestedsetbie', array('table' => 'project_catalogue'));
		$this->module = 'project';
	}
	
	public function view($id = 0){
		$id = (int)$id;
		$detailproject = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, slug, canonical, catalogueid, description, image, viewed, (SELECT fullname FROM user WHERE user.id = project.userid_created) as fullname, created',
			'table' => 'project',
			'where' => array('id' => $id,'publish' => 0,),
		));
		if(!isset($detailproject) || is_array($detailproject) == false || count($detailproject) == 0){
			$this->session->set_flashdata('message-danger', 'dự án không tồn tại');
			redirect(BASE_URL);
		}
		$data = comment(array('id' => $id, 'module' => $this->module));
		$detailCatalogue = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, canonical, image, lft, rgt',
			'table' => 'project_catalogue',
			'where' => array('id' => $detailproject['catalogueid']),
		));
		$data['breadcrumb'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, slug, canonical, lft, rgt',
			'table' => 'project_catalogue',
			'where' => array('lft <=' => $detailCatalogue['lft'],'rgt >=' => $detailCatalogue['lft']),
			'order_by' => 'lft ASC, order ASC'
		), TRUE);
		
		/* CẬP NHẬT LƯỢT XEM TỰ NHIÊN */
		$update['viewed'] = $detailproject['viewed'] + 1;
		$update['viewed'] = $detailproject['viewed'] + 1;
		$flag = $this->Autoload_Model->_update(array(
			'table' => 'project',
			'where' => array('id' => $id),
			'data' => $update,
		));
		
		/* OBJECT đã xem */
		$objectSee = isset($_COOKIE[CODE.'projectCookie'])?$_COOKIE[CODE.'projectCookie']:NULL;
		$objectid = json_decode($objectSee, TRUE);
		if (!isset($objectSee) || empty($objectSee)) {
			setcookie(CODE.'projectCookie', json_encode(array(
				0 => $id
			)), time() + (86400 * 30), '/');
		}else{
			foreach ($objectid as $key) {
				$objectid[] = $id;
			}
			$objectid = array_values(array_unique($objectid));
			setcookie(CODE.'projectCookie', json_encode($objectid), time() + (86400 * 30), '/');
		}
		
		$data['relatedproject'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, slug, canonical, image, description, created',
			'table' => 'project',
			'where' => array('id!= ' => $detailproject['id'], 'catalogueid' => $detailCatalogue['id']),
			'order_by' => 'order desc, id desc',
			'limit' => 4,
		), TRUE);
		
		$data['tag'] = $this->Autoload_Model->_get_where(array(
			'select' => 'tb1.id , tb1.title, tb1.canonical',
			'table' => 'tag as tb1',
			'join' => array(
				array('tag_relationship as tb2' , 'tb2.tagid = tb1.id', 'inner'),
			),
			'where' => array(
				'publish' => 0,
				'tb2.module' => 'project',
				'tb2.moduleid' => $id,
			),
		), true);
		
		$data['module'] = 'project';
		$data['moduleid'] = $detailproject['id'];
		$data['meta_title'] = !empty($detailproject['meta_title'])?$detailproject['meta_title']:$detailproject['title'];
		$data['meta_description'] = html_entity_decode(htmlspecialchars_decode(!empty($detailproject['meta_description'])?$detailproject['meta_description']:cutnchar(strip_tags($detailproject['description']), 300)));
		$data['meta_image'] = !empty($detailproject['image'])?base_url($detailproject['image']):'';
		$data['detailproject'] = $detailproject;
		$data['detailCatalogue'] = $detailCatalogue;
		$data['canonical'] = rewrite_url($detailproject['canonical'], TRUE, TRUE);
		$data['og_type'] = 'project';
		
		if(isset($detailproject['url_view']) && $detailproject['url_view'] != ''){
			$data['template'] = $detailproject['url_view'];
		}else{
			$data['template'] = 'project/frontend/project/view';
		}
		$this->load->view('homepage/frontend/layout/home', isset($data)?$data:NULL);
	}
	
	
}
