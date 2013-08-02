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

	/******* Goes to home *******/
	if($_request['arg1'] == ''){
		$page_obj = new Page();
		$page_obj->LoadPage($CONFIG->index_page);
		$template = $CONFIG->index_page->template;
		break 1;
	}

	/******* Goes to search *******/
	if($_request['arg1'] == 'search'){
		$page_obj = new Page();
		$page_obj->LoadPage($CONFIG->search);
		$template = $CONFIG->search->template;
		searchcms($_REQUEST['search']);
		break 1;
	}

	/****** Goes to individual script pages *******/
	foreach($CONFIG->static_page as $sp){
		if($sp->url == $_request['arg1'] ){
			$page_obj = new Page();
			$page_obj->LoadPage($sp);
			$template = $sp->template;
			break 2;
		}
	}

	/******* Listing pages here *******/
	$arr = explode("/", $_request["arg1"]);
	foreach($CONFIG->listing_page as $lp){
		if($lp->url == $arr[0] ){
			$_nurl = ltrim($_request["arg1"],$arr[0]);
			$class = (string)$lp->file;
			$obj = new $class($_nurl,$lp);
			$template = $obj->Load();
			$page_obj = new Page();

			$page_obj->LoadPage($lp);

			break 2;
		}
	}

	/******* Dynamic Page Check Here *******/
	//$urls = $DBobject->GetColumn('page_url', 'tbl_page', '');
	$sql = "SELECT listing_url FROM tbl_listing";
	$urls = $DBobject->wrappedSqlGet($sql);
	foreach ($urls as $url){
		if(in_array($_request['arg1'],$url)){
			$sql = "SELECT listing_id FROM tbl_listing WHERE listing_url = ?";
			$res = $DBobject->wrappedSqlGet($sql,array($_request['arg1']));
			//$p_id =  $res[0]['listing_id'];
			$page_obj = new Page();
			//$p_id->page_strut->table = 'tbl_listing';
			$p_id->page_strut->table->name='tbl_listing';
			$p_id->page_strut->table->orderby='listing_order';
			$p_id->pageID = $res[0]['listing_id'];
			$page_obj->LoadPage($p_id);
			$template = "body.tpl";
			break 2;
		}
	}
	$template = '404.tpl';
	break 1;
}



$SMARTY->display("extends:page.tpl|head.tpl|$template");
die();
