<?php
if(!$_Oli->verifyAuthKey() OR $_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) < $_Oli->translateUserRight('USER'))
	header('Location: ' . $_Oli->getShortcutLink('login'));

if($_Oli->getUrlParam(2) == 'edit' AND !empty($_Oli->getUrlParam(3)) AND !$_Oli->isEmptyPostVars()) {
	if(empty($_Oli->getPostVars()['name']))
		$resultCode = 'NAME_EMPTY';
	else if(!$_Upload->isExistFile(array('id' => $_Oli->getUrlParam(3))))
		$resultCode = 'UNKNOWN_FILE';
	else if($_Upload->getFileInfos('owner', array('id' => $_Oli->getUrlParam(3))) == $_Oli->getAuthKeyOwner()) {
		$updatedFileName = $_Upload->getFileInfos('name', array('id' => $_Oli->getUrlParam(3)));
		
		$name = (!empty($_Oli->getPostVars()['name'])) ? $_Oli->getPostVars()['name'] : '';
		$description = (!empty($_Oli->getPostVars()['description'])) ? $_Oli->getPostVars()['description'] : '';
		$nominative = ($_Oli->getPostVars()['nominative'] == 'public') ? true : false;
		$sensitiveContent = ($_Oli->getPostVars()['sensitiveContent']) ? true : false;
	
		$_Upload->updateFileInfos(array('name' => $name, 'description' => $description, 'nominative' => $nominative, 'sensitive_content' => $sensitiveContent), array('id' => $_Oli->getUrlParam(3)));
		if($_Oli->getUrlParam(4) == 'from-preview')
			header('Location: ' . $_Oli->getOption('url') . 'preview/' . $_Upload->getFileInfos('file_key', array('id' => $_Oli->getUrlParam(3))));
		else
			$resultCode = 'FILE_EDITED';
	}
	else
		$resultCode = 'NOT_YOUR_FILE';
}
else if($_Oli->getUrlParam(2) == 'delete' AND !empty($_Oli->getUrlParam(3))) {
	$paramData = urldecode($_Oli->getUrlParam(3));
	$selectedFiles = (!is_array($paramData)) ? ((is_array(unserialize($paramData))) ? unserialize($paramData) : [$paramData]) : $paramData;
		
	$errorStatus = '';
	foreach($selectedFiles as $eachKey) {
		if(!$_Upload->isExistFile(array('id' => $eachKey))) {
			$errorStatus = 'UNKNOWN_FILE';
			break;
		}
		else if($_Upload->getFileInfos('owner', array('id' => $eachKey)) != $_Oli->getAuthKeyOwner()) {
			$errorStatus = 'NOT_YOUR_FILE';
			break;
		}
	}
	
	if(!empty($errorStatus))
		$resultCode = $errorStatus;
	else if($_Oli->getUrlParam(4) != 'confirmed')
		$resultCode = 'CONFIRMATION_NEEDED';
	else {
		foreach($selectedFiles as $eachKey) {
			$_Upload->deleteFile(array('id' => $eachKey));
		}
		$resultCode = 'FILE_DELETED';
	}
}
?>

<!DOCTYPE html>
<html>
<head>

<?php include 'head.php'; ?>
<title>Manager - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include 'header.php'; ?>

<div class="header">
	<div class="container">
		<h1><i class="fa fa-file-image-o fa-fw"></i> Mes images</h1>
		<p>
			Page de gestion de vos images.
		</p>
	</div>
</div>

<?php if($_Oli->getUrlParam(2) == 'delete' AND !empty($_Oli->getUrlParam(3)) AND $resultCode == 'CONFIRMATION_NEEDED') { ?>
	<div class="message message-warning">
		<div class="container">
			<h1>Confirmez la suppression des images sélectionnés</h1>
			<p>
				<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/<?php echo $_Oli->getUrlParam(2); ?>/<?php echo $_Oli->getUrlParam(3); ?>/confirmed" class="btn btn-primary btn-block">
					<i class="fa fa-check fa-fw"></i> J'autorise la suppression définive de ces images et de leurs données
				</a>
				<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/" class="btn btn-danger btn-block">
					<i class="fa fa-times fa-fw"></i> Je refuse de supprimer ces images
				</a>
			</p>
		</div>
	</div>
<?php } else if($resultCode == 'NAME_EMPTY') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Le nom de l'image ne peut pas être laissé vide</h2>
		</div>
	</div>
<?php } else if($resultCode == 'UNKNOWN_FILE') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous avez tenté d'effectuer une action sur une image qui nous est inconnu ou qui n'existe pas</h2>
		</div>
	</div>
<?php } else if($resultCode == 'FILE_EDITED') { ?>
	<div class="message message-success">
		<div class="container">
			<h2>Les informations liées à l'image "<?php echo $updatedFileName; ?>" ont bien été mises à jour</h2>
		</div>
	</div>
<?php } else if($resultCode == 'FILE_DELETED') { ?>
	<div class="message message-success">
		<div class="container">
			<h2>Les images sélectionnés ont bien été supprimés de nos serveurs</h2>
		</div>
	</div>
<?php } else if($resultCode == 'NOT_YOUR_FILE') { ?>
	<div class="message message-danger">
		<div class="container">
			<h2>Vous avez tenté d'effectuer une action sur une image qui ne vous appartient pas, celle-ci a donc échouée</h2>
		</div>
	</div>
<?php } ?>

<div class="main">
	<div class="container">
		<?php if($_Oli->getUrlParam(2) == 'edit' AND !empty($_Oli->getUrlParam(3)) AND $_Upload->isExistFile(array('id' => $_Oli->getUrlParam(3))) AND empty($updatedFileName)) { ?>
			<a href="<?php echo $_Oli->getOption('url'); ?>my-files/" class="btn btn-primary btn-xs">
				<i class="fa fa-angle-left fa-fw"></i> Revenir à la liste de vos fichiers
			</a>
			
			<?php $fileInfos = $_Upload->getFileLines(array('id' => $_Oli->getUrlParam(3))); ?>
			<form action="<?php echo $_Oli->getOption('url'); ?>form.php" class="form form-horizontal" method="post">
				<h1>
					Edition du fichier
					<span class="text-primary"><?php echo $fileInfos['name']; ?></span>
				</h1>
				<div class="form-group">
					<label class="col-sm-2 control-label">Nom</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="name" value="<?php echo $fileInfos['name']; ?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Description</label>
					<div class="col-sm-10">
						<textarea class="form-control" name="description" rows="4"><?php echo $fileInfos['description']; ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Visibilité</label>
					<div class="col-sm-10">
						<div class="radio">
							<label><input type="radio" name="nominative" value="public" <?php if($fileInfos['nominative']) { ?>checked<?php } ?> /> Afficher mon pseudonyme</label> <br />
							<label><input type="radio" name="nominative" value="anonym" <?php if(!$fileInfos['nominative']) { ?>checked<?php } ?> /> Garder mon anonymat</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Contenu choquant</label>
					<div class="col-sm-10">
						<div class="checkbox">
							<label><input type="checkbox" name="sensitiveContent" <?php if($fileInfos['sensitive_content']) { ?>checked<?php } ?> /> Marquer cette image comme pouvant être choquante</label> <br />
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-primary"><i class="fa fa-pencil fa-fw"></i> Mettre à jour</button>
						<button type="reset" class="btn btn-default"><i class="fa fa-refresh fa-fw"></i> Réinitialiser</button>
					</div>
				</div>
			</form>
		<?php } else { ?>
			<?php $yourFiles = array_reverse($_Upload->getFileLines(array('owner' => $_Oli->getAuthKeyOwner()), true, true)); ?>
			<?php if(!empty($yourFiles)) { ?>
				<?php $countFiles = count($yourFiles); ?>
				
				<h1>Vos images</h1>
				<p><i class="fa fa-sort-numeric-desc fa-fw"></i> Triés du plus récent au plus ancien</p>
				<table class="table table-hover">
					<thead>
						<tr>
							<th class="selector-menu"><i class="fa fa-check fa-fw"></i></th>
							<th>Nom</th>
							<th>Description</th>
							<th><i class="fa fa-user fa-fw"></i></th>
							<th><i class="fa fa-eye-slash fa-fw"></i></th>
							<th colspan="3"></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($yourFiles as $eachFile) { ?>
							<tr id="<?php echo $eachFile['id']; ?>">
								<?php if(!empty($selectedFiles) AND in_array($eachFile['id'], $selectedFiles)) { ?>
									<td class="selector checked">
										<i class="fa fa-check-square fa-fw"></i>
									</td>
								<?php } else { ?>
									<td class="selector">
										<i class="fa fa-square-o fa-fw"></i>
									</td>
								<?php } ?>
								
								<td><?php echo $eachFile['name']; ?></td>
								<td><?php if(!empty($eachFile['description'])) { ?>Oui<?php } else { ?>Non<?php } ?></td>
								<td><?php if($eachFile['nominative']) { ?><i class="fa fa-check-square-o"></i><?php } else { ?><i class="fa fa-square-o"></i><?php } ?></td>
								<td><?php if($eachFile['sensitive_content']) { ?><i class="fa fa-check-square-o"></i><?php } else { ?><i class="fa fa-square-o"></i><?php } ?></td>
								<td>
									<a href="<?php echo $_Oli->getOption('url'); ?>preview/<?php echo $eachFile['file_key']; ?>"  class="btn btn-success btn-xs">
										View <i class="fa fa-eye fa-fw"></i>
									</a>
								</td>
								<td>
									<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/edit/<?php echo $eachFile['id']; ?>" class="btn btn-primary btn-xs">
										Edit <i class="fa fa-pencil fa-fw"></i>
									</a>
								</td>
								<td>
									<a href="<?php echo $_Oli->getOption('url') . $_Oli->getUrlParam(1); ?>/delete/<?php echo $eachFile['id']; ?>" class="btn btn-danger btn-xs">
										Delete <i class="fa fa-trash fa-fw"></i>
									</a>
								</td>
							</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="5">
								<a href="#selectAll" class="selectAll btn btn-primary btn-xs">
									Select All <i class="fa fa-check-square fa-fw"></i>
								</a>
								<a href="#unselectAll" class="unselectAll btn btn-danger btn-xs">
									Unselect All <i class="fa fa-square-o fa-fw"></i>
								</a>
							</td>
							<td colspan="2"><?php echo $countFiles; ?> <small>image<?php if($countFiles > 1) { ?>s<?php } ?></small></td>
							<td>
								<a href="<?php echo $_Oli->getOption('url'); ?>my-files/delete/" class="deleteSelected btn btn-danger btn-xs">
									Selected <i class="fa fa-trash fa-fw"></i>
								</a>
							</td>
						</tr>
					</tfoot>
				</table>
				
				<?php if($_Oli->getUserRightLevel(array('username' => $_Oli->getAuthKeyOwner())) >= $_Oli->translateUserRight('MODERATOR')) { ?>
					<a href="<?php echo $_Oli->getOption('url'); ?>manage-files" class="btn btn-primary disabled">Accéder au panel de modération</a>
				<?php } ?>
			<?php } else { ?>
				<h3>Vous n'avez aucune image pour le moment.</h3>
			<?php } ?>
		<?php } ?>
	</div>
</div>

<?php include 'footer.php'; ?>

<script>
(function($) {

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