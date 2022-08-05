<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends MY_Controller {

	public $module;
	function __construct() {
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library(array('configbie'));
		$this->load->library('nestedsetbie', array('table' => 'product_catalogue'));
		$this->load->helper('myproduct');
		$this->module = 'product';
	}

	public function view($page = 1){
		$page = (int)$page;
		$query = '';
		$this->commonbie->permission("product/backend/product/view", $this->auth['permission']);
		$data['from'] = 0;
		$data['to'] = 0;
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 20;
		$keyword = $this->input->get('keyword');
		if(!empty($keyword)){
			$keyword = '(title LIKE \'%'.$keyword.'%\' OR description LIKE \'%'.$keyword.'%\')';
		}

		$catalogueid = $this->input->get('catalogueid');

		if(!empty($catalogueid)){
			$query = 'catalogueid =  '.$catalogueid;
			$detailCatalogue = $this->Autoload_Model->_get_where(array(
				'select' => 'id, attrid',
				'table' => 'product_catalogue',
				'where' => array('id' => $catalogueid),
			));
			$data['attribute_catalogue'] = getListAttr($detailCatalogue['attrid']);
		}
		$config['total_rows'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id',
			'table' => 'product',
			'keyword' => $keyword,
			'count' => TRUE,
		));

		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('product/backend/product/view');
			$config['suffix'] = $this->config->item('url_suffix').(!empty($_SERVER['QUERY_STRING'])?('?'.$_SERVER['QUERY_STRING']):'');
			$config['first_url'] = $config['base_url'].$config['suffix'];
			$config['per_page'] = $perpage;
			$config['cur_page'] = $page;
			$config['uri_segment'] = 5;
			$config['use_page_numbers'] = TRUE;
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
			$data['PaginationList'] = $this->pagination->create_links();
			$totalPage = ceil($config['total_rows']/$config['per_page']);
			$page = ($page <= 0)?1:$page;
			$page = ($page > $totalPage)?$totalPage:$page;
			$page = $page - 1;
			$data['from'] = ($page * $config['per_page']) + 1;
			$data['to'] = ($config['per_page']*($page+1) > $config['total_rows']) ? $config['total_rows']  : $config['per_page']*($page+1);
			$data['listData'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id, title, canonical, image, ishome, highlight, publish, order, price, price_sale, quantity_cuoi_ki, quantity_dau_ki, catalogue, (SELECT account FROM user WHERE user.id = product.userid_created) as user_created, version, viewed, catalogueid, (SELECT title FROM product_catalogue WHERE product_catalogue.id = product.catalogueid) as catalogue_title, price_contact',
				'table' => 'product',
				'query' => $query,
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'keyword' => $keyword,
				'order_by' => 'order desc, updated desc',
			), TRUE);
		}
		
		$data['script'] = 'product';
		$data['config'] = $config;
		$data['template'] = 'product/backend/product/view';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function Create(){
		$attribute_catalogue = $this->Autoload_Model->_get_where(array(
			'table' => 'attribute_catalogue',
			'count' => 'true',
		),true);
		$data['countAttribute_catalogue'] = $attribute_catalogue;

		if($this->input->post('create')){
			$this->commonbie->permission("product/backend/product/create", $this->auth['permission']);

			$album=$this->input->post('album');
			$data = getDataPost($data);

			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('title', 'Tiêu đề sản phẩm', 'trim|required');
			// $this->form_validation->set_rules('content[title][]', 'Tiêu đề thành phần mở rộng', 'trim|required');
			$this->form_validation->set_rules('catalogueid', 'Danh mục chính', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('canonical', 'Đường dẫn bài viết', 'trim|required|callback__CheckCanonical');
			$this->form_validation->set_rules('attribute[]', '', 'callback__CheckAttribute');
			$this->form_validation->set_rules('attribute_catalogue[]', '', 'callback__CheckAttribute_catalogue');
			$this->form_validation->set_rules('quantity_start[]', '', 'callback__CheckQuantity_start');
			$this->form_validation->set_rules('quantity_end[]', '', 'callback__CheckQuantity_end');
			if($this->form_validation->run($this)){
				$_insert = array(
					'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'slug' => slug(htmlspecialchars_decode(html_entity_decode($this->input->post('title')))),
					'canonical' => slug($this->input->post('canonical')),
					'description' => $this->input->post('description'),
					'extend_description' =>json_encode( $this->input->post('content')),
					'catalogueid' => $this->input->post('catalogueid'),
					'catalogue' => json_encode($this->input->post('catalogue')),
					'tag' => json_encode($this->input->post('tag')),
					'meta_title' => $this->input->post('meta_title'),
					'meta_description' => $this->input->post('meta_description'),
					'publish' => $this->input->post('publish'),
					'image' => is($album[0]),
					'image_json' => is(base64_encode(json_encode($album))),
					'userid_created' => $this->auth['id'],
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
					'price' => $this->input->post('price'),
					'price_sale' => (int)str_replace('.','',$this->input->post('price_sale')),
					'price_contact' => is($this->input->post('price_contact')),
					'code' => $this->input->post('code'),
					'barcode' => $this->input->post('barcode'),
					'inventory' => $this->input->post('inventory'),
					'quantity_dau_ki' => $this->input->post('quantity_dau_ki'),
					'quantity_cuoi_ki' => $this->input->post('quantity_cuoi_ki'),
					'unlimited_sale' => is($this->input->post('unlimited_sale')),
					'wholesale' => $data['wholesale'],
					'wholesale_json' => base64_encode(json_encode(array($data['quantity_start'],$data['quantity_end'],$data['price_wholesale']))),
					'brandid' => $data['brandid'],
					'made_in' => $data['made_in'],
					'model' => $data['model'],
					'relateid' => is($data['create_product']),
					'version' => $data['version'],
					'publish_time' => merge_time($this->input->post('post_date'), $this->input->post('post_time')),
					'version_json' => base64_encode(json_encode(array($data['checkbox'],$data['attribute_catalogue'],$data['attribute']))),
					'image_color_json' => $data['image_color_json'],
					'video' => htmlspecialchars_decode(html_entity_decode($data['video'])),
					'icon_hot' => $data['icon_hot'],
					'prd_rela' => json_encode($this->input->post('prd_rela')),
					// 'file_price' => $this->input->post('file_price'),
					'landing_link' => $this->input->post('landing_link'),
				);
				$resultid_main = $this->Autoload_Model->_create(array(
					'table' => 'product',
					'data' => $_insert,
				));

				if($resultid_main > 0){
					$canonical = slug($this->input->post('canonical'));
					createData($data, $resultid_main, $canonical);
						
					if($data['create_product'] == -10000){
						// tạo phiên bản đồng thời tạo luôn sản phẩm
						if(isset($data['title_version']) && is_array($data['title_version']) && count($data['title_version'])){
							foreach($data['title_version'] as $key => $val){
								$_insert_product = array(
									'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title').' '.$val)),
									'slug' => slug(htmlspecialchars_decode(html_entity_decode($this->input->post('title').' '.$val))),
									'canonical' => slug($this->input->post('canonical').' '.$val),
									'description' => $this->input->post('description'),
									'extend_description' =>json_encode( $this->input->post('content')),
									'catalogueid' => $this->input->post('catalogueid'),
									'catalogue' => json_encode($this->input->post('catalogue')),
									'tag' => json_encode($this->input->post('tag')),
									'meta_title' => $this->input->post('meta_title'),
									'meta_description' => $this->input->post('meta_description'),
									'publish' => $this->input->post('publish'),
									'image' => is($data['image_version'][$key]),
									'image_json' => is(base64_encode(json_encode($album))),
									'userid_created' => $this->auth['id'],
									'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
									'price' =>(int)str_replace('.','',$data['price_version'][$key]) ,
									'price_sale' => (int)str_replace('.','',$this->input->post('price_sale')),
									'price_contact' => is($this->input->post('price_contact')),
									'code' =>$data['code_version'][$key] ,
									'barcode' =>$data['barcode_version'][$key] ,
									'inventory' => $this->input->post('inventory'),
									'quantity_dau_ki' => $this->input->post('quantity_dau_ki'),
									'quantity_cuoi_ki' => $this->input->post('quantity_cuoi_ki'),
									'unlimited_sale' => is($this->input->post('unlimited_sale')),
									'wholesale' => $data['wholesale'],
									'wholesale_json' => base64_encode(json_encode(array($data['quantity_start'],$data['quantity_end'],$data['price_wholesale']))),
									'brandid' => $data['brandid'],
									'made_in' => $data['made_in'],
									'model' => $data['model'],
									'publish_time' => merge_time($this->input->post('post_date'), $this->input->post('post_time')),
									'relateid' => $resultid_main,
									'image_color_json' => $data['image_color_json'],
									'video' => htmlspecialchars_decode(html_entity_decode($data['video'])),
									'icon_hot' => $data['icon_hot'],
									'prd_rela' => json_encode($this->input->post('prd_rela')),
									'landing_link' => $this->input->post('landing_link'),
								);
								$resultid = $this->Autoload_Model->_create(array(
									'table' => 'product',
									'data' => $_insert_product,
								));
								$canonical =  slug($this->input->post('canonical').' '.$val);
								createData($data, $resultid, $canonical ,$this->auth['id']);
							}
						}
					}
					$this->session->set_flashdata('message-success', 'Thêm sản phẩm mới thành công');
					redirect('product/backend/product/view');
				}
			}
		}
		$data['script'] = 'product';
		$data['template'] = 'product/backend/product/create';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	public function Update($id = 0){
		$data = comment(array('id' => $id, 'module' => $this->module));
		$page = ($this->input->get('page')) ? $this->input->get('page') : 1;
		$this->commonbie->permission("product/backend/product/update", $this->auth['permission']);
		$attribute_catalogue = $this->Autoload_Model->_get_where(array(
			'table' => 'attribute_catalogue',
			'count' => 'true',
		),true);
		$data['countAttribute_catalogue'] = $attribute_catalogue;
		$id = (int)$id;
		$product = $this->Autoload_Model->_get_where(array(
			'select' => 'id, code, title, slug, canonical, description, meta_title, meta_description, tag, catalogueid, catalogue, brandid, image, image_json, price, price_sale, price_contact,  barcode, inventory, quantity_dau_ki, quantity_cuoi_ki, wholesale, wholesale_json, version_json, publish, publish_time, unlimited_sale, image_color_json, made_in, model, extend_description, video, icon_hot, prd_rela, landing_link',
			'table' => 'product',
			'where' => array('id' => $id),
		));
		if(!isset($product) || is_array($product) == false || count($product) == 0){
			$this->session->set_flashdata('message-danger', 'sản phẩm không tồn tại');
			redirect('product/backend/product/view');
		}
		$data['product']=$product;

		
		//Kiểm tra xem sản phẩm có nằm trong chương trình khuyến mại nào không
		$current = gmdate('Y-m-d H:i:s', time() + 7*3600);
		$query = ' AND tb2.publish =  1 AND tb2.start_date  <=  "'.$current. '" AND ( tb2.end_date >= "'.$current.'" OR tb2.end_date = "0000-00-00 00:00:00" ) ';
		$promotional = $this->Autoload_Model->_get_where(array(
			'select' => 'tb2.id, tb2.catalogue, tb2.title, tb2.canonical, tb2.created, tb2.image_json, tb2.module, tb2.start_date, tb2.end_date, tb2.publish, tb2.discount_type, tb2.discount_value, tb2.condition_value, tb2.condition_type, tb2.freeship, tb2.freeshipAll, tb2.condition_value_1, tb2.condition_type_1, tb2.use_common, tb2.code, tb2.limmit_code, tb2.cityid, tb2.discount_moduleid',
				'table' => 'promotional',
			'table' => 'promotional_relationship as tb1',
			'join' => array(
				array('promotional as tb2', 'tb1.promotionalid = tb2.id', 'inner'),
			),
			'query' => 'tb1.module = "product" AND tb1.moduleid = '.$id.$query,
		),true);
		if(check_array($promotional)){
			foreach ($promotional as $key => $value) {
				$promotional1 = json_decode(getPromotional($value), true);
				$data['promotional'][$key] = $value;
				$data['promotional'][$key]['use_common'] = $promotional1['use_common'];
				$data['promotional'][$key]['detail'] = $promotional1['detail'];
			}
		}
		
		$version_json = json_decode(base64_decode($product['version_json']), true);
		$data['price_contact'] =$product['price_contact'];
		$data['inventory'] =$product['inventory'];

		$data['attribute'] = $version_json[2];
		if(isset($data['attribute']) && is_array($data['attribute']) && count($data['attribute'])){
			foreach ($data['attribute'] as $key => $value) {
				if($value == ''){
					$data['attribute_json'][$key]['json']='';
				}else{
					$data['attribute_json'][$key]['json']=base64_encode(json_encode($value));
				}
			}
		}

		$data['checkbox']= $version_json[0];
		$data['attribute_catalogue']= $version_json[1];
		if(isset($version_json[1]) && is_array($version_json[1]) && count($version_json[1])){
			foreach ($version_json[1] as $key => $value) {
				if($value == 2){
					if(isset($version_json[2][$key]) && is_array($version_json[2][$key]) && count($version_json[2][$key])){
						$color= $this->Autoload_Model->_get_where(array(
							'select' => 'id, title, color',
							'table' => 'attribute',
							'where_in' => $version_json[2][$key],
							'where_in_field' => 'id',
						),true);
						foreach ($color as $key => $value) {
							$data['color'][$value['id']]['title'] = $value['title'];
							$data['color'][$value['id']]['color'] = $value['color'];
						}
					}
				}
			}
		}


		$data['image_color'] = json_decode(base64_decode($product['image_color_json']), true);
		$data['image_json'] = json_decode(base64_decode($product['image_json']), true);
		$product_version = $this->Autoload_Model->_get_where(array(
			'select' => 'code_version, title, image, price_version,  barcode_version, attribute1, attribute2',
			'table' => 'product_version',
			'where' => array('productid' => $id),
			'order_by'=>'id ASC'
		),true);

		foreach ($product_version as $key => $val) {
			$data['image_version'][]=$val['image'];
			$data['title_version'][]=$val['title'];
			$data['price_version'][]=$val['price_version'];
			$data['code_version'][]=$val['code_version'];
			$data['barcode_version'][]=$val['barcode_version'];
			$data['attribute1'][] = $val['attribute1'];
			$data['attribute2'][] = $val['attribute2'];
		}
		if(isset($data['title_version']) && is_array($data['title_version']) && count($data['title_version'])){
			$data['version']= count($data['title_version']);
		}else{
			$data['version'] = 0;
		}

		$data['wholesale'] =0;
		if(isset($data['price_wholesale']) && is_array($data['price_wholesale']) && count($data['price_wholesale'])){
			$data['wholesale'] =1;
		}

		$product_wholesale = $this->Autoload_Model->_get_where(array(
			'select' => 'quantity_start, quantity_end, price_wholesale',
			'table' => 'product_wholesale',
			'where' => array('productid' => $id),
			'order_by'=>'id ASC'
		),true);
		foreach ($product_wholesale as $key => $val) {
			$data['quantity_start'][]=$val['quantity_start'];
			$data['quantity_end'][]=$val['quantity_end'];
			$data['price_wholesale'][]=$val['price_wholesale'];
		}
		
		$data['brandid'] = $product['brandid'];
		if($this->input->post('update')){

			$album = $this->input->post('album');
			$data = getDataPost($data);

			// print_r($this->input->post('price')); exit;

			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('title', 'Tiêu đề sản phẩm', 'trim|required');
			// $this->form_validation->set_rules('content[title][]', 'Tiêu đề thành phần mở rộng', 'trim|required');
			$this->form_validation->set_rules('catalogueid', 'Danh mục chính', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('canonical', 'Đường dẫn bài viết', 'trim|required|callback__CheckCanonical');
			$this->form_validation->set_rules('attribute[]', '', 'callback__CheckAttribute');
			$this->form_validation->set_rules('attribute_catalogue[]', '', 'callback__CheckAttribute_catalogue');
			$this->form_validation->set_rules('quantity_start[]', '', 'callback__CheckQuantity_start');
			$this->form_validation->set_rules('quantity_end[]', '', 'callback__CheckQuantity_end');
			
			if($this->form_validation->run($this)){
				$_update = array(
					'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'slug' => slug(htmlspecialchars_decode(html_entity_decode($this->input->post('title')))),
					'canonical' => slug($this->input->post('canonical')),
					'description' => $this->input->post('description'),
					'extend_description' =>json_encode( $this->input->post('content')),
					'catalogueid' => $this->input->post('catalogueid'),
					'catalogue' => json_encode($this->input->post('catalogue')),
					'tag' => json_encode($this->input->post('tag')),
					'meta_title' => $this->input->post('meta_title'),
					'meta_description' => $this->input->post('meta_description'),
					'publish' => $this->input->post('publish'),
					'image' => is($album[0]),
					'image_json' => is(base64_encode(json_encode($album))),
					'userid_created' => $this->auth['id'],
					'updated' => gmdate('Y-m-d H:i:s', time() + 7*3600),
					'price' => $this->input->post('price'),
					'price_sale' => (int)str_replace('.','',$this->input->post('price_sale')),
					'price_contact' => is($this->input->post('price_contact')),
					'code' => $this->input->post('code'),
					'barcode' => $this->input->post('barcode'),
					'inventory' => $this->input->post('inventory'),
					'quantity_dau_ki' => $this->input->post('quantity_dau_ki'),
					'quantity_cuoi_ki' => $this->input->post('quantity_cuoi_ki'),
					'unlimited_sale' => is($this->input->post('unlimited_sale')),
					'version' => $data['version'],
					'version_json' => base64_encode(json_encode(array($data['checkbox'],$data['attribute_catalogue'],$data['attribute']))),
					'wholesale' => $data['wholesale'],
					'wholesale_json' => base64_encode(json_encode(array($data['quantity_start'],$data['quantity_end'],$data['price_wholesale']))),
					'publish_time' => merge_time($this->input->post('post_date'), $this->input->post('post_time')),
					'brandid' => $data['brandid'],
					'made_in' => $data['made_in'],
					'model' => $data['model'],
					'image_color_json' => $data['image_color_json'],
					'video' => htmlspecialchars_decode(html_entity_decode($data['video'])),
					'icon_hot' => $data['icon_hot'],
					'prd_rela' => json_encode($this->input->post('prd_rela')),
					// 'file_price' => $this->input->post('file_price'),
					'landing_link' => $this->input->post('landing_link'),
				);

				// print_r($_update); exit;
				

				$flag = $this->Autoload_Model->_update(array(
					'where' => array('id' => $id),
					'table' => 'product',
					'data' => $_update,
				));
				if($flag > 0){
					// tạo đường dẫn cho sản phẩm
					$this->Autoload_Model->_delete(array(
						'where' => array('canonical' => $product['canonical'],'uri' => 'product/frontend/product/view','param' => $id),
						'table' => 'router',
					));
					
					// thêm danh mục cha vào bảng catalogue_relationship để sau này search
					$this->Autoload_Model->_delete(array(
						'where' => array('module' => 'product','moduleid' => $id),
						'table' => 'catalogue_relationship',
					));
					
					// thêm tag vào bảng tag_relationship để dễ dàng search
					$this->Autoload_Model->_delete(array(
						'where' => array('module' => 'product','moduleid' => $id),
						'table' => 'tag_relationship',
					));

					// thêm phiên bản sản phẩm
					$this->Autoload_Model->_delete(array(
						'where' => array('productid' => $id),
						'table' => 'product_version',
					));

					//thêm thuộc tính
					$this->Autoload_Model->_delete(array(
						'where' => array('moduleid' => $id, 'module' =>'product'),
						'table' => 'attribute_relationship',
					));

					// thêm bán buôn
					$this->Autoload_Model->_delete(array(
						'where' => array('productid' => $id),
						'table' => 'product_wholesale',
					));

					$canonical = slug($this->input->post('canonical'));
					createData($data, $id, $canonical, $this->auth['id']);
					$this->session->set_flashdata('message-success', 'Cập nhật sản phẩm mới thành công');
					redirect('product/backend/product/view?page='.$page);
				}
			}
		}
		$data['script'] = 'product';
		$data['template'] = 'product/backend/product/update';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	public function duplicate($id = 0){
		$data = comment(array('id' => $id, 'module' => $this->module));
		$page = ($this->input->get('page')) ? $this->input->get('page') : 1;
		$this->commonbie->permission("product/backend/product/update", $this->auth['permission']);
		$attribute_catalogue = $this->Autoload_Model->_get_where(array(
			'table' => 'attribute_catalogue',
			'count' => 'true',
		),true);
		$data['countAttribute_catalogue'] = $attribute_catalogue;
		$id = (int)$id;
		$product = $this->Autoload_Model->_get_where(array(
			'select' => 'id, code, title, slug, canonical, description, meta_title, meta_description, tag, catalogueid, catalogue, brandid, image, image_json, price, price_sale, price_contact,  barcode, inventory, quantity_dau_ki, quantity_cuoi_ki, wholesale, wholesale_json, version_json, publish, publish_time, unlimited_sale, image_color_json, made_in, model, extend_description, video, icon_hot, prd_rela, landing_link',
			'table' => 'product',
			'where' => array('id' => $id),
		));
		if(!isset($product) || is_array($product) == false || count($product) == 0){
			$this->session->set_flashdata('message-danger', 'sản phẩm không tồn tại');
			redirect('product/backend/product/view');
		}
		$product['title'] = str_duplicate(array('value' => $product['title']));
		$product['canonical'] = str_duplicate(array('value' => $product['canonical'], 'field' => 'canonical'));
		$data['product'] = $product;

		
		//Kiểm tra xem sản phẩm có nằm trong chương trình khuyến mại nào không
		$current = gmdate('Y-m-d H:i:s', time() + 7*3600);
		$query = ' AND tb2.publish =  1 AND tb2.start_date  <=  "'.$current. '" AND ( tb2.end_date >= "'.$current.'" OR tb2.end_date = "0000-00-00 00:00:00" ) ';
		$promotional = $this->Autoload_Model->_get_where(array(
			'select' => 'tb2.id, tb2.catalogue, tb2.title, tb2.canonical, tb2.created, tb2.image_json, tb2.module, tb2.start_date, tb2.end_date, tb2.publish, tb2.discount_type, tb2.discount_value, tb2.condition_value, tb2.condition_type, tb2.freeship, tb2.freeshipAll, tb2.condition_value_1, tb2.condition_type_1, tb2.use_common, tb2.code, tb2.limmit_code, tb2.cityid, tb2.discount_moduleid',
				'table' => 'promotional',
			'table' => 'promotional_relationship as tb1',
			'join' => array(
				array('promotional as tb2', 'tb1.promotionalid = tb2.id', 'inner'),
			),
			'query' => 'tb1.module = "product" AND tb1.moduleid = '.$id.$query,
		),true);
		if(check_array($promotional)){
			foreach ($promotional as $key => $value) {
				$promotional1 = json_decode(getPromotional($value), true);
				$data['promotional'][$key] = $value;
				$data['promotional'][$key]['use_common'] = $promotional1['use_common'];
				$data['promotional'][$key]['detail'] = $promotional1['detail'];
			}
		}
		
		$version_json = json_decode(base64_decode($product['version_json']), true);
		$data['price_contact'] =$product['price_contact'];
		$data['inventory'] =$product['inventory'];

		$data['attribute'] = $version_json[2];
		if(isset($data['attribute']) && is_array($data['attribute']) && count($data['attribute'])){
			foreach ($data['attribute'] as $key => $value) {
				if($value == ''){
					$data['attribute_json'][$key]['json']='';
				}else{
					$data['attribute_json'][$key]['json']=base64_encode(json_encode($value));
				}
			}
		}

		$data['checkbox']= $version_json[0];
		$data['attribute_catalogue']= $version_json[1];
		if(isset($version_json[1]) && is_array($version_json[1]) && count($version_json[1])){
			foreach ($version_json[1] as $key => $value) {
				if($value == 2){
					if(isset($version_json[2][$key]) && is_array($version_json[2][$key]) && count($version_json[2][$key])){
						$color= $this->Autoload_Model->_get_where(array(
							'select' => 'id, title, color',
							'table' => 'attribute',
							'where_in' => $version_json[2][$key],
							'where_in_field' => 'id',
						),true);
						foreach ($color as $key => $value) {
							$data['color'][$value['id']]['title'] = $value['title'];
							$data['color'][$value['id']]['color'] = $value['color'];
						}
					}
				}
			}
		}


		$data['image_color'] = json_decode(base64_decode($product['image_color_json']), true);
		$data['image_json'] = json_decode(base64_decode($product['image_json']), true);
		$product_version = $this->Autoload_Model->_get_where(array(
			'select' => 'code_version, title, image, price_version,  barcode_version, attribute1, attribute2',
			'table' => 'product_version',
			'where' => array('productid' => $id),
			'order_by'=>'id ASC'
		),true);

		foreach ($product_version as $key => $val) {
			$data['image_version'][]=$val['image'];
			$data['title_version'][]=$val['title'];
			$data['price_version'][]=$val['price_version'];
			$data['code_version'][]=$val['code_version'];
			$data['barcode_version'][]=$val['barcode_version'];
			$data['attribute1'][] = $val['attribute1'];
			$data['attribute2'][] = $val['attribute2'];
		}
		if(isset($data['title_version']) && is_array($data['title_version']) && count($data['title_version'])){
			$data['version']= count($data['title_version']);
		}else{
			$data['version'] = 0;
		}

		$data['wholesale'] =0;
		if(isset($data['price_wholesale']) && is_array($data['price_wholesale']) && count($data['price_wholesale'])){
			$data['wholesale'] =1;
		}

		$product_wholesale = $this->Autoload_Model->_get_where(array(
			'select' => 'quantity_start, quantity_end, price_wholesale',
			'table' => 'product_wholesale',
			'where' => array('productid' => $id),
			'order_by'=>'id ASC'
		),true);
		foreach ($product_wholesale as $key => $val) {
			$data['quantity_start'][]=$val['quantity_start'];
			$data['quantity_end'][]=$val['quantity_end'];
			$data['price_wholesale'][]=$val['price_wholesale'];
		}
		
		$data['brandid'] = $product['brandid'];
		if($this->input->post('create')){

			$album = $this->input->post('album');
			$data = getDataPost($data);

			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('','/');
			$this->form_validation->set_rules('title', 'Tiêu đề sản phẩm', 'trim|required');
			// $this->form_validation->set_rules('content[title][]', 'Tiêu đề thành phần mở rộng', 'trim|required');
			$this->form_validation->set_rules('catalogueid', 'Danh mục chính', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('canonical', 'Đường dẫn bài viết', 'trim|required|callback__CheckCanonical');
			$this->form_validation->set_rules('attribute[]', '', 'callback__CheckAttribute');
			$this->form_validation->set_rules('attribute_catalogue[]', '', 'callback__CheckAttribute_catalogue');
			$this->form_validation->set_rules('quantity_start[]', '', 'callback__CheckQuantity_start');
			$this->form_validation->set_rules('quantity_end[]', '', 'callback__CheckQuantity_end');
			
			if($this->form_validation->run($this)){
				$_insert = array(
					'title' => htmlspecialchars_decode(html_entity_decode($this->input->post('title'))),
					'slug' => slug(htmlspecialchars_decode(html_entity_decode($this->input->post('title')))),
					'canonical' => slug($this->input->post('canonical')),
					'description' => $this->input->post('description'),
					'extend_description' =>json_encode( $this->input->post('content')),
					'catalogueid' => $this->input->post('catalogueid'),
					'catalogue' => json_encode($this->input->post('catalogue')),
					'tag' => json_encode($this->input->post('tag')),
					'meta_title' => $this->input->post('meta_title'),
					'meta_description' => $this->input->post('meta_description'),
					'publish' => $this->input->post('publish'),
					'image' => is($album[0]),
					'image_json' => is(base64_encode(json_encode($album))),
					'userid_created' => $this->auth['id'],
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
					'price' => (int)str_replace('.','',$this->input->post('price')),
					'price_sale' => (int)str_replace('.','',$this->input->post('price_sale')),
					'price_contact' => is($this->input->post('price_contact')),
					'code' => $this->input->post('code'),
					'barcode' => $this->input->post('barcode'),
					'inventory' => $this->input->post('inventory'),
					'quantity_dau_ki' => $this->input->post('quantity_dau_ki'),
					'quantity_cuoi_ki' => $this->input->post('quantity_cuoi_ki'),
					'unlimited_sale' => is($this->input->post('unlimited_sale')),
					'wholesale' => $data['wholesale'],
					'wholesale_json' => base64_encode(json_encode(array($data['quantity_start'],$data['quantity_end'],$data['price_wholesale']))),
					'brandid' => $data['brandid'],
					'made_in' => $data['made_in'],
					'model' => $data['model'],
					'relateid' => is($data['create_product']),
					'version' => $data['version'],
					'publish_time' => merge_time($this->input->post('post_date'), $this->input->post('post_time')),
					'version_json' => base64_encode(json_encode(array($data['checkbox'],$data['attribute_catalogue'],$data['attribute']))),
					'image_color_json' => $data['image_color_json'],
					'video' => htmlspecialchars_decode(html_entity_decode($data['video'])),
					'icon_hot' => $data['icon_hot'],
					'prd_rela' => json_encode($this->input->post('prd_rela')),
					'landing_link' => $this->input->post('landing_link'),
				);
				$resultid_main = $this->Autoload_Model->_create(array(
					'table' => 'product',
					'data' => $_insert,
				));

				if($resultid_main > 0){
					$canonical = slug($this->input->post('canonical'));
					createData($data, $resultid_main, $canonical);
					$this->session->set_flashdata('message-success', 'Thêm sản phẩm mới thành công');
					redirect('product/backend/product/view?page='.$page);
				}
			}
		}
		$data['script'] = 'product';
		$data['template'] = 'product/backend/product/duplicate';
		$this->load->view('dashboard/backend/layout/dashboard', isset($data)?$data:NULL);
	}
	
	public function _CheckCanonical($canonical = ''){
		$originalCanonical = $this->input->post('original_canonical');
		if($canonical != $originalCanonical){
			$crc32 = sprintf("%u", crc32(slug($canonical)));
			$router = $this->Autoload_Model->_get_where(array(
				'select' => 'id',
				'table' => 'router',
				'where' => array('crc32' => $crc32),
				'count' => TRUE
			));
			if($router > 0){
				$this->form_validation->set_message('_CheckCanonical','Đường dẫn đã tồn tại, hãy chọn một đường dẫn khác');
				return false;
			}
		}
		return true;
	}
	public function _CheckAttribute($attribute = ''){
		$attribute = $this->input->post('attribute');
		$attribute_catalogue = $this->input->post('attribute_catalogue');
		if(isset($attribute) && is_array($attribute) && count($attribute)){ 
			foreach ($attribute as $key => $value) {
				if($value==''|| $value =null){
					$this->form_validation->set_message('_CheckAttribute','Bạn phải chọn thuộc tính');
					return false;
				}
			}
			return true;
		}
		if(isset($attribute_catalogue) && is_array($attribute_catalogue) && count($attribute_catalogue) && $attribute_catalogue != array(0)){
			$this->form_validation->set_message('_CheckAttribute','Bạn phải chọn thuộc tính');
			return false;
		}
	}
	public function _CheckAttribute_catalogue($attribute_catalogue = ''){
		if(isset($attribute_catalogue) && is_array($attribute_catalogue) && count($attribute_catalogue) ){ 
			foreach ($attribute_catalogue as $key => $value) {
				if($value==''|| $value ==null|| $value ==0){
					$this->form_validation->set_message('_CheckAttribute_catalogue','Bạn phải chọn nhóm thuộc tính');
					return false;
				}
			}
			return true;
		}
		return true;
	}
	public function _CheckQuantity_start(){
		$quantity_start = $this->input->post('quantity_start');
		if(isset($quantity_start) && is_array($quantity_start) && count($quantity_start)){ 
			foreach ($quantity_start as $key => $value) {
				if($value==''|| $value =null){
					$this->form_validation->set_message('_CheckQuantity_start','Bạn phải nhập số lượng bán buôn');
					return false;
				}
			}
			return true;
		}
		return true;
	}
	public function _CheckQuantity_end(){
		$quantity_end = $this->input->post('quantity_end');
		if(isset($quantity_end) && is_array($quantity_end) && count($quantity_end)){ 
			foreach ($quantity_end as $key => $value) {
				if($value==''|| $value =null){
					$this->form_validation->set_message('_CheckQuantity_end','Bạn phải nhập số lượng bán buôn');
					return false;
				}
			}
			return true;
		}
		return true;
	}
}
