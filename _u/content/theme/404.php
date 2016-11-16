<?php
if(!$_Oli->isExistInfosMySQL('url_shortener_list', array('link_key' => $_Oli->getUrlParam(1)))) {
	$resultCode = 'UNKNOWN_SHOTENED_LINK';
	include THEMEPATH . 'index.php';
}
else if(($_Oli->verifyAuthKey() AND !$_Oli->getInfosMySQL('url_shortener_preferences', 'links_transparency', array('username' => $_Oli->getAuthKeyOwner())) AND (!$_Oli->getInfosMySQL('url_shortener_list', 'sensitive_link', array('link_key' => $_Oli->getUrlParam(1))) OR !$_Oli->getInfosMySQL('url_shortener_preferences', 'inform_sensitive_link', array('username' => $_Oli->getAuthKeyOwner())))) OR $_Oli->getUrlParam(2) == 'confirmed') {
	$_Oli->updateInfosMySQL('url_shortener_list', array('click_count' => $_Oli->getInfosMySQL('url_shortener_list', 'click_count', array('link_key' => $_Oli->getUrlParam(1))) + 1), array('link_key' => $_Oli->getUrlParam(1)));
	echo header('Location: ' . $_Oli->getInfosMySQL('url_shortener_list', 'link', array('link_key' => $_Oli->getUrlParam(1))));
}
else { ?>

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
		<h1><i class="fa fa-file-text-o fa-fw"></i> Redirection</h1>
		<p>
			Confirmation de l'utilisateur
		</p>
	</div>
</div>

<div class="main">
	<div class="container">
		<h1>Souhaitez-vous continuer la redirection ?</h1>
		<p>
			Ce lien raccourci redirige vers :
			<span class="label label-<?php echo ($_Oli->getInfosMySQL('url_shortener_list', 'sensitive_link', array('link_key' => $_Oli->getUrlParam(1)))) ? 'danger' : 'primary'; ?>">
				<?php echo $_Oli->getInfosMySQL('url_shortener_list', 'link', array('link_key' => $_Oli->getUrlParam(1))); ?>
			</span> <br />
			
			<?php if($_Oli->getInfosMySQL('url_shortener_list', 'sensitive_link', array('link_key' => $_Oli->getUrlParam(1)))) { ?>
				<span class="text-danger">Attention, ce lien a été noté comme pouvant être choquant.</span>
			<?php } ?>
			<hr />
			
			<div class="col-sm-6">
				<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1) . '/confirmed'; ?>" class="btn btn-primary btn-block">J'accepte les risques et souhaite accéder au lien</a>
			</div>
			<div class="col-sm-6">
				<a href="<?php echo (!empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : $_Oli->getOption('url'); ?>" class="btn btn-default btn-block">
					<?php if(!empty($_SERVER['HTTP_REFERER'])) { ?>
						Revenir à la page précédante
					<?php } else { ?>
						Revenir à l'accueil
					<?php } ?>
				</a>
			</div>
			<div class="clearfix"></div>
			<hr />
			
			Si vous ne souhaitez pas voir cette confirmation, connectez-vous à votre compte si n'est pas déjà fait et aller modifier vos paramètres utilisateur. <br /> <br />
			
			Nous n'avons pas le contrôle sur ce que vous pourrez trouver sur ce lien, faites attention à vous et ne prennez pas de risques inutiles. <br />
			Notez qu'un lien peut ne pas être noté comme étant choquant mais l'être en réalité, <br />
			Pour toute information ou signalement, vous pouvez <a href="<?php echo $_Oli->getOption('url'); ?>contact/">nous contactez</a>.
		</p>
	</div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
	
<?php } ?>