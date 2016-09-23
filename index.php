<?php
$request = explode("?",$_SERVER['REQUEST_URI'],2);
if (preg_match('/[A-Z]/', $request[0])){
	$request[0] = strtolower($request[0]);
	$lowercase_file_url = ((($_SERVER['SERVER_PORT'] == 443 || !empty($_SERVER['HTTPS']))?"https://":"http://") . $_SERVER['SERVER_NAME'] . implode("?",$request));
  header("HTTP/1.1 301 Moved Permanently");
  header("Location: $lowercase_file_url");
  exit();
} 

include "includes/functions/functions.php";
global $CONFIG,$SMARTY,$DBobject,$REQUEST_URI;

$SMARTY->loadFilter('output', 'trimwhitespace');

// ASSIGN ALL STORED SMARTY VALUES
foreach($_SESSION['smarty'] as $key=>$val){
  $SMARTY->assign($key,$val);
}
$SMARTY->assign('DOMAIN', "http://" . $GLOBALS['HTTP_HOST']);
$SMARTY->assign('tempvars', $_SESSION['tempvars']);
unset($_SESSION['smarty']);
$SMARTY->assign('error', $_SESSION['error']);
unset($_SESSION['error']); // ASSIGN ERROR MESSAGES FOR TEMPLATES
$SMARTY->assign('notice', $_SESSION['notice']);
unset($_SESSION['notice']); // ASSIGN NOTICE MESSAGES FOR TEMPLATES
$SMARTY->assign('post', $_SESSION['post']);
unset($_SESSION['post']); // ASSIGN POST FOR FORM VARIABLES
$SMARTY->assign('user', $_SESSION['user']['public']); // ASSIGN USER FOR TEMPLATES
$SMARTY->assign('new_user', $_SESSION['user']['new_user']); // 
$SMARTY->assign('HTTP_REFERER', rtrim($_SERVER['HTTP_REFERER'],'/'));
$SMARTY->assign('REQUEST_URI', $REQUEST_URI);
$_request = htmlclean($_REQUEST);
$SMARTY->assign('_REQUEST', $_REQUEST);
$SMARTY->assign('orderNumber', $_SESSION['orderNumber']);
$SMARTY->assign('ga_ec', $_SESSION['ga_ec']);// ASSIGN JS-SCRIPTS TO GOOGLE ANALYTICS - ECOMMERCE (USED ON THANK YOU PAGE)
unset($_SESSION['ga_ec']);


$token = getToken('frontend');
$SMARTY->assign('token', $token);
$SMARTY->assign('timestamp', time());



while(true){
  
  /******* Processes *******/
  $needle = str_replace("/","\/","process/");
  $haystack = $_request["arg1"];
  if(preg_match("/^{$needle}/", $haystack)){
    foreach($CONFIG->process as $sp){
      if($sp->url == $_request['arg1']){ $file = (string)$sp->file; include ($file); }
    }
    die();
  }
  
  /******* Goes to 404 *******/
  if($_request['arg1'] == '404'){ $template = loadPage($CONFIG->error404); break 1; }
  /******* Goes to 403 *******/
  if($_request['arg1'] == '403'){ $template = loadPage($CONFIG->error403); break 1; }
  

  /******* Global process - pre data load *******/
  foreach($CONFIG->global_process_pre as $sp){
  	$file = (string)$sp->file;
  	if(file_exists($file)){ include ($file); }
  }
  
  /******* Goes to home *******/
  if($_request['arg1'] == ''){
    $template = loadPage($CONFIG->index_page); 
    break 1; 
  }
    
  /**
   * **** Goes to individual script pages ******
   */
  foreach($CONFIG->static_page as $sp){
    if($sp->url == $_request['arg1']){
      $template = loadPage($sp);
      break 2;
    }
  }
   
  /**
   * ***** Goes to search ******
   */
  foreach($CONFIG->search as $sp){
    if($sp->url == $_request['arg1']){
      $template = loadPage($sp);
  	  $file = (string)$sp->file;
  	  if(file_exists($file)){ 
  	    include ($file); 
  	  }else{ 
  	    searchcms($_REQUEST); 
  	  }
	  break 2;
    }
  }

  /**
   * ***** Listing pages here ******
   */
  $arr = explode("/", $_request["arg1"]);
  foreach($CONFIG->listing_page as $lp){
    if(empty($lp->url)){continue;}
    $needle = str_replace("/","\/",$lp->url);
    $haystack = $_request["arg1"];
    if(preg_match("/^{$needle}/",$haystack)){
      $_nurl = $_request["arg1"];
      $class = (string)$lp->file;
      $obj = new $class($_nurl,$lp);
      $template = $obj->Load((!empty($lp->root_parent_id)?$lp->root_parent_id:null),$_PUBLISHED);
      $menu = $obj->LoadMenu($lp->root_parent_id);
      $SMARTY->assign('menuitems',$menu);
      $fieldname = (string) $lp->url->attributes()->requiredvar;
      if(!empty($fieldname) && empty($_REQUEST[$fieldname])) {
      	$_request["arg1"] = '404';
      	$template = "";
      }
      foreach($lp->process as $sp){
        $file = (string)$sp->file;
        if(file_exists($file)){ include ($file);}
      }
      break 2;
    }
  }
  
  /**
   * ***** Dynamic Page Check Here ******
   */
  $_request["arg1"] = rtrim($_request["arg1"],'/');
  if(!empty($CONFIG->page_strut)){
    $struct = $CONFIG->page_strut;
    $class = (string)$struct->file;
    $obj = new $class($_request["arg1"],$struct);
    $id = $obj->ChkCache($_request["arg1"],$_PUBLISHED);
    if(! empty($id)){
      /**
       * **** Goes to individual script pages ******
       */
      foreach($CONFIG->static_page as $sp){
        if($sp->pageID == $id){
          $template = loadPage($sp);
          break 2;
        }
      }
      
      /******* Goes to 404 *******/
      if($CONFIG->error404->pageID == $id){ $template = loadPage($CONFIG->error404); break 1; }
      /******* Goes to 403 *******/
      if($CONFIG->error403->pageID == $id){ $template = loadPage($CONFIG->error403); break 1; }
      
      /**
       * **** load dynamic page *****
       */
      $template = $obj->Load($id,$_PUBLISHED);
	  $menu = $obj->LoadMenu($id);
      $SMARTY->assign('menuitems',$menu);
      foreach($struct->process as $sp){
        $file = (string)$sp->file;
        if(file_exists($file))	{ include ($file);}
      }
      break 1;
    }
  }
  
  /**
   * ***** Product pages here ******
   */
  foreach($CONFIG->product_page as $lp){
    if(empty($_request["arg1"])){continue;}
    $needle = str_replace("/","\/",$lp->url);
    $haystack = $_request["arg1"];
    if(preg_match("/^{$needle}/",$haystack)){
      $_nurl = $_request["arg1"];
      $class = (string)$lp->file;
      $obj = new $class($_nurl,$lp);
      $template = $obj->Load(null,$_PUBLISHED);
      $menu = $obj->LoadMenu($lp->pageID);
      $SMARTY->assign('menuitems',$menu);
      foreach($lp->process as $sp){
        $file = (string)$sp->file;
        if(file_exists($file))	{ include ($file);}
      }
      $fieldname = (string) $lp->url->attributes()->requiredvar;
      if(!empty($fieldname) && empty($_REQUEST[$fieldname])) {
        $_request["arg1"] = '404';
        $template = "";
      }
      break 2;
    }
  }
  
  $template = loadPage($CONFIG->error404);
  break 1;
}

if(empty($template)){
  $template = loadPage($CONFIG->error404);
}

/******* Global process - post data load *******/
foreach($CONFIG->global_process_post as $sp){
  $file = (string)$sp->file;
  if(file_exists($file)){ include ($file); }
}

$page_tpl = "page.tpl";
$SMARTY->display("extends:$page_tpl|$template");
die();


function loadPage($_conf){
  global $CONFIG,$_PUBLISHED,$SMARTY,$_request;
  if(!empty($_conf->header)){
     header($_conf->header);
  }else{
    header("HTTP/1.0 200 OK");
  }
  if(strtolower((string)$_conf->attributes()->standalone) == 'true'){
    foreach($_conf->process as $sp){
      $file = (string)$sp->file;
      if(file_exists($file))	{ include ($file);}
    }
  	$template = $_conf->template;
    $SMARTY->display("extends:$template");
    die();
  }else{
    if(!empty($CONFIG->page_strut)){
      $struct = $CONFIG->page_strut;
      foreach($_conf->associated as $a){
        $domdict = dom_import_simplexml($struct->table);
        $domcat  = dom_import_simplexml($a);
        $domcat  = $domdict->ownerDocument->importNode($domcat, TRUE);// Import the <cat> into the dictionary document
        $domdict->appendChild($domcat);// Append the <cat> to <c> in the dictionary
      }
      foreach($_conf->options as $o){
      	if(!empty($struct->table->options)){
      		foreach($o->field as $of){
      			$domdict = dom_import_simplexml($struct->table->options);
      			$domcat  = dom_import_simplexml($of);
      			$domcat  = $domdict->ownerDocument->importNode($domcat, TRUE);
      			$domdict->appendChild($domcat);
      		}
      	}else{
      		$domdict = dom_import_simplexml($struct->table);
      		$domcat  = dom_import_simplexml($o);
      		$domcat  = $domdict->ownerDocument->importNode($domcat, TRUE);// Import the <cat> into the dictionary document
      		$domdict->appendChild($domcat);// Append the <cat> to <c> in the dictionary
      	}
      }
      foreach($_conf->extends as $e){
        $domdict = dom_import_simplexml($struct->table);
        $domcat  = dom_import_simplexml($e);
        $domcat  = $domdict->ownerDocument->importNode($domcat, TRUE);// Import the <cat> into the dictionary document
        $domdict->appendChild($domcat);// Append the <cat> to <c> in the dictionary
      }
      $class = (string)$struct->file;
      $obj = new $class($_request["arg1"],$struct);
      $template = $obj->Load($_conf->pageID,$_PUBLISHED);
      if(!empty($template)){
      	foreach($struct->process as $sp){
      		$file = (string)$sp->file;
      		if(file_exists($file))	{ include ($file);}
      	}
        $template = $_conf->template;
        $menu = $obj->LoadMenu($_conf->pageID);
        $SMARTY->assign('menuitems',$menu);
        $SMARTY->assign('requested_page',$_SERVER['HTTP_REFERER']);
        loadPageAdditional($struct->table);
      }
    }
    foreach($_conf->process as $sp){
      $file = (string)$sp->file;
      if(file_exists($file))	{ include ($file);}
    }
  }
  loadPageAdditional($_conf);
  $fieldname = (string) $_conf->url->attributes()->requiredvar; 
  if(!empty($fieldname) && empty($_REQUEST[$fieldname])) { 
  	$_request["arg1"] = '404';
  	return "";
  }
  return $template;
}


function loadPageAdditional($_conf){
  global $CONFIG, $_PUBLISHED, $SMARTY, $_request;
  if(!empty($_conf)){
    foreach($_conf->additional as $ad){
      $tag = (string)$ad->tag;
      $name = (string)$ad->name;
      $data = (string)$ad->data;
      foreach($CONFIG->$tag as $lp){
        if($lp->attributes()->name == $name){
          foreach($ad->update as $up){
            $child = (string)$up->child;
            $parent = (string)$up->parent;
            $path = $parent . '->' . $child;
            $value = (string)$up->value;
            if(!empty($lp->$path)){
              $lp->$path = $value;
            } else{
              $lp->$parent->addChild($child, $value);
            }
          }
          $class = (string)$lp->file;
          $obj = new $class($_request["arg1"], $lp);
          $data2 = $obj->LoadTree($lp->root_parent_id);
          $SMARTY->assign($data, unclean($data2));
          break 1;
        }
      }
    }
  }
}