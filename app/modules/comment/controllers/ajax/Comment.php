<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment extends MY_Controller {

	public function __construct(){
		parent::__construct();
		if(!isset($this->auth) || is_array($this->auth) == FALSE || count($this->auth) == 0 ) redirect(BACKEND_DIRECTORY);
		$this->load->library(array('myconstant'));
		
		// pre($this->auth); die;
	}
	
	public function View(){
		$param = $this->input->get('param');
		$page = (int)$param['page'];
		$data['from'] = 0;
		$data['to'] = 0;
		
		//Tính tổng số bản ghi của trang danh mục
		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 10;
		$config['total_rows'] = $this->Autoload_Model->_get_where(array( //trả lại all số bản ghi
			'select' => 'id',
			'table' => 'comment',
			'where' => ($param['module'] != '0')? array('module' => $param['module']):'',
			'keyword' => '(fullname LIKE \'%'.$param['keyword'].'%\' OR email LIKE \'%'.$param['keyword'].'%\' OR phone LIKE \'%'.$param['keyword'].'%\' OR comment LIKE \'%'.$param['keyword'].'%\')',
			'count' => TRUE,
		));
		
		$listComment = '';
		$display ='<div class="text-small mb10">Hiển thị từ 0 đến 0 trên tổng số 0 bản ghi</div>';
		
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('comment/backend/comment/view');
			$config['suffix'] = $this->config->item('url_suffix').(!empty($_SERVER['QUERY_STRING'])?('?'.$_SERVER['QUERY_STRING']):'');
			$config['first_url'] = $config['base_url'].$config['suffix'];
			$config['per_page'] = $perpage;
			$config['cur_page'] = $page;
			$config['uri_segment'] = 5;
			$config['num_links'] = 1;
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
			$data['paginationList'] = $this->pagination->create_links();
			
			$totalPage = ceil($config['total_rows']/$config['per_page']);
			$page = ($page <= 0)?1:$page;
			$page = ($page > $totalPage)?$totalPage:$page;
			$page = $page - 1;
			$data['from'] = ($page * $config['per_page']) + 1;
			$data['to'] = ($config['per_page']*($page+1) > $config['total_rows']) ? $config['total_rows']  : $config['per_page']*($page+1);
			$data['listComment'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id, fullname, email, phone, module, detailid, parentid, rate, comment, image, publish',
				'table' => 'comment',
				'where' => ($param['module'] != '0')? array('module' => $param['module']):'',
				'keyword' => '(fullname LIKE \'%'.$param['keyword'].'%\' OR email LIKE \'%'.$param['keyword'].'%\' OR phone LIKE \'%'.$param['keyword'].'%\' OR comment LIKE \'%'.$param['keyword'].'%\')',
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'order_by' => 'fullname asc, id desc',
			), TRUE);
			
			// pre($data['listComment']);
			if(isset($data['listComment']) && is_array($data['listComment']) && count($data['listComment'])){
				$display .= '<div class="text-small mb10">Hiển thị từ '.$data['from'].' đến '.$data['to'].' trên tổng số '.$config['total_rows'].' bản ghi</div>';
				foreach($data['listComment'] as $key => $val){
					$detail = $this->Autoload_Model->_get_where(array(
						'select' => 'title',
						'table' => $val['module'],
						'where' => array('id' => $val['detailid']),
					));
					$listComment .= '<tr>';
						$listComment .= '<td class="text-left" style="width: 40px;">
											<input type="checkbox" name="checkbox[]" value="'.$val['id'].'" class="checkbox-item">
											<label for="" class="label-checkboxitem"></label>
										</td>';
						$listComment .= '<td class="text-center">'.$val['id'].'</td>';
						$listComment .= '<td class="text-left">'.$val['fullname'].'</td>';
						$listComment .= '<td class="text-center">'.(($val['phone'] != '')? $val['phone']:'-').'</td>';
						$listComment .= '<td class="text-left">'.(($val['email'] != '')? $val['email']:'-').'</td>';
						$listComment .= '<td class="text-center">'.$this->myconstant->get_data($val['module']).'</td>';
						$listComment .= '<td class="text-center"><a href="" target="_blank" title="'.$detail['title'].'"><i class="fa fa-link"></i></a><span>(0)</span></td>';
						$listComment .= '<td class="text-left text-comment"><span class="line-1">'.(($val['comment'] != '')? $val['comment']:'-').'</span></td>';
						$listComment .= '<td class="text-center">'.(($val['parentid'] > 0)? '-' : '<span class="rating order-1" data-stars="5" data-default-rating="'.$val['rate'].'" disabled ></span>').'</td>';
						$listComment .= '<td>
											<div class="switch uk-flex uk-flex-center">
												<div class="switch">
													<div class="onoffswitch">
														<input type="checkbox" '.(($val['publish'] == '1')? 'checked':'').' class="onoffswitch-checkbox publish" data-id="'.$val['id'].'" id="publish-'.$val['id'].'">
														<label class="onoffswitch-label" for="publish-'.$val['id'].'">
															<span class="onoffswitch-inner"></span>
															<span class="onoffswitch-switch"></span>
														</label>
													</div>
												</div>
											</div>
										</td>';
										
						$listComment .= '<td class="uk-flex uk-flex-center">
											<a type="button" href="'.(site_url('comment/backend/comment/update/'.$val['id'])).'" data-commentid="'.$val['id'].'" class="btn btn-sm btn-edit btn-primary"><i class="fa fa-edit"></i></a>
											<a type="button" data-title="" data-name="" data-module="comment" data-id="'.$val['id'].'" class="btn btn-sm btn-trash btn-danger ajax-delete"><i class="fa fa-trash"></i></a>
										</td>';
					$listComment .= '</tr>';
				}
			}
		}else{
			$listComment .= '<tr>';
				$listComment .= '<td colspan="11">';
					$listComment .= '<small class="text-danger">Không có dữ liệu phù hợp</small>';
				$listComment .= '</td>';						
			$listComment .= '</tr>';
		}
		
		echo json_encode(array(
			'display' => $display,
			'listComment' => $listComment,
			'paginationList' => isset($data['paginationList'])? $data['paginationList']:'',
		));die;
	}
	
	public function ajax_delete(){
		$id = (int)$this->input->post('id');
		$table = $this->input->post('table');
		
		//tiến hành xóa dữ liệu với id vừa lấy được
		$result = $this->Autoload_Model->_delete(array(
			'where' => array('id' => $id),
			'table' => $table,
		));
		
		if($result > 0){
			$error = array(
				'flag' => 0,
				'message' => '',
			);
		}else{
			$error = array(
				'flag' => 1,
				'message' => 'Xóa không thành công',
			);
		}
		echo json_encode(array(
			'error' => $error,
		));die;
	}
	
	//xóa nhiều
	//################  xóa nhóm => xóa all thành viên trong nhóm ################################
	public function ajax_group_delete(){
		
		$param = $this->input->post('param');
		
		//tiến hành xóa dữ liệu với danh sách id vừa lấy được
		if(isset($param['list']) && is_array($param['list']) && count($param['list'])){
			foreach($param['list'] as $key => $val){
				$result = $this->Autoload_Model->_delete(array(
					'where' => array('id' => $val),
					'table' => $param['table'],
				));
				
				if($result <= 0){
					$error = array(
						'flag' => 1,
						'message' => 'Xóa không thành công',
					);
					
					echo json_encode(array(
						'error' => $error,
					));die;
				}
			}
			//kết thúc quá trình delete dữ liệu
			$error = array(
				'flag' => 0,
				'message' => '',
			);
			
			echo json_encode(array(
				'error' => $error,
			));die;
		}
	}
	
	public function get_select2(){
		$module = $this->input->post('module');
		$detailid = $this->input->post('detailid');
		
		$detailData = $this->Autoload_Model->_get_where(array(
			'select' => 'id, title',
			'table' => $module,
			'where' => array(
				'id' => $detailid,
			),
		), TRUE);
		
		$temp = '';
		if(isset($detailData) && is_array($detailData) && count($detailData)){
			foreach($detailData as $key => $val){
				$temp[] = array(
					'id'=> $val['id'],
					'text' => $val['title'],
				);
			}
		}
		echo json_encode(array('items' => $temp));die();
	}
	
	public function status(){
		$id = $this->input->post('objectid');
		$object = $this->Autoload_Model->_get_where(array(
			'select' => 'id, publish',
			'table' => 'comment',
			'where' => array('id' => $id),
		));
		
		$_update['publish'] = (($object['publish'] == 1)? 0:1);
		$this->Autoload_Model->_update(array(
			'where' => array('id' => $id),
			'table' => 'comment',
			'data' => $_update,
		));
	}
	
	public function get_title_rate(){
		$numStar = (int)$this->input->post('numStar');
		
		$htmlReview = review_render($numStar);
		
		echo json_encode(array(
			'htmlReview' => $htmlReview,
		));die;
	}
	
	public function reply_comment(){
		$param = $this->input->post('param');
		$html = '';
		
		// lưu db thông tin người reply
		$_insert = array(
			'fullname' => $this->auth['fullname'],
			// 'phone' => $this->auth['phone'],
			'email' => $this->auth['email'],
			'parentid' => $param['parentid'],
			'comment' => $param['comment'],
			'image' => isset($param['image'])? json_encode($param['image']) : '',
			'module' => $param['module'],
			'detailid' => $param['detailid'],
			'publish' => 1,
			'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
		);
		
		// pre($_insert);die;
		$insertId = $this->Autoload_Model->_create(array(
			'table' => 'comment',
			'data' => $_insert,
		));
		
		if($insertId > 0){
			// html reply
			$_insert['id'] = $insertId;
			$param['id'] = $insertId;
			$param['dataInfo'] = base64_encode(json_encode($_insert)); //lưu lại thông tin id vừa đc insert
			$html = $this->get_html_reply_comment($param);
		}
		
		echo json_encode(array(
			'html' => $html,
		));die;
	}
	
	public function get_html_reply_comment($param = ''){
		$html = '';
		
		$html .= '<li>';
			$html .= '<div class="comment">';
				$html .= '<div class="uk-flex uk-flex-middle uk-flex-space-between">';
					$html .= '<div class="cmt-profile">';
						$html .= '<div class="uk-flex uk-flex-middle">';
							$html .= '<div class="_cmt-avatar"><img src="template/avatar.png" alt="" class="img-sm"></div>';
							$html .= '<div class="_cmt-name">'.$this->auth['fullname'].'</div>';
							$html .= '<i>QTV</i>';
						$html .= '</div>';
					$html .= '</div>';
					$html .= '<div class="uk-flex uk-flex-middle">';
						$html .= '<div class="edit-cmt"><a type = "button" title="" class="btn-edit" data-info="'.$param['dataInfo'].'" data-id="'.$param['id'].'" data-table="comment">Sửa</a></div>';
						$html .= '<div class="delete-cmt"><a type="button" title="" class="btn-delete ajax-delete" data-title="Lưu ý: Dữ liệu sẽ không thể khôi phục. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!" data-id = "'.$param['id'].'" data-table = "comment" data-closest="li" style="color: #e74c3c;">Xóa</a></div>';
					$html .= '</div>';
				$html .= '</div>';
				$html .= '<div class="cmt-content">';
					$html .= '<p>'.$param['comment'].'</p>';
					if(isset($param['image']) && is_array($param['image']) && count($param['image'])){
						$html .= '<div class="gallery-block mb10">';
							$html .= '<ul class="uk-list uk-flex uk-flex-middle clearfix lightBoxGallery">';
								foreach($param['image'] as $k => $v){
									$html .= '<li>';
										$html .= '<div class="thumb">';
											$html .= '<a href="'.$v.'" title="" data-gallery="#blueimp-gallery-'.$param['parentid'].'-'.$param['id'].'"><img src="'.$v.'" class="img-md"></a>';
										$html .= '</div>';
									$html .= '</li>';
								}
							$html .= '</ul>';
						$html .= '</div>';
					}
					$html .= '<i class="fa fa-clock-o"></i> <time class="timeago meta" datetime="'.gmdate('Y-m-d H:i:s', time() + 7*3600).'"></time>';
				$html .= '</div>';
			$html .= '</div>';
		$html .= '</li>';
		
		return $html;
	}
	
	public function update_comment(){
		$param = $this->input->post('param');
		//update với dữ liệu vừa lấy đc
		$_update = array(
			'comment' => $param['comment'],
			'image' => isset($param['image'])? json_encode($param['image']) : '',
			'updated' => gmdate('Y-m-d H:i:s', time() + 7*3600),
		);
		$result = $this->Autoload_Model->_update(array( //trả lại số dòng thay đổi trong db
			'where' => array('id' => $param['id']),
			'table' => 'comment',
			'data' => $_update,
		));
		$html = '';
		if($result > 0){ //cập nhật thành công
			$flagError = 0;
			//cập nhật lại data-info
			$param['dataInfo'] = array_merge($param['dataInfo'], $_update);
			
			$param['dataInfo'] = base64_encode(json_encode($param['dataInfo']));
			
			$html = $this->get_html_update_comment($param);
		}else{
			$flagError = 1;
		}
		
		echo json_encode(array(
			'flagError' => $flagError,
			'html' => $html,
		));die;
	}
	
	public function get_html_update_comment($param = ''){
		$html = '';
		
		$html .= '<div class="comment">';
			$html .= '<div class="uk-flex uk-flex-middle uk-flex-space-between">';
				$html .= '<div class="cmt-profile">';
					$html .= '<div class="uk-flex uk-flex-middle">';
						$html .= '<div class="_cmt-avatar"><img src="template/avatar.png" alt="" class="img-sm"></div>';
						$html .= '<div class="_cmt-name">'.$param['fullname'].'</div>';
						$html .= '<i>QTV</i>';
					$html .= '</div>';
				$html .= '</div>';
				$html .= '<div class="uk-flex uk-flex-middle">';
					$html .= '<div class="edit-cmt"><a type = "button" title="" class="btn-edit" data-info="'.$param['dataInfo'].'"  data-id="'.$param['id'].'" data-table="comment">Sửa</a></div>';
					$html .= '<div class="delete-cmt"><a type="button" title="" class="btn-delete ajax-delete" data-title="Lưu ý: Dữ liệu sẽ không thể khôi phục. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!" data-id = "'.$param['id'].'" data-table = "comment" data-closest="li" style="color: #e74c3c;">Xóa</a></div>';
				$html .= '</div>';
			$html .= '</div>';
			$html .= '<div class="cmt-content">';
				$html .= '<p>'.$param['comment'].'</p>';
				if(isset($param['image']) && is_array($param['image']) && count($param['image'])){
					$html .= '<div class="gallery-block mb10">';
						$html .= '<ul class="uk-list uk-flex uk-flex-middle clearfix lightBoxGallery">';
							foreach($param['image'] as $k => $v){
								$html .= '<li>';
									$html .= '<div class="thumb">';
										$html .= '<a href="'.$v.'" title="" data-gallery="#blueimp-gallery-'.$param['parentid'].'-'.$param['id'].'"><img src="'.$v.'" class="img-md"></a>';
									$html .= '</div>';
								$html .= '</li>';
							}
						$html .= '</ul>';
					$html .= '</div>';
				}
				$html .= '<i class="fa fa-clock-o"></i> <time class="timeago meta" datetime="'.gmdate('Y-m-d H:i:s', time() + 7*3600).'"></time>';
			$html .= '</div>';
		$html .= '</div>';
		
		return $html;
	}
	
	public function loadmore_comment(){
		$param = $this->input->post('param');
		
		$listComment = comment_render($param);
		$html = $this->get_html_loadmore_comment($listComment);
		echo json_encode(array(
			'html' => $html,
		));die;
	}
	
	public function get_html_loadmore_comment($data = ''){
		$html = '';
		if(isset($data) && is_array($data) && count($data)){
			foreach($data as $key => $val){
				$html .= '<li>';
					$html .= '<div class="comment">';
						$html .= '<div class="uk-flex uk-flex-middle uk-flex-space-between">';
							$html .= '<div class="cmt-profile">';
								$html .= '<div class="uk-flex uk-flex-middle">';
									$html .= '<div class="_cmt-avatar"><img src="template/avatar.png" alt="" class="img-sm"></div>';
									$html .= '<div class="_cmt-name">'.$val['fullname'].'</div>';
									$html .= '<div class="label label-primary _cmt-tag">Khách hàng</div>';
								$html .= '</div>';
							$html .= '</div>';
							$html .= '<div class="cmt-time">';
								$html .= '<i class="fa fa-clock-o"></i> ';
								$html .= '<time class="timeago meta" datetime="'.(($val['updated'] > $val['created']) ? $val['updated']: $val['created']).'"></time>';
							$html .= '</div>';
						$html .= '</div>';
						$html .= '<div class="cmt-content">';
							$html .= '<p>'.$val['comment'].'</p>';
							
							$album = json_decode($val['image']);
							
							if(isset($album) && is_array($album) && count($album)){
								$html .= '<div class="gallery-block mb10">';
									$html .= '<ul class="uk-list uk-flex uk-flex-middle clearfix lightBoxGallery">';
										foreach($album as $k => $v){
											$html .= '<li>';
												$html .= '<div class="thumb">';
													$html .= '<a href="<?php echo $v;?>" title="" data-gallery="#blueimp-gallery-'.$val['id'].'"><img src="'.$v.'" class="img-md"></a>';
												$html .= '</div>';
											$html .= '</li>';
										}
									$html .= '</ul>';
								$html .= '</div>';
							}
							$html .= '<div class="_cmt-reply">';
								$html .= '<a href="" title="" class="btn-reply" data-comment="1" data-id="'.$val['id'].'" data-module ="'.$val['module'].'" data-detailid = "'.$val['detailid'].'">Trả lời</a> ';
								$html .= '<span class="mr5 num-reply" data-num="'.(isset($val['child'])? count($val['child']) : 0).'">('.(isset($val['child'])? count($val['child']) : 0).')</span> ';
								$html .= '<span class="rating order-1 rt-cmt" data-stars="5" data-default-rating="'.$val['rate'].'" disabled ></span>';
							$html .= '</div>';
							$html .= '<div class="show-reply"></div>';
							$html .= '<div class="wrap-list-reply">';
								$html .= '<ul class="list-reply uk-list uk-clearfix" id="reply-to-'.$val['id'].'">';
									if(isset($val['child']) && is_array($val['child']) && count($val['child'])){
										foreach($val['child'] as $keyChild => $valChild){
											$html .= '<li>';
												$html .= '<div class="comment">';
													$html .= '<div class="uk-flex uk-flex-middle uk-flex-space-between">';
														$html .= '<div class="cmt-profile">';
															$html .= '<div class="uk-flex uk-flex-middle">';
																$html .= '<div class="_cmt-avatar"><img src="template/avatar.png" alt="" class="img-sm"></div>';
																$html .= '<div class="_cmt-name">'.$valChild['fullname'].'</div>';
																$html .= '<i>QTV</i>';
															$html .= '</div>';
														$html .= '</div>';
														$html .= '<div class="uk-flex uk-flex-middle toolbox-cmt">';
															$html .= '<div class="edit-cmt"><a type="button" title="" class="btn-edit" data-info="'.base64_encode(json_encode($valChild)).'" data-id="'.$valChild['id'].'" data-table="comment">Sửa</a></div>';
															$html .= '<div class="delete-cmt">';
																$html .= '<a type="hidden" title="" class="ajax-delete" data-title="Lưu ý: Dữ liệu sẽ không thể khôi phục. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!" data-id = "'.$valChild['id'].'" data-table = "comment" data-closest="li" ></a>';
																$html .= '<a type="button" title="" class="btn-delete" style="color: #e74c3c;">Xóa</a>';
															$html .= '</div>';
														$html .= '</div>';
													$html .= '</div>';
													$html .= '<div class="cmt-content">';
														$html .= '<p>'.$valChild['comment'].'</p>';
														$albumReply = json_decode($valChild['image']);
														if(isset($albumReply) && is_array($albumReply) && count($albumReply)){
															$html .= '<div class="gallery-block mb10">';
																$html .= '<ul class="uk-list uk-flex uk-flex-middle clearfix lightBoxGallery">';
																foreach($albumReply as $kR => $vR){
																	$html .= '<li>';
																		$html .= '<div class="thumb">';
																			$html .= '<a href="'.$vR.'" title="" data-gallery="#blueimp-gallery-'.$val['id'].'-'.$valChild['id'].'"><img src="'.$vR.'" class="img-md"></a>';
																		$html .= '</div>';
																	$html .= '</li>';
																}
																$html .= '</ul>';
															$html .= '</div>';
														}
														$html .= '<i class="fa fa-clock-o"></i> ';
														$html .= '<time class="timeago meta" datetime="'.(($valChild['updated'] > $valChild['created'])? $valChild['updated']:$valChild['created']).'"></time>';
													$html .= '</div>';
												$html .= '</div>';
											$html .= '</li>';
										}
									}
								$html .= '</ul>';
							$html .= '</div>';
						$html .= '</div>';
					$html .= '</div>';
				$html .= '</li>';
			}
		}
	
		return $html;
	}
	
}
