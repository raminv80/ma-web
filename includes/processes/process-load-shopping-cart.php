<?php
global $CONFIG, $SMARTY, $DBobject, $GA_ID, $REQUEST_URI;
try{
  /* $ship_obj = new ShippingClass();
   $methods = $ship_obj->getShippingMethods($itemNumber);
   $SMARTY->assign ( 'shippingMethods', $methods ); */
  
  /*//RE-APPLY DISCOUNT CODE 
   if(!empty($_SESSION['reApplydiscount']) && $itemNumber){		
    $res = $cart_obj->ApplyDiscountCode($_SESSION['reApplydiscount']);
    if ($res['error']) {
      $_SESSION['error']= $res['error'];
      $_SESSION['post']= $_POST;
    }
    $_SESSION['reApplydiscount'] = '';
    unset($_SESSION['reApplydiscount']);
    header('Location: /shopping-cart');
    die();
  } */
  
  $cart_obj = new cart($_SESSION['user']['public']['id']);
  $validation = $cart_obj->ValidateCart();
  $SMARTY->assign('validation',$validation);
  $totals = $cart_obj->CalculateTotal();
  $SMARTY->assign('totals',$totals);
  
  //Shipping
  $SMARTY->assign('shipping', $_SESSION['shipping']);
  $shippable = $cart_obj->ShippableCartitems();
  $shipping_obj = new ShippingClass(count($shippable), $cart_obj->GetCurrentFreeShippingDiscountName());
  $SMARTY->assign('shippingMethods', $shipping_obj->getShippingMethods());
  
}catch(exceptionCart $e) {
  $SMARTY->assign('error', $e->getMessage());
}

  
