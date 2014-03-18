<?php
// Defaine the base exception class (as a catch all for the API)
class exceptionBank extends Exception{}

// And the individual errors that can occur
class exceptionBankNotFound extends exceptionBank{}
class exceptionPaymentNotPreparedFound extends exceptionBank{}

class Bank {
	//BANK SPECIFIC VARIABLES ARE DEFINED GLOBALLY AS PRIVATE
	
	function __construct() {
		
	}	
	
	function PreparePayment($data){}
	
	
	function GetResponseMessage(){}
	
	
	function GetResponseCode(){}
	
	/**
	 * This function initiates the processing of a payment. It requires that PreparePayment() has already been called. 
	 * Returns true if the payment was processed successfully, otherwise returns falls. Additional information at the 
	 * result can be retrieved with GetResponseCode() and GetResponseMessage().
	 * 
	 * @throws exceptionBankNotFound
	 * @throws exceptionPaymentNotPreparedFound
	 * @return boolean
	 */
	function Submit(){
		
		return true;
	}
	
	/**
	 * This function takes an array representing the payment record, either successful or failed. It should have the following associated records
	 * 
	 * payment_cart_id => the cart ID associated with this payment,
	 * payment_user_id => the user ID associated with this payment,
	 * payment_billing_address_id => the record ID for the address record which is used for the billing address,
	 * payment_shipping_address_id => the record ID for the address record which is used for the shipping address,
	 * payment_status => status of the payment. This can be either A,F,P which are (A)pprove, (F)ail, (P)ending.
	 * payment_subtotal => product cost component,
	 * payment_discount => discount amount component,
	 * payment_shipping_fee => the shipping fee component,
	 * payment_charged_amount => total payment amount being charged to the banking system,
	 * payment_gst => GST amount component,
	 * payment_shipping_method => shipping method selected by user,
	 * payment_payee_name => payee name which was entered by user,
	 * payment_transaction_no => transaction number returned by the banking system,
	 * payment_response_summary_code => summary code returned by the banking system,
	 * payment_response_code => response code returned by the banking system,
	 * payment_response_msg => the message returned by the banking system
	 * payment_response_receipt_no => the reciept number as returned by the banking system.
	 * payment_response_settlementdate => the settlement date returned by the banking system.
	 * payment_response_transactiondate => the transaction date returned by the banking system.
	 * payment_response_cardscheme => cardscheme returned from the banking system. E.g. Mastercard, VISA, etc
	 * payment_response => the serialised response from the banking system.
	 * 
	 * @param unknown $payment
	 * @return string
	 */
	function StorePaymentRecord($payment){
		global $DBobject;
		
		$sql="INSERT INTO tbl_payment (payment_cart_id,payment_user_id,payment_billing_address_id,payment_shipping_address_id,payment_status,payment_subtotal,payment_discount,payment_shipping_fee,payment_charged_amount,payment_gst,payment_shipping_method,payment_payee_name,payment_transaction_no,payment_response_summary_code,payment_response_code,payment_response_msg,payment_response_receipt_no,payment_response_settlementdate,payment_response_transactiondate,payment_response_cardscheme,payment_response,payment_user_ip,payment_created)
			VALUES(:payment_cart_id,:payment_user_id,:payment_billing_address_id,:payment_shipping_address_id,:payment_status,:payment_subtotal,:payment_discount,:payment_shipping_fee,:payment_charged_amount,:payment_gst,:payment_shipping_method,:payment_payee_name,:payment_transaction_no,:payment_response_summary_code,:payment_response_code,:payment_response_msg,:payment_response_receipt_no,:payment_response_settlementdate,:payment_response_transactiondate,:payment_response_cardscheme,:payment_response,:payment_user_ip,now())";
		$params = array(
				"payment_cart_id" => $payment['payment_cart_id'],
				"payment_user_id" => $payment['payment_user_id'],
				"payment_billing_address_id" => $payment['payment_billing_address_id'],
				"payment_shipping_address_id" => $payment['payment_shipping_address_id'],
				"payment_status" => $payment['payment_status'],
				"payment_subtotal" => $payment['payment_subtotal'],
				"payment_discount" => $payment['payment_discount'],
				"payment_shipping_fee" => $payment['payment_shipping_fee'],
				"payment_charged_amount" => $payment['payment_charged_amount'],
				"payment_gst" => $payment['payment_gst'],
				"payment_shipping_method" => $payment['payment_shipping_method'],
				"payment_payee_name" => $payment['payment_payee_name'],
				"payment_transaction_no" => $payment['payment_transaction_no'],
				"payment_response_summary_code" => $payment['payment_response_summary_code'],
				"payment_response_code" => $payment['payment_response_code'],
				"payment_response_msg" => $payment['payment_response_msg'],
				"payment_response_receipt_no" => $payment['payment_response_receipt_no'],
				"payment_response_settlementdate" => $payment['payment_response_settlementdate'],
				"payment_response_transactiondate" => $payment['payment_response_transactiondate'],
				"payment_response_cardscheme" => $payment['payment_response_cardscheme'],
				"payment_response" => $payment['payment_response'],
				"payment_user_ip" => $_SERVER['REMOTE_ADDR']
		);
		
		$sql_res = $DBobject->wrappedSql($sql, $params);
		return $DBobject->wrappedSqlIdentity();
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
	function SetOrderStatus($paymentId, $statusId = 1, $adminId = null) {
		global $DBobject;
	
		$sql = " INSERT INTO tbl_order (
								order_payment_id,
								order_status_id,
								order_admin_id,
        				order_created
								)
							VALUES (
								:pid,
								:sid,
								:aid,
        				now()
							)";
		$params = array (
				":pid" => $paymentId,
				":sid" => $statusId,
				":aid" => $adminId
		);
		return $DBobject->wrappedSql ( $sql, $params );
	}
	
	
	/**
	 * Return payment record given the payment_id 
	 * 
	 * @param int $paymentId
	 * @return array
	 */
	function GetPaymentRecord($paymentId) {
		global $DBobject;
	
		$sql = "SELECT * FROM tbl_payment WHERE payment_id = :id AND payment_deleted IS NULL";
		$res = $DBobject->wrappedSql ( $sql, array (":id" => $paymentId) );
		return $res[0];
		
	}
	
}