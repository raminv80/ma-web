<?php
if($_SERVER['REMOTE_ADDR'] == '150.101.230.130'){
  set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
  include_once 'includes/functions/functions.php';
  global $DBobject, $CONFIG;
  	
  try{
    include_once 'includes/classes/voucher-class.php';
    $voucherObj = new Voucher();
    die(var_dump($voucherObj->GenerateVoucherCode()));
  }
  catch(Exception $e){
    die(var_dump($e));
  }
}

die();