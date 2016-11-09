<?php
global $CONFIG, $SMARTY, $DBobject, $GA_ID, $REQUEST_URI;
try{
  //Check if member still logged in
  $loginUrl = '/login';
  $user_obj = new UserClass();
  
  if(empty($_SESSION['user']['public']['maf']['token']) || !$user_obj->setSessionVars($_SESSION['user']['public']['maf']['token'])){
    $_SESSION['user']['public'] = null;
    $_SESSION['redirect'] = $REQUEST_URI;
    header('Location: ' . $loginUrl);
    die();
  }else{
    $_SESSION['user']['public']['maf'] = $user_obj->getSessionVars();
    $_SESSION['user']['public']['id'] = $_SESSION['user']['public']['maf']['main']['user_id'];
    $_SESSION['user']['public']['gname'] = $_SESSION['user']['public']['maf']['main']['user_firstname'];
    $_SESSION['user']['public']['surname'] = $_SESSION['user']['public']['maf']['main']['user_lastname'];
    $_SESSION['user']['public']['email'] = $_SESSION['user']['public']['maf']['main']['user_email'];
    $SMARTY->assign('user', $_SESSION['user']['public']);
  }
  
}catch(exceptionCart $e) {
  $SMARTY->assign('error', $e->getMessage());
}

  
  
  