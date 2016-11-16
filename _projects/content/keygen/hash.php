<!DOCTYPE html>
<html>
<head>

<?php include COMMONPATH . 'head.php'; ?>
<?php $_Oli->loadLocalScript('js/switchTo.js', false); ?>
<?php $_Oli->loadLocalScript('js/hash.js', false); ?>
<title>Hash - <?php echo $_Oli->getSetting('name'); ?></title>

</head>
<body>

<?php include THEMEPATH . 'header.php'; ?>

<div class="title-banner">
	File hashes generator
</div>

<div class="page-content">
	<div class="container">
		<div id="message" style="display: none;"></div>
		
		<?php $keygenSettings = $_Oli->getLinesMySQL('keygen_settings', array('username' => $_Oli->getAuthKeyOwner())); ?>
		<div class="content-box transparent">
			<p>
				<i class="fa fa-compress fa-fw"></i> Switch between generation modes
			</p>
			<ul class="switchTo">
				<li>
					<span value="hash" <?php if($keygenSettings['default_hash_mode'] == 'hash' OR !$keygenSettings['default_hash_mode']) { ?>class="text-muted" style="font-weight: 700; cursor: pointer;"<?php } else { ?>class="text-primary" style="cursor: pointer;"<?php } ?>>
						<i class="fa fa-lock fa-fw"></i> Generate a hash
					</span>
				</li>
				<li>
					<span value="filehash" <?php if($keygenSettings['default_hash_mode'] == 'filehash') { ?>class="text-muted" style="font-weight: 700; cursor: pointer;"<?php } else { ?>class="text-primary" style="cursor: pointer;"<?php } ?>>
						<i class="fa fa-code fa-fw"></i> Generate a file hash
					</span>
				</li>
			</ul>
		</div>
		
		<div class="content-box filehash">
			<form action="<?php echo $_Oli->getUrlParam(0); ?>_filehash.php" class="form form-horizontal" method="post">
				<div class="form-group">
					<label class="col-md-2 control-label">Hash</label>
					<div class="col-md-10">
						<div class="radio">
							<label><input type="radio" name="hash" value="md5" /> Give me md5</label> <br />
							<label><input type="radio" name="hash" value="sha1" checked /> Give me sha1</label>
						</div>
					</div>
				</div>
				
				<div class="form-group" only="hash" <?php if($keygenSettings['default_hash_mode'] != 'hash' AND $keygenSettings['default_hash_mode']) { ?>style="display: none;"<?php } ?>>
					<label class="col-md-2 control-label">Text</label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="text" />
					</div>
				</div>
				<div class="form-group" only="filehash" <?php if($keygenSettings['default_hash_mode'] != 'filehash') { ?>style="display: none;"<?php } ?>>
					<label class="col-md-2 control-label">Fichier</label>
					<div class="col-md-10">
						<input type="file" class="form-control" name="file" />
						<p class="help-block">
							<span class="text-primary"><i class="fa fa-lock fa-fw"></i> We promise not to keep your files on our servers!</span> <br />
							Once the filehash have been generated, we always delete the file from our server.
							But for safety reasons, we do not recommend you to uploading sensitive or private content here.
						</p>
					</div>
				</div> <hr />
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-10">
						<button type="submit" class="btn btn-primary">Generate your hash</button>
						<button type="reset" class="btn btn-default">Reset</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<?php include COMMONPATH . 'footer.php'; ?>

</body>
</html>