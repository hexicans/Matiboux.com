<!DOCTYPE html>
<html>
<head>

<?php include 'head.php'; ?>
<title>Home - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include 'header.php'; ?>

<div class="header">
	<div class="container">
		<h1>A propos</h1>
		<p>
			Quelques informations sur le projet
		</p>
	</div>
</div>

<div class="main">
	<div class="container">
		<h2><?php echo $_Oli->getOption('name'); ?></h2>
		<p>
			<?php echo $_Oli->getOption('name'); ?> est une plateforme d'hébergement de fichier. Tous les fichiers sont autorisés.
		</p>
	</div>
</div>

<?php include 'footer.php'; ?>

<?php $_Oli->loadEndHtmlFiles(); ?>

</body>
</html>