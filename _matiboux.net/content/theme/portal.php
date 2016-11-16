<!DOCTYPE html>
<html>
<head>

<?php include THEMEPATH . 'head.php'; ?>
<title>Portal - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include THEMEPATH . 'header.php'; ?>

<div class="header">
	<div class="container">
		<h1><i class="fa fa-sort-amount-desc fa-fw"></i> Mes projets</h1>
		<p>
			Un accès facilité à mes projets ainsi qu'un suivi de leur état.
		</p>
	</div>
</div>

<div class="main">
	<div class="container">
		<h3>Projets personnels</h3>
		
		<p>
			<a href="#" class="btn btn-primary btn-xs disabled">Oli</a>
			<small>
				- Framework PHP Open-Source <br />
				<span class="text-warning">Pas de site web disponible pour le moment, BETA privée</span>
			</small>
		</p>
		<hr />
		
		<?php $projects = [
			'manager',
			'imgshot',
			'keygen',
			'natrox_cloud',
			'url_shortener',
			'health',
			'random',
			'how',
			'apis'
		]; ?>
		<?php foreach($projects as $eachProject) { ?>
			<?php $settingsTable = 'settings_' . $eachProject; ?>
			<?php $url = $_Oli->getInfosMySQL($settingsTable, 'value', array('name' => 'url')); ?>
			<?php $version = $_Oli->getInfosMySQL($settingsTable, 'value', array('name' => 'version')); ?>
			<?php $status = $_Oli->getInfosMySQL($settingsTable, 'value', array('name' => 'status')); ?>
			
			<p>
				<a href="<?php echo ($status == 'not_available' OR $status == 'no_website' OR empty($url)) ? '#' : $url; ?>" class="btn btn-primary btn-xs<?php echo ($status == 'not_available' OR $status == 'no_website' OR empty($url)) ? ' disabled' : ''; ?>">
					<?php echo $_Oli->getInfosMySQL($settingsTable, 'value', array('name' => 'name')); ?>
				</a>
				<small>
					<?php if(!empty($version)) { ?>
						- <span class="label label-default">Version <?php echo $version; ?></span>
					<?php } ?> <br />
					<?php echo $_Oli->getInfosMySQL($settingsTable, 'value', array('name' => 'description')); ?> <br />
					<?php if(!empty($version)) { ?>
						<?php if($status == 'update_planned') { ?>
							<span class="text-primary">Mise à jour prévue pour ce projet</span>
						<?php } else if($status == 'support_update') { ?>
							<span class="text-info">Surveillance du projet pour d'éventuelles mises à jour</span>
						<?php } else if($status == 'finished') { ?>
							<span class="text-success">Projet finalisé (possibilité d'être mis à jour)</span>
						<?php } else if($status == 'still_in_dev') { ?>
							<span class="text-warning">Service encore en développement</span>
						<?php } else if($status == 'no_website') { ?>
							<span class="text-warning">Pas de site web disponible pour le moment</span>
						<?php } else if($status == 'not_available') { ?>
							<span class="text-danger">Non disponible</span>
						<?php } else { ?>
							<span class="text-default">Aucun statut défini pour ce projet</span>
						<?php } ?>
					<?php } ?>
				</small>
			</p>
		<?php } ?>
		<hr />
		
		<h3>Projets externes <i class="fa fa-external-link fa-fw"></i></h3>
		<p>
			<i class="fa fa-angle-right fa-fw"></i> <a href="<?php echo $_Oli->getShortcutLink('social'); ?>">Matiboux Social</a>
			<small>- Petit réseau social indépendant</small>
		</p>
		<p>
			<i class="fa fa-angle-right fa-fw"></i> <a href="#">Natrox Games</a>
			<small>- Groupe de serveurs de jeux</small>
		</p>
		<p>
			<i class="fa fa-angle-right fa-fw"></i> <a href="http://brains-master.com/">Brains-Master</a>
			<small>- Hébergement de plateformes web</small>
		</p>
	</div>
</div>

<?php include THEMEPATH . 'footer.php'; ?>

</body>
</html>