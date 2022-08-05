<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Slide extends MY_Controller{

	public function __construct(){
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library(array('configbie'));
	}

	public function add_group(){
		$this->load->library('form_validation');
		$this->form_validation->CI =& $this;
		$this->form_validation->set_rules('title','Tên nhóm Slide','trim|required');
		$this->form_validation->set_rules('keyword','Từ khóa','trim|required|callback__Check');
		if($this->form_validation->run($this)){
			$_insert = array(
				'title' => $this->input->post('title'),
				'keyword' => slug($this->input->post('keyword')),
				'userid_created' => $this->auth['id'],
				'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
			);
			
			$flag = $this->Autoload_Model->_create(array(
				'table' => 'slide_catalogue',
				'data' => $_insert
			));
			if($flag > 0){
				
				$this->session->set_flashdata('message-success', 'Tạo nhóm slide mới thành công');
				$error['flag'] = 0;
				$error['message'] = ''; 
				echo json_encode(array(
					'error' => $error,
				));die();
			}
			
			
		}else{
			$error['flag'] = 1;
			$error['message'] = validation_errors(); 
			echo json_encode(array(
				'error' => $error,
			));die();
		}
	}
	
	public function add_slide(){
		$this->load->library('form_validation');
		$this->form_validation->CI =& $this;
		$this->form_validation->set_rules('catalogueid','Tên nhóm Slide','trim|required|is_natural_no_zero|callback__CheckImage');
		if($this->form_validation->run($this)){
			$object = $this->input->post('object');
			$catalogueid = $this->input->post('catalogueid');
			
			
			if(isset($object['src']) && is_array($object['src']) && count($object['src'])){
				foreach($object['src'] as $key => $val){
					$_insert[] = array(
						'src' => $val,
						'title' => $object['title'][$key],
						'content' => $object['content'][$key],
						'link' => $object['link'][$key],
						'order' => $object['order'][$key],
						'catalogueid' => $catalogueid,
					);
				}
			}
			
		
			
			$flag = $this->Autoload_Model->_create_batch(array(
				'table' => 'slide',
				'data' => $_insert,
			));
			
			if($flag > 0){
				$this->session->set_flashdata('message-success', 'Tạo slide thành công');
				$error['flag'] = 0;
				$error['message'] = ''; 
				echo json_encode(array(
					'error' => $error,
				));die();
			}
			
			
		}else{
			$error['flag'] = 1;
			$error['message'] = validation_errors(); 
			echo json_encode(array(
				'error' => $error,
			));die();
		}
	}
	
	public function slide_catalogue(){
		$catalogueid = $this->input->get('id');
		$detailCatalogue = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title',
			'table' => 'slide_catalogue',
			'where' => array('id' => $catalogueid),
		));
		
		$config['total_rows'] = $this->Autoload_Model->_get_where(array(
			'table' => 'slide',
			'where' => array('catalogueid' => $catalogueid),
			'count'=>true,
		));
		$perpage = (!empty($form['perpage'])) ? $form['perpage'] : '2';
		$page = (!empty($form['page'])) ? $form['page'] : '1';
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('product/backend/product/view');
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
			$totalPage = ceil($config['total_rows']/$config['per_page']);
			$listPagination = $this->pagination->create_links();
			$page = ($page <= 0)?1:$page;
			$page = ($page > $totalPage)?$totalPage:$page;
			$page = $page - 1;
			$data['from'] = ($page * $config['per_page']) + 1;
			$data['to'] = ($config['per_page']*($page+1) > $config['total_rows']) ? $config['total_rows']  : $config['per_page']*($page+1);
			$data['slide_catalogue'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id, title, src, link, order',
				'table' => 'slide',
				'where' => array('catalogueid' => $catalogueid),
			),true);
		}
		
		
		$html = '';
		$html = $html.'<div class="row">';
			$html = $html.'<div class="col-lg-12">';
				$html = $html.'<h5 style="text-transform:uppercase;">'.$detailCatalogue['title'].'</h5>';
			$html = $html.'</div>';
			if(isset($data['slide_catalogue']) && is_array($data['slide_catalogue']) && count($data['slide_catalogue'])){
				$html = $html.'<div class="col-lg-12">';
					foreach($data['slide_catalogue'] as $key => $val){
						$html = $html.'<div class="file-box">';
							$html = $html.'<div class="file">';
								$html = $html.'<div href="#">';
									$html = $html.'<span class="corner"></span>';
									$html = $html.'<div class="image">';
										$html = $html.'<img alt="image" class="img-responsive" src="'.$val['src'].'">';
									$html = $html.'</div>';
									$html = $html.'<div class="file-name">';
										$html = $html .'<span style="font-size:10px;"><span style="font-weight:bold;">Chú thích</span>: '.((!empty($val['title'])) ? $val['title'] : '<span class="text-danger">Chưa xác định</span>').'</span>';
										$html = $html.'<br>';
										$html = $html.'<a style="font-size:10px;color:#676a6c;" href=""><span style="font-weight:bold;">Link</span>: '.((!empty($val['link'])) ? '<i style="color:blue;">'.$val['link'].'</i>' : '<span class="text-danger">Chưa xác định.</span>').'</a>';
										$html = $html.'<div class="file-action uk-flex uk-flex-middle uk-flex-space-between" style="margin-top:10px;">';
											$html = $html.'<a href ="" title="" class="edit-slide" data-id="'.$val['id'].'" style="font-size:10px;">Chỉnh sửa</a>';
											$html = $html.'<a type="button" class="ajax-delete" data-parent="file-box" data-title="Lưu ý: Dữ liệu sẽ không thể khôi phục. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!" data-module="slide" data-id="'.$val['id'].'" style="color:red;font-size:10px;"> Xóa</a>';
										$html = $html.'</div>';
									$html = $html.'</div>';
								$html = $html.'</div>';
							$html = $html.'</div>';
						$html = $html.'</div>';
					}
				$html = $html.'</div>';
			}
		$html = $html.'</div>';
		
			
		echo json_encode(array(
			'pagination' => (isset($listPagination)) ? $listPagination : '',
			'html' => (isset($html)) ? $html : '',
			'total' => $config['total_rows'],
		));die();
	}
	
	public function update_slide(){
		$post = $this->input->post('data');
		
		$_update = '';
		if(isset($post) && is_array($post) && count($post)){
			foreach($post as $key => $val){
				$_update[$val['name']] = $val['value'];
			}
		}

		// print_r($_update); exit;
		$flag = $this->Autoload_Model->_update(array(
			'where' => array('id' => $_update['id']),
			'table' => 'slide',
			'data' => $_update
		));
		
		echo json_encode(array(
			'flag' => 0,
			'message' => '',
			'src' => $_update['src'],
			'title' => $_update['title'],
			'content' => $_update['content'],
			'link' => $_update['link'],
		));die();
	}
	
	public function _CheckImage(){
		return true;
	}
	
	public function _Check($keyword = ''){
		$keyword = slug($keyword);
		$count = $this->Autoload_Model->_get_where(array(
			'select' => 'id',
			'table' => 'slide_catalogue',
			'where' => array('keyword' => $keyword),
			'count' => TRUE
		));
		if($count > 0){
			$this->form_validation->set_message('_Check','Từ khóa đã tồn tại');
			return false;
		}
		return true;
	}
}
