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
                    			<label for="switcher_checkbox_credibility">Crédibilité</label>
                    			<div class="form-group row m-b-10">
                    				<div class="col-md-2 p-t-3">
                    					<div class="switcher switcher-info">
                    						<input type="checkbox" name="credibility" id="switcher_checkbox_credibility" value="0" checked>
                    						<label for="switcher_checkbox_credibility"></label>
                    					</div>
                    				</div>
                    				<label class="col-md-5 col-form-label">
                    					<h5><span id="switcher_label_credibility" class="label label-info">Client est de confiance &nbsp;<i class="fas fa-thumbs-up"></i></span></h5>
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
                    </div>