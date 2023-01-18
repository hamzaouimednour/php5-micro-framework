<section class="account-page section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="row no-gutters">
                    <?php $this->requireTPL('p-profile-menu', PATH_PUBLIC); ?>

                    <!-- #modal-dialog -->
                    <div class="modal fade" id="view-order-modal" tabindex="-1" role="dialog" aria-labelledby="item-modal-label" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="item-modal-label"><i class="mdi mdi-cart"></i> <span id="modal-title"></span></h5>
                                    <button type="button" class="close close-top-right" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true"><i class="mdi mdi-close"></i></span>
                                        <span class="sr-only">Fermer</span>
                                    </button>
                                </div>

                                <form autocomplete="off" id="modal-form" class="needs-validation" novalidate>

                                </form>
                            </div>
                        </div>
                    </div>
                    <?php $this->requireTPL('p-user-order-modal', PATH_PUBLIC); ?>
                    <div class="col-md-8">
                        <div class="card card-body account-right">
                            <div class="widget">
                                <!-- New Address -->
                                <!-- /New Address -->
                                <div class="section-header">
                                    <h5 class="heading-design-h5">
                                        <i class="mdi mdi-basket"></i> Liste des commandes
                                    </h5>
                                </div>
                                <div class="order-list-tabel-main table-responsive">
                                    <?php
                                    $orders = (new Order)
                                        ->setCustomerId(Session::get('customer_id'))
                                        ->setStatus('1')
                                        ->getAllByCustomerId();
                                    $status = array(
                                        'P' => '<strong style="font-size:11px;" class="badge badge-warning text-uppercase"><i class="fas fa-sync"></i> &nbsp;En attente</strong>',
                                        'A' => '<strong style="font-size:11px;" class="badge badge-info text-uppercase"><i class="fas fa-check-circle"></i> &nbsp;Accepté</strong>',
                                        'R' => '<strong style="font-size:11px;" class="badge badge-danger text-uppercase"><i class="fas fa-times-circle"></i> &nbsp;Rejeté</strong>',
                                        'D' => '<strong style="font-size:11px;" class="badge badge-primary text-uppercase"><i class="fas fa-truck"></i> &nbsp;En Livraison</strong>',
                                        'L' => '<strong style="font-size:11px;" class="badge badge-success text-uppercase"><i class="fas fa-check-square"></i> &nbsp;commande Livré</strong>'
                                    );
                                    ?>
                                    <table id="orders-table" class="datatabel table table-striped table-bordered order-list-tabel" style="font-size: 100%;" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th width="1%" data-orderable="false">REF #</th>
                                                <th>Date Commande</th>
                                                <th>Statut</th>
                                                <th>Total</th>
                                                <th data-orderable="false">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($orders) {
                                                foreach ($orders as $order) {
                                                    $order_cart = (new Cart)->read($order->getJsonOrder());
                                                    $dateOrder = new DateTime($order->getDateTime());
                                                    ?>
                                                    <tr id="<?= Handler::encryptInt($order->getId()) ?>">
                                                        <td><strong><?= $order->getOrderUid() ?></strong></td>
                                                        <td><?= $dateOrder->format('d/m/Y H:i:s'); ?></td>
                                                        <td>
                                                        <?php
                                                        if($order->getStatus()){
                                                            echo $status[$order->getOrderStatus()];
                                                        }else{
                                                            echo '<strong style="font-size:11px;" class="badge badge-danger text-uppercase"><i class="mdi mdi-close-circle"></i> ANNULÉ</strong>';
                                                        }
                                                        ?>    
                                                        </td>
                                                        <td><?= $order_cart->getCustomerTotalPay() ?> <small><b>DT</b></small></td>
                                                        <td class="text-center">
                                                            <button data-id="<?= $order->getOrderUid() ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Voir Détails" class="btn btn-info btn-sm btn-view-order"><i class="mdi mdi-eye"></i></button>
                                                            <?php
                                                                    if ($order->getOrderStatus() == 'P' && $order->getStatus() !=0 ) {
                                                                        ?>
                                                                <button type="button" class="btn btn-secondary btn-sm btn-cancel-order" data-order-id="<?=Handler::encryptInt($order->getId())?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Annuler la commande" ><i class="mdi mdi-close-circle"></i></button>
                                                            <?php
                                                                    }
                                                                    ?>
                                                        </td>
                                                    </tr>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php
                                // $customerAddr = (new CustomerAddress)
                                //             ->setCustomerId(Session::get('customer_id'))
                                //             ->setStatus('1')
                                //             ->getElementsByUserIdWS();
                                // if(!$customerAddr){
                                //     $this->requireTPL('p-user-edit-address', PATH_PUBLIC); 
                                // }                                    

                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>