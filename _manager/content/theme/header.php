<header class="navbar navbar-static-top">
	<div class="container">
		<div class="navbar-header">
			<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a href="<?php echo $_Oli->getOption('url'); ?>" class="navbar-brand"><?php echo $_Oli->getOption('name'); ?></a>
		</div>
		
		<nav class="collapse navbar-collapse bs-navbar-collapse">
			<ul class="nav navbar-nav navbar-left">
				<li <?php if(empty($_Oli->getUrlParam(1))) echo 'class="active"'; ?>>
					<a href="<?php echo $_Oli->getOption('url'); ?>">
						<i class="fa fa-home fa-fw"></i>
					</a>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
						Vos informations <span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li>
							<a href="<?php echo $_Oli->getOption('url'); ?>main-infos/">Infos principales</a>
						</li>
						<li>
							<a href="<?php echo $_Oli->getOption('url'); ?>more-infos/">Infos supplémentaires</a>
						</li>
						<li>
							<a href="<?php echo $_Oli->getOption('url'); ?>permissions/">Vos permissions</a>
						</li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
						Plus <span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li>
							<a href="<?php echo $_Oli->getOption('url'); ?>personnal-data/">Vos données</a>
						</li>
						<li>
							<a href="<?php echo $_Oli->getOption('url'); ?>sessions/">Sessions actives</a>
						</li>
						<li>
							<a href="<?php echo $_Oli->getOption('url'); ?>requests/">Requêtes actives</a>
						</li>
					</ul>
				</li>
				<li class="dropdown">
					<?php if($_Oli->verifyAuthKey() AND $_Oli->getUrlParam(1) != 'tickets') { ?>
						<?php if($_Oli->getUrlParam(2) != 'admin' AND $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
							<?php $getTickets = $_Oli->getLinesMySQL('manager_tickets', [], true, true); ?>
							<?php if(!empty($getTickets)) { ?>
								<?php $adminTicketsNotifs = count($getTickets); ?>
								<?php foreach($getTickets as $eachTicket) { ?>
									<?php if($eachTicket['last_message_infos']['username'] != $eachTicket['owner'] OR $eachTicket['owner'] == $_Oli->getAuthKeyOwner()) { ?>
										<?php $adminTicketsNotifs--; ?>
									<?php } ?>
								<?php } ?>
							<?php } ?>
						<?php } else { ?>
							<?php $getTickets = $_Oli->getLinesMySQL('manager_tickets', array('owner' => $_Oli->getAuthKeyOwner()), true, true); ?>
							<?php if(!empty($getTickets)) { ?>
								<?php $yourTicketsNotifs = count($getTickets); ?>
								<?php foreach($getTickets as $eachTicket) { ?>
									<?php if($eachTicket['last_message_infos']['username'] == $eachTicket['owner']) { ?>
										<?php $yourTicketsNotifs--; ?>
									<?php } ?>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					<?php } ?>
					
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
						Support
						<?php if($adminTicketsNotifs > 0 AND $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
							- <span class="label label-danger"><?php echo $adminTicketsNotifs; ?></span>
						<?php } else if($yourTicketsNotifs > 0) { ?>
							- <span class="label label-primary"><?php echo $yourTicketsNotifs; ?></span>
						<?php } ?>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li>
							<a href="<?php echo $_Oli->getOption('url'); ?>tickets/">
								Vos tickets
								<?php if($yourTicketsNotifs > 0) { ?>
									<span class="badge"><?php echo $yourTicketsNotifs; ?></span>
								<?php } ?>
							</a>
						</li>
						<?php if($adminTicketsNotifs > 0 AND $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
							<li>
								<a href="<?php echo $_Oli->getOption('url'); ?>tickets/admin/">
									<i class="fa fa-warning fa-fw"></i> Gestion des tickets
									<span class="badge"><?php echo $adminTicketsNotifs; ?></span>
								</a>
							</li>
						<?php } ?>
						<li class="divider"></li>
						<li>
							<a href="<?php echo $_Oli->getShortcutLink('home'); ?>contact/">Contacter l'équipe</a>
						</li>
					</ul>
				</li>
			</ul>
			
			<ul class="nav navbar-nav navbar-right">
				<li>
					<a href="<?php if($_Oli->verifyAuthKey()) { ?><?php echo $_Oli->getShortcutLink('manager'); ?>main-infos/<?php } else { ?>#<?php } ?>">
						<?php echo strtoupper($_Oli->getCurrentUserLanguage()); ?>
					</a>
				</li>
				<?php if($_Oli->verifyAuthKey()) { ?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
							<?php echo $_Oli->getAuthKeyOwner(); ?> <span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li>
								<a href="<?php echo $_Oli->getShortcutLink('manager'); ?>user/<?php echo $_Oli->getAuthKeyOwner(); ?>"><i class="fa fa-user fa-fw"></i> Votre profil</a>
							</li>
							<li>
								<a href="<?php echo $_Oli->getShortcutLink('manager'); ?>"><i class="fa fa-gear fa-fw"></i> Manager</a>
							</li>
							<li>
								<a href="<?php echo $_Oli->getShortcutLink('portal'); ?>"><i class="fa fa-plus fa-fw"></i> Portail des projets</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="<?php echo $_Oli->getShortcutLink('home'); ?>">A propos de Matiboux</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="<?php echo $_Oli->getShortcutLink('login'); ?>/logout"><i class="fa fa-sign-out fa-fw"></i> Déconnexion</a>
							</li>
						</ul>
					</li>
				<?php } else { ?>
					<li>
						<a href="<?php echo $_Oli->getShortcutLink('login'); ?>"><i class="fa fa-sign-in fa-fw"></i> Connexion</a>
					</li>
				<?php } ?>
			</ul>
		</nav>
	</div>
</header>