<?php
// -------------------------------------------------------------------------
// Class:      Qvalent_Rest_PayWayAPI
// Created By: THEM Advertising
// Version:    1.0
// Created On: 01-Aug-2015
//
// Copyright 2015 THEM Advertising.
// -------------------------------------------------------------------------

class Qvalent_REST_PayWayAPI
{
  const SECRET = 1;
  const PUBLISHABLE = 2;
  
  const URL = 'https://api.payway.com.au/rest/v1/';

  var $secretAPIkey;
  var $publishableAPIkey;
  var $merchantId;
  var $bankAccountId;

  function Qvalent_REST_PayWayAPI()
  {
      $this->secretAPIkey = 'T10023_SEC_6es29q2sek6cnvetsrs6h8pkay5um3985djkjv93xm9mf8vruc3enbzd2ek2';
      $this->publishableAPIkey = 'T10023_PUB_aumgejfq7yd27cbkbypftzg87eht5vf84q3thibxy9wpa7ru44cnfddqrx4f';
      $this->merchantId = 'TEST';
      $this->bankAccountId = '0000000A';
  }
  
  /* Generate UUID v4 function:
   * UUID is used for idempotency-key passed in header of all cUrl requests,
   * and is used to prevent duplication when resending requests due to network
   * errors.
   */
  private function GenUUID() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),
        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,
        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,
        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
  }
  
  private function processRequest($curl_method, $url, $requestXML,$keyType=self::SECRET){
    if(empty($keyType) || ($keyType != self::SECRET && $keyType != self::PUBLISHABLE) ){
      throw new Exception("No request.");
      return false;
    }
    if (strncmp($url, self::URL, strlen(self::URL)) != 0) {
      $url = rtrim(self::URL, '/') . '/' . ltrim($url, '/');
    }
    
    //Set Key to use for this request
    if($keyType == self::SECRET){ $apiKey = $this->secretAPIkey.":"; }
    if($keyType == self::PUBLISHABLE){ $apiKey = $this->publishableAPIkey.":"; }
    // Set idempotency-key to prevent duplication.
    $idempotencykey = $this->GenUUID();
    $header = array();
    $header[]= "Content-Type: application/x-www-form-urlencoded";
    $header[]= "Idempotency-Key: {$idempotencykey}";
    $header[]= "Accept: application/json";

    # Send XML Request using CURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERPWD, $apiKey);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $curl_method = strtoupper($curl_method);
    switch ($curl_method){
      case "POST":
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($requestXML));
        break;
      case "PUT":
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($requestXML));
        break;
      case "GET":
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        break;
      case "DELETE":
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($requestXML));
        break;
      case "PATCH":
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($requestXML));
        break;
      default:
        throw new Exception("Invalid cUrl method.");
        return false;
    }
  
    # Post Query and Get Response
    $xml_response = curl_exec($ch);

    //Check for network errors. If network error wait 20 seconds and try again. If failed a second time return error.
    $_status = curl_getinfo($ch,CURLINFO_HTTP_CODE);
    if($_status == "503" || $_status == "500"){
      sleep(5);
      $xml_response = curl_exec($ch);
    }
    curl_close($ch);

    return json_decode($xml_response);
  }
  
  function CreateTransaction($payment) {
    $requestXML = $this->CreateXMLTransaction($payment);
    $response = $this->processRequest("POST", "/transactions", $requestXML, self::SECRET);
    return $response;
  }
  
  
  private function CreateXMLTransaction($payment) {
    $xml_transaction = array();
    $xml_transaction["customerNumber"]=$payment['customerNumber'];
    $xml_transaction["transactionType"]=$payment['transactionType'];
    $xml_transaction["principalAmount"]=$payment['principalAmount'];
    $xml_transaction["currency"]='aud';
    
    return $xml_transaction;
  }
  
  /**
   * Function accepts a single-use-token-id, optional member number, optional contact information.
   * This information is then passed to the Bank API where a new banking system stores the information
   * previously used to create the single-use-token-id against a new customer account.
   * 
   * Function returns a XML containing the Customer details including Customer number for future reference.
   * 
   * @param unknown $singleUseTokenId
   * @param string $memberNo
   * @param unknown $contact
   * @return Ambigous <boolean, SimpleXMLElement>
   */
  function CreateCustomer($singleUseTokenId, $orderNumber=null,$contact=array()) {
    $requestXML = $this->CreateXMLCustomer($singleUseTokenId, $this->merchantId, $this->bankAccountId, $contact);
//     $response = $this->processRequest("POST", "/customers/", $requestXML, self::SECRET);
    $response = $this->processRequest("PUT", "/customers/{$orderNumber}", $requestXML, self::SECRET);
    return $response;
  }
  
  private function CheckState($state){
    if(strtolower($state) =='australian capital territory'){ return "ACT"; }
    if(strtolower($state) =='new south wales'){ return "NSW"; }
    if(strtolower($state) =='queensland'){ return "QLD"; }
    if(strtolower($state) =='south australia'){ return "SA"; }
    if(strtolower($state) =='tasmania'){ return "TAS"; }
    if(strtolower($state) =='victoria'){ return "VIC"; }
    if(strtolower($state) =='western australia'){ return "WA"; }
    if(strtolower($state) =='northern territoy'){ return "NT"; }
    return "";
  }
  private function CreateXMLCustomer($singleUseTokenId, $merchantId, $bankAccountId, $contact=array()) {
    $xml_customer = array();
    $xml_customer["singleUseTokenId"]=$singleUseTokenId;
    $xml_customer["merchantId"]=$merchantId;
    $xml_customer["bankAccountId"]=$bankAccountId;
    
    $xml_customer["customerName"]=$contact['name'];
    $xml_customer["emailAddress"]=$contact['email'];
//     $xml_customer["sendEmailReceipts"]='true';
    $xml_customer["phoneNumber"]=$contact['phone'];
    $xml_customer["street1"]=$contact['street1'];
    $xml_customer["street2"]=$contact['street2'];
    $xml_customer["cityName"]=$contact['city'];
    $xml_customer["state"]=$this->CheckState($contact['state']);
    $xml_customer["postalCode"]=$contact['postcode'];

    
    return $xml_customer;
  }

  
  /**
   * Function accepts a credit card number, the associated name for the credit card, the cvn number on the back of the card,
   * the expiry month and year for the credit card. This information is then passed to the Banking API and a single-use-token
   * is created to allow the information to be stored against an individual customer.
   * @param unknown $cardNumber
   * @param unknown $cardholderName
   * @param unknown $cvn
   * @param unknown $expiryDateMonth
   * @param unknown $expiryDateYear
   * @return Single-use-token-id or false if there was an error.
   */
  function CreateCreditCardSingleUseToken($cardNumber, $cardholderName, $cvn, $expiryDateMonth, $expiryDateYear) {
    $requestXML = $this->CreateXMLCreditCardSingleUseToken($cardNumber, $cardholderName, $cvn, $expiryDateMonth, $expiryDateYear);
    $response = $this->processRequest("POST", "/single-use-tokens/", $requestXML, self::PUBLISHABLE);
    return $response;
  }
  
  private function CreateXMLCreditCardSingleUseToken($cardNumber, $cardholderName, $cvn, $expiryDateMonth, $expiryDateYear) {
    $xml_creditcard = array();
    $xml_creditcard["paymentMethod"]='creditCard';
    $xml_creditcard["cardNumber"]=$cardNumber;
    $xml_creditcard["cardholderName"]=$cardholderName;
    $xml_creditcard["cvn"]=$cvn;
    $xml_creditcard["expiryDateMonth"]=$expiryDateMonth;
    $xml_creditcard["expiryDateYear"]=$expiryDateYear;
    
    return $xml_creditcard;
  }
  
  /**
   * Function accepts a bsb (bank-state-branch holding the account), an account number, and the associated account name.
   * This information is then passed to the Banking API and a single-use-token is created to allow the information to be
   * stored against an individual customer.
   * @param unknown $bsb
   * @param unknown $accountNumber
   * @param unknown $accountName
   * @return Single-use-token-id or false if there was an error.
   */
  function CreateDirectDebitSingleUseToken($bsb, $accountNumber, $accountName) {
    $requestXML = $this->CreateXMLDirectDebitSingleUseToken($bsb, $accountNumber, $accountName);
    $response = $this->processRequest("POST", "/single-use-tokens/", $requestXML, self::PUBLISHABLE);
    return $response;
  }
  
  private function CreateXMLDirectDebitSingleUseToken($bsb, $accountNumber, $accountName) {
    $xml_directdebit = array();
    $xml_directdebit["paymentMethod"]='bankAccount';
    $xml_directdebit["bsb"]=$bsb;
    $xml_directdebit["accountNumber"]=$accountNumber;
    $xml_directdebit["accountName"]=$accountName;

    return $xml_directdebit;
  }
  
  /**
   * Function accepts a single-use-token-id, optional member number, optional contact information.
   * This information is then passed to the Bank API where a new banking system stores the information
   * previously used to create the single-use-token-id against a new customer account.
   * @param unknown $bsb
   * @param unknown $accountNumber
   * @param unknown $accountName
   * @return Single-use-token-id or false if there was an error.
   */
  function UpdatePaymentMethod($singleUseTokenId, $customerNumber) {
    $requestXML = $this->CreateXMLUpdatePaymentMethod($singleUseTokenId, $this->merchantId, $this->bankAccountId);
    $response = $this->processRequest("PUT", "/customers/{$customerNumber}/payment-setup/", $requestXML, self::SECRET);
    return $response;
  }
  
  private function CreateXMLUpdatePaymentMethod($singleUseTokenId, $merchantId, $bankAccountId) {
    $xml_customer = array();
    $xml_customer["singleUseTokenId"]=$singleUseTokenId;
    $xml_customer["merchantId"]=$merchantId;
    $xml_customer["bankAccountId"]=$bankAccountId;

    return $xml_customer;
  }
  
  function GetBankAccounts() {
    $response = $this->processRequest("GET", "/your-bank-accounts/", "", self::SECRET);
    return $response;
  }
  function GetMerchants() {
    $response = $this->processRequest("GET", "/merchants/", "", self::SECRET);
    return $response;
  }
    
  function DeleteCustomer($customerNumber) {
    if(!empty($customerNumber)){
      //Need to stop recurring payments prior to sending delete command.
     $response = $this->processRequest("PATCH", "/customers/{$customerNumber}/payment-setup", array("stopped"=>"true"), self::SECRET);
      
      //$response = $this->processRequest("DELETE", "/customers/{$customerNumber}/schedule", "", self::SECRET);
      //$response = $this->processRequest("DELETE", "/customers/{$customerNumber}", "", self::SECRET);
      return $response;
    }
    return false;
    
  }
}
?>