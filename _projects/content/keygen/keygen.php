<!DOCTYPE html>
<html>
<head>

<?php include COMMONPATH . 'head.php'; ?>
<?php $_Oli->loadLocalScript('js/switchTo.js', false); ?>
<?php $_Oli->loadLocalScript('js/keygen.js', false); ?>
<title><?php echo $_Oli->getSetting('name'); ?></title>

</head>
<body>

<?php include THEMEPATH . 'header.php'; ?>

<div class="title-banner">
	Password and keys generator
</div>

<div class="page-content">
	<div class="container">
		<div id="message" style="display: none;"></div>
<h3>KeyGen library live experiments</h3>
<pre>
20: <?php print_r(\Keygen\KeyGenLib::keygen(20)); ?> 
<?php print_r(\Keygen\KeyGenLib::lastParameters()); ?>
<?php //var_dump(\Keygen\KeyGenLib::redundancyForced()); ?>
<?php var_dump(\Keygen\KeyGenLib::getErrorCode()); ?>
</pre> <hr />
		
		<?php $keygenSettings = $_Oli->getLinesMySQL('keygen_settings', array('username' => $_Oli->getAuthKeyOwner())); ?>
		<?php /*<pre><?php print_r($keygenSettings); ?></pre>*/ ?>
		<div class="content-box transparent">
			<p>
				<i class="fa fa-compress fa-fw"></i> Switch between generation modes
			</p>
			<ul class="switchTo">
				<li>
					<span value="keygen" <?php if($keygenSettings['default_keygen_mode'] == 'keygen' OR !$keygenSettings['default_hash_mode']) { ?>class="text-muted" style="font-weight: 700; cursor: pointer;"<?php } else { ?>class="text-primary" style="cursor: pointer;"<?php } ?>>
						<i class="fa fa-lock fa-fw"></i> Generate a keygen
					</span>
				</li>
				<li>
					<span value="activation-key" <?php if($keygenSettings['default_keygen_mode'] == 'activation-key') { ?>class="text-muted" style="font-weight: 700; cursor: pointer;"<?php } else { ?>class="text-primary" style="cursor: pointer;"<?php } ?>>
						<i class="fa fa-code fa-fw"></i> Generate a activation key
					</span>
				</li>
			</ul>
		</div>
		
		<div class="content-box keygen">
			<form action="<?php echo $_Oli->getUrlParam(0); ?>_keygen.php" class="form form-horizontal" method="post">
				<div class="form-group">
					<label class="col-md-2 control-label">Genre</label>
					<div class="col-md-10">
						<div class="checkbox col-sm-5">
							<label><input type="checkbox" name="genreNum" <?php if(@$keygenSettings['keygen_inputs']['genreNum'] OR !isset($keygenSettings['keygen_inputs']['genreNum'])) { ?>checked<?php } ?> /> Numeric (1)</label>
						</div>
						<div class="checkbox col-sm-5">
							<label><input type="checkbox" name="genreMin" <?php if(@$keygenSettings['keygen_inputs']['genreMin'] OR !isset($keygenSettings['keygen_inputs']['genreMin'])) { ?>checked<?php } ?> /> Lowercase (a)</label>
						</div>
						<div class="checkbox col-sm-5">
							<label><input type="checkbox" name="genreMaj" <?php if(@$keygenSettings['keygen_inputs']['genreMaj'] OR !isset($keygenSettings['keygen_inputs']['genreMaj'])) { ?>checked<?php } ?> /> Uppercase (A)</label>
						</div>
						<div class="checkbox col-sm-5" only="keygen" <?php if($keygenSettings['default_keygen_mode'] != 'keygen' AND $keygenSettings['default_hash_mode']) { ?>style="display: none;"<?php } ?>>
							<label><input type="checkbox" name="genreSpe" <?php if(@$keygenSettings['keygen_inputs']['genreSpe']) { ?>checked<?php } ?> <?php if($keygenSettings['default_keygen_mode'] != 'keygen' AND $keygenSettings['default_hash_mode']) { ?>disabled<?php } ?> /> Spéciaux (@)</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">Longueur</label>
					<div class="col-md-10">
						<div class="input-group">
							<input type="number" class="form-control" name="length" value="<?php echo @$keygenSettings['keygen_inputs']['length'] ?: 12; ?>" />
							<div class="input-group-addon">caractères</div>
						</div>
					</div>
				</div>
				<div class="form-group" only="activation-key" <?php if($keygenSettings['default_keygen_mode'] != 'activation-key') { ?>style="display: none;"<?php } ?>>
					<label class="col-md-2 control-label">Nb de blocs</label>
					<div class="col-md-10">
						<div class="input-group">
							<input type="number" class="form-control" name="blocks" value="<?php echo @$keygenSettings['keygen_inputs']['blocks'] ?: 4; ?>" <?php if($keygenSettings['default_keygen_mode'] != 'activation-key') { ?>disabled<?php } ?> />
							<div class="input-group-addon">groupes</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">Extra</label>
					<div class="col-md-10">
						<div class="checkbox col-sm-5">
							<label><input type="checkbox" name="redundancy" <?php if(@$keygenSettings['keygen_inputs']['redundancy'] OR !isset($keygenSettings['keygen_inputs']['redundancy'])) { ?>checked<?php } ?> /> Redondance des caractères</label>
						</div>
						<div class="checkbox col-sm-5">
							<label><input type="checkbox" name="hashes" <?php if(@$keygenSettings['keygen_inputs']['hashes'] OR !isset($keygenSettings['keygen_inputs']['hashes'])) { ?>checked<?php } ?> /> Afficher les hashs</label>
						</div>
					</div>
				</div> <hr />
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-10">
						<button type="submit" class="btn btn-primary">Generate a key</button>
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