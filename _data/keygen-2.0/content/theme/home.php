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
			Une suggestion, un avis, un problème ? <br />
			<a href="https://twitter.com/intent/tweet?text=%40Matiboux%20%23KeyGen%20" target="_blank">Envoyez-nous un Tweet</a> !
		</div>
		<?php if(!getStatus()) { ?>
			<div class="error">
				Service indisponible
			</div>
		<?php } ?>
		<?php if(getUrlParam(2) == 'ERR' && getUrlParam(3) != '') { ?>
			<div class="error">
				<?php echo CheckError(getUrlParam(3)); ?>
			</div>
		<?php } else if(getUrlParam(2) != '') { ?>
			<div class="info">
				Cl&eacute; g&eacute;n&eacute;r&eacute;e : <?php echo getUrlParam(2); ?>
			</div>
		<?php } ?>
		<?php if(getStatus()) { ?>
			<form action="<?php echo getBaseUrl(); ?>generate.php" method="post">
		<?php } else { ?>
			<form action="" method="post">
		<?php } ?>
			<p><label for="genre">
				Choisissez un genre de cl&eacute; : <br />
				<input type="checkbox" name="genreNum" checked /> Num&eacute;rique (1) <br />
				<input type="checkbox" name="genreMin" checked /> Alphab&eacute;tique minuscule (a) <br />
				<input type="checkbox" name="genreMaj" checked /> Alphab&eacute;tique majuscule (A) <br />
				<input type="checkbox" name="genreSpe" disabled /> Sp&eacute;cial (@) <br />
			</label></p> <br />
			<p><label for="length">
				Choisissez la longueur de la cl&eacute; : <br />
				<input type="number" name="length" value="12" />
			</label></p> <br />
			<p><label for="multiCharacter">
				<input type="checkbox" name="multiCharacter" /> Les caract&egrave;res peuvent &ecirc;tre pr&eacute;sents plusieurs fois ?
			</label></p>
			<button type="submit">G&eacute;n&eacute;rer</button>
		</form>
		<p class="tools">&raquo; <a href="<?php echo getBaseUrl(); ?>terms-of-service">Conditions d'utilisation</a></p>
		<p class="tools">&raquo; <a href="http://matiboux.com/">Projet d&eacute;velop&eacute; par Matiboux</a></p>
	</div>
</body>
</html>