<?php 
// lấy thông tin khuyến mại gửi lên để ghi vào bảng promotional
if(!function_exists('setPromotional')){
	function setPromotional( $auth = '' ){
		$CI = & get_instance();
		$album=$CI->input->post('album');
		$_insert = array(
			'title' => htmlspecialchars_decode(html_entity_decode($CI->input->post('title'))),
			'slug' => slug(htmlspecialchars_decode(html_entity_decode($CI->input->post('title')))),
			'canonical' => slug($CI->input->post('canonical')),
			'meta_title' => $CI->input->post('meta_title'),
			'meta_description' => $CI->input->post('meta_description'),
			'description' => $CI->input->post('description'),
			'use_common' => is0($CI->input->post('use_common')),

			
			
			'catalogue' => $CI->input->post('catalogue'),
			'module' => $CI->input->post('module'),
			'image_json' => base64_encode(json_encode($album)),

			'discount_type' => $CI->input->post('discount_type'),
			'discount_value' => replace($CI->input->post('discount_value')),

			'condition_type' => $CI->input->post('condition_type'),

			'userid_created' => $auth,
			'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
			'publish' =>1,
		);

		$coupon = $CI->input->post('catalogue');
		if($coupon == 'CP'){
			$_insert['code'] = $CI->input->post('code');
			$not_limmit = $CI->input->post('not_limmit');
			if($not_limmit == -1){
				$_insert['limmit_code'] = -1;
			}else{
				$_insert['limmit_code'] = $CI->input->post('limmit_code');
			}
		}else{
			$_insert['code'] = '';
			$_insert['limmit_code'] = '';

		}
			$_insert['condition_type_1'] = $CI->input->post('condition_type_1');
			$_insert['condition_value_1'] = replace($CI->input->post('condition_value_1'));

		$discount_type = $CI->input->post('discount_type');
		if($discount_type == 'ship'){
			$_insert['freeship'] = is0($CI->input->post('freeship'));
			$_insert['freeshipAll'] = is0($CI->input->post('freeshipAll'));
			$_insert['cityid'] = json_encode($CI->input->post('city[]'));
			$_insert['districtid'] = json_encode($CI->input->post('district[]'));
		}else{
			$_insert['freeship'] = '';
			$_insert['freeshipAll'] = '';
			$_insert['cityid'] = '';
			$_insert['districtid'] = '';
		}
		if($discount_type == 'object'){
			$_insert['discount_moduleid'] = json_encode($CI->input->post('discount_moduleid'));
		}

		$condition_type = $CI->input->post('condition_type');
		if($condition_type != 'condition_cartAll' && $condition_type != 'condition_moduleidAll'){
			$_insert['condition_value'] = json_encode($CI->input->post('condition_value'));
		}else{
			$_insert['condition_value'] = '';
		}

		$choose_date = $CI->input->post('choose_date');
		if($choose_date != '1'){
			$_insert['start_date'] = convert_time($CI->input->post('start_date'), '-');
			$_insert['end_date'] = convert_time($CI->input->post('end_date'), '-');
		}else{
			$_insert['start_date'] = '0000-00-00 00:00:00';
			$_insert['end_date'] = '0000-00-00 00:00:00';
		}
		return $_insert;
	}
}	

// lưu thông tin vào bảng promotion_relationship
if(!function_exists('insertPromotionalRelationship')){
	function insertPromotionalRelationship($_insert, $id){
		switch ($_insert['discount_type'])
		{
			case 'ship':
				$cityList = json_decode($_insert['cityid']);
				$districtList = json_decode($_insert['districtid']);
				$localList = [];
				if(isset($cityList) && check_array($cityList) ){
					$localList = $cityList;
					$local = 'cityid';
				}
				if(isset($districtList) && check_array($districtList) ){
					$localList = $districtList;
					$local = 'districtid';
				}
				
				$_insert_relationship = getPromotionalRelationship($_insert, $id);
				$CI = & get_instance();
				$condition_type = $_insert['condition_type'];
				$condition_value = $_insert['condition_value'];

				if($condition_type == 'condition_moduleid'){
					$condition_value = json_decode($condition_value);
					if(isset($condition_value) && is_array($condition_value) && count($condition_value)){
						$_insert_relationship_batch = [];
						foreach ($condition_value as $key => $value) {
							if(isset($localList) && check_array($localList) ){
								foreach ($localList as $keyLocal => $valLocal) {
									$_insert_relationship[$local] = $valLocal;
									$_insert_relationship['moduleid'] =$value;
									$_insert_relationship_batch[] = $_insert_relationship ;
								}
							}
						}
						$CI->Autoload_Model->_create_batch(array(
							'table' => 'promoship_relationship',
							'data' => $_insert_relationship_batch,
						));
					}
				}

				if($condition_type == 'condition_module_catalogue'){
					$condition_value = json_decode($condition_value);
					if(isset($condition_value) && is_array($condition_value) && count($condition_value)){
						$_insert_relationship_batch = [];
						foreach ($condition_value as $key => $value) {
							$module = $CI->Autoload_Model->_get_where(array(
								'select' => 'id',
								'table' => $_insert['module'],
								'where' => array('catalogueid' => $value),
							),true);
							if(isset($module) && is_array($module) && count($module)){
								foreach ($module as $sub => $subs){ 
									if(isset($localList) && check_array($localList) ){
										foreach ($localList as $keyLocal => $valLocal) {
											$_insert_relationship[$local] = $valLocal;
											$_insert_relationship['moduleid'] =$subs['id'];
											$_insert_relationship['promotionalid'] =$id;
											$_insert_relationship_batch[] = $_insert_relationship ;
										}
									}
								}
							}
						}
						if(isset($_insert_relationship_batch) && is_array($_insert_relationship_batch) && count($_insert_relationship_batch)){
							$CI->Autoload_Model->_create_batch(array(
								'table' => 'promoship_relationship',
								'data' => $_insert_relationship_batch,
							));
						}
					}
				}

				if($condition_type == 'condition_moduleidAll' || $condition_type == 'condition_cartAll'){

					$module = $CI->Autoload_Model->_get_where(array(
						'select' => 'id',
						'table' => $_insert['module'],
					),true);
					if(isset($module) && is_array($module) && count($module)){
						foreach ($module as $sub => $subs) {
							if(isset($localList) && check_array($localList) ){
								foreach ($localList as $keyLocal => $valLocal) {
									$_insert_relationship[$local] = $valLocal;
									$_insert_relationship['moduleid'] =$subs['id'];
									$_insert_relationship['promotionalid'] =$id;
									$_insert_relationship_batch[] = $_insert_relationship ;
								}
							}
						}
					}
					if(isset($_insert_relationship_batch) && is_array($_insert_relationship_batch) && count($_insert_relationship_batch)){
						$CI->Autoload_Model->_create_batch(array(
							'table' => 'promoship_relationship',
							'data' => $_insert_relationship_batch,
						));
					}
				}
			default:
				$_insert_relationship = getPromotionalRelationship($_insert, $id);
				$CI = & get_instance();
				$condition_type = $_insert['condition_type'];
				$condition_value = $_insert['condition_value'];

				if($condition_type == 'condition_moduleid'){
					$condition_value = json_decode($condition_value);
					if(isset($condition_value) && is_array($condition_value) && count($condition_value)){
						$_insert_relationship_batch = [];
						foreach ($condition_value as $key => $value) {
							$_insert_relationship['moduleid'] =$value;
							$_insert_relationship_batch[] = $_insert_relationship ;
						}
						$CI->Autoload_Model->_create_batch(array(
							'table' => 'promotional_relationship',
							'data' => $_insert_relationship_batch,
						));
					}
				}

				if($condition_type == 'condition_module_catalogue'){
					$condition_value = json_decode($condition_value);
					if(isset($condition_value) && is_array($condition_value) && count($condition_value)){
						$_insert_relationship_batch = [];
						foreach ($condition_value as $key => $value) {
							$module = $CI->Autoload_Model->_get_where(array(
								'select' => 'id',
								'table' => $_insert['module'],
								'where' => array('catalogueid' => $value),
							),true);
							if(isset($module) && is_array($module) && count($module)){
								foreach ($module as $sub => $subs) {
									$_insert_relationship['moduleid'] =$subs['id'];
									$_insert_relationship['promotionalid'] =$id;
									$_insert_relationship_batch[] = $_insert_relationship ;
								}
							}
						}
						if(isset($_insert_relationship_batch) && is_array($_insert_relationship_batch) && count($_insert_relationship_batch)){
							$CI->Autoload_Model->_create_batch(array(
								'table' => 'promotional_relationship',
								'data' => $_insert_relationship_batch,
							));
						}
					}
				}

				if($condition_type == 'condition_moduleidAll' || $condition_type == 'condition_cartAll'){
					$module = $CI->Autoload_Model->_get_where(array(
						'select' => 'id',
						'table' => $_insert['module'],
					),true);
					if(isset($module) && is_array($module) && count($module)){
						foreach ($module as $sub => $subs) {
							$_insert_relationship['moduleid'] =$subs['id'];
							$_insert_relationship['promotionalid'] =$id;
							$_insert_relationship_batch[] = $_insert_relationship ;
						}
					}
					if(isset($_insert_relationship_batch) && is_array($_insert_relationship_batch) && count($_insert_relationship_batch)){
						$CI->Autoload_Model->_create_batch(array(
							'table' => 'promotional_relationship',
							'data' => $_insert_relationship_batch,
						));
					}
				}
				break;
		}
		
	}
}

// lấy thông tin khuyến mại gửi lên để ghi vào bảng promotional_relationship
if(!function_exists('getPromotionalRelationship')){
	function getPromotionalRelationship($_insert, $id){
		$CI = & get_instance();
		$_insert_relationship = [];
		$_insert_relationship['promotionalid'] = $id;
		$_insert_relationship['code'] = $_insert['code'];
		$_insert_relationship['use_common'] = $_insert['use_common'];
		$_insert_relationship['title'] = (isset($_insert['title'])) ? $_insert['title']: '';

		$choose_date = $CI->input->post('choose_date');
		if($choose_date != '1'){
			$_insert_relationship['start_date'] = $_insert['start_date'];
			$_insert_relationship['end_date'] = $_insert['end_date'];
		}

		$_insert_relationship['discount_type'] = $_insert['discount_type'];
		$_insert_relationship['discount_value'] = $_insert['discount_value'];



		
		$condition_type = $CI->input->post('condition_type');
		if($condition_type != 'condition_cartAll' && $condition_type != 'condition_moduleidAll'){
			$_insert['condition_value'] = json_encode($CI->input->post('condition_value'));
		}else{
			$_insert['condition_value'] = '';
		}

		$_insert_relationship['condition_type'] = $condition_type;
		$_insert_relationship['condition_value'] = $_insert['condition_value'];
		$_insert_relationship['condition_type_1'] = $_insert['condition_type_1'];
		$_insert_relationship['condition_value_1'] = $_insert['condition_value_1'];

		$discount_type = $CI->input->post('discount_type');
		if($discount_type == 'ship'){
			$_insert_relationship['freeship'] = $_insert['freeship'];
			$_insert_relationship['freeshipAll'] = $_insert['freeshipAll'];
			$_insert_relationship['cityid'] = $_insert['cityid'];
		}
		if($discount_type == 'object'){
			$_insert_relationship['discount_moduleid'] = json_encode($CI->input->post('discount_moduleid'));
		}
		$_insert_relationship['module'] = $_insert['module'];
		$_insert_relationship['created'] = $_insert['created'];
		$_insert_relationship['userid_created'] = $_insert['userid_created'];
		$_insert_relationship['publish'] = $_insert['publish'];
		return $_insert_relationship;
	}
}
