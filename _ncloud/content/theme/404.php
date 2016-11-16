<!DOCTYPE html>
<html>
<head>

<?php include 'head.php'; ?>
<title><?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include 'header.php'; ?>

<div class="header">
	<div class="container">
		<h1>Erreur HTTP 404</h1>
	</div>
</div>

<div class="main">
	<div class="container">
		<h2>La page demandé n'a pas été trouvée</h2>
		<p>
			Vérifiez le lien et réessayez. <br /> <br />
			
			Si vous êtes arrivées ici grâce à un lien ou après avoir validé un formulaire, il est possible qu'il sagissent d'un bug. <br />
			Si c'est le cas, merci de le signaler via la <a href="<?php echo $_Oli->getOption('name'); ?>bug-report">page de signalement</a>.
		</p>
	</div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>