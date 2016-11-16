<?php
if(!empty($_Oli->getPostVars())) {
	if(empty($_Oli->getPostVars()['link']))
		$resultCode = 'LINK_EMPTY';
	else if(!preg_match('/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i', $_Oli->getPostVars()['link']))
		$resultCode = 'LINK_INVALID';
	else if(!$_Oli->verifyAuthKey() AND $_Oli->isExistInfosMySQL('url_shortener_list', array('link' => $_Oli->getPostVars()['link']))) {
		$resultCode = 'LINK_ALREADY_EXIST';
		$shortenedLink = $_Oli->getOption('url') . $_Oli->getInfosMySQL('url_shortener_list', 'link_key', array('link' => $_Oli->getPostVars()['link']));
	}
	else {
		$id = $_Oli->getLastInfoMySQL('url_shortener_list', 'id') + 1;
		$link = $_Oli->getPostVars()['link'];
		$sensitiveLink = ($_Oli->getPostVars()['sensitiveLink']) ? true : false;
		$owner = ($_Oli->verifyAuthKey()) ? $_Oli->getAuthKeyOwner() : '';
		$date = date('Y-m-d');
		$time = date('H:i:s');
		$linkKey = $_Oli->keygen(5, true, false, true);
		$clickCount = 0;
		
		if($_Oli->insertLineMySQL('url_shortener_list', array('id' => $id, 'link' => $link, 'sensitive_link' => $sensitiveLink, 'owner' => $owner, 'date' => $date, 'time' => $time, 'link_key' => $linkKey, 'click_count' => $clickCount))) {
			$resultCode = 'SHORTENED_OK';
			$shortenedLink = $_Oli->getOption('url') . $linkKey;
		}
		else
			$resultCode = 'SHORTENED_FAILED';
	}
}
?>

<!DOCTYPE html>
<html>
<head>

<?php include 'head.php'; ?>
<title><?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include 'header.php'; ?>

<div class="header">
	<div class="container">
		<h1><i class="fa fa-file-text-o fa-fw"></i> <?php echo $_Oli->getOption('name'); ?></h1>
		<p>
			<?php echo $_Oli->getOption('description'); ?>
		</p>
	</div>
</div>

<?php if($resultCode == 'LINK_EMPTY') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous devez entrer un lien à raccourcir</h2>
		</div>
	</div>
<?php } else if($resultCode == 'LINK_INVALID') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous devez entrer un lien valide</h2>
		</div>
	</div>
<?php } else if($resultCode == 'SHORTENED_OK' AND !empty(shortenedLink)) { ?>
	<div class="message message-success">
		<div class="container">
			<h2>Votre lien a bien été raccourci, le voici :</h2>
			<p>
				<a href="<?php echo $shortenedLink; ?>" class="copyLink label label-primary"><?php echo $shortenedLink; ?></a>
			</p>
		</div>
	</div>
<?php } else if($resultCode == 'LINK_ALREADY_EXIST' AND !empty(shortenedLink)) { ?>
	<div class="message message-success">
		<div class="container">
			<h2>Ce lien a déjà été raccourci par quelqu'un, le voilà :</h2>
			<p>
				<a href="<?php echo $shortenedLink; ?>" class="copyLink label label-primary"><?php echo $shortenedLink; ?></a>
			</p>
		</div>
	</div>
<?php } else if($resultCode == 'SHORTENED_FAILED') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Une erreur s'est produite</h2>
		</div>
	</div>
<?php } else if($resultCode == 'UNKNOWN_SHOTENED_LINK') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Ce lien raccourci nous est inconnu ou n'existe pas</h2>
		</div>
	</div>
<?php } ?>

<div class="message" id="scriptMessage" style="display: none;">
	<div class="container">
		<h2></h2>
		<p></p>
	</div>
</div>

<div class="main">
	<div class="container">
		<form action="<?php echo $_Oli->getOption('url'); ?>form.php" class="form form-horizontal" method="post">
			<div class="form-group">
				<label class="col-sm-2 control-label">Votre lien</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="link" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Lien choquant</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label><input type="checkbox" name="sensitiveLink" /> Marquer ce lien comme pouvant être choquant</label> <br />
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-primary">Raccourcir</button>
					<button type="reset" class="btn btn-default">Réinitialiser</button>
				</div>
			</div>
		</form>
	</div>
</div>

<?php include 'footer.php'; ?>

<script>
(function($) {

$('.copyLink').click(function(e) {
	e.preventDefault();
	$('#scriptMessage').hide().removeClass().addClass('message');
	
	if($('#_hiddenTextToCopy_').length <= 0) {
		$('body').append(
			$('<textarea>').attr({
				id: '_hiddenTextToCopy_'
			}).css({
				position: 'absolute',
				top: '0',
				left: '-9999px'
			})
		);
	}
    var currentFocus = document.activeElement;
	$('#_hiddenTextToCopy_').empty().append($(this).attr('href')).focus();
    $('#_hiddenTextToCopy_')[0].setSelectionRange(0, $('#_hiddenTextToCopy_').val().length);
    
    try {
    	document.execCommand('copy');
    } catch(exception) {
        succeed = false;
    }
    $(currentFocus).focus();
	
	$('#scriptMessage').addClass('message-success');
	$('#scriptMessage').find('h2').empty().append(
		$('<i>').addClass('fa fa-clipboard fa-fw'),
		' Le lien a été copié !'
	);
	$('#scriptMessage').find('p').empty();
	$('#scriptMessage').show();
	
    return false;
});

})(jQuery);
</script>

</body>
</html>