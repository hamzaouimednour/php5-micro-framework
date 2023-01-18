<?php
require_once 	PATH_MODULES 		. "Authority.module.php";

if(Session::get('user_auth') != 2){
	Request::redirect(HTML_PATH_BACKEND . 'dashboard');
}

require_once 	PATH_CONTROLLERS	. "MenuCategories.class.php";

require_once 	PATH_CONTROLLERS	. "Dish.class.php";

require_once 	PATH_MODELS 		. "Error.model.php";

if(!empty($_POST)){

	error_reporting(E_ALL ^ E_NOTICE);  

	if($this->getAction() === 'Add'){

		$switcherStatus = (count($_POST) > 1) ? '1' : '0'; //1:ON, 0:OFF
		$menuCategorie = 	(new MenuCategories)
							->resetAI()
							->setRestaurantId(Session::get('user_id'))
							->setMenuName(Request::post('categorie_name'))
							->setStatus($switcherStatus)
							->addElement();
	
		if($menuCategorie){
			echo json_encode(array('status' => 'success', 'info' => AlertError::success(' categorie ajouté avec succés')));
		}else{
			echo json_encode(array('status' => 'failed', 'info' => AlertError::success(' n\'a pas réussi à ajouter une catégorie')));
		}
		exit;
	}
	if($this->getAction() === 'Status'){
	
		$getNewStatus = (Request::post('status') == '1') ? '0' : '1'; //1:ON, 0:OFF
		$menuCategorie = 	(new MenuCategories)
							->setRestaurantId(Session::get('user_id'))
							->setId(Handler::getNumber(Request::post('id')))
							->setStatus($getNewStatus)
							->updateElementById();
	
		if($menuCategorie){
			echo json_encode(array('status' => 'success', 'info' => AlertError::success(' categorie activé avec succés')));
		}else{
			echo json_encode(array('status' => 'failed', 'info' => AlertError::failed(' Erreur, réssayer plus tard! ')));
		}
		exit;
	}
	if($this->getAction() === 'Delete'){
	
		$menuCategorie = 	(new MenuCategories)
							->setRestaurantId(Session::get('user_id'))
							->setId(Handler::getNumber(Request::post('id')))
							->removeElementById();
	
		if($menuCategorie){
			echo json_encode(array('status' => 'success', 'info' => ' categorie supprimé avec succés'));
		}else{
			echo json_encode(array('status' => 'failed', 'info' => ' Réssayer une autre fois. '));
		}
		exit;
	}
	if($this->getAction() === 'Edit'){
	
		$menuCategorie = 	(new MenuCategories)
							->setRestaurantId(Session::get('user_id'))
							->setId(Handler::getNumber(Request::post('item_id')))
							->setMenuName(trim(strip_tags(Request::post('item_name'))))
							->updateElementById();
		if($menuCategorie){
			echo json_encode(array('status' => 'success', 'info' => AlertError::success(' categorie modifié avec succés')));
		}else{
			echo json_encode(array('status' => 'failed', 'info' => AlertError::failed(' Réssayer une autre fois. ')));
		}
		exit;
	}
	if($this->checkParams('Multi-delete', 0)){ //
	
		$menuCategorie = 	(new MenuCategories)
							->setRestaurantId(Session::get('user_id'))
							->setId( '(' . Handler::getNumberMap(Request::post('items')) . ')' )
							->removeElementsById();
		if($menuCategorie){
			echo json_encode(array('status' => 'success', 'info' => "Les Éléments sélectionnés ont été supprimés!"));
		}else{
			echo json_encode(array('status' => 'failed', 'info' => 'Réssayer une autre fois.'));
		}
		exit;
	}
}

// Call Scripts CSS / JS in specific pages.
$this->getScriptArray(
	[
		'datatable',
		'--component-script',
		'sweetalert',
		'spinner',
	]
);
// Start HTML
$this->requireTPL('header');
$this->requireTPL('page-loader');

?>

    <!-- begin #page-container -->
    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed show">

        <?php $this->requireTPL('header-navbar'); ?>

        <?php $this->requireTPL('page-sidebar'); ?>
        <!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<div class="breadcome">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row pull-right">
								<ol class="breadcrumb ">
									<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
									<li class="breadcrumb-item active">Food Categories </li>
								</ol>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- end breadcrumb -->
			<div class="m-b-20"></div>
		<?php 
		
			
			$this->requireTPL('categories');
		
		?>
            
		</div>
		<!-- end #content -->

        <?php $this->requireTPL('theme-panel'); ?>

        <!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
        <!-- end scroll to top btn -->
        
    </div>
    <!-- end page container -->
    

<?php

$this->requireTPL('base-js');

?>
