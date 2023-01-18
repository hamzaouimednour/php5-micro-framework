                        
                        <div class="col-md-4">
                            <div class="card account-left">
                                <div class="user-profile-header">
                                    <img alt="logo" draggable="false" src="<?= HTML_PATH_IMG . 'user.png' ?>">
                                    <?php
                                        $customer = (new Customer)->setUid(Session::get('customer_id'))->getUserByUid();
                                    ?>
                                    <h5 class="mb-1 text-secondary text-capitalize"><?= $customer->getFullName()?></h5>
                                    <p> +216 <?=$customer->getPhone()?> </p>
                                    <span class="badge badge-primary mt-2" data-toggle="modal" data-target="#bd-profile-modal" style="cursor: pointer;font-size: 14px;"><i class="mdi mdi-account-edit mb-2"></i> Modifier</span>
                                </div>
                                <div class="list-group">
                                    <a href="<?= HTML_PATH_ROOT . 'user/address' ?>" class="list-group-item list-group-item-action <?= $this->getComponent() == 'Address' ? 'active' : null ?>"><i aria-hidden="true" class="mdi mdi-map-marker-circle"></i> Adresses</a>
                                    <!-- <a href="<?= HTML_PATH_ROOT . 'user/bookmark' ?>" class="list-group-item list-group-item-action <?= $this->getComponent() == 'Bookmark' ? 'active' : null ?>"><i aria-hidden="true" class="mdi mdi-heart-outline"></i> Plats Favoris </a> -->
                                    <a href="<?= HTML_PATH_ROOT . 'user/orders' ?>" class="list-group-item list-group-item-action <?= $this->getComponent() == 'Orders' ? 'active' : null ?>"><i aria-hidden="true" class="mdi mdi-format-list-bulleted"></i> Liste des commandes</a>
                                    <a href="<?= HTML_PATH_ROOT . 'user/logout' ?>" class="list-group-item list-group-item-action"><i aria-hidden="true" class="mdi mdi-lock"></i> Déconnexion</a>
                                </div>
                            </div>
                        </div>