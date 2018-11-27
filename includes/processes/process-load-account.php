<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;

try{
  $cart_obj = new cart($_SESSION['user']['public']['id']);
  $orders = $cart_obj->GetOrderHistoryByUser($_SESSION['user']['public']['id']);
  $SMARTY->assign('orders', $orders);
  
  $user_obj = new UserClass();
  //$userDetails = $user_obj->RetrieveById($_SESSION['user']['public']['id']);
  $SMARTY->assign('userDetails', $userDetails);
}
catch(exceptionCart $e){
  $SMARTY->assign('error', $e->getMessage());
}

  
