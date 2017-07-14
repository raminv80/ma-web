<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;

$questions = array();
try{
  
  $SMARTY->assign('verifytoken', false);
  
  $csql = "SELECT surveytoken_id, surveytoken_status, surveytoken_question_type_id FROM tbl_surveytoken WHERE surveytoken_token = :surveytoken_token";
  
  if($cres = $DBobject->wrappedSql($csql, array(":surveytoken_token" => $_GET['token']))){
    if($cres[0]['surveytoken_status'] == 1){
      $SMARTY->assign('verifytoken', false);
    } else{
      $SMARTY->assign('verifytoken', true);
      $SMARTY->assign('surveytoken_id', $cres[0]['surveytoken_id']);
      
      $param = array();
      $where = " AND question_type_id = :question_type_id ";
      $param[":question_type_id"] = 1;
      
      if(!empty($cres[0]['surveytoken_question_type_id'])){
        $where = " AND question_type_id = :question_type_id ";
        $param[":question_type_id"] = $cres[0]['surveytoken_question_type_id'];
      }
      
      $sql = "SELECT * FROM tbl_question WHERE question_deleted IS NULL {$where} ORDER BY question_order ASC";
      if($res = $DBobject->wrappedSql($sql, $param)){
        foreach($res as $q){
          $typeid = $q['question_type_id'];
          if(!is_array($questions[$typeid])){
            $questions[$typeid] = array();
          }
          array_push($questions[$typeid], array(
              "question_id" => $q['question_id'], 
              "question" => $q['question_question'], 
              "option_group_id" => $q['question_qoption_group_id'], 
              "is_mandatory" => $q['question_mandatory'], 
              "question_triggers" => $q['question_triggers'] 
          ));
        }
      }
      $SMARTY->assign('questions', $questions);
      
      $answers = array();
      $sql = "SELECT * FROM tbl_qoption WHERE qoption_deleted IS NULL";
      if($res = $DBobject->wrappedSql($sql)){
        foreach($res as $a){
          $typeid = $a['qoption_group_id'];
          if(!is_array($answers[$typeid])){
            $answers[$typeid] = array();
          }
          array_push($answers[$typeid], array(
              "option_id" => $a['qoption_id'], 
              "option" => $a['qoption_option'] 
          ));
        }
      }
      $SMARTY->assign('answers', $answers);
    }
  }
}
catch(exceptionCart $e){
  $SMARTY->assign('error', $e->getMessage());
}

  
