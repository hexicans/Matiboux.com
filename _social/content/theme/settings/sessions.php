<?php
if(!$_Oli->verifyAuthKey() OR $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) < $_Oli->translateUserRight('USER'))
	header('Location: ' . $_Oli->getShortcutLink('login'));

if($_Oli->getUrlParam(2) == 'edit' AND !empty($_Oli->getUrlParam(3)) AND !$_Oli->isEmptyPostVars()) {
	if(empty($_Oli->getPostVars('expireDate')) OR empty($_Oli->getPostVars('expireTime')))
		$resultCode = 'EXPIRE_DATE_EMPTY';
	else if(strtotime($_Oli->getPostVars('expireDate') . ' ' . $_Oli->getPostVars('expireTime')) < time())
		$resultCode = 'LOW_EXPIRE_DATE';
	else if(strtotime($_Oli->getPostVars('expireDate') . ' ' . $_Oli->getPostVars('expireTime')) > strtotime($_Oli->getAccountInfos('SESSIONS', 'expire_date', array('id' => $_Oli->getUrlParam(3)))))
		$resultCode = 'HIGH_EXPIRE_DATE';
	else if(!$_Oli->isExistAccountInfos('SESSIONS', array('id' => $_Oli->getUrlParam(3))))
		$resultCode = 'UNKNOWN_SESSION';
	else if($_Oli->getAccountInfos('SESSIONS', 'username', array('id' => $_Oli->getUrlParam(3))) == $_Oli->getAuthKeyOwner()) {
		$updatedSessionId = $_Oli->getUrlParam(3);
		
		$expireDate = date('Y-m-d H:i:s', strtotime(((!empty($_Oli->getPostVars('expireDate'))) ? $_Oli->getPostVars('expireDate') : date('Y-m-d')) . ((!empty($_Oli->getPostVars('expireTime'))) ? $_Oli->getPostVars('expireTime') : date('H:i:s'))));
		
		$_Oli->updateAccountInfos('SESSIONS', array('expire_date' => $expireDate), array('id' => $_Oli->getUrlParam(3)));
		$resultCode = 'SESSION_EDITED';
	}
	else
		$resultCode = 'NOT_YOUR_SESSION';
}
else if($_Oli->getUrlParam(2) == 'delete' AND !empty($_Oli->getUrlParam(3))) {
	$paramData = urldecode($_Oli->getUrlParam(3));
	$selectedSessions = (!is_array($paramData)) ? ((is_array(unserialize($paramData))) ? unserialize($paramData) : [$paramData]) : $paramData;
	
	$errorStatus = '';
	foreach($selectedSessions as $eachKey) {
		if(!$_Oli->isExistAccountInfos('SESSIONS', array('id' => $eachKey))) {
			$errorStatus = 'UNKNOWN_SESSION';
			break;
		}
		else if($_Oli->getAccountInfos('SESSIONS', 'auth_key', array('id' => $eachKey)) == $_Oli->getAuthKey()) {
			$errorStatus = 'CURRENT_SESSION';
			break;
		}
		else if($_Oli->getAccountInfos('SESSIONS', 'username', array('id' => $eachKey)) != $_Oli->getAuthKeyOwner()) {
			$errorStatus = 'NOT_YOUR_SESSION';
			break;
		}
	}
	
	if(!empty($errorStatus))
		$resultCode = $errorStatus;
	else if($_Oli->getUrlParam(4) != 'confirmed')
		$resultCode = 'CONFIRMATION_NEEDED';
	else {
		foreach($selectedSessions as $eachKey) {
			$_Oli->deleteAccountLines('SESSIONS', array('id' => $eachKey));
		}
		$resultCode = 'SESSION_DELETED';
	}
}
?>

<!DOCTYPE html>
<html>
<head>

<?php include THEMEPATH . 'head.php'; ?>
<?php $_Oli->loadCdnScript('js/serialize-php.min.js', false); ?>
<?php $_Oli->loadLocalScript('js/selector.js', false); ?>
<title>Valid Sessions - <?php echo $_Oli->getSetting('name'); ?></title>

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
							<b>You asked to delete these selected sessions</b>, please confirm you want to delete them <hr />
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
				<?php } else if($resultCode == 'EXPIRE_DATE_EMPTY') { ?>
					<div class="message message-danger">
						<p>The expiration date can not be left empty</p>
					</div>
				<?php } else if($resultCode == 'LOW_EXPIRE_DATE') { ?>
					<div class="message message-danger">
						<p>The expiration date can not be prior to now</p>
					</div>
				<?php } else if($resultCode == 'HIGH_EXPIRE_DATE') { ?>
					<div class="message message-danger">
						<p>The expiration date can not be extended</p>
					</div>
				<?php } else if($resultCode == 'UNKNOWN_SESSION') { ?>
					<div class="message message-danger">
						<p>You tried to act on a session that not exist</p>
					</div>
				<?php } else if($resultCode == 'CURRENT_SESSION') { ?>
					<div class="message message-danger">
						<p>You tried to act on your current session</p>
					</div>
				<?php } else if($resultCode == 'NOT_YOUR_SESSION') { ?>
					<div class="message message-danger">
						<p>You tried to act on a request that is not yours</p>
					</div>
				<?php } else if($resultCode == 'SESSION_EDITED') { ?>
					<div class="message message-success">
						<p>The selected session have been edited</p>
					</div>
				<?php } else if($resultCode == 'SESSION_DELETED') { ?>
					<div class="message message-success">
						<p>The selected sessions have been deleted</p>
					</div>
				<?php } ?>
				
				<div class="message" id="script-message" style="display: none;">
					<p></p>
				</div>
				
				<?php $yourSessions = $_Oli->getAccountLines('SESSIONS', array('username' => $_Oli->getAuthKeyOwner()), true, true); ?>
				<div class="content-card">
					<h3>Active sessions</h3>
					<p>
						Keep an eye on all your active sessions. <br />
						Don't let anyone keep an unautorized access to your account.
						<?php if(!empty($yourSessions)) { ?> <hr />
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
				<?php if($_Oli->getUrlParam(2) == 'edit' AND !empty($_Oli->getUrlParam(3)) AND $_Oli->isExistAccountInfos('SESSIONS', array('id' => $_Oli->getUrlParam(3))) AND strtotime($_Oli->getAccountInfos('SESSIONS', 'expire_date', array('id' => $_Oli->getUrlParam(3)))) >= time() AND empty($updatedSessionId)) { ?>
					<div class="content-card">
						<?php $sessionInfos = $_Oli->getAccountLines('SESSIONS', array('id' => $_Oli->getUrlParam(3))); ?>
						<form action="<?php echo $_Oli->getUrlParam(0); ?>form.php" class="form form-horizontal" method="post">
							<h2>Editing a session</h2>
							<div class="form-group">
								<label class="col-sm-2 control-label">Infos</label>
								<div class="col-sm-10">
									ID : #<?php echo $_Oli->getUrlParam(3); ?> <br />
									Key : <?php echo str_repeat('*', (strlen($sessionInfos['auth_key']) <= 8) ? strlen($sessionInfos['auth_key']) : 8); ?> <br />
									IP address : <?php echo $sessionInfos['user_ip']; ?> <br />
									Logged the <?php echo date('d', strtotime($sessionInfos['login_date'])); ?>
									<?php switch(date('m', strtotime($sessionInfos['login_date']))) {
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
									} ?> <?php echo date('Y', strtotime($sessionInfos['login_date'])); ?> at <?php echo date('H:i', strtotime($sessionInfos['login_date'])); ?> <br />
									Expire : <?php echo date('d', strtotime($sessionInfos['expire_date'])); ?>
									<?php switch(date('m', strtotime($sessionInfos['expire_date']))) {
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
									} ?> <?php echo date('Y', strtotime($sessionInfos['expire_date'])); ?> at <?php echo date('H:i', strtotime($sessionInfos['expire_date'])); ?> <br />
									
									<?php $timeOutput = []; ?>
									<?php foreach($_Oli->dateDifference($sessionInfos['login_date'], $sessionInfos['expire_date'], true) as $eachUnit => $eachTime) { ?>
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
									
									<span class="text-muted">
										<i class="fa fa-angle-right fa-fw"></i>
										This means that this session is valid during <?php echo $timeOutput[0]; ?>
										<?php if(count($timeOutput) > 1) { ?>
											<small>
												<?php if(count($timeOutput) > 2) { ?>
													, <?php echo implode(', ', array_splice($timeOutput, 1, count($timeOutput) - 2)); ?>
												<?php } ?>
												and <?php echo $timeOutput[count($timeOutput) - 1]; ?>
											</small>
										<?php } ?>
									</span>
									
									<p class="help-block">
										<span class="text-danger">
											<i class="fa fa-warning fa-fw"></i> You can't extend the expiration date but only reduce it
										</span>
									</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Expiration</label>
								<div class="col-sm-5">
									<input type="date" class="form-control" name="expireDate" value="<?php echo date('Y-m-d', strtotime($sessionInfos['expire_date'])); ?>" max="<?php echo date('Y-m-d', strtotime($sessionInfos['expire_date'])); ?>" />
								</div>
								<div class="col-sm-5">
									<input type="time" class="form-control" name="expireTime" value="<?php echo date('H:i', strtotime($sessionInfos['expire_date'])); ?>" />
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="submit" class="btn btn-primary"><i class="fa fa-pencil fa-fw"></i> Mettre à jour</button>
									<button type="reset" class="btn btn-default"><i class="fa fa-refresh fa-fw"></i> Réinitialiser</button>
								</div>
							</div>
						</form>
					</div> <hr />
				<?php } ?>
				
				<?php if(!empty($yourSessions)) { ?>
					<div class="content-card">
						<p>
							<i class="fa fa-sort-numeric-desc fa-fw"></i> Sorted from newest to oldest requests <br />
							Shows your valid sessions for today, the <?php echo date('d', $now = time()); ?>
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
									<th>IP address</th>
									<th>Logged</th>
									<th>Validity</th>
									<th>Last use</th>
									<th colspan="2"></th>
								</tr>
							</thead>
							<tbody>
								<?php $countSessions = count($yourSessions); ?>
								<?php foreach(array_reverse($yourSessions) as $eachSession) { ?>
									<?php if(strtotime($eachSession['expire_date']) >= time()) { ?>
										<tr <?php if($eachSession['auth_key'] == $_Oli->getAuthKey()) { ?>class="info"<?php } ?> id="<?php echo $eachSession['id']; ?>">
											<?php if($eachSession['auth_key'] != $_Oli->getAuthKey()) { ?>
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
											
											<td><?php echo str_repeat('*', (strlen($eachSession['auth_key']) <= 8) ? strlen($eachSession['auth_key']) : 8); ?></td>
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
												<?php if($_Oli->getUrlParam(2) == 'edit' AND $eachSession['id'] == $_Oli->getUrlParam(3) AND empty($updatedSessionId)) { ?>
													<span class="text-info">
														<i class="fa fa-angle-up fa-fw"></i> Edition
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
													
													During <?php echo $timeOutput[0]; ?>
													<?php if(count($timeOutput) > 1) { ?>
														<small>
															<?php if(count($timeOutput) > 2) { ?>
																, <?php echo implode(', ', array_splice($timeOutput, 1, count($timeOutput) - 1)); ?>
															<?php } ?>
															and <?php echo $timeOutput[count($timeOutput) - 1]; ?>
														</small>
													<?php } ?>
												<?php } ?>
											</td>
											<td>
												<?php if($eachSession['auth_key'] != $_Oli->getAuthKey()) { ?>
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
													
													<?php echo $timeOutput[0]; ?>
													<?php if(count($timeOutput) > 1) { ?>
														<small>
															<?php if(count($timeOutput) > 2) { ?>
																, <?php echo implode(', ', array_splice($timeOutput, 1, count($timeOutput) - 1)); ?>
															<?php } ?>
															and <?php echo $timeOutput[count($timeOutput) - 1]; ?>
														</small>
													<?php } ?> ago
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
												<?php if($eachSession['auth_key'] != $_Oli->getAuthKey()) { ?>
													<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/delete/<?php echo $eachSession['id']; ?>" class="btn btn-danger btn-xs">
														Delete <i class="fa fa-trash fa-fw"></i>
													</a>
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
					</div>
				<?php } else { ?>
					<div class="message text-danger">
						<h3>You haven't got any valid sessions</h3>
						<p>
							I have to warn you.. <br />
							You know.. <br />
							You are not supposed to see this.. <br />
							You should have at least one session: your current session..
						</p>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<?php $_Oli->loadEndHtmlFiles(); ?>

</body>
</html>