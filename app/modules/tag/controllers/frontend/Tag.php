<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tag extends MY_Controller {
	public $module;
	function __construct() {
		parent::__construct();
		$this->load->library(array('configbie'));
		$this->load->helper(array('myfrontendcommon'));
		$this->module = array(
			'article' => 'Bài viết',
			'product' => 'Sản phẩm',
			'media' => 'Thư viện',	
		);
	}
	public function view($id = 0, $page = 1){
		$tagid = (int)$id;
		$seoPage = '';
		$page = (int)$page;
		$module = $this->input->get('module');
		$detailTag = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, slug, canonical, description',
			'table' => 'tag',
			'where' => array(
				'id' => $tagid,
			),
		));
		$module = ($module == '') ? 'article' : $module;
		if(!empty($module)){
			$config['total_rows'] = $this->Autoload_Model->_get_where(array(
				'select' => 'tb1.id',
				'table' => $module.' as tb1',
				'query' => '(id IN (SELECT moduleid FROM tag_relationship WHERE module = \''.$module.'\' AND tagid = '.$tagid.'))',
				'where' => array('publish' => 0),
				'order_by' => 'tb1.id desc, tb1.order asc',
				'count' => TRUE,
			),TRUE);
			$config['base_url'] = rewrite_url($detailTag['canonical'], FALSE, TRUE);
			if($config['total_rows'] > 0){
				$this->load->library('pagination');
				$config['suffix'] = $this->config->item('url_suffix').(!empty($_SERVER['QUERY_STRING'])?('?'.$_SERVER['QUERY_STRING']):'');
				$config['prefix'] = 'trang-';
				$config['first_url'] = $config['base_url'].$config['suffix'];
				$config['per_page'] = ($module == 'product') ? 25: 5;
				$config['uri_segment'] = 2;
				$config['use_page_numbers'] = TRUE;
				$config['full_tag_open'] = '<div class="pagination"><ul class="uk-pagination uk-pagination-left">';
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
				$data['objectList'] = $this->Autoload_Model->_get_where(array(
					'select' => 'tb1.id, tb1.title, tb1.slug, tb1.canonical, tb1.image, tb1.created, tb1.viewed, tb1.description, '.(($module == 'product') ? 'tb1.price, tb1.price_sale, tb1.quantity_dau_ki, tb1.quantity_cuoi_ki' : '').'',
					'table' => $module.' as tb1',
					'query' => '(id IN (SELECT moduleid FROM tag_relationship WHERE module = \''.$module.'\' AND tagid = '.$tagid.'))',
					'where' => array('publish' => 0),
					'start' => ($page * $config['per_page']),
					'limit' => $config['per_page'],
					'order_by' => 'tb1.id desc, tb1.order asc',
				),TRUE);
			}
			$data['module'] = $module;
			$data['template'] = 'tag/frontend/tag/'.($module == 'product') ? 'product' : 'view'.'';
		}else{
			$temp = '';
			foreach($this->module as $key => $val){
				$temp[] = array(
					'result' => array(
						'module' => $key,
						'title' => $val,
						'data' => $this->Autoload_Model->_get_where(array(
							'select' => 'tb1.id, tb1.title, tb1.slug, tb1.canonical, tb1.image, tb1.created, tb1.description, tb1.viewed, '.(($key == 'product') ? 'tb1.price, tb1.price_sale, tb1.quantity_dau_ki, tb1.quantity_cuoi_ki' : '').'',
							'table' => $key.' as tb1',
							'query' => '(id IN (SELECT moduleid FROM tag_relationship WHERE module = \''.$key.'\' AND tagid = '.$tagid.'))',
							'where' => array('publish' => 0),
							'limit' => 5,
							'order_by' => 'tb1.id desc, tb1.order asc',
						),TRUE)
					)
				);
			}
			$data['objectTag'] = $temp;
		}
		$data['detailTag'] = $detailTag;
		if(!isset($data['canonical']) || empty($data['canonical'])){
			$data['canonical'] = $config['base_url'].$this->config->item('url_suffix');
		}
		$data['meta_title'] = (!empty($detailTag['meta_title'])?$detailTag['meta_title']:$detailTag['title']).$seoPage;
		$data['meta_description'] = (!empty($detailTag['meta_description'])?$detailTag['meta_description']:cutnchar(strip_tags($detailTag['description']), 255)).$seoPage;
		$data['meta_image'] = !empty($detailTag['image'])?base_url($detailTag['image']):'';
		$data['script']= 'tag';
		$data['template'] = 'tag/frontend/tag/view';
		$this->load->view('homepage/frontend/layout/home', isset($data)?$data:NULL);
	}
	
	
}
