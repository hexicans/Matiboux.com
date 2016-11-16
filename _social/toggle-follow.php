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
#    * ['username']

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
else if(!isset($_POST['username'])) {
	$result['error'] = true;
	$result['errorCode'] = 'MISSING_USERNAME';
	$result['errorMessage'] = 'You have to specify an user to follow or unfollow';
	die(json_encode($result, JSON_FORCE_OBJECT));
}
else if(!$_Oli->isExistAccountInfos('ACCOUNTS', array('username' => $_POST['username']), false)) {
	$result['error'] = true;
	$result['errorCode'] = 'UNKNOWN_USERNAME';
	$result['errorMessage'] = 'The user you want to follow or unfollow doesn\'t exist';
	die(json_encode($result, JSON_FORCE_OBJECT));
}
else {
	if(!$_Oli->isExistInfosMySQL('social_follows', array('username' => $_Oli->getAuthKeyOwner($_POST['authKey']), 'follows' => $_POST['username']), false)) {
		$_POST['username'] = $_Oli->getAccountInfos('ACCOUNTS', 'username', $_POST['username'], false);
		if($_Oli->insertLineMySQL('social_follows', array('id' => $_Oli->getLastInfoMySQL('social_follows', 'id') + 1, 'username' => $_Oli->getAuthKeyOwner($_POST['authKey']), 'follows' => $_POST['username']))) {
			$_Oli->deleteLinesMySQL('social_notifications', array('username' => $_POST['username'], 'type' => 'unfollow', 'data' => array('username' => $_Oli->getAuthKeyOwner($_POST['authKey']))));
			$_Oli->insertLineMySQL('social_notifications', array('id' => $_Oli->getLastInfoMySQL('social_notifications', 'id') + 1, 'username' => $_POST['username'], 'type' => 'follow', 'data' => array('username' => $_Oli->getAuthKeyOwner($_POST['authKey'])), 'creation_date' => date('Y-m-d H:i:s')));
			
			$result['error'] = false;
			$result['doFollow'] = true;
			$result['username'] = $_POST['username'];
			die(json_encode($result, JSON_FORCE_OBJECT));
		}
		else {
			$result['error'] = true;
			$result['errorCode'] = 'FOLOW_ERROR';
			$result['errorMessage'] = 'An error occured while following this user, please try again';
			die(json_encode($result, JSON_FORCE_OBJECT));
		}
	}
	else {
		if($_Oli->deleteLinesMySQL('social_follows', array('username' => $_Oli->getAuthKeyOwner($_POST['authKey']), 'follows' => $_POST['username']))) {
			$_Oli->deleteLinesMySQL('social_notifications', array('username' => $_POST['username'], 'type' => 'follow', 'data' => array('username' => $_Oli->getAuthKeyOwner($_POST['authKey']))));
			$_Oli->insertLineMySQL('social_notifications', array('id' => $_Oli->getLastInfoMySQL('social_notifications', 'id') + 1, 'username' => $_POST['username'], 'type' => 'unfollow', 'data' => array('username' => $_Oli->getAuthKeyOwner($_POST['authKey'])), 'creation_date' => date('Y-m-d H:i:s')));
			
			$result['error'] = false;
			$result['doFollow'] = false;
			$result['username'] = $_POST['username'];
			die(json_encode($result, JSON_FORCE_OBJECT));
		}
		else {
			$result['error'] = true;
			$result['errorCode'] = 'UNFOLOW_ERROR';
			$result['errorMessage'] = 'An error occured while unfollowing this user, please try again';
			die(json_encode($result, JSON_FORCE_OBJECT));
		}
	}
}
?>