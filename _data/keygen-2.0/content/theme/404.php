<?php header ('Refresh: 3; URL=' . getBaseUrl()); ?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1" />
	<link rel="stylesheet" type="text/css" href="<?php echo getDataUrl(); ?>style.css" media="all">
	<title>Erreur 404 - <?php echo getName(); ?></title>
</head>
<body>
	<div id="container">
		<h1><a href="<?php echo getBaseUrl(); ?>"><?php echo getName(); ?></a></h1>
		<h2><?php echo getDescription(); ?></h2>
		<div class="tips">
			Une suggestion, un avis, un problème ? <br />
			<a href="https://twitter.com/intent/tweet?text=%40Matiboux%20%23KeyGen%20" target="_blank">Envoyez-nous un Tweet</a> !
		</div>
		<?php if(!getStatus()) { ?>
			<div class="error">
				Service indisponible
			</div>
		<?php } ?>
		<div class="error">
			Erreur 404 : Page non trouv&eacute;. <br /> 
			Redirection vers l'accueil dans trois secondes.
		</div>
		<div class="error_404">
			<h3>Erreur 404</h3>
			<p>
				<a href="<?php echo getBaseUrl(); ?>">Retourner sur l'accueil</a> ou attendez la redirection automatique.
			</p>
		</div>
		<p class="tools">&raquo; <a href="<?php echo getBaseUrl(); ?>">Accueil</a></p>
		<p class="tools">&raquo; <a href="http://matiboux.com/">Projet d&eacute;velop&eacute; par Matiboux</a></p>
	</div>
</body>
</html>