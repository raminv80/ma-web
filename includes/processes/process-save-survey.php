<?php
if(checkToken('frontend', $_POST["formToken"]) && empty($_POST['honeypot']) && (time() - $_POST['timestamp']) > 3){
  
  global $SMARTY, $DBobject, $CONFIG, $GA_ID;
  
  $params = array();
  $insert = array();
  
  $surveytoken_id = $_POST['surveytoken-id'];
  $sql = "SELECT * FROM tbl_surveytoken WHERE surveytoken_id = :surveytoken_id";
  
  $error = "";
  if($res = $DBobject->wrappedSql($sql, array(
      ":surveytoken_id" => $surveytoken_id 
  ))){
    if($res[0]['surveytoken_status'] == 0){
      try{
        $upsql = "UPDATE tbl_surveytoken SET surveytoken_status = 1 WHERE surveytoken_id = :surveytoken_id";
        $DBobject->wrappedSql($upsql, array(
            ":surveytoken_id" => $surveytoken_id 
        ));
      }
      catch(Exception $e){
        $error = 'There was an unexpected error updating your survey token.';
      }
      
      if(empty($error)){
        
        foreach($_POST['questionid'] as $key => $quesid){
          array_push($insert, "(:token{$key},:question_id{$key},:answerid{$key},:answer{$key}, NOW())");
          
          $answer = "";
          
          if(!empty($_POST['answerid'][$key]) && $_POST['answerid'][$key] != 0){
            $checksql = "SELECT qoption_option FROM tbl_qoption WHERE qoption_id = :qoption_id AND qoption_deleted IS NULL";
            if($checkres = $DBobject->wrappedSql($checksql, array(
                ":qoption_id" => $_POST['answerid'][$key] 
            ))){
              $answer = $checkres[0]['qoption_option'];
            }
          }
          $params[":token{$key}"] = $surveytoken_id;
          $params[":question_id{$key}"] = $quesid;
          $params[":answerid{$key}"] = (empty($_POST['answerid'][$key]))? 0 : $_POST['answerid'][$key];
          $params[":answer{$key}"] = (empty($_POST['answer'][$key]))? $answer : $_POST['answer'][$key];
        }
        
        $ins = implode(", ", $insert);
        // SAVE IN DATABASE
        try{
          $sql = "INSERT INTO tbl_survey (survey_surveytoken_id,survey_question_id,survey_qoption_id,survey_answer,survey_created) VALUES " . $ins;
          $DBobject->wrappedSql($sql, $params);
          
          if(!empty($GA_ID)){
            sendGAEvent($GA_ID, 'Survey', 'Submitted', $surveytoken_id);
          }
          
          // Notify when requested
          if(!empty($_POST['answer']['20']) || !empty($_POST['answer']['21'])){
            try{
              $SMARTY->assign('DOMAIN', "http://" . $_SERVER['HTTP_HOST']);
              $body = '<h2>Website - Survey</h2><br><b>' . $res[0]['surveytoken_user_id'] . '</b> has requested to be contacted about her/his feedback.';
              $subject = 'Someone has requested to be contacted about her/his feedback';
              $fromEmail = (string)$CONFIG->company->email_from;
              $to = 'kreek@medicalert.org.au, surveys@medicalert.org.au';
              $COMP = json_encode($CONFIG->company);
              $SMARTY->assign('COMPANY', json_decode($COMP, TRUE));
              $from = (string)$CONFIG->company->name;
              $sent = sendMail($to, $from, $fromEmail, $subject, $body);
            }
            catch(Exception $e){}
          }
          header("Location: /thank-you-for-taking-a-survey");
          die();
        }
        catch(Exception $e){
          $error = "There was an unexpected error saving your survey {$e}.";
        }
      }
    } else{
      header("Location: /thank-you-for-taking-a-survey");
      die();
    }
  }
}
$_SESSION['post'] = $_POST;
$_SESSION['error'] = $error;
header("Location: {$_SERVER['HTTP_REFERER']}#form-error");
die();
