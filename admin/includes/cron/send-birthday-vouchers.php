<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'includes/functions/functions.php';
global $DBobject, $SMARTY;

$error = '';

$subject = "Here's a birthday gift from us, to you! ";

$from = (string) $CONFIG->company->name;
$fromEmail = (string) $CONFIG->company->email_from;
$COMP = json_encode($CONFIG->company);
$SMARTY->assign('COMPANY', json_decode($COMP,TRUE));
$SMARTY->assign('DOMAIN', "http://" . $GLOBALS['HTTP_HOST']);

$cnt = 0;

$day = intval(date('d'));
$month = intval(date('m'));
//Only at THEM
if($_SERVER['REMOTE_ADDR'] == '45.124.202.249' && !empty($_REQUEST['day']) && !empty($_REQUEST['month'])){
  $day = intval($_REQUEST['day']);
  $month = intval($_REQUEST['month']);
}

//Discount code settings
$startDate = date('Y-m-d');
$endDate = date('Y-m-d', strtotime($startDate . ' + 30 day'));
$amount = 10;
$year = date('Y');

try {
  //Get all members by birthday from the API
  $cart_obj = new cart();
  $user_obj = new UserClass();
  if($members = $user_obj->GetMembersByBirthday($day, $month)){
    $members = array_reverse($members);
    //Build list of temp members with abandoned carts.
    $existingMembers = array();
    foreach($members as $r){
      if(!array_key_exists($r['emailAddress'], $existingMembers) && !IsUnsubscribed($r['emailAddress'])){
        $existingMembers[$r['emailAddress']]['name'] = $r['firstName'];
        $existingMembers[$r['emailAddress']]['user_id'] = $r['membershipNumber'];
      }
//       if(count($existingMembers) >= 50){
//         break;
//       }
    }
    
    //Create and send emails
    foreach($existingMembers as $email => $d){
      $attempt = 0;
      $validCode = false;
      while($attempt < 80 && !$validCode){
        $attempt++;
        $codeStr = GenerateVoucherCode();
        if(empty($cart_obj->GetDiscountData($codeStr))){
          $validCode = true;
        }
      }
      if($validCode){
        $newDiscountArr = array(
            "code" => $codeStr,
            "name" => "Birthday voucher",
            "description" => "Birthday voucher ({$d['user_id']}-{$year})",
            "amount" => $amount,
            "start_date" => $startDate,
            "end_date" => $endDate,
            "fixed_time" => 1,
            "isPublished" => 1,
            "usergroup_id" => 1,
            "user_id" => $d['user_id'],
            "isPercentage" => -1,
            "listing_id" => 777 //All products collection
        );
        if($discountId = $cart_obj->CreateDiscountCode($newDiscountArr)){
      
          $SMARTY->assign('name', $d['name']);
          $SMARTY->assign('code', $codeStr);
          $SMARTY->assign('amount', $amount);
          $SMARTY->assign('unsubscribe_token', 'bd-' . dechex($d['user_id']) . '-' . dechex(time()));
          $body = $SMARTY->fetch('email/birthday-voucher.tpl'); //FRONT-END templates
          createBulkMail(array($email), $from, $fromEmail, $subject, $body, 0, array($d['user_id']));
          $cnt++;
        }
      }else{
        $error .= '<br>'. $d['user_id'] . ' had no code.';
      }
    }
  }
  
} catch (Exception $e) {
  $error .= '<br>'. $e->getMessage();
}

if(!empty($error)){
  sendErrorMail('weberrors@them.com.au', $from, $fromEmail, 'Birthday voucher - cronjob',  $error);
}

echo "Cronjob ended";
die();





function IsUnsubscribed($email){
  global $DBobject;

  $params = array(
      ":unsubscribe_email" => $email
  );
  $sql = "SELECT unsubscribe_id FROM tbl_unsubscribe WHERE unsubscribe_deleted IS NULL AND unsubscribe_email = :unsubscribe_email";
  if($DBobject->wrappedSql($sql, $params)){
    return true;
  }
  return false;
}



function GenerateVoucherCode(){
  $blockSize = 5;
  $blockQty = 4;
  $codeArr = array();

  //Build code-array
  while(count($codeArr) < $blockQty){
    $subcodeStr = genRandomString($blockSize);
    if(!in_array($subcodeStr, $codeArr)) {
      array_push($codeArr, $subcodeStr);
    }
    //Code complete
    if(count($codeArr) == $blockQty){
      $codeStr = implode('-', $codeArr);
      return $codeStr;     
    }
  }
  return '';
}



