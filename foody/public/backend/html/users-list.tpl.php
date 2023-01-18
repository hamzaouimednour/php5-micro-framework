<?php
list($dataUser, $userAuth) = null;
switch (ucfirst($this->getParamsIndexOf())) {
	case 'Manager':
		$dataUser = (new Administrator)->getAll();
		$userAuth = 1;
		break;
	case 'Restaurant':
		$dataUser = (new Restaurant)->getAll();
		$userAuth = 2;
		break;
	case 'Customer':
		$userAuth = 4;
		if (Session::get('user_auth') == 1) {
			$dataUser = (new Customer)->getAll();
		}
		if (Session::get('user_auth') == 2) {
			$dataUser = (new Customer)->getAllByOrderRestaurantId(Session::get('user_id'));
		}
		break;
	case 'Delivery':
		$userAuth = 3;
		$dataUser = (new Delivery)->getAll();
		break;
}
?>
<!-- #modal-dialog -->
<div class="modal fade" id="modal-dialog">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><i class="icon-note"></i> Modifier Utilisateur</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="ion ion-ios-close-circle-outline"></i></button>
			</div>
			
			<form autocomplete="off" id="modal-form" class="needs-validation" novalidate>
				<div class="modal-body">
					<div id="modal-info-section"></div>
					<?php $this->requireTPL('user-'. $userAuth .'-edit') ?>
				</div>
				<div class="modal-footer">

					<input type="hidden" name="uid" value="" required>
					<input type="hidden" name="user_auth" value="" required>

					<button id="reset-modal-form" class="btn btn-white" data-dismiss="modal">Annuler</a>
					<button type="submit" class="btn btn-green">Enregistrer</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="panel panel-inverse">
	<!-- begin panel-heading -->
	<div class="panel-heading">
		<h4 class="panel-title">List des Utilisateurs</h4>
	</div>
	<!-- end panel-heading -->
	<!-- begin panel-body -->
	<div class="panel-body">
		<table id="data-table-default" class="table table-striped table-bordered" data-auth="<?= $userAuth ?>">
			<thead>
				<tr>
					<th width="1%" data-orderable="false"></th>
					<?php if($userAuth ==2) echo '<th width="1%" data-orderable="false"></th>'; ?> 
					<th class="text-nowrap" width="1%">Nom d'utilisateur</th>
					<?php if($userAuth ==2) echo '<th class="text-nowrap" width="1%">Nom du restaurant</th>'; ?> 
					<th class="text-nowrap">Email</th>
					<?php if($userAuth ==3) echo '<th class="text-nowrap text-center" width="1%">Véhicule</th>'; ?> 
					<?php if($userAuth ==3) echo '<th class="text-nowrap" width="1%">Disponibilité</th>'; ?> 
					<?php if($userAuth ==1) echo '<th class="text-nowrap">Username</th>'; ?> 
					<?php if($userAuth ==4) echo '<th class="text-nowrap">N° de Commandes</th>'; ?> 
					<th class="text-nowrap" >Date d'inscription</th>
					<?php if(in_array($userAuth, [2, 3])) echo '<th class="text-nowrap" width="1%">Partenariat</th>'; ?> 
					<th class="text-nowrap">Etat</th>
					<th width="1%" class="text-nowrap" data-orderable="false">Options</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if (!is_null($dataUser)) {
					foreach ($dataUser as $user) {
						?>
						<tr id="<?= $user->getUid() ?>">
							<td width="1%">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" id="checkbox-<?= $user->getUid() ?>" />
								</div>
							</td>
							<?php if($userAuth ==2) echo '<td width="1%" class="with-img"><img src="'.Handler::getCdnImage($user->getUid(), $user->getLogo(), 'crop', 128, 128).'" class="img-rounded height-30" /></td>'; ?> 
							<td class="f-s-600 text-inverse"><?= $user->getFullName() ?></td>
							<?php if($userAuth ==2) echo '<td>'. $user->getRestaurantName() .'</td>' ?>
							<td><?= $user->getEmail() ?></td>
							<?php $v = [1 => '<i class="fas fa-bicycle" data-toggle="tooltip" data-placement="bottom" title="Vélo"></i>', 2 => '<i class="fas fa-motorcycle" data-toggle="tooltip" data-placement="bottom" title="Moto"></i>', 3=>'<i class="fas fa-truck" data-toggle="tooltip" data-placement="bottom" title="Voiture"></i>']; if($userAuth ==3) echo '<td style="font-size:15px;" class="text-center">'. $v[$user->getVehicleId()] .'</td>' ?>
							<?php if($userAuth ==3) echo '<td>'. ($user->getAvailability()==0 ? '<strong class="label label-purple"><i class="fas fa-bell-slash"></i> &nbsp;Travaillé</strong>' : '<strong class="label label-success" ><i class="fas fa-bell"></i> &nbsp;Disponible</strong>') .'</td>' ?>
							<?php if($userAuth ==1) echo '<td>'. $user->getUsername() .'</td>' ?>
							<?php if($userAuth ==4) echo '<td>'. (new Order)->setCustomerId($user->getUid())->getCustomerNbrOrders() .'</td>' ?>
							<td><?= $user->getRegisterDate() ?></td>
							<?php $partnerStatus = ['P' => '<strong class="label label-warning"><i class="fas fa-sync"> &nbsp;En attente</strong>', 'A' => '<strong class="label label-green"><i class="fas fa-check-square"></i> Accepté</strong>', 'R' => '<strong class="label label-danger"><i class="fas fa-times"></i> Refusé</strong>']; if(in_array($userAuth, [2, 3])) echo '<td>'. $partnerStatus[$user->getPartnerRequest()] .'</td>'; ?> 
							<td id="status-<?= $user->getUid() ?>"><?= ($user->getUserStatus() == 1) ? '<span class="label label-green"><i class="fas fa-check-circle"></i> Active' : '<span class="label label-secondary"><i class="fas fa-times-circle"></i> Inactive'; ?></span></td>
							<td class="text-nowrap" width="11%">
								<button data-id="<?=$user->getUid()?>" class="btn btn-white btn-xs m-r-3 btn-status" data-toggle="tooltip" data-placement="left" title="<?= ($user->getUserStatus() != 1) ? 'Activer' : 'Inactiver' ?>" data-status="<?= $user->getUserStatus() ?>"><?= ($user->getUserStatus() == 1) ? '<i class="fas fa-user-alt-slash"></i>' : '&nbsp;<i class="fa fa-user"></i>&nbsp;' ?></button>
								<button data-id="<?=$user->getUid()?>" class="btn btn-white btn-xs m-r-3 btn-edit<?=$userAuth ==2 ? ' btn-r' : null?>" data-toggle="tooltip" data-placement="bottom" title="Modifier"><i class="fa fa-cog"></i></button>
								<button data-id="<?=$user->getUid()?>" class="btn btn-inverse btn-xs btn-delete"><i class="fa fa-trash" data-toggle="tooltip" data-placement="bottom" title="Supprimer"></i></button>
							</td>
						<?php
						}
					}
					?>
			</tbody>
		</table>
	</div>
	<!-- end panel-body -->
</div>