<?php
if(!$_Oli->verifyAuthKey() OR $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) < $_Oli->translateUserRight('USER'))
	header('Location: ' . $_Oli->getShortcutLink('login'));

if($_Oli->getUrlParam(2) == 'delete' AND !empty($_Oli->getUrlParam(3))) {
	$paramData = urldecode($_Oli->getUrlParam(3));
	$selectedMedias = (!is_array($paramData)) ? ((is_array(unserialize($paramData))) ? unserialize($paramData) : [$paramData]) : $paramData;
	
	foreach($selectedMedias as $eachKey) {
		if(!$_Media->isExistFileInfos(array('id' => $eachKey))) {
			$errorStatus = 'UNKNOWN_MEDIA';
			break;
		}
		else if($_Media->getFileInfos('owner', array('id' => $eachKey)) != $_Oli->getAuthKeyOwner()) {
			$errorStatus = 'NOT_YOUR_MEDIA';
			break;
		}
	}
	
	if(!empty($errorStatus)) $resultCode = $errorStatus;
	else if($_Oli->getUrlParam(4) != 'confirmed') $resultCode = 'CONFIRMATION_NEEDED';
	else {
		foreach($selectedMedias as $eachKey) {
			$deletedMediaInfos = $_Media->getFileLines(array('id' => $eachKey));
			$_Media->deleteFileInfos(array('id' => $eachKey));
			$_Media->deleteFile($deletedMediaInfos['path_addon'] . $deletedMediaInfos['file_name']);
		}
		$resultCode = 'MEDIA_DELETED';
	}
}
// else if($_Oli->getUrlParam(2) == 'remove' AND !empty($_Oli->getUrlParam(3))) {
	// $mediaKey = $_Oli->getUrlParam(4);
	// if(!$_Oli->isExistInfosMySQL('social_posts', array('id' => $_Oli->getUrlParam(3)))) $resultCode = 'UNKNOWN_POST';
	// else if($_Oli->getUrlParam(4) != 'confirmed') $resultCode = 'CONFIRMATION_NEEDED';
	// else {
		// foreach($selectedMedias as $eachKey) {
			// $deletedMediaInfos = $_Media->getFileLines(array('id' => $eachKey));
			// $_Oli->updadeInfosMySQL(array('media_key' => ''), array('id' => $_Oli->getUrlParam(3)));
		// }
		// $resultCode = 'MEDIA_REMOVED';
	// }
// }
?>

<!DOCTYPE html>
<html>
<head>

<?php include THEMEPATH . 'head.php'; ?>
<?php $_Oli->loadCdnScript('js/serialize-php.min.js', false); ?>
<?php $_Oli->loadLocalScript('js/selector.js', false); ?>
<?php $_Oli->loadLocalScript('js/actions.js', false); ?>
<?php $_Oli->loadLocalScript('js/media.js', false); ?>
<title>Your medias - <?php echo $_Oli->getSetting('name'); ?></title>

</head>
<body>

<?php include THEMEPATH . 'header.php'; ?>

<div class="bigMedia" style="display: none;"><img /></div>
<div class="main">
	<div class="container-fluid">
		<div class="row">
			<div class="leftBar col-sm-3">
				<?php if($_Oli->getUrlParam(2) == 'delete' AND !empty($_Oli->getUrlParam(3)) AND $resultCode == 'CONFIRMATION_NEEDED') { ?>
					<div class="message message-highlight-danger">
						<p>
							<b>You asked to delete these selected medias</b>, please confirm you want to delete them <hr />
							<span class="text-success">
								<i class="fa fa-check fa-fw"></i>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $_Oli->getUrlParam(3); ?>/confirmed">I want to delete them</a>
							</span> <br />
							<span class="text-danger">
								<i class="fa fa-times fa-fw"></i>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/">I refuse to delete them</a>
							</span>
						</p>
					</div>
				<?php } else if($_Oli->getUrlParam(2) == 'remove' AND !empty($_Oli->getUrlParam(3)) AND $resultCode == 'CONFIRMATION_NEEDED') { ?>
					<div class="message message-highlight-danger">
						<p>
							<b>You asked to remove the post media</b>, please confirm you want to remove it <hr />
							<span class="text-success">
								<i class="fa fa-check fa-fw"></i>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $_Oli->getUrlParam(3); ?>/confirmed/<?php echo $_Oli->getUrlParam(4); ?>/<?php echo $_Oli->getUrlParam(5); ?>">I want to remove it</a>
							</span> <br />
							<span class="text-danger">
								<i class="fa fa-times fa-fw"></i>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/posts/<?php echo $_Oli->getUrlParam(4); ?>/<?php echo $_Oli->getUrlParam(5); ?>">I refuse to remove it</a>
							</span>
						</p>
					</div>
				<?php } else if($resultCode == 'UNKNOWN_MEDIA') { ?>
					<div class="message message-danger">
						<p>You tried to act on a media that not exist</p>
					</div>
				<?php } else if($resultCode == 'NOT_YOUR_MEDIA') { ?>
					<div class="message message-danger">
						<p>You tried to act on a media that is not yours</p>
					</div>
				<?php } else if($resultCode == 'MEDIA_DELETED') { ?>
					<div class="message message-success">
						<p>The selected medias have been deleted</p>
					</div>
				<?php } else if($resultCode == 'UNKNOWN_POST') { ?>
					<div class="message message-danger">
						<p>You tried to act on a post that not exist</p>
					</div>
				<?php } else if($resultCode == 'MEDIA_REMOVED') { ?>
					<div class="message message-success">
						<p>The post media have been removed</p>
					</div>
				<?php } ?>
				
				<div class="message" id="script-message" style="display: none;">
					<p></p>
				</div>
				
				<div class="content-card">
					<?php if($_Oli->getUrlParam(2) == 'posts' AND !empty($_Oli->getUrlParam(3)) AND $_Media->isExistFileInfos(array('file_key' => $_Oli->getUrlParam(3))) AND $_Media->getFileInfos('owner', array('file_key' => $_Oli->getUrlParam(3))) == $_Oli->getAuthKeyOwner() AND $_Oli->isExistInfosMySQL('social_posts', array('media_key' => $_Oli->getUrlParam(3)))) { ?>
						<h3>Posts which use your media</h3>
						<p>
							See above every posts that used your media
						</p> <hr />
						<p>
							<span class="text-primary">
								<i class="fa fa-angle-left fa-fw"></i>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/">
									Go back to your media panel
								</a>
							</span>
						</p>
					<?php } else { ?>
						<?php $yourMedias = $_Media->getFileLines(array('owner' => $_Oli->getAuthKeyOwner()), true, true); ?>
						<h3>Your media</h3>
						<p>
							Manage all your media and see who has used your media in their posts
						</p>
						<?php if(!empty($yourMedias)) { ?> <hr />
							<p>
								<span class="text-primary">
									<i class="fa fa-check-square fa-fw"></i>
									<a href="#selectAll" class="selectAll">Select All</a>
								</span> <br />
								<span class="text-muted">
									<i class="fa fa-square-o fa-fw"></i>
									<a href="#unselectAll" class="unselectAll">Unselect All</a>
								</span> <br />
								<span class="text-danger">
									<i class="fa fa-trash fa-fw"></i>
									<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/delete/" class="deleteSelected">Delete Selected</a>
								</span>
							</p>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
			
			<div class="mainBar col-sm-9">
				<?php if($_Oli->getUrlParam(2) == 'posts' AND !empty($_Oli->getUrlParam(3)) AND $_Media->isExistFileInfos(array('file_key' => $_Oli->getUrlParam(3))) AND $_Media->getFileInfos('owner', array('file_key' => $_Oli->getUrlParam(3))) == $_Oli->getAuthKeyOwner() AND $_Oli->isExistInfosMySQL('social_posts', array('media_key' => $_Oli->getUrlParam(3)))) { ?>
					<?php $allPosts = $_Oli->getLinesMySQL('social_posts', array('media_key' => $_Oli->getUrlParam(3)), false, true); ?>
					<?php $countAllPosts = count($allPosts); ?>
					
					<?php $limit = 50; ?>
					<?php $totalPages = ceil($countAllPosts / $limit); ?>
					<?php $page = array_reverse($_Oli->getUrlParam('params'))[0] >= 1 ? (array_reverse($_Oli->getUrlParam('params'))[0] < $totalPages ? array_reverse($_Oli->getUrlParam('params'))[0] : $totalPages) : 1; ?>
					<?php $posts = array_slice(array_reverse($allPosts), ($page - 1) * $limit, $limit); ?>
					
					<?php if(!empty($posts)) { ?>
						<div class="tools">
							<p class="pull-right text-muted">
								Showing
								<?php if($page == 1) { ?>last<?php } ?>
								<?php echo count($posts); ?> posts
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
						
						<?php foreach($posts as $eachPost) { ?>
							<div class="post content-card" id="<?php echo $eachPost['id']; ?>">
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
										<a href="#" class="btn btn-link btn-xs disabled">
											<i class="fa fa-retweet fa-lg"></i>
											<b><?php echo $eachPost['reposts_count']; ?></b>
										</a>
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
										<a href="#" class="btn btn-link btn-xs disabled">
											<i class="fa fa-retweet fa-lg"></i>
											<b><?php echo $eachPost['reposts_count']; ?></b>
										</a>
									</p>
								</div>
							</div>
						<?php } ?>
					
						<div class="tools">
							<p class="pull-right text-muted">
								Showing
								<?php if($page == 1) { ?>last<?php } ?>
								<?php echo count($posts); ?> posts
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
					<?php } else { ?>
						<div class="post content-card text-center">
							<h3>Not any post to show!</h3>
							<p>
								I had to say "wtf", 'cause this isn't supporsed to happen!
							</p>
						</div>
					<?php } ?>
				<?php } else { ?>
					<?php if(!empty($yourMedias)) { ?>
						<?php $countMedias = count($yourMedias); ?>
						<div class="content-card">
							<p>
								<i class="fa fa-sort-numeric-desc fa-fw"></i> Sorted from newest to oldest requests <br />
								<span class="text-danger">
									<i class="fa fa-warning fa-fw"></i>
									Deleting one of your media will make it unavailable for any post that used it
								</span>
							</p>
							
							<table class="table table-hover">
								<thead>
									<tr>
										<th class="selector-menu"><i class="fa fa-check fa-fw"></i></th>
										<th><i class="fa fa-picture-o fa-fw"></i></th>
										<th colspan="3"></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach(array_reverse($yourMedias) as $eachMedia) { ?>
										<?php if($_Media->isExistFile($eachMedia['path_addon'] . $eachMedia['file_name'])) { ?>
											<tr id="<?php echo $eachMedia['id']; ?>">
												<?php if(!empty($selectedMedias) AND in_array($eachMedia['id'], $selectedMedias)) { ?>
													<td class="selector checked">
														<i class="fa fa-check-square fa-fw"></i>
													</td>
												<?php } else { ?>
													<td class="selector">
														<i class="fa fa-square-o fa-fw"></i>
													</td>
												<?php } ?>
												
												<td><img src="<?php echo $_Media->getUploadsUrl() . $eachMedia['path_addon'] . $eachMedia['file_name']; ?>" class="img-thumbnail preview" /></td>
												<td>
													<?php $postsUsingCount = $_Oli->isExistInfosMySQL('social_posts', array('media_key' => $eachMedia['file_key']), false, true); ?>
													<?php if($postsUsingCount > 0) { ?>
														<i class="fa fa-check fa-fw"></i>
														Used on <b><?php echo $postsUsingCount; ?> posts</b> <br />
														<span class="text-muted">
															<b><?php echo $_Oli->isExistInfosMySQL('social_posts', array('media_key' => $eachMedia['file_key'], 'owner' => $_Oli->getAuthKeyOwner()), false, true); ?> of them</b> are <b>yours</b>
														</span>
													<?php } else { ?>
														<span class="text-muted">
															<i class="fa fa-times fa-fw"></i>
															Isn't used on any post
														</span>
													<?php } ?>
												</td>
												<td>
													<a href="<?php echo $_Media->getUploadsUrl() . $eachMedia['path_addon'] . $eachMedia['file_name']; ?>" target="_blank" class="btn btn-success btn-xs">
														Open <i class="fa fa-external-link fa-fw"></i>
													</a>
												</td>
												<td>
													<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/posts/<?php echo $eachMedia['file_key']; ?>" class="btn btn-primary btn-xs">
														See posts <i class="fa fa-eye fa-fw"></i>
													</a>
												</td>
												<td>
													<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/delete/<?php echo $eachMedia['id']; ?>" class="btn btn-danger btn-xs">
														Delete <i class="fa fa-trash fa-fw"></i>
													</a>
												</td>
											</tr>
										<?php } else { ?>
											<?php $_Media->deleteFileInfos(array('id' => $eachMedia['id'])); ?>
											<?php $countMedias--; ?>
											
											<tr class="danger">
												<td></td>
												<td colspan="5">
													A media have been deleted: the file cannot be found and seems to have already been deleted
												</td>
											</tr>
										<?php } ?>
									<?php } ?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="3">
											<a href="#selectAll" class="selectAll btn btn-primary btn-xs">
												Select All <i class="fa fa-check-square fa-fw"></i>
											</a>
											<a href="#unselectAll" class="unselectAll btn btn-danger btn-xs">
												Unselect All <i class="fa fa-square-o fa-fw"></i>
											</a>
										</td>
										<td colspan="2"><?php echo $countMedias; ?> <small>media<?php if($countMedias > 1) { ?>s<?php } ?></small></td>
										<td>
											<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/delete/" class="deleteSelected btn btn-danger btn-xs">
												Selected <i class="fa fa-trash fa-fw"></i>
											</a>
										</td>
									</tr>
								</tfoot>
							</table>
						</div>
					<?php } else { ?>
						<div class="message text-danger">
							<h3>You haven't got any media uploaded</h3>
						</div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<?php $_Oli->loadEndHtmlFiles(); ?>

</body>
</html>