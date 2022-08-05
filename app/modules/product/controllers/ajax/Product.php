<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends MY_Controller {

	public function __construct(){
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
	}
	public function update_attr(){
		$param = $this->input->post('param');
		$list_id = $param['id_checked'];
		// pre($param);
		if(isset($param['attr']) && check_array($param['attr'])){

			// lấy ra thông tin version_json
			$detailProduct = $this->Autoload_Model->_get_where(array(
	            'select' => 'id,version_json',
	            'where_in' => $list_id,
	            'table' => 'product',
	        ), true);
	        // cập nhật lại version_json trong table product
	        if(isset($detailProduct) && check_array($detailProduct)){
	        	foreach ($detailProduct as $keyPrd => $valPrd) {
		        	$version_array = json_decode(base64_decode($valPrd['version_json']), true);
		        	$attrCata = $param['attrCata'];
		        	$attr = $param['attr'];

		        	// kiểm tra, nếu SP nào có phiên bản trùng với nhóm thuộc tính được thêm thì bỏ qua sp đó
		        	$continue = false;
		        	if(isset($version_array) && check_array($version_array)){
		        		foreach ($version_array[1] as $key => $value) {
		        			if($attrCata == $value){
		        				if($version_array[0][$key] == 1 ){
		        					$continue = true;
		        					foreach ($list_id as $keyId => $valId) {
		        						if($valId == $valPrd['id']){
		        							unset($list_id[$keyId]);
		        						}
		        					}
		        				}
		        			}
		        		}
		        	}
		        	if($continue == false){
		        		if(isset($version_array) && check_array($version_array) &&check_array(  $version_array[1]) && $version_array != array(0 => array( 0 => 0),1 => '' , 2 => '' )){
				        	if(!in_array($attrCata, $version_array[1])){
				        		foreach ($version_array as $keyVer => $valVer) {
				        			if($keyVer == 0){
				        				$valVer[] = 0;
						        		$version_array[0] = $valVer;
				        			}
				        			if($keyVer == 1){
				        				$valVer[] = $attrCata;
						        		$version_array[1] = $valVer;
				        			}
				        			if($keyVer == 2){
				        				$valVer[] = $attr;
						        		$version_array[2] = $valVer;
				        			}
					        	}
				        	}else{
				        		foreach ($version_array as $keyVer => $valVer) {
				        			if($keyVer == 1){
				        				foreach ($valVer as $key => $value) {
				        					if($value == $attrCata){
					        					$version_array[2][$key] = array_unique(array_merge($version_array[2][$key],$attr));
					        				}
				        				}
				        				
				        			}
				        			if($keyVer == 2){
				        				$valVer[] = $attr;
						        		
				        			}
					        	}

				        	}
				        	
				        	
				        }else{
			        		$version_array[0] = array(0 => 0);
			        		$version_array[1] = array(0 => $attrCata);
			        		$version_array[2] = array(0 => $attr);
				        }
				        $_update[] = array(
							'id' => $valPrd['id'],
							'version_json' => base64_encode(json_encode($version_array)),
							'updated' => gmdate('Y-m-d H:i:s', time() + 7*3600),
							'userid_updated' =>  $this->auth['id'],
						);
		        	}
		        }
		        if(isset($_update)){
			        $this->Autoload_Model->_update_batch(array(
						'table' => 'product',
						'update' => $_update,
						'field' =>'id',
					));
				}
	        }
	        // cập nhật lại bảng attribute_relationship
	        // lấy ra tất cả bản ghi của SP trong attribute_relationship
	        $attribute_relationship = $this->Autoload_Model->_get_where(array(
	            'select' => 'id, attrid, moduleid',
	            'where' => array('module' => 'product'),
	            'where_in' => $list_id,
	            'where_in_field' => 'moduleid',
	            'table' => 'attribute_relationship',
	        ), true);

	        foreach ($param['attr'] as $keyAttr => $valAttr) {
	        	foreach ($param['id_checked'] as $keyId => $valId ) {
	        		$temp[] = array('moduleid' => $valId , 'attrid' => $valAttr) ;
	        	}
	        }

        	if(isset($attribute_relationship) && check_array($attribute_relationship)){
		        foreach ($temp as $keyTemp => $valTemp) {
	        		foreach ($attribute_relationship as $key => $value) {
	        			if( $valTemp['attrid'] == $value['attrid'] && $valTemp['moduleid'] == $value['moduleid']){
	        				unset($temp[$keyTemp]);
	        			}
	        		}
        		}
	        }
	        foreach($temp as $keyTemp => $valTemp){
				$_insert[] = array(
					'moduleid' => $valTemp['moduleid'],
					'attrid' => $valTemp['attrid'],
					'module' => 'product',
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);
			}
			if(isset($_insert)){
				$this->Autoload_Model->_create_batch(array(
					'table' => 'attribute_relationship',
					'data' => $_insert,
				));
			}
			
			echo 'true'; die;
	    }
	    echo 'false'; die;
	}
	
	public function update_catalogue(){
		$id_checked = $this->input->post('id_checked');

		$catalogue = $this->input->post('catalogue');
		$detailProduct = $this->Autoload_Model->_get_where(array(
            'select' => 'id,catalogue',
            'where_in' => $id_checked,
            'where_in_field' =>'id',
            'table' => 'product',
        ), true);
        if(isset($detailProduct) && check_array($detailProduct)){
	        foreach ($detailProduct as $key => $prd) {
	        	$array_catalogue = json_decode($prd['catalogue']);
	        	if($array_catalogue ==''){
	        		$array_catalogue = array();
	        	}
	        	$list_catalogue = array_push($array_catalogue , $catalogue) ;
	        	$_update[] = array(
					'id' => $prd['id'],
					'catalogue' => json_encode($array_catalogue),
					'updated' => gmdate('Y-m-d H:i:s', time() + 7*3600),
					'userid_updated' =>  $this->auth['id'],
				);
	        }
	        $this->Autoload_Model->_update_batch(array(
				'table' => 'product',
				'update' => $_update,
				'field' =>'id',
			));
			echo 'true'; die;
		}
		echo 'false'; die;
	}
	public function update_price_product(){
		$data = $this->input->post('data');
		$data = json_decode($data,true);
		if(isset($data) && is_array($data) && count($data)){
			foreach($data as $key => $val){
				if($val == 0){
					continue;
				}
				$_update[] = array(
					'id' => $key,
					$val[1] => $val[0],
					'updated' => gmdate('Y-m-d H:i:s', time() + 7*3600),
					'userid_updated' =>  $this->auth['id'],
				);
			}
			$this->Autoload_Model->_update_batch(array(
				'table' => 'product',
				'update' => $_update,
				'field' =>'id',
			));
			echo 1;die;
		}
		
	}
	public function get_attrid(){
		$catalogueid = $this->input->post('catalogueid');
		$detailCatalogue = $this->Autoload_Model->_get_where(array(
			'select' => 'id, attrid',
			'table' => 'product_catalogue',
			'where' => array('id' => $catalogueid),
		));
		$attribute_catalogue = getListAttr($detailCatalogue['attrid']);
		$html = '';
		if(check_array($attribute_catalogue)){
			foreach($attribute_catalogue as $key => $val){
				$html = $html.'<li class="catalogue m-b-xs" data-keyword = '.$val['keyword_cata'].'>';
					$html = $html.'<div class="m-l-sm m-b-xs" style="color:#2c3e50"><b>'.$key.'</b></div>';
					$html = $html.'<div class="row no-margins" >';
						if(check_array($val)){
						foreach($val as $sub => $subs){
							if($sub != 'keyword_cata'){
								$html = $html.'<div class="col-sm-3">';
									$html = $html.'<div class="uk-flex uk-flex-middle m-b-xs attr">';
										$html = $html.'<input  class="checkbox-item filter" type="checkbox" name="attr[]" value="'.$sub.'">';
										$html = $html.'<label for="" class="label-checkboxitem m-r"></label>';
										$html = $html.$subs;
									$html = $html.'</div>';
								$html = $html.'</div>';
						}}}
					$html = $html.'</div>';
				$html = $html.'</li>';
			}
		}
		echo json_encode(array(
			'attribute_catalogue' => $html,
		));die();
    }
	// thêm mới thuộc tính
    public function create_attribute(){
    	if($this->input->post('data')){
			$temp=$this->input->post('data');
			foreach($temp as $key => $val){
				$data[$val['name']]=$val['value'];
			}
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters('', ' / ');
			$this->form_validation->set_rules('title','Tên thuộc tính','trim|required|max_length[100]');
			$this->form_validation->set_rules('catalogueid', 'Danh mục chính', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('title', 'Đường dẫn thuộc tính', 'trim|required|callback__CheckCanonical');
			if ($this->form_validation->run() == FALSE){
		        $errors = validation_errors();
		        echo $errors; die;
		    };
			if($this->form_validation->run($this)){
				$insert = array(
					'title' => htmlspecialchars_decode(html_entity_decode($data['title'])),
					'slug' => slug(htmlspecialchars_decode(html_entity_decode($data['title']))),
					'canonical' => slug($data['title']),
					'catalogueid' => $data['catalogueid'],
					'publish' => 0,
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
					'userid_created' => $this->auth['id'],
				);
				if(isset($insert) && is_array($insert) && count($insert)){
					$resultid = $this->Autoload_Model->_create(array('data' => $insert,'table' => 'attribute'));
					if($resultid > 0){
						if($resultid > 0){
							$canonical = slug($data['title']);
							if(!empty($canonical)){
								$router = array(
									'canonical' =>  slug($data['title']),
									'crc32' => sprintf("%u", crc32($canonical)),
									'uri' => 'attribute/frontend/attribute/view',
									'param' => $resultid,
									'type' => 'number',
									'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
								);
								$routerid = $this->Autoload_Model->_create(array(
									'table' => 'router',
									'data' => $router,
								));
							}
						}
						echo 'true';die;
					}
				}
			}
		}
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
	// xóa SP
	public function ajax_delete_product(){
		$param['id'] = (int)$this->input->post('id');
		$param['router'] = $this->input->post('router');
			
		if($param['router'] != '' && !empty($param['router'])){
			$router = $this->Autoload_Model->_delete(array(
				'where' => array('canonical' => $param['router']),
				'table' => 'router',
			));
		}
		//xóa phiên bản
		$this->Autoload_Model->_delete(array(
			'where' => array('productid' => $param['id']),
			'table' => 'product_version'
		));
		// xóa bán buôn bán lẻ
		$this->Autoload_Model->_delete(array(
			'where' => array('productid' => $param['id']),
			'table' => 'product_wholesale'
		));
		// xóa thuộc tính
		$this->Autoload_Model->_delete(array(
			'where' => array('moduleid' => $param['id'], 'module' => 'product'),
			'table' => 'attribute_relationship'
		));
		// xóa nhóm thuộc tính trong nhóm danh mục
		// $product = $this->Autoload_Model->_get_where(array(
		// 	'select' => 'version_json',
		// 	'table' => 'product',
		// 	'where' => array('id' => $param['id']),
		// ));
		// $version_json = json_decode(base64_decode($product['version_json']));
		// $product_catalogue = $this->Autoload_Model->_get_where(array(
		// 	'select' => 'attrid',
		// 	'table' => 'product_catalogue',
		// 	'where' => array('id' => $param['catalogueid']),
		// ));
		// $attrid=is(json_decode($product_catalogue['attrid'],true));
		// $attrid_old= $version_json[1];
		// foreach($attrid_old as $key => $val){
		// 	foreach ($attrid as $sub => $subs) {
		// 		if($val == $subs){
		// 			unset($attrid[$sub]);
		// 		}
		// 	}
		// }
		// $_update_attrid = array(
		// 	'attrid' => json_encode($attrid),
		// );
		// $this->Autoload_Model->_update(array(
		// 	'where' => array('id' => $param['catalogueid']),
		// 	'table' => 'product_catalogue',
		// 	'data' => $_update_attrid,
		// ));
		// xóa sản phẩm
		$flag = $this->Autoload_Model->_delete(array(
			'where' => array('id' => $param['id']),
			'table' => 'product'
		));
		echo $flag;die();
	}
	public function ajax_delete_product_all(){
		$param = $this->input->post('post');
		$id  = $param['id_checked'];
		$router  = $param['router'];
			
		$router = $this->Autoload_Model->_delete(array(
			'table' => 'router',
			'where_in' => $router,
			'where_in_field' => 'canonical',
		));
		//xóa phiên bản
		$this->Autoload_Model->_delete(array(
			'table' => 'product_version',
			'where_in' => $id,
			'where_in_field' => 'productid',
		));
		// xóa bán buôn bán lẻ
		$this->Autoload_Model->_delete(array(
			'table' => 'product_wholesale',
			'where_in' => $id,
			'where_in_field' => 'productid',
		));
		// xóa thuộc tính
		$this->Autoload_Model->_delete(array(
			'table' => 'attribute_relationship',
			'where_in' => $id,
			'where_in_field' => 'moduleid',
			'where' => array('module' => 'product'),
		));
		//
		$flag = $this->Autoload_Model->_delete(array(
			'table' => 'product',
			'where_in' => $id,
			'where_in_field' => 'id',
		));
		echo $flag;die();
	}
	
	
	public function ishome(){
		$id = $this->input->post('objectid');
		$object = $this->Autoload_Model->_get_where(array(
			'select' => 'id, ishome',
			'table' => 'product',
			'where' => array('id' => $id),
		));
		
		$_update['ishome'] = (($object['ishome'] == 1)?0:1);
		$this->Autoload_Model->_update(array(
			'where' => array('id' => $id),
			'table' => 'product',
			'data' => $_update,
		));
	}
	public function highlight(){
		$id = $this->input->post('objectid');
		$object = $this->Autoload_Model->_get_where(array(
			'select' => 'id, highlight',
			'table' => 'product',
			'where' => array('id' => $id),
		));
		
		$_update['highlight'] = (($object['highlight'] == 1)?0:1);
		$this->Autoload_Model->_update(array(
			'where' => array('id' => $id),
			'table' => 'product',
			'data' => $_update,
		));
	}
	
	public function status(){
		$id = $this->input->post('objectid');
		$object = $this->Autoload_Model->_get_where(array(
			'select' => 'id, publish',
			'table' => 'product',
			'where' => array('id' => $id),
		));
		
		$_update['publish'] = (($object['publish'] == 1)?0:1);
		$this->Autoload_Model->_update(array(
			'where' => array('id' => $id),
			'table' => 'product',
			'data' => $_update,
		));
	}
	
	public function listproduct(){
		$page = (int)$this->input->get('page');
		$json = [];
		$data['from'] = 0;
		$data['to'] = 0;
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 20;
		$keyword = $this->db->escape_like_str($this->input->get('keyword'));
		$keyword = '(title LIKE \'%'.$keyword.'%\' OR description LIKE \'%'.$keyword.'%\')';
		$param['catalogueid'] = $this->input->get('catalogueid');
		$param['catalogue'] = $this->input->get('catalogue');
		$param['brand'] = $this->input->get('brand');
		$param['publish'] = $this->input->get('publish');
		$param['tag'] = $this->input->get('tag');
		$param['start_price'] = $this->input->get('start_price');
		$param['end_price'] = $this->input->get('end_price');
		$param['attr'] = $this->input->get('attr');
		$query = '';
		$param['start_price'] = (int)str_replace('.','',$param['start_price']);
		$param['end_price'] = (int)str_replace('.','',$param['end_price']);
		if(isset($param['start_price']) && !empty($param['end_price'])){
			$query = $query.' AND tb1.price >= '.$param['start_price'].' AND tb1.price <= '.$param['end_price'].' ';
		}
		if(!in_array('product/backend/product/viewall', json_decode($this->auth['permission'], TRUE))){
			$query = $query.' AND tb1.userid_created = '.$this->auth['id'].' ';
		}

		if(!empty($param['catalogueid'])){
			$query = $query.' AND tb1.catalogueid =  '.$param['catalogueid'];
		}
		if(!empty($param['catalogue'])){
			$json[] =array('catalogue_relationship as tb4', 'tb1.id = tb4.moduleid AND tb4.module ="product"', 'inner');
			$query = $query.' AND ( ';
			foreach ($param['catalogue'] as $key => $value) {
				$query = $query.' tb4.catalogueid = '. $value.' OR ';
			}
			$query = substr( $query,  0, strlen($query) -3 );
			$query = $query.' )';
		}
		if(!empty($param['tag'])){
			$json[] =array('tag_relationship as tb2', 'tb1.id = tb2.moduleid AND tb2.module ="product"', 'inner');
			$query = $query.' AND ( ';
			foreach ($param['tag'] as $key => $value) {
				$query = $query.' tb2.tagid = '. $value.' OR ';
			}
			$query = substr( $query,  0, strlen($query) -3 );
			$query = $query.' )';
		}
		if(!empty($param['brand'])){
			$query = $query.' AND tb1.brandid =  '.$param['brand'];
		}
		if(!empty($param['publish']) && $param['publish'] !=-1){
			$query = $query.' AND tb1.publish =  '.$param['publish'];
		}
		// xử lí điều kiện lọc thuộc tính
		if(!empty($param['attr'])){
			$attr = explode(';',$param['attr']) ;
			foreach ($attr as $key => $val) {
				if ($key % 2 == 0){
					if($val != '' ){
						$attribute[$val][] = $attr[$key +1];
					}
				}else{
					continue;
				}
			}
			$total = 0;
			$index = 100;
			foreach ($attribute as $key => $val) {
				$attribute_catalogue = $this->Autoload_Model->_get_where(array(
					'select' =>'id',
					'table' =>'attribute_catalogue',
					'where'=> array('keyword'=> $key),
				));
				$query = $query.' AND ( ';
				$total++;
				$index ++;
				foreach ($val as $sub => $subs) {
					$index = $index + $total; 
					$query = $query.' tb'.$index.'.attrid =  '.$subs.' OR ';
					$json[] = array('attribute_relationship as tb'.$index, 'tb1.id = tb'.$index.'.moduleid AND tb'.$index.'.module ="product"', 'inner');
				}
				$query = substr( $query,  0, strlen($query) -3 );
				$query = $query.' ) ';
			}
			$query = $query.' GROUP BY `tb102`.`moduleid`';
		}
		$query = substr( $query,  4, strlen($query));
		$config['total_rows'] = $this->Autoload_Model->_get_where(array(
			'distinct' => 'true',
			'select' => 'tb1.title',
			'table' =>'product as tb1',
			'query' => $query,
			'join' => $json,
			'keyword' => $keyword,
			'count'=>true,
		));
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] ='#" data-page="';
			$config['suffix'] = $this->config->item('url_suffix').(!empty($_SERVER['QUERY_STRING'])?('?'.$_SERVER['QUERY_STRING']):'');
			$config['first_url'] = $config['base_url'].$config['suffix'];
			$config['per_page'] = $perpage;
			$config['cur_page'] = $page;
			$config['page'] = $page;
			$config['uri_segment'] = 2;
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
			$listproduct = $this->Autoload_Model->_get_where(array(
				'select' =>'tb1.image, tb1.version, tb1.title, tb1.price, tb1.price_sale, tb1.price_contact, tb1.id, tb1.canonical, tb1.viewed, tb1.order, (SELECT account FROM user WHERE tb1.userid_created = user.id) as user_created, tb1.created, tb1.ishome, tb1.highlight, tb1.publish, tb1.catalogueid, tb1.quantity_dau_ki, tb1.quantity_cuoi_ki, (SELECT title FROM product_catalogue WHERE product_catalogue.id = tb1.catalogueid) as catalogue_title,tb1.catalogue',
				'table' =>'product as tb1',
				'keyword' => $keyword,
				'join' => $json,
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'query' => $query,
				'order_by' => 'tb1.order desc, tb1.created asc',
			),true);
		}
		
		$html = '';
		 if(isset($listproduct) && is_array($listproduct) && count($listproduct)){ 
			foreach($listproduct as $key => $val){
				$image = getthumb($val['image']);
				$_catalogue_list = '';
				$catalogue = json_decode($val['catalogue'], TRUE);
				if(isset($catalogue) && is_array($catalogue) && count($catalogue)){
					$_catalogue_list = $this->Autoload_Model->_get_where(array(
						'select' => 'id, title, slug, canonical',
						'table' => 'product_catalogue',
						'where_in' => json_decode($val['catalogue'], TRUE),
						'where_in_field' => 'id',
					), TRUE);
				}
				$html = $html.'<tr class="gradeX" id="post-'.$val['id'].'">';
					$html = $html.'<td>';
						$html = $html.'<input type="checkbox" name="checkbox[]" value="'.$val['id'].'" data-router="'.$val['canonical'].'" class="checkbox-item">';
						$html = $html.'<label for="" class="label-checkboxitem"></label>';
					$html = $html.'</td>';
					$html = $html.'<td>';
						$html = $html.'<div class="uk-flex uk-flex-middle uk-flex-space-between">';
							$html = $html.'<div class="uk-flex uk-flex-middle ">';
								$html = $html.'<div class="image mr5">';
									$html = $html.'<span class="image-post img-cover"><img src="'.$image.'" alt="'.$val['title'].'" /></span>';
								$html = $html.'</div>';
								$html = $html.'<div class="main-info">';
									$html = $html.'<div class="title"><a class="maintitle" href="'.site_url('product/backend/product/update/'.$val['id']).'" title="">'.$val['title'].' ('.$val['viewed'].' lượt xem ) '.(($val['version']>0)?"(có ".$val['version']." phiên bản)":'').'</a></div>';
									$html = $html.'<div class="catalogue" style="font-size:10px">';
										$html = $html.'<span style="color:#f00000;">Nhóm hiển thị: </span>';
										$html = $html.'<a class="" style="color:#333;" href="'.site_url('product/backend/product/view?catalogueid='.$val['catalogueid']).'" title="">'.$val['catalogue_title'].'</a>'.((check_array($_catalogue_list)) ? ' ,' :'');
										if(check_array($_catalogue_list)){ 
											foreach($_catalogue_list as $keyCat => $valCat){
											$html = $html.'<a style="color:#333;" class="" href="'.site_url('product/backend/product/view?catalogueid='.$valCat['id']).'" title="">'.$valCat['title'].'</a> '.($keyCat + 1 < count($_catalogue_list)) ? ', ' : '';
											}
										}
									$html = $html.'</div>';
								$html = $html.'</div>';
							$html = $html.'</div>';
							$html = $html.'<div>';
								$html = $html.'<a target=_blank href="'.site_url($val['canonical']).'" ><i class="fa fa-link" aria-hidden="true"></i></a>';
							$html = $html.'</div>';
						$html = $html.'</div>';
					$html = $html.'</td>';
					// $html = $html.'<td class="text-right">'.$val['quantity_dau_ki'].'</td>';
					// $html = $html.'<td class="text-right">'.$val['quantity_cuoi_ki'].'</td>';
					$html = $html.'<td class="text-right price">';
						if($val['price_contact'] == 1 ){
							$html = $html.'<span>Giá liên hệ</span>';
						}else{
							$price = (!empty($val['price_sale']))? $val['price_sale'] : $val['price'];
							$field =  (!empty($val['price_sale']))? 'price_sale' : 'price';
							if(!empty($val['price_sale'])){
								$html = $html.'<i class="fa fa-tag m-r-xs" aria-hidden="true"></i>';
							}
							$html = $html.'<span>'.addCommas($price).'</span>';
						    $html = $html.form_input('price',addCommas($price) , 'data-id="'.$val['id'].'" data-field="'.$field.'"  class="int form-control" style="text-align:right; padding:6px 3px; display:none"');
						}
					$html = $html.'</td>';
					
					$html = $html.'<td>';
						$html = $html.form_input('order['.$val['id'].']', $val['order'], 'data-module="product" data-id="'.$val['id'].'"  class="form-control sort-order" placeholder="Vị trí" style="width:50px;text-align:right;"');
					$html = $html.'</td>';
					$html = $html.'<td class="text-center">'.$val['user_created'].'</td>';

					$html = $html.'<td class="tb_ishome">';
						$html = $html.'<div class="switch">';
							$html = $html.'<div class="onoffswitch">';
								$html = $html.'<input type="checkbox" '.(($val['ishome'] == 1) ? 'checked=""' : '').' class="onoffswitch-checkbox ishome" data-id="'.$val['id'].'" id="ishome-'.$val['id'].'">';
								$html = $html.'<label class="onoffswitch-label" for="ishome-'.$val['id'].'">';
									$html = $html.'<span class="onoffswitch-inner"></span>';
									$html = $html.'<span class="onoffswitch-switch"></span>';
								$html = $html.'</label>';
							$html = $html.'</div>';
						$html = $html.'</div>';
					$html = $html.'</td>';

					$html = $html.'<td class="tb_highlight">';
						$html = $html.'<div class="switch">';
							$html = $html.'<div class="onoffswitch">';
								$html = $html.'<input type="checkbox" '.(($val['highlight'] == 1) ? 'checked=""' : '').' class="onoffswitch-checkbox highlight" data-id="'.$val['id'].'" id="highlight-'.$val['id'].'">';
								$html = $html.'<label class="onoffswitch-label" for="highlight-'.$val['id'].'">';
									$html = $html.'<span class="onoffswitch-inner"></span>';
									$html = $html.'<span class="onoffswitch-switch"></span>';
								$html = $html.'</label>';
							$html = $html.'</div>';
						$html = $html.'</div>';
					$html = $html.'</td>';

					$html = $html.'<td>';
						$html = $html.'<div class="switch">';
							$html = $html.'<div class="onoffswitch">';
								$html = $html.'<input type="checkbox" '.(($val['publish'] == 0) ? 'checked=""' : '').' class="onoffswitch-checkbox publish" data-id="'.$val['id'].'" id="publish-'.$val['id'].'">';
								$html = $html.'<label class="onoffswitch-label" for="publish-'.$val['id'].'">';
									$html = $html.'<span class="onoffswitch-inner"></span>';
									$html = $html.'<span class="onoffswitch-switch"></span>';
								$html = $html.'</label>';
							$html = $html.'</div>';
						$html = $html.'</div>';
					$html = $html.'</td>';


					$html = $html.'<td class="text-center">';
						$html = $html.'<a type="button" href="'.(site_url('product/backend/product/update/'.$val['id'].'?page='.($page+1))).'" class="btn btn-primary m-r-xs"><i class="fa fa-edit"></i></a>';
						$html = $html.'<a type="button" class="btn btn-danger ajax_delete_product" data-title="Lưu ý: Dữ liệu sẽ không thể khôi phục. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!" data-router="'.$val['canonical'].'" data-id="'.$val['id'].'" data-catalogueid="'.$val['catalogueid'].'" data-module="product"><i class="fa fa-trash"></i></a>';
						$html .= '<a type="button" href="'.site_url('product/backend/product/duplicate/'.$val['id'].'?page=1').'" class="btn btn-info"><i class="fa fa-files-o" aria-hidden="true"></i></a>';
					$html = $html.'</td>';
				$html = $html.'</tr>';


			}
		}else{ 
			$html = $html.'<tr>
				<td colspan="100%"><small class="text-danger">Không có dữ liệu phù hợp</small></td>
			</tr>';
		}

		print_r($page); exit;
		echo json_encode(array(
			'pagination' => (isset($listPagination)) ? $listPagination : '',
			'html' => (isset($html)) ? $html : '',
			'total' => $config['total_rows'],
		));die();		
	}
}
