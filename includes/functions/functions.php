<?php
// Fatal Error Handler
// Registering shutdown function
register_shutdown_function("fatal_handler");
function fatal_handler() {
  // Getting last error
	global $CONFIG;
  $errno = error_get_last();
  if($errno['type'] === E_ERROR){
    $body = "<table><thead bgcolor='#c8c8c8'><th>Item</th><th>Description</th></thead><tbody>";
    $body .= "<tr valign='top'><td><b>Error</b></td><td><pre>{$errno['message']}</pre></td></tr>";
    $body .= "<tr valign='top'><td><b>Errno</b></td><td><pre>{$errno['type']}</pre></td></tr>";
    $body .= "<tr valign='top'><td><b>File</b></td><td>{$errno['file']}</td></tr>";
    $body .= "<tr valign='top'><td><b>Line</b></td><td>{$errno['line']}</td></tr>";
    $body .= '</tbody></table>';
    $body .= '<br />$_POST<br/>';
    $body .= print_r($_POST,true);
    $body .= '<br />$_SERVER<br/>';
    $body .= print_r($_SERVER,true);
    if($CONFIG->attributes()->staging == 'true'){
    	die($body);
    }
    $_SESSION['error'] = 'We had trouble saving your entry. Please review your entry and try again. If this continues please contact us.';
    header('location: /503.html');
    die('@ 503 Service Temporarily Unavailable');
  }
}

set_include_path($_SERVER['DOCUMENT_ROOT']);  //SET THE INCLUDE PATH TO DOCUMENT ROOT SO THAT ALL INCLUDES ARE DONE RELATIVE TO WEB FOLDER
ini_set('display_errors',0); ini_set('error_reporting',0 );error_reporting(0);

$_CONF_FILE = "/config/config.xml.php";
$GLOBALS['CONFIG'] = LOADCONFIG($_CONF_FILE);
$GLOBALS['SITE'] = "";
$cfg_host = (string)$CONFIG->domain;
$GLOBALS['HTTP_HOST'] = (!empty($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:$cfg_host);

include_once 'database/pdo-db-manager.php';
include_once 'database/table-class.php';
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
include_once 'includes/processes/processes.php';

if(! empty($CONFIG->database->host) && ! empty($CONFIG->database->user) && ! empty($CONFIG->database->password) && ! empty($CONFIG->database->dbname)){
  $GLOBALS['DBobject'] = new DBmanager();
}

if(! empty($CONFIG->socialwall)){
  $GLOBALS['SOCIAL'] = INITSOCIALWALL($CONFIG);
}

$GLOBALS['SMARTY'] = INITSMARTY($CONFIG);

$staging = (string)$CONFIG->attributes()->staging;
if($staging === "true" || $_PUBLISHED == 0 ){
  $SMARTY->assign("staging",true);
}else{
  $SMARTY->assign("staging",false);
  $ga_id = (string) $CONFIG->google_analytics->id;
  $SMARTY->assign("ga_id", $ga_id);
}

if(isMobile()){
  $SMARTY->assign("mobile",true);
}


//SECTION HOLDS THE INIT FUNCTIONS
function LOADCONFIG($_CONF_FILE){
  if($CONFIG = simplexml_load_file($_SERVER['DOCUMENT_ROOT'] . $_CONF_FILE)){
    $debug = (string)$CONFIG->attributes()->debug;
    if($debug == 'true'){ ini_set('display_errors',1); ini_set('error_reporting',E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT); } //ENABLE ERRORS IF DEBUG ON
  }else{
    ini_set('display_errors',1);  echo "configuration file not loaded";  die(); //CONFIG DOES NOT EXIST. DIE AND TELL USER
  }
  return $CONFIG;
}

function INITSOCIALWALL($CONFIG){
  include_once 'includes/social/youtube.class.php';
  include_once 'includes/social/instagram.class.php';
  include_once 'includes/social/twitter.class.php';
  include_once 'includes/social/facebook.php';
  include_once 'includes/social/socialwall.class.php';
  
  $tag = $CONFIG->socialwall->tag;
  $table = $CONFIG->socialwall->table;
  $ads = $CONFIG->socialwall->ads == true?TRUE:FALSE;
  $instagram = $CONFIG->socialwall->attributes()->instagram == "true"?TRUE:FALSE;
  $facebook = $CONFIG->socialwall->attributes()->facebook == "true"?TRUE:FALSE;
  $youtube = $CONFIG->socialwall->attributes()->youtube == "true"?TRUE:FALSE;
  $twitter = $CONFIG->socialwall->attributes()->twitter == "true"?TRUE:FALSE;
  return new SocialWall($tag,$table,$ads,$instagram,$facebook,$youtube,$twitter);
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
    $SMARTY->force_compile = true;
    $SMARTY->caching = false;
  }
  return $SMARTY;
}
