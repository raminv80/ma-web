<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

ini_set('session.cache_limiter', 'private');
include_once 'includes/functions/admin-functions.php';
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


$token = getToken();
$SMARTY->assign('token',$token);

//HANDLE USER PERMISSSIONS TO VIEW SITE. REDIRECT ALL NON-LOGGED IN USERS TO LOGIN
if($_request['arg1']  == 'logout' ){
	$_SESSION = null;
	session_destroy();
	session_start();
}

if((!isset($_SESSION['admin']) || empty($_SESSION['admin']) ) && $_request['arg1']  != 'register' && $_request['arg1']  != 'login' && $_request['arg1']  != 'recover-password'){
	header("Location:/new_admin/login");
}

if(!isset($_SESSION['admin']) || empty($_SESSION['admin']) ){
	$nav = "non-authd-nav.tpl|non-authd-footer.tpl";
}else{
	$nav = "nav.tpl|footer.tpl";
}

while(true){
	/******* Goes to login  *******/
	if($_request['arg1'] == 'login'){
		$template = "login.tpl";
		break 1;
	}
	
	/******* Goes to login  *******/
	if($_request['arg1'] == ''){
		$template = "home.tpl";
		break 1;
	}
	
	/******* Listing pages here *******/
	$arr = explode("/", $_request["arg1"]);
	/******* Goes to login  *******/
	if($arr[0] == 'list' && $arr[1] != ""){
		/****** Goes to individual script pages *******/
		foreach($CONFIG->admin->section as $sp){
			if($sp->url == $arr[1] ){
				if($sp->type == "LISTING"){
					$record = new Listing($sp);
					$list = $record->getListingList();
					$SMARTY->assign("list",$list);
					$template = $sp->list_template;
					break 2;
				}
				if($sp->type == "TABLE"){
					$record = new Record($sp);
					$list = $record->getRecordList();
					$SMARTY->assign("list",$list);
					$template = $sp->list_template;
					break 2;
				}
			}
		}
		break 1;
	}
	
	/******* Goes to login  *******/
	if($arr[0] == 'edit' && $arr[1] != ""){
		/****** Goes to individual script pages *******/
		foreach($CONFIG->admin->section as $sp){
			if($sp->url == $arr[1] ){
				if($sp->type == "LISTING"){
					$record = new Listing($sp);
					$tm = $record->getListing(intval($arr[2]));
					//$tm = $record->getListingBuilder(intval($arr[2]));
					$SMARTY->assign("fields",$tm);
					$template = $sp->edit_template;
					//$template = "edit_record_builder.tpl";
					break 2;
				}
				if($sp->type == "TABLE"){
					$record = new Record($sp);
					$tm = $record->getRecord(intval($arr[2]));
					$SMARTY->assign("fields",$tm);
					$template = $sp->edit_template;
					break 2;
				}
			}
		}
		break 1;
	}
	
	$template = '404.tpl';
	break 1;
}

$menu = array();

foreach($CONFIG->admin->section as $sp){
	if($sp->type == "LISTING"){
		$record = new Listing($sp);
		$list = $record->getListingList();
	}
	
	if($sp->type == "TABLE"){
		$record = new Record($sp);
		$list = $record->getRecordList();
	}
	$menu[] = array("title"=>$sp->title,"url"=>"/new_admin/list/{$sp->url}","list"=>$list);
}
$SMARTY->assign("menu",$menu);
$SMARTY->display("extends:page.tpl|$nav|$template");
die();
