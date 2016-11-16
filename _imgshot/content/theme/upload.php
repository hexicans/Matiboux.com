<?php
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
			Mise en ligne d'une image
		</p>
	</div>
</div>

<?php if($resultCode == 'NAME_EMPTY') { ?>
	<div class="message message-danger">
		<div class="container">
			<h1>Le nom de l'image ne peut pas être laissé pour vide</h1>
		</div>
	</div>
<?php } else if($resultCode == 'UPLOAD_FAILED') { ?>
	<div class="message message-danger">
		<div class="container">
			<h1>Une erreur s'est produite lors de la mise en ligne, veuillez réessayez</h1>
		</div>
	</div>
<?php } ?>

<?php if(!$_Oli->verifyAuthKey()) { ?>
	<div class="message message-primary">
		<div class="container">
			<h1><i class="fa fa-warning fa-fw"></i> Prenez garde !</h1>
			<p>
				Vous n'êtes pas connecté, alors faites attention : <br />
				Si vous mettez en ligne votre fichier alors que vous n'êtes pas connecté, vous ne pourrez pas le modifier ou le supprimer par la suite !
				Prenez garde aux contenus que vous mettez en ligne.
			</p>
		</div>
	</div>
<?php } ?>

<div class="main">
	<div class="container">
		<form action="<?php echo $_Oli->getOption('url'); ?>imgshot.php" class="form form-horizontal" method="post" enctype="multipart/form-data">
			<div class="form-group">
				<label class="col-sm-2 control-label">Nom</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="name" <?php if(!$_Oli->isEmptyPostVars()) { ?>value="<?php echo $_Oli->getPostVars('name'); ?>"<?php } ?> />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Image</label>
				<div class="col-sm-10">
					<input type="file" class="form-control" name="image" />
					<p class="help-block">
						<i class="fa fa-check fa-fw"></i>
						<?php if($_Upload->getAllowedFileTypes() == '*') { ?>
							Tout type de fichier autorisé 
						<?php } else { ?>
							Types de fichiers autorisés : <?php echo implode(', ', $_Upload->getAllowedFileTypes()); ?>
						<?php } ?> <br />
						Taille max : <?php echo floor($_Upload->getMaxSizeAllowed() / 1024 / 1024); ?> Mio
					</p>
				</div>
			</div>
			<?php if($_Oli->isExistInfosMySQL('imgshot_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('imgshot_preferences', 'show_description_input', array('username' => $_Oli->getAuthKeyOwner()))) { ?>
				<div class="form-group">
					<label class="col-sm-2 control-label">Description</label>
					<div class="col-sm-10">
						<textarea class="form-control" name="description" rows="4"><?php echo $_Oli->getPostVars('description'); ?></textarea>
					</div>
				</div>
			<?php } ?>
			<div class="form-group">
				<label class="col-sm-2 control-label">Confidentialité</label>
				<div class="col-sm-10">
					<div class="radio">
						<?php if($_Oli->verifyAuthKey()) { ?>
							<label><input type="radio" name="nominative" value="public" <?php if($_Oli->getPostVars('nominative') == 'public' OR ($_Oli->isExistInfosMySQL('imgshot_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('imgshot_preferences', 'identity_visibility', array('username' => $_Oli->getAuthKeyOwner())))) { ?>checked<?php } ?> /> Afficher mon pseudonyme</label> <br />
							<label><input type="radio" name="nominative" value="anonym" <?php if($_Oli->getPostVars('nominative') == 'public' OR (($_Oli->isExistInfosMySQL('imgshot_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND !$_Oli->getInfosMySQL('imgshot_preferences', 'identity_visibility', array('username' => $_Oli->getAuthKeyOwner()))) OR !$_Oli->isExistInfosMySQL('imgshot_preferences', array('username' => $_Oli->getAuthKeyOwner())))) { ?>checked<?php } ?> /> Garder mon anonymat</label>
						<?php } else { ?>
							<label><input type="radio" name="nominative" value="anonym" checked disabled /> Garder mon anonymat</label>
							<p class="help-block">
								<span class="text-danger"><i class="fa fa-user-secret fa-fw"></i> Vous n'êtes pas connecté</span>, votre identité sera donc masquée <br />
								<i class="fa fa-info fa-fw"></i> Connectez-vous si vous souhaitez que votre nom soit visible
							</p>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Affichage</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label><input type="checkbox" name="sensitiveContent" <?php if($_Oli->isExistInfosMySQL('imgshot_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('imgshot_preferences', 'sensitive_content', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> /> Marquer cette image comme pouvant être choquante</label> <br />
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-primary">Mettre en ligne</button>
					<button type="reset" class="btn btn-default">Réinitialiser</button>
				</div>
			</div>
		</form>
	</div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>