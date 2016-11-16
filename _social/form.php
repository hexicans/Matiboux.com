<?php
/** Load Oli Framework */
if(!defined('ABSPATH')) define('ABSPATH', dirname(__FILE__) . '/');
require_once ABSPATH . 'load.php';

/** ------ */

#  Oli Framework
#  Get Form Infos Script

$_Oli->setPostVarsCookie($_POST);
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>