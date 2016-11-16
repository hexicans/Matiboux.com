<?php
if(!$_Oli->verifyAuthKey() OR $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) < $_Oli->translateUserRight('USER')) header('Location: ' . $_Oli->getShortcutLink('login'));
?>

<!DOCTYPE html>
<html>
<head>

<?php include THEMEPATH . 'head.php'; ?>
<?php $_Oli->loadLocalScript('js/actions.js', false); ?>
<?php $_Oli->loadLocalScript('js/media.js', false); ?>
<title>Notifications - <?php echo $_Oli->getSetting('name'); ?></title>

</head>
<body>

<?php include THEMEPATH . 'header.php'; ?>

<div class="bigMedia" style="display: none;"><img /></div>
<div class="main">
	<div class="container-fluid">
		<div class="row">
			<div class="mainBar col-sm-offset-2 col-sm-8">
				<?php if($_Oli->getUrlParam(3) == 'delete' AND !empty($_Oli->getUrlParam(4)) AND $resultCode == 'CONFIRMATION_NEEDED') { ?>
					<div class="message message-highlight-danger">
						<p>
							<b>You asked to delete these posts</b>, please confirm you want to delete them <hr />
							<span class="text-success">
								<i class="fa fa-check fa-fw"></i>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $_Oli->getUrlParam(3); ?>/<?php echo $_Oli->getUrlParam(4); ?>/confirmed/<?php echo $page; ?>">I want to delete them</a>
							</span> <br />
							<span class="text-danger">
								<i class="fa fa-times fa-fw"></i>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/">I refuse to delete them</a>
							</span>
						</p>
					</div>
				<?php } else if($resultCode == 'UNKNOWN_POST') { ?>
					<div class="message message-danger">
						<p>You tried to act on a post that not exist</p>
					</div>
				<?php } else if($resultCode == 'NOT_YOUR_POST') { ?>
					<div class="message message-danger">
						<p>You tried to act on a post that is not yours</p>
					</div>
				<?php } else if($resultCode == 'POST_DELETED') { ?>
					<div class="message message-success">
						<p>The posts have been deleted</p>
					</div>
				<?php } ?>
				<div class="message" id="message" style="display: none;"></div>
				
				<?php /*$notifications = $_Oli->getLinesMySQL('social_notifications', array('username' => $_Oli->getAuthKeyOwner()), false, true); ?>
				<?php if(!empty($notifications)) { ?>
					<?php foreach(array_reverse($notifications) as $eachNotification) { ?>
						<?php if(!isset($eachNotification['seen_date'])) $_Oli->updateInfosMySQL('social_notifications', array('seen_date' => date('Y-m-d H:i:s')), array('id' => $eachNotification['id'])); ?>
						
						<div class="notification small content-card" id="<?php echo $eachPost['id']; ?>">
							<?php if(!isset($eachNotification['seen_date'])) { ?>
								<b>[NEW]</b> <br />
							<?php } ?>
							Type: <?php echo $eachNotification['type']; ?> <br />
							Data: <?php print_r($eachNotification['data']); ?> <br />
							Date: <?php echo date('d/m/Y H:i', strtotime($eachNotification['creation_date'])); ?> <hr />
						</div>
					<?php } ?>
				<?php }*/ ?>
				
				<?php foreach(array_merge($_Oli->getLinesMySQL('social_notifications', array('username' => $_Oli->getAuthKeyOwner()), false, true), $_Oli->getLinesMySQL('social_notifications', array('username' => 'all'), false, true)) as $eachNotification) { ?>
					<?php $allNotifications[$eachNotification['id']] = $eachNotification; ?>
				<?php } ?>
				<?php $countAllNotifications = count($allNotifications); ?>
				
				<?php $limit = 30; ?>
				<?php $totalPages = ceil($countAllNotifications / $limit); ?>
				<?php $page = array_reverse($_Oli->getUrlParam('params'))[0] >= 1 ? (array_reverse($_Oli->getUrlParam('params'))[0] < $totalPages ? array_reverse($_Oli->getUrlParam('params'))[0] : $totalPages) : 1; ?>
				
				<?php if(!empty($allNotifications)) { ?>
					<?php sort($allNotifications); ?>
					<?php $notifications = array_slice(array_reverse($allNotifications), ($page - 1) * $limit, $limit); ?>
					
					<div class="tools">
						<p class="pull-right text-muted">
							Showing
							<?php if($page == 1) { ?>last<?php } ?>
							<?php echo count($notifications); ?> notification<?php if(count($notifications) > 1) { ?>s<?php } ?>
						</p>
						<p>
							<?php if($page > 1) { ?>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $page - 1; ?>" class="btn btn-link btn-xs">
									<span class="badge"><i class="fa fa-angle-left fa-fw"></i></span>
								</a>
							<?php } ?>
							Page <?php echo $page; ?>
							<?php if($page < $totalPages) { ?>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $page + 1; ?>" class="btn btn-link btn-xs">
									<span class="badge"><i class="fa fa-angle-right fa-fw"></i></span>
								</a>
							<?php } ?>
						</p>
					</div>
					
					<?php foreach($notifications as $eachNotification) { ?>
						<?php if(!in_array($eachNotification['type'], ['announce', 'major_announce']) AND !isset($eachNotification['seen_date'])) $_Oli->updateInfosMySQL('social_notifications', array('seen_date' => date('Y-m-d H:i:s')), array('id' => $eachNotification['id'])); ?>
						<?php if(!in_array($eachNotification['type'], ['announce', 'major_announce']) OR ($eachNotification['type'] == 'announce' AND !$_Oli->getAccountInfos('INFOS', 'hide_announce', $_Oli->getAuthKeyOwner(), false)) OR ($eachNotification['type'] == 'major_announce' AND !($_Oli->getAccountInfos('INFOS', 'hide_announce', $_Oli->getAuthKeyOwner(), false) AND $_Oli->getAccountInfos('INFOS', 'hide_major_announce', $_Oli->getAuthKeyOwner(), false)))) { ?>
							<div class="notification <?php if(!in_array($eachNotification['type'], ['announce', 'major_announce'])) { ?>small<?php } ?> content-card <?php if($eachNotification['type'] == 'major_announce') { ?>highlight-danger<?php } else if($eachNotification['type'] == 'announce' OR !isset($eachNotification['seen_date'])) { ?>highlight<?php } ?>" <?php if(isset($eachNotification['data']['postId'])) { ?>id="<?php echo $eachNotification['data']['postId']; ?>"<?php } ?>>
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
											replied you with this post
										<?php } else if($eachNotification['type'] == 'mention') { $type = 'post'; ?>
											<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $eachNotification['data']['owner']; ?>"><?php echo $eachNotification['data']['owner']; ?></a>
											mentioned you in this post
										<?php } else if($eachNotification['type'] == 'like') { $type = 'post'; ?>
											<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $eachNotification['data']['username']; ?>"><?php echo $eachNotification['data']['username']; ?></a>
											liked your post
										<?php } else if($eachNotification['type'] == 'repost') { $type = 'post'; ?>
											<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $eachNotification['data']['username']; ?>"><?php echo $eachNotification['data']['username']; ?></a>
											reposted your post
										<?php } else if($eachNotification['type'] == 'follow') { $type = 'user'; ?> You have a new follower!
										<?php } else if($eachNotification['type'] == 'unfollow') { $type = 'user'; ?> You have lost a follower
										<?php } else { ?> You have a notification
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
									
									<p class="content"><?php echo nl2br($eachNotification['data']['message']); ?></p>
								<?php } else if($type == 'post') { ?>
									<?php $eachPostInfos = $_Oli->getLinesMySQL('social_posts', array('id' => $eachNotification['data']['postId']), false); ?>
									<span class="pull-right text-right">
										<p class="hidden-xs">
											<a href="<?php echo $_Oli->getUrlParam(0); ?>post/<?php echo $eachPostInfos['id']; ?>" class="btn btn-link btn-xs action-reply">
												<i class="fa fa-reply fa-lg"></i>
												<b><?php echo $_Oli->isExistInfosMySQL('social_posts', array('reply_to' => $eachPostInfos['id']), false) ?: 0; ?></b>
											</a>
											<a href="<?php echo $_Oli->getUrlParam(0); ?>toggle-like.php" class="btn btn-link btn-xs action-like <?php if($_Oli->isExistInfosMySQL('social_likes', array('username' => $_Oli->getAuthKeyOwner(), 'post_id' => $eachPost['id']), false)) { ?>active<?php } ?>">
												<i class="fa fa-thumbs-up fa-lg"></i>
												<b><?php echo $_Oli->isExistInfosMySQL('social_likes', array('post_id' => $eachPostInfos['id']), false) ?: 0; ?></b>
											</a>
											<a href="<?php if($eachPostInfos['owner'] != $_Oli->getAuthKeyOwner()) { ?><?php echo $_Oli->getUrlParam(0); ?>toggle-repost.php<?php } else { ?>#<?php } ?>" class="btn btn-link btn-xs <?php if($eachPostInfos['owner'] != $_Oli->getAuthKeyOwner()) { ?>action-repost<?php } else { ?>disabled<?php } ?> <?php if($_Oli->isExistInfosMySQL('social_posts', array('owner' => $_Oli->getAuthKeyOwner(), 'content' => '', 'quote_from' => $eachPostInfos['id']), false)) { ?>active<?php } ?>">
												<i class="fa fa-retweet fa-lg"></i>
												<b><?php echo $_Oli->isExistInfosMySQL('social_posts', array('content' => '', 'quote_from' => $eachPostInfos['id']), false) ?: 0; ?></b>
											</a>
											<?php if($eachPostInfos['owner'] == $_Oli->getAuthKeyOwner()) { ?>
												<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/delete/<?php echo $eachPostInfos['id']; ?>/<?php echo $page; ?>" class="btn btn-link btn-xs">
													<span class="text-danger">
														<i class="fa fa-trash fa-lg"></i>
													</span>
												</a>
											<?php } ?>
										</p>
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
									<?php } ?> <hr class="visible-xs-block" />
									
									<div class="meta visible-xs-block">
										<p>
											<a href="<?php echo $_Oli->getUrlParam(0); ?>post/<?php echo $eachPostInfos['id']; ?>" class="btn btn-link btn-xs action-reply">
												<i class="fa fa-reply fa-lg"></i>
												<b><?php echo $_Oli->isExistInfosMySQL('social_posts', array('reply_to' => $eachPostInfos['id']), false) ?: 0; ?></b>
											</a>
											<a href="<?php echo $_Oli->getUrlParam(0); ?>toggle-like.php" class="btn btn-link btn-xs action-like <?php if($_Oli->isExistInfosMySQL('social_likes', array('username' => $_Oli->getAuthKeyOwner(), 'post_id' => $eachPostInfos['id']), false)) { ?>active<?php } ?>">
												<i class="fa fa-thumbs-up fa-lg"></i>
												<b><?php echo $_Oli->isExistInfosMySQL('social_likes', array('post_id' => $eachPostInfos['id']), false) ?: 0; ?></b>
											</a>
											<a href="<?php if($eachPostInfos['owner'] != $_Oli->getAuthKeyOwner()) { ?><?php echo $_Oli->getUrlParam(0); ?>toggle-repost.php<?php } else { ?>#<?php } ?>" class="btn btn-link btn-xs <?php if($eachPostInfos['owner'] != $_Oli->getAuthKeyOwner()) { ?>action-repost<?php } else { ?>disabled<?php } ?> <?php if($_Oli->isExistInfosMySQL('social_posts', array('owner' => $_Oli->getAuthKeyOwner(), 'content' => '', 'quote_from' => $eachPostInfos['id']), false)) { ?>active<?php } ?>">
												<i class="fa fa-retweet fa-lg"></i>
												<b><?php echo $_Oli->isExistInfosMySQL('social_posts', array('content' => '', 'quote_from' => $eachPostInfos['id']), false) ?: 0; ?></b>
											</a>
											<?php if($eachPostInfos['owner'] == $_Oli->getAuthKeyOwner()) { ?>
												<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/delete/<?php echo $eachPostInfos['id']; ?>/<?php echo $page; ?>" class="btn btn-link btn-xs">
													<span class="text-danger">
														<i class="fa fa-trash fa-lg"></i>
													</span>
												</a>
											<?php } ?>
										</p>
									</div>
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
					<?php } ?>
					
					<div class="tools">
						<p class="pull-right text-muted">
							Showing
							<?php if($page == 1) { ?>last<?php } ?>
							<?php echo count($notifications); ?> notification<?php if(count($notifications) > 1) { ?>s<?php } ?>
						</p>
						<p>
							<?php if($page > 1) { ?>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $page - 1; ?>" class="btn btn-link btn-xs">
									<span class="badge"><i class="fa fa-angle-left fa-fw"></i></span>
								</a>
							<?php } ?>
							Page <?php echo $page; ?>
							<?php if($page < $totalPages) { ?>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $page + 1; ?>" class="btn btn-link btn-xs">
									<span class="badge"><i class="fa fa-angle-right fa-fw"></i></span>
								</a>
							<?php } ?>
						</p>
					</div>
				<?php } else { ?>
					<div class="message message-danger text-center">
						<h3>You don't have any notification</h3>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<?php $_Oli->loadEndHtmlFiles(); ?>

</body>
</html>