<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once 'admin/includes/functions/admin-functions.php';
global $DBobject;

$usr = $_POST['username'];
$error = true;

$sql = "SELECT admin_id AS ID from tbl_admin WHERE admin_username = :user";
$res = $DBobject->wrappedSqlGet($sql,array("user" => $usr));

if ($res[0]['ID'] == $_POST ['id']){
	$error = false;
}
	
echo json_encode(array("email"=>$error));
die();

