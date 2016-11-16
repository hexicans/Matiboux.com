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
				<li<?php if(empty($_Oli->getUrlParam(1))) echo ' class="active"'; ?>>
					<a href="<?php echo $_Oli->getOption('url'); ?>">Home</a>
				</li>
				<li<?php if($_Oli->getUrlParam(1) == 'settings') echo ' class="active"'; ?>>
					<a href="<?php echo $_Oli->getOption('url'); ?>settings/">Paramètres</a>
				</li>
				<li<?php if($_Oli->getUrlParam(1) == 'my-links') echo ' class="active"'; ?>>
					<a href="<?php echo $_Oli->getOption('url'); ?>my-links/">Mes liens</a>
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