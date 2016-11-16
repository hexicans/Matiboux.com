<?php
/** Load Oli Framework */
if(!defined('ABSPATH')) define('ABSPATH', dirname(__FILE__) . '/');
require_once ABSPATH . 'load.php';

/** ------ */

#  MATIBOUX SOCIAL:
#    Post Saving Script

#  Made to work with Oli Beta 1.7.0
#  and the Upload Manager extension

#  $_POST:
#    * ['authKey']
#    * ['content']
#    ? ['replyTo']
#  $_FILES:
#    ? ['media']


if(!isset($_POST['authKey'])) {
	$result['error'] = true;
	$result['errorCode'] = 'MISSING_AUTHKEY';
	$result['errorMessage'] = 'Your authentification key haven\'t been sent to us, it may be an error';
	die(json_encode($result, JSON_FORCE_OBJECT));
}
else if(!$_Oli->verifyAuthKey($_POST['authKey'])) {
	$result['error'] = true;
	$result['errorCode'] = 'NOT_CONNECTED';
	$result['errorMessage'] = 'You are not connected to the service';
	die(json_encode($result, JSON_FORCE_OBJECT));
}
else if(!isset($_POST['content'])) {
	$result['error'] = true;
	$result['errorCode'] = 'MISSING_CONTENT';
	$result['errorMessage'] = 'No post content have been sent to us, it may be an error';
	die(json_encode($result, JSON_FORCE_OBJECT));
}
else if(empty($_POST['content'])) {
	$result['error'] = true;
	$result['errorCode'] = 'EMPTY_CONTENT';
	$result['errorMessage'] = 'You must write something before sending your post';
	die(json_encode($result, JSON_FORCE_OBJECT));
}
else if(strlen($_POST['content']) > 512) {
	$result['error'] = true;
	$result['errorCode'] = 'CONTENT_TOO_LONG';
	$result['errorMessage'] = 'Your post cannot be longer than 1024 characters';
	die(json_encode($result, JSON_FORCE_OBJECT));
}
else {
	if(isset($_FILES['media'])) {
		$fileExtension = strtolower(substr(strrchr($_FILES['media']['name'], '.'), 1));
		$fileSize = $_FILES['media']['size'];
		$fileHash = sha1_file($_FILES['media']['tmp_name']);
		
		if($_Media->isExistFileInfos(array('file_size' => $fileSize, 'file_hash' => $fileHash))) $mediaKey = $_Media->getFileInfos('file_key', array('file_size' => $fileSize, 'file_hash' => $fileHash));
		else $mediaKey = $_Media->uploadFile($_FILES['media'], $_Oli->getAuthKeyOwner($_POST['authKey']) . '/', array('owner' => $_Oli->getAuthKeyOwner($_POST['authKey'])));
	}
	
	$postId = $_Oli->getLastInfoMySQL('social_posts', 'id') + 1;
	$postDate = date('Y-m-d H:i:s');
	
	preg_match_all('/@(\w+)/', $_POST['content'], $output);
	$mentions = is_array($output[1]) ? $output[1] : [$output[1]];
	
	$replyTo = !empty($_POST['replyTo']) ? $_POST['replyTo'] : '';
	$replyToUser = $_Oli->getInfosMySQL('social_posts', 'owner', array('id' => $replyTo), false);
	if((!in_array($replyToUser, $mentions) AND $replyToUser != $_Oli->getAuthKeyOwner($_POST['authKey'])) OR $replyToUser == 'all') {
		$replyTo = '';
		$replyToUser = '';
	}
	
	if($_Oli->insertLineMySQL('social_posts', array('id' => $postId, 'content' => $_POST['content'], 'reply_to' => $replyTo, 'media_key' => !empty($mediaKey) ? $mediaKey : '', 'owner' => $_Oli->getAuthKeyOwner($_POST['authKey']), 'user_ip' => $_Oli->getUserIP(), 'post_date' => $postDate))) {
		if(!empty($mentions)) {
			foreach($mentions as $eachMention) {
				if($_Oli->getAccountInfos('ACCOUNTS', 'username', $eachMention, false) != $_Oli->getAuthKeyOwner($_POST['authKey']) AND $_Oli->getAccountInfos('ACCOUNTS', 'username', $eachMention, false) != $replyToUser)
					$_Oli->insertLineMySQL('social_notifications', array('id' => $_Oli->getLastInfoMySQL('social_notifications', 'id') + 1, 'username' => $_Oli->getAccountInfos('ACCOUNTS', 'username', $eachMention, false), 'type' => 'mention', 'data' => array('postId' => $postId, 'owner' => $_Oli->getAuthKeyOwner($_POST['authKey'])), 'creation_date' => $postDate));
			}
		}
		
		if(!empty($replyToUser) AND $replyToUser != $_Oli->getAuthKeyOwner($_POST['authKey'])) $_Oli->insertLineMySQL('social_notifications', array('id' => $_Oli->getLastInfoMySQL('social_notifications', 'id') + 1, 'username' => $_Oli->getInfosMySQL('social_posts', 'owner', array('id' => $replyTo), false), 'type' => 'reply', 'data' => array('postId' => $postId, 'owner' => $_Oli->getAuthKeyOwner($_POST['authKey'])), 'creation_date' => $postDate));
		
		$result['error'] = false;
		$result['postId'] = $postId;
		if(!empty($mediaKey)) $result['mediaUrl'] = $_Media->getUploadsUrl() . $mediaKey . '.' . $fileExtension;
		die(json_encode($result, JSON_FORCE_OBJECT));
	}
	else {
		$result['error'] = true;
		$result['errorCode'] = 'POST_ERROR';
		$result['errorMessage'] = 'An error occured while saving your post, please try again';
		die(json_encode($result, JSON_FORCE_OBJECT));
	}
}
?>