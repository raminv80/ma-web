<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once 'admin/includes/functions/admin-functions.php';
global $DBobject;

$usr = $_POST['username'];
$duplicated = false;

$sql = "SELECT {$_POST['table']}_id AS ID from tbl_{$_POST['table']} WHERE {$_POST['table']}_username = :user AND {$_POST['table']}_deleted IS NULL";
$res = $DBobject->wrappedSqlGet($sql,array("user" => $usr));

if ($res[0]['ID'] != $_POST ['id'] && $res){
	$duplicated = true;
}
	
echo json_encode(array("email"=>$duplicated));
die();

