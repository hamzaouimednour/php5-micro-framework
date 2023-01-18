<?php
require_once PATH_MODULES     . "Session.module.php";

require_once PATH_CONTROLLERS . 'Restaurant.class.php';

require_once PATH_HELPERS . "URLifyHelper.php";

require_once PATH_CONTROLLERS . 'Specialties.class.php';

require_once PATH_CONTROLLERS . 'RestaurantSpecialties.class.php';

require_once PATH_CONTROLLERS . 'RestaurantWork.class.php';

require_once PATH_CONTROLLERS . 'Options.class.php';

require_once PATH_CONTROLLERS . 'RestaurantExtras.class.php';

require_once PATH_CONTROLLERS . 'Address.class.php';

require_once PATH_CONTROLLERS . 'MenuCategories.class.php';

require_once PATH_CONTROLLERS . 'Dish.class.php';

require_once PATH_CONTROLLERS . 'City.class.php';

require_once PATH_CONTROLLERS . 'DishExtras.class.php';

require_once PATH_CONTROLLERS . 'DishesPriceBySize.class.php';

require_once PATH_CONTROLLERS . 'CustomerDishBookmark.class.php';

require_once PATH_CONTROLLERS . 'Discount.class.php';

require_once PATH_MODELS      . "Error.model.php";

require_once PATH_MODELS      . "Cart.model.php";

if (!empty($_POST)) {

  error_reporting(E_ALL ^ E_NOTICE);

  if ($this->getParamsIndexOf() == 'AddItem') {

    if(Request::post('reset-cart')){
      $cartReset = (new Cart)->unset_cart();
    }
    
    $dish_id = Handler::decryptInt(Handler::getNumber(Request::post('item-id')));

    $dish = (new Dish)->setId($dish_id)->setStatus('1')->getElementById();

    if ($dish) {

      $item = array(
        $dish_id => [
          'unit_price' => NULL,
          'qty' => 0,
          'extras' => array()
          // 'discount' => ['type' => '', 'value' => '']
          // 'discount' => ['type' => '', 'purchased_qty' => '', 'free_qty' => '']
          // 'total' => 0
          // 'total_discount' => 0
          ]
        );

      //--------------------------------------------------------------------------  
      // @global CART: read cart data  from session.
      //--------------------------------------------------------------------------

      $cart = (new Cart);
      $cart->read();
      
      //================== Check Restaurant ===========================//
      if (empty($cart->getRestaurantUid())) {

        $restoWork = (new RestaurantWork)->setRestaurantId($dish->getRestaurantId())->getElementByRestaurantId();

        $cart->setRestaurantUid($dish->getRestaurantId());
        
        if($restoWork->getDeliveryType() == 1){
          $cart->setDeliveryFeeCart($restoWork->getDeliveryFee());
        }else{
          $op = (new Options)->setOptionName('delivery_fee')->getElementByOptionName();
          $cart->setDeliveryFeeCart($op->getOptionValue());
        }

      } else {
        if ($dish->getRestaurantId() != $cart->getRestaurantUid()) {
          echo json_encode(array('status' => 'failed', 'info' => "Votre panier contient des articles d'un autre restaurant. Voulez-vous réinitialiser votre panier pour ajouter des éléments de ce restaurant?"));
          exit;
        }
      }
      
      if(empty(Handler::getNumber(Request::post('items-qty')))){
        echo json_encode(array('status' => 'failed', 'info' => "La quantité du produit Invalide, doit au minimum égal a 1 !"));
        exit;
      }

      //--------------------------------------------------------------------------
      // @global QTY: insert product quantity.
      //--------------------------------------------------------------------------

      $item[$dish_id]['qty'] = Handler::getNumber(Request::post('items-qty'));

      $item[$dish_id]['comment'] = Request::post('item-instructions');

      //--------------------------------------------------------------------------
      // @todo Fetch all types of discount to use them.
      //--------------------------------------------------------------------------

      // $discountCart = (new DiscountCode)
      //             ->setDiscountItemId('0')
      //             ->setStatus('1')
      //             ->checkCodeByDishId();
      // if ($discountCart) {
      //   $cart['discount']['cart'] = true;
      //   $cart['discount']['type'] = $discount->getVoucherType();
      //   $cart['discount']['value'] = $discount->getVoucherValue();
      // }

      $discount = (new DiscountCode)
                  ->setDiscountItemId($dish_id)
                  ->setRestaurantId($dish->getRestaurantId())
                  ->setStatus('1')
                  ->checkCodeByDishId();
      if ($discount) {
        $item[$dish_id]['discount']['type']   = $discount->getVoucherType();
        $item[$dish_id]['discount']['value']  = $discount->getVoucherValue();
      }

      $discountQty = (new QuantityPromotion)->setDiscountItemId($dish_id)->setStatus('1')->getElementByDishId();

      if ($discountQty) {
        $item[$dish_id]['discount']['type']           = 'QTY';
        $item[$dish_id]['discount']['purchased_qty']  = $discountQty->getPurchasedQty();
        $item[$dish_id]['discount']['free_qty']       = $discountQty->getFreeDishesNumber();
      }

      //--------------------------------------------------------------------------
      // @todo Check Selected Price.
      // @property float $defaultPrice: = Product Price.
      //--------------------------------------------------------------------------

      $defaultPrice = 0;
      if ($dish->getPriceBySize() == 'T') {

        $dishPrices = (new DishesPriceBySize)
                      ->setDishId($dish_id)
                      ->getAllByDishId();

        if (!$dishPrices) {
          echo json_encode(array('status' => 'failed', 'info' => 'Produit n\'est pas disponible avec ce prix!'));
          exit;
        }

        foreach ($dishPrices as $object) {

          if($object->getId() == Handler::decryptInt(Request::post('item-size-price')) ){

            $item[$dish_id]['size-price'] = $object->getSizeName();
            $defaultPrice = $object->getPrice();

          }

        }
      } else {

        $defaultPrice = $dish->getPrice();

      }

      if( $defaultPrice == 0 ){
        echo json_encode(array('status' => 'failed', 'info' => "Prix du produit Inconnu, svp contacter l'administrateur!"));
        exit;
      }


      //--------------------------------------------------------------------------
      // @global UNIT_PRICE: insert product price.
      //--------------------------------------------------------------------------

      $item[$dish_id]['unit_price'] = $defaultPrice;

      //--------------------------------------------------------------------------
      // @todo Check Supplements existance/ checked.
      //--------------------------------------------------------------------------

      if(!empty(Request::post('item-extras'))){

        $dishExtras = (new DishExtras)->setDishId($dish_id)->countByDishId();

        if ($dishExtras != 0) {

          $selectedExtras = array();

          $extrasIds = (new DishExtras)->setDishId($dish_id)->getAllByDishId();
            
          array_walk($extrasIds, function (&$extraObj) {
            $extraObj =  $extraObj->getExtraId();
          });

          foreach (Request::post('item-extras') as $extraId) {
            $extraId = Handler::decryptInt(Handler::getNumber($extraId));
            if(in_array($extraId, $extrasIds)){
              $selectedExtras[] = $extraId;
            }
          }

          $extrasIdsString = implode(", ", $selectedExtras);
          if(empty($selectedExtras)){
            echo json_encode(array('status' => 'failed', 'info' => 'La/Les Supplément(s) inconnu(s), essayez à nouveau!'));
            exit;
          }
          $supplements = (new RestaurantExtras)->setId('(' . Handler::getNumberMap($extrasIdsString) . ')')->getElementsById();
          if (!$supplements) {
            echo json_encode(array('status' => 'failed', 'info' => 'La/Les Supplément(s) inconnu(s), essayez à nouveau!'));
            exit;
          }

          //--------------------------------------------------------------------------
          // @global EXTRAS: insert product extras.
          //--------------------------------------------------------------------------

          foreach ($supplements as $object) {
            $item[$dish_id]['extras'][$object->getId()] = $object->getPrice();
          }

        }else{
          echo json_encode(array('status' => 'failed', 'info' => 'La/Les Supplément(s) inconnu(s), essayez à nouveau!'));
          exit;
        }
        
      }
       

      //--------------------------------------------------------------------------
      // @global Calculate.
      //--------------------------------------------------------------------------



      //--------------------------------------------------------------------------
      // @global Insert Item in cart.
      //--------------------------------------------------------------------------
      $item_total = $cart->calcItemTotalDiscount($item);
      $item[$dish_id]['total'] = $item_total['total'];
      $item[$dish_id]['total_discount'] = $item_total['total_discount'];
      
      $cart->addItem($item);
      $cart->sum();
      $cart->set();
      echo json_encode(array('status' => 'success', 'info' => ''));
      exit;


    } else {
      echo json_encode(array('status' => 'failed', 'info' => 'Erreur survenue, produit selectionnez Invalide!'));
      exit;
    }
  }

  if ($this->getParamsIndexOf() == 'CheckItem') {
    $cart = (new Cart)->read();

    //================== Check Restaurant ===========================//
    if (empty($cart)) {
      echo json_encode(array('status' => 'success', 'info' => ''));
      exit;
    } else {

      $dish_id = Handler::decryptInt(Handler::getNumber(Request::post('item-id')));

      $dish = (new Dish)->setId($dish_id)->setStatus('1')->getElementById();

      if ($dish->getRestaurantId() != $cart->getRestaurantUid()) {
        echo json_encode(array('status' => 'failed', 'info' => "Votre panier contient des articles d'un autre restaurant. Voulez-vous réinitialiser votre panier pour ajouter des éléments de ce restaurant?"));
        exit;
      }else{
        echo json_encode(array('status' => 'success', 'info' => ''));
        exit;
      }

    }
  }

  if ($this->getParamsIndexOf() == 'Init') {
    $cart = new Cart;
    //================== Check Restaurant ===========================//
    echo json_encode(array('status' => 'success', 'info' => $cart->cartHTML()));
    exit;
  }


  if ($this->getParamsIndexOf() == 'RemoveItem') {
    $cart = new Cart;
    $total = 0;
    if($cart->removeItem(Handler::decryptInt(Request::post('item_id')), Request::post('item_key'))){
      $cart->sum();
      
      if(!empty($cart->getTotalDiscountCart())){
        $cart->calcCartDiscount();
      }
      
      if($cart->getTotalItems()){
        $cart->set();
      }else{
        $cart->unset_cart();
      }
      $delivery_fee = 0;
      if(!empty(Request::post('rdf')) && empty($cart->getTotalItems())){
        $restoWork = (new RestaurantWork)->setRestaurantId(Handler::getNumber(Request::post('rdf')))->getElementByRestaurantId();
            if($restoWork->getDeliveryType() == 1){
                $delivery_fee = $restoWork->getDeliveryFee();

            }else{
                $op = (new Options)->setOptionName('delivery_fee')->getElementByOptionName();
                $delivery_fee = $op->getOptionValue();
            }
      }
      if(!empty($cart->getTotalDiscountCart())){
        echo json_encode(array('status' => 'success', 'info' => '' , 'subtotal_cart' => Handler::toFixed($cart->getSubtotalCart()), 'total_cart' => Handler::toFixed($cart->getTotalDiscountCart()), 'delivery_fee' => $delivery_fee, 'count_items' => $cart->getTotalItems() ));
        exit;
      }

      echo json_encode(array('status' => 'success', 'info' => '' , 'subtotal_cart' => Handler::toFixed($cart->getSubtotalCart()), 'total_cart' => Handler::toFixed($cart->getTotalCart()), 'delivery_fee' => $delivery_fee, 'count_items' => $cart->getTotalItems() ));
      exit;
    }
    echo json_encode(array('status' => 'failed', 'info' => 'Element n\'existe pas dans la panier'));
    exit;

    //================== Check Restaurant ===========================//
    echo json_encode(array('status' => 'success', 'info' => $cart->cartHTML()));
    exit;
  }


  if ($this->getParamsIndexOf() == 'ItemQty') {
    $cart = new Cart;    
    $item_id = Handler::decryptInt(Request::post('item_id'));
    $item_key = Request::post('item_key');
    if($cart->updateQty($item_id, $item_key, Request::post('item_qty'))){
      $cart->getNewItemTotal($item_id, $item_key);
      $cart->sum();
      $cart->set();
      
      if ($cart->hasDiscount($item_id, $item_key)) {
        echo json_encode(array('status' => 'success', 'total_item' => $cart->getItemTotalPrice($item_id, $item_key), 'discount_item' => $cart->getItemTotalDiscountPrice($item_id, $item_key), 'subtotal_cart' => $cart->getSubtotalCart(), 'total_cart' => $cart->getTotalCart(), 'total_discount_cart' => $cart->getTotalDiscountCart() ));
        exit;
      }
      echo json_encode(array('status' => 'success', 'total_item' => $cart->getItemTotalPrice($item_id, $item_key), 'subtotal_cart' => $cart->getSubtotalCart(), 'total_cart' => $cart->getTotalCart(), 'total_discount_cart' => $cart->getTotalDiscountCart() ));
      exit;
    }
    echo json_encode(array('status' => 'failed', 'info' => 'Element n\'existe pas dans la panier'));
    exit;

    //================== Check Restaurant ===========================//
    echo json_encode(array('status' => 'success', 'info' => $cart->cartHTML()));
    exit;
  }


  if ($this->getParamsIndexOf() == 'CartDiscount') {

    $cart = new Cart;    

    $rid = Handler::decryptInt(Handler::getNumber(Request::post('rid')));
    $promo_code = Request::post('promo_code');
    if(!empty($cart->cart['discount']) && $cart->cart['discount']['code'] == mb_strtoupper($promo_code)){
      echo json_encode(array('status' => 'failed', 'info' => 'Le code de promotion déja utilisé'));
      exit;
    }
    $discountCart = (new DiscountCode)
                  ->setDiscountItemId('0')
                  ->setRestaurantId($rid)
                  ->setStatus('1')
                  ->checkCodeByDishId();
    if ($discountCart) {
      if(mb_strtoupper($discountCart->getVoucherUid()) == mb_strtoupper($promo_code)){
        $cart->cart['discount'] = array('code' => $discountCart->getVoucherUid(), 'type' => $discountCart->getVoucherType(), 'value' => $discountCart->getVoucherValue());
        $cart->calcCartDiscount();
        $cart->set();
        echo json_encode(array('status' => 'success', 'total_item' => $cart->getTotalDiscountCart()));
        exit;
      }
      echo json_encode(array('status' => 'failed', 'info' => 'Le code de promotion n\'est pas valide'));
      exit;

    }
    
    echo json_encode(array('status' => 'failed', 'info' => 'Le code de promotion n\'est pas valide'));
    exit;

  }

}
//  else {
//   Request::redirect(HTML_PATH_ROOT . '404');
// }