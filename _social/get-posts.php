<?php
/** Load Oli Framework */
if(!defined('ABSPATH')) define('ABSPATH', dirname(__FILE__) . '/');
require_once ABSPATH . 'load.php';

/** ------ */

#  MATIBOUX SOCIAL:
#    Post Saving Script

#  Made to work with Oli Beta 1.7.0

#  $_POST:
#    * ['username']
#    ? ['from']
#    ? ['limit']
#    ? ['avatarSize']

if(!isset($_POST['username'])) {
	$result['error'] = true;
	$result['errorCode'] = 'MISSING_IDENTITY';
	$result['errorMessage'] = 'No user have been specified';
	die(json_encode($result, JSON_FORCE_OBJECT));
}
else {
	$startFrom = (isset($_POST['from']) AND $_POST['from'] > 0) ? $_POST['from'] : null;
	$rowLimit = (isset($_POST['limit']) AND $_POST['limit'] > 0 AND $_POST['limit'] <= 300) ? $_POST['limit'] : 100;
	$avatarSize = (isset($_POST['avatarSize']) AND $_POST['avatarSize'] > 0) ? $_POST['avatarSize'] : 100;
	
	$posts = $_Oli->getLinesMySQL('social_posts', array('owner' => $_POST['username']), array('fromId' => $startFrom, 'limit' => $rowLimit), false, true);
	
	foreach($posts as $eachKey => $eachPost) {
		$avatarInfos = $_Avatar->getFileLines(array('name' => 'user_avatar', 'file_key' => $_POST['username']), false);
		$posts['avatarUrl'] = (!empty($avatarInfos)) ? $_Avatar->getUploadsUrl() . $avatarInfos['path_addon'] . $avatarInfos['file_name'] : $_Gravatar->getGravatar($_Oli->getAccountInfos('ACCOUNTS', 'email', $_POST['username'], false), $avatarSize);
		
		unset($posts[$eachKey]['media_key'], $posts[$eachKey]['user_ip']);
	}
	
	$result['error'] = false;
	$result['posts'] = $posts;
	die(json_encode($result, JSON_FORCE_OBJECT));
	
}
?>