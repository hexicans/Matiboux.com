<?php
/** Load Oli Framework */
if(!defined('ABSPATH')) define('ABSPATH', dirname(__FILE__) . '/');
require_once ABSPATH . 'load.php';

/** ------ */

/*\
|*|  ---------------------------
|*|  --- [  KeyGen Script  ] ---
|*|  --- [   Version 2.0   ] ---
|*|  ---------------------------
|*|  
|*|  KeyGen is an open source service made to generate random password and security keys for anyone
|*|  
|*|  Made to work with :
|*|  - Oli, a PHP Framework (https://oliframework.github.io/Oli/)
|*|  - The KeyGen PHP library ( /// )
|*|  PS: Both of them have been made by myself o/
|*|  
|*|  Copyright (C) 2014 Mathieu Guérin (aka "Matiboux")
|*|  Please read the README.md file for more infos
|*|  Also see the KeyGen project repository! (https://github.com/matiboux/KeyGen)
\*/

# $_POST parameters:
#   - ['genreNum']
#   - ['genreMin']
#   - ['genreMaj']
#   - ['genreSpe']
#   * ['length']
#   ? ['blocks']
#   ? ['redundancy']
#   ? ['hashes']
#   * ['authKey']

#    X ['password']
// $encryptKeySize = 256;

$_POST['genreNum'] = !isset($_POST['genreNum']) ? true : ($_POST['genreNum'] ? true : false);
$_POST['genreMin'] = !isset($_POST['genreMin']) ? true : ($_POST['genreMin'] ? true : false);
$_POST['genreMaj'] = !isset($_POST['genreMaj']) ? true : ($_POST['genreMaj'] ? true : false);
$_POST['genreSpe'] = $_POST['genreSpe'] ? true : false;
if(!$_POST['length'] OR $_POST['length'] <= 0) $_POST['length'] = false;
if(!$_POST['blocks'] OR $_POST['blocks'] <= 0) $_POST['blocks'] = false;
$_POST['redundancy'] = $_POST['redundancy'] ? true : false;

$charactersAllowed = '';
if($_POST['genreNum']) $charactersAllowed .= '1234567890';
if($_POST['genreMin']) $charactersAllowed .= 'abcdefghijklmnopqrstuvwxyz';
if($_POST['genreMaj']) $charactersAllowed .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
if($_POST['genreSpe'] AND !$_POST['blocks']) $charactersAllowed .= '!#$%&\()+-;?@[]^_{|}';

if(!$charactersAllowed) {
	$result['error'] = true;
	$result['errorCode'] = 'NO_GENRE';
	$result['errorMessage'] = 'No genre has been given';
	die(json_encode($result, JSON_FORCE_OBJECT));
}
else if(empty($_POST['length'])) {
	$result['error'] = true;
	$result['errorCode'] = 'NO_LENGTH';
	$result['errorMessage'] = 'No length has been given';
	die(json_encode($result, JSON_FORCE_OBJECT));
}
else if($_POST['length'] <= 0) {
	$result['error'] = true;
	$result['errorCode'] = 'LENGTH_NULL';
	$result['errorMessage'] = 'Length can\'t be null or lower than zero';
	die(json_encode($result, JSON_FORCE_OBJECT));
}
else if($_POST['length'] > 1024) {
	$result['error'] = true;
	$result['errorCode'] = 'HIGH_LENGTH';
	$result['errorMessage'] = 'Length can\'t be greater than 1024';
	die(json_encode($result, JSON_FORCE_OBJECT));
}
// else if($_POST['howMany'] > 16) {
	// $result['error'] = true;
	// $result['errorCode'] = 'TOO_MANY_KEYGENS';
	// $result['errorMessage'] = 'You can\'t request more than 16 keygens at a time';
	// die(json_encode($result, JSON_FORCE_OBJECT));
// }
else {
	if($_POST['length'] > strlen($charactersAllowed) AND !$_POST['multiCharacter']) $_POST['multiCharacter'] = $forceMultiCharacter = true;
	
	$keygen = null;
	$whileCount = 0;
	while($whileCount < $_POST['length']) {
		$randomCharacter = substr($charactersAllowed, $_Oli->randomNumber(0, strlen($charactersAllowed) - 1), 1);
		if($_POST['multiCharacter'] OR !strstr($keygen, $randomCharacter)) {
			$keygen .= $randomCharacter;
			$whileCount++;
		}
	}
	
	if(!empty($_POST['blockLength'])) {
		if($_POST['blockLength'] > $_POST['length']) $_POST['blockLength'] = $_POST['length'];
		$addBlockLength = $_POST['length'] % $_POST['blockLength'];
		$newKeygen = '';
		
		foreach(str_split($keygen, floor($_POST['length'] / $_POST['blockLength']) + ($addBlockLength > 0 ? 1 : 0)) as $eachPart) {
			$newKeygen .= $eachPart . '-';
			if($addBlockLength > 0) $addBlockLength--;
		}
		$keygen = substr($newKeygen, 0, -1);
	}
	
	// if($_Oli->verifyAuthKey($_POST['authKey'])) {
		// if(!$_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner($_POST['authKey']))))
			// $_Oli->insertLineMySQL('keygen_preferences', array('id' => $_Oli->getLastInfoMySQL('keygen_preferences', 'id') + 1, 'username' => $_Oli->getAuthKeyOwner($_POST['authKey']), 'genreNum' => $param_genreNum, 'genreMin' => $param_genreMin, 'genreMaj' => $param_genreMaj, 'genreSpe' => $param_genreSpe, 'length' => $_POST['length'], 'multiCharacter' => $param_multiCharacter));
		// else if(!$_Oli->getInfosMySQL('keygen_preferences', 'default_parameters', array('username' => $_Oli->getAuthKeyOwner($_POST['authKey']))))
			// $_Oli->updateInfosMySQL('keygen_preferences', array('genreNum' => $param_genreNum, 'genreMin' => $param_genreMin, 'genreMaj' => $param_genreMaj, 'genreSpe' => $param_genreSpe, 'length' => $_POST['length'], 'multiCharacter' => $param_multiCharacter), array('username' => $_Oli->getAuthKeyOwner($_POST['authKey'])));
		
		// if($_Oli->isExistInfosMySQL('keygen_preferences', array('username' => $_Oli->getAuthKeyOwner($_POST['authKey']))) AND $_Oli->getInfosMySQL('keygen_preferences', 'keep_history', array('username' => $_Oli->getAuthKeyOwner($_POST['authKey']))) AND !empty($_POST['password'])) {
			// if($_Oli->verifyLogin($_Oli->getAuthKeyOwner($_POST['authKey']), $_POST['password'])) {
				// $oldKeySize = GibberishAES::size();
				
				// GibberishAES::size($encryptKeySize);
				// $encryptedKeygen = GibberishAES::enc($keygen, $_POST['password']);
				// $_Oli->insertLineMySQL('keygen_history', array('id' => $_Oli->getLastInfoMySQL('keygen_history', 'id') + 1, 'username' => $_Oli->getAuthKeyOwner($_POST['authKey']), 'keygen' => $encryptedKeygen, 'length' => strlen($keygen), 'encrypt_key_size' => $encryptKeySize, 'date' => date('Y-m-d'), 'time' => date('H:i:s')));
				
				// GibberishAES::size($oldKeySize); // Restore old Key Size
			// }
			// else {
				// $_APIs->setResponse('error', true);
				// $_APIs->setResponse('errorCode', 'WRONG_PASSWORD');
				// $_APIs->setResponse('errorMessage', 'Le mot de passe que vous avez entré est incorrect, ce doit être le même que celui de votre compte');
				// die($_APIs->encodeResponse(JSON_FORCE_OBJECT));
			// }
		// }
	// }
	
	if($keygen == 'eli') $note = 'He was everything to me... I miss him so much';
	
	$result['error'] = false;
	$result['keygen'] = urlencode($keygen);
	if(!empty($note)) $result['note'] = $note;
	if($_POST['showsHashes']) $result['hashes'] = array(
		'crypt' => urlencode(crypt($keygen)),
		'md5' => urlencode(md5($keygen)),
		'sha1' => urlencode(sha1($keygen)),
		'sha256' => urlencode(hash('sha256', $keygen)),
		'sha512' => urlencode(hash('sha512', $keygen)));
	if($forceMultiCharacter) $result['forceMultiCharacter'] = $forceMultiCharacter;
	die(json_encode($result, JSON_FORCE_OBJECT));
}
?>