<?php
/*\
|*|
|*|  UPLOAD MANAGER
|*|    (Addon for Oli Framework)
|*|    BETA 1.0
|*|
|*|  Easy way to Manage Accounts
|*|  Tools to speed up your website
|*|
|*|  Created: November 22th 2015
|*|  Releases date:
|*|    ALPHA: February 2015
|*|    BETA: July 2015
|*|      * No info on previous releases
|*|      * [version 1.0]: November 22th 2015
|*|                (1.1): Soon.
|*|
|*|  Developper: Matiboux (http://twitter.com/Matiboux)
|*|
\*/

namespace UploadManager {

define('UPLOADMANAGER_VERSION', 'BETA 1.0');
define('UPLOADMANAGER_OWNER', 'Matiboux');

use \PDO; // Use PDO class

class UploadManager {
	/** Externals Class */
	private $_Oli;
	private $db = null;
	
	/** Path and Table */
	private $uploadsTable = '';
	private $uploadsPath = '';
	
	/** Parameters */
	private $fileMaxSize = 4194304; // 1 Mo = 1048576 octets
	private $fileNameLength = 24;
	private $fileAllowedTypes = [];
	
	
	/** --------------- */
	/**  Magic Methods  */
	/** --------------- */
	
	/** Construct */
	public function __construct() {
		global $_Oli;
		if(empty($_Oli))
			trigger_error('L\'objet du Framework Oli ($_Oli) n\'est pas défini', E_USER_ERROR);
		
		$this->_Oli = &$_Oli;
	}
	public function __destruct() {
	}
	
	/** Wrong Type on Object */
	public function __toString() {
		return 'UploadManager version ' . UPLOADMANAGER_VERSION . ' created by ' . UPLOADMANAGER_OWNER . '.';
	}
	
	/** *** *** */
	
	/** ------------------ */
	/**  MySQL PDO Object  */
	/** ------------------ */
	
	/** MySQL Config */
	public function setupExistMySQL() {
		if(empty($this->_Oli->db))
			trigger_error('L\'objet de connexion MySQL PDO n\'est pas défini dans le Framework Oli', E_USER_ERROR);
		
		$this->db = &$this->_Oli->db;
		return true;
	}
	public function setupManualMySQL($database, $username = 'root', $password = '', $host = 'localhost') {
		try {
			$this->db = new PDO('mysql:host=' . $host . ';dbname=' . $database . ';charset=utf8', $username, $password);
		}
		catch(Exception $e) {
			trigger_error($e->getMessage(), E_USER_ERROR);
		}
	}
	
	/** --------------- */
	/**  Configuration  */
	/** --------------- */
	
	/** Uploads Path */
	public function setUploadPath($path) {
		$this->uploadsPath = $path;
	}
	
	/** Uploads Url */
	public function setUploadUrl($url) {
		$this->uploadsUrl = $url;
	}
	
	/** Uploads Table */
	public function setUploadTable($table) {
		$this->uploadsTable = $table;
	}
	
	/** Uploads Max Size */
	public function setMaxSize($size) {
		$this->fileMaxSize = $size;
	}
	
	/** Uploads Name Length */
	public function setNameLength($length) {
		$this->fileNameLength = $length;
	}
	
	/** Uploads Allowed Types */
	public function setAllowedTypes($types) {
		$this->fileAllowedTypes = $types;
	}
	
	/** *** *** */
	
	/** ----------- */
	/**  Get Infos  */
	/** ----------- */
	
	/** Get Uploaded File Lines */
	public function getFileLines($where = [], $caseSensitive = true, $forceArray = false, $rawResult = false) {
		return $this->_Oli->getLinesMySQL($this->uploadsTable, $where, $caseSensitive, $forceArray, $rawResult);
	}
	
	/** Get Uploaded File Infos */
	public function getFileInfos($whatVar, $where = [], $caseSensitive = true, $forceArray = false, $rawResult = false) {
		return $this->_Oli->getInfosMySQL($this->uploadsTable, $whatVar, $where, $caseSensitive, $forceArray, $rawResult);
	}
	
	/** Is Exist Uploaded File */
	public function isExistFile($where, $caseSensitive = true) {
		return $this->_Oli->isExistInfosMySQL($this->uploadsTable, $where, $caseSensitive);
	}
	
	/** ------------------ */
	/**  Get Path and Url  */
	/** ------------------ */
	
	/** Get Uploads Path */
	public function getUploadsPath() {
		return $this->uploadsPath;
	}
	
	/** Get Uploads Url */
	public function getUploadsUrl() {
		return $this->uploadsUrl;
	}
	
	/** ------------------- */
	/**  Get File Max Size  */
	/** ------------------- */
	
	/** Get File Type */
	public function getMaxSizeAllowed() {
		return $this->fileMaxSize;
	}
	
	/** --------------- */
	/**  Get File Type  */
	/** --------------- */
	
	/** Get File Type */
	public function getAllowedFileTypes() {
		return $this->fileAllowedTypes;
	}
	
	/** Get File Type */
	public function getFileType($where, $caseSensitive = true) {
		$text_array = ['txt'];
		$image_array = ['bmp', 'jpg', 'jpeg', 'png', 'gif'];
		$music_array = ['mp3', 'ogg'];
		$video_array = ['mp4'];
		$webpage_array = ['htm', 'html'];
		$opendocument_array = ['odt', 'ods', 'odp', 'odg', 'odc', 'odf', 'odb', 'odi', 'odm'];
		// $apps_array = ['exe'];
		
		if(in_array($this->getFileInfos('file_type', $where, $caseSensitive), $text_array))
			return 'text';
		else if(in_array($this->getFileInfos('file_type', $where, $caseSensitive), $image_array))
			return 'image';
		else if(in_array($this->getFileInfos('file_type', $where, $caseSensitive), $music_array))
			return 'music';
		else if(in_array($this->getFileInfos('file_type', $where, $caseSensitive), $video_array))
			return 'video';
		else if(in_array($this->getFileInfos('file_type', $where, $caseSensitive), $webpage_array))
			return 'webpage';
		else if(in_array($this->getFileInfos('file_type', $where, $caseSensitive), $opendocument_array))
			return 'opendocument';
		else
			return 'unknown';
	}
	
	/** ----------------------- */
	/**  Manage Uploaded Files  */
	/** ----------------------- */
	
	/** Upload new File */
	public function uploadFile($file, $name, $owner = '') {
		$extensionFile = strtolower(substr(strrchr($file['name'], '.'), 1));
		$dirTarget = $this->uploadsPath;
		$fileKey = '';
		
		if($file['error'] != UPLOAD_ERR_OK
		OR $file['size'] > $this->fileMaxSize)
			return false;
		else if($this->fileAllowedTypes == '*'
		OR in_array($extensionFile, $this->fileAllowedTypes)) {
			do {
				$fileKey = $this->_Oli->keygen($this->fileNameLength);
				if(empty($fileKey))
					return false;
			} while(file_exists($dirTarget . $fileKey . '.*') OR $this->isExistFile(array('file_key' => $fileKey)));
			
			/** We are never so sure. */
			if(file_exists($dirTarget . $fileKey . '.*')
			OR $this->isExistFile(array('file_key' => $fileKey))
			OR empty($fileKey))
				return false;
			else {
				$fileSufix = '.' . $extensionFile;
				
				if(!file_exists($dirTarget))
					mkdir($dirTarget);
				
				if(move_uploaded_file($file['tmp_name'], $dirTarget . $fileKey . $fileSufix)) {
					$uploadMatches['id'] = $this->_Oli->getLastInfoMySQL($this->uploadsTable, 'id') + 1;
					$uploadMatches['name'] = $name;
					$uploadMatches['owner'] = $owner;
					$uploadMatches['date'] = date('Y/m/d');
					$uploadMatches['time'] = date('H:i:s');
					$uploadMatches['file_key'] = $fileKey;
					$uploadMatches['file_type'] = $extensionFile;
					$uploadMatches['file_name'] = $fileKey . $fileSufix;
					$uploadMatches['file_size'] = $file['size'];
					$uploadMatches['original_file_name'] = $file['name'];
					
					if($this->_Oli->insertLineMySQL($this->uploadsTable, $uploadMatches))
						return $fileKey;
					else
						return false;
				}
				else
					return false;
			}
		}
		else
			return false;
	}
	
	/** Update Uploaded File Infos */
	public function updateFileInfos($what, $where) {
		return $this->_Oli->updateInfosMySQL($this->uploadsTable, $what, $where);
	}
	
	/** Delete Uploaded File */
	public function deleteFile($where) {
		if($this->isExistFile($where)) {
			unlink($this->uploadsPath . $this->getFileInfos('file_name', $where));
			$this->_Oli->deleteLinesMySQL($this->uploadsTable, $where);
			return true;
		}
		else
			return false;
	}
}

}