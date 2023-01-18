<?php
$dishId = Handler::getNumber($this->getParamsIndexOf());
$dish = (new Dish)
        ->setId($dishId)
        ->getElementById();
$menuCategories = (new MenuCategories)
                  ->setRestaurantId(Session::get('user_id'))
                  ->setStatus('1')
                  ->getAllByRestaurantId();
$dishExtras = (new DishExtras)
              ->setDishId($dishId)
              ->getAllByDishId();
array_walk($dishExtras, function(&$item){$item = $item->getExtraId();});

$menuExtras = (new RestaurantExtras)
              ->setRestaurantId(Session::get('user_id'))
              ->setStatus('1')
              ->getAllByRestaurantId();
?>
<div class="col-lg-12">
  <div class="panel panel-inverse" data-sortable-id="ui-widget-5" data-init="true">
    <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><span class="label label-success m-r-10 pull-left"><i class="fas fa-plus"></i></span> Ajout plat au Menu
      </h4>
    </div>
    <div class="panel-body">
      <legend class="m-b-15">Modifier plat</legend>
      <div class="col-sm" id="note-msg"></div>

      <form id="dish-add-form" class="needs-validation" novalidate>
        <div class="row">
          <div class="col-sm-6">
            <div class="col">
              <div class="form-group">
                <label for="textInput1">Nom du plat <small class="text-danger" title="champ obligatoire">*</small></label>
                <input type="text" class="form-control" id="textInput1" name="dish_name" placeholder="Nom du plat" value="<?= $dish->getDishName() ?>" required>
                <div class="invalid-feedback">Ce champ est obligatoire.</div>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="selectInput1">Catégorie <small class="text-danger" title="champ obligatoire">*</small></label>

                <select id="selectInput1" name="categorie" class="default-select2 form-control" required>
                  <option value="" disabled> Sélectionnez une catégorie </option>
                  <?php
                  foreach ($menuCategories as $categorie) {
                    if ($dish->getMenuId() == $categorie->getId()) {
                      echo '<option value="' . $categorie->getId() . '" selected>' . $categorie->getMenuName() . '</option>';
                    } else {
                      echo '<option value="' . $categorie->getId() . '">' . $categorie->getMenuName() . '</option>';
                    }
                  }
                  ?>
                </select>
                <div class="invalid-feedback">Ce champ est obligatoire.</div>
              </div>
            </div>
            <div class="col">
              <label for="">Prix du repas <small class="text-danger" title="champ obligatoire">*</small></label>
              <div class="note note-secondary m-b-15">
                <h5><b>Prix!</b></h5>
                <p>
                  Définissez un prix ou plusieurs prix en fonction de la taille.
                  ex(différentes tailles pour les pizzas petites, moyennes et familiales).
                </p>
              </div>


              <div class="form-group row m-b-10">
                <div class="col-md-2 p-t-3">
                  <div class="switcher switcher-green">
                    <input type="checkbox" name="switcher_checkbox" id="switcher_checkbox" value="-1">
                    <label for="switcher_checkbox"></label>
                  </div>
                </div>
                <label class="col-md-5 col-form-label">
                  <h5><span id="switch_status" class="label label-purple">prix en fonction de la taille &nbsp;<abbr title="changer l'etat du switcher pour confirmer" class="initialism"><i class="fas fa-question-circle"></i></abbr></span></h5>
                </label>

              </div>

              <div class="form-group" id="price-default">
                <input type="text" name="dish_price" onpaste="return false;" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" class="form-control" placeholder="Prix" value="<?= (empty($dish->getPrice()) || $dish->getPrice() == 0) ? '' : Handler::currency_format_reverse($dish->getPrice()) ?>" data-toggle="popover" data-placement="right" data-trigger="hover focus" data-html="true" data-content="<small><i class='text-info fas fa-info-circle'></i> <strong>Exemple du format de Prix :</strong><br><strong>500</strong> : Cinq cents millimes <br><strong>1000</strong> : Un dinar et zéro millimes.<br><strong>10500</strong> : Dix dinars et cinq cents millimes.</small> " required>
                <div class="invalid-feedback">Ce champ est obligatoire.</div>
              </div>



              <?php
              if ($dish->getPriceBySize() == 'T') {

                $dishPriceBySize = (new DishesPriceBySize)
                  ->setDishId($dishId)
                  ->getAllByDishId();
                if ($dishPriceBySize) {
                  $countPrices = count($dishPriceBySize);
                  ?>
                  <div class="form-group" id="price-size" data-size="<?= $countPrices ?>">
                    <?php
                    $countItems = 0;
                    foreach ($dishPriceBySize as $priceBysize) {
                      $countItems++;
                      ?>
                      <div class="form-inline m-b-10" id="main-price-size">
                        <div class="form-group m-r-10">
                          <input type="text" class="form-control" name="dish-size[]" value="<?= $priceBysize->getSizeName() ?>" placeholder="Entrer taille">
                        </div>
                        <div class="form-group m-r-10">
                          <input type="text" class="form-control" name="dish-price[]" value="<?= Handler::currency_format_reverse($priceBysize->getPrice()) ?>" onpaste="return false;" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" placeholder="Prix" data-toggle="popover" data-placement="right" data-trigger="hover focus" data-html="true" data-content="<small><i class='text-info fas fa-info-circle'></i> <strong>Exemple du format de Prix :</strong><br><strong>500</strong> : Cinq cents millimes <br><strong>1000</strong> : Un dinar et zéro millimes.<br><strong>10500</strong> : Dix dinars et cinq cents millimes.</small> ">
                        </div>
                        <?php if ($countItems == 1) { ?>
                          <button type="button" class="btn btn-primary btn-sm" id="size-row" data-toggle="tooltip" data-placement="right" title="Ajouter autre taille"><i class="fas fa-plus-circle"></i></button>
                        <?php } else { ?>
                          <button type="button" class="btn btn-default btn-sm" id="remove-size-row" data-toggle="tooltip" data-placement="right" title="" data-original-title="Supprimer ce taille"><i class="fas fa-times-circle"></i></button>
                        <?php } ?>
                      </div>

                    <?php } ?>
                  </div>
                <?php
                    }
                  } else {
                ?>
              <div class="form-group" id="price-size" data-size="1">
                <div class="form-inline m-b-10" id="main-price-size">
                  <div class="form-group m-r-10">
                    <input type="text" class="form-control" name="dish-size[]" placeholder="Entrer taille">
                  </div>
                  <div class="form-group m-r-10">
                    <input type="text" class="form-control" name="dish-price[]" onpaste="return false;" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" placeholder="Prix" data-toggle="popover" data-placement="right" data-trigger="hover focus" data-html="true" data-content="<small><i class='text-info fas fa-info-circle'></i> <strong>Exemple du format de Prix :</strong><br><strong>500</strong> : Cinq cents millimes <br><strong>1000</strong> : Un dinar et zéro millimes.<br><strong>10500</strong> : Dix dinars et cinq cents millimes.</small> ">
                  </div>
                  <button type="button" class="btn btn-primary btn-sm" id="size-row" data-toggle="tooltip" data-placement="right" title="Ajouter autre taille"><i class="fas fa-plus-circle"></i></button>
                </div>
              </div>
              <?php  } ?>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="col">
              <div class="form-group">
                <label for="textareaInput1">Description du plat</label>
                <textarea class="form-control" name="dish-description" id="textareaInput1" placeholder='Description du plat ...' style="margin-top: 0px; margin-bottom: 0px; height: 204px;"><?=$dish->getDescription()?></textarea>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="select2">Les Suppléments</label>
                <select id="selectInput2" name="supplements[]" data-placeholder="Sélectionnez Les Suppléments" class="multiple-select2 form-control" multiple="multiple">
                  <optgroup label="Sélectionnez Les Suppléments">
                    <?php
                      foreach($menuExtras as $extra){
                        if(in_array($extra->getId(), $dishExtras)){
                          echo '<option value="'.$extra->getId().'" selected>'.$extra->getExtraName().'</option>';
                        }else{
                          echo '<option value="'.$extra->getId().'">'.$extra->getExtraName().'</option>';
                        }
                      }
                    ?>
                  </optgroup>
                </select>
              </div>
            </div>
            <div class="col">
              <label for="select2">Etat du plat</label>
              <div class="form-group row m-b-10">
                <div class="col-md-2 p-t-3">
                  <div class="switcher switcher-green">
                  <?php if($dish->getStatus() == 1){ ?>
                    <input type="checkbox" name="switcher_checkbox_status" id="switcher_checkbox_status" value="1" checked>
                  <?php }else{ ?>
                    <input type="checkbox" name="switcher_checkbox_status" id="switcher_checkbox_status" value="-1">
                  <?php } ?>
                    <label for="switcher_checkbox_status"></label>
                  </div>
                </div>
                <label class="col-md-5 col-form-label">
                  <?php if($dish->getStatus() == 1){ ?>
                    <h5><span id="switch_checkbox_status" class="label label-green">plat est Visible <i class="fas fa-eye"></i></span></h5>
                  <?php }else{ ?>
                  <h5><span id="switch_checkbox_status" class="label label-danger">plat est Invisible <i class="fas fa-eye-slash"></i></span></h5>
                  <?php } ?>
                </label>
              </div>
            </div>
          </div>
        </div>
      </form>

      <div class="col-sm form-group">
        <label for="exampleInputEmail1">Image du plat</label>

        <div id="dropzone">
          <form action="<?= HTML_PATH_BACKEND ?>dishes/Add/Save" class="dropzone needsclick" id="dropzoneFrom">
            <div class="dz-message needsclick">
              Drag & drop files <b>here</b> or <b>click</b> to upload.<br />
              <span class="dz-note needsclick">
                ( allowed File Extensions are <strong>jpeg</strong>, <strong>jpg</strong>, <strong>png</strong>, <strong>gif</strong>.)
              </span>
            </div>
          </form>
        </div>

      </div>
      <div class="btn-toolbar sw-toolbar sw-toolbar-bottom justify-content-end">
        <div class="btn-group mr-2 sw-btn-group" role="group">
          <button class="btn btn-default" type="button" id="reset-dish-form">Retour</button>
          <button class="btn btn-success" type="button" id="add-dish">Modifier</button>
        </div>
      </div>
    </div>
  </div>
</div>