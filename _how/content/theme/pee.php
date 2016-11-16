<!DOCTYPE html>
<html>
<head>

<?php include THEMEPATH . 'head.php'; ?>
<title>This is my pee pee</title>

</head>
<body>

<input type="hidden" id="peeMusicSrc" value="<?php echo $_Oli->getDataUrl(); ?>mp3/peeMusic.mp3">

<div class="main">
	<div class="container">
		<h1>This is my pee pee</h1>
		<p>Please take care of yours</p>
	</div>
</div>

<div class="footer">
	<?php include THEMEPATH . 'footer.php'; ?>
	<li>
		<i class="fa fa-youtube-play fa-fw"></i> <a href="https://youtu.be/8amtq5aXRL8">Original video</a>
	</li>
	<li>
		Pee Edition
	</li>
</div>

<?php $_Oli->loadEndHtmlFiles(); ?>

<script>
function changeBackground() {
	if($('body').hasClass('orange')) {
		var timeout = 1850;
		$('body').removeClass().addClass('light-green').css({
			background: '#34ff0a'
		});
	}
	else if($('body').hasClass('light-green')) {
		var timeout = 1850;
		$('body').removeClass().addClass('blue').css({
			background: '#135cff'
		});
	}
	else if($('body').hasClass('blue')) {
		var timeout = 1850;
		$('body').removeClass().addClass('pink').css({
			background: '#ff12f9'
		});
	}
	else if($('body').hasClass('pink')) {
		var timeout = 2250;
		$('body').removeClass().addClass('yellow').css({
			background: '#f4ff03'
		});
	}
	else if($('body').hasClass('yellow')) {
		var timeout = 1850;
		$('body').removeClass().addClass('red').css({
			background: '#ff1411'
		});
	}
	else if($('body').hasClass('red')) {
		var timeout = 1200;
		$('body').removeClass().addClass('light-blue').css({
			background: '#0de7ff'
		});
	}
	else if($('body').hasClass('light-blue')) {
		var timeout = 650;
		$('body').removeClass().addClass('light-green-alt').css({
			background: '#0bff32'
		});
	}
	else if($('body').hasClass('light-green-alt')) {
		var timeout = 1200;
		$('body').removeClass().addClass('purple').css({
			background: '#8110ff'
		});
	}
	else if($('body').hasClass('purple')) {
		var timeout = 650;
		$('body').removeClass().addClass('red-alt').css({
			background: '#ff1411'
		});
	}
	else if($('body').hasClass('red-alt')) {
		var timeout = 3800;
		$('body').removeClass().addClass('blue-alt').css({
			background: '#1963ff'
		});
	}
	else {
		var timeout = 1850;
		$('body').removeClass().addClass('orange').css({
			background: '#ffa00f'
		});
	}
	setTimeout(changeBackground, timeout);
}

$(document).ready(function() {
	var ballsMusic;
	changeBackground();
	
	ballsMusic = new Audio($('#peeMusicSrc').val());
	ballsMusic.load();
	ballsMusic.loop = true;
	ballsMusic.play();
});
</script>

<!-- Script executed with Oli PHP Framework in <?php echo $_Oli->getExecuteDelay() * 1000; ?> ms -->
<!-- Request executed with Oli PHP Framework in <?php echo $_Oli->getExecuteDelay(true) * 1000; ?> ms -->

</body>
</html>