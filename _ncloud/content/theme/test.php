<!DOCTYPE html>
<html>
<head>

<?php include 'head.php'; ?>
<title>Test - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include 'header.php'; ?>

<div class="header">
	<div class="container">
		<h1><i class="fa fa-times fa-fw"></i> Test</h1>
		<p>
			Ou comment détruire un serveur en quelques temps
		</p>
	</div>
</div>

<?php $yourFiles = $_Upload->getFileLines(array('owner' => $_Oli->getAuthKeyOwner()), true, true); ?>
<div class="main">
	<div class="container">
		Okok: <?php print_r($_Oli->getFirstInfoMySQL('imgshot_uploads', 'id')); ?> <br />
		Okok: <?php print_r($_Oli->getLastInfoMySQL('imgshot_uploads', 'id')); ?>
	</div>
</div>

<?php include 'footer.php'; ?>

<?php $_Oli->loadEndHtmlFiles(); ?>

</body>
</html>