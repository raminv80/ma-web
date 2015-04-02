<?php
ini_set('display_errors',1); ini_set('error_reporting',E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);
set_include_path($_SERVER['DOCUMENT_ROOT']);

if( true ){
	require_once 'includes/classes/paypal-expresscheckout-class-test.php';
	$config = array(
			'live' => 'f',
			'api_username' => 'online-facilitator_api1.them.com.au',
			'api_password' => 'NRK4843ACR9YCF36',
			'api_signature' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31AiMGCwhfSigIYuvCGVQhOeQwJa4N',
			'return_url' => $_SERVER['HTTP_HOST']. '/includes/processes/processes-paypal-test.php',
			'cancel_url' => $_SERVER['HTTP_HOST']. '/includes/processes/processes-paypal-test.php#error',
	);
	$cartItems = array();
	$cartItems[] = array(
			'itemName' =>	'TEST PRODUCT 1',
			'itemNumber'=> 11,
			'itemDescription' => '',
			'itemAmount' => 20,
			'itemQty'=> 2
	);
	$cartItems[] = array(
			'itemName' =>	'TEST PRODUCT 2',
			'itemNumber'=> 22,
			'itemDescription' => '',
			'itemAmount' => 12,
			'itemQty'=> 1
	);
	$discount = 2;
	$shippingFee = 5;
	$order_cartId = date("Ymd");
	
	if(empty($_SESSION['orderNumber'])){
		$orderNumber = $order_cartId.'-'.date("is");
		$_SESSION['orderNumber'] = $orderNumber;
	}else{
		$orderNumber = $_SESSION['orderNumber'];
	}
		
	$chargedAmount = (20*2)+(12) - $discount + $shippingFee;
		
	if($discount > 0){
		$cartItems[] = array(
				'itemName' =>	'Special discount',
				'itemNumber'=> '',
				'itemDescription' => 'DISC123',
				'itemAmount' => $discount * -1,
				'itemQty'=> 1
		);
	}
	$CartTotals = array();
	$CartTotals["subtotal"] = (20*2)+(12) - $discount;
	$CartTotals["shipping"] = $shippingFee;
	$CartTotals["total"] = $chargedAmount;
	$CartTotals["chargedAmount"] = $chargedAmount;
	
	$params = array(
			'payment_transaction_no' => $_SESSION['orderNumber'],
			'payment_cart_id' => $order_cartId
	);
	
	switch ($_REQUEST['action']) {
		case 'REDIRECT':
			
			
			//die(var_dump($cartItems).var_dump($CartTotals));
			$pay_obj = new PayPal($params, $config);
			$res =  $pay_obj->SetExpressCheckout($cartItems, $CartTotals);
			if(!$res){
				die($pay_obj->errorMsg);
			}
			break;
		
			
			
		default:
			if(!empty($_REQUEST['token'])){
					
					 
					$pay_obj = new PayPal($params, $config);
					$reponse = $pay_obj->Submit($_REQUEST['token']);
					if ($reponse){
						 
				
					// REDIRECT TO THANK YOU PAGE
    		  header('Location: /thank-you-for-buying');
    		  exit;
				
					} else {
					if ($pay_obj->errorMsg) {
						$_SESSION['error'] = $pay_obj->errorMsg;
					} else {
						$_SESSION['error'] = 'Payment failed. Verify information and try again. ';
					}
					}
				
					header('Location: /checkout#error');
				
			}
	}
	die();
}else{
	header('Location: /404');
  die();
}