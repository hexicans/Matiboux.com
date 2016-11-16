<?php
if(!$_Oli->verifyAuthKey() OR $_Oli->getUserRightLevel($_Oli->getAuthKeyOwner()) < $_Oli->translateUserRight('ADMIN')) header('Location: ' . $_Oli->getShortcutLink('login'));

if(!empty($_Oli->getUrlParam(2)) AND $_Oli->isExistAccountInfos('ACCOUNTS', $_Oli->getUrlParam(2), false)) {
	$targetUser = $_Oli->getAccountInfos('ACCOUNTS', 'username', array('email' => $_Oli->getUrlParam(2)), false) ?: $_Oli->getAccountInfos('ACCOUNTS', 'username', $_Oli->getUrlParam(2), false);
	if($_Oli->getUrlParam(3) == 'confirmed') {
		if($_Oli->getUserRightLevel($targetUser, false) >= $_Oli->translateUserRight('USER')) {
			$newAuthKey = $_Oli->keygen(100);
			$cookieDuration = 24*3600;
			
			$matches['id'] = $_Oli->getLastAccountInfo('SESSIONS', 'id') + 1;
			$matches['username'] = $targetUser;
			$matches['auth_key'] = $newAuthKey;
			$matches['user_ip'] = $_Oli->getUserIP();
			$matches['login_date'] = date('Y-m-d H:i:s');
			$matches['expire_date'] = date('Y-m-d H:i:s', time() + $cookieDuration);
			$matches['update_date'] = date('Y-m-d H:i:s');
			
			if($_Oli->insertAccountLine('SESSIONS', $matches)) {
				$_Oli->setAuthKeyCookie($newAuthKey, $cookieDuration);
				header('Location: ' . $_Oli->getUrlParam(0));
			}
			else $resultCode = 'LOGGING_ERROR';
		}
		else $resultCode = 'BANNED_USER';
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
				<?php if(!empty($_Oli->getUrlParam(2)) AND !empty($targetUser) AND $resultCode == 'CONFIRMATION_NEEDED') { ?>
					<div class="message message-highlight">
						<p>
							Please confirm you want to <b>log as @<?php echo $targetUser; ?></b> <hr />
							<span class="text-success">
								<i class="fa fa-check fa-fw"></i>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/confirmed">I want to log in</a>
							</span> <br />
							<span class="text-danger">
								<i class="fa fa-times fa-fw"></i>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/">I don't want to</a>
							</span>
						</p>
					</div>
				<?php } else if($resultCode == 'LOGGING_ERROR') { ?>
					<div class="message message-danger">
						<p>An error occured while logging you in</p>
					</div>
				<?php } else if($resultCode == 'BANNED_USER') { ?>
					<div class="message message-danger">
						<p>This user doesn't have sufficient rights to log in</p>
					</div>
				<?php } ?>
				<div class="message" id="message" style="display: none;"></div>
				
				<h3 class="text-center"><i class="fa fa-sign-in fa-fw"></i> Log as user</h3>
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
				
				<?php if(!empty($users)) { ?>
					<div class="col-sm-offset-3 col-sm-6">
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