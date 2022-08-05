<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Controller {

	public function __construct(){
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library(array('configbie'));
	}
	
	
	
	public function status(){
		$id = $this->input->post('objectid');
		$object = $this->Autoload_Model->_get_where(array(
			'select' => 'id, publish',
			'table' => 'order',
			'where' => array('id' => $id),
		));
		
		$_update['publish'] = (($object['publish'] == 1)?0:1);
		$this->Autoload_Model->_update(array(
			'where' => array('id' => $id),
			'table' => 'order',
			'data' => $_update,
		));
	}
	
	public function listorder(){
		$page = (int)$this->input->get('page');
		$json = [];
		$data['from'] = 0;
		$data['to'] = 0;
		$param = $this->input->get();
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 20;
		$param['page'] = (isset($param['page'])) ? $param['page']  : '1';
		$param['keyword'] = (isset($param['keyword'])) ? $param['keyword']  : '1';
		$keyword = $this->db->escape_like_str($param['keyword']);

		$param['promotionalid'] = (isset($param['promotionalid'])) ? $param['promotionalid']  : '0';
		$param['couponid'] = (isset($param['couponid'])) ? $param['couponid']  : '0';
		$param['date_added'] = (isset($param['date_added'])) ? $param['date_added']  : '0';
		$param['date_modified'] = (isset($param['date_modified'])) ? $param['date_modified']  : '0';
		$param['status'] = (isset($param['status'])) ? $param['status']  : '0';

		$query = '';
		if(isset($param['promotionalid']) && !empty($param['promotionalid'])){
			$query = $query.' AND tb1.userid_created = '.$this->auth['id'].' ';
		}
		if(isset($param['date_added']) && !empty($param['date_added'])){
			$query = $query.' AND tb1.created = '.$param['date_added'].' ';
		}
		if(isset($param['date_modified']) && !empty($param['date_modified'])){
			$query = $query.' AND tb1.updated = '.$param['date_modified'].' ';
		}
		if(isset($param['status'])){
			$query = $query.' AND status = "'.$param['status'].'" ';
		}

		$query = substr( $query, 4, strlen($query));
		$config['total_rows'] = $this->Autoload_Model->_get_where(array(
			'select' => 'id',
			'table' => 'order',
			'query' => $query,
			'keyword' => '(code LIKE \'%'.$keyword.'%\' OR id LIKE \'%'.$keyword.'%\' OR fullname LIKE \'%'.$keyword.'%\' OR phone LIKE \'%'.$keyword.'%\' OR email LIKE \'%'.$keyword.'%\' OR address_detail LIKE \'%'.$keyword.'%\')',
			'count' => TRUE,
		));
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] ='#" data-page="';
			$config['suffix'] = $this->config->item('url_suffix').(!empty($_SERVER['QUERY_STRING'])?('?'.$_SERVER['QUERY_STRING']):'');
			$config['first_url'] = $config['base_url'].$config['suffix'];
			$config['per_page'] = $perpage;
			$config['cur_page'] = $page;
			$config['page'] = $page;
			$config['uri_segment'] = 2;
			$config['use_page_numbers'] = TRUE;
			$config['reuse_query_string'] = TRUE;
			$config['full_tag_open'] = '<ul class="pagination no-margin">';
			$config['full_tag_close'] = '</ul>';
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="active"><a class="btn-primary">';
			$config['cur_tag_close'] = '</a></li>';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$this->pagination->initialize($config);
			$listPagination = $this->pagination->create_links();
			$totalPage = ceil($config['total_rows']/$config['per_page']);
			$page = ($page <= 0)?1:$page;
			$page = ($page > $totalPage)?$totalPage:$page;
			$page = $page - 1;
			$data['from'] = ($page * $config['per_page']) + 1;
			$data['to'] = ($config['per_page']*($page+1) > $config['total_rows']) ? $config['total_rows']  : $config['per_page']*($page+1);
		
			$listorder = $this->Autoload_Model->_get_where(array(
				'select' => 'id, fullname, created, updated, order, total_cart_final, status, phone',
				'table' => 'order',
				'query' => $query,
				'keyword' => '(code LIKE \'%'.$keyword.'%\' OR id LIKE \'%'.$keyword.'%\' OR fullname LIKE \'%'.$keyword.'%\' OR phone LIKE \'%'.$keyword.'%\' OR email LIKE \'%'.$keyword.'%\' OR address_detail LIKE \'%'.$keyword.'%\')',
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'order_by' => 'order desc,  id desc',
			), TRUE);	
		}
		
		$html = '';
		 if(isset($listorder) && is_array($listorder) && count($listorder)){ 
			foreach($listorder as $key => $val){
				$html = $html.'<tr>';
		            $html = $html.'<td class="text-center">'.$val['id'].'</td>';
		            $html = $html.'<td class="text-center">'.$val['fullname'].'</td>';
		            $html = $html.'<td class="text-center">'.number_phone($val['phone'], ' ') .'</td>';
		            $html = $html.'<td class="text-right">'.addCommas($val['total_cart_final']).'</td>';
		            $html = $html.'<td class="text-center">'.gettime($val['created'],'d-m-Y').'</td>';
		            $html = $html.'<td class="text-center">'.(($val['updated'] != '0000-00-00 00:00:00') ? gettime($val['updated'],'d-m-Y') : '-' ).'</td>';
		            $html = $html.'<td class="text-center">';
		                $html = $html.$this->configbie->data('state_order', $val['status']);
		            $html = $html.'</td>';
		            $html = $html.'<td class="text-center">';
		                $html = $html.'<a type="button" href="'.site_url('order/backend/order/update/'.$val['id'].'').'" class="btn m-r-xs btn-primary"><i class="fa fa-edit"></i></a>';
		                $html = $html.'<a type="button" class="btn btn-danger ajax_delete_order"  data-id="'.$val['id'].'"  data-module="order"><i class="fa fa-trash"></i></a>';
		            $html = $html.'</td>';
		        $html = $html.'</tr>';
			 }
		}else{ 
			$html = $html.'<tr>
				<td colspan="9"><small class="text-danger">Không có dữ liệu phù hợp</small></td>
			</tr>';
		}
		echo json_encode(array(
			'pagination' => (isset($listPagination)) ? $listPagination : '',
			'html' => (isset($html)) ? $html : '',
			'total' => $config['total_rows'],
		));die();		
	}
}
