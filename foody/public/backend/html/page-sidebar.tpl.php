	<!-- begin #sidebar -->
	<div id="sidebar" class="sidebar">
		<!-- begin sidebar scrollbar -->
		<div data-scrollbar="true" data-height="100%">
			<!-- begin sidebar user -->
			<?php require_once PATH_MODULES . 'UsersData.module.php'; ?>
			<ul class="nav">
				<li class="nav-profile">
					<a href="javascript:;" data-toggle="nav-profile">
						<?php
						$bgPics = array(1 => Handler::getCdnCoverImage('0', 'thumb-cover.jpg'), 2 => Handler::getCdnCoverImage('0', 'thumb-cover.jpg'), 3 => HTML_PATH_PUBLIC . "img/bg-delivery.jpg");
						?>
						<div class="cover with-shadow" style="background: url(<?=$bgPics[Session::get('user_auth')]?>) no-repeat;background-size:cover; "></div>
						<div class="image"> 
							<?php if(Session::get('user_auth')==2){
								if(!empty(UsersData::getRestaurantLogo())){
									echo '<img src="'. Handler::getCdnCoverImage(Session::get('user_id'), UsersData::getRestaurantLogo()).  '" alt="" />';
								}else{
									echo '<img src="'. HTML_PATH_PUBLIC . "assets/img/user/no-user.png" .  '" alt="" />';
								}
								
							}else{
								echo '<img src="'. HTML_PATH_PUBLIC . "assets/img/user/no-user.png" .  '" alt="" />';
							}?>
						</div>
						<div class="info">
							<b class="caret pull-right"></b>
							<?=ucwords(UsersData::getFullName());?> 
							<small><?=UsersData::getOccupation();?> </small>
						</div>
					</a>
				</li>
				<li>
					<ul class="nav nav-profile">
						<li><a href="<?= HTML_PATH_BACKEND . 'Account'; ?>"><i class="icon-settings"></i> Profile</a></li>
					</ul>
				</li>
			</ul>
			<!-- end sidebar user -->
			<!-- begin sidebar nav -->
			<ul class="nav">
				<li class="nav-header">Navigation</li>
				<?php 
				if(in_array(Session::get('user_auth'), [1,2])){
				$active='';
				if($this->getComponent() === 'Dashboard') $active='class="active"';
				?>
				<li <?=$active?> >
					<a href=<?= HTML_PATH_BACKEND . 'dashboard'; ?>>
						<i class="icon-screen-desktop"></i>
						<span>Dashboard</span>
					</a>
				</li>
				<?php
				}
				if(Session::get('user_auth') == 2){

				?>
				<li class="has-sub <?=(in_array($this->getComponent(),["Categories","Dishes","Drinks","Supplements"])) ? "active" : ""; ?>">
					<a href="javascript:;">
						<b class="caret pull-right"></b>
						<i class="ion-md-restaurant"></i>
						<span>Menu</span>
					</a>
					<ul class="sub-menu">
						<li class="<?=($this->getComponent() === "Categories") ? "active" : ""; ?>">
							<a href=<?= HTML_PATH_BACKEND . "categories"?>>
								Catégories</a>
						</li>

						<li class="has-sub <?=($this->getComponent() === "Dishes") ? "active" : ""; ?>">
							<a href="javascript:;">
								<b class="caret"></b>
								Plats
							</a>
							<ul class="sub-menu">
								<li class="<?=($this->getAction() !== "Add" && $this->getComponent() === "Dishes") ? "active" : ""; ?>">
								<a href=<?= HTML_PATH_BACKEND . 'dishes'; ?>>Afficher Plats <i class="icon-pencil pull-right"></i></a>
								</li>
								<li class="<?=($this->getAction() === "Add" && $this->getComponent() === "Dishes") ? "active" : ""; ?>">
									<a href=<?= HTML_PATH_BACKEND . 'dishes/add'; ?>>Ajouter Plat <i class="far fa-plus-square pull-right"></i></a>
								</li>
							</ul>
						</li>
						<li class="<?=($this->getComponent() === "Supplements") ? "active" : ""; ?>">
							<a href=<?= HTML_PATH_BACKEND . "supplements"?>>
								Suppléments<i class="icon-paper-plane text-theme m-l-5"></i>
							</a>
						</li>
					</ul>
				</li>
				<?php

				}
				if(in_array(Session::get('user_auth'), [1,2])){

				?>
				<li class="has-sub <?=(in_array($this->getComponent(),["Orders"])) ? "active" : ""; ?>">
					<a href="javascript:;">
						<b class="caret pull-right"></b>
						<i class="ion-ios-cart"></i>
						<span>Commandes</span>
						<!-- <span>Commandes<span class="label label-theme m-l-5">NEW</span></span> -->
					</a>
					<ul class="sub-menu">
						<li><a href=<?= HTML_PATH_BACKEND . "orders"?>>Liste Les Commandes</a></li>
					</ul>
				</li>
				<?php

				}
				?>
				<?php
				if(Session::get('user_auth') == 3){
					
				?>
				<li class="has-sub <?=(in_array($this->getComponent(),["Livraison"])) ? "active" : ""; ?>">
					<a href="javascript:;">
						<b class="caret pull-right"></b>
						<i class="ion-md-bicycle"></i>
						<span>Livraison</span>
					</a>
					<ul class="sub-menu  <?=($this->getComponent() === "Livraison") ? "active" : NULL; ?>">
						<li class="has-sub">
							<li><a href=<?= HTML_PATH_BACKEND . "Livraison"?>>Liste des Commandes</a></li>
						</li>
					</ul>
				</li>
				<?php					
				}
				if(Session::get('user_auth') == 1){
				?>
				<li class="has-sub <?=($this->getComponent() === "Users") ? "active" : NULL; ?>">
					<a href="javascript:;">
						<b class="caret pull-right"></b>
						<i class="ion-md-people"></i>
						<span>Utilisateurs</span>
					</a>
					<ul class="sub-menu">
						<li class="<?=($this->getParamsIndexOf(0) == "Manager" && $this->getComponent() === "Users") ? "active" : NULL; ?>"><a href="<?= HTML_PATH_BACKEND . 'Users/Manager'; ?>">Administrateurs</a></li>
						<li class="<?=($this->getParamsIndexOf(0) == "Restaurant" && $this->getComponent() === "Users") ? "active" : NULL; ?>"><a href="<?= HTML_PATH_BACKEND . 'Users/Restaurant'; ?>">Restaurants</a></li>
						<li class="<?=($this->getParamsIndexOf(0) == "Customer" && $this->getComponent() === "Users") ? "active" : NULL; ?>"><a href="<?= HTML_PATH_BACKEND . 'Users/Customer'; ?>">Clients</a></li>
						<li class="<?=($this->getParamsIndexOf(0) == "Delivery" && $this->getComponent() === "Users") ? "active" : NULL; ?>"><a href="<?= HTML_PATH_BACKEND . 'Users/Delivery'; ?>">Livreurs</a></li>
					</ul>
				</li>
				<?php					
				}
				if(in_array(Session::get('user_auth'), [1,2])){
				?>
				<li class="has-sub">
					<a href="javascript:;">
						<i class="ion-md-chatbubbles"></i>
						<b class="caret pull-right"></b>
						<span>Feedback</span>
					</a>
					<ul class="sub-menu">
						<li class="<?=($this->getComponent() == "Feedback") ? "active" : NULL; ?>"><a href="<?= HTML_PATH_BACKEND . 'Feedback'; ?>">Liste des Avis</a></li>
					</ul>
				</li>
				<?php					
				}
				if(Session::get('user_auth') == 2){
				?>
				<li class="has-sub <?=($this->getComponent() === "Promotion") ? "active" : NULL; ?>">
					<a href="javascript:;">
						<i class="fas fa-lg fa-fw m-r-10 fa-ticket-alt"></i>
						<b class="caret pull-right"></b>
						<span>Promotions</span>
					</a>
					<ul class="sub-menu">
						<li class="<?=($this->getParamsIndexOf() == 1 && $this->getComponent() === "Promotion") ? "active" : NULL; ?>"><a href="<?= HTML_PATH_BACKEND . 'Promotion/1'; ?>">Quantité</a></li>
						<li class="<?=($this->getParamsIndexOf() == 2 && $this->getComponent() === "Promotion") ? "active" : NULL; ?>"><a href="<?= HTML_PATH_BACKEND . 'Promotion/2'; ?>">Bon de réduction</a></li>
						<!-- <li class="<?=($this->getParamsIndexOf() == 3 && $this->getComponent() === "Promotion") ? "active" : NULL; ?>"><a href="<?= HTML_PATH_BACKEND . 'Promotion/3'; ?>">Prix Total d'Usage</a></li> -->
					</ul>
				</li>
				<?php					
				}
				?>
				<li class="has-sub <?=(in_array($this->getComponent(),["Account","City","Specialties"])) ? "active" : ""; ?>">
					<a href="javascript:;">
						<i class="icon-settings"></i>
						<b class="caret pull-right"></b>
						<span>Options</span>
					</a>
					<ul class="sub-menu">
						<li class="<?=($this->getComponent() == "Account") ? "active" : NULL; ?>"><a href="<?= HTML_PATH_BACKEND . 'Account'; ?>">Profile</a></li>
						<?php					
						if(Session::get('user_auth') == 1){
						?>
						<li><a href="<?= HTML_PATH_BACKEND . 'Settings'; ?>">Paramètres</a></li>
						<li><a href="<?= HTML_PATH_BACKEND . 'Cities'; ?>">Villes</a></li>
						<li><a href="<?= HTML_PATH_BACKEND . 'Specialties'; ?>">Spécialités</a></li>
						<li><a href="<?= HTML_PATH_BACKEND . 'Support'; ?>">Demandes des Utilisateurs</a></li>
						<?php					
						}
						?>
					</ul>
				</li>
				<!-- begin sidebar minify button -->
				<li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
				<!-- end sidebar minify button -->
			</ul>
			<!-- end sidebar nav -->
		</div>
		<!-- end sidebar scrollbar -->
	</div>
	<div class="sidebar-bg"></div>
	<!-- end #sidebar -->