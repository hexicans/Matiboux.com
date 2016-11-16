<?php
if(!$_Oli->verifyAuthKey() OR $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) < $_Oli->translateUserRight('USER'))
	header('Location: ' . $_Oli->getShortcutLink('login'));

if($_Oli->getUrlParam(2) == 'delete' AND !empty($_Oli->getUrlParam(3))) {
	$paramData = urldecode($_Oli->getUrlParam(3));
	$selectedRequests = (!is_array($paramData)) ? ((is_array(unserialize($paramData))) ? unserialize($paramData) : [$paramData]) : $paramData;
	
	$errorStatus = '';
	foreach($selectedRequests as $eachKey) {
		if(!$_Oli->isExistAccountInfos('REQUESTS', array('id' => $eachKey))) {
			$errorStatus = 'UNKNOWN_REQUEST';
			break;
		}
		else if($_Oli->getAccountInfos('REQUESTS', 'username', array('id' => $eachKey)) != $_Oli->getAuthKeyOwner()) {
			$errorStatus = 'NOT_YOUR_REQUEST';
			break;
		}
	}
	
	if(!empty($errorStatus))
		$resultCode = $errorStatus;
	else if($_Oli->getUrlParam(4) != 'confirmed')
		$resultCode = 'CONFIRMATION_NEEDED';
	else {
		foreach($selectedRequests as $eachKey) {
			$_Oli->deleteAccountLines('REQUESTS', array('id' => $eachKey));
		}
		$resultCode = 'REQUEST_CANCELED';
	}
}
?>

<!DOCTYPE html>
<html>
<head>

<?php include THEMEPATH . 'head.php'; ?>
<?php $_Oli->loadCdnScript('js/serialize-php.min.js', false); ?>
<?php $_Oli->loadLocalScript('js/selector.js', false); ?>
<title>Pending requests - <?php echo $_Oli->getSetting('name'); ?></title>

</head>
<body>

<?php include THEMEPATH . 'header.php'; ?>

<div class="main">
	<div class="container-fluid">
		<div class="row">
			<div class="leftBar col-sm-3">
				<?php if($_Oli->getUrlParam(2) == 'delete' AND !empty($_Oli->getUrlParam(3)) AND $resultCode == 'CONFIRMATION_NEEDED') { ?>
					<div class="message message-highlight-danger">
						<p>
							<b>You asked to delete these selected requests</b>, please confirm you want to delete them <hr />
							<span class="text-success">
								<i class="fa fa-check fa-fw"></i>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo urlencode($_Oli->getUrlParam(3)); ?>/confirmed">I want to delete them</a>
							</span> <br />
							<span class="text-danger">
								<i class="fa fa-times fa-fw"></i>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/">I refuse to delete them</a>
							</span>
						</p>
					</div>
				<?php } else if($resultCode == 'UNKNOWN_REQUEST') { ?>
					<div class="message message-danger">
						<p>You tried to act on a request that not exist</p>
					</div>
				<?php } else if($resultCode == 'NOT_YOUR_REQUEST') { ?>
					<div class="message message-danger">
						<p>You tried to act on a request that is not yours</p>
					</div>
				<?php } else if($resultCode == 'REQUEST_DELETED') { ?>
					<div class="message message-success">
						<p>The selected requests have been deleted</p>
					</div>
				<?php } ?>
				
				<div class="message" id="script-message" style="display: none;">
					<p></p>
				</div>
				
				<?php $yourRequests = $_Oli->getAccountLines('REQUESTS', array('username' => $_Oli->getAuthKeyOwner()), true, true); ?>
				<div class="content-card">
					<h3>Pending requests</h3>
					<p>
						Keep an eye on all your valid pending requests you created.
						<?php if(!empty($yourRequests)) { ?> <hr />
							<span class="text-primary">
								<i class="fa fa-check-square fa-fw"></i>
								<a href="#selectAll" class="selectAll">Select All</a>
							</span> <br />
							<span class="text-muted">
								<i class="fa fa-square-o fa-fw"></i>
								<a href="#unselectAll" class="unselectAll">Unselect All</a>
							</span> <br />
							<span class="text-danger">
								<i class="fa fa-trash fa-fw"></i>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/delete/" class="deleteSelected">Delete Selected</a>
							</span>
						<?php } ?>
					</p>
				</div>
			</div>
			
			<div class="mainBar col-sm-9">
				<?php if(!empty($yourRequests)) { ?>
					<div class="content-card">
						<p>
							<i class="fa fa-sort-numeric-desc fa-fw"></i> Sorted from newest to oldest requests <br />
							Shows your pending requests for today, the <?php echo date('d', $now = time()); ?>
							<?php switch(date('m', $now)) {
								case 01: echo 'january'; break;
								case 02: echo 'february'; break;
								case 03: echo 'march'; break;
								case 04: echo 'april'; break;
								case 05: echo 'may'; break;
								case 06: echo 'june'; break;
								case 07: echo 'july'; break;
								case 08: echo 'august'; break;
								case 09: echo 'september'; break;
								case 10: echo 'october'; break;
								case 11: echo 'november'; break;
								case 12: echo 'december'; break;
							} ?> <?php echo date('Y', $now); ?> at <?php echo date('H:i', $now); ?> <br />
							Displayed time differences are truncated
						</p>
						
						<table class="table table-hover">
							<thead>
								<tr>
									<th class="selector-menu"><i class="fa fa-check fa-fw"></i></th>
									<th>Key</th>
									<th>Action</th>
									<th>Creation</th>
									<th>Validity</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php $countRequests = count($yourRequests); ?>
								<?php foreach(array_reverse($yourRequests) as $eachRequest) { ?>
									<?php if(strtotime($eachRequest['expire_date']) >= time()) { ?>
										<tr id="<?php echo $eachRequest['id']; ?>">
											<?php if(!empty($selectedRequests) AND in_array($eachRequest['id'], $selectedRequests)) { ?>
												<td class="selector checked">
													<i class="fa fa-check-square fa-fw"></i>
												</td>
											<?php } else { ?>
												<td class="selector">
													<i class="fa fa-square-o fa-fw"></i>
												</td>
											<?php } ?>
											
											<td><?php echo str_repeat('*', (strlen($eachRequest['activate_key']) <= 8) ? strlen($eachRequest['activate_key']) : 8); ?></td>
											<td><?php echo $eachRequest['action']; ?></td>
											<td>
												<?php $timeOutput = []; ?>
												<?php foreach($_Oli->dateDifference($eachRequest['request_date'], time(), true) as $eachUnit => $eachTime) { ?>
													<?php if(count($timeOutput) < 2) { ?>
														<?php if($eachTime > 0) { ?>
															<?php if($eachUnit == 'years') { ?>
																<?php $timeOutput[] = $eachTime . ' year' . (($eachTime > 1) ? 's' : ''); ?>
															<?php } else if($eachUnit == 'days') { ?>
																<?php $timeOutput[] = $eachTime . ' day' . (($eachTime > 1) ? 's' : ''); ?>
															<?php } else if($eachUnit == 'hours') { ?>
																<?php $timeOutput[] = $eachTime . ' hour' . (($eachTime > 1) ? 's' : ''); ?>
															<?php } else if($eachUnit == 'minutes') { ?>
																<?php $timeOutput[] = $eachTime . ' minute' . (($eachTime > 1) ? 's' : ''); ?>
															<?php } else if($eachUnit == 'seconds') { ?>
																<?php $timeOutput[] = $eachTime . ' second' . (($eachTime > 1) ? 's' : ''); ?>
															<?php } ?>
														<?php } ?>
													<?php } else break; ?>
												<?php } ?>
												
												<?php echo $timeOutput[0]; ?>
												<?php if(count($timeOutput) > 1) { ?>
													<small>
														<?php if(count($timeOutput) > 2) { ?>
															, <?php echo implode(', ', array_splice($timeOutput, 1, count($timeOutput) - 1)); ?>
														<?php } ?>
														and <?php echo $timeOutput[count($timeOutput) - 1]; ?>
													</small>
												<?php } ?> ago
											</td>
											<td>
												<?php $timeOutput = []; ?>
												<?php foreach($_Oli->dateDifference($eachRequest['request_date'], $eachRequest['expire_date'], true) as $eachUnit => $eachTime) { ?>
													<?php if(count($timeOutput) < 2) { ?>
														<?php if($eachTime > 0) { ?>
															<?php if($eachUnit == 'years') { ?>
																<?php $timeOutput[] = $eachTime . ' year' . (($eachTime > 1) ? 's' : ''); ?>
															<?php } else if($eachUnit == 'days') { ?>
																<?php $timeOutput[] = $eachTime . ' day' . (($eachTime > 1) ? 's' : ''); ?>
															<?php } else if($eachUnit == 'hours') { ?>
																<?php $timeOutput[] = $eachTime . ' hour' . (($eachTime > 1) ? 's' : ''); ?>
															<?php } else if($eachUnit == 'minutes') { ?>
																<?php $timeOutput[] = $eachTime . ' minute' . (($eachTime > 1) ? 's' : ''); ?>
															<?php } else if($eachUnit == 'seconds') { ?>
																<?php $timeOutput[] = $eachTime . ' second' . (($eachTime > 1) ? 's' : ''); ?>
															<?php } ?>
														<?php } ?>
													<?php } else break; ?>
												<?php } ?>
												
												During <?php echo $timeOutput[0]; ?>
												<?php if(count($timeOutput) > 1) { ?>
													<small>
														<?php if(count($timeOutput) > 2) { ?>
															, <?php echo implode(', ', array_splice($timeOutput, 1, count($timeOutput) - 1)); ?>
														<?php } ?>
														and <?php echo $timeOutput[count($timeOutput) - 1]; ?>
													</small>
												<?php } ?>
											</td>
											<td>
												<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/delete/<?php echo $eachRequest['id']; ?>" class="btn btn-danger btn-xs">
													Delete <i class="fa fa-trash fa-fw"></i>
												</a>
											</td>
										</tr>
									<?php } else { ?>
										<?php $_Oli->deleteAccountLines('REQUESTS', array('id' => $eachRequest['id'])); ?>
										<?php $countRequests--; ?>
										
										<tr class="danger">
											<td></td>
											<td colspan="5">
												An expired request have been deleted
											</td>
										</tr>
									<?php } ?>
								<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="4">
										<a href="#selectAll" class="selectAll btn btn-primary btn-xs">
											Select All <i class="fa fa-check-square fa-fw"></i>
										</a>
										<a href="#unselectAll" class="unselectAll btn btn-danger btn-xs">
											Unselect All <i class="fa fa-square-o fa-fw"></i>
										</a>
									</td>
									<td><?php echo $countRequests; ?> <small>request<?php if($countRequests > 1) { ?>s<?php } ?></small></td>
									<td>
										<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/delete/" class="deleteSelected btn btn-danger btn-xs">
											Selected <i class="fa fa-trash fa-fw"></i>
										</a>
									</td>
								</tr>
							</tfoot>
						</table>
					</div>
				<?php } else { ?>
					<div class="message text-danger">
						<h3>You haven't got any pending request</h3>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<?php $_Oli->loadEndHtmlFiles(); ?>

</body>
</html>