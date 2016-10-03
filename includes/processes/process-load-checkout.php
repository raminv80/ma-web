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
  
  //VALIDATE MAF MEMBERS
  $addMSF = false;
  $addReactivationFee = false;
  $hasMAFProd = $cart_obj->HasMAFProducts();
  if($hasMAFProd){
    if(empty($_SESSION['user']['public']['id'])){
      //Add "member service fee - current_year" when member is not logged in
      $addMSF = true;
    }elseif(!empty($_SESSION['user']['public']['maf']) && !empty($_SESSION['user']['public']['maf']['main']['user_RenewalDate'])){
      //Add "member service fee - current_year" when member is logged in and membership has expired
      $renewalDate = new DateTime($_SESSION['user']['public']['maf']['main']['user_RenewalDate']);
      $today = new DateTime();
      if($today > $renewalDate){
        $addMSF = true;
      }
      //Add reactivation fee when year difference is greater than 2 
      $interval = $renewalDate->diff($today);
      if($interval->y > 1 && empty($interval->invert)){
        $addReactivationFee = true;
      }
    }
  }
  //Add/remove MAF membership fee 
  $msfArr = $cart_obj->GetCurrentMAF_MSF(225);
  $membershipFeeCartitemId  = $cart_obj->hasProductInCart($msfArr['product_object_id'], $msfArr['variant_id']);
  if($addMSF && empty($membershipFeeCartitemId)){
    $cart_obj->AddToCart($msfArr['product_object_id'], array(), 0, 1, null, $msfArr['variant_id']);
  }elseif(!$addMSF && !empty($membershipFeeCartitemId)){
    $cart_obj->RemoveFromCart($membershipFeeCartitemId);
  }
  //Add/remove MAF reactivation fee
  $reactivationCartitemId = $cart_obj->hasProductInCart(225, 16);
  if($addReactivationFee && empty($reactivationCartitemId)){
    $cart_obj->AddToCart(225, array(), 0, 1, null, 16);
  }elseif(!$addReactivationFee && !empty($reactivationCartitemId)){
    $cart_obj->RemoveFromCart($reactivationCartitemId);
  }
  //END OF VALIDATE MAF MEMBERS
  
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

  
