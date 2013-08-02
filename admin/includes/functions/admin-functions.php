<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
ini_set('display_errors',0);
error_reporting(0);
if($GLOBALS['CONFIG']=simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/admin/config/config.xml.php")){
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

include_once 'admin/includes/functions/admin-functions-general.php';
include_once 'admin/includes/classes/listing-class.php';
include_once 'admin/includes/classes/record-class.php';

include_once 'database/db-manager.php';
include_once 'database/table-class.php';
include_once 'database/utilities.php';
include_once 'smarty/Smarty.class.php';

$GLOBALS['EDITED'] = 'edited';
$GLOBALS['DELETED'] = 'deleted';
$GLOBALS['WARNING'] = 'warning';
$GLOBALS['ERROR'] = 'error';

session_start();
$_REQUEST	=	clean($_REQUEST);
$_POST		=	clean($_POST);
$_GET		=	clean($_GET);
$DBobject = new DBmanager();

$SMARTY = new Smarty;
$SMARTY->template_dir    =  $_SERVER['DOCUMENT_ROOT']."admin".$CONFIG->smartytemplate_config->templates;                    // name of directory for templates
$SMARTY->compile_dir     =  $_SERVER['DOCUMENT_ROOT']."admin".$CONFIG->smartytemplate_config->templates_c;     // name of directory for compiled templates
$SMARTY->plugins_dir     =  array($_SERVER['DOCUMENT_ROOT']."".$CONFIG->smartytemplate_config->plugins, $_SERVER['DOCUMENT_ROOT']."/smarty/plugins");  // plugin directories
$SMARTY->cache_dir   =  $_SERVER['DOCUMENT_ROOT']."".$CONFIG->smartytemplate_config->cache;                    // name of directory for cache
if(	$debug == 'true'){
	$SMARTY->debugging = true;
	$SMARTY->force_compile = true;
	$SMARTY->caching = false;
	
}else{
	$SMARTY->debugging = false;	
	$SMARTY->force_compile = true;
	$SMARTY->caching = false;
}

$staging = (string)$CONFIG->attributes()->staging;
if($staging === "true"){
	$SMARTY->assign("staging",true);
}else{
	$SMARTY->assign("staging",false);
}
