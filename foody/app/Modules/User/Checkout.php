<?php

defined('PATH_ROOT') or die('Attempt Unauthorized, Access Denied.');


//--------------------------------------------------------------------------
// Start Session.
//--------------------------------------------------------------------------
require_once PATH_MODULES     . "Session.module.php";

//--------------------------------------------------------------------------
// Require Controllers, Modules, Helpers.
//--------------------------------------------------------------------------

require_once PATH_CONTROLLERS . 'Restaurant.class.php';

require_once PATH_HELPERS . "URLifyHelper.php";

require_once PATH_CONTROLLERS . 'Specialties.class.php';

require_once PATH_CONTROLLERS . 'RestaurantSpecialties.class.php';

require_once PATH_CONTROLLERS . 'RestaurantWork.class.php';

require_once PATH_CONTROLLERS . 'RestaurantExtras.class.php';

require_once PATH_CONTROLLERS . 'Customer.class.php';

require_once PATH_CONTROLLERS . 'CustomerAddress.class.php';

require_once PATH_CONTROLLERS . 'Address.class.php';

require_once PATH_CONTROLLERS . 'City.class.php';

require_once PATH_CONTROLLERS . 'MenuCategories.class.php';

require_once PATH_CONTROLLERS . 'Dish.class.php';

require_once PATH_CONTROLLERS . 'DishExtras.class.php';

require_once PATH_CONTROLLERS . 'DishesPriceBySize.class.php';

require_once PATH_CONTROLLERS . 'CustomerDishBookmark.class.php';

require_once PATH_CONTROLLERS . 'Options.class.php';

require_once PATH_CONTROLLERS . 'Order.class.php';

require_once PATH_CONTROLLERS . 'Discount.class.php';

require_once PATH_MODELS      . "Error.model.php";

require_once PATH_MODELS      . "Cart.model.php";

$cart = (new Cart)->getRestaurantUid();

if(empty($cart) || empty(Session::get('customer_id')) ){
    Request::redirect( HTML_PATH_ROOT . '404' );
}
if (!empty($_POST)) {

    error_reporting(E_ALL ^ E_NOTICE);
  
    if ($this->getAction() == 'List') {
        $cart = (new Cart)->checkoutHTML();
        if(!empty($cart)){
            echo json_encode(
                array('status' => 'success', 'data' => $cart)
            );
            exit;
        }
        echo json_encode(
            array('status' => 'failed', 'info' => 'Le panier est vide !')
        );
        exit;
    }
    if ($this->getParamsIndexOf() == 'Address') {
        $addr_id = Handler::decryptInt(Handler::getNumber(Request::post('addr_id')));
        $customerAddress = (new Address)->setId($addr_id)->setUserAuth('4')->setUserId(Session::get('customer_id'))->getElementByAddressId();
        if(!$customerAddress){
            echo json_encode(
                array('status' => 'failed', 'info' => 'Echec! Adresse n\'est pas existe.')
            );
            exit;
        }

        $cart = (new Cart);
        $restoAddress = (new Address)->setUserAuth('2')->setUserId($cart->getRestaurantUid())->getElementByUserId();
        $data = array(
            'origins' => $restoAddress->getLatitude() . ',' . $restoAddress->getLongtitude(),
            'destinations' => $customerAddress->getLatitude() . ',' . $customerAddress->getLongtitude()
        );
        $jsonData = Handler::getGMapJson($data);
        $distance = $jsonData->rows[0]->elements[0]->distance->value / 1000;
        if($distance > 10){
            echo json_encode(
                array('status' => 'failed', 'info' => 'La livraison est indisponible pour votre adresse.')
            );
            exit;
        }
        $total_delivery_fee = Handler::deliveryFee($distance, $cart->getDeliveryFeeCart(), $cart->getInitDistance(), $cart->getUpFee() );
        $cart->setTotalDeliveryFeeCart($total_delivery_fee);
        $cart->setCustomerAddressId($addr_id);
        $cart->set();
        echo json_encode(
            array('status' => 'success', 'new_fee' => $total_delivery_fee)
        );
        exit;
    }

    if($this->getAction() == 'Add'){
        $restoAddress = (new Address)->setUserAuth('2')->setUserId($cart)->getElementByUserId();
        $cityId = $restoAddress->getCityId();
        $address = (new Address)
                    ->resetAI()
                    ->setUserAuth('4')
                    ->setUserId(Session::get('customer_id'))
                    ->setCityId($cityId)
                    ->setAddress(Request::post('modal-address'))
                    ->setLatitude(Request::post('modal-latitude'))
                    ->setLongtitude(Request::post('modal-longtitude'))
                    ->addElement(true);
        if(!$address){
            echo json_encode(array('status' => 'failed', 'info' => AlertError::failed('Erreur s\'est produite lors de l\'ajout d\'adresse!') ));
            exit;
        }
        $customerAddr = (new CustomerAddress)
                        ->resetAI()
                        ->setCustomerId(Session::get('customer_id'))
                        ->setAddressId($address)
                        ->setAddressName(Request::post('modal-address_name'))
                        ->setStreet(Request::post('modal-street'))
                        ->setBuilding(Request::post('modal-building'))
                        ->setInstructions(Request::post('modal-instructions'))
                        ->setStatus('1');
        if(Request::post('modal-building') == 'A'){
            $customerAddr= $customerAddr->setApartmentBloc(Request::post('modal-apartment_bloc'))
                                        ->setFloor(Request::post('modal-floor'));
        }
        $customerAddr = $customerAddr->addElement();
        if(!$customerAddr){
            echo json_encode(array('status' => 'failed', 'info' => AlertError::failed('Erreur s\'est produite lors de l\'ajout d\'adresse!') ));
            exit;
        }
        $option = '<option value="'.Handler::encryptInt($address).'" data-lat="'.Request::post('modal-latitude').'" data-lng="'.Request::post('modal-longtitude').'">'.Request::post('modal-address_name').'</option>';
        echo json_encode(array('status' => 'success', 'info' => AlertError::success('Votre adresse a été ajoutée avec succès!'), 'data' => $option ));
        exit;
    }

    if ($this->getParamsIndexOf() == 'Submit') {

        global $dateTime;
        $cart = new Cart;

        // Json data Google map between Restaurant & Customer.
        $customerAddress = (new Address)->setId($cart->getCustomerAddressId())->setUserAuth('4')->setUserId(Session::get('customer_id'))->getElementByAddressId();
        $restoAddress = (new Address)->setUserAuth('2')->setUserId($cart->getRestaurantUid())->getElementByUserId();
        $data = array(
            'origins' => $restoAddress->getLatitude() . ',' . $restoAddress->getLongtitude(),
            'destinations' => $customerAddress->getLatitude() . ',' . $customerAddress->getLongtitude()
        );
        $jsonGMapCustomer = json_encode(Handler::getGMapJson($data));

        // Jheck The Near DeliveryMan is near the restaurant.
        $delivery = (new Address)
                    ->setUserAuth('3')
                    ->setCityId($restoAddress->getCityId())
                    ->getAllDelivery();
        if($delivery){

            $deliveryUsersArray = array();
            foreach ($delivery as $obj) {
                $deliveryUsersArray[$obj->getUserId()] = $obj->getLatitude() .','. $obj->getLongtitude();
            }
            $destinations = implode('|', $deliveryUsersArray );
            $data = array(
                'origins' => $restoAddress->getLatitude() . ',' . $restoAddress->getLongtitude(),
                'destinations' => $destinations 
            );
            $jsonGMap = Handler::getGMapJson($data);
            $elements = $jsonGMap->rows[0]->elements;
            $minDuration = (int) $elements[0]->duration->value;
            $spKey = 0;
            foreach ($elements as $key => $obj) {
                if($obj->duration->value < $minDuration){
                    $minDuration = $obj->duration->value;
                    $spKey = (int) $key;
                }
            }

            $deliveryUsersKeys = array_keys( $deliveryUsersArray );

            // get The User ID
            $deliveryID = $deliveryUsersKeys[$spKey];

        }else{
            echo json_encode(array('status' => 'failed', 'info' => 'No Delivery Avaible right now'));
            exit;
        }

        //Cart validation
        $cart->sum();
        $cart->set();
        
        // Insert Order.
        $restoWork = (new RestaurantWork)->setRestaurantId($cart->getRestaurantUid())->getElementByRestaurantId();
        if($restoWork->getDeliveryType() == 1){
            $deliveryID = '0';
        }
        $nbrOrders = (new Order)->setCustomerId(Session::get('customer_id'))->getCustomerNbrOrders();
        $customerNbrOrders = (new Order)->setCustomerId(Session::get('customer_id'))->setStatus('1')->getCustomerNbrOrdersWS();
        $order = (new Order)
        ->resetAI()
        ->setOrderUid(Handler::generateOrderId(Session::get('customer_id'), ($nbrOrders+1) ))
        ->setCustomerId(Session::get('customer_id'))
        ->setAddressId($cart->getCustomerAddressId())
        ->setRestaurantId($cart->getRestaurantUid())
        ->setDeliveryId($deliveryID)
        ->setCustomerErrorCase(Handler::getNumber(Request::post('order_error')))
        ->setDateTime($dateTime->format('Y-m-d H:i:s'))
        ->setOrderNum( ($customerNbrOrders+1) )
        ->setOrderStatus('P')
        ->setStatus('1')
        ->setJsonOrder(Session::get($cart::session_name))
        ->setJsonGoogleMap($jsonGMapCustomer)
        ->addElement();

        if($order){
            if($deliveryID != 0){
                $deliveryMan = (new Delivery)->setUid($deliveryID)->setAvailability('1')->updateUserByUid();
            }
            echo json_encode(array('status' => 'success', 'info' => 'Félicitations! Votre commande a été soumise.'));
            exit;
        }
        echo json_encode(array('status' => 'failed', 'info' => 'Opération a échoué, veuillez réessayer plus tard.'));
        exit;
    }
}

$this->getScriptArray(
    [
        'google-maps',
        '--init-map-checkout',
        '--font-style',
        'icofont',
        'search',
        'font-awesome',
        'sweetalert',
        '--cart-script',
        '--component-script'
    ]
);


$this->requireTPL('p-header', PATH_PUBLIC);
$this->requireTPL('p-user-checkout', PATH_PUBLIC);
$this->requireTPL('p-footer', PATH_PUBLIC);
$this->requireTPL('p-base-js', PATH_PUBLIC);

?>