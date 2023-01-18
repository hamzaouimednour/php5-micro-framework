    <section class="account-page section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="row no-gutters">
                        <?php $this->requireTPL('p-profile-menu', PATH_PUBLIC); ?>

                        
                        <?php $this->requireTPL('p-user-add-address-modal', PATH_PUBLIC); ?>
                        <div class="col-md-8">
                            <div class="card card-body account-right">
                                <div class="widget">
                                    <!-- New Address --> <!-- /New Address -->
                                    <div class="section-header">
                                        <h5 class="heading-design-h5">
                                            <i class="mdi mdi-home-map-marker"></i> Gérer les Adresses
                                        </h5>
                                    </div>
                                    <?php 
                                    $customerAddr = (new CustomerAddress)
                                                ->setCustomerId(Session::get('customer_id'))
                                                ->setStatus('1')
                                                ->getElementsByUserIdWS();
                                    if(!$customerAddr){
                                        $this->requireTPL('p-user-address-none', PATH_PUBLIC); 
                                    }else{
                                        $this->requireTPL('p-user-edit-address', PATH_PUBLIC); 
                                    }                                    
                                    
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>