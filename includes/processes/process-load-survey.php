<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;



$questions = array();
try{

  $SMARTY->assign('verifytoken', false);
  
  $csql = "SELECT surveytoken_status FROM tbl_surveytoken WHERE surveytoken_token = :surveytoken_token";
  
  if($cres = $DBobject->wrappedSql($csql,array(":surveytoken_token"=>$_GET['token']))){
    
    if($cres[0]['surveytoken_status'] == 1){
      $SMARTY->assign('verifytoken', false);
    }else{
      $SMARTY->assign('verifytoken', true);
      

      $SMARTY->assign('surveytoken', $_GET['token']);
      
      $sql = "SELECT * FROM tbl_question WHERE question_deleted IS NULL ORDER BY question_order ASC";
      if($res = $DBobject->wrappedSql($sql)){
      
        foreach ($res as $q){
      
          $typeid = $q['question_type_id'];
      
          if(!is_array($questions[$typeid])){
            $questions[$typeid] = array();
          }
      
          array_push($questions[$typeid], array($q[question_id],$q['question_question'],$q['question_qoption_group_id'],$q['question_mandatory']));
        }
      
      }
      $SMARTY->assign('questions', $questions);
      
      
      $answers = array();
      
      $sql = "SELECT * FROM tbl_qoption WHERE qoption_delete IS NULL";
      if($res = $DBobject->wrappedSql($sql)){
      
        foreach ($res as $a){
      
          $typeid = $a['qoption_group_id'];
      
          if(!is_array($answers[$typeid])){
            $answers[$typeid] = array();
          }
      
          array_push($answers[$typeid], $a['qoption_option']);
        }
      
      }
      $SMARTY->assign('answers', $answers);
      
    }
  }
  
}catch(exceptionCart $e) {
  $SMARTY->assign('error', $e->getMessage());
}

  
