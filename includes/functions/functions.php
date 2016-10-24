<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);  //SET THE INCLUDE PATH TO DOCUMENT ROOT SO THAT ALL INCLUDES ARE DONE RELATIVE TO WEB FOLDER
ini_set('display_errors',0); ini_set('error_reporting',0 );error_reporting(0);

include_once 'database/fatal_handler.php';

$_CONF_FILE = "/config/config.xml.php";
$GLOBALS['CONFIG'] = LOADCONFIG($_CONF_FILE);
$GLOBALS['SITE'] = "";
$cfg_host = (string)$CONFIG->domain;
$GLOBALS['HTTP_HOST'] = (!empty($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:$cfg_host);
$_URI = explode("?", $_SERVER['REQUEST_URI']);
$GLOBALS['REQUEST_URI'] = rtrim($_URI[0],'/');

include_once 'database/pdo-db-manager.php';
include_once 'database/utilities.php';
include_once 'smarty/Smarty.class.php';

session_start();
$_REQUEST = htmlclean($_REQUEST);
$_POST = htmlclean($_POST);
$_GET = htmlclean($_GET);

$_SECURE_COOKIE = false;
if($_SERVER['SERVER_PORT'] == 443 || !empty($_SERVER['HTTPS'])){
  $_SECURE_COOKIE = true; /*IF HTTPs TURN THIS ON */
}
$currentCookieParams = session_get_cookie_params();
$sidvalue = session_id();
setcookie('PHPSESSID',//name
$sidvalue,//value
0,//expires at end of session
$currentCookieParams['path'],//path
$currentCookieParams['domain'],//domain
$_SECURE_COOKIE, //secure
true  //httponly: Only accessible via http. Not accessible to javascript
);

$_PUBLISHED = 1; // ASSIGN PUBLISHED 1 THIS IS TO MAKE SURE ALL PAGES WHICH ARE LOADED ARE PUBLISHED
// ASSIGN DRAFT VARIABLE IF THE URL CONTAINS "DRAFT". ALSO SET CONFIG TO STAGING = TRUE TO PREVENT GOOGLE INDEX.
$uri_prefix_draft = "/(^|\s)(draft\/)/";
if(preg_match($uri_prefix_draft,$_REQUEST['arg1'])){
  $_REQUEST['arg1'] = preg_replace($uri_prefix_draft,"",$_REQUEST['arg1']);
  $_DRAFT = true;
  $_PUBLISHED = 0; // UPDATE PUBLISHED TO 0 TO LOAD THE DRAFT VERSIONS
}

//$SMARTY;
$_match_subsite = false;
foreach($CONFIG->subsite as $sub){
  $needle = str_replace("/","\/",$sub->url);
  if(preg_match("/^{$needle}/",$_REQUEST["arg1"])){
    $GLOBALS['SITE'] = $sub->url;
    $GLOBALS['CONFIG'] = LOADCONFIG($sub->config);
    $_REQUEST["arg1"] = trim(str_replace("{$needle}", "", $_REQUEST["arg1"]),"/");
    //SET COOKIE FOR SUBSITE
    $currentCookieParams = session_get_cookie_params();
    setcookie('sub_site',//name
    $needle,//value
    time()+(60*60*24*30),//expires at end of session
    $currentCookieParams['path'],//path
    $currentCookieParams['domain'],//domain
    $_SECURE_COOKIE, //secure
    true  //httponly: Only accessible via http. Not accessible to javascript
    );
    $_match_subsite = true;
  }
}
if(!empty($_COOKIE["sub_site"]) && !$_match_subsite){
  $lowercase_file_url = strtolower(isset($_SERVER['HTTPS'])?"https://":"http://" . $GLOBALS['HTTP_HOST'] .'/'.$_COOKIE["sub_site"] . $_SERVER['REQUEST_URI']);
  header("Location: $lowercase_file_url");
  exit();
}


include_once 'includes/classes/listing-class.php';
include_once 'includes/functions/functions-search.php';

if(!empty($CONFIG->database->host) && !empty($CONFIG->database->user) && !empty($CONFIG->database->password) && !empty($CONFIG->database->dbname)){
  $GLOBALS['DBobject'] = new DBmanager();
}

if(!empty($CONFIG->product_page)){
	include_once 'includes/classes/product-class.php';
	include_once 'includes/classes/cart-class.php';
	include_once 'includes/classes/bank-class.php';
	include_once 'includes/classes/shipping-class.php';
	//include_once 'includes/classes/user-class.php';
	include_once 'includes/classes/user-class-maf.php';
}

$GLOBALS['SMARTY'] = INITSMARTY($CONFIG);

$staging = (string)$CONFIG->attributes()->staging;
if($staging === "true" || $_PUBLISHED == 0 ){
  $SMARTY->assign("staging",true);
  $GLOBALS['GA_ID'] = null;
}else{
  $SMARTY->assign("staging",false);
  $ga_id = (string) $CONFIG->google_analytics->id;
  $GLOBALS['GA_ID'] = $ga_id;
  $SMARTY->assign("ga_id", $ga_id);
}

//COMPANY INFO FROM CONFIG
$COMP = json_encode($CONFIG->company);
$SMARTY->assign('COMPANY', json_decode($COMP,TRUE));

//GLOBAL VARIABLES FROM CONFIG
foreach($CONFIG->global_variable as $gv){
  $GLOBALS['CONFIG_VARS'][(string)$gv->name] = (string)$gv->value;
}
$SMARTY->assign("CONFIG_VARS", $GLOBALS['CONFIG_VARS']);

//DATABASE VARIABLES FROM CONFIG
$cnt = 0;
$GLOBALS['DATABASE_VARS']['find'] = array();
$GLOBALS['DATABASE_VARS']['replace'] = array();
foreach($CONFIG->database_variable as $gv){
  $GLOBALS['DATABASE_VARS']['find'][$cnt] = (string)$gv->name;
  $tmpVar = (string)$gv->value->attributes()->global;
  $GLOBALS['DATABASE_VARS']['replace'][$cnt] = empty($tmpVar) ? (string)$gv->value : $GLOBALS['CONFIG_VARS'][$tmpVar];
  $cnt++;
}
$SMARTY->assign("DATABASE_VARS", $GLOBALS['DATABASE_VARS']);

//SECTION HOLDS THE INIT FUNCTIONS
function LOADCONFIG($_CONF_FILE){
  //libxml_use_internal_errors(true);
  if($CONFIG = simplexml_load_file($_SERVER['DOCUMENT_ROOT'] . $_CONF_FILE)){
    $debug = (string)$CONFIG->attributes()->debug;
    if($debug == 'true'){ ini_set('display_errors',1); ini_set('error_reporting',E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT); } //ENABLE ERRORS IF DEBUG ON
  }else{
    //var_dump(libxml_get_errors());
    ini_set('display_errors',1);  echo "configuration file not loaded";  die(); //CONFIG DOES NOT EXIST. DIE AND TELL USER
  }
  return $CONFIG;
}


function INITSMARTY($CONFIG){
  // Create Smarty object and set
  // paths within object
  $SMARTY = new Smarty();
  $SMARTY->template_dir = $_SERVER['DOCUMENT_ROOT'] . "/" . $CONFIG->smartytemplate_config->templates; // name of directory for templates
  $SMARTY->compile_dir = $_SERVER['DOCUMENT_ROOT'] . "/" . $CONFIG->smartytemplate_config->templates_c; // name of directory for compiled templates
  $SMARTY->plugins_dir = array(
      $_SERVER['DOCUMENT_ROOT'] . "/" . $CONFIG->smartytemplate_config->plugins,
      $_SERVER['DOCUMENT_ROOT'] . "/smarty/plugins"
  ); // plugin directories
  $SMARTY->cache_dir = $_SERVER['DOCUMENT_ROOT'] . "/" . $CONFIG->smartytemplate_config->cache; // name of directory for cache
  $debug = (string)$CONFIG->attributes()->debug;
	if($debug == 'true'){
    $SMARTY->debugging = true;
    $SMARTY->force_compile = true;
    $SMARTY->caching = false;
  }else{
    $SMARTY->debugging = false;
    $SMARTY->force_compile = false;
    $SMARTY->caching = false;
  }
  $staging = (string)$CONFIG->attributes()->staging;
  if($staging == 'true'){
    $SMARTY->force_compile = true;
  }else{
    $SMARTY->force_compile = false;
  }
  return $SMARTY;
}
