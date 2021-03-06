<!DOCTYPE html>
<html>
<head>

<?php include THEMEPATH . 'head.php'; ?>
<title><?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<div class="main">
	<div class="container">
		<h1><?php echo $_Oli->getOption('name'); ?></h1>
		<p>
			<?php echo $_Oli->getOption('description'); ?>
		</p>
	</div>
</div>

<div class="footer">
	<?php include THEMEPATH . 'footer.php'; ?>
	<li>
		Gay Edition
	</li>
</div>

<?php $_Oli->loadEndHtmlFiles(); ?>

<script>
function changeBackground() {
	if($('body').hasClass('red')) {
		$('body').removeClass().addClass('orange').css({
			background: '#f0ad4e'
		});
	}
	else if($('body').hasClass('orange')) {
		$('body').removeClass().addClass('yellow').css({
			background: '#ffcc00'
		});
	}
	else if($('body').hasClass('yellow')) {
		$('body').removeClass().addClass('green').css({
			background: '#34a853'
		});
	}
	else if($('body').hasClass('green')) {
		$('body').removeClass().addClass('blue').css({
			background: '#4285f4'
		});
	}
	else if($('body').hasClass('blue')) {
		$('body').removeClass().addClass('purple').css({
			background: '#bf00fe'
		});
	}
	else {
		$('body').removeClass().addClass('red').css({
			background: '#ea4335'
		});
	}
	
	setTimeout(changeBackground, 1000);
}

$(document).ready(function() {
	changeBackground();
});
</script>

<!-- Script executed with Oli PHP Framework in <?php echo $_Oli->getExecuteDelay() * 1000; ?> ms -->
<!-- Request executed with Oli PHP Framework in <?php echo $_Oli->getExecuteDelay(true) * 1000; ?> ms -->

</body>
</html>