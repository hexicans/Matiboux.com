<?php
if(!$_Oli->verifyAuthKey() OR $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) < $_Oli->translateUserRight('USER'))
	header('Location: ' . $_Oli->getShortcutLink('login'));

if($_Oli->getUrlParam(2) == 'change-sensitive' AND !empty($_Oli->getUrlParam(3))) {
	if(!$_Oli->isExistInfosMySQL('url_shortener_list', array('id' => $_Oli->getUrlParam(3))))
		$resultCode = 'UNKNOWN_LINK';
	else if($_Oli->getInfosMySQL('url_shortener_list', 'owner', array('id' => $_Oli->getUrlParam(3))) == $_Oli->getAuthKeyOwner()) {
		$newSensitiveLink = ($_Oli->getInfosMySQL('url_shortener_list', 'sensitive_link', array('id' => $_Oli->getUrlParam(3)))) ? false : true;
		$updatedLinkKey = $_Oli->getUrlParam(3);
		
		$_Oli->updateInfosMySQL('url_shortener_list', array('sensitive_link' => $newSensitiveLink), array('id' => $_Oli->getUrlParam(3)));
		$resultCode = 'LINK_UPDATED';
	}
	else
		$resultCode = 'NOT_YOUR_LINK';
}
else if($_Oli->getUrlParam(2) == 'delete' AND !empty($_Oli->getUrlParam(3))) {
	$paramData = urldecode($_Oli->getUrlParam(3));
	$selectedLinks = (!is_array($paramData)) ? ((is_array(unserialize($paramData))) ? unserialize($paramData) : [$paramData]) : $paramData;
	
	$errorStatus = '';
	foreach($selectedLinks as $eachKey) {
		if(!$_Oli->isExistInfosMySQL('url_shortener_list', array('id' => $eachKey))) {
			$errorStatus = 'UNKNOWN_LINK';
			break;
		}
		else if($_Oli->getInfosMySQL('url_shortener_list', 'owner', array('id' => $eachKey)) != $_Oli->getAuthKeyOwner()) {
			$errorStatus = 'NOT_YOUR_LINK';
			break;
		}
	}
	
	if(!empty($errorStatus))
		$resultCode = $errorStatus;
	else if($_Oli->getUrlParam(4) != 'confirmed')
		$resultCode = 'CONFIRMATION_NEEDED';
	else {
		foreach($selectedLinks as $eachKey) {
			$_Oli->deleteLinesMySQL('url_shortener_list', array('id' => $eachKey));
		}
		$resultCode = 'LINK_DELETED';
	}
}
?>

<!DOCTYPE html>
<html>
<head>

<?php include 'head.php'; ?>
<title>Mes liens - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include 'header.php'; ?>

<div class="header">
	<div class="container">
		<h1><i class="fa fa-link fa-fw"></i> Mes liens</h1>
		<p>
			Page de gestion de vos liens
		</p>
	</div>
</div>

<?php if($_Oli->getUrlParam(2) == 'delete' AND !empty($_Oli->getUrlParam(3)) AND $resultCode == 'CONFIRMATION_NEEDED') { ?>
	<div class="message message-warning">
		<div class="container">
			<h1>Confirmez la suppression des liens sélectionnés</h1>
			<p>
				<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $_Oli->getUrlParam(3); ?>/confirmed" class="btn btn-primary btn-block">
					<i class="fa fa-check fa-fw"></i> J'autorise la suppression définive de ces lien raccourcis
				</a>
				<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/" class="btn btn-danger btn-block">
					<i class="fa fa-times fa-fw"></i> Je refuse de supprimer ces liens
				</a>
			</p>
		</div>
	</div>
<?php } else if($resultCode == 'UNKNOWN_LINK') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>
				Vous avez tenté d'effectuer une action sur
				<?php if(!empty($unknownLinks) AND $unknownLinks > 1) { ?>
					plusieurs liens qui nous sont inconnus ou qui n'existent pas
				<?php } else { ?>
					un lien qui nous est inconnu ou qui n'existe pas
				<?php } ?>
			</h2>
		</div>
	</div>
<?php } else if($resultCode == 'LINK_UPDATED') { ?>
	<div class="message message-success">
		<div class="container">
			<h2>Les informations liées au lien ont bien été mises à jour</h2>
		</div>
	</div>
<?php } else if($resultCode == 'LINK_DELETED') { ?>
	<div class="message message-success">
		<div class="container">
			<h2>Le lien a bien été supprimé de nos serveurs</h2>
		</div>
	</div>
<?php } else if($resultCode == 'NOT_YOUR_LINK') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous avez tenté d'effectuer une action sur un lien qui ne vous appartient pas, celle-ci a donc échouée</h2>
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
		<?php $yourLinks = $_Oli->getLinesMySQL('url_shortener_list', array('owner' => $_Oli->getAuthKeyOwner()), true, true); ?>
		<?php if(!empty($yourLinks)) { ?>
			<h1>Vos liens</h1>
			<table class="table table-hover">
				<thead>
					<tr>
						<th class="selector-menu"><i class="fa fa-check fa-fw"></i></th>
						<th>Lien</th>
						<th>Clé</th>
						<th>Choquant</th>
						<th colspan="3"></th>
					</tr>
				</thead>
				<tbody>
					<?php $countLinks = count($yourLinks); ?>
					<?php foreach($yourLinks as $eachLink) { ?>
						<tr id="<?php echo $eachLink['id']; ?>">
							<?php if(!empty($selectedLinks) AND in_array($eachLink['id'], $selectedLinks)) { ?>
								<td class="selector checked">
									<i class="fa fa-check-square fa-fw"></i>
								</td>
							<?php } else { ?>
								<td class="selector">
									<i class="fa fa-square-o fa-fw"></i>
								</td>
							<?php } ?>
							
							<td><?php echo $eachLink['link']; ?></td>
							<td><?php echo $eachLink['link_key']; ?></td>
							<td>
								<?php if($eachLink['sensitive_link']) { ?><i class="fa fa-check-square-o fa-fw"></i><?php } else { ?><i class="fa fa-square-o fa-fw"></i><?php } ?>
								<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/change-sensitive/<?php echo $eachLink['id']; ?>" class="btn btn-primary btn-xs">
									<?php if($eachLink['sensitive_link']) { ?>Unset <i class="fa fa-unlock fa-fw"></i><?php } else { ?>Set <i class="fa fa-lock fa-fw"></i><?php } ?>
								</a>
							</td>
							<td>
								<a href="<?php echo $_Oli->getOption('url') . $eachLink['link_key']; ?>" class="btn btn-success btn-xs">
									Go <i class="fa fa-angle-double-right fa-fw"></i>
								</a>
							</td>
							<td>
								<a href="<?php echo $_Oli->getOption('url') . $eachLink['link_key']; ?>" class="copyLink btn btn-info btn-xs">
									Copy <i class="fa fa-clipboard fa-fw"></i>
								</a>
							</td>
							<td>
								<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/delete/<?php echo $eachLink['id']; ?>" class="btn btn-danger btn-xs">
									Delete <i class="fa fa-trash fa-fw"></i>
								</a>
							</td>
						</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4">
							<a href="#selectAll" class="selectAll btn btn-primary btn-xs">
								Select All <i class="fa fa-check-square fa-fw"></i>
							</a>
							<a href="#unselectAll" class="unselectAll btn btn-danger btn-xs">
								Unselect All <i class="fa fa-square-o fa-fw"></i>
							</a>
						</td>
						<td colspan="2"><?php echo $countLinks; ?> <small>lien<?php if($countLinks > 1) { ?>s<?php } ?></small></td>
						<td>
							<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/delete/" class="deleteSelected btn btn-danger btn-xs">
								Selected <i class="fa fa-trash fa-fw"></i>
							</a>
						</td>
					</tr>
				</tfoot>
			</table>
			
			<?php if($_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('MODERATOR')) { ?>
				<a href="<?php echo $_Oli->getOption('url'); ?>admin" class="btn btn-primary disabled">Accéder au panel de modération</a>
			<?php } ?>
		<?php } else { ?>
			<h3>Vous n'avez aucun liens liés à votre compte.</h3>
		<?php } ?>
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

$('.selector').click(function() {
	$(this).toggleClass('checked');
	
	if($(this).hasClass('checked')) {
		$(this).find('.fa').removeClass('fa-square-o');
		$(this).find('.fa').addClass('fa-check-square');
	}
	else {
		$(this).find('.fa').removeClass('fa-check-square');
		$(this).find('.fa').addClass('fa-square-o');
	}
});
$('.selectAll').click(function(e) {
	e.preventDefault();
	$('.selector').addClass('checked');
	$('.selector').each(function() {
		if($(this).hasClass('checked')) {
			$(this).find('.fa').removeClass('fa-square-o');
			$(this).find('.fa').addClass('fa-check-square');
		}
		else {
			$(this).find('.fa').removeClass('fa-check-square');
			$(this).find('.fa').addClass('fa-square-o');
		}
	});
	return false;
});
$('.unselectAll').click(function(e) {
	e.preventDefault();
	$('.selector').removeClass('checked');
	$('.selector').each(function() {
		if($(this).hasClass('checked')) {
			$(this).find('.fa').removeClass('fa-square-o');
			$(this).find('.fa').addClass('fa-check-square');
		}
		else {
			$(this).find('.fa').removeClass('fa-check-square');
			$(this).find('.fa').addClass('fa-square-o');
		}
	});
	return false;
});
$('.deleteSelected').click(function(e) {
	e.preventDefault();
	$('#scriptMessage').hide().removeClass().addClass('message');
	
	var selectArray = [];
	$('.selector.checked').parent().each(function() {
		selectArray.push($(this).attr('id'));
	});
	
	if(selectArray.length > 0) {
		// alert(serialize(selectArray));
		var url = $(this).attr('href') + encodeURIComponent(serialize(selectArray));
		window.location = url;
	}
	else {
		$('#scriptMessage').addClass('message-danger');
		$('#scriptMessage').find('h2').empty().append(
			$('<i>').addClass('fa fa-times fa-fw'),
			' Rien n\'a été sélectionné'
		);
		$('#scriptMessage').find('p').empty();
		$('#scriptMessage').show();
	}
	return false;
});

})(jQuery);
</script>

</body>
</html>