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

require_once PATH_CONTROLLERS . 'RestaurantSpecialties.class.php';

require_once PATH_CONTROLLERS . 'RestaurantWork.class.php';

require_once PATH_CONTROLLERS . 'RestaurantExtras.class.php';

require_once PATH_CONTROLLERS . 'Customer.class.php';

require_once PATH_CONTROLLERS . 'Address.class.php';

require_once PATH_CONTROLLERS . 'City.class.php';

require_once PATH_CONTROLLERS . 'MenuCategories.class.php';

require_once PATH_CONTROLLERS . 'Dish.class.php';

require_once PATH_CONTROLLERS . 'DishExtras.class.php';

require_once PATH_CONTROLLERS . 'DishesPriceBySize.class.php';

require_once PATH_CONTROLLERS . 'CustomerDishBookmark.class.php';

require_once PATH_CONTROLLERS . 'CustomerAddress.class.php';

require_once PATH_CONTROLLERS . 'Options.class.php';

require_once PATH_CONTROLLERS . 'Discount.class.php';

require_once PATH_MODELS      . "Error.model.php";

require_once PATH_MODELS      . "Cart.model.php";

if(!empty($_POST)){

    if($this->getAction() == 'Add'){
        $address = (new Address)
                    ->resetAI()
                    ->setUserAuth('4')
                    ->setUserId(Session::get('customer_id'))
                    ->setCityId(Handler::decryptInt(Request::post('modal-city_id')))
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
        echo json_encode(array('status' => 'success', 'info' => AlertError::success('Votre adresse a été ajoutée avec succès!') ));
        exit;
    }

    if($this->getAction() == 'Edit'){

        $address = (new Address)
                    ->setId(Handler::decryptInt(Request::post('item_id')))
                    ->setUserAuth('4')
                    ->setUserId(Session::get('customer_id'))
                    ->setCityId(Handler::decryptInt(Request::post('modal-city_id')))
                    ->setAddress(Request::post('modal-address'))
                    ->setLatitude(Request::post('modal-latitude'))
                    ->setLongtitude(Request::post('modal-longtitude'))
                    ->updateByAddressId();
        if(!$address){
            echo json_encode(array('status' => 'failed', 'info' => AlertError::failed('Erreur s\'est produite lors de modifier l\'adresse!') ));
            exit;
        }
        $customerAddr = (new CustomerAddress)
                        ->setCustomerId(Session::get('customer_id'))
                        ->setAddressId(Handler::decryptInt(Request::post('item_id')))
                        ->setAddressName(Request::post('modal-address_name'))
                        ->setStreet(Request::post('modal-street'))
                        ->setBuilding(Request::post('modal-building'))
                        ->setInstructions(Request::post('modal-instructions'))
                        ->setApartmentBloc(Request::post('modal-apartment_bloc'))
                        ->setFloor(Request::post('modal-floor'))
                        ->setStatus('1');

        $customerAddr = $customerAddr->updateByAddressId();
        if(!$customerAddr){
            echo json_encode(array('status' => 'failed', 'info' => AlertError::failed('Erreur s\'est produite lors de modifier l\'adresse!') ));
            exit;
        }
        echo json_encode(array('status' => 'success', 'info' => AlertError::success('Votre adresse a été modifié avec succès!') ));
        exit;
    }
    if($this->getAction() == 'Delete'){
        $cutomerAddr = (new CustomerAddress)
        ->setCustomerId(Session::get('customer_id'))
        ->setAddressId(Handler::decryptInt(Request::post('item_id')))
        ->SetStatus('0')
        ->updateByAddressId();
        if($cutomerAddr){
            echo json_encode(array('status' => 'success', 'info' => 'Votre adresse a été supprimé avec succès!' ));
            exit;
        }
        echo json_encode(array('status' => 'failed', 'info' => 'échec de suppression, essayez à nouveau!' ));
        exit;
    }
    if($this->getParamsIndexOf() == 'Fetch'){
        $cutomerAddr = (new CustomerAddress)
        ->setCustomerId(Session::get('customer_id'))
        ->setAddressId(Handler::decryptInt(Request::post('item_id')))
        ->SetStatus('1')
        ->getElementByUserAddressIdWS();
        if(!$cutomerAddr){
            echo json_encode(array('status' => 'failed', 'info' => 'échec de suppression, essayez à nouveau!' ));
            exit;
        }

        $addr = (new Address)
        ->setUserAuth('4')
        ->setUserId(Session::get('customer_id'))
        ->setId(Handler::decryptInt(Request::post('item_id')))
        ->getElementByAddressId();
        if(!$addr){
            echo json_encode(array('status' => 'failed', 'info' => 'échec de suppression, essayez à nouveau!' ));
            exit;
        }
        $data = array(
            'city_id' => Handler::encryptInt($addr->getCityId()),
            'address' => $addr->getAddress() ,
            'latitude' => $addr->getLatitude() ,
            'longtitude' => $addr->getLongtitude() ,
            'address_name' => $cutomerAddr->getAddressName(),
            'street' => $cutomerAddr->getStreet(),
            'building' => $cutomerAddr->getBuilding(),
            'instructions' => $cutomerAddr->getInstructions()
        );
        if($cutomerAddr->getBuilding() == 'A'){
            $data['apartment_bloc'] = $cutomerAddr->getApartmentBloc();
            $data['floor'] = $cutomerAddr->getFloor();
        }
        echo json_encode(array('status' => 'success', 'data' => $data ));
        exit;
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
        '--cart-script',
        '--component-script'
    ]
);
// Start HTML
$this->requireTPL('p-header', PATH_PUBLIC);

$this->requireTPL('p-user-address', PATH_PUBLIC);
$this->requireTPL('p-footer', PATH_PUBLIC);
$this->requireTPL('p-base-js', PATH_PUBLIC);
?>