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

/** ------ */

#  NATROX CLOUD:
#  UPLOAD SCRIPT

#  This Script has been made to work with
#  the PHP framework OLI (by Matiboux)
#  and the Upload Manager extension.

if(empty($_POST['name'])) {
	$_Oli->setPostVarsCookie(array('errorCode' => 'NAME_EMPTY'));
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}
else if(!$_Oli->verifyAuthKey()) {
	$_Oli->setPostVarsCookie(array('errorCode' => 'NOT_LOGGED'));
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}
else {
	$fileKey = $_Upload->uploadFile($_FILES['file'], $_POST['name'], $_Oli->getAuthKeyOwner());
	if(!empty($fileKey)) {
		$description = (!empty($_POST['description'])) ? $_POST['description'] : '';
		$nominative = ($_POST['nominative'] == 'public') ? true : false;
		$contentVisibility = (in_array($_POST['contentVisibility'], ['public', 'private'])) ? $_POST['contentVisibility'] : 'private';
		$sensitiveContent = ($_POST['sensitiveContent']) ? true : false;
		$contentPreview = ($_POST['contentPreview']) ? true : false;
		$downloadableContent = ($_POST['downloadableContent']) ? true : false;
		
		$_Upload->updateFileInfos(array('description' => $description, 'nominative' => $nominative, 'content_visibility' => $contentVisibility, 'sensitive_content' => $sensitiveContent, 'content_preview' => $contentPreview, 'downloadable_content' => $downloadableContent), array('file_key' => $fileKey));
		header('Location: ' . $_Oli->getOption('url') . 'preview/' . $fileKey);
	}
	else {
		$_Oli->setPostVarsCookie(array('errorCode' => 'UPLOAD_FAILED'));
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
}

// Overview of uploads table:
// (`id`, `name`, `description`, `owner`, `date`, `time`, `file_key`, `file_type`, `file_name`, `original_file_name`)
?>