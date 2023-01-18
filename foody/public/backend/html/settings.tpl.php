<div class="row justify-content-center">
    <!-- begin col-6 -->
    <div class="col-lg-11">

        <!-- begin panel -->
        <div class="panel panel-default panel-with-tabs" data-sortable="false">
            <div class="panel-heading ui-sortable-handle">
                <ul class="nav nav-tabs pull-right">
                    <li class="nav-item">
                        <a href="#tab1" data-toggle="tab" class="nav-link show active">
                            <i class="fas fa-edit"></i> <span class="d-none d-md-inline"> Editer Les Options</span>
                        </a>
                    </li>
                </ul>
                <h4 class="panel-title"><i class="fas fa-utensils"></i>&nbsp; Manager Les Options</h4>
            </div>
            
            <div class="tab-content">
                <div class="tab-pane fade active show" id="tab1">

                    <legend class="m-b-15"></legend>

                    <div class="col-md-9 offset-md-2 justify-content-center">

                        <div id="info-section"></div>
                        <?php
                        $options = (new Options)->getAll();
                        // $neededObject = array_filter($options,function ($e) {return $e->getOptionName() == 'init_distance';}
                        ?>
                        <form id="edit-options" class="needs-validation" novalidate>
                            <fieldset>
                                <div class="form-group row mb-4">
                                    <label for="validationTooltip01" class="col-md-3 col-form-label">Distance initial de livraison</label>
                                    <div class="col-md-7">
                                        <?php $dis = array_filter($options,function ($e) { return $e->getOptionName() == 'init_distance' ;}); ?>
                                        <div class="input-group">
                                            <input type="text" name="init_distance" class="form-control"
                                            id="validationTooltip01" placeholder="Distance initial de livraison" value="<?=current($dis)->getOptionValue()?>" required>
                                            <div class="invalid-tooltip">ce champ est obligatoire. </div>
                                            <div class="input-group-append"><button class="btn btn-primary" type="button" style="pointer-events: none;">&nbsp;KM&nbsp;</button></div>
                                        </div>
                                            
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label for="validationTooltip02" class="col-md-3 col-form-label">Frais de livraison</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <?php $fee = array_filter($options,function ($e) { return $e->getOptionName() == 'delivery_fee';}); ?>

                                            <input type="text" name="delivery_fee" class="form-control"
                                            id="validationTooltip02" placeholder="Frais de livraison" value="<?=current($fee)->getOptionValue()?>" required>
                                            <div class="invalid-tooltip">ce champ est obligatoire. </div>
                                            <div class="input-group-append"><button class="btn btn-primary" type="button" style="pointer-events: none;">&nbsp;DT&nbsp;</button></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label for="validationTooltip03" class="col-md-3 col-form-label">Frais de livraison extras</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <?php $extra_fee = array_filter($options,function ($e) { return $e->getOptionName() == 'up_fee';}); ?>
                                            <input type="text" name="up_fee" class="form-control"
                                            id="validationTooltip03" placeholder="Frais de livraison extras" value="<?=current($extra_fee)->getOptionValue()?>" required>
                                            <div class="invalid-tooltip">ce champ est obligatoire. </div>
                                            <div class="input-group-append"><button class="btn btn-primary" type="button" style="pointer-events: none;">&nbsp;DT&nbsp;</button></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label for="validationTooltip04" class="col-md-3 col-form-label">Temps de livraison minimal</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <?php $dtmin = array_filter($options,function ($e) { return $e->getOptionName() == 'delivery_time_min';}); ?>

                                            <input type="text" name="delivery_time_min" class="form-control"
                                            id="validationTooltip04" placeholder="Temps minimal de livraison" value="<?=current($dtmin)->getOptionValue()?>" required>
                                            <div class="invalid-tooltip">ce champ est obligatoire. </div>
                                            <div class="input-group-append"><button class="btn btn-primary" type="button" style="pointer-events: none;">MIN</button></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label for="validationTooltip05" class="col-md-3 col-form-label">Temps de livraison maximal</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <?php $dtmax = array_filter($options,function ($e) { return $e->getOptionName() == 'delivery_time_max';}); ?>

                                            <input type="text" name="delivery_time_max" class="form-control"
                                            id="validationTooltip05" placeholder="Temps maximal de livraison" value="<?=current($dtmax)->getOptionValue()?>" required>
                                            <div class="invalid-tooltip">ce champ est obligatoire. </div>
                                            <div class="input-group-append"><button class="btn btn-primary" type="button" style="pointer-events: none;">MIN</button></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-7 offset-md-3">
                                        <button type="reset" class="btn btn-default mr-3" onclick="window.history.back();"><i class="fas fa-reply"></i> Annuler</button>
                                        <button type="submit" class="btn btn-green m-r-5"><i class="fas fa-save"></i> Enregistrer les modifications</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- end panel -->
    </div>
    <!-- end col-6 -->
</div>