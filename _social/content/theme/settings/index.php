<?php
if(!$_Oli->verifyAuthKey() OR $_Oli->getUserRightLevel() < $_Oli->translateUserRight('USER')) header('Location: ' . $_Oli->getShortcutLink('login'));

if(!$_Oli->isEmptyPostVars()) {
	if($_Oli->issetPostVars('name') AND empty($_Oli->getPostVars('name'))) $resultCode = 'NAME_EMPTY';
	if($_Oli->issetPostVars('biography') AND strlen($_Oli->getPostVars('biography')) > 256) $resultCode = 'BIOGRAPHY_TOO_LONG';
	else {
		$language = (!$_Oli->isEmptyPostVars('language') AND in_array($_Oli->getPostVars('language'), ['fr', 'en'])) ? $_Oli->getPostVars('language') : 'en';
		$name = $_Oli->getPostVars('name');
		$biography = $_Oli->getPostVars('biography');
		$gender = (!$_Oli->isEmptyPostVars('gender') AND in_array($_Oli->getPostVars('gender'), ['male', 'female'])) ? $_Oli->getPostVars('gender') : '';
		$job = $_Oli->getPostVars('job');
		$location = $_Oli->getPostVars('location');
		$website = $_Oli->getPostVars('website');
		$showActivity = $_Oli->getPostVars('showActivity') ? true : false;
		$birthday = $_Oli->getAccountInfos('ACCOUNTS', 'birthday', $_Oli->getAuthKeyOwner()) ?: ($_Oli->getPostVars('birthday') ? date('Y-m-d', strtotime($_Oli->getPostVars('birthday'))) : null);
		
		$hideAnnounce = $_Oli->getPostVars('hideAnnounce') ? true : false;
		$hideMajorAnnounce = $_Oli->getPostVars('hideMajorAnnounce') ? true : false;
		
		if($_Oli->updateAccountInfos('ACCOUNTS', array('language' => $language, 'birthday' => $birthday), $_Oli->getAuthKeyOwner())
		AND $_Oli->updateAccountInfos('INFOS', array('name' => $name, 'biography' => $biography, 'gender' => $gender, 'job' => $job, 'location' => $location, 'website' => $website, 'show_activity' => $showActivity, 'hide_announce' => $hideAnnounce, 'hide_major_announce' => $hideMajorAnnounce), $_Oli->getAuthKeyOwner()))
			$resultCode = 'SETTINGS_UPDATED';
		else $resultCode = 'UPDATE_FAILED';
	}
}
else if($_Oli->getUrlParam(2) == 'change-password') {
	if($_Oli->isExistAccountInfos('REQUESTS', array('username' => $_Oli->getAuthKeyOwner(), 'action' => 'change-password')) AND strtotime($_Oli->getAccountInfos('REQUESTS', 'expire_date', array('username' => $_Oli->getAuthKeyOwner(), 'action' => 'change-password'))) >= time())
		$resultCode = 'REQUEST_ALREADY_EXIST';
	else if($_Oli->getUrlParam(3) == 'confirmed') {
		$activateKey = $_Oli->createRequest($_Oli->getAuthKeyOwner(), 'change-password');
		
		$email = $_Oli->getAccountInfos('ACCOUNTS', 'email', $_Oli->getAuthKeyOwner());
		$subject = 'Changez votre mot de passe';
		$message = 'Bonjour ' . $_Oli->getAuthKeyOwner() . ', <br />';
		$message .= 'Une requête de changement de mot de passe a été créée pour votre compte <br /> <br />';
		$message .= 'Rendez-vous sur ce lien pour choisir votre nouveau mot de passe : <br />';
		$message .= '<a href="' . $_Oli->getShortcutLink('login') . '/change-password/' . $activateKey . '">' . $_Oli->getShortcutLink('login') . '/change-password/' . $activateKey . '</a> <br /> <br />';
		$message .= 'Vous avez jusqu\'au ' . date('d/m/Y', strtotime($_Oli->getAccountInfos('REQUESTS', 'expire_date', array('username' => $_Oli->getAuthKeyOwner(), 'action' => 'change-password'))) + $_Oli->getRequestsExpireDelay()) . ', <br />';
		$message .= 'Une fois cette date passée, le code d\'activation ne sera plus valide <br /> <br />';
		$message .= 'Si vous n\'avez pas demandé ce changement de mot de passe, veuillez ignorer ce message <br />';
		$message .= 'Si vous avez l\'occasion de vous connecter sur le site, vous pouvez, depuis le panel, annuler cette requête';
		$headers = 'From: noreply@' . $_Oli->getSetting('domain') . "\r\n";
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$mailStatus = mail($email, $subject, utf8_decode($message), $headers);
		
		if($mailStatus) $resultCode = 'REQUEST_CREATED';
		else {
			$_Oli->deleteAccountLines('REQUESTS', array('activate_key' => $activateKey));
			$resultCode = 'REQUEST_FAILED';
		}
	}
	else $resultCode = 'CONFIRMATION_NEEDED';
}
?>

<!DOCTYPE html>
<html>
<head>

<?php include THEMEPATH . 'head.php'; ?>
<title>Settings - <?php echo $_Oli->getSetting('name'); ?></title>

</head>
<body>

<?php include THEMEPATH . 'header.php'; ?>

<div class="main">
	<div class="container-fluid">
		<div class="row">
			<div class="leftBar col-sm-3">
				<?php if($_Oli->getUrlParam(2) == 'change-password' AND $resultCode == 'CONFIRMATION_NEEDED') { ?>
					<div class="message message-highlight">
						<p>
							<b>You asked to change your password</b>, please confirm you want to create the request <hr />
							<a href="<?php echo $_Oli->getUrlParam(0); ?><?php echo $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/confirmed">
								<span class="text-success"><i class="fa fa-check fa-fw"></i> I want to create this request</span>
							</a> <br />
							<a href="<?php echo $_Oli->getUrlParam(0); ?><?php echo $_Oli->getUrlParam(1); ?>/">
								<span class="text-danger"><i class="fa fa-times fa-fw"></i> I refuse to create this request</span>
							</a>
						</p>
					</div>
				<?php } else if($resultCode == 'NAME_EMPTY') { ?>
					<div class="message message-danger">
						<p>You can't set an empty name!</p>
					</div>
				<?php } else if($resultCode == 'BIOGRAPHY_TOO_LONG') { ?>
					<div class="message message-danger">
						<p>Your biography cannot be longer than 255 characters</p>
					</div>
				<?php } else if($resultCode == 'REQUEST_ALREADY_EXIST') { ?>
					<div class="message message-danger">
						<p>This request already exists</p>
					</div>
				<?php } else if($resultCode == 'SETTINGS_UPDATED') { ?>
					<div class="message message-success">
						<p>Your settings have been updated</p>
					</div>
				<?php } else if($resultCode == 'REQUEST_CREATED') { ?>
					<div class="message message-success">
						<p>Your request has been created, check your mails to complete it</p>
					</div>
				<?php } else if($resultCode == 'UPDATE_FAILED' OR $resultCode == 'REQUEST_FAILED') { ?>
					<div class="message message-danger">
						<p>An error occurred, please try again</p>
					</div>
				<?php } ?>
				
				<div class="menu-links content-card">
					<h3>General settings</h3>
					<p value="avatar" class="text-muted" style="font-weight: 700;">
						<i class="fa fa-angle-right fa-fw"></i> Avatar
					</p>
					<p value="profile" class="text-primary">
						<i class="fa fa-angle-right fa-fw"></i> Profile
					</p>
					<p value="account" class="text-primary">
						<i class="fa fa-angle-right fa-fw"></i> Account
					</p>
					<p value="notifications" class="text-primary">
						<i class="fa fa-angle-right fa-fw"></i> Notifications
					</p>
					<p value="security" class="text-primary">
						<i class="fa fa-angle-right fa-fw"></i> Security
					</p>
				</div>
			</div>
			
			<div class="mainBar col-sm-9">
				<div class="edit-avatar content-card" from="avatar">
					<h3>Change your avatar</h3>
					<form action="<?php echo $_Oli->getUrlParam(0); ?>set-avatar.php" class="form form-horizontal" method="post" enctype="multipart/form-data">
						<div class="form-group">
							<div class="col-xs-12">
								<p><a href="#" class="btn btn-danger btn-xs delete-avatar"><i class="fa fa-trash fa-fw"></i> Delete your local avatar and use your Gravatar instead <span></span></a></p>
								<?php $avatarInfos = $_Avatar->getFileLines(array('name' => 'user_avatar', 'owner' => $_Oli->getAuthKeyOwner())); ?>
								<img src="<?php echo (!empty($avatarInfos)) ? $_Avatar->getUploadsUrl() . $avatarInfos['path_addon'] . $avatarInfos['file_name'] : $_Gravatar->getGravatar($_Oli->getAccountInfos('ACCOUNTS', 'email', $_Oli->getAuthKeyOwner()), 80); ?>" class="img-rounded avatar" alt="<?php echo $_Oli->getAuthKeyOwner(); ?>" />
								<label><input type="file" name="avatar" data-content="Choose your new avatar"> <span><?php if(empty($avatarInfos)) { ?>Using your Gravatar<?php } ?></span></label>
								<div class="progress progress-striped active" style="display: none;"><div class="progress-bar"></div></div>
								
								<p>
									<i class="fa fa-check fa-fw"></i>
									<?php if($_Avatar->getAllowedFileTypes() == '*') { ?>
										Any format allowed
									<?php } else { ?>
										Formats allowed : <b>.<?php echo implode(', .', $_Avatar->getAllowedFileTypes()); ?></b>
									<?php } ?> <br />
									<i class="fa fa-cloud-upload fa-fw"></i> Max size : <b><?php echo floor($_Avatar->getMaxSize('Mio')); ?> Mio</b>
								</p>
								<p>If no avatar have been uploaded here, your <a href="https://gravatar.com/">Gravatar</a> would be used instead if available.</p>
							</div>
						</div>
					</form>
				</div>
				
				<form action="<?php echo $_Oli->getUrlParam(0); ?>form.php" class="form form-horizontal" method="post">
					<div class="content-card" from="profile">
						<h3>Your Profile</h3>
						<div class="form-group">
							<label class="col-sm-2 control-label">Language</label>
							<div class="col-sm-10">
								<select class="form-control" name="language">
									<option value="en" <?php if($_Oli->getUserLanguage() == 'en' OR empty($_Oli->getUserLanguage())) { ?>selected<?php } ?>>
										English (EN)
									</option>
									<option value="fr" <?php if($_Oli->getUserLanguage() == 'fr') { ?>selected<?php } ?>>
										Français (FR)
									</option>
									<option disabled<?php /*value="de" <?php if($_Oli->getUserLanguage() == 'de') { ?>selected<?php } ?>*/ ?>>
										Deutsch (DE)
									</option>
									<option disabled<?php /*value="es" <?php if($_Oli->getUserLanguage() == 'es') { ?>selected<?php } ?>*/ ?>>
										Español (ES)
									</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Name</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="name" value="<?php echo $_Oli->getAccountInfos('INFOS', 'name', $_Oli->getAuthKeyOwner()) ?: $_Oli->getAuthKeyOwner(); ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Bio</label>
							<div class="col-sm-10">
								<textarea class="form-control" name="biography" rows="3"><?php echo $_Oli->getAccountInfos('INFOS', 'biography', $_Oli->getAuthKeyOwner()); ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Gender</label>
							<div class="col-sm-5 radio">
								<label>
									<input type="radio" name="gender" value="male" <?php if($_Oli->getAccountInfos('INFOS', 'gender', $_Oli->getAuthKeyOwner()) == 'male') { ?>checked<?php } ?> />
									<i class="fa fa-mars fa-fw"></i> Male
								</label>
							</div>
							<div class="col-sm-5 radio">
								<label>
									<input type="radio" name="gender" value="female" <?php if($_Oli->getAccountInfos('INFOS', 'gender', $_Oli->getAuthKeyOwner()) == 'female') { ?>checked<?php } ?> />
									<i class="fa fa-venus fa-fw"></i> Female
								</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Job</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="job" value="<?php echo $_Oli->getAccountInfos('INFOS', 'job', $_Oli->getAuthKeyOwner()); ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Location</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="location" value="<?php echo $_Oli->getAccountInfos('INFOS', 'location', $_Oli->getAuthKeyOwner()); ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Website</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="website" value="<?php echo $_Oli->getAccountInfos('INFOS', 'website', $_Oli->getAuthKeyOwner()); ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Show Activity</label>
							<div class="col-sm-10 checkbox">
								<label>
									<input type="checkbox" name="showActivity" <?php if($_Oli->getAccountInfos('INFOS', 'show_activity', $_Oli->getAuthKeyOwner())) { ?>checked<?php } ?> />
									<i class="fa fa-user fa-fw"></i> Let users know when I'm online
								</label>
								<p class="help-block">
									Once checked, <b>the last time you were logged will be shown</b> on your profile page. <br />
									Only logged users will be able to this.
								</p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Birthday</label>
							<div class="col-sm-10">
								<input type="date" class="form-control" name="birthday" value="<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'birthday', $_Oli->getAuthKeyOwner()); ?>" <?php if(!empty($_Oli->getAccountInfos('ACCOUNTS', 'birthday', $_Oli->getAuthKeyOwner()))) { ?>disabled<?php } ?> />
								<?php if(empty($_Oli->getAccountInfos('ACCOUNTS', 'birthday', $_Oli->getAuthKeyOwner()))) { ?>
									<p class="help-block">
										<span class="text-danger"><i class="fa fa-warning fa-fw"></i> Once set, you will not be able to change it again.</span> <br />
										Your birthday date is used to grant you access to restricted features.
									</p>
								<?php } else { ?>
									<p class="help-block">
										<span class="text-danger"><i class="fa fa-warning fa-fw"></i> Cannot be changed.</span>
										You can ask an admin to do it for you.
									</p>
								<?php } ?>
							</div>
						</div>
						<hr />
						
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="submit" class="btn btn-primary">Update</button>
								<button type="reset" class="btn btn-default">Reset</button>
							</div>
						</div>
					</div>
				
					<div class="content-card" from="account">
						<h3>Your Account</h3>
						<div class="form-group">
							<label class="col-sm-2 control-label">Username</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="username" value="<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'username', $_Oli->getAuthKeyOwner()); ?>" disabled />
								<p class="help-block">
									<span class="text-danger"><i class="fa fa-warning fa-fw"></i> Cannot be changed.</span>
								</p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Password</label>
							<div class="col-sm-10">
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/change-password" class="btn btn-primary <?php if($_Oli->isExistAccountInfos('REQUESTS', array('username' => $_Oli->getAuthKeyOwner(), 'action' => 'change-password')) AND strtotime($_Oli->getAccountInfos('REQUESTS', 'expire_date', array('username' => $_Oli->getAuthKeyOwner(), 'action' => 'change-password'))) >= time()) { ?>disabled<?php } ?>">
									<i class="fa fa-pencil fa-fw"></i> Change your password
								</a>
								<?php if($_Oli->isExistAccountInfos('REQUESTS', array('username' => $_Oli->getAuthKeyOwner(), 'action' => 'change-password')) AND strtotime($_Oli->getAccountInfos('REQUESTS', 'expire_date', array('username' => $_Oli->getAuthKeyOwner(), 'action' => 'change-password'))) >= time()) { ?>
									<p class="help-block">
										<span class="text-danger"><i class="fa fa-warning fa-fw"></i> A valid request already exists for this action.</span>
									</p>
								<?php } ?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Email</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="email" value="<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'email', $_Oli->getAuthKeyOwner()); ?>" disabled />
								<p class="help-block">
									<span class="text-danger"><i class="fa fa-warning fa-fw"></i> Cannot be changed.</span>
								</p>
							</div>
						</div>
						
						<?php /*<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="submit" class="btn btn-primary">Mettre à jour</button>
								<button type="reset" class="btn btn-default">Réinitialiser</button>
							</div>
						</div>*/ ?>
					</div>
				
					<div class="content-card" from="notifications">
						<h3>Your Notifications</h3>
						<div class="form-group">
							<label class="col-sm-2 control-label">Hide Announces</label>
							<div class="col-sm-10 checkbox">
								<label>
									<input type="checkbox" name="hideAnnounce" <?php if($_Oli->getAccountInfos('INFOS', 'hide_announce', $_Oli->getAuthKeyOwner())) { ?>checked<?php } ?> />
									Hide announcements from my notifications
								</label>
								<p class="help-block">
									Announcements keep you aware of project news and bigs updates.
								</p>
								<label>
									<input type="checkbox" name="hideMajorAnnounce" <?php if($_Oli->getAccountInfos('INFOS', 'hide_major_announce', $_Oli->getAuthKeyOwner())) { ?>checked<?php } ?> />
									<i class="fa fa-exclamation fa-fw"></i> Hide also importants announcements
								</label>
								<p class="help-block">
									<span class="text-danger"><i class="fa fa-warning fa-fw"></i> Not recommended!</span>
									Important announcements warn you from major problems (e.g. security problems). <br />
									Only works if regular announcements are also hidden.
								</p>
							</div>
						</div>
						<hr />
						
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="submit" class="btn btn-primary">Update</button>
								<button type="reset" class="btn btn-default">Reset</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php $_Oli->loadEndHtmlFiles(); ?>

</body>
</html>