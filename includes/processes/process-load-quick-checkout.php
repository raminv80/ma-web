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
  
}catch(exceptionCart $e) {
  $SMARTY->assign('error', $e->getMessage());
}

  
