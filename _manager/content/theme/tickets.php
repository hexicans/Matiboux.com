<?php
if(!$_Oli->verifyAuthKey() OR $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) < $_Oli->translateUserRight('USER'))
	header('Location: ' . $_Oli->getShortcutLink('login'));

$pokeAdminsMails = [
	// $_Oli->getAccountInfos('ACCOUNTS', 'email', array('username' => $_Oli->getOption('owner'))),
	'support@' . $_Oli->getOption('domain')
];
if($_Oli->getUrlParam(2) == 'view' AND !empty($_Oli->getUrlParam(3)) AND !$_Oli->isEmptyPostVars()) {
	if($_Oli->getUrlParam(2) == 'view' AND !empty($_Oli->getUrlParam(3)) AND !$_Oli->isEmptyPostVars()) {
		if(empty($_Oli->getPostVars('message')))
			$resultCode = 'MESSAGE_EMPTY';
		else if($_Oli->isExistInfosMySQL('manager_tickets', array('id' => $eachKey)) != $_Oli->getAuthKeyOwner()) {
			$title = $_Oli->getInfosMySQL('manager_tickets', 'title', array('ticket_key' => $_Oli->getUrlParam(3)));
			$owner = $_Oli->getInfosMySQL('manager_tickets', 'owner', array('ticket_key' => $_Oli->getUrlParam(3)));
			$messages = $_Oli->getInfosMySQL('manager_tickets', 'messages', array('ticket_key' => $_Oli->getUrlParam(3)));
			$creationDate = date('Y-m-d H:i:s');
			
			$messages[] = array('message' => $_Oli->getPostVars('message'), 'username' => $_Oli->getAuthKeyOwner(), 'postDate' => $creationDate);
			$lastMessageInfos = array('username' => $_Oli->getAuthKeyOwner(), 'postDate' => $creationDate);
			
			while(strlen(serialize($messages)) > (2 ** 16 - 1)) {
				array_shift($messages);
			}
			
			if($_Oli->updateInfosMySQL('manager_tickets', array('messages' => $messages, 'last_message_infos' => $lastMessageInfos), array('ticket_key' => $_Oli->getUrlParam(3)))) {
				if($owner != $_Oli->getAuthKeyOwner()) {
					/** Client Mail */
					$clientMail = $_Oli->getAccountInfos('ACCOUNTS', 'email', array('username' => $owner));
					$clientSubject = 'Un admin vous a répondu ! - #' . $_Oli->getUrlParam(3);
					$clientMessage = file_get_contents($_Oli->getOption('url') . 'generate-mail.php/ticket-anwser/client/' . urlencode(serialize(array('title' => $title, 'owner' => $owner, 'ticketKey' => $_Oli->getUrlParam(3)))));
					$clientHeaders = 'From: noreply@' . $_Oli->getOption('domain') . "\r\n";
					$clientHeaders .= 'MIME-Version: 1.0' . "\r\n";
					$clientHeaders .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					mail($clientMail, $clientSubject, utf8_decode($clientMessage), $clientHeaders);
				}
				else {
					/** Admin Mail */
					$adminSubject = 'Une réponse du client - #' . $_Oli->getUrlParam(3);
					$adminMessage = file_get_contents($_Oli->getOption('url') . 'generate-mail.php/ticket-anwser/admin/' . urlencode(serialize(array('title' => $title, 'owner' => $_Oli->getAuthKeyOwner(), 'ticketKey' => $_Oli->getUrlParam(3)))));
					$adminHeaders = 'From: noreply@' . $_Oli->getOption('domain') . "\r\n";
					$adminHeaders .= 'MIME-Version: 1.0' . "\r\n";
					$adminHeaders .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					foreach($pokeAdminsMails as $eachMail) {
						if(!mail($eachMail, $adminSubject, utf8_decode($adminMessage), $adminHeaders))
							break;
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
		} while($_Oli->isExistInfosMySQL('manager_tickets', array('ticket_key' => $newTicketKey)));
		
		$title = $_Oli->getPostVars('title');
		$creationDate = date('Y-m-d H:i:s');
		$message = array('message' => $_Oli->getPostVars('message'), 'username' => $_Oli->getAuthKeyOwner(), 'postDate' => $creationDate);
		$subject = (!empty($_Oli->getPostVars('subject'))) ? $_Oli->getPostVars('subject') : 'other';
		$priority = (!empty($_Oli->getPostVars('priority'))) ? $_Oli->getPostVars('priority') : 'medium';
		$message = array('message' => $_Oli->getPostVars('message'), 'username' => $_Oli->getAuthKeyOwner(), 'postDate' => $creationDate);
		$owner = $_Oli->getAuthKeyOwner();
		
		$messages = [$message];
		$lastMessageInfos = array('username' => $_Oli->getAuthKeyOwner(), 'postDate' => $creationDate);
		
		if($insertStatus = $_Oli->insertLineMySQL('manager_tickets', array('id' => $_Oli->getLastInfoMySQL('manager_tickets', 'id') + 1, 'title' => $title, 'messages' => $messages, 'subject' => $subject, 'priority' => $priority, 'owner' => $owner, 'ticket_key' => $newTicketKey, 'creation_date' => $creationDate, 'last_message_infos' => $lastMessageInfos))) {
			/** Client Mail */
			$clientMail = $_Oli->getAccountInfos('ACCOUNTS', 'email', array('username' => $_Oli->getAuthKeyOwner()));
			$clientSubject = 'Votre ticket a été créé ! - #' . $newTicketKey;
			$clientMessage = file_get_contents($_Oli->getOption('url') . 'generate-mail.php/new-ticket/client/' . urlencode(serialize(array('title' => $title, 'owner' => $_Oli->getAuthKeyOwner(), 'ticketKey' => $newTicketKey))));
			$clientHeaders = 'From: noreply@' . $_Oli->getOption('domain') . "\r\n";
			$clientHeaders .= 'MIME-Version: 1.0' . "\r\n";
			$clientHeaders .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			mail($clientMail, $clientSubject, utf8_decode($clientMessage), $clientHeaders);
			
			/** Admin Mail */
			$adminSubject = 'Un nouveau ticket a été créé - #' . $newTicketKey;
			$adminMessage = file_get_contents($_Oli->getOption('url') . 'generate-mail.php/new-ticket/admin/' . urlencode(serialize(array('title' => $title, 'owner' => $_Oli->getAuthKeyOwner(), 'ticketKey' => $newTicketKey))));
			$adminHeaders = 'From: noreply@' . $_Oli->getOption('domain') . "\r\n";
			$adminHeaders .= 'MIME-Version: 1.0' . "\r\n";
			$adminHeaders .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			foreach($pokeAdminsMails as $eachMail) {
				if(!mail($eachMail, $adminSubject, utf8_decode($adminMessage), $adminHeaders))
					break;
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
		if(!$_Oli->isExistInfosMySQL('manager_tickets', array('id' => $eachKey))) {
			$errorStatus = 'UNKNOWN_TICKET';
			break;
		}
		else if($_Oli->getInfosMySQL('manager_tickets', 'owner', array('id' => $eachKey)) != $_Oli->getAuthKeyOwner()) {
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
			$_Oli->deleteLinesMySQL('manager_tickets', array('id' => $eachKey));
		}
		$resultCode = 'TICKET_DELETED';
	}
}
?>

<!DOCTYPE html>
<html>
<head>

<?php include 'head.php'; ?>
<title>Vos tickets - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include 'header.php'; ?>

<div class="header">
	<div class="container">
		<h1>Vos tickets</h1>
		<p>
			Page de gestion de vos tickets <br />
		</p>
	</div>
</div>

<?php if($_Oli->getUrlParam(2) == 'delete' AND !empty($_Oli->getUrlParam(3)) AND $resultCode == 'CONFIRMATION_NEEDED') { ?>
	<div class="message message-warning">
		<div class="container">
			<h1>Confirmez la suppression des tickets sélectionnées</h1>
			<p>
				<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $_Oli->getUrlParam(3); ?>/confirmed" class="btn btn-primary btn-block">
					<i class="fa fa-check fa-fw"></i> J'autorise la suppression de ces tickets
				</a>
				<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/" class="btn btn-danger btn-block">
					<i class="fa fa-times fa-fw"></i> Je refuse de les supprimer
				</a>
			</p>
		</div>
	</div>
<?php } else if($resultCode == 'TITLE_EMPTY') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Le titre du ticket ne peut pas être laissé pour vide</h2>
		</div>
	</div>
<?php } else if($resultCode == 'MESSAGE_EMPTY') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Le message ne peut pas être laissé pour vide</h2>
		</div>
	</div>
<?php } else if($resultCode == 'TICKET_CREATED') { ?>
	<div class="message message-success">
		<div class="container">
			<h2>Votre ticket a correctement été créé</h2>
			<p>
				Les administrateurs ont été notifiés et vous répondront le plus rapidement possible
			</p>
		</div>
	</div>
<?php } else if($resultCode == 'TICKET_CREATE_ERROR') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Une erreur s'est produite lors de la création de votre ticket</h2>
		</div>
	</div>
<?php } else if($resultCode == 'TICKET_UPDATED') { ?>
	<div class="message message-success">
		<div class="container">
			<h2>Les informations liées au ticket ont bien été mises à jour</h2>
		</div>
	</div>
<?php } else if($resultCode == 'TICKET_UPDATE_ERROR') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Une erreur s'est produite lors de la mise à jour des informations liées au ticket</h2>
		</div>
	</div>
<?php } else if($resultCode == 'TICKET_DELETED') { ?>
	<div class="message message-success">
		<div class="container">
			<h2>La suppression du ticket a correctement été effectuée</h2>
		</div>
	</div>
<?php } else if($resultCode == 'UNKNOWN_TICKET') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous avez tenté d'effectuer une action sur un ticket qui nous est inconnu ou qui n'existe pas</h2>
		</div>
	</div>
<?php } else if($resultCode == 'NOT_YOUR_TICKET') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous avez tenté d'effectuer une action sur un ticket qui ne vous appartient pas</h2>
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
	<?php if($_Oli->getUrlParam(2) == 'new' AND empty($newTicketKey)) { ?>
		<div class="container">
			<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/" class="btn btn-primary btn-xs">
				<i class="fa fa-angle-left fa-fw"></i> Revenir à la liste de vos tickets
			</a> <hr />
			
			<form action="<?php echo $_Oli->getOption('url'); ?>form.php" class="form form-horizontal" method="post">
				<h1>Ouvrir un nouveau ticket</h1>
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
							<option value="commercial" <?php if($_Oli->getPostVars('subject') == 'commercial') { ?>selected<?php } ?>>
								Commercial
							</option>
							<option value="suggest" <?php if($_Oli->getPostVars('subject') == 'suggest') { ?>selected<?php } ?>>
								Suggestion
							</option>
							<option value="report" <?php if($_Oli->getPostVars('subject') == 'report') { ?>selected<?php } ?>>
								Signalement
							</option>
							<option value="talk" <?php if($_Oli->getPostVars('subject') == 'talk') { ?>selected<?php } ?>>
								Discution
							</option>
							<option value="other" <?php if($_Oli->isEmptyPostVars() OR $_Oli->getPostVars('subject') == 'other') { ?>selected<?php } ?>>
								Autre
							</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Priorité</label>
					<div class="col-sm-10">
						<select class="form-control" name="priority">
							<option value="low" <?php if($_Oli->getPostVars('priority') == 'low') { ?>selected<?php } ?>>
								Basse
							</option>
							<option value="medium" <?php if($_Oli->isEmptyPostVars() OR $_Oli->getPostVars('priority') == 'medium') { ?>selected<?php } ?>>
								Moyenne
							</option>
							<option value="high" <?php if($_Oli->getPostVars('priority') == 'high') { ?>selected<?php } ?>>
								Elevée
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
						<button type="submit" class="btn btn-primary"><i class="fa fa-pencil fa-fw"></i> Créer</button>
						<button type="reset" class="btn btn-default"><i class="fa fa-refresh fa-fw"></i> Réinitialiser</button>
					</div>
				</div>
			</form>
		</div>
	<?php } else if(($_Oli->getUrlParam(2) == 'view' AND !empty($_Oli->getUrlParam(3)) AND $_Oli->isExistInfosMySQL('manager_tickets', array('ticket_key' => $_Oli->getUrlParam(3)))) OR !empty($newTicketKey)) { ?>
		<?php $ticketKey = (!empty($newTicketKey)) ? $newTicketKey : $_Oli->getUrlParam(3); ?>
		<div class="container">
			<?php $viewTicket = $_Oli->getLinesMySQL('manager_tickets', array('ticket_key' => $ticketKey)); ?>
			
			<?php if($viewTicket['owner'] == $_Oli->getAuthKeyOwner()) { ?>
				<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/" class="btn btn-primary btn-xs">
					<i class="fa fa-angle-left fa-fw"></i> Revenir à la liste de vos tickets
				</a>
			<?php } else { ?>
				<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/admin/" class="btn btn-danger btn-xs">
					<i class="fa fa-angle-left fa-fw"></i> Gestions des tickets (admin)
				</a>
			<?php } ?> <hr />
			
			<h1>
				Ticket :
				<small>
					<span class="text-primary"><?php echo $viewTicket['title']; ?></span>
					<small> -
						<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/<?php echo $viewTicket['ticket_key']; ?>">
							#<?php echo $viewTicket['ticket_key']; ?>
						</a>
					</small>
				</small>
			</h1>
			
			<?php if($_Oli->getUrlParam(2) == 'view') { ?>
				<form action="<?php echo $_Oli->getOption('url'); ?>form.php" class="form form-horizontal ticket-answer" method="post">
					<div class="form-group">
						<div class="col-xs-12">
							<div class="input-group">
								<textarea type="text" class="form-control" name="message" placeholder="Répondre au ticket" rows="1"></textarea>
								<a href="#" class="input-group-addon btn btn-default postMessage">
									<i class="fa fa-paper-plane fa-fw"></i> Répondre
								</a>
							</div>
						</div>
					</div>
				</form>
			<?php } else { ?>
				<p>
					Vous ne pouvez répondre au ticket que depuis l'url de visualisation <br />
					Y accéder :
					<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/view/<?php echo $viewTicket['ticket_key']; ?>">
						/view/<?php echo $viewTicket['ticket_key']; ?>
					</a>
				</p>
			<?php } ?> <hr />
			
			<?php if(!empty($viewTicket['messages'])) { ?>
				<?php $countMessages = count($viewTicket['messages']); ?>
				<?php foreach(array_reverse($viewTicket['messages']) as $eachKey => $eachMessage) { ?>
					<b><?php echo nl2br($eachMessage['message']); ?></b> <br />
					Par
					<?php if($_Oli->getUserRightLevel(array('username' => $eachMessage['username'])) >= $_Oli->translateUserRight('ADMIN')) { ?>
						<span class="text-danger"><?php echo $eachMessage['username']; ?></span>
					<?php } else { ?>
						<span class="text-primary"><?php echo $eachMessage['username']; ?></span>
					<?php } ?>
					le <?php echo date('d/m/Y', strtotime($eachMessage['postDate'])); ?>
					à <?php echo date('H:i', strtotime($eachMessage['postDate'])); ?>
					<?php if(($eachKey + 1) < $countMessages) { ?><hr /><?php } ?>
				<?php } ?>
			<?php } else { ?>
				Aucun message.
			<?php } ?>
		</div>
	<?php } else { ?>
		<div class="container">
			<?php if(($_Oli->getUrlParam(2) != 'admin' AND !$deleteAsAdmin)) { ?>
				<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/new/" class="btn btn-primary btn-xs">
					<i class="fa fa-plus fa-fw"></i> Nouveau ticket
				</a>
			<?php } ?>
			<?php if($_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
				<?php if(($_Oli->getUrlParam(2) == 'admin' OR $deleteAsAdmin)) { ?>
					<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/" class="btn btn-success btn-xs">
						<i class="fa fa-angle-left fa-fw"></i> Retour à vos tickets
					</a>
				<?php } else { ?>
					<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/admin/" class="btn btn-danger btn-xs">
						<i class="fa fa-user fa-fw"></i> Gestions des tickets (admin)
					</a>
				<?php } ?>
			<?php } ?>
			<h1>
				<?php if(($_Oli->getUrlParam(2) == 'admin' OR $deleteAsAdmin) AND $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
					<small>
						<span class="label label-danger">ADMIN</span>
					</small>
					Gestion des tickets
				<?php } else { ?>
					Vos tickets
				<?php } ?>
			</h1>
			
			<?php $yourTickets = (($_Oli->getUrlParam(2) == 'admin' OR $deleteAsAdmin) AND $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) ? $_Oli->getLinesMySQL('manager_tickets', [], true, true) : $_Oli->getLinesMySQL('manager_tickets', array('owner' => $_Oli->getAuthKeyOwner()), true, true); ?>
			<?php if(!empty($yourTickets)) { ?>
				<p>
					<i class="fa fa-sort-numeric-desc fa-fw"></i> Triés de la plus récente à la plus ancienne <br />
					Les tickets vieux de plus de 15 jours n'ayant pas reçu de réponse depuis plus de 4 jours sont automatiquement supprimés. <br />
					Les différences horaires affichées sont tronquées.
				</p>
				
				<table class="table table-hover">
					<thead>
						<tr>
							<th class="selector-menu"><i class="fa fa-check fa-fw"></i></th>
							<th><i class="fa fa-comment-o fa-fw"></i></th>
							<th>Titre</th>
							<th>Sujet</th>
							<th>Priorité</th>
							<?php if(($_Oli->getUrlParam(2) == 'admin' OR $deleteAsAdmin) AND $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
								<th>Client</th>
							<?php } ?>
							<th>Création</th>
							<th colspan="2">Dernier message</th>
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
										<?php if($eachTicket['subject'] == 'commercial') { ?>
											Commercial
										<?php } else if($eachTicket['subject'] == 'suggest') { ?>
											Suggestion
										<?php } else if($eachTicket['subject'] == 'report') { ?>
											Signalement
										<?php } else if($eachTicket['subject'] == 'talk') { ?>
											Discution
										<?php } else { ?>
											Autre
										<?php } ?>
									</td>
									<td>
										<?php if($eachTicket['priority'] == 'high') { ?>
											<span class="text-danger">Elevée</span>
										<?php } else if($eachTicket['priority'] == 'low') { ?>
											<span class="text-muted">Basse</span>
										<?php } else { ?>
											Moyenne
										<?php } ?>
									</td>
									<?php if(($_Oli->getUrlParam(2) == 'admin' OR $deleteAsAdmin) AND $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
										<td>
											<b><?php echo $eachTicket['owner']; ?></b>
										</td>
									<?php } ?>
									<td>
										<?php $timeOutput = []; ?>
										<?php foreach(array_slice($_Oli->dateDifference($eachTicket['creation_date'], time(), true), 0, 2) as $eachUnit => $eachTime) { ?>
											<?php if($eachTime > 0) { ?>
												<?php if($eachUnit == 'years') { ?>
													<?php $timeOutput[] = $eachTime . ' an' . (($eachTime > 1) ? 's' : ''); ?>
												<?php } else if($eachUnit == 'days') { ?>
													<?php $timeOutput[] = $eachTime . ' jour' . (($eachTime > 1) ? 's' : ''); ?>
												<?php } else if($eachUnit == 'hours') { ?>
													<?php $timeOutput[] = $eachTime . ' heure' . (($eachTime > 1) ? 's' : ''); ?>
												<?php } else if($eachUnit == 'minutes') { ?>
													<?php $timeOutput[] = $eachTime . ' minute' . (($eachTime > 1) ? 's' : ''); ?>
												<?php } else if($eachUnit == 'seconds') { ?>
													<?php $timeOutput[] = $eachTime . ' seconde' . (($eachTime > 1) ? 's' : ''); ?>
												<?php } ?>
											<?php } ?>
										<?php } ?>
										
										<?php if(!empty($timeOutput)) { ?>
											Il y a <?php echo $timeOutput[0]; ?>
											<?php if(count($timeOutput) > 1) { ?>
												<small>
													<?php if(count($timeOutput) > 2) { ?>
														, <?php echo implode(', ', array_splice($timeOutput, 1, count($timeOutput) - 1)); ?>
													<?php } ?>
													et <?php echo $timeOutput[count($timeOutput) - 1]; ?>
												</small>
											<?php } ?>
										<?php } else { ?>
											Maintenant
										<?php } ?>
									</td>
									<td>
										<?php $timeOutput = []; ?>
										<?php foreach(array_slice($_Oli->dateDifference($eachTicket['last_message_infos']['postDate'], time(), true), 0, 2) as $eachUnit => $eachTime) { ?>
											<?php if($eachTime > 0) { ?>
												<?php if($eachUnit == 'years') { ?>
													<?php $timeOutput[] = $eachTime . ' an' . (($eachTime > 1) ? 's' : ''); ?>
												<?php } else if($eachUnit == 'days') { ?>
													<?php $timeOutput[] = $eachTime . ' jour' . (($eachTime > 1) ? 's' : ''); ?>
												<?php } else if($eachUnit == 'hours') { ?>
													<?php $timeOutput[] = $eachTime . ' heure' . (($eachTime > 1) ? 's' : ''); ?>
												<?php } else if($eachUnit == 'minutes') { ?>
													<?php $timeOutput[] = $eachTime . ' minute' . (($eachTime > 1) ? 's' : ''); ?>
												<?php } else if($eachUnit == 'seconds') { ?>
													<?php $timeOutput[] = $eachTime . ' seconde' . (($eachTime > 1) ? 's' : ''); ?>
												<?php } ?>
											<?php } ?>
										<?php } ?>
										
										<?php if(!empty($timeOutput)) { ?>
											Il y a <?php echo $timeOutput[0]; ?>
											<?php if(count($timeOutput) > 1) { ?>
												<small>
													<?php if(count($timeOutput) > 2) { ?>
														, <?php echo implode(', ', array_splice($timeOutput, 1, count($timeOutput) - 1)); ?>
													<?php } ?>
													et <?php echo $timeOutput[count($timeOutput) - 1]; ?>
												</small>
											<?php } ?>
										<?php } else { ?>
											Maintenant
										<?php } ?> <br />
										
										<small>
											Par <?php echo $eachTicket['last_message_infos']['username']; ?>
										</small>
									</td>
									<td>
										<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/view/<?php echo $eachTicket['ticket_key']; ?>" class="btn btn-success btn-xs">
											View <i class="fa fa-eye fa-fw"></i>
										</a>
									</td>
									<td>
										<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/delete/<?php echo $eachTicket['id']; ?>" class="btn btn-danger btn-xs">
											Delete <i class="fa fa-trash fa-fw"></i>
										</a>
									</td>
								</tr>
							<?php } else { ?>
								<?php $_Oli->deleteLinesMySQL('manager_tickets', array('id' => $eachTicket['id'])); ?>
								<?php $countTickets--; ?>
								
								<tr class="danger">
									<td></td>
									<td colspan="8">
										Un ticket expiré vient d'être supprimé.
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
								<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/delete/" class="deleteSelected btn btn-danger btn-xs">
									Selected <i class="fa fa-trash fa-fw"></i>
								</a>
							</td>
						</tr>
					</tfoot>
				</table>
			<?php } else { ?>
				<p>
					<?php if(($_Oli->getUrlParam(2) == 'admin' OR $deleteAsAdmin) AND $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
						Aucun ticket utilisateur à traiter.
					<?php } else { ?>
						Vous n'avez aucun ticket.
					<?php } ?>
				</p>
			<?php } ?>
		</div>
	<?php } ?>
</div>

<?php include 'footer.php'; ?>

<script>
(function($) {

$('.selector').click(function() {
	$(this).toggleClass('checked');
	
	if($(this).hasClass('checked')) {
		$(this).find('.fa').removeClass('fa-square-o');
		$(this).find('.fa').addClass('fa-check-square');
	}
	else {
		$(this).find('.fa').removeClass('fa-check-square');
		$(this).find('.fa').addClass('fa-square-o');
	}
});
$('.selectAll').click(function(e) {
	e.preventDefault();
	$('.selector').addClass('checked');
	$('.selector').each(function() {
		if($(this).hasClass('checked')) {
			$(this).find('.fa').removeClass('fa-square-o');
			$(this).find('.fa').addClass('fa-check-square');
		}
		else {
			$(this).find('.fa').removeClass('fa-check-square');
			$(this).find('.fa').addClass('fa-square-o');
		}
	});
	return false;
});
$('.unselectAll').click(function(e) {
	e.preventDefault();
	$('.selector').removeClass('checked');
	$('.selector').each(function() {
		if($(this).hasClass('checked')) {
			$(this).find('.fa').removeClass('fa-square-o');
			$(this).find('.fa').addClass('fa-check-square');
		}
		else {
			$(this).find('.fa').removeClass('fa-check-square');
			$(this).find('.fa').addClass('fa-square-o');
		}
	});
	return false;
});
$('.deleteSelected').click(function(e) {
	e.preventDefault();
	$('#scriptMessage').hide().removeClass().addClass('message');
	
	var selectArray = [];
	$('.selector.checked').parent().each(function() {
		selectArray.push($(this).attr('id'));
	});
	
	if(selectArray.length > 0) {
		// alert(serialize(selectArray));
		var url = $(this).attr('href') + encodeURIComponent(serialize(selectArray));
		window.location = url;
	}
	else {
		$('#scriptMessage').addClass('message-danger');
		$('#scriptMessage').find('h2').empty().append(
			$('<i>').addClass('fa fa-times fa-fw'),
			' Rien n\'a été sélectionné'
		);
		$('#scriptMessage').find('p').empty();
		$('#scriptMessage').show();
	}
	return false;
});

})(jQuery);
</script>

</body>
</html>