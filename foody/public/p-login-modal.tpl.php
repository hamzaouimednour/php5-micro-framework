    <div class="modal fade login-modal-main" id="bd-login-modal" data-keyboard="false" data-backdrop="static">
        <span id="page-redirect" data-redirect=""></span>
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="login-modal">
                        <div class="row">
                            <div class="col-lg-6 pad-right-0">
                                <div class="login-modal-left">
                                </div>
                            </div>
                            <div class="col-lg-6 pad-left-0">
                                <button type="button" class="close close-top-right" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"><i class="mdi mdi-close"></i></span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <form id="form-user" autocomplete="off" class="needs-validation" novalidate>
                                    <div class="login-modal-right">
                                        <!-- Tab panes -->
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="login" role="tabpanel">
                                                <h5 class="heading-design-h5">Connectez-vous à votre compte</h5>
                                                <div id="login-note-section"></div>
                                                <div id="login-note-section"></div>
                                                <fieldset class="form-group">
                                                    <label> Email / Numéro de Téléphone</label>
                                                    <input type="text" name="signin-email" class="form-control" placeholder="user@example.com" required>
                                                    <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                                </fieldset>
                                                <fieldset class="form-group">
                                                    <label>Mot de passe</label>
                                                    <input type="password" name="signin-pass" class="form-control" placeholder="********" required>
                                                    <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                                </fieldset>
                                                <fieldset class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="remember_me" class="custom-control-input" id="remember_me_checkbox">
                                                        <label class="custom-control-label" for="remember_me_checkbox">Rester connecté ?</label>
                                                    </div>
                                                </fieldset>
                                                <fieldset class="form-group">
                                                    <button type="submit" id="signin-btn" class="btn btn-lg btn-secondary btn-block"><i class="mdi mdi-login"></i> CONNECTER</button>
                                                </fieldset>
                                                <div class="login-with-sites text-center">
                                                    <p>ou Connectez-vous avec votre profil social:</p>
                                                    <a href="#" class="btn-facebook login-icons btn-lg"><i class="mdi mdi-facebook"></i> Facebook</a>
                                                    <a href="#" class="btn-google login-icons btn-lg"><i class="mdi mdi-google"></i> Google</a>
                                                    <!-- <button class="btn-twitter login-icons btn-lg"><i class="mdi mdi-twitter"></i> Twitter</button> -->
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="register" role="tabpanel">

                                                <h5 class="heading-design-h5">Inscrire maintenant!</h5>
                                                <div id="register-note-section"></div>
                                                <fieldset class="form-group">
                                                    <label>Nom et Prénom *</label>
                                                    <input type="text" name="signup-name" class="form-control" placeholder="nom et prénom" pattern="[a-zA-Z ]+" required>
                                                    <div class="invalid-feedback">Nom et Prénom invalide (éviter les caractères spéciaux).</div>
                                                </fieldset>
                                                <fieldset class="form-group">
                                                    <label>Email *</label>
                                                    <input type="email" name="signup-email" class="form-control" placeholder="user@example.com" required>
                                                    <div class="invalid-feedback">Email invalide.</div>
                                                </fieldset>
                                                <fieldset class="form-group">
                                                    <label>Numéro de Téléphone *</label>
                                                    <input type="text" name="signup-phone" class="form-control" placeholder="12 345 678" onpaste="off" onkeypress="return event.charCode >= 48 && event.charCode <= 57" pattern="[2,3,4,5,9]{1}[0-9]{7}" minlength="8" maxlength="8" required>
                                                    <div class="invalid-feedback">Numéro de Téléphone invalide.</div>
                                                </fieldset>
                                                <fieldset class="form-group">
                                                    <label>Mot de passe *</label>
                                                    <div class="input-group mb-2" id="show-hide-password">
                                                        <input type="password" name="signup-pass" class="form-control" placeholder="********" minlength="6" required>
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-default" id="show-password"><i class="mdi mdi-eye-off"></i></button>
                                                        </div>
                                                        <div class="invalid-feedback">Ce champ est obligatoire (6 characters minimum).</div>
                                                    </div>
                                                </fieldset>
                                                <fieldset class="form-group">
                                                    <button type="submit" id="signup-btn" class="btn btn-lg btn-secondary btn-block"><i class="mdi mdi-checkbox-marked-circle"></i> Créez votre compte</button>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="text-center login-footer-tab">
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-toggle="tab" href="#login" role="tab" id="login-link"><i class="mdi mdi-lock"></i> LOGIN</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#register" role="tab" id="register-link"><i class="mdi mdi-pencil"></i> REGISTER</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>