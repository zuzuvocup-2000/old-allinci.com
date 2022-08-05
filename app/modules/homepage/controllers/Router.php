<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Router extends MY_Controller{

	public function __construct(){
		parent::__construct();
	}
	
	public function index($canonical = '', $page = 1){
		$module = '';
		$count = $this->Autoload_Model->_get_where(array(
			'select' => 'id',
			'table' => 'router',
			'where' => array('canonical' => $canonical),
			'count' => TRUE,
		));
		if($count > 0){
			$router = $this->Autoload_Model->_get_where(array(
				'select' => 'id, canonical, type, param, crc32, uri',
				'table' => 'router',
				'where' => array('canonical' => $canonical),
			));
			
			$id = $router['param'];
			$router['param'] = array(
				'id' => $id,
				'canonical' => $canonical,
			);
			$module = modules::run($router['uri'], $router['param']['id'], $page);
				
			if(COMPRESS == 1){
				$search = array(
					'/\n/', // replace end of line by a space
					'/\>[^\S ]+/s', // strip whitespaces after tags, except space
					'/[^\S ]+\</s', // strip whitespaces before tags, except space
					'/(\s)+/s' // shorten multiple whitespace sequences
				);
				$replace = array(
					' ',
					'>',
					'<',
					'\\1'
				);
				echo preg_replace($search, $replace, $module);
			}
			else{
				echo $module;
			}
		}
		else{
			redirect('filenotfound');
		}
	}

}
