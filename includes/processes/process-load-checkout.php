<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;

try{
  $cart_obj = new cart();
  if($cart_obj->NumberOfProductsOnCart() < 1){
    header("Location: /shopping-cart");
    die();
  }
  /* $ship_obj = new ShippingClass();
   $methods = $ship_obj->getShippingMethods($cart_obj->NumberOfProductsOnCart());
   $SMARTY->assign ( 'shippingMethods', $methods ); */
  $validation = $cart_obj->ValidateCart();
  $SMARTY->assign('validation',$validation);
  $totals = $cart_obj->CalculateTotal();
  $SMARTY->assign('totals',$totals);

  $sql = "SELECT DISTINCT postcode_state FROM tbl_postcode WHERE postcode_state != 'OTHE' ORDER BY postcode_state";
  $states = $DBobject->wrappedSql($sql);
  $SMARTY->assign('options_state',$states);
  
  // ASSIGN JS-SCRIPTS TO GOOGLE ANALYTICS - ENHANCED ECOMMERCE
  $SMARTY->assign ( 'ga_ec', $cart_obj->getJSCartitemsByCartId_GA() . "ga('ec:setAction','checkout', { 'step': 1 });" );
  
}catch(exceptionCart $e) {
  $SMARTY->assign('error', $e->getMessage());
}

  
