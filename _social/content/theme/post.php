<?php
if(empty($_Oli->getUrlParam(2))) header('Location: ' . $_Oli->getUrlParam(0));

$postInfos = $_Oli->getLinesMySQL('social_posts', array('id' => $_Oli->getUrlParam(2)), false);
if($postInfos['owner'] != 'all' AND empty($postInfos['content']) AND !empty($postInfos['quote_from'])) {
	$citator = $postInfos['owner'];
	$postInfos = $_Oli->getLinesMySQL('social_posts', array('id' => $postInfos['quote_from']));
}
else $citator = null;

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
			if($eachKey == $_Oli->getUrlParam(2)) $postInfos = null;
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
<title>
	<?php if(!empty($postInfos)) { ?><?php echo $postInfos['owner']; ?>'s post -<?php } ?>
	<?php echo $_Oli->getSetting('name'); ?>
</title>

</head>
<body>

<?php include THEMEPATH . 'header.php'; ?>

<div class="bigMedia" style="display: none;"><img /></div>
<div class="main">
	<div class="container-fluid">
		<div class="row">
			<div class="mainBar col-sm-offset-2 col-sm-8">
				<?php $conversations = $_Oli->getLinesMySQL('social_posts', array('id' => $postInfos['reply_to']), false, true); ?>
				<?php for($i = 0; !empty($conversations[$i]['reply_to']); $i++) { ?>
					<?php if($_Oli->isExistInfosMySQL('social_posts', array('id' => $conversations[$i]['reply_to']), false)) $conversations[] = $_Oli->getLinesMySQL('social_posts', array('id' => $conversations[$i]['reply_to']), false); ?>
				<?php } ?>
				<?php $countConversations = count($conversations); ?>
				
				<?php $allReplies = $_Oli->getLinesMySQL('social_posts', array('reply_to' => $postInfos['id']), false, true); ?>
				<?php $countAllReplies = count($allReplies); ?>
				
				<?php $limit = 20; ?>
				<?php $totalPages = ceil($countAllReplies / $limit); ?>
				<?php $page = array_reverse($_Oli->getUrlParam('params'))[0] >= 1 ? (array_reverse($_Oli->getUrlParam('params'))[0] < $totalPages ? array_reverse($_Oli->getUrlParam('params'))[0] : $totalPages) : 1; ?>
				<?php $replies = array_slice(array_reverse($allReplies), ($page - 1) * $limit, $limit); ?>
				
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
				
				<?php if(!empty($postInfos)) { ?>
					<?php if(!empty($conversations)) { ?>
						<?php foreach(array_reverse($conversations) as $eachPost) { ?>
							<div class="post small content-card" id="<?php echo $eachPost['id']; ?>">
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
								</div> <hr />
							
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
					<?php } ?>
					
					<div class="post highlight content-card" id="<?php echo $postInfos['id']; ?>">
						<?php if(isset($citator)) { ?>
							<p>
								<b>
									<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $citator; ?>"><?php echo $citator; ?></a>
									reposted this:
								</b>
							</p>
						<?php } ?>
						
						<?php if($postInfos['owner'] != 'all') { ?>
							<span class="pull-right text-right">
								<p class="hidden-xs">
									<a href="#" class="btn btn-link btn-xs disabled">
										<i class="fa fa-reply fa-lg"></i>
										<b><?php echo $_Oli->isExistInfosMySQL('social_posts', array('reply_to' => $postInfos['id']), false) ?: 0; ?></b>
									</a>
									<a href="<?php echo $_Oli->getUrlParam(0); ?>toggle-like.php" class="btn btn-link btn-xs action-like <?php if($_Oli->isExistInfosMySQL('social_likes', array('username' => $_Oli->getAuthKeyOwner(), 'post_id' => $postInfos['id']), false)) { ?>active<?php } ?>">
										<i class="fa fa-thumbs-up fa-lg"></i>
										<b><?php echo $_Oli->isExistInfosMySQL('social_likes', array('post_id' => $postInfos['id']), false) ?: 0; ?></b>
									</a>
									<a href="<?php if($postInfos['owner'] != $_Oli->getAuthKeyOwner()) { ?><?php echo $_Oli->getUrlParam(0); ?>toggle-repost.php<?php } else { ?>#<?php } ?>" class="btn btn-link btn-xs <?php if($postInfos['owner'] != $_Oli->getAuthKeyOwner()) { ?>action-repost<?php } else { ?>disabled<?php } ?> <?php if($_Oli->isExistInfosMySQL('social_posts', array('owner' => $_Oli->getAuthKeyOwner(), 'content' => '', 'quote_from' => $postInfos['id']), false)) { ?>active<?php } ?>">
										<i class="fa fa-retweet fa-lg"></i>
										<b><?php echo $_Oli->isExistInfosMySQL('social_posts', array('content' => '', 'quote_from' => $postInfos['id']), false) ?: 0; ?></b>
									</a>
									<?php if($postInfos['owner'] == $_Oli->getAuthKeyOwner()) { ?>
										<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/delete/<?php echo $postInfos['id']; ?>/<?php echo $page; ?>" class="btn btn-link btn-xs">
											<span class="text-danger">
												<i class="fa fa-trash fa-lg"></i>
											</span>
										</a>
									<?php } ?>
								</p>
								<p>
									<i class="fa fa-clock-o fa-fw"></i>
									<a href="<?php echo $_Oli->getUrlParam(0); ?>post/<?php echo $postInfos['id']; ?>">
										<time datetime="<?php echo date('Y-m-d H:i:s', strtotime($postInfos['post_date'])); ?>">
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
								<?php $avatarInfos = $_Avatar->getFileLines(array('name' => 'user_avatar', 'owner' => $postInfos['owner'])); ?>
								<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $postInfos['owner']; ?>" class="header-left">
									<img src="<?php echo (!empty($avatarInfos)) ? $_Avatar->getUploadsUrl() . $avatarInfos['path_addon'] . $avatarInfos['file_name'] : $_Gravatar->getGravatar($_Oli->getAccountInfos('ACCOUNTS', 'email', $postInfos['owner']), 100); ?>" class="avatar img-rounded" alt="<?php echo $postInfos['owner']; ?>" />
								</a>
								<div class="header-body">
									<h4 class="heading">
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
						<?php } ?> <hr class="visible-xs-block" />
						
						<?php if($postInfos['owner'] != 'all') { ?>
							<div class="meta visible-xs-block">
								<p>
									<a href="#" class="btn btn-link btn-xs disabled">
										<i class="fa fa-reply fa-lg"></i>
										<b><?php echo $_Oli->isExistInfosMySQL('social_posts', array('reply_to' => $postInfos['id']), false) ?: 0; ?></b>
									</a>
									<a href="<?php echo $_Oli->getUrlParam(0); ?>toggle-like.php" class="btn btn-link btn-xs action-like <?php if($_Oli->isExistInfosMySQL('social_likes', array('username' => $_Oli->getAuthKeyOwner(), 'post_id' => $postInfos['id']), false)) { ?>active<?php } ?>">
										<i class="fa fa-thumbs-up fa-lg"></i>
										<b><?php echo $_Oli->isExistInfosMySQL('social_likes', array('post_id' => $postInfos['id']), false) ?: 0; ?></b>
									</a>
									<a href="<?php if($postInfos['owner'] != $_Oli->getAuthKeyOwner()) { ?><?php echo $_Oli->getUrlParam(0); ?>toggle-repost.php<?php } else { ?>#<?php } ?>" class="btn btn-link btn-xs <?php if($postInfos['owner'] != $_Oli->getAuthKeyOwner()) { ?>action-repost<?php } else { ?>disabled<?php } ?> <?php if($_Oli->isExistInfosMySQL('social_posts', array('owner' => $_Oli->getAuthKeyOwner(), 'content' => '', 'quote_from' => $postInfos['id']), false)) { ?>active<?php } ?>">
										<i class="fa fa-retweet fa-lg"></i>
										<b><?php echo $_Oli->isExistInfosMySQL('social_posts', array('content' => '', 'quote_from' => $postInfos['id']), false) ?: 0; ?></b>
									</a>
									<?php if($postInfos['owner'] == $_Oli->getAuthKeyOwner()) { ?>
										<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/delete/<?php echo $postInfos['id']; ?>/<?php echo $page; ?>" class="btn btn-link btn-xs">
											<span class="text-danger">
												<i class="fa fa-trash fa-lg"></i>
											</span>
										</a>
									<?php } ?>
								</p>
							</div>

							<?php if($_Oli->verifyAuthKey()) { ?> <hr />
								<form action="<?php echo $_Oli->getUrlParam(0); ?>send-post.php" redirect-url="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $page; ?>" class="form form-horizontal" method="post" enctype="multipart/form-data">
									<div class="form-group">
										<div class="col-xs-12">
											<input type="file" name="media" style="display: none;" />
											<div class="input-group">
												<textarea type="text" class="form-control" name="content" placeholder="Answer this post" rows="1"><?php if($postInfos['owner'] != $_Oli->getAuthKeyOwner()) { ?>@<?php echo $postInfos['owner']; ?> - <?php } ?></textarea>
												<a href="#" class="input-group-addon btn btn-default select-media">
													<i class="fa fa-picture-o fa-fw"></i>
												</a>
												<a href="#" class="input-group-addon btn btn-default submit">
													<i class="fa fa-paper-plane fa-fw"></i>
													<span class="hidden-xs">Poster</span>
												</a>
												</a>
											</div>
										</div>
									</div>
								</form>
							<?php } ?>
						<?php } ?>
					</div>
					
					<?php if(!empty($replies)) { ?> <hr />
						<h4 class="text-center">- Replies -</h4>
						
						<div class="tools">
							<p class="pull-right text-muted">
								Showing
								<?php if($page == 1) { ?>last<?php } ?>
								<?php echo count($replies); ?> repl<?php echo count($replies) <= 1 ? 'y' : 'ies'; ?>
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
						
						<?php foreach($replies as $eachReply) { ?>
							<div class="post small content-card" id="<?php echo $eachReply['id']; ?>">
								<span class="pull-right text-right">
									<p class="hidden-xs">
										<a href="#" class="btn btn-link btn-xs disabled">
											<i class="fa fa-reply fa-lg"></i>
											<b><?php echo $_Oli->isExistInfosMySQL('social_posts', array('reply_to' => $eachReply['id']), false) ?: 0; ?></b>
										</a>
										<a href="<?php echo $_Oli->getUrlParam(0); ?>toggle-like.php" class="btn btn-link btn-xs action-like <?php if($_Oli->isExistInfosMySQL('social_likes', array('username' => $_Oli->getAuthKeyOwner(), 'post_id' => $eachReply['id']), false)) { ?>active<?php } ?>">
											<i class="fa fa-thumbs-up fa-lg"></i>
											<b><?php echo $_Oli->isExistInfosMySQL('social_likes', array('post_id' => $eachReply['id']), false) ?: 0; ?></b>
										</a>
										<a href="#" class="btn btn-link btn-xs disabled">
											<i class="fa fa-retweet fa-lg"></i>
											<b>0</b>
										</a>
										<?php if($eachReply['owner'] == $_Oli->getAuthKeyOwner()) { ?>
											<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/delete/<?php echo $eachReply['id']; ?>/<?php echo $page; ?>" class="btn btn-link btn-xs">
												<span class="text-danger">
													<i class="fa fa-trash fa-lg"></i>
												</span>
											</a>
										<?php } ?>
									</p>
									<p>
										<i class="fa fa-clock-o fa-fw"></i>
										<time datetime="<?php echo date('Y-m-d H:i:s', strtotime($eachReply['post_date'])); ?>">
											<a href="<?php echo $_Oli->getUrlParam(0); ?>post/<?php echo $eachReply['id']; ?>">
												<?php $timeOutput = []; ?>
												<?php foreach($_Oli->dateDifference($eachReply['post_date'], time(), true) as $eachUnit => $eachTime) { ?>
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
									<?php $avatarInfos = $_Avatar->getFileLines(array('name' => 'user_avatar', 'owner' => $eachReply['owner'])); ?>
									<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $eachReply['owner']; ?>" class="header-left">
										<img src="<?php echo (!empty($avatarInfos)) ? $_Avatar->getUploadsUrl() . $avatarInfos['path_addon'] . $avatarInfos['file_name'] : $_Gravatar->getGravatar($_Oli->getAccountInfos('ACCOUNTS', 'email', $eachReply['owner']), 100); ?>" class="avatar img-rounded" alt="<?php echo $eachReply['owner']; ?>" />
									</a>
									<div class="header-body">
										<h4 class="heading">
											<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $eachReply['owner']; ?>">
												<?php echo $_Oli->getAccountInfos('INFOS', 'name', $eachReply['owner']) ?: $eachReply['owner']; ?>
											</a>
										</h4>
										<small>@<?php echo $eachReply['owner']; ?></small>
									</div>
								</div> <hr />
								
								<?php preg_match_all('/((?:https?:\/\/)?(?:[\da-z\.-]+)\.(?:[a-z\.]{2,6})([\/\w\.?=&-]*)*\/?)/', $eachReply['content'], $linksOutput); ?>
								<?php $links = is_array($linksOutput[1]) ? array_unique($linksOutput[1]) : [$linksOutput[1]]; ?>
								<?php if(!empty($links)) { ?>
									<?php foreach($links as $eachLink) { ?>
										<?php $replace = '<a href="' . $eachLink . '" target="_blank">' . $eachLink  . '</a>'; ?>
										<?php $eachReply['content'] = str_replace($eachLink, $replace, $eachReply['content']); ?>
									<?php } ?>
								<?php } ?>
							
								<?php preg_match_all('/(((?:https?:\/\/)?(?:[w]{3}\.)?)((?:[\da-z\.-]+)\.(?:[a-z\.]{2,6})([\/\w\.?=&-]*)*\/?))/', $eachReply['content'], $linksOutput); ?>
								<?php $links = is_array($linksOutput[1]) ? array_unique($linksOutput[1]) : [$linksOutput[1]]; ?>
								<?php if(!empty($links)) { ?>
									<?php foreach($links as $eachKey => $eachLink) { ?>
										<?php $replace = '<a href="' . ($linksOutput[2][$eachKey] ?: 'http://') . $linksOutput[3][$eachKey] . '" target="_blank">' . $linksOutput[3][$eachKey]  . '</a>'; ?>
										<?php $eachReply['content'] = str_replace($eachLink, $replace, $eachReply['content']); ?>
									<?php } ?>
								<?php } ?>
								
								<?php preg_match_all('/@([\w-]+)/', $eachReply['content'], $mentionsOutput); ?>
								<?php $mentions = is_array($mentionsOutput[1]) ? array_unique($mentionsOutput[1]) : [$mentionsOutput[1]]; ?>
								<?php if(!empty($mentions)) { ?>
									<?php foreach($mentions as $eachMention) { ?>
										<?php if($_Oli->isExistAccountInfos('ACCOUNTS', $eachMention, false)) { ?>
											<?php $replace = '<a href="' . $_Oli->getUrlParam(0) . 'user/' . $_Oli->getAccountInfos('ACCOUNTS', 'username', $eachMention, false) . '">@' . $_Oli->getAccountInfos('ACCOUNTS', 'username', $eachMention, false) . '</a>'; ?>
											<?php $eachReply['content'] = str_replace('@' . $eachMention, $replace, $eachReply['content']); ?>
										<?php } ?>
									<?php } ?>
								<?php } ?>
								
								<?php preg_match_all('/#(\w+)/', $eachReply['content'], $hashtagsOutput); ?>
								<?php $hashtags = is_array($hashtagsOutput[1]) ? array_unique($hashtagsOutput[1]) : [$hashtagsOutput[1]]; ?>
								<?php if(!empty($hashtags)) { ?>
									<?php foreach($hashtags as $eachHashtag) { ?>
										<?php $replace = '<a href="' . $_Oli->getUrlParam(0) . 'search/' . urlencode('#') . $eachHashtag . '">#' . $eachHashtag . '</a>'; ?>
										<?php $eachReply['content'] = str_replace('#' . $eachHashtag, $replace, $eachReply['content']); ?>
									<?php } ?>
								<?php } ?>
								
								<p class="content"><?php echo nl2br($eachReply['content']); ?></p>
								
								<?php $mediaInfos = $_Media->getFileLines(array('file_key' => $eachReply['media_key'])); ?>
								<?php if(!empty($mediaInfos) AND $_Media->isExistFile($mediaInfos['path_addon'] . $mediaInfos['file_name'])) { ?>
									<div class="media">
										<img src="<?php echo $_Media->getUploadsUrl() . $mediaInfos['path_addon'] . $mediaInfos['file_name']; ?>" />
									</div>
								<?php } ?> <hr class="visible-xs-block" />
								
								<div class="meta visible-xs-block">
									<p>
										<a href="#" class="btn btn-link btn-xs disabled">
											<i class="fa fa-reply fa-lg"></i>
											<b><?php echo $_Oli->isExistInfosMySQL('social_posts', array('reply_to' => $eachReply['id']), false) ?: 0; ?></b>
										</a>
										<a href="<?php echo $_Oli->getUrlParam(0); ?>toggle-like.php" class="btn btn-link btn-xs action-like <?php if($_Oli->isExistInfosMySQL('social_likes', array('username' => $_Oli->getAuthKeyOwner(), 'post_id' => $eachReply['id']), false)) { ?>active<?php } ?>">
											<i class="fa fa-thumbs-up fa-lg"></i>
											<b><?php echo $_Oli->isExistInfosMySQL('social_likes', array('post_id' => $eachReply['id']), false) ?: 0; ?></b>
										</a>
										<a href="#" class="btn btn-link btn-xs disabled">
											<i class="fa fa-retweet fa-lg"></i>
											<b>0</b>
										</a>
										<?php if($eachReply['owner'] == $_Oli->getAuthKeyOwner()) { ?>
											<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/delete/<?php echo $eachReply['id']; ?>/<?php echo $page; ?>" class="btn btn-link btn-xs">
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
								<?php echo count($replies); ?> repl<?php echo count($replies) <= 1 ? 'y' : 'ies'; ?>
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
					<?php } ?>
				<?php } else { ?>
					<div class="message message-danger text-center">
						<h3>This post does not exists!</h3>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<?php $_Oli->loadEndHtmlFiles(); ?>

</body>
</html>