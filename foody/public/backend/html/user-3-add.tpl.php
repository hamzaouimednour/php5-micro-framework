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
    <?= $this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css"); ?>
	<?= $this->appendCSS(HTML_PATH_PUBLIC . "assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css"); ?>
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
                        <h4 class="text-white">Devenez Livreur</h4>
                        <p>
                        Soyez votre propre patron et commencez à livrer aujourd'hui, à tout moment, n'importe où.
                        </p>
                        <b>Gagnez de l'argent avec votre propre emploi du temps.</b>
                    </div>
                </div>
                <!-- END account-sidebar -->
                <!-- BEGIN account-body -->
                <div class="account-body">
                    <!-- BEGIN row -->
                    <div class="row">
                        <!-- BEGIN col-6 -->
                        <div class="col-md-12 mt-3">
                            <div id="info-section"></div>
                            <form id="restaurant-form" class="needs-validation" autocomplete="off" novalidate>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="textInput1">Nom & Prénom <small class="text-danger" title="champ obligatoire">*</small></label>
                                                <input type="text" class="form-control" id="textInput1" name="user-full_name" placeholder="Nom & Prénom" required>
                                                <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="textInput2">Phone <small class="text-danger" title="champ obligatoire">*</small></label>
                                                <input type="text" class="form-control" name="user-phone" maxlength="8" id="textInput2" onpaste="return false;" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" placeholder="Numéro de téléphone" required>
                                                <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="selectInput1">Vehicule <small class="text-danger" title="champ obligatoire">*</small></label>
                                                <select id="selectInput1" name="user-vehicle_id" class="form-control" required>
                                                    <option value="" selected disabled> Sélectionnez une vehicule </option>
                                                    <?php
                                                    $vehicles = (new Vehicle)->getAll();
                                                    foreach ($vehicles as $vehicle) {
                                                        ?>
                                                        <option value="<?= $vehicle->getId() ?>"><?= utf8_encode($vehicle->getVehicle()) ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="textInput3">Email <small class="text-danger" title="champ obligatoire">*</small></label>
                                                <input type="email" class="form-control" id="textInput3" name="user-email" placeholder="foulen@example.com" required>
                                                <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="datepicker-birthdate-modal">Date de naissance <small class="text-danger" title="champ obligatoire">*</small></label>
                                                <div class="input-group date" data-date-start-date="Date.default">
                                                    <input type="text" name="user-birth_date" class="form-control" id="datepicker-birthdate" placeholder="Select Date" required/>
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                </div>
                                                <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="selectInput2">Temps de travail <small class="text-danger" title="champ obligatoire">*</small></label>
                                                <select id="selectInput2" name="user-working_time" class="form-control select2" required>
                                                    <option value="" selected disabled> Sélectionnez une Période </option>
                                                    <option value="1">Déjeuner ( Matin ~ 17h )</option>
                                                    <option value="2">Dîner ( 17h ~ 23h )</option>
                                                    <option value="3">Déjeuner + Dîner ( Matin ~ 23h )</option>
                                                </select>
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

                                                <select id="cityInput" name="user-city" class="select2 form-control" required>
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
                                                <label for="textareaInput1">Adresse (détaillé) <small class="text-danger" title="champ obligatoire">*</small></label>
                                                <textarea class="form-control" name="user-address" id="textareaInput1" placeholder="Adresse du restaurant / Zone Rue ..." style="margin-top: 0px; margin-bottom: 0px; height: 150px;" required></textarea>
                                                <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="">Positionner votre adresse sur le Map <small class="text-danger" title="champ obligatoire">*</small></label>
                                            <input class="d-none form-control" type="text" name="latitudeR" required>
                                            <input type="hidden" name="longitudeR" required>
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
    <?= $this->appendJS(HTML_PATH_PUBLIC . "assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"); ?>
    <?= $this->appendJS(HTML_PATH_PUBLIC . 'assets/js/special/script.js?' . time()); ?>
    <script async defer src="https://maps.googleapis.com/maps/api/js?language=fr-fr&key=AIzaSyCkG1aDqrbOk28PmyKjejDwWZhwEeLVJbA&callback=initMap"></script>
    <!-- ================== END BASE JS ================== -->

    <script>
        $(document).ready(function() {
            App.init();
            $(".select2").select2();
            try {
            $.fn.datepicker.dates['fr'] = {
                days: ["dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi"],
                daysShort: ["dim.", "lun.", "mar.", "mer.", "jeu.", "ven.", "sam."],
                daysMin: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
                months: ["janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre"],
                monthsShort: ["janv.", "févr.", "mars.", "avril.", "mai.", "juin", "juil.", "août.", "sept.", "oct.", "nov.", "déc."],
                today: "Aujourd'hui",
                monthsTitle: "Mois",
                clear: "Effacer",
                weekStart: 1,
                format: "dd/mm/yyyy"
            };
            $("#datepicker-birthdate").datepicker({
                format: "yyyy-mm-dd",
                orientation: "bottom",
                todayHighlight: !0,
                autoclose: !0,
                endDate: '-1d',
                language: 'fr'
            })
        } catch (e) {$.noop()}
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