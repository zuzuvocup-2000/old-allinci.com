<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promotional extends MY_Controller {

	public $module;
	function __construct() {
		parent::__construct();
		$this->load->library('nestedsetbie', array('table' => 'promotional_catalogue'));
		$this->load->helper(array('myfrontendcommon'));
	}
	
	public function view($id = 0){
		$id = (int)$id;
		$detailPromo = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title, slug, canonical, description, image_json',
			'table' => 'promotional',
			'where' => array('id' => $id),
		));
		if(!isset($detailPromo) || is_array($detailPromo) == false || count($detailPromo) == 0){
			$this->session->set_flashdata('message-danger', 'Chương trình không tồn tại');
			redirect(BASE_URL);
		}
		
		
		$data['module'] = 'promotional';
		$data['moduleid'] = $detailPromo['id'];
		$data['meta_title'] = !empty($detailPromo['meta_title'])?$detailPromo['meta_title']:$detailPromo['title'];
		$data['meta_description'] = html_entity_decode(htmlspecialchars_decode(!empty($detailPromo['meta_description'])?$detailPromo['meta_description']:cutnchar(strip_tags($detailPromo['description']), 300)));
		$data['meta_image'] = !empty($detailPromo['image'])?base_url($detailPromo['image']):'';
		$data['detailPromo'] = $detailPromo;
		$data['canonical'] = rewrite_url($detailPromo['canonical'], TRUE, TRUE);
		$data['og_type'] = 'promotional';
		
		
		$data['template'] = 'promotional/frontend/promotional/view';
		$this->load->view('homepage/frontend/layout/home', isset($data)?$data:NULL);
	}
	
	
}
