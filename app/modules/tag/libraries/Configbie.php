<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ConfigBie{
	
	function __construct($params = NULL){
		$this->params = $params;
	}

	public function data($field = 'process', $value = -1){
		$data['publish'] = array(
			-1 => '- Chọn xuất bản -',
			0 => 'Không xuất bản',
			1 => 'Xuất bản',
		);
		$data['amp'] = array(
			0 => 'Không sử dụng AMP',
			1 => 'Có sử dụng AMP',
		);
		$data['perpage_frontend'] = array(
			16 => '16',
			24 => '24',
			32 => '32',
			100 => '100',
		);
		$data['sort_frontend'] = array(
			'0' => 'Mặc định',
			'title|ASC' => 'Tên (A - Z)',
			'title|DESC' => 'Tên (Z - A)',
			'price|ASC' => 'Giá (Thấp đến cao)',
			'price|DESC' => 'Giá (Cao đến thấp)',
			'rate|ASC' => 'Đánh giá(Cao nhất)',
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
		if($value == -1){
			return $data[$field];
		}
		else{
			return $data[$field][$value];
		}
	}
}