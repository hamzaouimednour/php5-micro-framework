<?php
require_once PATH_MODULES . "Authority.module.php";

if(Session::get('user_auth') == 3){
    Request::redirect(HTML_PATH_BACKEND . 'Livraison');
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

require_once PATH_CONTROLLERS . 'City.class.php';

require_once PATH_MODELS      . "Error.model.php";

require_once PATH_MODELS      . "Cart.model.php";

require_once PATH_HELPERS     . "UploadHelper.php";

require_once PATH_HELPERS     . "CryptHelper.php";

if(!empty($_POST)){

    error_reporting(E_ALL ^ E_NOTICE);
    if($this->checkParams('Fetch', 0)){
        $feedback = (new RestaurantFeedback)
        ->setRestaurantId(Session::get('user_id'))
        ->setId(Handler::decryptInt(Request::post('rate_id')))
        ->getElementByRestaurantId();

        if($feedback){
            $customer = (new Customer)->setUid($feedback->getCustomerId())->getUserByUid();
            $rates = array(
                str_repeat('<i class="far fa-star"></i>', 5),
                '<i class="fas fa-star"></i>' . str_repeat('<i class="far fa-star"></i>', 4),
                str_repeat('<i class="fas fa-star"></i>', 2) . str_repeat('<i class="far fa-star"></i>', 3),
                str_repeat('<i class="fas fa-star"></i>', 3) . str_repeat('<i class="far fa-star"></i>', 2),
                str_repeat('<i class="fas fa-star"></i>', 4) . str_repeat('<i class="far fa-star"></i>', 1),
                str_repeat('<i class="fas fa-star"></i>', 5)
            );
            echo json_encode(array('status' => 'success', 'data' => array(
                'note' => $rates[$feedback->getRating()] ,
                'comment' => $feedback->getComment(),
                'customer' => $customer->getFullName(),
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
                                <li class="breadcrumb-item active">Dashboard </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end breadcrumb -->
        <?php $this->requireTPL('dashboard') ?>


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