<?php
define('MYSQL_DATABASE_NAME', 'ďąţąβą$€');
define('MYSQL_USERNAME', 'µ$€яɲąʍ€');
define('MYSQL_PASSWORD', 'ρą$$ώ๏яď');
define('MYSQL_HOST', 'h๏$ţ');

try {
	$db = new PDO('mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DATABASE_NAME, MYSQL_USERNAME, MYSQL_PASSWORD);
}
catch (Exception $e) {
	die('Erreur MySQL : ' . $e->getMessage());
}
?>