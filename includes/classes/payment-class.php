<?php
class Payment{
	
	var $requestType 			= 'payment';	// Request Type (payment or echo)
	
	var $transactionType		= '0';			// 0 = Normal Payment (Other options available)

	var $messageID;								// Random ID to track Transaction within Code - Temporary
	var $messageTimestamp;						// Timestamp of Transaction. Set Automatically During Submission.
												
	var $currency 				= 'AUD';		// Currency. Defaults to Australian Dollars ('AUD')
	
	var $xml_request  			= '';			// The XML request, used for submission
	var $xml_response  			= '';			// The XML response
	var $post_request  			= '';			// The POST request, used for submission
	var $post_response 			= '';			// The POST response
	
	var $payment_id				= '';			// The payment ID
	var $payment_cartid			= '';			// The payment cart ID
	
	var $amount          		= '';			// Transaction amount
	var $cc_number       		= '';			// Credit card number
	var $cc_expiry_year  		= '';			// Credit card expiry year
	var $cc_expiry_month 		= '';			// Credit card expiry month
	var $cc_cvc          		= '';			// Credit card CVC/CVN
	var $cc_name         		= '';			// Name on credit card
	
	var $response 				= array();		// Response array mapped from the XML response into common variables to be stored in the database
	
	var $errors 				= false;
	var $errorMsg				= '';
	
	var $payment_success 		= false;		// Payment success status
	

	function __construct(){
		
		global  $CONFIG;
		$this->live_url 			= $CONFIG["payment_gateway"]["live_url"];
		$this->test_url				= $CONFIG["payment_gateway"]["test_url"];
		$this->url_to_use 			= ($CONFIG["payment_gateway"]["live"]) ? $this->live_url : $this->test_url;
		$this->merchant_id			= $CONFIG["payment_gateway"]["merchant_id"];
		$this->merchant_password	= $CONFIG["payment_gateway"]["merchant_password"];
		$this->payment_cartid 		= $_SESSION['cartobject']['cart_id'];
		$this->messageTimestamp 	= date("YdmHis").substr(microtime(), 2, 3).sprintf("%+d", (date('Z') / 60));
		$this->messageID 			= sha1($this->messageTimestamp . mt_rand());
		
		$this->dbobject = new DBmanager();
		
	}
	
	/*
	 * Send the payment request to the appropriate banks payment class
	 */
	function ProcessPayment(){
		
		// Check to see if we have all the necessary details
		if(empty($this->amount) || empty($this->cc_number) || empty($this->cc_expiry_month) || empty($this->cc_expiry_year) || empty($this->cc_cvc) || empty($this->cc_name)){
			$this->errors = true;
			$this->errorMsg = 'PAYMENT ERROR: Missing payment details.';
		}else{
			$this->StorePaymentAttempt();
			$this->SubmitPayment();
			$this->CheckPaymentStatus();
			$this->StorePaymentResult();
		}
		
	}

	
	/*
	 * Store the payment attempt
	 */
	function StorePaymentAttempt(){
		
		if(empty($this->payment_cartid)){
			$errors = true;
			$this->errorMsg = 'PAYMENT ERROR: Missing card ID.';
		}else{
			$this->payment_cartid 	= $_SESSION['cartobject']['cart_id'];
				
			$sql = "INSERT into tbl_payment
			        (payment_user_id,
			         payment_cart_id,
			         payment_user_ip,
			         payment_status,
			         payment_amount
			        )
			        VALUES
			        ('".$_SESSION['user']['user_id']."',
			         '$this->payment_cartid',
			         '".$_SERVER['REMOTE_ADDR']."',
			         'P',
			         '".$this->amount."'
			        )";
			$sql_res = $this->dbobject->executeSQL($sql);
			
			if(!$this->dbobject->queryresult){
				$this->errors = true;
				$this->errorMsg = 'PAYMENT ERROR: Error with db insert query.';
			}
		
			$this->payment_id = $this->dbobject->wrappedSqlIdentity();

			if(empty($this->payment_id)){
				$this->errors = true;
				$this->errorMsg = 'PAYMENT ERROR: No payment ID was returned.';
			}
		}
	
	}
	
	/*
	 * Store the payment result
	 */
	function StorePaymentResult(){
		
		$sql= "UPDATE tbl_payment
		       SET payment_status = '".$this->response['payment_status']."',
		           payment_transaction_no = '".$this->response['transaction_no']."',
		           payment_response_code = '".$this->response['code']."',
		           payment_response_msg = '".$this->response['msg']."',
		           payment_response_reciept_no = '".$this->response['reciept_no']."',
		           payment_response_settlementdate = '".$this->response['settlementdate']."',
		           payment_response_transactiondate = '".$this->response['transactiondate']."',
		           payment_response_auth_id = '".$this->response['auth_id']."',
		           payment_response_carscheme = '".$this->response['carscheme']."',
		           payment_response_full = '".$this->response['payment_response']."',
		           payment_updated = now()
		       WHERE payment_id = '".$this->payment_id."'";
		$sql_res = $this->dbobject->executeSQL($sql);
		
		if(!$this->dbobject->queryresult){
			$this->errors = true;
			$this->errorMsg = 'PAYMENT ERROR: Error with db update query.';
		}
		
	}

	/*
	 * Format the amount to cents (dollars to cents)
	 * Returns amount as cents EG: 1000 for $10
	 */
	function FormatToCents($amount){
		
		return number_format((float)$amount * 100, 0, '.', '');
		
	}
	
	/*
	 * Email the payment response details to THEM 
	 */
	function sendMailToTHEM(){
		
		$body = '';
		foreach($this->response as $key=>$val){
			$body.= $key.' = '.$val.'\n';
		}
		mail('jeff@them.com.au', 'Payment Info', $body);
		
	}

}