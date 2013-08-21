<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

ini_set('session.cache_limiter', 'private');
include "includes/functions/functions.php";
global $CONFIG,$SMARTY,$DBobject;

//ASSIGN ALL STORED SMARTY VALUES
foreach($_SESSION['smarty'] as $key => $val){
	$SMARTY->assign($key,$val);
}
//ASSIGN ERROR MESSAGES FOR TEMPLATES
$SMARTY->assign('error',$_SESSION['error']);
$SMARTY->assign('notice',$_SESSION['notice']);

$_SESSION['error'] = "";
unset($_SESSION['error']);
$_SESSION['notice'] = "";
unset($_SESSION['notice']);
$_SESSION['smarty'] = "";
unset($_SESSION['smarty']);
$_request = clean($_REQUEST);
//ProcessUpdateNewsStatus();

while(true){
	$struct = $CONFIG->page_strut;
	$class = (string)$struct->file;
	
	/******* Goes to home *******/
	if($_request['arg1'] == ''){
		$obj = new $class('',$struct);
		$template = $obj->Load($CONFIG->index_page->pageID);
		$template = $CONFIG->index_page->template;
		break 1;
	}

	/******* Goes to search *******/
	if($_request['arg1'] == 'search'){
		$obj = new $class('',$struct);
		$template = $obj->Load($CONFIG->search->pageID);
		$template = $CONFIG->search->template;
		searchcms($_REQUEST['search']);
		break 1;
	}

	/****** Goes to individual script pages *******/
	foreach($CONFIG->static_page as $sp){
		if($sp->url == $_request['arg1'] ){
			$obj = new $class('',$struct);
			$template = $obj->Load($sp->pageID);
			$template = $sp->template;
			break 2;
		}
	}

	/******* Listing pages here *******/
	$arr = explode("/", $_request["arg1"]);
	foreach($CONFIG->listing_page as $lp){
		if($lp->url == $arr[0] ){
			//Load PAGE information. Parts of this data may be updated by the Listing class
			$obj = new $class('',$struct);
			$template = $obj->Load($lp->pageID);
			
			$_nurl = ltrim($_request["arg1"],$arr[0]);
			$class = (string)$lp->file;
			$obj = new $class($_nurl,$lp);
			$template = $obj->Load();
			break 2;
		}
	}

	/******* Dynamic Page Check Here *******/

	$arr = explode("/", $_request["arg1"]);
	foreach($arr as $a){
		if($a !== end($arr)){
			$sql = "SELECT * FROM tbl_category WHERE tbl_category.category_deleted IS NULL AND EXISTS (SELECT listing_id FROM tbl_listing WHERE tbl_listing.listing_id = tbl_category.category_listing_id AND tbl_listing.listing_deleted IS NULL AND tbl_listing.listing_url = :url )";
			$params = array(":url"=>$a);
			if($DBobject->wrappedSqlGet($sql,$params)){
				continue;
			}else{
				break 1;
			}
		}else{
			$sql = "SELECT listing_id FROM tbl_listing WHERE tbl_listing.listing_url = :url AND tbl_listing.listing_deleted IS NULL ";
			if($res = $DBobject->wrappedSqlGet($sql,array(":url"=>$a))){
				$obj = new $class('',$struct);
				$template = $obj->Load($res[0]['listing_id']);
				$template = $struct->template;
				break 2;
			}else{
				break 1;
			}
		}
	}
	
	header("HTTP/1.0 404 Not Found");
	/******* Goes to home *******/
	$obj = new $class('',$struct);
	$template = $obj->Load($CONFIG->error404->pageID);
	$template = $CONFIG->error404->template;
	break 1;
}



$SMARTY->display("extends:page.tpl|$template");
die();
