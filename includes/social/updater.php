<?php 
set_include_path($_SERVER['DOCUMENT_ROOT']);
ini_set('display_errors',0);
error_reporting(0);
if($GLOBALS['CONFIG']=simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/config/config.xml.php")){
	$debug = (string)$CONFIG->attributes()->debug;
	if($debug	==	'true'){
		ini_set('display_errors',1);
		ini_set('error_reporting', E_ALL ^ E_NOTICE ^ E_WARNING);
	}
}else{
	ini_set('display_errors',1);
	echo "configuration file not loaded";
	die();
}

require 'includes/functions/functions-general.php';
include 'database/utilities.php';
include 'database/db-manager.php';

require 'includes/classes/youtube.class.php';
require 'includes/classes/instagram.class.php';
require 'includes/classes/twitter.class.php';
require 'includes/classes/socialwall.class.php';

$SW = new SocialWall(TAG);
$res = $SW->GetSearchResults();
echo $res;


