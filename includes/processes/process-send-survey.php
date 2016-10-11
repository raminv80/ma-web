<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;


try{
  
  $surveytoken = genRandomString(10).time();
  $sql = "INSERT INTO tbl_surveytoken (surveytoken_user_id, surveytoken_token, surveytoken_created) VALUES
      (:surveytoken_user_id, :surveytoken_token, NOW())";
  
  if($res=$DBobject->wrappedSql($sql, array(":surveytoken_user_id"=>$user.id, ":surveytoken_token"=>$surveytoken))){
    //$to = $user.email;
    $to = "nijesh@them.com.au";
    $subject = 'Survey - MedicAlert';
    $fromEmail = (string)$CONFIG->company->email_from;
    $from = (string)$CONFIG->company->name;
    $bcc = null;
    
    echo $body = "<p>Hi</p>
      <p>Please click on link below in order to take our survey.</p>
      <p><a href='http://".$_SERVER['HTTP_HOST']."/survey?token={$surveytoken}'>MedicAlert survey</a></p>
          <p>Thank you,</p>
          <p>".(string)$CONFIG->company->name."</p>";
    
    $sent = sendMail($to, $from, $fromEmail, $subject, $body, $bcc);
  }
}catch(Exception $e){
  $error = 'There was an error sending your enquiry.';
}