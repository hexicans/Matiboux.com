<?php
/*\
|*|
|*|  OLI FRAMEWORK
|*|    Error Manager
|*|
|*|  see Oli Core file for more info
|*|
\*/

namespace OliFramework\ErrorManager {

use \Exception; // Use Exception class
use \ErrorException; // Use ErrorException class

class ExceptionHandler {
	function __construct() {
		set_exception_handler([$this, 'exception_handler']);
	}
	function __destruct() {
		restore_exception_handler();
	}
	
	public function exception_handler($exception) {
		echo 'Exception: ' . $exception->getMessage();
	}
}

class ErrorHandler {
	function __construct() {
		set_error_handler([$this, 'error_handler']);
	}
	function __destruct() {
		restore_error_handler();
	}
	
	public function error_handler($code, $message, $filepath, $line) {
		if(!($code & error_reporting()))
			return false;
		
		$filename = substr($filepath, strrpos($filepath, '/', -1) + 1, strlen($filepath) - strrpos($filepath, '/', -1) - 1);
		if($code == E_ERROR)
			$errorType = "PHP Fatal Error";
		else if($code == E_WARNING)
			$errorType = "PHP Warning";
		else if($code == E_PARSE)
			$errorType = "PHP Parse";
		else if($code == E_NOTICE)
			$errorType = "PHP Notice";
		else if($code == E_USER_ERROR)
			$errorType = "Fatal Error";
		else if($code == E_USER_WARNING)
			$errorType = "Warning";
		else if($code == E_USER_NOTICE)
			$errorType = "Notice";
		else if($code == E_STRICT)
			$errorType = "Strict";
		else
			$errorType = "Unknown Error";
		
		echo $errorType . ': ' . $message . PHP_EOL;
		echo 'Error called in file "' . $filename . '" (' . $filepath . ') at line ' . $line;
		
		if($code == E_ERROR
		OR $code == E_USER_ERROR)
			die();
		else
			echo PHP_EOL;
	}
}

}
?>