<!-- Modal -->
                        <div class="modal fade" id="bd-address-modal" role="dialog" aria-labelledby="address-modal-label" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 600px;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="address-modal-label">Ajouter Adresse de Livraison</h5>
                                        <button type="button" class="close close-top-right" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true"><i class="mdi mdi-close"></i></span>
                                            <span class="sr-only">Fermer</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="addr-modal-content">
                                        <div id="alert-section"></div>
                                        <form id="addr-add-form" class="needs-validation" novalidate>
                                            <input type="hidden" name="item_id" value="" />
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <?php 
                                                            $addrNumb = (new Address)->setUserAuth('4')->setUserId(Session::get('customer_id'))->countByUserId();
                                                        ?>
                                                        <label class="control-label">Nom de l'adresse <span class="required text-danger">*</span></label>
                                                        <input class="form-control border-form-control" name="modal-address_name" value="Mon adresse <?=($addrNumb+1)?>" data-value="Mon adresse <?=($addrNumb+1)?>" placeholder="Nom de l'adresse" type="text" required>
                                                        <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label">VILLE <span class="required text-danger">*</span></label>
                                                        <select class="select2 form-control border-form-control" name="modal-city_id" id="cityInput" required>
                                                            <option value="" disabled selected>S??lectionner ville</option>
                                                            <?php 
                                                            $cities = (new City)->setStatus('1')->getAllByStatus();
                                                            foreach ($cities as $city) {
                                                                echo '<option value="'.Handler::encryptInt($city->getId()).'" data-lat="'.$city->getLat().'" data-lng="'.$city->getLng().'" >'.$city->getCityName().'</option>';
                                                            }
                                                            ?>
                                                      </select>
                                                      <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label">B??timent <span class="required text-danger">*</span></label>
                                                        <select class="select2 form-control border-form-control" name="modal-building" id="modal-apartment-select" required>
                                                            <option value="" disabled selected>S??lectionner choix</option>
                                                            <option value="O">Maison</option>
                                                            <option value="A">Appartement</option>
                                                        </select>
                                                        <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row d-none" id="modal-apartment-extand">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Bloc / Nom d'appartement  <span class="required text-danger">*</span></label>
                                                        <input name="modal-apartment_bloc" class="form-control border-form-control" value="" placeholder="ex. residence foulen, bloc A" type="text">
                                                        <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Etage</label>
                                                        <input name="modal-floor" class="form-control border-form-control" value="" placeholder="numero de l'??tage" type="number">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Rue / Quartier <span class="required text-danger">*</span></label>
                                                        <input name="modal-street" class="form-control border-form-control" value="" placeholder="Quartier, N?? Rue ..." type="text" required>
                                                        <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Address (d??taill??)<span class="required text-danger">*</span></label>
                                                        <textarea name="modal-address" class="form-control border-form-control" style="margin-top: 0px; margin-bottom: 0px; height: 80px;resize: none;" required></textarea>
                                                        <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Instructions pour le Livreur</label>
                                                        <textarea name="modal-instructions" class="form-control border-form-control" style="margin-top: 0px; margin-bottom: 0px; height: 80px;resize: none;"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Positionner votre adresse sur le map : <span class="required text-danger">*</span></label>
                                                        <input type="hidden" name="modal-latitude" required/>
                                                        <input class="form-control" type="text" name="modal-longtitude" style="opacity: 0;width: 0;height:0;" required/>
                                                        <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                                        <div class="img-thumbnail map" id="" style="width:100%;height:400px;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">Annuler</button>
                                        <button type="submit" id="addr-add-submit" class="btn btn-success btn-lg">Enregistrer</button>
                                        <button type="button" id="addr-edit-submit" class="btn btn-success btn-lg d-none">Modifier</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>