<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
	public function __construct(){
		parent::__construct();
		// if(!isset($this->auth)) redirect('admin');
		$this->load->library(array('configbie'));
	}
	public function upload_file(){

		if (!empty($_FILES['picture']['name'])) {
			$config['upload_path'] = 'upload';
			$config['allowed_types'] = 'jpg|jpeg|png|gif';
			$config['file_name'] = $_FILES['picture']['name'];

			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if ($this->upload->do_upload('picture')) {
				$uploadData = $this->upload->data();
				pre($uploadData);
				$data["image"] = $uploadData['file_name'];
			} else{
				$data["image"] = '';
			}
		}else{
			$data["image"] = '';
		}
	}
	// lấy danh sách sản phẩm
	public function listProduct(){
		$keyword = $this->db->escape_like_str($this->input->get('keyword'));
		$productid= ($this->input->get('productid'))  ? $this->input->get('productid') : '';
		$data['listProduct'] = $this->Autoload_Model->_get_where(array(
			'table'=>'product',
			'search'=> '(title LIKE \'%'.$keyword.'%\')',
			'where_in_field'=>'id',
			'where_not_in'=>$productid,
			'limit'=> 7,
			'select'=>'id, code, title, price, quantity_dau_ki, quantity_cuoi_ki, catalogueid, image, tag, measure'
		),true);

		if(isset($data['listProduct']) && is_array($data['listProduct']) && count($data['listProduct'])){
			foreach($data['listProduct'] as $key => $val){
				$data['listProduct'][$key]['measure']= $this->configbie->data('measure', (!empty($val['measure']))?$val['measure']:'0'); 
				$data['listProduct'][$key]['data-info']=  base64_encode(json_encode($data['listProduct'][$key]));
			}
		}
		$html = '';
		if(isset($data['listProduct']) && is_array($data['listProduct']) && count($data['listProduct'])){
			foreach($data['listProduct'] as $key => $val){
        		$html=$html.'<li class="p-xxs" data-info="'.$val['data-info'].'">';
        			$html=$html.'<div class="uk-flex uk-flex-middle uk-flex-space-between">';
        				$html=$html.'<div class="uk-flex uk-flex-middle">';
	        				$html=$html.'<img  class="img-sm m-r" src=" '.$val['image'].'" alt="ảnh">';
	        				$html=$html.'<div>';
	        					$html=$html.'<div class="title"> '.cutnchar($val['title'],50).'</div>';
	        					$html=$html.'<div class="code"> '.$val['code'].'</div>';
	        				$html=$html.'</div>';
        				$html=$html.'</div>';
        				$html=$html.'<div>';
        					$html=$html.'<div class="uk-flex uk-flex-middle">';
        						$html=$html.'Giá bán : <b>'.number_format($val['price'],'0',',','.').'<sup> đ</sup></b>';
        					$html=$html.'</div>';
        					$html=$html.'<div class="total_product uk-flex">';
        						$html=$html.'<div class="m-r-xs" style="width:100px">Tồn cuối: <b> '.$val['quantity_cuoi_ki'].'</b></div>';
        						$html=$html.'<div style="width:100px">Tồn đầu: <b> '.$val['quantity_dau_ki'].'</b></div>';
        					$html=$html.'</div>';
        				$html=$html.'</div>';
        			$html=$html.'</div>';
        		$html=$html.'</li>';
            }
		}else{
			$html =$html.'<li class="p-xxs"> Không có sản phẩm phù hợp</li>';
		};
		echo json_encode(array(
			'html' => (isset($html)) ? $html : '',
		));die();
	}
	/* ================ UPDATE FIELD ======================= */
	public function ajax_update_status_by_field(){
		$post['module'] = $this->input->post('module');
		$post['objectid'] = $this->input->post('id');
		$post['field'] = $this->input->post('field');
		// Lấy ra thông tin của  object dựa vào id
			
		$this->db->select('id, '.$post['field'].'');
		$this->db->from($post['module']);
		$this->db->where(array(
			'id' => $post['objectid'],
		));
		$data['object'] = $this->db->get()->row_array();
		
		//Cập nhật
		$temp[$post['field']] = (($data['object'][$post['field']] == 1)?0:1);
		$temp['userid_updated'] = 1;
		$temp['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
		$this->db->where(array('id' => $post['objectid']));
		$this->db->update($post['module'], $temp);
		echo $temp[$post['field']];
		die();
	}
	public function getLocation(){
		$post['parentid'] = $this->input->post('parentid');
		$post['select'] = $this->input->post('select');
		$post['table'] = $this->input->post('table');
		$post['text'] = $this->input->post('text');
		$post['parentField'] = $this->input->post('parentField');
		
		$locationList = getLocation(array(
			'select' => $post['select'].', name',
			'table' => $post['table'],
			'where' => array($post['parentField'] => $post['parentid']),
			'field' => $post['select'],
			'text' => $post['text'],
		));
		$temp = [];
		if(isset($locationList) && is_array($locationList) && count($locationList)){
			foreach($locationList as $key => $val){
				$temp = $temp.'<option value="'.$key.'">'.$val.'</option>';
			}
		}
		
		
		echo json_encode(array(
			'html' => $temp,
		));die();
	}
	
	
	public function sort_order(){
		$param = $this->input->post('param');
		$_update['order'] = $param['order'];
		$flag = $this->Autoload_Model->_update(array(
			'where' => array('id' => $param['id']),
			'table' => $param['module'],
			'data' => $_update,
		));
		
		echo json_encode(array(
			'flag' => $flag,
		));die();
	}
	
	/* ================ RECYCLE ======================= */
	public function ajax_delete(){
		$param['module'] = $this->input->post('module');
		$param['id'] = (int)$this->input->post('id');
		$param['router'] = $this->input->post('router');
		$param['child'] = (int)$this->input->post('child');
		$module = explode('_', $param['module']);
		if(isset($module[1]) && $module[1] == 'catalogue'){
			if(isset($param['child']) && $param['child'] == 1){
				$allPost = $this->Autoload_Model->_get_where(array('select' => 'id','table' => $module[0],'where' => array('catalogueid' => $param['id'])),TRUE);
				if(isset($allPost) && is_array($allPost) && count($allPost)){
					foreach($allPost as $key => $val){
						$deleteRouter = $this->Autoload_Model->_delete(array(
							'where' => array('canonical' => $val['canonical'],'uri' => ''.$module[0].'/frontend/'.$module[0].'/view'),
							'table' => 'router',
						));
						$deleteRelation = $this->Autoload_Model->_delete(array(
							'where' => array('moduleid' => $val['id'],'module' => $module[0]),
							'table' => 'catalogue_relationship',
						));
						$deletePost = $this->Autoload_Model->_delete(array(
							'where' => array('id' => $val['id']),
							'table' => $module[0],
						));
						$deleteTag = $this->Autoload_Model->_delete(array(
							'where' => array('moduleid' => $val['id'], 'module' => $module[0]),
							'table' => 'tag_relationship',
						));
					}
				}
			}else{
				$deleteObject = $this->Autoload_Model->_delete(array(
					'where' => array('catalogueid' => $param['id']),
					'table' => $module[0],
				));
			}
		}
		if($param['router'] != '' && !empty($param['router'])){
			$router = $this->Autoload_Model->_delete(array(
				'where' => array('canonical' => $param['router']),
				'table' => 'router',
			));
		}
		
		$flag = $this->Autoload_Model->_delete(array(
			'where' => array('id' => $param['id']),
			'table' => $param['module']
		));
		
		if(isset($param['child']) && $param['child'] == 1){
			$this->load->library('nestedsetbie', array('table' => $param['module']));
			$this->nestedsetbie->Get('level ASC, order ASC');
			$this->nestedsetbie->Recursive(0, $this->nestedsetbie->Set());
			$this->nestedsetbie->Action();
		}
		
		echo $flag;die();
	}
	
	
	public function ajax_delete_all(){
		$flag = 0;
		$post = $this->input->post('post');
		if(isset($post['list']) && is_array($post['list']) && count($post['list'])){
			foreach($post['list'] as $key => $val){
				//Xóa bảng catalogue relation ship
				if($param['module'] != 'tag'){
					$deleteRelationShip = $this->Autoload_Model->_delete(array(
						'where' => array('moduleid' => $val,'module' => $post['module']),
						'table' => 'catalogue_relationship',
					));
					//Xóa bảng Tag
					$deleteTag = $this->Autoload_Model->_delete(array(
						'where' => array('moduleid' => $val, 'module' => $post['module']),
						'table' => 'tag_relationship',
					));
				}else{
					$deleteTag = $this->Autoload_Model->_delete(array(
						'where' => array('tagid' => $val),
						'table' => 'tag_relationship',
					));
				}
				//Xóa bảng Router
				$deleteRouter = $this->Autoload_Model->_delete(array(
					'where' => array('param' => $val,'uri' => ''.$post['module'].'/frontend/'.$post['module'].'/view'),
					'table' => 'router',
				));
				//Xóa đối tượng
				$deleteObject = $this->Autoload_Model->_delete(array(
					'where' => array('id' => $val),
					'table' => $post['module'],
				));
			}
			$flag = 1;
		}
		echo $flag;die();
	}
	
	/* LẤY DỮ LIỆU TỪ SELECT 2 GỬI LÊN */
	// lây dữ liệu thỏa mãn yêu cầu trong input select2
	
	public function pre_select2(){
		$locationVal = $this->input->post('locationVal');
		$module = $this->input->post('module');
		$select = $this->input->post('select');
		$value = $this->input->post('value');
		$condition = $this->input->post('condition');
		$condition =(!empty($condition)) ? $condition : '';
		$catalogueid = json_decode($value,true);
		$key = $this->input->post('key');
		if(empty($key)){
			$key = 'id';
		}

		if(isset($catalogueid) && is_array($catalogueid) && count($catalogueid)){
			$data = $this->Autoload_Model->_get_where(array(
				'select' => $key.','. $select,
				'table' => $module,
				'where_in'=> $catalogueid,
				'search'=> '('.$select.' LIKE \'%'.$locationVal.'%\''.$condition.')',
				'where_in_field'=>$key,
			),TRUE);
		}elseif(isset($catalogueid) && $catalogue != '') {
			$data = $this->Autoload_Model->_get_where(array(
				'select' => $key.','. $select,
				'table' => $module,
				'where'=>array($key=> $catalogueid),
				'search'=> '('.$select.' LIKE \'%'.$locationVal.'%\''.$condition.')',
			),TRUE);
		}
		$temp = [];
		if(isset($data) && is_array($data) && count($data)){
			foreach($data as $key1 => $val){
				$temp[] = array(
					'id'=> $val[$key],
					'text' => $val[$select],
				);
			}
		}
		echo json_encode(array('items' => $temp));die();
	}
	public function get_select2(){
		$locationVal = $this->input->post('locationVal');
		$module = $this->input->post('module');
		$select = $this->input->post('select');
		$key = $this->input->post('key');
		if(empty($key)){
			$key = 'id';
		}
		$condition = $this->input->post('condition');
		$condition =(!empty($condition)) ? $condition : '';
		$data = $this->Autoload_Model->_get_where(array(
			'select' => $key.','. $select,
			'table' => $module,
			'keyword'=> '('.$select.' LIKE \'%'.$locationVal.'%\''.$condition.')',
			'order_by' => $key.' desc',
			'limit' => 10,
		),TRUE);

		$temp = [];
		if(isset($data) && is_array($data) && count($data)){
			foreach($data as $index => $val){
				$temp[] = array(
					'id'=> $val[$key],
					'text' => $val[$select],
				);
			}
		}
		echo json_encode(array('items' => $temp));die();
	}
		
}
