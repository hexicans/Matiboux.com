<?php
if(!$_Oli->verifyAuthKey() OR $_Oli->getUserRightLevel($_Oli->getAuthKeyOwner()) < $_Oli->translateUserRight('ADMIN')) header('Location: ' . $_Oli->getShortcutLink('login'));

if($_Oli->getUrlParam(3) == 'edit' AND !empty($_Oli->getUrlParam(4)) AND !$_Oli->isEmptyPostVars() AND in_array($_Oli->getInfosMySQL('social_notifications', 'type', array('id' => $_Oli->getUrlParam(4), 'username' => $_Oli->getUrlParam(2)), false), ['announce', 'major_announce'])) {
	if($_Oli->isEmptyPostVars('message')) $resultCode = 'MESSAGE_EMPTY';
	else if(strlen($_Oli->getPostVars('message')) > 512) $resultCode = 'MESSAGE_TOO_LONG';
	else if(!$_Oli->isExistAccountInfos('social_notifications', array('id' => $_Oli->getUrlParam(4)))) $resultCode = 'UNKNOWN_POST';
	else {
		$message = $_Oli->getPostVars('message');
		
		if($_Oli->updateInfosMySQL('social_notifications', array('data' => array('message' => $message)), array('id' => $_Oli->getUrlParam(4))))
			$resultCode = 'NOTIFICATION_UPDATED';
		else $resultCode = 'UPDATE_FAILED';
	}
}
else if($_Oli->getUrlParam(3) == 'reset' AND !empty($_Oli->getUrlParam(4))) {
	if(!$_Oli->isExistAccountInfos('social_notifications', array('id' => $_Oli->getUrlParam(4)))) $resultCode = 'UNKNOWN_NOTIFICATION';
	else {
		if($_Oli->updateInfosMySQL('social_notifications', array('seen_date' => null), array('id' => $_Oli->getUrlParam(4))))
			$resultCode = 'NOTIFICATION_RESETED';
		else $resultCode = 'RESET_FAILED';
	}
}
else if($_Oli->getUrlParam(3) == 'delete' AND !empty($_Oli->getUrlParam(4))) {
	$paramData = urldecode($_Oli->getUrlParam(4));
	$selectedMedias = (!is_array($paramData)) ? ((is_array(unserialize($paramData))) ? unserialize($paramData) : [$paramData]) : $paramData;
	
	foreach($selectedMedias as $eachKey) {
		if(!$_Oli->isExistInfosMySQL('social_notifications', array('id' => $eachKey))) {
			$errorStatus = 'UNKNOWN_POST';
			break;
		}
	}
	
	if(!empty($errorStatus)) $resultCode = $errorStatus;
	else if($_Oli->getUrlParam(5) != 'confirmed') $resultCode = 'CONFIRMATION_NEEDED';
	else {
		foreach($selectedMedias as $eachKey) {
			$_Oli->deleteLinesMySQL('social_notifications', array('id' => $eachKey));
		}
		$resultCode = 'NOTIFICATION_DELETED';
	}
}
?>

<!DOCTYPE html>
<html>
<head>

<?php include THEMEPATH . 'head.php'; ?>
<?php $_Oli->loadLocalScript('js/search.js', false); ?>
<title>Manage user notifications - <?php echo $_Oli->getSetting('name'); ?></title>

</head>
<body>

<?php include THEMEPATH . 'admin/header.php'; ?>

<div class="bigMedia" style="display: none;"><img /></div>
<div class="main">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-offset-2 col-sm-8">
				<?php if($_Oli->getUrlParam(3) == 'delete' AND !empty($_Oli->getUrlParam(4)) AND $resultCode == 'CONFIRMATION_NEEDED') { ?>
					<div class="message message-highlight-danger">
						<p>
							<b>You asked to delete these notifications</b>, please confirm you want to delete them <hr />
							<span class="text-success">
								<i class="fa fa-check fa-fw"></i>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $_Oli->getUrlParam(3); ?>/<?php echo $_Oli->getUrlParam(4); ?>/confirmed">I want to delete them</a>
							</span> <br />
							<span class="text-danger">
								<i class="fa fa-times fa-fw"></i>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/">I refuse to delete them</a>
							</span>
						</p>
					</div>
				<?php } else if($resultCode == 'MESSAGE_EMPTY') { ?>
					<div class="message message-danger">
						<p>You can't let the message empty!</p>
					</div>
				<?php } else if($resultCode == 'MESSAGE_TOO_LONG') { ?>
					<div class="message message-danger">
						<p>The message cannot be longer than 512 characters</p>
					</div>
				<?php } else if($resultCode == 'UNKNOWN_NOTIFICATION') { ?>
					<div class="message message-danger">
						<p>You tried to act on a notification that not exist</p>
					</div>
				<?php } else if($resultCode == 'NOT_YOUR_POST') { ?>
					<div class="message message-danger">
						<p>You tried to act on a post that is not yours</p>
					</div>
				<?php } else if($resultCode == 'NOTIFICATION_UPDATED') { ?>
					<div class="message message-success">
						<p>The notification have been updated</p>
					</div>
				<?php } else if($resultCode == 'NOTIFICATION_RESETED') { ?>
					<div class="message message-success">
						<p>The notification have been reseted</p>
					</div>
				<?php } else if($resultCode == 'NOTIFICATION_DELETED') { ?>
					<div class="message message-success">
						<p>The post have been deleted</p>
					</div>
				<?php } else if(in_array($resultCode, ['UPDATE_FAILED', 'RESET_FAILED'])) { ?>
					<div class="message message-danger">
						<p>An error occurred, please try again</p>
					</div>
				<?php } ?>
				<div class="message" id="message" style="display: none;"></div>
				
				<h3 class="text-center"><i class="fa fa-file-text-o fa-fw"></i> Manage user notifications</h3>
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
				
				<?php $allUserNotifications = $_Oli->getLinesMySQL('social_notifications', array('username' => $_Oli->getUrlParam(2)), false, true); ?>
				<?php $countAllUserNotifications = count($allUserNotifications); ?>
				
				<?php $limit = 30; ?>
				<?php $totalPages = ceil($countAllUserNotifications / $limit); ?>
				<?php $page = array_reverse($_Oli->getUrlParam('params'))[0] >= 1 ? (array_reverse($_Oli->getUrlParam('params'))[0] < $totalPages ? array_reverse($_Oli->getUrlParam('params'))[0] : $totalPages) : 1; ?>
				
				
				<?php if(!empty($users) OR !empty($allUserNotifications)) { ?>
					<div class="<?php if(empty($allUserNotifications)) { ?>col-sm-offset-3 col-sm-6<?php } else { ?>col-sm-4<?php } ?>">
						<h4 class="text-center">Users found for <b><?php echo $_Oli->getUrlParam(2); ?></b></h4>
						
						<?php if($_Oli->getUrlParam(2) == 'all') { ?>
							<div class="profile content-card text-center">
								<h3 class="heading">Announcements Feed</h3>
							</div>
						<?php } else if($_Oli->isExistAccountInfos('ACCOUNTS', $_Oli->getUrlParam(2), false)) { ?>
							<?php if(empty($allUserNotifications)) { ?>
								<div class="message message-danger text-center">
									<p>This user hasn't got any post</h3>
								</div>
							<?php } ?>
							
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
								<p>To search users, your search request have to be <b>at least 3 characters long</p>
							</div>
						<?php } else if($_Oli->getUrlParam(2) != 'all') { ?>
							<div class="message message-warning text-center">
								<p>No user found</p>
							</div>
						<?php } ?>
					</div>
					
					<?php if($_Oli->getUrlParam(3) == 'edit' AND !empty($_Oli->getUrlParam(4)) AND in_array($_Oli->getInfosMySQL('social_notifications', 'type', array('id' => $_Oli->getUrlParam(4), 'username' => $_Oli->getUrlParam(2)), false), ['announce', 'major_announce'])) { ?>
						<div class="col-sm-8">
							<h4>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $page; ?>" class="btn btn-primary btn-sm">
									<i class="fa fa-angle-left fa-lg"></i> Back
								</a> - Edit an annoucement
							</h4>
							
							<?php $announceInfos = $_Oli->getLinesMySQL('social_notifications', array('id' => $_Oli->getUrlParam(4), 'username' => $_Oli->getUrlParam(2)), false); ?>
							<div class="notification content-card highlight" id="<?php echo $announceInfos['id']; ?>">
								<?php $rawContent = $announceInfos['data']['message']; ?>
								<?php preg_match_all('/(((?:https?:\/\/)?(?:[w]{3}\.)?)((?:[\da-z\.-]+)\.(?:[a-z\.]{2,6})([\/\w\.?=&-]*)*\/?))/', $announceInfos['data']['message'], $linksOutput); ?>
								<?php $links = is_array($linksOutput[1]) ? array_unique($linksOutput[1]) : [$linksOutput[1]]; ?>
								<?php if(!empty($links)) { ?>
									<?php foreach($links as $eachKey => $eachLink) { ?>
										<?php $replace = '<a href="' . ($linksOutput[2][$eachKey] ?: 'http://') . $linksOutput[3][$eachKey] . '" target="_blank">' . $linksOutput[3][$eachKey]  . '</a>'; ?>
										<?php $announceInfos['data']['message'] = str_replace($eachLink, $replace, $announceInfos['data']['message']); ?>
									<?php } ?>
								<?php } ?>
								
								<?php preg_match_all('/@([\w-]+)/', $announceInfos['data']['message'], $mentionsOutput); ?>
								<?php $mentions = is_array($mentionsOutput[1]) ? array_unique($mentionsOutput[1]) : [$mentionsOutput[1]]; ?>
								<?php if(!empty($mentions)) { ?>
									<?php foreach($mentions as $eachMention) { ?>
										<?php if($_Oli->isExistAccountInfos('ACCOUNTS', $eachMention, false)) { ?>
											<?php $replace = '<a href="' . $_Oli->getUrlParam(0) . 'user/' . $_Oli->getAccountInfos('ACCOUNTS', 'username', $eachMention, false) . '">@' . $_Oli->getAccountInfos('ACCOUNTS', 'username', $eachMention, false) . '</a>'; ?>
											<?php $announceInfos['data']['message'] = str_replace('@' . $eachMention, $replace, $announceInfos['data']['message']); ?>
										<?php } ?>
									<?php } ?>
								<?php } ?>
								
								<?php preg_match_all('/#(\w+)/', $announceInfos['data']['message'], $hashtagsOutput); ?>
								<?php $hashtags = is_array($hashtagsOutput[1]) ? array_unique($hashtagsOutput[1]) : [$hashtagsOutput[1]]; ?>
								<?php if(!empty($hashtags)) { ?>
									<?php foreach($hashtags as $eachHashtag) { ?>
										<?php $replace = '<a href="' . $_Oli->getUrlParam(0) . 'search/' . urlencode('#') . $eachHashtag . '">#' . $eachHashtag . '</a>'; ?>
										<?php $announceInfos['data']['message'] = str_replace('#' . $eachHashtag, $replace, $announceInfos['data']['message']); ?>
									<?php } ?>
								<?php } ?>
								
								
								<p><b><span class="text-primary">Announcement!</span></b></p>
								<p class="content"><?php echo nl2br($announceInfos['data']['message']); ?></p> <hr />
								
								<form action="<?php echo $_Oli->getUrlParam(0); ?>form.php" class="form form-horizontal" method="post">
									<div class="form-group">
										<div class="col-xs-12">
											<div class="input-group">
												<textarea type="text" class="form-control" name="message" rows="3"><?php echo $rawContent; ?></textarea>
												<a href="#" class="input-group-addon btn btn-default submit">
													<i class="fa fa-floppy-o fa-fw"></i> Edit
												</a>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					<?php } else if(!empty($allUserNotifications)) { ?>
						<div class="col-sm-8">
							<?php $userNotifications = array_slice(array_reverse($allUserNotifications), ($page - 1) * $limit, $limit); ?>
						
							<div class="tools">
								<p class="pull-right text-muted">
									Showing
									<?php if($page == 1) { ?>last<?php } ?>
									<?php echo count($userNotifications); ?> post<?php if(count($userNotifications) > 1) { ?>s<?php } ?>
								</p>
								<p>
									<?php if($page > 1) { ?>
										<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $_Oli->getUrlParam(3); ?>/<?php echo $page - 1; ?>" class="btn btn-link btn-xs">
											<span class="badge"><i class="fa fa-angle-left fa-fw"></i></span>
										</a>
									<?php } ?>
									Page <?php echo $page; ?>
									<?php if($page < $totalPages) { ?>
										<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $_Oli->getUrlParam(3); ?>/<?php echo $page + 1; ?>" class="btn btn-link btn-xs">
											<span class="badge"><i class="fa fa-angle-right fa-fw"></i></span>
										</a>
									<?php } ?>
								</p>
							</div>
							
							<?php foreach($userNotifications as $eachNotification) { ?>
								<div class="notification <?php if(!in_array($eachNotification['type'], ['announce', 'major_announce'])) { ?>small<?php } ?> content-card <?php if($eachNotification['type'] == 'major_announce') { ?>highlight-danger<?php } else if($eachNotification['type'] == 'announce' OR !isset($eachNotification['seen_date'])) { ?>highlight<?php } ?>" <?php if(isset($eachNotification['data']['postId'])) { ?>id="<?php echo $eachNotification['data']['postId']; ?>"<?php } ?>>
									<span class="pull-right text-right">
										<p class="hidden-xs">
											<?php if(in_array($eachNotification['type'], ['announce', 'major_announce'])) { ?>
												<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/edit/<?php echo $eachNotification['id']; ?>/<?php echo $page; ?>" class="btn btn-link btn-xs">
													<i class="fa fa-pencil fa-lg"></i>
												</a>
											<?php } else { ?>
												<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/reset/<?php echo $eachNotification['id']; ?>/<?php echo $page; ?>" class="btn btn-link btn-xs">
													<span class="text-primary">
														<i class="fa fa-refresh fa-lg"></i>
													</span>
												</a>
											<?php } ?>
											<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/delete/<?php echo $eachNotification['id']; ?>/<?php echo $page; ?>" class="btn btn-link btn-xs">
												<span class="text-danger">
													<i class="fa fa-trash fa-lg"></i>
												</span>
											</a>
										</p>
									</span>
									
									<p>
										<b>
											<?php if(!in_array($eachNotification['type'], ['announce', 'major_announce']) AND !isset($eachNotification['seen_date'])) { ?>
												<span class="text-primary">New!</span> -
											<?php } ?>
											
											<?php if($eachNotification['type'] == 'announce') { $type = 'announce'; ?>
												<span class="text-primary">Announcement!</span>
											<?php } else if($eachNotification['type'] == 'major_announce') { $type = 'announce'; ?>
												<span class="text-danger">Important announcement!</span>
											<?php } else if($eachNotification['type'] == 'reply') { $type = 'post'; ?>
												<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $eachNotification['data']['owner']; ?>"><?php echo $eachNotification['data']['owner']; ?></a>
												replied him with this post
											<?php } else if($eachNotification['type'] == 'mention') { $type = 'post'; ?>
												<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $eachNotification['data']['owner']; ?>"><?php echo $eachNotification['data']['owner']; ?></a>
												mentioned him in this post
											<?php } else if($eachNotification['type'] == 'like') { $type = 'post'; ?>
												<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $eachNotification['data']['username']; ?>"><?php echo $eachNotification['data']['username']; ?></a>
												liked his post
											<?php } else if($eachNotification['type'] == 'repost') { $type = 'post'; ?>
												<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $eachNotification['data']['username']; ?>"><?php echo $eachNotification['data']['username']; ?></a>
												reposted his post
											<?php } else if($eachNotification['type'] == 'follow') { $type = 'user'; ?> He has a new follower!
											<?php } else if($eachNotification['type'] == 'unfollow') { $type = 'user'; ?> He has lost a follower
											<?php } else { ?> He has a unknown notification
											<?php } ?>
										</b>
									</p>
									
									<?php if($type == 'announce') { ?>
										<?php preg_match_all('/(((?:https?:\/\/)?(?:[w]{3}\.)?)((?:[\da-z\.-]+)\.(?:[a-z\.]{2,6})([\/\w\.?=&-]*)*\/?))/', $eachNotification['data']['message'], $linksOutput); ?>
										<?php $links = is_array($linksOutput[1]) ? array_unique($linksOutput[1]) : [$linksOutput[1]]; ?>
										<?php if(!empty($links)) { ?>
											<?php foreach($links as $eachKey => $eachLink) { ?>
												<?php $replace = '<a href="' . ($linksOutput[2][$eachKey] ?: 'http://') . $linksOutput[3][$eachKey] . '" target="_blank">' . $linksOutput[3][$eachKey]  . '</a>'; ?>
												<?php $eachNotification['data']['message'] = str_replace($eachLink, $replace, $eachNotification['data']['message']); ?>
											<?php } ?>
										<?php } ?>
										
										<?php preg_match_all('/@([\w-]+)/', $eachNotification['data']['message'], $mentionsOutput); ?>
										<?php $mentions = is_array($mentionsOutput[1]) ? array_unique($mentionsOutput[1]) : [$mentionsOutput[1]]; ?>
										<?php if(!empty($mentions)) { ?>
											<?php foreach($mentions as $eachMention) { ?>
												<?php if($_Oli->isExistAccountInfos('ACCOUNTS', $eachMention, false)) { ?>
													<?php $replace = '<a href="' . $_Oli->getUrlParam(0) . 'user/' . $_Oli->getAccountInfos('ACCOUNTS', 'username', $eachMention, false) . '">@' . $_Oli->getAccountInfos('ACCOUNTS', 'username', $eachMention, false) . '</a>'; ?>
													<?php $eachNotification['data']['message'] = str_replace('@' . $eachMention, $replace, $eachNotification['data']['message']); ?>
												<?php } ?>
											<?php } ?>
										<?php } ?>
										
										<?php preg_match_all('/#(\w+)/', $eachNotification['data']['message'], $hashtagsOutput); ?>
										<?php $hashtags = is_array($hashtagsOutput[1]) ? array_unique($hashtagsOutput[1]) : [$hashtagsOutput[1]]; ?>
										<?php if(!empty($hashtags)) { ?>
											<?php foreach($hashtags as $eachHashtag) { ?>
												<?php $replace = '<a href="' . $_Oli->getUrlParam(0) . 'search/' . urlencode('#') . $eachHashtag . '">#' . $eachHashtag . '</a>'; ?>
												<?php $eachNotification['data']['message'] = str_replace('#' . $eachHashtag, $replace, $eachNotification['data']['message']); ?>
											<?php } ?>
										<?php } ?>
										
										<p class="content"><?php echo nl2br($eachNotification['data']['message']); ?></p> <hr class="visible-xs-block" />
										
										<div class="meta visible-xs-block">
											<p>
												<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/edit/<?php echo $eachPost['id']; ?>/<?php echo $page; ?>" class="btn btn-link btn-xs">
													<i class="fa fa-pencil fa-lg"></i>
												</a>
												<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/delete/<?php echo $eachPost['id']; ?>/<?php echo $page; ?>" class="btn btn-link btn-xs">
													<span class="text-danger">
														<i class="fa fa-trash fa-lg"></i>
													</span>
												</a>
											</p>
										</div>
									<?php } else if($type == 'post') { ?>
										<?php $eachPostInfos = $_Oli->getLinesMySQL('social_posts', array('id' => $eachNotification['data']['postId']), false); ?>
										<span class="pull-right text-right">
											<p>
												<i class="fa fa-clock-o fa-fw"></i>
												<a href="<?php echo $_Oli->getUrlParam(0); ?>post/<?php echo $eachPostInfos['id']; ?>">
													<time datetime="<?php echo date('Y-m-d H:i:s', strtotime($eachPostInfos['post_date'])); ?>">
														<?php $timeOutput = []; ?>
														<?php foreach($_Oli->dateDifference($eachPostInfos['post_date'], time(), true) as $eachUnit => $eachTime) { ?>
															<?php if(count($timeOutput) < 1) { ?>
																<?php if($eachTime > 0) { ?>
																	<?php if($eachUnit == 'years') { ?>
																		<?php $timeOutput[] = $eachTime . ' year' . (($eachTime > 1) ? 's' : ''); ?>
																	<?php } else if($eachUnit == 'days') { ?>
																		<?php $timeOutput[] = $eachTime . ' day' . (($eachTime > 1) ? 's' : ''); ?>
																	<?php } else if($eachUnit == 'hours') { ?>
																		<?php $timeOutput[] = $eachTime . ' hour' . (($eachTime > 1) ? 's' : ''); ?>
																	<?php } else if($eachUnit == 'minutes') { ?>
																		<?php $timeOutput[] = $eachTime . ' minute' . (($eachTime > 1) ? 's' : ''); ?>
																	<?php } else if($eachUnit == 'seconds') { ?>
																		<?php $timeOutput[] = $eachTime . ' second' . (($eachTime > 1) ? 's' : ''); ?>
																	<?php } ?>
																<?php } ?>
															<?php } else break; ?>
														<?php } ?>
														
														<?php if(!empty($timeOutput)) { ?>
															<?php echo $timeOutput[0]; ?>
															<?php if(count($timeOutput) > 1) { ?>
																<small>
																	<?php if(count($timeOutput) > 2) { ?>
																		, <?php echo implode(', ', array_splice($timeOutput, 1, count($timeOutput) - 2)); ?>
																	<?php } ?>
																	et <?php echo $timeOutput[count($timeOutput) - 1]; ?>
																</small>
															<?php } ?> ago
														<?php } else { ?>just now<?php } ?>
													</time>
												</a>
											</p>
										</span>
										<div class="header">
											<?php $avatarInfos = $_Avatar->getFileLines(array('name' => 'user_avatar', 'owner' => $eachPostInfos['owner'])); ?>
											<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $eachPostInfos['owner']; ?>" class="header-left">
												<img src="<?php echo (!empty($avatarInfos)) ? $_Avatar->getUploadsUrl() . $avatarInfos['path_addon'] . $avatarInfos['file_name'] : $_Gravatar->getGravatar($_Oli->getAccountInfos('ACCOUNTS', 'email', $eachPostInfos['owner']), 100); ?>" class="avatar img-rounded" alt="<?php echo $eachPostInfos['owner']; ?>" />
											</a>
											<div class="header-body">
												<h4 class="heading">
													<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $eachPostInfos['owner']; ?>">
														<?php echo $_Oli->getAccountInfos('INFOS', 'name', $eachPostInfos['owner']) ?: $eachPostInfos['owner']; ?>
													</a>
												</h4>
												<small>
													@<?php echo $eachPostInfos['owner']; ?>
												</small>
											</div>
										</div> <hr />
										
										<?php preg_match_all('/(((?:https?:\/\/)?(?:[w]{3}\.)?)((?:[\da-z\.-]+)\.(?:[a-z\.]{2,6})([\/\w\.?=&-]*)*\/?))/', $eachPostInfos['content'], $linksOutput); ?>
										<?php $links = is_array($linksOutput[1]) ? array_unique($linksOutput[1]) : [$linksOutput[1]]; ?>
										<?php if(!empty($links)) { ?>
											<?php foreach($links as $eachKey => $eachLink) { ?>
												<?php $replace = '<a href="' . ($linksOutput[2][$eachKey] ?: 'http://') . $linksOutput[3][$eachKey] . '" target="_blank">' . $linksOutput[3][$eachKey]  . '</a>'; ?>
												<?php $eachPostInfos['content'] = str_replace($eachLink, $replace, $eachPostInfos['content']); ?>
											<?php } ?>
										<?php } ?>
										
										<?php preg_match_all('/@([\w-]+)/', $eachPostInfos['content'], $mentionsOutput); ?>
										<?php $mentions = is_array($mentionsOutput[1]) ? array_unique($mentionsOutput[1]) : [$mentionsOutput[1]]; ?>
										<?php if(!empty($mentions)) { ?>
											<?php foreach($mentions as $eachMention) { ?>
												<?php if($_Oli->isExistAccountInfos('ACCOUNTS', $eachMention, false)) { ?>
													<?php $replace = '<a href="' . $_Oli->getUrlParam(0) . 'user/' . $_Oli->getAccountInfos('ACCOUNTS', 'username', $eachMention, false) . '">@' . $_Oli->getAccountInfos('ACCOUNTS', 'username', $eachMention, false) . '</a>'; ?>
													<?php $eachPostInfos['content'] = str_replace('@' . $eachMention, $replace, $eachPostInfos['content']); ?>
												<?php } ?>
											<?php } ?>
										<?php } ?>
										
										<?php preg_match_all('/#(\w+)/', $eachPostInfos['content'], $hashtagsOutput); ?>
										<?php $hashtags = is_array($hashtagsOutput[1]) ? array_unique($hashtagsOutput[1]) : [$hashtagsOutput[1]]; ?>
										<?php if(!empty($hashtags)) { ?>
											<?php foreach($hashtags as $eachHashtag) { ?>
												<?php $replace = '<a href="' . $_Oli->getUrlParam(0) . 'search/' . urlencode('#') . $eachHashtag . '">#' . $eachHashtag . '</a>'; ?>
												<?php $eachPostInfos['content'] = str_replace('#' . $eachHashtag, $replace, $eachPostInfos['content']); ?>
											<?php } ?>
										<?php } ?>
										
										<p class="content"><?php echo nl2br($eachPostInfos['content']); ?></p>
										
										<?php $mediaInfos = $_Media->getFileLines(array('file_key' => $eachPostInfos['media_key'])); ?>
										<?php if(!empty($mediaInfos) AND $_Media->isExistFile($mediaInfos['path_addon'] . $mediaInfos['file_name'])) { ?>
											<div class="media">
												<img src="<?php echo $_Media->getUploadsUrl() . $mediaInfos['path_addon'] . $mediaInfos['file_name']; ?>" />
											</div>
										<?php } ?>
									<?php } else if($type == 'user') { ?>
										<span class="pull-right text-right">
											<p>
												<b><?php echo $_Oli->isExistInfosMySQL('social_posts', array('owner' => $eachNotification['data']['username']), false) ?: 0; ?></b> posts <br />
												<b><?php echo $_Oli->isExistInfosMySQL('social_follows', array('follows' => $eachNotification['data']['username']), false) ?: 0; ?></b> followers
											</p>
										</span>
										<div class="header">
											<?php $avatarInfos = $_Avatar->getFileLines(array('name' => 'user_avatar', 'owner' => $eachNotification['data']['username'])); ?>
											<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $eachNotification['data']['username']; ?>" class="header-left">
												<img src="<?php echo (!empty($avatarInfos)) ? $_Avatar->getUploadsUrl() . $avatarInfos['path_addon'] . $avatarInfos['file_name'] : $_Gravatar->getGravatar($_Oli->getAccountInfos('ACCOUNTS', 'email', $eachNotification['data']['username']), 100); ?>" class="avatar img-rounded" alt="<?php echo $eachNotification['data']['username']; ?>" />
											</a>
											<div class="header-body">
												<h4 class="heading">
													<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $eachNotification['data']['username']; ?>">
														<?php echo $_Oli->getAccountInfos('INFOS', 'name', $eachNotification['data']['username']) ?: $eachNotification['data']['username']; ?>
													</a>
												</h4>
												<small>@<?php echo $eachNotification['data']['username']; ?></small>
											</div>
										</div>
									<?php } ?>
								</div>
							<?php } ?>
							
							<div class="tools">
								<p class="pull-right text-muted">
									Showing
									<?php if($page == 1) { ?>last<?php } ?>
									<?php echo count($userNotifications); ?> post<?php if(count($userNotifications) > 1) { ?>s<?php } ?>
								</p>
								<p>
									<?php if($page > 1) { ?>
										<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $_Oli->getUrlParam(3); ?>/<?php echo $page - 1; ?>" class="btn btn-link btn-xs">
											<span class="badge"><i class="fa fa-angle-left fa-fw"></i></span>
										</a>
									<?php } ?>
									Page <?php echo $page; ?>
									<?php if($page < $totalPages) { ?>
										<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $_Oli->getUrlParam(3); ?>/<?php echo $page + 1; ?>" class="btn btn-link btn-xs">
											<span class="badge"><i class="fa fa-angle-right fa-fw"></i></span>
										</a>
									<?php } ?>
								</p>
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