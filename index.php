<?php
/*
 * header("Pragma: no-cache"); header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1 header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
 */
include "includes/functions/functions.php";
global $CONFIG, $SMARTY, $DBobject;

// ASSIGN ALL STORED SMARTY VALUES
foreach ( $_SESSION ['smarty'] as $key => $val ) {
	$SMARTY->assign ( $key, $val );
}
// ASSIGN ERROR MESSAGES FOR TEMPLATES
$SMARTY->assign ( 'error', $_SESSION ['error'] );
$SMARTY->assign ( 'notice', $_SESSION ['notice'] );

// ASSIGN POST FOR FORM VARIABLES  
$SMARTY->assign ( 'post', $_SESSION ['post'] );
$_SESSION ['post'] = "";
unset ( $_SESSION ['post'] );

// ASSIGN USER FOR TEMPLATES
$SMARTY->assign ( 'user', $_SESSION ['user'] );

$_SESSION ['error'] = "";
unset ( $_SESSION ['error'] );
$_SESSION ['notice'] = "";
unset ( $_SESSION ['notice'] );
$_SESSION ['smarty'] = "";
unset ( $_SESSION ['smarty'] );
$_request = clean ( $_REQUEST );
// ProcessUpdateNewsStatus();

$token = getToken('frontend');
$SMARTY->assign('token',$token);

while ( true ) {
	$struct = $CONFIG->page_strut;
	$class = ( string ) $struct->file;
	
	/**
	 * ***** Goes to 404 ******
	 */
	if ($_request ['arg1'] == '404') {
		header ( "HTTP/1.0 404 Not Found" );
		
		if(strtolower((string)$CONFIG->error404->attributes()->standalone) == 'true'){
			$obj = new $class ( '', $struct );
			$template = $obj->Load( $CONFIG->error404->pageID );
			$template = $CONFIG->error404->template;
			$SMARTY->display ( "extends:$template" );
			die();
		}else{
			$obj = new $class ( '', $struct );
			$template = $obj->Load ( $CONFIG->error404->pageID );
			$template = $CONFIG->error404->template;
			$menu = $obj->LoadMenu ( $CONFIG->error404->pageID );
			$SMARTY->assign ( 'menuitems', $menu );
			break 1;
		}
	}
	
	/**
	 * ***** Goes to home ******
	 */
	if ($_request ['arg1'] == '') {
		$obj = new $class ( '', $struct );
		$template = $obj->Load ( $CONFIG->index_page->pageID );
		$template = $CONFIG->index_page->template;
		$menu = $obj->LoadMenu ( $CONFIG->index_page->pageID );
		$SMARTY->assign ( 'menuitems', $menu );
		break 1;
	}
	
	/**
	 * ***** Processes ******
	 */
	$needle = str_replace ( "/", "\/", "process/" );
	$haystack = $_request ["arg1"];
	if (preg_match ( "/^{$needle}/", $haystack )) {
		foreach ( $CONFIG->process as $sp ) {
			if ($sp->url == $_request ['arg1']) {
				$file = ( string ) $sp->file;
				include($file);
			}
		}
		die();
	}
	/**
	 * **** Goes to login ******
	 */
	if ($_request ['arg1'] == 'login') {
		if ($_SESSION['user']['id']) { 
	    	header("Location: /my-account");
	    	exit;
		}
		$obj = new $class ( '', $struct );
		$template = $obj->Load ( $CONFIG->login->pageID );
		$template = $CONFIG->login->template;
		$menu = $obj->LoadMenu ( $CONFIG->login->pageID );
		$SMARTY->assign ( 'menuitems', $menu );
		$_SESSION ['login_referer'] = $_SERVER['HTTP_REFERER'];
		break 1;
	}
	/**
	 * **** Goes to my-account ******
	 */
	if ($_request ['arg1'] == 'my-account') {
		if (is_null($_SESSION['user']['id'])) {
			header("Location: /login");
			exit;
		}
		$obj = new $class ( '', $struct );
		$template = $obj->Load ( $CONFIG->account->pageID );
		$template = $CONFIG->account->template;
		$menu = $obj->LoadMenu ( $CONFIG->account->pageID );
		$SMARTY->assign ( 'menuitems', $menu );
		$cart_obj = new cart();
		$orders = $cart_obj->GetOrderHistoryByUser($_SESSION['user']['id']);
		$SMARTY->assign ( 'orders', $orders );
		break 1;
	}
	
	
	/**
	 * ***** Goes to search ******
	 */
	if ($_request ['arg1'] == 'search') {
		$obj = new $class ( '', $struct );
		$template = $obj->Load ( $CONFIG->search->pageID );
		$template = $CONFIG->search->template;
		$menu = $obj->LoadMenu ( $CONFIG->search->pageID );
		$SMARTY->assign ( 'menuitems', $menu );
		searchcms ( $_POST ['search'] );
		break 1;
	}
	
	/**
	 * ***** Goes to CHECKOUT ******
	 */
	if ($_request ['arg1'] == 'store/checkout') {
		if (is_null($_SESSION['user']['id'])) { 
	    	header("Location: /login");
	    	exit;
		}
		$obj = new $class ( '', $CONFIG->checkout ); 
		$template = $obj->Load ( $CONFIG->checkout->pageID );
		$template = $CONFIG->checkout->template;
		$menu = $obj->LoadMenu ( $CONFIG->checkout->pageID );
		$SMARTY->assign ( 'menuitems', $menu );
		$cart_obj = new cart();
		$validation = $cart_obj->ValidateCart();
		$SMARTY->assign ( 'validation', $validation );
		$sql = "SELECT * FROM tbl_address WHERE address_user_id = :uid ORDER BY address_id";
		$addresses = $DBobject->wrappedSql($sql, array(':uid' => $_SESSION['user']['id']));
		$SMARTY->assign ( 'addresses', $addresses );
		$sql = "SELECT DISTINCT postcode_state FROM tbl_postcode WHERE postcode_state != 'OTHE' ORDER BY postcode_state";
		$states = $DBobject->wrappedSql($sql);
		$SMARTY->assign ( 'options_state', $states ); 
		//$productsOnCart = $cart_obj->GetDataProductsOnCart(); 
		//$SMARTY->assign ( 'productsOnCart', $productsOnCart );
		break 1;
	}
        
        
	/**
	 * ***** Goes to SHOPPING CART ******
	 */
	if ($_request ['arg1'] == 'store/shopping-cart') {
		$obj = new $class ( '', $struct );
		$template = $obj->Load ( $CONFIG->cart->pageID );
		$template = $CONFIG->cart->template;
		$menu = $obj->LoadMenu ( $CONFIG->cart->pageID );
		$SMARTY->assign ( 'menuitems', $menu );
		$cart_obj = new cart();
		$validation = $cart_obj->ValidateCart();
		$SMARTY->assign ( 'validation', $validation );
		//$productsOnCart = $cart_obj->GetDataProductsOnCart(); 
		//$SMARTY->assign ( 'productsOnCart', $productsOnCart );
		break 1;
	}
	
	/**
	 * **** Goes to individual script pages ******
	 */
	foreach ( $CONFIG->static_page as $sp ) {
		if ($sp->url == $_request ['arg1']) {
			$obj = new $class ( '', $struct );
			$template = $obj->Load ( $sp->pageID );
			$template = $sp->template;
			$menu = $obj->LoadMenu ( $sp->pageID );
			$SMARTY->assign ( 'menuitems', $menu );
			break 2;
		}
	}
	
	
	/**
	 * ***** Product pages here ******
	 */
	$arr = explode ( "/", $_request ["arg1"] );
	foreach ( $CONFIG->product_page as $lp ) {
		// if($lp->url == $arr[0] ){
		$needle = str_replace ( "/", "\/", $lp->url );
		$haystack = $_request ["arg1"];
		if (preg_match ( "/^{$needle}/", $haystack )) {
				
			$_nurl = $_request ["arg1"];
			$class = ( string ) $lp->file;
			$obj = new $class ( $_nurl, $lp );
			$template = $obj->Load ();
			$productsList = $obj->getProductList($lp->root_parent_id, true);
			$SMARTY->assign ( 'productsList', $productsList );
			$menu = $obj->LoadMenu ( $lp->pageID );
			$SMARTY->assign ( 'menuitems', $menu );
				
			break 2;
		}
	}
	
	/**
	 * ***** Listing pages here ******
	 */
	$arr = explode ( "/", $_request ["arg1"] );
	foreach ( $CONFIG->listing_page as $lp ) {
		// if($lp->url == $arr[0] ){
		$needle = str_replace ( "/", "\/", $lp->url );
		$haystack = $_request ["arg1"];
		if (preg_match ( "/^{$needle}/", $haystack )) {
			
			$_nurl = $_request ["arg1"];
			$class = ( string ) $lp->file;
			$obj = new $class ( $_nurl, $lp );
			$template = $obj->Load ();
			
			$menu = $obj->LoadMenu ( $lp->pageID );
			$SMARTY->assign ( 'menuitems', $menu );
			
			break 2;
		}
	}
	
	/**
	 * ***** Dynamic Page Check Here ******
	 */
	$url_parent_id = '0';
	$arr = explode ( "/", $_request ["arg1"] );
	foreach ( $arr as $a ) {
		if ($a !== end ( $arr )) {
			$sql = "SELECT listing_id FROM tbl_listing WHERE tbl_listing.listing_deleted IS NULL AND tbl_listing.listing_url = :url ";
			$params = array (
					":url" => $a 
			);
			if ($res = $DBobject->wrappedSqlGet ( $sql, $params )) {
				$url_parent_id = $res ['0'] ['listing_id'];
				continue;
			} else {
				break 1;
			}
		} else {
			$sql = "SELECT listing_id FROM tbl_listing WHERE tbl_listing.listing_url = :url AND tbl_listing.listing_parent_id = :pcat AND tbl_listing.listing_deleted IS NULL ";
			if ($res = $DBobject->wrappedSqlGet ( $sql, array (
					":url" => $a,
					":pcat" => $url_parent_id 
			) )) {
				$obj = new $class ( '', $struct );
				$template = $obj->Load ( $res [0] ['listing_id'] );
				$template = $struct->template;
				$menu = $obj->LoadMenu ( $res [0] ['listing_id'] );
				$SMARTY->assign ( 'menuitems', $menu );
				break 2;
			} else {
				break 1;
			}
		}
	}
	
	header ( "HTTP/1.0 404 Not Found" );
	if(strtolower((string)$CONFIG->error404->attributes()->standalone) == 'true'){
		$obj = new $class ( '', $struct );
		$template = $obj->Load( $CONFIG->error404->pageID );
		$template = $CONFIG->error404->template;
		$SMARTY->display ( "extends:$template" );
		die();
	}else{
		$obj = new $class ( '', $struct );
		$template = $obj->Load ( $CONFIG->error404->pageID );
		$template = $CONFIG->error404->template;
		$menu = $obj->LoadMenu ( $CONFIG->error404->pageID );
		$SMARTY->assign ( 'menuitems', $menu );
		break 1;
	}
}

/**
 * ***************************************
 * Load Data Shopping Cart for all pages *
 * ***************************************
 */
$cart_obj = new cart();
$itemNumber = $cart_obj->NumberOfProductsOnCart();
$SMARTY->assign ( 'itemNumber', $itemNumber );
$cart = $cart_obj->GetDataCart();
$SMARTY->assign ( 'cart', $cart );
$productsOnCart = $cart_obj->GetDataProductsOnCart();
$SMARTY->assign ( 'productsOnCart', $productsOnCart );

$SMARTY->display ( "extends:page.tpl|$template" );
die ();
