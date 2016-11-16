<?php
if(!$_Oli->verifyAuthKey()
OR $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) < $_Oli->translateUserRight('USER'))
	header('Location: ' . $_Oli->getShortcutLink('login'));

if(!$_Oli->isEmptyPostVars()) {
	// if(empty($_Oli->getPostVars()['name']))
		// $resultCode = 'YOUR_NAME_EMPTY';
	// else {
		$showDescriptionInput = ($_Oli->getPostVars()['showDescriptionInput']) ? true : false;
		$informSensitiveContent = ($_Oli->getPostVars()['informSensitiveContent']) ? true : false;
		$identityVisibility = ($_Oli->getPostVars()['identityVisibility'] == 'public') ? true : false;
		$contentVisibility = (in_array($_Oli->getPostVars()['contentVisibility'], ['public', 'private'])) ? $_Oli->getPostVars()['contentVisibility'] : 'private';
		$sensitiveContent = ($_Oli->getPostVars()['sensitiveContent']) ? true : false;
		$contentPreview = ($_Oli->getPostVars()['contentPreview']) ? true : false;
		$downloadableContent = ($_Oli->getPostVars()['downloadableContent']) ? true : false;
		
		if(!$_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner()))
		AND $_Oli->insertLineMySQL('natrox_cloud_preferences', array('id' => $_Oli->getLastInfoMySQL('natrox_cloud_preferences', 'id') + 1, 'username' => $_Oli->getAuthKeyOwner(), 'show_description_input' => $showDescriptionInput, 'inform_sensitive_content' => $informSensitiveContent, 'identity_visibility' => $identityVisibility, 'content_visibility' => $contentVisibility, 'sensitive_content' => $sensitiveContent, 'content_preview' => $contentPreview, 'downloadable_content' => $downloadableContent)))
			$resultCode = 'UPDATE_OK';
		else if($_Oli->updateInfosMySQL('natrox_cloud_preferences', array('show_description_input' => $showDescriptionInput, 'inform_sensitive_content' => $informSensitiveContent, 'identity_visibility' => $identityVisibility, 'content_visibility' => $contentVisibility, 'sensitive_content' => $sensitiveContent, 'content_preview' => $contentPreview, 'downloadable_content' => $downloadableContent), array('username' => $_Oli->getAuthKeyOwner())))
			$resultCode = 'UPDATE_OK';
		else
			$resultCode = 'UPDATE_FAILED';
	// }
}
?>

<!DOCTYPE html>
<html>
<head>

<?php include 'head.php'; ?>
<title>Paramètres - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include 'header.php'; ?>

<div class="header">
	<div class="container">
		<h1><i class="fa fa-gears fa-fw"></i> Paramètres</h1>
		<p>
			Page de gestion de vos paramètres
		</p>
	</div>
</div>

<?php if($resultCode == 'UPDATE_OK') { ?>
	<div class="message message-success">
		<div class="container">
			<h2>Vos paramètres ont été mis à jour</h2>
		</div>
	</div>
<?php } else if($resultCode == 'UPDATE_FAILED') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Une erreur s'est produite</h2>
		</div>
	</div>
<?php } ?>

<div class="main">
	<div class="container">
		<form action="<?php echo $_Oli->getOption('url'); ?>form.php" class="form form-horizontal" method="post">
			<h3>Préférences</h3>
			<div class="form-group">
				<label class="col-sm-2 control-label">Description</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label><input type="checkbox" name="showDescriptionInput" <?php if($_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('natrox_cloud_preferences', 'show_description_input', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> /> Afficher le champ de description sur la page de mise en ligne</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Affichage</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label><input type="checkbox" name="informSensitiveContent" <?php if(($_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('natrox_cloud_preferences', 'inform_sensitive_content', array('username' => $_Oli->getAuthKeyOwner()))) OR !$_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> /> Prévenir des contenus choquants avant de les afficher</label>
						<p class="help-block">
							<i class="fa fa-hand-paper-o fa-fw"></i> Si activé, une confirmation s'affichera avant d'afficher un contenu choquant. 
						</p>
					</div>
				</div>
			</div>
			<hr />
			
			<h3>Valeurs par défaut</h3>
			<div class="form-group">
				<label class="col-sm-2 control-label">Confidentialité</label>
				<div class="col-sm-10">
					<div class="radio">
						<label><input type="radio" name="identityVisibility" value="public" <?php if($_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('natrox_cloud_preferences', 'identity_visibility', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> /> Par défaut, afficher mon pseudonyme</label> <br />
						<label><input type="radio" name="identityVisibility" value="anonym" <?php if(($_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND !$_Oli->getInfosMySQL('natrox_cloud_preferences', 'identity_visibility', array('username' => $_Oli->getAuthKeyOwner()))) OR !$_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> /> Par défaut, garder mon anonymat</label>
						<p class="help-block">
							<i class="fa fa-user-secret fa-fw"></i> En choississant de garder votre anonymat, ne sera affiché aucune information vous concernant.
						</p>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Visibilité</label>
				<div class="col-sm-10">
					<div class="radio">
						<label><input type="radio" name="contentVisibility" value="public" <?php if(($_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('natrox_cloud_preferences', 'content_visibility', array('username' => $_Oli->getAuthKeyOwner())) == 'public') OR !$_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> /> Par défaut, marquer mes fichiers comme publics</label> <br />
						<label><input type="radio" name="contentVisibility" value="private" <?php if($_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('natrox_cloud_preferences', 'content_visibility', array('username' => $_Oli->getAuthKeyOwner())) == 'private') { ?>checked<?php } ?> /> Par défaut, marquer mes fichiers comme privés</label>
						<p class="help-block">
							<i class="fa fa-shield fa-fw"></i> En marquant votre fichier comme privé, personne à part vous ne pourra accéder à votre fichier.
						</p>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Affichage</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label><input type="checkbox" name="sensitiveContent" <?php if($_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('natrox_cloud_preferences', 'sensitive_content', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> /> Par défaut, marquer mes images comme choquantes</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Prévisualisation</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label><input type="checkbox" name="contentPreview" <?php if(($_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('natrox_cloud_preferences', 'content_preview', array('username' => $_Oli->getAuthKeyOwner()))) OR !$_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> /> Autoriser la prévisualisation de ce fichier</label> <br />
						<p class="help-block">
							<i class="fa fa-eye fa-fw"></i> Permet l'affichage, si possible, de la prévisualisation du contenu de ce fichier.
						</p>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Téléchargeable</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label><input type="checkbox" name="downloadableContent" <?php if(($_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('natrox_cloud_preferences', 'downloadable_content', array('username' => $_Oli->getAuthKeyOwner()))) OR !$_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> /> Autoriser le téléchargement de ce fichier</label> <br />
						<p class="help-block">
							<i class="fa fa-cloud-download fa-fw"></i> Permet, ou non, à n'importe qui de télécharger votre fichier.
						</p>
					</div>
				</div>
			</div>
			<hr />
			
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-primary">Mettre à jour</button>
					<button type="reset" class="btn btn-default">Réinitialiser</button>
				</div>
			</div>
		</form>
	</div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>