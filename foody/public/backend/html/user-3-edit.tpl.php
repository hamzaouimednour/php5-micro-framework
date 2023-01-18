                    <div class="row">

                    	<div class="col-sm-6">
                    		<div class="col">
                    			<div class="form-group">
                    				<label for="textInput1">Nom complet <small class="text-danger" title="champ obligatoire">*</small></label>
                    				<input type="text" class="form-control" id="textInput1" name="full_name" placeholder="Nom & Prénom" required>
                    				<div class="invalid-feedback">Ce champ est obligatoire.</div>
                    			</div>
                    		</div>

                    		<div class="col">
                    			<div class="form-group">
                    				<label for="textInput1">Date de naissance <small class="text-danger" title="champ obligatoire">*</small></label>
                    				<div class="input-group date" data-date-start-date="Date.default">
                    					<input type="text" name="birth_date" class="form-control" id="datepicker-birthdate-modal" placeholder="Select Date" />
                    					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    				</div>
                    				<div class="invalid-feedback">Ce champ est obligatoire.</div>
                    			</div>
                    		</div>
                    		<div class="col">
                    			<div class="form-group">
                    				<label for="textInput3">Phone <small class="text-danger" title="champ obligatoire">*</small></label>
                    				<input type="text" class="form-control" name="phone" id="textInput3" placeholder="Numéro de téléphone" required>
                    				<div class="invalid-feedback">Ce champ est obligatoire.</div>
                    			</div>
                    		</div>
                    		<div class="col">
                    			<div class="form-group">
                    				<label for="textInput3">Mot de passe</label>
                    				<input type="text" name="passwd" id="password-indicator-visible" class="form-control m-b-5" />
                    				<div id="passwordStrengthDiv2" class="is0 m-t-5"></div>
                    				<div class="invalid-feedback">Ce champ est obligatoire.</div>
                    			</div>
                    		</div>
                    	</div>

                    	<div class="col-sm-6">
                    		<div class="col">
                    			<div class="form-group">
                    				<label for="textInput4">Email <small class="text-danger" title="champ obligatoire">*</small></label>
                    				<input type="email" class="form-control" id="textInput4" name="email" placeholder="foulen@example.com" required>
                    				<div class="invalid-feedback">Ce champ est obligatoire.</div>
                    			</div>
                    		</div>

                    		<div class="col">
                    			<div class="form-group">
                    				<label for="selectInput1">Vehicule <small class="text-danger" title="champ obligatoire">*</small></label>
                    				<select id="selectInput1" name="vehicle_id" class="form-control" required>
                    					<option value="" selected disabled> Sélectionnez une vehicule </option>
                    					<?php
										$vehicles = (new Vehicle)->getAll();
										foreach ($vehicles as $vehicle) {
											?>
                    						<option value="<?= $vehicle->getId() ?>"><?= utf8_encode($vehicle->getVehicle()) ?></option>
                    					<?php } ?>
                    				</select>
                    				<div class="invalid-feedback">Ce champ est obligatoire.</div>
                    			</div>
                    		</div>
                    		<div class="col">
                    			<label for="switcher_checkbox_avaible">Disponibilité</label>
                    			<div class="form-group row m-b-10">
                    				<div class="col-md-2 p-t-3">
                    					<div class="switcher switcher-info">
                    						<input type="checkbox" name="availability" id="switcher_checkbox_avaible" value="1" checked>
                    						<label for="switcher_checkbox_avaible"></label>
                    					</div>
                    				</div>
                    				<label class="col-md-5 col-form-label">
                    					<h5><span id="switcher_label_avaible" class="label label-info">Livreur Disponible &nbsp;<i class="fas fa-hourglass-end"></i></span></h5>
                    				</label>

                    			</div>
                    		</div>
                    		<div class="col">
                    			<label for="switcher_checkbox_status">Etat d'utilisateur</label>
                    			<div class="form-group row m-b-10">
                    				<div class="col-md-2 p-t-3">
                    					<div class="switcher switcher-green">
                    						<input type="checkbox" name="user_status" id="switcher_checkbox_status" value="1" checked>
                    						<label for="switcher_checkbox_status"></label>
                    					</div>
                    				</div>
                    				<label class="col-md-5 col-form-label">
                    					<h5><span id="switch_checkbox_status" class="label label-green">Utilisateur est Active &nbsp;<i class="fas fa-eye"></i></span></h5>
                    				</label>

                    			</div>
                    		</div>
                    	</div>
                    	<legend></legend>
                    	<div class="col-sm-10">
							<div class="form-group row m-b-15 m-t-15 m-l-5">
								<label class="col-sm-5 col-form-label">Etat du demande de partenariat :</label>
								<div class="col-sm-5">
								<select id="selectInput2" name="partner_request" class="form-control" required>
                    					<option value="P"> En attente </option>
                    					<option value="A"> Accepté </option>
                    					<option value="R"> Refusé </option>
                    					
                    				</select>
								</div>
							</div>
                    	</div>
                    	<legend></legend>
                    	<div class="col-sm-10">
							<div class="form-group row m-b-15 m-t-15 m-l-5">
								<label class="col-sm-5 col-form-label">Envoyer Email de Mot de passe :</label>
								<div class="col-sm-5">
								<div class="input-group" id="btn-send-pwd" data-id=""><input type="text" id="user-pwd" data-auth="3" class="form-control" placeholder="mode de passe"><div class="input-group-append"><button class="btn btn-primary" type="button" id="send-pwd"><i id="send-icon" class="fas fa-paper-plane"></i></button></div></div>
								</div>
							</div>
                    	</div>
                    </div>