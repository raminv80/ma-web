<?php
$request = explode("?",$_SERVER['REQUEST_URI'],2);
if(preg_match("/media-centre/",  $request[0]) && count($uri_parts = explode("/",trim($request[0],"/"))) == 2){
  $request[0] = strtolower("/".$uri_parts[0]."#".$uri_parts[1]);
  $lowercase_file_url = ((($_SERVER['SERVER_PORT'] == 443 || !empty($_SERVER['HTTPS']))?"https://":"http://") . $_SERVER['SERVER_NAME'] . implode("?",$request));
  header("HTTP/1.1 301 Moved Permanently");
  header("Location: $lowercase_file_url");
  exit();
}
if (preg_match('/[A-Z]/', $request[0])){
	$request[0] = strtolower($request[0]);
	$lowercase_file_url = ((($_SERVER['SERVER_PORT'] == 443 || !empty($_SERVER['HTTPS']))?"https://":"http://") . $_SERVER['SERVER_NAME'] . implode("?",$request));
  header("HTTP/1.1 301 Moved Permanently");
  header("Location: $lowercase_file_url");
  exit();
} 
/*
 * header("Pragma: no-cache"); header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1 header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
 */
include "includes/functions/functions.php";
global $CONFIG,$SMARTY,$DBobject;

$SMARTY->loadFilter('output', 'trimwhitespace');

if(!empty($_REQUEST['ldc'])){
	$cart_obj = new cart();
	$res = $cart_obj->ApplyDiscountCode($_REQUEST['ldc']);
}
if(!empty($_REQUEST['ad'])){
	$sql = "SELECT discount_code,discount_banner,discount_banner_mob,discount_description, discount_listing_id,discount_product_id FROM tbl_discount WHERE discount_code = 'SAVE25' AND discount_deleted IS NULL AND discount_published = 1 AND (discount_unlimited_use=1 OR discount_fixed_time < discount_used) AND discount_usergroup_id = 0 AND discount_user_id = 0 AND (discount_start_date IS NULL||discount_start_date<=NOW()||discount_start_date>='0000-00-00') AND (discount_start_date IS NULL||discount_end_date>= NOW()||discount_end_date >= '0000-00-00')";
	$res = $DBobject->wrappedSql($sql,array('code'=>$_REQUEST['ad']));
	if(!empty($res)){
		$_SESSION['ad_session'] = $res[0];
	}
}
if(!empty($_SESSION['ad_session'])){
	$_SESSION['smarty']['ad_session'] = $_SESSION['ad_session'];
}

// ASSIGN ALL STORED SMARTY VALUES
foreach($_SESSION['smarty'] as $key=>$val){
  $SMARTY->assign($key,$val);
}
$SMARTY->assign('DOMAIN', "http://" . $GLOBALS['HTTP_HOST']);
unset($_SESSION['smarty']);
$SMARTY->assign('error',$_SESSION['error']);
unset($_SESSION['error']); // ASSIGN ERROR MESSAGES FOR TEMPLATES
$SMARTY->assign('notice',$_SESSION['notice']);
unset($_SESSION['notice']); // ASSIGN NOTICE MESSAGES FOR TEMPLATES
$SMARTY->assign('post',$_SESSION['post']);
unset($_SESSION['post']); // ASSIGN POST FOR FORM VARIABLES
$SMARTY->assign('address',$_SESSION['address']); // ASSIGN ADDRESS FOR FORM VARIABLES
$SMARTY->assign('comments',$_SESSION['comments']); // ASSIGN COMMENTS FOR FORM VARIABLES
$SMARTY->assign('redirect',!empty($_SESSION['redirect'])?$_SESSION['redirect']:(!empty($_SESSION['post']['redirect'])?$_SESSION['post']['redirect']:$_SERVER['HTTP_REFERER']));
unset($_SESSION['redirect']); // ASSIGN REDIRECT URL VALUE AFTER LOGIN AND SHOW LOGIN MODAL
$SMARTY->assign('user',$_SESSION['user']['public']); // ASSIGN USER FOR TEMPLATES
$SMARTY->assign ( 'HTTP_REFERER', rtrim($_SERVER['HTTP_REFERER'],'/') );
$_URI = explode("?",$_SERVER['REQUEST_URI']);
$SMARTY->assign ( 'REQUEST_URI', rtrim($_URI[0],'/') );
$_request = htmlclean($_REQUEST);
$SMARTY->assign ( '_REQUEST', $_REQUEST);
$SMARTY->assign ( 'orderNumber', $_SESSION['orderNumber'] );
$SMARTY->assign ( 'ga_ec', $_SESSION['ga_ec'] );// ASSIGN JS-SCRIPTS TO GOOGLE ANALYTICS - ECOMMERCE (USED ON THANK YOU PAGE)
unset ( $_SESSION ['ga_ec'] );
$SMARTY->assign('shippostcode',$_SESSION['shippostcode']);

$COMP = json_encode($CONFIG->company);
$SMARTY->assign('COMPANY', json_decode($COMP,TRUE));

$token = getToken('frontend');
$SMARTY->assign('token',$token);
$SMARTY->assign('timestamp',time());
while(true){
  
  /******* Processes *******/
  $needle = str_replace("/","\/","process/");
  $haystack = $_request["arg1"];
  if(preg_match("/^{$needle}/",$haystack)){
    foreach($CONFIG->process as $sp){
      if($sp->url == $_request['arg1']){ $file = (string)$sp->file; include ($file); }
    }
    die();
  }
  
  /******* Goes to 404 *******/
  if($_request['arg1'] == '404'){ $template = loadPage($CONFIG->error404); break 1; }
  /******* Goes to 403 *******/
  if($_request['arg1'] == '403'){ $template = loadPage($CONFIG->error403); break 1; }
  /******* Goes to 503 *******/
  if($_request['arg1'] == '503'){ $template = loadPage($CONFIG->error503); break 1; }
  
  /******* Goes to home *******/
  if($_request['arg1'] == ''){
    $template = loadPage($CONFIG->index_page); 
    break 1; 
  }
  
  /**
   * ***** Goes to CHECKOUT ******
   */
  if($_request['arg1'] == 'checkout'){
    /* if(empty($_SESSION['user']['public']['id'])){
     $_SESSION['redirect'] = "/checkout";
     header("Location: /login");
     exit();
     } */
    if(empty($_SESSION['selectedShipping'])){
    		header("Location: /shopping-cart");
    		die();
    }
    $cart_obj = new cart();
    if($cart_obj->NumberOfProductsOnCart() < 1){
      header("Location: /");
      die();
    }
    $template = loadPage($CONFIG->checkout);
    $ship_obj = new ShippingClass();
    $methods = $ship_obj->getShippingMethods($cart_obj->NumberOfProductsOnCart());
    $SMARTY->assign ( 'shippingMethods', $methods );
    $validation = $cart_obj->ValidateCart();
    $SMARTY->assign('validation',$validation);
    $totals = $cart_obj->CalculateTotal();
    $SMARTY->assign('totals',$totals);
    $sql = "SELECT * FROM tbl_address WHERE address_user_id = :uid ORDER BY address_id";
    $addresses = $DBobject->wrappedSql($sql,array(
        ':uid'=>$_SESSION['user']['public']['id']
    ));
    $SMARTY->assign('addresses',$addresses);
    $sql = "SELECT DISTINCT postcode_state FROM tbl_postcode WHERE postcode_state != 'OTHE' ORDER BY postcode_state";
    $states = $DBobject->wrappedSql($sql);
    $SMARTY->assign('options_state',$states);
    // ASSIGN JS-SCRIPTS TO GOOGLE ANALYTICS - ENHANCED ECOMMERCE
    $SMARTY->assign ( 'ga_ec', $cart_obj->getJSCartitemsByCartId_GA() . "ga('ec:setAction','checkout', { 'step': 1 });" );
  
    break 1;
  }
  
  /**
   * ***** Goes to SHOPPING CART ******
   */
  if($_request['arg1'] == 'shopping-cart'){
    $template = loadPage($CONFIG->cart);
    $cart_obj = new cart();
    $ship_obj = new ShippingClass();
    $itemNumber = $cart_obj->NumberOfProductsOnCart();
    $methods = $ship_obj->getShippingMethods($itemNumber);
    $SMARTY->assign ( 'shippingMethods', $methods );
    if(!empty($_SESSION['reApplydiscount']) && $itemNumber){		//RE-APPLY DISCOUNT CODE
      $res = $cart_obj->ApplyDiscountCode($_SESSION['reApplydiscount']);
      if ($res['error']) {
      		$_SESSION['error']= $res['error'];
      		$_SESSION['post']= $_POST;
      }
      $_SESSION['reApplydiscount'] = '';
      unset($_SESSION['reApplydiscount']);
      header('Location: /shopping-cart');
      die();
    }
    $validation = $cart_obj->ValidateCart();
    $SMARTY->assign('validation',$validation);
    $totals = $cart_obj->CalculateTotal();
    $SMARTY->assign('totals',$totals);
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
  		if(file_exists($file))
  		{ include ($file);}
  		else{ searchcms($_REQUEST); }
	   	break 2;
    }
  }
  

  /**
   * ***** Product pages here ******
   */
  foreach($CONFIG->product_page as $lp){
  	if(empty($lp->url)){continue;}
  	$needle = str_replace("/","\/",$lp->url);
  	$haystack = $_request["arg1"];
  	if(preg_match("/^{$needle}/",$haystack)){
  		$_nurl = $_request["arg1"];
  		$class = (string)$lp->file;
  		$obj = new $class($_nurl,$lp);
  		$template = $obj->Load(null,$_PUBLISHED);
  		$menu = $obj->LoadMenu($lp->pageID);
  		$SMARTY->assign('menuitems',$menu);
  		$obj->LoadAssociatedProducts();
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
  
  /**
   * ***** Listing pages here ******
   */
  $arr = explode("/",$_request["arg1"]);
  foreach($CONFIG->listing_page as $lp){
    if(empty($lp->url)){continue;}
    $needle = str_replace("/","\/",$lp->url);
    $haystack = $_request["arg1"];
    if(preg_match("/^{$needle}/",$haystack)){
    	foreach($lp->process as $sp){
    		$file = (string)$sp->file;
    		if(file_exists($file))	{ include ($file);}
    	}
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
      /**
       * **** load dynamic page *****
       */
    	foreach($struct->process as $sp){
    		$file = (string)$sp->file;
    		if(file_exists($file))	{ include ($file);}
    	}
      $template = $obj->Load($id,$_PUBLISHED);
			$menu = $obj->LoadMenu($id);
      $SMARTY->assign('menuitems',$menu);
      break 1;
    }
  }
  $template = loadPage($CONFIG->error404);
  break 1;
}


/**
 * ***************************************
 * Load Data Shopping Cart for all pages *
 * ***************************************
 */
$cart_obj = new cart();
if(!empty($_SESSION['user']['public']['store_id'])){
  $storeId = $_SESSION['user']['public']['store_id'];
  $sql = "SELECT listing_name, location_phone FROM tbl_listing LEFT JOIN tbl_location ON listing_id = location_listing_id WHERE listing_object_id = :id AND listing_deleted IS NULL AND listing_published = 1";
  $params = array(":id"=>$storeId);
  $res = $DBobject->wrappedSql($sql,$params);
  $SMARTY->assign("storename",$res[0]['listing_name']);
}
$itemNumber = $cart_obj->NumberOfProductsOnCart();
$SMARTY->assign('itemNumber',$itemNumber);
$cart = $cart_obj->GetDataCart();
$SMARTY->assign('cart',$cart);
$subtotal = $cart_obj->GetSubtotal();
$SMARTY->assign('subtotal',$subtotal);
$productsOnCart = $cart_obj->GetDataProductsOnCart();
$SMARTY->assign('productsOnCart',$productsOnCart);
if($CONFIG->checkout->attributes()->guest == 'true'){
  $SMARTY->assign('allowGuest',true);
}

if(empty($template)){
  $template = loadPage($CONFIG->error404);
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
  foreach($_conf->process as $sp){
  	$file = (string)$sp->file;
  	if(file_exists($file))	{ include ($file);}
  }
  if(strtolower((string)$_conf->attributes()->standalone) == 'true'){
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
	global $CONFIG,$_PUBLISHED,$SMARTY,$_request;
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
						$path = $parent .'->'. $child;
						$value = (string)$up->value;
						if(!empty($lp->$path)){
							$lp->$path = $value;
						}else{
							$lp->$parent->addChild($child, $value);
						}
					}
					$class = (string)$lp->file;
					$obj = new $class($_request["arg1"],$lp);
					$data2 = $obj->LoadTree($lp->root_parent_id);
					$SMARTY->assign($data,unclean($data2));
					break 1;
				}
			}
		}
	}
}