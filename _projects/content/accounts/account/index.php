<?php
if($_Oli->getUserRightLevel() < $_Oli->translateUserRight('USER')) header('Location: ' . $_Oli->getShortcutLink('login'));

// if($_Oli->getUrlParam(2) == 'change-password') {
	// if($_Oli->isExistAccountInfos('REQUESTS', array('username' => $_Oli->getAuthKeyOwner(), 'action' => 'change-password'))
	// AND strtotime($_Oli->getAccountInfos('REQUESTS', 'expire_date', array('username' => $_Oli->getAuthKeyOwner(), 'action' => 'change-password'))) >= time())
		// $resultCode = 'REQUEST_ALREADY_EXIST';
	// else if($_Oli->getUrlParam(3) == 'confirmed') {
		// $activateKey = $_Oli->createRequest($_Oli->getAuthKeyOwner(), 'change-password');
		
		// $email = $_Oli->getAccountInfos('ACCOUNTS', 'email', array('username' => $_Oli->getAuthKeyOwner()));
		// $subject = 'Changez votre mot de passe';
		// $message = 'Bonjour ' . $_Oli->getAuthKeyOwner() . ', <br />';
		// $message .= 'Une requête de changement de mot de passe a été créée pour votre compte <br /> <br />';
		// $message .= 'Rendez-vous sur ce lien pour choisir votre nouveau mot de passe : <br />';
		// $message .= '<a href="' . $_Oli->getShortcutLink('login') . '/change-password/' . $activateKey . '">' . $_Oli->getShortcutLink('login') . '/change-password/' . $activateKey . '</a> <br /> <br />';
		// $message .= 'Vous avez jusqu\'au ' . date('d/m/Y', strtotime($_Oli->getAccountInfos('REQUESTS', 'expire_date', array('username' => $_Oli->getAuthKeyOwner(), 'action' => 'change-password'))) + $_Oli->getRequestsExpireDelay()) . ', <br />';
		// $message .= 'Une fois cette date passée, le code d\'activation ne sera plus valide <br /> <br />';
		// $message .= 'Si vous n\'avez pas demandé ce changement de mot de passe, veuillez ignorer ce message <br />';
		// $message .= 'Si vous avez l\'occasion de vous connecter sur le site, vous pouvez, depuis le panel, annuler cette requête';
		// $headers = 'From: noreply@' . $_Oli->getOption('domain') . "\r\n";
		// $headers .= 'MIME-Version: 1.0' . "\r\n";
		// $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		// $mailStatus = mail($email, $subject, utf8_decode($message), $headers);
		
		// if($mailStatus) $resultCode = 'REQUEST_OK';
		// else {
			// $_Oli->deleteAccountLines('REQUESTS', array('activate_key' => $activateKey));
			// $resultCode = 'REQUEST_FAILED';
		// }
	// }
	// else $resultCode = 'CONFIRMATION_NEEDED';
// }
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
	<i class="fa fa-user fa-fw"></i> Your account settings
</div>

<div class="page-content">
	<div class="container">
		<?php if(isset($resultCode)) { ?>
			<?php
			list($prefix, $message) = explode(':', $resultCode);
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
		
		<div class="content-box">
			<form action="<?php echo $_Oli->getUrlParam(0); ?>form.php" class="form form-horizontal" method="post">
				<?php $accountsInfos = $_Oli->getAccountInfos('ACCOUNTS', ['email', 'register_date'], $_Oli->getAuthKeyOwner()); ?>
				
				<h2><i class="fa fa-lock fa-fw"></i> Main account informations</h2>
				<div class="form-group">
					<label class="col-md-2 control-label">Username</label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="username" value="<?php echo $_Oli->getAuthKeyOwner(); ?>" disabled />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">Email</label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="email" value="<?php echo $accountsInfos['email']; ?>" disabled />
					</div>
				</div>
				<p class="help-block col-md-10 col-md-offset-2">
					<i class="fa fa-warning fa-fw"></i> You cannot change these informations yourself, but you can ask an admin to change them for you.
				</p> <div class="clearfix"></div> <hr />
				
				<div class="form-group">
					<label class="col-md-2 control-label hidden-xs hidden-sm">Password</label>
					<div class="col-md-10">
						<a href="<?php echo $_Oli->getUrlParam(0); ?><?php echo $_Oli->getUrlParam(1); ?>/change-password" class="btn btn-primary">
							<i class="fa fa-pencil fa-fw"></i> Change your password
						</a>
					</div>
				</div>
				<p class="help-block col-md-10 col-md-offset-2">
					<i class="fa fa-info fa-fw"></i> This will create a new "change password" request.
					Then you'll receive an email with a link to set your new password.
					
					<?php if($expireDate = $_Oli->getAccountInfos('REQUESTS', 'expire_date', array('username' => $_Oli->getAuthKeyOwner(), 'action' => 'change-password')) AND strtotime($expireDate) >= time()) { ?> <br />
						<span class="text-danger">
							<i class="fa fa-warning fa-fw"></i> Creating a new request will delete the existing one!
						</span>
					<?php } ?>
				</p> <div class="clearfix"></div> <hr />
				
				<h2><i class="fa fa-user fa-fw"></i> Miscellaneous informations</h2>
				<div class="form-group">
					<label class="col-md-2 control-label">User Rights</label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="userRight" value="<?php echo $userRight = $_Oli->getUserRight(); ?> (<?php echo $_Oli->getAccountInfos('RIGHTS', 'name', array('user_right' => $userRight)); ?>)" disabled />
						
						<p class="help-block">
							<span class="text-primary"><i class="fa fa-star fa-fw"></i></span>
							<a href="<?php echo $_Oli->getUrlParam(0); ?>account/permissions/">Let's see your permissions!</a>
						</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">Register date</label>
					<div class="col-md-10">
						<input type="date" class="form-control" name="registerDate" value="<?php echo date('Y-m-d', strtotime($accountsInfos['register_date'])); ?>" disabled />
					</div>
				</div> <?php /*<hr />
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-10">
						<button type="submit" class="btn btn-primary"><i class="fa fa-cloud-upload fa-fw"></i> Update informations</button>
						<button type="reset" class="btn btn-default"><i class="fa fa-times fa-fw"></i> Reset</button>
					</div>
				</div>*/ ?>
			</form>
		</div>
	</div>
</div>

<?php include COMMONPATH . 'footer.php'; ?>

</body>
</html>