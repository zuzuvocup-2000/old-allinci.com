<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promotional extends MY_Controller {

	public function __construct(){
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
	}
	public function update_hightlight(){
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		$_update['hightlight'] = (($status == 1)?0:1);
		$this->Autoload_Model->_update(array(
			'where' => array('id' => $id),
			'table' => 'promotional',
			'data' => $_update,
		));
		$_update_rest['hightlight'] = 0;
		$this->Autoload_Model->_update(array(
			'where' => array('id !=' => $id),
			'table' => 'promotional',
			'data' => $_update_rest,
		));
		pre(1);

	}
	public function ajax_delete_promotional_all(){
		$param = $this->input->post('post');
		$id  = $param['id_checked'];
		$router  = $param['router'];
			
		$router = $this->Autoload_Model->_delete(array(
			'table' => 'router',
			'where_in' => $router,
			'where_in_field' => 'canonical',
		));
		// xóa sản phẩm
		$this->Autoload_Model->_delete(array(
			'where_in' => $id,
			'where_in_field' => 'promotionalid',
			'table' => 'promotional_relationship',
		));
		$flag = $this->Autoload_Model->_delete(array(
			'table' => 'promotional',
			'where_in_field' => 'id',
			'where_in' => $id,
		));
		
		echo $flag;die();
	}
	// xóa SP
	public function ajax_delete_promotional(){
		$param['id'] = (int)$this->input->post('id');
		$param['router'] = $this->input->post('router');
		$param['module'] = $this->input->post('module');
		if($param['router'] != '' && !empty($param['router'])){
			$router = $this->Autoload_Model->_delete(array(
				'where' => array('canonical' => $param['router']),
				'table' => 'router',
			));
		}
		// xóa sản phẩm
		$flag = $this->Autoload_Model->_delete(array(
			'where' => array('module' => $param['module'], 'promotionalid' => $param['id']),
			'table' => 'promotional_relationship'
		));
		$flag = $this->Autoload_Model->_delete(array(
			'where' => array('id' => $param['id']),
			'table' => 'promotional'
		));
		echo $flag;die();
	}

	public function listpromotional(){
		$page = (int)$this->input->get('page');
		$json = [];
		$data['from'] = 0;
		$data['to'] = 0;
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 20;
		$keyword = $this->db->escape_like_str($this->input->get('keyword'));
		$param['catalogue'] = $this->input->get('catalogue');
		$param['publish'] = $this->input->get('publish');
		$param['action'] = $this->input->get('action');
		foreach ($param as $key => $value) {
			if($value == '-1'){
				unset($param[$key]);
			}
		}
		$query = '';
		if(!in_array('product/backend/product/viewall', json_decode($this->auth['permission'], TRUE))){
			$query = $query.' AND tb1.userid_created = '.$this->auth['id'].' ';
		}
		if(!empty($keyword)){
			$query = $query.' AND (tb1.title LIKE \'%'.$keyword.'%\' OR tb1.description LIKE \'%'.$keyword.'%\') ';
		}
		if(isset($param['catalogue'])){
			$query = $query.' AND tb1.catalogue = "'.$param['catalogue'].'"';
		}
		if(isset($param['publish'])){
			$query = $query.' AND tb1.publish =  '.$param['publish'];
		}
		if(isset($param['action']) && $param['action'] == 1){
			$current = gmdate('Y-m-d H:i:s', time() + 7*3600);
			$query = $query.' AND tb1.publish =  1 AND tb1.start_date  <=  "'.$current. '" AND ( tb1.end_date >= "'.$current.'" OR tb1.end_date = "0000-00-00 00:00:00" ) ';
		}
		if(isset($param['action']) && $param['action'] == 0){
			$current = gmdate('Y-m-d H:i:s', time() + 7*3600);
			$query = $query.' AND tb1.publish = 0 AND tb1.end_date != "0000-00-00 00:00:00" AND ( tb1.start_date  >=  "'.$current. '" OR tb1.end_date <= "'.$current.'" ) ';
		}
		$query = substr( $query,  4, strlen($query));
		$config['total_rows'] = $this->Autoload_Model->_get_where(array(
			'table' =>'promotional as tb1',
			'query' => $query,
			'count'=>true,
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
			$listpromotional = $this->Autoload_Model->_get_where(array(
				'table' =>'promotional as tb1',
				'query' => $query,
				'select' => 'tb1.id, tb1.catalogue, tb1.title, tb1.canonical, tb1.created, tb1.image_json, (SELECT fullname FROM user WHERE user.id = tb1.userid_created) as user_created, tb1.module, tb1.start_date, tb1.end_date, tb1.publish, tb1.discount_type, tb1.discount_value, tb1.condition_value, tb1.condition_type, tb1.freeship, tb1.freeshipAll, tb1.condition_value_1, tb1.condition_type_1, tb1.use_common, tb1.code, tb1.limmit_code, tb1.cityid, tb1.discount_moduleid',
			),true);
			foreach ($listpromotional as $key => $value) {
				$promotional1 = json_decode(getPromotional($value), true);
				$listpromotional[$key] = $value;
				$listpromotional[$key]['use_common'] = $promotional1['use_common'];
				$listpromotional[$key]['detail'] = $promotional1['detail'];
			}
			
		}
		
		$html = '';
		 if(isset($listpromotional) && is_array($listpromotional) && count($listpromotional)){ 
			foreach($listpromotional as $key => $val){
				$current = gmdate('Y-m-d H:i:s', time() + 7*3600);
				if($val['publish'] ==  1 AND $val['start_date']  <=  $current  AND ( $val['end_date'] >= $current OR $val['end_date'] = "0000-00-00 00:00:00" ) ) {
				}
				$html = $html.'<tr class="gradeX " id="post-'.$val['id'].'">';
					$html = $html.'<td class="text-center">';
						$html = $html.'<input type="checkbox" name="checkbox[]" value="'.$val['id'].'" class="checkbox-item">';
						$html = $html.'<label for="" class="label-checkboxitem"></label>';
					$html = $html.'</td>';

					$html = $html.'<td>';
						$html = $html.'<div class="show-block-promotion" style="background: '.(($val['catalogue'] == 'CP') ? '#4da9c1' : '#0a3d62' ). '">';
							$html = $html.'<div class="inner">';
								$html = $html.'<div class="title">';
									$html = $html.'<div>'.$val['title'].'</div>';
									$limmit_code = ($val['limmit_code'] == -1)  ? ' Không giới hạn số lần ' : $val['limmit_code'];
									$html = $html.(($val['catalogue'] == 'CP') ? ' Mã Coupon: '.$val['code'].'('.$limmit_code.')' : ''); 
								$html = $html.'</div>';
								$html = $html.'<div class="detail">';
								$html = $html.'	'.$val['detail'];
								$html = $html.'</div>';
								$html = $html.'<div class="user_common">'.$val['use_common'];
								$html = $html.'</div>';
							$html = $html.'</div>';
						$html = $html.'</div>';
					$html = $html.'</td>';

					$html = $html.'<td  class="text-center">';
						if ($val['start_date'] == '0000-00-00 00:00:00'){
							$html = $html.settime($val['created'], 'date');
						}else{
							$html = $html.settime($val['start_date'], 'date');
						}
					$html = $html.'</td>';
					$html = $html.'<td  class="text-center">';
						if ($val['start_date'] == '0000-00-00 00:00:00'){
							$html = $html.' Không hết hạn';
						}else{
							$html = $html.'Đến '.settime($val['end_date'], 'date');
						}
					$html = $html.'</td>';

					$html = $html.'<td class="text-center">';
						$html = $html.'<div class="switch m-l-sm">';
							$html = $html.'<div class="onoffswitch">';
								$html = $html.'<input type="checkbox" '.(($val['publish'] == 1) ? 'checked=""' : '').' class="onoffswitch-checkbox publish change_status" data-id="'.$val['id'].'" data-module="promotional" data-field="publish" id="publish-'.$val['id'].'">';
								$html = $html.'<label class="onoffswitch-label" for="publish-'.$val['id'].'">';
									$html = $html.'<span class="onoffswitch-inner"></span>';
									$html = $html.'<span class="onoffswitch-switch"></span>';
								$html = $html.'</label>';
							$html = $html.'</div>';
						$html = $html.'</div>';
					$html = $html.'</td>';
					$html = $html.'<td class="text-center">';
						$html = $html.'<a type="button" href="'.(site_url('promotional/backend/promotional/update/'.$val['id'].'')).'" class="btn btn-sm btn-primary mr5"><i class="fa fa-edit"></i></a>';
						$html = $html.'<a type="button" data-title="Lưu ý: Dữ liệu sẽ không thể khôi phục. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!" class="btn btn-sm btn-danger ajax_delete_promotional" data-router="'.$val['canonical'].'" data-id="'.$val['id'].'" " data-module="'.$val['module'].'"><i class="fa fa-trash"></i></a>';

					$html = $html.'</td>';
				$html = $html.'</tr>';
			}
		}else{ 
			$html = $html.'<tr>
				<td colspan="10"><small class="text-danger">Không có dữ liệu phù hợp</small></td>
			</tr>';
			
		}
		echo json_encode(array(
			'pagination' => (isset($listPagination)) ? $listPagination : '',
			'html' => (isset($html)) ? $html : '',
			'total' => $config['total_rows'],
		));die();		
	}
	
	
	
	public function status(){
		$id = $this->input->post('objectid');
		$object = $this->Autoload_Model->_get_where(array(
			'select' => 'id, publish',
			'table' => 'article',
			'where' => array('id' => $id),
		));
		
		$_update['publish'] = (($object['publish'] == 1)?0:1);
		$this->Autoload_Model->_update(array(
			'where' => array('id' => $id),
			'table' => 'article',
			'data' => $_update,
		));
	}
	
	public function get_moduleid(){
		$id = $this->input->get('id');
		$discount_value = $this->input->get('discount_value');
		$module = $this->input->get('module');
		if($module == 'product'){
			$object = $this->Autoload_Model->_get_where(array(
				'select' => 'id, title, price, quantity_cuoi_ki, code, image',
				'table' => $module,
				'where_in' => $id,
				'where_in_field' =>'id',
			),true);
			$html = '';
			if(isset($object) && is_array($object) && count($object)){
				foreach($object as $key => $val){
					$html = $html . '<div class="p-xxs choose-moduleid m-b-sm" data-id='.$val['id'].'>';
		    			$html = $html . '<div class="uk-flex uk-flex-middle uk-flex-space-between">';
		    				$html = $html . '<div class="uk-flex uk-flex-middle">';
		        				$html = $html . '<div>';
			        				$html = $html . '<img class="img-sm m-r" src="'.$val['image'].'" alt="ảnh">';
			        			$html = $html . '</div>';
			        			$html = $html . '<div>';
			        				$html = $html . '<div class="title">'.$val['title'].'</div>';
									$html = $html . '<div class="content">Giá bán :<b style="    text-decoration: line-through;">'.addCommas($val['price']).'</b> còn <b class="text-danger">'.addCommas($val['price']*(100 - $discount_value)/100).'</b> <sup> đ</sup>/ <span class="m-r-xs">Tồn cuối: <b> '.$val['quantity_cuoi_ki'].'</b></span></div>';
								$html = $html . '</div>';
		    				$html = $html . '</div>';
		    				$html = $html . '<div class="del m-r-sm" data-id="'.$val['id'].'"><i class="fa fa-times" aria-hidden="true"></i></div>';
		    			$html = $html . '</div>';
		    		$html = $html . '</div>';
				}
			}
		}else{
			$object = $this->Autoload_Model->_get_where(array(
				'select' => 'id, title, price, image',
				'table' => $module,
				'where_in' => $id,
				'where_in_field' =>'id',
			),true);
			$html = '';
			if(isset($object) && is_array($object) && count($object)){
				foreach($object as $key => $val){
					$html = $html . '<div class="p-xxs choose-moduleid m-b-sm" data-id='.$val['id'].'>';
		    			$html = $html . '<div class="uk-flex uk-flex-middle uk-flex-space-between">';
		    				$html = $html . '<div class="uk-flex uk-flex-middle">';
		        				$html = $html . '<div>';
			        				$html = $html . '<img class="img-sm m-r" src="'.$val['image'].'" alt="ảnh">';
			        			$html = $html . '</div>';
			        			$html = $html . '<div>';
			        				$html = $html . '<div class="title">'.$val['title'].'</div>';
									$html = $html . '<div class="content">Giá bán :<b> '.$val['price'].'</b><sup> đ</sup></b></span></div>';
								$html = $html . '</div>';
		    				$html = $html . '</div>';
		    				$html = $html . '<div class="del m-r-sm" data-id="'.$val['id'].'"><i class="fa fa-times" aria-hidden="true"></i></div>';
		    			$html = $html . '</div>';
		    		$html = $html . '</div>';
				}
			}
		}
		echo json_encode(array(
			'html' => (isset($html)) ? $html : '',
		));die();
	}
}
