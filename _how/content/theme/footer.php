<li>
	<b><i class="fa fa-copyright fa-fw"></i> <?php echo $_Oli->getOption('owner'); ?> 2016</b>
</li>
<?php /*<li>
	<i class="fa fa-angle-left fa-fw"></i>
	<a href="<?php echo $_Oli->getShortcutLink('home'); ?>">Retour à l'accueil</a>
</li>*/ ?>
<li>
	<i class="fa fa-twitter fa-fw"></i>
	<a href="<?php echo $_Oli->getAccountInfos('INFOS', 'twitter_profile', array('username' => $_Oli->getOption('owner'))); ?>">@<?php echo str_replace('https://twitter.com/', '', $_Oli->getAccountInfos('INFOS', 'twitter_profile', array('username' => $_Oli->getOption('owner')))); ?></a>
</li>