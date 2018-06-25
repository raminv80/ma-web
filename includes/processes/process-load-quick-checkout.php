<?php
global $CONFIG, $SMARTY, $DBobject, $GA_ID, $REQUEST_URI;
try{
  
  $cart_obj = new cart($_SESSION['user']['public']['id']);
  $cart_obj->RemoveNonMembershipFeeCartitems();
  $cart_obj->RemoveDiscountCode();
  $validation = $cart_obj->ValidateCart($_SESSION['user']['public']);
  $SMARTY->assign('validation',$validation);
  $totals = $cart_obj->CalculateTotal();
  $SMARTY->assign('totals',$totals);
  $ifCardInCart = '';
  //check if card is already there in cart
  $lifetimeCardId = $cart_obj->hasProductInCart($GLOBALS['CONFIG_VARS']['membership_card_product_id'], $GLOBALS['CONFIG_VARS']['membership_card_variant_id']);
  if(!empty($lifetimeCardId)){
    $ifCardInCart = 'yes';
  }
  $SMARTY->assign('ifCardInCart', $ifCardInCart);
}catch(exceptionCart $e) {
  $SMARTY->assign('error', $e->getMessage());
}

  
