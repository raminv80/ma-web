<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
ini_set('display_errors',0);
error_reporting(0);
if($GLOBALS['CONFIG']=simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/config/config.xml.php")){
	$debug = (string)$CONFIG->attributes()->debug;
	if($debug	==	'true'){
		/*ini_set('display_errors',1);
		ini_set('error_reporting', E_ALL);*/
	}
}else{
	ini_set('display_errors',1);
	echo "configuration file not loaded";
	die();
}

include 'database/db-manager.php';
include 'database/table-class.php';
include 'database/utilities.php';
include 'smarty/Smarty.class.php';

include 'admin/includes/functions/admin-functions-general.php';
include 'admin/includes/classes/misc-class.php';
include 'admin/includes/classes/page-class.php';
include 'admin/includes/classes/form-class.php';
include 'admin/includes/classes/filelink-class.php';
include 'admin/includes/classes/fileupload-class.php';

session_start();

$_REQUEST	=	clean($_REQUEST);
$_POST		=	clean($_POST);
$_GET		=	clean($_GET);
$DBobject = new DBmanager();

$SMARTY = new Smarty;
$SMARTY->template_dir    =  $_SERVER['DOCUMENT_ROOT']."/".$CONFIG->smartytemplate_config->templates;                    // name of directory for templates
$SMARTY->compile_dir     =  $_SERVER['DOCUMENT_ROOT']."/".$CONFIG->smartytemplate_config->templates_c;     // name of directory for compiled templates
$SMARTY->plugins_dir     =  array($_SERVER['DOCUMENT_ROOT']."/".$CONFIG->smartytemplate_config->plugins, $_SERVER['DOCUMENT_ROOT']."/smarty/plugins");  // plugin directories
$SMARTY->cache_dir   =  $_SERVER['DOCUMENT_ROOT']."/".$CONFIG->smartytemplate_config->cache;                    // name of directory for cache
$SMARTY->assign('logo_img','http://'.$_SERVER['HTTP_HOST'].'/images/all-fresh-logo.png');
if(	$debug == 'true'){
	$SMARTY->debugging = true;
	$SMARTY->force_compile = true;
	$SMARTY->caching = false;
	
}else{
	$SMARTY->debugging = false;	
	$SMARTY->force_compile = true;
	$SMARTY->caching = false;
}