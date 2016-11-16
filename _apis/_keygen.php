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
|*|  * genreNum (boolean):
|*|      => Ajoute ou non les caractères numériques aux caractères autorisés
|*|  * genreMin (boolean): 
|*|      => Ajoute ou non les caractères minuscules aux caractères autorisés
|*|  * genreMaj (boolean): 
|*|      => Ajoute ou non les caractères majuscules aux caractères autorisés
|*|  * genreSpe (boolean, facultatif): 
|*|      => Ajoute ou non les caractères spéciaux aux caractères autorisés (pour des keygens)
|*|  * length (integer): 
|*|      => Définit la longueur de la clé
|*|  * blockLength (integer, facultatif): 
|*|      => Définit le nombre de blocs (pour des clés d'activation)
|*|  * multiCharacter (boolean, facultatif): 
|*|      => Autorise ou non la redondance des caractères dans la clé
|*|
|*|  * authKey (string, facultatif): 
|*|      => Clé d'authentification de la session utilisateur
|*|  * password (string, facultatif): 
|*|      => Mot de passe de crytage des keygen dans l'historique
|*|
\*/

$charactersAllowed = '';
$param_genreNum = (empty($param_genreNum)) ? false : true;
$param_genreMin = (empty($param_genreMin)) ? false : true;
$param_genreMaj = (empty($param_genreMaj)) ? false : true;
$param_genreSpe = (empty($param_genreSpe)) ? false : true;
$param_multiCharacter = (empty($param_multiCharacter)) ? false : true;

/** Encrypt Config */
$encryptKeySize = 256;

if($param_genreNum) $charactersAllowed .= '1234567890';
if($param_genreMin) $charactersAllowed .= 'abcdefghijklmnopqrstuvwxyz';
if($param_genreMaj) $charactersAllowed .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
if(empty($param_blockLength) AND $param_genreSpe) $charactersAllowed .= '!"#$%&\'()*+-./:;<=>?@[\\]^_`{|}~€,µ';

if(empty($charactersAllowed)) {
	$_APIs->setResponse('error', true);
	$_APIs->setResponse('errorCode', 'ERR_NO_GENRE');
	$_APIs->setResponse('errorMessage', 'Aucun genre de clé défini');
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
else if(empty($param_length)) {
	$_APIs->setResponse('error', true);
	$_APIs->setResponse('errorCode', 'ERR_NO_LENGTH');
	$_APIs->setResponse('errorMessage', 'Taille non définie');
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
else if($param_length <= 0) {
	$_APIs->setResponse('error', true);
	$_APIs->setResponse('errorCode', 'ERR_LENGTH_NULL');
	$_APIs->setResponse('errorMessage', 'Taille définie inférieure ou égale à zéro');
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
else if($param_length > 2048) {
	$_APIs->setResponse('error', true);
	$_APIs->setResponse('errorCode', 'ERR_LENGTH_TOO_BIG');
	$_APIs->setResponse('errorMessage', 'Taille définie trop élevée');
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
else {
	$lengthMax = strlen($charactersAllowed);
	if($param_length > $lengthMax AND !$param_multiCharacter) {
		$forceMultiCharacter = true;
		$param_length = $lengthMax;
	}
	
	$i = 0;
	$keygen = '';
	while($i < $param_length) {
		$randomCharacter = substr($charactersAllowed, $_Oli->randomNumber(0, $lengthMax - 1), 1);
		
		if($param_multiCharacter OR !strstr($keygen, $randomCharacter)) {
			$keygen .= $randomCharacter;
			$i++;
		}
	}
	
	if(!empty($param_blockLength)) {
		if($param_blockLength > $param_length)
			$param_blockLength = $param_length;
		
		$newKeyGen = '';
		foreach(str_split($keygen, round($param_length / $param_blockLength)) as $eachPart) {
			$newKeyGen .= $eachPart . '-';
		}
		
		$keygen = substr($newKeyGen, 0, -1);
	}
	
	if($_Oli->verifyAuthKey($param_authKey)) {
		if(!$_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner($param_authKey))))
			$_Oli->insertLineMySQL('keygen_preferences', array('id' => $_Oli->getLastInfoMySQL('keygen_preferences', 'id') + 1, 'username' => $_Oli->getAuthKeyOwner($param_authKey), 'genreNum' => $param_genreNum, 'genreMin' => $param_genreMin, 'genreMaj' => $param_genreMaj, 'genreSpe' => $param_genreSpe, 'length' => $param_length, 'multiCharacter' => $param_multiCharacter));
		else if(!$_Oli->getInfosMySQL('keygen_preferences', 'default_parameters', array('username' => $_Oli->getAuthKeyOwner($param_authKey))))
			$_Oli->updateInfosMySQL('keygen_preferences', array('genreNum' => $param_genreNum, 'genreMin' => $param_genreMin, 'genreMaj' => $param_genreMaj, 'genreSpe' => $param_genreSpe, 'length' => $param_length, 'multiCharacter' => $param_multiCharacter), array('username' => $_Oli->getAuthKeyOwner($param_authKey)));
		
		if($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner($param_authKey))) AND $_Oli->getInfosMySQL('keygen_preferences', 'keep_history', array('username' => $_Oli->getAuthKeyOwner($param_authKey))) AND !empty($param_encryptPassword)) {
			if($_Oli->verifyLogin($_Oli->getAuthKeyOwner($param_authKey), $param_encryptPassword)) {
				$oldKeySize = GibberishAES::size();
				
				GibberishAES::size($encryptKeySize);
				$encryptedKeygen = GibberishAES::enc($keygen, $param_encryptPassword);
				$_Oli->insertLineMySQL('keygen_history', array('id' => $_Oli->getLastInfoMySQL('keygen_history', 'id') + 1, 'username' => $_Oli->getAuthKeyOwner($param_authKey), 'keygen' => $encryptedKeygen, 'length' => strlen($keygen), 'encrypt_key_size' => $encryptKeySize, 'date' => date('Y-m-d'), 'time' => date('H:i:s')));
				
				GibberishAES::size($oldKeySize); // Restore old Key Size
			}
			else {
				$_APIs->setResponse('error', true);
				$_APIs->setResponse('errorCode', 'ERR_WRONG_PASSWORD');
				$_APIs->setResponse('errorMessage', 'Le mot de passe que vous avez entré est incorrect, ce doit être le même que celui de votre compte');
				die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
			}
		}
	}
	
	$_APIs->setResponse('error', false);
	$_APIs->setResponse('generatedKey', urlencode($keygen));
	if($param_showsHashs)
		$_APIs->setResponse('hashs', array(
			'crypt' => urlencode(crypt($keygen)),
			'md5' => urlencode(md5($keygen)),
			'sha1' => urlencode(sha1($keygen)),
			'sha256' => urlencode(hash('sha256', $keygen)),
			'sha512' => urlencode(hash('sha512', $keygen))));
	if($forceMultiCharacter)
		$_APIs->setResponse('forceMultiCharacter', $forceMultiCharacter);
	die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
}
?>