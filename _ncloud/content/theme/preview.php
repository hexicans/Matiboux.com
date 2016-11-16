<?php
if(empty($_Oli->getUrlParam(2))
OR !$_Upload->isExistFile(array('file_key' => $_Oli->getUrlParam(2)))) {
	header('Location: ' . $_Oli->getOption('url'));
}
?>

<!DOCTYPE html>
<html>
<head>

<?php include 'head.php'; ?>
<title>Visualisation - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include 'header.php'; ?>

<div class="header">
	<div class="container">
		<h1><i class="fa fa-eye fa-fw"></i> Visualisation</h1>
		<p>
			Visualisation d'un fichier mis en ligne
		</p>
	</div>
</div>

<div class="main">
	<div class="container">
		<?php $fileInfos = $_Upload->getFileLines(array('file_key' => $_Oli->getUrlParam(2))); ?>
		<?php $fileType = $_Upload->getFileType(array('file_key' => $_Oli->getUrlParam(2))); ?>
		
		<h2>
			<?php if($fileInfos['content_visibility'] != 'public') { ?>
				<i class="fa fa-lock fa-fw"></i>
			<?php } ?>
			<?php echo $fileInfos['name']; ?>
		</h2>
		
		<p>
			<?php if($fileType == 'text') { ?>
				<span class="text-primary"><i class="fa fa-file-text-o fa-fw"></i> Texte</span> mis en ligne
			<?php } else if($fileType == 'image') { ?>
				<span class="text-primary"><i class="fa fa-file-photo-o fa-fw"></i> Image</span> mise en ligne
			<?php } else if($fileType == 'music') { ?>
				<span class="text-primary"><i class="fa fa-file-audio-o fa-fw"></i> Musique</span> mise en ligne
			<?php } else if($fileType == 'video') { ?>
				<span class="text-primary"><i class="fa fa-file-video-o fa-fw"></i> Vidéo</span> mise en ligne
			<?php } else if($fileType == 'webpage') { ?>
				<span class="text-primary"><i class="fa fa-file-code-o fa-fw"></i> Page Web</span> mise en ligne
			<?php } else if($fileType == 'opendocument') { ?>
				<span class="text-primary"><i class="fa fa-file-o fa-fw"></i> Fichier OpenDocument</span> mise en ligne
			<?php } else { ?>
				<span class="text-primary"><i class="fa fa-file-o fa-fw"></i> Fichier</span> mis en ligne
			<?php } ?>
			<time datetime="<?php echo $fileInfos['date'] . ' ' . $fileInfos['time']; ?> ">
				le <span class="label label-default"><?php echo date('d/m/Y', strtotime($fileInfos['date'])); ?></span>
				à <span class="label label-default"><?php echo date('H:i', strtotime($fileInfos['time'])); ?></span>
			</time>
			<?php if($fileInfos['nominative'] AND !empty($fileInfos['owner'])) { ?>
				par <span class="label label-primary"><?php echo $fileInfos['owner']; ?></span>
			<?php } ?>
			<?php if($_Oli->getAuthKeyOwner() == $fileInfos['owner']) { ?>
				-
				<a href="<?php echo $_Oli->getOption('url'); ?>my-files/edit/<?php echo $fileInfos['id']; ?>/from-preview" class="btn btn-primary btn-xs">
					Edit <i class="fa fa-pencil fa-fw"></i>
				</a>
				<a href="<?php echo $_Oli->getOption('url'); ?>my-files/delete/<?php echo $fileInfos['id']; ?>" class="btn btn-danger btn-xs">
					Delete <i class="fa fa-trash fa-fw"></i>
				</a>
			<?php } ?>
		</p>
		
		<?php if($fileInfos['content_visibility'] == 'public' OR $fileInfos['owner'] == $_Oli->getAuthKeyOwner()) { ?>
			<?php $_Upload->updateFileInfos(array('views_counter' => $_Upload->getFileInfos('views_counter', array('file_key' => $_Oli->getUrlParam(2))) + 1), array('file_key' => $_Oli->getUrlParam(2))); ?>
			<?php $fileInfos['views_counter'] += 1; ?>
			
			<p>
				<?php if($fileInfos['views_counter'] > 0) { ?>
					Ce fichier comptabilise maintenant <span class="text-primary"><?php echo $fileInfos['views_counter']; ?> vue<?php if($fileInfos['views_counter'] > 1) { ?>s<?php } ?></span>
				<?php } else { ?>
					Ce fichier n'a comptabilisé <span class="text-danger">aucune vue</span> (c'est pas normal : vous l'avez vu)
				<?php } ?> <br />
				<?php if($fileInfos['downloadable_content']) { ?>
					<?php if($fileInfos['downloads_counter'] > 0) { ?>
						Ce fichier comptabilise maintenant <span class="text-primary"><?php echo $fileInfos['downloads_counter']; ?> téléchargement<?php if($fileInfos['downloads_counter'] > 1) { ?>s<?php } ?></span>
					<?php } else { ?>
						Ce fichier n'a comptabilisé <span class="text-danger">aucun téléchargement</span>
					<?php } ?>
				<?php } ?>
			</p>
			
			<?php if(!empty($fileInfos['description'])) { ?>
				<blockquote>
					<?php echo $fileInfos['description']; ?>
					<footer>Description de ce fichier</footer>
				</blockquote>
			<?php } ?>
			<hr />
			
			<?php if($fileInfos['content_visibility'] != 'public') { ?>
				<p>
					<span class="text-danger"><i class="fa fa-lock fa-fw"></i> Ce fichier est noté comme privé</span> <br />
					Malgré cela, ce fichier vous appartenant, vous conservez le droit d'y accéder.
				</p>
				<hr />
			<?php } ?>
			
			<?php $isContentBlock = false; ?>
			<?php if($fileInfos['sensitive_content'] AND (($_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('natrox_cloud_preferences', 'inform_sensitive_content', array('username' => $_Oli->getAuthKeyOwner()))) OR !$_Oli->isExistInfosMySQL('natrox_cloud_preferences', array('username' => $_Oli->getAuthKeyOwner())))) { ?>
				<?php $isContentBlock = true; ?>
				<p class="sensitive-content">
					<span class="text-danger">
						<i class="fa fa-eye-slash fa-fw"></i> Ce fichier a été marqué comme pouvant être choquant
					</span> <br />
					<?php if($fileInfos['content_preview']) { ?>
						<a href="#" class="btn btn-primary btn-sm" id="showSensitiveContent">
							<i class="fa fa-eye fa-fw"></i> Laissez-moi voir ce fichier
						</a>
					<?php } else { ?>
						Aucune prévisualisation de ce fichier n'est disponible, mais restez attentif.
					<?php } ?>
				</p>
			<?php } ?>
			
			<?php if($fileInfos['content_preview']) { ?>
				<?php $isContentBlock = true; ?>
				<?php if($fileType == 'text') { ?>
					<div class="preview">
						<pre>
							<?php readfile($_Upload->getUploadsPath() . $fileInfos['file_name']); ?>
						</pre>
					</div>
				<?php } else if($fileType == 'image') { ?>
					<div class="preview">
						<img src="<?php echo $_Upload->getUploadsUrl() . $fileInfos['file_name']; ?>" />
					</div>
				<?php } else if($fileType == 'webpage') { ?>
					<div class="preview">
						<iframe src="<?php echo $_Upload->getUploadsUrl() . $fileInfos['file_name']; ?>"></iframe>
					</div>
				<?php } else { ?>
					<div class="preview">
						Aucune prévisualisation de ce fichier n'est disponible.
					</div>
				<?php } ?>
			<?php } ?>
			<?php if($isContentBlock) { ?><hr /><?php } ?>
			
			<p>
				<?php if($fileInfos['downloadable_content'] OR $fileInfos['owner'] == $_Oli->getAuthKeyOwner()) { ?>
					Téléchargement : <br />
					<a href="<?php echo $_Oli->getOption('url'); ?>download/<?php echo $fileInfos['file_key']; ?>" class="btn btn-primary">
						<i class="fa fa-cloud-download fa-fw"></i> Télécharger le fichier
					</a>
					
					<?php if(!$fileInfos['downloadable_content']) { ?>
						<span class="text-danger"><i class="fa fa-lock fa-fw"></i> Le téléchargement n'est pas autorisé</span> <br />
						Malgré cela, ce fichier vous appartenant, vous bénéficiez tout de même de ce droit.
					<?php } ?>
				<?php } else { ?>
					<i class="fa fa-lock fa-fw"></i> Le téléchargement de ce fichier n'est pas autorisé
				<?php } ?>
			</p>
			
			<?php if($fileInfos['content_visibility'] == 'public') { ?>
				<hr />
				<div class="share">
					Partager : <br />
					<a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($fileInfos['name']); ?>&original_referer=<?php echo urlencode($_Oli->getOption('url')); ?>&url=<?php echo urlencode($_Oli->getFullUrl()); ?>&hashtags=ImgShot" class="btn btn-primary btn-sm" onclick="window.open(this.href, 'share_<?php $fileKey; ?>', 'left=50, top=50, width=600, height=400'); return false;"><i class="fa fa-twitter fa-fw"></i> Tweet</a>
					<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($_Oli->getFullUrl()); ?>" class="btn btn-primary btn-sm" onclick="window.open(this.href, 'share_<?php $fileKey; ?>', 'left=50, top=50, width=600, height=400'); return false;"><i class="fa fa-facebook fa-fw"></i> Partager sur Facebook</a>
					<a href="<?php echo $_Oli->getShortcutLink('social'); ?>share/<?php echo urlencode($fileInfos['name'] . ' ' . $_Oli->getFullUrl() . ' #ImgShot'); ?>" class="btn btn-default btn-sm disabled" onclick="window.open(this.href, 'share_<?php $fileKey; ?>', 'left=50, top=50, width=600, height=300'); return false;"><i class="fa fa-share-alt fa-fw"></i> Partager sur Matiboux Social</a>
				</div>
			<?php } ?>
		<?php } else { ?>
			<hr />
			<p>
				<span class="text-danger"><i class="fa fa-ban fa-fw"></i> L'accès à ce fichier ne vous est pas autorisé</span> <br />
				Si vous ne trouvez pas ça normal, vous pouvez toujours contacter son propriétaire
				<?php if($fileInfos['nominative'] AND !empty($fileInfos['owner'])) { ?>
					: <span class="text-primary">@<?php echo $fileInfos['owner']; ?></span>
				<?php } ?>
			</p>
			
			<?php if(!$_Oli->verifyAuthKey()) { ?>
				<p>
					Notez que vous êtes déconnecté.
					Si vous avez un compte et que vous pensez qu'il soit autorisé à accéder au fichier, <a href="<?php echo $_Oli->getShortcutLink('login'); ?>">connectez-vous</a> avec celui-ci.
				</p>
			<?php } ?>
		<?php } ?>
	</div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>