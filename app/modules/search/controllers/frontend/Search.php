<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends MY_Controller {
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
	public function view($page = 1){
		$seoPage = '';
		$page = (int)$page;
		$keyword = $this->input->get('keyword');
		$keyword = (!empty($keyword))? '(title LIKE \'%'.$keyword.'%\')' : '';
		$module = 'product';

		$_select = get_query_select_highlight($module);



		if(!empty($module)){
			$config['total_rows'] = $this->Autoload_Model->_get_where(array(
				'select' => 'object.id',
				'table' => $module.' as object',
				'where' => array('object.publish' => 0),
				'keyword' => $keyword,
				'distinct' => 'true',
				'count' =>TRUE,
			));
			$config['base_url'] = base_url('tim-kiem');
			if($config['total_rows'] > 0){
				$this->load->library('pagination');
				$config['suffix'] = $this->config->item('url_suffix').(!empty($_SERVER['QUERY_STRING'])?('?'.$_SERVER['QUERY_STRING']):'');
				$config['prefix'] = 'trang-';
				$config['first_url'] = $config['base_url'].$config['suffix'];
				$config['per_page'] = ($module == 'product') ? 25: 10;
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
					'select' => $_select,
					'table' => $module.' as object',
					'where' => array('object.publish' => 0),
					'limit' => $config['per_page'],
					'start' => $page * $config['per_page'],
					'keyword' => $keyword,
					'distinct' => 'true',
					'order_by' => 'id desc',
				), true);
			}
			$data['module'] = $module;
			$data['template'] = 'search/frontend/search/view';
		}else{
			$temp = '';
			foreach($this->module as $key => $val){
				$temp[] = array(
					'result' => array(
						'module' => $key,
						'title' => $val,
						'data' => $this->Autoload_Model->_get_where(array(
							'select' => 'object.id, object.title, object.slug, object.canonical, object.image, object.created, object.description, object.viewed, '.(($key == 'product') ? 'object.price, object.price_sale, object.quantity_dau_ki, object.quantity_cuoi_ki' : '').'',
							'table' => $key.' as object',
							'query' => '(id IN (SELECT moduleid FROM tag_relationship WHERE module = \''.$key.'\' AND tagid = '.$tagid.'))',
							'where' => array('publish' => 0),
							'limit' => 5,
							'order_by' => 'object.id desc, object.order asc',
						),TRUE)
					)
				);
			}
			$data['objectTag'] = $temp;
		}
		if(!isset($data['canonical']) || empty($data['canonical'])){
			$data['canonical'] = $config['base_url'].$this->config->item('url_suffix');
		}
		$data['meta_title'] = 'Kết quả tìm kiếm cho từ khóa: '.$this->input->get('keyword').''.$seoPage;
		$data['meta_image'] = !empty($detailTag['image'])?base_url($detailTag['image']):'';
		$this->load->view('homepage/frontend/layout/home', isset($data)?$data:NULL);
	}
	
	
}
