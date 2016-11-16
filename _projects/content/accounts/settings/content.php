<?php
if($_Oli->getUserRightLevel() < $_Oli->translateUserRight('USER')) header('Location: ' . $_Oli->getShortcutLink('login'));

// if(!$_Oli->isEmptyPostVars()) {
	// $language = (!$_Oli->isEmptyPostVars('language') AND in_array($_Oli->getPostVars('language'), ['en'])) ? $_Oli->getPostVars('language') : $_Oli->getDefaultLanguage();
	
	// $gender = $_Oli->getPostVars('gender');
	// $pseudonym = $_Oli->getPostVars('pseudonym');
	// $nickname = $_Oli->getPostVars('nickname');
	// $displayedName = $_Oli->getPostVars('displayedName') ?: 'username';
	// $addPseudonym = $_Oli->getPostVars('addPseudonym') ? true : false;
	// $biography = $_Oli->getPostVars('biography');
	
	// $firstname = $_Oli->getAccountInfos('INFOS', 'firstname', array('username' => $_Oli->getAuthKeyOwner()));
	// $lastname = $_Oli->getAccountInfos('INFOS', 'lastname', array('username' => $_Oli->getAuthKeyOwner()));
	// $birthday = $_Oli->getAccountInfos('ACCOUNTS', 'birthday', array('username' => $_Oli->getAuthKeyOwner()));
	
	// if(empty($firstname)) $firstname = $_Oli->getPostVars('firstname');
	// if(empty($lastname)) $lastname = $_Oli->getPostVars('lastname');
	// if(empty($birthday)) $birthday = $_Oli->getPostVars('birthday') ?: null;
	
	// if($_Oli->setUserLanguage($language) AND $_Oli->updateAccountInfos('ACCOUNTS', array('birthday' => $birthday)) AND $_Oli->updateAccountInfos('INFOS', array('pseudonym' => $pseudonym, 'nickname' => $nickname, 'firstname' => $firstname, 'lastname' => $lastname, 'displayed_name' => $displayedName, 'add_pseudonym' => $addPseudonym, 'gender' => $gender, 'biography' => $biography)))
		// $resultCode = 'S:Your informations have been successfully updated';
	// else $resultCode = 'D:An error occured, please try again later';
// }
?>

<!DOCTYPE html>
<html>
<head>

<?php include COMMONPATH . 'head.php'; ?>
<title>Your account - <?php echo $_Oli->getOption('name'); ?></title>

</head>
<body>

<?php include THEMEPATH . 'header.php'; ?>

<div class="title-banner">
	<i class="fa fa-picture-o fa-fw"></i> Your content settings
</div>

<div class="page-content">
	<div class="container">
		<?php if(isset($resultCode)) { ?>
			<?php
			list($prefix, $message) = explode(':', $resultCode, 2);
			if($prefix == 'P') $type = 'message-primary';
			else if($prefix == 'S') $type = 'message-success';
			else if($prefix == 'I') $type = 'message-info';
			else if($prefix == 'W') $type = 'message-warning';
			else if($prefix == 'D') $type = 'message-danger';
			?>
			
			<div class="message <?php echo $type; ?>">
				<?php echo $message; ?>
			</div>
		<?php } ?>
		
		<div class="content-box">
			<form action="<?php echo $_Oli->getUrlParam(0); ?>form.php" class="form form-horizontal" method="post">
				<?php //$accountsInfos = $_Oli->getAccountInfos('INFOS', ['rating'], $_Oli->getAuthKeyOwner()); ?>
				
				<h2><i class="fa fa-lock fa-fw"></i> Content ratings</h2>
				<div class="form-group">
					<label class="col-md-2 control-label hidden-xs hidden-sm">General</label>
					<div class="col-md-10">
						<div class="radio">
							<label><input type="radio" name="rating" value="general" <?php if($accountsInfos['rating'] == 'general') { ?>checked<?php } ?> /> General rated content</label> <br />
						</div>
						<p class="help-block">
							<span class="text-success">Contains no violence and no nudity #SFW</span>
						</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label hidden-xs hidden-sm">Mature</label>
					<div class="col-md-10">
						<div class="radio">
							<label><input type="radio" name="rating" value="mature" <?php if($accountsInfos['rating'] == 'mature') { ?>checked<?php } ?> /> Mature rated content</label> <br />
						</div>
						<p class="help-block">
							<span class="text-danger">Contains mild violence or nudity</span> <br />
							Nonsexual nudity exposing breasts or genitals (should not show arousal) <br />
							Mild violence
						</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label hidden-xs hidden-sm">Adult</label>
					<div class="col-md-10">
						<div class="radio">
							<label><input type="radio" name="rating" value="adult" <?php if($accountsInfos['rating'] == 'adult') { ?>checked<?php } ?> /> Adult rated content</label> <br />
						</div>
						<p class="help-block">
							<span class="text-danger">Contains sex or strong violence</span> <br />
							Erotic imagery, sexual activity or arousal <br />
							Strong violence, blood, serious injury or death
						</p>
					</div>
				</div> <hr />
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-10">
						<button type="submit" class="btn btn-primary"><i class="fa fa-cloud-upload fa-fw"></i> Update informations</button>
						<button type="reset" class="btn btn-default"><i class="fa fa-times fa-fw"></i> Reset</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<?php include COMMONPATH . 'footer.php'; ?>

</body>
</html>