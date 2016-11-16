<?php
if(!$_Oli->verifyAuthKey() OR $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) < $_Oli->translateUserRight('USER'))
	header('Location: ' . $_Oli->getShortcutLink('login'));

$pokeAdminsMails = [
	// $_Oli->getAccountInfos('ACCOUNTS', 'email', array('username' => $_Oli->getSetting('owner'))),
	'support@' . $_Oli->getSetting('domain')
];
if($_Oli->getUrlParam(2) == 'view' AND !empty($_Oli->getUrlParam(3)) AND !$_Oli->isEmptyPostVars()) {
	if($_Oli->getUrlParam(2) == 'view' AND !empty($_Oli->getUrlParam(3)) AND !$_Oli->isEmptyPostVars()) {
		if(empty($_Oli->getPostVars('message')))
			$resultCode = 'MESSAGE_EMPTY';
		else if($_Oli->isExistInfosMySQL('support_tickets', array('id' => $eachKey)) != $_Oli->getAuthKeyOwner()) {
			$title = $_Oli->getInfosMySQL('support_tickets', 'title', array('ticket_key' => $_Oli->getUrlParam(3)));
			$owner = $_Oli->getInfosMySQL('support_tickets', 'owner', array('ticket_key' => $_Oli->getUrlParam(3)));
			$messages = $_Oli->getInfosMySQL('support_tickets', 'messages', array('ticket_key' => $_Oli->getUrlParam(3)));
			$creationDate = date('Y-m-d H:i:s');
			
			$messages[] = array('message' => $_Oli->getPostVars('message'), 'username' => $_Oli->getAuthKeyOwner(), 'postDate' => $creationDate);
			$lastMessageInfos = array('username' => $_Oli->getAuthKeyOwner(), 'postDate' => $creationDate);
			
			while(strlen(serialize($messages)) > (2 ** 16 - 1)) {
				array_shift($messages);
			}
			
			if($_Oli->updateInfosMySQL('support_tickets', array('messages' => $messages, 'last_message_infos' => $lastMessageInfos), array('ticket_key' => $_Oli->getUrlParam(3)))) {
				if($owner != $_Oli->getAuthKeyOwner()) {
					/** Client Mail */
					$clientMail = $_Oli->getAccountInfos('ACCOUNTS', 'email', array('username' => $owner));
					$clientSubject = 'Un admin vous a répondu ! - #' . $_Oli->getUrlParam(3);
					$clientMessage = file_get_contents($_Oli->getUrlParam(0) . 'generate-mail.php/ticket-anwser/client/' . urlencode(serialize(array('title' => $title, 'owner' => $owner, 'ticketKey' => $_Oli->getUrlParam(3)))));
					$clientHeaders = 'From: noreply@' . $_Oli->getSetting('domain') . "\r\n";
					$clientHeaders .= 'MIME-Version: 1.0' . "\r\n";
					$clientHeaders .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					mail($clientMail, $clientSubject, utf8_decode($clientMessage), $clientHeaders);
				}
				else {
					/** Admin Mail */
					$adminSubject = 'Une réponse du client - #' . $_Oli->getUrlParam(3);
					$adminMessage = file_get_contents($_Oli->getUrlParam(0) . 'generate-mail.php/ticket-anwser/admin/' . urlencode(serialize(array('title' => $title, 'owner' => $_Oli->getAuthKeyOwner(), 'ticketKey' => $_Oli->getUrlParam(3)))));
					$adminHeaders = 'From: noreply@' . $_Oli->getSetting('domain') . "\r\n";
					$adminHeaders .= 'MIME-Version: 1.0' . "\r\n";
					$adminHeaders .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					foreach($pokeAdminsMails as $eachMail) {
						if(!mail($eachMail, $adminSubject, utf8_decode($adminMessage), $adminHeaders)) break;
					}
				}
				
				$resultCode = 'TICKET_UPDATED';
			}
			else
				$resultCode = 'TICKET_UPDATE_ERROR';
		}
		else
			$errorStatus = 'NOT_YOUR_TICKET';
	}
}
else if($_Oli->getUrlParam(2) == 'new' AND !$_Oli->isEmptyPostVars()) {
	if(empty($_Oli->getPostVars('title')))
		$resultCode = 'TITLE_EMPTY';
	else if(empty($_Oli->getPostVars('message')))
		$resultCode = 'MESSAGE_EMPTY';
	else {
		do {
			$newTicketKey = $_Oli->keygen(12);
		} while($_Oli->isExistInfosMySQL('support_tickets', array('ticket_key' => $newTicketKey)));
		
		$title = $_Oli->getPostVars('title');
		$creationDate = date('Y-m-d H:i:s');
		$message = array('message' => $_Oli->getPostVars('message'), 'username' => $_Oli->getAuthKeyOwner(), 'postDate' => $creationDate);
		$subject = (!empty($_Oli->getPostVars('subject'))) ? $_Oli->getPostVars('subject') : 'other';
		$priority = (!empty($_Oli->getPostVars('priority'))) ? $_Oli->getPostVars('priority') : 'medium';
		$message = array('message' => $_Oli->getPostVars('message'), 'username' => $_Oli->getAuthKeyOwner(), 'postDate' => $creationDate);
		$owner = $_Oli->getAuthKeyOwner();
		
		$messages = [$message];
		$lastMessageInfos = array('username' => $_Oli->getAuthKeyOwner(), 'postDate' => $creationDate);
		
		if($insertStatus = $_Oli->insertLineMySQL('support_tickets', array('id' => $_Oli->getLastInfoMySQL('support_tickets', 'id') + 1, 'title' => $title, 'messages' => $messages, 'subject' => $subject, 'priority' => $priority, 'owner' => $owner, 'ticket_key' => $newTicketKey, 'creation_date' => $creationDate, 'last_message_infos' => $lastMessageInfos))) {
			/** Client Mail */
			$clientMail = $_Oli->getAccountInfos('ACCOUNTS', 'email', array('username' => $_Oli->getAuthKeyOwner()));
			$clientSubject = 'Votre ticket a été créé ! - #' . $newTicketKey;
			$clientMessage = file_get_contents($_Oli->getUrlParam(0) . 'generate-mail.php/new-ticket/client/' . urlencode(serialize(array('title' => $title, 'owner' => $_Oli->getAuthKeyOwner(), 'ticketKey' => $newTicketKey))));
			$clientHeaders = 'From: noreply@' . $_Oli->getSetting('domain') . "\r\n";
			$clientHeaders .= 'MIME-Version: 1.0' . "\r\n";
			$clientHeaders .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			mail($clientMail, $clientSubject, utf8_decode($clientMessage), $clientHeaders);
			
			/** Admin Mail */
			$adminSubject = 'Un nouveau ticket a été créé - #' . $newTicketKey;
			$adminMessage = file_get_contents($_Oli->getUrlParam(0) . 'generate-mail.php/new-ticket/admin/' . urlencode(serialize(array('title' => $title, 'owner' => $_Oli->getAuthKeyOwner(), 'ticketKey' => $newTicketKey))));
			$adminHeaders = 'From: noreply@' . $_Oli->getSetting('domain') . "\r\n";
			$adminHeaders .= 'MIME-Version: 1.0' . "\r\n";
			$adminHeaders .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			foreach($pokeAdminsMails as $eachMail) {
				mail($eachMail, $adminSubject, utf8_decode($adminMessage), $adminHeaders);
			}
			
			$resultCode = 'TICKET_CREATED';
		}
		else
			$resultCode = 'TICKET_CREATE_ERROR';
	}
}
else if($_Oli->getUrlParam(2) == 'delete' AND !empty($_Oli->getUrlParam(3))) {
	$paramData = urldecode($_Oli->getUrlParam(3));
	$selectedSessions = (!is_array($paramData)) ? ((is_array(unserialize($paramData))) ? unserialize($paramData) : [$paramData]) : $paramData;
	
	$errorStatus = '';
	foreach($selectedSessions as $eachKey) {
		if(!$_Oli->isExistInfosMySQL('support_tickets', array('id' => $eachKey))) {
			$errorStatus = 'UNKNOWN_TICKET';
			break;
		}
		else if($_Oli->getInfosMySQL('support_tickets', 'owner', array('id' => $eachKey)) != $_Oli->getAuthKeyOwner()) {
			if($_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN'))
				$deleteAsAdmin = true;
			else {
				$errorStatus = 'NOT_YOUR_TICKET';
				break;
			}
		}
	}
	
	if(!empty($errorStatus))
		$resultCode = $errorStatus;
	else if($_Oli->getUrlParam(4) != 'confirmed')
		$resultCode = 'CONFIRMATION_NEEDED';
	else {
		foreach($selectedSessions as $eachKey) {
			$_Oli->deleteLinesMySQL('support_tickets', array('id' => $eachKey));
		}
		$resultCode = 'TICKET_DELETED';
	}
}
?>

<!DOCTYPE html>
<html>
<head>

<?php include THEMEPATH . 'head.php'; ?>
<?php $_Oli->loadCdnScript('js/serialize-php.min.js', false); ?>
<?php $_Oli->loadLocalScript('js/selector.js', false); ?>
<title>Vos tickets - <?php echo $_Oli->getSetting('name'); ?></title>

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
							<b>You asked to delete these selected tickets</b>, please confirm you want to delete them <hr />
							<span class="text-success">
								<i class="fa fa-check fa-fw"></i>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $_Oli->getUrlParam(3); ?>/confirmed">I want to delete them</a>
							</span> <br />
							<span class="text-danger">
								<i class="fa fa-times fa-fw"></i>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/">I refuse to delete them</a>
							</span>
						</p>
					</div>
				<?php } else if($resultCode == 'TITLE_EMPTY') { ?>
					<div class="message message-danger">
						<p>You cannot let the title empty</p>
					</div>
				<?php } else if($resultCode == 'MESSAGE_EMPTY') { ?>
					<div class="message message-danger">
						<p>You cannot let the message empty</p>
					</div>
				<?php } else if($resultCode == 'UNKNOWN_TICKET') { ?>
					<div class="message message-danger">
						<p>You tried to act on a ticket that not exist</p>
					</div>
				<?php } else if($resultCode == 'NOT_YOUR_TICKET') { ?>
					<div class="message message-danger">
						<p>You tried to act on a ticket that is not yours</p>
					</div>
				<?php } else if($resultCode == 'TICKET_UPDATED') { ?>
					<div class="message message-success">
						<p>The selected tickets have been updated</p>
					</div>
				<?php } else if($resultCode == 'TICKET_UPDATE_ERROR') { ?>
					<div class="message message-danger">
						<p>An error occured while updating your ticket</p>
					</div>
				<?php } else if($resultCode == 'TICKET_CREATED') { ?>
					<div class="message message-success">
						<p>Your ticket have been created</p>
					</div>
				<?php } else if($resultCode == 'TICKET_CREATE_ERROR') { ?>
					<div class="message message-danger">
						<p>An error occured while creating your ticket</p>
					</div>
				<?php } else if($resultCode == 'REQUEST_DELETED') { ?>
					<div class="message message-success">
						<p>The selected tickets have been deleted</p>
					</div>
				<?php } ?>
				
				<div class="message message-danger" id="script-message" style="display: none;">
					<p></p>
				</div>
				
				<div class="content-card">
					<h3>
						<?php if(($_Oli->getUrlParam(2) == 'admin' OR $deleteAsAdmin) AND $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
							Tickets management
						<?php } else { ?>
							Your tickets
						<?php } ?>
					</h3>
					<p>
						<?php if(($_Oli->getUrlParam(2) == 'admin' OR $deleteAsAdmin) AND $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
							Manage user tickets: <br />
							<b>Answer</b> to their question, <b>help them</b> to resolve their problems and <b>note</b> their suggestions
						<?php } else { ?>
							Any problem, question or suggestion? Ask our staff about it
						<?php } ?> <hr />
						
						<?php /*if($_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
							<?php $getTickets = $_Oli->getLinesMySQL('support_tickets', [], true, true); ?>
							<?php if(!empty($getTickets)) { ?>
								<?php $adminTicketsNotifs = count($getTickets); ?>
								<?php foreach($getTickets as $eachTicket) { ?>
									<?php if($eachTicket['last_message_infos']['username'] != $eachTicket['owner'] OR $eachTicket['owner'] == $_Oli->getAuthKeyOwner()) { ?>
										<?php $adminTicketsNotifs--; ?>
									<?php } ?>
								<?php } ?>
							<?php } ?>
						<?php } ?>
						<?php $getTickets = $_Oli->getLinesMySQL('support_tickets', array('owner' => $_Oli->getAuthKeyOwner()), true, true); ?>
						<?php if(!empty($getTickets)) { ?>
							<?php $yourTicketsNotifs = count($getTickets); ?>
							<?php foreach($getTickets as $eachTicket) { ?>
								<?php if($eachTicket['last_message_infos']['username'] == $eachTicket['owner']) { ?>
									<?php $yourTicketsNotifs--; ?>
								<?php } ?>
							<?php } ?>
						<?php }*/ ?>
						
						<?php if($_Oli->getUrlParam(2) == 'new' AND empty($newTicketKey)) { ?>
							<span class="text-primary">
								<i class="fa fa-angle-left fa-fw"></i>
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/">
									Go back to your tickets
									<?php if($yourTicketsNotifs > 0) { ?>
										<span class="badge"><?php echo $yourTicketsNotifs; ?></span>
									<?php } ?>
								</a>
							</span>
						<?php } else if(($_Oli->getUrlParam(2) == 'view' AND !empty($_Oli->getUrlParam(3)) AND $_Oli->isExistInfosMySQL('support_tickets', array('ticket_key' => $_Oli->getUrlParam(3)))) OR !empty($newTicketKey)) { ?>
							<?php $ticketKey = (!empty($newTicketKey)) ? $newTicketKey : $_Oli->getUrlParam(3); ?>
							<?php $viewTicket = $_Oli->getLinesMySQL('support_tickets', array('ticket_key' => $ticketKey)); ?>
							
							<?php if($viewTicket['owner'] == $_Oli->getAuthKeyOwner()) { ?>
								<span class="text-primary">
									<i class="fa fa-angle-left fa-fw"></i>
									<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/">
										Go back to your tickets
										<?php $yourTicketsNotifs--; ?>
										<?php if($yourTicketsNotifs > 0) { ?>
											<span class="badge"><?php echo $yourTicketsNotifs; ?></span>
										<?php } ?>
									</a>
								</span>
							<?php } else if($_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
								<span class="text-danger">
									<i class="fa fa-angle-left fa-fw"></i>
									<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/admin/">
										Go back to your admin panel
										<?php $adminTicketsNotifs--; ?>
										<?php if($adminTicketsNotifs > 0 AND $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
											<span class="badge"><?php echo $adminTicketsNotifs; ?></span>
										<?php } ?>
									</a>
								</span>
							<?php } ?>
						<?php } else { ?>
							<?php if(($_Oli->getUrlParam(2) != 'admin' AND !$deleteAsAdmin)) { ?>
								<span class="text-primary">
									<i class="fa fa-plus fa-fw"></i>
									<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/new/">
										Create a new ticket
									</a>
								</span>
							<?php } ?>
							<?php if($_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
								<?php if(($_Oli->getUrlParam(2) == 'admin' OR $deleteAsAdmin)) { ?>
									<span class="text-success">
										<i class="fa fa-angle-left fa-fw"></i>
										<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/">
											Go to your tickets
											<?php if($yourTicketsNotifs > 0) { ?>
												<span class="badge"><?php echo $yourTicketsNotifs; ?></span>
											<?php } ?>
										</a>
									</span>
								<?php } else { ?> <br />
									<span class="text-danger">
										<i class="fa fa-user fa-fw"></i>
										<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/admin/">
											Go to your admin panel
											<?php if($adminTicketsNotifs > 0 AND $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
												<span class="badge"><?php echo $adminTicketsNotifs; ?></span>
											<?php } ?>
										</a>
									</span>
								<?php } ?>
							<?php } ?>
						<?php } ?>
						
						<?php if($_Oli->getUrlParam(2) != 'new' AND ($_Oli->getUrlParam(2) != 'view' OR ($_Oli->getUrlParam(2) == 'view' AND $_Oli->getInfosMySQL('support_tickets', 'owner', array('ticket_key' => $_Oli->getUrlParam(3))) != $_Oli->getAuthKeyOwner() AND $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) < $_Oli->translateUserRight('ADMIN'))) AND empty($newTicketKey)) { ?>
							<?php $yourTickets = (($_Oli->getUrlParam(2) == 'admin' OR $deleteAsAdmin) AND $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) ? $_Oli->getLinesMySQL('support_tickets', [], true, true) : $_Oli->getLinesMySQL('support_tickets', array('owner' => $_Oli->getAuthKeyOwner()), true, true); ?>
							<?php if(!empty($yourTickets)) { ?> <hr />
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
						<?php } ?>
					</p>
				</div>
			</div>
			
			<div class="mainBar col-sm-9">
				<?php if($_Oli->getUrlParam(2) == 'new' AND empty($newTicketKey)) { ?>
					<div class="content-card">
						<form action="<?php echo $_Oli->getUrlParam(0); ?>form.php" class="form form-horizontal" method="post">
							<h1>Create a new ticket</h1>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-5">
									<input type="text" class="form-control" name="username" value="<?php echo $_Oli->getAuthKeyOwner(); ?>" disabled />
								</div>
								<div class="col-sm-5">
									<input type="email" class="form-control" name="email" value="<?php echo $_Oli->getAccountInfos('ACCOUNTS', 'email', array('username' => $_Oli->getAuthKeyOwner())); ?>" disabled />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Titre</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="title" <?php if(!$_Oli->isEmptyPostVars()) { ?>value="<?php echo $_Oli->getPostVars('title'); ?>"<?php } ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Sujet</label>
								<div class="col-sm-10">
									<select class="form-control" name="subject">
										<option value="business" <?php if($_Oli->getPostVars('subject') == 'business') { ?>selected<?php } ?>>
											Business
										</option>
										<option value="suggest" <?php if($_Oli->getPostVars('subject') == 'suggest') { ?>selected<?php } ?>>
											Suggest
										</option>
										<option value="report" <?php if($_Oli->getPostVars('subject') == 'report') { ?>selected<?php } ?>>
											Report
										</option>
										<option value="talk" <?php if($_Oli->getPostVars('subject') == 'talk') { ?>selected<?php } ?>>
											Talk
										</option>
										<option value="other" <?php if($_Oli->isEmptyPostVars() OR $_Oli->getPostVars('subject') == 'other') { ?>selected<?php } ?>>
											Other
										</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Priority</label>
								<div class="col-sm-10">
									<select class="form-control" name="priority">
										<option value="low" <?php if($_Oli->getPostVars('priority') == 'low') { ?>selected<?php } ?>>
											Low
										</option>
										<option value="medium" <?php if($_Oli->isEmptyPostVars() OR $_Oli->getPostVars('priority') == 'medium') { ?>selected<?php } ?>>
											Medium
										</option>
										<option value="high" <?php if($_Oli->getPostVars('priority') == 'high') { ?>selected<?php } ?>>
											High
										</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Message</label>
								<div class="col-sm-10">
									<textarea class="form-control" name="message" rows="6"><?php echo $_Oli->getPostVars('message'); ?></textarea>
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="submit" class="btn btn-primary"><i class="fa fa-pencil fa-fw"></i> Create</button>
									<button type="reset" class="btn btn-default"><i class="fa fa-refresh fa-fw"></i> Reset</button>
								</div>
							</div>
						</form>
					</div>
				<?php } else if(($_Oli->getUrlParam(2) == 'view' AND !empty($_Oli->getUrlParam(3)) AND $_Oli->isExistInfosMySQL('support_tickets', array('ticket_key' => $_Oli->getUrlParam(3))) AND ($_Oli->getInfosMySQL('support_tickets', 'owner', array('ticket_key' => $_Oli->getUrlParam(3))) == $_Oli->getAuthKeyOwner() OR $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN'))) OR !empty($newTicketKey)) { ?>
					<div class="ticket content-card">
						<h1>
							Ticket:
							<small>
								<span class="text-primary"><?php echo $viewTicket['title']; ?></span>
								<small> -
									<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/<?php echo $viewTicket['ticket_key']; ?>">
										#<?php echo $viewTicket['ticket_key']; ?>
									</a>
								</small>
							</small>
						</h1>
						
						<?php if($_Oli->getUrlParam(2) == 'view') { ?>
							<form action="<?php echo $_Oli->getUrlParam(0); ?>form.php" class="form form-horizontal ticket-answer" method="post">
								<div class="form-group">
									<div class="col-xs-12">
										<div class="input-group">
											<textarea type="text" class="form-control" name="message" placeholder="Répondre au ticket" rows="1"></textarea>
											<a href="#" class="input-group-addon btn btn-default submit">
												<i class="fa fa-paper-plane fa-fw"></i> Answer
											</a>
										</div>
									</div>
								</div>
							</form>
						<?php } else { ?>
							<p>
								You cannot answer from this page, go on this one: <br />
								<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/view/<?php echo $viewTicket['ticket_key']; ?>">
									/view/<?php echo $viewTicket['ticket_key']; ?>
								</a>
							</p>
						<?php } ?> <hr />
						
						<?php if(!empty($viewTicket['messages'])) { ?>
							<?php $countMessages = count($viewTicket['messages']); ?>
							<?php foreach(array_reverse($viewTicket['messages']) as $eachKey => $eachMessage) { ?>
								<b><?php echo nl2br($eachMessage['message']); ?></b> <br />
								<?php if($_Oli->getUserRightLevel(array('username' => $eachMessage['username'])) >= $_Oli->translateUserRight('ADMIN')) { ?>
									By <span class="text-danger"><?php echo $eachMessage['username']; ?></span>
								<?php } else { ?>
									By <span class="text-primary"><?php echo $eachMessage['username']; ?></span>
								<?php } ?>
								the <?php echo date('d', strtotime($eachMessage['postDate'])); ?>
								<?php switch(date('m', strtotime($eachMessage['postDate']))) {
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
								} ?> <?php echo date('Y', strtotime($eachMessage['postDate'])); ?>
								at <?php echo date('H:i', strtotime($eachMessage['postDate'])); ?>
								<?php if(($eachKey + 1) < $countMessages) { ?><hr /><?php } ?>
							<?php } ?>
						<?php } else { ?>
							No message
						<?php } ?>
					</div>
				<?php } else { ?>
					<?php if(!empty($yourTickets)) { ?>
						<div class="content-card">
							<p>
								<i class="fa fa-sort-numeric-desc fa-fw"></i> Sorted from newest to oldest ticket <br />
								<?php if(($_Oli->getUrlParam(2) == 'admin' OR $deleteAsAdmin) AND $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
									Shows your tickets for today 
								<?php } else { ?>
									Shows user tickets for today
								<?php } ?>, the <?php echo date('d', $now = time()); ?>
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
								Tickets older than 15 days or that haven't got any answer during 4 days will be deleted <br />
								Displayed time differences are truncated
							</p>
							
							<table class="table table-hover">
								<thead>
									<tr>
										<th class="selector-menu"><i class="fa fa-check fa-fw"></i></th>
										<th><i class="fa fa-comment-o fa-fw"></i></th>
										<th>Title</th>
										<th>Subject</th>
										<th>Priority</th>
										<?php if(($_Oli->getUrlParam(2) == 'admin' OR $deleteAsAdmin) AND $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
											<th>Client</th>
										<?php } ?>
										<th>Creation</th>
										<th>Last Message</th>
										<th colspan="2"></th>
									</tr>
								</thead>
								<tbody>
									<?php $countTickets = count($yourTickets); ?>
									<?php foreach(array_reverse($yourTickets) as $eachTicket) { ?>
										<?php if((strtotime($eachTicket['creation_date']) + 86400*15) >= time() AND (strtotime($eachTicket['last_message_infos']['postDate']) + 86400*4) >= time()) { ?>
											<?php if(($_Oli->getUrlParam(2) == 'admin' OR $deleteAsAdmin) AND $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
												<tr <?php if($eachTicket['owner'] == $_Oli->getAuthKeyOwner()) { ?>class="active text-muted"<?php } else if($eachTicket['last_message_infos']['username'] == $eachTicket['owner']) { ?>class="info"<?php } ?> id="<?php echo $eachTicket['id']; ?>">
											<?php } else { ?>
												<tr <?php if($eachTicket['last_message_infos']['username'] != $eachTicket['owner']) { ?>class="info"<?php } ?> id="<?php echo $eachTicket['id']; ?>">
											<?php } ?>
												<?php if(!empty($selectedSessions) AND in_array($eachTicket['id'], $selectedSessions)) { ?>
													<td class="selector checked">
														<i class="fa fa-check-square fa-fw"></i>
													</td>
												<?php } else { ?>
													<td class="selector">
														<i class="fa fa-square-o fa-fw"></i>
													</td>
												<?php } ?>
												
												<td><span class="badge"><?php echo count($eachTicket['messages']); ?></span></td>
												<td><b><?php echo $eachTicket['title']; ?></b></td>
												<td>
													<?php if($eachTicket['subject'] == 'business') { ?>
														Business
													<?php } else if($eachTicket['subject'] == 'suggest') { ?>
														Suggest
													<?php } else if($eachTicket['subject'] == 'report') { ?>
														Report
													<?php } else if($eachTicket['subject'] == 'talk') { ?>
														Talk
													<?php } else { ?>
														Other
													<?php } ?>
												</td>
												<td>
													<?php if($eachTicket['priority'] == 'high') { ?>
														<span class="text-danger">High</span>
													<?php } else if($eachTicket['priority'] == 'low') { ?>
														<span class="text-muted">Low</span>
													<?php } else { ?>
														Medium
													<?php } ?>
												</td>
												<?php if(($_Oli->getUrlParam(2) == 'admin' OR $deleteAsAdmin) AND $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
													<td>
														<b><?php echo $eachTicket['owner']; ?></b>
													</td>
												<?php } ?>
												<td>
													<?php $timeOutput = []; ?>
													<?php foreach($_Oli->dateDifference($eachTicket['creation_date'], time(), true) as $eachUnit => $eachTime) { ?>
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
														<?php } ?>
													<?php } ?>
													
													<?php if(!empty($timeOutput)) { ?>
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
														Now
													<?php } ?>
												</td>
												<td>
													<?php $timeOutput = []; ?>
													<?php foreach($_Oli->dateDifference($eachTicket['last_message_infos']['postDate'], time(), true) as $eachUnit => $eachTime) { ?>
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
														<?php } ?>
													<?php } ?>
													
													<?php if(!empty($timeOutput)) { ?>
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
														Now
													<?php } ?> <br />
													
													<small>
														By <?php echo $eachTicket['last_message_infos']['username']; ?>
													</small>
												</td>
												<td>
													<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/view/<?php echo $eachTicket['ticket_key']; ?>" class="btn btn-success btn-xs">
														View <i class="fa fa-eye fa-fw"></i>
													</a>
												</td>
												<td>
													<a href="<?php echo $_Oli->getUrlParam(0) . $_Oli->getUrlParam(1); ?>/delete/<?php echo $eachTicket['id']; ?>" class="btn btn-danger btn-xs">
														Delete <i class="fa fa-trash fa-fw"></i>
													</a>
												</td>
											</tr>
										<?php } else { ?>
											<?php $_Oli->deleteLinesMySQL('support_tickets', array('id' => $eachTicket['id'])); ?>
											<?php $countTickets--; ?>
											
											<tr class="danger">
												<td></td>
												<td colspan="8">
													An expired ticket have been deleted
												</td>
											</tr>
										<?php } ?>
									<?php } ?>
								</tbody>
								<tfoot>
									<tr>
										<?php if(($_Oli->getUrlParam(2) == 'admin' OR $deleteAsAdmin) AND $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
											<td colspan="7">
										<?php } else { ?>
											<td colspan="6">
										<?php } ?>
											<a href="#selectAll" class="selectAll btn btn-primary btn-xs">
												Select All <i class="fa fa-check-square fa-fw"></i>
											</a>
											<a href="#unselectAll" class="unselectAll btn btn-danger btn-xs">
												Unselect All <i class="fa fa-square-o fa-fw"></i>
											</a>
										</td>
										<td colspan="2"><?php echo $countTickets; ?> <small>ticket<?php if($countTickets > 1) { ?>s<?php } ?></small></td>
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
							<h3>
								<?php if(($_Oli->getUrlParam(2) == 'admin' OR $deleteAsAdmin) AND $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
									No user ticket to show
								<?php } else { ?>
									You haven't got any ticket
								<?php } ?>
							</h3>
						</div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<?php $_Oli->loadEndHtmlFiles(); ?>

</body>
</html>