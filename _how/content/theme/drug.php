<!DOCTYPE html>
<html>
<head>

<?php include THEMEPATH . 'head.php'; ?>
<title>Drooogue</title>

</head>
<body>

<input type="hidden" id="drugMusicSrc" value="<?php echo $_Oli->getDataUrl(); ?>mp3/drugMusic.mp3">

<div class="main">
	<div class="container">
		<h1>Drooogue</h1>
		<p>
			From one of Robert's video <br />
			<small>Don't forget to bring some Selecto</small>
		</p>
	</div>
</div>

<div class="footer">
	<?php include THEMEPATH . 'footer.php'; ?>
	<li>
		<i class="fa fa-youtube-play fa-fw"></i> <a href="https://www.youtube.com/user/GrobertTV">Robert channel</a>
	</li>
	<li>
		<i class="fa fa-youtube-play fa-fw"></i> <a href="https://youtu.be/Xq6W286_fNY">Original video</a>
	</li>
	<li>
		Drug Edition
	</li>
</div>

<?php $_Oli->loadEndHtmlFiles(); ?>

<script>
function changeBackground() {
	if($('body').hasClass('red')) {
		$('body').removeClass().addClass('yellow').css({
			background: '#ffff00'
		});
	}
	else if($('body').hasClass('yellow')) {
		$('body').removeClass().addClass('green').css({
			background: '#03ff00'
		});
	}
	else if($('body').hasClass('green')) {
		$('body').removeClass().addClass('turquoise').css({
			background: '#00feff'
		});
	}
	else if($('body').hasClass('turquoise')) {
		$('body').removeClass().addClass('dark_blue').css({
			background: '#0000ff'
		});
	}
	else if($('body').hasClass('dark_blue')) {
		$('body').removeClass().addClass('pink').css({
			background: '#ff00ff'
		});
	}
	else {
		$('body').removeClass().addClass('red').css({
			background: '#ff0000'
		});
	}
	
	setTimeout(changeBackground, 400);
}

$(document).ready(function() {
	var ballsMusic;
	changeBackground();
	
	ballsMusic = new Audio($('#drugMusicSrc').val());
	ballsMusic.load();
	ballsMusic.loop = true;
	ballsMusic.play();
});
</script>

<!-- Script executed with Oli PHP Framework in <?php echo $_Oli->getExecuteDelay() * 1000; ?> ms -->
<!-- Request executed with Oli PHP Framework in <?php echo $_Oli->getExecuteDelay(true) * 1000; ?> ms -->

</body>
</html>