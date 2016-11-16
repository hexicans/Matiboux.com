<?php
if($_Oli->getUserRightLevel() < $_Oli->translateUserRight('USER')) header('Location: ' . $_Oli->getShortcutLink('login'));
?>

<!DOCTYPE html>
<html>
<head>

<?php include COMMONPATH . 'head.php'; ?>
<title>Your account - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include THEMEPATH . 'header.php'; ?>

<div class="title-banner">
	<?php
	$name = $_Oli->getAuthKeyOwner();
	
	/** Common user headers */
	$texts[] = 'Hooray! Welcome back ' . $name . ' <span class="text-danger"><i class="fa fa-heart fa-fw"></i></span>';
	$texts[] = 'Hey there, sweet ' . $name . ' ~';
	$texts[] = 'Hi ' . $name . ' senpai! ~';
	$texts[] = 'Thank you ' . $name . ' for using my projects!';
	$texts[] = 'Hey, got something special planned today?';
	$texts[] = 'What do you got for me today? Something nice I hope!';
	$texts[] = 'Wanted to update something on your account?';
	$texts[] = 'A question on your account? Everything\'s here!';
	$texts[] = 'Don\'t be afraid, I\'m only here to help you';
	$texts[] = 'You there? Oh, don\'t make me blush';
	
	/** Specific user headers */
	if($name == 'Elionatrox') $texts[] = 'Don\'t forget to get a smooch from your fur <span class="text-danger"><i class="fa fa-heart fa-fw"></i></span> ~';
	else if($name == 'Matiboux') $texts[] = 'You got a smooch for bae, don\'t you? ~';
	
	/** User rights headers */
	if($_Oli->getUserRightLevel() >= $_Oli->translateUserRight('VIP')) {
		$texts[] = 'You\'re very important to us, ' . $name;
		$texts[] = 'Thanks for the support, dude!';
		$texts[] = 'Much love, much hugs and much *patpat*';
		$texts[] = 'Don\'t worry, we cover your back';
		$texts[] = 'The Commander needs your help!';
		
		if($_Oli->getUserRightLevel() >= $_Oli->translateUserRight('MOD')) {
			$texts[] = 'The community was waiting for you!';
			$texts[] = 'Thanks for the help!';
			
			if($_Oli->getUserRightLevel() >= $_Oli->translateUserRight('ADMIN')) {
				$texts[] = 'The police is here! Welcome officer ' . $name;
				$texts[] = 'Thanks for your help, sir!';
				
				if($_Oli->getUserRightLevel() >= $_Oli->translateUserRight('OWNER')) {
					$texts[] = 'Hey there, master ' . $name . '!';
					$texts[] = 'Look what you made, I\'m proud of you';
				}
			}
		}
	}
	
	echo $texts[$_Oli->randomNumber(0, count($texts) - 1)];
	?>
</div>

<div class="page-content">
	<div class="container">
		<?php if(isset($resultCode)) { ?>
			<?php
			list($prefix, $message) = explode(':', $resultCode, 2);
			if($prefix == 'P') $type = 'message-primary';
			else if($prefix == 'S') $type = 'message-success';
			else if($prefix == 'I') $type = 'message-info';
			else if($prefix == 'W') $type = 'message-warning';
			else if($prefix == 'D') $type = 'message-danger';
			?>
			
			<div class="message <?php echo $type; ?>">
				<?php echo $message; ?>
			</div>
		<?php } ?>
		
		<div class="content-box transparent text-center">
			<div class="stat-box col-sm-4 col-xs-6">
				<?php $registerDate = $_Oli->getAccountInfos('ACCOUNTS', 'register_date'); ?>
				<?php if(date('d/m') == date('d/m', strtotime($registerDate))) { ?>
					<?php
					$anniversary = date('Y') - date('Y', strtotime($registerDate));
					$anniversaryParts[0] = strlen($anniversary) > 1 ? substr($anniversary, -2, 1) : null;
					$anniversaryParts[1] = substr($anniversary, -1) ?: null;
					
					if($anniversaryParts[0] != 1) {
						if($anniversaryParts[1] == 1) $anniversarySufix = 'st';
						else if($anniversaryParts[1] == 2) $anniversarySufix = 'nd';
						else if($anniversaryParts[1] == 3) $anniversarySufix = 'rd';
					}
					if(!$anniversarySufix) $anniversarySufix = 'th';
					?>
					
					<h2><i class="fa fa-birthday-cake fa-fw"></i> Happy <?php echo $anniversary . $anniversarySufix; ?> anniversary</h2>
					<p>Wish you one more happy year with us!</p>
				<?php } else { ?>
					<h2><i class="fa fa-clock-o fa-fw"></i> <?php echo floor((time() - strtotime($registerDate)) / (3600 * 24)); ?> days</h2>
					<p>
						since you registered <br />
						(<?php echo date('d/m/Y', strtotime($registerDate)); ?>)
					</p>
				<?php } ?>
			</div>
			<?php if(in_array($_Oli->getAuthKeyOwner(), ['Matiboux', 'Elionatrox'])) { ?>
				<div class="stat-box col-sm-4 col-xs-6">
					<?php if($_Oli->getAuthKeyOwner() == 'Matiboux') { ?>
						<h2>Bae's fur <span class="text-danger"><i class="fa fa-heart fa-fw"></i></span></h2>
						<p>
							That makes you very special
						</p>
					<?php } else if($_Oli->getAuthKeyOwner() == 'Elionatrox') { ?>
						<h2>Fur's bae <span class="text-danger"><i class="fa fa-heart fa-fw"></i></span></h2>
						<p>
							That makes you very important
						</p>
					<?php } ?>
				</div>
			<?php } ?>
			<?php if($birthdayDate = $_Oli->getAccountInfos('ACCOUNTS', 'birthday') AND date('d/m') == date('d/m', strtotime($birthdayDate))) { ?>
				<div class="stat-box col-sm-4 col-xs-6">
					<?php //if(date('d/m') == date('d/m', strtotime($birthdayDate))) { ?>
						<?php
						$birthday = date('Y') - date('Y', strtotime($birthdayDate));
						$birthdayParts[0] = strlen($birthday) > 1 ? substr($birthday, -2, 1) : null;
						$birthdayParts[1] = substr($birthday, -1) ?: null;
						
						if($birthdayParts[0] != 1) {
							if($birthdayParts[1] == 1) $birthdaySufix = 'st';
							else if($birthdayParts[1] == 2) $birthdaySufix = 'nd';
							else if($birthdayParts[1] == 3) $birthdaySufix = 'rd';
						}
						if(!$birthdaySufix) $birthdaySufix = 'th';
						?>
						
						<h2><i class="fa fa-birthday-cake fa-fw"></i> Happy <?php echo $birthday . $birthdaySufix; ?> birthday!</h2>
						<p>"Make sure to have a great day, and a great life!"</p>
					<?php /*} else { ?>
						<h2><i class="fa fa-calendar fa-fw"></i> <?php echo floor(((time() - strtotime($birthdayDate)) / (3600 * 24)) % 365); ?> days</h2>
						<p>
							left until your birthday! <br />
							(<?php echo date('d/m/Y', strtotime($birthdayDate)); ?>)
						</p>
					<?php }*/ ?>
				</div>
			<?php } ?>
		</div> <hr />
		
		<div class="col-sm-4">
			<div class="content-box">
				<h2><i class="fa fa-lock fa-fw"></i> Account</h2>
				<ul>
					<li><a href="<?php echo $_Oli->getUrlParam(0); ?>account/">Account settings</a></li>
					<?php /*<li><a href="<?php echo $_Oli->getUrlParam(0); ?>account/encryption/">Encryption settings</a></li>*/ ?>
					<li><a href="<?php echo $_Oli->getUrlParam(0); ?>account/permissions/">Your permissions</a></li>
					<li><a href="<?php echo $_Oli->getUrlParam(0); ?>account/sessions/">Active sessions</a></li>
					<li><a href="<?php echo $_Oli->getUrlParam(0); ?>account/requests/">Pending requests</a></li>
				</ul>
			</div>
		</div>
		
		<div class="col-sm-4">
			<div class="content-box">
				<h2><i class="fa fa-pencil fa-fw"></i> Infos</h2>
				<ul>
					<li><a href="<?php echo $_Oli->getUrlParam(0); ?>infos/">General <span class="text-muted small">but personnal</span> informations</a></li>
					<?php /*<li><a href="<?php echo $_Oli->getUrlParam(0); ?>contact/">Contact and social links</a></li>*/ ?>
				</ul>
			</div>
		</div>
		
		<div class="col-sm-4">
			<div class="content-box">
				<h2><i class="fa fa-gear fa-fw"></i> Settings</h2>
				<ul>
					<?php /*<li><a href="<?php echo $_Oli->getUrlParam(0); ?>settings/">Global settings</a></li>*/ ?>
					<li><a href="<?php echo $_Oli->getUrlParam(0); ?>settings/content/">Content settings</a></li>
					<?php /*<li><a href="<?php echo $_Oli->getUrlParam(0); ?>imgshot/">ImgShot content settings</a></li>*/ ?>
				</ul>
			</div>
		</div>
		
		<?php /*<div class="content-box">
			<h2>Que peut-on faire avec ce panel ?</h2>
			<p>
				Ce panel de gestion vous permet de gérer simplement votre compte, vous aurez accès à : <br />
				<ul>
					<li>
						<b>Vos informations personnelles</b> <br />
						Modifiez et gardez à jour la plupart des informations liées à votre compte : <br />
						les informations principales mais encore votre langue, l'affichage de votre nom et diverses autres informations supplémentaires.
					</li> <br />
					<li>
						<b>La visualisation de vos droits</b> <br />
						Visualisez vos droits et interdiction qui vous sont définies ou que vous héritez de votre grade. <br />
						<small class="text-warning">
							<b>EXPERIMENTAL :</b> <br />
							Pour le moment, les permissions ne sont <b>ni utilisées</b>, <b>ni définies</b>. <br />
							Cependant, la fonctionnalité est susceptible d'être mise à oeuvre.
						</small>
					</li> <br />
					<li>
						<b>La visualisation de vos données</b> <br />
						Visualisez l'utilisation de votre espace de stockage pour chaque projet. <br />
						<small class="text-warning">
							<b>Attention :</b> <br />
							Pour le moment, <b>aucune limite</b> n'a été mise en place,
							mais la décision de sa mise en place pourrait éventuellement être examinée. <br />
							Cette mesure ne sera prise <b>que si nécessaire</b>, le service se voulant le <b>plus accessible possible</b>.
						</small>
					</li> <br />
					<li>
						<b>Une liste de vos sessions actives</b> <br />
						Gérez toutes les sessions actives liées à votre compte. <br />
						Gardez un oeil sur la date et la provenance des connexion ainsi que de leur dernière utilisation<span class="text-primary">*</span>.  <br />
						Soyez maître de votre compte et déconnectez à distance les sessions que vous n'utilisez plus. <br />
						<small class="text-primary">
							*La dernière utilisation d'une session est une date mise à jour à chaque rechargement de page
						</small>
					</li> <br />
					<li>
						<b>Une liste de vos requêtes</b> <br />
						Surveillez la liste des requêtes actives de votre compte :
						changements de mot de passe, vérifications de l'adresse mail, etc... <br />
						Ne laissez rien d'inutile et annulez les requêtes encore valides que vous ne terminerez pas.
					</li> <br />
					<li>
						<b>Un support avec tickets</b> <br />
						Signalez un problème ou proposez vos idées aux administrateurs à l'aide d'un ticket. <br />
						Maintenez un discussion simplement et restez attentifs aux réponses grâce aux notifications par mail.
					</li>
				</ul>
			</p>
		</div>*/ ?>
	</div>
</div>

<?php include COMMONPATH . 'footer.php'; ?>

</body>
</html>