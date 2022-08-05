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
		$data['connect'] = array(
			0 => 'Chọn Module',
			'article_catalogue' => 'Danh mục bài viết',
			'article' => 'Chi tiết bài viết',
			'media_catalogue' => 'Danh mục thư viện',
			'media' => 'Chi tiết thư viện',
			'product_catalogue' => 'Danh mục sản phẩm',
			'product' => 'Sản phẩm',
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