<nav class="navbar navbar-expand-lg navbar-default">
	<b><a class="navbar-brand" href="<?=base_url();?>"><?=$this->l_skin->apps_config('title');?></a></b>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapse_navbar" aria-expanded="false">
		<i class="fa fa-user-circle"></i>
	</button>
	
	<div class="collapse navbar-collapse" id="collapse_navbar">
		<ul class="navbar-nav mr-auto">
		</ul>
		<ul class="navbar-nav">
			<li class="nav-item dropdown">
				<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" id="navbarDropdown"><i class="fa fa-user-circle"></i>&nbsp;&nbsp;<?=$this->m_auth->get_session('name');?></a>
				<div class="dropdown-menu right-text" aria-labelledby="navbarDropdown">
					<a class="dropdown-item" href="<?=site_url('profil');?>"><i class="fa fa-user-cog"></i>&nbsp;&nbsp;Pengaturan Akun</a>
					<a class="dropdown-item" href="#"><i class="fa fa-question-circle"></i>&nbsp;&nbsp;Bantuan</a>
					<a class="dropdown-item" href="<?=site_url('user_guide');?>"><i class="fa fa-book"></i>&nbsp;&nbsp;User Guide</a>
					<a class="dropdown-item" href="<?=site_url('logout');?>"><i class="fa fa-power-off"></i>&nbsp;&nbsp;Keluar</a>
				</div>
			</li>
		</ul>
	</div>
</nav>

<a href="javascript:;" class="toggle front" id="sidenav-toggle">
    <i class="fa fa-bars"></i>
</a>