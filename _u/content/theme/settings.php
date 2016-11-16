<?php
if(!$_Oli->verifyAuthKey()
OR $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) < $_Oli->translateUserRight('USER'))
	header('Location: ' . $_Oli->getShortcutLink('login'));

if(!empty($_Oli->getPostVars())) {
	// if(empty($_Oli->getPostVars()['name']))
		// $resultCode = 'YOUR_NAME_EMPTY';
	// else {
		$linksTransparency = ($_Oli->getPostVars()['linksTransparency'] ? true : false);
		$informSensitiveLink = ($_Oli->getPostVars()['informSensitiveLink'] ? true : false);
		
		if(!$_Oli->isExistInfosMySQL('url_shortener_preferences', array('username' => $_Oli->getAuthKeyOwner()))
		AND $_Oli->insertLineMySQL('url_shortener_preferences', array('id' => $_Oli->getLastInfoMySQL('url_shortener_preferences', 'id') + 1, 'username' => $_Oli->getAuthKeyOwner(), 'links_transparency' => $linksTransparency, 'inform_sensitive_link' => $informSensitiveLink)))
			$resultCode = 'UPDATE_OK';
		else if($_Oli->updateInfosMySQL('url_shortener_preferences', array('links_transparency' => $linksTransparency, 'inform_sensitive_link' => $informSensitiveLink), array('username' => $_Oli->getAuthKeyOwner())))
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
	<div class="message message-success">
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
				<label class="col-sm-2 control-label">Transparence</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label><input type="checkbox" name="linksTransparency" <?php if(($_Oli->isExistInfosMySQL('url_shortener_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('url_shortener_preferences', 'links_transparency', array('username' => $_Oli->getAuthKeyOwner()))) OR !$_Oli->isExistInfosMySQL('url_shortener_preferences', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> /> Demander ma confirmation avant de me rediriger sur le lien original</label>
						<p class="help-block">
							<i class="fa fa-eye fa-fw"></i> Permet de vous prévenir des liens malveillants en affichant le lien original (vers lequel vous auriez été emmené) sur la page de confirmation. Ensuite, à vous de choisir si vous souhaiter continuer la redirection ou non.
						</p>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Lien choquant</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label><input type="checkbox" name="informSensitiveLink" <?php if(($_Oli->isExistInfosMySQL('url_shortener_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('url_shortener_preferences', 'inform_sensitive_link', array('username' => $_Oli->getAuthKeyOwner()))) OR !$_Oli->isExistInfosMySQL('url_shortener_preferences', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> /> Prévenir des liens choquants avant de les afficher</label>
						<p class="help-block">
							<i class="fa fa-hand-paper-o fa-fw"></i> Si activé, une confirmation s'affichera avant de vous rediriger vers un lien choquant (surpasse le paramètre de transparence).
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