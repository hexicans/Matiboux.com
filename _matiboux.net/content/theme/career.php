<!DOCTYPE html>
<html>
<head>

<?php include 'head.php'; ?>
<title>Parcours - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include 'header.php'; ?>

<div class="header">
	<div class="container">
		<h1>Mon Parcours</h1>
		<p>
			Mon parcours, dans les grandes lignes.
		</p>
	</div>
</div>

<div class="main">
	<?php if($_Oli->getUrlParam(2) == 'oli') { ?>
		<?php $category = 'oli'; ?>
	<?php } else if($_Oli->getUrlParam(2) == 'social') { ?>
		<?php $category = 'social'; ?>
	<?php } else { ?>
		<?php $category = 'personnal'; ?>
	<?php } ?>
	<div class="container">
		<p>
			<i class="fa fa-sort-numeric-asc fa-fw"></i> Triées dans l'ordre chronologique
			<a href="#" class="btn btn-primary btn-xs pull-right scrollBottom">
				<i class="fa fa-angle-double-down fa-fw"></i>
				Aller directement aux informations récentes
				<i class="fa fa-angle-double-down fa-fw"></i>
			</a>
		</p>
		<ul class="switchTo">
			<li>
				<span value="personnal" <?php if($category == 'personnal') { ?>class="text-muted" style="font-weight: 700;"<?php } else { ?>class="text-primary"<?php } ?>>
					<i class="fa fa-home fa-fw"></i> Parcours personnel
				</span>
			</li>
			<li>
				<span value="oli" <?php if($category == 'oli') { ?>class="text-muted" style="font-weight: 700;"<?php } else { ?>class="text-primary"<?php } ?>>
					Mon framework PHP : Oli
				</span>
			</li>
			<li>
				<span value="social" <?php if($category == 'social') { ?>class="text-muted" style="font-weight: 700;"<?php } else { ?>class="text-primary"<?php } ?>>
					Mon réseau social : Matiboux Social
				</span>
			</li>
		</ul>
	</div> <hr />
	<div class="container" only="personnal" <?php if($category != 'personnal') { ?>style="display: none;"<?php } ?>>
		<h2>Avant</h2>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Date</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>...</td>
					<td>Apprends le <b>langage C</b> (mode console)</td>
				</tr>
				<tr>
					<td>Fin 2012</td>
					<td>Apprends le <b>langage HTML</b> et <b>CSS</b></td>
				</tr>
				<tr>
					<td>Janv. 2013</td>
					<td>Commence la collaboration avec <b>Gaming-Actu</b></td>
				</tr>
				<tr>
					<td>Juin 2013</td>
					<td>Fonde un groupe de serveurs Minecraft : <b>MySurvivalAdventure</b></td>
				</tr>
				<tr>
					<td>Août 2013</td>
					<td>Apprends le <b>langage PHP</b></td>
				</tr>
			</tbody>
			<tfoot>
				<tr><td colspan="2"></tr></tr>
			</tfoot>
		</table>
		
		<h2>2014</h2>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Date</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Avril 2014</td>
					<td>
						Développe un projet de web radio : <b>Storm Radio</b> <br />
						<i class="fa fa-angle-right fa-fw"></i> Projet lié à Gaming-Actu
					</td>
				</tr>
				<tr>
					<td>Mai 2014</td>
					<td>Apprends le <b>langage JavaScript</b></td>
				</tr>
				<tr>
					<td>Juin 2014</td>
					<td>Fin de <b>Gaming-Actu</b></td>
				</tr>
				<tr>
					<td>Juil. 2014</td>
					<td>Devient membre d'un groupe de développeur : <b>Team CodeIt</b></td>
				</tr>
				<tr>
					<td>12 Juil. 2014</td>
					<td>
						Développe un projet de génération d'un nombre aléatoire :
						<a href="<?php echo $_Oli->getShortcutLink('random'); ?>"><b>Random Number</b></a> <br />
						<i class="fa fa-angle-right fa-fw"></i> Projet lié à Team CodeIt
					</td>
				</tr>
				<tr>
					<td>26 Juil. 2014</td>
					<td>
						Développe un projet d'hébergement d'images en ligne :
						<a href="<?php echo $_Oli->getShortcutLink('imgshot'); ?>"><b>ImgShot</b></a> <br />
						<i class="fa fa-angle-right fa-fw"></i> Projet lié à Team CodeIt
					</td>
				</tr>
				<tr>
					<td>30 Juil. 2014</td>
					<td>
						Développe un projet de génération de mot de passe aléatoire :
						<a href="<?php echo $_Oli->getShortcutLink('keygen'); ?>"><b>KeyGen</b></a> <br />
						<i class="fa fa-angle-right fa-fw"></i> Projet lié à Team CodeIt
					</td>
				</tr>
				<tr>
					<td>Août 2014</td>
					<td>
						Commence la collaboration avec <a href="http://brains-master.com/"><b>Brains-Master</b></a> <br />
						Celui-ci reprends les projets liés à Gaming-Actu
					</td>
				</tr>
				<tr>
					<td>Sept. 2014</td>
					<td>
						Fin de la <b>Team CodeIt</b> <br />
						Reprends Random Number, ImgShot et KeyGen comme des projets personnels
						
					</td>
				</tr>
				<tr>
					<td>27 Oct. 2014</td>
					<td>Achète le nom de domaine <b>matiboux.com</b> pour un usage personnel et mes projets</td>
				</tr>
				<tr>
					<td>30 Oct. 2014</td>
					<td>Développe un projet de jeu Twitter (classement) : <b>Twitter Game</b></td>
				</tr>
				<tr>
					<td>15 Nov. 2014</td>
					<td>Développe <b>Oli</b>, un <b>framework PHP</b> modulable</td>
				</tr>
			</tbody>
			<tfoot>
				<tr><td colspan="2"></tr></tr>
			</tfoot>
		</table>
		
		<h2>2015</h2>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Date</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>27 Janv. 2015</td>
					<td>
						Développe un projet de Manager (panel de gestion) pour mon site :
						<a href="<?php echo $_Oli->getShortcutLink('manager'); ?>"><b>Manager</b></a>
					</td>
				</tr>
				<tr>
					<td>21 Avril 2015</td>
					<td>
						Fonde la <b>Team Fruinity</b> <br />
						Membres originaux : Dav, Felixjules, TheKiller678 et moi 
					</td>
				</tr>
				<tr>
					<td>27 Août 2015</td>
					<td>Placement des Apis dans un sous-domaine dédié</td>
				</tr>
				<tr>
					<td>27 Nov. 2015</td>
					<td>
						Développe un projet de cloud :
						<a href="<?php echo $_Oli->getShortcutLink('natrox_cloud'); ?>"><b>Natrox Cloud</b></a> <br />
						<i class="fa fa-angle-right fa-fw"></i> Projet pour <a href="http://natrox.net/"><b>Natrox</b></a>, prends le poste de responsable de projet
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr><td colspan="2"></tr></tr>
			</tfoot>
		</table>
		
		<h2>2016</h2>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Date</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>19 Janv. 2016</td>
					<td>
						Développe un portail pour accéder à mes projets :
						<a href="<?php echo $_Oli->getShortcutLink('portal'); ?>"><b>Portail de projet</b></a>
					</td>
				</tr>
				<tr>
					<td>29 Fév. 2016</td>
					<td>
						Développe un projet de raccourcissement de lien :
						<a href="<?php echo $_Oli->getShortcutLink('url_shortener'); ?>"><b>Url Shortener</b></a>
					</td>
				</tr>
				<tr>
					<td>6 Mars 2016</td>
					<td>
						Développe un projet de réseau social :
						<a href="<?php echo $_Oli->getShortcutLink('social'); ?>"><b>Matiboux Social</b></a>
					</td>
				</tr>
				<tr>
					<td>18 Mars 2016</td>
					<td>Quitte la <b>Team Fruinity</b></td>
				</tr>
				<tr>
					<td>Avril 2016</td>
					<td>
						Reprise du projet de serveurs de jeux <b>Natrox Games</b></a> <br />
						<i class="fa fa-angle-right fa-fw"></i> Projet de <a href="http://natrox.net/"><b>Natrox</b></a>, prends le poste de responsable de projet
					</td>
				</tr>
				<tr>
					<td>14 Avril 2016</td>
					<td>
						Développe ce "site vitrine" pour de me présenter :
						<a href="<?php echo $_Oli->getShortcutLink('home'); ?>"><b>Matiboux</b></a> <br />
						Rattache le portail vers les projets à celui-ci
					</td>
				</tr>
				<tr>
					<td>22 Avril 2016</td>
					<td>
						Reprise d'un ancien projet de génération de nombres aléatoires :
						<a href="<?php echo $_Oli->getShortcutLink('random'); ?>"><b>Random Number</b></a>
					</td>
				</tr>
				<tr>
					<td>10 Mai 2016</td>
					<td>
						Développe un projet regroupant plusieurs outils liés à la santé :
						<a href="<?php echo $_Oli->getShortcutLink('health'); ?>"><b>Health</b></a>
					</td>
				</tr>
				<tr>
					<td>17 Mai 2016</td>
					<td>
						Développe un projet support rattaché à Manager :
						<a href="<?php echo $_Oli->getShortcutLink('manager'); ?>"><b>Manager</b></a> <br />
						Intègre une gestion des tickets utilisateur et une partie d'administration
					</td>
				</tr>
				<tr>
					<td>22 Mai 2016</td>
					<td>
						Met en place un compte GitHub avec un repository pour Oli :
						<a href="https://github.com/matiboux/"><b>@matiboux</b></a>
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr><td colspan="2"></tr></tr>
			</tfoot>
		</table>
	</div>
	<div class="container" only="oli" <?php if($category != 'oli') { ?>style="display: none;"<?php } ?>>
		<h2>Description</h2>
		<p>
			<b>Oli</b> est un de mes projets personnels <br />
			C'est un framework PHP open-source publié assez tardivement sur Github.
			Les version disponible sont téléchargeable depuis le github.
		</p>
		
		<h2>Avant</h2>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Date</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>16 Nov. 2014</td>
					<td>
						Commence le <b>développement du framework</b> : version PRE-DEV <br />
						Framework baptisé "MaTAP"
					</td>
				</tr>
				<tr>
					<td>6 Fév. 2015</td>
					<td>
						Passe en version <b>ALPHA</b> <br />
						Framework re-baptisé "Oli" <br />
						<small><a href="#"><i class="fa fa-cloud-download fa-fw"></i> Obtenir cette version</a></small>
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr><td colspan="2"></tr></tr>
			</tfoot>
		</table>
		
		<h2>Version BETA</h2>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Date</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Juil. 2015</td>
					<td><h3>Passe en version <b>BETA</b></h3></td>
				</tr>
				<tr class="warning text-muted">
					<td></td>
					<td><small class="text-danger">Très peu d'informations sur les précédentes mises à jours</small></td>
				</tr>
				
				<tr>
					<td>17 Août 2015</td>
					<td>Passe en version <b>BETA 1.5</b></td>
				</tr>
				<tr class="text-muted">
					<td><small>21 Août 2015</small></td>
					<td><small>Passe en version <b>BETA 1.5.1</b></small></td>
				</tr>
				<tr class="text-muted">
					<td><small>25 Août 2015</small></td>
					<td><small>Passe en version <b>BETA 1.5.2</b></small></td>
				</tr>
				<tr class="text-muted">
					<td><small>26 Août 2015</small></td>
					<td>
						<small>
							Passe en version <b>BETA 1.5.3</b> <br />
							<a href="#"><i class="fa fa-cloud-download fa-fw"></i> Obtenir cette version</a>
						</small>
					</td>
				</tr>
				<tr class="text-muted">
					<td><small>20 Nov. 2015</small></td>
					<td><small>Passe en version <b>BETA 1.5.5</b></small></td>
				</tr>
				
				<tr>
					<td>6 Dec. 2015</td>
					<td>Passe en version <b>BETA 1.6</b></td>
				</tr>
				<tr class="text-muted">
					<td><small>9 Dec. 2015</small></td>
					<td>
						<small>
							Passe en version <b>BETA 1.6.2</b> <br />
							<a href="#"><i class="fa fa-cloud-download fa-fw"></i> Obtenir cette version</a>
						</small>
					</td>
				</tr>
				<tr class="text-muted">
					<td><small>10 Jan. 2016</small></td>
					<td>
						<small>
							Passe en version <b>BETA 1.6.3</b> <br />
							<a href="#"><i class="fa fa-cloud-download fa-fw"></i> Obtenir cette version</a> |
							<a href="#"><i class="fa fa-file-text-o fa-fw"></i> Changelog</a>
						</small>
					</td>
				</tr>
				<tr class="text-muted">
					<td><small>10 Fév. 2016</small></td>
					<td>
						<small>
							Passe en version <b>BETA 1.6.4</b> <br />
							<a href="#"><i class="fa fa-cloud-download fa-fw"></i> Obtenir cette version</a> |
							<a href="#"><i class="fa fa-file-text-o fa-fw"></i> Changelog</a>
						</small>
					</td>
				</tr>
				<tr class="text-muted">
					<td><small>6 Juin 2016</small></td>
					<td>
						<small>
							Passe en version <b>BETA 1.6.5</b> <br />
							<a href="#"><i class="fa fa-cloud-download fa-fw"></i> Obtenir cette version</a> |
							<a href="#"><i class="fa fa-file-text-o fa-fw"></i> Changelog</a>
						</small>
					</td>
				</tr>
				<tr class="text-muted">
					<td><small>2 Juil. 2016</small></td>
					<td>
						<small>
							Passe en version <b>BETA 1.6.6</b> <br />
							<span class="text-danger"><i class="fa fa-times fa-fw"></i> Non disponible</span> |
							<a href="#"><i class="fa fa-file-text-o fa-fw"></i> Changelog</a>
						</small>
					</td>
				</tr>
				<tr class="info">
					<td>En cours</td>
					<td>
						Développe la version <b>BETA 1.7</b> <br />
						<small class="text-muted">
							Influencé par <a href="<?php echo $_Oli->getShortcutLink('social'); ?>"><b>Matiboux Social</b></a>
						</small>
					</td>
				</tr>
				<tr class="text-primary">
					<td><small>Bientôt</small></td>
					<td><small><i class="fa fa-github fa-fw"></i> Publié et maintenu sur Github</small></td>
				</tr>
			</tbody>
			<tfoot>
				<tr><td colspan="2"></tr></tr>
			</tfoot>
		</table>
	</div>
	<div class="container" only="social" <?php if($category != 'social') { ?>style="display: none;"<?php } ?>>
		<h2>Description</h2>
		<p>
			<a href="<?php echo $_Oli->getShortcutLink('social'); ?>"><b>Matiboux Social</b></a> est un de mes projets personnels <br />
			C'est un réseau social simple, mais pensé pour assurer la confidentialité des utilisateurs.
			Un réseau social ouvert à tout le monde, pour poster n'importe quoi, n'importe quand et sans limite.
		</p>
		
		<h2>Version PRE-DEV / ALPHA</h2>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Date</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>6 Mars 2016</td>
					<td>
						Commence le <b>développement du réseau social</b> : version PRE-DEV<br />
						Réseau baptisé "Matiboux Social"
					</td>
				</tr>
				<tr>
					<td>27 Mars 2016</td>
					<td>
						Permet la visualisation du profil d'un utilisateur : <br />
						<small class="text-muted">
							Syntaxe du lien : "<?php echo $_Oli->getShortcutLink('social'); ?>user/" + [nom d'utilisateur]
						</small>
					</td>
				</tr>
				<tr>
					<td>6 Fév. 2016</td>
					<td>
						Passe en version <b>ALPHA</b> <br />
						Prépare certaines pages (dont "Nouvelles" et "Profil") <br />
						Réfléchis à propos des règles liés à la confidentialité
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr><td colspan="2"></tr></tr>
			</tfoot>
		</table>
		
		<h2>Version BETA</h2>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Date</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>8 Juin 2016</td>
					<td>
						Passe en version <b>BETA</b> <br />
						Prise d'indépendance par rapport aux autres projets <br />
						Développement rapide & actif du projet <br />
						<small class="text-muted">
							Les nouveautés de cette période de développement ne seront pas détaillées <br />
							Développement simultané de Oli Beta 1.7 sur lequel il porte une très grande influence
						</small>
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr><td colspan="2"></tr></tr>
			</tfoot>
		</table>
	</div> <hr />
	<div class="container">
		<p>
			<a href="#" class="btn btn-primary btn-xs pull-right scrollTop">
				<i class="fa fa-angle-double-up fa-fw"></i>
				Remonter en haut de la page
				<i class="fa fa-angle-double-up fa-fw"></i>
			</a>
		</p>
	</div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>