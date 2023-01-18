
    <section class="account-page section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 mx-auto">
                    <div class="row no-gutters">
                        <?php $this->requireTPL('p-profile-menu', PATH_PUBLIC); ?>
                        <div class="col-md-8">
                            <div class="card card-body account-right">
                                <div class="widget">
                                    <div class="section-header">
                                        <h5 class="heading-design-h5">
                                            Mon Profil
                                        </h5>
                                    </div>
                                    <form class="">
                                        <div class="row justify-content-center">
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <label class="control-label">Nom et Prénom <span class="required text-danger">*</span></label>
                                                    <input class="form-control border-form-control" value="" placeholder="Gurdeep" type="text">
                                                </div>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <label class="control-label">Phone <span class="required text-danger">*</span></label>
                                                    <input class="form-control border-form-control" value="" placeholder="123 456 7890" type="number">
                                                </div>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <label class="control-label">Email </label>
                                                    <input class="form-control border-form-control " value="" placeholder="iamosahan@gmail.com" disabled="" type="email">
                                                </div>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <label class="control-label">Mot de passe actuel <span class="required  text-warning">*</span></label>
                                                    <div class="input-group mb-2" id="show-hide-password">
                                                        <input type="password" name="" class="form-control border-form-control" placeholder="********">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-default show-pwd"><i class="mdi mdi-eye-off"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <label class="control-label">Nouveau mot de passe <span class="required  text-warning">*</span></label>
                                                    <div class="input-group mb-2" id="show-hide-password">
                                                        <input type="password" name="" class="form-control border-form-control" placeholder="********">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-default show-pwd"><i class="mdi mdi-eye-off"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <button type="button" class="btn btn-danger btn-lg"> Annuler </button>
                                                <button type="button" class="btn btn-success btn-lg"> Save Changes </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>