<footer class="footer">
	<?php /*<div class="social">*/ ?>
		<?php /*<li><a href="https://twitter.com/Matiboux" class="fa fa-twitter icon"><span class="label">Twitter</span></a></li> */ ?>
		<?php /*<li><a href="https://www.facebook.com/pages/Matiboux/186196714888132" class="fa fa-facebook icon"><span class="label">Facebook</span></a></li>*/ ?>
		<?php /*<li><a href="https://plus.google.com/110660516774094928388" class="fa fa-google-plus icon"><span class="label">Google+</span></a></li>*/ ?>
		<?php /*<li><a href="http://steamcommunity.com/id/matiboux/" class="fa fa-steam icon"><span class="label">Steam</span></a></li>*/ ?>
		<?php /*<li><a href="https://www.youtube.com/matiboux/" class="fa fa-youtube icon"><span class="label">YouTube</span></a></li>*/ ?>
		<?php /*<li><a href="http://www.twitch.tv/matiboux" class="fa fa-twitch icon"><span class="label">Twitch</span></a></li>*/ ?>
	<?php /*</div>*/ ?>
	
	<div class="copyright">
		<li>
			<i class="fa fa-copyright"></i>
			<a href="<?php echo $_Oli->getAccountInfos('INFOS', 'website', array('username' => $_Oli->getOption('owner'))); ?>"><?php echo $_Oli->getOption('owner'); ?></a>
			- <?php echo date('Y', strtotime($_Oli->getOption('creation_date'))); ?>
		</li>
		<li>
			Version <?php echo $_Oli->getOption('version'); ?>
		</li>
	</div>
</footer>

<?php $_Oli->loadEndHtmlFiles(); ?>

<!-- Script executed with Oli PHP Framework in <?php echo $_Oli->getExecuteDelay() * 1000; ?> ms -->
<!-- Request executed with Oli PHP Framework in <?php echo $_Oli->getExecuteDelay(true) * 1000; ?> ms -->