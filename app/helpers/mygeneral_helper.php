<?php

if(!function_exists('get_query_select_highlight')){
	function get_query_select_highlight($module = 'product'){
		// lấy những thông tin chung
		$query_select = '`object`.`id`, `object`.`title`, `object`.`slug`, `object`.`canonical`, `object`.`image`, `object`.`description`,`object`.`created` ,`object`.`updated`';

		$query_select .= (($module == 'article_catalogue') ? ', `object`.`lft`, `object`.`rgt`, `object`.`parentid`' : '');
		// $query_select .= (($module == 'article') ? ', `object`.`url_view`, `object`.`extend_description`, `object`.`excerpt`' : '');
		$query_select .= (($module == 'product_catalogue') ? ', `object`.`lft`, `object`.`rgt`, `object`.`parentid`' : '');
		$query_select .= (($module == 'product') ? ', `object`.`price`, `object`.`price_sale`, `object`.`price_contact`' : '');
		$query_select .= (($module == 'media') ? ', `object`.`image_json`, `object`.`video_link`, `object`.`video_iframe`' : '');

		return $query_select;
	}
}



// chỉ lấy item được chỉ định: highlight or ishome

if(!function_exists('get_highlight_object')){
	function get_highlight_object($field = "highlight", $module = 'product') {
		$CI = & get_instance();

		if($field == 'ishome_all'){
			$_field = 'ishome';
			$result[] = get_highlight($_field, 'product_catalogue');
			$result[] = get_highlight($_field, 'product');
			$result[] = get_highlight($_field, 'article_catalogue');
			$result[] = get_highlight($_field, 'article');
		}else{
			$result[] = get_highlight($field, $module);
		}
		

		$result = array_filter($result);
		$temp = [];
		if(isset($result) && is_array($result) && count($result)){
			$temp = call_user_func_array('array_merge', $result);
		}
	    return $temp;
	}
}

// lấy danh mục highlight và chi tiết danh mục đó
/*
	in: $field : trường muốn lấy (highlight, ishome)
*/ 

if(!function_exists('get_group_highlight_object')){
	function get_group_highlight_object($field = "highlight", $module = 'product_catalogue') {
		$CI = & get_instance();
		
		$result = get_highlight($field, $module);
		
		if(isset($result) && is_array($result) && count($result)){
			$module = explode('_' , $module);
			$module = $module[0];

			foreach ($result as $key => $val) {
				$query_select = get_query_select_highlight($module);
				$result[$key]['post'] = $CI->Autoload_Model->_get_where(array(
					'select' => $query_select,
					'table' => $module.' as object',
					'where' => array(
						'object.publish' => 0,
						'object.catalogueid' => $val['id'],
					),
					// 'order_by' => 'object.order desc, object.id asc',
					'order_by' => 'object.id desc',

				), true); 
			}
		}
	    return $result;
	}
}

if(!function_exists('arr_merge')){
	function arr_merge($arr1, $arr2) {
		$result[] = $arr1;
		$result[] = $arr2;

	    return $result;
	}
}



if(!function_exists('get_highlight')){
	function get_highlight($field="highlight" , $module = 'product') {
		$CI = & get_instance();

		$query_select = get_query_select_highlight($module);
		$result =  $CI->Autoload_Model->_get_where(array(
			'select' => $query_select,
			'table' => $module.' as object',
			'where' => array(
				'publish' => 0,
				$field => 1,
			),
			'order_by' => 'order desc, id desc'
		),TRUE);

	    return $result;
	}
}
if(!function_exists('nice_number')){
	function nice_number($n) {
	    // first strip any formatting;
	    $n = (0+str_replace(",", "", $n));

	    // is this a number?
	    if (!is_numeric($n)) return false;

	    // now filter it;
	    if ($n >= 1000000000000) return round(($n/1000000000000), 2).' nghìn tỷ';
	    elseif ($n >= 1000000000) return round(($n/1000000000), 2).' tỷ';
	    elseif ($n >= 1000000) return round(($n/1000000), 2).' triệu';
	    elseif ($n >= 1000) return round(($n/1000), 2).' ngàn';

	    return number_format($n);
	}
}


if(!function_exists('renderHtmlTooltip')){
	function renderHtmlTooltip($src = 'template/backend/detail_view_type_2.png'){
		$html = '<button type="button" class="btn-popup" data-toggle="popover"  data-trigger="hover" data-container="body" data-placement="auto top" data-html="true" data-content="<div class=\'popup_image img-cover\'>
					<img src=\''.$src.'\'>
				</div>" data-original-title="" title="" aria-describedby="">
	                <i class="fa fa-info-circle" aria-hidden="true"></i>
	            </button>';

		return $html;
	}
}

if(!function_exists('render_discount_ship')){
	function render_ship($param = ''){
		$CI = & get_instance();
        $cityid = $param['cityid'];
        $districtid = $param['districtid'];

		$promo = $CI->Autoload_Model->_get_where(array(
            'select' => 'value',
            'table' => 'ship',
            'where' => array('districtid' => $districtid),
        ));
        if(!isset($promo) || !check_array($promo) ){
             $promo = $CI->Autoload_Model->_get_where(array(
                'select' => 'value',
                'table' => 'ship',
                'where' => array('cityid' => $cityid),
            ));
        }
        if(!isset($promo) || !check_array($promo) ){
        	$system = $CI->Autoload_Model->_get_where(array(
				'select' => 'keyword, content',
				'table' => 'general',
				'where' => array('keyword' => 'homepage_ship'),
			));

            $shipVal = (int)str_replace('.','',$system['content']);
        }else{
            $shipVal = $promo['value'];
        }
        return $shipVal;

	}
}
        
if(!function_exists('render_discount_ship')){
	function render_discount_ship($param = ''){
        $CI = & get_instance();
        $cityid = $param['cityid'];
        $districtid = $param['districtid'];
        $listId = $param['listId'];
        $qty = $param['qty'];
        $time = gmdate('Y-m-d', time() + 7*3600);
        $time = $time.' 00:00:00';
        $queryTime = ' (( start_date = "0000-00-00 00:00:00" && end_date = "0000-00-00 00:00:00" ) || ( start_date <= "'.$time.' " AND end_date >= "'.$time.'"))';
        $promo = $CI->Autoload_Model->_get_where(array(
            'select' => 'condition_type_1, condition_value_1, discount_value',
            'table' => 'promoship_relationship',
            'query' =>  $queryTime,
            'where' => array('module' => 'product', 'districtid' => $districtid),
            'where_in' => $listId,
            'where_in_field' => 'moduleid',
        ), true);
        if(!isset($promo) || !check_array($promo) ){
            $promo = $CI->Autoload_Model->_get_where(array(
                'select' => 'condition_type_1, condition_value_1, discount_value',
                'table' => 'promoship_relationship',
                'query' =>  $queryTime,
                'where' => array('module' => 'product', 'cityid' => $cityid),
                'where_in' => $listId,
                'where_in_field' => 'moduleid',
            ), true);
        }

        $discount_value = 0;
        if(isset($promo) && check_array($promo)) {
            foreach ($promo as $key => $val) {
                switch ($val['condition_type_1'])
                {
                    case 'condition_money':
                        if($total_cart > $val['condition_value_1']){
                            $discount_value = $val['discount_value'];
                        }
                        break;
                    case 'condition_quantity':
                        if($qty > $val['condition_value_1']){
                            $discount_value = $val['discount_value'];
                        }
                        break;
                }
            }
        }
        return $discount_value;

    }
}


if(!function_exists('getListUrlInXML')){
	function getListUrlInXML($param = ''){
		$doc = new DOMDocument('1.0');
        $doc->load('sitemap.xml');
        $urls = $doc->getElementsByTagName('url');
        $urlList = [];
        foreach ($urls as $e) {
            $loc = $e->getElementsByTagName('loc');
            $lastmod = $e->getElementsByTagName('lastmod');
            $priority = $e->getElementsByTagName('priority');
            $urlList[] = array(
                'loc' => $loc->item(0)->nodeValue,
                'lastmod' => $lastmod->item(0)->nodeValue,
                'priority' => $priority->item(0)->nodeValue,
            );
        }
        return $urlList;
	}
}
if(!function_exists('addUrlInXML')){
	function addUrlInXML($url = 'sitemap.xml', $urlList = ''){
		$xml = simplexml_load_file($url);
		$entry = $xml->addChild('url');
        $entry->addChild('loc', $urlList['loc']);
        $entry->addChild('lastmod', $urlList['lastmod']);
        $entry->addChild('priority', $urlList['priority']);
        $doc = new DOMDocument('1.0');
        $doc->formatOutput = true;
        $doc->preserveWhiteSpace = true;
        $doc->loadXML($xml->asXML(), LIBXML_NOBLANKS);
        $doc->save($url);
        return true;
	}
}
if(!function_exists('delUrlInXML')){
	function delUrlInXML($urlDel = ''){
		$doc = new DOMDocument('1.0');
        $doc->load('sitemap.xml');
        $the_document = $doc->documentElement;
        $url = $the_document->getElementsByTagName('url');
        $node = null;
        // $urlList = getListUrlInXML();
        foreach ($url as $e) {
            $loc = $e->getElementsByTagName('loc');
            if(isset($urlDel) && !empty($urlDel)){
            	if ($urlDel == $loc->item(0)->nodeValue) {
	                $node = $e;
	                break;
	            }
            }else{
            	$node = $e;
            	if ($node != null) {
		            $the_document->removeChild($node);
		        }
            	$node = null;
            }
        }
        if ($node != null) {
            $the_document->removeChild($node);
        }
        $doc->save('sitemap.xml');
    }
}
if(!function_exists('refreshSidemap')){
	function refreshSidemap(){
		$CI =& get_instance();
		// lấy tất cả router trong csdl
		$routerList = $CI->Autoload_Model->_get_where(array(
			'select' => 'canonical',
			'table' => 'router',
		), true);
		if(isset($routerList) && check_array($routerList)){
			$xmldoc = new DOMDocument();
			$xmldoc->formatOutput = true;
			$root = $xmldoc->createElement("urlset");
			$xmldoc->appendChild( $root );
			foreach ($routerList as $keyRo => $valRo) {
				$url = $xmldoc->createElement( "url" );
					$loc = $xmldoc->createElement("loc");
			        $loc->appendChild(
			               $xmldoc->createTextNode(site_url($valRo))
			        );
				    $url->appendChild($loc);

				    $lastmod = $xmldoc->createElement("lastmod");
			        $lastmod->appendChild(
			               $xmldoc->createTextNode(gmdate('Y-m-d H:i:s', time() + 7*3600))
			        );
				    $url->appendChild($lastmod);

				    $priority = $xmldoc->createElement("priority");
			        $priority->appendChild(
			               $xmldoc->createTextNode('0.80')
			        );
				    $url->appendChild($priority);
				$root->appendChild($url);
			}

			$xmldoc->save("sitemap.xml");
		}
		return true;
	}
}
if(!function_exists('updateSidemap')){
	function updateSidemap($param){
		$CI =& get_instance();
		if(isset($param['table']) && $param['table'] == 'router'){
			// kiểm tra xem trong bảng có URL chưa
			if(isset($param['where']) && check_array($param['where'])){
				$urlList = getListUrlInXML();
				$router = $CI->Autoload_Model->_get_where(array(
					'select' => 'canonical',
					'table' => 'router',
					'where' => $param['where'],
				));
				if(isset($urlList) && check_array($urlList) && isset($router['canonical']) && $router['canonical'] != ''){
					foreach ($urlList as $key => $val) {
						if(site_url($router['canonical']) == $val['loc']){
							delUrlInXML($router['canonical']);
						}
					}
				}
			}
			if(isset($param['canonical']) && $param['canonical'] != ''){
				// thêm URL
				$urlList =  Array(
		            'loc' => site_url($param['canonical']),
		            'lastmod' => gmdate('Y-m-d H:i:s', time() + 7*3600),
		            'priority' => 0.80,
		        );
				addUrlInXML($urlList);
			}

		}
		return true;
	}
}
if(!function_exists('convert_money')){
	function convert_money($param){
		$input = (isset($param['input'])) ? $param['input'] : 'VND';
		$output = (isset($param['output'])) ? $param['output'] : 'USD';
		$value = (isset($param['value'])) ? $param['value'] : '0';
		// Fetching JSON
		$req_url = 'https://api.exchangerate-api.com/v4/latest/'.$input;
		$response_json = file_get_contents($req_url);
		// Continuing if we got a result
		if(false !== $response_json) {
		    // Try/catch for json_decode operation
		    try {
			// Decoding
			$response_object = json_decode($response_json);

			// YOUR APPLICATION CODE HERE, e.g.
			$output_price = round(($value * $response_object->rates->$output), 2);
		    }
		    catch(Exception $e) {
		        // Handle JSON parse error...
		    }
		    return $output_price;
		}
		return 0;
	}
}
/* CẬP NHẬT LƯỢT XEM TỰ NHIÊN */
if(!function_exists('updateView')){
	function updateView($id = '', $viewed = ''){
		$CI =& get_instance();
		$update['viewed'] = $viewed + 1;
		$flag = $CI->Autoload_Model->_update(array(
			'table' => 'product',
			'where' => array('id' => $id),
			'data' => $update,
		));

		/* OBJECT đã xem */
		$objectSee = isset($_COOKIE[CODE.'productCookie'])?$_COOKIE[CODE.'productCookie']:NULL;
		$objectid = json_decode($objectSee, TRUE);
		if (!isset($objectSee) || empty($objectSee)) {
			setcookie(CODE.'productCookie', json_encode(array(
				0 => $id
			)), time() + (86400 * 30), '/');
		}else{
			foreach ($objectid as $key) {
				$objectid[] = $id;
			}
			$objectid = array_values(array_unique($objectid));
			setcookie(CODE.'productCookie', json_encode($objectid), time() + (86400 * 30), '/');
		}
	}
}
if(!function_exists('getPriceFrontend')){
	function getPriceFrontend($param = '', $suffix = true){
		$productDetail = $param['productDetail'];
		$price_old = 0;
		$price_final = 0;

		if(isset($productDetail['price_contact']) && $productDetail['price_contact'] == 1){
			return array(
				'price_old' => 'Giá Liên hệ',
				'price_final' => 'Giá Liên hệ',
				'percent' => '',
				'flag' => 1,
			);
		}else{
			if(isset($productDetail['price']) ){
				$price_final = $productDetail['price'];
			}
			if(isset($productDetail['price_version'])){
				$price_final = $productDetail['price_version'];
			}
			if(isset($productDetail['price_promo'])){
				$price_final = $productDetail['price_promo'];
			}
			if(isset($productDetail['price_wholesale'])){
				$price_final = $productDetail['price_wholesale'];
			}
			if(isset($productDetail['price_sale']) && $productDetail['price_sale'] != '' && $productDetail['price_sale'] > 0){
				$price_final = $productDetail['price_sale'];
			}
			if(isset($productDetail['price_coupon']) && $productDetail['price_coupon'] != '' && $productDetail['price_coupon'] > 0){
				$price_final = $productDetail['price_coupon'];
			}



			if(isset($productDetail['price_version'])){
				$price_old = $productDetail['price_version'];
			}
			if(isset($productDetail['price_wholesale'])){
				$price_old = $productDetail['price_wholesale'];
			}
			if(isset($productDetail['price']) ){
				$price_old = $productDetail['price'];
			}


			if($price_final == $price_old){
				$flag = 1;
			}else{
				$flag = 0;
			};

			if(!empty($price_final) && !empty($price_old)){
				$percent = ($price_old - $price_final)*100/$price_old;
				if($percent >1){
					$percent = round($percent);
				}else{
					$percent = round($percent,1);
				}
				$percent = $percent.' %';
			}

			if($suffix == false){
				$price_old = addCommas($price_old);
				$price_final = addCommas($price_final);
			}else{
				$price_old = addCommas($price_old).'<sup>đ</sup>';
				$price_final = addCommas($price_final).'<sup>đ</sup>';
			}

			


			return array(
				'price_old' => $price_old,
				'price_final' => $price_final,
				'percent' => isset($percent) ? $percent : '',
				'flag' => $flag,
			);
		}
	}
}
if(!function_exists('getProfitRevenue')){
	function getProfitRevenue($param = ''){
		$CI =& get_instance();
		$firstTimeInMonth = date('Y-m-01 00:00:00');
		$timeNow = date('Y-m-d H:i:s');

		// Lấy danh sách đơn hàng trong tháng
		$orderDetail = $CI->Autoload_Model->_get_where(array(
			'select' => 'id, price_final, quantity, moduleid, created',
			'table' => 'order_relationship',
			'query' => ' ( `created` >= "'.$firstTimeInMonth.' " ) AND ( created` <= "'.$timeNow.' " ) ',
			'order_by' => 'created ASC'
		), true);

		$productIdList = getColumsInArray($orderDetail, 'moduleid');
		// Lấy danh sách đơn nhập trong tháng
		$importDetail = $CI->Autoload_Model->_get_where(array(
			'select' => 'id, price, quantity, productid, created',
			'table' => 'import_relationship',
			'where_in' => $productIdList,
			'where_in_field' => 'productid',
			'query' => ' ( `created` >= "'.$firstTimeInMonth.' " ) AND ( created` <= "'.$timeNow.' " ) ',
		), true);
		// Lấy danh sách đơn trả hàng trong tháng
		$importReturnDetail = $CI->Autoload_Model->_get_where(array(
			'select' => 'id, price, quantity, productid, created',
			'table' => 'import_return_relationship',
			'where_in' => $productIdList,
			'where_in_field' => 'productid',
			'query' => ' ( `created` >= "'.$firstTimeInMonth.' " ) AND ( created` <= "'.$timeNow.' " ) ',
		), true);

		$profit[] = 0;
		$revenue[] = 0;
		foreach ($orderDetail as $keyOrder => $valOrder) {
			$created = $valOrder['created'];
			$id = $valOrder['moduleid'];
			$giaTriTon[$created] = 0;
			$soLuongTon[$created] = 0;

			foreach ($orderDetail as $keyOrder1 => $valOrder1){
				if($valOrder1['created'] < $created AND $id == $valOrder1['moduleid']){
					$quantity = $valOrder1['quantity'];
					$giaTriTon[$created] = (int)$giaTriTon[$created] - (int)$quantity*(int)$valOrder1['price_final'];
					$soLuongTon[$created] = (int)$soLuongTon[$created] - (int)$quantity;
				}
			}


			foreach ($importDetail as $keyImport => $valImport) {
				if(($valImport['created'] < $created) AND ($id == $valImport['productid'])){
					$quantity = $valImport['quantity'];
					$giaTriTon[$created] = (int)$giaTriTon[$created] + (int)$quantity*(int)$valImport['price'];
					$soLuongTon[$created] = (int)$soLuongTon[$created] + (int)$quantity;
				}
			}

			foreach ($importReturnDetail as $keyImportReturn => $valImportReturn) {
				if($valImportReturn['created'] < $created AND $id == $valImportReturn['productid']){
					$quantity = $valImport['quantity'];

					$giaTriTon[$created] = (int)$giaTriTon[$created] - (int)$quantity*(int)$valImportReturn['price'];
					$soLuongTon[$created] = (int)$soLuongTon[$created] - (int)$quantity;
				}
			}
			$profit[$created] = 0;
			$revenue[$created] = 0;
			$average_price = '';
			if(isset($giaTriTon) && check_array($giaTriTon)){
				foreach ($giaTriTon as $key => $value) {
					if($soLuongTon[$key] == 0){
						$average_price[$key] = 0;
					}else{
						$average_price[$key] = $value/ ($soLuongTon[$key]);
					}
				}
			}
			$average_price[$created] = (isset($average_price[$created])) ? $average_price[$created] : 0;
			$revenue[$created] =  $valOrder['price_final']*$valOrder['quantity'];
			$profit[$created] = $profit[$created] + ($valOrder['price_final'] - $average_price[$created])*$valOrder['quantity'];
		}
		$data_revenue ='';
		foreach ($revenue as $created => $val) {
			$created = trim($created);
			$date = substr( $created, 8, strlen($created)-17);
			$data_revenue[$date] = ((isset($data_revenue[$date])) ? $data_revenue[$date] : 0) + $val;
		}
		$data_profit ='';
		foreach ($profit as $created => $val) {
			$created = trim($created);
			$date = substr( $created, 8, strlen($created)-17);
			$data_profit[$date] = ((isset($data_profit[$date])) ? $data_profit[$date] : 0) + $val;
		}
		$time = getdate();
		$y = $time['year'];
		$m = $time['mon'];
		$js_revenue = '';
		$js_profit = '';
		$lastDate = date("t", strtotime(date('Y-m-d'))) ;
		for ($i = 1; $i <= $lastDate; $i++) {
			$i = ($i>9) ? $i : '0'.$i;
			$revenue = (isset($data_revenue[$i])) ? $data_revenue[$i] : 0;
			$js_revenue = $js_revenue.'[gd('.$y.', '.$m.', '.$i.'), '.$revenue.'], ';

			$profit = (isset($data_profit[$i])) ? $data_profit[$i] : 0;
			$js_profit = $js_profit.'[gd('.$y.', '.$m.', '.$i.'), '.$profit.'], ';

		}

		$js_revenue = '['.$js_revenue.']';
		$js_profit = '['.$js_profit.']';
		return array('profit' => $js_profit, 'revenue' => $js_revenue);

		// pre($giaTriTon,1);
		// pre($soLuongTon,1);
		// pre($average_price,1);
		// pre($profit);
	}
}
if(!function_exists('str_duplicate')){
	function str_duplicate($param = ''){
		$CI =& get_instance();
		$field = (!empty($param['field'])) ? $param['field'] : 'title';
		$value = $param['value'];
		$count_duplicate = $CI->Autoload_Model->_get_where(array(
			'table' => 'product',
			'where' => array($field => $value),
			'count' => true
		));
		if($count_duplicate >= 1){
			$value = $value.'(1)';
			str_duplicate(array('field' => $field, 'value' => $value ));
		}
		return $value;
	}
}
if(!function_exists('comment')){
	function comment($param = ''){
		$CI =& get_instance();
		$CI->load->helper(array('mycomment'));
		//thống kê bình luận và đánh giá
		//đánh giá:
		//	+ totalComment
		//	+ arrayRate
		//	+ averagePoint


		$data['statisticalRating'] = statistical_rating(array(
			'table' => 'comment',
			'module' => $param['module'],
			'detailid' => $param['id'],
		));

		// pre($data['statisticalRating']);die;

		//bình luận
		$array = array(
			'module' => $param['module'],
			'detailid' => $param['id'],
		);
		$data['listComment'] = comment_render($array);

		//xem thêm bình luận
		$data['module'] = $param['module'];
		$data['detailid'] = $param['id'];
		return $data;
	}
}
//trả về: mảng dữ liệu lấy từ bảng promotional_relationship vs ID sản phẩm
//đầu vào: mảng thông tin sản phẩm với thêm giá khuyến mại ( price_promo), thười gian KM ( time_promo), sản phẩm thặng kèm( prd_gift)

if(!function_exists('checkPromo')){
	function checkPromo($list_promo_rela = [], $id = ''){
		$CI =& get_instance();
		$promo_rela_common = [];
		$promo_rela_not_common = [];
		$promo_rela_action = [];
		if(isset($list_promo_rela) && check_array($list_promo_rela)){
			foreach ($list_promo_rela as $key => $promo_rela) {
				$getpromo = json_decode(getpromotional($promo_rela), true);
				$promo_rela['detail'] = $getpromo['detail'];
				if($promo_rela['use_common'] == 1){
					$promo_rela_common[] = $promo_rela;
				}else{
					$promo_rela_not_common[] = $promo_rela;
				}
			};
			if(count($promo_rela_common) >= 1){
				$promo_rela_action = $promo_rela_common;
			}else{
				$promo_rela_action[] = (isset($promo_rela_not_common[0])) ? $promo_rela_not_common[0] : '' ;
			}
		}
		// Nếu không có CTKm thì kiểm tra xem có ctkm nào thêm mới ko
		if(!isset($promo_rela_action) || !is_array($promo_rela_action) || count($promo_rela_action) == 0 ||  $promo_rela_action == array( 0 => '' ) ||  $promo_rela_action == array() ){

			$time = gmdate('Y-m-d', time() + 7*3600);
	        $time = $time.' 00:00:00';
	        $query = ' (( end_date = "0000-00-00 00:00:00" ) || ( start_date >= "'.$time.' " AND end_date <= "'.$time.'"))';
			$list_promo_rela_new = $CI->Autoload_Model->_get_where(array(
				'select' => 'id, discount_value, discount_type, discount_moduleid, use_common, promotionalid, condition_type, condition_value, condition_type_1, condition_value_1, module, freeship, freeshipAll, cityid, moduleid, start_date, end_date',
				'table' => 'promotional_relationship',
				'where' => array('moduleid' => $id, 'module' => 'product'),
				'query' => $query,
			), true);
			checkPromo($list_promo_rela_new, $id);
		}
		return $promo_rela_action;
	}
}
if(!function_exists('resultArrayCommonPromo')){
	function resultArrayCommonPromo($promo){
		$block_promo = '';
		$use_common = '';
		if(isset($promo) && is_array($promo) && count($promo)){
			foreach ($promo as $keyPromo => $valPromo) {
				$use_common[$valPromo['promotionalid']] = $valPromo['use_common'];
			}
			if(isset($use_common) && is_array($use_common) && count($use_common)){
				foreach ($use_common as $keyUse => $valUse) {
					$block_promo[$valUse][] = $keyUse;
				};
			}
		}
	}
}

//trả về: mảng danh mục thuộc tính => thuộc tính để in ra frontend
//đầu vào: chuối json trong database
if(!function_exists('getListAttr')){
	function getListAttr($attrid){
		$CI = & get_instance();
		$attribute = '';
		// lấy ra danh sách thuộc tính
		$list_attrid = json_decode($attrid, true);

		if(isset($list_attrid) && is_array($list_attrid) && count($list_attrid)){
			$list_attr_cata = $CI->Autoload_Model->_get_where(array(
				'select' => 'id, title, keyword',
				'table' => 'attribute_catalogue',
				'where_in' => array_keys($list_attrid),
			),true);

			$list_attribute = [];
			foreach ($list_attrid as $key => $value) {
				$list_attribute = array_merge($list_attribute, $value);
			}
			$list_attr= $CI->Autoload_Model->_get_where(array(
				'select' => 'id, title',
				'table' => 'attribute',
				'where_in' => $list_attribute,
			),true);
			foreach ($list_attrid as $key => $value) {
				foreach ($list_attr_cata as $sub => $subs) {
					foreach ($list_attr as $item => $items) {
						foreach ($value as $u => $k) {
							if( $key == $subs['id'] && $k == $items['id'] ){
								$attribute[$subs['title']]['keyword_cata'] = $subs['keyword'];
								$attribute[$subs['title']][$items['id']] = $items['title'];
							}
						}
					}
				}
			}
		}
		return $attribute;
	}
}

//trả về: giá cũ( giá bị gạch đi )
//đầu vào: mảng các loại giá
if(!function_exists('getPriceOld')){
	function getPriceOld($detail_prd){
		if(isset($detail_prd['price_version'])){
			return $detail_prd['price_version'];
		}
		if(isset($detail_prd['price_wholesale'])){
			return $detail_prd['price_wholesale'];
		}
		if(isset($detail_prd['price']) ){
			return $detail_prd['price'];
		}
		if(isset($detail_prd['price_sale']) && $detail_prd['price_sale'] != '' && $detail_prd['price_sale'] != 0){
			return $detail_prd['price_sale'];
		}
		if(isset($detail_prd['price_promo'])){
			return $detail_prd['price_promo'];
		}
	}
}
//trả về: giá mới( giá cuối cùng )
//đầu vào: mảng các loại giá
if(!function_exists('getPriceFinal')){
	function getPriceFinal($detail_prd, $coupon = false){
		if($coupon == true){
			if(isset($detail_prd['price_coupon']) && $detail_prd['price_coupon'] != '' && $detail_prd['price_coupon'] != 0){
				return $detail_prd['price_coupon'];
			}
		}
		if(isset($detail_prd['price_sale']) && $detail_prd['price_sale'] != '' && $detail_prd['price_sale'] != 0){
			return $detail_prd['price_sale'];
		}
		if(isset($detail_prd['price_wholesale'])){
			return $detail_prd['price_wholesale'];
		}
		if(isset($detail_prd['price_promo'])){
			return $detail_prd['price_promo'];
		}
		if(isset($detail_prd['price_version'])){
			return $detail_prd['price_version'];
		}
		if(isset($detail_prd['price']) ){
			return $detail_prd['price'];
		}
	}
}
//trả về: true nếu đầu vào là mảng
//đầu vào:
if(!function_exists('check_array')){
	function check_array($array){
		if(isset($array) && is_array($array) && count($array)){
			return true;
		}else{
			return false;
		}
	}
}
//trả về: sô điện thoại đã thêm khoảng trắng( chỉ tác dụng với sđt có 9 10 số )
//đầu vào:
if(!function_exists('number_phone')){
	function number_phone($number){
		$length = strlen($number);
		// return $number = preg_replace("/(.*)(\d{3})(\d{3})(\d{3})$/", "$1 $2 $3 $4", $number);
		if($length == 10){
			$number = preg_replace("/(\d{4})(\d{3})(\d{3})$/", "$1 $2 $3 $4", $number);
		}elseif($length == 9){
			$number = preg_replace("/(\d{3})(\d{3})(\d{3})$/", "$1 $2 $3 $4", $number);
		}
		return $number;
	}
}
//trả về: mảng đã được sắp xếp mảng con theo field( như name, id)
//đầu vào:
// array(
// 	'0' => array(
// 		'id' => 1,
// 		'name'=> sp1,
// 	),
// 	'1' => array(
// 		'id' => 2,
// 		'name'=> sp2,
// 	)
// )
function aasort (&$array, $key) {
    $sorter=array();
    $ret=array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii]=$va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii]=$array[$ii];
    }
    $array=$ret;
    return $array;
}

//trả về: mảng ko bị trùng lặp các mảng con bên trong( loại trừ trường field)
//đầu vào:
// array(
// 	'0' => array(
// 		'id' => 1,
// 		'name'=> sp1,
// 	),
// 	'1' => array(
// 		'id' => 2,
// 		'name'=> sp2,
// 	)
// )
if(!function_exists('arrays_unique')){
	function arrays_unique($param = '', $field = ''){
		if(isset($param) && is_array($param) && count($param)){
			foreach ($param as $key => $value) {
				$index = 0;
				foreach ($param as $sub => $subs) {
					if( $field != '' ){
						unset($value[$field]);
						unset($subs[$field]);
					}
					if($subs == $value){
						$index =$index + 1;
					}
				}
				if($index >= 2){
					unset($param[$key]);
				}
			}
		}
		return $param ;
	}
}
//trả về:  trả về mảng danh sách field
//đầu vào:
// array(
// 	'0' => array(
// 		'id' => 1,
// 		'name'=> sp1,
// 	),
// 	'1' => array(
// 		'id' => 2,
// 		'name'=> sp2,
// 	)
// )
//
function getColumsInArray($data=array(), $field= 'id' ){
    if(empty($field) || empty($data) ){
        return false ;
    }
    if(isset($data) && is_array($data) && count($data)){
    	foreach ($data as $key => $val) {
    		if(isset($val[$field])){
	    		$result[] = $val[$field];
    		}
    	}
    }
    return (isset($result)) ? $result : '' ;
}

//trả về: xóa commnet HTML
//đầu vào: $content
if(!function_exists('remove_html_comments')){
	function remove_html_comments($content = '') {
		return preg_replace('/<!--(.|\s)*?-->/', '', $content);
	}
}
//trả về: lấy thông tin ct khuyến mại, truyền vào là detailPromo
//đầu vào: thông tin $detailPromo lấy từ CSDL
if(!function_exists('getPromotional')){
	function getPromotional( $detailPromo = ''){
		$CI =& get_instance();
		$discount = '';
		if(isset($detailPromo)&& check_array($detailPromo) && $detailPromo['discount_type'] != ''){
			if($detailPromo['discount_type'] == 'object'){
				$ids =json_decode($detailPromo['discount_moduleid'], true);
				$module = $CI->Autoload_Model->_get_where(array(
					'table' => $detailPromo['module'],
					'where_in' => $ids,
					'where_in_field' => 'id',
					'select' => 'title, code, id',
				), true);
				foreach ($module as $sub => $value) {
					$discount = $discount.', <a style="color:orange"  href="'.site_url($detailPromo['module'].'/backend/'.$detailPromo['module'].'/update/'.$value['id']).' " target="_blank" > '. $value['title'].'(mã: '.$value['code'].')</a>';
				};
				$discount = substr( $discount,  2, strlen($discount));
				$discount = 'Giảm giá: '.$detailPromo['discount_value']. ' % '. $discount;
			}elseif($detailPromo['discount_type'] == 'ship'){
				if($detailPromo['freeship'] == 1){
					$cityid =json_decode($detailPromo['cityid'], true);
					$city = $CI->Autoload_Model->_get_where(array(
						'table' => 'vn_province',
						'where_in' => $cityid,
						'where_in_field' => 'provinceid',
						'select' => 'name',
					), true);
					foreach ($city as $sub => $value) {
						$discount = $discount .', '. $value['name'];
					};
					$discount = substr( $discount,  2, strlen($discount));
					$discount = "Freeship cho: ".$discount;
				}elseif($detailPromo['freeshipAll'] == 1){
					$discount = "Freeship toàn quốc";
				}else{
					$discount = 'Giảm giá ship: '.addCommas($detailPromo['discount_value']);
				}
			}else{
				$discount_type = array(
					'price' => 'VNĐ',
					'percent' => '%',
					'same' => 'Đồng giá',
					'ship' => 'Phí vận chuyển',
					'object' => 'Tặng đối tượng',
				);
				$discount = 'Giảm: '.addCommas($detailPromo['discount_value']).' '.$discount_type[$detailPromo['discount_type']];
			};


			if($detailPromo['condition_type'] == 'condition_moduleid'){
				$ids =json_decode($detailPromo['condition_value'], true);
				$module = $CI->Autoload_Model->_get_where(array(
					'table' => $detailPromo['module'],
					'where_in' => $ids,
					'where_in_field' => 'id',
					'select' => 'title, code',
				), true);
				$for = '';
				foreach ($module as $sub => $value) {
					$for = $for .', '. $value['title'].'(mã: '.$value['code'].')';
				};
				$for = substr( $for,  2, strlen($for));
				$for = 'áp dụng cho sp: '.$for;
			}elseif($detailPromo['condition_type'] == 'condition_module_catalogue'){
				$ids =json_decode($detailPromo['condition_value'], true);
				$module = $CI->Autoload_Model->_get_where(array(
					'table' => $detailPromo['module'].'_catalogue',
					'where_in' => $ids,
					'where_in_field' => 'id',
					'select' => 'title',
				), true);
				$for = '';
				foreach ($module as $sub => $value) {
					$for = $for .', '. $value['title'];
				};
				$for = substr( $for,  2, strlen($for));
				$for = 'áp dụng cho nhóm: '.$for;
			}else{
				$for = 'áp dụng tất cả sản phẩm';
			}


			$when ='';
			if($detailPromo['condition_type_1'] == 'condition_quantity'){
				if($detailPromo['condition_value_1'] > 0){
					$when = $when.' khi mua số lượng từ '.$detailPromo['condition_value_1'].' trở lên';
				}
			}else{
				if($detailPromo['condition_value_1'] > 0){
					$when = $when.' khi giá trị đơn hàng từ '.$detailPromo['condition_value_1'].' trở lên';
				}
			}
			$detail = $discount.' '.$for.' '.$when;


			$use_common ='';
			if($detailPromo['use_common'] == '1'){
				$use_common = $use_common.'Cho phép sử dụng chung với chương trình khuyến mại khác';
			}else{
				$use_common = $use_common.'Không cho phép sử dụng chung với chương trình khuyến mại khác';
			}
			$time_promo ='';
			if($detailPromo['end_date'] == '0000-00-00 00:00:00'){
				$time_promo = 'Đang trong t/g khuyến mại';
			}else{
				$time_promo = 'Từ '.gettime($detailPromo['start_date'], 'd-m').' đến '.gettime($detailPromo['end_date'], 'd-m');
			}
		}
		return json_encode(array(
			'detail' => isset($detail) ? $detail : '',
			'use_common' => isset($use_common) ? $use_common : '',
			'time_promo' => isset($time_promo) ? $time_promo : '',
		));
	}
}

//trả về: cập nhật lại số lượng ở trường field
//đầu vào:
// $param['module']  bảng
// $param['where']  điều kiện lấy ra row đó
// $param['select']  trường muốn cộng trừ số lượng
if(!function_exists('updateInt')){
	function updateInt( $param = ''){
		$CI =& get_instance();
		$param['minus'] = (isset($param['minus'])) ? $param['minus'] : 0;
		$param['plus'] = (isset($param['plus'])) ? $param['plus'] : 0;
		$object = $CI->Autoload_Model->_get_where(array(
			'table' => $param['module'],
			'where'=> $param['where'],
			'select'=> $param['field'],
		));
		if(isset($object) && is_array($object) && count($object)){
			$field = explode(',', $param['field']);
			foreach ($field as $key => $value) {
				$_update[$value] = (int)$object[trim($value)] + (int)str_replace('.','',$param['plus']) - (int)str_replace('.','',$param['minus']);
			};
			$_update['updated'] =  gmdate('Y-m-d H:i:s', time() + 7*3600);
			$CI->Autoload_Model->_update(array(
				'data' => $_update,
				'table' =>$param['module'],
				'where' => $param['where'],
			));

		}else{
			foreach ($param['where'] as $index => $val) {
				$_insert[$index] = $val;
			}
			$field = explode(',', $param['field']);
			foreach ($field as $key => $value) {
				$_insert[$value] = (int)str_replace('.','',$param['plus']) - (int)str_replace('.','',$param['minus']);
			};
			$_insert['created'] =  gmdate('Y-m-d H:i:s', time() + 7*3600);
			$CI->Autoload_Model->_create(array(
				'data' => $_insert,
				'table' =>$param['module'],
			));
		}

	}
}
//trả về: mã code cuối cùng trong module công thêm 1
//đầu vào: tên module
if(!function_exists('CodeRender')){
	function CodeRender($module = ''){
		$CI =& get_instance();
		$lastId = lastId($module);
		switch ($module) {
		    case "import":
		        $data = CODE_IMPORT.str_pad($lastId, 4, '0', STR_PAD_LEFT);
		        break;
		    case "construction":
		        $data = CODE_CONSTRUCTION.str_pad($lastId, 4, '0', STR_PAD_LEFT);
		        break;
	       	case "export":
		        $data = CODE_EXPORT.str_pad($lastId, 4, '0', STR_PAD_LEFT);
		        break;
		    case "product":
		        $data = CODE_PRODUCT.str_pad($lastId, 4, '0', STR_PAD_LEFT);
		        break;
		    case "supplier":
		        $data = CODE_SUPPLIER.str_pad($lastId, 4, '0', STR_PAD_LEFT);
		        break;
		    case "order":
		        $data = CODE_ORDER.str_pad($lastId, 4, '0', STR_PAD_LEFT);
		        break;
		}
		return $data;
	}
}
//trả về: TRUE FALSE, thêm dữ liệu vào bảng canonical
//đầu vào:
// $param['canonical'] đường dẫn canonical muốn thêm vào
// $param['resultid'] id của sản phẩm hay bài viết,... truyền vào
if(!function_exists('createCanonical')){
	function createCanonical($param){
		$CI =& get_instance();
		if( !empty($param['canonical']) && !empty($param['module'])){
			$module = explode('_', $param['module']);
			if(count($module) == 1){
				$module1 = $module[0];
				$module2 = $module[0];
			}else{
				$module1 = $module[0];
				$module2 = $module[1];
			}
			$router = array(
				'canonical' => $param['canonical'],
				'crc32' => sprintf("%u", crc32($param['canonical'])),
				'uri' => $module1.'/frontend/'.$module2.'/view',
				'param' => $param['resultid'],
				'type' => 'number',
				'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
			);
			$routerid = $CI->Autoload_Model->_create(array(
				'table' => 'router',
				'data' => $router,
			));
			if($routerid > 0){
				return true;
			}
		}
		return false;
	}
}
//trả về: TRUE FALSE, thêm dữ liệu vào bảng CatalogueRelationship
//đầu vào:
// $param['module'] module
// $param['moduleid'] moduleid
// $param['catalogue'] id của sản phẩm hay bài viết,... truyền vào
if(!function_exists('createCatalogueRelationship')){
	function createCatalogue_relationship($param = ''){
		$CI =& get_instance();
		$_catalogue_relation_ship[] = array(
			'module' => $param['module'],
			'moduleid' => $param['resultid'],
			'catalogueid' => $param['catalogueid'],
			'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
		);
		if(isset($param['catalogue']) && is_array($param['catalogue']) && count($param['catalogue'])){
			foreach($param['catalogue'] as $key => $val){
				if($val == $param['catalogueid']) continue;
				$_catalogue_relation_ship[] = array(
					'module' => $param['module'],
					'moduleid' => $param['resultid'],
					'catalogueid' => $val,
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);
			}
		}
		if(isset($_catalogue_relation_ship) && check_array($_catalogue_relation_ship)){
			$CI->Autoload_Model->_create_batch(array(
				'table' => 'catalogue_relationship',
				'data' => $_catalogue_relation_ship,
			));
		}
	}
}
// thêm thuộc tính vào bảng createModule_relationship
// -------------------------------------- --------------------------------------

if(!function_exists('createModule_relationship')){
	function createModule_relationship( $param = '' ){
		$CI = & get_instance();
		if(isset($param['data']) && is_array($param['data']) && count($param['data']) && $param['data']!=array('0'=>0)){
			foreach ($param['data'] as $key => $val) {
				if(isset($val) && is_array($val) && count($val) && $val!=array('0'=>0)){
					foreach ($val as $sub => $subs) {
						$_insert_attribute[] = array(
							'moduleid' => $param['moduleid'],
							$param['field'] => $subs,
							'module' => $param['module'],
							'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
						);
					}
				}else{
					$_insert_attribute[] = array(
						'moduleid' => $param['moduleid'],
						$param['field'] => $val,
						'module' => $param['module'],
						'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
					);
				}
			}
			if(check_array($_insert_attribute)){
				$CI->Autoload_Model->_create_batch(array(
					'table' => $param['table'],
					'data' => $_insert_attribute,
				));
			}
		}
	}
}
//trả về: kiểm tra $data là '' thì sẽ trả về 0
//đầu vào: $data
if(!function_exists('is0')){
	function is0($data){
		return (!empty($data))?$data:0;
	}
}
if(!function_exists('is')){
	function is($data){
		return (isset($data))? $data :'';
	}
}

//trả về: như hàm number_format
//đầu vào: $data
if(!function_exists('addCommas')){
	function addCommas($data){
		if(isset($data) && $data !='' && $data !=0){
			$data = replace($data);
			return number_format($data,'0',',','.');
		}
		return 0;
	}
}
//trả về: chuỗi bị cắt từ 0 tới kí tự thứ n
//đầu vào: $str chuỗi bị cắt, $n cắt bn kí tự
if(!function_exists('cutnchar')){
	function cutnchar($str = NULL, $n = 320){
		if(strlen($str) < $n) return $str;
		$html = substr($str, 0, $n);
		$html = substr($html, 0, strrpos($html,' '));
		return $html.'...';
	}
}

if(!function_exists('getLocation')){
	function getLocation($param = ''){
		$CI =& get_instance();
		if(!isset($param['where'])){
			$param['where'] = '';
		}
		$cityList = $CI->Autoload_Model->_get_where(array(
			'select' => $param['select'],
			'table' => $param['table'],
			'where' => $param['where'],
			'order_by' => 'name asc'
		), TRUE);

		$temp[0] = $param['text'];
		if(isset($cityList) && is_array($cityList) && count($cityList)){
			foreach($cityList as $key => $val){
				$temp[$val[$param['field']]] = $val['name'];
			}
		}
		return $temp;
	}
}

if(!function_exists('gettime')){
	function gettime($time, $type = 'H:i - d/m/Y'){
		return gmdate($type, strtotime($time) + 7*3600);
	}
}

if(!function_exists('pre')){
	function pre($list, $exit = 'die'){
	    echo "<pre>";
	    print_r($list);
	    if($exit == 'die')
	    {
	        die();
	    }
	}
}



if(!function_exists('getthumb')){
	function getthumb($image = '', $thumb = TRUE){
		$image = !empty($image)?$image:'template/not-found.png';
		if(!file_exists(dirname(dirname(dirname(__FILE__))).$image) ){
			$image = 'template/not-found.png';
		}
		if($thumb == TRUE){
			$image_thumb = str_replace('/upload/images/', '/upload/.thumbs/images/', $image);
			if (file_exists(dirname(dirname(dirname(__FILE__))).$image_thumb)){
				return $image_thumb;
			}
		}
		return $image;
	}
}


if(!function_exists('convert_time')){
	function convert_time($time = '', $type = '/'){
		if($time == ''){
			return '0000-00-00 00:00:00';
		}
		$current = explode($type, $time);
		$time_stamp = $current[2].'-'.$current[1].'-'.$current[0].' 00:00:00';
		return $time_stamp;
	}
}


if(!function_exists('removeutf8')){
	function removeutf8($value = NULL){
		$chars = array(
			'a'	=>	array('ấ','ầ','ẩ','ẫ','ậ','Ấ','Ầ','Ẩ','Ẫ','Ậ','ắ','ằ','ẳ','ẵ','ặ','Ắ','Ằ','Ẳ','Ẵ','Ặ','á','à','ả','ã','ạ','â','ă','Á','À','Ả','Ã','Ạ','Â','Ă'),
			'e' =>	array('ế','ề','ể','ễ','ệ','Ế','Ề','Ể','Ễ','Ệ','é','è','ẻ','ẽ','ẹ','ê','É','È','Ẻ','Ẽ','Ẹ','Ê'),
			'i'	=>	array('í','ì','ỉ','ĩ','ị','Í','Ì','Ỉ','Ĩ','Ị'),
			'o'	=>	array('ố','ồ','ổ','ỗ','ộ','Ố','Ồ','Ổ','Ô','Ộ','ớ','ờ','ở','ỡ','ợ','Ớ','Ờ','Ở','Ỡ','Ợ','ó','ò','ỏ','õ','ọ','ô','ơ','Ó','Ò','Ỏ','Õ','Ọ','Ô','Ơ'),
			'u'	=>	array('ứ','ừ','ử','ữ','ự','Ứ','Ừ','Ử','Ữ','Ự','ú','ù','ủ','ũ','ụ','ư','Ú','Ù','Ủ','Ũ','Ụ','Ư'),
			'y'	=>	array('ý','ỳ','ỷ','ỹ','ỵ','Ý','Ỳ','Ỷ','Ỹ','Ỵ'),
			'd'	=>	array('đ','Đ'),
		);
		foreach ($chars as $key => $arr)
			foreach ($arr as $val)
				$value = str_replace($val, $key, $value);
		return $value;
	}
}

if(!function_exists('slug')){
	function slug($value = NULL){
		$value = removeutf8($value);
		$value = str_replace('-', ' ', trim($value));
		$value = preg_replace('/[^a-z0-9-]+/i', ' ', $value);
		$value = trim(preg_replace('/\s\s+/', ' ', $value));
		return strtolower(str_replace(' ', '-', trim($value)));
	}
}
//trả về: mảng $param['field'] => $param['value']
//đầu vào: các giá trị trong hàm _get_where
if(!function_exists('dropdown')){
	function dropdown($param = ''){
		$CI =& get_instance();
		$order_by = (isset($param['order_by']))?$param['order_by'] : 'id' ;
		$query = (isset($param['query']))?$param['query'] : '' ;
		$select = (isset($param['select']))?$param['select'] : 'id, title' ;
		$field = (isset($param['field']))?$param['field'] : 'id' ;
		$value = (isset($param['value']))?$param['value'] : 'title' ;
		$temp = [];
		// $field = explode(',', $param['field']);
		$data = $CI->Autoload_Model->_get_where(array(
			'select' => $select,
			'table' => $param['table'],
			'order_by' => $order_by,
			'query' => $query,
		), TRUE);
		if(!empty($param['text'])){
			$temp[0] = $param['text'];
		}
		if(isset($data) && is_array($data) && count($data)){
			foreach($data as $key => $val){
				$temp[$val[$field]] = $val[$value];
			}
		}
		return $temp;
	}
}
// trả về: ID cuối cùng của module  + 1
// đầu vào: là tên module
if(!function_exists('lastId')){
	function lastId($module = ''){
		$CI =& get_instance();
		$lastRow = $CI->Autoload_Model->_get_where(array(
			'table'=>$module,
			'select'=>'id',
			'order_by'=>'id DESC',
		));
		return (int)$lastRow['id'] + 1;
	}
}


if(!function_exists('get_list')){
	function get_list($param =''){
		$CI =& get_instance();
		$list_data = $CI->Autoload_Model->_get_where(array(
			'select' => $param['select'],
			'table' => $param['table'],
			'order_by' =>  $param['order_by'],
		), TRUE);
		$team ='';
		if(!empty($list_data)){
			$team[0] = $param['text'];
		}
		if(isset($list_data) && is_array($list_data) && count($list_data)){
			foreach($list_data as $key => $val){
				$team[$val[$param['field']]] = $val[$param['value']];
			}
		}
		return $team;
	};
}


if(!function_exists('getQuerySelectByModule')){
	function getQuerySelectByModule($module = 'product'){
		// lấy những thông tin chung
		$query_select = '`object`.`id`, `object`.`title`, `object`.`slug`, `object`.`canonical`, `object`.`image`,`object`.`description`,`object`.`created` ,`object`.`updated`';
		$query_select .= (($module == 'article_catalogue' || $module == 'article' || $module == 'product_catalogue' || $module == 'product') ? ', `object`.`landing_link`' : '');

		$query_select .= (($module == 'article_catalogue') ? ', `object`.`url_view`, `object`.`excerpt`' : '');
		$query_select .= (($module == 'article') ? ', `object`.`url_view`, `object`.`extend_description`, `object`.`excerpt`' : '');
		$query_select .= (($module == 'product_catalogue') ? ', `object`.`landing_link`' : '');
		$query_select .= (($module == 'product') ? ', `object`.`price`, `object`.`price_sale`, `object`.`price_contact`' : '');
		$query_select .= (($module == 'media') ? ', `object`.`image_json`, `object`.`video_link`, `object`.`video_iframe`' : '');

		return $query_select;
	}
}

if(!function_exists('layout_control')){
	function layout_control($param = '', $flag = FALSE){
		$CI =& get_instance();
		$layout = $CI->Autoload_Model->_get_where(array(
			'select' => 'data_json',
			'table' => 'layout',
			'where' => array('id' => $param['layoutid']),
		));


		$jsonData = json_decode($layout['data_json'], TRUE);
		if(isset($jsonData) && is_array($jsonData) && count($jsonData)){
			foreach($jsonData as $key => $val){
				$module = explode('_', $val['module']);

				$query_select = getQuerySelectByModule($val['module']);

				$jsonData[$key][(count($module) == 1) ? 'post'  : 'catalogue'] = $CI->Autoload_Model->_get_where(array(
					'select' => $query_select,
					'table' => $val['module'].' as object',
					'where' => array('publish' => 0),
					'where_in' => $val['object'],
					'where_in_field' => 'id',
					'order_by' => 'order desc, title asc, id desc')
				,TRUE);
			}
		}
		if(isset($param['children']['flag']) && $param['children']['flag'] == TRUE){
			if(isset($jsonData) && is_array($jsonData) && count($jsonData)){
				foreach($jsonData as $key => $val){
					if(isset($val['catalogue']) && is_array($val['catalogue']) && count($val['catalogue'])){
						foreach($val['catalogue'] as $keyChildren => $valChildren){
							$jsonData[$key]['catalogue'][$keyChildren]['children'] =  $CI->Autoload_Model->_get_where(array(
								'select' => 'id, title, slug, canonical, image, description, icon, landing_link',
								'table' => $val['module'],
								'where' => array('publish' => 0,'parentid' => $valChildren['id']),
								// 'limit' => 4,
								'order_by' => 'order desc, id desc')
							,TRUE);
						}
					}
				}
				if(isset($param['children']['post']) && $param['children']['post'] == TRUE){
					foreach($jsonData as $key => $val){
						$module = current(explode('_', $val['module']));

						$query_select = getQuerySelectByModule($module);


						if(isset($val['catalogue']) && is_array($val['catalogue']) && count($val['catalogue'])){
							foreach($val['catalogue'] as $keyChildren => $valChildren){
								if(isset($valChildren['children']) && is_array($valChildren['children']) && count($valChildren['children'])){
									foreach($valChildren['children'] as $keyPost => $valPost){
										$jsonData[$key]['catalogue'][$keyChildren]['children'][$keyPost]['post'] =  $CI->Autoload_Model->_condition(array(
											'module' => $module,
											'select' => $query_select,
											'limit' => $param['children']['limit'],
											'order_by' => '`object`.`order` desc, `object`.`id` desc',
											'catalogueid' => $valPost['id']
										));
									}
								}
							}
						}
					}
				}
			}
		}

		if(isset($param['post']['flag']) && $param['post']['flag'] == TRUE ){

			if(isset($jsonData) && is_array($jsonData) && count($jsonData)){
				foreach($jsonData as $key => $val){
					$module = current(explode('_', $val['module']));

					$query_select = getQuerySelectByModule($module);

					if(isset($val['catalogue']) && is_array($val['catalogue']) && count($val['catalogue'])){
						foreach($val['catalogue'] as $keyChildren => $valChildren){
							$jsonData[$key]['catalogue'][$keyChildren]['post'] =  $CI->Autoload_Model->_condition(array(
								'select' => $query_select,
								'module' => $module,
								'limit' => $param['post']['limit'],
								'order_by' => '`object`.`order` desc, `object`.`id` desc',
								'catalogueid' => $valChildren['id'],
							));
						}
					}
				}
			}
		}

		return (isset($flag) && $flag == TRUE) ? $jsonData : $jsonData[0];
	}
}
