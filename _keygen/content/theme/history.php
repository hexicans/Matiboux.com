<?php
if(!$_Oli->verifyAuthKey() OR $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) < $_Oli->translateUserRight('USER'))
	header('Location: ' . $_Oli->getShortcutLink('login'));

$keepDelay = 1440;
$encryptPasswordDelay = 60;
$encryptPasswordLength = 128;

$encryptPasswords = $_Oli->getLinesMySQL('keygen_encrypt_passwords', [], true, true);
$_Oli->deleteLinesMySQL('keygen_encrypt_passwords', 'all');

foreach($encryptPasswords as $eachEncryptPassword) {
	if((strtotime($eachEncryptPassword['creation_date']) + $keepDelay) >= time())
		$_Oli->insertLineMySQL('keygen_encrypt_passwords', array('id' => $_Oli->getLastInfoMySQL('keygen_encrypt_passwords', 'id') + 1, 'encrypt_password' => $eachEncryptPassword['encrypt_password'], 'encrypt_key_size' => $eachEncryptPassword['encrypt_key_size'], 'creation_date' => $eachEncryptPassword['creation_date']));
}
if((strtotime($_Oli->getLastInfoMySQL('keygen_encrypt_passwords', 'creation_date')) + $encryptPasswordDelay) < time()) {
	$_Oli->insertLineMySQL('keygen_encrypt_passwords', array('id' => $_Oli->getLastInfoMySQL('keygen_encrypt_passwords', 'id') + 1, 'encrypt_password' => $_Oli->keygen($encryptPasswordLength), 'encrypt_key_size' => '256', 'creation_date' => date('Y-m-d H:i:s')));
}

if(!$_Oli->isEmptyPostVars()) {
	if(empty($_Oli->getPostVars('decryptPassword')))
		$resultCode = 'EMPTY_PASSWORD';
	else if($_Oli->verifyLogin($_Oli->getAuthKeyOwner(), $_Oli->getPostVars('decryptPassword'))) {
		$decryptPassword = $_Oli->getPostVars('decryptPassword');
		
		/** Save Decrypt Password in a Cookie */
		$oldKeySize = GibberishAES::size();
		
		GibberishAES::size($_Oli->getLastInfoMySQL('keygen_encrypt_passwords', 'encrypt_key_size'));
		$encryptedPassword = GibberishAES::enc($decryptPassword, $_Oli->getLastInfoMySQL('keygen_encrypt_passwords', 'encrypt_password'));
		$_Oli->setCookie('decryptPassword', serialize(array('encryptedPassword' => $encryptedPassword, 'encryptPasswordDate' => $_Oli->getLastInfoMySQL('keygen_encrypt_passwords', 'creation_date'))), $keepDelay, '/', '.' . $_Oli->getOption('domain'));
		
		GibberishAES::size($oldKeySize); // Restore old Key Size
		$resultCode = 'DECRYPT_SUCCESS';
	}
	else
		$resultCode = 'WRONG_PASSWORD';
}
else if(!$_Oli->isEmptyCookie('decryptPassword')) {
	/** Get Decrypt Password in the Saved Cookie */
	$oldKeySize = GibberishAES::size();
	$decryptCookie = $_Oli->getCookieContent('decryptPassword');
	
	foreach(array_reverse($_Oli->getLinesMySQL('keygen_encrypt_passwords', [], true, true)) as $eachEncryptPassword) {
		if($eachEncryptPassword['creation_date'] == $decryptCookie['encryptPasswordDate']) {
			GibberishAES::size($eachEncryptPassword['encrypt_key_size']);
			$decryptPassword = GibberishAES::dec($decryptCookie['encryptedPassword'], $eachEncryptPassword['encrypt_password']);
			
			GibberishAES::size($oldKeySize); // Restore old Key Size
			break;
		}
	}
}

if(!empty($decryptPassword)) {
	if($_Oli->getUrlParam(2) == 'edit' AND !empty($_Oli->getUrlParam(3)) AND !$_Oli->isEmptyPostVars()) {
		if(!$_Oli->isExistInfosMySQL('keygen_history', array('id' => $_Oli->getUrlParam(3))))
			$resultCode = 'UNKNOWN_KEYGEN';
		else if($_Oli->getInfosMySQL('keygen_history', 'username', array('id' => $_Oli->getUrlParam(3))) == $_Oli->getAuthKeyOwner()) {
			$updatedKeyGen = true;
			
			$oldKeySize = GibberishAES::size();
			GibberishAES::size($encryptKeySize);
			$label = (!empty($_Oli->getPostVars()['label'])) ? GibberishAES::enc($_Oli->getPostVars()['label'], $decryptPassword) : '';
			GibberishAES::size($oldKeySize); // Restore old Key Size
			$_Oli->updateInfosMySQL('keygen_history', array('label' => $label), array('id' => $_Oli->getUrlParam(3)));
			$resultCode = 'KEYGEN_EDITED';
		}
		else
			$resultCode = 'NOT_YOUR_KEYGEN';
	}
	else if($_Oli->getUrlParam(2) == 'delete' AND !empty($_Oli->getUrlParam(3))) {
		$paramData = urldecode($_Oli->getUrlParam(3));
		$selectedKeygens = (!is_array($paramData)) ? ((is_array(unserialize($paramData))) ? unserialize($paramData) : [$paramData]) : $paramData;
		
		$errorStatus = '';
		foreach($selectedKeygens as $eachKey) {
			if(!$_Oli->isExistInfosMySQL('keygen_history', array('id' => $eachKey))) {
				$errorStatus = 'UNKNOWN_KEYGEN';
				break;
			}
			else if($_Oli->getInfosMySQL('keygen_history', 'username', array('id' => $eachKey)) != $_Oli->getAuthKeyOwner()) {
				$errorStatus = 'NOT_YOUR_KEYGEN';
				break;
			}
		}
		
		if(!empty($errorStatus))
			$resultCode = $errorStatus;
		else if($_Oli->getUrlParam(4) != 'confirmed')
			$resultCode = 'CONFIRMATION_NEEDED';
		else {
			foreach($selectedKeygens as $eachKey) {
				$_Oli->deleteLinesMySQL('keygen_history', array('id' => $eachKey));
			}
			$resultCode = 'KEYGEN_DELETED';
		}
	}
}
?>

<!DOCTYPE html>
<html>
<head>

<?php include 'head.php'; ?>
<title>Historique - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include 'header.php'; ?>

<div class="header">
	<div class="container">
		<h1><i class="fa fa-history fa-fw"></i> Historique</h1>
		<p>
			Page de gestion de votre historique.
		</p>
	</div>
</div>

<?php if($_Oli->getUrlParam(2) == 'delete' AND !empty($_Oli->getUrlParam(3)) AND $resultCode == 'CONFIRMATION_NEEDED') { ?>
	<div class="message message-warning">
		<div class="container">
			<h1>Confirmez la suppression des keygens sélectionnés</h1>
			<p>
				<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $_Oli->getUrlParam(3); ?>/confirmed" class="btn btn-primary btn-block">
					<i class="fa fa-check fa-fw"></i> J'autorise la suppression définive de ces keygens
				</a>
				<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/" class="btn btn-danger btn-block">
					<i class="fa fa-times fa-fw"></i> Je refuse de supprimer ces keygens
				</a>
			</p>
		</div>
	</div>
<?php } else if($resultCode == 'EMPTY_PASSWORD') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous devez entrer votre mot de passe avant de pouvoir continuer</h2>
		</div>
	</div>
<?php } else if($resultCode == 'DECRYPT_SUCCESS') { ?>
	<div class="message message-success">
		<div class="container">
			<h2>Votre historique a correctectement été déchiffré</h2>
			<p>
				Pour vous permettre une navigation agréable tout en assurant votre sécurité, votre mot de passe a été chiffré et mémorisé sur votre navigateur. <br />
				Notez que vous êtes le seul à avoir accès à votre mot de passe et que nous le conservons pas.
				De plus, le mot de passe chiffré et stocké sur votre navigateur devrait être oublié dans <?php echo round($keepDelay / 60); ?> minute<?php if(round($keepDelay / 60) > 1) { ?>s<?php } ?>.
			</p>
		</div>
	</div>
<?php } else if($resultCode == 'WRONG_PASSWORD') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous avez entré un mot de passe incorrect</h2>
		</div>
	</div>
<?php } else if($resultCode == 'UNKNOWN_KEYGEN') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous avez tenté d'effectuer une action sur un keygen qui nous est inconnu ou qui n'existe pas</h2>
		</div>
	</div>
<?php } else if($resultCode == 'NO_KEYGEN_OWNED') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous avez tenté d'effectuer une action alors que votre historique est vide</h2>
		</div>
	</div>
<?php } else if($resultCode == 'NOT_YOUR_KEYGEN') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous avez tenté d'effectuer une action sur un keygen qui ne vous appartient pas, celle-ci a donc échouée</h2>
		</div>
	</div>
<?php } else if($resultCode == 'KEYGEN_DELETED') { ?>
	<div class="message message-success">
		<div class="container">
			<h2>Le keygen a bien été supprimé de votre historique</h2>
		</div>
	</div>
<?php } else if($resultCode == 'ALL_KEYGEN_DELETED') { ?>
	<div class="message message-success">
		<div class="container">
			<h2>L'historique de vos keygens a été entièrement vidé</h2>
		</div>
	</div>
<?php } ?>

<div class="message" id="script-message" style="display: none;">
	<div class="container">
		<h2></h2>
		<p></p>
	</div>
</div>

<div class="main">
	<div class="container">
		<?php $yourKeygens = $_Oli->getLinesMySQL('keygen_history', array('username' => $_Oli->getAuthKeyOwner()), true, true); ?>
		<?php if(!empty($yourKeygens)) { ?>
			<?php if(!empty($decryptPassword)) { ?>
				<?php if($_Oli->getUrlParam(2) == 'edit' AND !empty($_Oli->getUrlParam(3)) AND $_Oli->isExistInfosMySQL('keygen_history', array('id' => $_Oli->getUrlParam(3))) AND !$updatedKeyGen) { ?>
					<a href="<?php echo $_Oli->getOption('url'); ?>history/" class="btn btn-primary btn-xs">
						<i class="fa fa-angle-left fa-fw"></i> Revenir à l'historique
					</a>
					
					<?php $keygenInfos = $_Oli->getLinesMySQL('keygen_history', array('id' => $_Oli->getUrlParam(3))); ?>
					<form action="<?php echo $_Oli->getOption('url'); ?>form.php" class="form form-horizontal" method="post">
						<?php $oldKeySize = GibberishAES::size(); ?>
						<?php GibberishAES::size($encryptKeySize); ?>
						<?php $keygenInfos['keygen'] = GibberishAES::dec($keygenInfos['keygen'], $decryptPassword); ?>
						<?php $keygenInfos['label'] = GibberishAES::dec($keygenInfos['label'], $decryptPassword); ?>
						<?php GibberishAES::size($oldKeySize); // Restore old Key Size ?>
						
						<h1>Edition du keygen</h1>
						<div class="form-group">
							<label class="col-sm-2 control-label">Keygen</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="keygen" value="<?php echo $keygenInfos['keygen']; ?>" disabled />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Label</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="label" value="<?php echo $keygenInfos['label']; ?>" />
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="submit" class="btn btn-primary"><i class="fa fa-pencil fa-fw"></i> Mettre à jour</button>
								<button type="reset" class="btn btn-default"><i class="fa fa-refresh fa-fw"></i> Réinitialiser</button>
							</div>
						</div>
					</form>
				<?php } else { ?>
					<table class="table table-hover">
						<thead>
							<tr>
								<th class="selector-menu"><i class="fa fa-check fa-fw"></i></th>
								<th>Keygen</th>
								<th>Label</th>
								<th>Date</th>
								<th>Heure</th>
								<th colspan="3"></th>
							</tr>
						</thead>
						<tbody>
							<?php $countKeygens = count($yourKeygens); ?>
							<?php foreach($yourKeygens as $eachKeygen) { ?>
								<?php $oldKeySize = GibberishAES::size(); ?>
								<?php GibberishAES::size($encryptKeySize); ?>
								<?php $eachKeygen['keygen'] = GibberishAES::dec($eachKeygen['keygen'], $decryptPassword); ?>
								<?php $eachKeygen['label'] = GibberishAES::dec($eachKeygen['label'], $decryptPassword); ?>
								<?php GibberishAES::size($oldKeySize); // Restore old Key Size ?>
								
								<tr id="<?php echo $eachKeygen['id']; ?>">
									<?php if(!empty($selectedKeygens) AND in_array($eachKeygen['id'], $selectedKeygens)) { ?>
										<td class="selector checked">
											<i class="fa fa-check-square fa-fw"></i>
										</td>
									<?php } else { ?>
										<td class="selector">
											<i class="fa fa-square-o fa-fw"></i>
										</td>
									<?php } ?>
									
									<td><?php echo $eachKeygen['keygen']; ?></td>
									<td><?php echo $eachKeygen['label']; ?></td>
									<td><?php echo date('d/m/Y', strtotime($eachKeygen['date'])); ?></td>
									<td><?php echo date('H:i:s', strtotime($eachKeygen['time'])); ?></td>
									<td>
										<a href="<?php echo $eachKeygen['keygen']; ?>" class="copyKeygen btn btn-info btn-xs">
											Copy <i class="fa fa-clipboard fa-fw"></i>
										</a>
									</td>
									<td>
										<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/edit/<?php echo $eachKeygen['id']; ?>" class="btn btn-primary btn-xs">
											Edit <i class="fa fa-pencil fa-fw"></i>
										</a>
									</td>
									<td>
										<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/delete/<?php echo $eachKeygen['id']; ?>" class="btn btn-danger btn-xs">
											Delete <i class="fa fa-trash fa-fw"></i>
										</a>
									</td>
								</tr>
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
								<td colspan="2"><?php echo $countKeygens; ?> <small>keygen<?php if($countKeygens > 1) { ?>s<?php } ?></small></td>
								<td>
									<a href="<?php echo $_Oli->getOption('url'); ?>history/delete/" class="deleteSelected btn btn-danger btn-xs">
										Selected <i class="fa fa-trash fa-fw"></i>
									</a>
								</td>
							</tr>
						</tfoot>
					</table>
				<?php } ?>
			<?php } else { ?>
				<form action="<?php echo $_Oli->getOption('url'); ?>form.php" class="form form-horizontal" method="post">
					<h3>Déchiffrez votre historique</h3>
					<div class="form-group">
						<label class="col-sm-2 control-label">Mot de passe</label>
						<div class="col-sm-10">
							<input type="password" class="form-control" name="decryptPassword" />
							<p class="help-block">
								<i class="fa fa-lock fa-fw"></i> Afin d'assurer la sécurité de vos keygens, ceux-ci ont été chiffrés à l'aide de votre mot de passe avant d'être enregistré dans votre historique. <br />
								<i class="fa fa-angle-right fa-fw"></i> Entrez votre mot de passe pour déchiffrer vos keygens et voir votre historique.
							</p>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-primary"><i class="fa fa-unlock fa-fw"></i> Déchiffrer</button>
							<button type="reset" class="btn btn-default"><i class="fa fa-refresh fa-fw"></i> Réinitialiser</button>
						</div>
					</div>
				</form>
			<?php } ?>
		<?php } else { ?>
			<h3>Vous n'avez aucun keygen dans votre historique.</h3>
		<?php } ?>
	</div>
</div>

<?php include 'footer.php'; ?>

<script>
(function($) {

$('.copyKeygen').click(function(e) {
	e.preventDefault();
	$('#script-message').hide().removeClass().addClass('message');
	
	if($('#_hiddenTextToCopy_').length <= 0) {
		$('body').append(
			$('<textarea>').attr({
				id: '_hiddenTextToCopy_'
			}).css({
				position: 'absolute',
				top: '0',
				left: '-9999px'
			})
		);
	}
    var currentFocus = document.activeElement;
	$('#_hiddenTextToCopy_').empty().append($(this).attr('href')).focus();
    $('#_hiddenTextToCopy_')[0].setSelectionRange(0, $('#_hiddenTextToCopy_').val().length);
    
    try {
    	document.execCommand('copy');
    } catch(exception) {
        succeed = false;
    }
    $(currentFocus).focus();
	
	$('#script-message').addClass('message-success');
	$('#script-message').find('h2').empty().append(
		$('<i>').addClass('fa fa-clipboard fa-fw'),
		' Le lien a été copié !'
	);
	$('#script-message').find('p').empty();
	$('#script-message').show();
	
    return false;
});

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