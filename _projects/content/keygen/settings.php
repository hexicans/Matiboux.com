<?php
if(!$_Oli->verifyAuthKey()
OR $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) < $_Oli->translateUserRight('USER'))
	header('Location: ' . $_Oli->getShortcutLink('login'));

if(!$_Oli->isEmptyPostVars()) {
	// if(empty($_Oli->getPostVars()['name']))
		// $resultCode = 'YOUR_NAME_EMPTY';
	// else {
		// $defaultParameters = ($_Oli->getPostVars()['defaultParameters']) ? true : false;
		// $keepHistory = ($_Oli->getPostVars()['keepHistory']) ? true : false;
		
		// $genreNum = ($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND !$defaultParameters) ? $_Oli->getInfosMySQL('keygen_preferences', 'genreNum', array('username' => $_Oli->getAuthKeyOwner())) : (($_Oli->getPostVars()['genreNum']) ? true : false);
		// $genreMin = ($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND !$defaultParameters) ? $_Oli->getInfosMySQL('keygen_preferences', 'genreMin', array('username' => $_Oli->getAuthKeyOwner())) : (($_Oli->getPostVars()['genreMin']) ? true : false);
		// $genreMaj = ($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND !$defaultParameters) ? $_Oli->getInfosMySQL('keygen_preferences', 'genreMaj', array('username' => $_Oli->getAuthKeyOwner())) : (($_Oli->getPostVars()['genreMaj']) ? true : false);
		// $genreSpe = ($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND !$defaultParameters) ? $_Oli->getInfosMySQL('keygen_preferences', 'genreSpe', array('username' => $_Oli->getAuthKeyOwner())) : (($_Oli->getPostVars()['genreSpe']) ? true : false);
		// $length = ($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND !$defaultParameters) ? $_Oli->getInfosMySQL('keygen_preferences', 'length', array('username' => $_Oli->getAuthKeyOwner())) : $_Oli->getPostVars()['length'];
		// $blockLength = ($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND !$defaultParameters) ? $_Oli->getInfosMySQL('keygen_preferences', 'block_length', array('username' => $_Oli->getAuthKeyOwner())) : $_Oli->getPostVars()['blockLength'];
		// $multiCharacter = ($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND !$defaultParameters) ? $_Oli->getInfosMySQL('keygen_preferences', 'multi_character', array('username' => $_Oli->getAuthKeyOwner())) : (($_Oli->getPostVars()['multiCharacter']) ? true : false);
		
		$defaultParameters = (!empty($_Oli->getPostVars('defaultParameters'))) ? true : false;
		$defaultMode = (!empty($_Oli->getPostVars('defaultMode'))) ? $_Oli->getPostVars('defaultMode') : (($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner()))) ? $_Oli->getInfosMySQL('keygen_preferences', 'default_mode', array('username' => $_Oli->getAuthKeyOwner())) : 'keygen');
		$keepHistory = (!empty($_Oli->getPostVars('keepHistory'))) ? true : false;
		
		$genreNum = (!empty($_Oli->getPostVars('genreNum')) AND $defaultParameters) ? true : (($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner()))) ? $_Oli->getInfosMySQL('keygen_preferences', 'genreNum', array('username' => $_Oli->getAuthKeyOwner())) : false);
		$genreMin = (!empty($_Oli->getPostVars('genreMin')) AND $defaultParameters) ? true : (($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner()))) ? $_Oli->getInfosMySQL('keygen_preferences', 'genreMin', array('username' => $_Oli->getAuthKeyOwner())) : false);
		$genreMaj = (!empty($_Oli->getPostVars('genreMaj')) AND $defaultParameters) ? true : (($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner()))) ? $_Oli->getInfosMySQL('keygen_preferences', 'genreMaj', array('username' => $_Oli->getAuthKeyOwner())) : false);
		$genreSpe = (!empty($_Oli->getPostVars('genreSpe')) AND $defaultParameters) ? true : (($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner()))) ? $_Oli->getInfosMySQL('keygen_preferences', 'genreSpe', array('username' => $_Oli->getAuthKeyOwner())) : false);
		$length = (!empty($_Oli->getPostVars('length')) AND $defaultParameters) ? $_Oli->getPostVars('length') : (($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner()))) ? $_Oli->getInfosMySQL('keygen_preferences', 'length', array('username' => $_Oli->getAuthKeyOwner())) : 12);
		$blockLength = (!empty($_Oli->getPostVars('blockLength')) AND $defaultParameters) ? $_Oli->getPostVars('blockLength') : (($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner()))) ? $_Oli->getInfosMySQL('keygen_preferences', 'block_length', array('username' => $_Oli->getAuthKeyOwner())) : 4);
		$multiCharacter = (!empty($_Oli->getPostVars('multiCharacter')) AND $defaultParameters) ? true : (($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner()))) ? $_Oli->getInfosMySQL('keygen_preferences', 'multi_character', array('username' => $_Oli->getAuthKeyOwner())) : false);
		
		if(!$_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner()))
		AND $_Oli->insertLineMySQL('keygen_preferences', array('id' => $_Oli->getLastInfoMySQL('keygen_preferences', 'id') + 1, 'username' => $_Oli->getAuthKeyOwner(), 'genreNum' => $genreNum, 'genreMin' => $genreMin, 'genreMaj' => $genreMaj, 'genreSpe' => $genreSpe, 'length' => $length, 'block_length' => $blockLength, 'multi_character' => $multiCharacter, 'default_parameters' => $defaultParameters, 'keep_history' => $keepHistory)))
			$resultCode = 'UPDATE_OK';
		else if($_Oli->updateInfosMySQL('keygen_preferences', array('genreNum' => $genreNum, 'genreMin' => $genreMin, 'genreMaj' => $genreMaj, 'genreSpe' => $genreSpe, 'length' => $length, 'block_length' => $blockLength, 'multi_character' => $multiCharacter, 'default_parameters' => $defaultParameters, 'default_mode' => $defaultMode, 'keep_history' => $keepHistory), array('username' => $_Oli->getAuthKeyOwner())))
			$resultCode = 'UPDATE_OK';
		else
			$resultCode = 'UPDATE_FAILED';
	// }
}
?>

<!DOCTYPE html>
<html>
<head>

<?php include COMMONPATH . 'head.php'; ?>
<title>Paramètres - <?php echo $_Oli->getSetting('name'); ?></title>

</head>
<body>

<?php include THEMEPATH . 'header.php'; ?>

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
		<form action="<?php echo $_Oli->getUrlParam(0); ?>form.php" class="form form-horizontal" method="post">
			<h3>Préférences</h3>
			<div class="form-group">
				<label class="col-sm-2 control-label">Paramètrage</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label><input type="checkbox" name="defaultParameters" <?php if($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('keygen_preferences', 'default_parameters', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> /> Utiliser de paramètres de génération par défaut</label>
					</div>
					<p class="help-block">
						<i class="fa fa-toggle-on fa-fw"></i> Activé, vous pouvez définir les paramètres affichés par défaut au chargement la page de génération. <br />
						<i class="fa fa-toggle-off fa-fw"></i> Désactivé, les paramètres prendront les valeurs de ceux utilisés lors de la génération précédante.
					</p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Mode</label>
				<div class="col-sm-10">
					<div class="radio">
						<label><input type="radio" name="defaultMode" value="keygen" <?php if(($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND ($_Oli->getInfosMySQL('keygen_preferences', 'default_mode', array('username' => $_Oli->getAuthKeyOwner())) == 'keygen' OR empty($_Oli->getInfosMySQL('keygen_preferences', 'default_mode', array('username' => $_Oli->getAuthKeyOwner()))))) OR !$_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> /> KeyGens par défaut</label>
					</div>
					<div class="radio">
						<label><input type="radio" name="defaultMode" value="activation-key" <?php if($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('keygen_preferences', 'default_mode', array('username' => $_Oli->getAuthKeyOwner())) == 'activation-key') { ?>checked<?php } ?> /> Clés d'activation par défaut</label>
					</div>
					<p class="help-block">
						<i class="fa fa-compress fa-fw"></i> Définit le mode de génération affiché par défaut sur la page de génération
					</p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Historique</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label><input type="checkbox" name="keepHistory" <?php if($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('keygen_preferences', 'keep_history', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> /> Conserver un historique de vos clés générées</label>
						<p class="help-block">
							<span class="text-primary">
								<i class="fa fa-lock fa-fw"></i>
								Ne vous inquiétez pas, ils seront cryptés avant d'être enregistrés
							</span>
						</p>
					</div>
				</div>
			</div>
			<hr />
			
			<h3>Réglage des paramètres de génération</h3>
			<?php if(!$_Oli->getInfosMySQL('keygen_preferences', 'default_parameters', array('username' => $_Oli->getAuthKeyOwner()))) { ?>
				<p class="help-block">
					<span class="text-info">
						<i class="fa fa-info fa-fw"></i> Visualition des valeurs enregistrées <br />
						Activez les paramètres par défaut pour pouvoir les modifier
					</span>
				</p>
			<?php } ?>
			<div class="form-group">
				<label class="col-sm-2 control-label">Genre</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label <?php if(!$_Oli->getInfosMySQL('keygen_preferences', 'default_parameters', array('username' => $_Oli->getAuthKeyOwner()))) { ?>class="text-muted"<?php } ?>>
							<input type="checkbox" name="genreNum" <?php if(($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('keygen_preferences', 'genreNum', array('username' => $_Oli->getAuthKeyOwner()))) OR !$_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> <?php if(!$_Oli->getInfosMySQL('keygen_preferences', 'default_parameters', array('username' => $_Oli->getAuthKeyOwner()))) { ?>disabled<?php } ?> /> Numérique (1)
						</label>
					</div>
					<div class="checkbox">
						<label <?php if(!$_Oli->getInfosMySQL('keygen_preferences', 'default_parameters', array('username' => $_Oli->getAuthKeyOwner()))) { ?>class="text-muted"<?php } ?>>
							<input type="checkbox" name="genreMin" <?php if(($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('keygen_preferences', 'genreMin', array('username' => $_Oli->getAuthKeyOwner()))) OR !$_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> <?php if(!$_Oli->getInfosMySQL('keygen_preferences', 'default_parameters', array('username' => $_Oli->getAuthKeyOwner()))) { ?>disabled<?php } ?> /> Alphabétique minuscule (a)
						</label>
					</div>
					<div class="checkbox">
						<label <?php if(!$_Oli->getInfosMySQL('keygen_preferences', 'default_parameters', array('username' => $_Oli->getAuthKeyOwner()))) { ?>class="text-muted"<?php } ?>>
							<input type="checkbox" name="genreMaj" <?php if(($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('keygen_preferences', 'genreMaj', array('username' => $_Oli->getAuthKeyOwner()))) OR !$_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> <?php if(!$_Oli->getInfosMySQL('keygen_preferences', 'default_parameters', array('username' => $_Oli->getAuthKeyOwner()))) { ?>disabled<?php } ?> /> Alphabétique majuscule (A)
						</label>
					</div>
					<div class="checkbox">
						<label <?php if(!$_Oli->getInfosMySQL('keygen_preferences', 'default_parameters', array('username' => $_Oli->getAuthKeyOwner()))) { ?>class="text-muted"<?php } else { ?>class="text-danger"<?php } ?>>
							<input type="checkbox" name="genreSpe" <?php if($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('keygen_preferences', 'genreSpe', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> <?php if(!$_Oli->getInfosMySQL('keygen_preferences', 'default_parameters', array('username' => $_Oli->getAuthKeyOwner()))) { ?>disabled<?php } ?> /> Spéciaux (@)
							<?php if($_Oli->getInfosMySQL('keygen_preferences', 'default_parameters', array('username' => $_Oli->getAuthKeyOwner()))) { ?>- Peut poser certains problèmes<?php } ?>
						</label>
					</div>
					<?php if($_Oli->getInfosMySQL('keygen_preferences', 'default_parameters', array('username' => $_Oli->getAuthKeyOwner()))) { ?>
						<p class="help-block">
							<i class="fa fa-info fa-fw"></i>
							Les caractères spéciaux sont utilisés pour les keygens seulement.
						</p>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Longueur</label>
				<div class="col-sm-10">
					<div class="input-group">
						<input type="number" class="form-control" name="length" value="<?php if($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND !empty($_Oli->getInfosMySQL('keygen_preferences', 'length', array('username' => $_Oli->getAuthKeyOwner())))) { ?><?php echo $_Oli->getInfosMySQL('keygen_preferences', 'length', array('username' => $_Oli->getAuthKeyOwner())); ?><?php } else { ?>12<?php } ?>" <?php if(!$_Oli->getInfosMySQL('keygen_preferences', 'default_parameters', array('username' => $_Oli->getAuthKeyOwner()))) { ?>disabled<?php } ?> />
						<div class="input-group-addon">caractères</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Nb de blocs</label>
				<div class="col-sm-10">
					<div class="input-group">
						<input type="number" class="form-control" name="blockLength" value="<?php if($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND !empty($_Oli->getInfosMySQL('keygen_preferences', 'block_length', array('username' => $_Oli->getAuthKeyOwner())))) { ?><?php echo $_Oli->getInfosMySQL('keygen_preferences', 'block_length', array('username' => $_Oli->getAuthKeyOwner())); ?><?php } else { ?>4<?php } ?>" <?php if(!$_Oli->getInfosMySQL('keygen_preferences', 'default_parameters', array('username' => $_Oli->getAuthKeyOwner()))) { ?>disabled<?php } ?> />
						<div class="input-group-addon">groupes</div>
					</div>
					<?php if($_Oli->getInfosMySQL('keygen_preferences', 'default_parameters', array('username' => $_Oli->getAuthKeyOwner()))) { ?>
						<p class="help-block">
							<i class="fa fa-info fa-fw"></i>
							Le nombre de blocs est utilisé pour les clés d'activation seulement.
						</p>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Extra</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label <?php if(!$_Oli->getInfosMySQL('keygen_preferences', 'default_parameters', array('username' => $_Oli->getAuthKeyOwner()))) { ?>class="text-muted"<?php } ?>>
							<input type="checkbox" name="multiCharacter" <?php if($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('keygen_preferences', 'multi_character', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> <?php if(!$_Oli->getInfosMySQL('keygen_preferences', 'default_parameters', array('username' => $_Oli->getAuthKeyOwner()))) { ?>disabled<?php } ?> /> Redondance des caractères
						</label>
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

<?php include COMMONPATH . 'footer.php'; ?>

</body>
</html>