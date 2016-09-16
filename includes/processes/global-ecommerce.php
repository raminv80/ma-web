<?php
global $CONFIG, $SMARTY, $DBobject, $GA_ID, $REQUEST_URI;

// **************** REDIRECT SECTION
$loginUrl = '/login-register';
$membersAreaUrl = '/my-account';

$redirect = empty($_SESSION['redirect']) ? (empty($_SESSION['post']['redirect']) ? $_SERVER['HTTP_REFERER'] : $_SESSION['post']['redirect']) : $_SESSION['redirect'];
$SMARTY->assign('redirect', $redirect);
$_SESSION['redirect'] = null;

// Redirect to login page when displaying members only page and user is not logged in
if(!empty($SMARTY->getTemplateVars('listing_membersonly')) && $REQUEST_URI != $loginUrl && empty($_SESSION['user']['public']['id'])){
  $_SESSION['redirect'] = $REQUEST_URI;
  header('Location: ' . $loginUrl);
  die();
}

//Redirect to member's area when logged in
if($REQUEST_URI == $loginUrl && !empty($_SESSION['user']['public']['id'])){
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
  $subtotal = $cart_obj->GetSubtotal();
  $SMARTY->assign('subtotal', $subtotal);
  $productsOnCart = $cart_obj->GetDataProductsOnCart();
  $SMARTY->assign('productsOnCart', $productsOnCart);
  
  //Shipping
  $discountData = $cart_obj->GetDiscountData($cart['cart_discount_code']);
  $shippable = $cart_obj->ShippableCartitems();
  $SMARTY->assign('shippable', $shippable);
  $shipping_obj = new ShippingClass(count($shippable), $discountData['discount_shipping']);
  $SMARTY->assign('shippingMethods', $shipping_obj->getShippingMethods());
}
catch(exceptionCart $e){
  $SMARTY->assign('error', $e->getMessage());
}

  
