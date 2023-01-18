    <div class="container">
        <div class="row">
            <?php
            global $dateTime;
            $today = $dateTime->format('N');    // MySQL datetime format
            global $restaurant;
            global $delivery_fee;
            $delivery_fee = 0;
            $address = (new Address)->setUserAuth('2')->setUserId($restaurant->getUid())->getElementByUserId();
            $restoWork = (new RestaurantWork)->setRestaurantId($restaurant->getUid())->getElementByRestaurantId();
            $workTimesArray = $restoWork->getWorkTimes(true);
            global $closed;
            $closed = NULL;
            $workingToday = true;
            if (!array_key_exists($today, $workTimesArray)) {
                $closed = 'disabled';
                $workingToday = false;
            } else {
                $now = new DateTime('2019-01-31 ' . $dateTime->format('H:i'));
                $open = new DateTime('2019-01-31 ' . $workTimesArray[$today][0]);
                $close = new DateTime('2019-01-31 ' . $workTimesArray[$today][1]);
                if($workTimesArray[$today][1] == '00:00'){
                    $close = new DateTime('2019-02-01 ' . $workTimesArray[$today][1]);
                }
                if ($now < $open || $now > $close) {
                    $closed = 'disabled';
                }
            }
            if ($restoWork->getDeliveryType() == 1) {
                $deliveryMinTime = $restoWork->getDeliveryTimeMin();
                $deliveryMaxTime = $restoWork->getDeliveryTimeMax();
                $delivery_fee = $restoWork->getDeliveryFee();
            } else {
                $op1 = (new Options)->setOptionName('delivery_time_min')->getElementByOptionName();
                $op2 = (new Options)->setOptionName('delivery_time_max')->getElementByOptionName();
                $op3 = (new Options)->setOptionName('delivery_fee')->getElementByOptionName();
                $deliveryMinTime = (int) $op1->getOptionValue();
                $deliveryMaxTime = (int) $op2->getOptionValue();
                $delivery_fee = $op3->getOptionValue();
            }
            ?>
            <div class="modal fade" id="rates-modal" tabindex="-1" role="dialog" aria-labelledby="item-modal-label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered " role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="mdi mdi-message-text"></i> Attribuer une Note au Restaurant</h5>
                            <button type="button" class="close close-top-right" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"><i class="mdi mdi-close"></i></span>
                                <span class="sr-only">Fermer</span>
                            </button>
                        </div>
                        <div id="rate-info-section"></div>
                        <?php
                        if(!empty(Session::get('customer_id'))){
                            $feedback = (new RestaurantFeedback)->setCustomerId(Session::get('customer_id'))->getAllByCustomerId();
                            if($feedback){
                                $rates = $feedback->getRating();
                                $comment = $feedback->getComment();
                            }else{
                                $rates = NULL;
                                $comment = NULL;
                            }
                        }
                        
                        ?>
                        <form id="rate-modal-form" autocomplete="off" class="needs-validation" novalidate>
                            <div class="modal-body">
                            <input type="hidden" name="resto-id" value="<?=Handler::encryptInt($restaurant->getUid())?>">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="" class="font-weight-bold">Attribuer une Note /5 : <small>( dans l'ordre de 1 à 5 )</small></label>
                                            <br><div class='mt-2 starrr'></div>
                                            <input type="text" id="rate-choice" value="<?=$rates?>" name="user-rates" style="opacity: 0;width: 0;height:0;" required>
                                            <div class="invalid-feedback">Ce champ est obligatoire</div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1" class="font-weight-bold">Ecrire un Avis :</label>
                                            <textarea name="user-comment" class="form-control" id="exampleFormControlInput1" placeholder="ajouter avis sur les restaurant et/ou la livraison ..." style="margin-top: 0px; margin-bottom: 0px; height: 150px;" required><?=$comment?></textarea>
                                            <div class="invalid-feedback">Ce champ est obligatoire</div>
                                        </div>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"> Annuler</button>
                                <button type="submit" id="submit-rate" class="btn btn-primary btn-lg"> Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card mb-3">
                    <img class="card-img-top" style="width: 100%;height: 25vh;object-fit: cover;" src="<?= Handler::getCdnCoverImage($restaurant->getUid(), $restaurant->getCoverPhoto()) ?>" alt="Card image cap">

                    <div class="card-body">
                        <img style="float:left;" src="<?= Handler::getCdnImage($restaurant->getUid(), $restaurant->getLogo(), 'crop', 128, 128) ?>" class="img-thumbnail mr-3" width="80">
                        <h5 class="card-title text-uppercase ml-3 mt-2"><?= $restaurant->getRestaurantName() ?></h5>
                        <p class="text-dark"><i style="font-size: 15px;" class="mdi mdi-map-marker-radius"></i> <?= $address->getAddress() ?></p>
                        <div class="row">
                            <div class="col-md-12 mt-2">
                                <p><?= $restoWork->getDescription() ?></p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <i class="mdi mdi-truck"></i> &nbsp;Distance de Livraison Maximale : <strong>12 KM.</strong>
                            </div>
                        </div>

                        <div class="text-right">
                            <span class="float-left">
                                <?php
                                if ($closed == 'disabled') {
                                    echo '<button style="pointer-events: none;" class="mb-1 btn btn-danger" type="button"><i class="mdi mdi-alarm-off"></i> Fermé</button>';
                                } else {
                                    echo '<button style="pointer-events: none;" class="mb-1 btn btn-success" type="button"><i class="mdi mdi-alarm"></i> Ouvert</button>';
                                }
                                if($workingToday){
                                    echo '<button style="pointer-events: none;" class="ml-2 mb-1 btn btn-info" type="button"><i class="mdi mdi-clock"></i> '. $workTimesArray[$today][0] .' - '. $workTimesArray[$today][1] .'</button>';
                                }
                                ?>
                            </span>
                            <?php
                            $checkRates = (new Order)
                                ->setCustomerId(Session::get('customer_id'))
                                ->setRestaurantId($restaurant->getUid())
                                ->setStatus('1')
                                ->getCustomerNbrOrdersByRestaurantWS();
                            if ($checkRates > 0) {
                                ?>
                                <button class="btn btn-primary mb-1" type="button" data-toggle="modal" data-target="#rates-modal" title="Ajouter une Note / Avis"><i class="mdi mdi-message-text"></i> NOTE ?</button>

                            <?php
                            }
                            global $min_delivery_time_resto, $max_delivery_time_resto;
                            $min_delivery_time_resto = Handler::getMinutesAVG($restoWork->getPreparationTimeMin(), $deliveryMinTime);
                            $max_delivery_time_resto = Handler::getMinutesAVG($restoWork->getPreparationTimeMax(), $deliveryMaxTime);
                            
                            $rating = (new RestaurantFeedback)->setRestaurantId($restaurant->getUid())->getRatingByRestaurantId();
                            
                            $rates = array(
                                str_repeat('<i class="mdi mdi-star-outline"></i>', 5),
                                '<i class="text-warning mdi mdi-star"></i>' . str_repeat('<i class="mdi mdi-star-outline"></i>', 4),
                                str_repeat('<i class="text-warning mdi mdi-star"></i>', 2) . str_repeat('<i class="mdi mdi-star-outline"></i>', 3),
                                str_repeat('<i class="text-warning mdi mdi-star"></i>', 3) . str_repeat('<i class="mdi mdi-star-outline"></i>', 2),
                                str_repeat('<i class="text-warning mdi mdi-star"></i>', 4) . str_repeat('<i class="mdi mdi-star-outline"></i>', 1),
                                str_repeat('<i class="text-warning mdi mdi-star"></i>', 5)
                            );
                        
                            ?>
                            <button class="btn btn-secondary mb-1" type="button" data-toggle="tooltip" data-html="true" title='<?=$rates[(int) $rating]?>'><i class="fas fa-star"></i> <?= $rating ?></button>
                            <button class="shadow btn btn-success mb-1" type="button" data-toggle="tooltip" data-placement="bottom" title="Temps moyenne de Livraison"><i class="mdi mdi-bike"></i> <?= $min_delivery_time_resto . ' - ' . $max_delivery_time_resto ?> min </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>