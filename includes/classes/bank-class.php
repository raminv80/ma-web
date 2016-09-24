<?php
// Defaine the base exception class (as a catch all for the API)
class exceptionBank extends Exception{}

// And the individual errors that can occur
class exceptionBankNotFound extends exceptionBank{}
class exceptionPaymentNotPreparedFound extends exceptionBank{}

class Bank{
  private $DBobj;
  protected $requestType = 'Payment'; // Request Type (payment or echo)
  protected $transactionType = '0'; // 0 = Normal Payment (Other options available)
  protected $messageID; // Random ID to track Transaction within Code - Temporary
  protected $messageTimestamp; // Timestamp of Transaction. Set Automatically During Submission.
  protected $currency = 'AUD'; // Currency. Defaults to Australian Dollars ('AUD')
  protected $xml_request = ''; // The XML request, used for submission
  protected $xml_response = ''; // The XML response
  protected $post_request = ''; // The POST request, used for submission
  protected $post_response = ''; // The POST response
  protected $payment_id = ''; // The payment ID
  protected $payment_transactionno = ''; // The payment transaction number - Order ID
  protected $amount = ''; // Transaction amount
  protected $cc_number = ''; // Credit card number
  protected $cc_expiry_year = ''; // Credit card expiry year
  protected $cc_expiry_month = ''; // Credit card expiry month
  protected $cc_cvc = ''; // Credit card CVC/CVN
  protected $cc_name = ''; // Name on credit card
  protected $response = array(); // Response array mapped from the XML response into common variables to be stored in the database
  protected $order_info = array(); // Order information (user,shipping, billing)
  protected $errors = false;
  protected $errorMsg = '';
  protected $payment_success = false;
  protected $autorenewRecord = array();


  function __construct($data){
    global $DBobject;
    $this->DBobj = empty($data['database'])? $DBobject : $data['database'];
    
    $this->live_url = $data['settings']->payment_gateway->live_url;
    $this->test_url = $data['settings']->payment_gateway->test_url;
    $this->url_to_use = ($data['settings']->payment_gateway->live == true)? $this->live_url : $this->test_url;
    $this->merchant_id = $data['settings']->payment_gateway->merchant_id;
    $this->merchant_password = $data['settings']->payment_gateway->merchant_password;
    
    $this->order_info = $data['initPayment'];
    $this->payment_transactionno = $data['initPayment']['payment_transaction_no'];
    
    $this->messageTimestamp = date("YdmHis") . substr(microtime(), 2, 3) . sprintf("%+d", (date('Z') / 60));
    $this->messageID = sha1($this->messageTimestamp . mt_rand());
  }

  function GetErrorMessage(){
    return $this->errorMsg;
  }

  function GetPaymentId(){
    return $this->payment_id;
  }
  
  function GetBankCustomerRecord(){
    return $this->autorenewRecord;
  }
  
  function PreparePayment($data){
    $this->amount = $data['amount'];
    $this->cc_number = $data['number'];
    $this->cc_expiry_year = (strlen($data['year']) == 4) ? substr($data['year'], -2) : $data['year'];
    $this->cc_expiry_month = $data['month'];
    $this->cc_cvc = $data['csv'];
    $this->cc_name = $data['name'];
  }


  /**
   * This function initiates the processing of a payment.
   * It requires that PreparePayment() has already been called.
   * Returns true if the payment was processed successfully, otherwise returns falls. Additional information at the
   * result can be retrieved with GetResponseCode() and GetResponseMessage().
   *
   * @throws exceptionBankNotFound
   * @throws exceptionPaymentNotPreparedFound
   * @return boolean
   */
  function Submit(){
    // Check to see if we have all the necessary details
    $this->payment_success = false;
    if(empty($this->amount) && (empty($this->cc_number) || empty($this->cc_expiry_month) || empty($this->cc_expiry_year) || empty($this->cc_cvc) || empty($this->cc_name))){
      $this->errors = true;
      $this->errorMsg = 'PAYMENT ERROR: Missing payment details.';
    } elseif(!empty($this->amount) && floatval($this->amount) < 0.009 && empty($this->cc_number) && empty($this->cc_expiry_month) && empty($this->cc_expiry_year) && empty($this->cc_cvc) && empty($this->cc_name)){
      $this->StorePaymentRecord();
      $this->response['msg'] = 'FREE SAMPLE';
      $this->response['payment_status'] = 'A';
      $this->payment_success = true;
      $this->SetResponsePaymentRecord();
    } else{
      $this->StorePaymentRecord();
      $this->SubmitPayment();
      $this->SetResponsePaymentRecord();
    }
    return $this->payment_success;
  }


  /**
   * This function takes an array representing the payment record, either successful or failed.
   * It should have the following associated records
   *
   * payment_user_id => the user ID associated with this payment,
   * payment_billing_address_id => the record ID for the address record which is used for the billing address,
   * payment_shipping_address_id => the record ID for the address record which is used for the shipping address,
   * payment_subtotal => product cost component,
   * payment_discount => discount amount component,
   * payment_shipping_fee => the shipping fee component,
   * payment_gst => GST amount component,
   * payment_shipping_method => shipping method selected by user,
   * payment_payee_name => payee name which was entered by user,
   *
   * @param unknown $payment          
   * @return string
   */
  function StorePaymentRecord($payment){
    if(empty($payment)){
      $payment = $this->order_info;
    }
    if(empty($this->response['payment_status']) && empty($this->payment_transactionno)){
      $this->response['payment_status'] = empty($payment['payment_status']) ? 'P' : $payment['payment_status'];
      $this->payment_transactionno = $payment['payment_transaction_no'];
      $this->amount = $payment['payment_charged_amount'];
    }
    
    $sql = "INSERT INTO tbl_payment (payment_cart_id,payment_user_id,payment_billing_address_id,payment_shipping_address_id,payment_status,payment_subtotal,payment_discount,payment_shipping_fee,payment_charged_amount,payment_gst,payment_shipping_method,payment_shipping_comments,payment_method,payment_payee_name,payment_transaction_no,payment_user_ip,payment_created)
			VALUES(:payment_cart_id,:payment_user_id,:payment_billing_address_id,:payment_shipping_address_id,:payment_status,:payment_subtotal,:payment_discount,:payment_shipping_fee,:payment_charged_amount,:payment_gst,:payment_shipping_method,:payment_shipping_comments,:payment_method,:payment_payee_name,:payment_transaction_no,:payment_user_ip,now())";
    $params = array(
        "payment_cart_id" => $payment['payment_cart_id'], 
        "payment_user_id" => $payment['payment_user_id'], 
        "payment_billing_address_id" => $payment['payment_billing_address_id'], 
        "payment_shipping_address_id" => $payment['payment_shipping_address_id'], 
        "payment_status" => $this->response['payment_status'], 
        "payment_subtotal" => $payment['payment_subtotal'], 
        "payment_discount" => $payment['payment_discount'], 
        "payment_shipping_fee" => $payment['payment_shipping_fee'], 
        "payment_charged_amount" => $this->amount, 
        "payment_gst" => $payment['payment_gst'], 
        "payment_shipping_method" => $payment['payment_shipping_method'], 
        "payment_shipping_comments" => $payment['payment_shipping_comments'], 
        "payment_method" => $payment['payment_method'], 
        "payment_payee_name" => $payment['payment_payee_name'], 
        "payment_transaction_no" => $this->payment_transactionno, 
        "payment_user_ip" => $_SERVER['REMOTE_ADDR'] 
    );
    $sql_res = $this->DBobj->wrappedSql($sql, $params);
    $this->payment_id = $this->DBobj->wrappedSqlIdentity();
    return $this->payment_id;
  }
  
  
  /**
   * Set the response values in the current payment record
   * @return boolean
   */
  protected function SetResponsePaymentRecord(){
    if(!empty($this->payment_id)){
      $sql = "UPDATE tbl_payment SET payment_status = :payment_status, payment_response_summary_code = :payment_response_summary_code, 
          payment_response_code = :payment_response_code, payment_response_msg = :payment_response_msg,
          payment_response_receipt_no = :payment_response_receipt_no, payment_response_settlementdate = :payment_response_settlementdate,
          payment_response_transactiondate = :payment_response_transactiondate, payment_response_cardscheme = :payment_response_cardscheme,
          payment_response = :payment_response WHERE payment_id = :payment_id";
      $params = array(
          "payment_id" => $this->payment_id,
          "payment_status" => $this->response['payment_status'],
          "payment_response_summary_code" => $this->response['summary_code'],
          "payment_response_code" => $this->response['code'],
          "payment_response_msg" => $this->response['msg'],
          "payment_response_receipt_no" => $this->response['receipt_no'],
          "payment_response_settlementdate" => $this->response['settlementdate'],
          "payment_response_transactiondate" => $this->response['transactiondate'],
          "payment_response_cardscheme" => $this->response['cardscheme'],
          "payment_response" => $this->response['payment_response']
      );
      return $this->DBobj->wrappedSql($sql, $params);
    }
    return false;
  }
  
  
  /**
   * Set the user_id, billing_address_id and shipping_address_id values in the current payment record
   * Array keys: payment_user_id, payment_billing_address_id, payment_shipping_address_id.
   * @param array $payment
   * @return boolean
   */
  function SetUserAddressIds($payment){
    if(!empty($this->payment_id)){
      $sql = "UPDATE tbl_payment SET payment_user_id = :payment_user_id, payment_billing_address_id = :payment_billing_address_id, payment_shipping_address_id = :payment_shipping_address_id
  			WHERE payment_id = :payment_id";
      $params = array(
          "payment_id" => $this->payment_id,
          "payment_user_id" => $payment['payment_user_id'],
          "payment_billing_address_id" => $payment['payment_billing_address_id'],
          "payment_shipping_address_id" => $payment['payment_shipping_address_id']
      );
      return $this->DBobj->wrappedSql($sql, $params);
    }
    return false;
  }


  /**
   * Add a new status for closed cart.
   * Require payment_id and status_id is set (1) as default
   * Optional: admin_id
   *
   * @param int $paymentId          
   * @param int $statusId          
   * @param int $adminId          
   * @return boolean
   */
  function SetOrderStatus($paymentId, $statusId = 1, $adminId = null){
    $sql = " INSERT INTO tbl_order ( order_payment_id, order_status_id, order_admin_id, order_created )
      VALUES ( :pid, :sid, :aid, now() )";
    $params = array(
        ":pid" => $paymentId, 
        ":sid" => $statusId, 
        ":aid" => $adminId 
    );
    return $this->DBobj->wrappedSql($sql, $params);
  }


  /**
   * Save the sent-confirmation/invoice-email id in the payment table.
   *
   * @param int $paymentId          
   * @param int $emailId          
   * @return boolean
   */
  function SetInvoiceEmail($paymentId, $emailId){
    if(!empty($this->payment_id)){
      $sql = "UPDATE tbl_payment SET payment_invoice_email_id = :email WHERE payment_id = :pid ";
      $params = array(
          ":pid" => $paymentId, 
          ":email" => $emailId 
      );
      return $this->DBobj->wrappedSql($sql, $params);
    }
    return false;
  }


  /**
   * Return payment record given the payment_id
   *
   * @param int $paymentId          
   * @return array
   */
  function GetPaymentRecord($paymentId){
    $sql = "SELECT * FROM tbl_payment WHERE payment_id = :id AND payment_deleted IS NULL";
    $res = $this->DBobj->wrappedSql($sql, array(":id" => $paymentId));
    return $res[0];
  }

  
  /**
   * Check payment status and set status variable and error messages accordingly
   * @param array $_successCodeArr
   */
  protected function CheckPaymentStatus($_successCodeArr = array('00', '08')){
    if(in_array($this->response['code'], $_successCodeArr)){
      // Payment successful
      $this->response['payment_status'] = 'A';
      $this->payment_success = true;
    }else{
      // Payment failed
      $this->response['payment_status'] = 'F';
      $this->payment_success = false;
      if(!empty($this->response['code'])){
        $this->errorMsg .= "PAYMENT FAILED: Response code({$this->response['code']}) - {$this->response['msg']}<br>";
        //$this->errorMsg .= '<br> ->REQUEST XML: [ '.$this->xml_request.' ] <br>';
        //$this->errorMsg .= '<br> ->RESPONSE XML: [ '.$this->xml_response.' ] <br>';
      }
      $this->errorMsg = empty($this->errorMsg) ? 'Undefined payment error' : $this->errorMsg;
    }
  }
  
  
  /**
   * Create Auto-renew record
   * @param array $_data
   */
  function StoreAutoRenew($_data){
    $params = array(
        "autorenew_user_id" => $_data['user_id'],
        "autorenew_bank_customer_id" => $_data['bank_customer_id'],
        "autorenew_method" => $_data['method'],
        "autorenew_ip" => $_SERVER['REMOTE_ADDR'],
        "autorenew_ua" => $_SERVER['HTTP_USER_AGENT'],
        "autorenew_singletoken" => $_data['singletoken']
    );
    $sql = "INSERT INTO tbl_autorenew (autorenew_user_id, autorenew_bank_customer_id, autorenew_method, autorenew_ip, autorenew_ua, autorenew_singletoken, autorenew_created) 
        VALUES (:autorenew_user_id, :autorenew_bank_customer_id, :autorenew_method, :autorenew_ip, :autorenew_ua, :autorenew_singletoken, NOW())";
    return $this->DBobj->wrappedSql($sql, $params);
  }

  
  /**
   * Format the amount to cents (dollars to cents)
   * Returns amount as cents EG: 1000 for $10
   */
  function FormatToCents($amount){
    return number_format((float)$amount * 100, 0, '.', '');
  }
}