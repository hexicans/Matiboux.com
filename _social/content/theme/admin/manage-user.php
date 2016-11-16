<?php
if(!$_Oli->verifyAuthKey() OR $_Oli->getUserRightLevel($_Oli->getAuthKeyOwner()) < $_Oli->translateUserRight('ADMIN')) header('Location: ' . $_Oli->getShortcutLink('login'));

if(!$_Oli->isEmptyPostVars() AND $_Oli->isExistAccountInfos('ACCOUNTS', $_Oli->getUrlParam(2), false)) {
	if($_Oli->issetPostVars('biography') AND strlen($_Oli->getPostVars('biography')) > 256) $resultCode = 'BIOGRAPHY_TOO_LONG';
	else {
		$email = $_Oli->getPostVars('email');
		$birthday = date('Y-m-d', strtotime($_Oli->getPostVars('birthday')));
		
		$userRight = $_Oli->verifyUserRight($_Oli->getPostVars('userRight')) ? ($_Oli->translateUserRight($_Oli->getPostVars('userRight')) <= $_Oli->getUserRightLevel($_Oli->getAuthKeyOwner()) ? $_Oli->getPostVars('userRight') : $_Oli->getUserRight($_Oli->getAuthKeyOwner())) : 'USER';
		$adminNote = $_Oli->getPostVars('adminNote');
		
		$name = $_Oli->getPostVars('name');
		$biography = $_Oli->getPostVars('biography');
		$gender = in_array($_Oli->getPostVars('gender'), ['male', 'female']) ? $_Oli->getPostVars('gender') : '';
		$job = $_Oli->getPostVars('job');
		$location = $_Oli->getPostVars('location');
		$website = $_Oli->getPostVars('website');
		
		if($_Oli->updateAccountInfos('ACCOUNTS', array('email' => $email, 'birthday' => $birthday, 'user_right' => $userRight, 'admin_note' => $adminNote), $_Oli->getAccountInfos('ACCOUNTS', 'username', $_Oli->getUrlParam(2), false))
		AND $_Oli->updateAccountInfos('INFOS', array('name' => $name, 'biography' => $biography, 'gender' => $gender, 'job' => $job, 'location' => $location, 'website' => $website), $_Oli->getAccountInfos('ACCOUNTS', 'username', $_Oli->getUrlParam(2), false))) {
			$resultCode = 'SETTINGS_UPDATED';
			
			if($_Oli->issetPostVars('username') AND $_Oli->getPostVars('username') != $_Oli->getAccountInfos('ACCOUNTS', 'username', $_Oli->getUrlParam(2), false)) {
				$oldUsername = $_Oli->getAccountInfos('ACCOUNTS', 'username', $_Oli->getUrlParam(2), false);
				
				$_Oli->updateAccountUsername($_Oli->getPostVars('username'), $oldUsername);
				$_Oli->updateInfosMySQL('social_follows', array('username' => $_Oli->getPostVars('username')), array('username' => $oldUsername));
				$_Oli->updateInfosMySQL('social_follows', array('follows' => $_Oli->getPostVars('username')), array('follows' => $oldUsername));
				// $_Oli->updateInfosMySQL('social_likes', array('username' => $_Oli->getPostVars('username')), array('username' => $oldUsername));
				$_Oli->updateInfosMySQL('social_medias', array('owner' => $_Oli->getPostVars('username')), array('owner' => $oldUsername));
				$_Oli->updateInfosMySQL('social_notifications', array('username' => $_Oli->getPostVars('username')), array('username' => $oldUsername));
				$_Oli->updateInfosMySQL('social_posts', array('owner' => $_Oli->getPostVars('username')), array('owner' => $oldUsername));
				$_Oli->updateInfosMySQL('support_tickets', array('owner' => $_Oli->getPostVars('username')), array('owner' => $oldUsername));
				$_Oli->updateInfosMySQL('user_avatars', array('owner' => $_Oli->getPostVars('username')), array('owner' => $oldUsername));
				header('Location: ' . $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1) . '/' . $_Oli->getPostVars('username'));
			}
		}
		else $resultCode = 'UPDATE_FAILED';
	}
}
else if($_Oli->getUrlParam(3) == 'change-password' AND $_Oli->isExistAccountInfos('ACCOUNTS', $_Oli->getUrlParam(2), false)) {
	if($_Oli->getUrlParam(4) == 'confirmed') {
		$_Oli->deleteAccountLines('REQUESTS', array('username' => $_Oli->getAccountInfos('ACCOUNTS', 'username', $_Oli->getUrlParam(2), false), 'action' => 'change-password'));
		
		$activateKey = $_Oli->createRequest($_Oli->getAccountInfos('ACCOUNTS', 'username', $_Oli->getUrlParam(2), false), 'change-password');
		
		$email = $_Oli->getAccountInfos('ACCOUNTS', 'email', $_Oli->getAccountInfos('ACCOUNTS', 'username', $_Oli->getUrlParam(2), false));
		$subject = 'Changez votre mot de passe';
		$message = 'Bonjour ' . $_Oli->getAccountInfos('ACCOUNTS', 'username', $_Oli->getUrlParam(2), false) . ', <br />';
		$message .= 'Une requête de changement de mot de passe a été créée pour votre compte <br /> <br />';
		$message .= 'Rendez-vous sur ce lien pour choisir votre nouveau mot de passe : <br />';
		$message .= '<a href="' . $_Oli->getShortcutLink('login') . '/change-password/' . $activateKey . '">' . $_Oli->getShortcutLink('login') . '/change-password/' . $activateKey . '</a> <br /> <br />';
		$message .= 'Vous avez jusqu\'au ' . date('d/m/Y', strtotime($_Oli->getAccountInfos('REQUESTS', 'expire_date', array('username' => $_Oli->getAccountInfos('ACCOUNTS', 'username', $_Oli->getUrlParam(2), false), 'action' => 'change-password'))) + $_Oli->getRequestsExpireDelay()) . ', <br />';
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
<?php $_Oli->loadLocalScript('js/search.js', false); ?>
<title>Edit user infos - <?php echo $_Oli->getSetting('name'); ?></title>

</head>
<body>

<?php include THEMEPATH . 'admin/header.php'; ?>

<div class="bigMedia" style="display: none;"><img /></div>
<div class="main">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-offset-2 col-sm-8">
				<?php if($_Oli->getUrlParam(3) == 'change-password' AND $resultCode == 'CONFIRMATION_NEEDED') { ?>
					<div class="message message-highlight">
						<p>
							<b>You asked to create a new change password request</b>, please confirm you want to create the request <hr />
							<a href="<?php echo $_Oli->getUrlParam(0); ?><?php echo $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $_Oli->getUrlParam(3); ?>/confirmed">
								<span class="text-success"><i class="fa fa-check fa-fw"></i> I want to create this request</span>
							</a> <br />
							<a href="<?php echo $_Oli->getUrlParam(0); ?><?php echo $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/">
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
						<p>Your biography cannot be longer than 256 characters</p>
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
				<div class="message" id="message" style="display: none;"></div>
				
				<h3 class="text-center"><i class="fa fa-pencil fa-fw"></i> Edit user infos</h3>
				<div class="search content-card">
					<form action="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/" class="form form-horizontal" method="post" enctype="multipart/form-data">
						<div class="form-group">
							<div class="col-xs-12">
								<div class="input-group">
									<input type="text" class="form-control" name="search" placeholder="Search for a user" value="<?php echo $_Oli->getUrlParam(2); ?>" />
									<a href="#" class="input-group-addon btn btn-default submit">
										<i class="fa fa-search fa-fw"></i>
									</a>
								</div>
								<p class="help-block">
									<span class="text-info">Here you can search for an username</span>
								</p>
							</div>
						</div>
					</form>
				</div>
			</div>
			
			<?php if(!empty($_Oli->getUrlParam(2))) { ?>
				<?php foreach($_Oli->getAccountInfos('ACCOUNTS', 'username', null, false, true) as $eachUsername) { ?>
					<?php if(strlen($_Oli->getUrlParam(2)) >= 3 AND (stripos($eachUsername, substr($_Oli->getUrlParam(2), 0, 1) == '@' ? substr($_Oli->getUrlParam(2), 1) : $_Oli->getUrlParam(2)) !== false OR stripos($_Oli->getAccountInfos('INFOS', 'name', $eachUsername), substr($_Oli->getUrlParam(2), 0, 1) == '@' ? substr($_Oli->getUrlParam(2), 1) : $_Oli->getUrlParam(2)) !== false)) { ?>
						<?php $users[] = $eachUsername; ?>
					<?php } ?>
				<?php } ?>
				<?php $countUsers = count($users); ?>
				
				<?php $validUsername = $_Oli->isExistAccountInfos('ACCOUNTS', $_Oli->getUrlParam(2), false); ?>
				
				<?php if(!empty($users) OR $validUsername) { ?>
					<div class="<?php if(!$validUsername) { ?>col-sm-offset-3 col-sm-6<?php } else { ?>col-sm-4<?php } ?>">
						<h4 class="text-center">Users found for <b><?php echo $_Oli->getUrlParam(2); ?></b></h4>
						
						<?php if($_Oli->isExistAccountInfos('ACCOUNTS', $_Oli->getUrlParam(2), false)) { ?>
							<?php $targetUsername = $_Oli->getAccountInfos('ACCOUNTS', 'username', $_Oli->getUrlParam(2), false); ?>
							<div class="profile content-card text-center">
								<div class="header">
									<?php $avatarInfos = $_Avatar->getFileLines(array('name' => 'user_avatar', 'owner' => $targetUsername)); ?>
									<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $targetUsername; ?>" class="header-left">
										<img src="<?php echo (!empty($avatarInfos)) ? $_Avatar->getUploadsUrl() . $avatarInfos['path_addon'] . $avatarInfos['file_name'] : $_Gravatar->getGravatar($_Oli->getAccountInfos('ACCOUNTS', 'email', $targetUsername), 100); ?>" class="avatar img-rounded" alt="<?php echo $targetUsername; ?>" />
									</a>
									<div class="header-body">
										<h3 class="heading">
											<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $targetUsername; ?>">
												<?php echo $_Oli->getAccountInfos('INFOS', 'name', $targetUsername) ?: $targetUsername; ?>
											</a>
										</h3>
										<p><small>@<?php echo $targetUsername; ?></small></p>
										<p>
											<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $targetUsername; ?>" class="btn btn-default btn-sm">
												User profile
											</a>
										</p>
									</div>
								</div>
							</div>
						<?php } ?>
					
						<?php if(!empty($users)) { ?>
							<?php foreach(array_reverse($users) as $eachResult) { ?>
								<?php if(strtolower($_Oli->getUrlParam(2)) != strtolower($eachResult)) { ?>
									<div class="user content-card">
										<span class="pull-right text-right hidden-sm">
											<p>
												<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $eachResult; ?>" class="btn btn-default btn-sm">
													User profile
												</a>
											</p>
										</span>
										<div class="header">
											<?php $avatarInfos = $_Avatar->getFileLines(array('name' => 'user_avatar', 'owner' => $eachResult)); ?>
											<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $eachResult; ?>" class="header-left">
												<img src="<?php echo (!empty($avatarInfos)) ? $_Avatar->getUploadsUrl() . $avatarInfos['path_addon'] . $avatarInfos['file_name'] : $_Gravatar->getGravatar($_Oli->getAccountInfos('ACCOUNTS', 'email', $eachResult), 100); ?>" class="avatar img-rounded" alt="<?php echo $eachResult; ?>" />
											</a>
											<div class="header-body">
												<h4 class="heading">
													<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $eachResult; ?>">
														<?php echo $_Oli->getAccountInfos('INFOS', 'name', $eachResult) ?: $eachResult; ?>
													</a>
												</h4>
												<small>@<?php echo $eachResult; ?></small>
											</div>
										</div>
									</div>
								<?php } ?>
							<?php } ?>
						<?php } else if(strlen($_Oli->getUrlParam(2)) < 3) { ?>
							<div class="message message-warning text-center">
								<p>To search users, your search request have to be <b>at least 3 characters long</b></p>
							</div>
						<?php } ?>
					</div>
				
					<?php if($validUsername) { ?>
						<div class="col-sm-8">
							<h4>
								<?php $name = $_Oli->getAccountInfos('INFOS', 'name', $_Oli->getUrlParam(2), false) ?: $_Oli->getAuthKeyOwner(); ?>
								<b><?php echo $name; ?></b><?php echo substr($name, -1) == 's' ? '\'' : '\'s'; ?> infos
							</h4>
							<div class="content-card">
								<form action="<?php echo $_Oli->getUrlParam(0); ?>form.php" class="form form-horizontal" method="post">
									<div class="form-group">
										<label class="col-md-2 control-label">Username</label>
										<div class="col-md-10">
											<div class="input-group">
												<div class="input-group-addon">@</div>
												<input type="text" class="form-control" name="username" value="<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'username', $_Oli->getUrlParam(2), false); ?>" />
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-2 control-label">Password</label>
										<div class="col-md-10">
											<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/change-password" class="btn btn-primary">
												<i class="fa fa-pencil fa-fw"></i> Create a new change password request
											</a>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-2 control-label">Email</label>
										<div class="col-md-10">
											<input type="text" class="form-control" name="email" value="<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'email', $_Oli->getUrlParam(2), false); ?>" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-2 control-label">Birthday</label>
										<div class="col-md-10">
											<input type="date" class="form-control" name="birthday" value="<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'birthday', $_Oli->getUrlParam(2), false); ?>" />
										</div>
									</div> <hr />
									
									<div class="form-group">
										<label class="col-md-2 control-label">User Right</label>
										<div class="col-md-10">
											<select class="form-control" name="userRight">
												<?php foreach($_Oli->getAllUserRightsLines() as $eachRight) { ?>
													<?php if($_Oli->translateUserRight($eachRight['user_right']) <= $_Oli->getUserRightLevel($_Oli->getAuthKeyOwner())) { ?>
														<option value="<?php echo $eachRight['user_right']; ?>" <?php if($_Oli->getUserRight($_Oli->getUrlParam(2), false) == $eachRight['user_right']) { ?>selected<?php } ?>>
															<?php echo $eachRight['name']; ?> (<?php echo $eachRight['user_right']; ?>)
														</option>
													<?php } ?>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-2 control-label">Note</label>
										<div class="col-md-10">
											<textarea class="form-control" name="adminNote" rows="3"><?php echo $_Oli->getAccountInfos('ACCOUNTS', 'admin_note', $_Oli->getUrlParam(2), false); ?></textarea>
										</div>
									</div> <hr />
									
									<div class="form-group">
										<label class="col-md-2 control-label">Name</label>
										<div class="col-md-10">
											<input type="text" class="form-control" name="name" value="<?php echo $_Oli->getAccountInfos('INFOS', 'name', $_Oli->getUrlParam(2), false) ?: $_Oli->getAccountInfos('ACCOUNTS', 'username', $_Oli->getUrlParam(2), false); ?>" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-2 control-label">Bio</label>
										<div class="col-md-10">
											<textarea class="form-control" name="biography" rows="3"><?php echo $_Oli->getAccountInfos('INFOS', 'biography', $_Oli->getUrlParam(2), false); ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-2 control-label">Gender</label>
										<div class="col-md-5 radio">
											<label>
												<input type="radio" name="gender" value="male" <?php if($_Oli->getAccountInfos('INFOS', 'gender', $_Oli->getUrlParam(2), false) == 'male') { ?>checked<?php } ?> />
												<i class="fa fa-mars fa-fw"></i> Male
											</label>
										</div>
										<div class="col-md-5 radio">
											<label>
												<input type="radio" name="gender" value="female" <?php if($_Oli->getAccountInfos('INFOS', 'gender', $_Oli->getUrlParam(2), false) == 'female') { ?>checked<?php } ?> />
												<i class="fa fa-venus fa-fw"></i> Female
											</label>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-2 control-label">Job</label>
										<div class="col-md-10">
											<input type="text" class="form-control" name="job" value="<?php echo $_Oli->getAccountInfos('INFOS', 'job', $_Oli->getUrlParam(2), false); ?>" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-2 control-label">Location</label>
										<div class="col-md-10">
											<input type="text" class="form-control" name="location" value="<?php echo $_Oli->getAccountInfos('INFOS', 'location', $_Oli->getUrlParam(2), false); ?>" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-2 control-label">Website</label>
										<div class="col-md-10">
											<input type="text" class="form-control" name="website" value="<?php echo $_Oli->getAccountInfos('INFOS', 'website', $_Oli->getUrlParam(2), false); ?>" />
										</div>
									</div> <hr />
									
									<div class="form-group">
										<div class="col-md-offset-2 col-md-10">
											<button type="submit" class="btn btn-primary">Update</button>
											<button type="reset" class="btn btn-default">Reset</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					<?php } ?>
				<?php } else { ?>
					<div class="col-sm-offset-2 col-sm-8">
						<div class="message message-danger text-center">
							<h3>No result for <i><?php echo $_Oli->getUrlParam(2); ?></i></h3>
						</div>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
</div>

<?php $_Oli->loadEndHtmlFiles(); ?>

</body>
</html>