<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<head>
    <meta charset="utf-8" />
    <title>Partnership with foody</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta name="base-html" content="<?= HTML_PATH_ROOT ?>">
    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <?= $this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/bootstrap/css/bootstrap.min.css"); ?>
    <?= $this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/font-awesome/css/all.min.css"); ?>
    <?= $this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/animate/animate.min.css"); ?>
    <?= $this->appendCSS(HTML_PATH_PUBLIC . "assets/css/default/style.min.css"); ?>
    <?= $this->appendCSS(HTML_PATH_PUBLIC . "assets/css/default/style-responsive.min.css"); ?>
    <?= $this->appendCSS(HTML_PATH_PUBLIC . "assets/css/default/theme/default.css", 'id="theme"'); ?>
    <?= $this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/select2/dist/css/select2.min.css"); ?>
    <?= $this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/ionicons/css/ionicons.min.css"); ?>

    <!-- ================== END BASE CSS STYLE ================== -->

    <!-- ================== BEGIN BASE JS ================== -->
    <?= $this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/pace/pace.min.js"); ?>
    <?= $this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/jquery/jquery-3.3.1.min.js"); ?>
    <!-- ================== END BASE JS ================== -->
</head>

<body class="bg-silver">

    <!-- BEGIN #my-account -->
    <div class="section-container">
        <!-- BEGIN container -->
        <div class="container">

            <!-- BEGIN account-container -->
            <div class="account-container">
                <!-- BEGIN account-sidebar -->
                <div class="account-sidebar">
                    <div class="account-sidebar-cover">
                        <img src="<?=HTML_PATH_PUBLIC . 'img/slider/slider-9.jpeg'?>" alt="" />
                    </div>
                    <div class="account-sidebar-content">
                        <h4 class="text-white">Devenir Partenaire</h4>
                        <p>
                        Accédez à de nouveaux clients et augmentez vos ventes avec le réseau de distribution à la croissance la plus rapide aux Tunisie.
                        </p>
                        <b>
                        Plus d'affaires, moins d'effort
                        </b>
                    </div>
                </div>
                <!-- END account-sidebar -->
                <!-- BEGIN account-body -->
                <div class="account-body">
                    <!-- BEGIN row -->
                    <div class="row">
                        <!-- BEGIN col-6 -->
                        <div class="col-md-12 mt-5">
                            <div id="info-section"></div>
                            <form id="restaurant-form" class="needs-validation" novalidate>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="textInput1">Nom complet du responsable <small class="text-danger" title="champ obligatoire">*</small></label>
                                                <input type="text" class="form-control" id="textInput1" name="restaurant-full_name" placeholder="Nom & Prénom" required>
                                                <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="form-group">
                                                <label for="textInput2">Nom du restaurant <small class="text-danger" title="champ obligatoire">*</small></label>
                                                <input type="text" class="form-control" id="textInput2" name="restaurant-restaurant_name" placeholder="Nom du Resto" required>
                                                <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="selectInput1">Livraison <small class="text-danger" title="champ obligatoire">*</small></label>
                                                <select id="selectInput1" name="restaurant-delivery_type" class="form-control select2" required>
                                                    <option value="" selected>Select choice</option>
                                                    <option value="1">Votre propre service de livraison</option>
                                                    <option value="2">Nos service de livraison</option>
                                                </select>
                                                <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-sm-6">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="textInput3">Email <small class="text-danger" title="champ obligatoire">*</small></label>
                                                <input type="email" class="form-control" id="textInput3" name="restaurant-email" placeholder="foulen@example.com" required>
                                                <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="selectInput2">Spécialités <small class="text-danger" title="champ obligatoire">*</small></label>
                                                <select id="selectInput2" name="restaurant-specialties[]" class="form-control select2" multiple="multiple" data-placeholder="Sélectionnez la/les spécialité(s)" required>
                                                    <optgroup label="Sélectionnez la/les spécialité(s)">
                                                        <?php
                                                        $specialties = (new Specialties)->getAll();
                                                        foreach ($specialties as $specialty) {
                                                            ?>
                                                            <option value="<?= $specialty->getId() ?>"><?= $specialty->getSpecialty() ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </optgroup>
                                                </select>
                                                <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="form-group">
                                                <label for="textInput4">Phone <small class="text-danger" title="champ obligatoire">*</small></label>
                                                <input type="text" class="form-control" name="restaurant-phone" id="textInput4" onpaste="off" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="8" maxlength="8" placeholder="Numéro de téléphone" required>
                                                <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="col">
                                            <?php $cities = (new City)->setStatus('1')->getAllByStatus(); ?>
                                            <div class="form-group">
                                                <label for="cityInput">Ville <small class="text-danger" title="champ obligatoire">*</small></label>

                                                <select id="cityInput" name="restaurant-city" class="select2 form-control" required>
                                                    <option value="" selected disabled> Sélectionnez une Ville </option>
                                                    <?php
                                                    foreach ($cities as $city) {
                                                        ?>
                                                        <option value="<?= $city->getId() ?>" data-lat="<?= $city->getLat() ?>" data-lng="<?= $city->getLng() ?>"><?= $city->getCityName() ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                                <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="textareaInput1">Adresse du restaurant <small class="text-danger" title="champ obligatoire">*</small></label>
                                                <textarea class="form-control" name="restaurant-address" id="textareaInput1" placeholder="Adresse du restaurant / Zone Rue ..." style="margin-top: 0px; margin-bottom: 0px; height: 150px;" required></textarea>
                                                <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="">Positionner l'adresse du restaurant sur le Map <small class="text-danger" title="champ obligatoire">*</small></label>
                                            <input type="hidden" name="latitudeR" required>
                                            <input class="form-control" type="text" style="opacity: 0;width: 0;height:0;" name="longitudeR" required>
                                            <div class="invalid-feedback mb-2">Ce champ est obligatoire.</div>
                                            <div class="img-thumbnail" id="map" style="width:100%;height:400px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="btn-toolbar sw-toolbar sw-toolbar-bottom justify-content-end mt-4 mb-2">
                                    <div class="btn-group mr-2 sw-btn-group" role="group">
                                        <button class="btn btn-default" type="button" id="cancel"><i class="fas fa-reply"></i> Annuler</button>
                                        <button class="btn btn-success" type="submit" id="save"><i class="fas fa-paper-plane"></i> Envoyer</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- END col-6 -->
                    </div>
                    <!-- END row -->
                </div>
                <!-- END account-body -->
            </div>
            <!-- END account-container -->
        </div>
        <!-- END container -->
    </div>
    <!-- END #about-us-cover -->
    </div>
    <!-- END #page-container -->



    <!-- ================== BEGIN BASE JS ================== -->
    <?= $this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/bootstrap/js/bootstrap.bundle.min.js"); ?>
    <!--[if lt IE 9]>
		<script src="assets/crossbrowserjs/html5shiv.js"></script>
		<script src="assets/crossbrowserjs/respond.min.js"></script>
		<script src="assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
    <?= $this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/js-cookie/js.cookie.js"); ?>
    <?= $this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/paroller/jquery.paroller.min.js"); ?>
    <?= $this->appendJS(HTML_PATH_PUBLIC . "assets/js/special/apps.min.js"); ?>
    <?= $this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/bootstrap-combobox/js/bootstrap-combobox.js"); ?>
    <?= $this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/select2/dist/js/select2.min.js"); ?>
    <?= $this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/bootstrap-sweetalert/sweetalert.min.js"); ?>
    <?= $this->appendJS(HTML_PATH_PUBLIC . "assets/js/demo/form-plugins.demo.js"); ?>
    <?= $this->appendJS(HTML_PATH_PUBLIC . 'assets/js/special/script.js?' . time()); ?>
    <script async defer src="https://maps.googleapis.com/maps/api/js?language=fr-fr&key=AIzaSyCkG1aDqrbOk28PmyKjejDwWZhwEeLVJbA&callback=initMap"></script>
    <!-- ================== END BASE JS ================== -->

    <script>
        $(document).ready(function() {
            App.init();
            $(".select2").select2()
        });
    </script>
    <script>
        (function(i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function() {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-53034621-1', 'auto');
        ga('send', 'pageview');
    </script>
</body>

</html>