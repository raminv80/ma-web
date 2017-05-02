<?php
$error = 'Missing required info. Please try again.';
if(checkToken('frontend', $_POST["formToken"]) && empty($_POST['honeypot']) && (time() - $_POST['timestamp']) > 3){
  if(!empty($_POST['email']) && !empty($_POST['name']) && !empty($_POST['name']) && !empty($_POST['friendname']) && !empty($_POST['friendemail'])){
    global $CONFIG, $DBobject, $SMARTY, $SITE, $GA_ID;
    
    $error = '';
    $sent = 0;
    
    // SEND EMAIL TO FRIEND
    $SMARTY->unloadFilter('output', 'trimwhitespace');
    try{
      $SMARTY->assign('DOMAIN', "http://" . $_SERVER['HTTP_HOST']);
      $subject = 'Seniors offer just for you - Help protect the one you love with MedicAlert';
      $fromEmail = (string) $CONFIG->company->email_from;
      $to = $_POST['friendemail'];
      $SMARTY->assign('friend_name', $_POST['friendname']);
      $SMARTY->assign('user_name', $_POST['name']);
      $COMP = json_encode($CONFIG->company);
      $SMARTY->assign('COMPANY', json_decode($COMP,TRUE));
      $from = (string) $CONFIG->company->name;
      $body = $SMARTY->fetch("email/friend-referral-senior.tpl");
      $sent = sendMail($to, $from, $fromEmail, $subject, $body);
    }catch (Exception $e){
      $error = 'There was an error sending the contact email.';
    }
        
    // SAVE IN DATABASE
    if(empty($error)){
      try{
        $sql = "INSERT INTO tbl_refer (refer_name, refer_email, refer_membership_no, refer_friendname, refer_friendemail, refer_email_id, refer_created)
              VALUES (:refer_name, :refer_email, :refer_membership_no, :refer_friendname, :refer_friendemail, :refer_email_id, now() )";
        $params = array(
            ":refer_name" => $_POST['name'], 
            ":refer_email" => $_POST['email'], 
            ":refer_membership_no" => $_POST['memberno'], 
            ":refer_friendname" => $_POST['friendname'], 
            ":refer_friendemail" => $_POST['friendemail'], 
            ":refer_email_id" => $sent 
        );
        $DBobject->wrappedSql($sql, $params);
      }
      catch(Exception $e){
        //$error = 'There was an unexpected error saving your enquiry.';
      }
    }

    if(empty($error)){
      if(!empty($GA_ID)){
        sendGAEvent($GA_ID, 'Refer a friend', 'Submitted', $sent);
      }
      header("Location: /thank-you-for-referring-a-friend");
      die();
    }
  } else{
    $error = 'Please provide the required information.';
  }
} else{
  $error = 'Your session has expired.<br>Please try again.';
}

$_SESSION['post'] = $_POST;
$_SESSION['error'] = $error;
header("Location: {$_SERVER['HTTP_REFERER']}#form-error");
die();