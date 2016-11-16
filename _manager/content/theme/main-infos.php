<?php
if(!$_Oli->verifyAuthKey()
OR $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) < $_Oli->translateUserRight('USER'))
	header('Location: ' . $_Oli->getShortcutLink('login'));

if($_Oli->getUrlParam(2) == 'change-password') {
	if($_Oli->isExistAccountInfos('REQUESTS', array('username' => $_Oli->getAuthKeyOwner(), 'action' => 'change-password'))
	AND strtotime($_Oli->getAccountInfos('REQUESTS', 'expire_date', array('username' => $_Oli->getAuthKeyOwner(), 'action' => 'change-password'))) >= time())
		$resultCode = 'REQUEST_ALREADY_EXIST';
	else if($_Oli->getUrlParam(3) == 'confirmed') {
		$activateKey = $_Oli->createRequest($_Oli->getAuthKeyOwner(), 'change-password');
		
		$email = $_Oli->getAccountInfos('ACCOUNTS', 'email', array('username' => $_Oli->getAuthKeyOwner()));
		$subject = 'Changez votre mot de passe';
		$message = 'Bonjour ' . $_Oli->getAuthKeyOwner() . ', <br />';
		$message .= 'Une requête de changement de mot de passe a été créée pour votre compte <br /> <br />';
		$message .= 'Rendez-vous sur ce lien pour choisir votre nouveau mot de passe : <br />';
		$message .= '<a href="' . $_Oli->getShortcutLink('login') . '/change-password/' . $activateKey . '">' . $_Oli->getShortcutLink('login') . '/change-password/' . $activateKey . '</a> <br /> <br />';
		$message .= 'Vous avez jusqu\'au ' . date('d/m/Y', strtotime($_Oli->getAccountInfos('REQUESTS', 'expire_date', array('username' => $_Oli->getAuthKeyOwner(), 'action' => 'change-password'))) + $_Oli->getRequestsExpireDelay()) . ', <br />';
		$message .= 'Une fois cette date passée, le code d\'activation ne sera plus valide <br /> <br />';
		$message .= 'Si vous n\'avez pas demandé ce changement de mot de passe, veuillez ignorer ce message <br />';
		$message .= 'Si vous avez l\'occasion de vous connecter sur le site, vous pouvez, depuis le panel, annuler cette requête';
		$headers = 'From: noreply@' . $_Oli->getOption('domain') . "\r\n";
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$mailStatus = mail($email, $subject, utf8_decode($message), $headers);
		
		if($mailStatus)
			$resultCode = 'REQUEST_OK';
		else {
			$_Oli->deleteAccountLines('REQUESTS', array('activate_key' => $activateKey));
			$resultCode = 'REQUEST_FAILED';
		}
	}
	else
		$resultCode = 'CONFIRMATION_NEEDED';
}
else if(!$_Oli->isEmptyPostVars()) {
	// if(empty($_Oli->getPostVars()['name']))
		// $resultCode = 'YOUR_NAME_EMPTY';
	// else {
		$pseudonym = (!empty($_Oli->getPostVars()['pseudonym']) ? $_Oli->getPostVars()['pseudonym'] : '');
		$nickname = (!empty($_Oli->getPostVars()['nickname']) ? $_Oli->getPostVars()['nickname'] : '');
		$firstname = (empty($_Oli->getAccountInfos('ACCOUNTS', 'firstname', array('username' => $_Oli->getAuthKeyOwner()))) ? (!empty($_Oli->getPostVars()['firstname']) ? $_Oli->getPostVars()['firstname'] : '') : $_Oli->getAccountInfos('ACCOUNTS', 'firstname', array('username' => $_Oli->getAuthKeyOwner())));
		$lastname = (empty($_Oli->getAccountInfos('ACCOUNTS', 'lastname', array('username' => $_Oli->getAuthKeyOwner()))) ? (!empty($_Oli->getPostVars()['lastname']) ? $_Oli->getPostVars()['lastname'] : '') : $_Oli->getAccountInfos('ACCOUNTS', 'lastname', array('username' => $_Oli->getAuthKeyOwner())));
		$birthday = (empty($_Oli->getAccountInfos('ACCOUNTS', 'birthday', array('username' => $_Oli->getAuthKeyOwner()))) ? (!empty($_Oli->getPostVars()['birthday']) ? $_Oli->getPostVars()['birthday'] : null) : $_Oli->getAccountInfos('ACCOUNTS', 'birthday', array('username' => $_Oli->getAuthKeyOwner())));
		
		$language = (!empty($_Oli->getPostVars()['language'])) ? $_Oli->getPostVars()['language'] : $_Oli->getDefaultUserLanguage();
		$nameFormat = (!empty($_Oli->getPostVars()['nameFormat'])) ? $_Oli->getPostVars()['nameFormat'] : 'username';
		$pseudonymComplement = ($_Oli->getPostVars()['pseudonymComplement']) ? true : false;
		
		if($_Oli->updateAccountInfos('ACCOUNTS', array('pseudonym' => $pseudonym, 'nickname' => $nickname, 'firstname' => $firstname, 'lastname' => $lastname, 'birthday' => $birthday, 'language' => $language), array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->updateAccountInfos('INFOS', array('name_format' => $nameFormat, 'pseudonym_complement' => $pseudonymComplement), array('username' => $_Oli->getAuthKeyOwner()))) {
			$_Oli->setCurrentUserLanguage($language);
			$resultCode = 'UPDATE_OK';
		}
		else
			$resultCode = 'UPDATE_FAILED';
	// }
}
?>

<!DOCTYPE html>
<html>
<head>

<?php include 'head.php'; ?>
<title>Infos principales - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include 'header.php'; ?>

<div class="header">
	<div class="container">
		<h1><i class="fa fa-user fa-fw"></i> Infos principales</h1>
		<p>
			Page de gestion de votre compte et de vos informations principales
		</p>
	</div>
</div>

<?php if($_Oli->getUrlParam(2) == 'change-password' AND $resultCode == 'CONFIRMATION_NEEDED') { ?>
	<div class="message message-warning">
		<div class="container">
			<h1>Confirmez la création de la requête de changement de mot de passe</h1>
			<p>
				<a href="<?php echo $_Oli->getOption('url'); ?><?php echo $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/confirmed" class="btn btn-primary btn-block">
					<i class="fa fa-check fa-fw"></i> Je confirme la création de cette requête
				</a>
				<a href="<?php echo $_Oli->getOption('url'); ?><?php echo $_Oli->getUrlParam(1); ?>/" class="btn btn-danger btn-block">
					<i class="fa fa-times fa-fw"></i> Je refuse la création de cette requête
				</a>
			</p>
		</div>
	</div>
<?php } else if($resultCode == 'REQUEST_ALREADY_EXIST') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Une requête semblable existe déjà et empêche la création d'une nouvelle</h2>
		</div>
	</div>
<?php } else if($resultCode == 'REQUEST_OK') { ?>
	<div class="message message-success">
		<div class="container">
			<h2>La requête a bien été créée, veuillez vérifier vos mails pour terminer l'action</h2>
		</div>
	</div>
<?php } else if($resultCode == 'UPDATE_FAILED') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Une erreur s'est produite</h2>
		</div>
	</div>
<?php } else if($resultCode == 'UPDATE_OK') { ?>
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
			<h3>Infos principales</h3>
			<div class="form-group">
				<label class="col-sm-2 control-label">Nom d'utilisateur</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="username" value="<?php echo $_Oli->getAuthKeyOwner(); ?>" disabled />
					<p class="help-block">
						<i class="fa fa-warning fa-fw"></i> Ne peut pas être changé, mais vous pouvez demander à un administrateur de le faire pour vous.
					</p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Mot de passe</label>
				<div class="col-sm-10">
					<a href="<?php echo $_Oli->getOption('url'); ?><?php echo $_Oli->getUrlParam(1); ?>/change-password" class="btn btn-primary"><i class="fa fa-pencil fa-fw"></i> Changer votre mot de passe</a>
					<?php /*<p class="help-block">
						<i class="fa fa-warning fa-fw"></i> Une requête de changement de mot de passe est déjà en attente
					</p>*/ ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Email</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="email" value="<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'email', array('username' => $_Oli->getAuthKeyOwner())); ?>" disabled />
					<p class="help-block">
						<i class="fa fa-warning fa-fw"></i> Ne peut pas être changé : cette fonctionnalité poserait des problèmes de sécurité
					</p>
				</div>
			</div>
			<hr />
			
			<h3>Infos personnelles</h3>
			<div class="form-group">
				<label class="col-sm-2 control-label">Language</label>
				<div class="col-sm-10">
					<select class="form-control" name="language">
						<option value="en" <?php if($_Oli->getUserLanguage() == 'en' OR empty($_Oli->getUserLanguage())) { ?>selected<?php } ?>>
							English
						</option>
						<option value="fr" <?php if($_Oli->getUserLanguage() == 'fr') { ?>selected<?php } ?>>
							Français
						</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Pseudonyme</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="pseudonym" value="<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'pseudonym', array('username' => $_Oli->getAuthKeyOwner())); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Surnom</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="nickname" value="<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'nickname', array('username' => $_Oli->getAuthKeyOwner())); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Prénom / Nom</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" name="firstname" value="<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'firstname', array('username' => $_Oli->getAuthKeyOwner())); ?>" <?php if(!empty($_Oli->getAccountInfos('ACCOUNTS', 'firstname', array('username' => $_Oli->getAuthKeyOwner())))) { ?>disabled<?php } ?> />
				</div>
				<div class="col-sm-5">
					<input type="text" class="form-control" name="lastname" value="<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'lastname', array('username' => $_Oli->getAuthKeyOwner())); ?>" <?php if(!empty($_Oli->getAccountInfos('ACCOUNTS', 'lastname', array('username' => $_Oli->getAuthKeyOwner())))) { ?>disabled<?php } ?> />
				</div>
				<div class="col-sm-offset-2 col-sm-10">
					<?php if(empty($_Oli->getAccountInfos('ACCOUNTS', 'firstname', array('username' => $_Oli->getAuthKeyOwner()))) OR empty($_Oli->getAccountInfos('ACCOUNTS', 'lastname', array('username' => $_Oli->getAuthKeyOwner())))) { ?>
						<p class="help-block">
							<i class="fa fa-warning fa-fw"></i> Faites attention en entrant vos nom : par la suite, vous n'aurez plus le droit de les modifier. <br />
							<i>Note: Vos noms peuvent être requis pour accéder à certains services.</i>
						</p>
					<?php } else { ?>
						<p class="help-block">
							<i class="fa fa-warning fa-fw"></i> Ne peut plus être changé, mais vous pouvez demander à un administrateur de le faire pour vous.
						</p>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label"><i class="fa fa-gear fa-fw"></i> Affichage</label>
				<div class="col-sm-10">
					<select class="form-control" name="nameFormat">
						<option value="username" <?php if($_Oli->getAccountInfos('INFOS', 'name_format', array('username' => $_Oli->getAuthKeyOwner())) == 'username') { ?>selected<?php } ?>>
							<?php echo $_Oli->getAuthKeyOwner(); ?> - Nom d'utilisateur
						</option>
						<option value="pseudonym" <?php if($_Oli->getAccountInfos('INFOS', 'name_format', array('username' => $_Oli->getAuthKeyOwner())) == 'pseudonym') { ?>selected<?php } ?>>
							<?php if(!empty($_Oli->getAccountInfos('ACCOUNTS', 'pseudonym', array('username' => $_Oli->getAuthKeyOwner())))) { ?>
								<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'pseudonym', array('username' => $_Oli->getAuthKeyOwner())); ?> -
							<?php } ?> Pseudonyme
						</option>
						<option value="nickname" <?php if($_Oli->getAccountInfos('INFOS', 'name_format', array('username' => $_Oli->getAuthKeyOwner())) == 'nickname') { ?>selected<?php } ?>>
							<?php if(!empty($_Oli->getAccountInfos('ACCOUNTS', 'nickname', array('username' => $_Oli->getAuthKeyOwner())))) { ?>
								<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'nickname', array('username' => $_Oli->getAuthKeyOwner())); ?> -
							<?php } ?> Surnom
						</option>
						<option value="firstname" <?php if($_Oli->getAccountInfos('INFOS', 'name_format', array('username' => $_Oli->getAuthKeyOwner())) == 'firstname') { ?>selected<?php } ?>>
							<?php if(!empty($_Oli->getAccountInfos('ACCOUNTS', 'firstname', array('username' => $_Oli->getAuthKeyOwner())))) { ?>
								<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'firstname', array('username' => $_Oli->getAuthKeyOwner())); ?> -
							<?php } ?> Prénom
						</option>
						<option value="short_fullname" <?php if($_Oli->getAccountInfos('INFOS', 'name_format', array('username' => $_Oli->getAuthKeyOwner())) == 'short_fullname') { ?>selected<?php } ?>>
							<?php if(!empty($_Oli->getAccountInfos('ACCOUNTS', 'firstname', array('username' => $_Oli->getAuthKeyOwner()))) AND !empty($_Oli->getAccountInfos('ACCOUNTS', 'lastname', array('username' => $_Oli->getAuthKeyOwner())))) { ?>
								<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'firstname', array('username' => $_Oli->getAuthKeyOwner())); ?> <?php echo substr($_Oli->getAccountInfos('ACCOUNTS', 'lastname', array('username' => $_Oli->getAuthKeyOwner())), 0, 1); ?>. -
							<?php } ?> Prénom et Première lettre du Nom
						</option>
						<option value="fullname" <?php if($_Oli->getAccountInfos('INFOS', 'name_format', array('username' => $_Oli->getAuthKeyOwner())) == 'fullname') { ?>selected<?php } ?>>
							<?php if(!empty($_Oli->getAccountInfos('ACCOUNTS', 'firstname', array('username' => $_Oli->getAuthKeyOwner()))) AND !empty($_Oli->getAccountInfos('ACCOUNTS', 'lastname', array('username' => $_Oli->getAuthKeyOwner())))) { ?>
								<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'firstname', array('username' => $_Oli->getAuthKeyOwner())); ?> <?php echo $_Oli->getAccountInfos('ACCOUNTS', 'lastname', array('username' => $_Oli->getAuthKeyOwner())); ?> -
							<?php } ?> Prénom et Nom complet
						</option>
					</select>
					<p class="help-block">
						Les noms indiqué sont ceux enregistrés actuellement dans notre base de donnée. <br />
						Si modifiés, les nouvelles valeurs seront affichés après validation des changements.
					</p>
				</div>
				<div class="col-sm-10 col-sm-offset-2">
					<div class="checkbox">
						<label><input type="checkbox" name="pseudonymComplement" <?php if($_Oli->getAccountInfos('INFOS', 'pseudonym_complement', array('username' => $_Oli->getAuthKeyOwner()))) { ?>checked<?php } ?> /> Indiquer mon pseudonyme
					</div>
					<p class="help-block">
						Complète votre nom ou surnom avec votre pseudonyme, qui reflète parfois votre identité numérique.
					</p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Date de naissance</label>
				<div class="col-sm-10">
					<input type="date" class="form-control" name="birthday" value="<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'birthday', array('username' => $_Oli->getAuthKeyOwner())); ?>" <?php if(!empty($_Oli->getAccountInfos('ACCOUNTS', 'birthday', array('username' => $_Oli->getAuthKeyOwner())))) { ?>disabled<?php } ?> />
					<?php if(empty($_Oli->getAccountInfos('ACCOUNTS', 'birthday', array('username' => $_Oli->getAuthKeyOwner())))) { ?>
						<p class="help-block">
							<i class="fa fa-warning fa-fw"></i> Faites attention en entrant votre date de naissance : par la suite, vous n'aurez plus le droit de la modifier. <br />
							<i>Note: Votre date de naissance peut être requise pour vous permettre l'accès, volontairement limité, à certains services.</i>
						</p>
					<?php } else { ?>
						<p class="help-block">
							<i class="fa fa-warning fa-fw"></i> Ne peut plus être changé, mais vous pouvez demander à un administrateur de le faire pour vous.
						</p>
					<?php } ?>
				</div>
			</div>
			<hr />
			
			<h3>Divers</h3>
			<div class="form-group">
				<label class="col-sm-2 control-label">Grade</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="userRight" value="<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'user_right', array('username' => $_Oli->getAuthKeyOwner())); ?>" disabled />
					<p class="help-block">
						<i class="fa fa-hand-o-right fa-fw"></i> De manière plus claire, vous êtes un <span class="label label-default"><?php echo $_Oli->getAccountInfos('RIGHTS', 'name', array('user_right' => $_Oli->getAccountInfos('ACCOUNTS', 'user_right', array('username' => $_Oli->getAuthKeyOwner())))); ?></span> <br />
						<a href="<?php echo $_Oli->getOption('url'); ?>permissions" class="btn btn-primary btn-xs"><i class="fa fa-star-o fa-fw"></i> Voir les permissions de votre compte</a>
					</p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Date d'inscription</label>
				<div class="col-sm-10">
					<input type="date" class="form-control" name="registerDate" value="<?php echo date('Y-m-d', strtotime($_Oli->getAccountInfos('ACCOUNTS', 'register_date', array('username' => $_Oli->getAuthKeyOwner())))); ?>" disabled />
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