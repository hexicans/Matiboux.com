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
|*|  * content (string):
|*|      => Contenu du post de l'utilisateur
|*|  * imgshot_key (string, facultatif):
|*|      => Clé correspondant à l'image associée au message
|*|
|*|  * authKey (string, facultatif):
|*|      => Clé d'authentification de la session utilisateur
|*|
\*/

if(empty($param_content)) {
	$_APIs->setResponse('error', true);
	$_APIs->setResponse('errorCode', 'ERR_EMPTY_CONTENT');
	$_APIs->setResponse('errorMessage', 'Vous devez indiquer un message à poster avant de l\'envoyer');
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
else if(!$_Oli->verifyAuthKey($param_authKey)) {
	$_APIs->setResponse('error', true);
	$_APIs->setResponse('errorCode', 'ERR_');
	$_APIs->setResponse('errorMessage', 'Vous devez indiquer un message à poster avant de l\'envoyer');
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
else {
	$postId = $_Oli->getLastInfoMySQL('social_posts', 'id') + 1;
	$param_imgshot_key = (!empty($param_imgshot_key)) ? $param_imgshot_key : '';
	
	if($_Oli->insertLineMySQL('social_posts', array('id' => $postId, 
	'content' => $param_content, 
	'imgshot_media_key' => $param_imgshot_key, 
	'owner' => $_Oli->getAuthKeyOwner($param_authKey), 
	'user_ip' => $_Oli->getUserIP(), 
	'post_date' => date('Y-m-d H:i:s')))) {
		$_APIs->setResponse('error', false);
		$_APIs->setResponse('postId', $postId);
		if($reversedMinMax) $_APIs->setResponse('reversedMinMax', true);
		die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
	}
	else {
		$_APIs->setResponse('error', true);
		$_APIs->setResponse('errorCode', 'ERR_');
		$_APIs->setResponse('errorMessage', 'Vous devez indiquer un message à poster avant de l\'envoyer');
		die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
	}
}
?>