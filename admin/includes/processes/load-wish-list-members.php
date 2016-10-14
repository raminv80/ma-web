<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'admin/includes/functions/admin-functions.php';
global $CONFIG, $SMARTY, $DBobject;

$sql = "SELECT wishlist_user_id FROM tbl_wishlist WHERE wishlist_deleted IS NULL GROUP BY wishlist_user_id ORDER BY wishlist_user_id";
$list = $DBobject->wrappedSql($sql);
$SMARTY->assign("list",$list);