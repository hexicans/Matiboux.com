<?php
if(!$_Oli->verifyAuthKey() OR $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) < $_Oli->translateUserRight('USER'))
	header('Location: ' . $_Oli->getShortcutLink('login'));
?>

<!DOCTYPE html>
<html>
<head>

<?php include THEMEPATH . 'head.php'; ?>
<title>About - <?php echo $_Oli->getSetting('name'); ?></title>

</head>
<body>

<?php include THEMEPATH . 'header.php'; ?>

<div class="main">
	<div class="container-fluid">
		<div class="row">
			<div class="mainBar col-sm-offset-2 col-sm-8">
				<div class="message message-primary text-center">
					<h3><i class="fa fa-life-ring fa-fw"></i>  Support</h3>
					<p>Read the FAQ below or contact our staff</p>
				</div>
				
				<?php if($_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
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
				<?php } ?>
				<div class="content-card">
					<h3>
						Your tickets
						<span class="<?php if($yourTicketsNotifs > 0) { ?>label label-primary<?php } else { ?>badge<?php } ?>">
							<?php echo $yourTicketsNotifs?: 0; ?>
						</span>
					</h3>
					<?php if($adminTicketsNotifs > 0 AND $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('ADMIN')) { ?>
						<p class="text-danger">
							As an admin, you have <?php echo $adminTicketsNotifs; ?> ticket to answer <br />
							<a href="<?php echo $_Oli->getUrlParam(0); ?>support/tickets/admin/" class="btn btn-danger">
								<i class="fa fa-pencil fa-fw"></i> Go answer these tickets
							</a>
						</p>
					<?php } else if($yourTicketsNotifs > 0) { ?>
						<p>
							<a href="<?php echo $_Oli->getUrlParam(0); ?>support/tickets/" class="btn <?php if($yourTicketsNotifs > 0) { ?>btn-primary<?php } else { ?>btn-default<?php } ?>">
								Go check your tickets
							</a>
						</p>
					<?php } else { ?>
						<p>
							<a href="<?php echo $_Oli->getUrlParam(0); ?>support/tickets/" class="btn <?php if($yourTicketsNotifs > 0) { ?>btn-primary<?php } else { ?>btn-default<?php } ?>">
								Go check your tickets or create a new one
							</a>
						</p>
					<?php } ?>
				</div>
				<div class="content-card">
					<h3>FAQ</h3>
					<p>
						<b>Is there some question here?</b> <br />
						No. Not yet.
					</p>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $_Oli->loadEndHtmlFiles(); ?>

</body>
</html>