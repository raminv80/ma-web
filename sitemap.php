<?php
include "includes/functions/functions.php";
global $CONFIG,$SMARTY,$DBobject;

$SMARTY->debugging = false;
$SMARTY->force_compile = true;
$SMARTY->caching = false;

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domain = $protocol.$_SERVER['HTTP_HOST'];

$sql = "SELECT CONCAT('{$domain}/',cache_url) AS cache_url, cache_modified FROM cache_tbl_listing LEFT JOIN tbl_listing ON listing_object_id = cache_record_id WHERE cache_deleted IS NULL AND cache_published = 1 AND listing_deleted IS NULL AND AND listing_published = 1 AND listing_noindex != 1 ORDER BY cache_url";
$res = $DBobject->wrappedSql($sql);
$result = array();
foreach ($res as $r){
	if(!array_key_exists($r['cache_url'], $result)) $result["{$r['cache_url']}"] = $r;
}

$sql = "SELECT CONCAT('{$domain}/',product_url) AS cache_url, product_modified AS cache_modified FROM tbl_product WHERE product_deleted IS NULL AND product_published = 1 ORDER BY product_url ";
$res = $DBobject->wrappedSql($sql);
foreach ($res as $r){
	if(!array_key_exists($r['cache_url'], $result)) $result["{$r['cache_url']}"] = $r;
} 
$SMARTY->assign("pages",$result);

header('Content-Type: application/xml; charset=utf-8');
$page_tpl = "sitemap.tpl";
$SMARTY->display("extends:$page_tpl");
die();