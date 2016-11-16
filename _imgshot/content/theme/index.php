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
		<h1><i class="fa fa-picture-o fa-fw"></i> <?php echo $_Oli->getOption('name'); ?></h1>
		<p>
			<?php echo $_Oli->getOption('description'); ?>
		</p>
	</div>
</div>

<div class="main">
	<div class="container">
		<h3>Qu'est-ce que <?php echo $_Oli->getOption('name'); ?> ?</h3>
		<p>
			<?php echo $_Oli->getOption('name'); ?> est un service d'hébergement d'images en ligne. <br />
			Cet hébergement en ligne est libre, anonyme et peu restreint. N'importe qui peut en mettre en ligne, qu'il soit connecté ou non au service.
		</p>
		<hr />
		
		<h3>Que peut-on faire avec les images ?</h3>
		<p>
			Les images, une fois mises en ligne, auront toutes un identifiant aléatoire et un lien qui pourront être utilisés pour partager l'image n'importe où. <br />
			Si vous êtes connectés lors de la mise en ligne, l'image vous sera attribué de façon à ce que vous puissiez la modifier ou la supprimer plus tard.
		</p>
		<hr />
		
		<h3>Comment le service va-t-il évoluer ?</h3>
		<p>
			<?php echo $_Oli->getOption('name'); ?> évolura continuellement pour s'adapter à son temps et continuera à recevoir un support complet. <br />
			Le projet évolura aussi selon le framework Oli qui est utilisé comme moteur du site.
		</p>
		<?php /*<hr />
		
		<h3>Avis des utilisateurs</h3>
		<blockquote>
			<p>Shot. Upload. Share.</p>
			<footer>Eli <i class="fa fa-heart-o fa-fw"></i> about <?php echo $_Oli->getOption('name'); ?></footer>
		</blockquote>
		<blockquote>
			<p>Take a Shot, Upload & Share.</p>
			<footer>Eli <i class="fa fa-heart-o fa-fw"></i> about <?php echo $_Oli->getOption('name'); ?></footer>
		</blockquote>*/ ?>
	</div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>