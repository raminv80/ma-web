<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once 'admin/includes/functions/admin-functions.php';
global $DBobject;

$usr = $_POST['username'];
$duplicated = false;

$sql = "SELECT admin_id AS ID from tbl_admin WHERE admin_username = :user AND admin_deleted IS NULL";
$res = $DBobject->wrappedSqlGet($sql,array("user" => $usr));

if ($res[0]['ID'] != $_POST ['id'] && $res){
	$duplicated = true;
}
	
echo json_encode(array("email"=>$duplicated));
die();

