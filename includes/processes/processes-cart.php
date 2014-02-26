<?php
$referer = parse_url($_SERVER['HTTP_REFERER']);
if( $referer['host'] == $_SERVER['HTTP_HOST'] ){
	switch ($_POST['action']) {
		case 'ADDTOCART':
			$cart_obj = new cart();
			$response = $cart_obj->AddToCart($_POST['product_id'], $_POST['attr'], $_POST['quantity'], $_POST['price']);
			$itemsCount = $cart_obj->NumberOfProductsOnCart();
			$cart = $cart_obj->GetDataCart();
			$productsOnCart = $cart_obj->GetDataProductsOnCart();
			$SMARTY->assign('productsOnCart',$productsOnCart);
			$SMARTY->assign('itemNumber',$itemsCount);
			$SMARTY->assign('cart',$cart);
			$popoverShopCart= $SMARTY->fetch('templates/popover-shopping-cart.tpl');
			
			echo json_encode(array(
		    				'message' => $response,
		    				'itemsCount' => $itemsCount,
		    				'subtotal' => $cart['cart_subtotal'],
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
		    
		case 'placeOrder':
    		if (empty($_SESSION['user']['public']['id'])) {
    			$user_obj = new UserClass();
    			$values = array();
    			$values['username'] = $_POST['email'] . '#' . strtotime("now");
    			$values['email'] = $_POST['email'];
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
    				$_POST['address'][1]['address_user_id'] = $res['id']; 
    				$_POST['address'][2]['address_user_id'] = $res['id']; 
    			}
    			
    		} else {
    			$user_obj = new UserClass();
    		}
    		
    		$billID = $user_obj->InsertNewAddress($_POST['address'][1]);
    		$shipID = $billID;
    		if (is_null($_POST['same_address'])) { 
    			$shipID = $user_obj->InsertNewAddress($_POST['address'][2]);
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
	    		
	    		$params = array_merge(array(
	    				'payment_billing_address_id' => $billID,
	    				'payment_shipping_address_id' => $shipID,
	    				'payment_status' => 'P',
	    		),
	    				$_POST['payment']
	    		);
	    		$paymentId = $pay_obj->StorePaymentRecord($params);
	    		
	    		if ($reponse){
	    			// PAYMENT SUCCESS
	    			$cart_obj = new cart();
	    			$order_cartId = $cart_obj->cart_id;
	    			$cart_obj->CloseCart();
	    			$pay_obj->SetOrderStatus($paymentId);
	    			$cart_obj->CreateCart($_SESSION['user']['public']['id']);
	    			
	    			// SEND CONFIRMATION EMAIL
	    			$SMARTY->assign('user',$_SESSION['user']['public']);
	    			
					$billing = $user_obj->GetAddress($billID);
					$SMARTY->assign('billing',$billing);
					
					$shipping = $user_obj->GetAddress($shipID);
					$SMARTY->assign('shipping',$shipping);
					
					$order = $cart_obj->GetDataCart($order_cartId);
					$SMARTY->assign('order',$order);
					
					$orderItems = $cart_obj->GetDataProductsOnCart($order_cartId);
					$SMARTY->assign('orderItems',$orderItems);
					
					$buffer= $SMARTY->fetch('templates/email-confirmation.tpl');
					
					$to = $_SESSION['user']['public']['email'];
					$from = 'eShop';
					$fromEmail = 'noreply@cms.themserver.com';
					$subject = 'Confirmation of your order';
					$body = $buffer;
				
					sendMail($to, $from, $fromEmail, $subject, $body);
	    		
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