<?php 

//--------------------------------------------------------------------------
// Uer Authority Module.
//--------------------------------------------------------------------------

require_once  PATH_MODULES . 'Authority.module.php';

//--------------------------------------------------------------------------
// Build Objects.
//--------------------------------------------------------------------------
//users
$data['user_auth_1']                = $administrator->getAll();
$data['user_auth_2']                = $restaurant->getAll();
$data['user_auth_3']                = $delivery->getAll();
$data['user_auth_4']                = $customer->getAll();
//objects
$data['address']                    = $address->getAll();
$data['city']                       = $city->getAll();
$data['city_zone']                  = $cityZone->getAll();
$data['customer_address']           = $customerAddress->getAll();
$data['customer_dishes_bookmark']   = $customerDishesBookmark->getAll();
$data['customer_feedback']          = $customerFeedback->getAll();
$data['customer_orders_nbr']        = $customerOrdersNbr->getAll();
$data['customer_promotion']         = $customerPromotion->getAll();
$data['discount_code']              = $discountCode->getAll();
$data['discount_price']             = $discountPrice->getAll();
$data['dishes']                     = $dishes->getAll();
$data['dishes_extras']              = $dishesExtras->getAll();
$data['dishes_menu']                = $dishesMenu->getAll();
$data['modules']                    = $modules->getAll();
$data['options']                    = $options->getAll();
$data['orders']                     = $orders->getAll();
$data['order_dishes']               = $orderDishes->getAll();
$data['promotion_quantity']         = $promotionQuantity->getAll();
$data['restaurant_extras']          = $restaurantExtras->getAll();
$data['restaurant_feedback']        = $restaurantFeedback->getAll();
$data['restaurant_work']            = $restaurantWork->getAll();
$data['users_authority']            = $usersAuthority->getAll();
$data['users_logs']                 = $usersLogs->getAll();
$data['vehicle']                    = $vehicle->getAll();

?>