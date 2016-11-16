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
|*|  * height (integer):
|*|      => Taille de la personne
|*|  * weight (float): 
|*|      => Poids de la personne
|*|  * unitSystem (string, facultatif): 
|*|      => Système d'unité utilisé
|*|
|*|  * authKey (string, facultatif): 
|*|      => Clé d'authentification de la session utilisateur
|*|
\*/

if(empty($param_height)) {
	$_APIs->setResponse('error', true);
	$_APIs->setResponse('errorCode', 'ERR_NO_HEIGHT');
	$_APIs->setResponse('errorMessage', 'Taille non définie');
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
else if($param_height <= 0) {
	$_APIs->setResponse('error', true);
	$_APIs->setResponse('errorCode', 'ERR_HEIGHT_NULL');
	$_APIs->setResponse('errorMessage', 'Taille définie inférieure ou égale à zéro');
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
else if($param_height < 3) {
	$_APIs->setResponse('error', true);
	$_APIs->setResponse('errorCode', 'ERR_LOW_HEIGHT');
	$_APIs->setResponse('errorMessage', 'Taille définie trop basse, vérifiez l\'unité');
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
else if(empty($param_weight)) {
	$_APIs->setResponse('error', true);
	$_APIs->setResponse('errorCode', 'ERR_NO_WEIGHT');
	$_APIs->setResponse('errorMessage', 'Poids non défini');
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
else if($param_weight <= 0) {
	$_APIs->setResponse('error', true);
	$_APIs->setResponse('errorCode', 'ERR_WEIGHT_NULL');
	$_APIs->setResponse('errorMessage', 'Poids défini inférieure ou égale à zéro');
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
else {
	$allowedUnitSystems = ['InternationalSystem', 'EnglishSystem'];
	$param_unitSystem = (in_array($param_unitSystem, $allowedUnitSystems)) ? $param_unitSystem : $allowedUnitSystems[0];
	
	$calculatedIMC = round($param_weight / (($param_height / 100) ** 2), 2);
	
	// if($_Oli->verifyAuthKey($param_authKey)) {
		// if(!$_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner($param_authKey))))
			// $_Oli->insertLineMySQL('keygen_preferences', array('id' => $_Oli->getLastInfoMySQL('keygen_preferences', 'id') + 1, 'username' => $_Oli->getAuthKeyOwner($param_authKey), 'genreNum' => $param_genreNum, 'genreMin' => $param_genreMin, 'genreMaj' => $param_genreMaj, 'genreSpe' => $param_genreSpe, 'length' => $param_length, 'multiCharacter' => $param_multiCharacter));
		// else if(!$_Oli->getInfosMySQL('keygen_preferences', 'default_parameters', array('username' => $_Oli->getAuthKeyOwner($param_authKey))))
			// $_Oli->updateInfosMySQL('keygen_preferences', array('genreNum' => $param_genreNum, 'genreMin' => $param_genreMin, 'genreMaj' => $param_genreMaj, 'genreSpe' => $param_genreSpe, 'length' => $param_length, 'multiCharacter' => $param_multiCharacter), array('username' => $_Oli->getAuthKeyOwner($param_authKey)));
		
		// if($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner($param_authKey))) AND $_Oli->getInfosMySQL('keygen_preferences', 'keep_history', array('username' => $_Oli->getAuthKeyOwner($param_authKey))) AND !empty($param_encryptPassword)) {
			// if($_Oli->verifyLogin($_Oli->getAuthKeyOwner($param_authKey), $param_encryptPassword)) {
				// $oldKeySize = GibberishAES::size();
				
				// GibberishAES::size($encryptKeySize);
				// $encryptedKeygen = GibberishAES::enc($keygen, $param_encryptPassword);
				// $_Oli->insertLineMySQL('keygen_history', array('id' => $_Oli->getLastInfoMySQL('keygen_history', 'id') + 1, 'username' => $_Oli->getAuthKeyOwner($param_authKey), 'keygen' => $encryptedKeygen, 'length' => strlen($keygen), 'encrypt_key_size' => $encryptKeySize, 'date' => date('Y-m-d'), 'time' => date('H:i:s')));
				
				// GibberishAES::size($oldKeySize); // Restore old Key Size
			// }
			// else {
				// $_APIs->setResponse('error', true);
				// $_APIs->setResponse('errorCode', 'ERR_WRONG_PASSWORD');
				// $_APIs->setResponse('errorMessage', 'Le mot de passe que vous avez entré est incorrect, ce doit être le même que celui de votre compte');
				// die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
			// }
		// }
	// }
	
	$_APIs->setResponse('error', false);
	$_APIs->setResponse('unitSystem', $param_unitSystem);
	$_APIs->setResponse('calculatedIMC', urlencode($calculatedIMC));
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
?>