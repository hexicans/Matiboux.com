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

#  IMGSHOT:
#  UPLOAD SCRIPT

#  This Script has been made to work with
#  the framework OLI (by Matiboux)
#  and the Upload Manager extension.

#  $_POST:
#    -- NEEDED VARS --
#    > ['name']
#    > ['image']
#    -- Optional Vars --
#    > ['description']
#    > ['nominative']
#    > ['sensitiveContent']

$params = array(
	'name' => (!empty($_POST['name'])) ? $_POST['name'] : '',
	'description' => (!empty($_POST['description'])) ? $_POST['description'] : '',
	'nominative' => ($_POST['nominative'] == 'public') ? true : false,
	'sensitiveContent' => ($_POST['sensitiveContent']) ? true : false
);

if(empty($params['name'])) {
	$params['errorCode'] = 'UPLOAD_FAILED';
	$_Oli->setPostVarsCookie($params);
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}
else {
	if($_Oli->verifyAuthKey())
		$fileKey = $_Upload->uploadFile($_FILES['image'], $params['name'], $_Oli->getAuthKeyOwner());
	else
		$fileKey = $_Upload->uploadFile($_FILES['image'], $params['name']);
		
	if(!empty($fileKey)) {
		$_Upload->updateFileInfos(array('description' => $params['description'], 'nominative' => $params['nominative'], 'sensitive_content' => $params['sensitiveContent']), array('file_key' => $fileKey));
		header('Location: ' . $_Oli->getOption('url') . 'preview/' . $fileKey);
	}
	else {
		$params['errorCode'] = 'UPLOAD_FAILED';
		$_Oli->setPostVarsCookie($params);
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
}
?>