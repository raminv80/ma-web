<?php
$request = explode("?",$_SERVER['REQUEST_URI'],2);
if (preg_match('/[A-Z]/', $request[0])){
	$request[0] = strtolower($request[0]);
	$lowercase_file_url = ((($_SERVER['SERVER_PORT'] == 443 || !empty($_SERVER['HTTPS']))?"https://":"http://") . $GLOBALS['HTTP_HOST'] . implode("?",$request));
  header("HTTP/1.1 301 Moved Permanently");
  header("Location: $lowercase_file_url");
  exit();
} 
/*
 * header("Pragma: no-cache"); header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1 header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
 */
include "includes/functions/functions.php";
global $CONFIG,$SMARTY,$DBobject;

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
$SMARTY->assign ( 'orderNumber', $_SESSION['orderNumber'] );
$SMARTY->assign ( 'ga_ec', $_SESSION['ga_ec'] );// ASSIGN JS-SCRIPTS TO GOOGLE ANALYTICS - ECOMMERCE (USED ON THANK YOU PAGE)
unset ( $_SESSION ['ga_ec'] );

$token = getToken('frontend');
$SMARTY->assign('token',$token);
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
   * ***** Goes to Login-register ******
   */
  if($_request['arg1'] == 'login-register'){
  	if(!empty($_SESSION['user']['public']['id'])){
  		header("Location: /" . $CONFIG->login->fallback_redirect);
  		exit();
  	}
  	$template = loadPage($CONFIG->login);
  	break 1;
  }
  /**
   * **** Goes to my-account ******
   */
  if($_request['arg1'] == 'my-account'){
  	if($CONFIG->account->attributes()->restricted == 'true' && empty($_SESSION['user']['public']['id'])){
  		$_SESSION['redirect'] = "/my-account";
  		header("Location: /" . $CONFIG->account->fallback_redirect);
  		exit();
  	}
  	$template = loadPage($CONFIG->account);
  	$cart_obj = new cart();
  	$orders = $cart_obj->GetOrderHistoryByUser($_SESSION['user']['public']['id']);
  	$SMARTY->assign ( 'orders', $orders );
  	break 1;
  }
  
  /**
   * ***** Goes to search ******
   */
  if($_request['arg1'] == 'search'){
    $template = loadPage($CONFIG->search);
    searchcms($_GET['q']);
    break 1;
  }
  
  /**
   * ***** Goes to CHECKOUT ******
   */
  if($_request['arg1'] == 'checkout'){
    if(empty($_SESSION['user']['public']['id'])){
      $_SESSION['redirect'] = "/checkout";
      header("Location: /login-register");
      exit();
    }
    $template = loadPage($CONFIG->checkout);
    $cart_obj = new cart();
    if($cart_obj->NumberOfProductsOnCart() < 1){
    	header("Location: /");
    	exit();
    }
//     $ship_obj = new ShippingClass();
//     $methods = $ship_obj->getShippingMethods($cart_obj->NumberOfProductsOnCart());
//     $SMARTY->assign ( 'shippingMethods', $methods );
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
    //$ship_obj = new ShippingClass();
    //$methods = $ship_obj->getShippingMethods($cart_obj->NumberOfProductsOnCart());
    //$SMARTY->assign ( 'shippingMethods', $methods );
    if(!empty($_SESSION['reApplydiscount']) && !empty($_SESSION['user']['public']['id'])){		//RE-APPLY DISCOUNT CODE
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
   * ***** Product pages here ******
   */
  $arr = explode("/",$_request["arg1"]);
  foreach($CONFIG->product_page as $lp){
    if(empty($lp->url)){continue;}
    $needle = str_replace("/","\/",$lp->url);
    $haystack = $_request["arg1"];
    if(preg_match("/^{$needle}/",$haystack)){
      $_nurl = $_request["arg1"];
      $class = (string)$lp->file;
      $obj = new $class($_nurl,$lp);
      $template = $obj->Load(null,$_PUBLISHED);
      $obj->LoadAssociatedProducts();
      $menu = $obj->LoadMenu($lp->pageID);
      $SMARTY->assign('menuitems',$menu);
      if($template == 'product.tpl'){
      	$cart_obj = new cart();
      	$listingParent = $SMARTY->getTemplateVars('listing_parent');
      	$SMARTY->assign('product_FullCategoryName', $cart_obj->getFullCategoryName($listingParent['listing_id']));
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
      $_nurl = $_request["arg1"];
      $class = (string)$lp->file;
      $obj = new $class($_nurl,$lp);
      $template = $obj->Load(null,$_PUBLISHED);
      $menu = $obj->LoadMenu($lp->pageID);
      $SMARTY->assign('menuitems',$menu);
      break 2;
    }
  }
  /**
   * ***** Dynamic Page Check Here ******
   */
  if(!empty($CONFIG->page_strut)){
    $struct = $CONFIG->page_strut;
    $class = (string)$struct->file;
    $obj = new $class('',$struct);
    $id = $obj->ChkCache($_request["arg1"],$_PUBLISHED);
    if(! empty($id)){
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
  global $CONFIG,$_PUBLISHED,$SMARTY;
  if(!empty($_conf->header)){
     header($_conf->header);
  }else{
    header("HTTP/1.0 200 OK");
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
        $domdict = dom_import_simplexml($struct->table);
        $domcat  = dom_import_simplexml($o);
        $domcat  = $domdict->ownerDocument->importNode($domcat, TRUE);// Import the <cat> into the dictionary document
        $domdict->appendChild($domcat);// Append the <cat> to <c> in the dictionary
      }
      foreach($_conf->extends as $e){
        $domdict = dom_import_simplexml($struct->table);
        $domcat  = dom_import_simplexml($e);
        $domcat  = $domdict->ownerDocument->importNode($domcat, TRUE);// Import the <cat> into the dictionary document
        $domdict->appendChild($domcat);// Append the <cat> to <c> in the dictionary
      }
      
      $class = (string)$struct->file;
      $obj = new $class('',$struct);
      $template = $obj->Load($_conf->pageID,$_PUBLISHED);
      if(!empty($template)){
        $template = $_conf->template;
        $menu = $obj->LoadMenu($_conf->pageID);
        $SMARTY->assign('menuitems',$menu);
        $SMARTY->assign('requested_page',$_SERVER['HTTP_REFERER']);
      }
    }
  }
  foreach($_conf->additional as $ad){
    $tag = (string)$ad->tag;
    $name = (string)$ad->name;
    $data = (string)$ad->data;
    foreach($CONFIG->$tag as $lp){
      if($lp->attributes()->name == $name){
        $class = (string)$lp->file;
        $obj = new $class('',$lp);
        $data2 = $obj->LoadTree($lp->root_parent_id);
        $SMARTY->assign($data,unclean($data2));
        break 1;
      }
    }
  }
 
  return $template;
}
