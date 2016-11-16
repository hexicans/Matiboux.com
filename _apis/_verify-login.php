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
|*|  * authKey (string): 
|*|      => Clé d'identification de l'utilisateur
|*|
\*/

if(empty($param_authKey)) {
	$_APIs->setResponse('error', true);
	$_APIs->setResponse('errorCode', 'ERR_AUTH_KEY_EMPTY');
	$_APIs->setResponse('errorMessage', 'The authentification key is missing');
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
else if($resp = $_Oli->verifyAuthKey($param_authKey)) {
	$_APIs->setResponse('error', false);
	$_APIs->setResponse('resp', $resp);
	$_APIs->setResponse('username', $_Oli->getAuthKeyOwner($param_authKey));
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
else {
	$_APIs->setResponse('error', true);
	$_APIs->setResponse('errorCode', 'ERR_INVALID_AUTH_KEY');
	$_APIs->setResponse('errorMessage', 'This auth key is invalid');
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
?>