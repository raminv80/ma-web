<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once 'includes/functions/functions.php';

global $DBobject;

if(empty($_SESSION['user']['admin']['id'])) die('Restricted area');

$domain = "http://".$_SERVER['HTTP_HOST'].'/';

$sql = "SELECT wishlist_user_id AS 'Member ID', product_name AS 'Product name', CONCAT('{$domain}',product_url) AS 'Link', wishlist_modified AS 'Date-time' FROM tbl_wishlist 
        LEFT JOIN tbl_product ON product_object_id = wishlist_product_object_id
        WHERE wishlist_deleted IS NULL AND product_deleted IS NULL AND product_published = 1 
        ORDER BY wishlist_user_id DESC, wishlist_modified DESC";
$res = $DBobject->wrappedSql($sql);

$csv = AssociativeArrayToCSV(unclean($res));

$filename = "wish-list_".date('Y-m-d').".csv";
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Length: " . strlen($csv));
header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename=" . $filename);
echo $csv;
die();
