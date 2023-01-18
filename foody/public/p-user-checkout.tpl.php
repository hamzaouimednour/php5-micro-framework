    <?php $this->requireTPL('p-user-add-address-checkout', PATH_PUBLIC); ?>
    <section class="checkout-page section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mb-5">
                    <div class="checkout-step">
                        <div class="accordion" id="accordionExample">
                            <div class="card checkout-step-one">
                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link text-truncate" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" title="Vérification Numéro de Téléphone" disabled>
                                            <span class="number"><i class="mdi mdi-check"></i></span> Vérification Numéro de Téléphone
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <p>Nous avons besoin de votre numéro de téléphone pour pouvoir vous tenir au courant de votre commande.</p>
                                        <div class="form-row align-items-center">
                                            <div class="col-auto">
                                                <label class="sr-only">Numéro de Téléphone</label>
                                                <div class="input-group mb-2">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"><span class="mdi mdi-cellphone-iphone"></span></div>
                                                    </div>
                                                    <input type="text" class="form-control" placeholder="Entrer numéro de téléphone">
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button type="button" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" class="btn btn-secondary mb-2 btn-lg">NEXT</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card checkout-step-two">
                                <div class="card-header" id="headingTwo">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            <span class="number">2</span> Adresse de Livraison
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                    <div class="card-body">

                                        <form>
                                            <div class="col-lg-8 col-md-8 mx-auto">

                                                <?php
                                                // print_r(Session::get('foody_cart'));
                                                $cart = (new Cart)->getRestaurantUid();
                                                $restoAddress = (new Address)->setUserAuth('2')->setUserId($cart)->getElementByUserId();
                                                $restoInfo = (new Restaurant)->setUid($cart)->getUserByUid();
                                                echo '<span id="resto-geo" data-resto-lat="' . $restoAddress->getLatitude() . '" data-resto-lng="' . $restoAddress->getLongtitude() . '" data-title="' . $restoInfo->getRestaurantName() . '"></span>';
                                                $cityId = $restoAddress->getCityId();
                                                $customerAddrNbr = (new Address)->setUserAuth('4')->setUserId(Session::get('customer_id'))->countByUserId();
                                                
                                                $restoWork = (new RestaurantWork)->setRestaurantId($cart)->getElementByRestaurantId();
                                                $avgTime = Handler::getMinutesAVG($restoWork->getPreparationTimeMin(), $restoWork->getPreparationTimeMax());
                                                if($restoWork->getDeliveryType() == 1){
                                                    $delivery_fee = $restoWork->getDeliveryFee();
                                                    $init_distance = $restoWork->getInitDistance();
                                                    $up_fee = $restoWork->getUpFee();
                                                    $up_distance = $restoWork->getUpDistance();
                                    
                                                }else{
                                                    $op1 = (new Options)->setOptionName('delivery_fee')->getElementByOptionName();
                                                    $delivery_fee = $op1->getOptionValue();
                                                    $op2 = (new Options)->setOptionName('init_distance')->getElementByOptionName();
                                                    $init_distance = $op2->getOptionValue();
                                                    $op3 = (new Options)->setOptionName('up_fee')->getElementByOptionName();
                                                    $up_fee = $op3->getOptionValue();
                                                    $op4 = (new Options)->setOptionName('up_distance')->getElementByOptionName();
                                                    $up_distance = $op4->getOptionValue();
                                                }
                                                $addToCart = (new Cart);
                                                if(empty($addToCart->getInitDistance())){
                                                    $addToCart->setInitDistance($init_distance);
                                                    $addToCart->setUpFee($up_fee);
                                                    $addToCart->set();
                                                }
                                                ?>
                                                <span id="delivery-info" data-fee="<?=$delivery_fee?>" data-init-distance="<?=$init_distance?>" data-up-fee="<?=$up_fee?>" data-up-distance="<?=$up_distance?>" data-avg-preparation="<?=$avgTime?>"></span>
                                                <div id="resto-info" class="d-none">
                                                    <h6 class="text-uppercase"><i class="text-primary mdi mdi-food-fork-drink"></i> <?= $restoInfo->getRestaurantName() ?></h6>
                                                    <p><i class="text-danger mdi mdi-map-marker-radius"></i> <?= $restoAddress->getAddress() ?></p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="selectAddr">Sélectionner une Adresse <small id="new-address" class="text-success d-none">( <i class="mdi mdi-check"></i> Nouvelle adresse ajoutée )</small></label>
                                                    <select class="form-control select2" name="address" id="selectAddr">
                                                        <?php
                                                        if ($customerAddrNbr == 0) {
                                                            echo '<option value="" disabled selected>Aucun adresse trouvé</option>';
                                                        } else {
                                                            $customerAddress = (new Address)->setUserAuth('4')->setUserId(Session::get('customer_id'))->getElementsByUserId();
                                                            $customerAddrs = (new CustomerAddress)
                                                                ->setCustomerId(Session::get('customer_id'))
                                                                ->setStatus('1')
                                                                ->getElementsByUserIdWS();

                                                            echo '<option value="" disabled selected>Sélectionner adresse de livraison</option>';
                                                            foreach ($customerAddress as $addr) {
                                                                foreach ($customerAddrs as $customerAddr) {
                                                                    if ($customerAddr->getAddressId() == $addr->getId() && $cityId == $addr->getCityId()) {
                                                                        if(!empty($addToCart->getCustomerAddressId())){
                                                                            if($addToCart->getCustomerAddressId() == $customerAddr->getAddressId()){
                                                                                echo '<option value="' . Handler::encryptInt($addr->getId()) . '" data-lat="' . $addr->getLatitude() . '" data-lng="' . $addr->getLongtitude() . '" selected>' . $customerAddr->getAddressName() . '</option>';
                                                                            }else {
                                                                                echo '<option value="' . Handler::encryptInt($addr->getId()) . '" data-lat="' . $addr->getLatitude() . '" data-lng="' . $addr->getLongtitude() . '">' . $customerAddr->getAddressName() . '</option>';
                                                                            }
                                                                        }else {
                                                                            echo '<option value="' . Handler::encryptInt($addr->getId()) . '" data-lat="' . $addr->getLatitude() . '" data-lng="' . $addr->getLongtitude() . '">' . $customerAddr->getAddressName() . '</option>';
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        * S'il vous plaît choisir une adresse de livraison.
                                                    </div>
                                                </div>
                                                <p class="text-center mt-4"> <strong>OU</strong> </p>
                                                <hr class="mb-4">
                                                <div class="form-group text-center">
                                                    <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#bd-address-modal">
                                                        <i class="mdi mdi-map-marker-plus"></i> Ajouter Nouvelle Adresse
                                                    </button>
                                                </div>

                                                <button type="button" id="collapse2" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" class="btn btn-secondary mb-2 btn-lg  mt-4">SUIVANT</button>
                                            </div>
                                            <div class="row col-lg-9 col-md-9 mx-auto mt-5">
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="control-label">Temps <small>( min )</small></label>
                                                            <input class="form-control border-form-control" id="checkout-time" value="" placeholder="00.00 MIN" type="text" style="pointer-events:none;">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Distance <small>( KM )</small></label>
                                                            <input class="form-control border-form-control" id="checkout-distance" value="" placeholder="0.000 KM" type="text" style="pointer-events:none;">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <div class="form-group">
                                                            <label class="control-label">Frais de Livraison <small>( Nouveau )</small></label>
                                                            <input class="form-control border-form-control" id="checkout-delivery_fee" value="" placeholder="0.000 DT" type="text" style="pointer-events:none;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 mx-auto">
                                                    <div class="form-group">
                                                        <!-- <label class="control-label">Positionner votre adresse sur le map : <span class="required text-danger">*</span></label> -->
                                                        <input type="hidden" name="modal-latitude" required />
                                                        <input class="form-control" type="text" name="modal-longtitude" style="opacity: 0;width: 0;height:0;" required />
                                                        <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                                        <div class="img-thumbnail map" id="" style="width:100%;height:450px;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingThree">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed text-truncate" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" title="Au cas d'indisponibilité d'un article, que souhaiteriez vous ?" disabled>
                                            <span class="number">3</span> Au cas d'indisponibilité d'un article, que souhaiteriez vous ?
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="col-lg-8 col-md-8 mx-auto">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="customRadio1" name="order-err" class="custom-control-input" value="1" checked>
                                                    <label class="custom-control-label" for="customRadio1">Laisser le restaurant choisir/remplacer un article ou un autre detail.</label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="customRadio2" name="order-err" class="custom-control-input" value="2">
                                                    <label class="custom-control-label" for="customRadio2">Annuler cet article.</label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="customRadio3" name="order-err" class="custom-control-input" value="3">
                                                    <label class="custom-control-label" for="customRadio3">Annuler la commande.</label>
                                                </div>
                                            </div>

                                            <hr>
                                            <p><i class="fas fa-question-circle"></i> Si une modification survient sur vote commande ou a été rejetée par le restaurant, vous serez informé.</p>
                                            <button type="button" id="order-submit" data-toggle="collapse" data-target="#collapsefour" aria-expanded="false" aria-controls="collapsefour" class="btn btn-secondary mb-2 btn-lg">CONFIRMER LA COMMANDE</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingThree">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapsefour" aria-expanded="false" aria-controls="collapsefour" disabled>
                                                <span class="number">4</span> Statut de la commande
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapsefour" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="text-center">
                                                <div class="col-lg-10 col-md-10 mx-auto order-done">
                                                    
                                                </div>
                                                <div class="text-center">
                                                    <a href="#"><button type="submit" class="btn btn-secondary mb-2 btn-lg"><i class="mdi mdi-home"></i> &nbsp; Page d'accueil</button></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php

                $cart = new Cart;

                $cart_data = $cart->checkoutHTML();
                // print_r($cart_data);
                // $cart->sum();
                // print_r($cart->getCart());
                ?>
                <div class="col-md-4">
                    <div class="card">
                        <h5 class="card-header"><i class="mdi mdi-shopping"></i> Panier </h5>
                        <div id="checkout-content">
                            <?= $cart_data; ?>
                        </div>
                    </div>
                </div>
            </div>
    </section>