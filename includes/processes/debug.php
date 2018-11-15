<?php
if($_SERVER['REMOTE_ADDR'] == '45.124.202.249'){
die('disabled');
  set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
  include_once 'includes/functions/functions.php';
  global $DBobject, $CONFIG;
  	
  $number = '0123456789';
  $res = '(' . substr($number, 0, 2) . ') ' . substr($number, 2, 4) . ' ' . substr($number, 6, 4);
  
  $renewDate = '2015-06-30';
  $serverDate = '2018-08-22';
  //Calculate date difference between renewal date and today
  $renewalDate = date_create_from_format('Y-m-d', $renewDate);
  $renewalMonth = $renewalDate->format('m');
  //strtotime('last day of +2 month', strtotime(date('Y').'-'.$offerValidTill.'-01')
  $today = date_create_from_format('Y-m-d', $serverDate);
  $todayDate = $today->format('Ymd');
  $offerValidFromDate = date('Ymd', strtotime($today->format('Y').'-'.$renewalMonth.'-01'));
  $offerValidTillDate = date('Ymd', strtotime('last day of +2 month', strtotime($offerValidFromDate)));
  $interval = $renewalDate->diff($today);
  $year_diff = ceil(floatval($interval->format('%R%y.%m%d')));
  $day_diff = floatval($interval->format('%R%a'));
  echo $offerValidFromDate.'----'.$offerValidTillDate.'----'.$todayDate;
  //Verify if member requires reactivation
  $user['reactivation'] = 'f';
  if($year_diff > 1 && ((float)$todayDate < (float)$offerValidFromDate || (float)$todayDate > $offerValidTillDate)){
        //Add reactivation fee when year difference is greater than 1
        //also check that it's not in speacial offer months. renewal month plus 2 months
        $user['reactivation'] = 't';
  }
  echo $user['reactivation'];
  //die(var_dump($res));
  exit;
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