<?php
class exceptionVoucher extends Exception{}

class Voucher{
  protected $DBobj;
  protected $voucherRecord;


  function __construct($_db = '', $_site = ''){
    global $DBobject;
    
    $this->DBobj = empty($_db)? $DBobject : $_db;
    $this->user_id = 0;
  }
 
  
  /**
   * Insert a new record in tbl_voucher and return associative array: ('id', 'gname', 'surname', 'email')
   * On error return associative array: ('error')
   * Require associative array: ('gname', 'surname', 'password', 'email')
   *
   * @param array $user
   * @return array
   */
  function CreateVoucher($params){
    $temp_str = getPass(time() . '@@' . $user['email'], genRandomString(10));
    $params = array(
        ":username" => time() . '@@' . $user['email'],
        ":gname" => $user['gname'],
        ":surname" => (empty($user['surname'])? '' : $user['surname']),
        ":email" => $user['email'],
        ":password" => $temp_str,
        ":mobile" => $user['mobile'],
        ":user_site" => $this->site,
        ":email_promo" => (empty($user['want_email_promo'])? 0 : 1),
        ":sms_promo" => (empty($user['want_sms_promo'])? 0 : 1),
        ":ip" => $_SERVER['REMOTE_ADDR'],
        ":browser" => $_SERVER['HTTP_USER_AGENT']
    );
  
    $sql = "INSERT INTO tbl_voucher (user_username, user_gname, user_surname, user_email, user_password, user_mobile, user_site, user_email_promo, user_sms_promo, user_ip, user_browser, user_created)
					 values ( :username, :gname, :surname, :email, :password, :mobile, :user_site, :email_promo, :sms_promo, :ip, :browser, now() )";
    if($this->DBobj->wrappedSql($sql, $params)){
      $userId = $this->DBobj->wrappedSqlIdentity();
      $this->user_id = $userId;
      $result = array(
          "id" => $userId,
          "gname" => $user['gname'],
          "surname" => $user['surname'],
          "email" => $user['email']
      );
    } else{
      $this->user_id = 0;
      $result = array(
          'error' => 'There was a connection problem. Please, try again!'
      );
    }
    return $result;
  }


  /**
   * Update password field of a specific record in tbl_voucher and return associative array: ('success')
   * On error return associative array: ('error')
   * Require associative array: ( 'email', 'password', 'old_password')
   *
   * @param array $data          
   * @return array
   */
  function UpdatePassword($data){
    $res = $this->Authenticate($data['email'], $data['old_password']);
    if($res['error']){
      return array(
          'error' => 'Incorrect old password.' 
      );
    } else{
      $temp_str = getPass($data['email'], $data['password']);
      $params = array(
          ":id" => $res['id'], 
          ":password" => $temp_str, 
          ":ip" => $_SERVER['REMOTE_ADDR'], 
          ":browser" => $_SERVER['HTTP_USER_AGENT'] 
      );
      $sql = "UPDATE tbl_voucher SET user_password = :password, user_ip = :ip, user_browser = :browser WHERE user_id = :id ";
      if($this->DBobj->wrappedSql($sql, $params)){
        return array(
            'success' => 'Password has been updated.' 
        );
      } else{
        return array(
            'error' => 'There was a connection problem. Please, try again!' 
        );
      }
    }
  }


  /**
   * Get a single non-deleted record from tbl_voucher given the username
   * On error return false
   * Require string: username
   *
   * @param string $uname          
   * @return mixed
   */
  function GetByUsername($uname){
    $sql = "SELECT * FROM tbl_voucher WHERE user_username = :uname AND user_deleted IS NULL";
    $res = $this->DBobj->wrappedSql($sql, array(
        ":uname" => $uname 
    ));
    return $res[0];
  }


  
}
	
