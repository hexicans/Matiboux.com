<!DOCTYPE html>
<html>
<head>

<?php include COMMONPATH . 'head.php'; ?>
<title>Projects status - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include THEMEPATH . 'header.php'; ?>

<div class="title-banner">
	Project monitoring: status and updates
</div>

<div class="page-content">
	<div class="container">
		<div class="content-box transparent text-center">
			<h3>Here's a list of all the projects I'm currently running:</h3>
		</div>
		
		<div class="content-box">
			<?php
			$projects[] = 'settings_oli';
			$projects[] = 'settings_projects';
			$projects[] = 'settings';
			$projects[] = 'settings_accounts';
			$projects[] = 'settings_admin';
			if(in_array($_Oli->getAuthKeyOwner(), ['Matiboux', 'Elionatrox'])) $projects[] = 'settings_eli';
			$projects[] =  'settings_imgshot';
			$projects[] = 'settings_keygen';
			$projects[] =  'settings_urlshortener';
			// $projects[] =  'settings_random';
			// $projects[] =  'settings_ncloud';
			// $projects[] = 'settings_social';
			// $projects[] =  'settings_draws';
			?>
			<table class="table table-hover" style="min-width: 100%">
				<thead>
					<tr>
						<th style="width: 40px; text-align: center;"><i class="fa fa-check fa-fw"></i></th>
						<th>Project Infos</th>
						<th>Version</th>
						<th>Status</th>
						<th>Created</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach((!is_array($projects) ? [$projects] : $projects) as $eachProject) { ?>
						<?php
						foreach($_Oli->getInfosMySQL($eachProject, ['name', 'value']) as $eachSetting) {
							$projectInfos[$eachSetting['name']] = $eachSetting['value'];
						}
						?>
						
						<tr class="projects-status <?php if($eachProject == $_Oli->getSettingsTables()[0]) { ?>info<?php } else if($eachProject == 'settings_oli') { ?>active<?php } ?>" id="<?php echo $eachProject; ?>">
							<td style="text-align: center;">
								<?php if($projectInfos['status'] != 'not_available' AND !empty($projectInfos['url'])) { ?>
									<?php preg_match('/^(?:[w]{3}\.)?((?:(?:[\da-z\.-]+)\.)*(?:[\da-z-]+\.(?:[a-z\.]{2,6})))\/?(?:.)*/', $projectInfos['url'], $matches); ?>
									<?php $response = @fsockopen($matches[1], 80, $errno, $errstr, 10); ?>
									
									<span class="status <?php if($response) { ?>text-primary<?php } else { ?>text-danger<?php } ?>">
										<?php if($eachProject == $_Oli->getSettingsTables()[0]) { ?>
											<i class="fa fa-check-circle fa-fw"></i>
										<?php } else { ?>
											<i class="fa fa-circle fa-fw"></i>
										<?php } ?>
									</span>
									<?php if($response) fclose($response); ?>
								<?php } else { ?>
									<span class="text-muted"><i class="fa fa-times fa-fw"></i></span>
								<?php } ?>
							</td>
							<td>
								<a href="<?php echo ($projectInfos['status'] != 'not_available' AND !empty($projectInfos['url'])) ? ($https ? 'https://' : 'http://') . (is_array($projectInfos['url']) ? $projectInfos['url'][0] : $projectInfos['url']) : '#'; ?>"><?php echo $projectInfos['name']; ?></a> <br />
								<small><?php echo $projectInfos['description']; ?></small>
								<?php if($projectInfos['status'] != 'not_available' AND !empty($projectInfos['url'])) { ?> <br />
									<small class="text-muted"><?php echo ($https ? 'https://' : 'http://') . (is_array($projectInfos['url']) ? $projectInfos['url'][0] : $projectInfos['url']); ?></small>
								<?php } ?>
							</td>
							<td>
								<?php if(!empty($projectInfos['version'])) { ?>
									v. <?php echo strtolower($projectInfos['version']); ?>
								<?php } else { ?>
									<span class="text-muted"><i class="fa fa-times fa-fw"></i></span>
								<?php } ?>
							</td>
							<td>
								<?php if($projectInfos['status'] == 'update') { ?>
									<span class="text-primary">Update scheduled</span>
								<?php } else if($projectInfos['status'] == 'support') { ?>
									<span class="text-primary">Project support</span>
								<?php } else if($projectInfos['status'] == 'available') { ?>
									<span class="text-primary">Available</span>
								<?php } else if($projectInfos['status'] == 'finished') { ?>
									<span class="text-success">Finished project</span>
								<?php } else if($projectInfos['status'] == 'dev') { ?>
									<span class="text-warning">Under development</span>
								<?php } else if($projectInfos['status'] == 'standby') { ?>
									<span class="text-warning">On standby</span>
								<?php } else if($projectInfos['status'] == 'not_available') { ?>
									<span class="text-danger">Not available</span>
								<?php } else { ?>
									<span class="text-muted">No status provided</span>
								<?php } ?>
							</td>
							<td>
								<?php if(!empty($projectInfos['creation_date'])) { ?>
									<?php
									switch(date('n', strtotime($projectInfos['creation_date']))) {
										case 1: echo 'January'; break;
										case 2: echo 'February'; break;
										case 3: echo 'March'; break;
										case 4: echo 'April'; break;
										case 5: echo 'May'; break;
										case 6: echo 'June'; break;
										case 7: echo 'July'; break;
										case 8: echo 'August'; break;
										case 9: echo 'September'; break;
										case 10: echo 'October'; break;
										case 11: echo 'November'; break;
										case 12: echo 'December'; break;
									}
									?> <?php
									echo $creationDay = date('j', strtotime($projectInfos['creation_date']));
									if(substr($creationDay, 0, 1) != 1 AND substr($creationDay, 1, 1) == 1) echo 'st';
									else if(substr($creationDay, 0, 1) != 1 AND substr($creationDay, 1, 1) == 2) echo 'nd';
									else if(substr($creationDay, 0, 1) != 1 AND substr($creationDay, 1, 1) == 3) echo 'rd';
									else echo 'th';
									?>, <?php echo date('Y', strtotime($projectInfos['creation_date'])); ?>
								<?php } else { ?>
									<span class="text-muted"><i class="fa fa-times fa-fw"></i></span>
								<?php } ?>
							</td>
						</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td></td>
						<td colspan="4"><?php echo count($projects); ?> <small>projects</small></td>
					</tr>
				</tfoot>
			</table>
		</div>
		
		<div class="content-box">
			<h3>External or profesional stuff:</h3>
			<p>
				Got nothing special here for now.
				<?php /* <i class="fa fa-angle-right fa-fw"></i> <a href="#">Natrox Games</a>
				<small>- Games servers group</small>*/ ?>
			</p>
		</div>
	</div>
</div>

<?php include COMMONPATH . 'footer.php'; ?>

</body>
</html>