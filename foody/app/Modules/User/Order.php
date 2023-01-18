<?php

defined('PATH_ROOT') or die('Attempt Unauthorized, Access Denied.');

//--------------------------------------------------------------------------
// Start Session.
//--------------------------------------------------------------------------
require_once PATH_MODULES     . "Session.module.php";

if(empty(Session::get('customer_id'))){

    Request::redirect( HTML_PATH_ROOT . '404' );

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

if( !empty($this->getParamsIndexOf()) ){

    $order = (new Order)
            ->setOrderUid($this->getParamsIndexOf())
            ->setCustomerId(Session::get('customer_id'))
            ->getElementByCustomerId();
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
        $cAddr .= 'Bâtiment Résidentiel : ' .  $customerAddress->getApartmentBloc(). ' , étage N° '.  $customerAddress->getFloor();
    }
    $city = (new City)->setId($clientAddress->getCityId())->getElementById();
    $status = array(
        'P' => '<h6><strong class="badge badge-warning text-uppercase"><i class="mdi mdi-sync"></i> &nbsp;En attente</strong></h6>',
        'A' => '<h6><strong class="badge badge-info text-uppercase"><i class="mdi mdi-check-circle"></i> &nbsp;Accepté</strong></h6>',
        'R' => '<h6><strong class="badge badge-danger text-uppercase"><i class="mdi mdi-close-circle"></i> &nbsp;Rejeté</strong></h6>',
        'D' => '<h6><strong class="badge badge-primary text-uppercase"><i class="mdi mdi-truck"></i> &nbsp;En Livraison</strong></h6>',
        'L' => '<h6><strong class="badge badge-success text-uppercase"><i class="mdi mdi-clipboard-check"></i> &nbsp;commande Livré</strong></h6>'
    );
}else{
    die('Unauthorized permission!');
}
?>

                                <div class="modal-body" style="margin: 20px;">
                                    <div class="row" style="background: #f0f3f4;padding: 20px;">
                                        <div class="col-md-6">
                                            <p class="mb-1 text-black"><b>#</b> Numéro de commande: <strong>#<?=$order->getOrderUid()?></strong></p>
                                            <p class="mb-1"><i class="mdi mdi-calendar-clock"></i> Date: <strong><?=Handler::format_date_fr($order->getDateTime())?></strong></p>
                                            <p class="mb-1">Statut de la commande: <strong class="">
                                                <?php
                                                if($order->getStatus()){
                                                    echo $status[$order->getOrderStatus()];
                                                }else{
                                                    echo '<h6><strong class="badge badge-danger text-uppercase"><i class="mdi mdi-close-circle"></i> &nbsp;COMMANDE ANNULÉ PAR VOUS</strong></h6>';
                                                }
                                                ?>
                                            </strong></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1 text-primary"><strong>Livraison à:</strong></p>
                                            <p class="mb-1"><?=ucfirst($clientAddress->getAddress())?></strong></p>
                                            <p class="mb-1"><?=ucfirst($cAddr)?></p>
                                        </div>
                                    </div>
                                    <div class="row mt-5">
                                        <div class="col-md-12">
                                            <div class="col" style="background: #f0f3f4;padding: 20px;">
                                                <p class="mb-1">Commandé à partir de:</p>
                                                <h6 class="mb-1 text-black text-capitalize"><strong><?=$restaurant->getRestaurantName()?></strong></h6>
                                                <p class="mb-1"><?=ucfirst($restaurantAddr->getAddress())?></p>
                                            </div>
                                            <table class="table mt-3 mb-0" >
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-black font-weight-bold small" scope="col"> ARTICLE</th>
                                                        <th class="text-black font-weight-bold small" scope="col"> SUPPLEMENT</th>
                                                        <th class="text-right text-black font-weight-bold small" scope="col">QUANTITÉ</th>
                                                        <th class="text-right text-black font-weight-bold small" scope="col">PROMO</th>
                                                        <th class="text-right text-black font-weight-bold small" scope="col">REMARQUE!</th>
                                                        <th class="text-right text-black font-weight-bold small" scope="col">PRIX TOTAL</th>
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
                                                                    $extras = '<ul>';
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

                                                                    $extrasBtn = '<button class="btn btn-sm btn-primary" data-container="body" data-html="true" data-toggle="popover" data-placement="top" data-trigger="hover click" title="<small>Les Suppléments</small>" data-content="'.$extras.'" >
                                                                    <i class="mdi mdi-lock-plus"></i><small id="extras-count">x' . $supplementsNbr . '</small>
                                                                    </button>';
                                                                }else{
                                                                    $extrasBtn = 0;
                                                                }
                                                            }
                                                            
                                                    ?>
                                                    <tr class="small">
                                                        <td><?= ucfirst($dish->getDishName()) . $dishSizeName ?></td>
                                                        <td class="center"><?=$extrasBtn?></td>
                                                        <td class="text-center"><?= $item_qty ?></td>
                                                        <td class="text-center"><?= $promo ?></td>
                                                        <?php
                                                        if ($cart->getItemComment($item_id, $key)) {
                                                            echo '<td class="text-center"><button class="btn btn-white btn-sm border comment-popover" data-container="body" data-toggle="popover" data-trigger="hover click" data-placement="left" data-content="' . $cart->getItemComment($item_id, $key) . '"><i class="text-info mdi mdi-help-circle"></i></button></td>';
                                                        } else {
                                                            echo '<td class="text-center text-danger">Non</td>';
                                                        }
                                                        ?>
                                                        <td class="text-right"><?= $total_item_price ?></td>
                                                    </tr>

                                                    <?php
                                                            }
                                                        }
                                                    ?>
                                                    <tr class="small">
                                                        <td></td><td></td><td></td>
                                                        <td class="text-right font-weight-bold" colspan="2">TOTAL :</td>
                                                        <td class="text-right"> <?= $cart->getSubtotalCart() ?> <span class="small">DT</span></td>
                                                    </tr>
                                                    <tr class="small">
                                                        <td></td><td></td><td></td>
                                                        <td class="text-right font-weight-bold" colspan="2">FRAIS DE LIVRAISON :</td>
                                                        <td class="text-right"> <?= $delivery_fee ?> <span class="small">DT</span></td>
                                                    </tr>
                                                    <?php
                                                    if (!empty($cart->getDiscountCart())) {
                                                        ?>
                                                        <tr class="small">
                                                            <td></td><td></td><td></td>
                                                            <td class="text-right font-weight-bold" colspan="2">PROMOTION SUR COMMANDE :</td>
                                                            <td class="text-right"> <?= $cart_discount ?>  <span class="small">DT</span></td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                    <tr class="small">
                                                        <td></td><td></td><td></td>
                                                        <td class="text-right" colspan="2">
                                                            <h6 class="text-primary text-uppercase font-weight-bold">total à payer: </h6>
                                                        </td>
                                                        <td class="text-right">
                                                            <h6 class="text-primary"> <b><?= $total ?></b> <span class="small"><b>DT</b></span></h6>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <?php
                                            $choices = array(
                                                1 => 'Laisser le restaurant choisir/remplacer un article ou un autre detail.',
                                                2 => 'Annuler cet article.',
                                                3 => 'Annuler la commande.'
                                            )
                                            ?>
                                            <hr>
                                            <div class="fade show m-b-10" style="background: #f0f3f4;padding: 20px;">
                                                <strong><i class="mdi mdi-pin"></i> Au cas d'indisponibilité d'un article, vous souhaitez :</strong> <?=$choices[$order->getCustomerErrorCase()]?>
                                            </div>
                                            <?php
                                            if (!empty($order->getRestaurantComment())) {
                                                ?>
                                                <div class="mt-4" style="background: #f0f3f4;padding: 20px;">
                                                    <h5 class="text-warning"><i class="mdi mdi-help-circle"></i> Avis du Restaurant!</h5>
                                                    <p>
                                                        <?= $order->getRestaurantComment() ?>
                                                    </p>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <span id="order-id" data-order-id=""></span>
                                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="mdi mdi-keyboard-return"></i> Retour</button>
                                    <?php
                                        if($order->getOrderStatus() == 'P' && $order->getStatus() !=0){
                                    ?>
                                    <button type="button" data-order-id="<?=Handler::encryptInt($order->getId())?>" class="btn btn-secondary btn-cancel-order"><i class="mdi mdi-close-circle"></i> Annuler la commande</button>
                                    <?php
                                        }
                                    ?>
                                </div>