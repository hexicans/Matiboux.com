<?php
echo '<b>Welcome on the Oli auto-updater tool!</b> <br /> <br />';

if(!defined('OLI_GITHUB_API')) define('OLI_GITHUB_API', 'https://api.github.com/repos/OliFramework/Oli/');
$allowPrereleases = false;

if(!defined('ABSPATH')) {
	define('ABSPATH', dirname(__FILE__) . '/');
	if(!defined('SCRIPTBASEPATH')) define('SCRIPTBASEPATH', ABSPATH);
	
	function recursiveCopy($source, $destination) {
		if(!is_readable($source)) return false;
		$handle = opendir($source);
		$copyResult = false;
		@mkdir($destination);
		
		while(($file = readdir($handle) !== false)) {
			if($file != '.' AND $file != '..') {
				if(is_dir($source . '/' . $file)) $copyResult = recurse_copy($source . '/' . $file, $destination . '/' . $file);
				else $copyResult = copy($source . '/' . $file, $destination . '/' . $file);
				
				if(!$copyResult) return false;
			}
		}
		return true;
	}
	function isEmptyDir($directory) {
		if(!is_readable($directory)) return false;
		$handle = opendir($directory);
		
		while(($file = readdir($handle) !== false)) {
			if($file != '.' AND $file != '..') return false;
		}
		return true;
	}
	
	/** *** *** */
	
	echo 'Backing up your current installation in a new new directory nammed ".backup/"...';
	if(isEmptyDir(ABSPATH . '.backup/')) rmdir(ABSPATH . '.backup/') or die(' <b>Could not delete the empty ".backup/" directory</b>');
	else if(file_exists(ABSPATH . '.backup/')) {
		if(isEmptyDir(ABSPATH . '.backupOld/')) rmdir(ABSPATH . '.backupOld/') or die(' <b>Could not delete the empty ".backupOld/" directory</b>');
		
		if(!file_exists(ABSPATH . '.backupOld/')) rename(ABSPATH . '.backup/', ABSPATH . '.backupOld/') or die(' <b>We could not create the ".backup/" directory</b>');
		else die(' <b>Please delete the ".backupOld/" directory before</b>');
	}
	
	if(!file_exists(ABSPATH . '.backup/')) mkdir(ABSPATH . '.backup/') or die(' <b>We could not create the ".backup/" directory</b>');
	else die(' <b>The ".backup/" directory already exists!</b>');
	
	recursiveCopy(ABSPATH, ABSPATH . '.backup/') or die(' <b>We could not back up your website content</b>');
	if(SCRIPTBASEPATH != ABSPATH) recursiveCopy(SCRIPTBASEPATH, ABSPATH . '.backup/') or die(' <b>We could not back up the framework content</b>');
	echo '<br />';
	
	/** *** *** */
	
	if(!file_exists(ABSPATH . '.OliLatest/')) {
		$releasesCurl = curl_init(OLI_GITHUB_API . 'releases');
		curl_setopt($releasesCurl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($releasesCurl, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($releasesCurl, CURLOPT_USERAGENT, 'Oli/Beta-1.7.1 (AutoUpdater)');
		
		echo 'Getting a list of all the framework releases...';
		$releases = json_decode(curl_exec($releasesCurl), true) or die(' <b>We could not get the releases list</b>');
		curl_close($releasesCurl);
		echo '<br />';

		foreach($releases as $eachReleases) {
			if($allowPrereleases OR !$eachReleases['prerelease']) {
				$zipCurl = curl_init($eachReleases['zipball_url']);
				curl_setopt($zipCurl, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($zipCurl, CURLOPT_MAXREDIRS, 5);
				curl_setopt($zipCurl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($zipCurl, CURLOPT_CONNECTTIMEOUT, 10);
				curl_setopt($zipCurl, CURLOPT_USERAGENT, 'Oli/Beta-1.7.1 (AutoUpdater)');
				
				echo 'Downloading the latest ' . (!$eachReleases['prerelease'] ? 'release' : 'pre-release') . '\'s archive... <br />';
				$releaseArchive = curl_exec($zipCurl) or die(' <b>We could not get the archive</b>');
				file_put_contents('Oli_' . $eachReleases['tag_name'] . '.zip', $releaseArchive) or die(' <b>We could not save the archive</b>');
				curl_close($zipCurl);
				echo '<br />';
				
				break;
			}
		}
		
		if(!class_exists($classname = 'ZipArchive')) {
			die('<b>' . $classname . ' is not supported by your PHP configuration</b> <br />
			<b>But don\'t worry</b>: we still can finish the update together! <br />
			Find the "Oli_{version}.zip" archive and extract everything in a new directory nammed ".OliLatest/" <br />
			Once you done that, refresh me and I will finish the updates!');
		}
		else {
			$zipTool = new ZipArchive();
			// if($zipCreated AND $zipTool->open($eachReleases['tag_name'] . '.zip') === true) {
				// $zipTool->extractTo(ABSPATH . '.OliLatest/') ? print('Extracting up the auto-updater... <br />') : die('An occured occurred while extrating the new Oli version');
				// $zipTool->close();
				
				// echo 'ok';
			// }
			// else echo 'échec';
		}
	}
	
	if(file_exists(ABSPATH . '.OliLatest/')) {
		$dirName = array_reverse(explode('/', glob(ABSPATH . '.OliLatest/*')[0]))[0];
		
		echo 'Updating myself to become more powerful...';
		// copy(ABSPATH /*. '.OliLatest/' . $dirName*/ . 'autoupdater.php', ABSPATH . 'autoupdater.php') or die(' <b>We could not update the auto-updater</b>');
		echo '<br /> <br />';
		
		echo 'Yay! I have been successfully updated <br />
		Let\'s keep going work with the framework update <br /> <br />';
		echo '<b>Reloading...</b> <br /> <br /> <hr />';
		include ABSPATH . 'autoupdater.php';
	}
	else die('<b>Oh. The ".OliLatest/" directory cannot be found!</b>');
}
else {
	$dirName = array_reverse(explode('/', glob(ABSPATH . '.OliLatest/*')[0]))[0];
	
	echo 'Getting you the new config file as a model, to help you edit yours...';
	copy(ABSPATH . '.OliLatest/' . $dirName . '/config.json', ABSPATH . 'config.new.json') or die(' <b>We could not get the new config file</b>');
	echo '<br />';
	
	// echo 'Updating ...';
	// copy(ABSPATH . '.OliLatest/' . $dirName . '/config.json', ABSPATH . 'config.new.json') or die(' <b>We could not get the new config file</b>');
	// echo '<br />';
}

// ---

// $zip = new ZipArchive();
// $filename = "./test112.zip";

// if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
    // exit("Impossible d'ouvrir le fichier <$filename>\n");
// }

// $zip->addFromString("testfilephp.txt", "#1 Ceci est une chaîne texte, ajoutée comme testfilephp.txt.\n");
// $zip->addFromString("testfilephp2.txt", "#2 Ceci est une chaîne texte, ajoutée comme testfilephp2.txt.\n");
// $zip->addFile(ABSPATH . "index.php","/testfromfile.php");
// echo "Nombre de fichiers : " . $zip->numFiles . "\n";
// echo "Statut :" . $zip->status . "\n";
// $zip->close();

// ---

// $za = new ZipArchive();

// $za->open($eachReleases['tag_name'] . '.zip');
// print_r($za);
// echo "Nombre de fichiers : " . $za->numFiles . "\n";
// echo "Statut : " . $za->status  . "\n";
// echo "Statut du système : " . $za->statusSys . "\n";
// echo "Nom du fichier : " . $za->filename . "\n";
// echo "Commentaire : " . $za->comment . "\n";

// for ($i=0; $i<$za->numFiles;$i++) {
    // echo "index : $i\n";
    // print_r($za->statIndex($i));
// }
// echo "Nombre de fichiers :" . $za->numFiles . "\n";
?>