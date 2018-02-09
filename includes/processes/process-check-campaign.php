<?php
global $CONFIG,$SMARTY;

if(!empty($_REQUEST['utm_campaign'])){
  if($_REQUEST['utm_campaign'] == "Renewal Campaign 2018"){
    $_SESSION['tempvars']['quick_renew_hidden_message'] = 'true';
  }
}

if(!empty($_SESSION['tempvars']['quick_renew_hidden_message'])){
  $SMARTY->assign('quick_renew_hidden_message', $_SESSION['tempvars']['quick_renew_hidden_message']);
}