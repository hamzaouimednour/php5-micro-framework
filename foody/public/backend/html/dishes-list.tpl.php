<?php 
$dishes =   (new Dish)
            ->setRestaurantId(Session::get('user_id'))
            ->getAllByRestaurantId();

?>
            <div class="row">
                <div class="col-lg-12">
                    <!-- begin input-group -->
                    <div class="m-b-20">
                    </div>
                    <!-- end input-group -->
                    <!-- begin dropdown -->
                    <div class="dropdown pull-left">
                        
                        <a href="#" class="btn btn-white btn-white-without-border dropdown-toggle" data-toggle="dropdown">
                            filtres par catégorie
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="javascript:;">Posted Date</a></li>
                            <li><a href="javascript:;">View Count</a></li>
                            <li><a href="javascript:;">Total View</a></li>
                            <li class="divider"></li>
                            <li><a href="javascript:;">Location</a></li>
                        </ul>
                    </div>
                    <!-- end dropdown -->
                    <!-- begin btn-group -->
                    <div class="btn-group m-l-10 m-b-20">
                        <a href="javascript:;" class="btn btn-white btn-white-without-border"><i class="fa fa-list"></i></a>
                        <a href="javascript:;" class="btn btn-white btn-white-without-border"><i class="fa fa-th"></i></a>
                        <!-- <a href="javascript:;" class="btn btn-white btn-white-without-border"><i class="fa fa-th-large"></i></a> -->
                    </div>

                    <!-- end btn-group -->
                    <div class="pagination input-group col-sm-4 pull-right m-l-10 m-b-20">
                        <input type="text" id="filter-dishes" class="form-control input-white" placeholder="Chercher Un Plat">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-inverse"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-container"></div>

            <div class="row row-space-30" id="result-dishes">
            <?php
                global $sizeImageBackend;
                
                foreach ($dishes as $meal) {
                    $categorie = (new MenuCategories)
                                ->setId($meal->getMenuId())
                                ->getElementById();
                    $extras =   (new DishExtras)
                                ->setId($meal->getMenuId())
                                ->countByDishId();
                    if($meal->getPriceBySize() == 'T'){
                        $defaultPrice =  (new DishesPriceBySize)
                                        ->setDishId($meal->getId())
                                        ->getMinPriceByDishId()
                                        ->getPrice();
                    }else {
                        $defaultPrice = $meal->getPrice();
                    }
                    if($meal->getDishImage() === 'placeholder.jpg'){
                        $img = Handler::getCdnImage(0, $meal->getDishImage(), 'crop', $sizeImageBackend['width'], $sizeImageBackend['height']);
                    }else{
                        $img = Handler::getCdnImage(Session::get('user_id'), $meal->getDishImage(), 'crop', $sizeImageBackend['width'], $sizeImageBackend['height']);
                    }
                    $statusIcon = ($meal->getStatus() == 1) ? '<i class="text-success ion-md-radio-button-on" data-toggle="tooltip" data-placement="left" title="Active"></i>' : '<i class="text-danger ion-md-radio-button-on" data-toggle="tooltip" data-placement="left" title="Inactive"></i>' ;
                       
            ?>
                <!-- begin col-3 -->
                <div class="pr-items col-lg-2 w-auto p-5" id="<?=$meal->getId()?>">
                    <div class="d-none"><?=strtolower($meal->getDishName())?></div>
                    <!-- begin card -->
                    <div class="card shadow">
                        <img class="card-img-top" src="<?= $img ?>" alt="">
                        <div class="card-block">
                            <h5 class="card-title m-t-0 m-b-10"><?php echo '<div class="text-wrap" title="'.strtolower($meal->getDishName()).'"> ' .$statusIcon.' '.ucfirst($meal->getDishName()) . '</div>';?></h5>
                            <p class="card-text">
                                <span class="label label-inverse"><?=$categorie->getMenuName()?></span>
                                <p><b>Prix :</b> <?=$defaultPrice?> DT</p>
                                
                            </p>
                            <a href="javascript:;" class="btn btn-sm btn-default btn-edit" data-toggle="modal" data-target="#modal-dialog">Modifier</a>
                            <a href="javascript:;" class="btn btn-sm btn-default pull-right btn-delete" data-price="<?=$meal->getPriceBySize()?>" data-extras="<?=$extras?>" data-toggle="tooltip" data-placement="right" title="Supprimer"><i class="fas fa-times-circle"></i></a>
                        </div>
                    </div>
                </div>
            <?php } ?>
                <!-- end card -->

                <!-- end col-3 -->
            </div>