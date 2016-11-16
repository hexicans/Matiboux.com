<!DOCTYPE html>
<html>
<head>

<?php include THEMEPATH . 'head.php'; ?>
<title>About - <?php echo $_Oli->getSetting('name'); ?></title>

</head>
<body>

<?php include THEMEPATH . 'header.php'; ?>

<div class="main">
	<div class="container-fluid">
		<div class="row">
			<div class="mainBar col-sm-offset-2 col-sm-8">
				<div class="message message-danger text-center">
					<h3>This is an experimental preview</h3>
					<p>This project is still under developpement and is not finished yet</p>
				</div>
				
				<div class="content-card">
					<h3>About <?php echo $_Oli->getSetting('name'); ?></h3>
					<p>
						<?php echo $_Oli->getSetting('name'); ?> is a <i>social network</i> <b>created and developed by me</b>, <i><?php echo $_Oli->getSetting('owner'); ?></i>. <br />
						It has been <b>made entirely by me</b> with <b>Oli</b>, my <i>own PHP framework</i> which is open-source and available <a href="<?php echo $_Oli->getShortcutLink('oli'); ?>">here</a>. <br /> <br />
						
						I have also used these front-end frameworks for the website: <br />
						<span class="text-muted">They're all open source project, check their license for more infos</span>
						<ul>
							<li><a href="http://fontawesome.io/">Font Awesome</a></li>
							<li><a href="http://getbootstrap.com/">Bootstrap</a></li>
							<li><a href="http://jquery.com/">jQuery</a></li>
						</ul>
					</p>
				</div>
				<div class="content-card">
					<h3>Want to contact me?</h3>
					<p>
						You can contact me with: <br />
						- Email: <a href="mailto:<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'email', $_Oli->getSetting('owner')); ?>"><?php echo $_Oli->getAccountInfos('ACCOUNTS', 'email', $_Oli->getSetting('owner')); ?></a> <br />
						- Twitter: <a href="http://twitter.com/Matiboux">http://twitter.com/Matiboux</a> <br />
						You can also open a ticket on the <a href="<?php echo $_Oli->getUrlParam(0); ?>support/">support page</a> (you'll need an account) <br /> <br />
						
						You can use these to suggest me new features for my project. <b>Thanks for helping!</b>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $_Oli->loadEndHtmlFiles(); ?>

</body>
</html>