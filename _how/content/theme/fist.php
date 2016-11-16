<!DOCTYPE html>
<html>
<head>

<?php include THEMEPATH . 'head.php'; ?>
<title>Fist Me Daddy</title>

</head>
<body>

<input type="hidden" id="fistMusicSrc" value="<?php echo $_Oli->getDataUrl(); ?>mp3/fistMusic.mp3">

<div class="main">
	<div class="container">
		<h1>Fist Me Daddy</h1>
		<p>PewDiePie</p>
	</div>
</div>

<div class="footer">
	<?php include THEMEPATH . 'footer.php'; ?>
	<li>
		<i class="fa fa-youtube-play fa-fw"></i> <a href="https://youtu.be/8cIz3DKSKeI">Original video</a>
	</li>
	<li>
		Fist Edition
	</li>
</div>

<?php $_Oli->loadEndHtmlFiles(); ?>

<script>
function changeBackground() {
	if($('body').hasClass('black')) {
		var timeout = 250;
		$('body').removeClass().addClass('dark-gray').css({
			background: '#444'
		});
	}
	else if($('body').hasClass('dark-gray')) {
		var timeout = 250;
		$('body').removeClass().addClass('light-gray').css({
			background: '#888'
		});
	}
	else if($('body').hasClass('light-gray')) {
		var timeout = 250;
		$('body').removeClass().addClass('very-light-gray').css({
			background: '#bbb'
		});
	}
	else {
		var timeout = 250;
		$('body').removeClass().addClass('black').css({
			background: '#000'
		});
	}
	setTimeout(changeBackground, timeout);
}

$(document).ready(function() {
	var ballsMusic;
	changeBackground();
	
	ballsMusic = new Audio($('#fistMusicSrc').val());
	ballsMusic.load();
	ballsMusic.loop = true;
	ballsMusic.play();
});
</script>

<!-- Script executed with Oli PHP Framework in <?php echo $_Oli->getExecuteDelay() * 1000; ?> ms -->
<!-- Request executed with Oli PHP Framework in <?php echo $_Oli->getExecuteDelay(true) * 1000; ?> ms -->

</body>
</html>