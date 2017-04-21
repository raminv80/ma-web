<?php
global $CONFIG, $SMARTY, $DBobject, $GA_ID, $REQUEST_URI;
try{
  /* $ship_obj = new ShippingClass();
   $methods = $ship_obj->getShippingMethods($itemNumber);
   $SMARTY->assign ( 'shippingMethods', $methods ); */
  
  
  $cart_obj = new cart($_SESSION['user']['public']['id']);
  $validation = $cart_obj->ValidateCart($_SESSION['user']['public']);
  $SMARTY->assign('validation',$validation);
  $totals = $cart_obj->CalculateTotal();
  $SMARTY->assign('totals',$totals);
  
  //Shipping
  $SMARTY->assign('shipping', $_SESSION['shipping']);
  $shippable = $cart_obj->ShippableCartitems();
  $shipping_obj = new ShippingClass(count($shippable), $cart_obj->GetCurrentFreeShippingDiscountName());
  $SMARTY->assign('shippingMethods', $shipping_obj->getShippingMethods());
  
  //RE-APPLY DISCOUNT CODE - (BUPA/AUTISM with products) 
  if(!empty($_SESSION['reApplydiscount']) && $shippable){
    $res = $cart_obj->ApplyDiscountCode($_SESSION['reApplydiscount']);
    $_SESSION['reApplydiscount'] = '';
    header('Location: /shopping-cart#1');
    die();
  }
  
  //Has donation
  $SMARTY->assign('hasDonation', $cart_obj->hasProductInCart(217));
  
  //Has stainless steel product - display special
  $SMARTY->assign('showProductEspecial', $cart_obj->HasStainlessSteel());
  
  if(!empty($GA_ID)){
    $productsGA = $cart_obj->getCartitemsByCartId_GA();
    sendGAEnEcCheckoutStep($GA_ID, '1', 'Shopping cart', $productsGA);
  }
  
}catch(exceptionCart $e) {
  $SMARTY->assign('error', $e->getMessage());
}

  
