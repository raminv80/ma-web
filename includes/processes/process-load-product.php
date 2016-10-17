<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;

if(strpos($_SERVER['HTTP_REFERER'], '/products/')){
  $arr = explode('/', $_SERVER['HTTP_REFERER']);
  $SMARTY->assign('backcollection', $arr[4]);
}