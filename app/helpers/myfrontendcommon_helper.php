<?php


if(!function_exists('get_location_name')){
	function get_location_name($field = '', $table = 'vn_province'){
		$CI = & get_instance();
		$result = $CI->Autoload_Model->_get_where(array(
			'select' => 'name',
			'table' => $table,
			'where' => $field,
		));

		if(isset($result) && is_array($result) == false && count($result) == 0){ 
			$result = '';
	    }   

		return $result['name'];
	}
}
if(!function_exists('getObjCatChildPost')){
	function getObjCatChildPost($param = '', $module = 'product_catalogue'){

		$post = isset($param['post'])? $param['post'] : false;
		$post_child = isset($param['post_child'])? $param['post_child'] : false;

		$CI = & get_instance();
		$catalogue = $CI->Autoload_Model->_get_where(array(
			'select' => 'id, title, slug, canonical, lft, rgt, level, parentid, image, description, created',
			'table' => $module,
			'where' => array(
				'publish' => 0,
				'lft >=' => $param['lft'],
				'rgt <=' => $param['rgt'],
			),
			'order_by' => 'order desc, id asc',
		), true);

		// $catalogue = recursive($catalogue, isset($param['parentid'])? (int)$param['parentid'] : 0);

		if($post == false){
			return $catalogue;
		}

		if(isset($catalogue) && is_array($catalogue) && count($catalogue)){ 
			$module = explode('_' , $module);
			$module = $module[0];
			foreach ($catalogue as $key => $val) {
				$catalogue[$key]['post'] = $CI->Autoload_Model->_get_where(array(
					'select' => 'id, title, slug, canonical, catalogueid, image, description, created',
					'table' => $module,
					'where' => array(
						'publish' => 0,
						'catalogueid' => $val['id'],
					),
					'order_by' => 'order desc, id asc',

				), true);
			}

			$catalogue = recursive($catalogue, isset($param['parentid'])? (int)$param['parentid'] : 0);

		}



		return $catalogue;
	}
}

// in: 
	/*
		$detailArticle['title'] = $valPost['title'];
		$detailArticle['href'] = rewrite_url($valPost['canonical'], TRUE, TRUE);
		$detailArticle['image'] = $valPost['image'];
		$detailArticle['description'] = $valPost['description'];
		$detailArticle['extend_description'] = $valPost['extend_description'];
		$detailArticle['created'] = gettime($valPost['created'], 'd/m');
	*/

if(!function_exists('getExtDesc')){
	function getExtDesc($data = ''){
		$CI = & get_instance();
		$temp = [];


		$detailArticle = $data;

		//khối mở rộng
		if(isset($detailArticle['extend_description']) && $detailArticle['extend_description'] != ''){

			$extend_description = json_decode($detailArticle['extend_description'], true);
			// print_r($extend_description); exit;
			if(isset($extend_description) && is_array($extend_description) && count($extend_description)){
				foreach($extend_description as $key => $val){
					foreach($val as $keyChild => $valChild){
						if($key == 'title') $temp[$keyChild]['title'] = $valChild;
						else if($key == 'image') $temp[$keyChild]['image'] = $valChild;
						else $temp[$keyChild]['description'] = $valChild;
					}
				}

				krsort($extend_description['title']);
				krsort($extend_description['image']);
				krsort($extend_description['description']);
				// rsort($extend_description['image']); // xem lại: chưa hiểu 
			}
			
			// chia dữ liệu
			if(isset($temp) && is_array($temp) && count($temp)){
				krsort($temp);
				$temp = array_values($temp);
			}
			// print_r($temp); exit;
		}


		return $temp;
	}
}


if(!function_exists('getObjCatChild')){
	function getObjCatChild($param = '', $post = false , $module = 'product_catalogue'){
		$CI = & get_instance();
		$catalogue = $CI->Autoload_Model->_get_where(array(
			'select' => 'id, title, slug, canonical, lft, rgt, level, parentid, image, description, created',
			'table' => $module,
			'where' => array(
				'publish' => 0,
				'lft >=' => $param['lft'],
				'rgt <=' => $param['rgt'],
			),
			'order_by' => 'order desc, id asc',
		), true);

		$catalogue = recursive($catalogue);

		if($post == false){
			return $catalogue;
		}

		if(isset($catalogue) && is_array($catalogue) && count($catalogue)){ 
			$module = explode('_' , $module);
			$module = $module[0];

			foreach ($catalogue as $key => $val) {
				$catalogue[$key]['post'] = $CI->Autoload_Model->_get_where(array(
					'select' => 'id, title, slug, canonical, catalogueid, image, description',
					'table' => $module,
					'where' => array(
						'publish' => 0,
						'catalogueid' => $val['id'],
					),
					'order_by' => 'order desc, id asc',

				), true); 
			}
		}
		return $catalogue;
	}
}


// getListArtInCat
if(!function_exists('getListArtInCat')){
	function getListArtInCat( $param = [], $flag = false){
		$CI = & get_instance();

		$catalogue = '';
		$query = '';

		$level =  (isset($param['level']))? (int)$param['level']: 0;
		$child_limit = (isset($param['child_limit']))? (int)$param['child_limit']: 100;
		$child_order = (isset($param['child_order']))? $param['child_order'] : 'order desc, id desc';
		$post_limit = (isset($param['post_limit']))? (int)$param['post_limit'] : 100;
		$post_order = (isset($param['post_order']))? $param['post_order'] : 'order desc, id desc';

		if($level){
			$query = 'level = '.$level;
		}
		

		$detailCatalogue = (isset($param['detailCatalogue']))? $param['detailCatalogue'] : '';

		if(isset($detailCatalogue) && is_array($detailCatalogue) && count($detailCatalogue)){ 
			$catalogue = $CI->Autoload_Model->_get_where(array(
				'select' => 'id, title, slug, canonical, lft, rgt, parentid, image, icon, album, excerpt, description, created',
				'table' => 'article_catalogue',
				'where' => array(
					'publish' => 0,
					'lft >= ' => $detailCatalogue['lft'],
					'rgt <= ' => $detailCatalogue['rgt'],
				),
				'query' => $query,
				'limit' => $child_limit,
				'order_by' => $child_order,
			), true);
			if($post_limit != 0){
				if(isset($catalogue) && is_array($catalogue) && count($catalogue)){ 
					foreach ($catalogue as $key => $val) {
						$catalogue[$key]['post'] = $CI->Autoload_Model->_get_where(array(
							'select' => 'id, title, slug, canonical, image,excerpt, description, created',
							'table' => 'article',
							'where' => array(
								'publish' => 0,
								'catalogueid' => $val['id'],
							),
							'limit' => $post_limit,
							'order_by' => $post_order,
						), true); 
					}
				}
			}

			if($flag == true){ // lấy ra cả danh mục hiện tại
				$catalogue = recursive($catalogue);
			}else{
				$catalogue = recursive($catalogue, $detailCatalogue['id']);
			}

			
		}

		return $catalogue;
	}
}



if(!function_exists('renderCountDown')){
	function renderCountDown( $time = '' ){
		$html = '';
		if(isset($time) && !empty($time)){
			$Y = gettime($time, 'Y');
			$m = gettime($time, 'm');
			$d = gettime($time, 'd');
			$html = $html.'<div class="countdown-wrapper">';
				$html = $html.'<div class="countdown-timer instance-0" data-countdown-year="'.$Y.'" data-countdown-month="'.$m.'" data-countdown-day="'.$d.'"></div>';
			$html = $html.'</div>';
		}
		return $html;

	}
}
if(!function_exists('slide')){
	function slide( $param  = '' ){
		$CI = & get_instance();
		$slide = $CI->Autoload_Model->_get_where(array(
			'select' => 'id, title, src, link, content',
			'table' => 'slide',
			'query' => 'catalogueid IN (SELECT `id`  FROM `slide_catalogue` WHERE `keyword` = "'.$param['keyword'].'")',
			'order_by' => 'order desc'
		), true);
        return $slide;
	}
}
if(!function_exists('getBlockAttr')){
	function getBlockAttr( $detailproduct = '' ){
		$CI = & get_instance();
		if(isset($detailproduct['version_json'])){
			$version = json_decode(base64_decode($detailproduct['version_json']), true);
			if(isset($detailproduct) && check_array($detailproduct) && isset($detailproduct['title'])){
				if(isset($version) && is_array($version) && count($version)){

					$list_attr = $version[2];
					$list_attr_cata = $version[1];
					$list_checked_version = $version[0];
					if(isset($list_attr_cata) && check_array($list_attr_cata)){
						if(isset($list_checked_version) && check_array($list_checked_version)){
							foreach ($list_checked_version as $key => $value) {
								foreach ($list_attr_cata as $sub => $subs) {
									if($value == 1 && $key == $sub){
										$list_cata_version[] = $subs;
										$list_cata_version = array_unique($list_cata_version);
									}
								}
							}
						}
					}
					if(isset($list_attr) && check_array($list_attr)){
						$listAttrCata = $CI->Autoload_Model->_get_where(array(
							'select' => 'id, title',
							'table' => 'attribute_catalogue',
							'where_in' => $list_attr_cata,
						), TRUE);


						$version_attr = [];
						foreach ($list_attr as $key => $value) {
							$version_attr = array_merge($version_attr,$value);
						}
						$listAttr = $CI->Autoload_Model->_get_where(array(
							'select' => 'id,title, catalogueid',
							'table' => 'attribute',
							'where_in' => $version_attr,
							'where_in_field' => 'id',
						), TRUE);
						if(isset($listAttrCata) && check_array($listAttrCata)){
							foreach ($listAttrCata as $keyCata => $valCata) {
								$child = '';
								foreach ($listAttr as $keyAttr => $valAttr) {
									$detailCata[$keyCata] = $valCata;
									if($valAttr['catalogueid'] == $valCata['id']){
										$child[] = $valAttr;
									}
								}
								$detailCata[$keyCata]['child'] = $child;
							}
						}


					}
					if(isset($list_attr_cata) && check_array($list_attr_cata)){
						if(isset($detailproduct['image_color_json'])){
							$list_color = json_decode(base64_decode($detailproduct['image_color_json']), true);
							if(isset($list_color) && check_array($list_color) ){
								$listColor = $CI->Autoload_Model->_get_where(array(
									'select' => 'title, id, color, title',
									'table' => 'attribute',
									'distinct'=>'true',
									'where_in' => array_keys($list_color),
									'where_in_field' => 'id ',
								), TRUE);
								foreach($listColor as $keyColor => $valColor){
									$image_color = (!empty($list_color[$valColor['id']])) ? $list_color[$valColor['id']] :  '';
									$listColor[$keyColor]['image'] = $image_color;
								}
								if(isset($detailCata) && check_array($detailCata) ){
									foreach ($detailCata as $key => $val) {
										if($val['id'] ==2){
											$detailCata[$key]['child'] = $listColor;
										}
									}
								}
							}
						}

						if(isset($list_cata_version) && check_array($list_cata_version) && isset($detailCata) && check_array($detailCata) ){
							foreach ($list_cata_version as $key => $value) {
								foreach ($detailCata as $sub => $subs) {
									if($subs['id'] != 2){
										if($value == $subs['id']){
											$data['version'][$sub] = $subs;
											unset($detailCata[$sub]);
										}
									}else{
										if($value == $subs['id']){
											$subs['child'][0]['version'] = 1;
										}else{
											$subs['child'][0]['version'] = 0;
										}
										$data['color'] = $subs;
									}
								}
								$data['attr'] = $detailCata;
							}
						}
					}
				}
			}
		}
		return isset($data) ? $data : '';
	}
}
if(!function_exists('html_frontend_list_version')){
	function html_frontend_list_version($list_version = ''){
		$html= '';
		$index =0;
		if(isset($list_version) && is_array($list_version) && count($list_version)){
			$html =$html.'<div class="info-version common-mgb10  js_addtribute">';
				$html =$html.'<ul class="uk-grid uk-grid-small uk-grid-width-1-4">';
					foreach ($list_version as $key => $value) {
						$index ++ ;
						$html =$html.'<li '.(($index == 1) ? 'class="js_choose"' : '') .' data-id="'.$value['attribute1'].'-'.$value['attribute2'] .'">';
							$html =$html.'<a title="">';
								$html =$html.'<div class="info-size">'.$value['title'].'</div>';
								$html =$html.'<div class="info-price color-pink">'.addCommas($value['price_version']).' đ' .'</div>';
							$html =$html.'</a>';
						$html =$html.'</li>';
					}
				$html =$html.'</ul>';
			$html =$html.'</div>';
		}
		return $html;
	}
}
if(!function_exists('html_frontend_version')){
	function html_frontend_version($color = '', $version = ''){
		$html= '';
		$index =0;
		$html =$html.'<div class="js_addtribute">';
		if(isset($color) && is_array($color) && count($color)){
			$html =$html.'<div class="title">Màu sắc</div>';
			$html =$html.'<ul class="uk-clearfix uk-list uk-flex uk-flex-middle mb10">';
				foreach ($color as $key => $val) {
					$index ++ ;
					$html =$html.'<li class="m-r '.(($index == 1) ? 'js_choose' : '').'" data-id="'.$val['id'].'">';

						$html =$html.'<a  title="" class="uk-flex uk-flex-middle" data-color="">';
							if(!empty($val['image'])){
								$html = $html.'<span class="image img-scaledown"><img src="'.$val['image'].'" alt="'.$val['title'].'"></span>';
							}
							$html =$html.'<span class="subtitle">'.$val['title'].'</span>';
						$html =$html.'</a>';
					$html =$html.'</li>';
				}
			$html =$html.'</ul>';
		}
		if(isset($version) && is_array($version) && count($version)){
			$index =0;
			$html =$html.'<div class="title">'.$version[0]['catalogue'].'</div>';
			$html =$html.'<ul class="uk-list uk-flex uk-flex-middle mb10">';

				foreach ($version as $key => $val) {
					$index ++ ;
					$html =$html.'<li class="m-r '.(($index == 1) ? 'js_choose' : '').'" data-id="'.$val['id'].'">';

						$html =$html.'<a  title="" class="uk-flex uk-flex-middle" data-color="">';
							$html =$html.'<span class="subtitle">'.$val['title'].'</span>';
						$html =$html.'</a>';
					$html =$html.'</li>';
				}
			$html =$html.'</ul>';
		}
		$html =$html.'</div>';
		return $html;
	}
}
if(!function_exists('html_frontend_version_1')){
	function html_frontend_version_1($color = '', $version = ''){
		$html= '';
		$index =0;
		$html =$html.'<div class="js_addtribute">';
			if(isset($version) && is_array($version) && count($version)){
				foreach ($version as $key => $val) {
					$index =0;
					if($val['id'] != '4' ){
						$html =$html.'<div class="title">'.$val['title'].'</div>';
						$html =$html.'<ul class="uk-list uk-flex uk-flex-middle mb10">';
							foreach ($val['child'] as $sub => $subs) {
								$index ++ ;
								$content = $val['title']. ' :' .$subs['title'];
								$html =$html.'<li class="m-r '.(($index == 1) ? 'js_choose' : '').'" data-id="'.$subs['id'].'" data-content="'.$content.'">';

									$html =$html.'<a  title="" class="uk-flex uk-flex-middle" data-color="">';
										$html =$html.'<span class="subtitle">'.$subs['title'].'</span>';
									$html =$html.'</a>';
								$html =$html.'</li>';
							}
						$html =$html.'</ul>';
					}else{
						if(isset($val['child']) && check_array($val['child']) ){
							$html =$html.'<div class="subtitle">Lựa chọn Options</div>';
							$html =$html.'<div class="option-block">';
								$html =$html.'<div class="product-size uk-flex uk-flex-middle uk-clearfix">';
									$html =$html.'<label>Size</label>';
									$html =$html.'<select class="nice-select">';
										foreach ($val['child'] as $sub => $subs) {
											$content = 'Size :' .$subs['title'];
											$html =$html.'<option data-id="'.$subs['id'].'"  data-content="'.$content.'">'.$subs['title'].'</option>';
										}
									$html =$html.'</select>';
								$html =$html.'</div>';
							$html =$html.'</div>';
						}
					}
				}
			}
			if(isset($color) && is_array($color) && count($color)){
				$html =$html.'<div class="option-block">';
					$html =$html.'<div class="product-color uk-clearfix">';
						$html =$html.'<div class="mb10">Màu sắc</div>';
						$html =$html.'<ul class="color-list uk-list">';
							foreach ($color['child'] as $key => $val) {
								$index ++ ;
								$content = 'Màu sắc :' .$val['title'];
								$html =$html.'<li class="m-r '.(($index == 1) ? 'js_choose' : '').'" data-content="'.$content.'" data-id="'.$val['id'].'" data-version="'.$color['child'][0]['version'].'">';
									if(!empty($val['image'])){
										$html =$html.'<a><span class="image img-scaledown"><img src="'.$val['image'].'" alt="'.$val['title'].'"></span></a>';
									}else{
										$html =$html.'<a  style="background:'.$val['color'].'"></a>';
									}
								$html =$html.'</li>';
							}
						$html =$html.'</ul>';
					$html =$html.'</div>';
				$html =$html.'</div>';
			}
		$html =$html.'</div>';
		return $html;
	}
}
if(!function_exists('html_frontend_promo')){
	function html_frontend_wholesale($product_wholesale = ''){
		$html= '';
		$index =0;
		$html =$html.'';
		if(isset($product_wholesale) && is_array($product_wholesale) && count($product_wholesale)){ $index =0;
			$html =$html.'<div class="info-wholesale common-mgb10  js_wholesale">';
				$html =$html.'<ul class="uk-grid uk-grid-small uk-grid-width-1-3">';
					foreach ($product_wholesale as $key => $val) {
						$index ++ ;
						$html =$html.'<li data-quantity_start="'.$val['quantity_start'].'">';
							$html = $html.'<a title="" onclick="return false;">';
								$html =$html.'<div class="info-size" >Từ '.$val['quantity_start'].' sp đến '.$val['quantity_end'].' sp</div>';
								$html =$html.'<div class="info-price color-pink">'.addCommas($val['price_wholesale']).' đ'.'</div>';
							$html =$html.'</a>';
						$html =$html.'</li>';
					}
				$html =$html.'</ul>';
			$html =$html.'</div>';
		}
		return $html;
	}
}
if(!function_exists('html_frontend_promo')){
	function html_frontend_promo($block_promotional = ''){
		$html = '';
		if(isset($block_promotional) && is_array($block_promotional) && count($block_promotional)){
			$html =$html.'<div class="js_block_promotional">';
				$html =$html.'<div class="uk-accordion" data-uk-accordion>';
					$html =$html.'<div class="uk-accordion-title accordion-label">Khuyến mại</div>';
				    $html =$html.'<div class="uk-accordion-content accordion-content">';
				    	$html =$html.'<div class="form-row">';

							foreach ($block_promotional as $common => $list_promo) {
								if($common == 1){
									$promoid = '';
									foreach ($list_promo as $sub => $subs) {
										$promoid = $promoid.$sub.'-';
									}
									$promoid = substr( $promoid,  0, strlen($promoid) -1 );

									$html = $html.'<div class="uk-flex uk-flex-middle"><input class="input-radio" data-id='.$promoid.' type="radio" id="promo-'.$promoid.'" name="radio" ><label for="promo-'.$promoid.'" class="radio-label">';
									foreach ($list_promo as $key => $promo) {
										$html = $html.'<a href="'.$promo['canonical'].'">'.$promo['title'].' - <strong>'.$promo['detail'].'</strong></a></br>';
									}
									$html = $html.'</label></div>';
								}
								if( $common == 0){
									foreach ($list_promo as $key => $promo) {
										$html = $html.'<input class="input-radio" data-id='.$key.' type="radio" name="radio" id="promo-'.$key.'" >';
										$html = $html.'<label class="radio-label" for="promo-'.$key.'" ><a href="'.$promo['canonical'].'">'.$promo['detail'].'</a></label>';
									}
								}
							}
				    	$html =$html.'</div>';
				    $html =$html.'</div>';
				$html =$html.'</div>';
			$html =$html.'</div>';
		}
		return $html;
	}
}

if(!function_exists('logo')){
	function logo(){
		$CI =& get_instance();
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$system = $CI->Autoload_Model->_get_where(array(
			'select' => 'keyword, content',
			'table' => 'general',
			'order_by' => 'keyword asc',
		), TRUE);
		if(isset($system) && is_array($system) && count($system)){
			foreach($system as $key => $val){
				$system[$val['keyword']] = $val['content'];
			}
		}
		if($actual_link == base_url()){
			$logo = '<h1 class="logo" ><a itemprop="url" href="." title="'.$system['seo_meta_title'].'"><img src="'.$system['homepage_logo'].'" alt="'.$system['seo_meta_title'].'" itemprop="logo" /></a><span class="uk-hidden">'.$system['seo_meta_title'].'</span></h1>';
		}else{
			$logo = '<div class="logo" ><a itemprop="url" href="." title="'.$system['seo_meta_title'].'"><img src="'.$system['homepage_logo'].'" alt="'.$system['seo_meta_title'].'" itemprop="logo" /></a></div>';
		}
		return $logo;

	}
}


if(!function_exists('navigation')){
	function navigation($param = ''){
		$CI =& get_instance();
		$catalogue = $CI->Autoload_Model->_get_where(array(
			'select' => 'id',
			'table' => 'navigation_catalogue',
			'where' => array('keyword' => $param['keyword']),
		));
		$navigation = '';
		if(isset($catalogue) && is_array($catalogue) && count($catalogue)){
			$navigation = $CI->Autoload_Model->_get_where(array(
				'select' => 'id, title, lft, rgt, level, link',
				'table' => 'navigation',
				'where' => array('catalogueid' => $catalogue['id']),
				'order_by' => 'order desc'
			), TRUE);
		}
		if(isset($navigation) && is_array($navigation) && count($navigation)){
			foreach($navigation as $key => $val){
				$navigation[$key]['sub'] = $CI->Autoload_Model->_get_where(array(
					'select' => 'id, title, lft, rgt, level, parentid, link',
					'table' => 'navigation',
					'where' => array('lft >' => $val['lft'], 'rgt <' => $val['rgt']),
					'order_by' => 'order desc'
				), TRUE);
			}
		}
		if(isset($navigation) && is_array($navigation) && count($navigation)){
			foreach($navigation as $key => $val){
				if(isset($val['sub']) && is_array($val['sub']) && count($val['sub'])){
					$navigation[$key]['children'] = recursive($val['sub'], $val['id']);
					unset($navigation[$key]['sub']);
				}
			}
		}
		return $navigation;
	}
}
//trả về: mảng thông tin menu cha con(các menu cấp 1 điều kiện lấy được đặt trong module giao diện)
//đầu vào:
if(!function_exists('getMenu')){
	function getMenu(){
		$CI = & get_instance();
        $layout = $CI->Autoload_Model->_get_where(array(
            'select' => 'data_original',
            'table' => 'layout',
            'where' => array('id' => 2),
        ));
		$layout = json_decode($layout['data_original'], true);
		$list_cata_id = getColumsInArray($layout['object'], 'product_catalogue');
		$list_cata_id = $list_cata_id[0];
		$deatailProduct = $CI->Autoload_Model->_get_where(array(
            'select' => 'id, title, lft, rgt, level, parentid, canonical, image_json, icon',
            'table' => 'product_catalogue',
        ), true);
       	if(isset($list_cata_id) && is_array($list_cata_id) && count($list_cata_id)){
	        foreach ($list_cata_id as $key => $prd_cata_id) {
	        	foreach ($deatailProduct as $sub => $product_catalogue) {
	        		if($prd_cata_id == $product_catalogue['id']){
				        $detailMenu[] = $product_catalogue;
	        		}
	        	}
	        }
	    }
	    if(isset($detailMenu) && is_array($detailMenu) && count($detailMenu)){
	        foreach ($detailMenu as $key => $val) {
		        $detailMenu[$key]['child'] = recursive($deatailProduct, $val['id']);
	        }
	    }
		return (isset($detailMenu)) ?  $detailMenu : '';
	}
}

if(!function_exists('recursive')){
	function recursive($array = '', $parentid = 0){
		$temp = [];
		if(isset($array) && is_array($array) && count($array)){
			foreach($array as $key => $val){
				if($val['parentid'] == $parentid){
					$temp[] = $val;
					if(isset($temp) && is_array($temp) && count($temp)){
						foreach($temp as $keyTemp => $valTemp){
							$temp[$keyTemp]['children'] = recursive($array, $valTemp['id']);
						}
					}

				}
			}
		}
		return $temp;
	}
}
//trả về: mảng id trỏ đến mảng ( thuộc tính bảng product kết hợp vs promo relationship)
//đầu vào: limit:giớ hạn số lượng SP trả về
//         select: lấy thêm field ở bảng product
//         price_promo(true OR false): có tính SP khuyến mại thuộc SP KM ko
//         prd_promo(true OR false): chỉ lấy sản phẩm có CTKM
if(!function_exists('getDetailListPrd')){
	function getDetailListPrd($param = ''){

		$CI = & get_instance();
		$time = gmdate('Y-m-d', time() + 7*3600)." 00:00:00";
		$productList = (isset($param['productList'])) ? $param['productList'] : '';
        $query_promo = '(
	        				( `tb2`.`end_date` = "0000-00-00 00:00:00" )
	        				||
	        				( `tb2`.`start_date` <= "'.$time.' " AND `tb2`.`end_date` >= "'.$time.' 00:00:00'.'")
        				)
        				AND (`tb2`.`module` = "product") AND `tb2`.`code` = ""';
		$list_id = getColumsInArray($productList, 'productid');
       	if(isset($list_id) && is_array($list_id) && count($list_id)){
			$list_id = getColumsInArray($productList, 'id');
       	}
        $detailPromo = $CI->Autoload_Model->_get_where(array(
            'select' => 'tb2.discount_value, tb2.discount_type, tb2.discount_moduleid, tb2.use_common, tb2.promotionalid, tb2.condition_type, tb2.condition_value, tb2.condition_type_1, tb2.condition_value_1, tb2.module, tb2.freeship, tb2.freeshipAll, tb2.cityid, tb2.moduleid, tb2.start_date, tb2.end_date',
            'table' => 'promotional_relationship as tb2',
            'query' => $query_promo,
            'where_in' => $list_id,
            'where_in_field' => 'tb2.moduleid',
        ), true);
       	if(isset($productList) && check_array($productList)){
        	if(isset($detailPromo) && check_array($detailPromo)){
		        foreach ($productList as $keyPrd => $valPrd) {
		        	foreach ($detailPromo as $keyPromo => $valPromo) {
		        		if($valPrd['productid'] ==  $valPromo['moduleid']){
		        			$list_prd_promo[$valPrd['productid']][] = array_merge($valPromo,$valPrd);
		        		}
		        	}
		        }
	        }
	        if(isset($list_prd_promo) && is_array($list_prd_promo) && count($list_prd_promo)){
			    foreach ($list_prd_promo as $id => $prd) {
			    	$prd = checkPromo($prd, $id);
			    	$list_prd_promo[$id] = getInfoPrdPromo($prd);
			    }
			    foreach ($list_prd_promo as $keyPromo => $valPromo) {
			    	foreach ($productList as $keyPrd => $valPrd) {
	        			if($valPromo['moduleid'] == $valPrd['productid']){
	        				$list_prd[$keyPromo] = $valPromo;
	        			}
		        	}
			    }
		    }
		    if(isset($list_prd) && is_array($list_prd) && count($list_prd)){
			    foreach ($productList as $key => $val) {
				    foreach ($list_prd as $sub => $subs) {
				    	if((isset($val['id']) ? $val['id'] : $val['productid']) == (isset($subs['id']) ? $subs['id'] : $subs['productid'])){
				    		$productList[$key] = array_merge($val,$subs);
				    	}
				    }
			    }
		    }
        }
		return $productList;
	}
}

//trả về:  mảng ( thuộc tính bảng product kết hợp vs promo relationship), thêm html_gift_image, price_promo, time(thời gian KM)
//đầu vào: limit:giớ hạn số lượng SP trả về
//         select: lấy thêm field ở bảng product
//         price_promo(true OR false): có tính SP khuyến mại thuộc SP KM ko
if(!function_exists('getInfoPrdPromo')){
	function getInfoPrdPromo($prd = ''){
		$CI = & get_instance();
		$price_old = getPriceOld($prd[0]);
		$price_promo = $price_old;
		foreach ($prd as $key => $detailPromo) {
			if($detailPromo['price_sale'] == 0 && $detailPromo['price_contact'] == 0){
				if(!empty($detailPromo['discount_value'])){
					$getPromotional = json_decode(getPromotional($detailPromo), true);
					$time_promo = $getPromotional['time_promo'];
					if($detailPromo['discount_type'] == 'price'){
						$prd['detail_promo'][] = $getPromotional['detail'];
						$price_promo = $price_promo - $detailPromo['discount_value'];
					}elseif($detailPromo['discount_type'] == 'percent'){
						$prd['detail_promo'][] = $getPromotional['detail'];
						$price_promo = $price_promo - ($price_old * $detailPromo['discount_value'])/100;
						$price_promo = FLOOR($price_promo);
					}elseif($detailPromo['discount_type'] == 'same'){
						$prd['detail_promo'][] = $getPromotional['detail'];
						$price_promo = $detailPromo['discount_value'];
					}elseif($detailPromo['discount_type'] == 'object' && $detailPromo['discount_value'] == 100){
						$ids =json_decode($detailPromo['discount_moduleid'], true);
						$list_product_gift = $CI->Autoload_Model->_get_where(array(
							'table' => $detailPromo['module'],
							'where_in' => $ids,
							'select' => ' id, image, canonical',
						), true);
						foreach ($list_product_gift as $sub => $product_gift) {
							$prd_gift[$product_gift['id']]['image'] = $product_gift['image'];
							$prd_gift[$product_gift['id']]['canonical'] = $product_gift['canonical'];
						};
					}
				}
			}
		}
		unset($prd[0]['discount_value'],$prd[0]['discount_type'],$prd[0]['discount_moduleid'],$prd[0]['use_common'],$prd[0]['promotionalid'],$prd[0]['condition_type'],$prd[0]['condition_type_1'],$prd[0]['condition_value_1'],$prd[0]['module'],$prd[0]['freeship'],$prd[0]['freeshipAll'],$prd[0]['cityid']);
		$product = $prd[0];
    	$product['prd_gift'] = isset($prd_gift) ? $prd_gift : '';
    	$product['price_promo'] = (isset($price_promo) && $price_promo > 0) ? $price_promo : 0;
    	$product['time_promo'] = isset($time_promo) ? $time_promo : '';
		return $product;
	}
}

 ?>
