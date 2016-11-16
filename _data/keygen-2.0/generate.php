<?php
if(!defined('ABSPATH')) {
	define('ABSPATH', dirname(__FILE__) . '/');
}

require ABSPATH . 'includes/config.php';
require ABSPATH . 'includes/mysql.php';
require ABSPATH . 'includes/class.php';
require ABSPATH . 'includes/functions.php';

$key = '';
$charactersAllowed = '';
$countLoop = 0;
$lengthMax = 0;
$length = $_POST['length'];

/** All Errors :
 * ERR_NO_GENRE -> Any genre was selected
 * ERR_NO_LENGTH -> Wanted length is needed
 * ERR_LENGTH_NULL -> Wanted length is 0
 */
?>

<?php
if($_POST['genreNum']) {
	$charactersAllowed .= '1234567890';
}
if($_POST['genreMin']) {
	$charactersAllowed .= 'abcdefghijklmnopqrstuvwxyz';
}
if($_POST['genreMaj']) {
	$charactersAllowed .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
}
if($_POST['genreSpe']) {
	//$charactersAllowed .= '@#&%€*-+()!"\':;/?~`|•√π÷×{}£¥$°^_=[]™®©¶<>';
}

if($charactersAllowed == '') {
	header('Location: ' . getBaseUrl() . 'home/ERR/ERR_NO_GENRE');
}
else if($length == '') {
	header('Location: ' . getBaseUrl() . 'home/ERR/ERR_NO_LENGTH');
}
else if($length == 0) {
	header('Location: ' . getBaseUrl() . 'home/ERR/ERR_LENGTH_NULL');
}
else {
	$lengthMax = strlen($charactersAllowed);
	if($length > $lengthMax AND !$_POST['multiCharacter']) {
		$length = $lengthMax;
	}
	while($countLoop < $length) {
		$randomCharacter = substr($charactersAllowed, mt_rand(0, $lengthMax-1), 1);

		if($_POST['multiCharacter'] OR !strstr($key, $randomCharacter)) {
			$key .= $randomCharacter;
			$countLoop++;
		}
	}
	header('Location: ' . getBaseUrl() . 'home/' . $key);
}
?>