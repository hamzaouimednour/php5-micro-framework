    <section class="section-padding bg-dark inner-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h1 class="mt-0 mb-3 text-white">Offres près de chez vous</h1>
                    <div class="breadcrumbs">
                        <p class="mb-0 text-white">Meilleures offres dans vos restaurants préférés</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section pt-5 pb-5 products-listing">
        <div class="container">
            <div class="row">
                <?php $this->requireTPL('p-search-speciality', PATH_PUBLIC); ?>
                <div class="col-md-9">
                    <div class="row">
                        <?php

                        $restosIDsArray = [];

                        $specialtyInfos = explode('-', $this->getParamsIndexOf(1));

                        if(mb_strtolower(end($specialtyInfos) != 'delivery')){
                            $specialtyID = Handler::decryptInt(Handler::getNumber(end($specialtyInfos)));
                            $resotsSpec = (new RestaurantSpecialties)->setSpecialtyId($specialtyID)->getAllRestaurantsByCitySpecialty(Session::get('customer_city_id'));
                            foreach ($resotsSpec as $obj) {
                                array_push($restosIDsArray, $obj->getRestaurantId());
                            }
                        }else{
                            $restosIDs = (new Address)->setUserAuth('2')->setCityId(Session::get('customer_city_id'))->getAllRestaurantsByCity();
                            foreach ($restosIDs as $obj) {
                                array_push($restosIDsArray, $obj->getUserId());
                            }
                        }

                        $restosId = implode(', ', $restosIDsArray);
                        $restosInfo = (new Restaurant)->setUserStatus('1')->setPartnerRequest("'A'")->setUid('(' . Handler::getNumberMap($restosId) . ')')->getUsersByUidWS();
                        if ($restosInfo) {
                            foreach ($restosInfo as $obj) {
                                $restoWork = (new RestaurantWork)->setRestaurantId($obj->getUid())->getElementByRestaurantId();
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
                                $min_delivery_time_resto = Handler::getMinutesAVG($restoWork->getPreparationTimeMin(), $deliveryMinTime);
                                $max_delivery_time_resto = Handler::getMinutesAVG($restoWork->getPreparationTimeMax(), $deliveryMaxTime);
                                
                                global $dateTime;
                                $today = $dateTime->format('N');    // MySQL datetime format
                                $address = (new Address)->setUserAuth('2')->setUserId($obj->getUid())->getElementByUserId();
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
                                
                                
                                ?>
                                <div class="col-md-4 col-sm-6 mb-4 pb-2">
                                    <div class="list-card bg-white rounded overflow-hidden position-relative shadow-sm">
                                        <div class="list-card-image">
                                            <!-- <div class="star position-absolute"><span class="badge badge-success"><i class="mdi mdi-star"></i> 3.1</span></div> -->
                                            <!-- <div class="favourite-heart text-danger position-absolute"><a href="detail.html"><i class="icofont-heart"></i></a></div> -->
                                            <!-- <div class="member-plan position-absolute"><span class="badge badge-dark">Promoted</span></div> -->
                                            <a href="<?=HTML_PATH_ROOT . 'menu/' . URLify::filter($obj->getRestaurantName()) . '-' . Handler::encryptInt($obj->getUid()) ?>">
                                            <?php
                                            if(!empty($obj->getCoverPhoto())){
                                                echo '<img draggable="false" src="'.Handler::getCdnCoverImage($obj->getUid(), 'thumb-' . $obj->getCoverPhoto()).'" class="img-fluid item-img">';
                                            }else{
                                                echo '<img draggable="false" src="'.Handler::getCdnCoverImage('0', 'placeholder.png').'" class="img-fluid item-img">';
                                            }
                                            ?>
                                            </a>

                                        </div>
                                        <div class="p-3 position-relative">
                                            <div class="list-card-body">
                                                <h6 class="mb-1 text-dark text-capitalize text-center">
                                                    <a href="<?=HTML_PATH_ROOT . 'menu/' . URLify::filter($obj->getRestaurantName()) . '-' . Handler::encryptInt($obj->getUid()) ?>" class="text-dark">
                                                <?=$obj->getRestaurantName()?></a>
                                            </h6>
                                            <?php
                                            $specs = [];
                                            $specialties = (new RestaurantSpecialties)->setRestaurantId($obj->getUid())->getAllByRestaurantId();
                                            foreach ($specialties as $spec) {
                                                $specs[] = $spec->getSpecialtyId();
                                            }

                                            $specsIDs = implode(', ', $specs);
                                            $specialities = (new Specialties)->setId('(' . Handler::getNumberMap($specsIDs) . ')')->getElementsById();
                                            $specs = [];
                                            foreach ($specialities as $specialty) {
                                                $specs[] = $specialty->getSpecialty();
                                            }

                                            ?>
                                                <p title="• <?=implode(' • ', $specs)?>" class="text-gray mb-3 text-truncate text-capitalize"> • <?=implode(' • ', $specs)?></p>
                                                <p class="text-gray mb-3 time">
                                                    <span class="bg-light text-dark rounded pl-2 pb-1 pt-1 pr-2"><i class="mdi mdi-clock"></i> <?= $min_delivery_time_resto . ' - ' . $max_delivery_time_resto ?> min</span> 
                                                    <span class="float-right "><span style="pointer-events: none;" class="btn-info text-white rounded pl-2 pb-1 pt-1 pr-2"><i class="mdi mdi-bike"></i> <?= $delivery_fee?> <small>DT</small></span></span> 
                                                    <!-- <span class="float-right "> <button style="pointer-events: none;" class="btn btn-danger" type="button"><i class="mdi mdi-truck"></i> <?=$delivery_fee?></button></span> -->
                                                </p>
                                            </div>
                                            <div class="list-card-badge">
                                                <?php
                                                if($closed == 'disabled'){
                                                    echo '<span class="badge badge-danger">Fermé</span> <small>Le restaurant est maintenant fermé.</small>';
                                                }

                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php            # code...
                            }
                        }else{
                            echo '<div class="col-md-12 text-center load-more"><img draggable="false" width="350px" src="'.HTML_PATH_IMG .'no-food.png"></div>';
                        }
                        ?>

                        <div class="col-md-12 text-center load-more">
                            <!-- <button class="btn btn-primary" type="button" disabled="">
                                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                Loading...
                            </button> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>