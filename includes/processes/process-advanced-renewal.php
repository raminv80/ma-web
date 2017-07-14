<?php
/**
 * Validate token and save it in session
 * Input - valid string format: [HEX-timestamp]m[HEX-membership_number]r[HEX-renewal_date(yyyymmdd)]
 */

$redirect = 'my-account';

try{
  if(!empty($_REQUEST['tk'])){
    $_SESSION['tempvars']['advanced_renewal_mn'] = null;
    
    // Split string
    $tmpArr = explode('m', $_REQUEST['tk']);
    $timestamp = ctype_xdigit($tmpArr[0]) ? hexdec($tmpArr[0]) : 0;
    $tmpArr2 = explode('r',  $tmpArr[1]);
    $membershipNumber = ctype_xdigit($tmpArr2[0]) ? hexdec($tmpArr2[0]) : 0;
    $renewalDate = ctype_xdigit($tmpArr2[1]) ? hexdec($tmpArr2[1]) : 0;
  
    // Validate: timestamp - within 90 days (7776000 secs) and renewal date has not past
    if(!empty($timestamp) && (time() - $timestamp < 7776000) && !empty($membershipNumber) && !empty($renewalDate) && ($renewalDate - intval(date('Ymd')) > 0)){
      $_SESSION['tempvars']['advanced_renewal_mn'] = $membershipNumber;
      $redirect = 'quick-renew';
    }
  }
  
}catch(Exception $e) {
  
}
    
header("Location: /{$redirect}");
die();

  

  


  
