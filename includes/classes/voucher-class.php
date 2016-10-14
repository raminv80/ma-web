<?php
class exceptionVoucher extends Exception{}

class Voucher{
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
  function CreateVoucher($_data){
    $params = array(
        ":voucher_payment_id" => $_data['payment_id'],
        ":voucher_code" => $_data['code'],
        ":voucher_name" => $_data['name'],
        ":voucher_email" => $_data['email'],
        ":voucher_recipientname" => $_data['recipientname'],
        ":voucher_recipientemail" => $_data['recipientemail'],
        ":voucher_message" => $_data['message'],
        ":voucher_amount" => $_data['amount'],
        ":voucher_start_date" => $_data['start_date'],
        ":voucher_end_date" => $_data['end_date'],
        ":voucher_code_email_id" => (empty($_data['code_email_id']) ? 0 : $_data['code_email_id']),
        ":voucher_confirmation_email_id" => (empty($_data['confirmation_email_id']) ? 0 : $_data['confirmation_email_id'])
    );
  
    $sql = "INSERT INTO tbl_voucher (voucher_payment_id, voucher_code, voucher_name, voucher_email, voucher_recipientname, voucher_recipientemail, voucher_message, voucher_amount, voucher_start_date, voucher_end_date, voucher_code_email_id, voucher_confirmation_email_id, voucher_created)
			values (:voucher_payment_id, :voucher_code, :voucher_name, :voucher_email, :voucher_recipientname, :voucher_recipientemail, :voucher_message, :voucher_amount, :voucher_start_date, :voucher_end_date, :voucher_code_email_id, :voucher_confirmation_email_id, NOW())";
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
  function GetVoucher($_id = null){
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
  function DeleteVoucher($_id = null){
    if(!empty($_id)){
      $this->voucherId = $_id;
    }
    $sql = "UPDATE tbl_voucher SET voucher_deleted = NOW() WHERE voucher_deleted IS NULL AND voucher_id = :id";
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
  function SetVoucherEmailIds($_recipientEmailId, $_senderEmailId, $_id = null){
    if(!empty($_id)){
      $this->voucherId = $_id;
    }
    $sql = "UPDATE tbl_voucher SET voucher_code_email_id = :rid, voucher_confirmation_email_id = :sid WHERE voucher_deleted IS NULL AND voucher_id = :id";
    if($this->DBobj->wrappedSql($sql, array(":id" => $this->voucherId, ":rid" => $_recipientEmailId, ":sid" => $_senderEmailId))){
      return true;
    }
    return false;
  }
  
  /**
   * Update voucher_redeemed
   *
   * @return boolean
   */
  protected function UpdateRedeemDate(){
    $sql = "UPDATE tbl_voucher SET voucher_redeemed = NOW() WHERE voucher_redeemed IS NULL AND voucher_deleted IS NULL AND voucher_id = :id";
    if($this->DBobj->wrappedSql($sql, array(":id" => $this->voucherId))){
      return true;
    }
    return false;
  }
  
  
  /**
   * Redeem voucher
   *
   * @param string $_code
   * @return boolean
   */
  function RedeemVoucher($_code){
    $sql = "SELECT voucher_id FROM tbl_voucher WHERE voucher_deleted IS NULL AND voucher_redeemed IS NULL AND voucher_code = :code";
    if($res = $this->DBobj->wrappedSql($sql, array(":code" => $_code))){
      $this->voucherId = $res[0]['voucher_id'];
      return $this->UpdateRedeemDate();
    }
    return false;
  }
  
  
  /**
   * Check duplicate voucher code
   *
   * @param string $_code
   * @return boolean
   */
  function ExistVoucherCode($_code){
    $sql = "SELECT voucher_id FROM tbl_voucher WHERE voucher_code = :code";
    if($res = $this->DBobj->wrappedSql($sql, array(":code" => $_code))){
      return true;
    }
    return false;
  }
  
  
  /**
   * Generate voucher code
   * 
   * @return boolean
   */
  function GenerateVoucherCode(){
    $blockSize = 5;
    $blockQty = 4;
    $codeArr = array(); 
    
    //Build code-array
    while(count($codeArr) < $blockQty){
      $subcodeStr = genRandomString($blockSize);
      if(!in_array($subcodeStr, $codeArr)) {
        array_push($codeArr, $subcodeStr);
      }
      //Code complete
      if(count($codeArr) == $blockQty){
        $codeStr = implode('-', $codeArr);
        if(!$this->ExistVoucherCode($codeStr)){
          return $codeStr;
        }else{
          $codeArr = array();
        }
      }
    }
    return '';
  }
}
	
