                
                <div class="col-md-3 mb-2">
                    <?php
                    global $restaurant;
                    $code = (new DiscountCode)
                    ->setDiscountItemId('0')
                    ->setRestaurantId( $restaurant->getUid() )
                    ->setStatus('1')
                    ->checkCodeByDishId();
                    if($code){
                        if($code->getVoucherType() == '%'){
                            $badgeDiscount = '%'.$code->getVoucherValue().' OFF';
                        }else{
                            $badgeDiscount = '- '.$code->getVoucherValue().' DT';
                        }
                    ?>
                    <div class="bg-white rounded shadow-sm text-white mb-3 p-4 clearfix restaurant-detailed-earn-pts card-icon-overlap">
                        <img class="img-fluid float-left mr-3" src="<?=HTML_PATH_PUBLIC . 'img/discount.png'?>">
                        <h6 class="pt-0 text-primary mb-1 font-weight-bold">OFFER</h6>
                        <p class="mb-0"><?=$badgeDiscount?> Utiliser Coupon: <span class="text-danger font-weight-bold"><?=$code->getVoucherUid()?></span></p>
                        <div class="icon-overlap">
                            <i class="icofont-sale-discount"></i>
                        </div>
                    </div>
                    <?php
                    }
                    $categories = (new MenuCategories)->setRestaurantId($restaurant->getUid())->setStatus('1')->getAllByRestaurantId();
                    ?>
                    <div class="shop-filters">
                        <div id="accordion">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Menu <span class="mdi mdi-chevron-down float-right"></span>
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                    <div class="card-body card-shop-filters">
                                        <form class="form-inline mb-3">
                                            <div class="input-group">
                                                <input type="text" placeholder="Rechercher un Plat" id="filter-dishes" class="form-control">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-secondary" style="pointer-events: none;"> <i class="mdi mdi-file-find"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="cat-all" class="custom-control-input" id="cat-all" checked>
                                            <label class="custom-control-label" for="cat-all">Tous les repas</label>
                                        </div>
                                        <hr>
                                        <?php
                                        foreach ($categories as $categorie) {
                                            $dishesNbr = (new Dish)->setMenuId($categorie->getId())->countByMenuId();
                                        ?>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="cat-menu[]" class="custom-control-input" value="cat-<?=Handler::encryptInt($categorie->getId())?>" id="cat-<?=Handler::encryptInt($categorie->getId())?>" <?= ($dishesNbr == 0) ? 'disabled' : null;?>>
                                            <label class="custom-control-label text-capitalize" for="cat-<?=Handler::encryptInt($categorie->getId())?>"><?=$categorie->getMenuName()?> <span class="badge <?= ($dishesNbr == 0) ? 'badge-secondary' : 'badge-info';?>" style="min-width:20px;height:13px;"><?=$dishesNbr;?></span></label> 
                                        </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="left-ad mt-4">
					  <img class="img-fluid" src="http://via.placeholder.com/254x557" alt="">
				   </div> -->
                </div>