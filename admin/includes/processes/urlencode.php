<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'admin/includes/functions/admin-functions.php';
global $DBobject;

$name = $_POST ['value'];
$url = urlSafeString( htmlspecialchars_decode($name, ENT_QUOTES));
$duplicated = null;

if (isset($_POST ['id']) && isset($_POST ['table']) && isset($_POST ['field']) ) {
	$duplicated = false;
	$pre = str_replace ( "tbl_", "", $_POST ['table'] );
	$sql = "SELECT {$pre}_id AS ID FROM {$_POST ['table']} WHERE {$_POST ['field']} = :url AND {$pre}_deleted IS NULL";
	if($res = $DBobject->wrappedSql($sql,array(':url'=>$url))){
		if ($res[0]['ID'] != $_POST ['id']){
			$duplicated = true;
		}
	}
}
echo json_encode ( array ("url" => $url, "duplicated" => $duplicated  ) );
die ();
