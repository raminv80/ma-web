<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'admin/includes/functions/admin-functions.php';
global $DBobject;

$name = $_POST ['value'];
$url = urlSafeString ( $name );
$sql = "SELECT * FROM tbl_listing WHERE listing_url = :url AND listing_deleted IS NULL";
if($res = $DBobject->wrappedSql($sql,array(':url'=>$url))){
	$url = $url.'-'.date('MY',time());
}
echo json_encode ( array ("url" => $url ) );
die ();
