<?php
set_include_path( $_SERVER['DOCUMENT_ROOT'] );
ini_set( 'session.gc_maxlifetime', 7200 );

require_once 'vendor/autoload.php';
include_once 'database/fatal_handler.php';
include_once 'includes/functions/environment.php';

if ( APP_ENV === 'stage' || APP_ENV === 'production' ) {
    ini_set( 'display_errors', 0 );
    ini_set( 'error_reporting', 0 );
    error_reporting( 0 );
}else{
    error_reporting( E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT );
}

if ( $GLOBALS['CONFIG'] = simplexml_load_file( $_SERVER['DOCUMENT_ROOT'] . "/admin/config/config.xml.php" ) ) {
    if ( APP_DEBUG && APP_ENV==="development" ) {
        ini_set( 'display_errors', 1 );
        ini_set( 'error_reporting', E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT );
    }
} else {
    ini_set( 'display_errors', 1 );
    echo "configuration file not loaded";
    die();
}

include_once 'admin/includes/functions/admin-functions-general.php';
include_once 'admin/includes/classes/listing-class.php';
include_once 'admin/includes/classes/record-class.php';

include_once 'database/pdo-db-manager.php';
include_once 'database/utilities.php';

$GLOBALS['EDITED']    = 'edited';
$GLOBALS['DELETED']   = 'deleted';
$GLOBALS['WARNING']   = 'warning';
$GLOBALS['ERROR']     = 'error';
$GLOBALS['HTTP_HOST'] = ( ! empty( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : "" );

session_start();
$_REQUEST = clean( $_REQUEST );
$_POST    = clean( $_POST );
$_GET     = clean( $_GET );
$DBobject = new DBmanager( DB_HOST, DB_NAME, DB_USER, DB_PASSWORD );

$SMARTY               = new Smarty;
$SMARTY->error_reporting = E_ALL & ~E_NOTICE;
$SMARTY->template_dir = rtrim( $_SERVER['DOCUMENT_ROOT'], '/' ) . "/admin" . $CONFIG->smartytemplate_config->templates;                    // name of directory for templates
$SMARTY->compile_dir  = rtrim( $_SERVER['DOCUMENT_ROOT'], '/' ) . "/admin" . $CONFIG->smartytemplate_config->templates_c;     // name of directory for compiled templates
$SMARTY->plugins_dir  = array(
    $_SERVER['DOCUMENT_ROOT'] . "" . $CONFIG->smartytemplate_config->plugins,
    $_SERVER['DOCUMENT_ROOT'] . "/smarty/plugins"
);  // plugin directories
$SMARTY->cache_dir    = $_SERVER['DOCUMENT_ROOT'] . "" . $CONFIG->smartytemplate_config->cache;                    // name of directory for cache
if ( APP_DEBUG ) {
    $SMARTY->debugging     = true;
    $SMARTY->force_compile = true;
    $SMARTY->caching       = false;

} else {
    $SMARTY->debugging     = false;
    $SMARTY->force_compile = false;
    $SMARTY->caching       = false;
}

if ( APP_ENV === 'staging' ) {
    $SMARTY->assign( "staging", true );
} else {
    $SMARTY->assign( "staging", false );
}

//COMPANY INFO FROM CONFIG
$COMP = json_encode( $CONFIG->company );
$SMARTY->assign( 'COMPANY', json_decode( $COMP, true ) );

//GLOBAL VARIABLES FROM CONFIG
foreach ( $CONFIG->global_variable as $gv ) {
    $GLOBALS['CONFIG_VARS'][ (string) $gv->name ] = (string) $gv->value;
}
$SMARTY->assign( "CONFIG_VARS", $GLOBALS['CONFIG_VARS'] );
