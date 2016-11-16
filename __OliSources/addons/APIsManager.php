<?php
/*\
|*|  --------------------------
|*|  --- [  APIs Manager  ] ---
|*|  --------------------------
|*|  ~ An Oli PHP Framework addon
|*|  
|*|  APIs Manager in an easier way to create your API
|*|  
|*|  MIT License
|*|  Copyright (C) 2015 Mathieu Guérin (aka "Matiboux")
|*|  
|*|    Permission is hereby granted, free of charge, to any person obtaining a copy
|*|    of this software and associated documentation files (the "Software"), to deal
|*|    in the Software without restriction, including without limitation the rights
|*|    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
|*|    copies of the Software, and to permit persons to whom the Software is
|*|    furnished to do so, subject to the following conditions:
|*|    
|*|    The above copyright notice and this permission notice shall be included in all
|*|    copies or substantial portions of the Software.
|*|    
|*|    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
|*|    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
|*|    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
|*|    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
|*|    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
|*|    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
|*|    SOFTWARE.
|*|  
|*|  --- --- ---
|*|  
|*|  Releases date:
|*|    v1.0: 23 August 2015
|*|    v1.1: 22 August 2016
\*/

namespace APIsManager {

use \APIsManager\JSONResponse;

class APIsManager {

	/** Oli Object */
	private $_Oli;
	
	private $defaultCharset = 'utf-8';
	private $authorizedWebsites = [];
	private $apiUrl = '';
	
	/** --------------- */
	/**  Magic Methods  */
	/** --------------- */
	
	/** Class construct function */
	public function __construct() {
		global $_Oli;
		if(!isset($_Oli)) trigger_error('The Oli Framework object ($_Oli) is not defined!', E_USER_ERROR);
		
		$this->_Oli = &$_Oli;
	}
	
	/** *** *** *** */
	
	/** --------------- */
	/**  Configuration  */
	/** --------------- */
	
	/** Set default charset */
	public function setDefaultCharset($charset = null) {
		$this->defaultCharset = $charset ?: 'utf-8';
	}
	
	/** Set authorized websites */
	public function setAuthorizedWebsites($websites = null) {
		if(is_array($websites)) {
			foreach($websites as $eachKey => $eachWebsite) {
				if($eachWebsite == 'this') $eachWebsite = $this->_Oli->getOption('url');
				if(substr($eachWebsite, -1) == '/') $websites[$eachKey] = substr($eachWebsite, 0, -1);
			}
		}
		else if($websites != '*') $websites = [$websites];
		$this->authorizedWebsites = $websites ?: [];
	}
	
	/** Set API url */
	public function setAPIUrl($apiUrl) {
		$this->apiUrl = $$apiUrl;
	}
	
	/** *** *** */
	
	/** Setup API */
	public function setupAPI($contentType, $charset = null, $force = true) {
		if($contentType == 'JSON') {
			$this->APIContentType = 'JSON';
			$contentType = 'application/json';
		}
		else if(in_array($contentType, ['SERIAL', 'SERIALIZE'])) {
			$this->APIContentType = 'SERIAL';
			$contentType = 'text/plain';
		}
		else trigger_error('You tried to set a content type that is not supported', E_USER_ERROR);
		
		if($this->authorizedWebsites == '*') header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
		else {
			foreach($this->authorizedWebsites as $eachWebsite) {
				if($_SERVER['HTTP_ORIGIN'] == $eachWebsite) {
					header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
					break;
				}
			}
		}
		
		$this->_Oli->setContentType($contentType, $charset = $charset ?: $this->defaultCharset, $force);
	}
	
	/** ----------- */
	/**  Get Infos  */
	/** ----------- */
	
	/** Get parameters */
	public function getParameters($priority = null, $getAll = true) {
		if($priority == 'GET') return $getAll ? array_merge($_POST, $_GET) : $_GET;
		else return $getAll ? array_merge($_GET, $_POST) : $_POST;
	}
	
	/** Get API url */
	public function getAPIUrl() {
		return $this->apiUrl;
	}
	
	/** --------------------- */
	/**  Response Management  */
	/** --------------------- */
	
	/** Set Response */
	public function setResponse($key, $value) {
		$this->responseArray[$key] = $value;
	}
	
	/** Get Response */
	public function getResponse($key = null) {
		if(!empty($key)) return $this->responseArray[$key];
		else return $this->responseArray;
	}
	
	/** Reset Response */
	public function resetResponse() {
		$this->responseArray = [];
	}
	
	/** Encode Response */
	public function encodeResponse($options = null, $depth = null) {
		if($this->APIContentType = 'JSON') return json_encode($this->responseArray, $options ?: null, $depth ?: null);
		else if($this->APIContentType = 'SERIAL') return serialize($this->responseArray);
		else return false;
	}
}

}