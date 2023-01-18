<?php
$orders = (new Order)
    ->setDeliveryId(Session::get('user_id'))
    ->setStatus('1')
    ->getAllByDeliveryIdWS();
$status = array(
    'P' => '<h5><strong class="label label-warning text-uppercase"><i class="fas fa-sync"></i> &nbsp;En attente</strong></h5>',
    'A' => '<h5><strong class="label label-green text-uppercase"><i class="fas fa-check-circle"></i> &nbsp;Accepté</strong></h5>',
    'R' => '<h5><strong class="label label-danger text-uppercase"><i class="fas fa-times-circle"></i> &nbsp;Rejeté</strong></h5>',
    'D' => '<h5><strong class="label label-primary text-uppercase"><i class="fas fa-truck"></i> &nbsp;Restaurant vous attend</strong></h5>',
    'L' => '<h5><strong class="label label-success text-uppercase"><i class="fas fa-check-square"></i> &nbsp;commande Livré</strong></h5>'
);
?>
<!-- #modal-dialog -->
<div class="modal fade" id="modal-dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="far fa-list-alt"></i> <span id="modal-title-order"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="ion ion-ios-close-circle-outline"></i></button>
            </div>

            <div class="modal-body">
                <div class="col">
                    <p id="destination-address"></p>
                </div>
                <div class="row mt-2">
                <div class="col">
                    <div class="img-thumbnail" id="map" style="width:100%;height:400px;"></div>
                </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-3">
                        <input id="checkout-distance" type="text" style="pointer-events: none;" class="form-control">
                    </div>
                    <div class="col-md-3">
                     <input id="checkout-time" type="text" style="pointer-events: none;" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="" target="_blank" class="btn btn-primary" id="open-gps">Ouvrir dans GOOGLE MAP (GPS GUIDE)</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<div class="panel panel-inverse">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title">List des Commandes</h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <?php
        $deliveryAddress = (new Address)->setUserAuth('3')->setUserId(Session::get('user_id'))->getElementByUserId();
        $lastOrderId = (new Order)->setDeliveryId(Session::get('user_id'))->setStatus('1')->getLastOrderByDeliveryId();
        ?>
        <span id="delivery-geo" data-lat="<?=$deliveryAddress->getLatitude()?>" data-lng="<?=$deliveryAddress->getlongtitude()?>"></span>
        <span id="last-order-id" data-id="<?= Handler::encryptInt($lastOrderId) ?>"></span>
        <table id="data-table-orders" class="table table-striped table-bordered" style="width:100%;">
            <thead>
                <tr class="text-center">
                    <th class="text-nowrap" width="1%">#REF</th>
                    <th class="text-nowrap">Client</th>
                    <th class="text-nowrap">Restaurant</th>
                    <th class="text-nowrap">Date de commande</th>
                    <th class="text-nowrap">Frais Livraison</th>
                    <th class="text-nowrap">Prix Total</th>
                    <th class="text-nowrap">Etat</th>
                    <th width="1%" class="text-nowrap" data-orderable="false">Options</th>
                </tr>
            </thead>
            <tbody id="data-tbody-orders" class="text-center">
                <?php
                if ($orders) {
                    foreach ($orders as $order) {
                        $cart = (new Cart)->read($order->getJsonOrder());
                        $dateOrder = new DateTime($order->getDateTime());
                        $client = (new Customer)->setUid($order->getCustomerId())->getUserByUid();
                        $clientAddress = (new Address)->setId($cart->getCustomerAddressId())->setUserAuth('4')->setUserId($order->getCustomerId())->getElementByAddressId();
                        $customerAddress = (new CustomerAddress)->setAddressId($cart->getCustomerAddressId())->setCustomerId($order->getCustomerId())->getElementByUserAddressId();
                        $cAddr = $customerAddress->getStreet();
                        if($customerAddress->getBuilding() == 'A'){
                            $cAddr .= 'Bâtiment Résidentiel : ' .  $customerAddress->getAppartmentBloc(). ' , étage N° '.  $customerAddress->getFloor();
                        }

                        $restaurant = (new Restaurant)->setUid($order->getRestaurantId())->getUserByUid();
                        $restaurantAddr = (new Address)->setUserAuth('2')->setUserId($order->getRestaurantId())->getElementByUserId();
                        ?>
                        <tr id="<?= Handler::encryptInt($order->getId()) ?>" class="">
                        <span id="customer-addr-<?= Handler::encryptInt($order->getId()) ?>" style="display:none;"><?=$clientAddress->getAddress() . ' ' . $cAddr?></span>
                        <span id="resto-addr-<?= Handler::encryptInt($order->getId()) ?>" style="display:none;"><?=$restaurantAddr->getAddress()?></span>
                            <td class="text-inverse"><strong><?= $order->getOrderUid() ?></strong></td>
                            <td class="text-inverse text-capitalize"><?= $client->getFullName() ?></td>
                            <td class="text-inverse text-capitalize"><?= $restaurant->getFullName() ?></td>
                            <td><?= $dateOrder->format('d/m/Y H:i:s'); ?></td>
                            <td class="font-weight-bold"><?= $cart->getTotalDeliveryFeeCart() ?> <small><b>DT</b></small></td>
                            <td class="font-weight-bold"><?= $cart->getCustomerTotalPay() ?> <small><b>DT</b></small></td>
                            <td class="text-center" id="<?= Handler::encryptInt($order->getId()) ?>-status"><?= $status[$order->getOrderStatus()] ?></td>
                            <td class="text-nowrap" width="1%">
                            <button data-id="<?= $order->getOrderUid() ?>" class="btn btn-green btn-sm m-r-3 btn-delivred" data-toggle="tooltip" data-placement="bottom" title="Commande Livré?"><i style="font-size: 13px;" class="fa fa-check-circle"></i></button>

                                <?php
                                        if (!in_array($order->getOrderStatus(), ['P', 'A', 'L'])) {
                                            ?>
                                    <button data-id="<?= $order->getOrderUid() ?>" class="btn btn-green btn-sm m-r-3 btn-delivred" data-toggle="tooltip" data-placement="bottom" title="Commande Livré?"><i style="font-size: 13px;" class="fa fa-check-circle"></i></button>
                                <?php
                                        }
                                        ?>
                                <button data-lat="<?=$restaurantAddr->getLatitude()?>" data-lng="<?=$restaurantAddr->getLongtitude()?>" data-id="<?= $order->getOrderUid() ?>" class="btn btn-indigo btn-sm btn-resto" data-toggle="tooltip" data-placement="bottom" title="Chemain vers restaurant?"><i style="font-size: 13px;" class="fas fa-map-marker-alt"></i></button>
                                <button data-lat="<?=$clientAddress->getLatitude()?>" data-lng="<?=$clientAddress->getLongtitude()?>" data-id="<?= $order->getOrderUid() ?>" class="btn btn-purple btn-sm btn-customer" data-toggle="tooltip" data-placement="bottom" title="Chemain vers client?"><i style="font-size: 13px;" class="fas fa-map-marker-alt"></i></button>
                            </td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- end panel-body -->
</div>