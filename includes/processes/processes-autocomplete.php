<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;
$referer = parse_url($_SERVER['HTTP_REFERER']);
if($referer['host'] == $GLOBALS['HTTP_HOST'] && !empty($_POST['timestamp']) && !empty($_POST['q'])){
  $error = '';
  $success = '';
  try{
    $SMARTY->assign('products', SearchProduct($_POST['q']));
    $template = $SMARTY->fetch('templates/ec_search-product-autocomplete.tpl');
    $success = true;
  
  }catch(exceptionCart $e){
    $error = $e->getMessage();
  }
  
  echo json_encode(array(
      'success' => $success,
      'error' => $error,
      'timestamp' => $_POST['timestamp'],
      'template' => $template
  ));
}
die();