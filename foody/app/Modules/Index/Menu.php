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

require_once PATH_CONTROLLERS . 'RestaurantFeedback.class.php';

require_once PATH_CONTROLLERS . 'Address.class.php';

require_once PATH_CONTROLLERS . 'Order.class.php';

require_once PATH_CONTROLLERS . 'City.class.php';

require_once PATH_CONTROLLERS . 'Customer.class.php';

require_once PATH_CONTROLLERS . 'MenuCategories.class.php';

require_once PATH_CONTROLLERS . 'Dish.class.php';

require_once PATH_CONTROLLERS . 'DishExtras.class.php';

require_once PATH_CONTROLLERS . 'DishesPriceBySize.class.php';

require_once PATH_CONTROLLERS . 'CustomerDishBookmark.class.php';

require_once PATH_CONTROLLERS . 'Options.class.php';

require_once PATH_CONTROLLERS . 'Discount.class.php';

require_once PATH_MODELS      . "Error.model.php";

require_once PATH_MODELS      . "Cart.model.php";

$restoInfo = explode('-', $this->getParamsIndexOf());
$restoID = Handler::decryptInt(Handler::getNumber(end($restoInfo)));

if(!is_numeric($restoID)){
    Request::redirect( HTML_PATH_ROOT . '404' );
}
/**
 *  Stats for restaurant.
 */
$user_ip = Handler::getUserIp();

$stats_restos = PATH_VIEWS . 'stats-restos.json';

Handler::counter_specs(Handler::encryptInt($restoID), $user_ip, $stats_restos);

global $restaurant;
$restaurant = (new Restaurant)->setUid($restoID)->getUserByUid();

if(!$restaurant){
    Request::redirect( HTML_PATH_ROOT . '404' );
}

if($restaurant->getUserStatus() == 0 || $restaurant->getPartnerRequest() != 'A'){
    Request::redirect( HTML_PATH_ROOT . '404' );
}
$dish = (new Dish)->setRestaurantId($restoID)->countByRestaurantId();
if($dish == 0){
    Request::redirect( HTML_PATH_ROOT . '404' );
}

if(!empty($_POST)){

    error_reporting(E_ALL ^ E_NOTICE);

    if($this->getParamsIndexOf(1) == 'Fetch'){
        $dish_id = Handler::getNumber(Handler::decryptInt(Request::post('item_id')));
        //================== Fetch Dish ==========================//
        $dish = (new Dish)
                ->setId($dish_id)
                ->setRestaurantId($restoID)
                ->setStatus('1')
                ->getElementByRestaurantIdWS();
        
        if($dish){

            $data = '';

            //================== Fetch Prices & default Price ===========================//
            //================== Fetch NEW Prices (Discount) ===========================//
            $discount = (new DiscountCode)
                        ->setDiscountItemId($dish->getId())
                        ->setRestaurantId($restoID)
                        ->setStatus('1')
                        ->checkCodeByDishId();

            if ($dish->getPriceBySize() == 'T') {

                $dishDefaultPrice = (new DishesPriceBySize)
                                    ->setDishId($dish_id)
                                    ->getMinPriceByDishId();
                $defaultPrice = $dishDefaultPrice->getPrice();

                $dishPrices = (new DishesPriceBySize)
                                ->setDishId($dish_id)
                                ->getAllByDishId();
                if(!$dishPrices){
                    echo json_encode(array('status' => 'failed', 'info' => 'failed to fetch Sizes Prices'));
                    exit;
                }
                if($discount){
                    $defaultPrice = Handler::toFixed(Handler::getPriceByDiscount($defaultPrice, $discount->getVoucherType(), $discount->getVoucherValue()));
                    foreach ($dishPrices as $obj) {
                        $obj->setPrice(Handler::toFixed(Handler::getPriceByDiscount($obj->getPrice(), $discount->getVoucherType(), $discount->getVoucherValue())));
                    }
                }
                $data .= AlertError::itemsModalPrice($dishPrices);
               
            }else{
                if($discount){
                    $defaultPrice = Handler::toFixed(Handler::getPriceByDiscount($defaultPrice, $discount->getVoucherType(), $discount->getVoucherValue()));
                }else{
                    $defaultPrice = $dish->getPrice();
                }
            }




            //================== Fetch Extras ===========================//
            $dishExtras = (new DishExtras)->setDishId($dish_id)->countByDishId();
            if($dishExtras != 0){
                $extrasIds = (new DishExtras)
                        ->setDishId($dish_id)
                        ->getAllByDishId();
                array_walk($extrasIds, function (&$extraObj) {
                    $extraObj =  $extraObj->getExtraId();
                });
                $extrasIdsString = implode(", ",$extrasIds);
                $supplements = (new RestaurantExtras)
                            ->setId( '(' . Handler::getNumberMap($extrasIdsString) . ')' )
                            ->getElementsById();
                if(!$supplements){
                    echo json_encode(array('status' => 'failed', 'info' => 'failed to fetch Extas'));
                    exit;
                }
                $data .= AlertError::itemsModalExtras($supplements);
            }

            $dataArray = array(
                'status' => 'success',
                'info' => $data,
                'item' => Handler::encryptInt($dish_id),
                'defaultPrice' => $defaultPrice,
            );

            //================== Fetch Discount ===========================//
            $discountQty = (new QuantityPromotion)->setDiscountItemId($dish_id)->setStatus('1')->getElementByDishId();

            if($discountQty){
                $dataArray['discountQty'] = array('purchasedQty' => $discountQty->getPurchasedQty() , 'freeQty' => $discountQty->getFreeDishesNumber());
            }
            echo json_encode($dataArray);
            exit;

        }else{
            echo json_encode(array('status' => 'failed'));
            exit;
        }
    }

}

if(empty(Session::get('customer_city_id'))){
    Request::redirect(HTML_PATH_ROOT);
}

$this->getScriptArray(
    [
        '--font-style',
        'icofont',
        'rating',
        'search',
        'font-awesome',
        'sweetalert',
        '--cart-script',
        '--component-script'
    ]
);
// var_dump($_SESSION);

$this->requireTPL('p-header', PATH_PUBLIC);
$this->requireTPL('p-menu-restaurant', PATH_PUBLIC);
$this->requireTPL('p-menu-items', PATH_PUBLIC);
$this->requireTPL('p-footer', PATH_PUBLIC);
$this->requireTPL('p-base-js', PATH_PUBLIC);

?>