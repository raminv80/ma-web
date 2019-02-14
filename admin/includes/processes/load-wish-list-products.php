<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'admin/includes/functions/admin-functions.php';
global $CONFIG, $SMARTY, $DBobject;

$arr = explode("/", $_REQUEST["arg1"]);

$SMARTY->assign("wishlist_user_id", $arr[2]);
$params = array(':wishlist_user_id' => $arr[2]);

$sql = "SELECT tbl_product.*, tbl_gallery.*, wishlist_modified FROM tbl_wishlist 
        LEFT JOIN tbl_product ON product_object_id = wishlist_product_object_id
        LEFT JOIN tbl_gallery ON gallery_product_id = product_id
        WHERE wishlist_deleted IS NULL AND product_deleted IS NULL AND product_published = 1 
        AND gallery_deleted IS NULL AND wishlist_user_id = :wishlist_user_id 
        GROUP BY wishlist_product_object_id
        ORDER BY wishlist_modified DESC";
$products = $DBobject->wrappedSql($sql, $params);
$SMARTY->assign("products",$products);