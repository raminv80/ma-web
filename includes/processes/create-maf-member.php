<?php
echo 'helo';
echo $_SERVER['REMOTE_ADDR'];
exit;
if($_SERVER['REMOTE_ADDR'] == '150.101.230.130'){
  
  set_include_path($_SERVER['DOCUMENT_ROOT']);
  include_once 'includes/functions/functions.php';
  global $SMARTY, $DBobject, $CONFIG, $GA_ID;
  
  $email = 'garman@internode.on.net ';
  
  $sql = "SELECT * FROM tbl_usertemp WHERE usertemp_email LIKE :email";
  $params = array(
      ':email' => $email
  ); 
  if($res = $DBobject->wrappedSql($sql, $params)){
    $usrTemp = array();
    
    $usrTemp = array(
        "gname" => $res[0]['usertemp_gname'],
        "surname" => $res[0]['usertemp_surname'],
        "email" => $res[0]['usertemp_email'],
        "mobile" => $res[0]['usertemp_mobile'],
        "db_dob" => $res[0]['usertemp_dob'],
        "gender" => $res[0]['usertemp_gender'],
        "address" => $res[0]['usertemp_address'],
        "suburb" => $res[0]['usertemp_suburb'],
        "state" => $res[0]['usertemp_state'],
        "postcode" => $res[0]['usertemp_postcode'],
        "heardabout" => $res[0]['usertemp_heardabout']
    );
    
    //die(var_dump($usrTemp));
    
    $user_obj = new UserClass();
    
    // MAF - Create new member
    
    $MAFMemberId = $user_obj->CreateMember($usrTemp);
    if(empty($MAFMemberId)){
      // create guest user when failed
      die('failed');
    } else{
      saveInLog('member-create', 'external', $MAFMemberId, $usrTemp['state']);
       
      $userArr = array(
          "id" => $MAFMemberId,
          "gname" => $usrTemp['gname'],
          "surname" => $usrTemp['surname'],
          "email" => $usrTemp['email']
      );
      try{
        // Send welcome email
        $SMARTY->assign('user', $userArr);
        $to = $userArr['email'];
        $subject = 'MedicAlert Foundation Registration';
        $body = $SMARTY->fetch('email/welcome.tpl');
        sendMail($to, $from, $fromEmail, $subject, $body);
        echo $body;
      }
      catch(Exception $e){}
    
    }
  }
  
  
}
die();