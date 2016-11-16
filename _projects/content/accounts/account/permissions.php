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
	<i class="fa fa-lock fa-fw"></i> Your permissions
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
		
		<div class="content-box transparent text-center">
			<h3>Your group: <?php echo $userRight = $_Oli->getUserRight(); ?></h3>
		</div>

		<div class="content-box">
			<?php $userPermissions = $_Oli->getRightPermissions($userRight); ?>
			<?php if($userPermissions == '*') { ?>
				<p>You got full permissions!</p>
			<?php } else if(is_array($userPermissions)) { ?>
				<?php
				foreach($userPermissions as $eachPermission) {
					if(substr($eachPermission, 0, 1) == '-') $prohibitedList[] = substr($eachPermission, 1);
					else $authorizedList[] = $eachPermission;
				}
				?>
				
				<?php if(!empty($authorizedList)) { ?>
					<h2><i class="fa fa-check fa-fw"></i> What you can do</h2>
					
					<?php $firstLoop = true; ?>
					<?php foreach($authorizedList as $eachPermission) { ?>
						<?php if(!$firstLoop) { ?> <br />
						<?php } else $firstLoop = false; ?>
						
						<span class="text-primary">
							<i class="fa fa-plus fa-fw"></i> <?php echo $eachPermission; ?>
						</span>
					<?php } ?>
				<?php } ?>
				
				<?php if(!empty($prohibitedList)) { ?>
					<h2><i class="fa fa-times fa-fw"></i> What you cannot do</h2>
					
					<?php $firstLoop = true; ?>
					<?php foreach($prohibitedList as $eachPermission) { ?>
						<?php if(!$firstLoop) { ?> <br />
						<?php } else $firstLoop = false; ?>
						
						<span class="text-danger">
							<i class="fa fa-minus fa-fw"></i> <?php echo $eachPermission; ?>
						</span>
					<?php } ?>
				<?php } ?>
			<?php } else { ?>
				<p class="text-danger">
					An error occured while reading your permissions. <br />
					Please notify an admin about this error.
				</p>
			<?php } ?>
		</div>
	</div>
</div>

<?php include COMMONPATH . 'footer.php'; ?>

</body>
</html>