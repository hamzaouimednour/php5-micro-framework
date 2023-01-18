<div class="row justify-content-center">
    <!-- begin col-6 -->
    <div class="col-lg-11">

        <!-- begin panel -->
        <div class="panel panel-default panel-with-tabs" data-sortable="false">
            <div class="panel-heading ui-sortable-handle">
                <ul class="nav nav-tabs pull-right">
                    <li class="nav-item"><a href="#manage" data-toggle="tab" class="nav-link show active"><i
                                class="fas fa-edit"></i> <span class="d-none d-md-inline"> Editer</span></a></li>
                    <li class="nav-item"><a href="#add" data-toggle="tab" class="nav-link show"><i
                                class="fas fa-plus-circle"></i> <span class="d-none d-md-inline"> Ajouter</span></a>
                    </li>
                </ul>
                <h4 class="panel-title"><i class="fas fa-utensils"></i>&nbsp; Manager Les Suppléments</h4>
            </div>
            <!-- #modal-dialog -->
            <div class="modal fade" id="modal-dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"><i class="icon-note"></i> Modifier Supplément</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                    class="ion ion-ios-close-circle-outline"></i></button>
                        </div>
                        <form autocomplete="off" id="modal-form" class="needs-validation" novalidate>
                            <div class="modal-body">
                                <div id="modal-info-section"></div>
                                <div class="form-group row m-b-15">
                                    <label for="validationTooltip02" class="col-sm-3 col-form-label">Le Supplément</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="item_name" class="form-control"
                                            id="validationTooltip02" required>
                                        <div class="invalid-tooltip">
                                            Ce champ est obligatoire.
                                        </div>
                                        <input type="hidden" name="item_id" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group row m-b-15">
                                    <label for="validationTooltip03" class="col-sm-3 col-form-label">Prix</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="item_price" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57" class="form-control"
                                            id="validationTooltip03" placeholder="Prix" data-toggle="popover" data-placement="right" data-trigger="hover focus" data-html="true" data-content="<small><i class='text-info fas fa-info-circle'></i> <strong>Exemple du Prix sans '.' ou ',' :</strong><br><strong>500</strong> : Cinq cents millimes <br><strong>1000</strong> : Un dinar et zéro millimes.<br><strong>10500</strong> : Dix dinars et cinq cents millimes.</small> " required>
                                        <div class="invalid-tooltip">
                                            Ce champ est obligatoire.
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
            <div class="tab-content">
                <div class="tab-pane fade active show" id="manage">
                    <div class="table-responsive">
                        <table class="table table-bordered widget-table widget-table-rounded" data-id="widget">
                            <thead>
                                <tr>
                                    <th>
										<div class="checkbox checkbox-css">
                                            <input type="checkbox" id="cssCheckboxAll" name="select-all">
                                            <label for="cssCheckboxAll"></label>
										</div>
									</th>
                                    <th>Supplément Info</th>
                                    <th class="text-center">Prix</th>
                                    <th class="text-center">Plat</th>
                                    <th class="text-center">Statut</th>
                                    <th class="text-center">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $allExtras = (new RestaurantExtras)->setRestaurantId(Session::get('user_id'))->getAllByRestaurantId();
                                    $dishesNbr = new DishExtras;
                                    foreach ($allExtras as $extra) {
								?>
                                <tr id="<?= $extra->getId() ?>">
                                    <td class="text-center" width="1%">
                                        <div class="checkbox checkbox-css">
                                            <input type="checkbox" id="cssCheckbox<?= $extra->getId() ?>" name="multi_items[]" value="<?= $extra->getId() ?>">
                                            <label for="cssCheckbox<?= $extra->getId() ?>"></label>
                                        </div>
                                    </td>
                                    <td width="50%">
                                        <p class="widget-table-desc m-b-15" id="extra-name-<?= $extra->getId() ?>" style="font-size:12px"><?= $extra->getExtraName() ?></p>
                                    </td>
                                    <td class="text-center">
                                        <p class="widget-table-desc m-b-15" id="extra-price-<?= $extra->getId() ?>"><?= $extra->getPrice() ?></p>
                                    </td>
                                    <td class="text-center">
                                        <b class="text-inverse" data-id="widget-elm" data-light-class="text-inverse"
                                            data-dark-class="text-white"><?php $dishesNbr->setExtraId($extra->getId()); echo $dishesNbr->countByExtraId(); ?></b><br>
                                    </td>
                                    <td class="text-center widget-table-desc m-b-15"
                                        id="status-<?= $extra->getId() ?>"
                                        data-status="<?= $extra->getStatus() ?>">
                                        <h5><?= ($extra->getStatus() == 1) ? '<span class="label label-green"><i class="fas fa-check-circle"></i> Active' : '<span class="label label-secondary"><i class="fas fa-times-circle"></i> Inactive'; ?></span>
                                        </h5>
                                    </td>
                                    <td>
                                        <button class="btn btn-inverse btn-sm width-80 rounded-corner btn-edit"
                                            data-id="widget-elm"
                                            data-light-class="btn btn-inverse btn-sm width-80 rounded-corner"
                                            data-dark-class="btn btn-default btn-sm width-80 rounded-corner">Editer</button>
                                        <!--   -->
                                        <button type="button"
                                            class="btn btn-default btn-sm width-35 rounded-corner btn-status"
                                            data-status="<?= $extra->getStatus() ?>" data-toggle="tooltip"
                                            data-placement="bottom"
                                            title="<?= ($extra->getStatus() == 1) ? 'Désactiver' : 'Activer'; ?>">
                                            <?= ($extra->getStatus() == 1) ? '<i class="fas fa-eye-slash">' : '<i class="fas fa-eye">'; ?></i>
                                        </button>
                                        <button type="button"
                                            class="btn btn-danger btn-sm width-35 rounded-corner btn-delete"
                                            data-toggle="tooltip" data-placement="bottom" title="Supprimer">
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

                    <legend class="m-b-15">Ajouter Catégorie au Menu</legend>

                    <div class="col-md-8 offset-md-2 justify-content-center">

                        <div id="info-section"></div>

                        <form id="add-supplement" class="needs-validation" novalidate>
                            <fieldset>
                                <div class="form-group row m-b-15">
                                    <label for="validationTooltip01" class="col-md-3 col-form-label">Le Supplément</label>
                                    <div class="col-md-7">
                                        <input type="text" name="extra_name" class="form-control"
                                            id="validationTooltip01" placeholder="Supplément" required>
                                        <div class="invalid-feedback">
                                            Ce champ est obligatoire.
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row m-b-15">
                                    <label for="validationTooltip01" class="col-md-3 col-form-label">Prix</label>
                                    <div class="col-md-7">
                                        <input type="text" name="extra_price" onkeypress="return event.charCode >= 48 && event.charCode <= 57" class="form-control"
                                            id="validationTooltip01" placeholder="Prix" data-toggle="popover" data-placement="right" data-trigger="hover focus" data-html="true" data-content="<small><i class='text-info fas fa-info-circle'></i> <strong>Exemple du format de Prix :</strong><br><strong>500</strong> : Cinq cents millimes <br><strong>1000</strong> : Un dinar et zéro millimes.<br><strong>10500</strong> : Dix dinars et cinq cents millimes.</small> " required>
                                        <div class="invalid-feedback">
                                            Ce champ est obligatoire.
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row m-b-15">
                                    <label class="col-md-3 col-form-label">Statut du Supplément</label>
                                    <div class="col-md-7">
                                        <div class="switcher">
                                            <input type="checkbox" name="switcher_checkbox" id="switcher_checkbox"
                                                checked="" value="1">
                                            <label for="switcher_checkbox"></label>
                                        </div>
                                        <label id="switcher_checkbox_label" class="col-md-3 col-form-label"><span class="badge badge-green">Visible</span></label>

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-7 offset-md-3">
                                        <button type="reset" class="btn btn-default" id="reset-add-supplement">Annuler</button>
                                        <button type="submit" class="btn btn-primary m-r-5">Ajouter</button>
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