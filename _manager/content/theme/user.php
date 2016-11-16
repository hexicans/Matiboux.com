<?php
if(empty($_Oli->getUrlParam(2)) OR !$_Oli->isExistAccountInfos('ACCOUNTS', array('username' => $_Oli->getUrlParam(2)), false))
	header('Location: ' . $_Oli->getOption('url'));
?>

<!DOCTYPE html>
<html>
<head>

<?php include 'head.php'; ?>
<title><?php echo $_Oli->getAccountInfos('ACCOUNTS', 'username', array('username' => $_Oli->getUrlParam(2)), false); ?> - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include 'header.php'; ?>

<div class="header">
	<div class="container">
		<h1>
			<i class="fa fa-user fa-fw"></i>
			<?php if($_Oli->getAccountInfos('INFOS', 'name_format', array('username' => $_Oli->getUrlParam(2)), false) == 'fullname' AND !empty($_Oli->getAccountInfos('ACCOUNTS', 'firstname', array('username' => $_Oli->getUrlParam(2)), false)) AND !empty($_Oli->getAccountInfos('ACCOUNTS', 'lastname', array('username' => $_Oli->getUrlParam(2)), false))) { ?>
				<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'firstname', array('username' => $_Oli->getUrlParam(2)), false); ?> <?php echo $_Oli->getAccountInfos('ACCOUNTS', 'lastname', array('username' => $_Oli->getUrlParam(2)), false); ?>
			<?php } else if($_Oli->getAccountInfos('INFOS', 'name_format', array('username' => $_Oli->getUrlParam(2)), false) == 'short_fullname' AND !empty($_Oli->getAccountInfos('ACCOUNTS', 'firstname', array('username' => $_Oli->getUrlParam(2)), false)) AND !empty($_Oli->getAccountInfos('ACCOUNTS', 'lastname', array('username' => $_Oli->getUrlParam(2)), false))) { ?>
				<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'firstname', array('username' => $_Oli->getUrlParam(2)), false); ?> <?php echo substr($_Oli->getAccountInfos('ACCOUNTS', 'lastname', array('username' => $_Oli->getUrlParam(2)), false), 0, 1); ?>.
			<?php } else if($_Oli->getAccountInfos('INFOS', 'name_format', array('username' => $_Oli->getUrlParam(2)), false) == 'firstname' AND !empty($_Oli->getAccountInfos('ACCOUNTS', 'firstname', array('username' => $_Oli->getUrlParam(2)), false))) { ?>
				<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'firstname', array('username' => $_Oli->getUrlParam(2)), false); ?>
			<?php } else if($_Oli->getAccountInfos('INFOS', 'name_format', array('username' => $_Oli->getUrlParam(2)), false) == 'nickname' AND !empty($_Oli->getAccountInfos('ACCOUNTS', 'nickname', array('username' => $_Oli->getUrlParam(2)), false))) { ?>
				<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'nickname', array('username' => $_Oli->getUrlParam(2)), false); ?>
			<?php } else if(($_Oli->getAccountInfos('INFOS', 'name_format', array('username' => $_Oli->getUrlParam(2)), false) == 'pseudonym' OR $_Oli->getAccountInfos('INFOS', 'pseudonym_complement', array('username' => $_Oli->getUrlParam(2)), false)) AND !empty($_Oli->getAccountInfos('ACCOUNTS', 'pseudonym', array('username' => $_Oli->getUrlParam(2)), false))) { ?>
				<?php $pseudonymShown = true; ?>
				<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'pseudonym', array('username' => $_Oli->getUrlParam(2)), false); ?>
			<?php } else { ?>
				<?php $usernameShown = true; ?>
				@<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'username', array('username' => $_Oli->getUrlParam(2)), false); ?>
			<?php } ?>
		</h1>
		
		<p>
			<?php if(!$pseudonymShown AND $_Oli->getAccountInfos('INFOS', 'pseudonym_complement', array('username' => $_Oli->getUrlParam(2)), false) AND !empty($_Oli->getAccountInfos('ACCOUNTS', 'pseudonym', array('username' => $_Oli->getUrlParam(2)), false)) AND !in_array($_Oli->getAccountInfos('INFOS', 'name_format', array('username' => $_Oli->getUrlParam(2)), false), ['pseudonym', 'username'])) { ?>
				<?php $pseudonymComplementShown = true; ?>
				alias "<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'pseudonym', array('username' => $_Oli->getUrlParam(2)), false); ?>"
			<?php } ?>
			<?php if(!$usernameShown) { ?>
				<?php if($pseudonymComplementShown) { ?>|<?php }?>
				@<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'username', array('username' => $_Oli->getUrlParam(2)), false); ?>
			<?php } ?>
		</p>
	</div>
</div>

<div class="main">
	<div class="container">
		<h2>A propos</h2>
		<?php if(!empty($_Oli->getAccountInfos('INFOS', 'biography', array('username' => $_Oli->getUrlParam(2)), false))) { ?>
			<blockquote>
				<p><?php echo nl2br($_Oli->getAccountInfos('INFOS', 'biography', array('username' => $_Oli->getUrlParam(2)), false)); ?></p>
			</blockquote>
		<?php } ?>
		
		<?php $birthday = $_Oli->getAccountInfos('ACCOUNTS', 'birthday', array('username' => $_Oli->getUrlParam(2)), false); ?>
		<?php $registerDate = $_Oli->getAccountInfos('ACCOUNTS', 'register_date', array('username' => $_Oli->getUrlParam(2)), false); ?>
		<p>
			<?php if($_Oli->getAccountInfos('INFOS', 'gender', array('username' => $_Oli->getUrlParam(2)), false) == 'male') { ?>
				<i class="fa fa-mars fa-fw"></i> Est un Homme <br />
			<?php } else if($_Oli->getAccountInfos('INFOS', 'gender', array('username' => $_Oli->getUrlParam(2)), false) == 'female') { ?>
				<i class="fa fa-venus fa-fw"></i> Est une Femme <br />
			<?php } ?>
			
			<?php if(!empty($_Oli->getAccountInfos('INFOS', 'location', array('username' => $_Oli->getUrlParam(2)), false))) { ?>
				<i class="fa fa-map-marker fa-fw"></i> Habite à <?php echo $_Oli->getAccountInfos('INFOS', 'location', array('username' => $_Oli->getUrlParam(2)), false); ?> <br />
			<?php } ?>
			
			<i class="fa fa-user-plus fa-fw"></i> Inscrit en
			<?php switch(date('m', strtotime($registerDate))) {
				case 01: echo 'janvier'; break;
				case 02: echo 'février'; break;
				case 03: echo 'mars'; break;
				case 04: echo 'avril'; break;
				case 05: echo 'mai'; break;
				case 06: echo 'juin'; break;
				case 07: echo 'juillet'; break;
				case 08: echo 'août'; break;
				case 09: echo 'septembre'; break;
				case 10: echo 'octobre'; break;
				case 11: echo 'novembre'; break;
				case 12: echo 'décembre'; break;
			} ?> <?php echo date('Y', strtotime($registerDate)); ?> <br />
			
			<?php if(!empty($birthday)) { ?>
				<i class="fa fa-birthday-cake fa-fw"></i> Anniversaire le <?php echo date('d', strtotime($birthday)); ?>
				<?php
				switch(date('m', strtotime($birthday))) {
					case 01: echo 'janvier'; break;
					case 02: echo 'février'; break;
					case 03: echo 'mars'; break;
					case 04: echo 'avril'; break;
					case 05: echo 'mai'; break;
					case 06: echo 'juin'; break;
					case 07: echo 'juillet'; break;
					case 08: echo 'août'; break;
					case 09: echo 'septembre'; break;
					case 10: echo 'octobre'; break;
					case 11: echo 'novembre'; break;
					case 12: echo 'décembre'; break;
				}
				?>
			<?php } ?>
		</p> <hr />
		
		<h2>
			Liens favoris
			<?php $atLeastOneLink = false; ?>
			<?php if(!empty($_Oli->getAccountInfos('INFOS', 'website', array('username' => $_Oli->getUrlParam(2)), false))) { ?>
				<?php $atLeastOneLink = true; ?>
				<a href="<?php echo $_Oli->getAccountInfos('INFOS', 'website', array('username' => $_Oli->getUrlParam(2)), false); ?>"><i class="fa fa-globe fa-fw"></i></a>
			<?php } ?>
			<?php if(!empty($_Oli->getAccountInfos('INFOS', 'twitter_profile', array('username' => $_Oli->getUrlParam(2)), false))) { ?>
				<?php $atLeastOneLink = true; ?>
				<a href="<?php echo $_Oli->getAccountInfos('INFOS', 'twitter_profile', array('username' => $_Oli->getUrlParam(2)), false); ?>"><i class="fa fa-twitter fa-fw"></i></a>
			<?php } ?>
			<?php if(!empty($_Oli->getAccountInfos('INFOS', 'facebook_profile', array('username' => $_Oli->getUrlParam(2)), false))) { ?>
				<?php $atLeastOneLink = true; ?>
				<a href="<?php echo $_Oli->getAccountInfos('INFOS', 'facebook_profile', array('username' => $_Oli->getUrlParam(2)), false); ?>"><i class="fa fa-facebook fa-fw"></i></a>
			<?php } ?>
			
			<?php if(!$atLeastOneLink) { ?>
				<small><i class="fa fa-times fa-fw"></i> Aucun lien renseigné</small>
			<?php } ?>
		</h2>
	</div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>