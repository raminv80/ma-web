<?php 
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'includes/functions/functions.php';
global $DBobject, $CONFIG;

$from = (string)$CONFIG->company->name;
$fromEmail = (string) $CONFIG->company->email_from;
$hasErrors = false;
$emailBody = '';

$user_obj = new UserClass();
//get record of all renewal payments which doesn't have a matching log or a deleted log
$sql = "SELECT tbl_payment.*, tbl_cartitem.cartitem_variant_name, tbl_log.* 
  FROM tbl_payment 
  LEFT JOIN tbl_cartitem ON tbl_payment.payment_cart_id = tbl_cartitem.cartitem_cart_id 
  LEFT JOIN tbl_log ON tbl_payment.payment_id = tbl_log.log_record_id AND tbl_log.log_action = 'member-process-payment' AND tbl_log.log_deleted IS NULL 
  WHERE tbl_payment.payment_is_renewal = '1' AND tbl_payment.payment_status = 'A' AND tbl_payment.payment_deleted IS NULL AND tbl_log.log_id IS NULL";

if($res = $DBobject->wrappedSql($sql)){
  foreach($res as $record){
      //make a call to api to process the payments
      $userPaymentRecord = array();
      $userPaymentRecord['membershipYear'] = $record['cartitem_variant_name'];
      $userPaymentRecord['memberPaymentAmount'] = $record['payment_charged_amount'];
      $userPaymentRecord['membershipNumber'] = $record['payment_user_id'];
      $userPaymentRecord['memberPaymentReference'] = $record['payment_transaction_no'];
      if($resp = $user_obj->processPayment($userPaymentRecord)){
        $resp = json_decode($resp);
        saveInLog('member-process-payment', 'external', $record['payment_id'], $resp->message);
      }else{
        $hasErrors = true;
        $emailBody .= "Member id:  {$record['payment_user_id']} <br>". $user_obj->getErrorMsg().'<br/><br/>';
      }
  }
}

if($hasErrors){
  sendErrorMail('weberrors@them.com.au', $from, $fromEmail, 'Process payments cron', $emailBody);
}  

die();