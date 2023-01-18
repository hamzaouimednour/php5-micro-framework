<?php
$dataUser = (new Restaurant)
    ->setUid(Session::get('user_id'))
    ->getUserByUid();
$restoWork = (new RestaurantWork)->setRestaurantId(Session::get('user_id'))->getElementByRestaurantId();
$address = (new Address)
    ->setUserAuth(Session::get('user_auth'))
    ->setUserId(Session::get('user_id'))
    ->getElementByUserId();
$myDelivery = NULL;
?>

<div class="row justify-content-center">
    <div class="col-lg-11">
        <!-- begin nav-tabs -->
        <ul class="nav nav-tabs">
            <li class="nav-items">
                <a href="#tab-1" data-toggle="tab" class="nav-link show active">
                    <span class="d-sm-none"><i class="fas fa-user-circle"></i></span>
                    <span class="d-sm-block d-none"><i class="fas fa-user-circle"></i> &nbsp;Profile</span>
                </a>
            </li>
            <li class="nav-items">
                <a href="#tab-2" data-toggle="tab" class="nav-link show">
                    <span class="d-sm-none"><i class="fas fa-cogs"></i></span>
                    <span class="d-sm-block d-none"><i class="fas fa-image"></i> &nbsp;Photo de couverture</span>
                </a>
            </li>
            <li class="nav-items">
                <a href="#tab-3" data-toggle="tab" class="nav-link show">
                    <span class="d-sm-none"><i class="fas fa-cogs"></i></span>
                    <span class="d-sm-block d-none"><i class="fas fa-cogs"></i> &nbsp;Support & Gestion de compte</span>
                </a>
            </li>
        </ul>
        <!-- end nav-tabs -->
        <!-- begin tab-content -->
        <div class="tab-content">
            <!-- begin tab-pane -->
            <div class="tab-pane fade active show" id="tab-1">
                <div class="m-b-20"></div>

                <div id="note-msg"></div>

                <form id="resto-account-form" class="needs-validation" autocomplete="off" novalidate>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="col">
                                <div class="form-group">
                                    <label for="textInput1">Nom & Prénom du responsable <small class="text-danger" title="champ obligatoire">*</small></label>
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
                                            $RestaurantSpecialties = (new RestaurantSpecialties)->setRestaurantId(Session::get('user_id'))->getAllByRestaurantId();
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
                    <hr>
                    <div class="row">

                        <div class="col">
                            <div class="form-group m-b-20">
                                <label for="textInput010">Mot de passe actuel </label>
                                <div class="input-group m-b-10">
                                    <input type="password" class="form-control" id="textInput010" autocomplete="off" name="old-pwd" placeholder="ancien mot de passe">
                                    <span class="input-group-addon pwd-show"><i class="fa fa-eye-slash"></i></span>
                                    <div class="invalid-tooltip">Mot de passe incorrect</div>
                                    <div class="valid-tooltip"></div>
                                </div>

                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group m-b-20">
                                <label for="textInput020">Nouveau mot de passe </label>
                                <div class="input-group m-b-10">
                                    <input type="password" class="form-control" id="textInput020" autocomplete="off" name="new-pwd" placeholder="******">
                                    <span class="input-group-addon pwd-show"><i class="fa fa-eye-slash"></i></span>
                                    <div class="invalid-tooltip">Ce champ devient obligatoire</div>
                                </div>
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
                                        <input type="text" class="form-control" id="textInput001" name="delivery-fee" onpaste="return false;" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="frais de livraison" value="<?= ($restoWork->getDeliveryType() == 1) ? Handler::reverse_currency_format($restoWork->getDeliveryFee()) : NULL ?>" <?= ($restoWork->getDeliveryType() == 1) ? 'required' : NULL ?>>
                                        <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="form-group">
                                        <label for="textInput002">Distance Extra <small> ( Toujour = <b>1 KM </b>)</small></label>
                                        <input type="text" class="form-control" id="textInput002"  name="up-distance" value="1" disabled>
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
                                    <input type="text" class="form-control" id="textInput01" name="restaurant-min_delivery" onpaste="return false;" value="<?= Handler::reverse_currency_format($restoWork->getMinDelivery()) ?>" onpaste="return false;" min="0" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" placeholder="Prix minimal du Commande" required>
                                    <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="textInput02">Prix Maximal du Commande <small class="text-danger" title="champ obligatoire">*</small></label>
                                    <input type="text" class="form-control" id="textInput02" name="restaurant-max_delivery" onpaste="return false;" value="<?= Handler::reverse_currency_format($restoWork->getMaxDelivery()) ?>" onpaste="return false;" min="0" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" placeholder="Prix maximal du Commande" required>
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
                                    <input type="text" class="form-control" id="textInput04" name="restaurant-preparation_time_max" onpaste="return false;" value="<?= $restoWork->getPreparationTimeMax() ?>" onpaste="return false;" min="0" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" placeholder="Prix maximal du Commande" required>
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
                                        <input class="work-time" type="checkbox" name="day-1" id="day-1" <?= (in_array(1, $restoWork->getWorkDays())) ? 'checked' : null; ?> />
                                        <label for="day-1">Lundi</label>
                                    </div>
                                </label>
                                <div class="form-inline m-b-10">
                                    <div class="form-group">
                                        <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                                            <input type="text" name="day-1-open" value="<?= $restoWork->getWorkOpenTime(1) ?>" class="form-control time" placeholder="00:00" <?= (in_array(1, $restoWork->getWorkDays())) ? null : 'disabled'; ?>>
                                            <span class="input-group-addon">
                                                <span class="fas fa-clock fa-spin"></span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                                            <input type="text" name="day-1-close" value="<?= $restoWork->getWorkCloseTime(1) ?>" class="form-control time" placeholder="00:00" <?= (in_array(1, $restoWork->getWorkDays())) ? null : 'disabled'; ?>>
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
                                        <input class="work-time" type="checkbox" name="day-3" id="day-3" <?= (in_array(3, $restoWork->getWorkDays())) ? 'checked' : null; ?> />
                                        <label for="day-3">Mercredi</label>
                                    </div>
                                </label>
                                <div class="form-inline m-b-10">
                                    <div class="form-group">
                                        <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                                            <input type="text" name="day-3-open" value="<?= $restoWork->getWorkOpenTime(3) ?>" class="form-control time" placeholder="00:00" <?= (in_array(3, $restoWork->getWorkDays())) ? null : 'disabled'; ?>>
                                            <span class="input-group-addon">
                                                <span class="fas fa-clock fa-spin"></span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                                            <input type="text" name="day-3-close" value="<?= $restoWork->getWorkCloseTime(3) ?>" class="form-control time" placeholder="00:00" <?= (in_array(3, $restoWork->getWorkDays())) ? null : 'disabled'; ?>>
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
                                        <input class="work-time" type="checkbox" name="day-5" id="day-5" <?= (in_array(5, $restoWork->getWorkDays())) ? 'checked' : null; ?> />
                                        <label for="day-5">Vendredi</label>
                                    </div>
                                </label>
                                <div class="form-inline m-b-10" id="">
                                    <div class="form-group">
                                        <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                                            <input type="text" name="day-5-open" value="<?= $restoWork->getWorkOpenTime(5) ?>" class="form-control time" placeholder="00:00" <?= (in_array(5, $restoWork->getWorkDays())) ? null : 'disabled'; ?>>
                                            <span class="input-group-addon">
                                                <span class="fas fa-clock fa-spin"></span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                                            <input type="text" name="day-5-close" value="<?= $restoWork->getWorkCloseTime(5) ?>" class="form-control time" placeholder="00:00" <?= (in_array(5, $restoWork->getWorkDays())) ? null : 'disabled'; ?>>
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
                                        <input class="work-time" type="checkbox" name="day-7" id="day-7" <?= (in_array(7, $restoWork->getWorkDays())) ? 'checked' : null; ?> />
                                        <label for="day-7">Dimanche</label>
                                    </div>
                                </label>
                                <div class="form-inline m-b-10" id="">
                                    <div class="form-group">
                                        <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                                            <input type="text" name="day-7-open" value="<?= $restoWork->getWorkOpenTime(7) ?>" class="form-control time" placeholder="00:00" <?= (in_array(7, $restoWork->getWorkDays())) ? null : 'disabled'; ?>>
                                            <span class="input-group-addon">
                                                <span class="fas fa-clock fa-spin"></span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                                            <input type="text" name="day-7-close" value="<?= $restoWork->getWorkCloseTime(7) ?>" class="form-control time" placeholder="00:00" <?= (in_array(7, $restoWork->getWorkDays())) ? null : 'disabled'; ?>>
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
                                        <input class="work-time" type="checkbox" name="day-2" id="day-2" <?= (in_array(2, $restoWork->getWorkDays())) ? 'checked' : null; ?> />
                                        <label for="day-2">Mardi</label>
                                    </div>
                                </label>
                                <div class="form-inline m-b-10" id="">
                                    <div class="form-group">
                                        <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                                            <input type="text" name="day-2-open" value="<?= $restoWork->getWorkOpenTime(2) ?>" class="form-control time" placeholder="00:00" <?= (in_array(2, $restoWork->getWorkDays())) ? null : 'disabled'; ?>>
                                            <span class="input-group-addon">
                                                <span class="fas fa-clock fa-spin"></span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                                            <input type="text" name="day-2-close" value="<?= $restoWork->getWorkCloseTime(2) ?>" class="form-control time" placeholder="00:00" <?= (in_array(2, $restoWork->getWorkDays())) ? null : 'disabled'; ?>>
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
                                        <input class="work-time" type="checkbox" name="day-4" id="day-4" <?= (in_array(4, $restoWork->getWorkDays())) ? 'checked' : null; ?> />
                                        <label for="day-4">Jeudi</label>
                                    </div>
                                </label>
                                <div class="form-inline m-b-10" id="">
                                    <div class="form-group">
                                        <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                                            <input type="text" name="day-4-open" value="<?= $restoWork->getWorkOpenTime(4) ?>" class="form-control time" placeholder="00:00" <?= (in_array(4, $restoWork->getWorkDays())) ? null : 'disabled'; ?>>
                                            <span class="input-group-addon">
                                                <span class="fas fa-clock fa-spin"></span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                                            <input type="text" name="day-4-close" value="<?= $restoWork->getWorkCloseTime(4) ?>" class="form-control time" placeholder="00:00" <?= (in_array(4, $restoWork->getWorkDays())) ? null : 'disabled'; ?>>
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
                                        <input class="work-time" type="checkbox" name="day-6" id="day-6" <?= (in_array(6, $restoWork->getWorkDays())) ? 'checked' : null; ?> />
                                        <label for="day-6">Samedi</label>
                                    </div>
                                </label>
                                <div class="form-inline m-b-10">
                                    <div class="form-group">
                                        <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                                            <input type="text" name="day-6-open" value="<?= $restoWork->getWorkOpenTime(6) ?>" class="form-control time" placeholder="00:00" <?= (in_array(6, $restoWork->getWorkDays())) ? null : 'disabled'; ?>>
                                            <span class="input-group-addon">
                                                <span class="fas fa-clock fa-spin"></span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group clockpicker m-r-10" data-placement="right" data-align="top" data-autoclose="true">
                                            <input type="text" name="day-6-close" value="<?= $restoWork->getWorkCloseTime(6) ?>" class="form-control time" placeholder="00:00" <?= (in_array(6, $restoWork->getWorkDays())) ? null : 'disabled'; ?>>
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

                                    <select id="selectInput07" name="city" class="default-select2 form-control" required>
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
                                    <textarea class="form-control" name="address" id="textareaInput2" placeholder="Adresse du restaurant / Zone Rue ..." style="margin-top: 0px; margin-bottom: 0px; height: 150px;"><?= $address->getAddress() ?></textarea>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="latitudeR">Latitude </label>
                                    <input type="text" class="form-control" id="latitudeR" name="latitude" value="<?= $address->getLatitude() ?>" placeholder="Latitude du position du Resto" style="pointer-events: none;" required>
                                    <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="longitudeR">Longitude </label>
                                    <input type="text" class="form-control" id="longitudeR" name="longitude" value="<?= $address->getLongtitude() ?>" placeholder="Longitude du position du Resto" style="pointer-events: none;" required>
                                    <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="img-thumbnail" id="map" style="width:100%;height:400px;"></div>
                        </div>
                    </div>

                    <p class="text-right m-b-10 m-t-10">
                        <button type="button" onclick="window.history.back();" class="btn btn-default m-r-5">Retour</button>
                        <button type="submit" id="s-account" class="btn btn-green"><i class="fas fa-save"></i> &nbsp;Enregistrer</button>
                    </p>

                </form>
            </div>
            <!-- end tab-pane -->
            <!-- begin tab-pane -->
            <div class="tab-pane fade" id="tab-2">
                <div class="col-sm form-group">
                    <div id="cover-msg"></div>
                    <?php
                    if(!empty($dataUser->getCoverPhoto())){
                        echo '<p class="m-t-20 text-center"><img src="'.Handler::getCdnCoverImage(Session::get('user_id'), "thumb-" . $dataUser->getCoverPhoto()).'" class="img-thumbnail" alt=""></p><hr>';
                    }
                    ?>
                    <label for="exampleInputEmail1">Photo de couverture du restaurant <small>( doit étre une photo HD )</small></label>

                    <div class="dropzone needsclick" id="cover_dropzone">
                        <div class="dz-message needsclick">
                            Drag & drop files <b>here</b> or <b>click</b> to upload.<br />
                            <span class="dz-note needsclick">
                                ( allowed File Extensions are <strong>jpeg</strong>, <strong>jpg</strong>, <strong>png</strong>, <strong>gif</strong>.)
                            </span>
                        </div>
                    </div>
                    <p class="text-right m-b-10 mt-5">
                        <button type="button" onclick="window.history.back();" class="btn btn-default m-r-5">Retour</button>
                        <button type="button" id="s-cover" class="btn btn-green">Enregistrer</button>
                    </p>
                </div>
            </div>
            
            <!-- end tab-pane -->
            <!-- begin tab-pane -->
            <div class="tab-pane fade" id="tab-3">
                <div class="note note-secondary m-b-15">
                    <h4><b>Envoyer une demande!</b></h4>
                    <p>
                        Si vous trouvez des erreurs ou des problèmes, choisissez simplement l’un des thèmes et écrivez une petite description décrivant votre demande.
                    </p>
                </div>
                <div class="m-b-20"></div>
                <div id="note-msg-2"></div>
    
                <form id="request-form" class="needs-validation" novalidate>
                    <div class="justify-content-center form-group row">
                        <div class="col-md-6">
                            <select name="request" class="form-control selectpicker" data-style="btn-white" required>
                                <option value="" selected disabled> Sélectionnez un thème </option>
                                <option value="Account Deletion"> Suppression du compte </option>
                                <option value="Help & Support"> Aide & Support </option>
                                <option value="Technical Issue"> Problème Technique </option>
                                <option value="Other"> Autre </option>
                            </select>
                        </div>
                    </div>
                    <div class="justify-content-center form-group row">
                        <div class="col-md-6">
                            <textarea name="description" class="form-control" placeholder="votre description / raisons ..." style="margin-top: 0px; margin-bottom: 0px; height: 241px;"></textarea>
                        </div>
                    </div>
                    <div class="justify-content-center form-group row">
                        <button type="submit" class="btn btn-primary" style="width:150px;"> <i class="fas fa-paper-plane"></i>&nbsp; Envoyer</button>
                    </div>
                </form>
            </div>
            <!-- end tab-pane -->
        </div>
        <!-- end tab-content -->
    </div>
</div>