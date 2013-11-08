<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

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
	header("Location:/admin/login");
	die();
}

if(!empty($_SESSION['admin']) && $_request['arg1']  == 'login'){
	die('here');
	header("Location:/admin");
	die();
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
	if($_request['arg1'] == 'home' || $_request['arg1'] == ''){
		$template = "home.tpl";
		break 1;
	}

	foreach($CONFIG->resource as $sp){

		if($sp->url == $_request["arg1"] ){
			$template = $sp->template;
			break 2;
		}
	}

	/******* Listing pages here *******/
	$arr = explode("/", $_request["arg1"]);
	/******* Goes to login  *******/
	if($arr[0] == 'list' && $arr[1] != ""){
		/****** Goes to individual script pages *******/
		foreach($CONFIG->section as $sp){
			if($sp->url == $arr[1] ){
				$SMARTY->assign("zone",$sp->title);
				$template = "list.tpl";
				if($sp->type == "LISTING"){
					$record = new Listing($sp);
					$list = $record->getListingList();
					$SMARTY->assign("list",$list);
					$SMARTY->assign("path",(string)$sp->url);
					$template = $sp->list_template;
					break 2;
				}
				if($sp->type == "TABLE"){
					$record = new Record($sp);
					$list = $record->getRecordList();
					$SMARTY->assign("list",$list);
					$SMARTY->assign("path",(string)$sp->url);
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
		foreach($CONFIG->section as $sp){
			if($sp->url == $arr[1] ){
				$SMARTY->assign("zone",$sp->title);
				if($sp->type == "LISTING"){
					$record = new Listing($sp);
					$tm = $record->getListing(intval($arr[2]));
					$SMARTY->assign("fields",$tm);
					$template = $sp->edit_template;
					foreach($sp->custom_template as $ct){
						$f = $ct->attributes()->field;
						$v = $ct->attributes()->value;
						if($tm["{$f}"] == $v){
							$template = $ct;
							break;
						}
					}
					break 2;
				}
				if($sp->type == "TABLE"){
					$record = new Record($sp);
					$tm = $record->getRecord(intval($arr[2]));
					$SMARTY->assign("fields",$tm);
					$SMARTY->assign("type",(string)$sp->slide);
					$template = $sp->edit_template;
					foreach($sp->custom_template as $ct){
						$f = $ct->attributes()->field;
						$v = $ct->attributes()->value;
						if($tm["{$f}"] ===$v){
							$template = $ct;
							break;
						}
					}
					break 2;
				}
			}
		}
		break 1;
	}

	if($arr[0] == 'delete' && $arr[1] != ""){
		foreach($CONFIG->section as $sp){
			if($sp->url == $arr[1] ){
				if($sp->type == "LISTING"){
					$record = new Listing($sp);
					$res = $record->deleteListing($arr[2]);
				}
				if($sp->type == "TABLE"){
					$record = new Record($sp);
					$res = $record->deleteRecord($arr[2]);
				}
				header("Location: {$_SERVER['HTTP_REFERER']}");
				die();
			}
		}
		break 1;
	}

	$template = '404.tpl';
	break 1;
}

$menu = array();

foreach($CONFIG->section as $sp){
	$list = array();
	if($sp->type == "LISTING" && $sp->showlist != 'FALSE'){
		$record = new Listing($sp);
		$list = $record->getListingList();
	}

	if($sp->type == "TABLE" && $sp->showlist != 'FALSE'){
		$record = new Record($sp);
		$list = $record->getRecordList();
	}
	$menu[] = array("title"=>$sp->title,"url"=>"/admin/list/{$sp->url}","list"=>$list);
}
$SMARTY->assign("menu",$menu);
$SMARTY->display("extends:page.tpl|$nav|$template");
die();
