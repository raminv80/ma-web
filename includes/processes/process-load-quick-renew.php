<?php
global $CONFIG, $SMARTY, $DBobject, $GA_ID, $REQUEST_URI;
try{

  $cart_obj = new cart($_SESSION['user']['public']['id']);
  $cart_obj->RemoveNonMembershipFeeCartitems();
  $cart = $cart_obj->GetDataCart();
  if($cart['cart_discount_code'] != 'SENIORS') $cart_obj->RemoveDiscountCode(); // If no seniors discount exists
  $validation = $cart_obj->ValidateCart($_SESSION['user']['public'], true);
  $SMARTY->assign('validation',$validation);
  $totals = $cart_obj->CalculateTotal();
  $SMARTY->assign('totals',$totals);

}catch(exceptionCart $e) {
  $SMARTY->assign('error', $e->getMessage());
}
