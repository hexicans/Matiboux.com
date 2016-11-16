<?php
/** OLI FRAMEWORK REQUIREMENTS */
if(!defined('ABSPATH')) define('ABSPATH', dirname(__FILE__) . '/');

$scriptBasePath = fgets(fopen(ABSPATH . 'script_basepath.oli', 'r'));
if(!defined('SCRIPTBASEPATH')) {
	if(!empty($scriptBasePath)) define('SCRIPTBASEPATH', $scriptBasePath);
	else define('SCRIPTBASEPATH', ABSPATH);
}

if(!defined('INCLUDEPATH')) define('INCLUDEPATH', SCRIPTBASEPATH . 'includes/');
if(!defined('ADDONSPATH')) define('ADDONSPATH', SCRIPTBASEPATH . 'addons/');

if(!defined('CONTENTPATH')) define('CONTENTPATH', ABSPATH . 'content/');
if(!defined('THEMEPATH')) define('THEMEPATH', CONTENTPATH . 'theme/');
if(!defined('MEDIAPATH')) define('MEDIAPATH', CONTENTPATH . 'media/');

require_once INCLUDEPATH . 'loader.php';
use \OliFramework\OliCore;
$_Oli = new OliCore;

require_once ABSPATH . 'config.php';

/** --- --- */

$_APIs->setauthorizedWebsites('*'); // Change to a public visibility
$_APIs->setupAPI('JSON');

if(!empty($_POST)) {
	foreach($_POST as $eachKey => $eachValue) {
		${'param_' . $eachKey} = $eachValue;
	}
}
else if(!empty($_GET)) {
	foreach($_GET as $eachKey => $eachValue) {
		${'param_' . $eachKey} = $eachValue;
	}
}

/*\
|*|  
|*|  Parameters:
|*|  * username (string): 
|*|      => Identifiant du compte à connecter
|*|  * password (string):
|*|      => Mot de passe du compte à connecter
|*|  * rememberMe (boolean, facultatif):
|*|      => Définit une longue durée de connexion ou non
|*|
\*/

$param_rememberMe = (empty($param_rememberMe)) ? false : true;

if(empty($param_username) OR empty($param_password)) {
	$_APIs->setResponse('error', true);
	$_APIs->setResponse('errorCode', 'ERR_USERNAME_OR_PASSWORD_EMPTY');
	$_APIs->setResponse('errorMessage', 'The username or the password is missing');
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
else if(!$_Oli->isExistAccountInfos('ACCOUNTS', array('username' => $param_username))) {
	$_APIs->setResponse('error', true);
	$_APIs->setResponse('errorCode', 'ERR_UNKOWN_ACCOUNT');
	// $_APIs->setResponse('errorMessage', '');
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
else if($_Oli->getUserRightLevel(array('username' => $param_username)) == $_Oli->translateUserRight('NEW-USER')) {
	$_APIs->setResponse('error', true);
	$_APIs->setResponse('errorCode', 'ERR_UNACTIVATED_ACCOUNT');
	// $_APIs->setResponse('errorMessage', '');
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
else if($_Oli->getUserRightLevel(array('username' => $param_username)) == $_Oli->translateUserRight('BANNED')) {
	$_APIs->setResponse('error', true);
	$_APIs->setResponse('errorCode', 'ERR_BANNED_ACCOUNT');
	// $_APIs->setResponse('errorMessage', '');
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
else if($_Oli->getUserRightLevel(array('username' => $param_username)) < $_Oli->translateUserRight('USER')) {
	$_APIs->setResponse('error', true);
	$_APIs->setResponse('errorCode', 'ERR_TOO_WEAK_ACCOUNT');
	// $_APIs->setResponse('errorMessage', '');
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
else if($_Oli->verifyLogin($param_username, $param_password)) {
	//             60 sec  =  1 minute
	//           3600 sec  =  1 heure
	//        24*3600 sec  =  1 jour
	//     30*24*3600 sec  =  1 mois
	//    365*24*3600 sec  =  1 an
	// 10*365*24*3600 sec  =  10 ans
	$cookie_duration = ($param_rememberMe) ? 14*24*3600 : 24*3600; // 2 semaines : 1 jour
	
	$userSessions = $_Oli->getAccountLines('SESSIONS', array('username' => $param_username));
	$countUserSessions = $_Oli->isExistAccountInfos('SESSIONS', array('username' => $param_username));
	if(!empty($userSessions)) {
		$userSessions = ($countUserSessions == 1) ? [$userSessions] : $userSessions;
		foreach($userSessions as $eachLine) {
			if(strtotime($eachLine['expire_date']) < time())
				$_Oli->deleteAccountLines('SESSIONS', array('auth_key' => $eachLine['auth_key']));
		}
	}
	
	$authKey = $_Oli->loginAccount($param_username, $param_password, $cookie_duration);
	
	$_APIs->setResponse('error', false);
	$_APIs->setResponse('authKey', $authKey);
	$_APIs->setResponse('authKeyCookieDuration', $cookie_duration);
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
else {
	$_APIs->setResponse('error', true);
	$_APIs->setResponse('errorCode', 'ERR_WRONG_USERNAME_OR_PASSWORD');
	$_APIs->setResponse('errorMessage', 'You used a wrong username or password');
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
?>