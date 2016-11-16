<?php
if(!$_Oli->verifyAuthKey() OR $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) < $_Oli->translateUserRight('USER'))
	header('Location: ' . $_Oli->getShortcutLink('login'));

if($_Oli->getUrlParam(2) == 'edit' AND !empty($_Oli->getUrlParam(3)) AND !$_Oli->isEmptyPostVars()) {
	if(empty($_Oli->getPostVars('expireDate')) OR empty($_Oli->getPostVars('expireTime')))
		$resultCode = 'EXPIRE_DATE_EMPTY';
	else if(strtotime($_Oli->getPostVars('expireDate') . ' ' . $_Oli->getPostVars('expireTime')) < time())
		$resultCode = 'LOW_EXPIRE_DATE';
	else if(strtotime($_Oli->getPostVars('expireDate') . ' ' . $_Oli->getPostVars('expireTime')) > strtotime($_Oli->getAccountInfos('SESSIONS', 'expire_date', array('id' => $_Oli->getUrlParam(3)))))
		$resultCode = 'HIGH_EXPIRE_DATE';
	else if(!$_Oli->isExistAccountInfos('SESSIONS', array('id' => $_Oli->getUrlParam(3))))
		$resultCode = 'UNKNOWN_SESSION';
	else if($_Oli->getAccountInfos('SESSIONS', 'username', array('id' => $_Oli->getUrlParam(3))) == $_Oli->getAuthKeyOwner()) {
		$updatedSessionId = $_Oli->getUrlParam(3);
		
		$expireDate = date('Y-m-d H:i:s', strtotime(((!empty($_Oli->getPostVars('expireDate'))) ? $_Oli->getPostVars('expireDate') : date('Y-m-d')) . ((!empty($_Oli->getPostVars('expireTime'))) ? $_Oli->getPostVars('expireTime') : date('H:i:s'))));
		
		$_Oli->updateAccountInfos('SESSIONS', array('expire_date' => $expireDate), array('id' => $_Oli->getUrlParam(3)));
		$resultCode = 'SESSION_EDITED';
	}
	else
		$resultCode = 'NOT_YOUR_SESSION';
}
else if($_Oli->getUrlParam(2) == 'delete' AND !empty($_Oli->getUrlParam(3))) {
	$paramData = urldecode($_Oli->getUrlParam(3));
	$selectedSessions = (!is_array($paramData)) ? ((is_array(unserialize($paramData))) ? unserialize($paramData) : [$paramData]) : $paramData;
	
	$errorStatus = '';
	foreach($selectedSessions as $eachKey) {
		if(!$_Oli->isExistAccountInfos('SESSIONS', array('id' => $eachKey))) {
			$errorStatus = 'UNKNOWN_SESSION';
			break;
		}
		else if($_Oli->getAccountInfos('SESSIONS', 'auth_key', array('id' => $eachKey)) == $_Oli->getAuthKey()) {
			$errorStatus = 'CURRENT_SESSION';
			break;
		}
		else if($_Oli->getAccountInfos('SESSIONS', 'username', array('id' => $eachKey)) != $_Oli->getAuthKeyOwner()) {
			$errorStatus = 'NOT_YOUR_SESSION';
			break;
		}
	}
	
	if(!empty($errorStatus))
		$resultCode = $errorStatus;
	else if($_Oli->getUrlParam(4) != 'confirmed')
		$resultCode = 'CONFIRMATION_NEEDED';
	else {
		foreach($selectedSessions as $eachKey) {
			$_Oli->deleteAccountLines('SESSIONS', array('id' => $eachKey));
		}
		$resultCode = 'SESSION_TERMINATED';
	}
}
?>

<!DOCTYPE html>
<html>
<head>

<?php include 'head.php'; ?>
<title>Sessions actives - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include 'header.php'; ?>

<div class="header">
	<div class="container">
		<h1>Sessions actives</h1>
		<p>
			Page de gestion de vos sessions actives
		</p>
	</div>
</div>

<?php if($_Oli->getUrlParam(2) == 'delete' AND !empty($_Oli->getUrlParam(3)) AND $resultCode == 'CONFIRMATION_NEEDED') { ?>
	<div class="message message-warning">
		<div class="container">
			<h1>Confirmez la déconnexion des requêtes sélectionnées</h1>
			<p>
				<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $_Oli->getUrlParam(3); ?>/confirmed" class="btn btn-primary btn-block">
					<i class="fa fa-check fa-fw"></i> J'autorise la déconnexion de ces sessions
				</a>
				<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/" class="btn btn-danger btn-block">
					<i class="fa fa-times fa-fw"></i> Je refuse de terminer ces sessions
				</a>
			</p>
		</div>
	</div>
<?php } else if($resultCode == 'EXPIRE_DATE_EMPTY') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>La date d'expiration ne peut pas être laissé vide</h2>
		</div>
	</div>
<?php } else if($resultCode == 'LOW_EXPIRE_DATE') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>La date d'expiration ne peut pas être antérieur à maintenant</h2>
		</div>
	</div>
<?php } else if($resultCode == 'HIGH_EXPIRE_DATE') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>La date d'expiration ne peut pas être supérieur à la date d'expiration précédante</h2>
		</div>
	</div>
<?php } else if($resultCode == 'UNKNOWN_SESSION') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous avez tenté d'effectuer une action sur une session qui nous est inconnue ou qui n'existe pas</h2>
		</div>
	</div>
<?php } else if($resultCode == 'SESSION_EDITED') { ?>
	<div class="message message-success">
		<div class="container">
			<h2>Les informations liées à la session #<?php echo $updatedSessionId; ?> ont bien été mises à jour</h2>
		</div>
	</div>
<?php } else if($resultCode == 'CURRENT_SESSION') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous avez tenté d'effectuer une action sur votre session active</h2>
		</div>
	</div>
<?php } else if($resultCode == 'SESSION_TERMINATED') { ?>
	<div class="message message-success">
		<div class="container">
			<h2>Les sessions sélectionnées ont bien été déconnectées</h2>
		</div>
	</div>
<?php } else if($resultCode == 'NOT_YOUR_SESSION') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous avez tenté d'effectuer une action sur une session qui ne vous appartient pas</h2>
		</div>
	</div>
<?php } ?>

<div class="message" id="scriptMessage" style="display: none;">
	<div class="container">
		<h2></h2>
		<p></p>
	</div>
</div>

<div class="main">
	<?php if($_Oli->getUrlParam(2) == 'edit' AND !empty($_Oli->getUrlParam(3)) AND $_Oli->isExistAccountInfos('SESSIONS', array('id' => $_Oli->getUrlParam(3))) AND strtotime($_Oli->getAccountInfos('SESSIONS', 'expire_date', array('id' => $_Oli->getUrlParam(3)))) >= time() AND empty($updatedSessionId)) { ?>
		<div class="container">
			<?php $sessionInfos = $_Oli->getAccountLines('SESSIONS', array('id' => $_Oli->getUrlParam(3))); ?>
			<form action="<?php echo $_Oli->getOption('url'); ?>form.php" class="form form-horizontal" method="post">
				<h1>Edition d'une session</h1>
				<div class="form-group">
					<label class="col-sm-2 control-label">Infos</label>
					<div class="col-sm-10">
						ID : #<?php echo $_Oli->getUrlParam(3); ?> <br />
						Clé : <?php echo str_repeat('*', (strlen($sessionInfos['auth_key']) <= 8) ? strlen($sessionInfos['auth_key']) : 8); ?> <br />
						Adresse IP : <?php echo $sessionInfos['user_ip']; ?> <br />
						Date de connexion : <?php echo date('d/m/Y H:i', strtotime($sessionInfos['login_date'])); ?> <br />
						Date d'expiration : <?php echo date('d/m/Y H:i', strtotime($sessionInfos['expire_date'])); ?> <br />
						
						<?php $timeOutput = []; ?>
						<?php foreach($_Oli->dateDifference($sessionInfos['login_date'], $sessionInfos['expire_date'], true) as $eachUnit => $eachTime) { ?>
							<?php if(count($timeOutput) < 2) { ?>
								<?php if($eachTime > 0) { ?>
									<?php if($eachUnit == 'years') { ?>
										<?php $timeOutput[] = $eachTime . ' an' . (($eachTime > 1) ? 's' : ''); ?>
									<?php } else if($eachUnit == 'days') { ?>
										<?php $timeOutput[] = $eachTime . ' jour' . (($eachTime > 1) ? 's' : ''); ?>
									<?php } else if($eachUnit == 'hours') { ?>
										<?php $timeOutput[] = $eachTime . ' heure' . (($eachTime > 1) ? 's' : ''); ?>
									<?php } else if($eachUnit == 'minutes') { ?>
										<?php $timeOutput[] = $eachTime . ' minute' . (($eachTime > 1) ? 's' : ''); ?>
									<?php } else if($eachUnit == 'seconds') { ?>
										<?php $timeOutput[] = $eachTime . ' seconde' . (($eachTime > 1) ? 's' : ''); ?>
									<?php } ?>
								<?php } ?>
							<?php } else break; ?>
						<?php } ?>
						
						<span class="text-muted">
							<i class="fa fa-angle-right"></i>
							soit une durée de validité de <?php echo $timeOutput[0]; ?>
							<?php if(count($timeOutput) > 1) { ?>
								<small>
									<?php if(count($timeOutput) > 2) { ?>
										, <?php echo implode(', ', array_splice($timeOutput, 1, count($timeOutput) - 2)); ?>
									<?php } ?>
									et <?php echo $timeOutput[count($timeOutput) - 1]; ?>
								</small>
							<?php } ?>
						</span>
						
						<p class="help-block">
							<span class="text-danger">
								<i class="fa fa-warning fa-fw"></i> Vous ne pouvez pas définir une date d'expiration qui étendrait la durée de validité.
								Vous pouvez seulement la réduire.
							</span>
						</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Expiration</label>
					<div class="col-sm-5">
						<input type="date" class="form-control" name="expireDate" value="<?php echo date('Y-m-d', strtotime($sessionInfos['expire_date'])); ?>" max="<?php echo date('Y-m-d', strtotime($sessionInfos['expire_date'])); ?>" />
					</div>
					<div class="col-sm-5">
						<input type="time" class="form-control" name="expireTime" value="<?php echo date('H:i', strtotime($sessionInfos['expire_date'])); ?>" />
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-primary"><i class="fa fa-pencil fa-fw"></i> Mettre à jour</button>
						<button type="reset" class="btn btn-default"><i class="fa fa-refresh fa-fw"></i> Réinitialiser</button>
					</div>
				</div>
			</form>
		</div> <hr />
	<?php } ?>
	
	<div class="container">
		<?php //$currentSession = $_Oli->getAccountLines('SESSIONS', array('auth_key' => $_Oli->getAuthKey())); ?>
		<?php $yourSessions = $_Oli->getAccountLines('SESSIONS', array('username' => $_Oli->getAuthKeyOwner()), true, true); ?>
		<?php if(!empty($yourSessions)) { ?>
			<h1>Vos sessions actives</h1>
			<p>
				<i class="fa fa-sort-numeric-desc fa-fw"></i> Triés de la plus récente à la plus ancienne <br />
				Toutes vos sessions actives pour aujourd'hui, le <?php echo date('d/m/Y'); ?> à <?php echo date('H:i'); ?> <br />
				Les différences horaires affichées sont tronquées
			</p>
			
			<table class="table table-hover">
				<thead>
					<tr>
						<th class="selector-menu"><i class="fa fa-check fa-fw"></i></th>
						<th>Clé</th>
						<th>Adresse IP</th>
						<th>Connexion d'origine</th>
						<th>Durée de validité</th>
						<th>Dernière utilisation</th>
						<th colspan="2"></th>
					</tr>
				</thead>
				<tbody>
					<?php $countSessions = count($yourSessions); ?>
					<?php foreach(array_reverse($yourSessions) as $eachSession) { ?>
						<?php if(strtotime($eachSession['expire_date']) >= time()) { ?>
							<tr <?php if($eachSession['auth_key'] == $_Oli->getAuthKey()) { ?>class="info"<?php } ?> id="<?php echo $eachSession['id']; ?>">
								<?php if($eachSession['auth_key'] != $_Oli->getAuthKey()) { ?>
									<?php if(!empty($selectedSessions) AND in_array($eachSession['id'], $selectedSessions)) { ?>
										<td class="selector checked">
											<i class="fa fa-check-square fa-fw"></i>
										</td>
									<?php } else { ?>
										<td class="selector">
											<i class="fa fa-square-o fa-fw"></i>
										</td>
									<?php } ?>
								<?php } else { ?>
									<td></td>
								<?php } ?>
								
								<td><?php echo str_repeat('*', (strlen($eachSession['auth_key']) <= 8) ? strlen($eachSession['auth_key']) : 8); ?></td>
								<td><?php echo $eachSession['user_ip']; ?></td>
								<td>
									<?php $timeOutput = []; ?>
									<?php foreach($_Oli->dateDifference($eachSession['login_date'], time(), true) as $eachUnit => $eachTime) { ?>
										<?php if(count($timeOutput) < 2) { ?>
											<?php if($eachTime > 0) { ?>
												<?php if($eachUnit == 'years') { ?>
													<?php $timeOutput[] = $eachTime . ' an' . (($eachTime > 1) ? 's' : ''); ?>
												<?php } else if($eachUnit == 'days') { ?>
													<?php $timeOutput[] = $eachTime . ' jour' . (($eachTime > 1) ? 's' : ''); ?>
												<?php } else if($eachUnit == 'hours') { ?>
													<?php $timeOutput[] = $eachTime . ' heure' . (($eachTime > 1) ? 's' : ''); ?>
												<?php } else if($eachUnit == 'minutes') { ?>
													<?php $timeOutput[] = $eachTime . ' minute' . (($eachTime > 1) ? 's' : ''); ?>
												<?php } else if($eachUnit == 'seconds') { ?>
													<?php $timeOutput[] = $eachTime . ' seconde' . (($eachTime > 1) ? 's' : ''); ?>
												<?php } ?>
											<?php } ?>
										<?php } else break; ?>
									<?php } ?>
									
									Il y a <?php echo $timeOutput[0]; ?>
									<?php if(count($timeOutput) > 1) { ?>
										<small>
											<?php if(count($timeOutput) > 2) { ?>
												, <?php echo implode(', ', array_splice($timeOutput, 1, count($timeOutput) - 2)); ?>
											<?php } ?>
											et <?php echo $timeOutput[count($timeOutput) - 1]; ?>
										</small>
									<?php } ?>
								</td>
								<td>
									<?php if($_Oli->getUrlParam(2) == 'edit' AND $eachSession['id'] == $_Oli->getUrlParam(3) AND empty($updatedSessionId)) { ?>
										<span class="text-info">
											<i class="fa fa-angle-up fa-fw"></i> Edition
										</span>
									<?php } else { ?>
										<?php $timeOutput = []; ?>
										<?php foreach($_Oli->dateDifference($eachSession['login_date'], $eachSession['expire_date'], true) as $eachUnit => $eachTime) { ?>
											<?php if(count($timeOutput) < 2) { ?>
												<?php if($eachTime > 0) { ?>
													<?php if($eachUnit == 'years') { ?>
														<?php $timeOutput[] = $eachTime . ' an' . (($eachTime > 1) ? 's' : ''); ?>
													<?php } else if($eachUnit == 'days') { ?>
														<?php $timeOutput[] = $eachTime . ' jour' . (($eachTime > 1) ? 's' : ''); ?>
													<?php } else if($eachUnit == 'hours') { ?>
														<?php $timeOutput[] = $eachTime . ' heure' . (($eachTime > 1) ? 's' : ''); ?>
													<?php } else if($eachUnit == 'minutes') { ?>
														<?php $timeOutput[] = $eachTime . ' minute' . (($eachTime > 1) ? 's' : ''); ?>
													<?php } else if($eachUnit == 'seconds') { ?>
														<?php $timeOutput[] = $eachTime . ' seconde' . (($eachTime > 1) ? 's' : ''); ?>
													<?php } ?>
												<?php } ?>
											<?php } else break; ?>
										<?php } ?>
										
										Pendant <?php echo $timeOutput[0]; ?>
										<?php if(count($timeOutput) > 1) { ?>
											<small>
												<?php if(count($timeOutput) > 2) { ?>
													, <?php echo implode(', ', array_splice($timeOutput, 1, count($timeOutput) - 2)); ?>
												<?php } ?>
												et <?php echo $timeOutput[count($timeOutput) - 1]; ?>
											</small>
										<?php } ?>
									<?php } ?>
								</td>
								<td>
									<?php if($eachSession['auth_key'] != $_Oli->getAuthKey()) { ?>
										<?php $timeOutput = []; ?>
										<?php foreach($_Oli->dateDifference($eachSession['update_date'], time(), true) as $eachUnit => $eachTime) { ?>
											<?php if(count($timeOutput) < 2) { ?>
												<?php if($eachTime > 0) { ?>
													<?php if($eachUnit == 'years') { ?>
														<?php $timeOutput[] = $eachTime . ' an' . (($eachTime > 1) ? 's' : ''); ?>
													<?php } else if($eachUnit == 'days') { ?>
														<?php $timeOutput[] = $eachTime . ' jour' . (($eachTime > 1) ? 's' : ''); ?>
													<?php } else if($eachUnit == 'hours') { ?>
														<?php $timeOutput[] = $eachTime . ' heure' . (($eachTime > 1) ? 's' : ''); ?>
													<?php } else if($eachUnit == 'minutes') { ?>
														<?php $timeOutput[] = $eachTime . ' minute' . (($eachTime > 1) ? 's' : ''); ?>
													<?php } else if($eachUnit == 'seconds') { ?>
														<?php $timeOutput[] = $eachTime . ' seconde' . (($eachTime > 1) ? 's' : ''); ?>
													<?php } ?>
												<?php } ?>
											<?php } else break; ?>
										<?php } ?>
										
										Il y a <?php echo $timeOutput[0]; ?>
										<?php if(count($timeOutput) > 1) { ?>
											<small>
												<?php if(count($timeOutput) > 2) { ?>
													, <?php echo implode(', ', array_splice($timeOutput, 1, count($timeOutput) - 2)); ?>
												<?php } ?>
												et <?php echo $timeOutput[count($timeOutput) - 1]; ?>
											</small>
										<?php } ?>
									<?php } else { ?>
										<span class="text-primary"><b>Session actuelle</b></span>
									<?php } ?>
								</td>
								<td>
									<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/edit/<?php echo $eachSession['id']; ?>" class="btn btn-primary btn-xs">
										Edit <i class="fa fa-pencil fa-fw"></i>
									</a>
								</td>
								<td>
									<?php if($eachSession['auth_key'] != $_Oli->getAuthKey()) { ?>
										<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/delete/<?php echo $eachSession['id']; ?>" class="btn btn-danger btn-xs">
											Delete <i class="fa fa-trash fa-fw"></i>
										</a>
									<?php } ?>
								</td>
							</tr>
						<?php } else { ?>
							<?php $_Oli->deleteAccountLines('SESSIONS', array('id' => $eachSession['id'])); ?>
							<?php $countSessions--; ?>
							
							<tr class="danger">
								<td></td>
								<td colspan="7">
									Une session expirée vient d'être supprimée.
								</td>
							</tr>
						<?php } ?>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5">
							<a href="#selectAll" class="selectAll btn btn-primary btn-xs">
								Select All <i class="fa fa-check-square fa-fw"></i>
							</a>
							<a href="#unselectAll" class="unselectAll btn btn-danger btn-xs">
								Unselect All <i class="fa fa-square-o fa-fw"></i>
							</a>
						</td>
						<td colspan="2"><?php echo $countSessions; ?> <small>session<?php if($countSessions > 1) { ?>s<?php } ?></small></td>
						<td>
							<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/delete/" class="deleteSelected btn btn-danger btn-xs">
								Selected <i class="fa fa-trash fa-fw"></i>
							</a>
						</td>
					</tr>
				</tfoot>
			</table>
		<?php } else { ?>
			<h3>Vous n'avez aucune session active (ce ne qui n'est pas normal ;_;).</h3>
		<?php } ?>
	</div>
</div>

<?php include 'footer.php'; ?>

<script>
(function($) {

$('.selector').click(function() {
	$(this).toggleClass('checked');
	
	if($(this).hasClass('checked')) {
		$(this).find('.fa').removeClass('fa-square-o');
		$(this).find('.fa').addClass('fa-check-square');
	}
	else {
		$(this).find('.fa').removeClass('fa-check-square');
		$(this).find('.fa').addClass('fa-square-o');
	}
});
$('.selectAll').click(function(e) {
	e.preventDefault();
	$('.selector').addClass('checked');
	$('.selector').each(function() {
		if($(this).hasClass('checked')) {
			$(this).find('.fa').removeClass('fa-square-o');
			$(this).find('.fa').addClass('fa-check-square');
		}
		else {
			$(this).find('.fa').removeClass('fa-check-square');
			$(this).find('.fa').addClass('fa-square-o');
		}
	});
	return false;
});
$('.unselectAll').click(function(e) {
	e.preventDefault();
	$('.selector').removeClass('checked');
	$('.selector').each(function() {
		if($(this).hasClass('checked')) {
			$(this).find('.fa').removeClass('fa-square-o');
			$(this).find('.fa').addClass('fa-check-square');
		}
		else {
			$(this).find('.fa').removeClass('fa-check-square');
			$(this).find('.fa').addClass('fa-square-o');
		}
	});
	return false;
});
$('.deleteSelected').click(function(e) {
	e.preventDefault();
	$('#scriptMessage').hide().removeClass().addClass('message');
	
	var selectArray = [];
	$('.selector.checked').parent().each(function() {
		selectArray.push($(this).attr('id'));
	});
	
	if(selectArray.length > 0) {
		// alert(serialize(selectArray));
		var url = $(this).attr('href') + encodeURIComponent(serialize(selectArray));
		window.location = url;
	}
	else {
		$('#scriptMessage').addClass('message-danger');
		$('#scriptMessage').find('h2').empty().append(
			$('<i>').addClass('fa fa-times fa-fw'),
			' Rien n\'a été sélectionné'
		);
		$('#scriptMessage').find('p').empty();
		$('#scriptMessage').show();
	}
	return false;
});

})(jQuery);
</script>

</body>
</html>