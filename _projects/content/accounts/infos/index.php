<?php
if($_Oli->getUserRightLevel() < $_Oli->translateUserRight('USER')) header('Location: ' . $_Oli->getShortcutLink('login'));

if(!$_Oli->isEmptyPostVars()) {
	$language = (!$_Oli->isEmptyPostVars('language') AND in_array($_Oli->getPostVars('language'), ['en'])) ? $_Oli->getPostVars('language') : $_Oli->getDefaultLanguage();
	
	$gender = $_Oli->getPostVars('gender');
	$pseudonym = $_Oli->getPostVars('pseudonym');
	$nickname = $_Oli->getPostVars('nickname');
	$displayedName = $_Oli->getPostVars('displayedName') ?: 'username';
	$addPseudonym = $_Oli->getPostVars('addPseudonym') ? true : false;
	$biography = $_Oli->getPostVars('biography');
	
	$firstname = $_Oli->getAccountInfos('INFOS', 'firstname', array('username' => $_Oli->getAuthKeyOwner()));
	$lastname = $_Oli->getAccountInfos('INFOS', 'lastname', array('username' => $_Oli->getAuthKeyOwner()));
	$birthday = $_Oli->getAccountInfos('ACCOUNTS', 'birthday', array('username' => $_Oli->getAuthKeyOwner()));
	
	if(empty($firstname)) $firstname = $_Oli->getPostVars('firstname');
	if(empty($lastname)) $lastname = $_Oli->getPostVars('lastname');
	if(empty($birthday)) $birthday = $_Oli->getPostVars('birthday') ?: null;
	
	if($_Oli->setUserLanguage($language) AND $_Oli->updateAccountInfos('ACCOUNTS', array('birthday' => $birthday)) AND $_Oli->updateAccountInfos('INFOS', array('pseudonym' => $pseudonym, 'nickname' => $nickname, 'firstname' => $firstname, 'lastname' => $lastname, 'displayed_name' => $displayedName, 'add_pseudonym' => $addPseudonym, 'gender' => $gender, 'biography' => $biography)))
		$resultCode = 'S:Your informations have been successfully updated';
	else $resultCode = 'D:An error occured, please try again later';
}
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
	<i class="fa fa-pencil fa-fw"></i> Your personnal informations
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
				<?php $accountsInfos = array_merge($_Oli->getAccountInfos('ACCOUNTS', ['birthday'], $_Oli->getAuthKeyOwner()), $_Oli->getAccountInfos('INFOS', ['gender', 'pseudonym', 'nickname', 'firstname', 'lastname', 'displayed_name', 'add_pseudonym', 'biography'], $_Oli->getAuthKeyOwner())); ?>
				
				<h2><i class="fa fa-picture-o fa-fw"></i> User interface</h2>
				<div class="form-group">
					<label class="col-md-2 control-label">Language</label>
					<div class="col-md-10">
						<select class="form-control" name="language">
							<?php $language = $_Oli->getUserLanguage(); ?>
							<option value="en" <?php if($language == 'en' OR empty($language)) { ?>selected<?php } ?>>
								English (EN)
							</option>
							<option disabled<?php /*value="fr" <?php if($language == 'fr') { ?>selected<?php } ?>*/ ?>>
								Français (FR)
							</option>
							<option disabled<?php /*value="de" <?php if($language == 'de') { ?>selected<?php } ?>*/ ?>>
								Deutsch (DE)
							</option>
							<option disabled<?php /*value="es" <?php if($language == 'es') { ?>selected<?php } ?>*/ ?>>
								Español (ES)
							</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">Theme</label>
					<div class="col-md-10">
						<select class="form-control" name="theme" disabled>
							<option value="light" selected>
								Light theme
							</option>
							<option value="dark">
								Dark theme
							</option>
						</select>
					</div>
				</div> <hr />
				
				<h2><i class="fa fa-user fa-fw"></i> Your identity</h2>
				<div class="form-group">
					<label class="col-md-2 control-label">Gender</label>
					<div class="col-md-10">
						<div class="radio">
							<label><input type="radio" name="gender" value="male" <?php if($accountsInfos['gender'] == 'male') { ?>checked<?php } ?> /> <i class="fa fa-mars fa-fw"></i> Male</label> <br />
							<label><input type="radio" name="gender" value="female" <?php if($accountsInfos['gender'] == 'female') { ?>checked<?php } ?> /> <i class="fa fa-venus fa-fw"></i> Female</label>
						</div>
					</div>
				</div> <hr />
				
				<div class="form-group">
					<label class="col-md-2 control-label">Pseudonym</label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="pseudonym" value="<?php echo $accountsInfos['pseudonym']; ?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">Nickname</label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="nickname" value="<?php echo $accountsInfos['nickname']; ?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">First & Last name</label> <div class="clearfix visible-sm-block"></div>
					<div class="col-md-5 col-sm-6">
						<input type="text" class="form-control" name="firstname" value="<?php echo $accountsInfos['firstname']; ?>" <?php if(!empty($accountsInfos['firstname'])) { ?>disabled<?php } ?> />
					</div>
					<div class="col-md-5 col-sm-6">
						<input type="text" class="form-control" name="lastname" value="<?php echo $accountsInfos['lastname']; ?>" <?php if(!empty($accountsInfos['lastname'])) { ?>disabled<?php } ?> />
					</div> <div class="clearfix visible-sm-block"></div>
					<div class="col-md-offset-2 col-md-10">
						<p class="help-block">
							<?php if(empty($accountsInfos['firstname']) OR empty($accountsInfos['lastname'])) { ?>
								<i class="fa fa-warning fa-fw"></i> Be careful while typing your name: after sumbiting it, you won't be able to change it again by yourself <br />
								<i>Please also note that your name can be required to grant you access to specific services</i>
							<?php } else { ?>
								<i class="fa fa-warning fa-fw"></i> You cannot change these informations yourself, but you can ask an admin to change them for you.
							<?php } ?>
						</p>
					</div>
				</div> <hr />
				
				<div class="form-group">
					<label class="col-md-2 control-label"><i class="fa fa-gear fa-fw"></i> Displayed name</label>
					<div class="col-md-10">
						<select class="form-control" name="displayedName">
							<option value="username" <?php if($accountsInfos['displayed_name'] == 'username') { ?>selected<?php } ?>>
								<?php echo $_Oli->getAuthKeyOwner(); ?> - Username
							</option>
							<option value="pseudonym" <?php if($accountsInfos['displayed_name'] == 'pseudonym') { ?>selected<?php } ?>>
								<?php if(!empty($accountsInfos['pseudonym'])) { ?>
									<?php echo $accountsInfos['pseudonym']; ?> -
								<?php } ?> Pseudonym
							</option>
							<option value="nickname" <?php if($accountsInfos['displayed_name'] == 'nickname') { ?>selected<?php } ?>>
								<?php if(!empty($accountsInfos['nickname'])) { ?>
									<?php echo $accountsInfos['nickname']; ?> -
								<?php } ?> Nickname
							</option>
							<option value="firstname" <?php if($accountsInfos['displayed_name'] == 'firstname') { ?>selected<?php } ?>>
								<?php if(!empty($accountsInfos['firstname'])) { ?>
									<?php echo $accountsInfos['firstname']; ?> -
								<?php } ?> First name
							</option>
							<option value="initials" <?php if($accountsInfos['displayed_name'] == 'short_fullname') { ?>selected<?php } ?>>
								<?php if(!empty($accountsInfos['firstname']) AND !empty($accountsInfos['lastname'])) { ?>
									<?php echo substr($accountsInfos['firstname'], 0, 1); ?>.<?php echo substr($accountsInfos['lastname'], 0, 1); ?>. -
								<?php } ?> Name initials
							</option>
							<option value="short_fullname" <?php if($accountsInfos['displayed_name'] == 'short_fullname') { ?>selected<?php } ?>>
								<?php if(!empty($accountsInfos['firstname']) AND !empty($accountsInfos['lastname'])) { ?>
									<?php echo $accountsInfos['firstname']; ?> <?php echo substr($accountsInfos['lastname'], 0, 1); ?>. -
								<?php } ?> First name and initial
							</option>
							<option value="fullname" <?php if($accountsInfos['displayed_name'] == 'fullname') { ?>selected<?php } ?>>
								<?php if(!empty($accountsInfos['firstname']) AND !empty($accountsInfos['lastname'])) { ?>
									<?php echo $accountsInfos['firstname']; ?> <?php echo $accountsInfos['lastname']; ?> -
								<?php } ?> Full name
							</option>
						</select>
						<p class="help-block">
							The names (showed as examples) are the ones currently saved in our database
						</p>
					</div>
					<div class="col-md-10 col-md-offset-2">
						<div class="checkbox">
							<label><input type="checkbox" name="addPseudonym" <?php if($accountsInfos['add_pseudonym']) { ?>checked<?php } ?> /> Add my pseudonym to my displayed name</label>
						</div>
						<p class="help-block">
							Shows your pseudonym along with your displayed name
						</p>
					</div>
				</div> <hr />
				
				<div class="form-group">
					<label class="col-md-2 control-label">Biography</label>
					<div class="col-md-10">
						<textarea class="form-control" name="biography" rows="3"><?php echo $accountsInfos['biography']; ?></textarea>
						<p class="help-block">
							Tell us about yourself, who are you and what do you do?
							<?php if(strpos($accountsInfos['biography'], 'Eli') !== false OR strpos($accountsInfos['biography'], 'Eliott') !== false) { ?> <br />
								<i class="small">You mentioned the fur's only one name...</i>
							<?php } else if(strpos($accountsInfos['biography'], 'the only one') !== false OR strpos($accountsInfos['biography'], 'my only one') !== false) { ?> <br />
								<i class="small">His "only one", this is how the fur like to call his babe</i>
							<?php } else if(strpos($accountsInfos['biography'], 'I love him') !== false) { ?> <br />
								<i class="small">The fur is loving his babe very much too...</i>
							<?php } ?>
						</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">Birthday</label>
					<div class="col-md-10">
						<input type="date" class="form-control" name="birthday" value="<?php echo $accountsInfos['birthday']; ?>" <?php if(!empty($accountsInfos['birthday'])) { ?>disabled<?php } ?> />
						<p class="help-block">
							<?php if(empty($accountsInfos['birthday'])) { ?>
								<i class="fa fa-warning fa-fw"></i> Be careful while typing your name: after sumbiting it, you won't be able to change it again by yourself <br />
								<i>Please also note that your name can be required to grant you access to specific services</i>
							<?php } else { ?>
								<i class="fa fa-warning fa-fw"></i> You cannot change this information yourself, but you can ask an admin to change them for you.
								<?php if(date('d/m', strtotime($accountsInfos['birthday'])) == '19/07') { ?> <br />
									<i class="small">July 19th is a very special date for the fur</i>
								<?php } ?>
							<?php } ?>
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