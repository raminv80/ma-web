<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'admin/includes/functions/admin-functions.php';
global $DBobject;

// $name = $_POST ['value'];
$url = urlSafeString( htmlspecialchars_decode($_POST ['value'], ENT_QUOTES));
$duplicated = null;

if (isset($_POST ['id']) && isset($_POST ['table']) && isset($_POST ['field']) && isset($_POST ['field2'])) {
	$duplicated = false;
	$pre = str_replace ( "tbl_", "", $_POST ['table'] );
	$sql = "SELECT {$pre}_object_id AS ID FROM {$_POST ['table']} WHERE {$_POST ['field']} = :url AND {$_POST ['field2']} = :pid AND {$pre}_deleted IS NULL";
	if($res = $DBobject->wrappedSql($sql,array(':url'=>$url,':pid'=>$_POST ['value2']))){
		foreach ($res as $r){
			if ($r['ID'] != $_POST ['id']){
				echo json_encode ( array ("url" => $url, "duplicated" => true  ) );
				die ();
			}
		}
	}
}
echo json_encode ( array ("url" => $url, "duplicated" => $duplicated  ) );
die ();
