<?php
if(getUrlParam(2) == '' || getUrlParam(3) == '') {
	header('Refresh: 3; url=' . getBaseUrl());
}
?>


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
		<h1><a href="<?php echo getBaseUrl(); ?>"><?php echo getName(); ?></a></h1>
		<h2><?php echo getDescription(); ?></h2>
		<div class="tips">
			Une suggestion, un avis, un probl&egrave;me ? <a href="https://twitter.com/intent/tweet?text=%40Matiboux%20%23ImgShot%20" target="_blank">Envoyez-nous un Tweet</a> !
		</div>
		<?php if(!getStatus()) { ?>
			<div class="error">
				Service indisponible
			</div>
		<?php } ?>
		<?php if(getUrlParam(2) == '' && getUrlParam(3) == '') { ?>
			<div class="error">
				Aucun param&egrave;tre indiqu&eacute;.
				Redirection vers l'accueil dans 3 secondes.
			</div>
		<?php } else if(getUrlParam(2) == '' || getUrlParam(3) == '') { ?>
			<div class="error">
				Un des param&egrave;tre est manquant.
				Redirection vers l'accueil dans 3 secondes.
			</div>
		<?php } ?>
		<?php if(getUrlParam(2) != '' || getUrlParam(3) != '') { ?>
			<div class="view">
				<h3><?php echo getUrlParam(3); ?></h3>
				<a href="<?php echo getMediaUrl() . getUrlParam(2) . '/' . getUrlParam(3); ?>">
					<img src="<?php echo getMediaUrl() . getUrlParam(2) . '/' . getUrlParam(3); ?>" alt="<?php echo getUrlParam(3); ?>" />
				</a>
				<p>
					Cliquez sur l'image pour la voir en taille r&eacute;elle.
				</p>
			</div>
		<?php } else { ?>
			<div class="view">
				<h3>Image non trouv&eacute;e</h3>
			</div>
		<?php } ?>
		<p class="tools">&raquo; <a href="<?php echo getBaseUrl() ?>terms-of-service">Conditions d'utilisation</a></p>
		<p class="tools">&raquo; <a href="http://matiboux.com/">Projet d&eacute;velop&eacute; par Matiboux</a></p>
	</div>
</body>
</html>