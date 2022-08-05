<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {
	public function __construct(){
		parent::__construct();
	}

	public function test(){
		$this->load->view('homepage/frontend/home/test');
	}
	public function test_mail(){
		$data['param'] = array(
			'header' => 'Thông tin đặt hàng',
			'fullname' => 'Đỗ Minh Tú',
			'email' => 'tudo210994@gmail.com',
			'p_phone' => '0364315545',
			'address' => 'Long Biên, Hà Nội',
			'total_price' => '150000',
			'payment_code' => 'SP001',
			'payment_created' => '25/05/2020',
			'fee' => 0,
			'product' => '',
			'web' => $this->general['contact_website'],
			'hotline' => $this->general['contact_hotline'],
			'phone' => $this->general['contact_hotline'],
			'logo' => base_url($this->general['homepage_logo']),
			'brandname' => $this->general['contact_website'],
			'system_email' => $this->general['contact_email'],
			'system_address' => $this->general['contact_address'],
		);
		$this->load->view('homepage/frontend/home/test_mail', $data);
	}

	public function index(){
		$article_1 = layout_control(array(
			'layoutid' => 1,
			'children' => array(
				'flag' => FALSE,
				'post' => FALSE,
				// 'limit' => 6,
			),
			'post' => array(
				'flag' => TRUE,
				'limit' => 8
			)
		), false);

		// $data['article_1'] = $article_1;

		$article_2 = layout_control(array(
			'layoutid' => 6,
			'children' => array(
				'flag' => FALSE,
				'post' => FALSE,
				// 'limit' => 6,
			),
			'post' => array(
				'flag' => TRUE,
				'limit' => 8
			)
		), false);


		// $data['article_2'] = $article_2;

		$menuPrd = layout_control(array(
			'layoutid' => 2,
			'children' => array(
				'flag' => true,
				'post' => true,
				'limit' => 1000,
			),
			'post' => array(
				'flag' => false,
				'limit' => 8
			)
		), false);


		$data['menuPrd'] = $menuPrd;
		// print_r($menuPrd); exit;


		$chef = layout_control(array(
			'layoutid' => 7,
			'children' => array(
				'flag' => FALSE,
				'post' => FALSE,
				// 'limit' => 6,
			),
			'post' => array(
				'flag' => true,
				'limit' => 1
			)
		), FALSE);

		// print_r($chef); exit;
		// $data['chef'] = $chef;



		$customer = layout_control(array(
			'layoutid' => 3,
			'children' => array(
				'flag' => FALSE,
				'post' => FALSE,
				// 'limit' => 6,
			),
			'post' => array(
				'flag' => true,
				'limit' => 6
			)
		), FALSE);

		// $data['customer'] = $customer;
		// print_r($customer); exit;

		$booking = layout_control(array(
			'layoutid' => 8,
			'children' => array(
				'flag' => FALSE,
				'post' => FALSE,
				// 'limit' => 6,
			),
			'post' => array(
				'flag' => true,
				'limit' => 1
			)
		), FALSE);
		// $data['booking'] = $booking;

		// print_r($booking); exit;


		$highlightPrd = get_group_highlight_object('ishome', 'product_catalogue');
		// print_r($highlightPrd); exit;
		$data['highlightPrd'] = $highlightPrd;

		$detailCatalogue = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, slug, canonical, image, excerpt, description, lft, rgt',
			'table' => 'article_catalogue',
			'where' => array(
				'publish' => 0,
				'id' => 5,
			),
		));

		// $gallery = $this->Autoload_Model->_get_where(array(
		// 	'select' => 'id, title, image_json',
		// 	'table' => 'media',
		// 	'where' => array(
		// 		'publish' => 0,
		// 		'id' => 1,
		// 	),
		// ));


	    if(isset($gallery) && is_array($gallery) && count($gallery)){ 
	    	$album = json_decode($gallery['image_json'], true);
			$data['album'] = $album;
			// print_r($album); exit;
	    }


		for ($i = 1; $i <= 8; $i++) {
			if(!isset($this->general['commit_icon_'.$i])) break;
	        $list_commit[] = array(
	            'image' => $this->general['commit_icon_'.$i],
	            'title' => $this->general['commit_title_'.$i],
	            'content' => $this->general['commit_content_'.$i],
	        );
	    }
		// $data['list_commit'] = $list_commit;
	    
	    

		$data['canonical'] = base_url();
		$data['meta_title'] = $this->general['seo_meta_title'];
		$data['meta_description'] = $this->general['seo_meta_description'];
		$data['og_type'] = 'website';
		$data['template'] = 'homepage/frontend/home/index';
		$this->load->view('homepage/frontend/layout/home', isset($data)?$data:NULL);
	}

	public function close_website(){
		$this->load->view('homepage/frontend/home/close_website', isset($data)?$data:NULL);
	}
}
