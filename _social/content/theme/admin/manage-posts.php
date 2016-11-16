<?php
if(!$_Oli->verifyAuthKey() OR $_Oli->getUserRightLevel($_Oli->getAuthKeyOwner()) < $_Oli->translateUserRight('MODERATOR')) header('Location: ' . $_Oli->getShortcutLink('login'));

if($_Oli->getUrlParam(3) == 'edit' AND !empty($_Oli->getUrlParam(4)) AND !$_Oli->isEmptyPostVars() AND $_Oli->isExistInfosMySQL('social_posts', array('id' => $_Oli->getUrlParam(4), 'owner' => $_Oli->getUrlParam(2)), false)) {
	if($_Oli->isEmptyPostVars('content')) $resultCode = 'CONTENT_EMPTY';
	else if(strlen($_Oli->getPostVars('content')) > 512) $resultCode = 'CONTENT_TOO_LONG';
	else if(!$_Oli->isExistAccountInfos('social_posts', array('id' => $_Oli->getUrlParam(4)))) $resultCode = 'UNKNOWN_POST';
	else {
		$editedPostId = $_Oli->getUrlParam(4);
		$content = $_Oli->getPostVars('content');
		
		if($_Oli->updateInfosMySQL('social_posts', array('content' => $content), array('id' => $editedPostId)))
			$resultCode = 'POST_UPDATED';
		else $resultCode = 'UPDATE_FAILED';
	}
}
else if($_Oli->getUrlParam(3) == 'delete' AND !empty($_Oli->getUrlParam(4))) {
	$paramData = urldecode($_Oli->getUrlParam(4));
	$selectedMedias = (!is_array($paramData)) ? ((is_array(unserialize($paramData))) ? unserialize($paramData) : [$paramData]) : $paramData;
	
	foreach($selectedMedias as $eachKey) {
		if(!$_Oli->isExistInfosMySQL('social_posts', array('id' => $eachKey))) {
			$errorStatus = 'UNKNOWN_POST';
			break;
		}
	}
	
	if(!empty($errorStatus)) $resultCode = $errorStatus;
	else if($_Oli->getUrlParam(5) != 'confirmed') $resultCode = 'CONFIRMATION_NEEDED';
	else {
		foreach($selectedMedias as $eachKey) {
			if($_Oli->isEmptyInfosMySQL('social_posts', 'content', array('id' => $eachKey)) AND !$_Oli->isEmptyInfosMySQL('social_posts', 'quote_from', array('id' => $eachKey)))
				$_Oli->deleteLinesMySQL('social_notifications', array('username' => $_Oli->getInfosMySQL('social_posts', 'owner', array('id' => $_Oli->getInfosMySQL('social_posts', 'quote_from', array('id' => $eachKey)))), 'type' => 'repost', 'data' => array('username' => $_Oli->getInfosMySQL('social_posts', 'owner', array('id' => $eachKey)), 'postId' => $_Oli->getInfosMySQL('social_posts', 'quote_from', array('id' => $eachKey)))));
			$_Oli->deleteLinesMySQL('social_posts', array('id' => $eachKey));
		}
		$resultCode = 'POST_DELETED';
	}
}
?>

<!DOCTYPE html>
<html>
<head>

<?php include THEMEPATH . 'head.php'; ?>
<?php $_Oli->loadLocalScript('js/search.js', false); ?>
<title>Manage user posts - <?php echo $_Oli->getSetting('name'); ?></title>

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
							<b>You asked to delete these posts</b>, please confirm you want to delete them <hr />
							<span class="text-success">
								<i class="fa fa-check fa-fw"></i>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $_Oli->getUrlParam(3); ?>/<?php echo $_Oli->getUrlParam(4); ?>/confirmed/<?php echo $_Oli->getUrlParam(5); ?>">I want to delete them</a>
							</span> <br />
							<span class="text-danger">
								<i class="fa fa-times fa-fw"></i>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $_Oli->getUrlParam(5); ?>">I refuse to delete them</a>
							</span>
						</p>
					</div>
				<?php } else if($resultCode == 'CONTENT_EMPTY') { ?>
					<div class="message message-danger">
						<p>You can't let the post content empty!</p>
					</div>
				<?php } else if($resultCode == 'CONTENT_TOO_LONG') { ?>
					<div class="message message-danger">
						<p>The post content cannot be longer than 512 characters</p>
					</div>
				<?php } else if($resultCode == 'UNKNOWN_POST') { ?>
					<div class="message message-danger">
						<p>You tried to act on a post that not exist</p>
					</div>
				<?php } else if($resultCode == 'NOT_YOUR_POST') { ?>
					<div class="message message-danger">
						<p>You tried to act on a post that is not yours</p>
					</div>
				<?php } else if($resultCode == 'POST_UPDATED') { ?>
					<div class="message message-success">
						<p>Your settings have been updated</p>
					</div>
				<?php } else if($resultCode == 'POST_DELETED') { ?>
					<div class="message message-success">
						<p>The post have been deleted</p>
					</div>
				<?php } else if($resultCode == 'UPDATE_FAILED') { ?>
					<div class="message message-danger">
						<p>An error occurred, please try again</p>
					</div>
				<?php } ?>
				<div class="message" id="message" style="display: none;"></div>
				
				<h3 class="text-center"><i class="fa fa-file-text-o fa-fw"></i> Manage user posts</h3>
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
				
				<?php $allUserPosts = $_Oli->getLinesMySQL('social_posts', array('owner' => $_Oli->getUrlParam(2)), false, true); ?>
				<?php $countAllUserPosts = count($allUserPosts); ?>
				
				<?php $limit = 50; ?>
				<?php $totalPages = ceil($countAllUserPosts / $limit); ?>
				<?php $page = array_reverse($_Oli->getUrlParam('params'))[0] >= 1 ? (array_reverse($_Oli->getUrlParam('params'))[0] < $totalPages ? array_reverse($_Oli->getUrlParam('params'))[0] : $totalPages) : 1; ?>
				
				
				<?php if(!empty($users) OR !empty($allUserPosts)) { ?>
					<div class="<?php if(empty($allUserPosts)) { ?>col-sm-offset-3 col-sm-6<?php } else { ?>col-sm-4<?php } ?>">
						<h4 class="text-center">Users found for <b><?php echo $_Oli->getUrlParam(2); ?></b></h4>
						
						<?php if($_Oli->getUrlParam(2) == 'all' AND !empty($allUserPosts)) { ?>
							<div class="profile content-card text-center">
								<h3 class="heading">Announcements Feed</h3>
							</div>
						<?php } else if($_Oli->isExistAccountInfos('ACCOUNTS', $_Oli->getUrlParam(2), false)) { ?>
							<?php if(empty($allUserPosts)) { ?>
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
								</div> <hr />
								
								<p class="col-md-4 col-sm-6 col-xs-4">
									<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $targetUsername; ?>" class="btn btn-link">
										Posts <br />
										<span class="badge"><?php echo $_Oli->isExistInfosMySQL('social_posts', array('owner' => $targetUsername), false) ?: 0; ?></span>
									</a>
								</p>
								<p class="hidden-sm col-xs-4">
									<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $targetUsername; ?>/followings" class="btn btn-link">
										Followings <br />
										<span class="badge"><?php echo $_Oli->isExistInfosMySQL('social_follows', array('username' => $targetUsername), false) ?: 0; ?></span>
									</a>
								</p>
								<p class="col-md-4 col-sm-6 col-xs-4">
									<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $targetUsername; ?>/followers" class="btn btn-link">
										Followers <br />
										<span class="badge"><?php echo $_Oli->isExistInfosMySQL('social_follows', array('follows' => $targetUsername), false) ?: 0; ?></span>
									</a>
								</p>
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
					
					<?php if($_Oli->getUrlParam(3) == 'edit' AND !empty($_Oli->getUrlParam(4)) AND $_Oli->isExistInfosMySQL('social_posts', array('id' => $_Oli->getUrlParam(4), 'owner' => $_Oli->getUrlParam(2)), false) AND !isset($editedPostId)) { ?>
						<div class="col-sm-8">
							<h4>
								<?php $name = $_Oli->getAccountInfos('INFOS', 'name', $_Oli->getUrlParam(2), false) ?: $_Oli->getAccountInfos('ACCOUNTS', 'username', $_Oli->getUrlParam(2), false); ?>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $page; ?>" class="btn btn-primary btn-sm">
									<i class="fa fa-angle-left fa-lg"></i> Back
								</a> - Edit a post of <b><?php echo $name; ?></b>
							</h4>
							
							<?php $postInfos = $_Oli->getLinesMySQL('social_posts', array('id' => $_Oli->getUrlParam(4), 'owner' => $_Oli->getUrlParam(2)), false); ?>
							<div class="post content-card <?php if($postInfos['owner'] == 'all') { ?>highlight<?php } ?>" id="<?php echo $postInfos['id']; ?>">
								<?php if($postInfos['owner'] != 'all') { ?>
									<span class="pull-right text-right">
										<p>
											<i class="fa fa-clock-o fa-fw"></i>
											<time datetime="<?php echo date('Y-m-d H:i:s', strtotime($postInfos['post_date'])); ?>">
												<a href="<?php echo $_Oli->getUrlParam(0); ?>post/<?php echo $postInfos['id']; ?>">
													<?php $timeOutput = []; ?>
													<?php foreach($_Oli->dateDifference($postInfos['post_date'], time(), true) as $eachUnit => $eachTime) { ?>
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
												</a>
											</time>
										</p>
									</span>
									<div class="header">
										<?php $avatarInfos = $_Avatar->getFileLines(array('name' => 'user_avatar', 'owner' => $postInfos['owner'])); ?>
										<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $postInfos['owner']; ?>" class="header-left">
											<img src="<?php echo (!empty($avatarInfos)) ? $_Avatar->getUploadsUrl() . $avatarInfos['path_addon'] . $avatarInfos['file_name'] : $_Gravatar->getGravatar($_Oli->getAccountInfos('ACCOUNTS', 'email', $postInfos['owner']), 100); ?>" class="avatar img-rounded" alt="<?php echo $postInfos['owner']; ?>" />
										</a>
										<div class="header-body">
											<h4 class="heading">
												<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $postInfos['owner']; ?>">
												<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $postInfos['owner']; ?>">
													<?php echo $_Oli->getAccountInfos('INFOS', 'name', $postInfos['owner']) ?: $postInfos['owner']; ?>
												</a>
											</h4>
											<small>@<?php echo $postInfos['owner']; ?></small>
										</div>
									</div>
								<?php } else { ?>
									<h4 class="heading">Announcement!</h4>
								<?php } ?> <hr />
								
								<?php $rawContent = $postInfos['content']; ?>
								<?php preg_match_all('/(((?:https?:\/\/)?(?:[w]{3}\.)?)((?:[\da-z\.-]+)\.(?:[a-z\.]{2,6})([\/\w\.?=&-]*)*\/?))/', $postInfos['content'], $linksOutput); ?>
								<?php $links = is_array($linksOutput[1]) ? array_unique($linksOutput[1]) : [$linksOutput[1]]; ?>
								<?php if(!empty($links)) { ?>
									<?php foreach($links as $eachKey => $eachLink) { ?>
										<?php $replace = '<a href="' . ($linksOutput[2][$eachKey] ?: 'http://') . $linksOutput[3][$eachKey] . '" target="_blank">' . $linksOutput[3][$eachKey]  . '</a>'; ?>
										<?php $postInfos['content'] = str_replace($eachLink, $replace, $postInfos['content']); ?>
									<?php } ?>
								<?php } ?>
								
								<?php preg_match_all('/@([\w-]+)/', $postInfos['content'], $mentionsOutput); ?>
								<?php $mentions = is_array($mentionsOutput[1]) ? array_unique($mentionsOutput[1]) : [$mentionsOutput[1]]; ?>
								<?php if(!empty($mentions)) { ?>
									<?php foreach($mentions as $eachMention) { ?>
										<?php if($_Oli->isExistAccountInfos('ACCOUNTS', $eachMention, false)) { ?>
											<?php $replace = '<a href="' . $_Oli->getUrlParam(0) . 'user/' . $_Oli->getAccountInfos('ACCOUNTS', 'username', $eachMention, false) . '">@' . $_Oli->getAccountInfos('ACCOUNTS', 'username', $eachMention, false) . '</a>'; ?>
											<?php $postInfos['content'] = str_replace('@' . $eachMention, $replace, $postInfos['content']); ?>
										<?php } ?>
									<?php } ?>
								<?php } ?>
								
								<?php preg_match_all('/#(\w+)/', $postInfos['content'], $hashtagsOutput); ?>
								<?php $hashtags = is_array($hashtagsOutput[1]) ? array_unique($hashtagsOutput[1]) : [$hashtagsOutput[1]]; ?>
								<?php if(!empty($hashtags)) { ?>
									<?php foreach($hashtags as $eachHashtag) { ?>
										<?php $replace = '<a href="' . $_Oli->getUrlParam(0) . 'search/' . urlencode('#') . $eachHashtag . '">#' . $eachHashtag . '</a>'; ?>
										<?php $postInfos['content'] = str_replace('#' . $eachHashtag, $replace, $postInfos['content']); ?>
									<?php } ?>
								<?php } ?>
								
								<p class="content"><?php echo nl2br($postInfos['content']); ?></p>
								
								<?php $mediaInfos = $_Media->getFileLines(array('file_key' => $postInfos['media_key'])); ?>
								<?php if(!empty($mediaInfos) AND $_Media->isExistFile($mediaInfos['path_addon'] . $mediaInfos['file_name'])) { ?>
									<div class="media">
										<img src="<?php echo $_Media->getUploadsUrl() . $mediaInfos['path_addon'] . $mediaInfos['file_name']; ?>" />
									</div>
								<?php } ?> <hr />
								
								<form action="<?php echo $_Oli->getUrlParam(0); ?>form.php" class="form form-horizontal" method="post">
									<div class="form-group">
										<div class="col-xs-12">
											<div class="input-group">
												<textarea type="text" class="form-control" name="content" rows="3"><?php echo $rawContent; ?></textarea>
												<a href="#" class="input-group-addon btn btn-default submit">
													<i class="fa fa-floppy-o fa-fw"></i> Edit
												</a>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					<?php } else if(!empty($allUserPosts)) { ?>
						<div class="col-sm-8">
							<?php $userPosts = array_slice(array_reverse($allUserPosts), ($page - 1) * $limit, $limit); ?>
						
							<div class="tools">
								<p class="pull-right text-muted">
									Showing
									<?php if($page == 1) { ?>last<?php } ?>
									<?php echo count($userPosts); ?> post<?php if(count($userPosts) > 1) { ?>s<?php } ?>
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
							
							<?php foreach($userPosts as $eachPost) { ?>
								<?php if($postInfos['owner'] != 'all' AND empty($eachPost['content']) AND !empty($eachPost['quote_from'])) { ?>
									<?php $citator = $eachPost['owner']; ?>
									<?php $citatorPostId = $eachPost['id']; ?>
									<?php $eachPost = $_Oli->getLinesMySQL('social_posts', array('id' => $eachPost['quote_from'])); ?>
								<?php } else $citator = null; ?>
								
								<div class="post content-card <?php if($_Oli->getUrlParam(3) == 'delete' AND ($_Oli->getUrlParam(4) == $eachPost['id'] OR (isset($citator) AND $_Oli->getUrlParam(4) == $citatorPostId))) { ?>highlight-danger<?php } else if($eachPost['owner'] == 'all') { ?>highlight<?php } ?> <?php if(isset($citator)) { ?>repost<?php } ?>" id="<?php echo $eachPost['id']; ?>">
									<?php if(isset($citator)) { ?>
										<p>
											<b>
												<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $citator; ?>"><?php echo $citator; ?></a>
												reposted this:
											</b>
										</p>
									<?php } ?>
									
									<span class="pull-right text-right">
										<p class="hidden-xs">
											<?php if(!isset($citator)) { ?>
												<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/edit/<?php echo $eachPost['id']; ?>/<?php echo $page; ?>" class="btn btn-link btn-xs">
													<i class="fa fa-pencil fa-lg"></i>
												</a>
												<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/delete/<?php echo $eachPost['id']; ?>/<?php echo $page; ?>" class="btn btn-link btn-xs">
													<span class="text-danger">
														<i class="fa fa-trash fa-lg"></i>
													</span>
												</a>
											<?php } else { ?>
												<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/delete/<?php echo $citatorPostId; ?>/<?php echo $page; ?>" class="btn btn-link btn-xs">
													<span class="text-danger">
														<i class="fa fa-ban fa-lg"></i>
													</span>
												</a>
											<?php } ?>
										</p>
										<?php if($eachPost['owner'] != 'all') { ?>
											<p>
												<i class="fa fa-clock-o fa-fw"></i>
												<time datetime="<?php echo date('Y-m-d H:i:s', strtotime($eachPost['post_date'])); ?>">
													<a href="<?php echo $_Oli->getUrlParam(0); ?>post/<?php echo $eachPost['id']; ?>">
														<?php $timeOutput = []; ?>
														<?php foreach($_Oli->dateDifference($eachPost['post_date'], time(), true) as $eachUnit => $eachTime) { ?>
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
													</a>
												</time>
											</p>
										<?php } ?>
									</span>
									<?php if($eachPost['owner'] != 'all') { ?>
										<div class="header">
											<?php $avatarInfos = $_Avatar->getFileLines(array('name' => 'user_avatar', 'owner' => $eachPost['owner'])); ?>
											<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $eachPost['owner']; ?>" class="header-left">
												<img src="<?php echo (!empty($avatarInfos)) ? $_Avatar->getUploadsUrl() . $avatarInfos['path_addon'] . $avatarInfos['file_name'] : $_Gravatar->getGravatar($_Oli->getAccountInfos('ACCOUNTS', 'email', $eachPost['owner']), 100); ?>" class="avatar img-rounded" alt="<?php echo $eachPost['owner']; ?>" />
											</a>
											<div class="header-body">
												<h4 class="heading">
													<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $eachPost['owner']; ?>">
													<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $eachPost['owner']; ?>">
														<?php echo $_Oli->getAccountInfos('INFOS', 'name', $eachPost['owner']) ?: $eachPost['owner']; ?>
													</a>
												</h4>
												<small>@<?php echo $eachPost['owner']; ?></small>
											</div>
										</div>
									<?php } else { ?>
										<h4 class="heading">Announcement!</h4>
									<?php } ?> <hr />
									
									<?php preg_match_all('/(((?:https?:\/\/)?(?:[w]{3}\.)?)((?:[\da-z\.-]+)\.(?:[a-z\.]{2,6})([\/\w\.?=&-]*)*\/?))/', $eachPost['content'], $linksOutput); ?>
									<?php $links = is_array($linksOutput[1]) ? array_unique($linksOutput[1]) : [$linksOutput[1]]; ?>
									<?php if(!empty($links)) { ?>
										<?php foreach($links as $eachKey => $eachLink) { ?>
											<?php $replace = '<a href="' . ($linksOutput[2][$eachKey] ?: 'http://') . $linksOutput[3][$eachKey] . '" target="_blank">' . $linksOutput[3][$eachKey]  . '</a>'; ?>
											<?php $eachPost['content'] = str_replace($eachLink, $replace, $eachPost['content']); ?>
										<?php } ?>
									<?php } ?>
									
									<?php preg_match_all('/@([\w-]+)/', $eachPost['content'], $mentionsOutput); ?>
									<?php $mentions = is_array($mentionsOutput[1]) ? array_unique($mentionsOutput[1]) : [$mentionsOutput[1]]; ?>
									<?php if(!empty($mentions)) { ?>
										<?php foreach($mentions as $eachMention) { ?>
											<?php if($_Oli->isExistAccountInfos('ACCOUNTS', $eachMention, false)) { ?>
												<?php $replace = '<a href="' . $_Oli->getUrlParam(0) . 'user/' . $_Oli->getAccountInfos('ACCOUNTS', 'username', $eachMention, false) . '">@' . $_Oli->getAccountInfos('ACCOUNTS', 'username', $eachMention, false) . '</a>'; ?>
												<?php $eachPost['content'] = str_replace('@' . $eachMention, $replace, $eachPost['content']); ?>
											<?php } ?>
										<?php } ?>
									<?php } ?>
									
									<?php preg_match_all('/#(\w+)/', $eachPost['content'], $hashtagsOutput); ?>
									<?php $hashtags = is_array($hashtagsOutput[1]) ? array_unique($hashtagsOutput[1]) : [$hashtagsOutput[1]]; ?>
									<?php if(!empty($hashtags)) { ?>
										<?php foreach($hashtags as $eachHashtag) { ?>
											<?php $replace = '<a href="' . $_Oli->getUrlParam(0) . 'search/' . urlencode('#') . $eachHashtag . '">#' . $eachHashtag . '</a>'; ?>
											<?php $eachPost['content'] = str_replace('#' . $eachHashtag, $replace, $eachPost['content']); ?>
										<?php } ?>
									<?php } ?>
									
									<p class="content"><?php echo nl2br($eachPost['content']); ?></p>
									
									<?php $mediaInfos = $_Media->getFileLines(array('file_key' => $eachPost['media_key'])); ?>
									<?php if(!empty($mediaInfos) AND $_Media->isExistFile($mediaInfos['path_addon'] . $mediaInfos['file_name'])) { ?>
										<div class="media">
											<img src="<?php echo $_Media->getUploadsUrl() . $mediaInfos['path_addon'] . $mediaInfos['file_name']; ?>" />
										</div>
									<?php } ?> <hr class="visible-xs-block" />
									
									<div class="meta visible-xs-block">
										<p>
											<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/edit/<?php echo $postInfos['id']; ?>/<?php echo $page; ?>" class="btn btn-link btn-xs">
												<i class="fa fa-pencil fa-lg"></i>
											</a>
											<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/delete/<?php echo $postInfos['id']; ?>/<?php echo $page; ?>" class="btn btn-link btn-xs">
												<span class="text-danger">
													<i class="fa fa-trash fa-lg"></i>
												</span>
											</a>
										</p>
									</div>
								</div>
							<?php } ?>
							
							<div class="tools">
								<p class="pull-right text-muted">
									Showing
									<?php if($page == 1) { ?>last<?php } ?>
									<?php echo count($userPosts); ?> post<?php if(count($userPosts) > 1) { ?>s<?php } ?>
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