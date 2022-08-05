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
		$data['perpage'] = array(
			5 => '5 bản ghi',
			10 => '10 bản ghi',
			15 => '15 bản ghi',
			20 => '20 bản ghi',
			25 => '25 bản ghi',
			30 => '30 bản ghi',
			35 => '35 bản ghi',
			40 => '40 bản ghi',
			45 => '45 bản ghi',
			50 => '50 bản ghi',

			// 1 => '1 bản ghi',
			// 2 => '2 bản ghi',
			// 3 => '3 bản ghi',
			// 4 => '4 bản ghi',
			// 5 => '5 bản ghi',
			// 10 => '10 bản ghi',

		);
		if($value == -1){
			return $data[$field];
		}
		else{
			return $data[$field][$value];
		}
	}
}