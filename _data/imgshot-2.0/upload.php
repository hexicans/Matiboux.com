<?php
if(!defined('ABSPATH')) {
	define('ABSPATH', dirname(__FILE__) . '/');
}

require ABSPATH . 'includes/config.php';
require ABSPATH . 'includes/mysql.php';
require ABSPATH . 'includes/class.php';
require ABSPATH . 'includes/functions.php';

$key = '';
$maxSize = 4194304; // 1 Mo = 1048576 octets
$extensionsAllowed = array('jpg', 'jpeg', 'gif', 'png');
$extensionFile = strtolower(substr(strrchr($_FILES['image']['name'], '.'),1));

/** All Errors :
 * ERR_NO_FILE -> No file sent.
 * ERR_SIZE -> File too big (PHP, Form or verification)
 * ERR_PARTIAL -> Partially uploaded file
 * ERR_TYPE -> Wrong type of file
 * ERR_NAME_FILE_EMPTY -> No generated file name
 * ERR_GENERATE_NAME_TIMED_OUT_AND_ALREADY_USED -> Generate timed out and name is already used
 * ERR_SAVE_FILE -> Problem when save
 */
?>

<?php
if ($_FILES['image']['error'] == UPLOAD_ERR_NO_FILE) {
	header('Location: http://imgshot.matiboux.com/home/ERR_NO_FILE');
}
else if ($_FILES['image']['error'] == UPLOAD_ERR_INI_SIZE) {
	header('Location: http://imgshot.matiboux.com/home/ERR_HIGH_SIZE');
}
else if ($_FILES['image']['error'] == UPLOAD_ERR_FORM_SIZE) {
	header('Location: http://imgshot.matiboux.com/home/ERR_HIGH_SIZE');
}
else if ($_FILES['image']['error'] == UPLOAD_ERR_PARTIAL ) {
	header('Location: http://imgshot.matiboux.com/home/ERR_PARTIAL');
}
else if($_FILES['image']['size'] > $maxSize) {
	header('Location: http://imgshot.matiboux.com/home/ERR_HIGH_SIZE');
}
else if(in_array($extensionFile, $extensionsAllowed)) {
	$dirName = MEDIA . date(Y) . '/';
	
	$key = '';
	$countLoop = 1;
	while($countLoop <= 10) {
		$key = KeyGen(12);
		if($key == '') {
			header('Location: http://imgshot.matiboux.com/home/ERR_NAME_FILE_EMPTY');
		}
		else if(!file_exists($dirName . $key . '.*')) {
			break;
		}
		$countLoop++;
	}
	if(file_exists($dirName . $key . '.*')) {
		header('Location: http://imgshot.matiboux.com/home/ERR_GENERATE_NAME_TIMED_OUT_AND_ALREADY_USED');
	}
	else if($key != '') {
		$fileName = $key . '.' . $extensionFile;
		$dirWhereSave = $dirName . $key . '.' . $extensionFile;
		$saveFile = move_uploaded_file($_FILES['image']['tmp_name'], $dirWhereSave);
		if(!file_exists($dirName)) {
			mkdir($dirName);
			$indexFile = fopen($dirName . 'index.php', 'w');
			fputs($indexFile, '<?php /** Chut. */ ?>');
			fclose($indexFile);
		}
		else if(!file_exists($dirName . 'index.php')) {
			$indexFile = fopen($dirName . 'index.php', 'w');
			fputs($indexFile, '<?php /** Chut. */ ?>');
			fclose($indexFile);
		}
		if ($saveFile) {
			header('Location: http://imgshot.matiboux.com/view/' . date(Y) . '/' . $fileName);
		}
		else {
			header('Location: http://imgshot.matiboux.com/home/ERR_SAVE_FILE');
		}
	}
	else {
		header('Location: http://imgshot.matiboux.com/home/ERR_NAME_FILE_EMPTY');
	}
}
else { // Unkown Error
	header('Location: http://imgshot.matiboux.com/home/ERR_UNKOWN');
}
?>