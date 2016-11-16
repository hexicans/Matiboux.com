<footer class="footer">
	<div class="social">
		<li><a href="https://twitter.com/GroupeNatrox" class="fa fa-twitter icon"><span class="label">Twitter</span></a></li>
	</div>
	
	<div class="copyright">
		<li>
			<i class="fa fa-copyright"></i>
			<a href="http://natrox.net/">Natrox</a>
			- <?php echo date('Y', strtotime($_Oli->getOption('creation_date'))); ?>
		</li>
		<li>
			Développeur :
			<a href="<?php echo $_Oli->getAccountInfos('INFOS', 'website', array('username' => $_Oli->getOption('owner'))); ?>"><?php echo $_Oli->getOption('owner'); ?></a>
		</li>
		<li>
			Version <?php echo $_Oli->getOption('version'); ?>
		</li>
	</div>
</footer>

<?php $_Oli->loadEndHtmlFiles(); ?>

<!-- Script executed with Oli PHP Framework in <?php echo $_Oli->getExecuteDelay() * 1000; ?> ms -->
<!-- Request executed with Oli PHP Framework in <?php echo $_Oli->getExecuteDelay(true) * 1000; ?> ms -->