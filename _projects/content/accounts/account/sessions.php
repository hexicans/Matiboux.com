<?php
if($_Oli->getUserRightLevel() < $_Oli->translateUserRight('USER')) header('Location: ' . $_Oli->getShortcutLink('login'));

if($_Oli->getUrlParam(2) == 'edit' AND !empty($_Oli->getUrlParam(3))) {
	if(!$sessionInfos = $_Oli->getAccountLines('SESSIONS', array('id' => $_Oli->getUrlParam(3)))) $errorStatus = 'D:You tried to edit a session which not exists';
	else if($sessionInfos['username'] != $_Oli->getAuthKeyOwner()) $errorStatus = 'D:You tried to edit a session which not belongs to you';
	else if(strtotime($sessionInfos['expire_date']) >= time()) {
		$editingSession = $sessionInfos['id'];
		
		if(!$_Oli->isEmptyPostVars()) {
			if(empty($_Oli->getPostVars('expireDate')) OR empty($_Oli->getPostVars('expireTime'))) $errorStatus = 'D:You did not enter the new expiration date';
			else if(strtotime($_Oli->getPostVars('expireDate') . ' ' . $_Oli->getPostVars('expireTime')) > strtotime($sessionInfos['expire_date'])) $errorStatus = 'D:You cannot extend the session lifetime!';
			else {
				$expireDate = date('Y-m-d H:i:s', strtotime($_Oli->getPostVars('expireDate') . ' ' . $_Oli->getPostVars('expireTime')));
				
				if($_Oli->updateAccountInfos('SESSIONS', array('expire_date' => $expireDate), array('id' => $sessionInfos['id']))) {
					$resultCode = 'S:Your session have been successfully updated';
					$editingSession = false;
				}
				else $resultCode = 'D:An error occured while updating your session'; 
			}
		}
	}
}
else if($_Oli->getUrlParam(2) == 'delete' AND !empty($_Oli->getUrlParam(3))) {
	$paramData = urldecode($_Oli->getUrlParam(3));
	$selectedSessions = !is_array($paramData) ? (is_array(json_decode($paramData, true)) ? json_decode($paramData, true) : [$paramData]) : $paramData;
	
	foreach($selectedSessions as $eachKey) {
		if(!$sessionsInfos = $_Oli->getAccountLines('SESSIONS', array('id' => $eachKey))) $errorStatus = 'D:You tried to delete a session which not exists';
		else if($sessionsInfos['auth_key'] == sha1($_Oli->getAuthKey())) $errorStatus = 'D:You tried to delete your current session';
		else if($sessionsInfos['username'] != $_Oli->getAuthKeyOwner()) $errorStatus = 'D:You tried to delete a session which not belongs to you';
		
		if(isset($errorStatus)) break;
	}
	
	if(!empty($errorStatus)) $resultCode = $errorStatus;
	else if($_Oli->getUrlParam(4) != 'confirmed') {
		$resultCode = 'P:Please confirm your action below (just click "Confirm all")';
		$confirmationNeeded = true;
	}
	else {
		foreach($selectedSessions as $eachKey) {
			if(!$_Oli->deleteAccountLines('SESSIONS', array('id' => $eachKey))) {
				$deleteFailed = true;
				break;
			}
		}
		
		if(!$deleteFailed) $resultCode = 'S:The selected sessions have been successfully deleted';
		else $resultCode = 'D:An error occured while deleting your sessions'; 
	}
}
?>

<!DOCTYPE html>
<html>
<head>

<?php include COMMONPATH . 'head.php'; ?>
<?php $_Oli->loadCommonScript('js/selector.js', false); ?>
<title>Your active sessions - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include THEMEPATH . 'header.php'; ?>

<div class="title-banner">
	<i class="fa fa-pencil fa-fw"></i> Your active sessions
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
		<div id="message" style="display: none;"></div>
		
		<?php if($editingSession) { ?>
			<div class="content-box">
				<form action="<?php echo $_Oli->getUrlParam(0); ?>form.php" class="form form-horizontal" method="post">
					<?php $sessionInfos = $_Oli->getAccountLines('SESSIONS', array('id' => $_Oli->getUrlParam(3))); ?>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Infos</label>
						<div class="col-md-10">
							Session ID #<?php echo $sessionInfos['id']; ?> <br />
							It expires the <?php echo date('d/m/Y', strtotime($sessionInfos['expire_date'])); ?> at <?php echo date('H:i', strtotime($sessionInfos['expire_date'])); ?> <br />
							
							<?php $timeOutput = []; ?>
							<?php foreach($_Oli->dateDifference(time(), $sessionInfos['expire_date'], true) as $eachUnit => $eachTime) { ?>
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
							<span class="text-muted">
								<i class="fa fa-angle-right fa-fw"></i>
								
								So your session will be still active for <?php echo $timeOutput[0]; ?><?php if($timeOutputCount > 2) { ?>,<?php } ?>
									<?php if($timeOutputCount > 1) { ?>
										<small>
											<?php if($timeOutputCount > 2) { ?>
												<?php echo implode(', ', array_splice($timeOutput, 1, $timeOutputCount - 1)); ?>
											<?php } ?>
											and <?php echo $timeOutput[$timeOutputCount - 1]; ?>
										</small>
									<?php } ?>
								<?php } else { ?>
									Your session isn't active anymore
								<?php } ?>
							</span>
							
							<p class="help-block">
								<span class="text-danger">
									<i class="fa fa-warning fa-fw"></i> You can't extend the session lifetime, you can only reduce it
								</span>
							</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Expiration</label> <div class="clearfix visible-sm-block"></div>
						<div class="col-md-5 col-sm-6">
							<input type="date" class="form-control" name="expireDate" value="<?php echo date('Y-m-d', strtotime($sessionInfos['expire_date'])); ?>" max="<?php echo date('Y-m-d', strtotime($sessionInfos['expire_date'])); ?>" />
						</div>
						<div class="col-md-5 col-sm-6">
							<input type="time" class="form-control" name="expireTime" value="<?php echo date('H:i', strtotime($sessionInfos['expire_date'])); ?>" />
						</div>
					</div> <hr />
					
					<div class="form-group">
						<div class="col-md-offset-2 col-md-10">
							<button type="submit" class="btn btn-primary"><i class="fa fa-cloud-upload fa-fw"></i> Update session</button>
							<button type="reset" class="btn btn-default"><i class="fa fa-refresh fa-fw"></i> Reset</button>
							<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/" class="btn btn-danger"><i class="fa fa-times fa-fw"></i> Abort</a>
						</div>
					</div>
				</form>
			</div>
		<?php } ?>
		
		<div class="content-box">
			<?php $yourSessions = $_Oli->getAccountLines('SESSIONS', $_Oli->getAuthKeyOwner(), true, true); ?>
			<?php if(!empty($yourSessions)) { ?>
				<p class="text-muted">
					<i class="fa fa-sort-numeric-desc fa-fw"></i> Sorted from newest to oldest sessions. <br />
					On this page, you'll find your current session and all your other active sessions. <br />
					Expired sessions are automatically deleted every time you visit this page. <br />
					<i class="small">
						Note that the displayed authkey is not the real one, but its (shortened) sha1 hash. <br />
						Please also note that the shown time differences are truncated.
					</i>
				</p>
				
				<table class="table table-hover">
					<thead>
						<tr>
							<th class="selector-menu"><i class="fa fa-check fa-fw"></i></th>
							<th>AuthKey</th>
							<th>IP address</th>
							<th>Logged</th>
							<th>Active</th>
							<th>Last seen</th>
							<?php if(isset($selectedSessions) AND $confirmationNeeded) { ?>
								<th></th>
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
						<?php $countSessions = count($yourSessions); ?>
						<?php foreach(array_reverse($yourSessions) as $eachSession) { ?>
							<?php if(strtotime($eachSession['expire_date']) >= time()) { ?>
								<tr <?php if($eachSession['auth_key'] == sha1($_Oli->getAuthKey())) { ?>class="info"<?php } ?> id="<?php echo $eachSession['id']; ?>">
									<?php if($eachSession['auth_key'] != sha1($_Oli->getAuthKey())) { ?>
										<?php if(!empty($selectedSessions) AND in_array($eachSession['id'], $selectedSessions)) { ?>
											<td class="selector checked">
												<i class="fa fa-check-square fa-fw"></i>
											</td>
										<?php } else { ?>
											<td class="selector">
												<i class="fa fa-square-o fa-fw"></i>
											</td>
										<?php } ?>
									<?php } else { ?>
										<td></td>
									<?php } ?>
									
									<td class="text-muted small"><?php echo substr($eachSession['auth_key'], 0, 7); ?></td>
									<td><?php echo $eachSession['user_ip']; ?></td>
									<td>
										<?php $timeOutput = []; ?>
										<?php foreach($_Oli->dateDifference($eachSession['login_date'], time(), true) as $eachUnit => $eachTime) { ?>
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
										<?php if($editingSession == $eachSession['id']) { ?>
											<span class="text-info">
												<i class="fa fa-pencil fa-fw"></i> Editing
											</span>
										<?php } else { ?>
											<?php $timeOutput = []; ?>
											<?php foreach($_Oli->dateDifference($eachSession['login_date'], $eachSession['expire_date'], true) as $eachUnit => $eachTime) { ?>
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
										<?php if($eachSession['auth_key'] != sha1($_Oli->getAuthKey())) { ?>
											<?php $timeOutput = []; ?>
											<?php foreach($_Oli->dateDifference($eachSession['update_date'], time(), true) as $eachUnit => $eachTime) { ?>
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
												<?php echo $timeOutput[0]; ?><?php if($timeOutputCount > 2) { ?>,<?php } ?>
												<?php if($timeOutputCount > 1) { ?>
													<small>
														<?php if($timeOutputCount > 2) { ?>
															<?php echo implode(', ', array_splice($timeOutput, 1, $timeOutputCount - 1)); ?>
														<?php } ?>
														and <?php echo $timeOutput[$timeOutputCount - 1]; ?>
													</small>
												<?php } ?> ago
											<?php } else { ?>
												now
											<?php } ?>
										<?php } else { ?>
											<span class="text-primary"><b>Your current session</b></span>
										<?php } ?>
									</td>
									<td>
										<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/edit/<?php echo $eachSession['id']; ?>" class="btn btn-primary btn-xs">
											Edit <i class="fa fa-pencil fa-fw"></i>
										</a>
									</td>
									<td>
										<?php if($eachSession['auth_key'] != sha1($_Oli->getAuthKey())) { ?>
											<?php if(isset($selectedSessions) AND in_array($eachSession['id'], $selectedSessions) AND $confirmationNeeded) { ?>
												<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/delete/<?php echo $eachSession['id']; ?>/confirmed" class="btn btn-warning btn-xs">
													Confirm <i class="fa fa-trash fa-fw"></i>
												</a>
											<?php } else { ?>
												<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/delete/<?php echo $eachSession['id']; ?>" class="btn btn-danger btn-xs">
													Delete <i class="fa fa-trash fa-fw"></i>
												</a>
											<?php } ?>
										<?php } ?>
									</td>
								</tr>
							<?php } else { ?>
								<?php $_Oli->deleteAccountLines('SESSIONS', array('id' => $eachSession['id'])); ?>
								<?php $countSessions--; ?>
								
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
							<td colspan="5">
								<a href="#selectAll" class="selectAll btn btn-primary btn-xs">
									Select All <i class="fa fa-check-square fa-fw"></i>
								</a>
								<a href="#unselectAll" class="unselectAll btn btn-danger btn-xs">
									Unselect All <i class="fa fa-square-o fa-fw"></i>
								</a>
							</td>
							<td colspan="2"><?php echo $countSessions; ?> <small>session<?php if($countSessions > 1) { ?>s<?php } ?></small></td>
							<td>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/delete/" class="deleteSelected btn btn-danger btn-xs">
									Selected <i class="fa fa-trash fa-fw"></i>
								</a>
							</td>
						</tr>
					</tfoot>
				</table>
			<?php } else { ?>
				<h3>You don't have any active sessions</h3>
				<p>
					It is IMPOSSIBLE! How did you do this? Tell me, I need to know. <br />
					Please contact an administrator quickly about this
				</p>
			<?php } ?>
		</div>
	</div>
</div>

<?php include COMMONPATH . 'footer.php'; ?>

</body>
</html>