<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export extends MY_Controller {

	private $module;
	public function __construct() {
        parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == false || count($this->auth) == 0) redirect(BACKEND_DIRECTORY);
		$this->load->library('configbie');
		$this->module = 'export';
    } 
    public function listData(){
    	$data['script']='export';
		$this->commonbie->permission("export/backend/export/create", $this->auth['permission']);
		$data['from'] = 0;
		$data['to'] = 0;
		$form = convertSerialize(json_decode($this->input->get('form'),true));

		$keyword= (!empty($form['keyword'])) ? $form['keyword'] : '';
		$keyword = $this->db->escape_like_str($keyword);
		
		$page = (!empty($form['page'])) ? $form['page'] : 1;
		$perpage =(!empty($form['perpage'])) ? $form['perpage'] : 20;
		$workerid =(!empty($form['workerid'])) ? $form['workerid'] : '';
		//Tính tổng số bản ghi của trang danh mục
		
		$constructionid =(!empty($form['construction'])) ? $form['construction'] : '';
		$constructidWhere = ($constructionid > 0) ? 'AND constructionid = "'.$constructionid.'"' :'' ;
		
		$product = $this->Autoload_Model->_get_where(array(
			'table' => 'product',
			'where'=> array('code'=> $keyword),
			'select'=>'id',
		));
		$query = '';
		if(!empty($product)){
			$query = 'tb2.productid = '.$product['id'].$constructidWhere;
		}else{
			$query = 'tb3.title like  \'%'.$keyword.'%\''.$constructidWhere;
		}
		$config['total_rows'] = $this->Autoload_Model->_get_where(array(
			'table'=>'export as tb1',
			'join'=> array(
				array('export_relationship as tb2', 'tb2.exportid = tb1.id', 'inner'),
				array('construction as tb3', 'tb3.id = tb1.constructionid', 'inner'),
			),	
			'select'=>'tb1.id',
			'group_by'=>'tb1.id',
			'query'=>$query,
			'count' => TRUE,
		));
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('export/backend/export/view');
			$config['suffix'] = $this->config->item('url_suffix').(!empty($_SERVER['QUERY_STRING'])?('?'.$_SERVER['QUERY_STRING']):'');
			$config['first_url'] = $config['base_url'].$config['suffix'];
			$config['per_page'] = $perpage;
			$config['cur_page'] = $page;
			$config['uri_segment'] = 5;
			$config['use_page_numbers'] = TRUE;
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
			
			$data['listData'] = $this->Autoload_Model->_get_where(array(
				'table'=>'export as tb1',
				'join'=> array(
					array('export_relationship as tb2', 'tb2.exportid = tb1.id', 'inner'),
					array('construction as tb3', 'tb3.id = tb1.constructionid', 'inner'),
				),
				'query'=> $query,
				'limit'=> $config['per_page'],
				'start'=>$page * $config['per_page'],
				'group_by'=>'tb1.id',
				'select'=>'tb1.id,tb1.code, tb1.constructionid, tb1.status, (SELECT title FROM stock WHERE stock.id = tb1.stockid) as stock, (SELECT fullname FROM user WHERE user.id = tb1.userid_created) as userid_created, tb1.workerid',
				'order_by' => 'tb1.created desc',
			),true);
		}
		$html = '';
		if(isset($data['listData']) && is_array($data['listData']) && count($data['listData'])){
			foreach($data['listData'] as $key => $val){
				$construction = $this->Autoload_Model->_get_where(array(
					'table' => 'construction',
					'select' => 'title, code, worker, (SELECT fullname FROM customer WHERE customer.id = construction.customerid) as customer,(SELECT fullname FROM user WHERE user.id = construction.userid_charge) as userid_charge',
					'where'=> array('id'=> $val['constructionid'])
				));
				$worker=json_decode($construction['worker']);
				$fullname_worker= $this->Autoload_Model->_get_where(array(
					'table'=>'user',
					'select'=>'id, fullname,',
					'where_in_field'=>'id',
					'where_in'=>($worker==0) ? array(''):$worker,
				),true);
				$html = $html . '<tr class="gradeX choose">';
					$html = $html . '<td>';
						$html = $html . '<input type="checkbox" name="checkbox[]" value="'.$val['id'] .'" class="checkbox-item">';
						$html = $html . '<label for="" class="label-checkboxitem"></label>';
					$html = $html . '</td>';
					$html = $html . '<td class="text-center">'. $val['id'] .'</td>';
					$html = $html . '<td class="text-center"><a href="'.site_url("construction/backend/construction/update/".$val['constructionid']).'">'.$construction['code'].'</a>';
					$html = $html . '</td>';
					$html = $html . '<td>'.  $construction['title'] .'</td>';
					$html = $html . '<td>'.  $construction['userid_charge'] .'</td>';
					$html = $html . '<td>'.  $val['stock'] .'</td>';
					$html = $html . '<td>';
						foreach( $fullname_worker as $key =>$sub ){
							echo '<span class="label label-success-light pull-left m-r-xs ">'.$sub['fullname'].'</span>';
						}; 
					$html = $html . '</td>';
					$html = $html . '<td class="text-center" id="status">';
					$html = $html . (( $val['status'] == 0) ? '<span class=" label label-warning-light  m-r-xs m-">Chờ xuất hàng</span>' : '<span class="label label-success-light m-r-xs m-">Đã nhận hàng</span>') .'</td>';
					$html = $html . '</td>';
					$html = $html . '</td>';
					$html = $html . '</td>';
					$html = $html . '<td class="text-center">';
						$html = $html . '<a type="button" href="'. site_url('export/backend/export/update/'.$val['id']).'" class="btn btn-sm btn-primary m-r-xs"><i class="fa fa-edit"></i></a>';
					$html = $html . '</td>';
				$html = $html . '</tr>';
			}
		}else{
				$html = '<tr>
						<td colspan="12">
							<small class="text-danger">Không có dữ liệu phù hợp</small>
						</td>
					</tr>';
		};
		echo json_encode(array(
			'pagination' => (isset($listPagination)) ? $listPagination : '',
			'html' => (isset($html)) ? $html : '',
			'total' => $config['total_rows'],
		));die();
    }
    //xóa đơn hàng
    public function ajax_recycle(){
		$param['module'] = $this->input->post('module');
		$param['id'] = (int)$this->input->post('id');
		$param['code'] = $this->input->post('code');
		if(isset($param) && is_array($param) && count($param)){
			$log['message'] = 'Tài khoản: '. $this->auth['account'].' đã xóa '.$param['code'];
			$log['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
			$log['userid_created'] = $this->auth['id'];
			if(isset($log) && is_array($log) && count($log)){
				$insertid = $this->Autoload_Model->_create(array(
					'insert' => $log,
					'table' => 'history',
				));
			}
			$this->Autoload_Model->_remove(array(
				'where'=> array('exportid'=>$param['id']),
				'table'=>'export_relationship',
			));
			$flag = $this->Autoload_Model->_remove(array(
				'where'=> array('id'=>$param['id']),
				'table'=>$param['module'],
			));
			echo $flag;die();
		}
	}
    //tình trang nhập kho: hàng chưa về, đã nhập kho
    public function ajax_export(){
    	$id = $this->input->post('id');
		$productId = $this->input->post('productId');
		$quantity = $this->input->post('quantity');
		$quantityOld = $this->input->post('quantityOld');
		foreach($productId as $key =>$val){
			$count_product = $this->Autoload_Model->_get_where(array(
				'table' => 'product',
				'select'=> 'quantity, quantity_in_stock',
				'where'=> array('id' => $val)
			));
			$update_product[]=array(
				'id'=>$val,
				'quantity' => $count_product['quantity'] - $quantity[$key] + $quantityOld[$key],
				'quantity_in_stock' => $count_product['quantity_in_stock'] - $quantity[$key]  ,
			);
		};
		$update_export=array(
			'time_finish' => gmdate('Y-m-d H:i:s', time() + 7*3600),
			'status'=>1,
		);
		$this->Autoload_Model->_update(array('update' => $update_export,'table' =>'export' ,'value'=>$id));
		$this->Autoload_Model->_update_batch(array('update' => $update_product,'table' =>'product' ,'field'=>'id'));
		echo 1;die;
	}
}


	