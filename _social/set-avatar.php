<?php
/** Load Oli Framework */
if(!defined('ABSPATH')) define('ABSPATH', dirname(__FILE__) . '/');
require_once ABSPATH . 'load.php';

/** ------ */

#  MATIBOUX SOCIAL:
#    Avatar Upload Script

#  Made to work with Oli Beta 1.7.0
#  and the Upload Manager extension

#  $_POST:
#    * ['authKey']
#    ? ['size']
#  $_FILES:
#    ? ['avatar']

if(!isset($_POST['authKey'])) {
	$result['error'] = 'MISSING_AUTHKEY';
	die(json_encode($result, JSON_FORCE_OBJECT));
}
else {
	$previousAvatarInfos = $_Avatar->getFileLines(array('owner' => $_Oli->getAuthKeyOwner($_POST['authKey'])));
	$_Avatar->deleteFile($previousAvatarInfos['path_addon'] . $previousAvatarInfos['file_name']);
	
	$_Avatar->deleteFile($_Oli->getAuthKeyOwner($_POST['authKey']) . '.*');
	$_Avatar->deleteFileInfos(array('owner' => $_Oli->getAuthKeyOwner($_POST['authKey'])));
	$_Avatar->deleteFileInfos(array('id' => $_Oli->getAccountInfos('ACCOUNTS', 'id', $_Oli->getAuthKeyOwner($_POST['authKey']))));
	
	if(isset($_FILES['avatar'])) {
		$fileExtension = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
		if($fileKey = $_Avatar->uploadFile($_FILES['avatar'], $_Oli->getAuthKeyOwner($_POST['authKey']), array('id' => $_Oli->getAccountInfos('ACCOUNTS', 'id', $_Oli->getAuthKeyOwner($_POST['authKey'])), 'name' => 'user_avatar', 'owner' => $_Oli->getAuthKeyOwner($_POST['authKey'])))) {
			$result['error'] = false;
			$result['url'] = $_Avatar->getUploadsUrl() . $fileKey . '.' . $fileExtension;
			die(json_encode($result, JSON_FORCE_OBJECT));
		}
		else {
			$result['error'] = 'UPLOAD_FAILED';
			die(json_encode($result, JSON_FORCE_OBJECT));
		}
	}
	else {
		$result['error'] = false;
		$result['url'] = $_Gravatar->getGravatar($_Oli->getAccountInfos('ACCOUNTS', 'email', array('username' => $_Oli->getAuthKeyOwner($_POST['authKey']))), (isset($_POST['size'])) ? $_POST['size'] : 80);
		die(json_encode($result, JSON_FORCE_OBJECT));
	}
}
?>