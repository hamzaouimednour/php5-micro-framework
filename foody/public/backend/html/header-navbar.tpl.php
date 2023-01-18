<?php require_once PATH_MODULES . 'UsersData.module.php'; ?>
		<!-- begin #header -->
		<div id="header" class="header navbar-default">
			<!-- begin navbar-header -->
			<div class="navbar-header">
				<a href="#" class="navbar-brand ml-5"><img src="<?= HTML_PATH_PUBLIC . "img/foody_logo.jpg" ?>"></a>
				<button type="button" class="navbar-toggle" data-click="sidebar-toggled">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<!-- end navbar-header -->
			
            <!-- begin header-nav -->
			<ul class="navbar-nav navbar-right">
				<li>
					<form class="navbar-form">
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Recherche" />
							<button type="button" class="btn btn-search"><i class="fa fa-search"></i></button>
						</div>
					</form>
				</li>
				<li class="dropdown">
					<a href="#" data-toggle="dropdown" class="dropdown-toggle f-s-14">
						<i class="fa fa-bell"></i>
						<span class="label">0</span>
					</a>
					<ul class="dropdown-menu media-list dropdown-menu-right">
						<li class="dropdown-header">NOTIFICATIONS (0)</li>
					</ul>
				</li>
				<li class="dropdown navbar-user">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<img src= <?= HTML_PATH_PUBLIC . "assets/img/user/no-user.png";?> alt="User" /> 
						<span class="d-none d-md-inline"><?=ucwords(UsersData::getFullName());?> </span> <b class="caret"></b>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a href="<?= HTML_PATH_BACKEND . 'Account'; ?>" class="dropdown-item"><i class="far fa-lg fa-fw m-r-10 fa-user"></i>Profile</a>
						<!-- <a href="<?= HTML_PATH_BACKEND . 'Settings'; ?>" class="dropdown-item"><i class="far fa-lg fa-fw m-r-10 fa-sun"></i>Paramètres</a> -->
						<div class="dropdown-divider"></div>
						<a href= <?= HTML_PATH_BACKEND . "logout";?> class="dropdown-item"><i class="fas fa-lg fa-fw m-r-10 fa-unlock-alt"></i>Déconnexion</a>
					</div>
				</li>
			</ul>
			<!-- end header navigation right -->
		</div>
		<!-- end #header -->