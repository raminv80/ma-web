<?php
include "includes/functions/functions.php";
global $CONFIG,$SMARTY,$DBobject;

$sql = "SELECT * FROM cache_tbl_listing WHERE cache_deleted IS NULL AND cache_published = '1' ORDER BY cache_url ASC";
$res = $DBobject->wrappedSql($sql);
$SMARTY->assign("pages",$res);

$page_tpl = "sitemap.tpl";
$SMARTY->display("extends:$page_tpl");
die();