<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'includes/functions/functions.php';
global $SMARTY, $DBobject, $CONFIG, $GA_ID;

try{
  $startDate = date('Y-m-d');
  //Only at THEM
  if($_SERVER['REMOTE_ADDR']=='150.101.230.130' && !empty($_REQUEST['date'])){
    $startDate = date('Y-m-d', strtotime($_REQUEST['date']));
  }
  
  $voucherId = 0;
  include_once 'includes/classes/voucher-class.php';
  $voucherObj = new Voucher();
  if($voucherArr = $voucherObj->GetPendingToSendVouchers($startDate)){
    foreach($voucherArr as $v){
      $voucherId = $v['voucher_id'];
      
      // SEND GIFT CERTIFICATE TO RECIPIENT
      $to = $v['voucher_recipientemail'];
      $SMARTY->unloadFilter('output', 'trimwhitespace');
      $SMARTY->assign('name', $v['voucher_recipientname']);
      $SMARTY->assign('sender_name', (empty($v['voucher_anonymous']) ? $v['voucher_name'] : ''));
      $SMARTY->assign('code', $v['voucher_code']);
      $SMARTY->assign('amount', $v['voucher_amount']);
      $SMARTY->assign('custom_message', $v['voucher_message']);
      $fromEmail = (string)$CONFIG->company->email_from;
      $from = (string)$CONFIG->company->name;
      $SMARTY->assign('DOMAIN', "http://" . $GLOBALS['HTTP_HOST']);
      $COMP = json_encode($CONFIG->company);
      $SMARTY->assign('COMPANY', json_decode($COMP, TRUE));
      
      $subject = 'Someone has sent you a MedicAlert gift certificate';
      $body = $SMARTY->fetch('email/gift-certificate.tpl');
      $mailID_recipient = sendMail($to, $from, $fromEmail, $subject, $body, null, 0, 0, -2);

      // SEND GIFT CERTIFICATE TO SENDER
      $to = $v['voucher_email'];
      $SMARTY->assign('recipient_name', $v['voucher_recipientname']);
      $SMARTY->assign('recipient_email', $v['voucher_recipientemail']);
      $SMARTY->assign('sender_name', $v['voucher_name']);
      $subject = 'Your MedicAlert gift certificate was sent';
      $body = $SMARTY->fetch('email/confirmation-gift-certificate.tpl');
      $mailID_sender = sendMail($to, $from, $fromEmail, $subject, $body, null, 0, 0, -2);

      $voucherObj->SetVoucherEmailIds($mailID_recipient, $mailID_sender, $voucherId);
      echo $mailID_sender.'<br>';
      
      
    }
  }
  
}catch(Exception $e){
  sendErrorMail('weberrors@them.com.au', $from, $fromEmail, 'Cron send voucher', "Voucher id:  {$voucherId} <br>". $e);
}
die('End');