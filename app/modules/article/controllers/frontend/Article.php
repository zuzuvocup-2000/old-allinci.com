<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article extends MY_Controller {

	public $module;
	function __construct() {
		parent::__construct();
		$this->load->library('nestedsetbie', array('table' => 'article_catalogue'));
		$this->module = 'article';
	}
	
	public function view($id = 0){
		$id = (int)$id;

		$detailArticle = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, slug, canonical, catalogueid, description, image, album, viewed, (SELECT fullname FROM user WHERE user.id = article.userid_created) as fullname, created',
			'table' => 'article',
			'where' => array(
				'id' => $id,
				'publish' => 0,
			),
		));
		if(!isset($detailArticle) || is_array($detailArticle) == false || count($detailArticle) == 0){
			$this->session->set_flashdata('message-danger', 'Bài viết không tồn tại');
			redirect(BASE_URL);
		}

		$join[] = array('catalogue_relationship as tb2', 'tb2.catalogueid = tb3.id', 'inner');

		$detailArticle['list_cat'] = $this->Autoload_Model->_get_where(array(
			'select' => 'tb3.id, tb3.title, tb3.slug, tb3.canonical',
			'table' => 'article_catalogue as tb3',
			'where' => array(
				'tb3.publish' => 0,
				'tb2.moduleid' => $id,
			),
			'join' => $join,
		), true);

		// print_r($detailArticle); exit;s

		$data = comment(array('id' => $id, 'module' => $this->module));
		$detailCatalogue = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, canonical, image, lft, rgt',
			'table' => 'article_catalogue',
			'where' => array('id' => $detailArticle['catalogueid']),
		));
		$data['breadcrumb'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, slug, canonical, lft, rgt',
			'table' => 'article_catalogue',
			'where' => array('lft <=' => $detailCatalogue['lft'],'rgt >=' => $detailCatalogue['lft']),
			'order_by' => 'lft ASC, order ASC'
		), TRUE);
		
		/* CẬP NHẬT LƯỢT XEM TỰ NHIÊN */
		$update['viewed'] = $detailArticle['viewed'] + 1;
		$update['viewed'] = $detailArticle['viewed'] + 1;
		$flag = $this->Autoload_Model->_update(array(
			'table' => 'article',
			'where' => array('id' => $id),
			'data' => $update,
		));
		
		/* OBJECT đã xem */
		$objectSee = isset($_COOKIE[CODE.'articleCookie'])?$_COOKIE[CODE.'articleCookie']:NULL;
		$objectid = json_decode($objectSee, TRUE);
		if (!isset($objectSee) || empty($objectSee)) {
			setcookie(CODE.'articleCookie', json_encode(array(
				0 => $id
			)), time() + (86400 * 30), '/');
		}else{
			foreach ($objectid as $key) {
				$objectid[] = $id;
			}
			$objectid = array_values(array_unique($objectid));
			setcookie(CODE.'articleCookie', json_encode($objectid), time() + (86400 * 30), '/');
		}
		
		$data['relatedArticle'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, slug, canonical, image, description, created',
			'table' => 'article',
			'where' => array('id!= ' => $detailArticle['id'], 'catalogueid' => $detailCatalogue['id']),
			'order_by' => 'order desc, id desc',
			'limit' => 6,
		), TRUE);
		
		$data['tag'] = $this->Autoload_Model->_get_where(array(
			'select' => 'tb1.id , tb1.title, tb1.canonical',
			'table' => 'tag as tb1',
			'join' => array(
				array('tag_relationship as tb2' , 'tb2.tagid = tb1.id', 'inner'),
			),
			'where' => array(
				'publish' => 0,
				'tb2.module' => 'article',
				'tb2.moduleid' => $id,
			),
		), true);
		
		$data['module'] = 'article';
		$data['moduleid'] = $detailArticle['id'];
		$data['meta_title'] = !empty($detailArticle['meta_title'])?$detailArticle['meta_title']:$detailArticle['title'];
		$data['meta_description'] = html_entity_decode(htmlspecialchars_decode(!empty($detailArticle['meta_description'])?$detailArticle['meta_description']:cutnchar(strip_tags($detailArticle['description']), 300)));
		$data['meta_image'] = !empty($detailArticle['image'])?base_url($detailArticle['image']):'';
		$data['detailArticle'] = $detailArticle;
		$data['detailCatalogue'] = $detailCatalogue;
		$data['canonical'] = rewrite_url($detailArticle['canonical'], TRUE, TRUE);
		$data['og_type'] = 'article';
		
		if(isset($detailArticle['url_view']) && $detailArticle['url_view'] != ''){
			$data['template'] = $detailArticle['url_view'];
		}else{
			$data['template'] = 'article/frontend/article/view';
		}
		$this->load->view('homepage/frontend/layout/home', isset($data)?$data:NULL);
	}
	
	
}
