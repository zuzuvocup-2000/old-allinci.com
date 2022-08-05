<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogue extends MY_Controller {

	public $module;
	function __construct() {
		parent::__construct();
		$this->load->library('nestedsetbie', array('table' => 'article_catalogue'));
	}

	public function View($id = 0, $page = 1){
		$id = (int)$id;
		$page = (int)$page;
		$seoPage = '';
		$detailCatalogue = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, canonical, image, lft, rgt, meta_keyword, meta_title, meta_description, excerpt, description',
			'table' => 'article_catalogue',
			'where' => array('id' => $id),
		));
		if(!isset($detailCatalogue) && !is_array($detailCatalogue) && count($detailCatalogue) == 0){
			$this->session->set_flashdata('message-danger', 'Danh mục bài viết không tồn tại');
			redirect(BASE_URL);
		}
		$data['breadcrumb'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, slug, canonical, lft, rgt',
			'table' => 'article_catalogue',
			'where' => array('lft <=' => $detailCatalogue['lft'],'rgt >=' => $detailCatalogue['lft']),
			'order_by' => 'lft ASC, order ASC'
		), TRUE);

		$config['total_rows'] = $this->Autoload_Model->_condition(array(
			'module' => 'article',
			'select' => '`object`.`id`',
			'where' => '`object`.`publish_time` <= "'.$this->currentTime.'" AND `object`.`publish` = 0',
			'catalogueid' => $id,
			'count' => TRUE
		));



		$config['base_url'] = rewrite_url($detailCatalogue['canonical'], FALSE, TRUE);
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['suffix'] = $this->config->item('url_suffix').(!empty($_SERVER['QUERY_STRING'])?('?'.$_SERVER['QUERY_STRING']):'');
			$config['prefix'] = 'trang-';
			$config['first_url'] = $config['base_url'].$config['suffix'];
			$config['per_page'] = 6;
			$config['uri_segment'] = 2;
			$config['use_page_numbers'] = TRUE;
			$config['full_tag_open'] = '<div class="pagination js-news-paging uk-text-left"><ul class="uk-pagination">';
			$config['full_tag_close'] = '</ul></div>';
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="uk-active"><a>';
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
			$seoPage = ($page >= 2)?(' - Trang '.$page):'';
			if($page >= 2){
				$data['canonical'] = $config['base_url'].'/trang-'.$page.$this->config->item('url_suffix');
			}
			$page = $page - 1;


			$articleList = $this->Autoload_Model->_condition(array(
				'module' => 'article',
				'select' => '`object`.`id`, `object`.`title`,`object`.`canonical`, `object`.`image`, `object`.`created`, `object`.`viewed`, `object`.`description`, (SELECT fullname FROM user WHERE user.id = object.userid_created) as user_created, (SELECT count(id) FROM comment WHERE comment.detailid = object.id AND comment.module = \'article\') as comment
				',
				'where' => '`object`.`publish_time` <= "'.$this->currentTime.'" AND `object`.`publish` = 0',
				'catalogueid' => $id,
				'limit' => $config['per_page'],
				'start' => ($page * $config['per_page']),
				'order_by' => '`object`.`order` desc, `object`.`title` asc, `object`.`id` desc',
			));

			if(isset($articleList) && is_array($articleList) && count($articleList)){ 
				$join[] = array('catalogue_relationship as tb2', 'tb2.catalogueid = tb3.id', 'innner');
				foreach ($articleList as $key => $val) {
					$articleList[$key]['list_cat'] = $this->Autoload_Model->_get_where(array(
						'select' => 'tb3.id, tb3.title, tb3.slug, tb3.canonical',
						'table' => 'article_catalogue as tb3',
						'where' => array(
							'tb3.publish' => 0,
							'tb2.moduleid' => $val['id'],
						),
						'join' => $join,
					), true);
				}
		    }
		    // print_r($articleList); exit;
			$data['articleList'] = $articleList;
		}


		$data['module'] = 'article_catalogue';
		$data['meta_title'] = (!empty($detailCatalogue['meta_title'])?$detailCatalogue['meta_title']:$detailCatalogue['title']).$seoPage;
		$data['meta_description'] = (!empty($detailCatalogue['meta_description'])?$detailCatalogue['meta_description']:cutnchar(strip_tags($detailCatalogue['description']), 255)).$seoPage;
		$data['meta_image'] = !empty($detailCatalogue['image'])?base_url($detailCatalogue['image']):'';
		$data['detailCatalogue'] = $detailCatalogue;
		if(!isset($data['canonical']) || empty($data['canonical'])){
			$data['canonical'] = $config['base_url'].$this->config->item('url_suffix');
		}
		if(isset($detailCatalogue['url_view']) && $detailCatalogue['url_view'] != ''){
			$data['template'] = $detailCatalogue['url_view'];
		}else{
			if(isset($detailCatalogue['canonical']) && $detailCatalogue['canonical'] == 'about-us'){
				$data['template'] = 'article/frontend/catalogue/contact';
			}else{
				$data['template'] = 'article/frontend/catalogue/view';
			}
		}

		$this->load->view('homepage/frontend/layout/home', isset($data)?$data:NULL);
	}
}
