<?php
/* RETRO COMPATIBILITE */
error_reporting(E_ALL & ~E_NOTICE);
define('URLDATA', '');
define('URL', '');
define('WEBSITE_NAME', 'Prev Matiboux');
?>

<html lang="fr">
	<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		
		<link rel="shortcut icon" href="<?php echo URLDATA; ?>LogoStormradio.png">
		<link rel="stylesheet" type="text/css" href="<?php echo URLDATA; ?>login.css" media="all">
		<title>Connexion - <?php echo WEBSITE_NAME; ?></title>
	</head>
	<body>
		<div id="login">

		<h1><?php echo WEBSITE_NAME; ?></h1>
			<?php if ( $_GET['p'] == 'admin' ) { ?>
				<div class="error">
					Pour avoir accès à la zone d'administration, veuillez vous connecter avant !
				</div>
			<?php } if ( $_GET['action'] == 'forgetpassword' ) { ?>
				<?php if ( 0 ) { ?>
					<div class="error">
						La demande de changement de mot de passe n'a pas pu être envoyé : l'identifiant <b>[Identifiant]</b> n'existe pas.
					</div>
					<div class="info">
						La demande de changement de mot de passe a été envoyé à l'email <b>[Email]</b>
					</div>
				<?php } else { ?>
					<div class="tips">
						Veuillez saisir votre identifiant. Un nouveau mot de passe vous sera envoyé par <b>mail</b> dans les 24 heures par nos administrateurs.
					</div>
				<?php } ?>
			<?php } else if ( $_GET['action'] == 'logout' ) { ?>
				<div class="info">
					Vous êtes désormais déconnecté(e).
					<?php $_SESSION['connected'] = false; ?>
					<?php session_destroy(); ?>
				</div>
			<?php } else { ?>
				<?php if ( isset($_POST['user']) && isset($_POST['pass']) ) { ?>
					<?php if ( $_POST['user'] == '' ) { ?>
						<div class="error">
							Le champ de l'identifiant est vide.
						</div>
					<?php } else if ( $_POST['pass'] == '' ) { ?>
						<div class="error">
							Le champ du mot de passe est vide.
						</div>
					<?php } else { ?>
						<?php $query = $bdd->prepare('SELECT * FROM `account` WHERE username = :username'); ?>
						<?php $query->bindValue(':username', $_POST['user'], PDO::PARAM_STR); ?>
						<?php $query->execute(); ?>
						<?php $data = $query->fetch(); ?>
						<?php if( md5($_POST['pass']) == $data['password'] ) { ?>
							<?php if ( $data['banned'] ) { ?>
								<div class="error">
									Le compte associé à l'identifiant <b><?php echo $_POST['user']; ?></b> a été bloqué, vous ne pouvez pas vous connecter ! <br />
								</div>
							<?php } else { ?>
								<div class="info">
									Vous êtes désormais connecté(e), <b><?php echo $data['firstname']; ?></b>.
									<?php $_SESSION['connected'] = true; ?>
									<?php $_SESSION['username'] = $data['username']; ?>
									<?php $_SESSION['email'] = $data['email']; ?>
									<?php $_SESSION['firstname'] = $data['firstname']; ?>
								</div>
							<?php } ?>
						<?php } else { ?>
							<div class="error">
								L'identifiant <b><?php echo $_POST['user']; ?></b> et/ou le mot de passe ne sont pas valide.
							</div>
						<?php } ?>
						<?php $query->CloseCursor(); ?>
					<?php } ?>
				<?php } ?>
			<?php } ?>

			<?php if ( $_SESSION['connected'] ) { ?>
				<form action="" method="post">
					Vous êtes connecté(e) ! <br />
					<a href="<?php echo URL; ?>">&laquo; Aller sur <?php echo WEBSITE_NAME; ?></a> <br />
					<a href="<?php echo URL; ?>?p=admin">&laquo; Aller dans la zone d'admistration</a>
				</form>
				<p class="tools"><a href="?p=login&action=logout">Deconnexion</a></p>
			<?php } else { ?>
				<?php if ( $_GET['action'] == 'forgetpassword' ) { ?>
					<form action="?p=login&action=forgetpassword" method="post">
						<?php /*
						<p><label for="user">
							Identifiant <br />
							<input type="text" name="user" id="user" />
						</label></p>
						<input type="submit" value="Envoyer la demande" /> <br />
						*/ ?>
						<p>Le changement de mot de passe est indisponible pour le moment. <br />
						<a href="?p=login&action=login">Retour à la connexion</a></p>
					</form>
				
					<p class="tools"><a href="?action=login">Connexion</a></p>
				<?php } else { ?>	
					<form action="?p=login&action=login" method="post">
						<p><label for="user">
							Identifiant <br />
							<input type="text" name="user" id="user" />
						</label></p>
						<p><label for="pass">
							Mot de passe <br />
							<input type="password" name="pass" id="pass" /> <br />
						</label></p>
						<input type="submit" value="Connexion" /> <br />
					</form>
					<p class="tools"><a href="<?php echo URL; ?>?p=login&action=forgetpassword">Mot de passe oublié ?</a></p>
				<?php } ?>
			<?php } ?>

			<p class="back"><a href="<?php echo URL; ?>">&laquo; Revenir sur <?php echo WEBSITE_NAME; ?></a></p>

		</div>
	</body>
</html>