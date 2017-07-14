<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;

// This is for abandoned carts for non-members

$redirect = '404';

if(!empty($_REQUEST['tk'])){
  
  // The token is made up of the following strings: 'em-' . dechex($d['user_id']) . '-' . time()
  $tokenArr = explode('-', $_REQUEST['tk']);
  
  try{
    $redirect = 'unsubscribe#error';
    
    // List of accepted prefixes
    $nonMemberArr = array('tm');
    $memberArr = array('em', 'bd');
    $acceptedArr = array_merge($nonMemberArr, $memberArr); 
    
    // Validate the token
    if(in_array($tokenArr[0], $acceptedArr) && is_int(hexdec($tokenArr[1])) && is_int(hexdec($tokenArr[2])) && hexdec($tokenArr[2]) < time()){
      
      $response = 0;
      $userId = 0;
      
      //Get email address for non-member
      if(in_array($tokenArr[0], $nonMemberArr)){
        $userId = hexdec($tokenArr[1]) * -1;
      }
      
      //Get email address for existing member
      if(in_array($tokenArr[0], $memberArr)){
        $userId = hexdec($tokenArr[1]);
      }
      
      $params = array(
          'uid' => $userId
      );
      $sql = "SELECT email_to FROM tbl_email_queue WHERE email_user_id = :uid ORDER BY email_created DESC LIMIT 1";
      if($res = $DBobject->wrappedSql($sql, $params)){
        if(SetUnsubscribe($res[0]['email_to'], $_REQUEST['tk'])){
          $redirect = 'unsubscribe';
        }
      }
    }
  }
  catch(exceptionCart $e){
    sendErrorMail('weberrors@them.com.au', $from, $fromEmail, 'Abandoned cart - get cart',  $e->getMessage());
  }
}

header('Location: /' . $redirect);
die();





function SetUnsubscribe($email, $token){
  global $DBobject;
  
  $params = array(
      ":unsubscribe_email" => $email,
      ":unsubscribe_token" => $token
  );

  $sql = "INSERT INTO tbl_unsubscribe (unsubscribe_email, unsubscribe_token, unsubscribe_created)
			values (:unsubscribe_email, :unsubscribe_token, NOW())";
  if($DBobject->wrappedSql($sql, $params)){
    return $DBobject->wrappedSqlIdentity();
  }
  return 0;
}