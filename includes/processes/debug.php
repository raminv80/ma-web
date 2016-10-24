<?php
if($_SERVER['REMOTE_ADDR'] == '150.101.230.130'){
  set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
  include_once 'includes/functions/functions.php';
  global $DBobject, $CONFIG;
  	
  
  
  try{
    $startDate = '2016-10-30';
    $endDate = date('Y-m-d', strtotime($startDate . ' + 1 year'));
    $amount = 88;
    
    include_once 'includes/classes/voucher-class.php';
    $cart_obj = new cart($_SESSION['user']['public']['id']);
    $voucherObj = new Voucher();
    $attempt = 0;
    $validCode = false;
    while($attempt < 20 && !$validCode){
      $attempt++;
      $codeStr = $voucherObj->GenerateVoucherCode();
      if(empty($cart_obj->GetDiscountData($codeStr))){
        $validCode = true;
      }
    }
    if($validCode){
      $newVoucherArr = array(
          "payment_id" => 888,
          "code" => $codeStr,
          "name" => 'Apolo Kok',
          "email" => 'apolo@them.com.au',
          "recipientname" => 'Joe',
          "recipientemail" => 'Doe',
          "amount" => $amount,
          "start_date" => $startDate,
          "end_date" => $endDate
      );
      if($voucherId = $voucherObj->CreateVoucher($newVoucherArr)){
      
        $newDiscountArr = array(
            "code" => $codeStr,
            "name" => "Gift certificate ($voucherId)",
            "description" => "Gift certificate ($voucherId) - Order no. ",
            "amount" => $amount,
            "start_date" => $startDate,
            "end_date" => $endDate,
            "fixed_time" => 1,
            "isPublished" => 1,
        );
        if($discountId = $cart_obj->CreateDiscountCode($newDiscountArr)){
          die(var_dump($codeStr));
        }
      }
    }
    die('Creation error'.$voucherId);
    
  }
  catch(Exception $e){
    die(var_dump($e));
  }
}

die();