<?php
if(!$_Oli->verifyAuthKey() OR $_Oli->getUserRightLevel($_Oli->getAuthKeyOwner()) < $_Oli->translateUserRight('MODERATOR')) header('Location: ' . $_Oli->getShortcutLink('login'));
?>

<!DOCTYPE html>
<html>
<head>

<?php include THEMEPATH . 'head.php'; ?>
<?php $_Oli->loadLocalScript('js/search.js', false); ?>
<title>Admin - <?php echo $_Oli->getSetting('name'); ?></title>

</head>
<body>

<?php include THEMEPATH . 'admin/header.php'; ?>

<div class="main">
	<div class="container-fluid">
		ADMIN ZONE <hr /> <br />
		
		You're connected?
		<?php if($_Oli->verifyAuthKey()) { ?> Yes <br />
		And you're <?php echo $_Oli->getUserRight(); ?> <br />
		So <b>you <?php echo $_Oli->getUserRightLevel() >= $_Oli->translateUserRight('MODERATOR') ? 'can' : 'can\'t'; ?> walk around here</b>
		<?php } else { ?> No
		<?php } ?> <br /> <br />
		
		This section WILL allow admins to : <br />
		- manage users (edit infos, rights and more) <br />
		- manage settings of any user <br />
		- connect as any user (without knowing his password) <br />
		- manage posts (delete and even edit them) <br />
		- manage medias (change their owner and delete them) <br />
		- manage notifications (and purge them for any user) <br />
		- manage sessions (delete them or extend their expiration date) <br />
		- manage requests (delete them or extend their expiration date) <br />
		X view search history of any user (/!\ Search history is not available yet!)
	</div>
</div>

<?php $_Oli->loadEndHtmlFiles(); ?>

</body>
</html>