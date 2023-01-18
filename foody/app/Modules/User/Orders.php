<?php

defined('PATH_ROOT') or die('Attempt Unauthorized, Access Denied.');


//--------------------------------------------------------------------------
// Start Session.
//--------------------------------------------------------------------------
require_once PATH_MODULES     . "Session.module.php";

if(empty(Session::get('customer_id'))){

    Request::redirect( HTML_PATH_ROOT . '404' );

}

//--------------------------------------------------------------------------
// Require Controllers, Modules, Helpers.
//--------------------------------------------------------------------------

require_once PATH_CONTROLLERS . 'Restaurant.class.php';

require_once PATH_HELPERS . "URLifyHelper.php";

require_once PATH_CONTROLLERS . 'Specialties.class.php';

require_once PATH_CONTROLLERS . 'Delivery.class.php';

require_once PATH_CONTROLLERS . 'RestaurantSpecialties.class.php';

require_once PATH_CONTROLLERS . 'RestaurantWork.class.php';

require_once PATH_CONTROLLERS . 'RestaurantExtras.class.php';

require_once PATH_CONTROLLERS . 'Customer.class.php';

require_once PATH_CONTROLLERS . 'Address.class.php';

require_once PATH_CONTROLLERS . 'City.class.php';

require_once PATH_CONTROLLERS . 'MenuCategories.class.php';

require_once PATH_CONTROLLERS . 'Dish.class.php';

require_once PATH_CONTROLLERS . 'Order.class.php';

require_once PATH_CONTROLLERS . 'DishExtras.class.php';

require_once PATH_CONTROLLERS . 'DishesPriceBySize.class.php';

require_once PATH_CONTROLLERS . 'CustomerDishBookmark.class.php';

require_once PATH_CONTROLLERS . 'CustomerAddress.class.php';

require_once PATH_CONTROLLERS . 'Options.class.php';

require_once PATH_CONTROLLERS . 'Discount.class.php';

require_once PATH_MODELS      . "Error.model.php";

require_once PATH_MODELS      . "Cart.model.php";

if(!empty($_POST)){

    if($this->getParamsIndexOf() == 'CancelOrder'){
        $order_id = Handler::decryptInt(Request::post('order_id'));
        $orderCheck = (new Order)->setId($order_id)
        ->setCustomerId(Session::get('customer_id'))
        ->getElementByCustomerIdOrderId();
        if($orderCheck){
            if($orderCheck->getOrderStatus() == 'P' && $orderCheck->getStatus() != 0){
                $order = (new Order)->setId($order_id)
                ->setCustomerId(Session::get('customer_id'))
                ->setStatus('0')
                ->updateElementByCustomerId();
                if($order){
                    $delivery = (new Delivery)
                    ->setUid($orderCheck->getDeliveryId())
                    ->setAvailability('1')
                    ->updateUserByUid();
                    if($delivery){
                        echo json_encode(array('status' => 'success', 'info' => 'Commande annulée avec succès'));
                        exit;
                    }
                    echo json_encode(array('status' => 'failed', 'info' => 'Operation echoué, cant chnage delivery status'));
                    exit;
                }else{
                    echo json_encode(array('status' => 'success', 'info' => 'xxOpération a échoué, essayez à nouveau'));
                    exit;
                }
            }else{
                echo json_encode(array('status' => 'failed', 'info' => 'Échoué, impossible d\'annuler la commande dans cet état.'));
                exit;
            }
        }else{
            echo json_encode(array('status' => 'failed', 'info' => 'Operation echoué, Commande introuvable. '));
            exit;
        }
    }
}

$this->getScriptArray(
    [
        'google-maps',
        '--add-map-address',
        'icofont',
        'search',
        'font-awesome',
        'sweetalert',
        'datatables',
        '--cart-script',
        '--component-script'
    ]
);
// Start HTML
$this->requireTPL('p-header', PATH_PUBLIC);

$this->requireTPL('p-user-orders', PATH_PUBLIC);
$this->requireTPL('p-footer', PATH_PUBLIC);
$this->requireTPL('p-base-js', PATH_PUBLIC);
?>