<?php
if(!$_Oli->verifyAuthKey() OR $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) < $_Oli->translateUserRight('USER')) header('Location: ' . $_Oli->getShortcutLink('login'));

if($_Oli->getUrlParam(2) == 'delete' AND !empty($_Oli->getUrlParam(3))) {
	$paramData = urldecode($_Oli->getUrlParam(3));
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
	else if($_Oli->getUrlParam(4) != 'confirmed') $resultCode = 'CONFIRMATION_NEEDED';
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
<?php $_Oli->loadLocalScript('js/post.js', false); ?>
<?php $_Oli->loadLocalScript('js/actions.js', false); ?>
<?php $_Oli->loadLocalScript('js/media.js', false); ?>
<title><?php echo $_Oli->getSetting('name'); ?></title>

</head>
<body>

<?php include THEMEPATH . 'header.php'; ?>

<div class="bigMedia" style="display: none;"><img /></div>
<div class="main">
	<div class="container-fluid">
		<div class="row">
			<div class="leftBar fixed col-sm-4">
				<?php if($_Oli->getUrlParam(2) == 'delete' AND !empty($_Oli->getUrlParam(3)) AND $resultCode == 'CONFIRMATION_NEEDED') { ?>
					<div class="message message-highlight-danger">
						<p>
							<b>You asked to delete these posts</b>, please confirm you want to delete them <hr />
							<span class="text-success">
								<i class="fa fa-check fa-fw"></i>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $_Oli->getUrlParam(3); ?>/confirmed/<?php echo $_Oli->getUrlParam(4); ?>">I want to delete them</a>
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
				
				<div class="profile content-card hidden-xs text-center">
					<h4>
						<small>Hey</small>
						<?php echo $_Oli->getAuthKeyOwner(); ?>
						<small>!</small>
					</h4>
					<?php $avatarInfos = $_Avatar->getFileLines(array('name' => 'user_avatar', 'owner' => $_Oli->getAuthKeyOwner())); ?>
					<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $_Oli->getAuthKeyOwner(); ?>">
						<img src="<?php echo (!empty($avatarInfos)) ? $_Avatar->getUploadsUrl() . $avatarInfos['path_addon'] . $avatarInfos['file_name'] : $_Gravatar->getGravatar($_Oli->getAccountInfos('ACCOUNTS', 'email', array('username' => $_Oli->getAuthKeyOwner())), 100); ?>" class="avatar img-rounded" alt="<?php echo $_Oli->getAuthKeyOwner(); ?>" />
					</a> <hr />
					
					<p class="col-md-4 col-sm-6">
						<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $_Oli->getAuthKeyOwner(); ?>" class="btn btn-link">
							Posts <br />
							<span class="badge"><?php echo $_Oli->isExistInfosMySQL('social_posts', array('owner' => $_Oli->getAuthKeyOwner()), false) ?: 0; ?></span>
						</a>
					</p>
					<p class="col-md-4 hidden-sm">
						<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $_Oli->getAuthKeyOwner(); ?>/followings" class="btn btn-link">
							Followings <br />
							<span class="badge"><?php echo $_Oli->isExistInfosMySQL('social_follows', array('username' => $_Oli->getAuthKeyOwner()), false) ?: 0; ?></span>
						</a>
					</p>
					<p class="col-md-4 col-sm-6">
						<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $_Oli->getAuthKeyOwner(); ?>/followers" class="btn btn-link">
							Followers <br />
							<span class="badge"><?php echo $_Oli->isExistInfosMySQL('social_follows', array('follows' => $_Oli->getAuthKeyOwner()), false) ?: 0; ?></span>
						</a>
					</p>
					<div class="clearfix"></div> <hr />
					
					<p>
						<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $_Oli->getAuthKeyOwner(); ?>" class="btn btn-primary btn-sm">
							<i class="fa fa-user fa-fw"></i> Your profile
						</a>
						<a href="<?php echo $_Oli->getUrlParam(0); ?>settings/" class="btn btn-default btn-sm">
							<i class="fa fa-gear fa-fw"></i> Settings
						</a>
					</p>
				</div>
				
				<?php /*<div class="content-card text-center">
					<button onclick="myFunction('Demo1')" class="w3-btn-block w3-theme-l1 w3-left-align"><i class="fa fa-circle-o-notch fa-fw w3-margin-right"></i> My Groups</button>
					<div id="Demo1" class="w3-accordion-content w3-container ">
						<p>Some text..</p>
					</div>
					<button onclick="myFunction('Demo3')" class="w3-btn-block w3-theme-l1 w3-left-align"><i class="fa fa-users fa-fw w3-margin-right"></i> My Photos</button>
					<div id="Demo3" class="w3-accordion-content w3-container  ">
						<div class="w3-row-padding">
							<div class="w3-half">
								<img src="img_lights.jpg" style="width:100%" class="w3-margin-bottom">
							</div>
							<div class="w3-half">
								<img src="img_nature.jpg" style="width:100%" class="w3-margin-bottom">
							</div>
							<div class="w3-half">
								<img src="img_mountains.jpg" style="width:100%" class="w3-margin-bottom">
							</div>
							<div class="w3-half">
								<img src="img_forest.jpg" style="width:100%" class="w3-margin-bottom">
							</div>
							<div class="w3-half">
								<img src="img_nature.jpg" style="width:100%" class="w3-margin-bottom">
							</div>
							<div class="w3-half">
								<img src="img_fjords.jpg" style="width:100%" class="w3-margin-bottom">
							</div>
						</div>
					</div>
				</div>*/ ?>
				
				<?php /*<div class="content-card">
					<p>Interests</p>
					<p>
						<span class="label label-primary">News</span>
						<span class="label label-primary">W3Schools</span>
						<span class="label label-default">Labels</span>
					</p>
				</div>*/ ?>
				
				<div class="about-links content-card">
					<p><i class="fa fa-angle-right fa-fw"></i> <a href="<?php echo $_Oli->getUrlParam(0); ?>about/">About us</a></p>
					<p><i class="fa fa-angle-right fa-fw"></i> <a href="<?php echo $_Oli->getUrlParam(0); ?>support/">Help & Support</a></p>
					<?php /*<p><i class="fa fa-angle-right fa-fw"></i> <a href="#">[Mentions légales]</a></p>*/ ?>
				</div>
			</div>
			
			<div class="mainBar col-sm-8">
				<?php $allFolowings = array_merge([$_Oli->getAuthKeyOwner(), 'all'], $_Oli->getInfosMySQL('social_follows', 'follows', array('username' => $_Oli->getAuthKeyOwner()), false, true) ?: []); ?>
				<?php foreach($allFolowings as $eachfollowing) { ?>
					<?php foreach($_Oli->getLinesMySQL('social_posts', array('owner' => $eachfollowing), false, true) as $eachPost) { ?>
						<?php if(!empty($eachPost['content']) OR (!empty($eachPost['quote_from']) AND !in_array($_Oli->getInfosMySQL('social_posts', 'owner', array('id' => $eachPost['quote_from'])), $allFolowings))) $allPosts[$eachPost['id']] = $eachPost; ?>
					<?php } ?>
				<?php } ?>
				<?php $countAllPosts = count($allPosts); ?>
				
				<?php $limit = 50; ?>
				<?php $totalPages = ceil($countAllPosts / $limit); ?>
				<?php $page = array_reverse($_Oli->getUrlParam('params'))[0] >= 1 ? (array_reverse($_Oli->getUrlParam('params'))[0] < $totalPages ? array_reverse($_Oli->getUrlParam('params'))[0] : $totalPages) : 1; ?>
				
				<div class="new-post content-card">
					<form action="<?php echo $_Oli->getUrlParam(0); ?>send-post.php" redirect-url="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $page; ?>" class="form form-horizontal" method="post" enctype="multipart/form-data">
						<div class="form-group">
							<div class="col-xs-12">
								<input type="file" name="media" style="display: none;" />
								<div class="input-group">
									<textarea type="text" class="form-control" name="content" placeholder="Something to say?" rows="1"></textarea>
									<a href="#" class="input-group-addon btn btn-default select-media">
										<i class="fa fa-picture-o fa-fw"></i>
									</a>
									<a href="#" class="input-group-addon btn btn-default submit">
										<i class="fa fa-paper-plane fa-fw"></i>
										<span class="hidden-xs">Poster</span>
									</a>
								</div>
							</div>
						</div>
					</form>
				</div>
				
				<?php if(!empty($allPosts)) { ?>
					<?php sort($allPosts); ?>
					<?php $posts = array_slice(array_reverse($allPosts), ($page - 1) * $limit, $limit); ?>
					
					<div class="tools">
						<p class="pull-right text-muted">
							Showing
							<?php if($page == 1) { ?>last<?php } ?>
							<?php echo count($posts); ?> post<?php if(count($posts) > 1) { ?>s<?php } ?>
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
					
					<?php foreach($posts as $eachPost) { ?>
						<?php if($eachPost['owner'] != 'all' OR !$_Oli->getAccountInfos('INFOS', 'hide_announce', $_Oli->getAuthKeyOwner(), false)) { ?>
							<?php if(empty($eachPost['content']) AND !empty($eachPost['quote_from'])) { ?>
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
								
								<?php if($eachPost['owner'] != 'all') { ?>
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
								<?php } ?>
							</div>
						<?php } ?>
					<?php } ?>
					
					<div class="tools">
						<p class="pull-right text-muted">
							Showing
							<?php if($page == 1) { ?>last<?php } ?>
							<?php echo count($posts); ?> post<?php if(count($posts) > 1) { ?>s<?php } ?>
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
					<div class="post content-card text-center">
						<h3>Not any post to show!</h3>
						<p>You can post your owns or search for other peoples to follow!</p> <?php /*<hr />
						
						<p>Whould you like to subscribe to...</p>
						<div class="row">
							<div class="col-xs-4">
								<img src="http://www.w3schools.com/w3css/img_avatar2.png" alt="User One" class="avatar img-rounded" />
								User One
								
								<div class="meta">
									<a href="#" class="btn btn-primary btn-sm"><i class="fa fa-user-plus fa-fw"></i> follow</a>
								</div>
							</div>
							<div class="col-xs-4">
								<img src="http://www.w3schools.com/w3css/img_avatar5.png" alt="User One" class="avatar img-rounded" />
								User Two
								
								<div class="meta">
									<a href="#" class="btn btn-primary btn-sm"><i class="fa fa-user-plus fa-fw"></i> follow</a>
								</div>
							</div>
							<div class="col-xs-4">
								<img src="http://www.w3schools.com/w3css/img_avatar6.png" alt="User One" class="avatar img-rounded" />
								User Three
								
								<div class="meta">
									<a href="#" class="btn btn-primary btn-sm"><i class="fa fa-user-plus fa-fw"></i> follow</a>
								</div>
							</div>
						</div>*/ ?>
					</div>
				<?php } ?>
				
				<?php /*
				<div class="post content-card text-center">
					<h3>Not any post to show</h3> <hr />
					
					<p>Whould you like to subscribe to...</p>
					<div class="row">
						<div class="col-xs-4">
							<img src="http://www.w3schools.com/w3css/img_avatar2.png" alt="User One" class="avatar img-rounded" />
							User One
							
							<div class="meta">
								<a href="#" class="btn btn-primary btn-sm"><i class="fa fa-user-plus fa-fw"></i> follow</a>
							</div>
						</div>
						<div class="col-xs-4">
							<img src="http://www.w3schools.com/w3css/img_avatar5.png" alt="User One" class="avatar img-rounded" />
							User Two
							
							<div class="meta">
								<a href="#" class="btn btn-primary btn-sm"><i class="fa fa-user-plus fa-fw"></i> follow</a>
							</div>
						</div>
						<div class="col-xs-4">
							<img src="http://www.w3schools.com/w3css/img_avatar6.png" alt="User One" class="avatar img-rounded" />
							User Three
							
							<div class="meta">
								<a href="#" class="btn btn-primary btn-sm"><i class="fa fa-user-plus fa-fw"></i> follow</a>
							</div>
						</div>
					</div>
				</div>
				
				<div class="post content-card">
					<span class="pull-right text-right hidden-xs">
						<p>
							<a href="#" class="btn btn-link btn-xs action-reply"><i class="fa fa-reply fa-lg"></i> <b>210</b></a>
							<a href="#" class="btn btn-link btn-xs action-like active"><i class="fa fa-thumbs-up fa-lg"></i> <b>1.3k</b></a>
							<a href="#" class="btn btn-link btn-xs action-repost active"><i class="fa fa-retweet fa-lg"></i> <b>764</b></a>
						</p>
						<p>
							<i class="fa fa-clock-o fa-fw"></i>
							<time datetime="<?php echo date('Y-m-d H:i:s', time() - 60); ?>">1 min ago</time>
						</p>
					</span>
					<h4 class="header">
						<img src="http://www.w3schools.com/w3css/img_avatar2.png" alt="User One" class="avatar img-rounded" />
						User One
					</h4> <hr />
					
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
					<div class="media">
						<div class="col-xs-6">
							<img src="http://www.w3schools.com/w3css/img_lights.jpg" class="img-thumbnail" />
						</div>
						<div class="col-xs-6">
							<img src="http://www.w3schools.com/w3css/img_nature.jpg" class="img-thumbnail" />
						</div>
					</div>
					
					<div class="meta visible-xs-block">
						<hr />
						<p>
							<a href="#" class="btn btn-link btn-xs action-reply"><i class="fa fa-reply fa-lg"></i> <b>210</b></a>
							<a href="#" class="btn btn-link btn-xs action-like active"><i class="fa fa-thumbs-up fa-lg"></i> <b>1.3k</b></a>
							<a href="#" class="btn btn-link btn-xs action-repost active"><i class="fa fa-retweet fa-lg"></i> <b>764</b></a>
						</p>
						<p>
							<i class="fa fa-clock-o fa-fw"></i>
							<time datetime="<?php echo date('Y-m-d H:i:s', time() - 60); ?>">1 min ago</time>
						</p>
					</div>
				</div>
				
				<div class="post content-card">
					<span class="pull-right text-right hidden-xs">
						<p>
							<a href="#" class="btn btn-link btn-xs action-reply"><i class="fa fa-reply fa-lg"></i> <b>14</b></a>
							<a href="#" class="btn btn-link btn-xs action-like active"><i class="fa fa-thumbs-up fa-lg"></i> <b>124</b></a>
							<a href="#" class="btn btn-link btn-xs action-repost"><i class="fa fa-retweet fa-lg"></i> <b>74</b></a> <br />
						</p>
						<p>
							<i class="fa fa-clock-o fa-fw"></i>
							<time datetime="<?php echo date('Y-m-d H:i:s', time() - (60 * 13)); ?>">13 min ago</time>
						</p>
					</span>
					<h4 class="header">
						<img src="http://www.w3schools.com/w3css/img_avatar5.png" alt="User Two" class="avatar img-rounded" />
						User Two
					</h4> <hr />
					
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
					
					<div class="meta visible-xs-block">
						<hr />
						<p>
							<a href="#" class="btn btn-link btn-xs action-reply"><i class="fa fa-reply fa-lg"></i> <b>14</b></a>
							<a href="#" class="btn btn-link btn-xs action-like active"><i class="fa fa-thumbs-up fa-lg"></i> <b>124</b></a>
							<a href="#" class="btn btn-link btn-xs action-repost"><i class="fa fa-retweet fa-lg"></i> <b>74</b></a> <br />
						</p>
						<p>
							<i class="fa fa-clock-o fa-fw"></i>
							<time datetime="<?php echo date('Y-m-d H:i:s', time() - (60 * 13)); ?>">13 min ago</time>
						</p>
					</div>
				</div>
				
				<div class="post content-card">
					<span class="pull-right text-right hidden-xs">
						<p>
							<a href="#" class="btn btn-link btn-xs action-reply"><i class="fa fa-reply fa-lg"></i> <b>134</b></a>
							<a href="#" class="btn btn-link btn-xs action-like"><i class="fa fa-thumbs-up fa-lg"></i> <b>1.1k</b></a>
							<a href="#" class="btn btn-link btn-xs action-repost"><i class="fa fa-retweet fa-lg"></i> <b>672</b></a>
						</p>
						<p>
							<i class="fa fa-clock-o fa-fw"></i>
							<time datetime="<?php echo date('Y-m-d H:i:s', time() - (60 * 32)); ?>">32 min ago</time>
						</p>
					</span>
					<h4 class="header">
						<img src="http://www.w3schools.com/w3css/img_avatar6.png" alt="User Three" class="avatar img-rounded" />
						User Three
					</h4> <hr />
					
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
					<div class="media">
						<img src="http://www.w3schools.com/w3css/img_nature.jpg" class="img-thumbnail" />
					</div>
					
					<div class="meta visible-xs-block">
						<hr />
						<p>
							<a href="#" class="btn btn-link btn-xs action-reply"><i class="fa fa-reply fa-lg"></i> <b>210</b></a>
							<a href="#" class="btn btn-link btn-xs action-like active"><i class="fa fa-thumbs-up fa-lg"></i> <b>1.3k</b></a>
							<a href="#" class="btn btn-link btn-xs action-repost active"><i class="fa fa-retweet fa-lg"></i> <b>764</b></a>
						</p>
						<p>
							<i class="fa fa-clock-o fa-fw"></i>
							<time datetime="<?php echo date('Y-m-d H:i:s', time() - 60); ?>">1 min ago</time>
						</p>
					</div>
				</div>*/ ?>
			</div>
			
			<?php /*<div class="col-sm-3">
				<div class="content-card">
					<p>Events:</p>
					<img src="img_forest.jpg" alt="Forest" style="width:100%;">
					<p><strong>Holiday</strong></p>
					<p>Friday 15:00</p>
					<p><button class="w3-btn w3-btn-block w3-theme-l4">Info</button></p>
				</div>
				
				<div class="friend-request content-card text-center">
					<p>Friend Request</p>
					<img src="http://www.w3schools.com/w3css/img_avatar6.png" alt="Jane Doe" class="avatar img-rounded">
					<span>Jane Doe</span>
					
					<div class="row">
						<div class="col-xs-6">
							<a href="#" class="btn btn-success btn-xs"><i class="fa fa-check fa-fw"></i></a>
						</div>
						<div class="col-xs-6">
							<a href="#" class="btn btn-danger btn-xs"><i class="fa fa-remove fa-fw"></i></a>
						</div>
					</div>
				</div>
				
				<div class="content-card text-center">
					<p>ADS</p>
				</div>
				
				<div class="content-card text-center">
					<p><i class="fa fa-bug fa-2x fa-fw"></i></p>
				</div>
			</div>*/ ?>
		</div>
	</div>
</div>

<?php $_Oli->loadEndHtmlFiles(); ?>

</body>
</html>