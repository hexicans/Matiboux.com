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
		<h1><i class="fa fa-gears fa-fw"></i> <?php echo $_Oli->getOption('name'); ?></h1>
		<p>
			Panel de gestion de votre compte.
		</p>
	</div>
</div>

<div class="main">
	<?php if($_Oli->verifyAuthKey()) { ?>
		<div class="container">
			<h2>Statistiques globales</h2>
			<div class="data-box col-xs-12">
				<h2><?php echo floor((time() - strtotime($_Oli->getAccountInfos('ACCOUNTS', 'register_date', array('username' => $_Oli->getAuthKeyOwner())))) / (3600 * 24)); ?></h2>
				<p>
					jours depuis votre inscription <br />
					(<?php echo date('d/m/Y', strtotime($_Oli->getAccountInfos('ACCOUNTS', 'register_date', array('username' => $_Oli->getAuthKeyOwner())))); ?>)
				</p>
			</div>
		</div> <hr />
	<?php } ?>
		
	<div class="container">
		<h2>Que peut-on faire avec ce panel ?</h2>
		<p>
			Ce panel de gestion vous permet de gérer simplement votre compte, vous aurez accès à : <br />
			<ul>
				<li>
					<b>Vos informations personnelles</b> <br />
					Modifiez et gardez à jour la plupart des informations liées à votre compte : <br />
					les informations principales mais encore votre langue, l'affichage de votre nom et diverses autres informations supplémentaires.
				</li> <br />
				<li>
					<b>La visualisation de vos droits</b> <br />
					Visualisez vos droits et interdiction qui vous sont définies ou que vous héritez de votre grade. <br />
					<small class="text-warning">
						<b>EXPERIMENTAL :</b> <br />
						Pour le moment, les permissions ne sont <b>ni utilisées</b>, <b>ni définies</b>. <br />
						Cependant, la fonctionnalité est susceptible d'être mise à oeuvre.
					</small>
				</li> <br />
				<li>
					<b>La visualisation de vos données</b> <br />
					Visualisez l'utilisation de votre espace de stockage pour chaque projet. <br />
					<small class="text-warning">
						<b>Attention :</b> <br />
						Pour le moment, <b>aucune limite</b> n'a été mise en place,
						mais la décision de sa mise en place pourrait éventuellement être examinée. <br />
						Cette mesure ne sera prise <b>que si nécessaire</b>, le service se voulant le <b>plus accessible possible</b>.
					</small>
				</li> <br />
				<li>
					<b>Une liste de vos sessions actives</b> <br />
					Gérez toutes les sessions actives liées à votre compte. <br />
					Gardez un oeil sur la date et la provenance des connexion ainsi que de leur dernière utilisation<span class="text-primary">*</span>.  <br />
					Soyez maître de votre compte et déconnectez à distance les sessions que vous n'utilisez plus. <br />
					<small class="text-primary">
						*La dernière utilisation d'une session est une date mise à jour à chaque rechargement de page
					</small>
				</li> <br />
				<li>
					<b>Une liste de vos requêtes</b> <br />
					Surveillez la liste des requêtes actives de votre compte :
					changements de mot de passe, vérifications de l'adresse mail, etc... <br />
					Ne laissez rien d'inutile et annulez les requêtes encore valides que vous ne terminerez pas.
				</li> <br />
				<li>
					<b>Un support avec tickets</b> <br />
					Signalez un problème ou proposez vos idées aux administrateurs à l'aide d'un ticket. <br />
					Maintenez un discussion simplement et restez attentifs aux réponses grâce aux notifications par mail.
				</li>
			</ul>
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