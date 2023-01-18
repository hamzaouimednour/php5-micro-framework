			<!-- begin row -->
			<div class="row">
				<!-- begin col-3 -->
				<?php
                	if(Session::get('user_auth') == 2 ){
                ?>
				<div class="col-lg-3 col-md-6">
					<div class="widget widget-stats bg-gradient-teal">
						<div class="stats-icon stats-icon-lg"><i class="fa fa-globe fa-fw"></i></div>
						<div class="stats-content">
							<div class="stats-title">TOTAL VISITEURS</div>
							<div class="stats-number"><?php
                            $json_tasks = file_get_contents(PATH_VIEWS . 'stats-restos.json');
							$task_array = json_decode($json_tasks, true);
							if(is_array($task_array) && array_key_exists(Handler::encryptInt(Session::get('user_id')), $task_array) ){
								echo count($task_array[Handler::encryptInt(Session::get('user_id'))]);
							}else{
								echo '0';
							}
                            ?></div>
							<div class="stats-progress progress">
								<div class="progress-bar" style="width: 100%;"></div>
							</div>
							<div class="stats-desc">Nombre total des visiteurs sur votre espace.</div>
						</div>
					</div>
				</div>
				<?php
                	}else{
				?>
				
                <div class="col-lg-3 col-md-6">
					<div class="widget widget-stats bg-gradient-teal">
						<div class="stats-icon stats-icon-lg"><i class="fa fa-globe fa-fw"></i></div>
						<div class="stats-content">
							<div class="stats-title">TOTAL VISITEURS</div>
							<div class="stats-number"><?php
                            $json_tasks = file_get_contents(PATH_VIEWS . 'stats-website.json');
                            $task_array = json_decode($json_tasks, true);
                            echo count($task_array);
                            ?></div>
							<div class="stats-progress progress">
								<div class="progress-bar" style="width: <?=count($task_array)?>%;"></div>
							</div>
							<div class="stats-desc">Nombre total des visiteurs</div>
						</div>
					</div>
				</div>
				<?php	
					}
                ?>
				<!-- end col-3 -->
				<!-- begin col-3 -->
				<div class="col-lg-3 col-md-6">
					<div class="widget widget-stats bg-gradient-blue">
						<div class="stats-icon stats-icon-lg"><i class="fa fa-dollar-sign fa-fw"></i></div>
						<div class="stats-content">
							<div class="stats-title">COMMANDES D'AUJOURD'HUI</div>
							<div class="stats-number">
                            <?php
                            if(Session::get('user_auth') == 1 ){
                                $orderNbr = (new Order)->getTodayOrdersNbr();
                            }else{
                                $orderNbr = (new Order)->setRestaurantId(Session::get("user_id"))->getTodayOrdersNbrByRestaurantId();
                            }
                            echo $orderNbr;
                            ?>
                            </div>
							<div class="stats-progress progress">
								<div class="progress-bar" style="width: <?=$orderNbr?>%;"></div>
							</div>
							<div class="stats-desc">Tous les commandes d'aujourd'hui</div>
						</div>
					</div>
				</div>
				<!-- end col-3 -->
				<!-- begin col-3 -->
				<div class="col-lg-3 col-md-6">
					<div class="widget widget-stats bg-gradient-purple">
						<div class="stats-icon stats-icon-lg"><i class="fa fa-archive fa-fw"></i></div>
						<div class="stats-content">
							<div class="stats-title">TOTAL COMMANDES</div>
							<div class="stats-number"><?php
                            if(Session::get('user_auth') == 1 ){
                                $c = (new Order)->rowCount();
                            }else{
                                $c = (new Order)->setRestaurantId(Session::get("user_id"))->rowCountByRestaurantId();
                            }
                            echo $c;
                            ?></div>
							<div class="stats-progress progress">
								<div class="progress-bar" style="width: <?=$c?>%;"></div>
							</div>
							<div class="stats-desc">Nombre total des commandes</div>
						</div>
					</div>
				</div>
				<!-- end col-3 -->
				<!-- begin col-3 -->
				<div class="col-lg-3 col-md-6">
					<div class="widget widget-stats bg-gradient-black">
						<div class="stats-icon stats-icon-lg"><i class="fa fa-comment-alt fa-fw"></i></div>
						<div class="stats-content">
							<div class="stats-title">TOTAL FEEDBACK</div>
                            <div class="stats-number"><?php
                            if(Session::get('user_auth') == 1 ){
                                $totalRates = (new RestaurantFeedback)->rowCount();
                            }else{
                                $totalRates = (new RestaurantFeedback)->setRestaurantId(Session::get("user_id"))->rowCountByRestaurantId();
                            }
                            echo $totalRates;
                            ?></div>
							<div class="stats-progress progress">
                                <div class="progress-bar" style="width: <?=$totalRates?>%;"></div>
                            </div>
                            <div class="stats-desc">Nombre total des avis</div>

						</div>
					</div>
				</div>
				<!-- end col-3 -->
			</div>
            <!-- end row -->
            <?php
            if(Session::get('user_auth') == 1){
            ?>
            <!-- begin row -->
			<div class="row">
				<!-- begin col-3 -->
				<div class="col-lg-3 col-md-6">
					<div class="widget widget-stats bg-orange">
						<div class="stats-icon"><i class="ion ion-md-restaurant"></i></div>
						<div class="stats-info">
							<h4>DEMANDE DE PARTENARIAT </h4>
							<p><?=(new Restaurant)->setPartnerRequest('P')->countPartnership()?></p>	
						</div>
						<div class="stats-link">
							<a href="<?= HTML_PATH_BACKEND . 'Users/Restaurant'; ?>">Liste des Restaurants <i class="fa fa-arrow-alt-circle-right"></i></a>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="widget widget-stats bg-lime">
						<div class="stats-icon"><i class="ion ion-md-bicycle"></i></div>
						<div class="stats-info">
                            <h4>DEMANDE DE PARTENARIAT </h4>
							<p><?=(new Delivery)->setPartnerRequest('P')->countPartnership()?></p>
						</div>
						<div class="stats-link">
							<a href="<?= HTML_PATH_BACKEND . 'Users/Delivery'; ?>">Liste des Livreurs <i class="fa fa-arrow-alt-circle-right"></i></a>
						</div>
					</div>
				</div>
				<!-- end col-3 -->
				<!-- begin col-3 -->
				<div class="col-lg-3 col-md-6">
					<div class="widget widget-stats bg-grey-darker">
						<div class="stats-icon"><i class="fa fa-users"></i></div>
						<div class="stats-info">
							<h4>TOTAL CLIENTS</h4>
							<p><?=(new Customer)->rowCount();?></p>
						</div>
						<div class="stats-link">
							<a href="<?= HTML_PATH_BACKEND . 'Users/Customer'; ?>">Liste des clients <i class="fa fa-arrow-alt-circle-right"></i></a>
						</div>
					</div>
				</div>
				<!-- end col-3 -->
			</div>
            <!-- end row -->
            <?php
            }
            ?>
			<!-- begin row -->
			<div class="row">
				<!-- end col-4 -->
				<!-- begin col-4 -->
				<div class="col-lg-4">
					<!-- begin panel -->
					<div class="panel panel-inverse">
						<div class="panel-heading">
							<h4 class="panel-title"><i class="fas fa-calendar-alt"></i> &nbsp;Calendrier </h4>
                        </div>
                        <div class="panel-body">
						<div id="datepicker-inline" class="datepicker-full-width position-relative"><div></div></div>

                        </div>

					</div>
					<!-- end panel -->
				</div>
				<!-- end col-4 -->
			</div>
			<!-- end row -->