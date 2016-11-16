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
|*|  * min (integer):
|*|      => Minimum de l'intervalle de génération
|*|  * max (integer):
|*|      => Maximum de l'intervalle de génération
|*|
|*|  * authKey (string, facultatif):
|*|      => Clé d'authentification de la session utilisateur
|*|
\*/

if(empty($param_min)) {
	$_APIs->setResponse('error', true);
	$_APIs->setResponse('errorCode', 'ERR_NO_MIN');
	$_APIs->setResponse('errorMessage', 'Minimum non défini');
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
else if(empty($param_max)) {
	$_APIs->setResponse('error', true);
	$_APIs->setResponse('errorCode', 'ERR_NO_MAX');
	$_APIs->setResponse('errorMessage', 'Maximum non défini');
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
else {
	if($param_min > $param_max) {
		$reversedMinMax = true;
		
		/** Reverse $param_min & $param_max variables */
		$param_min += $param_max;
		$param_max = $param_min - $param_max;
		$param_min = $param_min - $param_max;
	}
	$generatedNumber = $_Oli->randomNumber($param_min, $param_max);
	
	if($_Oli->verifyAuthKey($param_authKey)) {
		if($_Oli->isExistInfosMySQL('random_preferences', array('username' => $_Oli->getAuthKeyOwner($param_authKey))) AND !$_Oli->getInfosMySQL('random_preferences', 'default_parameters', array('username' => $_Oli->getAuthKeyOwner($param_authKey))))
			$_Oli->updateInfosMySQL('random_preferences', array('min' => $param_min, 'max' => $param_max), array('username' => $_Oli->getAuthKeyOwner($param_authKey)));
		else
			$_Oli->insertLineMySQL('random_preferences', array('id' => $_Oli->getLastInfoMySQL('random_preferences', 'id') + 1, 'username' => $_Oli->getAuthKeyOwner($param_authKey), 'min' => $param_min, 'max' => $param_max));
	}
	
	$_APIs->setResponse('error', false);
	$_APIs->setResponse('randomNumber', urlencode($generatedNumber));
	if($reversedMinMax) $_APIs->setResponse('reversedMinMax', true);
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
?>