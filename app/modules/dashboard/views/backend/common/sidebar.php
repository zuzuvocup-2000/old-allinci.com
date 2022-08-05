<nav class="navbar-default navbar-static-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav metismenu" id="side-menu">
			<li class="nav-header">
				<div class="dropdown profile-element"> <span>
					<img alt="image" class="img-circle" style="height:50px;" src="<?php echo (!empty($this->auth['avatar'])) ? $this->auth['avatar'] : 'template/not-found.png' ?>" />
				</span>
				<a data-toggle="dropdown" class="dropdown-toggle" href="#">
					<span class="clear"> <span class="block m-t-xs"> <strong class="font-bold" style="color:#fff;font-weight:500;"><?php echo $this->auth['fullname']; ?></strong>
					</span> <span class="text-muted text-xs block"><?php echo $this->auth['catalogue']; ?> <b style="color:#8095a8;" class="caret"></b></span> </span> </a>
					<ul class="dropdown-menu animated fadeInRight m-t-xs">
						<li><a href="<?php echo site_url('user/backend/account/profile'); ?>">Hồ sõ cá nhân</a></li>
						<li class="divider"></li>
						<li><a href="<?php echo site_url('user/backend/auth/logout'); ?>">Ðăng xuất</a></li>
					</ul>
				</div>
				<div class="logo-element">
					HT+
				</div>
			</li>
			<li class="landing_link">
				<a target="_blank" href="<?php echo base_url('admin'); ?>"><i class="fa fa-star"></i> <span class="nav-label">Dashboard</span> <span class="label label-warning pull-right">NEW</span></a>
			</li>

			<?php /*
			<li class="<?php echo ($this->router->module == 'ship') ? 'active' : '' ?>">
				<a href="<?php echo site_url('ship/backend/ship/view'); ?>"><i class="fa fa-truck"></i> <span class="nav-label"> Quản lý phí ship</span></a>
			</li>


			<li class="<?php echo ($this->router->module == 'customer') ? 'active' : '' ?>">
				<a href="<?php echo site_url('customer/backend/customer/view'); ?>"><i class="fa fa-users"></i> <span class="nav-label"> Quản lý khách hàng</span></a>
			</li>
			*/ ?>
			<li class="<?php echo ($this->router->module == 'promotion') ? 'active' : '' ?>">
				<a href="#"><i class="fa fa-money" aria-hidden="true"></i> <span class="nav-label"> Promotional</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse">
					<li class="">
						<a href="<?php echo site_url('promotion/backend/promotion/view'); ?>"><i class="fa fa-money"></i> <span class="nav-label"> Promotional</span></a>
					</li>
					<?php /*
					<li class="">
						<a href="<?php echo site_url('promotion/backend/promotion/view2'); ?>"><i class="fa fa-money"></i> <span class="nav-label"> Promotional 2</span></a>
					</li>
					*/ ?>
				</ul>
			</li>
			<li class="<?php echo ($this->router->module == 'order') ? 'active' : '' ?>">
				<a href="<?php echo site_url('order/backend/order/view'); ?>"><i class="fa fa-plus"></i> <span class="nav-label"> Quản lý đơn hàng</span></a>
			</li>

			<!-- li class="<?php echo ($this->router->module == 'supplier'|| $this->router->module == 'customer' || $this->router->module == 'order' || $this->router->module == 'import') ? 'active' : '' ?>">
				<a href="<?php echo site_url('supplier/backend/supplier/view'); ?>"><i class="fa fa-cart-plus" aria-hidden="true"></i><span class="nav-label">QL Bán hàng</span> <span class="fa arrow"></span></a>
				<ul class="nav nav-second-level">
					<li><a href="<?php echo site_url('order/backend/order/view') ?>">QL Đơn hàng</a></li>
				</ul>
			</li> -->

			


		
			<li class="<?php echo ($this->router->module == 'product' || $this->router->module == 'attribute' || $this->router->module == 'promotional') ? 'active' : '' ?>">
				<a href="#"><i class="fa fa-diamond" aria-hidden="true"></i> <span class="nav-label"> Quản lý sản phẩm</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse">
					<li><a href="<?php echo site_url('product/backend/catalogue/view'); ?>">QL Loại sản phẩm</a></li>
					<li><a href="<?php echo site_url('product/backend/product/view'); ?>">QL Sản phẩm</a></li>
					<li><a href="<?php echo site_url('product/backend/brand/view'); ?>">QL Thương hiệu</a></li>
					<li><a href="<?php echo site_url('attribute/backend/attribute/view'); ?>">QL Thuộc tính</a></li>
					<li><a href="<?php echo site_url('attribute/backend/catalogue/view'); ?>">QL Loại thuộc tính </a></li>
					<li><a href="<?php echo site_url('promotional/backend/promotional/view'); ?>">QL Khuyến mại</a></li>
				</ul>
			</li>
		
			<li class="<?php echo ($this->router->module == 'article') ? 'active' : '' ?>">
				<a href="#"><i class="fa fa-edit"></i> <span class="nav-label"> Quản lý bài viết</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse">
					<li><a href="<?php echo site_url('article/backend/catalogue/view'); ?>">Nhóm bài viết</a></li>
					<li><a href="<?php echo site_url('article/backend/article/view'); ?>">Bài viết</a></li>
				</ul>
			</li>
			<li class="<?php echo ($this->router->module == 'page') ? 'active' : '' ?>">
				<a href="#"><i class="fa fa-edit"></i> <span class="nav-label"> QL Nội dung tĩnh</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse">
					<li><a href="<?php echo site_url('page/backend/catalogue/view'); ?>">Nhóm Nội dung</a></li>
					<li><a href="<?php echo site_url('page/backend/page/view'); ?>">Chi tiết</a></li>
				</ul>
			</li>
			<li class="<?php echo ($this->router->module == 'media') ? 'active' : '' ?>">
				<a href="#"><i class="fa fa-camera-retro"></i> <span class="nav-label"> Quản lý thư viện</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse">
					<li><a href="<?php echo site_url('media/backend/catalogue/view'); ?>">Nhóm thư viện</a></li>
					<li><a href="<?php echo site_url('media/backend/media/view'); ?>">Thư viện</a></li>
				</ul>
			</li>
			
			<li class="<?php echo ($this->router->module == 'support') ? 'active' : '' ?>">
				<a href="#"><i class="fa fa-users"></i><span class="nav-label">Hỗ trợ trực tuyến</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse">
					<li><a href="<?php echo site_url('support/backend/catalogue/view'); ?>">Nhóm hỗ trợ</a></li>
					<li><a href="<?php echo site_url('support/backend/support/view'); ?>">Chi tiết hỗ trợ</a></li>
				</ul>
			</li>


			<li class="<?php echo ($this->router->module == 'comment') ? 'active' : '' ?>">
				<a href="<?php echo site_url('comment/backend/comment/view'); ?>"><i class="fa fa-comments"></i> <span class="nav-label"> Quản lý comment</span></a>
			</li>
			<li class="<?php echo ($this->router->module == 'contact') ? 'active' : '' ?>">
				<a href="#"><i class="fa fa-envelope"></i><span class="nav-label">Liên Hệ</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse">
					<li><a href="<?php echo site_url('contact/backend/catalogue/view'); ?>">Nhóm liên hệ</a></li>
					<li><a href="<?php echo site_url('contact/backend/contact/view'); ?>">Danh sách liên hệ</a></li>
				</ul>
			</li>
			<li class="<?php echo ($this->router->module == 'general' || $this->router->module == 'tag') ? 'active' : '' ?> <?php echo ($this->router->module == 'navigation') ? 'active' : '' ?> <?php echo ($this->router->module == 'layout') ? 'active' : '' ?>">
				<a href="#"><i class="fa fa-cog"></i> <span class="nav-label">Cấu hình chung</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse">
					<li><a href="<?php echo site_url('layout/backend/layout/refreshSidemap'); ?>">Cập nhật SiteMap</a></li>
					<li><a href="<?php echo site_url('layout/backend/layout/view'); ?>">Quản lý giao diện</a></li>
					<li><a href="<?php echo site_url('general/backend/slide/view'); ?>">Banner & Slide</a></li>
					<li><a href="<?php echo site_url('general/backend/general/view'); ?>">Cấu hình chung</a></li>
					<li><a href="<?php echo site_url('navigation/backend/navigation/view'); ?>">Cài đặt Menu</a></li>
					<li><a href="<?php echo site_url('tag/backend/tag/view'); ?>">Quản lý Tag</a></li>
				</ul>
			</li>
			<li class="<?php echo ($this->router->module == 'user') ? 'active' : '' ?>">
				<a href="#"><i class="fa fa-users"></i> <span class="nav-label">Thành viên</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse">
					<li><a href="<?php echo site_url('user/backend/catalogue/view'); ?>">Nhóm thành viên</a></li>
					<li><a href="<?php echo site_url('user/backend/user/view'); ?>">Thành viên</a></li>
				</ul>
			</li>
			<li class="landing_link">
					<a target="_blank" href="<?php echo BASE_URL; ?>"><i class="fa fa-star"></i> <span class="nav-label">Xem Website</span> <span class="label label-warning pull-right">NEW</span></a>
				</li>
			</div>
		</nav>