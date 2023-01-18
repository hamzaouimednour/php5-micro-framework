					<div class="row">
                    	<div class="col-sm-6">
                    		<div class="col">
                    			<div class="form-group">
                    				<label for="textInput1">Nom complet du responsable <small class="text-danger" title="champ obligatoire">*</small></label>
                    				<input type="text" class="form-control" id="textInput1" name="user-full_name" placeholder="Nom & Prénom" required>
                    				<div class="invalid-feedback">Ce champ est obligatoire.</div>
                    			</div>
                    		</div>

                    		<div class="col">
                    			<div class="form-group">
                    				<label for="textInput1">Nom du restaurant <small class="text-danger" title="champ obligatoire">*</small></label>
                    				<input type="text" class="form-control" id="textInput2" name="user-restaurant_name" placeholder="Num du Resto" required>
                    				<div class="invalid-feedback">Ce champ est obligatoire.</div>
                    			</div>
							</div>
							<div class="col">
                    			<div class="form-group">
                    				<label for="selectInput1">Livraison <small class="text-danger" title="champ obligatoire">*</small></label>
                    				<select id="selectInput1" name="user-delivery_type" class="form-control" required>
                    					<option value="" selected disabled> Sélectionnez un type de Livraison </option>
                    					<option value="1">Propre service de livraison</option>
                    					<option value="2">Nos service de livraison</option>
                    				</select>
                    				<div class="invalid-feedback">Ce champ est obligatoire.</div>
                    			</div>
							</div>
                    		
							<div class="col">
                    			<div class="form-group">
                    				<label for="textInput3">Mot de passe</label>
                    				<input type="text" name="user-passwd" id="password-indicator-visible" class="form-control m-b-5" />
                                        <div id="passwordStrengthDiv2" class="is0 m-t-5"></div>
                    				<div class="invalid-feedback">Ce champ est obligatoire.</div>
                    			</div>
                    		</div>
                    	</div>

                    	<div class="col-sm-6">
                    		<div class="col">
                    			<div class="form-group">
                    				<label for="textInput4">Email <small class="text-danger" title="champ obligatoire">*</small></label>
                    				<input type="email" class="form-control" id="textInput4" name="user-email" placeholder="foulen@example.com" required>
                    				<div class="invalid-feedback">Ce champ est obligatoire.</div>
                    			</div>
                    		</div>
							<div class="col">
                    			<div class="form-group">
                    				<label for="select2Input1">Spécialités <small class="text-danger" title="champ obligatoire">*</small></label>
                    				<select id="select2Input1" name="user-specialties[]" class="form-control multiple-select2"  multiple="multiple"  data-placeholder="Sélectionnez la/les spécialité(s)" required>
										<optgroup label="Sélectionnez la/les spécialité(s)">
										<?php
											$specialties = (new Specialties)->getAll();
											foreach($specialties as $specialty){
										?>
											<option value="<?=$specialty->getId()?>"><?=$specialty->getSpecialty()?></option>
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
                    				<label for="textInput3">Phone <small class="text-danger" title="champ obligatoire">*</small></label>
                    				<input type="text" class="form-control" name="user-phone" id="textInput3" placeholder="Numéro de téléphone" required>
                    				<div class="invalid-feedback">Ce champ est obligatoire.</div>
                    			</div>
							</div>
                    		<div class="col">
                    			<label for="select2">Etat d'utilisateur</label>
                    			<div class="form-group row m-b-10">
                    				<div class="col-md-2 p-t-3">
                    					<div class="switcher switcher-green">
                    						<input type="checkbox" name="user-user_status" id="switcher_checkbox_status" value="1" checked>
                    						<label for="switcher_checkbox_status"></label>
                    					</div>
                    				</div>
                    				<label class="col-md-5 col-form-label">
                    					<h5><span id="switch_checkbox_status" class="label label-green">Utilisateur est Active &nbsp;<i class="fas fa-eye"></i></span></h5>
                    				</label>

                    			</div>
							</div>                    		
                    	</div>
                    </div>