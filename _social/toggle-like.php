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
#    * ['postId']

if(!isset($_POST['authKey'])) {
	$result['error'] = true;
	$result['errorCode'] = 'MISSING_AUTHKEY';
	$result['errorMessage'] = 'Your authentification key haven\'t been sent to us, it may be an error';
	die(json_encode($result, JSON_FORCE_OBJECT));
}
else 
if(!$_Oli->verifyAuthKey($_POST['authKey'])) {
	$result['error'] = true;
	$result['errorCode'] = 'INVALID_AUTHKEY';
	$result['errorMessage'] = 'Your authentification key has expired';
	die(json_encode($result, JSON_FORCE_OBJECT));
}
else if(!isset($_POST['postId'])) {
	$result['error'] = true;
	$result['errorCode'] = 'MISSING_POST';
	$result['errorMessage'] = 'You have to specify which post to like';
	die(json_encode($result, JSON_FORCE_OBJECT));
}
else if(!$_Oli->isExistInfosMySQL('social_posts', array('id' => $_POST['postId']), false)) {
	$result['error'] = true;
	$result['errorCode'] = 'UNKNOWN_POST';
	$result['errorMessage'] = 'The post you want to like doesn\'t exist';
	die(json_encode($result, JSON_FORCE_OBJECT));
}
else {
	$postOwner = $_Oli->getInfosMySQL('social_posts', 'owner', array('id' => $_POST['postId']), false);
	if($postOwner != 'all') {
		if(!$_Oli->isExistInfosMySQL('social_likes', array('username' => $_Oli->getAuthKeyOwner($_POST['authKey']), 'post_id' => $_POST['postId']), false)) {
			if($_Oli->insertLineMySQL('social_likes', array('id' => $_Oli->getLastInfoMySQL('social_likes', 'id') + 1, 'username' => $_Oli->getAuthKeyOwner($_POST['authKey']), 'post_id' => $_POST['postId']))) {
				$_Oli->deleteLinesMySQL('social_notifications', array('username' => $postOwner, 'type' => 'like', 'data' => array('username' => $_Oli->getAuthKeyOwner($_POST['authKey']), 'postId' => $_POST['postId'])));
				if($postOwner != $_Oli->getAuthKeyOwner($_POST['authKey'])) $_Oli->insertLineMySQL('social_notifications', array('id' => $_Oli->getLastInfoMySQL('social_notifications', 'id') + 1, 'username' => $postOwner, 'type' => 'like', 'data' => array('username' => $_Oli->getAuthKeyOwner($_POST['authKey']), 'postId' => $_POST['postId']), 'creation_date' => date('Y-m-d H:i:s')));
				
				$result['error'] = false;
				$result['doLike'] = true;
				$result['likesCount'] = $_Oli->isExistInfosMySQL('social_likes', array('post_id' => $_POST['postId']), false);
				die(json_encode($result, JSON_FORCE_OBJECT));
			}
			else {
				$result['error'] = true;
				$result['errorCode'] = 'LIKE_ERROR';
				$result['errorMessage'] = 'An error occured while adding a like on this post, please try again';
				die(json_encode($result, JSON_FORCE_OBJECT));
			}
		}
		else {
			if($_Oli->deleteLinesMySQL('social_likes', array('username' => $_Oli->getAuthKeyOwner($_POST['authKey']), 'post_id' => $_POST['postId']))) {
				$_Oli->deleteLinesMySQL('social_notifications', array('username' => $postOwner, 'type' => 'like', 'data' => array('username' => $_Oli->getAuthKeyOwner($_POST['authKey']), 'postId' => $_POST['postId'])));
				
				$result['error'] = false;
				$result['doLike'] = false;
				$result['likesCount'] = $_Oli->isExistInfosMySQL('social_likes', array('post_id' => $_POST['postId']), false);
				die(json_encode($result, JSON_FORCE_OBJECT));
			}
			else {
				$result['error'] = true;
				$result['errorCode'] = 'UNLIKE_ERROR';
				$result['errorMessage'] = 'An error occured while removing your like from this post, please try again';
				die(json_encode($result, JSON_FORCE_OBJECT));
			}
		}
	}
	else {
		$result['error'] = true;
		$result['errorCode'] = 'ANNOUNCEMENT_POST';
		$result['errorMessage'] = 'You tried to like an announcement';
		die(json_encode($result, JSON_FORCE_OBJECT));
	}
}
?>