<?php
require_once PATH_MODULES . "Authority.module.php";

if(!in_array(Session::get('user_auth'), [1,2])){
	Request::redirect(HTML_PATH_BACKEND . 'dashboard');
}

require_once PATH_CONTROLLERS . 'Order.class.php';

require_once PATH_CONTROLLERS . 'Administrator.class.php';

require_once PATH_CONTROLLERS . 'Customer.class.php';

require_once PATH_CONTROLLERS . 'CustomerAddress.class.php';

require_once PATH_CONTROLLERS . 'Restaurant.class.php';

require_once PATH_CONTROLLERS . 'Delivery.class.php';

require_once PATH_CONTROLLERS . 'Dish.class.php';

require_once PATH_CONTROLLERS . 'Specialties.class.php';

require_once PATH_CONTROLLERS . 'RestaurantSpecialties.class.php';

require_once PATH_CONTROLLERS . 'RestaurantExtras.class.php';

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



error_reporting(E_ALL ^ E_NOTICE);

if (!empty($this->getParamsIndexOf()) && empty($this->getParamsIndexOf(1))) {
    $order = (new Order)
        ->setOrderUid($this->getParamsIndexOf())
        ->setRestaurantId(Session::get('user_id'))
        ->getElementByOrderUid();
    if (!$order) {
        echo json_encode(array('status' => 'failed', 'info' => 'Operation echoué, Réssayer une autre fois. '));
    }
    $cart = (new Cart)->read($order->getJsonOrder());

    if($order->getDeliveryId() == '0'){
        $delivery = NULL;
    }else{
        $delivery = (new Delivery)->setUid($order->getDeliveryId())->getUserByUid();
    }
    $deliveryAddress = (new Address)->setUserAuth('3')->setUserId($order->getDeliveryId())->getElementByUserId();

    $client = (new Customer)->setUid($order->getCustomerId())->getUserByUid();
    $clientAddress = (new Address)->setId($cart->getCustomerAddressId())->setUserAuth('4')->setUserId($order->getCustomerId())->getElementByAddressId();
    $customerAddress = (new CustomerAddress)->setAddressId($cart->getCustomerAddressId())->setCustomerId($order->getCustomerId())->getElementByUserAddressId();
    $cAddr = $customerAddress->getStreet();
    if($customerAddress->getBuilding() == 'A'){
        $cAddr .= 'Bâtiment Résidentiel : ' .  $customerAddress->getApartmentBloc(). ' , étage N° '.  $customerAddress->getFloor();
    }
    $city = (new City)->setId($clientAddress->getCityId())->getElementById();

}elseif( !empty($this->getParamsIndexOf()) && $this->getParamsIndexOf(1) == 'webmaster' ){

    $order = (new Order)
            ->setOrderUid($this->getParamsIndexOf())
            ->getElementByOrderUidWP();
    if (!$order) {
        echo json_encode(array('status' => 'failed', 'info' => 'Operation echoué, Réssayer une autre fois. '));
        exit;
    }
    $cart = (new Cart)->read($order->getJsonOrder());
    if($order->getDeliveryId() == '0'){
        $delivery = NULL;
    }else{
        $delivery = (new Delivery)->setUid($order->getDeliveryId())->getUserByUid();
    }

    $deliveryAddress = (new Address)->setUserAuth('3')->setUserId($order->getDeliveryId())->getElementByUserId();
    $client = (new Customer)->setUid($order->getCustomerId())->getUserByUid();
    $restaurant = (new Restaurant)->setUid($order->getRestaurantId())->getUserByUid();
    $restaurantAddr = (new Address)->setUserAuth('2')->setUserId($order->getRestaurantId())->getElementByUserId();
    $clientAddress = (new Address)->setId($cart->getCustomerAddressId())->setUserAuth('4')->setUserId($order->getCustomerId())->getElementByAddressId();
    $customerAddress = (new CustomerAddress)->setAddressId($cart->getCustomerAddressId())->setCustomerId($order->getCustomerId())->getElementByUserAddressId();
    $cAddr = $customerAddress->getStreet();
    if($customerAddress->getBuilding() == 'A'){
        $cAddr .= ' Bâtiment Résidentiel : ' .  $customerAddress->getApartmentBloc(). ' , étage N° '.  $customerAddress->getFloor();
    }
    $city = (new City)->setId($clientAddress->getCityId())->getElementById();

}else{
    die('Unauthorized permission!');
}

?>
<div class="modal-body">
    <div id="modal-info-section"></div>

    <div class="invoice">

        <!-- begin invoice-header -->
        <div class="invoice-header">
            <div class="invoice-from">
                <i class="fas fa-user"></i> Client :
                <address class="m-t-5 m-b-5">
                    <strong class="text-inverse text-capitalize"><?= $client->getFullName() ?></strong><br />
                    <span style="word-break: break-word;"><?= ucfirst($clientAddress->getAddress()) ?></span><br />
                    <span style="word-break: break-word;"><?= ucfirst($cAddr) ?></span><br />
                    <span><?= $city->getCityName() ?></span><br />
                    <span>(+216) <?= $client->getPhone()  ?></span><br />
                </address>
            </div>
            <div class="invoice-to">
                <i class="fas fa-motorcycle"></i> Livreur :
                <?php 
                    if(is_null($delivery)){
                ?>
                <address class="m-t-5 m-b-5">
                    <strong class="text-inverse text-uppercase text-green">Livraison par Restaurant</strong><br />
                </address>
                <?php
                    }else{ 
                ?>
                <address class="m-t-5 m-b-5">
                    <strong class="text-inverse text-capitalize"><?= $delivery->getFullName() ?></strong><br />
                    <span style="word-break: break-word;"><?= ucfirst($deliveryAddress->getAddress()) ?></span><br />
                    <span><?= $city->getCityName() ?></span><br />
                    <span>(+216) <?= $delivery->getPhone()  ?></span><br />
                </address>
                <?php } ?>
            </div>

            <?php
                if(Session::get('user_auth') == '1'){
            ?>
            <div class="invoice-to">
                <i class="fas fa-shopping-bag"></i> Restaurant :
                <address class="m-t-5 m-b-5">
                    <strong class="text-inverse text-capitalize"><?= $restaurant->getRestaurantName() ?></strong><br />
                    <span style="word-break: break-word;"><?= ucfirst($restaurantAddr->getAddress()) ?></span><br />
                    <span><?= $city->getCityName() ?></span><br />
                    <span>(+216) <?= $restaurant->getPhone()  ?></span><br />
                </address>
            </div>
            <?php
                }
            ?>

            <div class="invoice-date">
                <small>Date de commande</small>
                <div class="date text-inverse m-t-5"><?= Handler::format_date_fr($order->getDateTime()) ?></div>
                <div class="invoice-detail">
                    #<?= $order->getOrderUid() ?><br />
                </div>
            </div>
        </div>
        <!-- end invoice-header -->
        <!-- begin invoice-content -->
        <div class="invoice-content">
            <!-- begin table-responsive -->
            <div class="table-responsive">
                <table class="table table-invoice">
                    <thead>
                        <tr>
                            <th class="text-center" width="1%" style="padding: 10px 5px;"><i class="fas fa-times-circle"></i></th>
                            <th>DESCRIPTION ARTICLE</th>
                            <th class="text-center text-uppercase" width="10%">quantité</th>
                            <th class="text-center" width="10%">PRIX UNIT</th>
                            <th class="text-center" width="10%">SUPPLEMENT</th>
                            <th class="text-center" width="1%">PROMO</th>
                            <th class="text-center" width="10%">REMARQUE!</th>
                            <th class="text-right" width="15%">PRIX TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($cart->getTotalDeliveryFeeCart())) {
                            $delivery_fee = $cart->getTotalDeliveryFeeCart();
                        } else {
                            $delivery_fee = $cart->getDeliveryFeeCart();
                        }
                        if (!empty($cart->getTotalDiscountCart()) && Handler::getNumber($cart->getTotalDiscountCart()) != 0) {
                            $total = $cart->getTotalDiscountCart();
                        } else {
                            $total = $cart->getTotalCart();
                        }
                        $cart_discount = NULL;
                        if (!empty($cart->getDiscountCart())) {
                            if ($cart->getDiscountCart()['type'] == "%") {
                                $cart_discount = '- ' . $cart->getDiscountCart()['value'] . ' %';
                            }
                            if ($cart->getDiscountCart()['type'] == '$') {
                                $cart_discount = '- ' . $cart->getDiscountCart()['value'] . ' DT';
                            }
                        }
                        foreach ($cart->getItems() as $item_id => $item) {
                            foreach ($item as $key => $item_data) {
                                $dish = (new Dish)->setId($item_id)->setStatus('1')->getElementByIdWS();
                                if ($dish) {

                                    $dishSizeName = NULL;
                                    if ($cart->getItemSizeName($item_id, $key)) {
                                        $dishSizeName = ' <small>( ' . $cart->getItemSizeName($item_id, $key) . ' )</small>';
                                    }

                                    $item_qty = $cart->getItemQty($item_id, $key);

                                    $item_price = Handler::toFixed($item_data['unit_price']);
                                    $total_item_price = Handler::toFixed($item_data['total']);
                                    $promo = '<span class="text-danger">Non</span>';
                                    if ($cart->hasDiscount($item_id, $key)) {
                                        $promo = '<span class="text-green">Oui</span>';
                                        $total_item_price = Handler::toFixed($item_data['total_discount']);
                                    }
                                    $supplementsNbr = $cart->hasExtras($item_id, $key);
                                    $extras = NULL;
                                    if ($supplementsNbr) {
                                        $extras = '<table class="table-borderless">';
                                        $extrasIdsString = implode(", ", array_keys($item_data['extras']));
                                        $supplements = (new RestaurantExtras)
                                            ->setId('(' . Handler::getNumberMap($extrasIdsString) . ')')
                                            ->getElementsById();
                                        if (!$supplements) {
                                            echo json_encode(array('status' => 'failed', 'info' => '[0522] failed to fetch Extas'));
                                            exit;
                                        }
                                        foreach ($supplements as $extra) {
                                            $extras .= "<tr><td width='50%'><i class='fas fa-check'></i>&nbsp;&nbsp; " . ucfirst($extra->getExtraName()) . "</td><td width='50%'> ( " . $extra->getPrice() . " <small>DT</small> )</td> </tr>";
                                        }
                                        $extras .= "</table>";
                                    }
                                }
                                ?>
                                <tr>
                                    <td class="text-center" style="padding: 5px 5px 11px;">
                                        <div class="checkbox checkbox-css is-invalid">
                                            <input type="checkbox" id="cssCheckbox1"/>
                                            <label for="cssCheckbox1"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-inverse text-capitalize"><i class="ion-md-restaurant"></i>&nbsp; <?= ucfirst($dish->getDishName()) . $dishSizeName ?></span><br />
                                        <div class="ml-3"><?= $extras ?></div>
                                    </td>
                                    <td class="text-center"><?= $item_qty ?></td>
                                    <td class="text-center"><?= $item_price ?> <small>DT</small></td>
                                    <td class="text-center"><?= $supplementsNbr ?></td>
                                    <td class="text-center"><?= $promo ?></td>
                                    <?php
                                            if ($cart->getItemComment($item_id, $key)) {
                                                echo '<td class="text-center"><button class="btn btn-white btn-sm border comment-popover" data-container="body" data-toggle="popover" data-trigger="hover click" data-placement="left" data-content="' . $cart->getItemComment($item_id, $key) . '"><i class="text-info fas fa-exclamation-circle"></i></button></td>';
                                            } else {
                                                echo '<td class="text-center text-danger">Non</td>';
                                            }
                                            ?>
                                    <td class="text-right"><?= $total_item_price ?> <small>DT</small></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- end table-responsive -->
            <!-- begin invoice-price -->
            <div class="invoice-price">
                <div class="invoice-price-left">
                    <div class="invoice-price-row">
                        <div class="sub-price">
                            <small>TOTAL</small>
                            <span class="text-inverse"><?= $cart->getSubtotalCart() ?> <span class="small">DT</span></span>
                        </div>
                        <div class="sub-price">
                            <i class="fa fa-plus text-muted"></i>
                        </div>
                        <div class="sub-price">
                            <small>FRAIS DE LIVRAISON</small>
                            <span class="text-inverse"><?= $delivery_fee ?> <span class="small">DT</span></span>
                        </div>
                        <?php
                        if (!empty($cart->getDiscountCart())) {
                            ?>
                            <span class="ml-5">
                                <div class="sub-price">
                                    <small>PROMOTION SUR COMMANDE</small>
                                    <span class="text-inverse"><?= $cart_discount ?> </span>
                                </div>
                            </span>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="invoice-price-right">
                    <small>TOTAL</small> <span class="f-w-600"><?= $total ?> <span class="small">DT</span></span>
                </div>
            </div>
            <!-- end invoice-price -->
        </div>
        <!-- end invoice-content -->
        <?php
        $choices = array(
             1 => 'Laisser le restaurant choisir/remplacer un article ou un autre detail.',
             2 => 'Annuler cet article.',
             3 => 'Annuler la commande.'
        )
        ?>
        <div class="alert alert-secondary fade show m-b-10">
            <strong><i class="fas fa-thumbtack"></i> Au cas d'indisponibilité d'un article, le client  souhaite:</strong> <?=$choices[$order->getCustomerErrorCase()]?>
        </div>
        <?php
        if (!empty($order->getRestaurantComment())) {
            ?>
            <div class="note note-secondary mt-4">
                <h5 class="text-warning"><i class="fas fa-exclamation-circle"></i> Avis du Restaurant!</h5>
                <p>
                    <?= $order->getRestaurantComment() ?>
                </p>
            </div>
        <?php
        }
        ?>
        <!-- begin invoice-note -->
        <small>
            * Faire attention avant d'accepter ou de refuser la demande.
        </small>
        <!-- end invoice-note -->

    </div>
    <!-- end invoice -->
</div>
<div class="modal-footer" data-order-id="<?= Handler::encryptInt($order->getId()) ?>">
    <?php
    if(Session::get('user_auth') == 2){
        if ($order->getOrderStatus() == 'P') {
    ?>
        <button id="reject-order" data-order-id="<?= Handler::encryptInt($order->getId()) ?>" class="btn btn-danger text-uppercase"><i class="fas fa-times-circle"></i>&nbsp;&nbsp; Rejeté</button>
        <button id="accept-order" data-order-id="<?= Handler::encryptInt($order->getId()) ?>" class="btn btn-green text-uppercase"><i class="fas fa-check-circle"></i>&nbsp;&nbsp; Accepter</button>
    <?php
        }
    ?>
    <?php
        if ($order->getOrderStatus() == 'R') {
    ?>
        <button id="order-rejected" class="btn btn-danger text-uppercase" disabled><i class="fas fa-times-circle"></i>&nbsp;&nbsp; commande rejeté</button>
    <?php
        }
    ?>
    <?php
        if ($order->getOrderStatus() == 'A') {
    ?>
        <button class="btn btn-info text-uppercase" disabled><i class="fas fa-hourglass-half"></i>&nbsp;&nbsp; commande accepté & en préparation</button>
        <button id="delivery-order" class="btn btn-green text-uppercase"><i class="fas fa-truck"></i>&nbsp;&nbsp; soumettre pour livraison <strong>?</strong></button>
    <?php
        }
    ?>
    <?php
        if ($order->getOrderStatus() == 'D') {
    ?>
        <button class="btn btn-primary text-uppercase" disabled><i class="fas fa-truck"></i>&nbsp;&nbsp; la commande est en cours de livraison</button>
    <?php
            if($order->getDeliveryId() != '0'){
    ?>
        <button class="btn btn-green text-uppercase" id="order-delivred"><i class="fas fa-check-circle"></i>&nbsp;&nbsp; la commande est livré <strong>?</strong></button>

    <?php
            }
        }
    ?>
    <?php
        if ($order->getOrderStatus() == 'L') {
    ?>
        <button class="btn btn-success text-uppercase" disabled><i class="fas fa-check-square"></i>&nbsp;&nbsp; commande livré</button>
    <?php
        }
    }else{
    ?>
    <button class="btn btn-default" data-dismiss="modal"> Fermer </button>
    <?php
    }
    ?>
</div>