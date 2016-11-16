<?php
/*\
|*|  ---------------------------
|*|  --- [  KeyGen Library  ] ---
|*|  --- [   Version 1.0    ] ---
|*|  ---------------------------
|*|  
|*|  The KeyGen Library, from KeyGen, an open source service for random password and security keys generation
|*|  Copyright (C) 2014 Mathieu Guérin (aka "Matiboux")
|*|  
|*|    This program is free software: you can redistribute it and/or modify
|*|    it under the terms of the GNU General Public License as published by
|*|    the Free Software Foundation, either version 3 of the License, or
|*|    (at your option) any later version.
|*|    
|*|    This program is distributed in the hope that it will be useful,
|*|    but WITHOUT ANY WARRANTY; without even the implied warranty of
|*|    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
|*|    GNU General Public License for more details.
|*|    
|*|    You should have received a copy of the GNU General Public License
|*|    along with this program. If not, see <http://www.gnu.org/licenses/>.
|*|  
|*|  Please see the README.md file for more infos!
|*|  
|*|  --- --- ---
|*|  
|*|  You can use this library with any PHP source code
|*|  or with Oli, an open source PHP framework, as an addon (automatically reconized by Oli, no configuration needed)
|*|  
|*|  The official KeyGen Library repository: https://github.com/matiboux/KeyGenLib
|*|  Also don't forget to try the KeyGen service!
|*|  - Project website: http://keygen.matiboux.com/
|*|  - Project repository: https://github.com/matiboux/KeyGen
\*/

namespace KeyGen {

class KeyGenLib {

	const NUMERIC = '1234567890';
	const LOWERCASE = 'abcdefghijklmnopqrstuvwxyz';
	const UPPERCASE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	const SPECIAL = '!#$%&\()+-;?@[]^_{|}';
	
	private static $lastParameters = null;
	private static $forcedRedundancy = false;
	
	private static $lastErrorCode = null;
	
	/** ------------------ */
	/**  KeyGen functions  */
	/** ------------------ */
	
	/** KeyGen function */
	public static function keygen($length = 12, $numeric = true, $lowercase = true, $uppercase = true, $special = false, $redundancy = false) {
		$charactersAllowed = '';
		if($numeric) $charactersAllowed .= self::NUMERIC;
		if($lowercase) $charactersAllowed .= self::LOWERCASE;
		if($uppercase) $charactersAllowed .= self::UPPERCASE;
		if($special) $charactersAllowed .= self::SPECIAL;
		
		if(empty($length) OR $length <= 0) {
			// self::setError('LENGTH_NULL');
			self::$lastErrorCode = 1;
			return false;
		}
		else if(empty($charactersAllowed)) {
			// self::setError('NO_KEY_GENRE');
			self::$lastErrorCode = 2;
			return false;
		}
		else {
			self::$lastParameters = array(
				'length' => $length,
				'numeric' => $numeric,
				'lowercase' => $lowercase,
				'uppercase' => $uppercase,
				'special' => $special,
				'redundancy' => $redundancy
			);
			
			if($length > strlen($charactersAllowed) AND !$redundancy) $redundancy = true;
			
			$keygen = '';
			while(strlen($keygen) < $length) {
				$randomCharacter = substr($charactersAllowed, mt_rand(0, strlen($charactersAllowed) - 1), 1);
				if($redundancy OR !strstr($keygen, $randomCharacter)) $keygen .= $randomCharacter;
			}
			
			self::$lastErrorCode = 0;
			return $keygen;
		}
	}
	
	/** KeyGen function */
	public static function enc($string, $pass) {
        $key_size = self::$key_size;
        // Set a random salt.
        $salt = self::random_bytes(8);
        $salted = '';
        $dx = '';
        // Lengths in bytes:
        $key_length = (int) ($key_size / 8);
        $block_length = 16; // 128 bits, iv has the same length.
        // $salted_length = $key_length (32, 24, 16) + $block_length (16) = (48, 40, 32)
        $salted_length = $key_length + $block_length;
        while (self::strlen($salted) < $salted_length) {
            $dx = md5($dx.$pass.$salt, true);
            $salted .= $dx;
        }
        $key = self::substr($salted, 0, $key_length);
        $iv = self::substr($salted, $key_length, $block_length);
        $encrypted = self::aes_cbc_encrypt($string, $key, $iv);
        return $encrypted !== false ? base64_encode('Salted__'.$salt.$encrypted) : false;
    }
    /**
     * Decrypt AES (256, 192, 128)
     *
     * @param   string  $string     The input message to be decrypted.
     * @param   string  $pass       The secret pass-phrase that has been used for encryption.
     * @return  mixed               base64 decrypted string, FALSE on failure.
     */
    public static function dec($string, $pass) {
        $key_size = self::$key_size;
        // Lengths in bytes:
        $key_length = (int) ($key_size / 8);
        $block_length = 16;
        $data = base64_decode($string);
        $salt = self::substr($data, 8, 8);
        $encrypted = self::substr($data, 16);
        /**
         * From https://github.com/mdp/gibberish-aes
         *
         * Number of rounds depends on the size of the AES in use
         * 3 rounds for 256
         *     2 rounds for the key, 1 for the IV
         * 2 rounds for 128
         *     1 round for the key, 1 round for the IV
         * 3 rounds for 192 since it's not evenly divided by 128 bits
         */
        $rounds = 3;
        if ($key_size == 128) {
            $rounds = 2;
        }
        $data00 = $pass.$salt;
        $md5_hash = array();
        $md5_hash[0] = md5($data00, true);
        $result = $md5_hash[0];
        for ($i = 1; $i < $rounds; $i++) {
            $md5_hash[$i] = md5($md5_hash[$i - 1].$data00, true);
            $result .= $md5_hash[$i];
        }
        $key = self::substr($result, 0, $key_length);
        $iv = self::substr($result, $key_length, $block_length);
        return self::aes_cbc_decrypt($encrypted, $key, $iv);
    }
    /**
     * Sets the key-size for encryption/decryption in number of bits
     * @param   mixed       $newsize    The new key size. The valid integer values are: 128, 192, 256 (default)
     *                                  $newsize may be NULL or may be omited - in this case
     *                                  this method is just a getter of the current key size value.
     * @return  integer                 Returns the old key size value.
     */
    public static function size($newsize = null) {
        $result = self::$key_size;
        if (is_null($newsize)) {
            return $result;
        }
        $newsize = (string) $newsize;
        if ($newsize == '') {
            return $result;
        }
        $valid_integer = ctype_digit($newsize);
        $newsize = (int) $newsize;
        if (!$valid_integer || !in_array($newsize, self::$valid_key_sizes)) {
            trigger_error('GibberishAES: Invalid key size value was to be set. It should be an integer value (number of bits) amongst: '.implode(', ', self::$valid_key_sizes).'.', E_USER_WARNING);
        } else {
            self::$key_size = $newsize;
        }
        return $result;
    }
	
	/** ----------------------- */
	/**  Parameters Management  */
	/** ----------------------- */
	
	/** KeyGen function */
	public static function lastParameters() {
		return self::$lastParameters ?: false;
	}
	
	/** ------------------ */
	/**  Error Management  */
	/** ------------------ */
	
	/** Set error report */
	// public function setError() {
		
	// }
	
	/** Get error report code */
	public static function getErrorCode() {
		return self::$lastErrorCode ?: false;
	}
	
	/** Get error report infos */
	// public function getErrorInfo() {
		// return self::$lastErrorCode ?: false;
	// }

}

}
?>