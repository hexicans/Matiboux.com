<?php
if(!$_Oli->verifyAuthKey()
OR $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) < $_Oli->translateUserRight('USER'))
	header('Location: ' . $_Oli->getShortcutLink('login'));

if($_Oli->getUrlParam(2) == 'show'
AND !empty($_Oli->getUrlParam(3))
AND !$_Oli->isEmptyPostVars()) {
	if(empty($_Oli->getPostVars()['message']))
		$resultCode = 'MESSAGE_EMPTY';
	else if(!$_Oli->isExistInfosMySQL('messenger_conversations', array('conversation_key' => $_Oli->getUrlParam(3))))
		$resultCode = 'UNKNOWN_CONVERSATION';
	else if(!$_Oli->isExistInfosMySQL('messenger_members', array('username' => $_Oli->getAuthKeyOwner(), 'conversation_key' => $_Oli->getUrlParam(3))))
		$resultCode = 'NOT_CONVERSATION_MEMBER';
	else {
		$message = (!empty($_Oli->getPostVars()['message']) ? $_Oli->getPostVars()['message'] : '');
		$post_date = date('Y-m-d H:i:s');
		
		$_Oli->insertLineMySQL('messenger_messages', array('id' => $_Oli->getLastInfoMySQL('messenger_messages', 'id') + 1, 'author' => $_Oli->getAuthKeyOwner(), 'message' => $message, 'conversation_key' => $_Oli->getUrlParam(3), 'status' => 'new', 'post_date' => $post_date));
		$_Oli->updateInfosMySQL('messenger_messages', array('last_message_date' => $post_date), array('conversation_key' => $_Oli->getUrlParam(3)));
		$resultCode = 'MESSAGE_SENT';
	}
}
else if($_Oli->getUrlParam(2) == 'leave'
AND !empty($_Oli->getUrlParam(3))) {
	if(!$_Oli->isExistInfosMySQL('messenger_conversations', array('conversation_key' => $_Oli->getUrlParam(3))))
		$resultCode = 'UNKNOWN_CONVERSATION';
	else if(!$_Oli->isExistInfosMySQL('messenger_members', array('username' => $_Oli->getAuthKeyOwner(), 'conversation_key' => $_Oli->getUrlParam(3))))
		$resultCode = 'NOT_CONVERSATION_MEMBER';
	else if($_Oli->getUrlParam(4) == 'confirmed') {
		$deletedFileName = $_Upload->getFileInfos('name', array('file_key' => $_Oli->getUrlParam(3)));
		
		$_Oli->deleteLinesMySQL('messenger_members', array('conversation_key' => $_Oli->getUrlParam(3)));
		$resultCode = 'CONVERSATION_LEFT';
	}
	else
		$resultCode = 'CONFIRMATION_NEEDED';
}
?>

<!DOCTYPE html>
<html>
<head>

<?php include 'head.php'; ?>
<title>Manager - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include 'header.php'; ?>

<div class="header">
	<div class="container">
		<h1>Messenger</h1>
		<p>
			Votre messagerie privée avec d'autres utilisateurs
		</p>
	</div>
</div>

<?php if($_Oli->getUrlParam(2) == 'leave'
AND !empty($_Oli->getUrlParam(3))
AND $resultCode == 'CONFIRMATION_NEEDED') { ?>
	<div class="message message-warning">
		<div class="container">
			<h1>Confirmez votre choix de quitter la conversation</h1>
			<p>
				<a href="<?php echo $_Oli->getOption('url'); ?>messenger/leave/<?php echo $_Oli->getUrlParam(3); ?>/confirmed" class="btn btn-primary btn-block">
					<i class="fa fa-check fa-fw"></i> Je souhaite quitter cette conversation
				</a>
				<a href="<?php echo $_Oli->getOption('url'); ?>messenger/" class="btn btn-danger btn-block">
					<i class="fa fa-times fa-fw"></i> Je refuse de quitter cette conversation
				</a>
			</p>
		</div>
	</div>
<?php } else if($resultCode == 'MESSAGE_EMPTY') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Le message ne peut pas être laissé vide</h2>
		</div>
	</div>
<?php } else if($resultCode == 'UNKNOWN_CONVERSATION') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous avez tenté d'effectuer une action sur une conversation qui nous est inconnue ou qui n'existe pas</h2>
		</div>
	</div>
<?php } else if($resultCode == 'NOT_CONVERSATION_MEMBER') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous avez tenté d'effectuer une action sur une conversation dont vous ne faite pas partie, celle-ci a donc échouée</h2>
		</div>
	</div>
<?php } else if($resultCode == 'MESSAGE_SENT') { ?>
	<div class="message message-success">
		<div class="container">
			<h2>Votre message a bien été envoyé</h2>
		</div>
	</div>
<?php } else if($resultCode == 'CONVERSATION_LEFT') { ?>
	<div class="message message-success">
		<div class="container">
			<h2>Vous avez quitter la conversation</h2>
		</div>
	</div>
<?php } /* ... */
else if($resultCode == 'UNKNOWN_FILE') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous avez tenté d'effectuer une action sur une image qui nous est inconnu ou qui n'existe pas</h2>
		</div>
	</div>
<?php } else if($resultCode == 'FILE_DELETED') { ?>
	<div class="message message-success">
		<div class="container">
			<h2>Les informations liées à l'image "<?php echo $updatedFileName; ?>" ont bien été mises à jour</h2>
		</div>
	</div>
<?php } else if($resultCode == 'FILE_DELETED') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>L'image "<?php echo $deletedFileName; ?>" a bien été supprimé de nos serveurs</h2>
		</div>
	</div>
<?php } else if($resultCode == 'NOT_YOUR_FILE') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous avez tenté d'effectuer une action sur une image qui ne vous appartient pas, celle-ci a donc échouée</h2>
		</div>
	</div>
<?php } ?>

<div class="main">
	<div class="container">
		<?php if($_Oli->getUrlParam(2) == 'show'
		AND !empty($_Oli->getUrlParam(3))
		AND $_Oli->isExistInfosMySQL('messenger_members', array('username' => $_Oli->getAuthKeyOwner(), 'conversation_key' => $_Oli->getUrlParam(3)))
		AND !$leftConversation) { ?>
			<a href="<?php echo $_Oli->getOption('url'); ?>messenger/" class="btn btn-primary btn-xs">
				<i class="fa fa-angle-left fa-fw"></i> Revenir à la liste de vos conversations
			</a>
			<a href="<?php echo $_Oli->getOption('url'); ?>messenger/leave/<?php echo $_Oli->getUrlParam(3); ?>" class="btn btn-danger btn-xs">
				<i class="fa fa-trash fa-fw"></i> Quitter la conversation
			</a>
			
			<?php $chatMessages = $_Oli->getLinesMySQL('messenger_messages', array('conversation_key' => $_Oli->getUrlParam(3))); ?>
			<?php $countChatMessages = $_Oli->isExistInfosMySQL('messenger_messages', array('conversation_key' => $_Oli->getUrlParam(3))); ?>
			<?php if(!empty($chatMessages)) { ?>
				<?php $chatMessages = ($countChatMessages == 1) ? [$chatMessages] : $chatMessages; ?>
				<?php $countShownChatMessages = count($chatMessages); ?>
				<h1>Votre conversation</h1>
				<p>
					Participants : <span class="label label-primary">Vous</span>,
					<span class="label label-default"><?php echo implode('</span>, <span class="label label-default">', array_diff($_Oli->getInfosMySQL('messenger_members', 'username', array('conversation_key' => $_Oli->getUrlParam(3))), [$_Oli->getAuthKeyOwner()])); ?></span>
				</p>
				<p>
					les <b><?php echo $countShownChatMessages; ?> derniers messages</b>
					affichés sur <?php echo $countChatMessages; ?>
				</p>
				
				<div class="conversation">
					<?php if($countShownChatMessages < $countChatMessages) { ?>
						<div class="element">
							<h3 class="text-centered"><i class="fa fa-caret-up fa-fw"></i></h3>
						</div>
					<?php } ?>
					<?php foreach($chatMessages as $eachLine) { ?>
						<div class="element">
							<div class="infos">
								<div class="author">
									<?php if($eachLine['author'] == $_Oli->getAuthKeyOwner()) { ?>
										<b>Vous</b>
									<?php } else { ?>
										<span><?php echo $eachLine['author']; ?></span>
									<?php } ?>
									
									<?php if($_Oli->getUserRightLevel(array('username' => $eachLine['author'])) >= $_Oli->translateUserRight('VIP')) { ?>
										<span class="label label-primary">
									<?php } else if($_Oli->getUserRightLevel(array('username' => $eachLine['author'])) == $_Oli->translateUserRight('VIP')) { ?>
										<span class="label label-primary">
									<?php } else { ?>
										<span class="label label-default">
									<?php } ?>
									<?php echo $_Oli->getAccountInfos('RIGHTS', 'name', array('user_right' => $_Oli->getAccountInfos('ACCOUNTS', 'user_right', array('username' => $eachLine['author'])))); ?></span>
								</div>
								<div class="date pull-right">
									<?php $timeOutput = []; ?>
									<?php foreach($_Oli->dateDifference(time(), $eachLine['post_date'], false) as $eachUnit => $eachTime) { ?>
										<?php if($eachUnit == 'years') { ?>
											<?php $timeOutput[] = '~' . $eachTime . 'y'; ?>
										<?php } else if($eachUnit == 'days') { ?>
											<?php $timeOutput[] = '~' . $eachTime . 'j'; ?>
										<?php } else if($eachUnit == 'hours') { ?>
											<?php $timeOutput[] = '~' . $eachTime . 'h'; ?>
										<?php } else if($eachUnit == 'minutes') { ?>
											<?php $timeOutput[] = $eachTime . 'm'; ?>
										<?php } else if($eachUnit == 'seconds') { ?>
											<?php $timeOutput[] = $eachTime . 's'; ?>
										<?php } ?>
									<?php } ?>
									<b><?php echo implode(', ', $timeOutput); ?></b>
									
									<?php $messageNowSeen = false; ?>
									<?php if($eachLine['status'] == 'new' AND $eachLine['author'] != $_Oli->getAuthKeyOwner()) { ?>
										<?php $messageNowSeen = $_Oli->updateInfosMySQL('messenger_messages', array('status' => 'seen'), array('id' => $eachLine['id'])); ?>
									<?php } ?>
									
									<?php if($eachLine['status'] == 'seen' OR $messageNowSeen) { ?>
										<span class="text-danger"><i class="fa fa-check fa-fw"></i></span>
									<?php } else if($eachLine['status'] == 'new') { ?>
										<span class="text-primary"><i class="fa fa-send fa-fw"></i></span>
									<?php } ?>
								</div>
							</div>
							
							<div class="content">
								<?php echo htmlentities($eachLine['message']); ?>
							</div>
						</div>
					<?php } ?>
					
					<form action="<?php echo $_Oli->getOption('url'); ?>form.php" class="form form-horizontal" method="post">
						<h4>Envoyer une réponse</h4>
						<div class="form-group">
							<div class="col-sm-12">
								<textarea class="form-control" name="message" rows="4"></textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-12">
								<button type="submit" class="btn btn-primary">Envoyer</button>
								<button type="reset" class="btn btn-default">Réinitialiser</button>
							</div>
						</div>
					</form>
				</div>
			<?php } else { ?>
				<h3>Conversation vide (ceci est anormal).</h3>
			<?php } ?>
		<?php } else { ?>
			<?php $yourChats = $_Oli->getLinesMySQL('messenger_members', array('username' => $_Oli->getAuthKeyOwner())); ?>
			<?php if(!empty($yourChats)) { ?>
				<h1>Vos conversations</h1>
				<table class="table table-hover">
					<thead>
						<tr>
							<th></th>
							<th>Correspondants</th>
							<th>Identifiant</th>
							<th>Grade</th>
							<th>Dernier Message</th>
							<td>
								<a href="<?php echo $_Oli->getOption('url'); ?>messenger/new"  class="btn btn-primary btn-xs">
									New chat <i class="fa fa-plus fa-fw"></i>
								</a>
							</td>
						</tr>
					</thead>
					<tbody>
						<?php $countChats = 3; ?>
						<?php if(is_array($yourChats[0])) { ?>
							<?php $countChats = count($yourChats); ?>
							<?php foreach($yourChats as $eachLine) { ?>
								<tr>
									<?php $countNotifications = 0; ?>
									<?php if($_Oli->isExistInfosMySQL('messenger_messages', array('conversation_key' => $eachLine['conversation_key'], 'status' => 'new'))) { ?>
										<?php $countNotifications += $_Oli->isExistInfosMySQL('messenger_messages', array('conversation_key' => $eachLine['conversation_key'], 'status' => 'new')) - $_Oli->isExistInfosMySQL('messenger_messages', array('author' => $_Oli->getAuthKeyOwner(), 'conversation_key' => $yourChats['conversation_key'], 'status' => 'new')); ?></span></td>
									<?php } ?>
									<?php if($countNotifications >= 1) { ?>
										<td><span class="label label-danger">+<?php echo $countNotifications; ?></span></td>
									<?php } else { ?>
										<td><span class="label label-primary"><?php echo $_Oli->isExistInfosMySQL('messenger_messages', array('conversation_key' => $eachLine['conversation_key'])); ?></span></td>
									<?php } ?>
									<td><?php echo implode(', ', array_diff($_Oli->getInfosMySQL('messenger_members', 'username', array('conversation_key' => $eachLine['conversation_key'])), [$_Oli->getAuthKeyOwner()])); ?></td>
									<td><?php echo $eachLine['conversation_key']; ?></td>
									<td><?php echo $eachLine['user_right']; ?></td>
									<td>
										<?php $timeOutput = []; ?>
										<?php foreach(array_slice($_Oli->dateDifference(time(), $_Oli->getInfosMySQL('messenger_conversations', 'last_message_date', array('conversation_key' => $eachLine['conversation_key'])), true), 0, 2) as $eachUnit => $eachTime) { ?>
											<?php if($eachUnit == 'years') { ?>
												<?php $timeOutput[] = $eachTime . ' ans'; ?>
											<?php } else if($eachUnit == 'days') { ?>
												<?php $timeOutput[] = $eachTime . 'j'; ?>
											<?php } else if($eachUnit == 'hours') { ?>
												<?php $timeOutput[] = $eachTime . 'h'; ?>
											<?php } else if($eachUnit == 'minutes') { ?>
												<?php $timeOutput[] = $eachTime . 'mn'; ?>
											<?php } else if($eachUnit == 'seconds') { ?>
												<?php $timeOutput[] = $eachTime . 's'; ?>
											<?php } ?>
										<?php } ?>
										<?php $lastTimeOutput = (count($timeOutput) > 1) ? array_pop($timeOutput) : ''; ?>
										Il y a
										<b>
											<?php echo implode(', ', $timeOutput); ?>
											<?php if(!empty($lastTimeOutput)) { ?>
												et <?php echo $lastTimeOutput; ?>
											<?php } ?>
										</b>
									</td>
									<td>
										<a href="<?php echo $_Oli->getOption('url'); ?>messenger/show/<?php echo $eachLine['conversation_key']; ?>"  class="btn btn-success btn-xs">
											Show <i class="fa fa-eye fa-fw"></i>
										</a>
										<a href="<?php echo $_Oli->getOption('url'); ?>messenger/delete/<?php echo $eachLine['conversation_key']; ?>" class="btn btn-danger btn-xs">
											Delete <i class="fa fa-trash fa-fw"></i>
										</a>
									</td>
								</tr>
							<?php } ?>
						<?php } else { ?>
							<?php $countChats = 1; ?>
							<tr>
								<?php $countNotifications = 0; ?>
								<?php if($_Oli->isExistInfosMySQL('messenger_messages', array('conversation_key' => $yourChats['conversation_key'], 'status' => 'new'))) { ?>
									<?php $countNotifications += $_Oli->isExistInfosMySQL('messenger_messages', array('conversation_key' => $yourChats['conversation_key'], 'status' => 'new')) - $_Oli->isExistInfosMySQL('messenger_messages', array('author' => $_Oli->getAuthKeyOwner(), 'conversation_key' => $yourChats['conversation_key'], 'status' => 'new')); ?></span></td>
								<?php } ?>
								<?php if($countNotifications >= 1) { ?>
									<td><span class="label label-danger">+<?php echo $countNotifications; ?></span></td>
								<?php } else { ?>
									<td><span class="label label-primary"><?php echo $_Oli->isExistInfosMySQL('messenger_messages', array('conversation_key' => $yourChats['conversation_key'])); ?></span></td>
								<?php } ?>
								<td><?php echo implode(', ', array_diff($_Oli->getInfosMySQL('messenger_members', 'username', array('conversation_key' => $yourChats['conversation_key'])), [$_Oli->getAuthKeyOwner()])); ?></td>
								<td><?php echo $yourChats['conversation_key']; ?></td>
								<td><?php echo $yourChats['user_right']; ?></td>
								<td>
									<?php $timeOutput = []; ?>
									<?php foreach(array_slice($_Oli->dateDifference(time(), $_Oli->getInfosMySQL('messenger_conversations', 'last_message_date', array('conversation_key' => $yourChats['conversation_key'])), true), 0, 2) as $eachUnit => $eachTime) { ?>
										<?php if($eachUnit == 'years') { ?>
											<?php $timeOutput[] = $eachTime . ' ans'; ?>
										<?php } else if($eachUnit == 'days') { ?>
											<?php $timeOutput[] = $eachTime . 'j'; ?>
										<?php } else if($eachUnit == 'hours') { ?>
											<?php $timeOutput[] = $eachTime . 'h'; ?>
										<?php } else if($eachUnit == 'minutes') { ?>
											<?php $timeOutput[] = $eachTime . 'mn'; ?>
										<?php } else if($eachUnit == 'seconds') { ?>
											<?php $timeOutput[] = $eachTime . 's'; ?>
										<?php } ?>
									<?php } ?>
									<?php $lastTimeOutput = (count($timeOutput) > 1) ? array_pop($timeOutput) : ''; ?>
									Il y a
									<b>
										<?php echo implode(', ', $timeOutput); ?>
										<?php if(!empty($lastTimeOutput)) { ?>
											et <?php echo $lastTimeOutput; ?>
										<?php } ?>
									</b>
								</td>
								<td>
									<a href="<?php echo $_Oli->getOption('url'); ?>messenger/show/<?php echo $yourChats['conversation_key']; ?>"  class="btn btn-success btn-xs">
										Show <i class="fa fa-eye fa-fw"></i>
									</a>
									<a href="<?php echo $_Oli->getOption('url'); ?>messenger/delete/<?php echo $yourChats['conversation_key']; ?>" class="btn btn-danger btn-xs">
										Delete <i class="fa fa-trash fa-fw"></i>
									</a>
								</td>
							</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="5"></td>
							<td><?php echo $countChats; ?> conversation<?php if($countChats > 1) { ?>s<?php } ?></td>
						</tr>
					</tfoot>
				</table>
			<?php } else { ?>
				<h3>Vous n'avez aucune conversation active</h3>
				<p>
					Si vous voulez, vous pouvez créer une
					<a href="<?php echo $_Oli->getOption('url'); ?>messenger/new"  class="btn btn-primary btn-xs">
						Nouvelle conversation <i class="fa fa-plus fa-fw"></i>
					</a>
				</p>
			<?php } ?>
		<?php } ?>
	</div>
</div>

<?php include 'footer.php'; ?>

<?php $_Oli->loadEndHtmlFiles(); ?>

</body>
</html>