<?php
/*\
|*|  --------------------------
|*|  --- [  MySQL Module  ] ---
|*|  --------------------------
|*|  
|*|  This is the MySQL module of Oli, an open source PHP framework
|*|  This module has been added to Oli in version Beta 1.7.1
|*|  
|*|  Copyright (C) 2016 Mathieu Guérin (aka "Matiboux")
|*|  Please see the OliCore.php file for more infos
\*/

namespace OliFramework {

class MySQL {

	/** MySQL databases */
	public $dbList = [];
	public $currentDb = null;
	
	/** --------------------- */
	/**  Database Management  */
	/** --------------------- */
	
	/** Add database */
	public function addDB($id, $database, $username = 'root', $password = '', $hostname = 'localhost', $switchTo = false) {
		try {
			$db = new \PDO('mysql:host=' . $hostname . ';dbname=' . $database . ';charset=utf8', $username, $password);
		}
		catch(PDOException $e) {
			trigger_error($e->getMessage(), E_USER_ERROR);
		}
		
		if($db) {
			if(!empty($id)) $this->dbList[$id] = $db;
			else $this->dbList[] = $db;
			
			if($switchTo) $this->currentDb = &$this->dbList[$id ?: end(array_keys($this->dbList, $db))];
			else return true;
		}
		else return false;
	}
	
	/** Rename database */
	public function renameDB($id, $newId) {
		
	}
	
	/** Remove database */
	public function removeDB($id) {
		
	}
	
	/** --------------------- */
	/**  Database Navigation  */
	/** --------------------- */
	
	/** Switch to databse */
	public function switchTo($id) {
		if(array_key_exists($id, $this->dbList)) $this->currentDb = &$this->dbList[$id];
		else return false;
	}

}

}
?>