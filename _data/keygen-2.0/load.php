<?php
require ABSPATH . 'includes/config.php';
require ABSPATH . 'includes/mysql.php';
require ABSPATH . 'includes/class.php';
require ABSPATH . 'includes/functions.php';

if(getUrlParam(1) == '' && file_exists(THEME . 'home.php')) {
	include THEME . 'home.php';
}
else if(file_exists(THEME . getUrlParam(1) . '.php')) {
	include THEME . getUrlParam(1) . '.php';
}
else if(file_exists(THEME . '404.php')) {
	include THEME . '404.php';
}
else {
	echo 'ERROR 404: FICHIER NON TROUVE';
}
?>