<!DOCTYPE html>
<html>
<head>

<?php include 'head.php'; ?>
<title><?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include 'header.php'; ?>

<div class="header">
	<div class="container">
		<h1><i class="fa fa-lock fa-fw"></i> <?php echo $_Oli->getOption('name'); ?></h1>
		<p>
			<?php echo $_Oli->getOption('description'); ?>
		</p>
	</div>
</div>

<div class="message" id="error-message" style="display: none;">
	<div class="container">
		<h1></h1>
		<p></p>
	</div>
</div>
<div class="message" id="generated-message" style="display: none;">
	<div class="container">
		<h1></h1>
		<p></p>
	</div>
</div>

<div class="main">
	<div class="container">
		<p>
			<i class="fa fa-compress fa-fw"></i> Changer de mode de génération
		</p>
		<ul class="switchTo">
			<li>
				<span value="keygen" <?php if(($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND ($_Oli->getInfosMySQL('keygen_preferences', 'default_mode', array('username' => $_Oli->getAuthKeyOwner())) == 'keygen' OR empty($_Oli->getInfosMySQL('keygen_preferences', 'default_mode', array('username' => $_Oli->getAuthKeyOwner()))))) OR !$_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner()))) { ?>class="text-muted" style="font-weight: 700;"<?php } else { ?>class="text-primary"<?php } ?>>
					<i class="fa fa-lock fa-fw"></i> Générer un keygen
				</span>
			</li>
			<li>
				<span value="activation-key" <?php if($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('keygen_preferences', 'default_mode', array('username' => $_Oli->getAuthKeyOwner())) == 'activation-key') { ?>class="text-muted" style="font-weight: 700;"<?php } else { ?>class="text-primary"<?php } ?>>
					<i class="fa fa-code fa-fw"></i> Générer une clé d'activation
				</span>
			</li>
		</ul>
	</div> <hr />
	<div class="container" only="keygen" <?php if($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND ($_Oli->getInfosMySQL('keygen_preferences', 'default_mode', array('username' => $_Oli->getAuthKeyOwner())) != 'keygen' AND !empty($_Oli->getInfosMySQL('keygen_preferences', 'default_mode', array('username' => $_Oli->getAuthKeyOwner()))))) { ?>style="display: none;"<?php } ?>>
		<h3>Configs - Keygen</h3>
		<span class="text-danger">Configs prédéfinies non disponibles pour le moment</span>
		<?php /*<ul class="configs text-primary">
			<li>
				<span value="keygen">
					<i class="fa fa-lock fa-fw"></i> Switch to Keygen
				</span>
			</li>
		</ul>*/ ?>
	</div>
	<div class="container" only="activation-key" <?php if(($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND ($_Oli->getInfosMySQL('keygen_preferences', 'default_mode', array('username' => $_Oli->getAuthKeyOwner())) != 'activation-key' OR empty($_Oli->getInfosMySQL('keygen_preferences', 'default_mode', array('username' => $_Oli->getAuthKeyOwner()))))) OR !$_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner()))) { ?>style="display: none;"<?php } ?>>
		<h3>Configs - Clés d'activation</h3>
		<span class="text-danger">Configs prédéfinies non disponibles pour le moment</span>
		<?php /*<ul class="configs text-primary">
			<li>
				<span value="activation-key">
					<i class="fa fa-code fa-fw"></i> Switch to Activation Key
				</span>
			</li>
		</ul>*/ ?>
	</div> <hr />
	<div class="container">
		<form action="<?php echo $_Oli->getShortcutLink('apis'); ?>_keygen.php" class="form form-horizontal keygen" method="post">
			<div class="form-group">
				<label class="col-sm-2 control-label">Genre</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label><input type="checkbox" name="genreNum" <?php if(!$_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) OR !$_Oli->verifyAuthKey() OR ($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('keygen_preferences', 'genreNum', array('username' => $_Oli->getAuthKeyOwner())))) { ?>checked<?php } ?> /> Numérique (1)</label>
					</div>
					<div class="checkbox">
						<label><input type="checkbox" name="genreMin" <?php if(!$_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) OR !$_Oli->verifyAuthKey() OR ($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('keygen_preferences', 'genreMin', array('username' => $_Oli->getAuthKeyOwner())))) { ?>checked<?php } ?> /> Alphabétique minuscule (a)</label>
					</div>
					<div class="checkbox">
						<label><input type="checkbox" name="genreMaj" <?php if(!$_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) OR !$_Oli->verifyAuthKey() OR ($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('keygen_preferences', 'genreMaj', array('username' => $_Oli->getAuthKeyOwner())))) { ?>checked<?php } ?> /> Alphabétique majuscule (A)</label>
					</div>
					<div class="checkbox" only="keygen" <?php if($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND ($_Oli->getInfosMySQL('keygen_preferences', 'default_mode', array('username' => $_Oli->getAuthKeyOwner())) != 'keygen' AND !empty($_Oli->getInfosMySQL('keygen_preferences', 'default_mode', array('username' => $_Oli->getAuthKeyOwner()))))) { ?>style="display: none;"<?php } ?>>
						<label class="text-danger"><input type="checkbox" name="genreSpe" <?php if($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('keygen_preferences', 'genreSpe', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> /> Spéciaux (@) - Peut poser certains problèmes</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Longueur</label>
				<div class="col-sm-10">
					<div class="input-group">
						<input type="number" class="form-control" name="length" value="<?php if($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND !empty($_Oli->getInfosMySQL('keygen_preferences', 'length', array('username' => $_Oli->getAuthKeyOwner())))) { ?><?php echo $_Oli->getInfosMySQL('keygen_preferences', 'length', array('username' => $_Oli->getAuthKeyOwner())); ?><?php } else { ?>12<?php } ?>" />
						<div class="input-group-addon">caractères</div>
					</div>
				</div>
			</div>
			<div class="form-group" only="activation-key" <?php if(($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND ($_Oli->getInfosMySQL('keygen_preferences', 'default_mode', array('username' => $_Oli->getAuthKeyOwner())) != 'activation-key' OR empty($_Oli->getInfosMySQL('keygen_preferences', 'default_mode', array('username' => $_Oli->getAuthKeyOwner()))))) OR !$_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner()))) { ?>style="display: none;"<?php } ?>>
				<label class="col-sm-2 control-label">Nb de blocs</label>
				<div class="col-sm-10">
					<div class="input-group">
						<input type="number" class="form-control" name="blockLength" value="<?php if($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND !empty($_Oli->getInfosMySQL('keygen_preferences', 'block_length', array('username' => $_Oli->getAuthKeyOwner())))) { ?><?php echo $_Oli->getInfosMySQL('keygen_preferences', 'block_length', array('username' => $_Oli->getAuthKeyOwner())); ?><?php } else { ?>4<?php } ?>" disabled />
						<div class="input-group-addon">groupes</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Extra</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label><input type="checkbox" name="multiCharacter" <?php if($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('keygen_preferences', 'multi_character', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> /> Redondance des caractères</label>
					</div>
					<div class="checkbox">
						<label><input type="checkbox" name="showsHashs" <?php if($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('keygen_preferences', 'shows_hashs', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> /> Afficher les hashs</label>
					</div>
				</div>
			</div>
			
			<?php if($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('keygen_preferences', 'keep_history', array('username' => $_Oli->getAuthKeyOwner()))) { ?>
				<hr />
				<div class="form-group">
					<label class="col-sm-2 control-label">Mot de passe</label>
					<div class="col-sm-10">
						<input type="password" class="form-control" name="password" />
						<p class="help-block">
							<i class="fa fa-lock fa-fw"></i> Vous avez activé votre historique, afin d'assurer la sécurité de vos keygens, ils seront cryptés à l'aide de votre mot de passe avant d'être enregistré dans votre historique. <br />
							<i class="fa fa-times fa-fw"></i> Ne pas entrer votre mot de passe empêchera l'enregistrement de ce keygen dans votre historique.
						</p>
					</div>
				</div>
			<?php } ?>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-primary">Générer une clé</button>
					<button type="reset" class="btn btn-default">Réinitialiser</button>
				</div>
			</div>
		</form>
	</div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>