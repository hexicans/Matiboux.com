<?php
if(!$_Oli->verifyAuthKey()
OR $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) < $_Oli->translateUserRight('USER'))
	header('Location: ' . $_Oli->getShortcutLink('login'));
?>

<!DOCTYPE html>
<html>
<head>

<?php include 'head.php'; ?>
<title>Manager - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include 'header.php'; ?>

<div class="header">
	<div class="container">
		<h1><i class="fa fa-line-chart fa-fw"></i> Vue d'ensemble</h1>
		<p>
			Vue d'ensemble, statistiques à propos de votre compte
		</p>
	</div>
</div>

<div class="message message-info">
	<div class="container">
		<h2>Service de gestion encore en développement</h2>
	</div>
</div>

<?php $yourFiles = $_Upload->getFileLines(array('owner' => $_Oli->getAuthKeyOwner()), true, true); ?>
<div class="main">
	<div class="container">
		<h1>Statistiques globales</h1>
		<div class="data-box col-sm-4 col-xs-6">
			<h2><?php echo count($yourFiles); ?></h2>
			<p>fichier uploadés</p>
		</div>
		<div class="data-box col-sm-4 col-xs-6">
			<h2><?php echo count($yourFiles); ?></h2>
			<p>
				fichiers publics <br />
				<span class="text-danger">Confidentialité des fichiers non disponible pour le moment</span>
			</p>
		</div>
		<div class="data-box col-sm-4 col-xs-6">
			<h2>X</h2>
			<p>
				fichiers privés <br />
				<span class="text-danger">Confidentialité des fichiers non disponible pour le moment</span>
			</p>
		</div>
		<div class="data-box col-sm-6 col-xs-6">
			<?php $totalViews = 0; ?>
			<?php foreach($yourFiles as $eachFile) { ?>
				<?php $totalViews = $totalViews + $eachFile['views_counter']; ?>
			<?php } ?>
			<h2><?php echo $totalViews; ?></h2>
			<p>total de vues</p>
		</div>
		<div class="data-box col-sm-6 col-xs-12">
			<?php $totalDownloads = 0; ?>
			<?php foreach($yourFiles as $eachFile) { ?>
				<?php $totalDownloads = $totalDownloads + $eachFile['downloads_counter']; ?>
			<?php } ?>
			<h2><?php echo $totalDownloads; ?></h2>
			<p>total de téléchargements</p>
		</div>
	</div>
</div>

<?php include 'footer.php'; ?>

<?php $_Oli->loadEndHtmlFiles(); ?>

</body>
</html>