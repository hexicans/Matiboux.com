<?php
if(!$_Oli->verifyAuthKey()
OR $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) < $_Oli->translateUserRight('USER'))
	header('Location: ' . $_Oli->getShortcutLink('login'));

?>

<!DOCTYPE html>
<html>
<head>

<?php include 'head.php'; ?>
<title>Vos permissions - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include 'header.php'; ?>

<div class="header">
	<div class="container">
		<h1><i class="fa fa-user fa-fw"></i> Vos permissions</h1>
		<p>
			Page de visualisation des permissions de votre compte
		</p>
	</div>
</div>

<div class="message message-info">
	<div class="container">
		<h2>La gestion des permissions n'est pas encore disponible</h2>
	</div>
</div>

<div class="main">
	<div class="container">
		<h3>Votre grade : <span class="label label-primary"><?php echo $_Oli->getAccountInfos('ACCOUNTS', 'user_right', array('username' => $_Oli->getAuthKeyOwner())); ?></span></h3>
		<i class="fa fa-hand-o-right fa-fw"></i> De manière plus claire, vous êtes un <span class="label label-default"><?php echo $_Oli->getAccountInfos('RIGHTS', 'name', array('user_right' => $_Oli->getAccountInfos('ACCOUNTS', 'user_right', array('username' => $_Oli->getAuthKeyOwner())))); ?></span> <br />
		<hr />
		
		<?php $yourPermissions = $_Oli->getAccountInfos('RIGHTS', 'permissions', array('user_right' => $_Oli->getAccountInfos('ACCOUNTS', 'user_right', array('username' => $_Oli->getAuthKeyOwner())))); ?>
		<?php if($yourPermissions == '*') { ?>
			Votre grade vous donne <b>tous les droits</b>.
		<?php } else { ?>
			Votre grade vous donne les droits suivants :
			<ul>
				<?php foreach($yourPermissions as $eachPermissions) { ?>
					<li><?php echo $eachPermissions; ?></li>
				<?php } ?>
			</ul>
		<?php } ?>
	</div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>