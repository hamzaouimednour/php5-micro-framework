    <section class="shop-list section-padding">
        <div class="container">
            <div class="row">
                <?php $this->requireTPL('p-menu-search', PATH_PUBLIC); ?>
                <div class="col-md-9">

                    <!-- Shopping Item Details Modal -->
                    <div class="modal fade" id="bd-item-modal" tabindex="-1" role="dialog" aria-labelledby="item-modal-label" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="item-modal-label">{}</h5>
                                    <button type="button" class="close close-top-right" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true"><i class="mdi mdi-close"></i></span>
                                        <span class="sr-only">Fermer</span>
                                    </button>
                                </div>

                                <form id="item-modal-form" autocomplete="off">
                                <div class="modal-body">
                                    <div class="row mr-1 ml-1">
                                        <input name="item-id" type="hidden" value="">
                                        <div class="col-sm-12" id="item-modal-content" data-max-cart="0">
                                            ...
                                        </div>
                                    </div>
                                    <div class="row mr-1 ml-1">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label">Extra instructions ? </label> <small class="float-right text-truncate">Lister toutes les demandes spéciales</small>
                                                <textarea class="form-control border-form-control" id="item-comment" name="item-instructions" placeholder="ex. sans frac du pain, extra épicé, etc..." style="margin-top: 0px; margin-bottom: 0px; height: 100px;resize: none;"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="col-sm-4">
                                        <div class="input-group qty">
                                            <span class="input-group-btn">
                                                <button disabled="disabled" class="btn btn-theme-round btn-number" type="button" id="items-qty-minus">-</button>
                                            </span>
                                            <input type="text" name="items-qty" id="items-qty" max="10" min="1" value="1" class="form-control border-form-control form-control-sm input-number" maxlength="3" minlength="1" onpaste="return false;" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" required>
                                            <span class="input-group-btn">
                                                <button class="btn btn-theme-round btn-number" type="button" id="items-qty-plus">+</button>
                                            </span>
                                        </div>
                                    </div>
                                    <span id="discount-qty" data-item-free-qty="0" data-item-purchased-qty="0"></span>
                                    <button type="submit" class="btn btn-primary btn-lg" id="add-cart-modal" data-item-price="0" data-item-total-price="0"><i id="add-cart-icon" class="mdi mdi-cart"></i> Ajouter - <b id="item-total-price"></b> <small>DT</small></button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Shopping Items Data -->

                    <!-- <a href="#"><img class="img-fluid mb-3" src="img/shop.jpg" alt=""></a> -->
                    <div class="col mb-5 pb-3 shop-head">

                        <div class="btn-group float-right mt-2">
                            <button style="width: 160px;" type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="dropdown-filter">
                                Trier repas par &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" id="sort-price-asc">Prix (Croissant)</a>
                                <a class="dropdown-item" href="#" id="sort-price-desc">Prix (Décroissant)</a>
                            </div>
                        </div>
                        <!-- <div class="col-md-4 pmb-3"></div> -->
                    </div>

                    <div class="row" id="card-row">
                        <?php
                        global $sizeImageFront;
                        global $restaurant;
                        $dishes = (new Dish)->setRestaurantId($restaurant->getUid())->setStatus('1')->getAllByRestaurantId();
                        //======================= btn-bookmark =======================//
                        if (!empty(Session::get('customer_id'))) {
                            $data = (new CustomerDishBookmark)->setUserId(Session::get('customer_id'))->getAllByUserId();
                            global $dishesIdArray;
                            $dishesIdArray = [];
                            array_walk($data, function (&$dishObj) {
                                global $dishesIdArray;
                                array_push($dishesIdArray, $dishObj->getDishId());
                            });
                        }

                        foreach ($dishes as $dish) {
                            if ($dish->getPriceBySize() == 'T') {
                                $defaultPrice = (new DishesPriceBySize)
                                    ->setDishId($dish->getId())
                                    ->getMinPriceByDishId()
                                    ->getPrice();
                            } else {
                                $defaultPrice = $dish->getPrice();
                            }
                            if ($dish->getDishImage() === 'placeholder.jpg') {
                                $img = Handler::getCdnImage(0, $dish->getDishImage(), 'crop', $sizeImageFront['width'], $sizeImageFront['height']);
                            } else {
                                $img = Handler::getCdnImage($restaurant->getUid(), $dish->getDishImage(), 'crop', $sizeImageFront['width'], $sizeImageFront['height']);
                            }
                            ?>

                            <div class="col-md-4 pmb-3 col-parent" id="card-<?= Handler::encryptInt($dish->getId()) ?>">
                                <div class="d-none menu-id">cat-<?= Handler::encryptInt($dish->getMenuId()) ?></div>
                                <div class="d-none dish-name"><?= mb_strtolower($dish->getDishName()) ?></div>
                                <div class="card card-item text-center shadow cat-<?= Handler::encryptInt($dish->getMenuId()) ?>">
                                    <img draggable="false" src="<?= $img ?>" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <?php
                                        $badgeDiscount = null;
                                        $discount = (new DiscountCode)
                                                    ->setDiscountItemId($dish->getId())
                                                    ->setRestaurantId($restaurant->getUid())
                                                    ->setStatus('1')
                                                    ->checkCodeByDishId();
                                                    
                                        if($discount){
                                            if($discount->getVoucherType() == '%'){
                                                $badgeDiscount = '%'.$discount->getVoucherValue().' OFF';
                                            }else{
                                                $badgeDiscount = '- '.$discount->getVoucherValue().' DT';
                                            }
                                        
                                        }

                                        $badgeDiscountQty = null;
                                        $discountQty = (new QuantityPromotion)->setDiscountItemId($dish->getId())->setStatus('1')->getElementByDishId();
                                        if($discountQty){
                                            $badgeDiscountQty = $discountQty->getPurchasedQty() . ' = '.$discountQty->getFreeDishesNumber().' GRATUIT';
                                        }
                                        
                                    if(!is_null($badgeDiscount)){
                                        $newPrice = Handler::toFixed(Handler::getPriceByDiscount($defaultPrice, $discount->getVoucherType(), $discount->getVoucherValue()));
                                    ?>
                                    <div class="product-header"><span class="badge badge-success"><?=$badgeDiscount?></span></div>
                                    <?php }elseif(!is_null($badgeDiscountQty)){ ?>
                                    <div class="product-header"><span class="badge badge-success"><?=$badgeDiscountQty?></span></div>
                                    <?php } ?>
                                    <div class="product-body">
                                            <h5 class="text-truncate" id="item-<?= Handler::encryptInt($dish->getId()) ?>-name"><?= ucfirst($dish->getDishName()) ?></h5>
                                            <!-- <?php global $min_delivery_time_resto, $max_delivery_time_resto; ?> -->
                                            <h6 class="text-capitalize"><strong><span class="mdi mdi-approval"></span>  <?php $menu = (new MenuCategories)->setId($dish->getMenuId())->getElementById(); echo $menu->getMenuName();?> </strong></h6>
                                        </div>
                                        <div class="product-footer" data-price="<?= is_null($badgeDiscount) ? Handler::currency_format_reverse($defaultPrice) : Handler::currency_format_reverse($newPrice) ?>">

                                            <p class="offer-price mb-0"><?= is_null($badgeDiscount) ? $defaultPrice : $newPrice ?> DT <i class="mdi mdi-tag-outline"></i> <?= is_null($badgeDiscount) ? null : '<span class="regular-price">'.$defaultPrice.'</span>' ?>
                                            </p>
                                            <?php
                                            global $closed;
                                                if ($dish->getStatus() != 0) {
                                                    ?>
                                                <button type="button" class="btn btn-secondary btn-sm launch-item-modal" data-item-id="<?= Handler::encryptInt($dish->getId()) ?>" <?=$closed?>>&nbsp;<i id="launch-item-modal-<?= Handler::encryptInt($dish->getId()) ?>" class="mdi mdi-cart-outline"></i>&nbsp;</button>
                                            <?php
                                                } else {
                                                    ?>
                                                <button type="button" class="btn btn-secondary btn-sm" disabled>&nbsp;<i class="mdi mdi-cart-off" <?=$closed?>></i>&nbsp;</button>
                                            <?php
                                                }
                                                ?>
                                            <?php
                                                if (!empty(Session::get('customer_id'))) {
                                                    $action = (!empty($dishesIdArray) && in_array($dish->getId(), $dishesIdArray)) ? 'Delete' : 'Add';
                                                    if($action == 'Delete'){
                                                        echo '<button type="button" class="btn btn-light btn-sm btn-bookmark" data-action="'.$action .'" data-item-id="'. Handler::encryptInt($dish->getId()) .'"><i class="text-danger mdi mdi-heart"></i></button>';
                                                    }else{
                                                        echo '<button type="button" class="btn btn-light btn-sm btn-bookmark" data-action="'.$action .'" data-item-id="'. Handler::encryptInt($dish->getId()) .'"><i class="mdi mdi-heart"></i></button>';
                                                    }
                                                } else {
                                                    ?>
                                                <button type="button" class="btn btn-light btn-sm" data-target="#bd-login-modal" data-toggle="modal" ><i class="mdi mdi-heart"></i></button>
                                            <?php
                                                }
                                                ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>