<?php

set_time_limit(0); // just in case it too long, not recommended for production
error_reporting(E_ALL | E_STRICT); // Set E_ALL for debuging
// error_reporting(0);
ini_set('max_file_uploads', 50);   // allow uploading up to 50 files at once

// needed for case insensitive search to work, due to broken UTF-8 support in PHP
ini_set('mbstring.internal_encoding', 'UTF-8');
ini_set('mbstring.func_overload', 2);

include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderConnector.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinder.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeDriver.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeLocalFileSystem.class.php';
// Required for MySQL storage connector
 include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeMySQL.class.php';
// Required for FTP connector support
 include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeFTP.class.php';

//SETS UP THE CONFIG WHICH CAN BE USED
$GLOBALS['CONFIG']=simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/admin/config/config.xml.php");
session_start();
$_paths = array();

if(!empty($_SESSION['user']['admin']["stores"]) && $_SESSION['user']['admin']["level"] > 1){
	foreach($_SESSION['user']['admin']["stores"] as $id){
		$dir = "/sudofiles/id".$id['access_store_id'];
		if(!file_exists($_SERVER['DOCUMENT_ROOT'].$dir)){
			$chk = fileManager_make_path($_SERVER['DOCUMENT_ROOT'].$dir);
		}
		if(file_exists($_SERVER['DOCUMENT_ROOT'].$dir)){
				$_paths[] = array(
						'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
						'path'          => $_SERVER['DOCUMENT_ROOT'].$dir,         // path to files (REQUIRED)
						'URL'           => $dir, // URL to files (REQUIRED)
						'accessControl' => 'access'             // disable and hide dot starting files (OPTIONAL)
				);
		}
	}
}else{
	$_paths[] = array(
			'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
			'path'          => $_SERVER['DOCUMENT_ROOT'] .'/uploads/',         // path to files (REQUIRED)
			'URL'           => '/uploads/', // URL to files (REQUIRED)
			'accessControl' => 'access'             // disable and hide dot starting files (OPTIONAL)
		);
	$_paths[] = array(
			'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
			'path'          => $_SERVER['DOCUMENT_ROOT'] .'/sudofiles/',         // path to files (REQUIRED)
			'URL'           => '/storefiles/', // URL to files (REQUIRED)
			'accessControl' => 'access'             // disable and hide dot starting files (OPTIONAL)
	);
}


/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from  '.' (dot)
 *
 * @param  string  $attr  attribute name (read|write|locked|hidden)
 * @param  string  $path  file path relative to volume root directory started with directory separator
 * @return bool|null
 **/
function access($attr, $path, $data, $volume) {
	return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
		? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
		:  null;                                    // else elFinder decide it itself
}

$opts = array(
	 'debug' => true,
	'roots' => $_paths
);

/* ORIGINAL EXAMPLE OF PATHS */
/* $opts = array(
	 'debug' => true,
		'roots' => array(
	   array(
	   		'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
	   		'path'          => $_SERVER['DOCUMENT_ROOT'] .$_path,         // path to files (REQUIRED)
	   		'URL'           => $_path, // URL to files (REQUIRED)
	   		'accessControl' => 'access'             // disable and hide dot starting files (OPTIONAL)
	   ),
			array(
				 'driver' => 'MySQL',
						'host'   => $CONFIG->database->host,
						'user'   => $CONFIG->database->user,
						'pass'   => $CONFIG->database->password,
						'db'     => $CONFIG->database->dbname,
						'path'   => 1,
				),
			array(
					'driver' => 'FTP',
					'host'   => 'uploads.ilisys.com.au',
					'user'   => 'themserver.com',
					'pass'   => 'c@^^3L5tRu7s!n53CR*7',
					'path'   => '/public/cms/uploads/'
			)
		)
); */

// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();

/**
 Make a nested path , creating directories down the path
 Recursion !!
 */
function fileManager_make_path($path) {
	if(is_dir($path)){
		return true;
	}else{
		$dir = pathinfo($path,PATHINFO_DIRNAME);
		if(fileManager_make_path($dir)){
			if(mkdir($path)){
				chmod($dir,0777);
				return true;
			}
		}
	}
	return false;
}