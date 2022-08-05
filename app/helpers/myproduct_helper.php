<?php 
// thêm dữ liệu vào các bảng
if(!function_exists('createData')){
	function createData( $data = '', $resultid= '', $canonical='' , $auth = ''){
		$CI = & get_instance();
		// tạo đường dẫn cho sản phẩm
		
		createCanonical(array(
			'module' => 'product',
			'canonical' => $canonical,
			'resultid' => $resultid,
		));

		// thêm danh mục cha vào bảng catalogue_relationship để sau này search
		createCatalogue_relationship(array(
			'module' => 'product',
			'catalogue' => $CI->input->post('catalogue'),
			'catalogueid' => $CI->input->post('catalogueid'),
			'resultid' => $resultid,
		));
		
		// thêm tag vào bảng tag_relationship để dễ dàng search
		$tag = $CI->input->post('tag');
		createModule_relationship(array(
			'data' => $tag,
			'moduleid' => $resultid,
			'field' => 'tagid',
			'table' => 'tag_relationship',
			'module' => 'product'
		));
		// thêm nhóm thuộc tính vào nhóm sản phẩm
		updateAttridInProduct_catalogue(array(
			'catalogueid' => $CI->input->post('catalogueid'),
			'attribute_catalogue' => $data['attribute_catalogue'],
			'attribute' => $data['attribute'],
		));

		// thêm phiên bản sản phẩm
		createProduct_version(array(
			'title_version' => $data['title_version'],
			'price_version' => $data['price_version'],
			'code_version' => $data['code_version'],
			'barcode_version' => $data['barcode_version'],
			'attribute1' => $data['attribute1'],
			'attribute2' => $data['attribute2'],
			'image' => $data['image_version'],
			'productid' => $resultid,
		), $auth );

		//thêm thuộc tính
		createModule_relationship(array(
			'data' => $data['attribute'],
			'moduleid' => $resultid,
			'field' => 'attrid',
			'table' => 'attribute_relationship',
			'module' => 'product',
		));

		// thêm bán buôn
		if($data['wholesale']==1){
			createProduct_wholesale(array(
				'quantity_start' => $data['quantity_start'],
				'quantity_end' => $data['quantity_end'],
				'price_wholesale' => $data['price_wholesale'],
				'productid' => $resultid,
			));
		}
		return true;
	}
}
// xử lí dữ liệu post gửi lên
if(!function_exists('getDataPost')){
	function getDataPost($data = ''){
		$CI = & get_instance();
		// xử lí hình ảnh cho màu sắc
		$image_color = $CI->input->post('image_color');
		$data['image_color'] = $image_color;
		if(isset($image_color) && is_array($image_color) && count($image_color)){ 
			foreach ($image_color as $key => $value) {
				if($value == 'template/not-found.png' || $value == $key){
					unset($image_color[$key]);
				}else{
					$color= $CI->Autoload_Model->_get_where(array(
						'select' => 'id, title, color',
						'table' => 'attribute',
						'where' => array('id'=>$key),
					));
					$data['color'][$color['id']]['title'] = $color['title'];
					$data['color'][$color['id']]['color'] = $color['color'];
				}
			}
		}
		if($image_color == array('')){
			$image_color = '';
		}else{
			$image_color =  base64_encode(json_encode($image_color));
		}
		$data['image_color_json'] = $image_color;


		$data['price_contact'] = $CI->input->post('price_contact');
		$data['unlimited_sale'] = $CI->input->post('unlimited_sale');
		$data['attribute'] = $CI->input->post('attribute');
		if(isset($data['attribute']) && is_array($data['attribute']) && count($data['attribute'])){
			foreach ($data['attribute'] as $key => $value) {
				if($value == ''){
					$data['attribute_json'][$key]['json']='';
				}else{
					$data['attribute_json'][$key]['json']=base64_encode(json_encode($value));
				}
			}
		}

		$data['create_product'] = $CI->input->post('create_product');
		$data['checkbox'] = $CI->input->post('checkbox_val');
		$data['attribute_catalogue'] = ($CI->input->post('attribute_catalogue') == array(0=> 0)? '' :$CI->input->post('attribute_catalogue'));
		$data['image_version'] = $CI->input->post('image_version[]');
		$data['title_version'] = $CI->input->post('title_version[]');
		$data['price_version'] = $CI->input->post('price_version[]');
		$data['code_version'] = $CI->input->post('code_version[]');
		$data['barcode_version'] = $CI->input->post('barcode_version[]');
		if(isset($data['title_version']) && is_array($data['title_version']) && count($data['title_version'])){
			$data['version']= count($data['title_version']);
		}else{
			$data['version'] = 0;
		}

		$data['attribute1'] = $CI->input->post('attribute1');
		$data['attribute2'] = $CI->input->post('attribute2');
		
		$data['inventory'] = $CI->input->post('inventory');
		$data['quantity_start'] = $CI->input->post('quantity_start');
		$data['quantity_end'] = $CI->input->post('quantity_end');
		$data['price_wholesale'] = $CI->input->post('price_wholesale');
		$data['wholesale'] =0;
		if(isset($data['price_wholesale']) && is_array($data['price_wholesale']) && count($data['price_wholesale'])){
			$data['wholesale'] =1;
		}
		$data['brandid'] = $CI->input->post('brandid');
		$data['made_in'] = $CI->input->post('made_in');
		$data['model'] = $CI->input->post('model');
		$data['video'] = htmlspecialchars_decode(html_entity_decode($CI->input->post('video')));
		$data['icon_hot'] = $CI->input->post('icon_hot');
		$data['prd_rela'] = $CI->input->post('prd_rela');
		return $data;
	}
}
// thêm nhóm thuộc tính vào nhóm sản phẩm
if(!function_exists('updateAttridInProduct_catalogue')){
	function updateAttridInProduct_catalogue( $param = '' ){
		$CI = & get_instance();
		if(check_array($param['attribute_catalogue']) && $param['attribute_catalogue'] != Array(0 => 0) ){
			$product_catalogue = $CI->Autoload_Model->_get_where(array(
				'select' => 'attrid',
				'table' => 'product_catalogue',
				'where' => array('id' => $param['catalogueid']),
			));

			$attrid_old = is(json_decode($product_catalogue['attrid'],true));

			foreach($param['attribute_catalogue'] as $key => $cata){
				foreach($param['attribute'] as $sub => $attr){
					if($key == $sub){
						$attrid_new[$cata] = $attr;
					}
				}
			}

			if(check_array($attrid_old)){
				foreach($attrid_old as $cata_old => $attr_old){
					if(check_array($attr_old)){
						if(isset($attrid_new) && check_array($attrid_new)){
							foreach($attrid_new as $cata_new => $attr_new){
								if($cata_old == $cata_new){
									$attrid[$cata_old] = array_unique(array_merge($attr_new, $attr_old));
								}
								if($cata_old != $cata_new){
									$attrid[$cata_new] = (isset($attrid[$cata_new])) ? array_unique(array_merge($attr_new, $attrid[$cata_new])) : $attr_new;
									$attrid[$cata_old] = (isset($attrid[$cata_old])) ? array_unique(array_merge($attr_old, $attrid[$cata_old])) : $attr_old;
									 ;
								}
							}
						}
					}else{
						foreach($param['attribute_catalogue'] as $key => $val){
							foreach($param['attribute'] as $sub => $subs){
								if($sub == $key){
									$attrid[$val] = $subs;
								}
							}
						}	
					}
				}
			}else{
				foreach($param['attribute_catalogue'] as $key => $val){
					foreach($param['attribute'] as $sub => $subs){
						if($sub == $key){
							$attrid[$val] = $subs;
						}
					}
				}
			}
			if(isset($attrid) && check_array($attrid)){
				$_update_attrid = array(
					'attrid' => json_encode($attrid),
				);

				$CI->Autoload_Model->_update(array(
					'where' => array('id' => $param['catalogueid']),
					'table' => 'product_catalogue',
					'data' => $_update_attrid,
				));
			}
		}
	}
}
// thêm phiên bản sản phẩm
if(!function_exists('createProduct_version')){
	function createProduct_version( $param = '', $auth = '' ){
		$CI = & get_instance();
		if(isset($param['title_version']) && is_array($param['title_version']) && count($param['title_version'])){
			foreach($param['title_version'] as $key => $val){
				$_insert_version[] = array(
					'productid' => $param['productid'],
					'title' =>$val ,
					'price_version' =>(int)str_replace('.','',$param['price_version'][$key]) ,
					'code_version' =>$param['code_version'][$key] ,
					'barcode_version' =>$param['barcode_version'][$key] ,
					'attribute1' =>is($param['attribute1'][$key]) ,
					'attribute2' =>is($param['attribute2'][$key]) ,
					'image' => is($param['image'][$key]),
					'userid_created' => $auth,
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);
			}
			if(check_array($_insert_version)){
				$CI->Autoload_Model->_create_batch(array(
					'table' => 'product_version',
					'data' => $_insert_version,
				));
			}
		}
	}
}

// thêm bán buôn
if(!function_exists('createProduct_wholesale')){
	function createProduct_wholesale( $param = '' ){
		$CI = & get_instance();
		foreach($param['quantity_start'] as $key => $val){
			$_insert_wholesale[] = array(
				'productid' => $param['productid'],
				'price_wholesale' =>(int)str_replace('.','',$param['price_wholesale'][$key]) ,
				'quantity_start' =>is($param['quantity_start'][$key]) ,
				'quantity_end' =>is($param['quantity_end'][$key]) ,
			);
		}
		if(check_array($_insert_wholesale)){
			$CI->Autoload_Model->_create_batch(array(
				'table' => 'product_wholesale',
				'data' => $_insert_wholesale,
			));
		}
	}
}
