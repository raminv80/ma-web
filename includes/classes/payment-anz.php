<?php
class PaymentANZ{
	private $vpcURL = "https://migs.mastercard.com.au/vpcdps";
	private $vpc_Version = "1";
	private $vpc_Command = "pay";
	private $vpc_Merchant = "TESTANZEMACONSUL";
	private $vpc_AccessCode = "4700392";
	private $vpc_TxSource = "INTERNET";
	private $vpc_TxSourceSubType = "SINGLE";
	
	private $messageTimestamp = "";
	private $messageID = "";
	
	function __construct(){
		global  $CONFIG;
		$this->$messageTimestamp 	= date("YdmHis").substr(microtime(), 2, 3).sprintf("%+d", (date('Z') / 60));
		$this->messageID 			= sha1($this->messageTimestamp . mt_rand());
	}

	/*
	 * Submit payment request and return result
	 */
	function SubmitPayment($_data){
	
		// create a variable to hold the POST data information and capture it
		$params['vpc_Version'] = $this->vpc_Version;
		$params['vpc_Command'] = $this->vpc_Command;
		$params['vpc_AccessCode'] = $this->vpc_AccessCode;
		$params['vpc_MerchTxnRef'] = $this->messageID;
		$params['vpc_Merchant'] = $this->vpc_Merchant;
		$params['vpc_OrderInfo'] = "Invoice # {$_POST['invoiceno']}";
		$params['vpc_Amount'] = $_POST['amount'];
		$params['vpc_CardNum'] = $_POST['ccname'];
		$params['vpc_CardExp'] = $_POST['ccyear'].$_POST['ccmonth'];
		$params['vpc_CardSecurityCode'] = $_POST['ccnumber'];
		$params['vpc_TicketNo'] = "";
		$params['vpc_TxSource'] = $this->vpc_TxSource;
		$params['vpc_TxSourceSubType'] = $this->vpc_TxSourceSubType;
		
		$postdata = $this->CreateXMLRequest($params);
		// Get a HTTPS connection to VPC Gateway and do transaction
		// turn on output buffering to stop response going to browser
		ob_start();
		
		// initialise Client URL object
		$ch = curl_init();
		
		// set the URL of the VPC
		curl_setopt ($ch, CURLOPT_URL, $this->vpcURL);
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $postdata);
		
		// connect
		curl_exec ($ch);
		
		// get response
		$response = ob_get_contents();
		
		// turn output buffering off.
		ob_end_clean();
		
		// Extract the available receipt fields from the VPC Response
		// If not present then let the value be equal to 'No Value Returned'
		$map = array();
		
		// process response if no errors
		if (strlen($message) == 0) {
			$pairArray = split("&", $response);
			foreach ($pairArray as $pair) {
				$param = split("=", $pair);
				$map[urldecode($param[0])] = urldecode($param[1]);
			}
			$message         = null2unknown($map, "vpc_Message");
		}
		
		// Standard Receipt Data
		# merchTxnRef not always returned in response if no receipt so get input
		//TK//$merchTxnRef     = $vpc_MerchTxnRef;
		$merchTxnRef     = $_POST["vpc_MerchTxnRef"];
		
		$amount             = null2unknown($map, "vpc_Amount");
		$locale          = null2unknown($map, "vpc_Locale");
		$batchNo         = null2unknown($map, "vpc_BatchNo");
		$command         = null2unknown($map, "vpc_Command");
		$version         = null2unknown($map, "vpc_Version");
		$cardType        = null2unknown($map, "vpc_Card");
		$orderInfo       = null2unknown($map, "vpc_OrderInfo");
		$receiptNo       = null2unknown($map, "vpc_ReceiptNo");
		$merchantID      = null2unknown($map, "vpc_Merchant");
		$authorizeID     = null2unknown($map, "vpc_AuthorizeId");
		$transactionNo   = null2unknown($map, "vpc_TransactionNo");
		$acqResponseCode = null2unknown($map, "vpc_AcqResponseCode");
		$txnResponseCode = null2unknown($map, "vpc_TxnResponseCode");
				
	}
	
	/*
	 * Create the XML request
	 */
	function CreatPostDataRequest($params){
		$ampersand = "";
		foreach($params as $key => $value) {
			// create the POST data input leaving out any fields that have no value
			if (strlen($value) > 0) {
				$postData .= $ampersand . urlencode($key) . '=' . urlencode($value);
				$ampersand = "&";
			}
		}
		return $postData;	
	}
	
	/*
	 * Process the XML response
	 */
	function ProcessXMLResponse(){
		
		// Encode xml as json and decode from json to php array
		$xml = simplexml_load_string($this->xml_response);
		$json = json_encode($xml);
		$xml_response_array = json_decode($json, true);

		// Map response variables to common variables to be stored in the database
		$this->response['status_code']          = $xml_response_array['Status']['statusCode'];
		$this->response['transaction_no']       = $xml_response_array['Payment']['TxnList']['Txn']['txnID'];
		$this->response['code']                 = $xml_response_array['Payment']['TxnList']['Txn']['responseCode'];
		$this->response['msg']                  = $xml_response_array['Payment']['TxnList']['Txn']['responseText'];
		$this->response['reciept_no']           = $xml_response_array['Payment']['TxnList']['Txn']['txnID'];
		$this->response['settlementdate']       = $xml_response_array['Payment']['TxnList']['Txn']['settlementDate'];
		$this->response['transactiondate']      = '';
		$this->response['carscheme']            = '';
		$this->response['auth_id']              = '';
		$this->response['payment_response']     = $json;
	}
	
	/*
	 * Check payment status and set status variable and error messages accordingly
	*/
	function CheckPaymentStatus(){
	
		if($this->response['code'] == "0"){
			// Payment successful
			$this->response['payment_status'] = 'S';
			$this->payment_success = true;
		}else{
			// Payment failed
			$this->response['payment_status'] = 'F';
			$this->payment_success = false;
			$this->errorMsg = 'PAYMENT ERROR: Response code('.$this->response['code'].') Response message ('.$this->response['msg'].')';
		}
	
	}
	
}