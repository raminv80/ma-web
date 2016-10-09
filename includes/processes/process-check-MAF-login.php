<?php
global $CONFIG, $SMARTY, $DBobject, $GA_ID, $REQUEST_URI;
try{
  //Check if member still logged in
  $loginUrl = '/login-register';
  $user_obj = new UserClass();
  $loginCheck = $user_obj->setSessionVars($_SESSION['user']['public']['maf']['token']);
  if(!$loginCheck){
    $_SESSION['user']['public'] = null;
    $_SESSION['redirect'] = $REQUEST_URI;
    header('Location: ' . $loginUrl);
    die();
  }
  
}catch(exceptionCart $e) {
  $SMARTY->assign('error', $e->getMessage());
}

  
