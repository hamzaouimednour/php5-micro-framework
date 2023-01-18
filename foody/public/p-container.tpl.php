<section class="osahan-slider">
    <div id="osahanslider" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#osahanslider" data-slide-to="0" class="active"></li>
            <li data-target="#osahanslider" data-slide-to="1"></li>
        </ol>
        <div class="carousel-inner" role="listbox">
            <div class="carousel-item active" style="background-image: url('<?= HTML_PATH_PUBLIC . 'img/slider/slider-01.jpg' ?>')">
                <div class="overlay"></div>
            </div>
            <div class="carousel-item" style="background-image: url('<?= HTML_PATH_PUBLIC . '/img/slider/slider-02.jpg'?>')">
                <div class="overlay"></div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#osahanslider" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#osahanslider" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    <div class="slider-form">
        <div class="container">
            <h1 class="text-center text-white font-phone" style="font-family:Nettizen;">Find Your Favorite Food</h1>
            <form id="home-search-form" class="needs-validation" novalidate>
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Livraison</a>
                    </li>
                </ul>
                <div class="row no-gutters">

                    <div class="col-md-3">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="mdi mdi-map-marker-radius"></i></div>
                            <select class="form-control select2" name="search-city" required>
                                <?php
                                if (empty(Session::get('customer_city_id'))) {
                                    echo '<option value="" disabled selected>Choisir votre ville</option>';
                                }
                                $cities = (new City)->setStatus('1')->getAllByStatus();
                                if ($cities) {
                                    foreach ($cities as $city) {
                                        if (!empty(Session::get('customer_city_id')) && Session::get('customer_city_id') == $city->getId()) {
                                            echo '<option value="' . Handler::encryptInt($city->getId()) . '" selected>' . $city->getCityName() . '</option>';
                                        } else {
                                            echo '<option value="' . Handler::encryptInt($city->getId()) . '">' . $city->getCityName() . '</option>';
                                        }
                                    }
                                }
                                ?>
                            </select>
                            <div class="invalid-tooltip">Sélectionnez une ville.</div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="mdi mdi-food"></i></div>
                            <input id="search-cuisine" class="form-control" placeholder="Recherche par cuisine, ou voir tout les cuisines ..." type="text" name="search-cuisine">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary btn-block no-radius font-weight-bold" id="find-restos"><i id="find-restos-icon" class="mdi mdi-magnify"></i> SEARCH</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>



<!-- start main section -->
<section class="section-padding">
    <div class="section-title text-center mb-5">
        <h2>Prêt à commander?</h2>
        <p>Comment commander ?, Obtenir votre nourriture juste en 3 étapes.</p>
    </div>
    <div class="container">
        <!-- Page Content -->
        <div class="row justify-content-center">

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow">
                    <img draggable="false" src="<?=HTML_PATH_PUBLIC . 'img/small/food.jpg' ?>" class="card-img-top" alt="...">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-3 text-capitalize"><i class="mdi mdi-magnify"></i> Trouvez Vos Favoris</h5>
                        <div class="card-text text-black-50 text-justify">Trouvez ce que vous cherchez et commandez facilement à partir les meilleurs restaurants.</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow">
                    <img draggable="false" src="<?=HTML_PATH_PUBLIC . 'img/small/gmap.png' ?>" class="card-img-top" alt="...">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-3"><i class="mdi mdi-map-marker-radius"></i> Géolocalisez vous</h5>
                        <div class="card-text text-black-50 text-justify">Gagnez du temps en traitant votre liste de choses à faire à la maison, au travail ou en déplacement.</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow">
                    <img draggable="false" src="<?=HTML_PATH_PUBLIC . 'img/small/livraison.jpg' ?>" class="card-img-top" alt="...">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-3"><i class="mdi mdi-bike"></i> Livraison à domicile</h5>
                        <div class="card-text text-black-50 text-justify">Suivez l'état de vos commandes en ligne jusqu'à la livraison effectuée et payer à la livraison.</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
</section>

<section class="section-padding">
    <div class="section-title text-center mb-5">
        <h2 class="text-capitalize">rejoins notre équipe</h2>
        <p>Travailler avec Foody.</p>
    </div>
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-5 mb-4 mt-3 mx-auto">
                <div class="card">
                    <div class="row no-gutters">
                        <div class="col-md-5">
                            <img draggable="false" src="<?= HTML_PATH_PUBLIC . 'img/bg-delivery-400-1.jpg' ?>" class="card-img-top" style="height:300px;" alt="...">
                        </div>
                        <div class="col-md-7">
                            <div class="card-body">
                                <h5 class="card-title">Devenir livreur</h5>
                                <p class="card-text">Soyez votre propre patron et commencez à livrer aujourd'hui, à tout moment, n'importe où.</p>

                                <p class="card-text">Rejoignez notre communauté de livreurs, Gagnez de l'argent avec votre propre emploi du temps..</p>
                                <a target="_blank" href="<?= HTML_PATH_ROOT . 'delivery/signup' ?>" class="btn btn-secondary stretched-link">Devenir Livreur <i class="mdi mdi-bike"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5 mb-4 mt-3 mx-auto">
                <div class="card">
                    <div class="row no-gutters">
                        <div class="col-md-5">
                            <img draggable="false" src="<?= HTML_PATH_PUBLIC . 'img/restaurant-partnership-400.jpg' ?>" class="card-img-top" style="height:300px;" alt="...">
                        </div>
                        <div class="col-md-7">
                            <div class="card-body">
                                <h5 class="card-title">Devenir Partenaire</h5>
                                <p class="card-text">Accédez à de nouveaux clients et augmentez vos ventes avec le réseau de distribution.</p>
                                <p class="card-text">La révolution de la livraison est en marche. Nous mettons à votre disposition notre outil de livraison.</p>
                                <a target="_blank" href="<?= HTML_PATH_ROOT . 'restaurant/signup' ?>" class="btn btn-secondary stretched-link">Devenir Partenaire <i class="mdi mdi-food-fork-drink"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- end main section -->