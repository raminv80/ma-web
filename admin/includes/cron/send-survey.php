<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'includes/functions/functions.php';
global $SMARTY, $DBobject, $CONFIG, $GA_ID;

try{
  
  require_once 'includes/classes/survey-class.php';
  $surveyObj = new Survey();
  
  if($res = $surveyObj->GetPendingSurveys()){
    foreach($res as $r){
      $to = $r['surveytoken_useremail'];
      $surveytoken = $r['surveytoken_token'];
      $subject = 'Survey - MedicAlert';
      //$fromEmail = 'noreply@' . str_replace("www.", "", $GLOBALS['HTTP_HOST']);
      $fromEmail = (string)$CONFIG->company->email_from;
      $from = (string)$CONFIG->company->name;
      
      $message1 = "Thank you for your recent purchase.";
      $message2 = "MedicAlert values your feedback, so we can continue to improve this process,
          we would appreciate it if you could spare just a few minutes to let us know about your experience.";
      
      if($r['surveytoken_question_type_id'] == 2){
        $message1 = "Thank you for keeping your member profile up-to-date.";
        $message2 = "So we can continue to improve this process, we would appreciate it if you could spare just a few minutes to let us know about your experience.";
      }

      $SMARTY->unloadFilter('output', 'trimwhitespace');
      $SMARTY->assign('message1', $message1);
      $SMARTY->assign('message2', $message2);
      $SMARTY->assign('surveytoken', $surveytoken);
      $SMARTY->assign('DOMAIN', "http://" . $GLOBALS['HTTP_HOST']);
      $COMP = json_encode($CONFIG->company);
      $SMARTY->assign('COMPANY', json_decode($COMP, TRUE));
      
      $body = $SMARTY->fetch('email/email-survey.tpl');
      if($sent = sendMail($to, $from, $fromEmail, $subject, $body, null, 0, 0, -2)){
        $surveyObj->SetSurveyEmailId($sent, $r['surveytoken_id']);
        echo $sent.'<br>';
      }
    }
  }
  
}catch(Exception $e){
  sendErrorMail('weberrors@them.com.au', $from, $fromEmail, 'Send survey', "Token:  {$surveytoken} <br>". $surveyObj->getErrorMsg());
}
die();