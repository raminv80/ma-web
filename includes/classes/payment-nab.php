<?php
class PaymentNAB extends Payment{
	
	function __construct(){
		parent::__construct();
	}

	/*
	 * Submit payment request and return result
	 */
	function SubmitPayment(){
	
		$this->CreateXMLRequest();
			
		// Send XML request using CURL
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => $this->url_to_use,
			CURLOPT_HEADER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => trim($this->xml_request),
			CURLOPT_RETURNTRANSFER => true,
		));
	
		// Execute query and get response
		//$this->xml_response = curl_exec($ch);	// use this for test/live gateway testing
		$this->SetXMLResponse();				// use this for testing without merchant details or gateway access (fake XML response data)
		curl_close($ch);
		$this->ProcessXMLResponse();
				
	}
	
	/*
	 * Create the XML request
	 */
	function CreateXMLRequest(){
		
		$this->xml_request = '
		<?xml version="1.0" encoding="UTF-8"?>
		<NABTransactMessage>
			<MessageInfo>
				<messageID>'.urlencode($this->messageID).'</messageID>
				<messageTimestamp>'.urlencode($this->messageTimestamp).'</messageTimestamp>
				<timeoutValue>60</timeoutValue>
				<apiVersion>xml-4.2</apiVersion>
			</MessageInfo>
			<MerchantInfo>
				<merchantID>'.urlencode($this->merchant_id).'</merchantID>
				<password>'.urlencode($this->merchant_password).'</password>
			</MerchantInfo>
			<RequestType>'.urlencode($this->requestType).'</RequestType>
			<Payment>
				<TxnList count="1">
					<Txn ID="1">
						<txnType>'.urlencode($this->transactionType).'</txnType>
						<txnSource>23</txnSource>
						<amount>'.urlencode($this->FormatToCents($this->amount)).'</amount>
						<purchaseOrderNo>'.urlencode($this->payment_id).'</purchaseOrderNo>
						<currency>'.urlencode($this->currency).'</currency>
						<CreditCardInfo>
							<cardNumber>'.urlencode($this->cc_number).'</cardNumber>
							<cvv>'.urlencode($this->cc_cvc).'</cvv>
							<expiryDate>'.urlencode($this->cc_expiry_month.'/'.$this->cc_expiry_year).'</expiryDate>
						</CreditCardInfo>
					</Txn>
				</TxnList>
			</Payment>
		</NABTransactMessage>';
		
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
	
		if($this->response['code'] == "00" || $this->response['code'] == "08"){
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
	
	/*
	 * Set XML response to a fake for testing without merchant details or gateway access
	 */
	function SetXMLResponse(){
		$this->xml_response = '<?xml version="1.0" encoding="UTF-8"?>
		<NABTransactMessage>
			<MessageInfo>
				<messageID>8af793f9af34bea0cf40f5fb750f64</messageID>
				<messageTimestamp>20042303111226938000+660</messageTimestamp>
				<apiVersion>xml-4.2</apiVersion>
			</MessageInfo>
			<MerchantInfo>
				<merchantID>ABC0001</merchantID>
			</MerchantInfo>
			<RequestType>Payment</RequestType>
			<Status>
				<statusCode>000</statusCode>
				<statusDescription>Normal</statusDescription>
			</Status>
			<Payment>
				<TxnList count="1">
					<Txn ID="1">
						<txnType>0</txnType>
						<txnSource>23</txnSource>
						<amount>200</amount>
						<currency>AUD</currency>
						<purchaseOrderNo>test</purchaseOrderNo>
						<approved>Yes</approved>
						<responseCode>00</responseCode>
						<responseText>Approved</responseText>
						<settlementDate>20040323</settlementDate>
						<txnID>009887</txnID>
						<CreditCardInfo>
							<pan>444433...111</pan>
							<expiryDate>08/12</expiryDate>
							<cardType>6</cardType>
							<cardDescription>Visa</cardDescription>
						</CreditCardInfo>
					</Txn>
				</TxnList>
			</Payment>
		</NABTransactMessage>';
		$this->xml_response = str_replace(chr(10), '', $this->xml_response);
	}
	
}