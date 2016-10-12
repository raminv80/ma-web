<?php
global $CONFIG, $SMARTY, $DBobject, $GA_ID, $REQUEST_URI;

// **************** REDIRECT SECTION
$loginUrl = '/login';
$loginRegisterUrl = '/login-register';
$checkoutUrl = '/checkout';
$membersAreaUrl = '/my-account';


$redirect = empty($_SESSION['redirect']) ? (empty($_SESSION['post']['redirect']) ? $_SERVER['HTTP_REFERER'] : $_SESSION['post']['redirect']) : $_SESSION['redirect'];
$SMARTY->assign('redirect', $redirect);
$_SESSION['redirect'] = null;

// Redirect to login page when displaying members only page and user is not logged in
if((!empty($SMARTY->getTemplateVars('listing_membersonly')) && $REQUEST_URI != $loginUrl && empty($_SESSION['user']['public']['id']))
    || ($REQUEST_URI == $checkoutUrl && empty($_SESSION['user']['public']['id']) && empty($_SESSION['user']['new_user']))
  ){
  $_SESSION['redirect'] = $REQUEST_URI;
  header('Location: ' . (($REQUEST_URI == $checkoutUrl)? $loginRegisterUrl : $loginUrl));
  die();
}

//Redirect to member's area when logged in
if(($REQUEST_URI == $loginUrl || $REQUEST_URI == $loginRegisterUrl) && !empty($_SESSION['user']['public']['id'])){
  header('Location: ' . $membersAreaUrl);
  die();
}


// **************** LOAD ECOMMERCE DETAILS
try{
  $cart_obj = new cart($_SESSION['user']['public']['id']);
  $cart = $cart_obj->GetDataCart();
  $SMARTY->assign('cart', $cart);
  $itemNumber = $cart_obj->NumberOfProductsOnCart();
  $SMARTY->assign('itemNumber', $itemNumber);
  $discount = $cart_obj->GetDiscountData($cart['cart_discount_code']);
  $SMARTY->assign('discount', $discount);
  $subtotal = $cart_obj->GetSubtotal();
  $SMARTY->assign('subtotal', $subtotal);
  $productsOnCart = $cart_obj->GetDataProductsOnCart();
  $SMARTY->assign('productsOnCart', $productsOnCart);
  
  //Wish list
  $wishlist = $cart_obj->GetWishList();
  $SMARTY->assign('wishlist', $wishlist);
  
  //Temporary user's addresses
  $SMARTY->assign('address', $_SESSION['address']);
  
}
catch(exceptionCart $e){
  $SMARTY->assign('error', $e->getMessage());
}

  
