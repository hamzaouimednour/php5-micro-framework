<?php
require_once PATH_MODULES . "Authority.module.php";

if(!in_array(Session::get('user_auth'), [2])){
	Request::redirect(HTML_PATH_BACKEND . 'dashboard');
}

require_once PATH_CONTROLLERS . 'Customer.class.php';

require_once PATH_CONTROLLERS . 'Restaurant.class.php';

require_once PATH_CONTROLLERS . 'Discount.class.php';

require_once PATH_CONTROLLERS . 'Dish.class.php';

require_once PATH_MODELS      . "Error.model.php";

if(!empty($_POST)){

    error_reporting(E_ALL ^ E_NOTICE);
    $promo = NULL;
    switch ($this->getParamsIndexOf()) {
        case 1:
            $promo = new QuantityPromotion;
            break;
        case 2:
            $promo = new DiscountCode;
            break;
        case 3:
            $promo = new CustomerDiscount;
            break;
    }
    if($this->getParamsIndexOf(1) == 'Add'){

        //==================== CHeck Item Exitance ================================
        $promoCheck = $promo;
        $promoCheck = $promoCheck->setDiscountItemId(Request::post('discount_item_id'))->getElementByItemId();
        if($promoCheck){
            echo json_encode(array('status' => 'failed', 'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur d\'ajout du promotion, Ce Plat déjà en promotion!', true)));
            exit;
        }
        //==================== Insert Promo ================================
        $promo = $promo->resetAI();
        $promoData = $_POST;
        $promoData['status'] = Request::post('switcher_checkbox_status');
        foreach ($promoData as $property => $value) {
            if(method_exists($promo, 'set'.Handler::getMethod($property)))
                call_user_func_array( array($promo, 'set'.Handler::getMethod($property)), array($promoData[$property]) );
        }
        $promo = $promo->setRestaurantId(Session::get('user_id'))->addElement();
        if($promo){
            echo json_encode(array('status' => 'success', 'info' => AlertError::defaultNote('success', 'ion-md-checkmark-circle-outline', 'Succés!', 'Promo ajouté avec succés.', true)));
        }else{
            echo json_encode(array('status' => 'failed', 'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur d\'ajout du promotion Réessayer!', true)));
        }
        exit;
    }
    if($this->checkParams('Delete', 1)){
	
		$promo = 	$promo->setRestaurantId(Session::get('user_id'))
						->setId(Handler::getNumber(Request::post('id')))
						->removeElementById();
	
		if($promo){
			echo json_encode(array('status' => 'success', 'info' => ' Element supprimé avec succés'));
		}else{
			echo json_encode(array('status' => 'failed', 'info' => ' Réssayer une autre fois. '));
		}
		exit;
    }
    if($this->checkParams('Status', 1)){
	
		$getNewStatus = (Request::post('status') == '1') ? '0' : '1'; //1:ON, 0:OFF
		$promo = 	$promo->setRestaurantId(Session::get('user_id'))
							->setId(Handler::getNumber(Request::post('id')))
							->setStatus($getNewStatus)
							->updateElementById();
	
		if($promo){
			echo json_encode(array('status' => 'success', 'info' => AlertError::success(' categorie activé avec succés')));
		}else{
			echo json_encode(array('status' => 'failed', 'info' => AlertError::failed(' Erreur, réssayer plus tard! ')));
		}
		exit;
	}
    if($this->checkParams('Multi-delete', 1)){ //
	
		$promo = 	$promo->setRestaurantId(Session::get('user_id'))
							->setId( '(' . Handler::getNumberMap(Request::post('items')) . ')' )
							->removeElementsById();
		if($promo){
			echo json_encode(array('status' => 'success', 'info' => "Les Éléments sélectionnés ont été supprimés!"));
		}else{
			echo json_encode(array('status' => 'failed', 'info' => 'Réssayer une autre fois.'));
		}
		exit;
    }
    if($this->checkParams('Fetch', 1)){
        $promo = $promo->setId(Handler::getNumber(Request::post('promo_id')))->getElementById();
		if($promo){
			echo json_encode($promo);
		}else{
			echo json_encode(array('status' => 'failed', 'info' => 'Operation echoué, Réssayer une autre fois. '));
		}
		exit;
    }       
    
    
    //==================== Edit Promo ================================
    if($this->checkParams('Edit', 1)){

        $promoData = $_POST;
        $promoData['status'] = Request::post('modal-status');
        foreach ($promoData as $property => $value) {
            $property1 = substr($property, 6);
            if(method_exists($promo, 'set'.Handler::getMethod($property1)))
                call_user_func_array( array($promo, 'set'.Handler::getMethod($property1)), array($promoData[$property]) );
        }
        $promo = $promo->setRestaurantId(Session::get('user_id'))->updateElementById();
        if($promo){
            echo json_encode(array('status' => 'success', 'info' => AlertError::defaultNote('success', 'ion-md-checkmark-circle-outline', 'Succés!', 'Promo modifié avec succés.', true)));
        }else{
            echo json_encode(array('status' => 'failed', 'info' => AlertError::defaultNote('danger', 'ion-md-close', 'Echoué!', 'Erreur de modifier la promotion, Réessayer!', true)));
        }
        exit;
	}

}

$this->getScriptArray(
    [
        'select2',
        'bootstrap-select',
        'datetimepicker',
        'sweetalert',
        'form-plugins',
        '--component-script'
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
                                <li class="breadcrumb-item active">Promotion </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end breadcrumb -->
        <div class="m-b-20"></div>
        <?php
        if(in_array($this->getParamsIndexOf(), [1,2,3]))
            $this->requireTPL('promotion-'. $this->getParamsIndexOf());
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