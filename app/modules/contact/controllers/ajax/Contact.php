<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends MY_Controller {

	public function __construct(){
		parent::__construct();
	}

	// public function sleep(){
	// 	sleep(2);
	// }

	public function bookmark(){
		// echo 1;die;

		$id = $this->input->post('idContact');
		$bookmark = $this->input->post('bookmark');
		$_update['bookmark'] = ($bookmark == 0) ? 1 : 0 ;
		$this->Autoload_Model->_update(array(
			'where' => array('id' => $id),
			'table' => 'contact',
			'data' => $_update,
		));
	}

	public function viewed(){
		$id = $this->input->post('id');
		$viewed = $this->input->post('viewed');
		// echo $viewed; die();
		if($viewed == 0){
			$viewed =1;
			$_update['viewed'] = $viewed;
			$result = $this->Autoload_Model->_update(array(
				'where' => array('id' => $id),
				'table' => 'contact',
				'data' => $_update,
			));
			echo $result; die();
		}
	}

	public function listContact(){
		$page = (int)$this->input->get('page');
		$data['from'] = 0;
		$data['to'] = 0;

		$perpage = ($this->input->get('perpage')) ? $this->input->get('perpage') : 5;
		$keyword = $this->db->escape_like_str($this->input->get('keyword'));
		$catalogueid = (int)$this->input->get('catalogueid');
		if($catalogueid > 0){
			$config['total_rows'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id',
				'table' => 'contact',
				'count' => TRUE,
				// 'where' => array('catalogueid' => $catalogueid),
				'where' => ($catalogueid ==0) ? '' : array( 'catalogueid' => $catalogueid) ,
				'keyword' => '(fullname LIKE \'%'.$keyword.'%\')',
			));
		}else{
			$config['total_rows'] = $this->Autoload_Model->_get_where(array(
				'select' => 'id',
				'table' => 'contact',
				'count' => TRUE,
				'keyword' => '(fullname LIKE \'%'.$keyword.'%\')',
			));
		}
		$html = '';
		if($config['total_rows'] > 0){
			$this->load->library('pagination');
			$config['base_url'] = base_url('contact/backend/contact/view');
			$config['suffix'] = $this->config->item('url_suffix').(!empty($_SERVER['QUERY_STRING'])?('?'.$_SERVER['QUERY_STRING']):'');
			$config['first_url'] = $config['base_url'].$config['suffix'];
			$config['per_page'] = $perpage;
			$config['cur_page'] = $page;
			$config['page'] = $page;
			$config['uri_segment'] = 5;
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

			$listContact = $this->Autoload_Model->_get_where(array(
				'select' => 'id, file, viewed, fullname, email, phone, created, subject, message, bookmark,(SELECT title FROM contact_catalogue WHERE contact_catalogue.id = contact.catalogueid) as catalogueTitle',
				'table' => 'contact',
				'where' => ($catalogueid ==0) ? '' : array( 'catalogueid' => $catalogueid) ,
				'keyword' => '(fullname LIKE \'%'.$keyword.'%\')',
				'start' => $page * $config['per_page'],
				'limit' => $config['per_page'],
				'order_by' => 'viewed asc, created desc',
			), TRUE);	
			if(isset($listContact) && is_array($listContact) && count($listContact)){ 
				foreach($listContact as $key => $val){ 
					$html = $html .'<tr class="gradeX" id="">';
					$html = $html .'<td class="pd-top text-center pdt25" >';
					$html = $html .'<input type="checkbox" name="checkbox[]" value="'.$val['id'].'" class="checkbox-item">';
					$html = $html .'<label for="" class="label-checkboxitem"></label>';
					$html = $html .'</td>';
					$html = $html .'<td class="pd-top text-center pdt25"><i class="fa fa-star '.(($val['bookmark'] == 1) ? ' text-yellow' : '').'" data-id="'.$val['id'].'" data-bookmark="'.$val['bookmark'].'"></i>';
					$html = $html .'</td>';
					$html = $html .'<td class="pd-top text-center pdt25">';
					if(strlen($val['file']) > 0){
						$html = $html .'<i class="fa fa-paperclip"></i>';
					}
					$html = $html .'</td>';
					$html = $html .'<td class="pd-top">';
					$html = $html .'<a data-id="'.$val['id'].'" data-viewed="'.$val['viewed'].'" class="detail-contact title-1 '.(($val['viewed'] == 0) ? ' text-blue' : '').'" href="" >'.$val['fullname'].'</a>';
					$html = $html .'<div class="">';
					$html = $html .'<a data-id="'.$val['id'].'" data-viewed="'.$val['viewed'].'" href="" class="detail-contact subtitle-1 '.(($val['viewed'] == 0) ? ' text-blue-1' : '').'" title="'.$val['phone'].'" class="subtitle-1">';
					$html = $html .''.$val['phone'].'';
					$html = $html .'</a>';
					$html = $html .'</div>';
					$html = $html .'</td> ';
					$html = $html .'<td class="pd-top">';
					$html = $html .'<a data-id="'.$val['id'].'" data-viewed="'.$val['viewed'].'" class="maintitle detail-contact title-1 '.(($val['viewed'] == 0) ? ' text-blue' : '').'" style="" href="" title="">';
					$html = $html .''.$val['subject'].'';
					$html = $html .'</a>';
					$html = $html .'<div class="">';
					$html = $html .'<a data-id="'.$val['id'].'" data-viewed="'.$val['viewed'].'" href="" class="detail-contact subtitle-1 '.(($val['viewed'] == 0) ? ' text-blue-1' : '').'" title="">';
					$html = $html .''.$val['message'].'';
					$html = $html .'</a>';
					$html = $html .'</div>';
					$html = $html .'</td>';
					$html = $html .'<td class="pd-top text-center">'.gettime($val['created'],'d/m/Y h:i:s').'</td> ';
					$html = $html .'<td class="text-center actions" style="padding-top:18px;">';
					$html = $html .'<a  type="button" class="btn btn-danger btn-delete ajax-delete"  data-id="'.$val['id'].'" data-title="L??u ??: Khi b???n x??a nh??m, to??n b??? th??nh vi??n trong nh??m n??y s??? b??? x??a. H??y ch???c ch???n r???ng b???n mu???n th???c hi???n h??nh ?????ng n??y!" data-router="" data-module="contact" data-child=""><i class="fa fa-trash"></i></a>';
					$html = $html .'</td>';
					$html = $html .'</tr>';
				}
			}
		}else{ 
			$html = $html .'<tr><td colspan="9"><small class="text-danger">Kh??ng c?? d??? li???u ph?? h???p</small></td></tr> ';
		}
		echo json_encode(array(
			'pagination' => (isset($listPagination)) ? $listPagination : '',
			'html' => (isset($html)) ? $html : '',
			'total' => $config['total_rows'],
		));die();
	}



	/* ================ delete ======================= */
	public function ajax_delete(){
		$param['module'] = $this->input->post('module');
		$param['id'] = (int)$this->input->post('id');
		$param['child'] = (int)$this->input->post('child');

		$flag = $this->Autoload_Model->_delete(array(
			'where' => array('id' => $param['id']),
			'table' => $param['module']
		));
		echo $flag; die();
	}

	public function ajax_group_delete(){
		$param = $this->input->post('param');
		if(isset($param['list']) && is_array($param['list']) && count($param['list'])){
			foreach($param['list'] as $key => $val){
				$result = $this->Autoload_Model->_delete(array(
					'where' => array('id' => $val),
					'table' => $param['module'],
				));
				if($result > 0){
					$countChild = $this->Autoload_Model->_get_where(array(
						'where' => array('catalogueid' => $val),
						'table' => 'contact',
						'count' => TRUE,
					));
					if($countChild >0){
						$resultChild = $this->Autoload_Model->_delete(array(
							'where' => array('catalogueid' => $val),
							'table' => 'contact',
						));
						if($resultChild <= 0){
							$error = array(
								'flag' => 1,
								'message' => 'X??a kh??ng th??nh c??ng ph???n t??? con trong nh??m',
							);
							echo json_encode(array(
								'error' => $error,
							));die;
						}
					}else{
						$error = array(
							'flag' => 1,
							'message' => 'X??a kh??ng th??nh c??ng',
						);
						echo json_encode(array(
							'error' => $error,
						));die;
					}
				}
				$error = array(
					'flag' => 0,
					'message' => '',
				);
				echo json_encode(array(
					'error' => $error,
				));die;
			}
		}
	}
	
	
	public function phone_call(){
		$this->load->library('form_validation');
		$this->form_validation->CI =& $this;
		$this->form_validation->set_error_delimiters('','/');
		$this->form_validation->set_rules('phone', 'S??? ??i???n tho???i', 'trim|required|is_numeric|min_length[10]|max_length[11]');
		if($this->form_validation->run($this)){
			$this->load->library(array('mailbie'));
			$this->mailbie->sent(array(
				'to' => 'tuannc.dev@gmail.com',
				'cc' => 'minhphuong2811.tb@gmail.com',
				'subject' => 'Th??ng tin kh??ch h??ng: ',
				'message' => '<div>S??? ??i???n tho???i: <span style="color:red;">'.$this->input->post('phone').'</span></div>',
			));
			$this->session->set_flashdata('message-success', 'G???i th??ng tin th??nh c??ng, Ch??ng t??i s??? li??n h??? l???i v???i b???n trong th???i gian s???m nh???t');
			$error['flag'] = 0;
			$error['message'] = ''; 
		}else{
			$error['flag'] = 1;
			$error['message'] = validation_errors(); 
			
		}
		echo json_encode(array(
			'error' => $error,
		));die();
	}

	public function save_contact_register(){
		if($this->input->post('data')){
			$data = $this->input->post('data');
			$email = $this->input->post('email');
			
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('', ' / ');
			$this->form_validation->set_rules('email','Email','trim|required|valid_email');
			
			
			if($this->form_validation->run($this)){
				$error = '';
				//validate th??nh c??ng ti???n h??nh l??u th??ng tin v??o db contact
				$_insert = array(
					'subject' => '????ng k?? nh???n b???n tin ??u ????i',
					'email' => $email,
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);
				
				$insertId = $this->Autoload_Model->_create(array(
					'table' => 'contact',
					'data' => $_insert,
				));

				//g???i email
				$this->load->library('mailbie');
					
				$this->mailbie->sent(array(
					'to' => array('tudo2109@gmail.com'),
					'cc' => '',
					'subject' => 'Y??u c???u ????ng k?? nh???n th??ng b??o t??? h??? th???ng '.$this->general['contact_website'].'',
					'message' =>	'<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
									<section class="mail-content" style="border: 1px solid #E5E5E5;">
										<div class="header" style="background: #0077bc; padding: 15px;border-bottom: 1px solid #E5E5E5;">
											<h2><span style="display: block; color: #fff; font-size: 18px;text-transform: uppercase;text-align: center;">Th??ng tin kh??ch h??ng</span></h2>
										</div>
										<div class="content" style="padding: 0 15px;">
											<p style="margin-bottom: 10px;"><label class="md-label" style="font-size: 13px;font-weight: 600; margin-right: 20px;">Email: </label><span style="text-transform: capitalize;">'.$email.'</span></p>
										</div>
									</section>
									',
				));	

				$this->mailbie->sent(array(
					// 'to' => array($this->general['contact_email']),
					'to' => array($email),
					'cc' => '',
					'subject' => 'Th??ng b??o t??? h??? th???ng '.$this->general['contact_website'].'',
					'message' =>	'<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
									<section class="mail-content" style="border: 1px solid #E5E5E5;">
										<div class="header" style="background: #0077bc; padding: 15px;border-bottom: 1px solid #E5E5E5;">
											<h2><span style="display: block; color: #fff; font-size: 18px;text-transform: uppercase;text-align: center;">'.$this->general['contact_website'].' ch??o m???ng b???n</span></h2>
										</div>
										<div class="content" style="padding: 0 15px;">
											'.$this->general['email_content_1'].'
										</div>

									</section>
									',
				));

				
				if($insertId > 0){
					echo json_encode(array(
						'error' => $error,
					));die;
				}
				
				echo json_encode(array(
					'error' => '???? c?? l???i x???y ra. Xin vui l??ng quay l???i sau ??t ph??t !',
				));die;
				
			}else{
				echo json_encode(array(
					'error' => validation_errors(),
				)); die;
			}
			
		}
	}


	public function save_info_contact(){
		if($this->input->post('data')){
			$data = $this->input->post('data');
			$prd_name = $this->input->post('prd_name');
			$fullname = $this->input->post('fullname');
			$email = $this->input->post('email');
			$phone = $this->input->post('phone');
			$message = $this->input->post('message');
			
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('', ' / ');
			$this->form_validation->set_rules('fullname','H??? t??n','trim|required');
			$this->form_validation->set_rules('email','Email','trim|required|valid_email');
			$this->form_validation->set_rules('phone','S??? ??i???n tho???i','trim|required|is_numeric|min_length[10]|max_length[11]');
			
			
			if($this->form_validation->run($this)){
				$error = '';
				//validate th??nh c??ng ti???n h??nh l??u th??ng tin v??o db contact
				$_insert = array(
					'fullname' => $fullname,
					'email' => $email,
					'phone' => $phone,
					'message' => $message,
					'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
				);
				
				$insertId = $this->Autoload_Model->_create(array(
					'table' => 'contact',
					'data' => $_insert,
				));


				//g???i email
				$this->load->library('mailbie');
					
				$this->mailbie->sent(array(
					'to' => array('tudo2109@gmail.com'),
					'cc' => '',
					'subject' => 'Y??u c???u ????ng k?? b??o gi?? s???n ph???m t??? h??? th???ng '.$this->general['contact_website'].'',
					'message' =>	'<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
									<section class="mail-content" style="border: 1px solid #E5E5E5;">
										<div class="header" style="background: #0077bc; padding: 15px;border-bottom: 1px solid #E5E5E5;">
											<h2><span style="display: block; color: #fff; font-size: 18px;text-transform: uppercase;text-align: center;">Th??ng tin kh??ch h??ng</span></h2>
										</div>
										<div class="content" style="padding: 0 15px;">
											<p style="margin-bottom: 10px;"><label class="md-label" style="font-size: 13px;font-weight: 600; margin-right: 20px;">S???n ph???m: </label><span style="text-transform: uppercase;">'.$prd_name.'</span></p>
											<p style="margin-bottom: 10px;"><label class="md-label" style="font-size: 13px;font-weight: 600; margin-right: 20px;">H??? t??n: </label><span style="text-transform: capitalize;">'.$fullname.'</span></p>
											<p style="margin-bottom: 10px;"><label class="md-label" style="font-size: 13px;font-weight: 600; margin-right: 20px;">Email: </label><span style="">'.$email.'</span></p>
											<p style="margin-bottom: 10px;"><label class="md-label" style="font-size: 13px;font-weight: 600; margin-right: 20px;">??i???n tho???i: </label><span style="text-transform: capitalize;">'.$phone.'</span></p>
											<p style="margin-bottom: 10px;"><label class="md-label" style="font-size: 13px;font-weight: 600; margin-right: 20px;">Nhu c???u c??? th???: </label><span style="">'.(!empty($message)? $message: 'G???i b??o gi??').'</span></p>
										</div>
									</section>
									',
				));	

				$this->mailbie->sent(array(
					'to' => array($email),
					'cc' => '',
					'subject' => 'Y??u c???u ????ng k?? b??o gi?? s???n ph???m t??? h??? th???ng '.$this->general['contact_website'].'',
					'message' =>	'<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
									<section class="mail-content" style="border: 1px solid #E5E5E5;">
										<div class="header" style="background: #0077bc; padding: 15px;border-bottom: 1px solid #E5E5E5;">
											<h2><span style="display: block; color: #fff; font-size: 18px;text-transform: uppercase;text-align: center;">Th??ng tin kh??ch h??ng</span></h2>
										</div>
										<div class="content" style="padding: 0 15px;">
											<p style="margin-bottom: 10px;"><label class="md-label" style="font-size: 13px;font-weight: 600; margin-right: 20px;">S???n ph???m: </label><span style="text-transform: uppercase;">'.$prd_name.'</span></p>
											<p style="margin-bottom: 10px;"><label class="md-label" style="font-size: 13px;font-weight: 600; margin-right: 20px;">H??? t??n: </label><span style="text-transform: capitalize;">'.$fullname.'</span></p>
											<p style="margin-bottom: 10px;"><label class="md-label" style="font-size: 13px;font-weight: 600; margin-right: 20px;">Email: </label><span style="">'.$email.'</span></p>
											<p style="margin-bottom: 10px;"><label class="md-label" style="font-size: 13px;font-weight: 600; margin-right: 20px;">??i???n tho???i: </label><span style="text-transform: capitalize;">'.$phone.'</span></p>
											<p style="margin-bottom: 20px;"><label class="md-label" style="font-size: 13px;font-weight: 600; margin-right: 20px;">Nhu c???u c??? th???: </label><span style="">'.(!empty($message)? $message: 'G???i b??o gi??').'</span></p>
											'.$this->general['email_content_2'].'
										</div>
									</section>
									',
				));	
				
				if($insertId > 0){
					echo json_encode(array(
						'error' => $error,
					));die;
				}
				
				echo json_encode(array(
					'error' => '???? c?? l???i x???y ra. Xin vui l??ng quay l???i sau ??t ph??t !',
				));die;
				
			}else{
				echo json_encode(array(
					'error' => validation_errors(),
				)); die;
			}
			
		}
	}


	public function contact_baogia(){
		$prd_name = $this->input->post('prd_name');
		$fullname = $this->input->post('fullname');
		$phone = $this->input->post('phone');
		$email = $this->input->post('email');
		$message = $this->input->post('message');
		
		$this->load->library('form_validation');
		$this->form_validation->CI =& $this;
		$this->form_validation->set_error_delimiters('', ' / ');
		$this->form_validation->set_rules('fullname','H??? t??n','trim|required');
		$this->form_validation->set_rules('phone','S??? ??i???n tho???i','trim|required|is_numeric|min_length[10]|max_length[11]');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email');
		$this->form_validation->set_rules('message','Nhu c???u c??? th???','trim|required');

		if($this->form_validation->run($this)){
			$_subject = 'Y??u c???u "B??o gi??"';
			$_message = '	
				<p>T??n s???n ph???m: <b>'.$prd_name.'</b></p>
				<p>T??n kh??ch h??ng: <b>'.$fullname.'</b></p>
				<p>??i???n tho???i: <b>'.$phone.'</b></p>
				<p>Email: <b>'.$email.'</b></p>
				<p>Nhu c???u: <b>'.$message.'</b></p>
			';

			$_insert = array(
				'subject' => $_subject,
				'phone' => $phone,
				'email' => $email,
				'message' => $_message,
				'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
			);
			
			$insertId = $this->Autoload_Model->_create(array(
				'table' => 'contact',
				'data' => $_insert,
			));

			$this->load->library(array('mailbie'));
			$this->mailbie->sent(array(
				// 'to' => array($this->general['contact_email']),
				'to' => array('tudo210994@gmail.com'),
				'cc' => '',
				'subject' => $_subject.' t??? h??? th???ng '.BASE_URL,
				'message' => $_message,
			));
			$this->session->set_flashdata('message-success', 'G???i th??ng tin th??nh c??ng, Ch??ng t??i s??? li??n h??? l???i v???i b???n trong th???i gian s???m nh???t');
			$error['flag'] = 0;
			$error['message'] = ''; 
		}else{
			$error['flag'] = 1;
			$error['message'] = validation_errors(); 
			
		}
		echo json_encode(array(
			'error' => $error,
		));die();
	}


	public function contact_register_2(){
		$fullname = $this->input->post('fullname');
		$phone = $this->input->post('phone');
		$email = $this->input->post('email');
		$budget = $this->input->post('budget');
		
		$this->load->library('form_validation');
		$this->form_validation->CI =& $this;
		$this->form_validation->set_error_delimiters('', ' / ');
		$this->form_validation->set_rules('fullname','H??? t??n','trim|required');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email');
		$this->form_validation->set_rules('phone','S??? ??i???n tho???i','trim|required|is_numeric|min_length[10]|max_length[11]');
		$this->form_validation->set_rules('budget','Ng??n s??ch','trim|required|callback__CheckSelect');

		if($this->form_validation->run($this)){
			$_subject = 'Y??u c???u "T?? v???n"';
			$_message = '	
				<p>T??n kh??ch h??ng: <b>'.$fullname.'</b></p>
				<p>Email: <b>'.$email.'</b></p>
				<p>??i???n tho???i: <b>'.$phone.'</b></p>
				<p>Ng??n s??ch: <b>'.$budget.'</b></p>
			';

			$_insert = array(
				'subject' => $_subject,
				'phone' => $phone,
				'email' => $email,
				'message' => $_message,
				'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
			);
			
			$insertId = $this->Autoload_Model->_create(array(
				'table' => 'contact',
				'data' => $_insert,
			));

			$this->load->library(array('mailbie'));
			$this->mailbie->sent(array(
				'to' => array($this->general['contact_email']),
				// 'to' => array('tudo210994@gmail.com'),
				'cc' => '',
				'subject' => $_subject.' t??? h??? th???ng '.BASE_URL,
				'message' => $_message,
			));
			$this->session->set_flashdata('message-success', 'G???i th??ng tin th??nh c??ng, Ch??ng t??i s??? li??n h??? l???i v???i b???n trong th???i gian s???m nh???t');
			$error['flag'] = 0;
			$error['message'] = ''; 
		}else{
			$error['flag'] = 1;
			$error['message'] = validation_errors(); 
			
		}
		echo json_encode(array(
			'error' => $error,
		));die();
	}


	public function contact_register(){
		$email = $this->input->post('email');
		
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', ' / ');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email');

		if($this->form_validation->run($this)){
			$_subject = 'Y??u c???u "Nh???n ngay b??o gi?? v?? ch????ng tr??nh ??u ????i m???i nh???t"';
			$_message = '	
				<p>Email: <b>'.$email.'</b></p>
			';

			$_insert = array(
				'subject' => $_subject,
				// 'fullname' => $fullname,
				// 'phone' => $phone,
				'email' => $email,
				// 'message' => $_message,
				'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
			);
			
			$insertId = $this->Autoload_Model->_create(array(
				'table' => 'contact',
				'data' => $_insert,
			));

			$this->load->library(array('mailbie'));
			$this->mailbie->sent(array(
				// 'to' => array($this->general['contact_email']),
				'to' => array('tudo210994@gmail.com'),
				'cc' => '',
				'subject' => $_subject.' t??? h??? th???ng '.BASE_URL,
				'message' => $_message,
			));
			$this->session->set_flashdata('message-success', 'G???i th??ng tin th??nh c??ng, Ch??ng t??i s??? li??n h??? l???i v???i b???n trong th???i gian s???m nh???t');
			$error['flag'] = 0;
			$error['message'] = ''; 
		}else{
			$error['flag'] = 1;
			$error['message'] = validation_errors(); 
			
		}
		echo json_encode(array(
			'error' => $error,
		));die();
	}

	public function _CheckSelect($select = '0'){
		if($select == '0'){
			$this->form_validation->set_message('_CheckSelect', 'B???n ph???i ch???n {field}');
			return false;
		}

		return true;
	}




	
}