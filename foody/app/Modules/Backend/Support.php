<?php
require_once PATH_MODULES . "Authority.module.php";

if(!in_array(Session::get('user_auth'), [1])){
	Request::redirect(HTML_PATH_BACKEND . 'dashboard');
}

require_once PATH_CONTROLLERS . 'Order.class.php';

require_once PATH_CONTROLLERS . 'Administrator.class.php';

require_once PATH_CONTROLLERS . 'Customer.class.php';

require_once PATH_CONTROLLERS . 'Restaurant.class.php';

require_once PATH_CONTROLLERS . 'Delivery.class.php';

require_once PATH_CONTROLLERS . 'Specialties.class.php';

require_once PATH_CONTROLLERS . 'RestaurantSpecialties.class.php';

require_once PATH_CONTROLLERS . 'RestaurantFeedback.class.php';

require_once PATH_CONTROLLERS . 'RestaurantWork.class.php';

require_once PATH_CONTROLLERS . 'VehicleType.class.php';

require_once PATH_CONTROLLERS . 'Options.class.php';

require_once PATH_CONTROLLERS . 'Order.class.php';

require_once PATH_CONTROLLERS . 'Address.class.php';

require_once PATH_CONTROLLERS . 'UsersRequests.class.php';

require_once PATH_CONTROLLERS . 'City.class.php';

require_once PATH_MODELS      . "Error.model.php";

require_once PATH_MODELS      . "Cart.model.php";

require_once PATH_HELPERS     . "UploadHelper.php";

require_once PATH_HELPERS     . "CryptHelper.php";

if(!empty($_POST)){

    error_reporting(E_ALL ^ E_NOTICE);
    if($this->checkParams('Fetch', 0)){
        $req = (new UsersRequests)->setId(Handler::decryptInt(Request::post('req_id')))->getElementById();
        if($req){

            if ($req->getUserAuth() == 2) {
                $user = (new Restaurant)->setUid($req->getUserId())->getUserByUid();
                $auth = 'Restaurant';
            }
            if ($req->getUserAuth() == 3) {
                $user = (new Delivery)->setUid($req->getUserId())->getUserByUid();
                $auth = 'Livreur';
            }
            $sujets = array(
                "Account Deletion" => "Suppression du compte",
                "Help & Support" => "Aide & Support",
                "Technical Issue" => "Problème Technique",
                "Other" => "Autre"
            );
            echo json_encode(array('status' => 'success', 'data' => array(
                'name' => $user->getFullName() ,
                'auth' => $auth,
                'suj' => $sujets[$req->getRequest()],
                'desc' => $req->getDescription(),
            )));
        } else {
            echo json_encode(array('status' => 'failed', 'info' => 'Opération échouée, Réssayer une autre fois.'));
        }
        exit;
    }
}

$this->getScriptArray(
       [
        'select2',
        'sweetalert',
        'datatable',
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
                                <li class="breadcrumb-item active">Help & Support </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end breadcrumb -->
        <?php $this->requireTPL('support') ?>


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