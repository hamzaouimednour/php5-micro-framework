<?php
$user = (new Administrator)
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
                                    <input type="text" class="form-control" id="textInput1" name="full_name" value="<?= $user->getFullName() ?>" placeholder="Nom & Prénom" required>
                                    <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Username </label>
                                    <input type="text" class="form-control" value="<?= $user->getUserName() ?>" placeholder="username" disabled>
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
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="col">
                                <div class="form-group m-b-20">
                                    <label for="textInput01">Mot de passe actuel </label>
                                    <div class="input-group m-b-10">
                                        <input type="password" class="form-control" id="textInput01" name="old-pwd" placeholder="ancien mot de passe">
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
                                        <input type="password" class="form-control" id="textInput02" name="new-pwd" placeholder="******">
                                        <span class="input-group-addon pwd-show"><i class="fa fa-eye-slash"></i></span>
                                        <div class="invalid-tooltip">Ce champ devient obligatoire</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <p class="text-right m-b-10 m-t-10">
                        <button type="button" onclick="window.history.back();" class="btn btn-default m-r-5">Retour</button>
                        <button type="submit" id="s-account" class="btn btn-green">Enregistrer</button>
                    </p>

                </form>
            </div>
            <!-- end tab-pane -->
        </div>
        <!-- end tab-content -->
    </div>
</div>