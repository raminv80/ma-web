<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;

try{
  $cart_obj = new cart($_SESSION['user']['public']['id']);
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
  
}catch(exceptionCart $e) {
  $SMARTY->assign('error', $e->getMessage());
}

  
