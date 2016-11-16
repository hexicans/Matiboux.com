<?php
/*\
|*|
|*|  OLI FRAMEWORK - BETA 1.6.6
|*|  Copyright Matiboux 2015
|*|
|*|  Created: 
|*|  Releases date:
|*|    PRE-DEV: 16 November 2014
|*|    ALPHA: 6 February 2015
|*|    BETA: July 2015
|*|      * No info on previous releases
|*|      * [version 1.5]: 17 August 2015
|*|              (1.5.1): 21 August 2015
|*|              (1.5.2): 25 August 2015
|*|              (1.5.3): 26 August 2015
|*|              (1.5.5): 20 November 2015
|*|      * [version 1.6]: 6 December 2015
|*|              (1.6.2): 9 December 2015
|*|              (1.6.3): 10 January 2016
|*|              (1.6.4): 10 February 2016
|*|              (1.6.5): 6 May 2016
|*|              (1.6.6): 2 June 2016
|*|      * [version 1.7]: X July 2016
|*|
|*|  Developper: Matiboux (http://matiboux.com/)
|*|
\*/

namespace OliFramework {

use \OliFramework\ErrorManager\ErrorHandler; // Use ErrorHandler class
use \OliFramework\ErrorManager\ExceptionHandler; // Use ExceptionHandler class
use \PDO; // Use PDO class (native PHP class)

class OliCore {
	
	/** ------------------ */
	/**  Oli Version Info  */
	/** ------------------ */
	
	const OLI_VERSION = 'BETA 1.6.6';
	const OLI_OWNER = 'Matiboux';
	const OLI_WEBSITE = ''; //'http://oli.matiboux.com/';
	
	/** --------------- */
	/**  Oli Variables  */
	/** --------------- */
	
	/** Setup Class Timestamp */
	public $setupClassTimestamp = null;
	
	/** Externals Class */
	public $db = null; // Database PDO Object
	public $ErrorHandler = null; // Oli Error Handler
	public $ExceptionHandler = null; // Oli Error Handler
	
	/** Tables Configuration */
	private $settingsTables = [];
	private $shortcutLinksTable = '';
	
	/** Content Type */
	private $currentContentType = '';
	private $defaultContentType = 'HTML';
	private $contentTypeHasBeenForced = false;
	
	/** Html Files Buffer List */
	private $HtmlLoaderList = [];
	
	/** CDN Url */
	private $cdnUrl = '';
	
	/** Translations & User Language */
	private $defaultUserLanguage = 'en';
	private $currentUserLanguage = '';
	private $translationsTable = '';
	
	/** Post Vars Cookie */
	private $postVarsProtection = false;
	private $postVarsCookieName = '';
	private $postVarsCookieExpireDelay = 1;
	private $postVarsCookieDomain = '';
	private $postVarsCookieSecure = false;
	private $postVarsCookieHttpOnly = false;
	
	/** -------------------- */
	/**  Accounts Variables  */
	/** -------------------- */
	
	/** Enable / Disable Accounts Management */
	private $accountsManagementStatus = false;
	
	/** Tables Configuration */
	private $accountsTable = '';
	private $accountsInfosTable = '';
	private $accountsSessionsTable = '';
	private $accountsRequestsTable = '';
	private $accountsPermissionsTable = '';
	private $accountsRightsTable = '';
	
	/** Hash Preferences */
	private $hashAlgorithm = PASSWORD_DEFAULT;
	private $hashSalt = '';
	private $hashCost = 0;
	
	/** Auth Key Cookie */
	private $authKeyCookieName = '';
	private $authKeyCookieDomain = '';
	private $authKeyCookieSecure = '';
	private $authKeyCookieHttpOnly = '';
	
	/** Register Verification Mode */
	private $registerVerification = false;
	private $requestsExpireDelay = 172800;
	private $defaultUserRight = 'USER';
	
	/** *** *** *** */
	
	/** --------------- */
	/**  Magic Methods  */
	/** --------------- */
	
	public function __construct() {
		$this->setupClassTimestamp = microtime(true);
		$this->ExceptionHandler = new ExceptionHandler;
		$this->ErrorHandler = new ErrorHandler;
		
		$this->setContentType('DEFAULT', 'utf-8');
		$this->setCurrentUserLanguage('DEFAULT');
	}
	public function __destruct() {
		$this->loadEndHtmlFiles();
	}
	
	/** Wrong Type on Object */
	public function __toString() {
		return 'Oli Framework version ' . self::OLI_VERSION . ' created by ' . self::OLI_OWNER . '.'; // Visit ' . self::OLI_WEBSITE . ' for more informations.';
	}
	
	/** *** *** *** */
	
	/** --------------- */
	/**  Configuration  */
	/** --------------- */
	
		/** ------------------ */
		/**  MySQL PDO Object  */
		/** ------------------ */
		
		/** Setup MySQL & Config */
		public function setupMySQL($database, $username = 'root', $password = '', $host = 'localhost') {
			try {
				$this->db = new PDO('mysql:host=' . $host . ';dbname=' . $database . ';charset=utf8', $username, $password);
			}
			catch(PDOException $e) {
				trigger_error($e->getMessage(), E_USER_ERROR);
			}
		}
		
		/** ------------------- */
		/**  Oli Configuration  */
		/** ------------------- */
		
			/** ------------------------- */
			/**  FIRST TIME LAUNCH SETUP  */
			/** ------------------------- */
			
			/** First Time Lauch Setup */
			// public function firstTimeLaunchSetup() {
			// }
		
			/** ---------------------- */
			/**  Tables Configuration  */
			/** ---------------------- */
			
			/** Set Settings Tables */
			public function setSettingsTables($tables) {
				if(is_array($tables))
					$this->settingsTables = $tables;
				else
					$this->settingsTables[] = $tables;
				
				// foreach($this->settingsTables as $eachTable) {
					// $this->createTableMySQL($eachTable, array('id' => 'int PRIMARY KEY NOT NULL AUTO_INCREMENT', 'name' => 'varchar(64)', 'value' => 'varchar(256)'));
					// if(!$this->isExistInfosMySQL($eachTable)) {
						// $this->insertLineMySQL($eachTable, array('id' => 1, 'name' => 'url', 'value' => $this->getUrlParam(0)));
						// $this->insertLineMySQL($eachTable, array('id' => 2, 'name' => 'name', 'value' => ''));
						// $this->insertLineMySQL($eachTable, array('id' => 3, 'name' => 'description', 'value' => ''));
						// $this->insertLineMySQL($eachTable, array('id' => 4, 'name' => 'version', 'value' => '1.0'));
						// $this->insertLineMySQL($eachTable, array('id' => 5, 'name' => 'creation_date', 'value' => date('Y-m-d')));
						// $this->insertLineMySQL($eachTable, array('id' => 6, 'name' => 'domain', 'value' => ''));
						// $this->insertLineMySQL($eachTable, array('id' => 7, 'name' => 'status', 'value' => 'active'));
					// }
				// }
				// die();
			}
			
			/** Set Shortcut Links Tables */
			public function setShortcutLinksTable($table) {
				$this->shortcutLinksTable = $table;
			}
		
			/** -------------- */
			/**  Content Type  */
			/** -------------- */
			
			/** Set Content Type */
			public function setDefaultContentType($defaultContentType) {
				$this->defaultContentType = $defaultContentType;
			}
			
			/** ------------------- */
			/**  CDN Configuration  */
			/** ------------------- */
		
			/** Set Common Files Url */
			public function setCdnUrl($url) {
				$this->cdnUrl = $url;
			}
			
			/** -------------------------------------------- */
			/**  Translations & User Language Configuration  */
			/** -------------------------------------------- */
			
			/** Set Default User Language */
			public function setDefaultUserLanguage($language = 'en') {
				$this->defaultUserLanguage = $language;
			}
			
			/** Set Translations Table */
			public function setTranslationsTable($table) {
				$this->translationsTable = $table;
			}
			
			/** -------------------------------- */
			/**  Post Vars Cookie Configuration  */
			/** -------------------------------- */
			
			/** Set Post Vars Cookie Name */
			public function setPostVarsCookieName($name) {
				$this->postVarsCookieName = $name;
			}
			/** Set Post Vars Cookie Expire Delay - Deprecated */
			public function setPostVarsCookieExpireDelay($delay) { 
				$this->postVarsCookieExpireDelay = $delay;
			}
			/** Set Post Vars Cookie Domain */
			public function setPostVarsCookieDomain($domain) {
				$this->postVarsCookieDomain = $domain;
			}
			/** Set Post Vars Cookie Secure Parameter */
			public function setPostVarsCookieSecure($secure) {
				$this->postVarsCookieSecure = $secure;
			}
			/** Set Post Vars Cookie Http Only Parameter */
			public function setPostVarsCookieHttpOnly($httponly) {
				$this->postVarsCookieHttpOnly = $httponly;
			}
			
			/** ------------------------- */
			/**  Time Zone Configuration  */
			/** ------------------------- */
		
			/** Set Time Zone */
			public function setTimeZone($timezone) {
				date_default_timezone_set($timezone);
			}
		
		/** ------------------------ */
		/**  Accounts Configuration  */
		/** ------------------------ */
		
			/** ---------------------- */
			/**  Tables Configuration  */
			/** ---------------------- */
			
			/** Set Main Accounts Table */
			public function setAccountsTable($table) {
				$this->accountsTable = $table;
			}
			/** Set Accounts Infos Table */
			public function setAccountsInfosTable($table) {
				$this->accountsInfosTable = $table;
			}
			/** Set Accounts Sessions Table */
			public function setAccountsSessionsTable($table) {
				$this->accountsSessionsTable = $table;
			}
			/** Set Accounts Requests Table */
			public function setAccountsRequestsTable($table) {
				$this->accountsRequestsTable = $table;
			}
			/** Set Accounts Infos Table */
			public function setAccountsPermissionsTable($table) {
				$this->accountsPermissionsTable = $table;
			}
			/** Set Accounts Rights Table */
			public function setAccountsRightsTable($table) {
				$this->accountsRightsTable = $table;
			}
			
			/** -------------------- */
			/**  Hash Configuration  */
			/** -------------------- */
			
			/** Set Hash Algorithm */
			public function setHashAlgorithm($algorithm) {
				$this->hashAlgorithm = $algorithm;
			}
			/** Set Hash Salt */
			public function setHashSalt($salt) {
				$this->hashSalt = $salt;
			}
			/** Set Hash Cost */
			public function setHashCost($cost) {
				$this->hashCost = $cost;
			}
			
			/** ------------------------------- */
			/**  Auth Key Cookie Configuration  */
			/** ------------------------------- */
			
			/** Set Auth Key Cookie Name */
			public function setAuthKeyCookieName($name) {
				$this->authKeyCookieName = $name;
			}
			/** Set Auth Key Cookie Domain */
			public function setAuthKeyCookieDomain($domain) {
				$this->authKeyCookieDomain = $domain;
			}
			/** Set Auth Key Cookie Secure Parameter */
			public function setAuthKeyCookieSecure($secure) {
				$this->authKeyCookieSecure = $secure;
			}
			/** Set Auth Key Cookie HttpOnly Parameter */
			public function setAuthKeyCookieHttpOnly($httponly) {
				$this->authKeyCookieHttpOnly = $httponly;
			}
			
			/** ----------------------- */
			/**  Register Verification  */
			/** ----------------------- */
			
			/** Enable / Disable Register Verification */
			public function enableRegisterVerification() {
				$this->registerVerification = true;
				$this->defaultUserRight = 'NEW-USER';
				return true;
			}
			public function disableRegisterVerification() {
				$this->registerVerification = false;
				$this->defaultUserRight = 'USER';
				return true;
			}
			
			/** Set Requests Expire Delay */
			public function setRequestsExpireDelay($expireDelay) {
				$this->requestsExpireDelay = $expireDelay;
			}
	
	/** *** *** *** */
	
	/** ----------------- */
	/**  MySQL Functions  */
	/** ----------------- */
	
		/** ---------------- */
		/**  Read Functions  */
		/** ---------------- */
	
		/** Is exist a PDO Connection */
		public function isSetupMySQL() {
			if(!empty($this->db))
				return true;
			else {
				trigger_error('L\'objet de connexion MySQL PDO n\'est pas défini', E_USER_ERROR);
				return false;
			}
		}
	
		/** Raw MySQL Object */
		public function getRawMySQL() {
			$this->isSetupMySQL(); // Check for PDO connection
			return $this->db;
		}
		
		/** Get All Data from Table */
		public function getDataMySQL($table, $parameters = '') {
			$this->isSetupMySQL(); // Check for PDO connection
			
			if(!empty($parameters))
				$query = $this->db->prepare('SELECT * FROM ' . $table . ' ' . $parameters);
			else
				$query = $this->db->prepare('SELECT * FROM ' . $table);
			
			if($query->execute())
				return $query->fetchAll();
			else
				return false;
		}
		
		/** Get First Info from Table */
		public function getFirstInfoMySQL($table, $whatVar, $rawResult = false) {
			$dataMySQL = $this->getDataMySQL($table);
			return (!is_array($dataMySQL[0][$whatVar]) AND is_array(unserialize($dataMySQL[0][$whatVar])) AND !$rawResult) ? unserialize($dataMySQL[0][$whatVar]) : $dataMySQL[0][$whatVar];
		}
		
		/** Get Last Info from Table */
		public function getLastInfoMySQL($table, $whatVar, $rawResult = false) {
			$dataMySQL = $this->getDataMySQL($table, 'ORDER BY id DESC');
			return (!is_array($dataMySQL[0][$whatVar]) AND is_array(unserialize($dataMySQL[0][$whatVar])) AND !$rawResult) ? unserialize($dataMySQL[0][$whatVar]) : $dataMySQL[0][$whatVar];
		}
		
		/** Get Lines from Table */
		public function getLinesMySQL($table, $where = [], $caseSensitive = true, $forceArray = false, $rawResult = false) {
			$dataMySQL = $this->getDataMySQL($table);
			$valueArray = [];
			$status = [];
			if(!empty($dataMySQL)) {
				foreach($dataMySQL as $eachLineKey => $eachLine) {
					$status[$eachLineKey] = [];
					if(!empty($where)) {
						$whereLineID = 0;
						foreach($where as $whereVar => $whereValue) {
							$whereLineID++;
							if($whereVar == '*') {
								foreach($eachLine as $eachKey => $eachValue) {
									if(is_array($whereValue)) {
										$eachValue = (!is_array($eachValue) AND is_array(unserialize($eachValue)) AND !$rawResult) ? unserialize($eachValue) : $eachValue;
										
										$status[$eachLineKey][$whereLineID] = $eachKey;
										foreach($whereValue as $eachWhereKey => $eachWhereValue) {
											$toCompare = (!$caseSensitive) ? strtolower($eachValue[$eachWhereKey]) : $eachValue[$eachWhereKey];
											$compareWith = (!$caseSensitive) ? strtolower($eachWhereValue) : $eachWhereValue;
											
											if($toCompare != $compareWith) {
												$status[$eachLineKey][$whereLineID] = false;
												break;
											}
										}
										
										if($status[$eachLineKey][$whereLineID] == $eachKey)
											break;
									}
									else {
										$toCompare = (!$caseSensitive) ? strtolower($eachValue) : $eachValue;
										$compareWith = (!$caseSensitive) ? strtolower($whereValue) : $whereValue;
										
										if($toCompare == $compareWith) {
											$status[$eachLineKey][$whereLineID] = $eachKey;
											break;
										}
										else
											$status[$eachLineKey][$whereLineID] = false;
									}
								}
							}
							else {
								if(is_array($whereValue)) {
									$eachLine[$whereVar] = (!is_array($eachLine[$whereVar]) AND is_array(unserialize($eachLine[$whereVar])) AND !$rawResult) ? unserialize($eachLine[$whereVar]) : $eachLine[$whereVar];
									
									$status[$eachLineKey][$whereLineID] = $whereVar;
									foreach($whereValue as $eachWhereKey => $eachWhereValue) {
										$toCompare = (!$caseSensitive) ? strtolower($eachLine[$whereVar][$eachWhereKey]) : $eachLine[$whereVar][$eachWhereKey];
										$compareWith = (!$caseSensitive) ? strtolower($eachWhereValue) : $eachWhereValue;
										
										if($toCompare != $compareWith) {
											$status[$eachLineKey][$whereLineID] = false;
											break;
										}
									}
									
									// if($status[$eachLineKey][$whereLineID] == $whereVar)
										// break;
								} else {
									$toCompare = (!$caseSensitive) ? strtolower($eachLine[$whereVar]) : $eachLine[$whereVar];
									$compareWith = (!$caseSensitive) ? strtolower($whereValue) : $whereValue;
									
									if($toCompare == $compareWith) {
										$status[$eachLineKey][$whereLineID] = $whereVar;
										// break;
									}
									else
										$status[$eachLineKey][$whereLineID] = false;
								}
							}
						}
					}
					
					if((!in_array(false, $status[$eachLineKey]) AND !empty($status[$eachLineKey])) OR empty($where)) {
						foreach($eachLine as $eachKey => $eachValue) {
							$eachLine[$eachKey] = (!is_array($eachValue) AND is_array(unserialize($eachValue)) AND !$rawResult) ? unserialize($eachValue) : $eachValue;
						}
						
						$valueArray[] = $eachLine;
					}
				}
			}
			else
				return false;
			
			if($forceArray OR count($valueArray) > 1)
				return $valueArray;
			else if(count($valueArray) == 1)
				return $valueArray[0];
			else
				return false;
		}
		
		/** Get Infos from Table */
		public function getInfosMySQL($table, $whatVar, $where = [], $caseSensitive = true, $forceArray = false, $rawResult = false) {
			$dataMySQL = $this->getDataMySQL($table);
			$valueArray = [];
			$status = [];
			if(!empty($dataMySQL)) {
				foreach($dataMySQL as $eachLineKey => $eachLine) {
					$status[$eachLineKey] = [];
					if(!empty($where)) {
						$whereLineID = 0;
						foreach($where as $whereVar => $whereValue) {
							$whereLineID++;
							if($whereVar == '*') {
								foreach($eachLine as $eachKey => $eachValue) {
									if(is_array($whereValue)) {
										$eachValue = (!is_array($eachValue) AND is_array(unserialize($eachValue)) AND !$rawResult) ? unserialize($eachValue) : $eachValue;
										
										$status[$eachLineKey][$whereLineID] = $eachKey;
										foreach($whereValue as $eachWhereKey => $eachWhereValue) {
											$toCompare = (!$caseSensitive) ? strtolower($eachValue[$eachWhereKey]) : $eachValue[$eachWhereKey];
											$compareWith = (!$caseSensitive) ? strtolower($eachWhereValue) : $eachWhereValue;
											
											if($toCompare != $compareWith) {
												$status[$eachLineKey][$whereLineID] = false;
												break;
											}
										}
										
										if($status[$eachLineKey][$whereLineID] == $eachKey)
											break;
									}
									else {
										$toCompare = (!$caseSensitive) ? strtolower($eachValue) : $eachValue;
										$compareWith = (!$caseSensitive) ? strtolower($whereValue) : $whereValue;
										
										if($toCompare == $compareWith) {
											$status[$eachLineKey][$whereLineID] = $eachKey;
											break;
										}
										else
											$status[$eachLineKey][$whereLineID] = false;
									}
								}
							}
							else {
								if(is_array($whereValue)) {
									$eachLine[$whereVar] = (!is_array($eachLine[$whereVar]) AND is_array(unserialize($eachLine[$whereVar])) AND !$rawResult) ? unserialize($eachLine[$whereVar]) : $eachLine[$whereVar];
									
									$status[$eachLineKey][$whereLineID] = $whereVar;
									foreach($whereValue as $eachWhereKey => $eachWhereValue) {
										$toCompare = (!$caseSensitive) ? strtolower($eachLine[$whereVar][$eachWhereKey]) : $eachLine[$whereVar][$eachWhereKey];
										$compareWith = (!$caseSensitive) ? strtolower($eachWhereValue) : $eachWhereValue;
										
										if($toCompare != $compareWith) {
											$status[$eachLineKey][$whereLineID] = false;
											break;
										}
									}
									
									// if($status[$eachLineKey][$whereLineID] == $whereVar)
										// break;
								} else {
									$toCompare = (!$caseSensitive) ? strtolower($eachLine[$whereVar]) : $eachLine[$whereVar];
									$compareWith = (!$caseSensitive) ? strtolower($whereValue) : $whereValue;
									
									if($toCompare == $compareWith) {
										$status[$eachLineKey][$whereLineID] = $whereVar;
										// break;
									}
									else
										$status[$eachLineKey][$whereLineID] = false;
								}
							}
						}
					}
					
					if((!in_array(false, $status[$eachLineKey]) AND !empty($status[$eachLineKey])) OR empty($where)) {
						$eachLine[$whatVar] = (!is_array($eachLine[$whatVar]) AND is_array(unserialize($eachLine[$whatVar])) AND !$rawResult) ? unserialize($eachLine[$whatVar]) : $eachLine[$whatVar];
						$valueArray[] = $eachLine[$whatVar];
					}
				}
			}
			else
				return false;
			
			if($forceArray OR count($valueArray) > 1)
				return $valueArray;
			else if(count($valueArray) == 1)
				return $valueArray[0];
			else
				return false;
		}
		
		/** Get Summed Infos from Table */
		public function getSummedInfosMySQL($table, $whatVar, $where = [], $caseSensitive = true) {
			$summedInfos = null;
			foreach($this->getInfosMySQL($table, $whatVar, $where, $caseSensitive, true) as $eachInfo) {
				$eachInfo = (!is_array($eachInfo) AND is_array(unserialize($eachInfo))) ? unserialize($eachInfo) : $eachInfo;
				$summedInfos += $eachInfo;
			}
			
			return (is_array($summedInfos) AND $rawResult) ? serialize($summedInfos) : $summedInfos;
		}
		
		/** Is Exist Infos from Table */
		public function isExistInfosMySQL($table, $where = [], $caseSensitive = true) {
			$dataMySQL = $this->getDataMySQL($table);
			$valueArray = [];
			$status = [];
			if(!empty($dataMySQL)) {
				foreach($dataMySQL as $eachLineKey => $eachLine) {
					$status[$eachLineKey] = [];
					if(!empty($where)) {
						$whereLineID = 0;
						foreach($where as $whereVar => $whereValue) {
							$whereLineID++;
							if($whereVar == '*') {
								foreach($eachLine as $eachKey => $eachValue) {
									if(is_array($whereValue)) {
										$eachValue = (!is_array($eachValue) AND is_array(unserialize($eachValue)) AND !$rawResult) ? unserialize($eachValue) : $eachValue;
										
										$status[$eachLineKey][$whereLineID] = $eachKey;
										foreach($whereValue as $eachWhereKey => $eachWhereValue) {
											$toCompare = (!$caseSensitive) ? strtolower($eachValue[$eachWhereKey]) : $eachValue[$eachWhereKey];
											$compareWith = (!$caseSensitive) ? strtolower($eachWhereValue) : $eachWhereValue;
											
											if($toCompare != $compareWith) {
												$status[$eachLineKey][$whereLineID] = false;
												break;
											}
										}
										
										if($status[$eachLineKey][$whereLineID] == $eachKey)
											break;
									}
									else {
										$toCompare = (!$caseSensitive) ? strtolower($eachValue) : $eachValue;
										$compareWith = (!$caseSensitive) ? strtolower($whereValue) : $whereValue;
										
										if($toCompare == $compareWith) {
											$status[$eachLineKey][$whereLineID] = $eachKey;
											break;
										}
										else
											$status[$eachLineKey][$whereLineID] = false;
									}
								}
							}
							else {
								if(is_array($whereValue)) {
									$eachLine[$whereVar] = (!is_array($eachLine[$whereVar]) AND is_array(unserialize($eachLine[$whereVar])) AND !$rawResult) ? unserialize($eachLine[$whereVar]) : $eachLine[$whereVar];
									
									$status[$eachLineKey][$whereLineID] = $whereVar;
									foreach($whereValue as $eachWhereKey => $eachWhereValue) {
										$toCompare = (!$caseSensitive) ? strtolower($eachLine[$whereVar][$eachWhereKey]) : $eachLine[$whereVar][$eachWhereKey];
										$compareWith = (!$caseSensitive) ? strtolower($eachWhereValue) : $eachWhereValue;
										
										if($toCompare != $compareWith) {
											$status[$eachLineKey][$whereLineID] = false;
											break;
										}
									}
									
									// if($status[$eachLineKey][$whereLineID] == $whereVar)
										// break;
								} else {
									$toCompare = (!$caseSensitive) ? strtolower($eachLine[$whereVar]) : $eachLine[$whereVar];
									$compareWith = (!$caseSensitive) ? strtolower($whereValue) : $whereValue;
									
									if($toCompare == $compareWith) {
										$status[$eachLineKey][$whereLineID] = $whereVar;
										// break;
									}
									else
										$status[$eachLineKey][$whereLineID] = false;
								}
							}
						}
					}
					
					if((!in_array(false, $status[$eachLineKey]) AND !empty($status[$eachLineKey])) OR empty($where))
						$valueArray[] = true;
				}
			}
			else
				return false;
			
			if(count($valueArray) >= 1)
				return count($valueArray);
			else
				return false;
		}
		
		/** ----------------- */
		/**  Write Functions  */
		/** ----------------- */
		
		/** Create new Table */
		public function createTableMySQL($table, $matches) {
			foreach($matches as $matchName => $matchOption) {
				$queryData[] = $matchName . ' ' . $matchOption;
			}
			$query = $this->db->prepare('CREATE TABLE ' . $table . '(' . implode(', ', $queryData) . ')');
			return $query->execute();
		}
		
		/** Clear Table (Delete only data) */
		public function clearTableMySQL($table) {
			$query = $this->db->prepare('TRUNCATE TABLE ' . $table);
			return $query->execute();
		}
		
		/** Delete Table */
		public function deleteTableMySQL($table) {
			$query = $this->db->prepare('DROP TABLE ' . $table);
			return $query->execute();
		}
		
		/** Add column in Table */
		public function addColumnTableMySQL($table, $matches) {
			foreach($matches as $matchName => $matchOption) {
				$queryData[] = $matchName . ' ' . $matchOption;
			}
			$query = $this->db->prepare('ALTER TABLE ' . $table . ' ADD ' . implode(', ', $queryData) . ')');
			return $query->execute();
		}
		
		/** Update column in Table */
		public function updateColumnTableMySQL($table, $matches) {
			foreach($matches as $matchName => $matchOption) {
				$queryData[] = $matchName . ' ' . $matchOption;
			}
			$query = $this->db->prepare('ALTER TABLE ' . $table . ' MODIFY ' . implode(', ', $queryData) . ')');
			return $query->execute();
		}
		
		/** Rename column in Table */
		public function renameColumnTableMySQL($table, $column, $matches) {
			foreach($matches as $matchName => $matchOption) {
				$queryData[] = $matchName . ' ' . $matchOption;
			}
			$query = $this->db->prepare('ALTER TABLE ' . $table . ' CHANGE ' . $column . ' ' . implode(', ', $queryData) . ')');
			return $query->execute();
		}
		
		/** Delete column in Table */
		public function deleteColumnTableMySQL($table, $column) {
			$query = $this->db->prepare('ALTER TABLE ' . $table . ' DROP ' . $column . ')');
			return $query->execute();
		}
		
		/** Insert Lines from Table */
		public function insertLineMySQL($table, $matches) {
			foreach($matches as $matchKey => $matchValue) {
				$queryVars[] = $matchKey;
				$queryValues[] = ':' . $matchKey;
				
				$matchValue = (is_array($matchValue)) ? serialize($matchValue) : $matchValue;
				$matches[$matchKey] = $matchValue;
			}
			$query = $this->db->prepare('INSERT INTO ' . $table . '(' . implode(', ', $queryVars) . ') VALUES(' . implode(', ', $queryValues) . ')');
			return $query->execute($matches);
		}
		
		/** Update Infos from Table */
		public function updateInfosMySQL($table, $what, $where) {
			$matches = [];
			foreach($what as $whatVar => $whatValue) {
				$queryWhat[] = $whatVar . ' = :what_' . $whatVar;
				
				$whatValue = (is_array($whatValue)) ? serialize($whatValue) : $whatValue;
				$matches['what_' . $whatVar] = $whatValue;
			}
			if($where != 'all') {
				foreach($where as $whereVar => $whereValue) {
					$queryWhere[] = $whereVar . ' = :where_' . $whereVar;
					
					$whereValue = (is_array($whereValue)) ? serialize($whereValue) : $whereValue;
					$matches['where_' . $whereVar] = $whereValue;
				}
			}
			$query = $this->db->prepare('UPDATE ' . $table . ' SET '  . implode(', ', $queryWhat) . (($where != 'all') ? ' WHERE ' . implode(' AND ', $queryWhere) : ''));
			return $query->execute($matches);
		}
		
		/** Delete Lines from Table */
		public function deleteLinesMySQL($table, $where) {
			if($where != 'all') {
				$matches = [];
				foreach($where as $whereVar => $whereValue) {
					$queryWhere[] = $whereVar . ' = :' . $whereVar;
					
					$whereValue = (is_array($whereValue)) ? serialize($whereValue) : $whereValue;
					$matches[$whereVar] = $whereValue;
				}
			}
			$query = $this->db->prepare('DELETE FROM ' . $table . (($where != 'all') ? ' WHERE ' . implode(' AND ', $queryWhere) : ''));
			return $query->execute($matches);
		}
	
	/** *** *** *** */
	
	/** --------------- */
	/**  Oli Functions  */
	/** --------------- */
	
		/** --------- */
		/**  General  */
		/** --------- */
		
		/** Get Option */
		public function getOption($option = '') {
			foreach($this->settingsTables as $eachTable) {
				if(!empty($option)) {
					$optionResult = $this->getInfosMySQL($eachTable, 'value', array('name' => $option));
					if(!empty($optionResult)) {
						if($optionResult == 'null')
							return '';
						else
							return $optionResult;
					}
				}
				else {
					foreach($this->getLinesMySQL($eachTable) as $eachValue) {
						if(empty($optionResult[$eachValue['name']]))
							$optionResult[$eachValue['name']] = $eachValue['value'];
					}
				}
			}
			
			foreach($optionResult as $eachKey => $eachValue) {
				if($eachValue == 'null')
					$optionResult[$eachKey] = '';
			}
			return $optionResult;
		}
		
		/** Get Status */
		public function getStatus() {
			return $this->getOption('status');
		}
		
		/** Get Shortcut Link */
		public function getShortcutLink($shortcut) {
			return $this->getInfosMySQL($this->shortcutLinksTable, 'url', array('name' => $shortcut));
		}
		
		/** Get Shortcut Link */
		public function getExecuteDelay($fromRequest = false) {
			if($fromRequest)
				return microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
			else
				return microtime(true) - $this->setupClassTimestamp;
		}
	
		/** --------------------- */
		/**  Translations & Text  */
		/** --------------------- */
		
			/** ------------------------------- */
			/**  Read Accounts Infos Functions  */
			/** ------------------------------- */
			
			/** Get Translation Lines */
			public function getTranslationLines($where, $caseSensitive = true, $forceArray = false, $rawResult = false) {
				return $this->getLinesMySQL($this->translationsTable, $where, $caseSensitive, $forceArray, $rawResult);
			}
			
			/** Get Translation */
			public function getTranslation($whatLanguage, $where, $caseSensitive = true, $forceArray = false, $rawResult = false) {
				return $this->getInfosMySQL($this->translationsTable, $whatLanguage, $where, $caseSensitive, $forceArray, $rawResult);
			}
			
			/** Is Exist Translation */
			public function isExistTranslation($where, $caseSensitive = true) {
				return $this->isExistInfosMySQL($this->translationsTable, $where, $caseSensitive);
			}
		
			/** -------------------------------- */
			/**  Write Accounts Infos Functions  */
			/** -------------------------------- */
			
			/** Insert Translation */
			public function insertTranslation($matches) {
				return $this->insertLineMySQL($this->translationsTable, $matches);
			}
			
			/** Update Translations */
			public function updateTranslations($what, $where) {
				return $this->updateInfosMySQL($this->translationsTable, $what, $where);
			}
			
			/** Update Account Infos */
			public function deleteTranslations($where) {
				return $this->deleteLinesMySQL($this->translationsTable, $where);
			}
		
			/** ------------------- */
			/**  Translation Tools  */
			/** ------------------- */
		
			/** Echo Translated Text */
			public function __($text, $text_plural = '', $count = 0) {
				$text = ($count > 1) ? $text_plural : $text;
				if($this->currentUserLanguage != $this->defaultUserLanguage AND $this->getTranslation($this->currentUserLanguage, array($this->defaultUserLanguage => $text)))
					echo $this->getTranslation($this->currentUserLanguage, array($this->defaultUserLanguage => $text));
				else {
					if(!$this->isExistTranslation(array($this->defaultUserLanguage => $text)))
						$this->insertTranslation(array($this->defaultUserLanguage => $text));
					echo $text;
				}
			}
		
		/** ---------------- */
		/**  HTTP Functions  */
		/** ---------------- */
		
			/** -------------- */
			/**  Content Type  */
			/** -------------- */
			
			/** Set Content Type */
			public function setContentType($contentType = null, $charset = 'utf-8', $force = false) {
				if(!$this->contentTypeHasBeenForced OR $force) {
					if($force)
						$this->contentTypeHasBeenForced = true;
					
					if(empty($contentType) OR $contentType == 'DEFAULT')
						$contentType = $this->defaultContentType;
					
					if($contentType == 'DEBUG_MODE')
						error_reporting(E_ALL);
					else
						error_reporting(E_ALL & ~E_NOTICE);
					
					if($contentType == 'HTML')
						$this->currentContentType = 'text/html';
					else if($contentType == 'CSS')
						$this->currentContentType = 'text/css';
					else if($contentType == 'JAVASCRIPT')
						$this->currentContentType = 'text/javascript';
					else if($contentType == 'JSON')
						$this->currentContentType = 'application/json';
					else if($contentType == 'PDF')
						$this->currentContentType = 'application/pdf';
					else if($contentType == 'RSS')
						$this->currentContentType = 'application/rss+xml';
					else if($contentType == 'XML')
						$this->currentContentType = 'text/xml';
					else if($contentType == 'DEBUG_MODE' OR $contentType == 'PLAIN')
						$this->currentContentType = 'text/plain';
					else
						return false;
					
					header('Content-Type: ' . $this->currentContentType . ';charset=' . $charset);
					return true;
				}
				else
					return false;
			}
			
			/** Get Current Content Type */
			public function getContentType() {
				return $this->currentContentType;
			}
			
			/** ------------------- */
			/**  Cookie Management  */
			/** ------------------- */
		
				/** -------------------------- */
				/**  Create and Delete Cookie  */
				/** -------------------------- */
				
				/** Set Cookie */
				public function setCookie($name, $value, $expireDelay, $path, $domain, $secure = false, $httpOnly = false) {
					$value = (is_array($value)) ? serialize($value) : $value;
					return setcookie($name, $value, time() + $expireDelay, '/', $domain, $secure, $httpOnly);
				} 
				
				/** Delete Cookie */
				public function deleteCookie($name, $path, $domain, $secure = false, $httpOnly = false) {
					return setcookie($name, null, -1, '/', $domain, $secure, $httpOnly);
				}
			
				/** ----------- */
				/**  Get Infos  */
				/** ----------- */
				
				/** Get Cookie Content */
				public function getCookieContent($name, $rawResult = false) {
					return (!is_array($_COOKIE[$name]) AND is_array(unserialize($_COOKIE[$name])) AND !$rawResult) ? unserialize($_COOKIE[$name]) : $_COOKIE[$name];
				}
				
				/** is Empty Cookie */
				public function isEmptyCookie($name) {
					return empty($this->getCookieContent($name, true));
				}
			
			/** ------------ */
			/**  _POST vars  */
			/** ------------ */
		
				/** -------------------------- */
				/**  Create and Delete Cookie  */
				/** -------------------------- */
				
				/** Set Post Vars Cookie */
				public function setPostVarsCookie($postVars) {
					$this->postVarsProtection = true;
					return $this->setCookie($this->postVarsCookieName, $postVars, $this->postVarsCookieExpireDelay, '/', $this->postVarsCookieDomain, $this->postVarsCookieSecure, $this->postVarsCookieHttpOnly);
				} 
				
				/** Delete Post Vars Cookie - Deprecated */
				public function deletePostVarsCookie() {
					if(!$this->postVarsProtection)
						return $this->deleteCookie($this->postVarsCookieName, '/', $this->postVarsCookieDomain, $this->postVarsCookieSecure, $this->postVarsCookieHttpOnly);
					else
						return false;
				} 
				
				/** Protect Post Vars Cookie */
				public function protectPostVarsCookie() {
					$this->postVarsProtection = true;
					return $this->setCookie($this->postVarsCookieName, $this->getRawPostVars(), $this->postVarsCookieExpireDelay, '/', $this->postVarsCookieDomain, $this->postVarsCookieSecure, $this->postVarsCookieHttpOnly);
				}
			
				/** ----------- */
				/**  Get Infos  */
				/** ----------- */
			
				/** Get Post Vars Cookie Name */
				public function getPostVarsCookieName() {
					return $this->postVarsCookieName;
				}
				
				/** Get Raw Post Vars */
				public function getRawPostVars() {
					return $this->getCookieContent($this->postVarsCookieName, true);
				}
				
				/** Get Post Vars */
				public function getPostVars($whatVar = null) {
					$postVars = $this->getCookieContent($this->postVarsCookieName);
					return (!empty($whatVar)) ? $postVars[$whatVar] : $postVars;
				}
				
				/** is Empty Post Vars */
				public function isEmptyPostVars() {
					return $this->isEmptyCookie($this->postVarsCookieName);
				}
				
				/** is Protected Post Vars Cookie */
				public function isProtectedPostVarsCookie() {
					return $this->postVarsProtection;
				}
		
		/** ---------------- */
		/**  HTML Functions  */
		/** ---------------- */
		
		/** Load Content */
		public function loadContent($fileName) {
			if(!empty($this->getUserLanguage()))
				$this->setCurrentUserLanguage($this->getUserLanguage());
			
			if((empty($fileName) OR $fileName == 'home')
			AND file_exists(THEMEPATH . 'index.php'))
				return THEMEPATH . 'index.php';
			else if(file_exists(THEMEPATH . $fileName . '.php'))
				return THEMEPATH . $fileName . '.php';
			else if(file_exists(THEMEPATH . '404.php'))
				return THEMEPATH . '404.php';
			else {
				trigger_error('ERROR 404: FILE ASSOCIATED TO "' . $fileName . '" NOT FOUND', E_USER_ERROR);
				return false;
			}
		}
		
		/** Load CSS */
		public function loadLocalStyle($url, $loadNow = true, $minimize = false) {
			if($minimize) {
				$styleFile = @fopen($this->getDataUrl() . $url, 'r');
				$styleCode = '';
				
				if ($styleFile) {
					while (($buffer = fgets($styleFile, 4096)) !== false) {
						$styleCode = $styleCode . $buffer;
					}
					
					if (feof($styleFile))
						$codeLine = '<style type="text/css">' . $this->minimizeStyleSize($styleCode) . '</style>';
					else 
						trigger_error('Error: fgets() has failed', E_USER_ERROR);
					
					fclose($styleFile);
				}
			}
			else
				$codeLine = '<link rel="stylesheet" type="text/css" href="' . $this->getDataUrl() . $url . '">';
			
			if($loadNow)
				echo $codeLine . PHP_EOL;
			else
				$this->HtmlLoaderList[] = $codeLine;
			return true;
		}
		public function loadCdnStyle($url, $loadNow = true, $minimize = false) {
			if($minimize) {
				$styleFile = @fopen($this->getCdnUrl() . $url, 'r');
				$styleCode = '';
				
				if ($styleFile) {
					while (($buffer = fgets($styleFile, 4096)) !== false) {
						$styleCode = $styleCode . $buffer;
					}
					
					if (feof($styleFile))
						$codeLine = '<style type="text/css">' . $this->minimizeStyleSize($styleCode) . '</style>';
					else 
						trigger_error('Error: fgets() has failed', E_USER_ERROR);
					
					fclose($styleFile);
				}
			}
			else
				$codeLine = '<link rel="stylesheet" type="text/css" href="' . $this->getCdnUrl() . $url . '">';
			
			if($loadNow)
				echo $codeLine . PHP_EOL;
			else
				$this->HtmlLoaderList[] = $codeLine;
			return true;
		}
		
		/** Load Script */
		public function loadLocalScript($url, $loadNow = true, $minimize = false) {
			if($minimize) {
				$styleFile = @fopen($this->getDataUrl() . $url, 'r');
				$styleCode = '';
				
				if ($styleFile) {
					while (($buffer = fgets($styleFile, 4096)) !== false) {
						$styleCode = $styleCode . $buffer;
					}
					
					if (feof($styleFile))
						$codeLine = '<script type="text/javascript">' . $this->minimizeScriptSize($styleCode) . '</script>';
					else 
						trigger_error('Error: fgets() has failed', E_USER_ERROR);
					
					fclose($styleFile);
				}
			}
			else
				$codeLine = '<script type="text/javascript" src="' . $this->getDataUrl() . $url . '"></script>';
			
			if($loadNow)
				echo $codeLine . PHP_EOL;
			else
				$this->HtmlLoaderList[] = $codeLine;
			return true;
		}
		public function loadCdnScript($url, $loadNow = true, $minimize = false) {
			if($minimize) {
				$styleFile = @fopen($this->getCdnUrl() . $url, 'r');
				$styleCode = '';
				
				if ($styleFile) {
					while (($buffer = fgets($styleFile, 4096)) !== false) {
						$styleCode = $styleCode . $buffer;
					}
					
					if (feof($styleFile))
						$codeLine = '<script type="text/javascript">' . $this->minimizeScriptSize($styleCode) . '</script>';
					else 
						trigger_error('Error: fgets() has failed', E_USER_ERROR);
					
					fclose($styleFile);
				}
			}
			else
				$codeLine = '<script type="text/javascript" src="' . $this->getCdnUrl() . $url . '"></script>';
			
			if($loadNow)
				echo $codeLine . PHP_EOL;
			else
				$this->HtmlLoaderList[] = $codeLine;
			return true;
		}
		
		/** Load End Files */
		public function loadEndHtmlFiles() {
			echo PHP_EOL;
			foreach($this->HtmlLoaderList as $eachCodeLine) {
				echo array_shift($this->HtmlLoaderList) . PHP_EOL;
			}
			return true;
		}
		
		/** Minimize Style Size */
		public function minimizeStyleSize($styleCode) {
			$styleCode = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $styleCode);
			$styleCode = preg_replace('!\s+!', ' ', $styleCode);
			$styleCode = str_replace(': ', ':', $styleCode);
			$styleCode = str_replace(["\r\n", "\r", "\n", "\t"], '', $styleCode);
			$styleCode = str_replace(';}', '}', $styleCode);
			return $styleCode;
		}
		/** Minimize Script Size */
		public function minimizeScriptSize($scriptCode) {
			$scriptCode = preg_replace('!^[ \t]*/\*.*?\*/[ \t]*[\r\n]!s', '', $scriptCode);
			$scriptCode = preg_replace('![ \t]*[^:]//.*[ \t]*[\r\n]?!', '', $scriptCode);
			$scriptCode = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $scriptCode);
			$scriptCode = preg_replace('!\s+!', ' ', $scriptCode);
			$scriptCode = str_replace([' {', ' }', '{ ', '; '], ['{', '}', '{', ';'], $scriptCode);
			$scriptCode = str_replace(["\r\n", "\r", "\n", "\t"], '', $scriptCode);
			return $scriptCode;
		}
		
		/** -------------------- */
		/**  URL Read Functions  */
		/** -------------------- */
		
		/** Get Full Url */
		public function getFullUrl() {
			return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		}
		
		/** Get Url Parameters */
		//  null => Full Url
		// 'all' => All Param in array
		//     0 => Url without any parameters
		//     1 => First Parameter : Filename
		//     # => Others Parameters
		public function getUrlParam($param = null) {
			if((!is_integer($param) AND empty($param)) OR $param < 0)
				return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			else {
				$frationnedUrl = explode('/', $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
				$newFrationnedUrl = [];
				$tempHomeUrl = 'http://';
				
				$countForeachLoop = 0;
				foreach($frationnedUrl as $eachPart) {
					if($tempHomeUrl != $this->getOption('url')) {
						$tempHomeUrl .= $eachPart . '/';
						$countForeachLoop++;
					}
				}
				if($tempHomeUrl != $this->getOption('url'))
					$tempHomeUrl = 'http://' . $_SERVER['HTTP_HOST'];
				
				$countWhileLoop = $countForeachLoop - 1;
				$firstWhileLoop = true;
				while(!empty($frationnedUrl[$countWhileLoop])) {
					if($firstWhileLoop)
						$newFrationnedUrl[] = $tempHomeUrl;
					else
						$newFrationnedUrl[] = $frationnedUrl[$countWhileLoop];
					
					$firstWhileLoop = false; // First Loop Done
					$countWhileLoop++;
				}
				
				if(is_string($param) AND $param == 'all')
					return $newFrationnedUrl;
				else if(!empty($newFrationnedUrl[$param]))
					return $newFrationnedUrl[$param];
				else
					return false;
			}
		}
		
		/** Get Url to Content Data Files */
		public function getDataUrl() {
			return $this->getOption('url') . 'content/theme/data/';
		}
		
		/** Get Url to Content Media Files */
		public function getMediaUrl() {
			return $this->getOption('url') . 'content/media/';
		}
		
		/** Get Url to CDN Files */
		public function getCdnUrl() {
			return $this->cdnUrl;
		}
		
		/** -------------------------- */
		/**  User Language Management  */
		/** -------------------------- */
		
		/** Get Default User Language */
		public function getDefaultUserLanguage() {
			return $this->defaultUserLanguage;
		}
		
		/** Get Current User Language */
		public function setCurrentUserLanguage($language = 'DEFAULT') {
			$this->currentUserLanguage = (!empty($language) AND $language != 'DEFAULT') ? $language : $this->defaultUserLanguage;
			return true;
		}
		
		/** Get Current User Language */
		public function getCurrentUserLanguage() {
			return $this->currentUserLanguage;
		}
		
		/** Get User Language */
		public function setUserLanguage($language = 'DEFAULT', $where = []) {
			$language = (!empty($language) AND $language != 'DEFAULT') ? $language : $this->defaultUserLanguage;
			if(empty($where)) {
				if($this->verifyAuthKey())
					$where = array('username' => $this->getAuthKeyOwner());
				else
					return false;
			}
			
			if($this->updateAccountInfos('ACCOUNTS', array('language' => $language), $where)) {
				$this->currentUserLanguage = $language;
				return true;
			}
			else
				return false;
		}
		
		/** Get User Language */
		public function getUserLanguage($where = [], $caseSensitive = true, $forceArray = false, $rawResult = false) {
			if(empty($where) AND $this->verifyAuthKey())
				$where = array('username' => $this->getAuthKeyOwner());
			else
				return false;
			
			return $this->getAccountInfos('ACCOUNTS', 'language', $where, $caseSensitive, $forceArray, $rawResult);
		}
		
		/** ------- */
		/**  Tools  */
		/** ------- */
		
			/** ---------------------- */
			/**  Generators Functions  */
			/** ---------------------- */
			
			/** Generate random number */
			public function randomNumber($minimal = 1, $maximal = 100) {
				return mt_rand($minimal, $maximal);
			}
			
			/** Generate random secure key */
			public function keygen($length = 12, $numeric = true, $lowercase = true, $uppercase = true, $special = false) {
				$charactersAllowed = '';
				$key = '';
				
				if($numeric) $charactersAllowed .= '1234567890';
				if($lowercase) $charactersAllowed .= 'abcdefghijklmnopqrstuvwxyz';
				if($uppercase) $charactersAllowed .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
				if($special) $charactersAllowed .= '@*-+()!\':;?~`|{}^_=[]'; // Banned characters for URL Param use : €"\/•√π÷×£¥$°™®©¶<>
				
				if($charactersAllowed == '' OR $length == null OR $length == '' OR $length <= 0)
					return false;
				else {
					$countWhileLoop = 0;
					while($countWhileLoop < $length) {
						$randomCharacter = substr($charactersAllowed, $this->randomNumber(0, strlen($charactersAllowed) - 1), 1);
						
						if(!strstr($key, $randomCharacter) OR $length > strlen($charactersAllowed)) { // If the character is not already in the key...
							$key .= $randomCharacter;
							$countWhileLoop++;
						}
					}
					
					return $key;
				}
			}
		
			/** ----------------------- */
			/**  Date & Time Functions  */
			/** ----------------------- */
			
			/** Difference between two date */
			public function dateDifference($startDate, $endDate, $precise, $details = true) {
				if(is_string($startDate))
					$startDate = strtotime($startDate);
				if(is_string($endDate))
					$endDate = strtotime($endDate);
				
				$difference = abs($startDate - $endDate);
				$buffer = $difference;
				
				$results['total_seconds'] = $buffer;
				$results['seconds'] = $buffer % 60;
				
				$buffer = floor(($buffer - $results['seconds']) / 60);
				$results['total_minutes'] = $buffer;
				$results['minutes'] = $buffer % 60;
				
				$buffer = floor(($buffer - $results['minutes']) / 60);
				$results['total_hours'] = $buffer;
				$results['total_hours'] = $buffer;
				$results['hours'] = $buffer % 24;
				
				$buffer = floor(($buffer - $results['hours']) / 24);
				$results['total_days'] = $buffer;
				$results['days'] = $buffer % 365.25;
				
				$buffer = floor(($buffer - $results['months']) / 365.25);
				$results['years'] = $buffer;
				
				if($precise) {
					if(!empty($results['years']))
						return array('years' => $results['years'], 'days' => $results['days'], 'hours' => $results['hours'], 'minutes' => $results['minutes'], 'seconds' => $results['seconds']);
					else if(!empty($results['days']))
						return array('days' => $results['total_days'], 'hours' => $results['hours'], 'minutes' => $results['minutes'], 'seconds' => $results['seconds']);
					else if(!empty($results['hours']))
						return array('hours' => $results['total_hours'], 'minutes' => $results['minutes'], 'seconds' => $results['seconds']);
					else if(!empty($results['minutes']))
						return array('minutes' => $results['total_minutes'], 'seconds' => $results['seconds']);
					else
						return array('seconds' => $results['total_seconds']);
				}
				else {
					if($details) {
						if(!empty($results['years']))
							return array('years' => $results['years']);
						else if(!empty($results['total_days']))
							return array('days' => $results['total_days']);
						else if(!empty($results['total_hours']))
							return array('hours' => $results['total_hours']);
						else if(!empty($results['total_minutes']))
							return array('minutes' => $results['total_minutes']);
						else
							return array('seconds' => $results['total_seconds']);
					}
					else {
						if(!empty($results['years']))
							return $results['years'];
						else if(!empty($results['total_days']))
							return $results['total_days'];
						else if(!empty($results['total_hours']))
							return $results['total_hours'];
						else if(!empty($results['total_minutes']))
							return $results['total_minutes'];
						else
							return $results['total_seconds'];
					}
				}
			}
			
			/** ----------------------- */
			/**  Client Infos Functions */
			/** ----------------------- */
			
			/** Get User IP Address */
			public function getUserIP() {
				if($_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
					if(!empty($_SERVER['REMOTE_ADDR'])) $client_ip = $_SERVER['REMOTE_ADDR'];
					else if(!empty($_ENV['REMOTE_ADDR'])) $client_ip = $_ENV['REMOTE_ADDR'];
					else $client_ip = 'unknown';

					$entries = preg_split('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']);

					reset($entries);
					while(list(, $entry) = each($entries)) {
						$entry = trim($entry);
						if(preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list)){
							// http://www.faqs.org/rfcs/rfc1918.html
							$private_ip = [
								'/^0\./',
								'/^127\.0\.0\.1/',
								'/^192\.168\..*/',
								'/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/',
								'/^10\..*/'];

							$found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);

							if($client_ip != $found_ip) {
								$client_ip = $found_ip;
								break;
							}
						}
					}
				}
				else {
					if(!empty($_SERVER['REMOTE_ADDR'])) $client_ip = $_SERVER['REMOTE_ADDR'];
					else if(!empty($_ENV['REMOTE_ADDR'])) $client_ip = $_ENV['REMOTE_ADDR'];
					else $client_ip = 'unknown';
				}
				
				return $client_ip;
			}
	
	/** -------------------- */
	/**  Accounts Functions  */
	/** -------------------- */
	
		/** -------------------------------------- */
		/**  Enable / Disable Accounts Management  */
		/** -------------------------------------- */
		
		/** Enable / Disable Accounts Management */
		public function enableAccountsManagement() {
			$this->accountsManagementStatus = true;
			return true;
		}
		public function disableAccountsManagement() {
			$this->accountsManagementStatus = false;
			return true;
		}
		
		/** Is Accounts Management Enabled */
		public function getAccountsManagementStatus() {
			return $this->accountsManagementStatus;
		}
		
		/** ----------------- */
		/**  MySQL Functions  */
		/** ----------------- */
	
			/** ----------------------- */
			/**  Translate Table Codes  */
			/** ----------------------- */
			
			/*\
			|*|
			|*|  Table Codes :
			|*|  - ACCOUNTS - User Accounts List
			|*|  - INFOS - User Accounts Additional Infos
			|*|  - PERMISSIONS - User Permissions
			|*|  - RIGHTS - User Rights List
			|*|  - SESSIONS - User Accounts (login) Sessions
			|*|  - REQUESTS - User Accounts Requests
			|*|
			\*/
			
			/** Translate Table Code */
			public function translateAccountsTableCode($tableCode) {
				if($tableCode == 'ACCOUNTS')
					return $this->accountsTable;
				else if($tableCode == 'INFOS')
					return $this->accountsInfosTable;
				else if($tableCode == 'SESSIONS')
					return $this->accountsSessionsTable;
				else if($tableCode == 'REQUESTS')
					return $this->accountsRequestsTable;
				else if($tableCode == 'PERMISSIONS')
					return $this->accountsPermissionsTable;
				else if($tableCode == 'RIGHTS')
					return $this->accountsRightsTable;
				else 
					return false;
			}
		
			/** ------------------------------- */
			/**  Read Accounts Infos Functions  */
			/** ------------------------------- */
			
			/** Get Lines from Accounts Table */
			public function getAccountLines($tableCode, $where = [], $caseSensitive = true, $forceArray = false, $rawResult = false) {
				return $this->getLinesMySQL($this->translateAccountsTableCode($tableCode), $where, $caseSensitive, $forceArray, $rawResult);
			}
			
			/** Get Infos from Accounts Table */
			public function getAccountInfos($tableCode, $whatVar, $where = [], $caseSensitive = true, $forceArray = false, $rawResult = false) {
				return $this->getInfosMySQL($this->translateAccountsTableCode($tableCode), $whatVar, $where, $caseSensitive, $forceArray, $rawResult);
			}
			
			/** Is Exist Infos from Accounts Table */
			public function isExistAccountInfos($tableCode, $where, $caseSensitive = true) {
				return $this->isExistInfosMySQL($this->translateAccountsTableCode($tableCode), $where, $caseSensitive);
			}
		
			/** -------------------------------- */
			/**  Write Accounts Infos Functions  */
			/** -------------------------------- */
			
			/** Update Account Infos */
			public function updateAccountInfos($tableCode, $what, $where) {
				return $this->updateInfosMySQL($this->translateAccountsTableCode($tableCode), $what, $where);
			}
			
			/** Update Account Infos */
			public function deleteAccountLines($tableCode, $where) {
				return $this->deleteLinesMySQL($this->translateAccountsTableCode($tableCode), $where);
			}
			
			/** Delete Full Account */
			public function deleteFullAccount($where) {
				$this->deleteAccountLines('ACCOUNTS', $where);
				$this->deleteAccountLines('INFOS', $where);
				$this->deleteAccountLines('SESSIONS', $where);
				$this->deleteAccountLines('REQUESTS', $where);
				return true;
			}
		
			/** ---------------------- */
			/**  User Right Functions  */
			/** ---------------------- */
			
			/** Verify User Right */
			public function verifyUserRight($userRight, $caseSensitive = true) {
				return $this->isExistAccountInfos('RIGHTS', array('user_right' => $userRight), $caseSensitive);
			}
			
			/** Verify User Right */
			public function translateUserRight($userRight, $caseSensitive = true) {
				if(!empty($this->getAccountInfos('RIGHTS', 'id', array('user_right' => $userRight), false)))
					return $this->getAccountInfos('RIGHTS', 'id', array('user_right' => $userRight), false);
				else if(!empty($this->getAccountInfos('RIGHTS', 'id', array('acronym' => $userRight), false)))
					return $this->getAccountInfos('RIGHTS', 'id', array('acronym' => $userRight), false);
				else if(!empty($this->getAccountInfos('RIGHTS', 'user_right', array('id' => $userRight), false)))
					return $this->getAccountInfos('RIGHTS', 'user_right', array('id' => $userRight), false);
				else
					return false;
			}
			
			/** Get User Right */
			public function getUserRight($where = [], $caseSensitive = true) {
				if(empty($where) AND $this->verifyAuthKey())
					$where = array('username' => $this->getAuthKeyOwner());
				
				return $this->getAccountInfos('ACCOUNTS', 'user_right', $where, $caseSensitive);
			}
			public function getUserRightLevel($where = [], $caseSensitive = true) {
				return $this->translateUserRight($this->getUserRight($where, $caseSensitive), false);
			}
			public function getUserRightPermissions($where = [], $caseSensitive = true) {
				return $this->getAccountInfos('RIGHTS', 'permissions', array('user_right' => $this->getUserRight($where, $caseSensitive)), false);
			}
			
			/** Update User Right */
			public function updateUserRight($userRight, $where = [], $caseSensitive = true) {
				$userRight = strtoupper($userRight);
				if($this->verifyUserRight($userRight))
					return $this->updateAccountInfos('ACCOUNTS', array('user_right' => $userRight), $where, $caseSensitive);
				else
					return false;
			}
		
			/** ----------------------- */
			/**  Permissions Functions  */
			/** ----------------------- */
		
				/** --------- */
				/**  General  */
				/** --------- */
				
				/** Is User Permitted */
				public function isUserPermitted($permission) {
					
				}
		
				/** -------------------- */
				/**  Rights Permissions  */
				/** -------------------- */
				
				/** Set Right Permissions */
				public function setRightPermissions($permissions, $userRight) {
					
				}
				
				/** Add Right Permissions */
				public function addRightPermissions($permissions, $userRight) {
					
				}
				
				/** Remove Right Permissions */
				public function removeRightPermissions($permissions, $userRight) {
					
				}
				
				/** Delete Right Permissions */
				public function deleteRightPermissions($userRight) {
					
				}
				
				/** Is Right Permitted */
				public function isRightPermitted($permission) {
					
				}
		
				/** ------------------ */
				/**  User Permissions  */
				/** ------------------ */
				
				/** Set User Permissions */
				public function setUserPermissions($permissions, $userRight) {
					
				}
				
				/** Add User Permissions */
				public function addUserPermissions($permissions, $userRight) {
					
				}
				
				/** Remove User Permissions */
				public function removeUserPermissions($permissions, $userRight) {
					
				}
				
				/** Delete User Permissions */
				public function deleteUserPermissions($userRight) {
					
				}
		
		/** ---------------------------- */
		/**  Auth Key Cookie Management  */
		/** ---------------------------- */
		
			/** ------------------- */
			/**  Create and Delete  */
			/** ------------------- */
			
			/** Set Auth Key Cookie */
			public function setAuthKeyCookie($authKey, $cookie_duration) {
				if(!$this->accountsManagementStatus)
					trigger_error('La gestion de compte n\'est pas activée', E_USER_ERROR);
				else
					return $this->setCookie($this->authKeyCookieName, $authKey, $cookie_duration, '/', $this->authKeyCookieDomain, $this->authKeyCookieSecure, $this->authKeyCookieHttpOnly);
			}
			
			/** Delete Auth Key Cookie */
			public function deleteAuthKeyCookie() {
				if(!$this->accountsManagementStatus)
					trigger_error('La gestion de compte n\'est pas activée', E_USER_ERROR);
				else
					return $this->deleteCookie($this->authKeyCookieName, '/', $this->authKeyCookieDomain, $this->authKeyCookieSecure, $this->authKeyCookieHttpOnly);
			}
		
			/** ----------- */
			/**  Get Infos  */
			/** ----------- */
		
			/** Get Auth Key Cookie Name */
			public function getAuthKeyCookieName() {
				return $this->authKeyCookieName;
			}
			
			/** Get Auth Key (Cookie Value) */
			public function getAuthKey() {
				return $this->getCookieContent($this->authKeyCookieName);
			}
		
			/** Verify Auth Key  */
			public function verifyAuthKey($authKey = null) {
				$authKey = (!empty($authKey)) ? $authKey : $this->getAuthKey();
				if(!empty($authKey) AND $this->isExistAccountInfos('SESSIONS', array('auth_key' => $authKey)) AND strtotime($this->getAccountInfos('SESSIONS', 'expire_date', array('auth_key' => $authKey))) >= time()) {
					$this->updateAccountInfos('SESSIONS', array('update_date' => date('Y-m-d H:i:s')), array('auth_key' => $authKey));
					return true;
				}
				else
					return false;
			}
		
			/** Get Auth Key Owner */
			public function getAuthKeyOwner($authKey = null) {
				$authKey = (!empty($authKey)) ? $authKey : $this->getAuthKey();
				if($this->verifyAuthKey($authKey))
					return $this->getAccountInfos('SESSIONS', 'username', array('auth_key' => $authKey));
				else
					return false;
			}
		
		/** ---------------- */
		/**  Login Requests  */
		/** ---------------- */
		
			/** ------------------------------- */
			/**  Requests Management Functions  */
			/** ------------------------------- */
		
			/** Get Register Verification Status */
			public function getRequestsExpireDelay() {
				return $this->requestsExpireDelay;
			}
			
			/** Create new Request */
			public function createRequest($username, $action) {
				$activateKey = $this->keygen(6, false, true, true);
				
				$requestsMatches['id'] = $this->getLastInfoMySQL($this->accountsRequestsTable, 'id') + 1;
				$requestsMatches['username'] = $username;
				$requestsMatches['activate_key'] = $activateKey;
				$requestsMatches['action'] = $action;
				$requestsMatches['request_date'] = date('Y-m-d H:i:s');
				$requestsMatches['expire_date'] = date('Y-m-d H:i:s', time() + $this->requestsExpireDelay);
				$this->insertLineMySQL($this->accountsRequestsTable, $requestsMatches);
				
				return $activateKey;
			}
		
			/** -------------------- */
			/**  Register Functions  */
			/** -------------------- */
		
			/** Get Register Verification Status */
			public function getRegisterVerificationStatus() {
				return $this->registerVerification;
			}
			
			/** Register new Account */
			public function registerAccount($username, $password, $email) {
				if(!$this->accountsManagementStatus)
					trigger_error('La gestion de compte n\'est pas activée', E_USER_ERROR);
				else {
					if($this->isExistAccountInfos('ACCOUNTS', array('username' => $username), false) AND $this->getUserRightLevel(array('username' => $username)) == $this->translateUserRight('NEW-USER') AND (($this->isExistAccountInfos('REQUESTS', array('username' => $username), false) AND strtotime($this->getAccountInfos('REQUESTS', 'expire_date', array('username' => $username))) < time()) OR !$this->isExistAccountInfos('REQUESTS', array('username' => $username), false)))
						$this->deleteFullAccount(array('username' => $username));
					else if($this->isExistAccountInfos('ACCOUNTS', array('email' => $email), false) AND $this->getUserRightLevel(array('email' => $email)) == $this->translateUserRight('NEW-USER') AND (($this->isExistAccountInfos('REQUESTS', array('username' => $this->getAccountInfos('ACCOUNTS', 'username', array('email' => $email))), false) AND strtotime($this->getAccountInfos('REQUESTS', 'expire_date', array('username' => $this->getAccountInfos('ACCOUNTS', 'username', array('email' => $email))))) < time()) OR !$this->isExistAccountInfos('REQUESTS', array('username' => $this->getAccountInfos('ACCOUNTS', 'username', array('email' => $email))), false)))
						$this->deleteFullAccount(array('email' => $email));
					
					if(!$this->isExistAccountInfos('ACCOUNTS', array('username' => $username), false)
					AND !$this->isExistAccountInfos('ACCOUNTS', array('email' => $email), false)) {
						if($this->isExistAccountInfos('INFOS', array('username' => $username), false))
							$this->deleteAccountLines('INFOS', array('username' => $username));
						if($this->isExistAccountInfos('SESSIONS', array('username' => $username), false))
							$this->deleteAccountLines('SESSIONS', array('username' => $username));
						if($this->isExistAccountInfos('REQUESTS', array('username' => $username), false))
							$this->deleteAccountLines('REQUESTS', array('username' => $username));
						
						$accountsMatches['id'] = $this->getLastInfoMySQL($this->accountsTable, 'id') + 1;
						$accountsMatches['username'] = $username;
						$accountsMatches['password'] = $this->hashPassword($password);
						$accountsMatches['email'] = $email;
						$accountsMatches['register_date'] = date('Y-m-d H:i:s');
						$accountsMatches['user_right'] = $this->defaultUserRight;
						$this->insertLineMySQL($this->accountsTable, $accountsMatches);
						
						$infosMatches['id'] = $this->getLastInfoMySQL($this->accountsInfosTable, 'id') + 1;
						$infosMatches['username'] = $username;
						$this->insertLineMySQL($this->accountsInfosTable, $infosMatches);
					
						if($this->registerVerification) {
							$activateKey = $this->createRequest($username, 'activate');
							
							$subject = 'Activation de votre compte';
							$message = 'Bonjour ' . $username . ', <br />';
							$message .= 'Un compte a été créé à votre email. <br />';
							$message .= 'Si vous n\'avez pas créé de compte, veuillez ignorer ce message, <br />';
							$message .= 'Sinon, veuillez vous rendre sur ce lien pour activer votre compte : <br />';
							$message .= '<a href="' . $this->getOption('url') . 'login.php/activate/' . $activateKey . '">' . $this->getOption('url') . 'login.php/activate/' . $activateKey . '</a> <br />';
							$message .= 'Vous avez jusqu\'au ' . date('d/m/Y', strtotime($this->getAccountInfos('REQUESTS', 'expire_date', array('username' => $username))) + $this->requestsExpireDelay) . ' pour activer votre compte, <br />';
							$message .= 'Une fois cette date passée, le code d\'activation ne sera plus valide';
							$headers = 'From: contact@' . $this->getOption('domain') . "\r\n";
							$headers .= 'MIME-Version: 1.0' . "\r\n";
							$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
							
							// $mailStatus = mail($email, $subject, wordwrap(utf8_decode($message), 70, PHP_EOL), $headers);
							$mailStatus = mail($email, $subject, utf8_decode($message), $headers);
							
							if(!$mailStatus) {
								$this->deleteFullAccount($username);
								return false;
							}
						}
						
						return true;
					}
					else
						return false; 
				}
			}
			
			/** ----------------- */
			/**  Login Functions  */
			/** ----------------- */
			
			/** Verify Login */
			public function verifyLogin($username, $password) {
				if($this->isExistAccountInfos('ACCOUNTS', array('username' => $username), false))
					return password_verify($password, $this->getAccountInfos('ACCOUNTS', 'password', array('username' => $username), false));
				else
					return false;
			}
			
			/** Login (and Set Authentification Key Cookie) */
			public function loginAccount($username, $password, $cookieDuration = 0) {
				if(!$this->accountsManagementStatus)
					trigger_error('La gestion de compte n\'est pas activée', E_USER_ERROR);
				else if($this->verifyLogin($username, $password)) {
					$username = $this->getAccountInfos('ACCOUNTS', 'username', array('username' => $username), false);
					if($this->getUserRightLevel(array('username' => $username)) >= $this->translateUserRight('USER')) {
						$newAuthKey = $this->keygen(100);
						if(empty($cookieDuration) OR $cookieDuration <= 0)
							$cookieDuration = 24*3600;
						
						$matches['id'] = $this->getLastInfoMySQL($this->accountsSessionsTable, 'id') + 1;
						$matches['username'] = $username;
						$matches['auth_key'] = $newAuthKey;
						$matches['user_ip'] = $this->getUserIP();
						$matches['login_date'] = date('Y-m-d H:i:s');
						$matches['expire_date'] = date('Y-m-d H:i:s', time() + $cookieDuration);
						$matches['update_date'] = date('Y-m-d H:i:s');
						
						if($this->insertLineMySQL($this->accountsSessionsTable, $matches)) {
							$this->setAuthKeyCookie($newAuthKey, $cookieDuration);
							return $newAuthKey;
						}
						else
							return false;
					}
					else
						return false;
				}
				else
					return false;
			}
			
			/** ------------------ */
			/**  Logout Functions  */
			/** ------------------ */
			
			/** Logout (and Delete Authentification Key Cookie) */
			public function logoutAccount() {
				if(!$this->accountsManagementStatus)
					trigger_error('La gestion de compte n\'est pas activée', E_USER_ERROR);
				else {
					$this->deleteLinesMySQL($this->accountsSessionsTable, array('auth_key' => $this->getAuthKey()));
					$this->deleteAuthKeyCookie();
					return true;
				}
			}
		
		/** --------------- */
		/**  Hash Password  */
		/** --------------- */
		
		/** Hash Password */
		public function hashPassword($password) {
			$options = [];
			if(!empty($this->hashSalt))
				$options['salt'] = $this->hashSalt;
			else if(!empty($this->hashCost))
				$options['cost'] = $this->hashCost;
			
			return password_hash($password, $this->hashAlgorithm, $options);
		}

}

}
?>