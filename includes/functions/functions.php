<?php
// Fatal Error Handler
# Registering shutdown function
register_shutdown_function( "fatal_handler" );
function fatal_handler() {
  # Getting last error
  $errno = error_get_last();
  if($errno['type'] === E_ERROR){
    $to = "nick@them.com.au,apolo@them.com.au";
    $from = "noreply@" . str_replace ( "www.", "", $_SERVER ['HTTP_HOST'] );
    $fromEmail = "noreply@" . str_replace ( "www.", "", $_SERVER ['HTTP_HOST'] );
    $subject = "Fatal Error Occured ";
    $body = "<table><thead bgcolor='#c8c8c8'><th>Item</th><th>Description</th></thead><tbody>";
    $body .= "<tr valign='top'><td><b>Error</b></td><td><pre>{$errno['message']}</pre></td></tr>";
    $body .= "<tr valign='top'><td><b>Errno</b></td><td><pre>{$errno['type']}</pre></td></tr>";
    $body .= "<tr valign='top'><td><b>File</b></td><td>{$errno['file']}</td></tr>";
    $body .= "<tr valign='top'><td><b>Line</b></td><td>{$errno['line']}</td></tr>";
    $body .= '</tbody></table>';
    $body .= '<br />$_POST<br/>';
    $body .= print_r ( $_POST,true );
    $body .= '<br />$_SERVER<br/>';
    $body .= print_r ( $_SERVER,true );
    /* To send HTML mail, you can set the Content-type header. */
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
    /* additional headers */
    $headers .= "From: " . $from . " <" . $fromEmail . ">\r\n";
    $headers .= "Bcc: cmsemails@them.com.au\r\n";
    ini_set ( 'sendmail_from', $fromEmail );
    try{
      $mailSent = mail ( $to, $subject, $body, $headers );
    }catch(Exception $e){
    }
    ini_restore ( 'sendmail_from' );
    $_SESSION ['error'] = 'We had trouble saving your entry. Please review your entry and try again. If this continues please contact us.';
    header('location: /503');
    die ('@ 503 Service Temporarily Unavailable');
  }
}


set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
ini_set ( 'display_errors', 1 );
error_reporting ( 1 );
if($GLOBALS ['CONFIG'] = simplexml_load_file ( $_SERVER ['DOCUMENT_ROOT'] . "/config/config.xml.php" )){
  $debug = ( string ) $CONFIG->attributes ()->debug;
  if($debug == 'true'){
    ini_set ( 'display_errors', 1 );
    ini_set ( 'error_reporting', E_ALL ^ E_NOTICE ^ E_WARNING );
  }
}else{
  ini_set ( 'display_errors', 1 );
  echo "configuration file not loaded";
  die ();
}

include 'database/db-manager.php';
include 'database/table-class.php';
include 'database/utilities.php';
include 'smarty/Smarty.class.php';

// include 'includes/classes/page-class.php';
include 'includes/classes/listing-class.php';
include 'includes/classes/product-class.php';
include 'includes/classes/user-class.php';
include 'includes/classes/cart-class.php';
include 'includes/classes/PayWay.php';

include 'includes/functions/functions-search.php';

require 'includes/social/youtube.class.php';
require 'includes/social/instagram.class.php';
require 'includes/social/twitter.class.php';
require 'includes/social/facebook.php';
require 'includes/social/socialwall.class.php';

include 'includes/processes/processes.php';

include 'includes/functions/mobile-functions.php';

session_start ();

$_REQUEST = clean ( $_REQUEST );
$_POST = clean ( $_POST );
$_GET = clean ( $_GET );
$DBobject = new DBmanager ();

if(! empty ( $CONFIG->socialwall )){
  $tag = $CONFIG->socialwall->tag;
  $table = $CONFIG->socialwall->table;
  $ads = $CONFIG->socialwall->ads == true ? TRUE : FALSE;
  $instagram = $CONFIG->socialwall->attributes ()->instagram == "true" ? TRUE : FALSE;
  $facebook = $CONFIG->socialwall->attributes ()->facebook == "true" ? TRUE : FALSE;
  $youtube = $CONFIG->socialwall->attributes ()->youtube == "true" ? TRUE : FALSE;
  $twitter = $CONFIG->socialwall->attributes ()->twitter == "true" ? TRUE : FALSE;
  $SOCIAL = new SocialWall ( $tag, $table, $ads, $instagram, $facebook, $youtube, $twitter );
}

// Create Smarty object and set
// paths within object
$SMARTY = new Smarty ();
$SMARTY->template_dir = $_SERVER ['DOCUMENT_ROOT'] . "/" . $CONFIG->smartytemplate_config->templates; // name of directory for templates
$SMARTY->compile_dir = $_SERVER ['DOCUMENT_ROOT'] . "/" . $CONFIG->smartytemplate_config->templates_c; // name of directory for compiled templates
$SMARTY->plugins_dir = array (
    $_SERVER ['DOCUMENT_ROOT'] . "/" . $CONFIG->smartytemplate_config->plugins,
    $_SERVER ['DOCUMENT_ROOT'] . "/smarty/plugins" 
); // plugin directories
$SMARTY->cache_dir = $_SERVER ['DOCUMENT_ROOT'] . "/" . $CONFIG->smartytemplate_config->cache; // name of directory for cache
if($debug == 'true'){
  $SMARTY->debugging = true;
  $SMARTY->force_compile = true;
  $SMARTY->caching = false;
}else{
  $SMARTY->debugging = false;
  $SMARTY->force_compile = true;
  $SMARTY->caching = false;
}

$staging = ( string ) $CONFIG->attributes ()->staging;
if($staging === "true"){
  $SMARTY->assign ( "staging", true );
}else{
  $SMARTY->assign ( "staging", false );
}

if(isMobile ()){
  $SMARTY->assign ( "mobile", true );
}

