<?php
// ignore_user_abort(true);
// set_time_limit(0);
// ob_start();

// $filePath = $_Upload->getUploadsPath() . $_Upload->getFileInfos('file_name', array('file_key' => $_Oli->getUrlParam(2)));
// $originalFileName = $_Upload->getFileInfos('original_file_name', array('file_key' => $_Oli->getUrlParam(2)));

/** UNO */
// if(file_exists($filePath)) {
	// header('Pragma: public');
	// header('Expires: 0');
	// header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	// header('Cache-Control: public');
	// header('Content-Description: File Transfer');
	// header('Content-type: application/octet-stream');
	// header('Content-Disposition: attachment; filename="' . $fileName . '"');
	// header('Content-Transfer-Encoding: binary');
	// header('Content-Length: ' . filesize($filePath));
	// ob_end_flush();
	// @readfile($filePath);
// }
// else {
	// header('Location: ' . $_SERVER['HTTP_REFERER']);
// }


/** DOS */
// if(file_exists($filePath)) {
	// switch($_Upload->getFileInfos('file_type', array('file_key' => $_Oli->getUrlParam(2)))) {
		// case 'pdf':
			// header('Content-type: application/pdf');
			// header('Content-Disposition: attachment; filename='' . $_Upload->getFileInfos('file_name', array('file_key' => $_Oli->getUrlParam(2))) . ''');
			// break;
		// default:
			// header('Content-type: application/octet-stream');
			// header('Content-Disposition: filename='' . $_Upload->getFileInfos('file_name', array('file_key' => $_Oli->getUrlParam(2))) . ''');
			// break;
	// }
	// header('Content-length: ' . filesize($filePath));
	// header('Cache-control: private');
		// while(!feof($fd)) {
				// $buffer = fread($fd, 2048);
				// echo $buffer;
		// }
	
	// echo 'plz: ' . $filePath . ' <br />';
	// ob_end_clean();
	// readfile($filePath);
	
	// switch(strrchr(basename($filePath), '.')) {
		// case '.gz': $fileType = 'application/x-gzip'; break;
		// case '.tgz': $fileType = 'application/x-gzip'; break;
		// case '.zip': $fileType = 'application/zip'; break;
		// case '.pdf': $fileType = 'application/pdf'; break;
		// case '.png': $fileType = 'image/png'; break;
		// case '.gif': $fileType = 'image/gif'; break;
		// case '.jpg': $fileType = 'image/jpeg'; break;
		// case '.txt': $fileType = 'text/plain'; break;
		// case '.htm': $fileType = 'text/html'; break;
		// case '.html': $fileType = 'text/html'; break;
		// default: $fileType = 'application/octet-stream'; break;
	// }
	
	// header('Content-disposition: attachment; filename = ' . basename($filePath));
	// header('Content-Type: application/force-download');
	// header('Content-Transfer-Encoding: ' . $fileType . '\n');
	// header('Content-Length: ' . filesize($filePath));
	// header('Pragma: no-cache');
	// header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0, public');
	// header('Expires: 0');
	// readfile($filePath);
// }
// else {
	// header('Location: ' . $_SERVER['HTTP_REFERER']);
// }
?>
