<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
ini_set('display_errors',1);
error_reporting(1);
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

include 'database/db-manager.php';
include 'database/table-class.php';
include 'database/utilities.php';
include 'smarty/Smarty.class.php';

//include 'includes/classes/page-class.php';
include 'includes/classes/listing-class.php';
include 'includes/classes/product-listing-class.php';
include 'includes/functions/functions-general.php';
include 'includes/functions/functions-search.php';

require 'includes/social/youtube.class.php';
require 'includes/social/instagram.class.php';
require 'includes/social/twitter.class.php';
require 'includes/social/facebook.php';
require 'includes/social/socialwall.class.php';

include 'includes/processes/processes.php';

include 'includes/functions/mobile-functions.php';

session_start();

$_REQUEST	=	clean($_REQUEST);
$_POST		=	clean($_POST);
$_GET		=	clean($_GET);
$DBobject = new DBmanager();

if(!empty($CONFIG->socialwall)){
	$tag = $CONFIG->socialwall->tag;
	$table = $CONFIG->socialwall->table;
	$ads = $CONFIG->socialwall->ads == true ? TRUE:FALSE;
	$instagram = $CONFIG->socialwall->attributes()->instagram == "true" ? TRUE:FALSE;
    $facebook = $CONFIG->socialwall->attributes()->facebook == "true" ? TRUE:FALSE;
    $youtube = $CONFIG->socialwall->attributes()->youtube == "true" ? TRUE:FALSE;
    $twitter = $CONFIG->socialwall->attributes()->twitter == "true" ? TRUE:FALSE;
	$SOCIAL = new SocialWall($tag,$table,$ads,$instagram,$facebook,$youtube,$twitter);
}

//Create Smarty object and set
//paths within object
$SMARTY = new Smarty;
$SMARTY->template_dir    =  $_SERVER['DOCUMENT_ROOT']."/".$CONFIG->smartytemplate_config->templates;                    // name of directory for templates
$SMARTY->compile_dir     =  $_SERVER['DOCUMENT_ROOT']."/".$CONFIG->smartytemplate_config->templates_c;     // name of directory for compiled templates
$SMARTY->plugins_dir     =  array($_SERVER['DOCUMENT_ROOT']."/".$CONFIG->smartytemplate_config->plugins, $_SERVER['DOCUMENT_ROOT']."/smarty/plugins");  // plugin directories
$SMARTY->cache_dir   =  $_SERVER['DOCUMENT_ROOT']."/".$CONFIG->smartytemplate_config->cache;                    // name of directory for cache
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

if(isMobile()){
	$SMARTY->assign("mobile",true);
}

