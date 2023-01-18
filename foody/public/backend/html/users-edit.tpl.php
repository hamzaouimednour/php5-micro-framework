<?php
list($dataUser, $userAuth) = null;
$userUid = Handler::decryptInt($this->getParamsIndexOf(1));
switch ($this->getParamsIndexOf()) {
  case 1:
    $dataUser = (new Administrator);
    break;
  case 2:
    $dataUser = (new Restaurant);
    break;
  case 3:
    $dataUser = (new Delivery);
    break;
  case 4:
    $dataUser = (new Customer);
    break;
}
$dataUser = $dataUser->setUid($userUid)->getUserByUid();
$address = (new Address)
  ->setUserAuth($this->getParamsIndexOf())
  ->setUserId($userUid)
  ->getElementByUserId();
if ($this->getParamsIndexOf() == 2) {
  $restoWork = (new RestaurantWork)->setRestaurantId($userUid)->getElementByRestaurantId();
}
$myDelivery = NULL;
if ($dataUser) {
  ?>

    <div class="col-lg-12">
      <div class="panel panel-inverse" data-sortable-id="ui-widget-5" data-init="true">
        <div class="panel-heading ui-sortable-handle">
          <h4 class="panel-title"><span class="label label-success m-r-10 pull-left"><i class="fas fa-plus"></i></span> Manager Les Utilisateurs
          </h4>
        </div>
        <div class="panel-body">
          <?php
            $partnerStatus = ['P' => 'En attente &nbsp;<i class="fas fa-sync"></i>', 'A' => 'Accepté &nbsp;<i class="fas fa-check-square"></i>', 'R' => 'Refusé &nbsp;<i class="fas fa-times-circle"></i>'];
            $partnerLabel = ['P' => 'warning', 'A' => 'green', 'R' => 'danger'];

            ?>
          <div class="text-right">
            <button class="btn btn-primary mb-2" id="btn-send-pwd" data-id="<?=$userUid?>" data-container="body" data-toggle="popover" data-html="true" title="Envoyer Mot de passe" data-placement="bottom" data-content='<div class="input-group"><input type="text" id="user-pwd" data-auth="2" class="form-control" placeholder="mode de passe"><div class="input-group-append"><button class="btn btn-primary" type="button" id="send-pwd"><i id="send-icon" class="fas fa-paper-plane"></i></button></div></div>'>E-mail de Mot de passe &nbsp;<i class="fas fa-envelope"></i></button>
            <div class="btn-group mb-2">
              <a href="#" class="btn btn-<?= $partnerLabel[$dataUser->getPartnerRequest()] ?>" disabled>Demande <?= $partnerStatus[$dataUser->getPartnerRequest()] ?></a>
              <a href="#" class="btn btn-<?= $partnerLabel[$dataUser->getPartnerRequest()] ?> dropdown-toggle" data-toggle="dropdown"></a>
              <ul class="dropdown-menu pull-right">
                <li> <a href="javascript:;" class="text-success" id="partner-accept" <?= $dataUser->getPartnerRequest() == 'A' ? 'style="cursor: not-allowed;pointer-events: none;"' : false ?>><i class="fas fa-check-square"></i> Accepter</a> </li>
                <li class="divider"></li>
                <li> <a href="javascript:;" class="text-danger" id="partner-refuse" <?= $dataUser->getPartnerRequest() == 'R' ? 'style="cursor: not-allowed;pointer-events: none;"' : false ?>><i class="fas fa-times-circle"></i> Refuser</a></li>
              </ul>
            </div>
          </div>
          <hr class="mb-4">

          <div class="col-sm" id="note-msg"></div>

          <form id="user-form" class="needs-validation" autocomplete="off" novalidate>
            <div class="row">
              <div class="col-sm-6">
                <div class="col">
                  <div class="form-group">
                    <label for="textInput1">Nom complet du responsable <small class="text-danger" title="champ obligatoire">*</small></label>
                    <input type="text" class="form-control" id="textInput1" name="user-full_name" placeholder="Nom & Prénom" value="<?= $dataUser->getFullName() ?>" required>
                    <div class="invalid-feedback">Ce champ est obligatoire.</div>
                  </div>
                </div>

                <div class="col">
                  <div class="form-group">
                    <label for="textInput2">Nom du restaurant <small class="text-danger" title="champ obligatoire">*</small></label>
                    <input type="text" class="form-control" id="textInput2" name="user-restaurant_name" placeholder="Nom du Resto" value="<?= $dataUser->getRestaurantName() ?>" required>
                    <div class="invalid-feedback">Ce champ est obligatoire.</div>
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="selectInput1">Livraison <small class="text-danger" title="champ obligatoire">*</small></label>
                    <select id="selectInput1" name="restaurant-delivery_type" class="form-control" required>
                      <option value="" selected disabled> Sélectionnez un type de Livraison </option>
                      <option value="1" <?= ($restoWork->getDeliveryType() == 1) ? ' selected' : NULL ?>>Propre service de livraison</option>
                      <option value="2" <?= ($restoWork->getDeliveryType() == 2) ? ' selected' : NULL ?>>Nos service de livraison</option>
                    </select>
                    <div class="invalid-feedback">Ce champ est obligatoire.</div>
                  </div>
                </div>

                <div class="col">
                  <div class="form-group">
                    <label for="password-indicator-visible">Mot de passe</label>
                    <input type="text" name="user-passwd" id="password-indicator-visible" class="form-control m-b-5" />
                    <div id="passwordStrengthDiv2" class="is0 m-t-5"></div>
                    <div class="invalid-feedback">Ce champ est obligatoire.</div>
                  </div>
                </div>

              </div>

              <div class="col-sm-6">
                <div class="col">
                  <div class="form-group">
                    <label for="textInput4">Email <small class="text-danger" title="champ obligatoire">*</small></label>
                    <input type="email" class="form-control" id="textInput4" name="user-email" placeholder="foulen@example.com" value="<?= $dataUser->getEmail() ?>" required>
                    <div class="invalid-feedback">Ce champ est obligatoire.</div>
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="select2Input1">Spécialités <small class="text-danger" title="champ obligatoire">*</small></label>
                    <select id="select2Input1" name="user-specialties[]" class="form-control multiple-select2" multiple="multiple" data-placeholder="Sélectionnez la/les spécialité(s)" required>
                      <optgroup label="Sélectionnez la/les spécialité(s)">
                        <?php
                          $specialties = (new Specialties)->getAll();
                          $RestaurantSpecialties = (new RestaurantSpecialties)->setRestaurantId($userUid)->getAllByRestaurantId();
                          array_walk($RestaurantSpecialties, function (&$item) {
                            $item = $item->getSpecialtyId();
                          });
                          foreach ($specialties as $specialty) {
                            if (in_array($specialty->getId(), $RestaurantSpecialties)) {
                              echo '<option value="' . $specialty->getId() . '" selected>' . $specialty->getSpecialty() . '</option>';
                            } else {
                              ?>
                            <option value="<?= $specialty->getId() ?>"><?= $specialty->getSpecialty() ?></option>
                        <?php
                            }
                          }
                          ?>
                      </optgroup>
                    </select>
                    <div class="invalid-feedback">Ce champ est obligatoire.</div>
                  </div>
                </div>

                <div class="col">
                  <div class="form-group">
                    <label for="textInput3">Phone <small class="text-danger" title="champ obligatoire">*</small></label>
                    <input type="text" class="form-control" name="user-phone" id="textInput3" placeholder="Numéro de téléphone" value="<?= $dataUser->getPhone() ?>" required>
                    <div class="invalid-feedback">Ce champ est obligatoire.</div>
                  </div>
                </div>
                <div class="col">
                  <label for="">Etat d'utilisateur</label>
                  <div class="form-group row m-b-10">
                    <div class="col-md-2 p-t-3">
                      <div class="switcher switcher-green">
                        <input type="checkbox" name="user-user_status" id="switcher_checkbox_status" <?= ($dataUser->getUserStatus() == 1) ? 'value="1" checked' : 'value="0"' ?>>
                        <label for="switcher_checkbox_status"></label>
                      </div>
                    </div>
                    <label class="col-md-5 col-form-label">
                      <h5><span id="switch_checkbox_status" class="label <?= ($dataUser->getUserStatus() == 1) ? 'label-green' : 'label-danger'; ?>"><?= ($dataUser->getUserStatus() == 1) ? 'Utilisateur est Active &nbsp;<i class="fas fa-eye"></i>' : 'Utilisateur est Inactive <i class="fas fa-eye-slash"></i>' ?></span></h5>
                    </label>

                  </div>
                </div>
              </div>
            </div>

            <div class="col-sm form-group">
              <label for="exampleInputEmail1">Logo du restaurant</label>

              <div class="dropzone needsclick" id="dropzone">
                <div class="dz-message needsclick">
                  Drag & drop files <b>here</b> or <b>click</b> to upload.<br />
                  <span class="dz-note needsclick">
                    ( allowed File Extensions are <strong>jpeg</strong>, <strong>jpg</strong>, <strong>png</strong>, <strong>gif</strong>.)
                  </span>
                </div>
              </div>
            </div>
            <div id="delivery-options" <?= ($restoWork->getDeliveryType() == 1) ? NULL : 'class="d-none"' ?>>
              <hr>
              <div class="col">
                <div class="note note-secondary m-b-15">
                  <h5><b>Les Options de Livraison!</b></h5>
                  <p>
                    On a un `Frais de livraison Minimal` : exemple on suppose ce frais = 2.500 DT, et une `Distance Minimal` = 3 KM,
                    toute commande a distance inférieur a cette distance sera avec ce frais.<br>
                    On a `Distance Extra` = 1 KM, et un `Frais Extra` = 0.500 DT alors pour toute commande avec distance supérieur a `Distance Minimal` 3 KM
                    en comptant chaque extra Kilométre en ajout 0.500 DT, exemple distance = 4 KM alors le frais sera 3.500 DT.
                  </p>
                  <h5><b>Format des Prix & Distance :</b></h5>
                  <p>Tous les prix doit au format des Millimes (i.e. </strong><strong>500</strong> : Cinq cents millimes , <strong>1000</strong> : Un dinar et zéro millimes. , <strong>10500</strong> : Dix dinars et cinq cents millimes.</small>) </p>
                  Tous les distance ont KM (i.e. </strong><strong>1</strong> : 1 KM , <strong>1000</strong> : 1000 KM , <strong>50</strong> : 50 KM.</small>)
                </div>
              </div>
              <div class="row">

                <div class="col-sm-6">
                  <div class="col">
                    <div class="form-group">
                      <label for="textInput001">Frais de livraison<small class="text-danger" title="champ obligatoire">*</small></label>
                      <input type="text" class="form-control" id="textInput001" placeholder="frais de livraison" name="delivery-fee" onpaste="return false;" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="" value="<?= ($restoWork->getDeliveryType() == 1) ? Handler::reverse_currency_format($restoWork->getDeliveryFee()) : NULL ?>" <?= ($restoWork->getDeliveryType() == 1) ? 'required' : NULL ?>>
                      <div class="invalid-feedback">Ce champ est obligatoire.</div>
                    </div>
                  </div>

                  <div class="col">
                    <div class="form-group">
                      <label for="textInput002">Distance Extra <small> ( Toujour = <b>1 KM </b>)</small></label>
                      <input type="text" class="form-control" id="textInput002" name="up-distance" value="1" disabled>
                      <div class="invalid-feedback">Ce champ est obligatoire.</div>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label for="textInput05">Délai à emporter: Minimum <small class="text-danger" title="champ obligatoire">*</small><small> (en MIN)</small></label>
                      <input type="text" class="form-control" id="textInput05" name="restaurant-delivery_time_min" onpaste="return false;" onkeypress="return event.charCode >= 48 && event.charCode <= 57" value="<?= ($restoWork->getDeliveryType() == 1) ? $restoWork->getDeliveryTimeMin() : NULL ?>" onpaste="return false;" min="0" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" placeholder="Délai de livraison minimal" <?= ($restoWork->getDeliveryType() == 1) ? 'required' : NULL ?>>
                      <div class="invalid-feedback">Ce champ est obligatoire.</div>
                    </div>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="col">
                    <div class="form-group">
                      <label for="textInput004">Distance <small class="text-danger" title="champ obligatoire">*</small> <small> (en KM)</small></label>
                      <input type="text" class="form-control" id="textInput004" name="init-distance" placeholder="distance" onpaste="return false;" onkeypress="return event.charCode >= 48 && event.charCode <= 57" value="<?= ($restoWork->getDeliveryType() == 1) ? Handler::getNumber((float) $restoWork->getInitDistance()) : NULL ?>" <?= ($restoWork->getDeliveryType() == 1) ? 'required' : NULL ?>>
                      <div class="invalid-feedback">Ce champ est obligatoire.</div>
                    </div>
                  </div>

                  <div class="col">
                    <div class="form-group">
                      <label for="textInput5">Frais Extra <small class="text-danger" title="champ obligatoire">*</small></label>
                      <input type="text" class="form-control" name="up-fee" id="textInput5" placeholder="frais extra" onpaste="return false;" onkeypress="return event.charCode >= 48 && event.charCode <= 57" value="<?= ($restoWork->getDeliveryType() == 1) ? Handler::reverse_currency_format($restoWork->getUpFee()) : NULL ?>" <?= ($restoWork->getDeliveryType() == 1) ? 'required' : NULL ?>>
                      <div class="invalid-feedback">Ce champ est obligatoire.</div>
                    </div>
                  </div>

                  <div class="col">
                    <div class="form-group">
                      <label for="textInput06">Délai à emporter: Maximum <small class="text-danger" title="champ obligatoire">*</small> <small> (en MIN)</small></label>
                      <input type="text" class="form-control" id="textInput06" onpaste="return false;" onkeypress="return event.charCode >= 48 && event.charCode <= 57" name="restaurant-delivery_time_max" value="<?= ($restoWork->getDeliveryType() == 1) ? $restoWork->getDeliveryTimeMax() : NULL ?>" onpaste="return false;" min="0" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" placeholder="Délai de livraison maximal" <?= ($restoWork->getDeliveryType() == 1) ? 'required' : NULL ?>>
                      <div class="invalid-feedback">Ce champ est obligatoire.</div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
            <hr>

            <div class="row">
                        <div class="col-sm-6">
                            <div class="col">
                                <div class="form-group">
                                    <label for="textInput01">Prix Minimal du Commande <small class="text-danger" title="champ obligatoire">*</small></label>
                                    <input type="text" class="form-control" id="textInput01" name="restaurant-min_delivery" onpaste="return false;" value="<?= Handler::reverse_currency_format($restoWork->getMinDelivery()) ?>" onpaste="return false;" min="0" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" placeholder="Prix minimal du Commande">
                                    <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="textInput02">Prix Maximal du Commande <small class="text-danger" title="champ obligatoire">*</small></label>
                                    <input type="text" class="form-control" id="textInput02" name="restaurant-max_delivery" onpaste="return false;" value="<?= Handler::reverse_currency_format($restoWork->getMaxDelivery()) ?>" onpaste="return false;" min="0" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" placeholder="Prix maximal du Commande">
                                    <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="textInput03">Temps de préparation: Minimum <small class="text-danger" title="champ obligatoire">*</small><small> (en MIN)</small></label>
                                    <input type="text" class="form-control" id="textInput03" name="restaurant-preparation_time_min" onpaste="return false;" value="<?= $restoWork->getPreparationTimeMin() ?>" onpaste="return false;" min="0" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" placeholder="Temps de préparation minimal">
                                    <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="textInput04">Temps de préparation: Maximum <small class="text-danger" title="champ obligatoire">*</small> <small> (en MIN)</small></label>
                                    <input type="text" class="form-control" id="textInput04" name="restaurant-preparation_time_max" onpaste="return false;" value="<?= $restoWork->getPreparationTimeMax() ?>" onpaste="return false;" min="0" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" placeholder="Temps de préparation maximal">
                                    <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                </div>
                            </div>

                        </div>

                        <div class="col-sm-6">
                            <div class="col">
                                <div class="form-group">
                                    <label for="textareaInput1">Description</label>
                                    <textarea class="form-control" name="restaurant-description" id="textareaInput1" placeholder='votre specialité / repas principal ...' style="margin-top: 0px; margin-bottom: 0px; height: 260px;"><?= $restoWork->getDescription() ?></textarea>
                                </div>
                            </div>

                        </div>
                    </div>

            <hr>

            <div class="row">
              <?php $restoWork->setWorkDayTime(); ?>
              <div class="col-sm-6">
                <div class="col">
                  <label>
                    <div class="checkbox checkbox-css">
                      <input class="work-time" type="checkbox" name="day-1" id="day-1" <?= is_array($restoWork->getWorkDays()) ? (in_array(1, $restoWork->getWorkDays()) ? 'checked' : null) : null; ?> />
                      <label for="day-1">Lundi</label>
                    </div>
                  </label>
                  <div class="form-inline m-b-10">
                    <div class="form-group">
                      <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                        <input type="text" name="day-1-open" value="<?= $restoWork->getWorkOpenTime(1) ?>" class="form-control time" placeholder="00:00" <?= is_array($restoWork->getWorkDays()) ? (in_array(1, $restoWork->getWorkDays()) ? null : 'disabled') : 'disabled'; ?>>
                        <span class="input-group-addon">
                          <span class="fas fa-clock fa-spin"></span>
                        </span>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                        <input type="text" name="day-1-close" value="<?= $restoWork->getWorkCloseTime(1) ?>" class="form-control time" placeholder="00:00" <?= is_array($restoWork->getWorkDays()) ? (in_array(1, $restoWork->getWorkDays()) ? null : 'disabled') : 'disabled'; ?>>
                        <span class="input-group-addon">
                          <span class="fas fa-clock fa-spin"></span>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col">
                  <label>
                    <div class="checkbox checkbox-css">
                      <input class="work-time" type="checkbox" name="day-3" id="day-3" <?= is_array($restoWork->getWorkDays()) ? (in_array(3, $restoWork->getWorkDays()) ? 'checked' : null) : NULL; ?> />
                      <label for="day-3">Mercredi</label>
                    </div>
                  </label>
                  <div class="form-inline m-b-10">
                    <div class="form-group">
                      <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                        <input type="text" name="day-3-open" value="<?= $restoWork->getWorkOpenTime(3) ?>" class="form-control time" placeholder="00:00" <?= is_array($restoWork->getWorkDays()) ? (in_array(3, $restoWork->getWorkDays()) ? null : 'disabled') : 'disabled'; ?>>
                        <span class="input-group-addon">
                          <span class="fas fa-clock fa-spin"></span>
                        </span>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                        <input type="text" name="day-3-close" value="<?= $restoWork->getWorkCloseTime(3) ?>" class="form-control time" placeholder="00:00" <?= is_array($restoWork->getWorkDays()) ? (in_array(3, $restoWork->getWorkDays()) ? null : 'disabled') : 'disabled'; ?>>
                        <span class="input-group-addon">
                          <span class="fas fa-clock fa-spin"></span>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <label>
                    <div class="checkbox checkbox-css">
                      <input class="work-time" type="checkbox" name="day-5" id="day-5" <?= is_array($restoWork->getWorkDays()) ? (in_array(5, $restoWork->getWorkDays()) ? 'checked' : null) : NULL; ?> />
                      <label for="day-5">Vendredi</label>
                    </div>
                  </label>
                  <div class="form-inline m-b-10" id="">
                    <div class="form-group">
                      <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                        <input type="text" name="day-5-open" value="<?= $restoWork->getWorkOpenTime(5) ?>" class="form-control time" placeholder="00:00" <?= is_array($restoWork->getWorkDays()) ? (in_array(5, $restoWork->getWorkDays()) ? null : 'disabled') : 'disabled'; ?>>
                        <span class="input-group-addon">
                          <span class="fas fa-clock fa-spin"></span>
                        </span>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                        <input type="text" name="day-5-close" value="<?= $restoWork->getWorkCloseTime(5) ?>" class="form-control time" placeholder="00:00" <?= is_array($restoWork->getWorkDays()) ? (in_array(5, $restoWork->getWorkDays()) ? null : 'disabled') : 'disabled'; ?>>
                        <span class="input-group-addon">
                          <span class="fas fa-clock fa-spin"></span>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <label>
                    <div class="checkbox checkbox-css">
                      <input class="work-time" type="checkbox" name="day-7" id="day-7" <?= is_array($restoWork->getWorkDays()) ? (in_array(7, $restoWork->getWorkDays()) ? 'checked' : null) : NULL; ?> />
                      <label for="day-7">Dimanche</label>
                    </div>
                  </label>
                  <div class="form-inline m-b-10" id="">
                    <div class="form-group">
                      <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                        <input type="text" name="day-7-open" value="<?= $restoWork->getWorkOpenTime(7) ?>" class="form-control time" placeholder="00:00" <?= is_array($restoWork->getWorkDays()) ? (in_array(7, $restoWork->getWorkDays()) ? null : 'disabled') : 'disabled'; ?>>
                        <span class="input-group-addon">
                          <span class="fas fa-clock fa-spin"></span>
                        </span>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                        <input type="text" name="day-7-close" value="<?= $restoWork->getWorkCloseTime(7) ?>" class="form-control time" placeholder="00:00" <?= is_array($restoWork->getWorkDays()) ? (in_array(7, $restoWork->getWorkDays()) ? null : 'disabled') : 'disabled'; ?>>
                        <span class="input-group-addon">
                          <span class="fas fa-clock fa-spin"></span>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="col">
                  <label>
                    <div class="checkbox checkbox-css">
                      <input class="work-time" type="checkbox" name="day-2" id="day-2" <?= is_array($restoWork->getWorkDays()) ? (in_array(2, $restoWork->getWorkDays()) ? 'checked' : null) : NULL; ?> />
                      <label for="day-2">Mardi</label>
                    </div>
                  </label>
                  <div class="form-inline m-b-10" id="">
                    <div class="form-group">
                      <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                        <input type="text" name="day-2-open" value="<?= $restoWork->getWorkOpenTime(2) ?>" class="form-control time" placeholder="00:00" <?= is_array($restoWork->getWorkDays()) ? (in_array(2, $restoWork->getWorkDays()) ? null : 'disabled') : 'disabled'; ?>>
                        <span class="input-group-addon">
                          <span class="fas fa-clock fa-spin"></span>
                        </span>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                        <input type="text" name="day-2-close" value="<?= $restoWork->getWorkCloseTime(2) ?>" class="form-control time" placeholder="00:00" <?= is_array($restoWork->getWorkDays()) ? (in_array(2, $restoWork->getWorkDays()) ? null : 'disabled') : 'disabled'; ?>>
                        <span class="input-group-addon">
                          <span class="fas fa-clock fa-spin"></span>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <label>
                    <div class="checkbox checkbox-css">
                      <input class="work-time" type="checkbox" name="day-4" id="day-4" <?= is_array($restoWork->getWorkDays()) ? (in_array(4, $restoWork->getWorkDays()) ? 'checked' : NULL) : NULL; ?> />
                      <label for="day-4">Jeudi</label>
                    </div>
                  </label>
                  <div class="form-inline m-b-10" id="">
                    <div class="form-group">
                      <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                        <input type="text" name="day-4-open" value="<?= $restoWork->getWorkOpenTime(4) ?>" class="form-control time" placeholder="00:00" <?= is_array($restoWork->getWorkDays()) ? (in_array(4, $restoWork->getWorkDays()) ? null : 'disabled') : 'disabled'; ?>>
                        <span class="input-group-addon">
                          <span class="fas fa-clock fa-spin"></span>
                        </span>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                        <input type="text" name="day-4-close" value="<?= $restoWork->getWorkCloseTime(4) ?>" class="form-control time" placeholder="00:00" <?= is_array($restoWork->getWorkDays()) ? (in_array(4, $restoWork->getWorkDays()) ? null : 'disabled') : 'disabled'; ?>>
                        <span class="input-group-addon">
                          <span class="fas fa-clock fa-spin"></span>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <label>
                    <div class="checkbox checkbox-css">
                      <input class="work-time" type="checkbox" name="day-6" id="day-6" <?= is_array($restoWork->getWorkDays()) ? (in_array(6, $restoWork->getWorkDays()) ? 'checked' : null) : NULL; ?> />
                      <label for="day-6">Samedi</label>
                    </div>
                  </label>
                  <div class="form-inline m-b-10">
                    <div class="form-group">
                      <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                        <input type="text" name="day-6-open" value="<?= $restoWork->getWorkOpenTime(6) ?>" class="form-control time" placeholder="00:00" <?= is_array($restoWork->getWorkDays()) ? (in_array(6, $restoWork->getWorkDays()) ? null : 'disabled') : 'disabled'; ?>>
                        <span class="input-group-addon">
                          <span class="fas fa-clock fa-spin"></span>
                        </span>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                        <input type="text" name="day-6-close" value="<?= $restoWork->getWorkCloseTime(6) ?>" class="form-control time" placeholder="00:00" <?= is_array($restoWork->getWorkDays()) ? (in_array(6, $restoWork->getWorkDays()) ? null : 'disabled') : 'disabled'; ?>>
                        <span class="input-group-addon">
                          <span class="fas fa-clock fa-spin"></span>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <hr>

            <div class="row">
              <div class="col-sm-5">
                <div class="col">
                  <?php $cities = (new City)->setStatus('1')->getAllByStatus(); ?>
                  <div class="form-group">
                    <label for="selectInput07">Ville <small class="text-danger" title="champ obligatoire">*</small></label>

                    <select id="selectInput07" name="restaurant-city" class="default-select2 form-control" required>
                      <option value="" selected disabled> Sélectionnez une Ville </option>
                      <?php
                        foreach ($cities as $city) {
                          ?>
                        <option value="<?= $city->getId() ?>" <?= ($address->getCityId() == $city->getId()) ? 'selected' : NULL ?>><?= $city->getCityName() ?></option>
                      <?php
                        }
                        ?>
                    </select>
                    <div class="invalid-feedback">Ce champ est obligatoire.</div>
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="textareaInput2">Adresse du restaurant</label>
                    <textarea class="form-control" name="restaurant-address" id="textareaInput2" placeholder="Adresse du restaurant / Zone Rue ..." style="margin-top: 0px; margin-bottom: 0px; height: 150px;"><?= $address->getAddress() ?></textarea>
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="latitudeR">Latitude <small class="text-danger" title="champ obligatoire">*</small></label>
                    <input type="text" class="form-control" id="latitudeR" name="restaurant-latitude" value="<?= $address->getLatitude() ?>" placeholder="Latitude du position du Resto" required>
                    <div class="invalid-feedback">Ce champ est obligatoire.</div>
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="longitudeR">Longitude <small class="text-danger" title="champ obligatoire">*</small></label>
                    <input type="text" class="form-control" id="longitudeR" name="restaurant-longitude" value="<?= $address->getLongtitude() ?>" placeholder="Longitude du position du Resto" required>
                    <div class="invalid-feedback">Ce champ est obligatoire.</div>
                  </div>
                </div>
              </div>
              <div class="col-sm-7">
                <div class="img-thumbnail" id="map" style="width:100%;height:400px;"></div>
              </div>
            </div>
            <div class="btn-toolbar sw-toolbar sw-toolbar-bottom justify-content-end">
              <div class="btn-group mr-2 sw-btn-group" role="group">
                <button class="btn btn-default" type="button" id="return-user-form"><i class="fas fa-reply"></i> Annuler</button>
                <button class="btn btn-success" type="submit" id="save-user">Enregistrer</button>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>
  <?php } ?>