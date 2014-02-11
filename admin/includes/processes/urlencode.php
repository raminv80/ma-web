<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'admin/includes/functions/admin-functions.php';
global $DBobject;

$name = $_POST ['value'];
$url = urlSafeString( htmlspecialchars_decode($name, ENT_QUOTES));
if (isset($_POST ['id'])) {
	$error = false;
	if ($_POST ['product']) {
		$sql = "SELECT product_id AS ID FROM tbl_product WHERE product_url = :url AND product_deleted IS NULL";
		if($res = $DBobject->wrappedSql($sql,array(':url'=>$url))){
			if ($res[0]['ID'] != $_POST ['id']){
				$error = true;
			}
		}
	} else {
		$sql = "SELECT listing_id AS ID FROM tbl_listing WHERE listing_url = :url AND listing_deleted IS NULL";
		if($res = $DBobject->wrappedSql($sql,array(':url'=>$url))){
			if ($res[0]['ID'] != $_POST ['id']){
					$error = true;
			}
		}
	}
}

echo json_encode ( array ("url" => $url, "error" => $error  ) );
die ();
