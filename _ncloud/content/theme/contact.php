<?php
if(!$_Oli->isEmptyPostVars()) {
	if(empty($_Oli->getPostVars()['name']))
		$resultCode = 'YOUR_NAME_EMPTY';
	else if(empty($_Oli->getPostVars()['email']))
		$resultCode = 'YOUR_EMAIL_EMPTY';
	else if(empty($_Oli->getPostVars()['subject']))
		$resultCode = 'SUBJECT_EMPTY';
	else if(empty($_Oli->getPostVars()['message']))
		$resultCode = 'MESSAGE_EMPTY';
	else {
		$headers = 'From: ' . $_Oli->getPostVars()['email'] . "\r\n" .
			'Reply-To: ' . $_Oli->getPostVars()['email'] . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
		$parameters = '-f' . $_Oli->getPostVars()['email'];
		
		// if(mail('contact@' . $_Oli->getOption('domain'), $_Oli->getPostVars()['subject'], $_Oli->getPostVars()['message'], $headers, $parameters))
			// $resultCode = 'EMAIL_SENT';
		// else
			$resultCode = 'EMAIL_FAILED';
	}
}
?>

<!DOCTYPE html>
<html>
<head>

<?php include 'head.php'; ?>
<title>Home - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include 'header.php'; ?>

<div class="header">
	<div class="container">
		<h1>Contactez-nous</h1>
		<p>
			Envoyez-nous un message grâce à ce formulaire de contact
		</p>
	</div>
</div>

<?php if($resultCode == 'YOUR_NAME_EMPTY') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous devez indiquer votre nom</h2>
		</div>
	</div>
<?php } else if($resultCode == 'YOUR_EMAIL_EMPTY') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous devez indiquer votre email</h2>
		</div>
	</div>
<?php } else if($resultCode == 'SUBJECT_EMPTY') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous devez indiquer un sujet</h2>
		</div>
	</div>
<?php } else if($resultCode == 'MESSAGE_EMPTY') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous devez indiquer un message à envoyer</h2>
		</div>
	</div>
<?php } else if($resultCode == 'EMAIL_SENT') { ?>
	<div class="message message-success">
		<div class="container">
			<h2>Le message a correctement été envoyé</h2>
		</div>
	</div>
<?php } else if($resultCode == 'EMAIL_FAILED') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Une erreur est survenue, le message ne s'est pas envoyé</h2>
		</div>
	</div>
<?php } ?>

<div class="main">
	<div class="container">
		<form action="<?php echo $_Oli->getOption('url'); ?>form.php" class="form form-horizontal" method="post">
			<div class="form-group">
				<label class="col-sm-2 control-label">Nom</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="name" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Email</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="email" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Sujet</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="subject" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Message</label>
				<div class="col-sm-10">
					<textarea class="form-control" name="message" rows="5"></textarea>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-primary">Mettre en ligne</button>
					<button type="reset" class="btn btn-default">Réinitialiser</button>
				</div>
			</div>
		</form>
	</div>
</div>

<?php include 'footer.php'; ?>

<?php $_Oli->loadEndHtmlFiles(); ?>

</body>
</html>