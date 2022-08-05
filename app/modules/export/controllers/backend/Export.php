<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export extends MY_Controller {

	public $module;
	function __construct() {
        parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == false || count($this->auth) == 0) redirect(BACKEND_DIRECTORY);
		$this->load->library(array('configbie'));
		$this->module = 'export';
	}
	public function view($page = 1){
		$data['script']='export';
		$this->commonbie->permission("export/backend/export/view", $this->auth['permission']);
		$page = (int)$page;
		$data['from'] = 0;
		$data['to'] = 0;
		//Tính tổng số bản ghi của trang danh mục
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 20;
		$config['total_rows'] = $this->Autoload_Model->_get_where(array(
			'table' => $this->module,
			'count'=>true,
		));
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('customer/backend/catalogue/view');
			$config['suffix'] = $this->config->item('url_suffix').(!empty($_SERVER['QUERY_STRING'])?('?'.$_SERVER['QUERY_STRING']):'');
			$config['first_url'] = $config['base_url'].$config['suffix'];
			$config['per_page'] = $perpage;
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
			$data['PaginationList'] = $this->pagination->create_links();
			$totalPage = ceil($config['total_rows']/$config['per_page']);
			$page = ($page <= 0)?1:$page;
			$page = ($page > $totalPage)?$totalPage:$page;
			$page = $page - 1;
			$data['from'] = ($page * $config['per_page']) + 1;
			$data['to'] = ($config['per_page']*($page+1) > $config['total_rows']) ? $config['total_rows']  : $config['per_page']*($page+1);
			$data['listData'] = $this->Autoload_Model->_get_where(array(
				'table'=>$this->module,
				'limit'=> $config['per_page'],
				'start'=>$page * $config['per_page'],
				'select'=>'id,code, constructionid, status,stockid , total_money, (SELECT fullname FROM user WHERE user.id = export.userid_created) as userid_created, (SELECT title FROM stock WHERE stock.id = export.stockid) as stock,',
				'order_by' => 'created desc',
			),true);
		}
		$data['config'] = $config;

		$data['template'] = 'export/backend/export/view';
		$this->load->view('dashboard/backend/layouts/home', isset($data)?$data:NULL);
	}
	public function update($id = 0){
		$data['script']='export';
		$this->commonbie->permission("export/backend/export/update", $this->auth['permission']);
		$id = (int)$id;
		$data['detailData'] = $this->Autoload_Model->_get_where(array(
				'table' => $this->module,
				'select'=>'id, data_product, constructionid, code, stockid, status, total_money, note, time_finish',
				'where'=> array('id'=>$id),
			));
		$data['detail_construction'] = $this->Autoload_Model->_get_where(array(
				'table' => 'construction',
				'select' => 'id, customerid, code, (SELECT fullname FROM customer WHERE customer.id = construction.customerid) as customer, date_start, title, data_product, data_prd_out, code, worker,  (SELECT title FROM construction_catalogue WHERE construction_catalogue.id = construction.catalogueid) as catalogue, (SELECT fullname FROM user WHERE user.id = construction.userid_charge) as userid_charge , note',
				'where'=> array('id'=>$data['detailData']['constructionid']),
			));
		$data['worker'] = json_decode($data['detail_construction']['worker'], TRUE);
		$worker = $this->input->post('worker');
		if(!isset($data['detailData']) || is_array($data['detailData']) == false || count($data['detailData']) == 0){
			$this->session->set_flashdata('message-danger', 'Đơn xuất không tồn tại hoặc bạn không có quyền truy cập');
			redirect('export/backend/export/view');
		}
		//lấy dữ liệu sp từ danh sách sp được chon
		$product = $this->input->post('product');
		if(isset($product) && is_array($product) && count($product)) {
			if(isset($product['id']) && is_array($product['id']) && count($product['id'])){
				foreach ($product['id'] as $key => $val) {
					$list_product[$key]['id'] = $val; 
					$list_product[$key]['quantity'] = $product['quantity'][$key]; 
					$list_product[$key]['code'] = $product['code'][$key]; 
					$list_product[$key]['measure'] = $product['measure'][$key]; 
					$list_product[$key]['price_output'] =  (int)str_replace('.','',$product['price'][$key]);
					$list_product[$key]['quantity_paid'] = $product['quantity_paid'][$key]; 
					$list_product[$key]['quantity_error'] = $product['quantity_error'][$key]; 
				}
			}
		}else{
			$list_product = json_decode(base64_decode($data['detailData']['data_product']),true);
		}
		$data['list_product']=(isset($list_product)) ? $list_product : '' ;

		$prd_out = $this->input->post('prd_out');
		if(isset($prd_out) && is_array($prd_out) && count($prd_out)) {
			foreach ($prd_out['quantity'] as $key => $val) {
				$list_prd_out[$key]['quantity'] = $prd_out['quantity'][$key]; 
				$list_prd_out[$key]['code'] = $prd_out['code'][$key]; 
				$list_prd_out[$key]['title'] = $prd_out['title'][$key]; 
				$list_prd_out[$key]['supplierid'] = $prd_out['supplierid'][$key]; 
				$list_prd_out[$key]['measure_exchange'] = $prd_out['measure_exchange'][$key]; 
				$list_prd_out[$key]['price_output'] = (int)str_replace('.','',$prd_out['price'][$key]);
			}
		}else{
			$list_prd_out = json_decode(base64_decode($data['detailData']['data_product']),true);
		}
		$data['list_prd_out']=(isset($list_prd_out)) ? $list_prd_out : '' ;
		$update['code'] =$this ->input ->post('code');
		if($this->input->post('update')){
			$log['message'] = 'Tài khoản: '. $this->auth['account'].' cập nhật đơn xuất '.$update['code'];
			$log['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
			$log['userid_created'] = $this->auth['id'];
			if(isset($log) && is_array($log) && count($log)){
				$insertid = $this->Autoload_Model->_create(array(
					'insert' => $log,
					'table' => 'history',
				));
			}
			$stockid = $data['detailData']['stockid'];
			if($stockid=='1'){
				// cập nhật lại số lượng hàng trong kho
				$list_product_old = json_decode(base64_decode($data['detailData']['data_product']),true);
				$detail_prd ='';
				$total_money =0;
				$total_money_input =0;
				foreach($list_product as $key =>$val){
					$quantity_new= (isset($val['quantity'])) ? $val['quantity'] : 0;
					$quantity_paid= (isset($val['quantity_paid'])) ? $val['quantity_paid'] : 0;
					$quantity_error= (isset($val['quantity_error'])) ? $val['quantity_error'] : 0;
					$list_product_old[$key]['quantity']= (isset($list_product_old[$key]['quantity'])) ? $list_product_old[$key]['quantity'] : 0;
					$list_product_old[$key]['quantity_paid']= (isset($list_product_old[$key]['quantity_paid'])) ? $list_product_old[$key]['quantity_paid'] : 0;
					$list_product_old[$key]['quantity_error']= (isset($list_product_old[$key]['quantity_error'])) ? $list_product_old[$key]['quantity_error'] : 0;
					$quantity_new= $quantity_new -  $quantity_paid - $quantity_error;
					$quantity_old= $list_product_old[$key]['quantity'] -  $list_product_old[$key]['quantity_paid'] - $list_product_old[$key]['quantity_error'];
					$total_money = $total_money + $quantity_new*$val['price_output'];
					$product = $this->Autoload_Model->_get_where(array(
						'table' => 'product',
						'where'=>array('id'=>$val['id']),
						'select'=>'price_input',
					));
					$total_money_input = $total_money_input + $quantity_new*$product['price_input'];
					$object = $this->Autoload_Model->_get_where(array(
						'table' => 'product',
						'where'=>array('id'=>$val['id']),
						'select'=>'quantity_in_stock',
					));
					$update_object=array(
						'quantity_in_stock' => $object['quantity_in_stock'] - $quantity_new + $quantity_old,
					);
					$export_relationship = $this->Autoload_Model->_get_where(array(
						'table' => 'export_relationship',
						'where'=>array('exportid'=>$id, 'productid'=> $val['id']),
						'select'=>'id',
					));
					$this->Autoload_Model->_update(array('update' => $update_object,'table' => 'product', 'value' => $val['id']));
					$update_relationship_product=array(
						'quantity' => $quantity_new,
						'quantity_paid' => $quantity_paid,
						'quantity_error' => $quantity_error,
						'price_input' => $product['price_input'],
					);
					$this->Autoload_Model->_update(array('update' => $update_relationship_product,'table' => 'export_relationship', 'value' => $export_relationship['id']));
					$detail_prd = $detail_prd.$quantity_new.' '.$val['measure'].' '.$val['code'].' + ';
				};
				
					

				$length= strlen($detail_prd) - 2;
				$detail_prd = substr($detail_prd, 0, $length);
				$update_export=array(
					'detail_prd'=>$detail_prd,
					'total_money'=>$total_money,
					'total_money_input'=>$total_money_input,
					'data_product'=> base64_encode(json_encode($list_product)),
					'note'=>$this->input->post('note'),
					'time_finish' => gmdate('Y-m-d H:i:s', time() + 7*3600),
					'status'=>1,
				);
				$this->Autoload_Model->_update(array('update' => $update_export,'table' => 'export', 'value' => $id));
				$this->Autoload_Model->_update(array('update' => array('profit'=>$total_money - $total_money_input),'table' => 'construction', 'value' =>$data['detailData']['constructionid']));
			}else{
				$detail_prd ='';
				$total_money =0;
				foreach($list_prd_out as $key =>$val){
					$total_money =$total_money + $val['quantity']*$val['price_output'];
					$detail_prd = $detail_prd.$val['quantity'].' '.$this->configbie->data('measure', $val['measure_exchange']).' '.$val['code'].' + ';
				};
				$length= strlen($detail_prd) - 2;
				$detail_prd = substr($detail_prd, 0, $length);
				$update_export=array(
					'data_product'=> base64_encode(json_encode($list_prd_out)),
					'note'=>$this->input->post('note'),
					'total_money'=> $total_money,
					'detail_prd'=>$detail_prd,
					'time_finish' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);
				$this->Autoload_Model->_update(array('update' => $update_export,'table' => 'export', 'value' => $id));
				// xóa đơn nhập cũ đi
				$this->Autoload_Model->_remove(array(
					'table' =>'import',
					'where'=>array('constructionid'=>$data['detailData']['constructionid'], 'stockid'=>2),
				));
				// cập nhật số lượng nhập hàng ngoài kho bằng số lượng xuất
				if(isset($list_prd_out)&& is_array($list_prd_out) && count($list_prd_out)){
					$list_prd_out_common=array();
					foreach($list_prd_out as $key =>$val){
						$count=0;
						foreach($list_prd_out as $sub =>$subs){
							if($sub == $key){
								continue;
							}
							$supplierid = $val['supplierid'];
							if($subs['supplierid'] == $supplierid){
								$count= $count + 1;
								if($count == 1){
									$list_prd_out_common[$key][]=$list_prd_out[$key];
									unset($list_prd_out[$key]);
								}
								$list_prd_out_common[$key][]=$list_prd_out[$sub];
								unset($list_prd_out[$sub]);
							}
						}
					}
					foreach($list_prd_out as $key =>$val){ 
						$val1=array($val);
						$create_import=array(
							'data_product'=> base64_encode(json_encode($val1)),
							'code'=> CodeRender('import'),
							'stockid'=>2,
							'status'=>1,
							'constructionid'=>$data['detailData']['constructionid'],
							'supplierid'=>$val['supplierid'],
							'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
							'userid_created' => $this->auth['id'],
						);
						$importid =$this->Autoload_Model->_create(array('insert' => $create_import,'table' => 'import'));
					}
					foreach($list_prd_out_common as $key =>$val){
						$total_money_prd_out = 0;
						foreach($val as $sub =>$subs){
							$supplierid = $subs['supplierid'];
						}
						$create_import_common=array(
							'data_product'=> (isset($list_prd_out_common[$key]) ? base64_encode(json_encode($list_prd_out_common[$key])):''),
							'code'=> CodeRender('import'),
							'stockid'=>2,
							'constructionid'=>$data['detailData']['constructionid'],
							'supplierid'=>$supplierid,
							'status'=>1,
							'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
							'userid_created' => $this->auth['id'],
						);
						$importid =$this->Autoload_Model->_create(array('insert' => $create_import_common,'table' => 'import'));
					}
				}
			}
			$this->session->set_flashdata('message-success','Cập nhật đơn xuất thành công');
			redirect(base_url('export/backend/export/view/'.$id));
		}
		$data['template'] = 'export/backend/export/update';
		$this->load->view('dashboard/backend/layouts/home', isset($data)?$data:NULL);
	}
	
	public function excel(){
		$url = substr(APPPATH, 0, -4);
		$excel_path = $url.'plugin/PHPExcel/Classes/PHPExcel.php';
		require($excel_path);

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0); 
		// $listExport = $this->Autoload_Model->_get_where(array(
			// 'table'=>$this->module,
			// 'select'=>' (SELECT fullname FROM user WHERE user.id = export.userid_created) as userid_created, (SELECT title FROM stock WHERE stock.id = export.stockid) as stock,',
			// 'order_by' => 'created desc',
		// ),true);
		
		
		$listExport	= $this->db->query('
			SELECT tb1.*, tb2.*, (SELECT title FROM product WHERE product.id = tb2.productid) as product_title
			FROM export as tb1
			INNER JOIN export_relationship as tb2
			WHERE tb1.id = tb2.exportid
			ORDER BY `tb1`.`created` desc
		')->result_array();
		
		// echo '<pre>';
		// print_r($listExport);die();
	
		$columnArray = array("A", "B", "C", "D", "E", "F", "G", "H", "I");
		$titlecolumnArray = array('STT','ID','MÃ công trình','Tên công trình','Tên sản phẩm','Số mang đi','Số mang về','Số mét hỏng','Thực dán');
		$row_count = 1;
		 $styleArray = array(
			  'borders' => array(
				  'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
				  )
			  )
		  );
		$objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
		foreach($columnArray as $key => $val){
			$objPHPExcel->getActiveSheet()->SetCellValue($val.$row_count, $titlecolumnArray[$key]);  // lấy ra tiêu đề của từng cột	
			 $objPHPExcel->getActiveSheet()->getColumnDimension($val)->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getStyle($val.$row_count)->applyFromArray(
				array(
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F28A8C')
					)
				)
			);
		}
		$i = 2;
		$total_row = $i + count($listExport);
		$total = 0;
		
		if(isset($listExport) && is_array($listExport) && count($listExport)){
			foreach($listExport as $key => $val){
				$construction = $this->Autoload_Model->_get_where(array(
					'table' => 'construction',
					'select' => 'code, worker, title, (SELECT fullname FROM customer WHERE customer.id = construction.customerid) as customer,(SELECT fullname FROM user WHERE user.id = construction.userid_charge) as userid_charge',
					'where'=> array('id'=> $val['constructionid'])
				));
				$worker=json_decode($construction['worker']);
				$fullname_worker = $this->Autoload_Model->_get_where(array(
					'table'=>'user',
					'select'=>'id, fullname,',
					'where_in_field'=>'id',
					'where_in'=>($worker==0) ? array(''):$worker,
				),true);
				$text = '';
				if(isset($fullname_worker ) && is_array($fullname_worker ) && count($fullname_worker )){
					foreach($fullname_worker as $key => $val){
						$text = $val['fullname'].', ';
					}
				}
				
				$objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(50);
				$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i, $i); 
				$objPHPExcel->getActiveSheet()->SetCellValue('B'.$i, $val['id']); 
				$objPHPExcel->getActiveSheet()->SetCellValue('C'.$i, $construction['code']); 
				$objPHPExcel->getActiveSheet()->SetCellValue('D'.$i, $construction['title']); 
				$objPHPExcel->getActiveSheet()->SetCellValue('E'.$i, $val['product_title']); 
				$objPHPExcel->getActiveSheet()->SetCellValue('F'.$i, $val['quantity']); 
				$objPHPExcel->getActiveSheet()->SetCellValue('G'.$i, $val['quantity_paid']); 
				$objPHPExcel->getActiveSheet()->SetCellValue('H'.$i, $val['quantity_error']); 
				$objPHPExcel->getActiveSheet()->SetCellValue('I'.$i, $val['quantity'] + $val['quantity_paid'] + $val['quantity_error']); 
				$i++;
			}
		}
		$random = random(6,true);
		
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
		$objWriter->save(''.$url.'upload/files/excel/export_'.$random.str_replace('/','_',date("Y/m/d")).'.xlsx'); 
		$data['filename'] = 'upload/files/excel/export_'.$random.str_replace('/','_',date("Y/m/d")).'.xlsx';
		
		echo '<a href="'.base_url($data['filename']).'" downloaded>Download</a>';die();
	}
	
}
