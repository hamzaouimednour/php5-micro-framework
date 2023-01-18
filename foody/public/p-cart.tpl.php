    <?php

        $cart = new Cart;        
        // print_r(Session::get('foody_cart'));
        // $cart->unset_cart();
        global $restaurant, $delivery_fee;

        if (!isset($restaurant) && !empty($cart->getRestaurantUid())) {
            $restaurant = (new Restaurant)->setUid($cart->getRestaurantUid())->getUserByUid() ;
        }
        if (!isset($delivery_fee)) {
            $delivery_fee = Handler::toFixed(0) ;
        }
        $cart_data = $cart->cartHTML();
        $cart->set();
        // $cart->sum();
        // print_r($cart->getCart());
        // print_r($_SESSION);
    ?>
    <span id="menu-rid" data-rid="<?=isset($restaurant) ? Handler::encryptInt($restaurant->getUid()) : NULL?>"></span>
    <div id="cart-sidebar" class="cart-sidebar">
        <div class="cart-sidebar-header">
            <h5>
                <span class="text-success"><span id="items-cart"><?=$cart_data['count_items'];?></span> Elément(s)</span> <a data-toggle="offcanvas" class="float-right" href="#"><i class="mdi mdi-close"></i>
                </a>
            </h5>
        </div>
        <div class="cart-sidebar-body">
            <?=$cart_data['data'];?>
        </div>
        <div class="cart-sidebar-footer">
            <div class="cart-store-details">
                <p>Total <strong class="float-right"><span id="subtotal-cart"><?=$cart_data['subtotal_cart']?></span> <small><b>DT</b></small></strong></p>
                <?php
                if(!empty($cart->getRestaurantUid())){
                    $selectedResto = $cart->getRestaurantUid();
                    if(!empty($selectedResto)){
                        if($restaurant->getUid() != $selectedResto){
                            echo '<span id="rdf-change" data-rdf="'.$restaurant->getUid().'"></span>';
                        }
                    }
                }
                ?>
                
                <p>Frais de Livraison <strong class="float-right text-danger">+ <span id="delivery-cart"><?=(empty($cart_data['delivery_fee']) || $cart_data['delivery_fee'] == 0) ? $delivery_fee : $cart_data['delivery_fee']?></span> <small><b>DT</b></small></strong></p>
                <div class="input-group input-group-sm mt-2 mb-2">
                    <input type="text" class="form-control" id="promo-code" placeholder="Entrer promo code">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" id="btn-discount" <?=$cart_data['total_cart'] == 0 ? 'disabled' : NULL?>><i class="mdi mdi-sale"></i> APPLY</button>
                    </div>
                </div>
            </div>
                <?php
                $orderVerification = null;
                if(!empty(Session::get('customer_id')) ){
                    $orderVerification = '' ;
                }else{
                    $orderVerification = 'data-target="#bd-login-modal" data-toggle="modal"' ;
                }
                ?>
                <button class="btn btn-secondary btn-lg btn-block text-left" <?=$orderVerification?> id="checkout-cart" type="button" <?=$cart_data['total_cart'] == 0 ? 'disabled' : NULL?>>
                    <span class="float-left text-uppercase"><i class="mdi mdi-cart-outline"></i> Passer commande </span>
                    <?php
                        $total = 0;
                        if(!empty($cart_data['total_discount_cart']) && Handler::getNumber($cart_data['total_discount_cart']) != 0){
                            $total = $cart_data['total_discount_cart'];
                        }else{
                            $total = $cart_data['total_cart'];
                        }
                    ?>
                    <span class="float-right"><strong id="total-cart"><?=$total?> </strong> <small><b>DT</b></small> <span class="mdi mdi-chevron-right"></span></span>
                </button>
        </div>
    </div>