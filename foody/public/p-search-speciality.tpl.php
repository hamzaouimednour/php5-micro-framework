                <div class="col-md-3 mb-3">
                    <div class="shop-filters">
                        <div id="accordion">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Cuisine <span class="mdi mdi-chevron-down float-right"></span>
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                    <div class="card-body card-shop-filters">
                                        <!-- <form class="form-inline mb-3">
                                            <div class="input-group">
                                                <input type="text" placeholder="Rechercher Menu" class="form-control">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-secondary"> <i class="mdi mdi-file-find"></i></button>
                                                </div>
                                            </div>
                                        </form> -->
                                        <div class="custom-control custom-checkbox">
                                            <?php
                                            $checked = NULL;
                                            if(mb_strtolower($this->getParamsIndexOf(1))  == 'delivery'){
                                                $checked = 'checked';
                                            }
                                            ?>
                                            <input type="checkbox" name="specialty-search[]" data-href="delivery" class="custom-control-input" id="specialty-all" <?=$checked?> >
                                            <label class="custom-control-label text-capitalize" for="specialty-all">Tous Les Cuisines</label>
                                        </div>
                                        <hr>
                                        <?php
                                            $specialtyInfos = explode('-', $this->getParamsIndexOf(1));
                                            $specialtyID = Handler::decryptInt(Handler::getNumber(end($specialtyInfos)));

                                            $restosIDs = (new Address)->setUserAuth('2')->setCityId(Session::get('customer_city_id'))->getAllRestaurantsByCity();
                                            if($restosIDs){
                                            $restosIDsArray = [];
                                            foreach ($restosIDs as $obj) {
                                                array_push($restosIDsArray , $obj->getUserId() );
                                            }
                                            $restosIDsArray = implode(', ', $restosIDsArray);

                                            $specialities = (new RestaurantSpecialties)->setRestaurantId('(' . Handler::getNumberMap($restosIDsArray) . ')')->getElementsByRestaurantId();
                                            $specialtiesIDsArray = [];
                                            foreach ($specialities as $obj) {
                                                array_push($specialtiesIDsArray , $obj->getSpecialtyId() );
                                            }
                                            $specialtiesIDsArray = implode(', ', $specialtiesIDsArray);
                                            $specialities = (new Specialties)->setId('(' . Handler::getNumberMap($specialtiesIDsArray) . ')')->getElementsById();

                                            foreach ($specialities as $specialty) {
                                                $checked = NULL;
                                                if($specialtyID == $specialty->getId()){
                                                    $checked = 'checked';
                                                }
                                        ?>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="specialty-search[]" data-href="<?=mb_strtolower(URLify::filter($specialty->getSpecialty()) . '-' . Handler::encryptInt($specialty->getId()))?>" id="specialty-<?=Handler::encryptInt($specialty->getId())?>" <?=$checked?> >
                                            <label class="custom-control-label text-capitalize" for="specialty-<?=Handler::encryptInt($specialty->getId())?>"><?=$specialty->getSpecialty()?></label>
                                        </div>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>