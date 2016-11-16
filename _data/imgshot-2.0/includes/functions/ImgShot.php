<?php
function KeyGen($length = 12) {
	$key = ''; // Prepare 'key' variable.
	$i = 0; // Prepare 'i' for the while.
	$charactersAllowed = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; // All characters allowed. Characters can be add or remove.
	$lengthMax = strlen($charactersAllowed); // Find how many character is allowed.
	if( $length > $lengthMax ) { // If the desired length is too long...
		$length = $lengthMax; // Set the length to the length max.
	}
	while($i < $length) {
		$randomCharacter = substr($charactersAllowed, mt_rand(0, $lengthMax-1), 1);
		if(!strstr($key, $randomCharacter)) { // If the character is not already in the key...
			$key .= $randomCharacter; // Add the character in the key 
			$i++; // Add 1 to the counter
		}
	}
	return $key; // Return the key
}
function CheckError($error) {
	if($error == '') { // If $error is empty, no error.
		$errorMessage = '';
	}
	else if($error == 'ERR_NO_FILE') { // ERR_NO_FILE -> No file sent.
		return 'Vous n\'avez pas envoy&eacute; de fichier.';
	}
	else if($error == 'ERR_HIGH_SIZE') { // ERR_SIZE -> File too big (PHP, Form or verification)
		return 'L\'image est trop lourde.';
	}
	else if($error == 'ERR_PARTIAL') { // ERR_PARTIAL -> Partially uploaded file
		return 'Le transfert de l\'image a &eacute;chou&eacute;. <br /> R&eacute;essayez encore.';
	}
	else if($error == 'ERR_TYPE') { // ERR_TYPE -> Wrong type of file
		return 'Le type du fichier envoy&eacute; n\'est pas permis.';
	}
	else if($error == 'ERR_NAME_FILE_EMPTY') { // ERR_NAME_FILE_EMPTY -> No generated file name
		return 'Le fichier n\'a pas pu se g&eacute;n&eacute;rer un nom. <br /> R&eacute;essayez encore ou signalez le probl&egrave;me.';
	}
	else if($error == 'ERR_GENERATE_NAME_TIMED_OUT_AND_ALREADY_USED') { // <ERR_GENERATE_NAME_TIMED_OUT_AND_ALREADY_USED -> Generate timed out and name is already used
		return 'Trop de g&eacute;n&eacute;ration sans finir par en g&eacute;n&eacute;rer un non utilis&eacute;. <br /> R&eacute;essayez encore ou signalez le probl&egrave;me.';
	}
	else if($error == 'ERR_SAVE_FILE') { // ERR_SAVE_FILE -> Problem when save
		return 'Le fichier ne peut pas &ecirc;tre enregistr&eacute;e. <br /> R&eacute;essayez encore ou signalez le probl&egrave;me.';
	}
	else {
		return 'Erreur inconnue.';
	}
}
?>