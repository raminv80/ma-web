<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;

try{

  
  $viewed = array();
  $SMARTY->assign('viewed_products', $viewed);
  
}catch(exceptionCart $e) {
  $SMARTY->assign('error', $e->getMessage());
}

  
