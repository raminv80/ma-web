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
    
    //Dynamic postage
    $shipCnt = 1;
    $shipping_obj = new ShippingClass($shipCnt);
    $methodArr = $shipping_obj->getShippingMethods();
    foreach($methodArr as $m){
      if(!empty($m)){
        $GLOBALS['CONFIG_VARS']['postage'] = '$'. number_format($m, 2, '.', ',');
        //Postage - DATABASE VARIABLES
        foreach($GLOBALS['DATABASE_VARS']['find'] as $k => $v){
          if($v == '==postage=='){
            $GLOBALS['DATABASE_VARS']['replace'][$k] = $GLOBALS['CONFIG_VARS']['postage'];
            break;
          }
        }
        break;
      }
    }
    
    $GLOBALS['CONFIG_VARS']['membership_fee'] = '$'. round($msfArr['variant_price'], 2);
    $SMARTY->assign("CONFIG_VARS", $GLOBALS['CONFIG_VARS']);
    
    //MAF ONLY - DATABASE VARIABLES
    foreach($GLOBALS['DATABASE_VARS']['find'] as $k => $v){
      if($v == '==membership_fee=='){
        $GLOBALS['DATABASE_VARS']['replace'][$k] = $GLOBALS['CONFIG_VARS']['membership_fee'];
        break;
      }
    }
    //MAF ONLY - DATABASE VARIABLES - Calculations must be done separately
    foreach($GLOBALS['DATABASE_VARS']['find'] as $k => $v){
      if($v == '==mintotal_membership_cost=='){
        $GLOBALS['DATABASE_VARS']['replace'][$k] = floatval(str_replace('$', '', $GLOBALS['CONFIG_VARS']['membership_fee'])) + floatval(str_replace('$', '', $GLOBALS['CONFIG_VARS']['postage'])) + floatval(str_replace('$', '', $GLOBALS['CONFIG_VARS']['medical_id_price']));
        $GLOBALS['DATABASE_VARS']['replace'][$k] = number_format($GLOBALS['DATABASE_VARS']['replace'][$k], 2, '.', ',');
        break;
      }
    }
    $SMARTY->assign("DATABASE_VARS", $GLOBALS['DATABASE_VARS']);
  }
}
catch(exceptionCart $e){
  $SMARTY->assign('error', $e->getMessage());
}

  
