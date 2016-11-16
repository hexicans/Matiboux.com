<header class="navbar navbar-static-top">
	<div class="container">
		<div class="navbar-header">
			<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a href="<?php echo $_Oli->getUrlParam(0); ?>" class="navbar-brand"><?php echo $_Oli->getSetting('name'); ?></a>
		</div>
		
		<nav class="collapse navbar-collapse bs-navbar-collapse">
			<ul class="nav navbar-nav navbar-left">
				<li <?php if($_Oli->getUrlParam(1) == 'home') echo 'class="active"'; ?>>
					<a href="<?php echo $_Oli->getUrlParam(0); ?>"><i class="fa fa-home fa-fw"></i></a>
				</li>
				<li <?php if($_Oli->getUrlParam(1) == 'admin') echo 'class="active"'; ?>>
					<a href="<?php echo $_Oli->getUrlParam(0); ?>admin/"><i class="fa fa-cog fa-fw"></i></a>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
						<i class="fa fa-user fa-fw"></i> Manage <span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<?php if(in_array($_Oli->getUrlParam(1), ['admin/log-as', 'admin/manage-user', 'admin/manage-posts', 'admin/manage-medias', 'admin/manage-notifications', 'admin/manage-sessions', 'admin/manage-requests', 'admin/manage-history'])) $search = $_Oli->getUrlParam(2); ?>
						<li>
							<a href="<?php echo $_Oli->getUrlParam(0); ?>admin/manage-user/<?php echo $search; ?>">
								<i class="fa fa-edit fa-fw"></i> Edit user infos
							</a>
						</li>
						<li>
							<a href="<?php echo $_Oli->getUrlParam(0); ?>admin/manage-posts/<?php echo $search; ?>">
								<i class="fa fa-file-text-o fa-fw"></i> Manage user posts
							</a>
						</li>
						<li>
							<a href="<?php echo $_Oli->getUrlParam(0); ?>admin/manage-notifications/<?php echo $search; ?>">
								<i class="fa fa-bell fa-fw"></i> Manage user notifications
							</a>
						</li>
						<?php /*<li>
							<a href="<?php echo $_Oli->getUrlParam(0); ?>admin/manage-medias/<?php echo $search; ?>">
								<i class="fa fa-user fa-fw"></i> Manage medias
							</a>
						</li>
						<li>
							<a href="<?php echo $_Oli->getUrlParam(0); ?>admin/manage-sessions/<?php echo $search; ?>">
								<i class="fa fa-user fa-fw"></i> Manage user sessions
							</a>
						</li>
						<li>
							<a href="<?php echo $_Oli->getUrlParam(0); ?>admin/manage-requests/<?php echo $search; ?>">
								<i class="fa fa-envelope fa-fw"></i> Manage user requests
							</a>
						</li>
						<li>
							<a href="<?php echo $_Oli->getUrlParam(0); ?>admin/manage-history/<?php echo $search; ?>">
								<i class="fa fa-search fa-fw"></i> Check user search history
							</a>
						</li>*/ ?>
						<li>
							<a href="<?php echo $_Oli->getUrlParam(0); ?>admin/log-as/<?php echo $search; ?>">
								<i class="fa fa-sign-in fa-fw"></i> Log as user
							</a>
						</li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
						<i class="fa fa-exclamation fa-fw"></i> Announcements <span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li>
							<a href="<?php echo $_Oli->getUrlParam(0); ?>admin/add-announcement/">
								<i class="fa fa-plus fa-fw"></i> Create an announce
							</a>
						</li>
						<li>
							<a href="<?php echo $_Oli->getUrlParam(0); ?>admin/manage-notifications/all">
								<i class="fa fa-bell fa-fw"></i> Manage announcement notifications
							</a>
						</li>
						<li>
							<a href="<?php echo $_Oli->getUrlParam(0); ?>admin/manage-posts/all">
								<i class="fa fa-file-text-o fa-fw"></i> Manage announcement posts
							</a>
						</li>
					</ul>
				</li>
			</ul>
			
			<ul class="nav navbar-nav navbar-right">
				<?php if($_Oli->verifyAuthKey()) { ?>
					<?php if($_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
						<?php $getTickets = $_Oli->getLinesMySQL('support_tickets', [], true, true); ?>
						<?php if(!empty($getTickets)) { ?>
							<?php $adminTicketsNotifs = count($getTickets); ?>
							<?php foreach($getTickets as $eachTicket) { ?>
								<?php if($eachTicket['last_message_infos']['username'] != $eachTicket['owner'] OR $eachTicket['owner'] == $_Oli->getAuthKeyOwner()) { ?>
									<?php $adminTicketsNotifs--; ?>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					<?php } ?>
					<?php $getTickets = $_Oli->getLinesMySQL('support_tickets', array('owner' => $_Oli->getAuthKeyOwner()), true, true); ?>
					<?php if(!empty($getTickets)) { ?>
						<?php $yourTicketsNotifs = count($getTickets); ?>
						<?php foreach($getTickets as $eachTicket) { ?>
							<?php if($eachTicket['last_message_infos']['username'] == $eachTicket['owner']) { ?>
								<?php $yourTicketsNotifs--; ?>
							<?php } ?>
						<?php } ?>
					<?php } ?>
					
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
							<?php $avatarInfos = $_Avatar->getFileLines(array('name' => 'user_avatar', 'file_key' => $_Oli->getAuthKeyOwner())); ?>
							<img src="<?php echo (!empty($avatarInfos)) ? $_Avatar->getUploadsUrl() . $avatarInfos['path_addon'] . $avatarInfos['file_name'] : $_Gravatar->getGravatar($_Oli->getAccountInfos('ACCOUNTS', 'email', array('username' => $_Oli->getAuthKeyOwner())), 40); ?>" class="img-rounded avatar" alt="<?php echo $_Oli->getAuthKeyOwner(); ?>" />
							<span><?php echo $_Oli->getAuthKeyOwner(); ?></span>
							<?php if($_Oli->getUrlParam(1) != 'support/tickets') { ?>
								<?php if($adminTicketsNotifs > 0 AND $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
									- <span class="label label-danger"><?php echo $adminTicketsNotifs; ?></span>
								<?php } else if($yourTicketsNotifs > 0) { ?>
									- <span class="label label-primary"><?php echo $yourTicketsNotifs; ?></span>
								<?php } ?>
							<?php } ?>
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li>
								<a href="<?php echo $_Oli->getUrlParam(0); ?>user/<?php echo $_Oli->getAuthKeyOwner(); ?>/"><i class="fa fa-user fa-fw"></i> Your profile</a>
							</li>
							<li>
								<a href="<?php echo $_Oli->getUrlParam(0); ?>about/"><i class="fa fa-question-circle fa-fw"></i> About</a>
							</li>
							<li>
								<a href="<?php echo $_Oli->getUrlParam(0); ?>support/">
									<i class="fa fa-life-ring fa-fw"></i> Support
									<?php if($_Oli->getUrlParam(1) != 'support/tickets' AND $yourTicketsNotifs > 0) { ?>
										- <span class="label label-primary"><?php echo $yourTicketsNotifs; ?></span>
									<?php } ?>
								</a>
							</li>
							<?php if($_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('MODERATOR')) { ?>
								<li class="divider"></li>
								<?php if($_Oli->getUrlParam(1) != 'support/tickets' AND $adminTicketsNotifs > 0 AND $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
									<li>
										<a href="<?php echo $_Oli->getUrlParam(0); ?>support/tickets/admin/">
											<i class="fa fa-warning fa-fw"></i> Ticket management
											- <span class="label label-danger"><?php echo $adminTicketsNotifs; ?></span>
										</a>
									</li>
								<?php } ?>
								<li>
									<a href="<?php echo $_Oli->getUrlParam(0); ?>admin/"><i class="fa fa-cog fa-fw"></i> Admin panel</a>
								</li>
							<?php } ?>
							<li class="divider"></li>
							<li>
								<a href="<?php echo $_Oli->getShortcutLink('login'); ?>/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
							</li>
						</ul>
					</li>
				<?php } else { ?>
					<li>
						<a href="<?php echo $_Oli->getShortcutLink('login'); ?>"><i class="fa fa-sign-in fa-fw"></i> Login</a>
					</li>
				<?php } ?>
			</ul>
		</nav>
	</div>
</header>