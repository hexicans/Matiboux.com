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

#  Oli Framework
#  Get Form Infos Script

$_Oli->setPostVarsCookie($_POST);
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>