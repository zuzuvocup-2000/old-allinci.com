<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ConfigBie{
	
	function __construct($params = NULL){
		$this->params = $params;
	}

	public function data($field = 'process', $value = -1){
		$data['publish'] = array(
			-1 => 'Chọn trạng thái hiển thị',
			0 => 'Không hiến thị',
			1 => 'Đang hiển thị',
		);
		$data['amp'] = array(
			0 => 'Không sử dụng AMP',
			1 => 'Có sử dụng AMP',
		);
		$data['perpage'] = array(
			20 => '20 bản ghi',
			30 => '30 bản ghi',
			40 => '40 bản ghi',
			50 => '50 bản ghi',
			60 => '60 bản ghi',
			70 => '70 bản ghi',
			80 => '80 bản ghi',
			90 => '90 bản ghi',
			100 => '100 bản ghi',
		);
		$data['module'] = array(
			'product' => 'sản phẩm',
			'land' => 'bất động sản',
			'tour' => 'tour',
			'hotel' => 'khách sạn',
		);
		$data['catalogue'] = array(
			'CP' => 'Mã khuyến mại ( Coupon )',
			'KM' => 'Chương trình khuyến mại',
		);
		$data['discount_type'] = array(
			'price' => 'VNĐ',
			'percent' => '%',
			'same' => 'Đồng giá',
			'ship' => 'Phí vận chuyển',
			'object' => 'Tặng đối tượng',
		);
		
		$data['condition_type'] = array(
			'condition_cartAll'=>'Tất cả đơn hàng', 
			'condition_moduleidAll'=> "Tất cả đối tượng",
			'condition_module_catalogue'=>"Nhóm đối tượng", 
			'condition_moduleid'=> "Đối tượng",
		);
		$data['condition_type_1'] = array(
			'condition_quantity' => 'Số lượng đối tượng áp dụng',
			'condition_money' => 'Tổng giá trị đơn hàng',
		);
		if($value == -1){
			return $data[$field];
		}
		else{
			return $data[$field][$value];
		}
	}
}