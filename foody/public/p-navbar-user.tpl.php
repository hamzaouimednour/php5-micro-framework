    <nav class="navbar navbar-light navbar-expand-lg bg-dark bg-faded osahan-menu">
        <div class="container">
            <a class="navbar-brand" href="<?=HTML_PATH_ROOT?>"> <img draggable="false" src="<?=HTML_PATH_PUBLIC . 'img/logo-1.png' ?>" alt="logo"> </a>
            <div class="navbar-collapse" id="navbarNavDropdown ">
                <div class="navbar-nav mr-auto mt-5 mt-lg-2 margin-auto top-categories-search-main">
                    <div class="top-categories-search">
                        <div class="input-group">
                            <span class="input-group-btn categories-dropdown">
                           <select class="form-control-select" name="search-city" required>
                            <?php 
                                if(empty(Session::get('customer_city_id'))){
                                    echo '<option value="" disabled selected>Choisir votre ville</option>';
                                }
                                $cities = (new City)->setStatus('1')->getAllByStatus();
                                if($cities){
                                    foreach ($cities as $city) {
                                        if(!empty(Session::get('customer_city_id'))){
                                            echo '<option value="'.Handler::encryptInt($city->getId()).'" selected>'.$city->getCityName().'</option>';

                                        }else{
                                            echo '<option value="'.Handler::encryptInt($city->getId()).'">'.$city->getCityName().'</option>';
                                        }
                                    }
                                }
                              ?>
                              
                           </select>
                        </span>
                            <input class="form-control" id="search-cuisine" placeholder="Rechercher par cuisine" type="text">
                            <span class="input-group-btn">
                        <button class="btn btn-secondary" type="button" id="navbar-search"><i id="find-restos-icon" class="mdi mdi-file-find"></i> Search</button>
                        </span>
                        </div>
                    </div>
                </div>
                <div class="my-2 my-lg-0">
                    <ul class="list-inline main-nav-right">
                        <li class="list-inline-item dropdown osahan-top-dropdown">
                            <a class="btn btn-theme-round dropdown-toggle dropdown-toggle-top-user" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="bg-white" alt="logo" draggable="false" src="<?= HTML_PATH_IMG . 'user1.jpg'?>">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-list-design">
                                <a href="#" data-toggle="modal" data-target="#bd-profile-modal" class="dropdown-item"><i aria-hidden="true" class="mdi mdi-account-outline"></i>  Profil</a>
                                <a href="<?=HTML_PATH_ROOT . 'user/address'?>" class="dropdown-item"><i aria-hidden="true" class="mdi mdi-map-marker-circle"></i>  Adresses</a>
                                <!-- <a href="<?=HTML_PATH_ROOT . 'user/bookmark'?>" class="dropdown-item"><i aria-hidden="true" class="mdi mdi-heart-outline"></i>  Plats Favoris</a> -->
                                <a href="<?=HTML_PATH_ROOT . 'user/orders'?>" class="dropdown-item"><i aria-hidden="true" class="mdi mdi-format-list-bulleted"></i>  Commandes</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?=HTML_PATH_ROOT . 'user/logout'?>"><i class="mdi mdi-lock"></i> Déconnexion</a>
                            </div>
                        </li>
                        <li class="list-inline-item cart-btn">
                            <?php 
                            $cart = new Cart;
                            $countCartItems = $cart->getTotalItems();
                            ?>
                            <a href="#" data-toggle="offcanvas" class="btn btn-link border-none"><i class="mdi mdi-cart"></i><small id="cart-value" class="cart-value"><?=$countCartItems?></small></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <?php $this->requireTPL('p-pwd', PATH_PUBLIC); ?>