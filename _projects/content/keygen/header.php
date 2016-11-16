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
				<li <?php if(in_array($_Oli->getUrlParam(1), ['home', 'keygen'])) echo 'class="active"'; ?>>
					<a href="<?php echo $_Oli->getUrlParam(0); ?>keygen/">Keygen</a>
				</li>
				<li <?php if($_Oli->getUrlParam(1) == 'hash') echo 'class="active"'; ?>>
					<a href="<?php echo $_Oli->getUrlParam(0); ?>hash/">Hash</a>
				</li>
				<li <?php if($_Oli->getUrlParam(1) == 'settings') echo 'class="active"'; ?>>
					<a href="<?php echo $_Oli->getUrlParam(0); ?>settings/"><i class="fa fa-gear fa-fw"></i> Settings</a>
				</li>
				<li <?php if($_Oli->getUrlParam(1) == 'history') echo 'class="active"'; ?>>
					<a href="<?php echo $_Oli->getUrlParam(0); ?>history/"><i class="fa fa-history fa-fw"></i> History</a>
				</li>
			</ul>
			
			<ul class="nav navbar-nav navbar-right">
				<?php include COMMONPATH . 'right-navbar.php'; ?>
			</ul>
		</nav>
	</div>
</header>