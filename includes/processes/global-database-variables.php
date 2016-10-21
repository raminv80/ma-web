<?php
global $CONFIG, $SMARTY, $DBobject, $GA_ID, $REQUEST_URI;

try{
  //Set dynamic global variables
  if(empty($GLOBALS['CONFIG_VARS'])){
    $GLOBALS['CONFIG_VARS'] = array();
  }
  
  //MAF ONLY - membership_fee
  $cart_obj = new cart($_SESSION['user']['public']['id']);
  if($msfArr = $cart_obj->GetCurrentMAF_MSF(225)){
    $GLOBALS['CONFIG_VARS']['membership_fee'] = '$'. round($msfArr['variant_price'], 2);
    $SMARTY->assign("CONFIG_VARS", $GLOBALS['CONFIG_VARS']);
  }
}
catch(exceptionCart $e){
  $SMARTY->assign('error', $e->getMessage());
}

  
