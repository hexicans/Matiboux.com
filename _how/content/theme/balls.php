<!DOCTYPE html>
<html>
<head>

<?php include THEMEPATH . 'head.php'; ?>
<title>On s'en bat les couilles</title>

</head>
<body>

<input type="hidden" id="ballsMusicSrc" value="<?php echo $_Oli->getDataUrl(); ?>mp3/ballsMusic.mp3">

<div class="main">
	<div class="container">
		<h1>On s'en bat les couilles</h1>
	</div>
</div>

<div class="footer">
	<?php include THEMEPATH . 'footer.php'; ?>
	<li>
		<i class="fa fa-youtube-play fa-fw"></i> <a href="https://youtu.be/XoDY9vFAaG8">Original video</a>
	</li>
	<li>
		Balls Edition
	</li>
</div>

<?php $_Oli->loadEndHtmlFiles(); ?>

<script>
function changeBackground() {
	if($('body').hasClass('red')) {
		var timeout = 400;
		$('body').removeClass().addClass('green').css({
			background: '#01fe09'
		});
	}
	else if($('body').hasClass('green')) {
		var timeout = 400;
		$('body').removeClass().addClass('orange').css({
			background: '#ff8201'
		});
	}
	else if($('body').hasClass('orange')) {
		var timeout = 400;
		$('body').removeClass().addClass('blue-sky').css({
			background: '#01fcff'
		});
	}
	else if($('body').hasClass('blue-sky')) {
		var timeout = 400;
		$('body').removeClass().addClass('purple').css({
			background: '#fc00ff'
		});
	}
	else if($('body').hasClass('purple')) {
		var timeout = 400;
		$('body').removeClass().addClass('yellow').css({
			background: '#fafe02'
		});
	}
	else if($('body').hasClass('yellow')) {
		var timeout = 400;
		$('body').removeClass().addClass('dark-blue').css({
			background: '#0217ff'
		});
	}
	else {
		var timeout = 400;
		$('body').removeClass().addClass('red').css({
			background: '#ff0001'
		});
	}
	
	setTimeout(changeBackground, timeout);
}

$(document).ready(function() {
	var ballsMusic;
	
	$('body').removeClass().addClass('black').css({
		background: '#010001'
	});
	setTimeout(changeBackground, 250);
	
	ballsMusic = new Audio($('#ballsMusicSrc').val());
	ballsMusic.load();
	ballsMusic.loop = true;
	ballsMusic.play();
});
</script>

<!-- Script executed with Oli PHP Framework in <?php echo $_Oli->getExecuteDelay() * 1000; ?> ms -->
<!-- Request executed with Oli PHP Framework in <?php echo $_Oli->getExecuteDelay(true) * 1000; ?> ms -->

</body>
</html>