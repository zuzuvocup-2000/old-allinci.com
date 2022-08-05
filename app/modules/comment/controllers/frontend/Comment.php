<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library(array('myconstant'));
		
	}
	

	public function sent_comment(){
		if($this->input->post()){
			$param = $this->input->post('param');
			$cmtName = $this->input->post('cmtName');
			
			//validation form
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_error_delimiters(' ', ' /');
			$this->form_validation->set_rules('cmtName' , 'Họ tên' , 'trim|required');
			
			if($this->form_validation->run($this)){
				$html = '';
				//validation thành công tiến hành lưu dữ liệu
				
				// lưu db thông tin người reply
				$_insert = array(
					'fullname' => $cmtName,
					'comment' => $param['comment'],
					'rate' => $param['rate'],
					'module' => $param['module'],
					'detailid' => $param['detailid'],
					'publish' => 0,
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);
				
				// pre($_insert);die;
				$insertId = $this->Autoload_Model->_create(array(
					'table' => 'comment',
					'data' => $_insert,
				));
				
				if($insertId > 0){
					echo json_encode(array(
						'error' => 0,
						'message' => 'Cám ơn bạn đã đánh giá. Vui lòng chờ chúng tôi kiểm duyệt !',
					));die;
				}
			}
			echo json_encode(array(
				'error' => 1,
				'message' => validation_errors(),
			));die;
		}
		
	}
	
	public function get_title_rate(){
		$numStar = (int)$this->input->post('numStar');
		
		$htmlReview = review_render($numStar);
		
		echo json_encode(array(
			'htmlReview' => $htmlReview,
		));die;
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
