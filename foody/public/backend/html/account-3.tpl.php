<?php
$user = (new Delivery)
    ->setUid(Session::get('user_id'))
    ->getUserByUid();
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

                <form id="account-form" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="col">
                                <div class="form-group">
                                    <label for="textInput1">Nom complet <small class="text-danger" title="champ obligatoire">*</small></label>
                                    <input type="text" class="form-control" id="textInput1" name="full_name" placeholder="Nom & Prénom" value="<?= $user->getFullName() ?>" required>
                                    <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="textInput1">Date de naissance <small class="text-danger" title="champ obligatoire">*</small></label>
                                    <div class="input-group date" data-date-start-date="Date.default">
                                        <input type="text" name="birth_date" class="form-control" id="datepicker-birthdate" autocomplete="off" value="<?= $user->getBirthDate() ?>" placeholder="Select Date" />
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>
                                    <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="textInput3">Phone <small class="text-danger" title="champ obligatoire">*</small></label>
                                    <input type="text" class="form-control" name="phone" id="textInput3" value="<?= $user->getPhone() ?>" placeholder="Numéro de téléphone" value="<?= $user->getPhone() ?>" required>
                                    <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="col">
                                <div class="form-group">
                                    <label for="textInput4">Email <small class="text-danger" title="champ obligatoire">*</small></label>
                                    <input type="email" class="form-control" id="textInput4" name="email" value="<?= $user->getEmail() ?>" placeholder="foulen@example.com" required>
                                    <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="selectInput1">Vehicule <small class="text-danger" title="champ obligatoire">*</small></label>
                                    <select id="selectInput1" name="vehicle_id" class="form-control" required>
                                        <option value="" selected disabled> Sélectionnez une vehicule </option>
                                        <?php
                                        $vehicles = (new Vehicle)->getAll();
                                        foreach ($vehicles as $vehicle) {
                                            ?>
                                        <option value="<?= $vehicle->getId() ?>" <?= ($user->getVehicleId() == $vehicle->getId()) ? 'selected' : NULL ?>><?= $vehicle->getVehicle() ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                </div>
                            </div>
                            <div class="col">
                                <label for="switcher_checkbox_avaible">Disponibilité</label>
                                <div class="form-group row m-b-10">
                                    <div class="col-md-2 p-t-3">
                                        <div class="switcher switcher-info">
                                            <input type="checkbox" name="availability" id="switcher_checkbox_avaible" <?= ($user->getAvailability()) ? 'value="1" checked' : 'value="0"' ?>>
                                            <label for="switcher_checkbox_avaible"></label>
                                        </div>
                                    </div>
                                    <label class="col-md-5 col-form-label">
                                        <h5><span id="switcher_label_avaible" <?= ($user->getAvailability()) ? 'class="label label-info">Livreur Disponible &nbsp;<i class="fas fa-hourglass-end fa-pulse"></i>' : 'class="label label-warning"> Livreur Indisponible <i class="fas fa-hourglass"></i>' ?> </span> </h5> </label> </div> </div> </div> </div> <hr>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="col">
                                                            <div class="form-group m-b-20">
                                                                <label for="textInput01">Mot de passe actuel </label>
                                                                <div class="input-group m-b-10">
                                                                    <input type="password" class="form-control" id="textInput01" autocomplete="off" name="old-pwd" placeholder="ancien mot de passe">
                                                                    <span class="input-group-addon pwd-show"><i class="fa fa-eye-slash"></i></span>
                                                                    <div class="invalid-tooltip">Mot de passe incorrect</div>
                                                                    <div class="valid-tooltip"></div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <div class="col">
                                                            <div class="form-group m-b-20">
                                                                <label for="textInput02">Nouveau mot de passe </label>
                                                                <div class="input-group m-b-10">
                                                                    <input type="password" class="form-control" id="textInput02" autocomplete="off" name="new-pwd" placeholder="******">
                                                                    <span class="input-group-addon pwd-show"><i class="fa fa-eye-slash"></i></span>
                                                                    <div class="invalid-tooltip">Ce champ devient obligatoire</div>
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
                                                                    $address = (new Address)
                                                                            ->setUserAuth(Session::get('user_auth'))
                                                                            ->setUserId(Session::get('user_id'))
                                                                            ->getElementByUserId();
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
                                                                <label for="textareaInput2">Adresse Complete</label>
                                                                <textarea class="form-control" name="address" id="textareaInput2" placeholder="Adresse du restaurant / Zone Rue ..." style="margin-top: 0px; margin-bottom: 0px; height: 150px;"><?= $address->getAddress() ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label for="latitudeR">Latitude <small class="text-danger" title="champ obligatoire">*</small></label>
                                                                <input type="text" class="form-control" id="latitudeR" name="latitude" value="<?= $address->getLatitude() ?>" placeholder="Latitude du position du Resto" required>
                                                                <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label for="longitudeR">Longitude <small class="text-danger" title="champ obligatoire">*</small></label>
                                                                <input type="text" class="form-control" id="longitudeR" name="longitude" value="<?= $address->getLongtitude() ?>" placeholder="Longitude du position du Resto" required>
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
                                                    <button type="submit" id="s-account" class="btn btn-green">Enregistrer</button>
                                                </p>

                </form>
            </div>
            <!-- end tab-pane -->
            <!-- begin tab-pane -->
            <div class="tab-pane fade" id="tab-2">
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
                                <option value="" selected disabled> Sélectionnez un sujet </option>
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