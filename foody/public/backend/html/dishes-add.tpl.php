<?php
$menuCategories = (new MenuCategories)
                  ->setRestaurantId(Session::get('user_id'))
                  ->setStatus('1')
                  ->getAllByRestaurantId();
$menuExtras = (new RestaurantExtras)
              ->setRestaurantId(Session::get('user_id'))
              ->setStatus('1')
              ->getAllByRestaurantId();
?>
<div class="col-lg-12">
  <div class="panel panel-inverse" data-sortable-id="ui-widget-5" data-init="true">
    <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><span class="label label-success m-r-10 pull-left"><i class="fas fa-plus"></i></span> Ajout Plat au Menu
      </h4>
    </div>
    <div class="panel-body">
      <legend class="m-b-15">Ajouter Plat</legend>
      <div class="col-sm" id="note-msg"></div>

      <form id="dish-add-form" class="needs-validation" novalidate>
        <div class="row">
          <div class="col-sm-6">
            <div class="col">
              <div class="form-group">
                <label for="textInput1">Nom du plat <small class="text-danger" title="champ obligatoire">*</small></label>
                <input type="text" class="form-control" id="textInput1" name="dish_name" placeholder="Nom du plat" required>
                <div class="invalid-feedback">Ce champ est obligatoire.</div>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="selectInput1">Catégorie <small class="text-danger" title="champ obligatoire">*</small></label>

                <select id="selectInput1" name="categorie" class="default-select2 form-control" required>
                  <option value="" selected disabled> Sélectionnez une catégorie </option>
                  <?php
                    foreach($menuCategories as $categorie){
                  ?>
                  <option value="<?=$categorie->getId()?>"><?=$categorie->getMenuName()?></option>
                  <?php
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
                <input type="text" name="dish_price" onpaste="return false;" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" class="form-control" placeholder="Prix" data-toggle="popover" data-placement="right" data-trigger="hover focus" data-html="true" data-content="<small><i class='text-info fas fa-info-circle'></i> <strong>Exemple du format de Prix :</strong><br><strong>500</strong> : Cinq cents millimes <br><strong>1000</strong> : Un dinar et zéro millimes.<br><strong>10500</strong> : Dix dinars et cinq cents millimes.</small> " required>
                <div class="invalid-feedback">Ce champ est obligatoire.</div>
              </div>
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
            </div>
          </div>
          <div class="col-sm-6">
            <div class="col">
              <div class="form-group">
                <label for="textareaInput1">Description du plat</label>
                <textarea class="form-control" name="dish-description" id="textareaInput1" placeholder='Description du plat ...' style="margin-top: 0px; margin-bottom: 0px; height: 204px;"></textarea>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="select2">Les Suppléments</label>
                <select id="selectInput2" name="supplements[]" data-placeholder="Sélectionnez Les Suppléments" class="multiple-select2 form-control" multiple="multiple">
                  <optgroup label="Sélectionnez Les Suppléments">
                  <?php
                    foreach($menuExtras as $extra){
                  ?>
                  <option value="<?=$extra->getId()?>"><?=$extra->getExtraName()?></option>
                  <?php
                    }
                  ?>
                  </optgroup>
                </select>
              </div>
            </div>
            <div class="col">
              <label for="switcher_checkbox_status ">Etat du plat</label>
              <div class="form-group row m-b-10">
                <div class="col-md-2 p-t-3">
                  <div class="switcher switcher-green">
                    <input type="checkbox" name="switcher_checkbox_status" id="switcher_checkbox_status" value="1" checked>
                    <label for="switcher_checkbox_status"></label>
                  </div>
                </div>
                <label class="col-md-5 col-form-label">
                  <h5><span id="switch_checkbox_status" class="label label-green">plat est Visible <i class="fas fa-eye"></i></span></h5>
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
          <button class="btn btn-default" type="button" id="reset-dish-form">Annuler</button>
          <button class="btn btn-success" type="button" id="add-dish">Ajouter</button>
        </div>
      </div>
    </div>
  </div>
</div>