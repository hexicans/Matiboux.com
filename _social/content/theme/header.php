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
				<?php if($_Oli->verifyAuthKey()) { ?>
					<li <?php if($_Oli->getUrlParam(1) == 'notifications') echo 'class="active"'; ?>>
						<a href="<?php echo $_Oli->getUrlParam(0); ?>notifications/">
							<i class="fa fa-bell fa-fw"></i>
							<span class="hidden-xs">Notifications</span>
							
							<?php $newNotificationsCount = $_Oli->isExistInfosMySQL('social_notifications', array('username' => $_Oli->getAuthKeyOwner(), 'seen_date' => null), false); ?>
							<?php if($newNotificationsCount > 0 AND $_Oli->getUrlParam(1) != 'notifications') { ?>
								<span class="badge"><?php echo $newNotificationsCount; ?></span>
							<?php } ?>
						</a>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
							<i class="fa fa-gear fa-fw"></i> Settings <span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li>
								<a href="<?php echo $_Oli->getUrlParam(0); ?>settings/">General settings</a>
							</li>
							<li>
								<a href="<?php echo $_Oli->getUrlParam(0); ?>settings/media">Your media</a>
							</li>
							<li>
								<a href="<?php echo $_Oli->getUrlParam(0); ?>settings/sessions">Active sessions</a>
							</li>
							<li>
								<a href="<?php echo $_Oli->getUrlParam(0); ?>settings/requests">Pending requests</a>
							</li>
						</ul>
					</li>
					<li <?php if($_Oli->getUrlParam(1) == 'search') echo 'class="active"'; ?>>
						<a href="<?php echo $_Oli->getUrlParam(0); ?>search/"><i class="fa fa-search fa-fw"></i></a>
					</li>
				<?php } else { ?>
					<li <?php if($_Oli->getUrlParam(1) == 'about') echo 'class="active"'; ?>>
						<a href="<?php echo $_Oli->getUrlParam(0); ?>about/"><i class="fa fa-question-circle fa-fw"></i> About</a>
					</li>
				<?php } ?>
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
							<?php $avatarInfos = $_Avatar->getFileLines(array('name' => 'user_avatar', 'owner' => $_Oli->getAuthKeyOwner())); ?>
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