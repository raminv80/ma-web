<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;


try{
  
  do{
  
  $surveytoken = genRandomString(10).time();
  
  $sql = "SELECT surveytoken_id FROM tbl_surveytoken WHERE surveytoken_token = :surveytoken_token AND surveytoken_deleted IS NULL";
  $res = $DBobject->wrappedSql($sql, array(":surveytoken_token"=>$surveytoken));
  
  }while(!empty($res));
  
  $question_type_id = 1;
  
  $inssql = "INSERT INTO tbl_surveytoken (surveytoken_user_id, surveytoken_token, surveytoken_useremail, surveytoken_question_type_id, surveytoken_created) VALUES
      (:surveytoken_user_id, :surveytoken_token, :surveytoken_useremail, :surveytoken_question_type_id, NOW())";
  
  if($res=$DBobject->wrappedSql($inssql, array(":surveytoken_user_id"=>($_SESSION['user']['public']['id'])?$_SESSION['user']['public']['id']:2222, 
      ":surveytoken_token"=>$surveytoken, 
      ":surveytoken_useremail"=>($_SESSION['user']['public']['email'])?$_SESSION['user']['public']['email']:"nijesh@gmail.com",
      ":surveytoken_question_type_id" => $question_type_id
  ))){
    //$to = $user.email;
    $to = "nijesh@them.com.au";
    $subject = 'Survey - MedicAlert';
    $fromEmail = (string)$CONFIG->company->email_from;
    $from = (string)$CONFIG->company->name;
    $bcc = null;
    
    if($question_type_id == 1){
      $message1 = "Thank you for your recent purchase.";
      $message2 = "MedicAlert values your feedback, so we can continue to improve this process, 
          we would appreciate it if you could spare just a few minutes to let us know about your experience.";
    }else if($question_type_id == 2){

      $message1 = "Thank you for keeping your member profile up-to-date.";
      $message2 = "So we can continue to improve this process, we would appreciate it if you could spare just a few minutes to let us know about your experience.";
    }

    $SMARTY->assign('message1', $message1);
    $SMARTY->assign('message2', $message2);
    $SMARTY->assign('surveytoken', $surveytoken);
    

    echo $body = $SMARTY->fetch('email/email-survey.tpl');
    
    $sent = sendMail($to, $from, $fromEmail, $subject, $body, $bcc);
  }
}catch(Exception $e){
  echo $error = "There was an error sending a survey {$e}.";
}