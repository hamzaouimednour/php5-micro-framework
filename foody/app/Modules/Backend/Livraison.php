<?php 

require_once PATH_MODULES . "Authority.module.php";

require_once PATH_CONTROLLERS . 'Order.class.php';

require_once PATH_CONTROLLERS . 'Administrator.class.php';

require_once PATH_CONTROLLERS . 'Customer.class.php';

require_once PATH_CONTROLLERS . 'CustomerAddress.class.php';

require_once PATH_CONTROLLERS . 'Restaurant.class.php';

require_once PATH_CONTROLLERS . 'Delivery.class.php';

require_once PATH_CONTROLLERS . 'Specialties.class.php';

require_once PATH_CONTROLLERS . 'RestaurantSpecialties.class.php';

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
    if($this->getParamsIndexOf() === 'OrderDelivered'){
        $order = (new Order)
                ->setId(Handler::decryptInt(Handler::getNumber(Request::post('order_id'))))
                ->setOrderStatus('L')
			    ->updateElementById();
	
		if(!$order){
            echo json_encode(array('status' => 'failed', 'info' => ' Erreur, réssayer plus tard! '));
		}else{
            echo json_encode(array('status' => 'success', 'info' => ''));
		}
		exit;
    }
}

$this->getScriptArray(
    [
        'select2',
        'toastr',
        'invoice',
        'sweetalert',
        'datatable',
        'datepicker',
        'form-plugins',
        'google-maps',
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
                                <li class="breadcrumb-item active">Orders </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end breadcrumb -->
        <?php $this->requireTPL('delivery');  ?>


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