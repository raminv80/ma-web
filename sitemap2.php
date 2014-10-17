<?php
include "includes/functions/functions.php";
global $CONFIG,$SMARTY,$DBobject;

$SMARTY->debugging = false;
$SMARTY->force_compile = true;
$SMARTY->caching = false;

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domain = $protocol.$_SERVER['HTTP_HOST'];

$sql = "SELECT CONCAT('{$domain}',cache_url) AS cache_url, cache_modified FROM cache_tbl_listing WHERE cache_deleted IS NULL AND cache_published = '1' ORDER BY cache_url ASC";
$res = $DBobject->wrappedSql($sql);
$SMARTY->assign("pages",$res);

header('Content-Type: application/xml; charset=utf-8');
$page_tpl = "sitemap.tpl";
$SMARTY->display("extends:$page_tpl");
die();