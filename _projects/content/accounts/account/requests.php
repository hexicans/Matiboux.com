<?php
if($_Oli->getUserRightLevel() < $_Oli->translateUserRight('USER')) header('Location: ' . $_Oli->getShortcutLink('login'));

if($_Oli->getUrlParam(2) == 'delete' AND !empty($_Oli->getUrlParam(3))) {
	$paramData = urldecode($_Oli->getUrlParam(3));
	$selectedRequests = !is_array($paramData) ? (is_array(json_decode($paramData, true)) ? json_decode($paramData, true) : [$paramData]) : $paramData;
	
	foreach($selectedRequests as $eachKey) {
		if(!$_Oli->isExistAccountInfos('REQUESTS', array('id' => $eachKey))) $errorStatus = 'D:You tried to delete a session that not exists';
		else if($_Oli->getAccountInfos('REQUESTS', 'auth_key', array('id' => $eachKey)) == $_Oli->getAuthKey()) $errorStatus = 'D:You tried to delete your current session';
		else if($_Oli->getAccountInfos('REQUESTS', 'username', array('id' => $eachKey)) != $_Oli->getAuthKeyOwner()) $errorStatus = 'D:You tried to delete a session that not belongs to you';
		
		if(isset($errorStatus)) break;
	}
	
	if(!empty($errorStatus)) $resultCode = $errorStatus;
	else if($_Oli->getUrlParam(4) != 'confirmed') {
		$resultCode = 'P:Please confirm your action below (just click "Confirm all")';
		$confirmationNeeded = true;
	}
	else {
		foreach($selectedRequests as $eachKey) {
			if(!$_Oli->deleteAccountLines('REQUESTS', array('id' => $eachKey))) {
				$deleteFailed = true;
				break;
			}
		}
		
		if(!$deleteFailed) $resultCode = 'S:The selected requests have been successfully deleted';
		else $resultCode = 'D:An error occured while deleting your requests'; 
	}
}
?>

<!DOCTYPE html>
<html>
<head>

<?php include COMMONPATH . 'head.php'; ?>
<?php $_Oli->loadCommonScript('js/selector.js', false); ?>
<title>Your pending requests - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include THEMEPATH . 'header.php'; ?>

<div class="title-banner">
	<i class="fa fa-pencil fa-fw"></i> Your pending requests
</div>

<div class="page-content">
	<div class="container">
		<?php if(isset($resultCode)) { ?>
			<?php
			list($prefix, $message) = explode(':', $resultCode, 2);
			if($prefix == 'P') $type = 'message-primary';
			else if($prefix == 'S') $type = 'message-success';
			else if($prefix == 'I') $type = 'message-info';
			else if($prefix == 'W') $type = 'message-warning';
			else if($prefix == 'D') $type = 'message-danger';
			?>
			
			<div class="message <?php echo $type; ?>">
				<?php echo $message; ?>
			</div>
		<?php } ?>
		<div id="script-message"></div>
		
		<div class="content-box">
			<?php $yourRequests = $_Oli->getAccountLines('REQUESTS', $_Oli->getAuthKeyOwner(), true, true); ?>
			<?php if(!empty($yourRequests)) { ?>
				<p class="text-muted">
					<i class="fa fa-sort-numeric-desc fa-fw"></i> Sorted from newest to oldest requests. <br />
					On this page, you'll find all your pending requests. <br />
					Expired requests are automatically deleted every time you visit this page. <br />
					<i class="small">
						Note that the displayed keygen is not the real one, but its (shortened) sha1 hash. <br />
						Please also note that the shown time differences are truncated.
					</i>
				</p>
				
				<table class="table table-hover">
					<thead>
						<tr>
							<th class="selector-menu"><i class="fa fa-check fa-fw"></i></th>
							<th>KeyGen</th>
							<th>Action</th>
							<th>Created</th>
							<th>Active</th>
							<?php if(isset($selectedRequests) AND $confirmationNeeded) { ?>
								<th>
									<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/delete/<?php echo urlencode($_Oli->getUrlParam(3)); ?>/confirmed" class="btn btn-warning btn-xs">
										Confirm all <i class="fa fa-trash fa-fw"></i>
									</a>
								</th>
							<?php } else { ?>
								<th colspan="2"></th>
							<?php } ?>
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
									
									<td class="text-muted small"><?php echo substr(sha1($eachRequest['activate_key']), 0, 7); ?></td>
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
										
										<?php $timeOutputCount = count($timeOutput); ?>
										<?php if(!empty($timeOutput)) { ?>
											<?php $timeOutput[0]; ?><?php if($timeOutputCount > 2) { ?>,<?php } ?>
											<?php echo $timeOutput[0]; ?><?php if($timeOutputCount > 2) { ?>,<?php } ?>
											<?php if($timeOutputCount > 1) { ?>
												<small>
													<?php if($timeOutputCount > 2) { ?>
														<?php echo implode(', ', array_splice($timeOutput, 1, $timeOutputCount)); ?>
													<?php } ?>
													and <?php echo $timeOutput[$timeOutputCount - 1]; ?>
												</small>
											<?php } ?> ago
										<?php } else { ?>
											Now!
										<?php } ?>
									</td>
									<td>
										<?php if($_Oli->getUrlParam(2) == 'edit' AND $eachRequest['id'] == $_Oli->getUrlParam(3) AND empty($updatedSessionId)) { ?>
											<span class="text-info">
												<i class="fa fa-pencil fa-fw"></i> Editing
											</span>
										<?php } else { ?>
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
											
											<?php $timeOutputCount = count($timeOutput); ?>
											<?php if(!empty($timeOutput)) { ?>
												For <?php echo $timeOutput[0]; ?><?php if($timeOutputCount > 2) { ?>,<?php } ?>
												<?php if($timeOutputCount > 1) { ?>
													<small>
														<?php if($timeOutputCount > 2) { ?>
															<?php echo implode(', ', array_splice($timeOutput, 1, $timeOutputCount - 1)); ?>
														<?php } ?>
														and <?php echo $timeOutput[$timeOutputCount - 1]; ?>
													</small>
												<?php } ?>
											<?php } else { ?>
												Never
											<?php } ?>
										<?php } ?>
									</td>
									<td>
										<?php if($eachRequest['auth_key'] != $_Oli->getAuthKey()) { ?>
											<?php if(isset($selectedRequests) AND in_array($eachRequest['id'], $selectedRequests) AND $confirmationNeeded) { ?>
												<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/delete/<?php echo $eachRequest['id']; ?>/confirmed" class="btn btn-warning btn-xs">
													Confirm <i class="fa fa-trash fa-fw"></i>
												</a>
											<?php } else { ?>
												<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/delete/<?php echo $eachRequest['id']; ?>" class="btn btn-danger btn-xs">
													Delete <i class="fa fa-trash fa-fw"></i>
												</a>
											<?php } ?>
										<?php } ?>
									</td>
								</tr>
							<?php } else { ?>
								<?php $_Oli->deleteAccountLines('REQUESTS', array('id' => $eachRequest['id'])); ?>
								<?php $countRequests--; ?>
								
								<tr class="danger">
									<td></td>
									<td colspan="7">
										An expired session have been deleted
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
							<td><?php echo $countRequests; ?> <small>session<?php if($countRequests > 1) { ?>s<?php } ?></small></td>
							<td>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/delete/" class="deleteSelected btn btn-danger btn-xs">
									Selected <i class="fa fa-trash fa-fw"></i>
								</a>
							</td>
						</tr>
					</tfoot>
				</table>
			<?php } else { ?>
				<h3>You don't have any pending requests</h3>
				<p>
					Requests can be created for nearly anything: <br />
					Account activation, password change, phone confirmation... <?php /*<br />
					It could be usefull be developers too, with auth requests for example.*/ ?>
				</p>
			<?php } ?>
		</div>
	</div>
</div>

<?php include COMMONPATH . 'footer.php'; ?>

</body>
</html>