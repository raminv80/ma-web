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
		foreach($CONFIG->group as $gp){	
			foreach($gp->section as $sp){
				if($sp->url == $arr[1] ){
					if(intval($sp->attributes()->level) >= intval($_SESSION['user']['admin']['level'])){
						//IF ADMIN HAS STORES
						if(intval($_SESSION['user']['admin']['level']) == 2 && !empty($_SESSION['user']['admin']['franchisee'])){
							// INJECT CONFIG FOR PROMOTIONS
							$a = array();
							foreach ($_SESSION['user']['admin']['franchisee'] as $strs){
								$a[] = $sp->franchiseefield . ' = ' . $strs['access_franchisee_id'];
							}
							$where =  implode(' OR ', $a);
							//CREATE WHERE CONFIG STRUCTURE WHICH INCLUDES ALL ADMIN FRANCHISEE AGAINST FRANCHISEEFIELD eg. (FRANCHISEE_ID = 4 OR FRANCHISEE_ID = 5)
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
							break 3;
						}
						if($sp->type == "PRODUCT"){
							$record = new Product($sp);
							$list = $record->getRecordList($parentID);
							$SMARTY->assign("list",$list);
							$SMARTY->assign("path",(string)$sp->url);
							$template = $sp->list_template;
							break 3;
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
							break 3;
						}
					}else{
						$template = "home.tpl";
						break 3;
					}
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
		foreach($CONFIG->group as $gp){
			foreach($gp->section as $sp){
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
							break 3;
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
							break 3;
						}
					}else{
						$template = "home.tpl";
						break 3;
					}
				}
			}
		}
		break 1;
	}

	if($arr[0] == 'delete' && $arr[1] != ""){
		foreach($CONFIG->group as $gp){
			foreach($gp->section as $sp){
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
						break 3;
					}
				}
			}
		}
		break 1;
	}
	
	foreach($CONFIG->group as $gp){
		foreach($gp->section as $sp){
			if((string) $sp->attributes()->subsection == 'true' ){
				$needle = str_replace("/","\/",$sp->url);
				if(preg_match("/^{$needle}/",$arr[0])){
					foreach($sp->subsection as $sb){
						$needle2 = str_replace("/","\/",$sb->url);
						if(preg_match("/^{$needle2}/",$arr[1])){
							
							$template = (string) $sb->template;
							$classTermObj = new ClassTermClass();
							$terms = $classTermObj->GetCurrentTerm();
							$SMARTY->assign("terms",$terms);
							$classes = $classTermObj->GetCurrentClasses();
							$SMARTY->assign("classes",$classes);
							
							if($arr[1] == 'new' || $arr[1] == 're-enrol' || $arr[1] == 'convert'){
								$_SESSION['makepayment'] = '';
								$enrol_obj = new Enrolment();
								$_SESSION['enrol']['step'] = (empty($_SESSION['enrol']['step']))?1:$_SESSION['enrol']['step'];
								
								//CLEAR SESSION WHEN NO USER_ID
								if((empty($arr[2]) && ($_SESSION['enrol']['step']) < 3) || (!empty($arr[2]) && !empty($_SESSION['enrol']['user']['user_id']) && $arr[2] != $_SESSION['enrol']['user']['user_id']) ){
									unset($_SESSION['user']['public']);
									unset($_SESSION['enrol']);
									session_regenerate_id();
									$enrol_obj->CreateCart();
								}
								if(!empty($arr[2])  && empty($_SESSION['enrol']['user']['user_id'])){
									
									$member_obj = new Member();
									$memberArr = $member_obj->GetMemberDetails($arr[2]);
									$_SESSION['enrol']['user'] = $memberArr['user'];
									$_SESSION['enrol']['address']['B'] = $memberArr['address'];
									$_SESSION['enrol']['students'] = $memberArr['students'];
									
									//FAKE USER LOGIN & CART INIT
									if(!empty($_SESSION['enrol']['user']['user_id'])){
										$_SESSION['user']['public'] = array (
												"id" => $_SESSION['enrol']['user']['user_id'],
												"gname" => $_SESSION['enrol']['user']['user_gname'],
												"surname" => $_SESSION['enrol']['user']['user_surname'],
												"email" => $_SESSION['enrol']['user']['user_email']
										);
										$cartArr = $enrol_obj->GetDataCart();
										if(empty($cartArr['cart_user_id'])){
											$enrol_obj->SetUserCart($_SESSION['enrol']['user']['user_id']);
										}
										
									}
									
								}
								
								$_SESSION['enrol']['convert'] = false;
								if($arr[1] == 'convert'){
									$_SESSION['enrol']['convert'] = true;
									$trialArr = $enrol_obj->GetTrialClassesByUserId($_SESSION['enrol']['user']['user_id']);
									$SMARTY->assign("selectedClasses",$trialArr['selectedClasses']);
									$_SESSION['enrol']['selectedStudent'] = $trialArr['selectedStudent'];
									$SMARTY->assign("selectedStudent",$trialArr['selectedStudent']);
									$_SESSION['enrol']['type'] = 'S';
									
									foreach ($trialArr['classterm'] as $ct){
											$response[] = $enrol_obj->AddToCart($ct['id'], $ct['attr'], 1, 0, null, 0, 1, 'S', true);
									}
								}
								
								if($arr[1] == 're-enrol'){
									$selectedClasses = $enrol_obj->GetMemberPreviousTermClasses($_SESSION['enrol']['user']['user_id']);
									$SMARTY->assign("selectedClasses",$selectedClasses);
									$_SESSION['enrol']['type'] = 'S';
								}
								
								$SMARTY->assign("step",$_SESSION['enrol']['step']);
								$SMARTY->assign("user",$_SESSION['enrol']['user']);
								$SMARTY->assign("type",$_SESSION['enrol']['type']);
								$SMARTY->assign("address",$_SESSION['enrol']['address']);
								$SMARTY->assign("students",$_SESSION['enrol']['students']);
								$SMARTY->assign('classtermstudents', $_SESSION['enrol']['classtermStudent']);
								$SMARTY->assign('additional', $_SESSION['enrol']['additional']);
								$cart = $enrol_obj->GetDataCart();
								$SMARTY->assign('cart',$cart);
								$totals = $enrol_obj->CalculateTotal();
								$SMARTY->assign('totals', $totals);
								$productsOnCart = $enrol_obj->GetDataProductsOnCart();
								$SMARTY->assign('productsOnCart',$productsOnCart);
								loadAdditional($sb);
							}
							
							
							if($arr[1] == 'existing' && !empty($arr[2])){
								$member_obj = new Member();
								$memberArr = $member_obj->GetMemberDetails($arr[2]);
								if (!empty($memberArr['user'])){
									$SMARTY->assign('user', $memberArr['user']);
									$SMARTY->assign('notes', $memberArr['notes']);
									$SMARTY->assign('emails', $memberArr['emails']);
									$address = array('B'=>$memberArr['address']);
									$SMARTY->assign('address', $address);
									$enrol_obj = new Enrolment();
									$studentArr = array();
									foreach ($memberArr['students'] as $student){
										$studentArr["{$student['student_id']}"] = $student;
										$studentArr["{$student['student_id']}"]['enrolments'] = $enrol_obj->GetStudentEnrolments($student['student_id']);
									}
									$SMARTY->assign('students', $studentArr);
									$payments = $enrol_obj->GetOrderHistoryByUser($arr[2]);
									$SMARTY->assign ( 'payments', $payments );
								}
							}
							
							
							if($arr[1] == 'make-payment' && !empty($arr[2])){
								$member_obj = new Member();
								
								$memberArr = $member_obj->RetrieveById($arr[2]);
								if(!empty($memberArr)){
									$addresses = $member_obj->GetUsersAddresses($memberArr['user_id']);
									$_SESSION['makepayment']['user'] = $memberArr;
									$_SESSION['address']['B'] = $addresses[0];
									$_SESSION['user']['public'] = array (
											"id" => $memberArr['user_id'],
											"gname" => $memberArr['user_gname'],
											"surname" => $memberArr['user_surname'],
											"email" => $memberArr['user_email']
									);
									$enrol_obj = new Enrolment();
									$cartArr = $enrol_obj->GetDataCart();
									if(!empty($cartArr['cart_user_id']) && $cartArr['cart_user_id'] != $memberArr['user_id']){
										session_regenerate_id();
										$enrol_obj->CreateCart();
									}
									$enrol_obj->SetUserCart($memberArr['user_id']);
									
									$SMARTY->assign('user', $memberArr);
									$SMARTY->assign("customProduct",$_SESSION['makepayment']['customProduct']);
									$SMARTY->assign("address",$_SESSION['address']);
									$SMARTY->assign('cart',$cartArr);
									$totals = $enrol_obj->CalculateTotal();
									$SMARTY->assign('totals', $totals);
									$productsOnCart = $enrol_obj->GetDataProductsOnCart();
									$SMARTY->assign('productsOnCart',$productsOnCart);
									$_SESSION['makepayment']['step'] = empty($_SESSION['makepayment']['step'])?5:$_SESSION['makepayment']['step'];
									$SMARTY->assign("step",$_SESSION['makepayment']['step']);
								}
							}
							break 4;
						}
					}
				}
			}
		}
	}
	$template = '404.tpl';
	break 1;
}

$menu = array();
foreach($CONFIG->group as $gp){
	foreach($gp->section as $sp){
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
			//	CUSTOM SECTIONS
			$customUrls= array();
			if($sp->type == "CUSTOM"){ 
				if(!empty($sp->file)){
					$class = (string)$sp->file;
					$obj = new $class($sp);
					$list = $obj->getList();
				}
				foreach($sp->subsection as $sub){
					$title =	(string) $sub->title;
					$url =	(string) $sub->url;
					if(!empty($title) && !empty($url) && empty($sub->attributes()->hidden) ){
						$customUrls["/admin/{$sp->url}/{$url}"] = $title;
					}
				}
				
			}
			$groupName = (string)$gp->attributes()->name;
			$menu["{$groupName}"]['section'][] = array("title"=>$sp->title,
											"url"=>"/admin/list/{$sp->url}",
											"list"=>$list,
											"addNewUrl"=>$addUrl,
											"customUrls"=>$customUrls
			);
		}
	}
}
$SMARTY->assign("menu", $menu);
$SMARTY->display("extends:page.tpl|$nav|$template");
die();

function loadAdditional($_conf){
	global $CONFIG,$_PUBLISHED,$SMARTY;

	foreach($_conf->additional as $ad){
		$tag = (string)$ad->tag;
		$name = (string)$ad->name;
		$data = (string)$ad->data;
		foreach($CONFIG->$tag as $lp){
			if($lp->attributes()->name == $name){
				$tmp_lp = new SimpleXMLElement($lp->asXML());
				foreach($ad->where as $e){
					$tag = (string)$e->attributes()->tag;
					$domdict = dom_import_simplexml($tmp_lp->$tag);
					$domcat  = dom_import_simplexml($e);
					$domcat  = $domdict->ownerDocument->importNode($domcat, TRUE);// Import the <cat> into the dictionary document
					$domdict->appendChild($domcat);// Append the <cat> to <c> in the dictionary
				}
				$class = (string)$tmp_lp->file;
				$obj = new $class('',$tmp_lp);
				$data2 = $obj->LoadTree($lp->root_parent_id);
				$SMARTY->assign($data,unclean($data2));
				break 1;
			}
		}
	}
	return true;
}
