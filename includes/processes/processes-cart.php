<?php
$referer = parse_url($_SERVER['HTTP_REFERER']);
if( $referer['host'] == $_SERVER['HTTP_HOST'] ){
	switch ($_POST['action']) {
		case 'ADDTOCART':
			$cart_obj = new cart();
			$response = $cart_obj->AddToCart($_POST['product_id'], $_POST['attr'], $_POST['quantity'], $_POST['price']);
			$itemsCount = $cart_obj->NumberOfProductsOnCart();
			$subtotal = $cart_obj->GetSubtotal();
			$productsOnCart = $cart_obj->GetDataProductsOnCart();
			$SMARTY->assign('productsOnCart',$productsOnCart);
			$SMARTY->assign('itemNumber',$itemsCount);
			$SMARTY->assign('subtotal', $subtotal);
			$popoverShopCart= $SMARTY->fetch('templates/popover-shopping-cart.tpl');
			
			echo json_encode(array(
		    				'message' => $response,
		    				'itemsCount' => $itemsCount,
		    				'subtotal' => $subtotal,
		    				'url' => 'http://'.$_SERVER['HTTP_HOST'].'/store/shopping-cart',
							'popoverShopCart' =>  str_replace(array('\r\n', '\r', '\n', '\t'), ' ', $popoverShopCart)
	    				));
		    exit;
		    
	    case 'DeleteItem':
	    	$cart_obj = new cart();
	    	$response = $cart_obj->RemoveFromCart($_POST['cartitem_id']);
            $totals = $cart_obj->CalculateTotal();
            $itemsCount = $cart_obj->NumberOfProductsOnCart();
            $cart = $cart_obj->GetDataCart();
            $productsOnCart = $cart_obj->GetDataProductsOnCart();
            $SMARTY->assign('productsOnCart',$productsOnCart);
            $SMARTY->assign('itemNumber',$itemsCount);
            $SMARTY->assign('cart',$cart);
            $popoverShopCart= $SMARTY->fetch('templates/popover-shopping-cart.tpl');
	    	echo json_encode(array(
	    					'itemsCount' => $itemsCount,
                            'response'=> $response,
                            'totals'=>$totals,
							'popoverShopCart' =>  str_replace(array('\r\n', '\r', '\n', '\t'), ' ', $popoverShopCart)
            ));
	    	exit;

    	case 'updateCart':
    		$cart_obj = new cart();
    		$subtotals = $cart_obj->UpdateQtyCart($_POST['qty']);
    		$totals = $cart_obj->CalculateTotal();
            $itemsCount = $cart_obj->NumberOfProductsOnCart();
            $cart = $cart_obj->GetDataCart();
            $productsOnCart = $cart_obj->GetDataProductsOnCart();
            $SMARTY->assign('productsOnCart',$productsOnCart);
            $SMARTY->assign('itemNumber',$itemsCount);
            $SMARTY->assign('cart',$cart);
            $popoverShopCart= $SMARTY->fetch('templates/popover-shopping-cart.tpl');
    		echo json_encode(array(
		    		'itemsCount'=> $itemsCount,
    				'subtotals'=>$subtotals,
    				'totals'=>$totals,
					'popoverShopCart' =>  str_replace(array('\r\n', '\r', '\n', '\t'), ' ', $popoverShopCart)
    		));
    		exit;
    		
		case 'applyDiscount':
		    $cart_obj = new cart();
		    $res = $cart_obj->ApplyDiscountCode($_POST['discount_code']);
		    if ($res['error']) {
		    	$_SESSION['error']= $res['error'];
		    	$_SESSION['post']= $_POST;
		    	header('Location: '.$_SERVER['HTTP_REFERER'].'#error');
		    } else {
		    	header('Location: '.$_SERVER['HTTP_REFERER']);
		    }
		    exit;
		

	    case 'getShippingFees':
	    	$_SESSION['address'] = $_POST['address'];
	    	
	    	//TODO: CALL SHIPPING CLASS TO GET FEES BASED ON ADDRESS 
	    	$methods = array('Standard', 'Express');
	    	
	    	//CALCULATE FEES FOR EACH METHODS
	    	$cart_obj = new cart();
	    	$fess = array();
	    	foreach ($methods as $m) {
	    		$fees["{$m}"] = $cart_obj->CalculateShippingFee($m);
	    	}	
	    	echo json_encode(array(
	    			'shippingMethods'=> $fees,
	    			'billing'=> $_POST['address']['B'],
	    			'shipping'=> $_POST['address']['S'],
	    			'same'=> $_POST['address']['same_address']
	    	));
	    	exit;
		    	
		case 'placeOrder':
    		if (empty($_SESSION['user']['public']['id'])) { // ADD GUEST USER
    			$user_obj = new UserClass();
    			$values = array();
    			$values['username'] = $_SESSION['address']['B']['email'] . '#' . strtotime("now");
    			$values['email'] = $_SESSION['address']['B']['email'];
    			$values['password'] = session_id ();
    			$values['gname'] = 'Guest';
    			$values['surname'] = '';
    			$res = $user_obj->Create($values);
    			
    			if( $res['error'] ) {
    				$_SESSION['error']= $res['error'];
    				$_SESSION['post']= $_POST;
    				header("Location: ".$_SERVER['HTTP_REFERER']."#error");
    				exit;
    				die();
    			} else {
    				$cart_obj = new cart();
    				$cart_obj->SetUserCart($res['id']);
    				$_SESSION['user']['public'] = $res;
    				$_POST['address']['B']['address_user_id'] = $res['id']; 
    				$_POST['address']['S']['address_user_id'] = $res['id']; 
    			}
    			
    		} else {
    			$user_obj = new UserClass();
    		}
    		
    		$billID = $user_obj->InsertNewAddress(array_merge(array( 
    									'address_user_id' => $_SESSION['user']['public']['id']
    									),$_SESSION['address']['B'] ) );
    		$shipID = $billID;
    		if (is_null($_SESSION['address']['same_address'])) { 
    			$shipID = $user_obj->InsertNewAddress(array_merge(array( 
    									'address_user_id' => $_SESSION['user']['public']['id']
    									),$_SESSION['address']['S'] ) );
    		}
    		
    		if ($billID && $shipID) {
    			$pay_obj = new PayWay();
	    		//TODO: INITIALISE BANK PAYMENT
    			$response = false;
    			$error_msg = null;
    			
	    		try{
	    			//TODO: SUBMIT PAYMENT
	    			$reponse = $pay_obj->Submit();	
	    			
	    		}catch(Exception $e){
	    			$error_msg = 'Payment failed: Connection Error. ';
	    		}
	    		
	    		$cart_obj = new cart();
	    		$order_cartId = $cart_obj->cart_id;
	    		$shippingFee = $cart_obj->CalculateShippingFee($_POST['payment']['payment_shipping_method']);
	    		$totals = $cart_obj->CalculateTotal();
	    		
	    		$params = array_merge(array(
	    				'payment_billing_address_id' => $billID,
	    				'payment_shipping_address_id' => $shipID,
	    				'payment_status' => 'P',
	    				'payment_transaction_no' => $order_cartId . '-' .  strtotime("now"),
	    				'payment_cart_id' => $order_cartId,
	    				'payment_user_id' => $_SESSION['user']['public']['id'],
	    				'payment_subtotal' => $totals['subtotal'],
	    				'payment_discount' => $totals['discount'],
	    				'payment_shipping_fee' => $shippingFee,
	    				'payment_charged_amount' => $totals['total'] + $shippingFee,
	    				'payment_gst' => ($totals['GST_Taxable'] + $shippingFee)/10
	    				),
	    				$_POST['payment']
	    		);
	    		$paymentId = $pay_obj->StorePaymentRecord($params);
	    		
	    		if ($reponse){
	    		  try{
	    		    // SEND CONFIRMATION EMAIL
	    		    $user = $_SESSION['user']['public'];
	    		    $SMARTY->assign('user',$user);
	    		    $billing = $user_obj->GetAddress($billID);
	    		    $SMARTY->assign('billing',$billing);
	    		    $shipping = $user_obj->GetAddress($shipID);
	    		    $SMARTY->assign('shipping',$shipping);
	    		    $order = $cart_obj->GetDataCart($order_cartId);
	    		    $SMARTY->assign('order',$order);
	    		    $payment = $pay_obj->GetPaymentRecord($paymentId);
	    		    $SMARTY->assign('payment',$payment);
	    		    $orderItems = $cart_obj->GetDataProductsOnCart($order_cartId);
	    		    $SMARTY->assign('orderItems',$orderItems);
	    		    $buffer= $SMARTY->fetch('templates/email-confirmation.tpl');
	    		    $to = $_SESSION['address']['B']['email'];
	    		    $from = 'eShop';
	    		    $fromEmail = 'noreply@cms.themserver.com';
	    		    $subject = 'Confirmation of your order';
	    		    $body = $buffer;
	    		    if($mailID = sendMail($to, $from, $fromEmail, $subject, $body)){
	    		    	$pay_obj->SetInvoiceEmail($paymentId, $mailID);
	    		    }
	    		  }catch(Exception $e){}
	    		  
	    			// PAYMENT SUCCESS
	    			$cart_obj->CloseCart();
	    			$pay_obj->SetOrderStatus($paymentId);
	    			
	    			
	    			// LOG OUT GUEST USER
	    			if ($_SESSION['user']['public']['gname'] == 'Guest') {
	    				unset ( $_SESSION['user']['public'] );
	    				session_regenerate_id();
	    			}
	    			// OPEN NEW CART
	    			$cart_obj->CreateCart($_SESSION['user']['public']['id']);

  					
  					//UNPUBLISH ONE-TIME USE DISCOUNT CODE 
  					if ($order['cart_discount_code']) {
  						$cart_obj->UnpublishOneTimeDiscountCode($order['cart_discount_code']);  
  					}
  					
  					unset ( $_SESSION['address'] );
	    			// REDIRECT TO THANK YOU PAGE	
	    			header('Location: /thank-you-for-buying');
	    			exit;
	    			
	    		} else {
	    			if ($error_msg) {
	    				$_SESSION['error'] = $error_msg;
	    			} else {
	    				$_SESSION['error'] = 'Payment failed. Verify information and try again. ';
	    			}
	    		}
    		} else {
    			$_SESSION['error']= 'Database Connection Error. Please try again, otherwise contact us by phone.';
    		}
    		
    		$_SESSION['post']= $_POST;
    		header('Location: '.$_SERVER['HTTP_REFERER'].'#error');
    		
    		exit;

	
	}
	die();
}else{
	header('Location: /404');
    die();
}