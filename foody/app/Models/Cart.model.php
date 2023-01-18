<?php

/**
 *  {124, [[12,2000,2], []] }
 *  ERROR : when he want same order but with diffrent options;
 * uset $cart->sum(); after any Init of The CART.
 */

class Cart
{
    const session_name = "foody_cart";

    public $cartError = NULL;

    public $json_errors = array(

        JSON_ERROR_NONE,

        JSON_ERROR_DEPTH,

        JSON_ERROR_STATE_MISMATCH,

        JSON_ERROR_CTRL_CHAR,

        JSON_ERROR_SYNTAX,

        JSON_ERROR_UTF8
    );

    public $qty_limit = array(

        'item_id'      => null,

        'qty_limit'    => null
    );

    public $discount_qty = array(

        'item_id'  => null,

        'purchased_qty' => null,

        'free_qty' => null
    );

    public $min_price = null;

    public $max_price = null;

    public $cart = array();
    // public $cart = array(
    //      //Restaurant ID
    //      'restaurant_uid' => NULL,
    //      //Items
    //      'items' => array(
    //         //ITEM_ID => [UNIT_PRICE => , QTY =>, [[EXTRA_ID1 => EXTRA1_PRICE], [EXTRA_ID2 => EXTRA2_PRICE], ...]] (1..*)
    //         NULL => ['unit_price' => NULL, 'qty' => NULL, [NULL => NULL]],
    //         NULL => ['unit_price' => NULL, 'qty' => NULL]
    //      ),
    //     'delivery_fee'  => NULL,
    //     'discount'      => NULL,
    //     'subtotal'      => NULL,    
    //     'total'         => NULL
    // ); // (RESTO_ID, [ITEM_ID => [PRICE, QTY]], DELIVERY_FEE, ,DISCOUNT, SUB_TOTAL, TOTAL)

    public function __construct() {
        $this->read();
    }

    public function getRestaurantUid()
    {
        return !empty($this->cart['restaurant_uid']) ?  $this->cart['restaurant_uid'] : NULL;
    }
    /**
     * @return array
     */
    public function getItems()
    {
        return !empty($this->cart['items']) ?  $this->cart['items'] : NULL;
    }
    public function getDeliveryFeeCart()
    {
        return !empty($this->cart['delivery_fee']) ?  $this->cart['delivery_fee'] : 0;
    }
    public function getTotalDeliveryFeeCart()
    {
        return !empty($this->cart['total_delivery_fee']) ?  $this->cart['total_delivery_fee'] : 0;
    }

    public function getCustomerAddressId()
    {
        return !empty($this->cart['customer_address_id']) ?  $this->cart['customer_address_id'] : NULL;
    }
    
    public function getInitDistance()
    {
        return !empty($this->cart['init_distance']) ?  $this->cart['init_distance'] : null;
    }

    public function getUpFee()
    {
        return !empty($this->cart['up_fee']) ?  $this->cart['up_fee'] : null;
    }

    public function getDiscountCart()
    {
        return !empty($this->cart['discount']) ?  $this->cart['discount'] : 0;
    }

    public function getSubtotalCart()
    {
        return !empty($this->cart['subtotal']) ?  $this->cart['subtotal'] : 0;
    }

    public function getTotalCart()
    {
        return !empty($this->cart['total']) ?  $this->cart['total'] : 0;
    }
    public function getTotalDiscountCart()
    {
        return (!empty($this->cart['total_discount']) && $this->cart['total_discount'] != 0) ?  $this->cart['total_discount'] : 0;
    }
    public function getCart()
    {
        return $this->cart;
    }

    public function setRestaurantUid($uid)
    {
        // Should Order From ond 1 Restaurant
        if (!empty($this->cart['restaurant_uid']) && $uid != $this->cart['restaurant_uid']) {
            return false;
        }
        $this->cart['restaurant_uid'] = $uid;
        return $this;
    }
    public function setItems($items)
    {
        $this->cart['items'] = $items;
        return $this;
    }
    public function setDeliveryFeeCart($delivery_fee)
    {
        $this->cart['delivery_fee'] = $delivery_fee;
        return $this;
    }
    public function setTotalDeliveryFeeCart($delivery_fee)
    {
        $this->cart['total_delivery_fee'] = $delivery_fee;
        return $this;
    }
    
    public function setInitDistance($delivery_init)
    {
        $this->cart['init_distance'] = $delivery_init;
        return $this;
    }

    public function setUpFee($up_fee)
    {
        $this->cart['up_fee'] = $up_fee;
        return $this;
    }

    public function setCustomerAddressId($addrID)
    {
        $this->cart['customer_address_id'] = $addrID;
        return $this;
    }

    public function setDiscountCart($discount)
    {
        $this->cart['discount'] = $discount;
        return $this;
    }

    public function setSubtotalCart($subtotal)
    {
        $this->cart['subtotal'] = $subtotal;
        return $this;
    }

    public function setTotalCart($total)
    {
        $this->cart['total'] = $total;
        return $this;
    }
    public function setTotalDiscountCart($total_discount)
    {
        $this->cart['total_discount'] = $total_discount;
        return $this;
    }

    public function setCart($cart)
    {
        $this->cart = $cart;
        return $this;
    }

    public function read($src = NULL)
    {
        //$content  = Encryption::decode(Cookie::read(''));
        if(is_null($src)){
            $content  = Session::get(self::session_name);
            if($content){
                $this->cart = json_decode($content, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $this;
                } else {
                    $this->cart = NULL;
                    $this->json_errors[json_last_error()];
                    return $this;
                }
            }
        }else{
            $this->cart = json_decode($src, true);
            return $this;
        }
        $this->cart = NULL;
        return $this->cart;
        
    }


    public function sum()
    {
        $items = $this->getItems();
        if(!empty($this->getTotalDeliveryFeeCart())){
            $delivery_fee = $this->getTotalDeliveryFeeCart();
        }else{
            $delivery_fee = $this->getDeliveryFeeCart();
        }
        $discount = $this->getDiscountCart();
        
        $sub_total = 0;
        $total = 0;
        $total_discount = 0;

        if($this->getTotalItems()){

            foreach ($items as $item_id => $item_data) {
                foreach ($item_data as $item_id_i => $item_data_i) {
                    // reset to zero.
                    $item_extras_price = 0;

                    $item_unit_price = $item_data_i['unit_price'];
                    $item_qty = $item_data_i['qty'];

                    if (count($item_data_i['extras']) && !empty($item_data_i['extras'])) {
                        foreach ($item_data_i['extras'] as $extra_id => $extra_price) {
                            $item_extras_price += $extra_price;
                        }
                    }

                    $item_price = ($item_unit_price + $item_extras_price);

                    $this->cart['items'][$item_id][$item_id_i]['total'] = Handler::toFixed($item_price * $item_qty);
                    
                    if (array_key_exists('discount', $item_data_i) && !empty($item_data_i['discount'])) {
                        if ($item_data_i['discount']['type'] == 'QTY') {
                            $item_qty = $this->calcDiscountQty($item_qty, $item_data_i['discount']['purchased_qty'], $item_data_i['discount']['free_qty']);
                            $this->cart['items'][$item_id][$item_id_i]['total_discount'] = Handler::toFixed($item_price * $item_qty);
                        } else {
                            $item_price = Handler::getPriceByDiscount($item_unit_price, $item_data_i['discount']['type'], $item_data_i['discount']['value']);
                            $this->cart['items'][$item_id][$item_id_i]['total_discount'] = Handler::toFixed(($item_price + $item_extras_price) * $item_qty);
                        }
                    }
                    
                    $sub_total += (empty($item_data_i['total_discount'])) ? $item_data_i['total'] : $item_data_i['total_discount'];
                }
            }

            $total = $sub_total + $delivery_fee;

            if(!empty($discount)){
                $total_discount = Handler::getPriceByDiscount($sub_total, $this->cart['discount']['type'], $this->cart['discount']['value']);
                $total_discount += $delivery_fee;
            }
            if (!is_null($this->min_price)) {
                if ($total < $this->min_price) {
                    $this->setError("votre commande n'est Exiger pas le montant minimum pour livraison");
                }
            }
            if (!is_null($this->max_price)) {
                if ($total > $this->max_price) {
                    $this->setError("votre commande dépasse le montant maximum pour livraison");
                }
            }
        }
        
        $this->cart['subtotal'] = Handler::toFixed($sub_total);
        $this->cart['total'] =  Handler::toFixed($total);
        $this->cart['total_discount'] =  Handler::toFixed($total_discount);
        // print_r($this->getCart());
        return $this;
    }


    /**
     * Check if an Item exist in cart.
     *
     * @param [type] $item_id
     * @return void
     */
    public function itemExist($item_id)
    {
        if(empty($this->cart['items'])){
            return false;
        }
        return in_array($item_id, array_keys($this->cart['items']));
    }
    public function itemExistKey($item_id, $key)
    {
        return empty($this->cart['items'][$item_id][$key]);
    }

    public function removeItem($item_id, $key)
    {
        if(is_array($this->cart['items']) && is_array($this->cart['items'][$item_id]))
        if(array_key_exists($item_id, $this->cart['items']) && array_key_exists($key, $this->cart['items'][$item_id])){
            unset($this->cart['items'][$item_id][$key]);
            return true;
        }
        return false;
    }


    public function getTotalItems()
    {
        $count = 0;
        if(!empty($this->cart['items'])){
            foreach ($this->cart['items'] as $key => $value) {
                $count += count($this->cart['items'][$key]);
            }
        }
        return $count;
    }
    public function getTotalItemsQty()
    {
        $count = 0;
        if(!empty($this->cart['items'])){
            foreach ($this->cart['items'] as $item_id => $items_data) {
                foreach ($items_data as $key => $value) {
                    $count += $this->cart['items'][$item_id][$key]['qty'];
                }
            }
        }
        return $count;
    }
    public function getRestaurantGain()
    {
        $total = 0;
        $delivery_fee = 0;
        if (!empty($this->getTotalDiscountCart()) && Handler::getNumber($this->getTotalDiscountCart()) != 0) {
            $total = $this->getTotalDiscountCart();
        }else{
            $total = $this->getTotalCart();
        }
        if(!empty($this->getTotalDeliveryFeeCart())){
            $delivery_fee = $this->getTotalDeliveryFeeCart();
        }else{
            $delivery_fee = $this->getDeliveryFeeCart();
        }

        return Handler::toFixed($total - $delivery_fee);
    }

    public function getCustomerTotalPay()
    {
        $total = 0;
        if (!empty($this->getTotalDiscountCart()) && Handler::getNumber($this->getTotalDiscountCart()) != 0) {
            $total = $this->getTotalDiscountCart();
        }else{
            $total = $this->getTotalCart();
        }

        return $total;
    }

    /**
     * Check if item exist, if true then get the data.
     * @requires @addItem()
     * @param [type] $item_id
     * @return array
     */
    public function getItemData($item_id)
    {
        if ($this->itemExist($item_id)) {
            return $this->cart['items'][$item_id];
        }
        return false;
    }


    /**
     * get Qty of specified item
     *
     * @param [type] $item_id
     * @return void
     */
    public function getItemQty($item_id, $key)
    {
        return $this->itemExist($item_id) ? $this->cart['items'][$item_id][$key]['qty'] : false;
    }
    public function getItemSizeName($item_id, $key)
    {
        return !empty($this->cart['items'][$item_id][$key]['size-price']) ? $this->cart['items'][$item_id][$key]['size-price'] : false;
    }
    public function getItemComment($item_id, $key)
    {
        return !empty($this->cart['items'][$item_id][$key]['comment']) ? $this->cart['items'][$item_id][$key]['comment'] : false;
    }

    public function getItemTotalPrice($item_id, $key)
    {
        if($this->itemExist($item_id)){
            return $this->cart['items'][$item_id][$key]['total'];
        }
         return 0;
    }
    public function getItemTotalDiscountPrice($item_id, $key)
    {
        if($this->itemExist($item_id)){
            return $this->cart['items'][$item_id][$key]['total_discount'];
        }
         return 0;
    }
    public function getTotalItemPrice($item_id)
    {
        $total = 0;
        if($this->itemExist($item_id)){
            foreach ($this->cart['items'][$item_id] as $key => $item) {
                $total += $item['total'];
            }
        }
        return $total;
    }
    public function getTotalDiscountItemPrice($item_id)
    {
        $total = 0;
        if($this->itemExist($item_id)){
            foreach ($this->cart['items'][$item_id] as $key => $item) {
                $total += $item['total_discount'];
            }
        }
        return $total;
    }

    public function getTotalItemsPrice()
    {
        $items_total_price = 0;
        foreach ($this->cart['items'] as $item_id => $value) {
            $items_total_price += $this->getTotalItemPrice($item_id);
        }
    }

    public function getSubTotalPrice()
    {
        return ($this->getTotalItemsPrice() + $this->getTotalItemsExtrasPrice());
    }

    public function updateQty($item_id, $key, $qty)
    {
        if ($this->itemExist($item_id) && ($qty > 0)) {
            $this->cart['items'][$item_id][$key]["qty"] = $qty;
            return $this;
        }
        return false;
    }
    public function setQtyLimit($item_id, $Qtylimit)
    {
        $this->qty_limit['item_id'] = $item_id;
        $this->qty_limit['qty_limit'] = $Qtylimit;
        return $this;
    }

    public function setMinPrice($MinPrice)
    {
        $this->min_price = $MinPrice;
        return $this;
    }

    public function setMaxPrice($MaxPrice)
    {
        $this->max_price = $MaxPrice;
        return $this;
    }

    public function getTotalPrice()
    {
        return ($this->getSubTotalPrice() - $this->getDiscountValue()) + $this->getDeliveryFee();
    }

    public function calcDiscountQty($qty, $purchased_qty, $free_qty)
    {
        if (!empty($qty)) {
            $pfQty = $purchased_qty + $free_qty;
            if ($qty >= $pfQty) {
                $restQty = $qty - $pfQty;
                $regularQty = $purchased_qty;
                while ($restQty >= $pfQty) {
                    $restQty -= $pfQty;
                    $regularQty += $purchased_qty;
                }
                return $restQty + $regularQty;
            }
            return $qty;
        }
    }

    public function calcCartDiscount()
    {
        $total_discount = 0;
        if(!empty($this->cart['discount'])){
            $total_discount = Handler::toFixed(Handler::getPriceByDiscount($this->cart['subtotal'], $this->cart['discount']['type'], $this->cart['discount']['value']));
            if(!empty($this->getTotalDeliveryFeeCart())){
                $delivery_fee = $this->getTotalDeliveryFeeCart();
            }else{
                $delivery_fee = $this->getDeliveryFeeCart();
            }
            $this->setTotalDiscountCart(Handler::toFixed($total_discount + $delivery_fee));
        }
        return $this;
        
    }

    /**
     * Calculate total (with discount) for item
     *
     * @param array $item
     * @return array
     */
    public function calcItemTotalDiscount(array $item)
    {

        $item_total['total'] = 0;
        $item_total['total_discount'] = 0;
        
        if (!empty($item)) {
            foreach ($item as $key => $item_data) {

                $item_extras_price = 0;

                $item_unit_price = $item_data['unit_price'];
                $item_qty = $item_data['qty'];

                if (array_key_exists('extras', $item_data) && !empty($item_data['extras'])) {
                    foreach ($item_data['extras'] as $extra_id => $extra_price) {
                        $item_extras_price += $extra_price;
                    }
                }

                $item_price = ($item_unit_price + $item_extras_price);
                $item_total['total'] = Handler::toFixed($item_price * $item_qty);
                if (array_key_exists('discount', $item_data) && !empty($item_data['discount'])) {
                    if ($item_data['discount']['type'] == 'QTY') {
                        $item_qty = $this->calcDiscountQty($item_qty, $item_data['discount']['purchased_qty'], $item_data['discount']['free_qty']);
                        $item_total['total_discount'] = Handler::toFixed($item_price * $item_qty);
                    } else {
                        $item_price = Handler::getPriceByDiscount($item_unit_price, $item_data['discount']['type'], $item_data['discount']['value']);
                        $item_total['total_discount'] = Handler::toFixed(($item_price + $item_extras_price) * $item_qty);
                    }
                }
                
            }

            return $item_total;
        }
    }
    public function getNewItemTotal($item_id, $key)
    {
        $item_total['total'] = 0;
        $item_total['total_discount'] = 0;
        $item_extras_price = 0;

        if ($this->itemExist($item_id)) {

            $item_data = $this->cart['items'][$item_id][$key];
            $item_unit_price = $item_data['unit_price'];
            $item_qty = $item_data['qty'];

            if (array_key_exists('extras', $item_data) && !empty($item_data['extras'])) {
                foreach ($item_data['extras'] as $extra_id => $extra_price) {
                    $item_extras_price += $extra_price;
                }
            }

            $item_price = ($item_unit_price + $item_extras_price);
            $item_total['total'] = Handler::toFixed($item_price * $item_qty);

            if (array_key_exists('discount', $item_data) && !empty($item_data['discount'])) {
                if ($item_data['discount']['type'] == 'QTY') {
                    $item_qty = $this->calcDiscountQty($item_qty, $item_data['discount']['purchased_qty'], $item_data['discount']['free_qty']);
                    $item_total['total_discount'] = Handler::toFixed($item_price * $item_qty);
                } else {
                    $item_price = Handler::getPriceByDiscount($item_unit_price, $item_data['discount']['type'], $item_data['discount']['value']);
                    $item_total['total_discount'] = Handler::toFixed(($item_price + $item_extras_price) * $item_qty);
                }
            }

            $this->cart['items'][$item_id][$key]['total'] =  $item_total['total'];
            $this->cart['items'][$item_id][$key]['total_discount'] = $item_total['total_discount'];
                
        }
        return $this;
    }
    

    /**
     * //ITEM_ID => [UNIT_PRICE, QTY, [[EXTRA_ID1 => EXTRA1_PRICE], [EXTRA_ID2 => EXTRA2_PRICE], ...]] (1..*)
     *     NULL => [NULL, NULL, [NULL => NULL]],
     *
     * @param array $item_data
     * @return void
     */

    public function addItem(array $item_data)
    {
        //check if array is empty
        if (!empty($item_data)) {

            $item_id = key($item_data);
            $item_data = $item_data[$item_id];
            
            //check if item is exist
            if ($this->itemExist($item_id)) {
                $ftest = false;
                $key = false;
                foreach ($this->getItemData($item_id) as $key_i => $item_i) {
                    $compare_data_1 = array($item_i['unit_price'], $item_i['extras']);
                    $compare_data_2 = array($item_data['unit_price'], $item_data['extras']);
                    if(Handler::array_equal( $compare_data_1,  $compare_data_2 )){
                        $ftest = true;
                        $key = $key_i;
                    }
                }
                

                if($ftest && is_numeric($key)){
                    //update quantity
                    $current_qty = $this->getItemQty($item_id, $key);
                    $this->updateQty($item_id, $key, ($current_qty + $item_data['qty']));
                }else{
                    array_push($this->cart['items'][$item_id], $item_data);
                }
            }else{
                $this->cart['items'][$item_id] = array();
                array_push($this->cart['items'][$item_id], $item_data);
            }
            
        } else {
            return false;
        }
    }

    public function unset_cart()
    {
        $this->cart = NULL;
        Session::remove($this::session_name);
    }
    public function set()
    {
        return Session::set($this::session_name, json_encode($this->cart));
    }
    public function setError($err)
    {
        $this->cartError = $err;
    }
    public function getError()
    {
        return $this->cartError;
    }
    public function hasExtras($item_id, $key)
    {
        return !empty($this->cart['items'][$item_id][$key]['extras']) ?  count($this->cart['items'][$item_id][$key]['extras']) : 0;
    }
    public function hasDiscount($item_id, $key)
    {
        return !empty($this->cart['items'][$item_id][$key]['discount']) ?  count($this->cart['items'][$item_id][$key]['discount']) : 0;
    }
    public function getDiscount($item_id, $key)
    {
        return $this->cart['items'][$item_id][$key]['discount'];
    }




    public function cartHTML()
    {

        $data = '';
        $items = $this->getItems();
        $count_items = $this->getTotalItems();
        $cart = $this->sum();

        if($count_items != 0){
            foreach ($items as $item_id => $item_data_i) {
                $dish = (new Dish)->setId($item_id)->setStatus('1')->getElementByIdWS();
            foreach ($item_data_i as $key => $item_data)  {
                if ($dish) {
                    $dishSizeName = NULL;
                    if($this->getItemSizeName($item_id, $key)){
                        $dishSizeName = ' <small>( '.$this->getItemSizeName($item_id, $key).' )</small>';
                    }
                    $item_qty = $this->getItemQty($item_id, $key);
                    $discount = NULL;
                    $item_price = '<p class="offer-price mb-0"><span id="total-item">' . Handler::toFixed($item_data['total']) . '</span> <small><b>DT</b></small> <i class="mdi mdi-tag-outline"></i></p>';
                    $item_price_val = Handler::toFixed($item_data['total']);
                    if ($this->hasDiscount($item_id, $key)) {
                        if ($this->getDiscount($item_id, $key)['type'] == "QTY") {
                            $discount = '<span class="badge badge-success">' . $this->getDiscount($item_id, $key)['purchased_qty'] . '<i class="mdi mdi-cart"></i>:  +' . $this->getDiscount($item_id, $key)['free_qty'] . ' GRATUIT</span>';
                        }
                        if ($this->getDiscount($item_id, $key)['type'] == "%") {
                            $discount = '<span class="badge badge-success"> %' . $this->getDiscount($item_id, $key)['value'] . ' OFF </span>';
                        }
                        if ($this->getDiscount($item_id, $key)['type'] == '$') {
                            $discount = '<span class="badge badge-success"> - ' .Handler::toFixed(Handler::currency_format($this->getDiscount($item_id, $key)['value'])) . ' DT </span>';
                        }
                        $item_price = '<p class="offer-price mb-0"><span id="discount-item">' . Handler::toFixed($item_data['total_discount']) . '</span> <small><b>DT</b></small> <i class="mdi mdi-tag-outline"></i> <span id="total-item" class="regular-price">' . Handler::toFixed($item_data['total']) . '</span></p>';
                        $item_price_val = Handler::toFixed($item_data['total_discount']);;
                    }
                    
                    if (!$this->hasExtras($item_id, $key)) {
                        $data .= '
                        <div class="cart-list-product" data-item-key="'.$key.'" data-item-id="'.Handler::encryptInt($item_id).'" data-item-price="'.$item_price_val.'">
                            <a class="float-right btn btn-sm btn-danger remove-cart-item" data-original-title="Supprimer" href="#" title="" data-trigger="hover" data-placement="top" data-toggle="tooltip"><i class="mdi mdi-close-circle"></i></a>
                            <div class="row">
                                <div class="col ml-1">
                                ' . $discount . '
                                <h5 class="mt-2"><span class="mdi mdi-food"></span> <a href="#" class="text-dark">' . ucfirst($dish->getDishName()) . $dishSizeName . '</a></h5>
                                <h6><strong class="text-success"><span class="mdi mdi-approval"></span> Disponible</strong></h6>
                                ' . $item_price . '
                                </div>
                            </div>

                            <div class="float-right mt-2">
                                <div class="input-group qty">
                                    <span class="input-group-btn">
                                        <button style="height: 25px;font-size:20px;" class="btn btn-theme-round btn-number btn-qty-minus" type="button" '.($item_qty <= 1 ? "disabled" : NULL).'>-</button>
                                    </span>
                                    <input style="width: 45px;height: 25px;pointer-events: none;" type="text" name="item-qty"  max="10" min="1" value="'.$item_qty.'" class="form-control border-form-control form-control-sm input-number" maxlength="3" minlength="1" onpaste="return false;" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" required="required">
                                    <span class="input-group-btn">
                                        <button style="height: 25px;font-size:20px;" class="btn btn-theme-round btn-number btn-qty-plus" type="button" >+</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                            ';
                    } else {
                        $extras = "<ul>";
                        $extrasIdsString = implode(", ", array_keys($item_data['extras']));
                        $supplements = (new RestaurantExtras)
                                        ->setId('(' . Handler::getNumberMap($extrasIdsString) . ')')
                                        ->getElementsById();
                        if (!$supplements) {
                            echo json_encode(array('status' => 'failed', 'info' => '[0522] failed to fetch Extas'));
                            exit;
                        }
                        foreach ($supplements as $extra) {
                            $extras .= "<li><i class='mdi mdi-check'></i> ".ucfirst($extra->getExtraName())." (".$extra->getPrice()." <small>DT</small>)</li>";
                        }
                        $extras .= "</ul>";
                        
                        $data .= '
                        <div class="cart-list-product" data-item-key="'.$key.'" data-item-id="'.Handler::encryptInt($item_id).'" data-item-price="'.$item_price_val.'">
                            <a class="float-right btn btn-sm btn-danger remove-cart-item" data-trigger="hover" data-original-title="Supprimer" href="#" title="" data-placement="top" data-toggle="tooltip"><i class="mdi mdi-close-circle"></i></a>
                            <div class="row">
                                <div class="col ml-1">
                                ' . $discount . '
                                <h5 class="mt-2"><span class="mdi mdi-food"></span> <a href="#" class="text-dark">' . ucfirst($dish->getDishName()) . $dishSizeName . '</a></h5>
                                <h6><strong class="text-success"><span class="mdi mdi-approval"></span> Disponible</strong></h6>
                                ' . $item_price . '
                                </div>
                            </div>
                            <table class="float-right mt-2">
                                <tr>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-container="body" data-html="true" data-toggle="popover" data-placement="top" data-trigger="hover click" title="<small>Les Suppléments</small>" data-content="'.$extras.'" >
                                            <i class="mdi mdi-lock-plus"></i> 
                                            <small id="extras-count">x' . $this->hasExtras($item_id, $key) . '</small>
                                        </button>
                                    </td>
                                    <td>
                                        <div class="input-group qty ml-3">
                                            <span class="input-group-btn">
                                                <button style="height: 25px;font-size:20px;" class="btn btn-theme-round btn-number btn-qty-minus" type="button" '.($item_qty <= 1 ? "disabled" : NULL).'>-</button>
                                            </span>
                                            <input style="max-width: 40px;width: 45px;height: 25px;pointer-events: none;" type="text" name="item-qty" max="10" min="1" value="'.$item_qty.'" class="form-control border-form-control form-control-sm input-number" maxlength="3" minlength="1" onpaste="return false;" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" required="">
                                            <span class="input-group-btn">
                                                <button style="height: 25px;font-size:20px;" class="btn btn-theme-round btn-number btn-qty-plus" type="button" >+</button>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                            ';
                    }
                }
            }
            }
        }else{
            $data = '
            <div class="text-center">
                <img width="250" src="'.HTML_PATH_IMG.'/empty_shop_cart.svg" />
            </div>';
        }
        if(!empty($this->getTotalDeliveryFeeCart())){
            $delivery_fee = $this->getTotalDeliveryFeeCart();
        }else{
            $delivery_fee = $this->getDeliveryFeeCart();
        }
        return array(
            'count_items' => $count_items,
            'data' => $data,
            'delivery_fee' => $delivery_fee,
            'subtotal_cart' => $cart->getSubtotalCart(),
            'total_cart' => $cart->getTotalCart(),
            'total_discount_cart' => $cart->getTotalDiscountCart()
        );
    }
    public function checkoutHTML()
    {

        $data = '<div class="card-body pt-0 pr-0 pl-0 pb-0" id="checkout-content">';
        $items = $this->cart['items'];
        $count_items = $this->getTotalItems();
        $cart = $this->sum();

        if($count_items != 0){
            foreach ($items as $item_id => $item_data_i) {
                $dish = (new Dish)->setId($item_id)->setStatus('1')->getElementByIdWS();
            foreach ($item_data_i as $key => $item_data)  {
                if ($dish) {
                    $dishSizeName = NULL;
                    if($this->getItemSizeName($item_id, $key)){
                        $dishSizeName = ' <small>( '.$this->getItemSizeName($item_id, $key).' )</small>';
                    }
                    $item_qty = $this->getItemQty($item_id, $key);
                    $item_price = '<p class="offer-price mb-0"><span id="total-item">' . Handler::toFixed($item_data['total']) . '</span> <small><b>DT</b></small> <i class="mdi mdi-tag-outline"></i></p>';
                    if ($this->hasDiscount($item_id, $key)) {
                        $item_price = '<p class="offer-price mb-0"><span id="total-item">' . Handler::toFixed($item_data['total_discount']) . '</span> <small><b>DT</b></small> <i class="mdi mdi-tag-outline"></i></p>';
                    }

                    global $sizeImageFront;
                    if ($dish->getDishImage() === 'placeholder.jpg') {
                        $img = Handler::getCdnImage(0, $dish->getDishImage(), 'crop', $sizeImageFront['width'], $sizeImageFront['height']);
                    } else {
                        $img = Handler::getCdnImage($this->getRestaurantUid(), $dish->getDishImage(), 'crop', $sizeImageFront['width'], $sizeImageFront['height']);
                    }

                    $data .= '
                    <div class="cart-list-product">
                        <img class="img-thumbnail" style="height: 70px;" src="'. $img .'" alt="">
                        <h5 class="mt-2"><a href="#" class="text-dark"> <span class="text-success mdi mdi-approval"></span> '.ucfirst($dish->getDishName()) . $dishSizeName .'</a></h5>
                        '.$item_price.'
                    </div>
                    ';
                }
              }
            }
            $totalDiscountCart = NULL;
            $total = 0;
            if (!empty($cart->getTotalDiscountCart()) && Handler::getNumber($cart->getTotalDiscountCart()) != 0) {
                $totalDiscountCart = '<h6>TOTAL <small class="text-muted">(avec promotion)</small> <strong class="float-right text-danger"><span id="checkout-totaldiscount">'.$cart->getTotalDiscountCart().' </span> <small><b>DT</b></small></strong> </h6>';
                $total = $cart->getTotalDiscountCart();
            }else{
                $total = $cart->getTotalCart();
            }
            if(!empty($this->getTotalDeliveryFeeCart())){
                $delivery_fee = $this->getTotalDeliveryFeeCart();
            }else{
                $delivery_fee = $this->getDeliveryFeeCart();
            }
            $data .= '                        
            </div>
            <div class="cart-sidebar-footer">
                <div class="cart-store-details">
                    <p>Total <strong class="float-right"><span id="checkout-subtotal">'.$cart->getSubtotalCart().'</span> <small><b>DT</b></small></strong></p>
                    <p>Frais de Livraison <strong class="float-right text-danger">+ <span id="checkout-delivery">'.$delivery_fee.'</span> <small><b>DT</b></small></strong></p>
                    '.$totalDiscountCart.'
                </div>
                <hr>
                <button class="btn btn-success btn-lg btn-block text-left" type="button" style="pointer-events: none;"><strong class="float-left text-uppercase">total à payer </strong><span class="float-right"><strong id="checkout-total">'.$total.'</strong> <small><b>DT</b></small></span></button>
            </div>';
        }
        return $data;
    }
}
