<div id="page-wrapper" class="gray-bg dashboard-1 fix-wrapper">
	<div class="row border-bottom">
		<?php $this->load->view('dashboard/backend/common/navbar');?>
	</div>

	<!-- --------------------------- -->
	<form method="post" action="" class="form-horizontal box">
		<div class="wrapper wrapper-content animated fadeInRight">


			<?php 
				  // echo '<pre>';
				// print_r($contact);die();

			if(isset($contact) && is_array($contact) && count($contact)){ ?>
				<?php foreach($contact as $key => $val){ ?>

					<div class="row">
						<div class=" animated fadeInRight">
							<div class="mail-box-header">
								<div class="pull-right tooltip-demo">
									<a href="mailbox.html" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Move to trash"><i class="fa fa-trash-o"></i> </a>
									<a href="#" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Print email"><i class="fa fa-print"></i> </a>
									<a href="<?php echo site_url('contact/backend/contact/create'); ?>" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Reply"><i class="fa fa-reply"></i> Reply</a>
								</div>
								<h2>Thông tin chi tiết liên hệ của khách hàng</h2>
								<div class="mail-tools tooltip-demo">
									<h3 class="contact-viewdetail-subject">
										<span class="font-normal">Tiêu đề: </span><?php echo $val['subject']; ?>
									</h3>
									<h5 class="contact-box-info">
										<span class="pull-right font-normal"><?php echo gettime($val['created'],'h:i:s - d/m/Y '); ?></span>
										<p><span class="font-normal">Khách hàng: </span><?php echo $val['fullname']; ?></p>
										<p><span class="font-normal">Email: </span><?php echo $val['email']; ?></p>
										<p><span class="font-normal">Phone: </span><?php echo $val['phone']; ?></p>
									</h5>
								</div>
							</div>
							<div class="mail-box">
								<div class="mail-body">
									<h3 class="contact-viewdetail-content">
										<span class="font-normal">Nội dung thư: </span>
									</h3>
									<div>
										<?php echo $val['message']; ?>
									</div>
								</div>
								<div class="mail-attachment">
									<p>
										<span><i class="fa fa-paperclip"></i> File đính kèm - </span>
										<span>(Click vào file để tải xuống)</span>
									</p>

									<div class="attachment clearfix ">
										<?php if(isset($val['file']) && is_array($val['file']) && count($val['file'])){ ?>
											<ul class="mgb0 uk-list ">
												<?php foreach($val['file'] as $keyFile => $valFile){ ?>
													<?php $typeFile = pathinfo($valFile, PATHINFO_EXTENSION ); ?>
													<li class="col-lg-3">
														<div class="file-box">
															<div class="file">
																<a href="<?php echo $valFile; ?>" download title="<?php echo basename($valFile) ; ?>">
																	<span class="corner"></span>

																	<div class="icon">
																		<?php echo type_file_html($typeFile) ;?>
																		
																	</div>
																	<div class="file-name text-center">
																		<?php echo basename($valFile) ; ?>
																	</div>
																</a>
															</div>
														</div>
													</li>
												<?php } ?>
											</ul>
										<?php }else {
											echo '<p class="text-danger"> Không có file đính kèm </p>';
										} ?>
									</div>

								</div>
								<div class="mail-body text-right tooltip-demo">
									<button title="" data-placement="top" data-toggle="tooltip" data-original-title="Trash" class="btn btn-sm btn-white"><i class="fa fa-trash-o"></i> Remove</button>
									<button title="" data-placement="top" data-toggle="tooltip"  onclick="printDiv('print');" type="button" data-original-title="Print" class="btn btn-sm btn-white"><i class="fa fa-print"></i> Print</button>
									<a class="btn btn-sm btn-primary" href="<?php echo site_url('contact/backend/contact/create'); ?>"><i class="fa fa-reply"></i> Reply</a>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				<?php }} ?>

			</div>
		</form>
		<?php $this->load->view('dashboard/backend/common/footer'); ?>
	</div>