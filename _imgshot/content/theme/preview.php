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
			Visualisation d'une image mise en ligne
		</p>
	</div>
</div>

<div class="main">
	<div class="container">
		<?php $fileInfos = $_Upload->getFileLines(array('file_key' => $_Oli->getUrlParam(2))); ?>
		<?php $fileType = $_Upload->getFileType(array('file_key' => $_Oli->getUrlParam(2))); ?>
		
		<h2><?php echo $fileInfos['name']; ?></h2>
		<p>
			<span class="text-primary"><i class="fa fa-file-photo-o fa-fw"></i> Image</span> mise en ligne
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
		
		<?php if(!empty($fileInfos['description'])) { ?>
			<blockquote>
				<?php echo nl2br($fileInfos['description']); ?>
				<footer>Description de cette image</footer>
			</blockquote>
		<?php } ?>
		<hr />
		
		<?php if($fileInfos['sensitive_content'] AND (($_Oli->isExistInfosMySQL('imgshot_preferences', array('username' => $_Oli->getAuthKeyOwner())) AND $_Oli->getInfosMySQL('imgshot_preferences', 'inform_sensitive_content', array('username' => $_Oli->getAuthKeyOwner()))) OR !$_Oli->isExistInfosMySQL('imgshot_preferences', array('username' => $_Oli->getAuthKeyOwner())))) { ?>
			<p class="sensitive-content">
				<span class="text-danger">
					<i class="fa fa-eye-slash fa-fw"></i> Cette image a été marquée comme pouvant être choquante
				</span> <br />
				<a href="#" class="btn btn-primary btn-sm" id="showSensitiveContent">
					<i class="fa fa-eye fa-fw"></i> Laissez-moi voir cette image
				</a>
			</p>
		<?php } ?>
		
		<div class="preview">
			<img src="<?php echo $_Upload->getUploadsUrl() . $_Upload->getFileInfos('file_name', array('file_key' => $_Oli->getUrlParam(2))); ?>" />
		</div>
		<hr />
		
		<div class="share">
			<a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($_Upload->getFileInfos('name', array('file_key' => $_Oli->getUrlParam(2)))); ?>&original_referer=<?php echo urlencode($_Oli->getOption('url')); ?>&url=<?php echo urlencode($_Oli->getFullUrl()); ?>&hashtags=ImgShot" class="btn btn-primary btn-sm" onclick="window.open(this.href, 'share_<?php $_Oli->getUrlParam(2); ?>', 'left=50, top=50, width=600, height=400'); return false;"><i class="fa fa-twitter fa-fw"></i> Tweet</a>
			<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($_Oli->getFullUrl()); ?>" class="btn btn-primary btn-sm" onclick="window.open(this.href, 'share_<?php $_Oli->getUrlParam(2); ?>', 'left=50, top=50, width=600, height=400'); return false;"><i class="fa fa-facebook fa-fw"></i> Partager sur Facebook</a>
			<a href="<?php echo $_Oli->getShortcutLink('social'); ?>share/<?php echo urlencode($_Upload->getFileInfos('name', array('file_key' => $_Oli->getUrlParam(2))) . ' ' . $_Oli->getFullUrl() . ' #ImgShot'); ?>" class="btn btn-default btn-sm disabled" onclick="window.open(this.href, 'share_<?php $_Oli->getUrlParam(2); ?>', 'left=50, top=50, width=600, height=300'); return false;"><i class="fa fa-share-alt fa-fw"></i> Partager sur Matiboux Social</a>
		</div>
	</div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>