<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1" />
	<link rel="stylesheet" type="text/css" href="<?php echo getDataUrl(); ?>style.css" media="all">
	<title>Home - <?php echo getName(); ?></title>
</head>
<body>
	<div id="container">
		<h1><a href="<?php echo getBaseUrl() ?>"><?php echo getName(); ?></a></h1>
		<h2><?php echo getDescription(); ?></h2>
		<div class="tips">
			Une suggestion, un avis, un problème ? <a href="https://twitter.com/intent/tweet?text=%40Matiboux%20%23ImgShot%20" target="_blank">Envoyez-nous un Tweet</a> !
		</div>
		<div class="tips">
			Taille maximale de l'image : 4 Mo <br/>
			Formats autoris&eacute;s : jpg, jpeg, png, gif. <br /> <br />
			Si le transfert est validé, vous serez ensuite redirigé sur votre image.
		</div>
		<?php if(!getStatus()) { ?>
			<div class="error">
				Service indisponible
			</div>
		<?php } ?>
		<?php if(getUrlParam(2) != '') { ?>
			<div class="error">
				<?php echo CheckError(getUrlParam(2)); ?>
			</div>
		<?php } ?>
		<?php if(getStatus()) { ?>
			<form action="<?php echo getBaseUrl() ?>upload.php" method="post" enctype="multipart/form-data">
		<?php } else { ?>
			<form action="" method="post">
		<?php } ?>
			<p><label for="image">
				Choisissez une image : <br />
				<input type="file" name="image" />
				<input type="hidden" name="lang" value="fr" />
			</label></p>
			<button type="submit">Envoyer</button>
		</form>
		<p class="tools">&raquo; <a href="<?php echo getBaseUrl() ?>terms-of-service">Conditions d'utilisation</a></p>
		<p class="tools">&raquo; <a href="http://matiboux.com/">Projet d&eacute;velop&eacute; par Matiboux</a></p>
	</div>
</body>
</html>