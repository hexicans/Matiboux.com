<?php
if(!$_Oli->verifyAuthKey() OR $_Oli->getUserRightLevel($_Oli->getAuthKeyOwner()) < $_Oli->translateUserRight('ADMIN')) header('Location: ' . $_Oli->getShortcutLink('login'));

if(!$_Oli->isEmptyPostVars() AND $_Oli->isExistAccountInfos('ACCOUNTS', $_Oli->getUrlParam(2), false)) {
	if($_Oli->isEmptyPostVars('content')) $resultCode = 'CONTENT_EMPTY';
	else {
		$type = in_array($_Oli->getPostVars('type'), ['notification', 'post']) ? $_Oli->getPostVars('type') : '';
		$content = $_Oli->getPostVars('content');
		$priority = in_array($_Oli->getPostVars('priority'), ['medium', 'high']) ? $_Oli->getPostVars('priority') : '';
		
		if($type == 'notification') {
			if($_Oli->insertLineMySQL('social_notifications', array('id' => $_Oli->getLastInfoMySQL('social_notifications', 'id') + 1, 'username' => 'all', 'type' => ($priority == 'high' ? 'major_announce' : 'announce'), 'data' => array('message' => $content), 'creation_date' => date('Y-m-d H:i:s'))))
				$resultCode = 'NOTIFICATION_CREATED';
			else $resultCode = 'CREATION_ERROR';
		}
		else if($type == 'post') {
			if($_Oli->insertLineMySQL('social_posts', array('id' => $_Oli->getLastInfoMySQL('social_posts', 'id') + 1, 'content' => $content, 'owner' => 'all', 'post_date' => date('Y-m-d H:i:s'))))
				$resultCode = 'POST_CREATED';
			else $resultCode = 'CREATION_ERROR';
		}
		else $resultCode = 'UNKNOWN_TYPE';
	}
}
?>

<!DOCTYPE html>
<html>
<head>

<?php include THEMEPATH . 'head.php'; ?>
<title>Create an announce - <?php echo $_Oli->getSetting('name'); ?></title>

</head>
<body>

<?php include THEMEPATH . 'admin/header.php'; ?>

<div class="bigMedia" style="display: none;"><img /></div>
<div class="main">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-offset-2 col-sm-8">
				<?php if($resultCode == 'CONTENT_EMPTY') { ?>
					<div class="message message-danger">
						<p>You can't let the announce content empty!</p>
					</div>
				<?php } else if($resultCode == 'UNKNOWN_TYPE') { ?>
					<div class="message message-danger">
						<p>You have set an unknown announce type</p>
					</div>
				<?php } else if($resultCode == 'NOTIFICATION_CREATED') { ?>
					<div class="message message-success">
						<p>Your notification announcement has been created</p>
					</div>
				<?php } else if($resultCode == 'POST_CREATED') { ?>
					<div class="message message-success">
						<p>Your post announcement has been created</p>
					</div>
				<?php } else if($resultCode == 'CREATION_ERROR') { ?>
					<div class="message message-danger">
						<p>An error occurred, please try again</p>
					</div>
				<?php } ?>
				<div class="message" id="message" style="display: none;"></div>
				
				<h3 class="text-center"><i class="fa fa-plus fa-fw"></i> Create an announce</h3>
				<div class="content-card">
					<form action="<?php echo $_Oli->getUrlParam(0); ?>form.php" class="form form-horizontal" method="post">
						<div class="form-group">
							<label class="col-md-2 control-label">Type</label>
							<div class="col-md-10">
								<select class="form-control" name="type">
									<option value="notification" selected>Notification</option>
									<option value="post">Post</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Content</label>
							<div class="col-md-10">
								<textarea class="form-control" name="content" rows="3"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Priority</label>
							<div class="col-md-10">
								<select class="form-control" name="priority">
									<option value="medium" selected>Medium</option>
									<option value="high">High</option>
								</select>
								<p class="help-block">
									This only apply on notification announcements
								</p>
							</div>
						</div> <hr />
						
						<div class="form-group">
							<div class="col-md-offset-2 col-md-10">
								<button type="submit" class="btn btn-primary">Create</button>
								<button type="reset" class="btn btn-default">Reset</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $_Oli->loadEndHtmlFiles(); ?>

</body>
</html>