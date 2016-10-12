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
  
  if($res=$DBobject->wrappedSql($inssql, array(":surveytoken_user_id"=>$_SESSION['user']['public']['id'], 
      ":surveytoken_token"=>$surveytoken, 
      ":surveytoken_useremail"=>$_SESSION['user']['public']['email'],
      ":surveytoken_question_type_id" => $question_type_id
  ))){
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
  echo $error = 'There was an error sending a survey.';
}