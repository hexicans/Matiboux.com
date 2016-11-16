<?php
if(!$_Oli->verifyAuthKey()
OR $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) < $_Oli->translateUserRight('USER'))
	header('Location: ' . $_Oli->getShortcutLink('login'));

if(!$_Oli->isEmptyPostVars()) {
	// if(empty($_Oli->getPostVars()['name']))
		// $resultCode = 'YOUR_NAME_EMPTY';
	// else {
		$gender = (!empty($_Oli->getPostVars()['gender']) AND in_array($_Oli->getPostVars()['gender'], ['male', 'female'])) ? $_Oli->getPostVars()['gender'] : '';
		$biography = (!empty($_Oli->getPostVars()['biography'])) ? $_Oli->getPostVars()['biography'] : '';
		$location = (!empty($_Oli->getPostVars()['location'])) ? $_Oli->getPostVars()['location'] : '';
		$website = (!empty($_Oli->getPostVars()['website'])) ? $_Oli->getPostVars()['website'] : '';
		$twitterProfile = (!empty($_Oli->getPostVars()['twitterProfile'])) ? $_Oli->getPostVars()['twitterProfile'] : '';
		$facebookProfile = (!empty($_Oli->getPostVars()['facebookProfile'])) ? $_Oli->getPostVars()['facebookProfile'] : '';
		
		if($_Oli->updateAccountInfos('INFOS', array('gender' => $gender, 'biography' => $biography, 'location' => $location, 'website' => $website, 'twitter_profile' => $twitterProfile, 'facebook_profile' => $facebookProfile), array('username' => $_Oli->getAuthKeyOwner())))
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
<title>Infos supplémentaires - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include 'header.php'; ?>

<div class="header">
	<div class="container">
		<h1><i class="fa fa-file-text-o fa-fw"></i> Infos principales</h1>
		<p>
			Page de gestion de vos informations supplémentaires
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
			<h3>Infos personnelles</h3>
			<div class="form-group">
				<label class="col-sm-2 control-label">Sexe</label>
				<div class="col-sm-10">
					<div class="radio">
						<label><input type="radio" name="gender" value="male" <?php if($_Oli->getAccountInfos('INFOS', 'gender', array('username' => $_Oli->getAuthKeyOwner())) == 'male') { ?>checked<?php } ?> /> <i class="fa fa-mars fa-fw"></i> Homme</label> <br />
						<label><input type="radio" name="gender" value="female" <?php if($_Oli->getAccountInfos('INFOS', 'gender', array('username' => $_Oli->getAuthKeyOwner())) == 'female') { ?>checked<?php } ?> /> <i class="fa fa-venus fa-fw"></i> Femme</label>
					</div>
				</div>
			</div> 
			<?php /*<div class="form-group">
				<label class="col-sm-2 control-label">Situation Amoureuse</label>
				<div class="col-sm-10">
					<div class="radio">
						<label><input type="radio" name="gender" value="male" /> En couple</label> <br />
						<label><input type="radio" name="gender" value="female" /> Célibataire</label>
					</div>
				</div>
			</div>*/ ?> <hr />
			<div class="form-group">
				<label class="col-sm-2 control-label">Biographie</label>
				<div class="col-sm-10">
					<textarea class="form-control" name="biography" rows="5"><?php echo $_Oli->getAccountInfos('INFOS', 'biography', array('username' => $_Oli->getAuthKeyOwner())); ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Localisation</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="location" value="<?php echo $_Oli->getAccountInfos('INFOS', 'location', array('username' => $_Oli->getAuthKeyOwner())); ?>" />
					<p class="help-block">
						Restez vague ! <br />
						Cette information pourra être affichée publiquement sur votre profil.
					</p>
				</div>
			</div>
			<hr />
			
			<h3>Liens favoris</h3>
			<div class="form-group">
				<label class="col-sm-2 control-label">Matiboux Social</label>
				<div class="col-sm-10">
					<div class="input-group">
						<a href="<?php echo $_Oli->getShortcutLink('social') . 'user/' . $_Oli->getAuthKeyOwner(); ?>" class="input-group-addon btn btn-default"><i class="fa fa-external-link"></i></a>
						<input type="text" class="form-control" name="matibouxProfile" value="<?php echo $_Oli->getShortcutLink('social') . 'user/' . $_Oli->getAuthKeyOwner(); ?>" disabled />
					</div>
				</div>
			</div> <hr />
			<div class="form-group">
				<label class="col-sm-2 control-label">Site Internet</label>
				<div class="col-sm-10">
					<div class="input-group">
						<?php if(!empty($_Oli->getAccountInfos('INFOS', 'website', array('username' => $_Oli->getAuthKeyOwner())))) { ?>
							<a href="<?php echo $_Oli->getAccountInfos('INFOS', 'website', array('username' => $_Oli->getAuthKeyOwner())); ?>" class="input-group-addon btn btn-default"><i class="fa fa-external-link"></i></a>
						<?php } else { ?>
							<div class="input-group-addon"><i class="fa fa-pencil"></i></div>
						<?php } ?>
						<input type="text" class="form-control" name="website" value="<?php echo $_Oli->getAccountInfos('INFOS', 'website', array('username' => $_Oli->getAuthKeyOwner())); ?>" />
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label"><i class="fa fa-twitter fa-fw"></i> Twitter</label>
				<div class="col-sm-10">
					<div class="input-group">
						<?php if(!empty($_Oli->getAccountInfos('INFOS', 'twitter_profile', array('username' => $_Oli->getAuthKeyOwner())))) { ?>
							<a href="<?php echo $_Oli->getAccountInfos('INFOS', 'twitter_profile', array('username' => $_Oli->getAuthKeyOwner())); ?>" class="input-group-addon btn btn-default"><i class="fa fa-external-link"></i></a>
						<?php } else { ?>
							<div class="input-group-addon"><i class="fa fa-pencil"></i></div>
						<?php } ?>
						<input type="text" class="form-control" name="twitterProfile" value="<?php echo $_Oli->getAccountInfos('INFOS', 'twitter_profile', array('username' => $_Oli->getAuthKeyOwner())); ?>" />
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label"><i class="fa fa-facebook fa-fw"></i> Facebook</label>
				<div class="col-sm-10">
					<div class="input-group">
						<?php if(!empty($_Oli->getAccountInfos('INFOS', 'facebook_profile', array('username' => $_Oli->getAuthKeyOwner())))) { ?>
							<a href="<?php echo $_Oli->getAccountInfos('INFOS', 'facebook_profile', array('username' => $_Oli->getAuthKeyOwner())); ?>" class="input-group-addon btn btn-default"><i class="fa fa-external-link"></i></a>
						<?php } else { ?>
							<div class="input-group-addon"><i class="fa fa-pencil"></i></div>
						<?php } ?>
						<input type="text" class="form-control" name="facebookProfile" value="<?php echo $_Oli->getAccountInfos('INFOS', 'facebook_profile', array('username' => $_Oli->getAuthKeyOwner())); ?>" />
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