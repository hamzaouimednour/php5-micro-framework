    <nav class="navbar navbar-light navbar-expand-lg bg-dark bg-faded osahan-menu">
        <div class="container">
            <a class="navbar-brand" href="<?=HTML_PATH_ROOT?>"> <img draggable="false" src="<?=HTML_PATH_PUBLIC . 'img/logo-1.png' ?>" alt="logo"> </a>
            <div class="navbar-collapse" id="navbarNavDropdown ">
                <div class="navbar-nav mr-auto mt-5 mt-lg-2 margin-auto top-categories-search-main">
                    <div class="top-categories-search">
                        <div class="input-group">
                            <span class="input-group-btn categories-dropdown">
                           <select class="form-control-select" name="search-city">
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
                        <li class="list-inline-item cart-btn">
                            <a href="#" data-target="#bd-login-modal" data-toggle="modal" class="btn btn-link border-none"><i class="mdi mdi-account-circle"></i></a>
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