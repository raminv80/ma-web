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

// ASSIGN ADMIN USER FOR TEMPLATES
$SMARTY->assign ( 'admin', $_SESSION['user']['admin'] );

$token = getToken('admin');
$SMARTY->assign('token',$token);

//HANDLE USER PERMISSSIONS TO VIEW SITE. REDIRECT ALL NON-LOGGED IN USERS TO LOGIN
if($_request['arg1']  == 'logout' ){
	$_SESSION = null;
	session_destroy();
	session_start();
}

if((!isset($_SESSION['user']['admin']) || empty($_SESSION['user']['admin']) ) && $_request['arg1']  != 'register' && $_request['arg1']  != 'login' && $_request['arg1']  != 'recover-password'){
	header("Location:/admin/login");
	die();
}

if(!empty($_SESSION['user']['admin']) && $_request['arg1']  == 'login'){
	die('here');
	header("Location:/admin");
	die();
}

if(!isset($_SESSION['user']['admin']) || empty($_SESSION['user']['admin']) ){
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
		$SMARTY->assign('them_news_domain',$CONFIG->them_news->domain);
		$SMARTY->assign('them_news_page',$CONFIG->them_news->page);
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
				if(intval($sp->attributes()->level) >= intval($_SESSION['user']['admin']['level'])){
					//IF ADMIN HAS STORES
					if(intval($_SESSION['user']['admin']['level']) == 2 && !empty($_SESSION['user']['admin']['stores'])){
						// INJECT CONFIG FOR PROMOTIONS
						$a = array();
						foreach ($_SESSION['user']['admin']['stores'] as $strs){
							$a[] = $sp->storefield . ' = ' . $strs['access_store_id'];
						}
						$where =  implode(' OR ', $a);
						//CREATE WHERE CONFIG STRUCTURE WHICH INCLUDES ALL ADMIN STORES AGAINST STOREFIELD eg. (STORE_ID = 4 OR STORE_ID = 5)
						//ADD WHERE CONFIG TO EXISTING WHERE IF ANY
					}
					
					
					$SMARTY->assign("zone",$sp->title);
					$SMARTY->assign ( "typeID", $sp->type_id );
					$parentID = 0;
					if ($sp->root_parent_id) {
						$SMARTY->assign ( "rootParentID", $sp->root_parent_id );
						$parentID = $sp->root_parent_id;
					}
					$template = "list.tpl";
					if($sp->type == "LISTING"){
						if(!empty($where)){
							if(empty($sp->where)){
								$sp->addChild ( 'where', $where );
							}else{
								$sp->where = $sp->table->where . " AND ( ". $where ." )";
							}
						}
						$record = new Listing($sp);
						$list = $record->getListingList($parentID);
						$SMARTY->assign("list",$list);
						$SMARTY->assign("path",(string)$sp->url);
						$template = $sp->list_template;
						break 2;
					}
					if($sp->type == "PRODUCT"){
						$record = new Product($sp);
						$list = $record->getRecordList($parentID);
						$SMARTY->assign("list",$list);
						$SMARTY->assign("path",(string)$sp->url);
						$template = $sp->list_template;
						break 2;
					}
					if($sp->type == "TABLE"){
						if(!empty($where)){
							if(empty($sp->table->where)){
								$sp->table->addChild ( 'where', $where );
							}else{
								$sp->table->where = $sp->table->where . " AND ( ". $where ." )";
							}
						}
						$record = new Record($sp);
						$list = $record->getRecordList();
						$SMARTY->assign("list",$list);
						$SMARTY->assign("path",(string)$sp->url);
						$template = $sp->list_template;
						break 2;
					}
				}else{
					$template = "home.tpl";
					break 2;
				}
			}
		}
		break 1;
	}

	/******* Goes to login  *******/
	if($arr[0] == 'edit' && $arr[1] != ""){
		/****** Goes to individual script pages *******/
		if(empty($arr[2])){
			foreach($CONFIG->sequence as $sp){
				$sql = "INSERT INTO {$sp->table} () VALUES ()";
				$DBobject->wrappedSql($sql);
				$objID = $DBobject->wrappedSqlIdentity();
			}
		}
		foreach($CONFIG->section as $sp){
			if($sp->url == $arr[1] ){
				if(intval($sp->attributes()->level) >= intval($_SESSION['user']['admin']['level']) ){
					$SMARTY->assign("zone",$sp->title);
					$SMARTY->assign ( "typeID", $sp->type_id );
					$SMARTY->assign ( "parentID", $sp->parent_id );
					$SMARTY->assign ( "rootParentID", $sp->root_parent_id );
					$SMARTY->assign ( "objID", $objID );
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
						foreach($sp->custom as $ct){
						  $f = $ct->attributes()->field;
						  $v = $ct->attributes()->value;
						  if($tm["{$f}"] == $v){
						    foreach($ct->associated as $a){
						      $domdict = dom_import_simplexml($sp);
						      $domcat  = dom_import_simplexml($a);
						      $domcat  = $domdict->ownerDocument->importNode($domcat, TRUE);// Import the <cat> into the dictionary document
						      $domdict->appendChild($domcat);// Append the <cat> to <c> in the dictionary
						    }
						    foreach($ct->options as $o){
						      $domdict = dom_import_simplexml($sp);
						      $domcat  = dom_import_simplexml($o);
						      $domcat  = $domdict->ownerDocument->importNode($domcat, TRUE);// Import the <cat> into the dictionary document
						      $domdict->appendChild($domcat);// Append the <cat> to <c> in the dictionary
						    }
						    foreach($ct->extends as $e){
						      $domdict = dom_import_simplexml($sp);
						      $domcat  = dom_import_simplexml($e);
						      $domcat  = $domdict->ownerDocument->importNode($domcat, TRUE);// Import the <cat> into the dictionary document
						      $domdict->appendChild($domcat);// Append the <cat> to <c> in the dictionary
						    }
						    $record = new Listing($sp);
    						$tm = $record->getListing(intval($arr[2]));
    						$SMARTY->assign("fields",$tm);
    						$template = $ct->template;
						    break;
						  }
						}
						break 2;
					}
					if($sp->type == "TABLE" || $sp->type == "PRODUCT"){
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
				}else{
					$template = "home.tpl";
					break 2;
				}
			}
		}
		break 1;
	}

	if($arr[0] == 'delete' && $arr[1] != ""){
		foreach($CONFIG->section as $sp){
			if($sp->url == $arr[1] ){
				if(intval($sp->attributes()->level) >= intval($_SESSION['user']['admin']['level']) ){
					if($sp->type == "LISTING"){
						$record = new Listing($sp);
						$res = $record->deleteListing($arr[2]);
					}
					if($sp->type == "TABLE" || $sp->type == "PRODUCT"){
						$record = new Record($sp);
						$res = $record->deleteRecord($arr[2]);
					}
					header("Location: {$_SERVER['HTTP_REFERER']}");
					die();
				}else{
					$template = "home.tpl";
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

foreach($CONFIG->section as $sp){
	if(intval($sp->attributes()->level) >= intval($_SESSION['user']['admin']['level']) ){
		$list = array();
		if($sp->type == "LISTING" && $sp->showlist != 'FALSE'){
			$record = new Listing($sp);
			$list = $record->getListingList();
		}
	
		if($sp->type == "TABLE" && $sp->showlist != 'FALSE'){
			$record = new Record($sp);
			$list = $record->getRecordList();
		}
		$addUrl = "/admin/edit/{$sp->url}";
		if((string)$sp->attributes()->hideadd == 'true'){
		  $addUrl = "";
		}
		$menu[] = array("title"=>$sp->title,"url"=>"/admin/list/{$sp->url}","list"=>$list,"addNewUrl"=>$addUrl);
	}
}
$SMARTY->assign("menu",$menu);
$SMARTY->display("extends:page.tpl|$nav|$template");
die();
