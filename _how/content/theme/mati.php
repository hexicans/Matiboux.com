<!DOCTYPE html>
<html>
<head>

<?php include THEMEPATH . 'head.php'; ?>
<title>Mati sad song</title>

</head>
<body>

<input type="hidden" id="matiSadSongSrc" value="<?php echo $_Oli->getDataUrl(); ?>mp3/matiSadSong.mp3">

<div class="main">
	<div class="container">
		<h1>Mati sad song</h1>
		<p>
			I'm being sad again.. so back in a bad mood once again... <br />
			Today is September 13th, 2016. <br />
			<small>From the Expand game soundtracks</small>
		</p>
	</div>
</div>

<div class="footer">
	<?php include THEMEPATH . 'footer.php'; ?>
	<li>
		<i class="fa fa-steam fa-fw"></i> <a href="http://expandgame.com/">Expand game</a>
	</li>
	<li>
		Sad Mati Edition
	</li>
</div>

<?php $_Oli->loadEndHtmlFiles(); ?>

<script>
function changeBackground() {
	if($('body').hasClass('dark-gray')) {
		$('body').removeClass().addClass('battleship-grey').css({
			background: '#788585'
		});
	}
	else if($('body').hasClass('battleship-grey')) {
		$('body').removeClass().addClass('dim-gray').css({
			background: '#6f6866'
		});
	}
	else if($('body').hasClass('dim-gray')) {
		$('body').removeClass().addClass('old-burgundy').css({
			background: '#38302e'
		});
	}
	else if($('body').hasClass('old-burgundy')) {
		$('body').removeClass().addClass('dim-gray-2').css({
			background: '#6f6866'
		});
	}
	else if($('body').hasClass('dim-gray-2')) {
		$('body').removeClass().addClass('battleship-grey-2').css({
			background: '#788585'
		});
	}
	else {
		$('body').removeClass().addClass('dark-gray').css({
			background: '#9caea9'
		});
	}
	
	setTimeout(changeBackground, 2614);
}

$(document).ready(function() {
	changeBackground();
	
	var maliottSadSong;
	maliottSadSong = new Audio($('#matiSadSongSrc').val());
	maliottSadSong.load();
	maliottSadSong.loop = true;
	maliottSadSong.play();
});
</script>

<!-- Script executed with Oli PHP Framework in <?php echo $_Oli->getExecuteDelay() * 1000; ?> ms -->
<!-- Request executed with Oli PHP Framework in <?php echo $_Oli->getExecuteDelay(true) * 1000; ?> ms -->

</body>
</html>