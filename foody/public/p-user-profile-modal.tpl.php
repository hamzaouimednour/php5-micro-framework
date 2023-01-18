                        <div class="modal fade" id="bd-profile-modal" tabindex="-1" role="dialog" aria-labelledby="item-modal-label" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="profile-modal-label"><i class="mdi mdi-account-edit"></i> Modifier votre compte</h5>
                                        <button type="button" class="close close-top-right" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true"><i class="mdi mdi-close"></i></span>
                                            <span class="sr-only">Fermer</span>
                                        </button>
                                        
                                    </div>
                                    <?php
                                    if(!empty(Session::get('customer_id')))
                                    $customer = (new Customer)->setUid(Session::get('customer_id'))->getUserByUid();

                                    ?>
                                    <div id="account-update-info"></div>
                                    <form id="update-account-info" class="needs-validation" novalidate>
                                    <div class="modal-body ml-1 mr-1" id="profile-modal-content">
                                        <div class="form-group">
                                            <label class="control-label">Nom et Prénom </label>
                                            <input class="form-control border-form-control" value="<?=$customer->getFullName()?>" placeholder="Gurdeep" type="text" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Téléphone </label>
                                            <input class="form-control border-form-control" type="number"  value="<?=$customer->getPhone()?>" placeholder="12 345 678" onpaste="off" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" pattern="[2,3,4,5,9]{1}[0-9]{7}" minlength="8" maxlength="8" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Email </label>
                                            <input class="form-control border-form-control " value="<?=$customer->getEmail()?>" placeholder="iamosahan@gmail.com" disabled="" type="email">
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <label class="control-label">Mot de passe actuel <span class="required  text-warning">*</span></label>
                                            <div class="input-group mb-2" id="show-hide-password">
                                                <input type="password" name="user-oldpwd" class="form-control border-form-control" placeholder="********" required>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-default show-pwd"><i class="mdi mdi-eye-off"></i></button>
                                                </div>
                                                <div class="invalid-feedback">Mot de passe incorrect</div>
                                                <div class="valid-feedback"></div>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Nouveau mot de passe <span class="required  text-warning">*</span></label>
                                            <div class="input-group mb-2" id="show-hide-password">
                                                <input type="password" name="user-newpwd" class="form-control border-form-control" placeholder="********" required>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-default show-pwd"><i class="mdi mdi-eye-off"></i></button>
                                                </div>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-success btn-lg" id="update-user-account"><i class="mdi mdi-content-save"></i> Enregistrer</button>
                                    </div>
                                </div>
                            </div>
                        </div>