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
				<?php /*<li <?php if(empty($_Oli->getUrlParam(1))) echo 'class="active"'; ?>>
					<a href="<?php echo $_Oli->getUrlParam(0); ?>"><i class="fa fa-home fa-fw"></i></a>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
						<i class="fa fa-gear fa-fw"></i> Settings <span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li>
							<a href="<?php echo $_Oli->getUrlParam(0); ?>settings/account">Your account</a>
						</li>
						<li>
							<a href="<?php echo $_Oli->getUrlParam(0); ?>settings/sessions">Active sessions</a>
						</li>
						<li>
							<a href="<?php echo $_Oli->getUrlParam(0); ?>settings/requests">Pending requests</a>
						</li>
					</ul>
				</li>*/ ?>
			</ul>
			
			<ul class="nav navbar-nav navbar-right">
				<?php include COMMONPATH . 'right-navbar.php'; ?>
			</ul>
		</nav>
	</div>
</header>