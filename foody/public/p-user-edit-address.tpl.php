                                    <div class="row">
                                        <div class="col-sm-12 text-right mb-5">
                                            <button type="button" class="btn btn-primary btn-lg" id="add-address-modal"><i class="mdi mdi-map-marker-plus"></i> Ajouter Adresse </button>
                                        </div>
                                    </div>
                                    <div class="row" id="row-addr">
                                        <?php
                                            $addrs = (new Address)
                                            ->setUserAuth('4')
                                            ->setUserId(Session::get('customer_id'))
                                            ->getElementsByUserId();
                                            $customerAddrs = (new CustomerAddress)
                                            ->setCustomerId(Session::get('customer_id'))
                                            ->setStatus('1')
                                            ->getElementsByUserIdWS();
                                            foreach ($addrs as $addr) {
                                                foreach ($customerAddrs as $customerAddr) {
                                                    if($customerAddr->getAddressId() == $addr->getId()){
                                        ?> 
                                        <div class="col-sm-6 mb-4">
                                            <div class="card shadow">
                                                <div class="card-body">
                                                    <h5 class="card-title"><?=ucfirst($customerAddr->getAddressName())?></h5>
                                                    <p class="card-text"><?=$addr->getAddress();?></p>
                                                    
                                                    <button class="btn btn-success float-right btn-edit" data-id="<?=Handler::encryptInt($customerAddr->getAddressId())?>" data-toggle="tooltip" data-placement="top" title="Modifier" data-trigger="hover"><i class="mdi mdi-pencil"></i></button>
                                                    <button class="btn btn-danger float-right btn-delete mr-2" data-id="<?=Handler::encryptInt($customerAddr->getAddressId())?>" data-toggle="tooltip" data-placement="top" title="Supprimer" data-trigger="hover"><i class="mdi mdi-delete"></i></button>
                                                </div>
                                            </div>
                                        </div>     
                                        <?php
                                                    }
                                                }
                                            }
                                        ?>

                                    </div>