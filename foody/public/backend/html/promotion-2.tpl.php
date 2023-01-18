<div class="row justify-content-center">
    <!-- begin col-6 -->
    <div class="col-lg-12">
        <!-- #modal-dialog -->
        <div class="modal fade" id="modal-dialog">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="icon-note"></i> Modifier Promotion</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="ion ion-ios-close-circle-outline"></i></button>
                    </div>
                    <form autocomplete="off" id="modal-form" class="needs-validation" novalidate>
                        <input name="modal-id" type="hidden" value="">
                        <div class="modal-body">
                            <div id="modal-info-section"></div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="select2insidemodal">Plat <small class="text-danger" title="champ obligatoire">*</small></label>

                                            <select style="width: 100% !important;" id="select2insidemodal" name="modal-discount_item_id" class="form-control" required>
                                                <option value="" selected disabled> Sélectionnez un Plat </option>

                                                <optgroup label="Bon de réduction sur Panier">
                                                    <option value="0">Promotion sur Panier</option>
                                                </optgroup>
                                                <optgroup label="Bon de réduction sur Plat">
                                                <?php
                                                $dishes = (new Dish)->setRestaurantId(Session::get('user_id'))->getAllByRestaurantId();
                                                foreach ($dishes as $dish) {
                                                    ?>
                                                <option value="<?= $dish->getId() ?>"><?= $dish->getDishName() ?></option>
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
                                            <label for="textInput01">Code du coupon <small class="text-danger" title="champ obligatoire">*</small></label>

                                            <input type="text" class="form-control" id="textInput01" name="modal-voucher_uid" placeholder="exemple: ABCDEF123" style="text-transform: uppercase" required>
                                            <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="">Type du coupon <small class="text-danger" title="champ obligatoire">*</small></label>

                                            <div class="col-md-9">
                                                <div class="radio radio-css radio-inline">
                                                    <input type="radio" name="modal_voucher_type" id="inlineCssRadio01" value="$" checked="">
                                                    <label class="widget-table-desc m-b-15" for="inlineCssRadio01">en Prix (Millimes)</label>
                                                </div>
                                                <div class="radio radio-css radio-inline">
                                                    <input type="radio" name="modal_voucher_type" id="inlineCssRadio02" value="%">
                                                    <label class="widget-table-desc m-b-15" for="inlineCssRadio02">en Percentage (%)</label>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="textInput02">Valeur du coupon <small class="text-danger" title="champ obligatoire">*</small></label>
                                            <div class="input-group m-r-10">
                                                <input type="text" id="textInput02" onpaste="return false;" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" name="modal-voucher_value" class="form-control" placeholder="Valeur du coupon" required>
                                                <span class="input-group-addon">
                                                    <span id="addon-voucher-type">$</span>
                                                </span>
                                            </div>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="">Validité du Promotion <small class="text-danger" title="champ obligatoire">*</small></label>

                                            <div class="input-group input-daterange">
                                                <input type="text" name="modal-start_date_time" autocomplete="off" id="datetimepicker-modal-1" class="form-control" name="start" placeholder="Date de Début" required>
                                                <span class="input-group-addon"><i class="fas fa-calendar-alt"></i></span>
                                                <input type="text" name="modal-end_date_time" autocomplete="off" id="datetimepicker-modal-2" class="form-control" name="end" placeholder="Date de Fin" required>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">


                                    <div class="col">
                                        <div class="form-group">
                                            <label for="textareaInput01">Description du Promotion</label>
                                            <textarea class="form-control" name="modal-description" id="textareaInput01" placeholder="Détails du Promotion ..." style="margin-top: 0px; margin-bottom: 0px; height: 251px;"></textarea>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label for="switcher_checkbox_status">Etat du Promotion</label>
                                        <div class="form-group row m-b-10">
                                            <div class="col-md-2 p-t-3">
                                                <div class="switcher switcher-green">
                                                    <input type="checkbox" name="modal-status" id="modal-switcher_checkbox_status" value="1" checked>
                                                    <label for="modal-switcher_checkbox_status"></label>
                                                </div>
                                            </div>
                                            <label class="col-md-5 col-form-label">
                                                <h5><span id="modal-switch_checkbox_status" class="label label-green">Promotion Active <i class="fas fa-eye"></i></span></h5>
                                            </label>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Annuler</a>
                            <button id="btnnn" type="submit" class="btn btn-green">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- begin panel -->
        <div class="panel panel-default panel-with-tabs" data-sortable="false">
            <div class="panel-heading ui-sortable-handle">
                <ul class="nav nav-tabs pull-right">
                    <li class="nav-item"><a href="#manage" data-toggle="tab" class="nav-link show active"><i class="fas fa-edit"></i> <span class="d-none d-md-inline"> Editer</span></a></li>
                    <li class="nav-item"><a href="#add" data-toggle="tab" class="nav-link show"><i class="fas fa-plus-circle"></i> <span class="d-none d-md-inline"> Ajouter</span></a>
                    </li>
                </ul>
                <h4 class="panel-title"><i class="fas fa-utensils"></i>&nbsp; Manager Les Promotions</h4>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade active show" id="manage">
                    <div class="table-responsive">
                        <table class="table table-bordered widget-table widget-table-rounded" data-id="widget">
                            <thead>
                                <tr>
                                    <th width="1%">
                                        <div class="checkbox checkbox-css">
                                            <input type="checkbox" id="cssCheckboxAll" name="select-all">
                                            <label for="cssCheckboxAll"></label>
                                        </div>
                                    </th>
                                    <th class="text-center">Plat</th>
                                    <th class="text-center">Coupon Code</th>
                                    <th class="text-center">Valeur</th>
                                    <th class="text-center">Début du Promo</th>
                                    <th class="text-center">Fin du Promo</th>
                                    <th class="text-center">Validité</th>
                                    <th class="text-center">Statut</th>
                                    <th class="text-center">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                global $dateTime;

                                $allDiscountQty = (new DiscountCode)->setRestaurantId(Session::get('user_id'))->getAllByRestaurantId();
                                foreach ($allDiscountQty as $discountQty) {
                                    $d1 = (new DateTime($discountQty->getEndDateTime()))->format('Y-m-d H:i:s');
                                    $d2 = $dateTime->format('Y-m-d H:i:s');
                                    $dishName =  ($discountQty->getDiscountItemId() != 0) ? (new Dish)->setId($discountQty->getDiscountItemId())->getElementById() : 'PROMOTION SUR PANIER <i class="fas fa-shopping-cart"></i>';
                                ?>
                                <tr id="<?= $discountQty->getId() ?>">
                                    <td class="text-center" width="1%">
                                        <div class="checkbox checkbox-css">
                                            <input type="checkbox" id="cssCheckbox<?= $discountQty->getId() ?>" name="multi_items[]" value="<?= $discountQty->getId() ?>">
                                            <label for="cssCheckbox<?= $discountQty->getId() ?>"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <p style="font-size:12px" class="widget-table-desc m-b-15 <?= ($discountQty->getDiscountItemId() == 0) ? 'text-pink' : null ?>"><?= ($discountQty->getDiscountItemId() != 0) ? $dishName->getDishName() : $dishName ?></p>
                                    </td>
                                    <td class="text-center">
                                    <h5><span class="badge badge-default"><b class="text-inverse text-uppercase" data-id="widget-elm" data-light-class="text-inverse" data-dark-class="text-white"><?= $discountQty->getVoucherUid() ?></b></span></h5>
                                    </td>
                                    <td class="text-center">
                                    <b class="text-inverse" data-id="widget-elm" data-light-class="text-inverse" data-dark-class="text-white"><?= ($discountQty->getVoucherType() == '$') ? Handler::currency_format($discountQty->getVoucherValue()) .' DT': $discountQty->getVoucherValue() .' %'?></b><br>
                                    </td>
                                    <td class="text-center">
                                        <p class="widget-table-desc m-b-15"><?= $discountQty->getStartDateTime() ?></p>
                                    </td>
                                    <td class="text-center">
                                        <p class="widget-table-desc m-b-15"><?= $discountQty->getEndDateTime() ?></p>
                                    </td>
                                    <td class="text-center">
                                        <h5><?= ($d1 < $d2) ? '<span class="label label-warning"><i class="fas fa-calendar-times"></i> Invalide' : '<span class="label label-success"><i class="fas fa-calendar-check"></i> Valid'; ?></span>
                                        </h5>
                                    </td>
                                    <td class="text-center widget-table-desc m-b-15" id="status-<?= $discountQty->getId() ?>" data-status="<?= $discountQty->getStatus() ?>">
                                        <h5><?= ($discountQty->getStatus() == 1) ? '<span class="label label-green"><i class="fas fa-check-circle"></i> Active' : '<span class="label label-secondary"><i class="fas fa-times-circle"></i> Inactive'; ?></span>
                                        </h5>
                                    </td>
                                    <td width="20%">
                                        <button class="btn btn-inverse btn-sm width-80 rounded-corner btn-edit" data-id="widget-elm" data-light-class="btn btn-inverse btn-sm width-80 rounded-corner" data-dark-class="btn btn-default btn-sm width-80 rounded-corner">Editer</button>
                                        <!--   -->
                                        <button type="button" class="btn btn-default btn-sm width-35 rounded-corner btn-status" data-status="<?= $discountQty->getStatus() ?>" data-toggle="tooltip" data-placement="bottom" title="<?= ($discountQty->getStatus() == 1) ? 'Désactiver' : 'Activer'; ?>">
                                            <?= ($discountQty->getStatus() == 1) ? '<i class="fas fa-eye-slash">' : '<i class="fas fa-eye">'; ?></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm width-35 rounded-corner btn-delete" data-toggle="tooltip" data-placement="bottom" title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>

                                    <?php
                                    }
                                    ?>
                                    <!-- <h4 class="widget-table-title">Mavic Pro Combo</h4> -->

                                </tr>
                            </tbody>
                        </table>
                        <button type="button" id="multi-delete" class="btn btn-yellow m-r-5 m-b-5" data-toggle="tooltip" data-placement="right" title="" data-original-title="Supprimer Sélections" disabled><i class="fas fa-trash-alt"></i> &nbsp;Supprimer</button>
                        <!-- end widget-table -->
                    </div>
                </div>
                <div class="tab-pane fade" id="add">

                    <legend class="m-b-15">Ajouter Promotion par Coupon</legend>

                    <div id="info-section"></div>

                    <form id="add-discount" data-promo="1" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="selectInput01">Plat <small class="text-danger" title="champ obligatoire">*</small></label>

                                        <select style="width: 100% !important;" id="selectInput01" name="discount_item_id" class="default-select2 form-control" required>
                                            <option value="" selected disabled> Sélectionnez un Plat </option>
                                            <optgroup label="Bon de réduction sur Panier">
												<option value="0">Promotion sur Panier</option>
                                            </optgroup>
                                            <optgroup label="Bon de réduction sur Plat">
                                                <?php
                                                $dishes = (new Dish)->setRestaurantId(Session::get('user_id'))->getAllByRestaurantId();
                                                foreach ($dishes as $dish) {
                                                    ?>
                                                <option value="<?= $dish->getId() ?>"><?= $dish->getDishName() ?></option>
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
                                        <label for="textInput1">Code du coupon <small class="text-danger" title="champ obligatoire">*</small></label>

                                        <input type="text" class="form-control" id="textInput1" name="voucher_uid" style="text-transform: uppercase" placeholder="exemple: ABCDEF123" required>
                                        <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="textInput1">Type du coupon <small class="text-danger" title="champ obligatoire">*</small></label>

                                        <div class="col-md-9">
                                            <div class="radio radio-css radio-inline">
                                                <input type="radio" name="voucher_type" id="inlineCssRadio1" value="$" checked="">
                                                <label class="widget-table-desc m-b-15" for="inlineCssRadio1">en Prix (Millimes)</label>
                                            </div>
                                            <div class="radio radio-css radio-inline">
                                                <input type="radio" name="voucher_type" id="inlineCssRadio2" value="%">
                                                <label class="widget-table-desc m-b-15" for="inlineCssRadio2">en Percentage (%)</label>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback">Ce champ est obligatoire.</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="textInput2">Valeur du coupon <small class="text-danger" title="champ obligatoire">*</small></label>
                                        <div class="input-group m-r-10">
                                            <input type="text" id="textInput2" onpaste="return false;" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" name="voucher_value" class="form-control" placeholder="Valeur du coupon" required>
                                            <span class="input-group-addon">
                                                <span class="addon-voucher-type">$</span>
                                            </span>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="">Validité du Promotion <small class="text-danger" title="champ obligatoire">*</small></label>

                                        <div class="input-group input-daterange">
                                            <input type="text" name="start_date_time" autocomplete="off" id="datetimepicker3" class="form-control" name="start" placeholder="Date de Début" required>
                                            <span class="input-group-addon"><i class="fas fa-calendar-alt"></i></span>
                                            <input type="text" name="end_date_time" autocomplete="off" id="datetimepicker4" class="form-control" name="end" placeholder="Date de Fin" required>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">


                                <div class="col">
                                    <div class="form-group">
                                        <label for="textareaInput1">Description du Promotion</label>
                                        <textarea class="form-control" name="description" id="textareaInput1" placeholder="Détails du Promotion ..." style="margin-top: 0px; margin-bottom: 0px; height: 251px;"></textarea>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="switcher_checkbox_status ">Etat du Promotion</label>
                                    <div class="form-group row m-b-10">
                                        <div class="col-md-2 p-t-3">
                                            <div class="switcher switcher-green">
                                                <input type="checkbox" name="switcher_checkbox_status" id="switcher_checkbox_status" value="1" checked>
                                                <label for="switcher_checkbox_status"></label>
                                            </div>
                                        </div>
                                        <label class="col-md-5 col-form-label">
                                            <h5><span id="switch_checkbox_status" class="label label-green">Promotion Active <i class="fas fa-eye"></i></span></h5>
                                        </label>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="btn-toolbar sw-toolbar sw-toolbar-bottom justify-content-end">
                            <div class="btn-group mr-2 sw-btn-group" role="group">
                                <button class="btn btn-default" type="button" id="reset-discount-form">Annuler</button>
                                <button class="btn btn-success" type="submit" id="add-discount">Ajouter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- end panel -->
    </div>
    <!-- end col-6 -->
</div>