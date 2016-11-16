<?php
if(!$_Oli->verifyAuthKey() OR $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) < $_Oli->translateUserRight('USER'))
	header('Location: ' . $_Oli->getShortcutLink('login'));
?>

<!DOCTYPE html>
<html>
<head>

<?php include 'head.php'; ?>
<title>Vos données - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include 'header.php'; ?>

<div class="header">
	<div class="container">
		<h1>Vos données</h1>
		<p>
			Gestion de vos données et votre espace de stockage
		</p>
	</div>
</div>

<?php if($_Oli->getUrlParam(2) == 'delete' AND !empty($_Oli->getUrlParam(3)) AND $resultCode == 'CONFIRMATION_NEEDED') { ?>
	<div class="message message-warning">
		<div class="container">
			<h1>Confirmez l'annulation de la requête liée à l'ID #<?php echo $_Oli->getUrlParam(3); ?></h1>
			<p>
				<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $_Oli->getUrlParam(3); ?>/confirmed" class="btn btn-primary btn-block">
					<i class="fa fa-check fa-fw"></i> J'autorise l'annulation de cette requête
				</a>
				<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/" class="btn btn-danger btn-block">
					<i class="fa fa-times fa-fw"></i> Je refuse d'annuler cette session
				</a>
			</p>
		</div>
	</div>
<?php } else if($_Oli->getUrlParam(2) == 'deleteSelected' AND !empty($_Oli->getUrlParam(3)) AND $resultCode == 'CONFIRMATION_NEEDED') { ?>
	<div class="message message-warning">
		<div class="container">
			<h1>Confirmez l'annulation des requêtes sélectionnées</h1>
			<p>
				<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $_Oli->getUrlParam(3); ?>/confirmed" class="btn btn-primary btn-block">
					<i class="fa fa-check fa-fw"></i> J'autorise l'annulation de ces requêtes
				</a>
				<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/" class="btn btn-danger btn-block">
					<i class="fa fa-times fa-fw"></i> Je refuse de supprimer ces requêtes
				</a>
			</p>
		</div>
	</div>
<?php } else if($resultCode == 'UNKNOWN_REQUEST') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous avez tenté d'effectuer une action sur une requête qui nous est inconnue ou qui n'existe pas</h2>
		</div>
	</div>
<?php } else if($resultCode == 'REQUEST_CANCELED') { ?>
	<div class="message message-success">
		<div class="container">
			<h2>La requête a bien été annulée</h2>
		</div>
	</div>
<?php } else if($resultCode == 'NOT_YOUR_REQUEST') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous avez tenté d'effectuer une action sur une requête qui ne vous appartient pas, celle-ci a donc échouée</h2>
		</div>
	</div>
<?php } ?>

<div class="message" id="scriptMessage" style="display: none;">
	<div class="container">
		<h2></h2>
		<p></p>
	</div>
</div>

<div class="main">
	<div class="container">
		<?php $totalSize = 0; ?>
		
		<?php $socialPostsSize = ($_Oli->isExistInfosMySQL('-', array('username' => $_Oli->getAuthKeyOwner()))) ? $_Oli->getSummedInfosMySQL('-', '-', array('username' => $_Oli->getAuthKeyOwner())) : 0; ?>
		<?php $totalSize += $socialPostsSize; ?>
		<?php $socialMessagesSize = ($_Oli->isExistInfosMySQL('-', array('username' => $_Oli->getAuthKeyOwner()))) ? $_Oli->getSummedInfosMySQL('-', '-', array('username' => $_Oli->getAuthKeyOwner())) : 0; ?>
		<?php $totalSize += $socialMessagesSize; ?>
		
		<?php $imgshotSize = ($_Oli->isExistInfosMySQL('imgshot_uploads', array('owner' => $_Oli->getAuthKeyOwner()))) ? $_Oli->getSummedInfosMySQL('imgshot_uploads', 'file_size', array('owner' => $_Oli->getAuthKeyOwner())) : 0; ?>
		<?php $totalSize += $imgshotSize; ?>
		<?php $keygenSize = ($_Oli->isExistInfosMySQL('keygen_history', array('username' => $_Oli->getAuthKeyOwner()))) ? $_Oli->getSummedInfosMySQL('keygen_history', 'length', array('username' => $_Oli->getAuthKeyOwner())) : 0; ?>
		<?php $totalSize += $keygenSize; ?>
		<?php $ncloudSize = ($_Oli->isExistInfosMySQL('natrox_cloud_uploads', array('owner' => $_Oli->getAuthKeyOwner()))) ? $_Oli->getSummedInfosMySQL('natrox_cloud_uploads', 'file_size', array('owner' => $_Oli->getAuthKeyOwner())) : 0; ?>
		<?php $totalSize += $ncloudSize; ?>
		<?php $urlShortenerSize = ($_Oli->isExistInfosMySQL('url_shortener_list', array('owner' => $_Oli->getAuthKeyOwner()))) ? $_Oli->getSummedInfosMySQL('url_shortener_list', 'length', array('owner' => $_Oli->getAuthKeyOwner())) : 0; ?>
		<?php $totalSize += $urlShortenerSize; ?>
		<?php $randomSize = ($_Oli->isExistInfosMySQL('-', array('username' => $_Oli->getAuthKeyOwner()))) ? $_Oli->getSummedInfosMySQL('-', '-', array('username' => $_Oli->getAuthKeyOwner())) : 0; ?>
		<?php $totalSize += $randomSize; ?>
		<?php $healthSize = ($_Oli->isExistInfosMySQL('-', array('username' => $_Oli->getAuthKeyOwner()))) ? $_Oli->getSummedInfosMySQL('-', '-', array('username' => $_Oli->getAuthKeyOwner())) : 0; ?>
		<?php $totalSize += $healthSize; ?>
		
		<h1>Statistiques globales</h1>
		<div class="data-box col-sm-4 col-xs-6">
			<h2>
				<?php if($totalSize >= 1024*1024*1024) { ?>
					<?php echo round($totalSize / (1024*1024*1024), 1); ?>
					<?php $usedUnit = 'Gio'; ?>
				<?php } else if($totalSize >= 1024*1024) { ?>
					<?php echo round($totalSize / (1024*1024), 1); ?>
					<?php $usedUnit = 'Mio'; ?>
				<?php } else if($totalSize >= 1024) { ?>
					<?php echo round($totalSize / (1024), 1); ?>
					<?php $usedUnit = 'Kio'; ?>
				<?php } else { ?>
					<?php echo $totalSize; ?>
					<?php $usedUnit = 'octets'; ?>
				<?php } ?>
			</h2>
			<p>
				<?php echo $usedUnit; ?> utilisés
			</p>
		</div>
		<div class="data-box col-sm-4 col-xs-6">
			<h2>N/A</h2>
			<p>
				Go disponibles <br />
				<span class="text-danger">Limitations de stockage non définies</span>
			</p>
		</div>
		<div class="data-box col-sm-4 col-xs-6">
			<h2>N/A</h2>
			<p>
				Go à votre disposition au total <br />
				<span class="text-danger">Limitations de stockage non définies</span>
			</p>
		</div>
		
		<h1>Utilisation pour chaque projet</h1>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Service</th>
					<th>Nombre d'éléments</th>
					<th>Espace de stockage utilisé</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr class="warning">
					<?php $socialPostsCount = ($_Oli->isExistInfosMySQL('-', array('username' => $_Oli->getAuthKeyOwner())) > 0) ? $_Oli->isExistInfosMySQL('-', array('username' => $_Oli->getAuthKeyOwner())) : 0; ?>
					<?php $socialMessagesCount = ($_Oli->isExistInfosMySQL('-', array('username' => $_Oli->getAuthKeyOwner())) > 0) ? $_Oli->isExistInfosMySQL('-', array('username' => $_Oli->getAuthKeyOwner())) : 0; ?>
					<td>
						<a href="<?php echo $_Oli->getShortcutLink('social'); ?>">
							<i class="fa fa-users fa-fw"></i> Social
						</a>
						<small>- <span class="text-danger">Non Disponible</span></small>
					</td>
					<td>
						<?php echo $socialPostsCount; ?> <small>post<?php if($socialPostsCount > 1) { ?>s<?php } ?></small> <br />
						<?php echo $socialMessagesCount; ?> <small>discussion<?php if($socialMessagesCount > 1) { ?>s<?php } ?></small>
					</td>
					<td>
						<?php if($socialPostsSize >= 1024*1024*1024) { ?>
							<?php echo round($socialPostsSize / (1024*1024*1024), 1); ?>
							<small>Gio</small>
						<?php } else if($socialPostsSize >= 1024*1024) { ?>
							<?php echo round($socialPostsSize / (1024*1024), 1); ?>
							<small>Mio</small>
						<?php } else if($socialPostsSize >= 1024) { ?>
							<?php echo round($socialPostsSize / (1024), 1); ?>
							<small>Kio</small>
						<?php } else { ?>
							<?php echo $socialPostsSize; ?>
							<small>octets</small>
						<?php } ?> <br />
						<?php if($socialMessagesSize >= 1024*1024*1024) { ?>
							<?php echo round($socialMessagesSize / (1024*1024*1024), 1); ?>
							<small>Gio</small>
						<?php } else if($socialMessagesSize >= 1024*1024) { ?>
							<?php echo round($socialMessagesSize / (1024*1024), 1); ?>
							<small>Mio</small>
						<?php } else if($socialMessagesSize >= 1024) { ?>
							<?php echo round($socialMessagesSize / (1024), 1); ?>
							<small>Kio</small>
						<?php } else { ?>
							<?php echo $socialMessagesSize; ?>
							<small>octets</small>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<?php $imgshotCount = ($_Oli->isExistInfosMySQL('imgshot_uploads', array('owner' => $_Oli->getAuthKeyOwner())) > 0) ? $_Oli->isExistInfosMySQL('imgshot_uploads', array('owner' => $_Oli->getAuthKeyOwner())) : 0; ?>
					<td>
						<a href="<?php echo $_Oli->getShortcutLink('imgshot'); ?>">
							<i class="fa fa-picture-o fa-fw"></i> ImgShot
						</a>
					</td>
					<td><?php echo $imgshotCount; ?> <small>image<?php if($imgshotCount > 1) { ?>s<?php } ?></small></td>
					<td>
						<?php if($imgshotSize >= 1024*1024*1024) { ?>
							<?php echo round($imgshotSize / (1024*1024*1024), 1); ?>
							<small>Gio</small>
						<?php } else if($imgshotSize >= 1024*1024) { ?>
							<?php echo round($imgshotSize / (1024*1024), 1); ?>
							<small>Mio</small>
						<?php } else if($imgshotSize >= 1024) { ?>
							<?php echo round($imgshotSize / (1024), 1); ?>
							<small>Kio</small>
						<?php } else { ?>
							<?php echo $imgshotSize; ?>
							<small>octets</small>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<?php $keygenCount = ($_Oli->isExistInfosMySQL('keygen_history', array('username' => $_Oli->getAuthKeyOwner())) > 0) ? $_Oli->isExistInfosMySQL('keygen_history', array('username' => $_Oli->getAuthKeyOwner())) : 0; ?>
					<td>
						<a href="<?php echo $_Oli->getShortcutLink('keygen'); ?>">
							<i class="fa fa-lock fa-fw"></i> KeyGen
						</a>
					</td>
					<td><?php echo $keygenCount; ?> <small>keygen<?php if($keygenCount > 1) { ?>s<?php } ?></small></td>
					<td>
						<?php if($keygenSize >= 1024*1024*1024) { ?>
							<?php echo round($keygenSize / (1024*1024*1024), 1); ?>
							<small>Gio</small>
						<?php } else if($keygenSize >= 1024*1024) { ?>
							<?php echo round($keygenSize / (1024*1024), 1); ?>
							<small>Mio</small>
						<?php } else if($keygenSize >= 1024) { ?>
							<?php echo round($keygenSize / (1024), 1); ?>
							<small>Kio</small>
						<?php } else { ?>
							<?php echo $keygenSize; ?>
							<small>octets</small>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<?php $ncloudCount = ($_Oli->isExistInfosMySQL('natrox_cloud_uploads', array('owner' => $_Oli->getAuthKeyOwner())) > 0) ? $_Oli->isExistInfosMySQL('natrox_cloud_uploads', array('owner' => $_Oli->getAuthKeyOwner())) : 0; ?>
					<td>
						<a href="<?php echo $_Oli->getShortcutLink('ncloud'); ?>">
							<i class="fa fa-cloud-upload fa-fw"></i> Natrox Cloud
						</a>
					</td>
					<td><?php echo $ncloudCount; ?> <small>fichier<?php if($ncloudCount > 1) { ?>s<?php } ?></small></td>
					<td>
						<?php if($ncloudSize >= 1024*1024*1024) { ?>
							<?php echo round($ncloudSize / (1024*1024*1024), 1); ?>
							<small>Gio</small>
						<?php } else if($ncloudSize >= 1024*1024) { ?>
							<?php echo round($ncloudSize / (1024*1024), 1); ?>
							<small>Mio</small>
						<?php } else if($ncloudSize >= 1024) { ?>
							<?php echo round($ncloudSize / (1024), 1); ?>
							<small>Kio</small>
						<?php } else { ?>
							<?php echo $ncloudSize; ?>
							<small>octets</small>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<?php $urlShortenerCount = ($_Oli->isExistInfosMySQL('url_shortener_list', array('owner' => $_Oli->getAuthKeyOwner())) > 0) ? $_Oli->isExistInfosMySQL('url_shortener_list', array('owner' => $_Oli->getAuthKeyOwner())) : 0; ?>
					<td>
						<a href="<?php echo $_Oli->getShortcutLink('url_shortener'); ?>">
							<i class="fa fa-link fa-fw"></i> Url Shortener
						</a>
					</td>
					<td><?php echo $urlShortenerCount; ?> <small>lien<?php if($urlShortenerCount > 1) { ?>s<?php } ?></small></td>
					<td>
						<?php if($urlShortenerSize >= 1024*1024*1024) { ?>
							<?php echo round($urlShortenerSize / (1024*1024*1024), 1); ?>
							<small>Gio</small>
						<?php } else if($urlShortenerSize >= 1024*1024) { ?>
							<?php echo round($urlShortenerSize / (1024*1024), 1); ?>
							<small>Mio</small>
						<?php } else if($urlShortenerSize >= 1024) { ?>
							<?php echo round($urlShortenerSize / (1024), 1); ?>
							<small>Kio</small>
						<?php } else { ?>
							<?php echo $urlShortenerSize; ?>
							<small>octets</small>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<?php $randomCount = ($_Oli->isExistInfosMySQL('random_history', array('username' => $_Oli->getAuthKeyOwner())) > 0) ? $_Oli->isExistInfosMySQL('random_history', array('username' => $_Oli->getAuthKeyOwner())) : 0; ?>
					<td>
						<a href="<?php echo $_Oli->getShortcutLink('random'); ?>">
							<i class="fa fa-random fa-fw"></i> Random
						</a>
					</td>
					<td><?php echo $randomCount; ?> <small>nombre<?php if($randomCount > 1) { ?>s<?php } ?></small></td>
					<td>
						<?php if($randomSize >= 1024*1024*1024) { ?>
							<?php echo round($randomSize / (1024*1024*1024), 1); ?>
							<small>Gio</small>
						<?php } else if($randomSize >= 1024*1024) { ?>
							<?php echo round($randomSize / (1024*1024), 1); ?>
							<small>Mio</small>
						<?php } else if($randomSize >= 1024) { ?>
							<?php echo round($randomSize / (1024), 1); ?>
							<small>Kio</small>
						<?php } else { ?>
							<?php echo $randomSize; ?>
							<small>octets</small>
						<?php } ?>
					</td>
				</tr>
				<tr class="warning">
					<?php $healthCount = ($_Oli->isExistInfosMySQL('-', array('username' => $_Oli->getAuthKeyOwner())) > 0) ? $_Oli->isExistInfosMySQL('-', array('username' => $_Oli->getAuthKeyOwner())) : 0; ?>
					<td>
						<a href="<?php echo $_Oli->getShortcutLink('health'); ?>">
							<i class="fa fa-heartbeat fa-fw"></i> Health
						</a>
						<small>- <span class="text-danger">Non Disponible</span></small>
					</td>
					<td><?php echo $healthCount; ?> <small>valeur<?php if($healthCount > 1) { ?>s<?php } ?></small></td>
					<td>
						<?php if($healthSize >= 1024*1024*1024) { ?>
							<?php echo round($healthSize / (1024*1024*1024), 1); ?>
							<small>Gio</small>
						<?php } else if($healthSize >= 1024*1024) { ?>
							<?php echo round($healthSize / (1024*1024), 1); ?>
							<small>Mio</small>
						<?php } else if($healthSize >= 1024) { ?>
							<?php echo round($healthSize / (1024), 1); ?>
							<small>Kio</small>
						<?php } else { ?>
							<?php echo $healthSize; ?>
							<small>octets</small>
						<?php } ?>
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2"><b>Total :</b></td>
					<td>
						<?php if($totalSize >= 1024*1024*1024) { ?>
							<?php echo round($totalSize / (1024*1024*1024), 1); ?>
							<small>Gio</small>
						<?php } else if($totalSize >= 1024*1024) { ?>
							<?php echo round($totalSize / (1024*1024), 1); ?>
							<small>Mio</small>
						<?php } else if($totalSize >= 1024) { ?>
							<?php echo round($totalSize / (1024), 1); ?>
							<small>Kio</small>
						<?php } else { ?>
							<?php echo $totalSize; ?>
							<small>octets</small>
						<?php } ?>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>