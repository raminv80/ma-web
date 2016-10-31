<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'admin/includes/functions/admin-functions.php';
global $DBobject, $SMARTY, $CONFIG;

$error = "Missing fields or session expired.";
$referer = parse_url($_SERVER['HTTP_REFERER']);
if($referer['host'] == $GLOBALS['HTTP_HOST'] && !empty($_SESSION['user']['admin']["id"]) && !empty($_GET['id'])){
  $sql = "SELECT * FROM tbl_email_queue WHERE email_id = :id ";
  if($res = $DBobject->wrappedSql( $sql, array(':id' => $_GET["id"]))){
  	$SMARTY->assign('content', unclean($res[0]['email_content']));
  	$SMARTY->display('email/view-email-content.tpl');
  	die();
  }
}
die();




