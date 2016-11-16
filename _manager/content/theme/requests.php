<?php
if(!$_Oli->verifyAuthKey() OR $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) < $_Oli->translateUserRight('USER'))
	header('Location: ' . $_Oli->getShortcutLink('login'));

if($_Oli->getUrlParam(2) == 'delete' AND !empty($_Oli->getUrlParam(3))) {
	$paramData = urldecode($_Oli->getUrlParam(3));
	$selectedRequests = (!is_array($paramData)) ? ((is_array(unserialize($paramData))) ? unserialize($paramData) : [$paramData]) : $paramData;
	
	$errorStatus = '';
	foreach($selectedRequests as $eachKey) {
		if(!$_Oli->isExistAccountInfos('REQUESTS', array('id' => $eachKey))) {
			$errorStatus = 'UNKNOWN_REQUEST';
			break;
		}
		else if($_Oli->getAccountInfos('REQUESTS', 'username', array('id' => $eachKey)) != $_Oli->getAuthKeyOwner()) {
			$errorStatus = 'NOT_YOUR_REQUEST';
			break;
		}
	}
	
	if(!empty($errorStatus))
		$resultCode = $errorStatus;
	else if($_Oli->getUrlParam(4) != 'confirmed')
		$resultCode = 'CONFIRMATION_NEEDED';
	else {
		foreach($selectedRequests as $eachKey) {
			$_Oli->deleteAccountLines('REQUESTS', array('id' => $eachKey));
		}
		$resultCode = 'REQUEST_CANCELED';
	}
}
?>

<!DOCTYPE html>
<html>
<head>

<?php include 'head.php'; ?>
<title>Requêtes actives - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include 'header.php'; ?>

<div class="header">
	<div class="container">
		<h1>Requêtes actives</h1>
		<p>
			Page de gestion de vos requêtes actives
		</p>
	</div>
</div>

<?php if($_Oli->getUrlParam(2) == 'delete' AND !empty($_Oli->getUrlParam(3)) AND $resultCode == 'CONFIRMATION_NEEDED') { ?>
	<div class="message message-warning">
		<div class="container">
			<h1>Confirmez l'annulation des requêtes sélectionnées</h1>
			<p>
				<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $_Oli->getUrlParam(3); ?>/confirmed" class="btn btn-primary btn-block">
					<i class="fa fa-check fa-fw"></i> J'autorise l'annulation de ces requêtes
				</a>
				<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/" class="btn btn-danger btn-block">
					<i class="fa fa-times fa-fw"></i> Je refuse de supprimer ces requêtes
				</a>
			</p>
		</div>
	</div>
<?php } else if($resultCode == 'UNKNOWN_REQUEST') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous avez tenté d'effectuer une action sur une requête qui nous est inconnue ou qui n'existe pas</h2>
		</div>
	</div>
<?php } else if($resultCode == 'REQUEST_CANCELED') { ?>
	<div class="message message-success">
		<div class="container">
			<h2>Les requêtes sélectionnées ont bien été annulées</h2>
		</div>
	</div>
<?php } else if($resultCode == 'NOT_YOUR_REQUEST') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous avez tenté d'effectuer une action sur une requête qui ne vous appartient pas</h2>
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
	<div class="container">
		<?php $yourRequests = $_Oli->getAccountLines('REQUESTS', array('username' => $_Oli->getAuthKeyOwner()), true, true); ?>
		<?php if(!empty($yourRequests)) { ?>
			<h1>Vos requêtes actives</h1>
			<p>
				<i class="fa fa-sort-numeric-desc fa-fw"></i> Triés de la plus récente à la plus ancienne <br />
				Toutes vos requêtes actives pour aujourd'hui, le <?php echo date('d/m/Y'); ?> à <?php echo date('H:i:s'); ?> <br />
				Les différences horaires affichées sont tronquées
			</p>
			
			<table class="table table-hover">
				<thead>
					<tr>
						<th class="selector-menu"><i class="fa fa-check fa-fw"></i></th>
						<th>Clé</th>
						<th>Action associée</th>
						<th>Date de création</th>
						<th>Durée de validité</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php $countRequests = count($yourRequests); ?>
					<?php foreach(array_reverse($yourRequests) as $eachRequest) { ?>
						<?php if(strtotime($eachRequest['expire_date']) >= time()) { ?>
							<tr id="<?php echo $eachRequest['id']; ?>">
								<?php if(!empty($selectedRequests) AND in_array($eachRequest['id'], $selectedRequests)) { ?>
									<td class="selector checked">
										<i class="fa fa-check-square fa-fw"></i>
									</td>
								<?php } else { ?>
									<td class="selector">
										<i class="fa fa-square-o fa-fw"></i>
									</td>
								<?php } ?>
								
								<td><?php echo str_repeat('*', (strlen($eachRequest['activate_key']) <= 8) ? strlen($eachRequest['activate_key']) : 8); ?></td>
								<td><?php echo $eachRequest['action']; ?></td>
								<td>
									<?php $timeOutput = []; ?>
									<?php foreach(array_slice($_Oli->dateDifference($eachRequest['request_date'], time(), true), 0, 2) as $eachUnit => $eachTime) { ?>
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
									<?php } ?>
									
									Il y a <?php echo $timeOutput[0]; ?>
									<?php if(count($timeOutput) > 1) { ?>
										<small>
											<?php if(count($timeOutput) > 2) { ?>
												, <?php echo implode(', ', array_splice($timeOutput, 1, count($timeOutput) - 1)); ?>
											<?php } ?>
											et <?php echo $timeOutput[count($timeOutput) - 1]; ?>
										</small>
									<?php } ?>
								</td>
								<td>
									<?php $timeOutput = []; ?>
									<?php foreach(array_slice($_Oli->dateDifference($eachRequest['request_date'], $eachRequest['expire_date'], true), 0, 2) as $eachUnit => $eachTime) { ?>
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
									<?php } ?>
									
									Pendant <?php echo $timeOutput[0]; ?>
									<?php if(count($timeOutput) > 1) { ?>
										<small>
											<?php if(count($timeOutput) > 2) { ?>
												, <?php echo implode(', ', array_splice($timeOutput, 1, count($timeOutput) - 1)); ?>
											<?php } ?>
											et <?php echo $timeOutput[count($timeOutput) - 1]; ?>
										</small>
									<?php } ?>
								</td>
								<td>
									<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/delete/<?php echo $eachRequest['id']; ?>" class="btn btn-danger btn-xs">
										Delete <i class="fa fa-trash fa-fw"></i>
									</a>
								</td>
							</tr>
						<?php } else { ?>
							<?php $_Oli->deleteAccountLines('REQUESTS', array('id' => $eachRequest['id'])); ?>
							<?php $countRequests--; ?>
							
							<tr class="danger">
								<td></td>
								<td colspan="5">
									Une requête expirée vient d'être supprimée.
								</td>
							</tr>
						<?php } ?>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4">
							<a href="#selectAll" class="selectAll btn btn-primary btn-xs">
								Select All <i class="fa fa-check-square fa-fw"></i>
							</a>
							<a href="#unselectAll" class="unselectAll btn btn-danger btn-xs">
								Unselect All <i class="fa fa-square-o fa-fw"></i>
							</a>
						</td>
						<td><?php echo $countRequests; ?> <small>requête<?php if($countRequests > 1) { ?>s<?php } ?></small></td>
						<td>
							<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/delete/" class="deleteSelected btn btn-danger btn-xs">
								Selected <i class="fa fa-trash fa-fw"></i>
							</a>
						</td>
					</tr>
				</tfoot>
			</table>
		<?php } else { ?>
			<h3>Vous n'avez aucune requête en cours.</h3>
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