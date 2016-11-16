<?php
/** Load Oli Framework */
if(!defined('ABSPATH')) define('ABSPATH', dirname(__FILE__) . '/');
require_once ABSPATH . 'load.php';

/** ------ */

#  KEYGEN: Filehash keygen script

#  Made for Oli Beta 1.7.0

#  $_POST:
#    * ['hash']
#  $_FILE:
#    * ['file']

if(!isset($_FILES['file'])) {
	$result['error'] = true;
	$result['errorCode'] = 'NO_FILE';
	$result['errorMessage'] = 'No file has been uploaded';
	die(json_encode($result, JSON_FORCE_OBJECT));
}
else {
	// print_r($_FILES['file']);
	// print_r(sha1_file($_FILES['file']['tmp_name']));
	// die();
	if($_POST['hash'] == 'md5') $fileHash = md5_file($_FILES['file']['tmp_name']);
	else if($_POST['hash'] == 'sha1') $fileHash = sha1_file($_FILES['file']['tmp_name']);
	else $fileHash = null;
	
	if(isset($fileHash)) {
		$result['error'] = false;
		$result['hashType'] = $_POST['hash'];
		$result['fileHash'] = urlencode($fileHash);
		die(json_encode($result, JSON_FORCE_OBJECT));
	}
	else {
		$result['error'] = true;
		$result['errorCode'] = 'INVALID_HASH_TYPE';
		$result['errorMessage'] = 'An invalid hash type has been given';
		die(json_encode($result, JSON_FORCE_OBJECT));
	}
}
?>