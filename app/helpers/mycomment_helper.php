<?php 

function getMuls($param){
	if(isset($param) && is_array($param) && count($param)){
		$mul = 1;
		foreach ($param as $key => $val) {
			$mul = $mul * $val;
		}
		$temp = [];
		foreach ($param as $key => $val) {
			$temp[$key] = $mul / $val;
		}
		return $temp;
	}
	return false;
}

if(!function_exists('review_render')){
	function review_render($rate = 0){
		$CI =& get_instance();
		$data = array('-', 'Rất kém' , 'Kém', 'Bình thường', 'Tốt', 'Rất tốt');
		return $data[$rate];
	}
}

//thống kê đánh giá
if(!function_exists('statistical_rating')){
	function statistical_rating($param = ''){
		$CI =& get_instance();
		
		//tính tổng số bản ghi có trong chi tiết module
		$totalRows = $CI->Autoload_Model->_get_where(array(
			'select' => 'id, module, detailid, rate',
			'table' => $param['table'],
			'where' => array(
				'module' => $param['module'],
				'detailid' => $param['detailid'],
				'parentid' => 0,
			),
		), true);
		
		
		$data = [];
		$data['totalComment'] = count($totalRows); // đếm số bản ghi
		
		//nếu có comment
		if($data['totalComment'] > 0){
			$data['arrayRate'] = recursive_number($totalRows);
			
			$sum = 0;
			if(isset($data['arrayRate']) && is_array($data['arrayRate']) && count($data['arrayRate'])){
				foreach($data['arrayRate'] as $key => $val){
					$sum += $key*$val;
				}
			}
			//tính điểm TB = tổng điểm / số cmt
				$data['averagePoint'] = round($sum/$data['totalComment'] , 1, PHP_ROUND_HALF_UP);
		}else{
			//không có cmt
			$data['arrayRate'] = array(
				5 => 0,
				4 => 0,
				3 => 0,
				2 => 0,
				1 => 0,
			);
			$data['averagePoint'] = 0;
		}
		
		// pre($data['arrayRate']);
		
		return $data;
	}
}

if(!function_exists('recursive_number')){
	function recursive_number($data = [] , $number = 5 , $result = []){
		//đếm all các giá trị = $count
		$count = 0;
		if(isset($data) && is_array($data) && count($data)){
			foreach($data as $key => $val){
				if($val['rate'] == $number){
					$count++;
				}
			}
			$result[$number] = $count;
			if($number > 1){
				$result = recursive_number($data , $number - 1, $result);
			}
		}
		return $result;
	}
}

if(!function_exists('my_recursive')){
	function my_recursive($data = '' , $parentid = 0, $result = ''){
		if(isset($data) && is_array($data) && count($data)){
			foreach($data as $key => $val){
				if($val['parentid'] == $parentid){
					unset($data[$key]);
					if($parentid > 0){
						$result['child'][] = my_recursive($data , $val['id'] , $val);
					}else{
						$result[] = my_recursive($data , $val['id'] , $val);
					}
				}
			}
		}
		return $result;
	}
}


if(!function_exists('comment_render')){
	function comment_render($param = ''){
		$CI =& get_instance();
		//lấy toàn bộ comment
		$listComment = $CI->Autoload_Model->_get_where(array(
			'select' => 'id, fullname, comment, image, rate, module, detailid, parentid, created, updated',
			'table' => 'comment',
			'where' => array(
				'module' => $param['module'],
				'detailid' => $param['detailid'],
				'publish' => 1,
				'parentid' => 0,
			),
			'limit' => isset($param['limit'])? $param['limit']: 2,
			'start' => isset($param['start'])? $param['start']: 0,
			'order_by' => 'id desc',
		), true);
		
		if(isset($listComment) && is_array($listComment) && count($listComment)){
			foreach($listComment as $key => $val){
				$listComment[$key]['child'] = $CI->Autoload_Model->_get_where(array(
					'select' => 'id, fullname, comment, image, rate, module, detailid, parentid, created, updated',
					'table' => 'comment',
					'where' => array(
						'module' => $param['module'],
						'detailid' => $param['detailid'],
						'publish' => 1,
						'parentid' => $val['id'],
					),
					'order_by' => 'id desc',
				), true);
			}
			
		}
		return $listComment;
	}
}


?>
