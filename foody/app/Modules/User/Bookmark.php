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

require_once PATH_CONTROLLERS . 'Address.class.php';

require_once PATH_CONTROLLERS . 'City.class.php';

require_once PATH_CONTROLLERS . 'MenuCategories.class.php';

require_once PATH_CONTROLLERS . 'Dish.class.php';

require_once PATH_CONTROLLERS . 'DishExtras.class.php';

require_once PATH_CONTROLLERS . 'DishesPriceBySize.class.php';

require_once PATH_CONTROLLERS . 'CustomerDishBookmark.class.php';

require_once PATH_CONTROLLERS . 'Options.class.php';

require_once PATH_CONTROLLERS . 'Discount.class.php';

require_once PATH_MODELS      . "Error.model.php";

require_once PATH_MODELS      . "Cart.model.php";


if(!empty($_POST)){

    error_reporting(E_ALL ^ E_NOTICE);
    
    if(in_array($this->getAction(), ['Add', 'Delete'])){

        if(!empty(Session::get('customer_id'))){
            if($this->getAction() == 'Add'){
                $dishBookmark = (new CustomerDishBookmark)
                        ->resetAI()
                        ->setUserId(Session::get('customer_id'))
                        ->setDishId(Handler::getNumber(Handler::decryptInt(Request::post('item_id'))))
                        ->addElement();
                if($dishBookmark){
                    echo json_encode(array('status' => 'success'));
                    exit;
                }else{
                    echo json_encode(array('status' => 'failed'));
                    exit;
                }
            }
            if($this->getAction() == 'Delete'){
                $dishBookmark = (new CustomerDishBookmark)
                        ->setUserId(Session::get('customer_id'))
                        ->setDishId(Handler::getNumber(Handler::decryptInt(Request::post('item_id'))))
                        ->removeElementByDishId();
                if($dishBookmark){
                    echo json_encode(array('status' => 'success'));
                    exit;
                }else{
                    echo json_encode(array('status' => 'failed'));
                    exit;
                }
            }
        }else{
            echo json_encode(array('status' => 'failed'));
            exit;
        }
    }

}

?>