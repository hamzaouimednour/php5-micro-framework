<?php
require_once 	PATH_MODULES 		. "Authority.module.php";

if(!in_array(Session::get('user_auth'), [2])){
	Request::redirect(HTML_PATH_BACKEND . 'dashboard');
}

require_once 	PATH_CONTROLLERS	. "RestaurantExtras.class.php";

require_once 	PATH_CONTROLLERS	. "DishExtras.class.php";

require_once 	PATH_MODELS 		. "Error.model.php";

if(!empty($_POST)){

	error_reporting(E_ALL ^ E_NOTICE);

	if($this->getAction() === 'Add'){
		if(empty($_POST) or Handler::array_check($_POST, array_keys($_POST))){
			echo json_encode(array('status' => 'error', 'info' => AlertError::failed('Svp remplir les champs qui sont obligatoires!')));
			exit;
		}
		global $dateTime;
		$switcherStatus = (count($_POST) > 2) ? '1' : '0'; //1:ON, 0:OFF
		$supplements = 	(new RestaurantExtras)
						->resetAI()
						->setRestaurantId(Session::get('user_id'))
						->setPrice(Handler::currency_format(Handler::getNumber(Request::post('extra_price'))))
						->setExtraName(trim(strip_tags(Request::post('extra_name'))))
                        ->setStatus($switcherStatus)
                        ->setDateTime($dateTime->format('Y-m-d H:i:s'))
						->addElement();
	
		if($supplements){
			echo json_encode(array('status' => 'success', 'info' => AlertError::success(' Supplement ajouté avec succés')));
		}else{
			echo json_encode(array('status' => 'failed', 'info' => AlertError::failed(' n\'a pas réussi à ajouter un Supplement')));
		}
		exit;
	}
	if($this->getAction() === 'Status'){
	
		$getNewStatus = (Request::post('status') == '1') ? '0' : '1'; //1:ON, 0:OFF
		$supplements = 	(new RestaurantExtras)
						->setRestaurantId(Session::get('user_id'))
						->setId(Handler::getNumber(Request::post('id')))
						->setStatus($getNewStatus)
						->updateElementById();
	
		if($supplements){
			echo json_encode(array('status' => 'success', 'info' => AlertError::success(' Supplement activé avec succés')));
		}else{
			echo json_encode(array('status' => 'failed', 'info' => AlertError::failed(' Erreur, réssayer plus tard! ')));
		}
		exit;
	}
	if($this->getAction() === 'Delete'){
	
		$supplements = 	(new RestaurantExtras)
						->setRestaurantId(Session::get('user_id'))
						->setId(Handler::getNumber(Request::post('id')))
						->removeElementById();
	
		if($supplements){
			echo json_encode(array('status' => 'success', 'info' => ' categorie supprimé avec succés'));
		}else{
			echo json_encode(array('status' => 'failed', 'info' => ' Réssayer une autre fois. '));
		}
		exit;
	}
	if($this->getAction() === 'Edit'){
	
		$supplements = 	(new RestaurantExtras)
						->setRestaurantId(Session::get('user_id'))
						->setId(Handler::getNumber(Request::post('item_id')))
						->setExtraName(trim(strip_tags(Request::post('item_name'))))
						->setPrice(Handler::currency_format(Handler::getNumber(Request::post('item_price'))))
						->updateElementById();
		if($supplements){
			echo json_encode(array('status' => 'success', 'info' => AlertError::success(' categorie modifié avec succés')));
		}else{
			echo json_encode(array('status' => 'failed', 'info' => AlertError::failed(' Réssayer une autre fois. ')));
		}
		exit;
	}
	if($this->checkParams('Multi-delete', 0)){ //
	
		$supplements = 	(new RestaurantExtras)
						->setRestaurantId(Session::get('user_id'))
						->setId( '(' . Handler::getNumberMap(Request::post('items')) . ')' )
						->removeElementsById();
		if($supplements){
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
									<li class="breadcrumb-item active">Food Supplements </li>
								</ol>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- end breadcrumb -->
			<div class="m-b-20"></div>
		<?php 
		
			
			$this->requireTPL('supplements');
		
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
