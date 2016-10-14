<?php
class exceptionSurvey extends Exception{}

class Survey{
  protected $DBobj;
  protected $surveyId;
  protected $surveyToken;
  protected $surveyRecord  = array();
  protected $errorMsg = null;
  
  /*
   * 
   * 
   *  THE SURVEY RESPONSE WAS NOT INCLUDED IN THIS CLASS
   * 
   * 
   * 
   */
  
  function __construct($_db = ''){
    global $DBobject;
    
    $this->DBobj = empty($_db)? $DBobject : $_db;
  }
 
  
  /**
   * Insert a new record 
   *
   * @param array $_data
   * @return array
   */
  function CreateSurvey($_userId, $_email, $_questionTypeId = 1){
    
    do{
      $this->surveyToken = genRandomString(10).time();
      $res = $this->GetSurveyByToken();
    }while(!empty($res));
    
    $params = array(
        ":surveytoken_user_id" => $_userId,
        ":surveytoken_token" => $this->surveyToken,
        ":surveytoken_useremail"=> $_email,
        ":surveytoken_question_type_id" => $_questionTypeId
    );
    $sql = "INSERT INTO tbl_surveytoken (surveytoken_user_id, surveytoken_token, surveytoken_useremail, surveytoken_question_type_id, surveytoken_created) VALUES
      (:surveytoken_user_id, :surveytoken_token, :surveytoken_useremail, :surveytoken_question_type_id, NOW())";
    if($this->DBobj->wrappedSql($sql, $params)){
      $this->surveyId = $this->DBobj->wrappedSqlIdentity();
      return $this->surveyId;
    }
    return 0;
  }

  
  /**
   * Get survey record by id
   * 
   * @param int $_id
   * @return array
   */
  function GetSurveyById($_id = null){
    if(!empty($_id)){
      $this->surveyId = $_id;
    }
    if(empty($this->surveyRecord)){
      $sql = "SELECT * FROM tbl_surveytoken WHERE surveytoken_id= :id AND surveytoken_deleted IS NULL";
      if($res = $this->DBobj->wrappedSql($sql, array(":id" => $this->surveyId))){
        $this->surveyRecord = $res[0];
        $this->surveyToken = $res[0]['surveytoken_token'];
      }
    }
    return $this->surveyRecord;
  }
  

  /**
   * Get survey record by token
   *
   * @param int $_id
   * @return array
   */
  function GetSurveyByToken($_token = null){
    if(!empty($_token)){
      $this->surveyToken = $_token;
    }
    if(empty($this->surveyRecord)){
      $sql = "SELECT * FROM tbl_surveytoken WHERE surveytoken_token= :id AND surveytoken_deleted IS NULL";
      if($res = $this->DBobj->wrappedSql($sql, array(":id" => $this->surveyToken))){
        $this->surveyId = $res[0]['surveytoken_id'];
        $this->surveyRecord = $res[0];
      }
    }
    return $this->surveyRecord;
  }
  
  
  /**
   * Delete survey
   *
   * @param int $_id
   * @return boolean
   */
  function DeleteSurvey($_id = null){
    if(!empty($_id)){
      $this->voucherId = $_id;
    }
    $sql = "UPDATE tbl_surveytoken SET surveytoken_deleted = NOW() WHERE surveytoken_deleted IS NULL AND surveytoken_id = :id";
    if($this->DBobj->wrappedSql($sql, array(":id" => $this->voucherId))){
      $this->surveyRecord = array();
      $this->surveyId = 0;
      $this->surveyToken = 0;
      return true;
    }
    return false;
  }
  

  /**
   * Update survey email id
   * 
   * @param int $_emailId
   * @param int $_id
   * @return boolean
   */
  function SetSurveyEmailId($_emailId, $_id = null){
    if(!empty($_id)){
      $this->surveyId = $_id;
    }
    $sql = "UPDATE tbl_surveytoken SET surveytoken_email_id = :email_id WHERE surveytoken_deleted IS NULL AND surveytoken_id = :id";
    if($this->DBobj->wrappedSql($sql, array(":id" => $this->surveyId, ":email_id" => $_emailId))){
      return true;
    }
    return false;
  }

  
  /**
   * Get ALL pending surveys
   * 
   * @param int $_limit
   * @return array
   */
  function GetPendingSurveys($_limit = 50){
    $sql = "SELECT * FROM tbl_surveytoken WHERE surveytoken_deleted IS NULL AND (surveytoken_email_id IS NULL OR surveytoken_email_id = 0) ORDER BY surveytoken_created LIMIT {$_limit}";
    return $this->DBobj->wrappedSql($sql);
  }
  

}
	
