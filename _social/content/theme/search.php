<!DOCTYPE html>
<html>
<head>

<?php include THEMEPATH . 'head.php'; ?>
<?php $_Oli->loadLocalScript('js/actions.js', false); ?>
<?php $_Oli->loadLocalScript('js/search.js', false); ?>
<?php $_Oli->loadLocalScript('js/media.js', false); ?>
<title>Search - <?php echo $_Oli->getSetting('name'); ?></title>

</head>
<body>

<?php include THEMEPATH . 'header.php'; ?>

<div class="bigMedia" style="display: none;"><img /></div>
<div class="main">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-offset-2 col-sm-8">
				<div class="message" id="message" style="display: none;"></div>
				
				<h3 class="text-center"><i class="fa fa-search fa-fw"></i> Search</h3>
				<div class="search content-card">
					<form action="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/" class="form form-horizontal" method="post" enctype="multipart/form-data">
						<div class="form-group">
							<div class="col-xs-12">
								<div class="input-group">
									<input type="text" class="form-control" name="search" placeholder="Search" value="<?php echo $_Oli->getUrlParam(2); ?>" />
									<a href="#" class="input-group-addon btn btn-default submit">
										<i class="fa fa-search fa-fw"></i>
									</a>
								</div>
								<p class="help-block">
									<span class="text-info">Here you can search for an username, a post or a hashtag</span>
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
				
				<?php foreach($_Oli->getLinesMySQL('social_posts', null, false, true) as $eachPost) { ?>
					<?php if(stripos($eachPost['content'], $_Oli->getUrlParam(2)) !== false OR stripos($eachPost['owner'], substr($_Oli->getUrlParam(2), 0, 1) == '@' ? substr($_Oli->getUrlParam(2), 1) : $_Oli->getUrlParam(2)) !== false OR stripos($_Oli->getAccountInfos('INFOS', 'name', $eachPost['owner']), substr($_Oli->getUrlParam(2), 0, 1) == '@' ? substr($_Oli->getUrlParam(2), 1) : $_Oli->getUrlParam(2)) !== false) { ?>
						<?php if(!empty($eachPost['content'])) $allPosts[$eachPost['id']] = $eachPost; ?>
					<?php } ?>
				<?php } ?>
				<?php $countAllPosts = count($allPosts); ?>
				
				<?php $limit = 50; ?>
				<?php $totalPages = ceil($countAllPosts / $limit); ?>
				<?php $page = array_reverse($_Oli->getUrlParam('params'))[0] >= 1 ? (array_reverse($_Oli->getUrlParam('params'))[0] < $totalPages ? array_reverse($_Oli->getUrlParam('params'))[0] : $totalPages) : 1; ?>
				
				<?php if(!empty($users) OR !empty($allPosts)) { ?>
					<div class="<?php if(empty($allPosts)) { ?>col-sm-offset-3 col-sm-6<?php } else { ?>col-sm-4<?php } ?>">
						<h4 class="text-center">Results for <b><?php echo $_Oli->getUrlParam(2); ?></b></h4>
						<div class="content-card">
							<h4><i class="fa fa-search fa-fw"></i> <?php echo $countUsers + $countAllPosts; ?> results found!</h4>
							<p>
								You have found: <br />
								<i class="fa fa-angle-right fa-fw"></i> <?php echo $countUsers; ?> <small>user<?php if($countUsers > 1) { ?>s<?php } ?></small> <br />
								<i class="fa fa-angle-right fa-fw"></i> <?php echo $countAllPosts; ?> <small>post<?php if($countAllPosts > 1) { ?>s<?php } ?></small>
							</p>
						</div>
					
						<?php if(!empty($users)) { ?>
							<?php foreach(array_reverse($users) as $eachResult) { ?>
								<div class="user content-card">
									<span class="pull-right text-right hidden-sm">
										<p>
											<b><?php echo $_Oli->isExistInfosMySQL('social_posts', array('owner' => $eachResult), false) ?: 0; ?></b> posts <br />
											<b><?php echo $_Oli->isExistInfosMySQL('social_follows', array('follows' => $eachResult), false) ?: 0; ?></b> followers
										</p>
									</span>
									<div class="header">
										<?php $avatarInfos = $_Avatar->getFileLines(array('name' => 'user_avatar', 'owner' => $eachResult)); ?>
										<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $eachResult; ?>" class="header-left">
											<img src="<?php echo (!empty($avatarInfos)) ? $_Avatar->getUploadsUrl() . $avatarInfos['path_addon'] . $avatarInfos['file_name'] : $_Gravatar->getGravatar($_Oli->getAccountInfos('ACCOUNTS', 'email', $eachResult), 100); ?>" class="avatar img-rounded" alt="<?php echo $eachResult; ?>" />
										</a>
										<div class="header-body">
											<h4 class="heading">
												<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $eachResult; ?>">
													<?php echo $_Oli->getAccountInfos('INFOS', 'name', $eachResult) ?: $eachResult; ?>
												</a>
											</h4>
											<small>@<?php echo $eachResult; ?></small>
										</div>
									</div>
								</div>
							<?php } ?>
						<?php } else if(strlen($_Oli->getUrlParam(2)) < 3) { ?>
							<div class="message message-warning text-center">
								<p>To search users, your search request have to be <b>at least 3 characters long</b></p>
							</div>
						<?php } ?>
					</div>
				
					<?php if(!empty($allPosts)) { ?>
						<div class="col-sm-8">
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
											<a href="<?php if($eachPost['owner'] != $_Oli->getAuthKeyOwner()) { ?><?php echo $_Oli->getUrlParam(0); ?>toggle-repost.php<?php } else { ?>#<?php } ?>" class="btn btn-link btn-xs <?php if($eachPost['owner'] != $_Oli->getAuthKeyOwner()) { ?>action-repost<?php } else { ?>disabled<?php } ?> <?php if($_Oli->isExistInfosMySQL('social_posts', array('owner' => $_Oli->getAuthKeyOwner(), 'content' => '', 'quote_from' => $eachPost['id']), false)) { ?>active<?php } ?>">
												<i class="fa fa-retweet fa-lg"></i>
												<b><?php echo $_Oli->isExistInfosMySQL('social_posts', array('content' => '', 'quote_from' => $eachPost['id']), false) ?: 0; ?></b>
											</a>
											<?php if($eachPost['owner'] == $_Oli->getAuthKeyOwner()) { ?>
												<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/delete/<?php echo $eachPost['id']; ?>/<?php echo $page; ?>" class="btn btn-link btn-xs">
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
												<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/delete/<?php echo $eachPost['id']; ?>/<?php echo $page; ?>" class="btn btn-link btn-xs">
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