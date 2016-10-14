<?php
class exceptionSurvey extends Exception{}

class Survey{
  protected $DBobj;
  protected $voucherId;
  protected $voucherRecord  = array();
  protected $errorMsg = null;
  


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
      $surveytoken = genRandomString(10).time();
      $sql = "SELECT surveytoken_id FROM tbl_surveytoken WHERE surveytoken_token = :surveytoken_token AND surveytoken_deleted IS NULL";
      $res = $this->DBobj->wrappedSql($sql, array(":surveytoken_token" => $surveytoken));
    }while(!empty($res));
    
    $question_type_id = 1;
    
    
    if($res=$DBobject->wrappedSql($inssql, )){
      
    }
    
    $params = array(
        ":surveytoken_user_id" => $_userId,
        ":surveytoken_token" => $surveytoken,
        ":surveytoken_useremail"=> $_email,
        ":surveytoken_question_type_id" => $_questionTypeId
    );
    $sql = "INSERT INTO tbl_surveytoken (surveytoken_user_id, surveytoken_token, surveytoken_useremail, surveytoken_question_type_id, surveytoken_created) VALUES
      (:surveytoken_user_id, :surveytoken_token, :surveytoken_useremail, :surveytoken_question_type_id, NOW())";
    if($this->DBobj->wrappedSql($sql, $params)){
      $this->voucherId = $this->DBobj->wrappedSqlIdentity();
      return $this->voucherId;
    }
    return 0;
  }

  
  /**
   * Get voucher record including email
   * 
   * @param int $_id
   * @return array
   */
  function GetSurvey($_id = null){
    if(!empty($_id)){
      $this->voucherId = $_id;
    }
    if(empty($this->voucherRecord)){
      $sql = "SELECT * FROM tbl_voucher LEFT JOIN tbl_email_queue ON email_id = voucher_code_email_id WHERE voucher_deleted IS NULL AND email_deleted IS NULL AND voucher_id = :id";
      if($res = $this->DBobj->wrappedSql($sql, array(":id" => $this->voucherId))){
        $this->voucherRecord = $res[0];
      }
    }
    return $this->voucherRecord;
  }

  
  /**
   * Delete voucher
   *
   * @param int $_id
   * @return boolean
   */
  function DeleteSurvey($_id = null){
    if(!empty($_id)){
      $this->voucherId = $_id;
    }
    $sql = "UPDATE tbl_voucher SET voucher_deleted = NOW() WHERE voucher_id = :id";
    if($this->DBobj->wrappedSql($sql, array(":id" => $this->voucherId))){
      $this->voucherRecord = array();
      $this->voucherId = 0;
      return true;
    }
    return false;
  }
  

  /**
   * Update voucher email ids
   *
   * @param int $_id
   * @return boolean
   */
  function SetSurveyEmailIds($_recipientEmailId, $_senderEmailId, $_id = null){
    if(!empty($_id)){
      $this->voucherId = $_id;
    }
    $sql = "UPDATE tbl_voucher SET voucher_code_email_id = :rid, voucher_confirmation_email_id = :sid WHERE voucher_id = :id";
    if($this->DBobj->wrappedSql($sql, array(":id" => $this->voucherId, ":rid" => $_recipientEmailId, ":sid" => $_senderEmailId))){
      $this->voucherRecord = array();
      $this->voucherId = 0;
      return true;
    }
    return false;
  }

  

  
  

}
	
