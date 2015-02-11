<?php
// ==================================
// PayPal Express Checkout Module
// ==================================
class PayPal extends Bank {
	
	
	
	function __construct($data) {
		global  $CONFIG, $HTTP_HOST;
		
		parent::__construct($data);
		
		$this->SandboxFlag 		= ((string) $CONFIG->paypal->live == 'true') ? false : true;

		$this->API_UserName = (string) $CONFIG->paypal->api_username;
		$this->API_Password = (string) $CONFIG->paypal->api_password;
		$this->API_Signature = (string) $CONFIG->paypal->api_signature;

		$PORT = ($_SERVER['SERVER_PORT'] == 443 || !empty($_SERVER['HTTPS']))?"https://":"http://";
		$this->returnURL = $PORT . $HTTP_HOST .'/'. (string) $CONFIG->paypal->return_url;
		$this->cancelURL = $PORT . $HTTP_HOST .'/'. (string) $CONFIG->paypal->cancel_url;
		
		if ($this->SandboxFlag == true)
		{
			$this->API_Endpoint = "https://api-3t.sandbox.paypal.com/nvp";
			$this->PAYPAL_URL = "https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token=";
		}
		else
		{
			$this->API_Endpoint = "https://api-3t.paypal.com/nvp";
			$this->PAYPAL_URL = "https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&useraction=commit&token=";
		}
		
		// BN Code 	is only applicable for partners
		$this->sBNCode = "PP-ECWizard";
		
		$this->currencyCodeType = "AUD";
		$this->paymentType = "Sale";
		$this->version="93";
		
	}	
	
	

	/**
	 * This function initiates the processing of a payment. SetExpressCheckout() needs to be called first.
	 * Returns true if the payment was processed successfully, otherwise returns false.
	 *
	 * @return boolean
	 */
	function Submit( $token = '')
	{
		$this->response['payment_status'] = 'F';
		$this->response['cardscheme']  = 'PAYPAL';
	
		if(!empty($token))
		{
			$nvpstr="&TOKEN=" . $token;
	
			$resArray = $this->hash_call("GetExpressCheckoutDetails",$nvpstr);
			$ack = strtoupper($resArray["ACK"]);
			if (($ack == "SUCCESS" || $ack=="SUCCESSWITHWARNING") && !empty($resArray['PAYERID']) )
			{
				$this->response['payment_response'] 		= json_encode($resArray);
				$finalResArray = $this->ConfirmPayment($token, $resArray['PAYERID'], $resArray['PAYMENTREQUEST_0_AMT']);
				$ack = strtoupper($finalResArray["ACK"]);
				if ($ack == "SUCCESS")
				{
					$this->payment_success								 	= true;
					$this->amount 													= $finalResArray['PAYMENTINFO_0_AMT'];
					$this->response['payment_status'] 			= 'A';
					$this->response['summary_code']         = $finalResArray["PAYMENTINFO_0_PAYMENTSTATUS"];
					$this->response['code']                 = $finalResArray["PAYMENTINFO_0_ERRORCODE"];
					$this->response['msg']                  = $finalResArray["PAYMENTINFO_0_ACK"];
					$this->response['receipt_no']           = $finalResArray["PAYMENTINFO_0_TRANSACTIONID"];
					$this->response['payment_response'] 		= json_encode($finalResArray);
				}
			}
		}
		$this->StorePaymentRecord();
		return $this->payment_success;
	}
	
	
	/**
	 * Initialize the Paypal Express Checkout. On success, it will redirect to paypal website.
	 * It requires associative array with cart items and totals.
	 * 
	 * @param array $cartItemsArr
	 * @param array $totalArr
	 * @return boolean
	 */
	function SetExpressCheckout($cartItemsArr, $totalArr){
		
		$this->amount = $totalArr["chargedAmount"];
		
		$orderInfo = '';
		foreach ($cartItemsArr as $k => $v){
			$orderInfo .= $this->CreateCartItem($k, $v['itemName'], $v['itemQty'], $v['itemAmount'],  $v['itemNumber'], $v['itemDescription']);
		}
		$orderInfo .= $this->CreateCartTotal($totalArr["subtotal"], $totalArr["shipping"], $totalArr["total"]);

		$resArray = $this->CallShortcutExpressCheckout ($this->amount, $this->returnURL, $this->cancelURL, $orderInfo);
		$ack = strtoupper($resArray["ACK"]);
		if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING")
		{
			$this->RedirectToPayPal ( $resArray["TOKEN"] );
			return true;
		} 
		else  
		{
			$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
			$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
			$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
			$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
			
			$this->errorMsg = "SetExpressCheckout API call failed. ";
			$this->errorMsg .= " Detailed Error Message: " . $ErrorLongMsg;
			$this->errorMsg .= " Short Error Message: " . $ErrorShortMsg;
			$this->errorMsg .= " Error Code: " . $ErrorCode;
			$this->errorMsg .= " Error Severity Code: " . $ErrorSeverityCode;
			return $this->errors;
		}
	}

		
	
	



	function CreateCartItem($index, $itemName, $itemQty, $itemAmount,  $itemNumber = '', $itemDescription = '')
	{	
		$STR = "&L_PAYMENTREQUEST_0_NAME$index=$itemName";
		$STR .= "&L_PAYMENTREQUEST_0_AMT$index=$itemAmount";
		$STR .= "&L_PAYMENTREQUEST_0_QTY$index=$itemQty";
		$STR .= empty($itemNumber)?"":"&L_PAYMENTREQUEST_0_NUMBER$index=$itemNumber";
		$STR .= empty($itemDescription)?"":"&L_PAYMENTREQUEST_0_DESC$index=$itemDescription";
		return $STR;
	}

	
	function CreateCartTotal($subtotal, $shipping, $total)
	{
		$STR = "&PAYMENTREQUEST_0_ITEMAMT=$subtotal";
		$STR .= "&PAYMENTREQUEST_0_SHIPPINGAMT=$shipping";
		$STR .= "&PAYMENTREQUEST_0_AMT=$total";
		$STR .= "&PAYMENTREQUEST_0_CURRENCYCODE=". $this->currencyCodeType;
		$STR .= "&PAYMENTREQUEST_0_INVNUM=". $this->payment_transactionno;
		$STR .= "&ALLOWNOTE=1";
		return $STR;
	}


	/*
	 '-------------------------------------------------------------------------------------------------------------------------------------------
	' Purpose: 	Prepares the parameters for the SetExpressCheckout API Call.
	' Inputs:
	'		paymentAmount:  	Total value of the shopping cart
	'		currencyCodeType: 	Currency code value the PayPal API
	'		paymentType: 		paymentType has to be one of the following values: Sale or Order or Authorization
	'		returnURL:			the page where buyers return to after they are done with the payment review on PayPal
	'		cancelURL:			the page where buyers return to when they cancel the payment review on PayPal
	'		productItems: 	(optional) order details with product list
	'--------------------------------------------------------------------------------------------------------------------------------------------
	*/
	function CallShortcutExpressCheckout( $paymentAmount, $returnURL, $cancelURL, $orderDetails = '' )
	{

		$nvpstr= $orderDetails . "&PAYMENTREQUEST_0_AMT=". $paymentAmount;
		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_PAYMENTACTION=" . $this->paymentType;
		$nvpstr = $nvpstr . "&NOSHIPPING=1" ; //Suppressing the Buyer's Shipping Address
		$nvpstr = $nvpstr . "&RETURNURL=" . $returnURL;
		$nvpstr = $nvpstr . "&CANCELURL=" . $cancelURL;
		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_CURRENCYCODE=" . $this->currencyCodeType;

		$resArray = $this->hash_call("SetExpressCheckout", $nvpstr);
		$ack = strtoupper($resArray["ACK"]);
		if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING")
		{
			$token = urldecode($resArray["TOKEN"]);
			$_SESSION['TOKEN']=$token;
		}
		return $resArray;
	}


	

	
	/*
	 '-------------------------------------------------------------------------------------------------------------------------------------------
	' Purpose: 	Prepares the parameters for the GetExpressCheckoutDetails API Call.
	'
	' Inputs:
	'		sBNCode:	The BN code used by PayPal to track the transactions from a given shopping cart.
	' Returns:
	'		The NVP Collection object of the GetExpressCheckoutDetails Call Response.
	'--------------------------------------------------------------------------------------------------------------------------------------------
	*/
	function ConfirmPayment( $token, $payerID, $FinalPaymentAmt )
	{
		/* Gather the information to make the final call to
		 finalize the PayPal payment.  The variable nvpstr
		holds the name value pairs
		*/


		$serverName 		= urlencode($_SERVER['SERVER_NAME']);

		$nvpstr  = '&TOKEN=' . $token . '&PAYERID=' . $payerID . '&PAYMENTREQUEST_0_PAYMENTACTION=' . $this->paymentType . '&PAYMENTREQUEST_0_AMT=' . $FinalPaymentAmt;
		$nvpstr .= '&PAYMENTREQUEST_0_CURRENCYCODE=' . $this->currencyCodeType . '&IPADDRESS=' . $serverName;

		/* Make the call to PayPal to finalize payment
		 If an error occurred, show the resulting errors
		*/
		$resArray = $this->hash_call("DoExpressCheckoutPayment",$nvpstr);

		/* Display the API response back to the browser.
		 If the response from PayPal was a success, display the response parameters'
		If the response was an error, display the errors received using APIError.php.
		*/
		$ack = strtoupper($resArray["ACK"]);

		return $resArray;
	}


	/**
	 '-------------------------------------------------------------------------------------------------------------------------------------------
	 * hash_call: Function to perform the API call to PayPal using API signature
	 * @methodName is name of API  method.
	 * @nvpStr is nvp string.
	 * returns an associative array containing the response from the server.
	 '-------------------------------------------------------------------------------------------------------------------------------------------
	 */
	protected function hash_call($methodName,$nvpStr)
	{
		//setting the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);


		//NVPRequest for submitting to server
		$nvpreq="METHOD=" . urlencode($methodName) . "&VERSION=" . urlencode($this->version) . "&PWD=" . urlencode($this->API_Password) . "&USER=" . urlencode($this->API_UserName) . "&SIGNATURE=" . urlencode($this->API_Signature) . $nvpStr . "&BUTTONSOURCE=" . urlencode($this->sBNCode);

		//setting the nvpreq as POST FIELD to curl
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

		//getting response from server
		$response = curl_exec($ch);

		//convrting NVPResponse to an Associative Array
		$nvpResArray = $this->deformatNVP($response);
		$nvpReqArray = $this->deformatNVP($nvpreq);
		$_SESSION['nvpReqArray']=$nvpReqArray;

		if (curl_errno($ch))
		{
			// moving to display page to display curl errors
			$_SESSION['curl_error_no']=curl_errno($ch) ;
			$_SESSION['curl_error_msg']=curl_error($ch);

			//Execute the Error handling module to display errors.
		}
		else
		{
			//closing the curl
			curl_close($ch);
		}

		return $nvpResArray;
	}

	/*'----------------------------------------------------------------------------------
	 Purpose: Redirects to PayPal.com site.
	Inputs:  NVP string.
	Returns:
	----------------------------------------------------------------------------------
	*/
	protected function RedirectToPayPal ( $token )
	{
		$payPalURL = $this->PAYPAL_URL . $token;
		header("Location: ".$payPalURL);
		exit;
	}


	/*'----------------------------------------------------------------------------------
	 * This function will take NVPString and convert it to an Associative Array and it will decode the response.
	* It is usefull to search for a particular key and displaying arrays.
	* @nvpstr is NVPString.
	* @nvpArray is Associative Array.
	----------------------------------------------------------------------------------
	*/
	protected function deformatNVP($nvpstr)
	{
		$intial=0;
		$nvpArray = array();

		while(strlen($nvpstr))
		{
			//postion of Key
			$keypos= strpos($nvpstr,'=');
			//position of value
			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);

			/*getting the Key and Value values and storing in a Associative Array*/
			$keyval=substr($nvpstr,$intial,$keypos);
			$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
			//decoding the respose
			$nvpArray[urldecode($keyval)] =urldecode( $valval);
			$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
		}
		return $nvpArray;
	}
}
?>