<div class="modal fade" id="modal-dialog">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i style="font-size: 13px;" class="ion-md-chatbubbles"></i> Avis du Client</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="ion ion-ios-close-circle-outline"></i></button>
            </div>
            <div class="modal-body">
                <div class="col">
                    <div class="form-group">
                        <label for="" class="font-weight-bold">Nom du client :</label>
                        <input class="form-control text-capitalize" id="customer-name" style="pointer-events: none;"/>

                    </div>
                    <div class="form-group">
                        <label for="" class="font-weight-bold">Note du client :</label>
                        <p id="customer-note" class="text-warning"></p>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="customer-comment" class="font-weight-bold">Avis du client :</label>
                        <textarea class="form-control" id="customer-comment" style="pointer-events: none;margin-top: 0px; margin-bottom: 0px; height: 170px;" required></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Fermer</button>
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
        <h4 class="panel-title">List des Avis</h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <table id="data-table-default" class="table table-striped table-bordered" style="width:100%;">
            <thead>
                <tr class="text-center">
                    <th class="text-nowrap" width="20%">Client</th>
                    <?php if (Session::get('user_auth') == 1) {
                        echo '<th class="text-nowrap" width="1%">Restaurant</th>';
                    } ?>
                    <th class="text-nowrap" width="20%">Date</th>

                    <th class="text-nowrap" width="1%">Total des Commandes</th>
                    <th class="text-nowrap">Note /5</th>
                    <th class="text-nowrap">Avis</th>
                    <th width="1%" class="text-nowrap" data-orderable="false">Options</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php
                if (Session::get('user_auth') == 1) {
                    $rates = (new RestaurantFeedback)->getAll();
                } else {
                    $rates = (new RestaurantFeedback)->setRestaurantId(Session::get('user_id'))->getAllByRestaurantId();
                }
                if ($rates) {
                    foreach ($rates as $feedback) {
                        if (Session::get('user_auth') == 1) {
                            $resto = (new Restaurant)->setUid($feedback->getRestaurantId())->getUserByUid();
                        }
                        $dateRate = new DateTime($feedback->getDateTime());
                        $client = (new Customer)->setUid($feedback->getCustomerId())->getUserByUid();
                        $orderNbr = (new Order)->setStatus('1')->setCustomerId($feedback->getCustomerId())->setRestaurantId($feedback->getRestaurantId())->getCustomerNbrOrdersWS();
                        ?>
                        <tr id="<?= Handler::encryptInt($feedback->getId()) ?>" class="">
                            <td class="text-inverse text-capitalize"><?= $client->getFullName() ?></td>
                            <?php
                                if (Session::get('user_auth') == 1) {
                                        echo '<td class="text-inverse text-capitalize">' . $resto->getFullName() . '</td>';
                                }
                            ?>
                            <td><?= $dateRate->format('d/m/Y H:i:s'); ?></td>

                            <td class="font-weight-bold text-dark" width="1%"><?= $orderNbr ?> <i class="fas fa-shopping-cart"></i></td>
                            <td class="font-weight-bold text-warning" width="1%"><?= $feedback->getRating() ?> <i class="fas fa-star"></i> </td>
                            <td class="text-truncate"><?= $feedback->getComment() ?> </td>
                            <td class="text-nowrap" width="1%">
                                <button data-id="<?= Handler::encryptInt($feedback->getId()) ?>" class="btn btn-white btn-xs m-r-3 btn-view"><i class="fa fa-eye"></i></button>
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