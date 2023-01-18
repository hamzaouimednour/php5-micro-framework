<?php
require_once PATH_MODULES . "Authority.module.php";

if(!in_array(Session::get('user_auth'), [1,2])){
	Request::redirect(HTML_PATH_BACKEND . 'dashboard');
}

require_once PATH_CONTROLLERS . 'Order.class.php';

require_once PATH_CONTROLLERS . 'Administrator.class.php';

require_once PATH_CONTROLLERS . 'Customer.class.php';

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
    
    $user = null;
    switch (Request::post('user_auth')) {
        case 1:
            $user = new Administrator;
            break;
        case 2:
            $user = new Restaurant;
            break;
        case 3:
            $user = new Delivery;
            break;
        case 4:
            $user = new Customer;
    }

	if($this->getParamsIndexOf() === 'OrderReject'){
        $order = (new Order)
                ->setId(Handler::decryptInt(Handler::getNumber(Request::post('order_id'))))
                ->setRestaurantComment(Request::post('restaurant_comment'))
                ->setOrderStatus('R')
			    ->updateElementById();
	
		if(!$order){
            echo json_encode(array('status' => 'failed', 'info' => ' Erreur, réssayer plus tard! '));
		}else{
            echo json_encode(array('status' => 'success', 'info' => ''));
		}
		exit;
    }
	if($this->getParamsIndexOf() === 'OrderAccept'){
        $order = (new Order)
                ->setId(Handler::decryptInt(Handler::getNumber(Request::post('order_id'))))
                ->setOrderStatus('A')
			    ->updateElementById();
	
		if(!$order){
            echo json_encode(array('status' => 'failed', 'info' => ' Erreur, réssayer plus tard! '));
		}else{
            echo json_encode(array('status' => 'success', 'info' => ''));
		}
		exit;
    }
	if($this->getParamsIndexOf() === 'OrderDelivery'){
        $order = (new Order)
                ->setId(Handler::decryptInt(Handler::getNumber(Request::post('order_id'))))
                ->setOrderStatus('D')
			    ->updateElementById();
	
		if(!$order){
            echo json_encode(array('status' => 'failed', 'info' => ' Erreur, réssayer plus tard! '));
		}else{
            echo json_encode(array('status' => 'success', 'info' => ''));
		}
		exit;
    }
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


    if($this->getParamsIndexOf() == 'OrderComment'){
        $ordercomment = (new Order)->setOrderUid(Request::post('order_id'))
        ->setRestaurantId(Session::get('user_id'))
        ->getElementByOrderUid();
        if($ordercomment){
            echo json_encode(array('status' => 'success', 'info' =>  $ordercomment->getRestaurantComment() ));
            exit;
        }
    }

    if($this->getParamsIndexOf() == 'OrderCommentSubmit'){
        $ordercomment = (new Order)
        ->setRestaurantId(Session::get('user_id'))
        ->setOrderUid(Request::post('order-ref'))
        ->setRestaurantComment(Request::post('restaurant-comment'))
        ->updateElementByOrderUid();
        if($ordercomment){
            echo json_encode(array('status' => 'success', 'info' =>  AlertError::success('Votre remarque enregistrer.') ));
            exit;
        }
        echo json_encode(array('status' => 'failed', 'info' =>  AlertError::failed('Opération a échoué, veuillez réessayer!') ));
        exit;
    }


	if($this->getAction() === 'List'){
        $orders = (new Order)
                ->setRestaurantId(Session::get('user_id'))
                ->setId(Handler::decryptInt(Handler::getNumber(Request::post('last_order_id'))))
                ->setStatus('1')
			    ->getNewOrdersByRestaurantIdWS();
        $newLastOrderId = (new Order)->setRestaurantId(Session::get('user_id'))->setStatus('1')->getLastOrderByRestaurantId();
		if(!$orders){
            echo json_encode(array('status' => 'none'));
		}else{
            $data = '';
            $status = array(
                'P' => '<h5><strong class="label label-warning text-uppercase"><i class="fas fa-sync"> &nbsp;En attente</strong></h5>',
                'A' => '<h5><strong class="label label-green text-uppercase"><i class="fas fa-check-circle"> &nbsp;Accepté</strong></h5>',
                'R' => '<h5><strong class="label label-danger text-uppercase"><i class="fas fa-times-circle"> &nbsp;Rejeté</strong></h5>',
                'D' => '<h5><strong class="label label-primary text-uppercase"><i class="fas fa-truck"> &nbsp;En Livraison</strong></h5>',
                'L' => '<h5><strong class="label label-success text-uppercase"><i class="fas fa-check-square"> &nbsp;commande Livré</strong></h5>'
            );
            foreach ($orders as $order) {
                $notifications = array();
                $cart = (new Cart)->read($order->getJsonOrder());
                $dateOrder = new DateTime($order->getDateTime());
                $client = (new Customer)->setUid($order->getCustomerId())->getUserByUid();
                $customerNbrOrders = (new Order)->setCustomerId($order->getCustomerId())->setStatus('1')->getCustomerNbrOrdersWS();
                $data .= '
                        <tr id="'.Handler::encryptInt($order->getId()).'" class="selected text-center">
                            <td class="text-inverse"><strong>'. $order->getOrderUid() .'</strong></td>
                            <td class="text-inverse text-capitalize">'. $client->getFullName() .'</td>
                            <td>'. $dateOrder->format('d/m/Y H:i:s') .'</td>
                            <td class="font-weight-bold">'. $customerNbrOrders .'</td>
                            <td class="font-weight-bold">'. $cart->getTotalItemsQty().'</td>
                            <td class="font-weight-bold">'. $cart->getCustomerTotalPay().' <small><b>DT</b></small></td>
                            <td class="text-center" id="'.Handler::encryptInt($order->getId()).'-status">'. $status[$order->getOrderStatus()] .'</td>
                            <td class="text-nowrap" width="1%">
                                <button data-id="'. $order->getOrderUid() .'" class="btn btn-white btn-xs m-r-3 btn-view"><i class="fa fa-eye"></i></button>
                                <button data-id="'. $order->getOrderUid() .'" class="btn btn-inverse btn-xs btn-delete"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                ';
                $notifications[] = '<p><strong>#' . $order->getOrderUid() . '</strong>: <i class="fas fa-user"></i> <i class="text-capitalize">'.$client->getFullName().'</i> passé une commande de <b>'. $cart->getCustomerTotalPay() .'</b> DT</p>';
            }
            echo json_encode(
                array(
                    'status' => 'success', 
                    'data' => $data,
                    'last_id' => Handler::encryptInt($newLastOrderId),
                    'notifications' => $notifications

                )
            );
		}
		exit;
    }
}
if(Session::get('user_auth') == 1){
    $plugins =     [
        'select2',
        'toastr',
        'invoice',
        'sweetalert',
        'datatable',
        'datepicker',
        'form-plugins',
        'google-maps',
        '--component-script-webmaster'
    ];
}else{
    $plugins =     [
        'select2',
        'toastr',
        'invoice',
        'sweetalert',
        'datatable',
        'datepicker',
        'form-plugins',
        'google-maps',
        '--component-script'
    ];
}

$this->getScriptArray(
    $plugins
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
        <?php (Session::get('user_auth') == 2) ? $this->requireTPL('orders') : $this->requireTPL('orders-webmaster');  ?>


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