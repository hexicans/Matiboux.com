<?php
if(!$_Oli->verifyAuthKey()
OR $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) < $_Oli->translateUserRight('USER'))
	header('Location: ' . $_Oli->getShortcutLink('login'));

if(!empty($_Oli->getPostVars())) {
	if(!empty($_Oli->getPostVars()['errorCode']))
		$resultCode = $_Oli->getPostVars()['errorCode'];
}
?>

<!DOCTYPE html>
<html>
<head>

<?php include 'head.php'; ?>
<title>Mise en ligne - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include 'header.php'; ?>

<div class="header">
	<div class="container">
		<h1><i class="fa fa-cloud-upload fa-fw"></i> Mise en ligne</h1>
		<p>
			Page de mise en ligne rapide de vos fichiers
		</p>
	</div>
</div>

<?php if($resultCode == 'NAME_EMPTY') { ?>
	<div class="message message-danger">
		<div class="container">
			<h1>Le nom de l'image ne peut pas être laissé pour vide</h1>
		</div>
	</div>
<?php } else if($resultCode == 'NOT_LOGGED') { ?>
	<div class="message message-danger">
		<div class="container">
			<h1>Vous devez être connecté pour mettre en ligne un fichier</h1>
		</div>
	</div>
<?php } else if($resultCode == 'UPLOAD_FAILED') { ?>
	<div class="message message-danger">
		<div class="container">
			<h1>Une erreur s'est produite lors de la mise en ligne, veuillez réessayez</h1>
		</div>
	</div>
<?php } ?>

<div class="main">
	<div class="container">
		<form action="<?php echo $_Oli->getOption('url'); ?>ncloud.php" class="form form-horizontal" method="post" enctype="multipart/form-data">
			<div class="form-group">
				<label class="col-sm-2 control-label">Nom</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="name" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Fichier</label>
				<div class="col-sm-10">
					<input type="file" class="form-control" name="file" />
					<p class="help-block">
						<i class="fa fa-check fa-fw"></i>
						<?php if($_Upload->getAllowedFileTypes() == '*') { ?>
							Tout type de fichier autorisé 
						<?php } else { ?>
							Types de fichiers autorisés : <?php echo implode(', ', $_Upload->getAllowedFileTypes()); ?>
						<?php } ?>
					</p>
				</div>
			</div>
			<?php if($_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('natrox_cloud_preferences', 'show_description_input', array('username' => $_Oli->getAuthKeyOwner()))) { ?>
				<div class="form-group">
					<label class="col-sm-2 control-label">Description</label>
					<div class="col-sm-10">
						<textarea class="form-control" name="description" rows="4"></textarea>
					</div>
				</div>
			<?php } ?>
			<div class="form-group">
				<label class="col-sm-2 control-label">Confidentialité</label>
				<div class="col-sm-10">
					<div class="radio">
						<label><input type="radio" name="nominative" value="public" <?php if($_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('natrox_cloud_preferences', 'identity_visibility', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> /> Afficher mon pseudonyme</label> <br />
						<label><input type="radio" name="nominative" value="anonym" <?php if(($_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND !$_Oli->getInfosMySQL('natrox_cloud_preferences', 'identity_visibility', array('username' => $_Oli->getAuthKeyOwner()))) OR !$_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> /> Garder mon anonymat</label>
						<p class="help-block">
							<i class="fa fa-user-secret fa-fw"></i> En gardant votre anonymat, vous bloquez l'affichage d'informations vous concernant.
						</p>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Visibilité</label>
				<div class="col-sm-10">
					<div class="radio">
						<label><input type="radio" name="contentVisibility" value="public" <?php if(($_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND ($_Oli->getInfosMySQL('natrox_cloud_preferences', 'content_visibility', array('username' => $_Oli->getAuthKeyOwner())) == 'public' OR empty($_Oli->getInfosMySQL('natrox_cloud_preferences', 'identity_visibility', array('username' => $_Oli->getAuthKeyOwner()))))) OR !$_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> /> Marquer ce fichier comme publique </label> <br />
						<label><input type="radio" name="contentVisibility" value="private" <?php if($_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('natrox_cloud_preferences', 'content_visibility', array('username' => $_Oli->getAuthKeyOwner())) == 'private') { ?>checked<?php } ?> /> Marquer ce fichier comme privé</label>
						<p class="help-block">
							<i class="fa fa-shield fa-fw"></i> En marquant votre fichier comme privé, personne à part vous ne pourra accéder à ce fichier.
						</p>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Affichage</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label><input type="checkbox" name="sensitiveContent" <?php if($_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('natrox_cloud_preferences', 'sensitive_content', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> /> Marquer ce fichier comme pouvant être choquant</label>
					</div>
				</div>
				<div class="col-sm-10 col-sm-offset-2">
					<div class="checkbox">
						<label><input type="checkbox" name="contentPreview" <?php if(($_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('natrox_cloud_preferences', 'content_preview', array('username' => $_Oli->getAuthKeyOwner()))) OR !$_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> /> Autoriser la prévisualisation de ce fichier</label> <br />
						<p class="help-block">
							<i class="fa fa-eye fa-fw"></i> Permet l'affichage, si possible, de la prévisualisation du contenu de ce fichier.
						</p>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Téléchargement</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label><input type="checkbox" name="downloadableContent" <?php if(($_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('natrox_cloud_preferences', 'downloadable_content', array('username' => $_Oli->getAuthKeyOwner()))) OR !$_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> /> Autoriser le téléchargement de ce fichier</label> <br />
						<p class="help-block">
							<i class="fa fa-cloud-download fa-fw"></i> Permet à n'importe qui de télécharger les fichiers. <br />
							<i class="fa fa-user fa-fw"></i> En tant que propriétaire, vous aurez toujours le droit de le télécharger. <br />
							<i class="fa fa-info fa-fw"></i> Notez que un contenu prévisualisable pourra, malgré ce paramètre, être récupéré.
						</p>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-primary"><i class="fa fa-cloud-upload fa-fw"></i> Mettre en ligne</button>
					<button type="reset" class="btn btn-default"><i class="fa fa-refresh fa-fw"></i> Réinitialiser</button>
				</div>
			</div>
		</form>
	</div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>