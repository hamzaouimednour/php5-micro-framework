<?php
$orders = (new Order)
    ->setRestaurantId(Session::get('user_id'))
    ->setStatus('1')
    ->getAllByRestaurantIdWS();
$status = array(
    'P' => '<h5><strong class="label label-warning text-uppercase"><i class="fas fa-sync"></i> &nbsp;En attente</strong></h5>',
    'A' => '<h5><strong class="label label-green text-uppercase"><i class="fas fa-check-circle"></i> &nbsp;Accepté</strong></h5>',
    'R' => '<h5><strong class="label label-danger text-uppercase"><i class="fas fa-times-circle"></i> &nbsp;Rejeté</strong></h5>',
    'D' => '<h5><strong class="label label-primary text-uppercase"><i class="fas fa-truck"></i> &nbsp;En Livraison</strong></h5>',
    'L' => '<h5><strong class="label label-success text-uppercase"><i class="fas fa-check-square"></i> &nbsp;commande Livré</strong></h5>'
);
?>
<!-- #modal-dialog -->
<div class="modal fade" id="modal-dialog">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="far fa-list-alt"></i> &nbsp;#<span id="modal-title-order"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="ion ion-ios-close-circle-outline"></i></button>
            </div>

            <form autocomplete="off" id="modal-form" class="needs-validation" novalidate>

            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-comment">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i style="font-size: 13px;" class="ion-md-chatbubbles"></i> Remarque</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="ion ion-ios-close-circle-outline"></i></button>
            </div>
            <form autocomplete="off" id="modal-form-comment" class="needs-validation" novalidate>
                <div class="modal-body">
                    <div id="comment-info-section"></div>
                    <input id="order-ref" name="order-ref" value="" type="hidden">
                    <div class="row">
                        <div class="col">
                            <label> Ajouter / Modifier votre remarque ici :</label>
                            <textarea id="restaurant-comment" name="restaurant-comment" class="form-control" placeholder="ajouter votre remarque ..." style="margin-top: 0px; margin-bottom: 0px; height: 150px;" required></textarea>
                            <div class="invalid-feedback">Ce champ est obligatoire</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="comment-save"><i class="fas fa-save"></i> Enregistrer</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                </div>
            </form>
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
        $lastOrderId = (new Order)->setRestaurantId(Session::get('user_id'))->setStatus('1')->getLastOrderByRestaurantId();
        ?>
        <span id="last-order-id" data-id="<?= Handler::encryptInt($lastOrderId) ?>"></span>
        <table id="data-table-orders" class="table table-striped table-bordered" style="width:100%;">
            <thead>
                <tr class="text-center">
                    <th class="text-nowrap" width="1%">#REF</th>
                    <th class="text-nowrap">Client</th>
                    <th class="text-nowrap">Date de commande</th>
                    <th class="text-nowrap" width="1%">Commande N°</th>
                    <th class="text-nowrap">Quantité Total</th>
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
                        ?>
                        <tr id="<?= Handler::encryptInt($order->getId()) ?>" class="">
                            <td class="text-inverse"><strong><?= $order->getOrderUid() ?></strong></td>
                            <td class="text-inverse text-capitalize"><?= $client->getFullName() ?></td>
                            <td><?= $dateOrder->format('d/m/Y H:i:s'); ?></td>
                            <td class="font-weight-bold"><?= $order->getOrderNum() ?> </td>
                            <td class="font-weight-bold" width="1%"><?= $cart->getTotalItemsQty() ?> </td>
                            <td class="font-weight-bold"><?= $cart->getCustomerTotalPay() ?> <small><b>DT</b></small></td>
                            <td class="text-center" id="<?= Handler::encryptInt($order->getId()) ?>-status"><?= $status[$order->getOrderStatus()] ?></td>
                            <td class="text-nowrap" width="1%">
                                <button data-id="<?= $order->getOrderUid() ?>" class="btn btn-white btn-xs m-r-3 btn-view"><i class="fa fa-eye"></i></button>
                                <button data-id="<?= $order->getOrderUid() ?>" class="btn btn-inverse btn-xs btn-comment" data-toggle="tooltip" data-placement="bottom" title="Remarque?"><i style="font-size: 13px;" class="ion-md-chatbubbles"></i></button>
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