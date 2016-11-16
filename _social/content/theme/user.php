<?php
if(empty($_Oli->getUrlParam(2))) header('Location: ' . $_Oli->getUrlParam(0));

if($_Oli->getUrlParam(3) == 'delete' AND !empty($_Oli->getUrlParam(4))) {
	$paramData = urldecode($_Oli->getUrlParam(4));
	$selectedMedias = (!is_array($paramData)) ? ((is_array(unserialize($paramData))) ? unserialize($paramData) : [$paramData]) : $paramData;
	
	foreach($selectedMedias as $eachKey) {
		if(!$_Oli->isExistInfosMySQL('social_posts', array('id' => $eachKey))) {
			$errorStatus = 'UNKNOWN_POST';
			break;
		}
		else if($_Oli->getInfosMySQL('social_posts', 'owner', array('id' => $eachKey)) != $_Oli->getAuthKeyOwner()) {
			$errorStatus = 'NOT_YOUR_POST';
			break;
		}
	}
	
	if(!empty($errorStatus)) $resultCode = $errorStatus;
	else if($_Oli->getUrlParam(5) != 'confirmed') $resultCode = 'CONFIRMATION_NEEDED';
	else {
		foreach($selectedMedias as $eachKey) {
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
<?php $_Oli->loadLocalScript('js/toggle-follow.js', false); ?>
<?php $_Oli->loadLocalScript('js/actions.js', false); ?>
<?php $_Oli->loadLocalScript('js/media.js', false); ?>
<title>
<?php if($_Oli->isExistAccountInfos('ACCOUNTS', $_Oli->getUrlParam(2), false)) { ?>
	<?php echo $_Oli->getAccountInfos('INFOS', 'name', $_Oli->getUrlParam(2), false) ?: $_Oli->getAccountInfos('ACCOUNTS', 'username', $_Oli->getUrlParam(2), false); ?> -
<?php } ?> <?php echo $_Oli->getSetting('name'); ?>
</title>

</head>
<body>

<?php include THEMEPATH . 'header.php'; ?>

<div class="bigMedia" style="display: none;"><img /></div>
<div class="main">
	<div class="container-fluid">
		<div class="row">
			<?php if($_Oli->isExistAccountInfos('ACCOUNTS', array('username' => $_Oli->getUrlParam(2)), false) OR $_Oli->getUrlParam(2) == 'all') { ?>
				<div class="leftBar col-sm-4">
					<?php if($_Oli->getUrlParam(3) == 'delete' AND !empty($_Oli->getUrlParam(4)) AND $resultCode == 'CONFIRMATION_NEEDED') { ?>
						<div class="message message-highlight-danger">
							<p>
								<b>You asked to delete these posts</b>, please confirm you want to delete them <hr />
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
					
					<?php if($_Oli->getUrlParam(2) == 'all') { ?>
						<div class="profile content-card text-center">
							<h3 class="heading">Announcements Feed</h3>
						</div>
					<?php } else { ?>
						<div class="profile content-card">
							<div class="header">
								<?php $avatarInfos = $_Avatar->getFileLines(array('name' => 'user_avatar', 'owner' => $_Oli->getUrlParam(2)), false); ?>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'username', $_Oli->getUrlParam(2), false); ?>" class="header-left">
									<img src="<?php echo (!empty($avatarInfos)) ? $_Avatar->getUploadsUrl() . $avatarInfos['path_addon'] . $avatarInfos['file_name'] : $_Gravatar->getGravatar($_Oli->getAccountInfos('ACCOUNTS', 'email', $_Oli->getUrlParam(2)), 100); ?>" class="avatar img-rounded" alt="<?php echo $_Oli->getUrlParam(2); ?>" />
								</a>
								<div class="header-body">
									<h3 class="heading">
										<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'username', $_Oli->getUrlParam(2), false); ?>">
											<?php echo $_Oli->getAccountInfos('INFOS', 'name', $_Oli->getUrlParam(2)) ?: $_Oli->getAccountInfos('ACCOUNTS', 'username', $_Oli->getUrlParam(2), false); ?>
										</a>
									</h3>
									<p><small>@<?php echo $_Oli->getUrlParam(2); ?></small></p>
									<?php if($_Oli->verifyAuthKey() AND $_Oli->getAccountInfos('ACCOUNTS', 'username', $_Oli->getUrlParam(2), false) != $_Oli->getAuthKeyOwner()) { ?> <hr />
										<p>
											<a href="<?php echo $_Oli->getUrlParam(0); ?>toggle-follow.php" class="btn <?php if(!$_Oli->isExistInfosMySQL('social_follows', array('username' => $_Oli->getAuthKeyOwner(), 'follows' => $_Oli->getUrlParam(2)), false)) { ?>btn-primary<?php } else { ?>btn-danger<?php } ?> btn-sm toggle-follow" people="<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'username', $_Oli->getUrlParam(2), false); ?>">
												<?php if(!$_Oli->isExistInfosMySQL('social_follows', array('username' => $_Oli->getAuthKeyOwner(), 'follows' => $_Oli->getUrlParam(2)), false)) { ?>
													<i class="fa fa-user-plus fa-fw"></i> Follow him!
												<?php } else { ?>
													<i class="fa fa-user-times fa-fw"></i> Unfollow
												<?php } ?>
											</a>
										</p>
									<?php } ?>
								</div>
							</div>
							
							<?php if(!$_Oli->isEmptyAccountInfos('INFOS', 'biography', $_Oli->getUrlParam(2), false)) { ?> <hr />
								<p><?php echo nl2br($_Oli->getAccountInfos('INFOS', 'biography', $_Oli->getUrlParam(2), false)); ?></p>
							<?php } ?> <hr />
							
							<?php if(!$_Oli->isEmptyAccountInfos('INFOS', 'job', $_Oli->getUrlParam(2), false)) { $variousInfo = true; ?>
								<p>
									<i class="fa fa-pencil fa-fw"></i>
									Work as <b><?php echo $_Oli->getAccountInfos('INFOS', 'job', $_Oli->getUrlParam(2), false); ?></b>
								</p>
							<?php } ?>
							<?php if(!$_Oli->isEmptyAccountInfos('INFOS', 'location', $_Oli->getUrlParam(2), false)) { $variousInfo = true; ?>
								<p>
									<i class="fa fa-home fa-fw"></i>
									Live in <b><?php echo $_Oli->getAccountInfos('INFOS', 'location', $_Oli->getUrlParam(2), false); ?></b>
								</p>
							<?php } ?>
							<?php if(!$_Oli->isEmptyAccountInfos('ACCOUNTS', 'birthday', $_Oli->getUrlParam(2), false)) { $variousInfo = true; ?>
								<p>
									<i class="fa fa-birthday-cake fa-fw"></i> the
									<b>
										<?php echo date('d', strtotime($_Oli->getAccountInfos('ACCOUNTS', 'birthday', $_Oli->getUrlParam(2), false))); ?>
										<?php switch(date('n', strtotime($_Oli->getAccountInfos('ACCOUNTS', 'birthday', $_Oli->getUrlParam(2), false)))) {
											case 1: echo 'january'; break;
											case 2: echo 'february'; break;
											case 3: echo 'march'; break;
											case 4: echo 'april'; break;
											case 5: echo 'may'; break;
											case 6: echo 'june'; break;
											case 7: echo 'july'; break;
											case 8: echo 'august'; break;
											case 9: echo 'september'; break;
											case 10: echo 'october'; break;
											case 11: echo 'november'; break;
											case 12: echo 'december'; break;
										} ?>
									</b>
								</p>
							<?php } ?>
							<?php if(!$_Oli->isEmptyAccountInfos('INFOS', 'website', $_Oli->getUrlParam(2), false)) { $variousInfo = true; ?>
								<p>
									<?php preg_match('/^((?:https?:\/\/)?(?:[w]{3}\.)?)((?:[\da-z\.-]+)\.(?:[a-z\.]{2,6})(\/[\w\.?=&-]+)*)\/?$/i', $_Oli->getAccountInfos('INFOS', 'website', $_Oli->getUrlParam(2), false), $websiteOutput); ?>
									<?php $website = is_array($websiteOutput[1]) ? array_unique($websiteOutput[1]) : [$websiteOutput[1]]; ?>
									
									<i class="fa fa-globe fa-fw"></i>
									<a href="<?php echo ($websiteOutput[1] ?: 'http://') . $websiteOutput[2]; ?>" target="_blank">
										<?php echo $websiteOutput[2]; ?>
									</a>
								</p>
							<?php } ?> <?php if($variousInfo) { ?><hr /><?php } ?>
							
							<p>
								<i class="fa fa-user-plus fa-fw"></i> since
								<b>
									<?php switch(date('m', strtotime($_Oli->getAccountInfos('ACCOUNTS', 'register_date', $_Oli->getUrlParam(2), false)))) {
										case 01: echo 'january'; break;
										case 02: echo 'february'; break;
										case 03: echo 'march'; break;
										case 04: echo 'april'; break;
										case 05: echo 'may'; break;
										case 06: echo 'june'; break;
										case 07: echo 'july'; break;
										case 08: echo 'august'; break;
										case 09: echo 'september'; break;
										case 10: echo 'october'; break;
										case 11: echo 'november'; break;
										case 12: echo 'december'; break;
									} ?>
									<?php echo date('Y', strtotime($_Oli->getAccountInfos('ACCOUNTS', 'register_date', $_Oli->getUrlParam(2), false))); ?>
								</b>
							</p>
							<?php if($_Oli->verifyAuthKey() AND $_Oli->getAccountInfos('INFOS', 'show_activity', $_Oli->getUrlParam(2), false) AND $_Oli->isExistAccountInfos('SESSIONS', $_Oli->getUrlParam(2))) { ?>
							<p>
								<?php $timeOutput = []; ?>
								<?php foreach($_Oli->dateDifference($_Oli->getAccountInfos('SESSIONS', 'update_date', $_Oli->getUrlParam(2), array('order_by' => 'update_date DESC', 'limit' => 1), false), time(), true) as $eachUnit => $eachTime) { ?>
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
								
								<i class="fa fa-eye fa-fw"></i>
								Last seen
								<?php if(!empty($timeOutput)) { ?>
									<?php echo $timeOutput[0]; ?>
									<?php if(count($timeOutput) > 1) { ?>
										<small>
											<?php if(count($timeOutput) > 2) { ?>
												, <?php echo implode(', ', array_splice($timeOutput, 1, count($timeOutput) - 1)); ?>
											<?php } ?>
											and <?php echo $timeOutput[count($timeOutput) - 1]; ?>
										</small>
									<?php } ?> ago
								<?php } else { ?>
									<b>now</b>
								<?php } ?>
							</p>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
				
				<div class="mainBar col-sm-8">
					<?php if($_Oli->getUrlParam(2) != 'all') { ?>
						<div class="meta text-center">
							<p class="col-xs-4">
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>" class="btn btn-link">
									Posts <br />
									<span class="badge"><?php echo $_Oli->isExistInfosMySQL('social_posts', array('owner' => $_Oli->getUrlParam(2)), false) ?: 0; ?></span>
								</a>
							</p>
							<p class="col-xs-4">
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/followings" class="btn btn-link">
									Followings <br />
									<span class="badge"><?php echo $_Oli->isExistInfosMySQL('social_follows', array('username' => $_Oli->getUrlParam(2)), false) ?: 0; ?></span>
								</a>
							</p>
							<p class="col-xs-4">
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/followers" class="btn btn-link">
									Followers <br />
									<span class="badge"><?php echo $_Oli->isExistInfosMySQL('social_follows', array('follows' => $_Oli->getUrlParam(2)), false) ?: 0; ?></span>
								</a>
							</p>
						</div>
					<?php } ?>
					
					<?php if($_Oli->getUrlParam(2) != 'all' AND $_Oli->getUrlParam(3) == 'followers' AND $_Oli->isExistInfosMySQL('social_follows', array('username' => $_Oli->getUrlParam(2)), false)) { ?>
						<?php $followings = $_Oli->getInfosMySQL('social_follows', 'follows', array('username' => $_Oli->getUrlParam(2)), false, true); ?>
						<?php $countfollowings = count($followings); ?>
						
						<?php if(!empty($followings)) { ?>
							<?php foreach(array_reverse($followings) as $eachfollowing) { ?>
								<div class="user content-card">
									<span class="pull-right text-right">
										<p>
											<b><?php echo $_Oli->isExistInfosMySQL('social_posts', array('owner' => $eachfollowing), false) ?: 0; ?></b> posts <br />
											<b><?php echo $_Oli->isExistInfosMySQL('social_follows', array('follows' => $eachfollowing), false) ?: 0; ?></b> followers
										</p>
									</span>
									<h4 class="header">
										<?php $avatarInfos = $_Avatar->getFileLines(array('name' => 'user_avatar', 'owner' => $eachfollowing)); ?>
										<img src="<?php echo (!empty($avatarInfos)) ? $_Avatar->getUploadsUrl() . $avatarInfos['path_addon'] . $avatarInfos['file_name'] : $_Gravatar->getGravatar($_Oli->getAccountInfos('ACCOUNTS', 'email', $eachfollowing), 100); ?>" class="avatar img-rounded" alt="<?php echo $eachfollowing; ?>" />
										<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $eachfollowing; ?>">
											<?php echo $_Oli->getAccountInfos('INFOS', 'name', $eachfollowing) ?: $eachfollowing; ?>
										</a>
									</h4>
								</div>
							<?php } ?>
						<?php } else { ?>
							<div class="content-card text-center">
								<?php if($_Oli->getAuthKeyOwner() == $_Oli->getUrlParam(2)) { ?>
									<h3>You don't follow anyone</h3>
								<?php } else { ?>
									<h3>This user doesn't follow anyone!</h3>
								<?php } ?>
							</div>
						<?php } ?>
					<?php } else if($_Oli->getUrlParam(2) != 'all' AND $_Oli->getUrlParam(3) == 'followers' AND $_Oli->isExistInfosMySQL('social_follows', array('follows' => $_Oli->getUrlParam(2)), false)) { ?>
						<?php $followers = $_Oli->getInfosMySQL('social_follows', 'username', array('follows' => $_Oli->getUrlParam(2)), false, true); ?>
						<?php $countfollowers = count($followers); ?>
						
						<?php if(!empty($followers)) { ?>
							<?php foreach(array_reverse($followers) as $eachfollowers) { ?>
								<div class="user content-card">
									<span class="pull-right text-right">
										<p>
											<b><?php echo $_Oli->isExistInfosMySQL('social_posts', array('owner' => $eachfollowers), false) ?: 0; ?></b> posts <br />
											<b><?php echo $_Oli->isExistInfosMySQL('social_follows', array('follows' => $eachfollowers), false) ?: 0; ?></b> followers
										</p>
									</span>
									<h4 class="header">
										<?php $avatarInfos = $_Avatar->getFileLines(array('name' => 'user_avatar', 'owner' => $eachfollowers)); ?>
										<img src="<?php echo (!empty($avatarInfos)) ? $_Avatar->getUploadsUrl() . $avatarInfos['path_addon'] . $avatarInfos['file_name'] : $_Gravatar->getGravatar($_Oli->getAccountInfos('ACCOUNTS', 'email', $eachfollowers), 100); ?>" class="avatar img-rounded" alt="<?php echo $eachfollowers; ?>" />
										<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $eachfollowers; ?>">
											<?php echo $_Oli->getAccountInfos('INFOS', 'name', $eachfollowers) ?: $eachfollowers; ?>
										</a>
									</h4>
								</div>
							<?php } ?>
						<?php } else { ?>
							<div class="content-card text-center">
								<?php if($_Oli->getAuthKeyOwner() == $_Oli->getUrlParam(2)) { ?>
									<h3>You don't have any followers yet!</h3>
								<?php } else { ?>
									<h3>This user has no followers yet!</h3>
									<p>Maybe you could be his first follower</p>
								<?php } ?>
							</div>
						<?php } ?>
					<?php } else { ?> <?php if($_Oli->getUrlParam(2) != 'all') { ?><hr /><?php } ?>
						<?php $userPosts = $_Oli->getLinesMySQL('social_posts', array('owner' => $_Oli->getUrlParam(2)), false, true); ?>
						<?php $countUserPosts = count($userPosts); ?>
						
						<?php $limit = 50; ?>
						<?php $totalPages = ceil($countAllPosts / $limit); ?>
						<?php $page = array_reverse($_Oli->getUrlParam('params'))[0] >= 1 ? (array_reverse($_Oli->getUrlParam('params'))[0] < $totalPages ? array_reverse($_Oli->getUrlParam('params'))[0] : $totalPages) : 1; ?>
						<?php $posts = array_slice(array_reverse($userPosts), ($page - 1) * $limit, $limit); ?>
						
						<?php if(!empty($posts)) { ?>
							<div class="tools">
								<p class="pull-right text-muted">
									Showing
									<?php if($page == 1) { ?>last<?php } ?>
									<?php echo count($posts); ?> post<?php if(count($posts) > 1) { ?>s<?php } ?>
								</p>
								<p>
									<?php if($page > 1) { ?>
										<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $page - 1; ?>" class="btn btn-link btn-xs">
											<span class="badge"><i class="fa fa-angle-left fa-fw"></i></span>
										</a>
									<?php } ?>
									Page <?php echo $page; ?>
									<?php if($page < $totalPages) { ?>
										<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $page + 1; ?>" class="btn btn-link btn-xs">
											<span class="badge"><i class="fa fa-angle-right fa-fw"></i></span>
										</a>
									<?php } ?>
								</p>
							</div>
							
							<?php foreach($posts as $eachPost) { ?>
								<?php if($postInfos['owner'] != 'all' AND empty($eachPost['content']) AND !empty($eachPost['quote_from'])) { ?>
									<?php $citator = $eachPost['owner']; ?>
									<?php $eachPost = $_Oli->getLinesMySQL('social_posts', array('id' => $eachPost['quote_from'])); ?>
								<?php } else $citator = null; ?>
								
								<div class="post content-card <?php if($eachPost['owner'] == 'all') { ?>highlight<?php } ?> <?php if(isset($citator)) { ?>repost<?php } ?>" id="<?php echo $eachPost['id']; ?>">
									<?php if(isset($citator)) { ?>
										<p>
											<b>
												<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $citator; ?>"><?php echo $citator; ?></a>
												reposted this:
											</b>
										</p>
									<?php } ?>
									
									<?php if($eachPost['owner'] != 'all') { ?>
										<span class="pull-right text-right">
											<p class="hidden-xs">
												<a href="#" class="btn btn-link btn-xs disabled">
													<i class="fa fa-reply fa-lg"></i>
													<b><?php echo $_Oli->isExistInfosMySQL('social_posts', array('reply_to' => $eachPost['id']), false) ?: 0; ?></b>
												</a>
												<a href="<?php echo $_Oli->getUrlParam(0); ?>toggle-like.php" class="btn btn-link btn-xs action-like <?php if($_Oli->isExistInfosMySQL('social_likes', array('username' => $_Oli->getAuthKeyOwner(), 'post_id' => $eachPost['id']), false)) { ?>active<?php } ?>">
													<i class="fa fa-thumbs-up fa-lg"></i>
													<b><?php echo $_Oli->isExistInfosMySQL('social_likes', array('post_id' => $eachPost['id']), false) ?: 0; ?></b>
												</a>
												<a href="<?php if($eachPost['owner'] != $_Oli->getAuthKeyOwner()) { ?><?php echo $_Oli->getUrlParam(0); ?>toggle-repost.php<?php } else { ?>#<?php } ?>" class="btn btn-link btn-xs <?php if($eachPost['owner'] != $_Oli->getAuthKeyOwner()) { ?>action-repost<?php } else { ?>disabled<?php } ?> <?php if($_Oli->isExistInfosMySQL('social_posts', array('owner' => $_Oli->getAuthKeyOwner(), 'content' => '', 'quote_from' => $eachPost['id']), false)) { ?>active<?php } ?>">
													<i class="fa fa-retweet fa-lg"></i>
													<b><?php echo $_Oli->isExistInfosMySQL('social_posts', array('content' => '', 'quote_from' => $eachPost['id']), false) ?: 0; ?></b>
												</a>
												<?php if($eachPost['owner'] == $_Oli->getAuthKeyOwner()) { ?>
													<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/delete/<?php echo $eachPost['id']; ?>/<?php echo $page; ?>" class="btn btn-link btn-xs">
														<span class="text-danger">
															<i class="fa fa-trash fa-lg"></i>
														</span>
													</a>
												<?php } ?>
											</p>
											<p>
												<i class="fa fa-clock-o fa-fw"></i>
												<a href="<?php echo $_Oli->getUrlParam(0); ?>post/<?php echo $eachPost['id']; ?>">
													<time datetime="<?php echo date('Y-m-d H:i:s', strtotime($eachPost['post_date'])); ?>">
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
														
														<?php echo $timeOutput[0]; ?>
														<?php if(count($timeOutput) > 1) { ?>
															<small>
																<?php if(count($timeOutput) > 2) { ?>
																	, <?php echo implode(', ', array_splice($timeOutput, 1, count($timeOutput) - 2)); ?>
																<?php } ?>
																et <?php echo $timeOutput[count($timeOutput) - 1]; ?>
															</small>
														<?php } ?> ago
													</time>
												</a>
											</p>
										</span>
										<div class="header">
											<?php $avatarInfos = $_Avatar->getFileLines(array('name' => 'user_avatar', 'owner' => $eachPost['owner'])); ?>
											<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $eachPost['owner']; ?>" class="header-left">
												<img src="<?php echo (!empty($avatarInfos)) ? $_Avatar->getUploadsUrl() . $avatarInfos['path_addon'] . $avatarInfos['file_name'] : $_Gravatar->getGravatar($_Oli->getAccountInfos('ACCOUNTS', 'email', $eachPost['owner']), 100); ?>" class="avatar img-rounded" alt="<?php echo $eachPost['owner']; ?>" />
											</a>
											<div class="header-body">
												<h4 class="heading">
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
											<a href="#" class="btn btn-link btn-xs disabled">
												<i class="fa fa-reply fa-lg"></i>
												<b><?php echo $_Oli->isExistInfosMySQL('social_posts', array('reply_to' => $eachPost['id']), false) ?: 0; ?></b>
											</a>
											<a href="<?php echo $_Oli->getUrlParam(0); ?>toggle-like.php" class="btn btn-link btn-xs action-like <?php if($_Oli->isExistInfosMySQL('social_likes', array('username' => $_Oli->getAuthKeyOwner(), 'post_id' => $eachPost['id']), false)) { ?>active<?php } ?>">
												<i class="fa fa-thumbs-up fa-lg"></i>
												<b><?php echo $_Oli->isExistInfosMySQL('social_likes', array('post_id' => $eachPost['id']), false) ?: 0; ?></b>
											</a>
											<a href="<?php if($eachPost['owner'] != $_Oli->getAuthKeyOwner()) { ?><?php echo $_Oli->getUrlParam(0); ?>toggle-repost.php<?php } else { ?>#<?php } ?>" class="btn btn-link btn-xs <?php if($eachPost['owner'] != $_Oli->getAuthKeyOwner()) { ?>action-repost<?php } else { ?>disabled<?php } ?> <?php if($_Oli->isExistInfosMySQL('social_posts', array('owner' => $_Oli->getAuthKeyOwner(), 'content' => '', 'quote_from' => $eachPost['id']), false)) { ?>active<?php } ?>">
												<i class="fa fa-retweet fa-lg"></i>
												<b><?php echo $_Oli->isExistInfosMySQL('social_posts', array('content' => '', 'quote_from' => $eachPost['id']), false) ?: 0; ?></b>
											</a>
											<?php if($eachPost['owner'] == $_Oli->getAuthKeyOwner()) { ?>
												<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/delete/<?php echo $eachPost['id']; ?>/<?php echo $page; ?>" class="btn btn-link btn-xs">
													<span class="text-danger">
														<i class="fa fa-trash fa-lg"></i>
													</span>
												</a>
											<?php } ?>
										</p>
									</div>
								</div>
							<?php } ?>
							
							<div class="tools">
								<p class="pull-right text-muted">
									Showing
									<?php if($page == 1) { ?>last<?php } ?>
									<?php echo count($posts); ?> post<?php if(count($posts) > 1) { ?>s<?php } ?>
								</p>
								<p>
									<?php if($page > 1) { ?>
										<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $page - 1; ?>" class="btn btn-link btn-xs">
											<span class="badge"><i class="fa fa-angle-left fa-fw"></i></span>
										</a>
									<?php } ?>
									Page <?php echo $page; ?>
									<?php if($page < $totalPages) { ?>
										<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $page + 1; ?>" class="btn btn-link btn-xs">
											<span class="badge"><i class="fa fa-angle-right fa-fw"></i></span>
										</a>
									<?php } ?>
								</p>
							</div>
						<?php } else { ?>
							<div class="content-card text-center">
								<h3>This user has no post yet!</h3>
							</div>
						<?php } ?>
					<?php } ?>
				</div>
			<?php } else { ?>
				<div class="mainBar col-sm-offset-2 col-sm-8">
					<div class="message message-danger text-center">
						<h3>The user <i><?php echo ucfirst($_Oli->getUrlParam(2)); ?></i> does not exists!</h3>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>

<?php $_Oli->loadEndHtmlFiles(); ?>

</body>
</html>