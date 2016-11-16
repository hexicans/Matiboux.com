<?php
function CheckError($error) {
	if($error == '') { // If $error is empty, no error.
		return '';
	}
	else if($error == 'ERR_NO_GENRE') { // ERR_NO_GENRE -> Any genre was selected
		return 'Vous n\'avez pas s&eacute;lectionn&eacute; de genre.';
	}
	else if($error == 'ERR_NO_LENGTH') { // ERR_NO_LENGTH -> Wanted length is needed
		return 'Vous n\'avez pas entrez de longueur souhait&eacute;e.';
	}
	else if($error == 'ERR_LENGTH_NULL') { // ERR_LENGTH_NULL -> Wanted length is 0
		return 'La longueur ne doit pas être égale à 0.';
	}
	else {
		return 'Erreur inconnue.';
	}
}
?>