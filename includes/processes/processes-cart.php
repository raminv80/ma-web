<?php
global $SMARTY,$DBobject,$CONFIG;
$referer = parse_url($_SERVER['HTTP_REFERER']);
if( $referer['host'] == $GLOBALS['HTTP_HOST'] ){
	switch ($_POST['action']) {
		case 'ADDTOCART':
			$cart_obj = new cart();			
			$response = $cart_obj->AddToCart($_POST['product_id'], $_POST['attr'], $_POST['quantity'], $_POST['price'], null, $_POST['listing_id']);
			$itemsCount = $cart_obj->NumberOfProductsOnCart();
			$subtotal = $cart_obj->GetSubtotal();
			$productsOnCart = $cart_obj->GetDataProductsOnCart();
			$SMARTY->assign('productsOnCart',$productsOnCart);
			$SMARTY->assign('itemNumber',$itemsCount);
			$SMARTY->assign('subtotal', $subtotal);
			$popoverShopCart= $SMARTY->fetch('templates/popover-shopping-cart.tpl');
			$productGA = $cart_obj->getProductInfo_GA($_POST['product_id'], $_POST['attr'], $_POST['quantity'], $_POST['listing_id']);
			echo json_encode(array(
								'product' => $productGA,
								'message' => $response,
		    				'itemsCount' => $itemsCount,
		    				'subtotal' => $subtotal,
								'url' => 'http://'.$GLOBALS['HTTP_HOST'].'/shopping-cart',
								'popoverShopCart' =>  str_replace(array('\r\n', '\r', '\n', '\t'), ' ', $popoverShopCart)
	    				));
		    exit;
		    
	    case 'DeleteItem':
	    	$cart_obj = new cart();
	    	$response = $cart_obj->RemoveFromCart($_POST['cartitem_id']);
	    			$productGA = $cart_obj->getProductInfoByCartItem_GA($_POST['cartitem_id']);
	    			$totals = $cart_obj->CalculateTotal();
            $itemsCount = $cart_obj->NumberOfProductsOnCart();
            $cart = $cart_obj->GetDataCart();
            $productsOnCart = $cart_obj->GetDataProductsOnCart();
            $SMARTY->assign('productsOnCart',$productsOnCart);
            $SMARTY->assign('itemNumber',$itemsCount);
            $SMARTY->assign('subtotal',$totals['subtotal']);
            $SMARTY->assign('cart',$cart);
            $popoverShopCart= $SMARTY->fetch('templates/popover-shopping-cart.tpl');
	    	echo json_encode(array(
	    					'product' => $productGA,
	    					'itemsCount' => $itemsCount,
                'response'=> $response,
                'totals'=>$totals,
								'popoverShopCart' =>  str_replace(array('\r\n', '\r', '\n', '\t'), ' ', $popoverShopCart)
            ));
	    	exit;

    	case 'updateCart':
    		$cart_obj = new cart();
    		$updated_products = $cart_obj->UpdateQtyCart($_POST['qty']);
    		$subtotals = $updated_products['subtotals'];
    		$priceunits = $updated_products['priceunits'];
    		$pricemodifier = $updated_products['pricemodifier'];
    		$totals = $cart_obj->CalculateTotal();
            $itemsCount = $cart_obj->NumberOfProductsOnCart();
            $cart = $cart_obj->GetDataCart();
            $productsOnCart = $cart_obj->GetDataProductsOnCart();
            $SMARTY->assign('productsOnCart',$productsOnCart);
            $SMARTY->assign('itemNumber',$itemsCount);
            $SMARTY->assign('subtotal',$totals['subtotal']);
            $SMARTY->assign('cart',$cart);
            $popoverShopCart= $SMARTY->fetch('templates/popover-shopping-cart.tpl');
    		echo json_encode(array(
		    		'itemsCount'=> $itemsCount,
    				'subtotals'=>$subtotals,
    		    'pricemodifier'=>$pricemodifier,
    		    'priceunits'=>$priceunits,
    				'totals'=>$totals,
					'popoverShopCart' =>  str_replace(array('\r\n', '\r', '\n', '\t'), ' ', $popoverShopCart)
    		));
    		exit;
    case 'updatePostage':
    	$ship_obj = new ShippingClass();
      echo json_encode(array(
          'postagefee'=> $ship_obj->getPostageByPostcode($_POST['postcode'])
      ));
      exit;
    	  
    case 'addFavourite':
    		$logged = false;
    		$success = false;
    		if(!empty($_SESSION['user']['public']['id'])){
    			$logged = true;
	      	$cart_obj = new cart();
	      	if($res = $cart_obj->AddFavourite($_SESSION['user']['public']['id'], $_POST['productObjId'])){
	      		$success = true;
	      	}
    		}
      	echo json_encode(array(
      			'success'=>$success,
      			'logged'=>$logged
      	));
      	exit();
    
    case 'deleteFavourite':
    		$logged = false;
    		if(!empty($_SESSION['user']['public']['id'])){
    			$logged = true;
	      	$cart_obj = new cart();
	      	$res = $cart_obj->DeleteFavourite($_SESSION['user']['public']['id'], $_POST['productObjId']);
    		}
      	echo json_encode(array(
      			'error'=>$res['error'],
      			'success'=>$res['success'],
      			'logged'=>$logged
      	));
      	exit();
    		
    		
		case 'applyDiscount':
		    $cart_obj = new cart();
		    $res = $cart_obj->ApplyDiscountCode($_POST['discount_code']);
		    if ($res['error']) {
		    	$_SESSION['error']= $res['error'];
		    	$_SESSION['reApplydiscount']= ($res['reApplyAfterLogin'])?$_POST['discount_code']:'';
		    	$_SESSION['post']= $_POST;
		    	header('Location: '.$_SERVER['HTTP_REFERER'].'#error');
		    } else {
		    	header('Location: '.$_SERVER['HTTP_REFERER']);
		    }
		    exit;
		    
		    case 'checkout1':
		      $_SESSION['smarty']['selectedShippingPostcode'] = $_POST['postcodefield'];
		      $_SESSION['smarty']['selectedShippingFee'] = $_POST['shipFee'];
		      $_SESSION['smarty']['selectedShipping'] = $_POST['shipMethod'];
		      $_SESSION['smarty']['postageID'] = $_POST['postageID'];
		      
		      $sql = "SELECT * FROM tbl_postcode WHERE postcode_postcode = :postcode";
		      $params = array(":postcode"=>$_POST['postcodefield']);
		      $res = $DBobject->wrappedSql($sql,$params);
		      if(!empty($res[0])){
		        $_SESSION['smarty']['selectedShippingState'] = $res[0]['postcode_state'];
		        if(count($res) == 1){
		          $_SESSION['smarty']['selectedShippingSuburb'] = $res[0]['postcode_suburb'];
		        }
		      }
		      
		      if($CONFIG->checkout->attributes()->guest != 'true' && empty($_SESSION['user']['public']['id'])){
		        $_SESSION['redirect'] = "/checkout";
		        header("Location: /login-register");
		        exit();
		      }
		      header('Location: /checkout');
		      exit;
		    
		    case 'checkout2':
		      $_SESSION['postageID'] = $_POST['postageID'];
		      $_SESSION['address'] = $_POST['address'];
		      $bsum = $_POST['address']['B']['address_name'] .'<br />';
		      $bsum .= $_POST['address']['B']['address_surname'] .'<br />';
		      $bsum .= $_POST['address']['B']['address_line1'] .'<br />';
		      $bsum .= $_POST['address']['B']['address_suburb'] .' ';
		      $bsum .= $_POST['address']['B']['address_state'] .' ';
		      $bsum .= $_POST['address']['B']['address_postcode'] .'<br />';
		      $bsum .= $_POST['address']['B']['email'] .'<br />';
		      $bsum .= $_POST['address']['B']['address_telephone'] .'<br />';
		      if ($_POST['address']['same_address']){
		        $ssum = '<span class="small">Shipping address same as billing address<br />';
		      }else{
		        $ssum = $_POST['address']['S']['address_name'] .'<br />';
		        $ssum .= $_POST['address']['S']['address_surname'] .'<br />';
		        $ssum .= $_POST['address']['S']['address_line1'] .'<br />';
		        $ssum .= $_POST['address']['S']['address_suburb'] .' ';
		        $ssum .= $_POST['address']['S']['address_state'] .' ';
		        $ssum .= $_POST['address']['S']['address_postcode'] .'<br />';
		        $ssum .= $_POST['address']['S']['address_telephone'] .'<br />';
		      }
		      $_SESSION['comments'] = $_POST['comments'];
		      if($_POST['comments']){
		        $ssum .= 'Shipping instructions: ' . $_POST['comments'] . '<br />';
		      }else{
		        $ssum .= 'No shipping instructions <br />';
		      }
		    
		      echo json_encode(array(
		          'response'=> true,
		          'billing'=> $bsum,
		          'shipping'=> $ssum,
		          'comments'=> $_POST['comments']
		      ));
		      exit;

	    case 'getShippingFees':
	    	$_SESSION['address'] = $_POST['address'];
	    	
	    	$ship_obj = new ShippingClass();
	    	$cart_obj = new cart();
	    	
	    	$methods = $ship_obj->getShippingMethods($cart_obj->NumberOfProductsOnCart()); 
	    	
	    	echo json_encode(array(
	    			'shippingMethods'=> $methods,
	    			'billing'=> $_POST['address']['B'],
	    			'shipping'=> $_POST['address']['S'],
	    			'same'=> $_POST['address']['same_address']
	    	));
	    	exit;
		    	
		case 'placeOrder':
				if(!empty($_SESSION['address']['B'])){
					$isGuest = false;
					$user_obj = new UserClass();
					$values = array();
					$values['email'] = $_SESSION['address']['B']['email'];
					$values['gname'] = $_SESSION['address']['B']['address_name'];
					$values['surname'] = $_SESSION['address']['B']['address_surname'];
					$promo = 0;
					if($_SESSION['address']['wantpromo']){
						 $promo = 1;
						 try{
						 	require_once 'includes/createsend/csrest_subscribers.php';
						 	$wrap = new CS_REST_Subscribers('', '060d24d9003a77b06b95e7c47691975b'); //!!!! UPDATE CREATESEND LIST CODE !!!!!
						 	$cs_result = $wrap->add(array(
						 			'EmailAddress' => $values['email'],
						 			'Name' => $values['gname'] . ' ' . $values['surname'],
						 			'CustomFields' => array(),
						 			"Resubscribe" => "true"
						 	));
						 }catch(Exception $e){}
					}
					$values['want_promo'] = $promo;
					if(empty($_SESSION['user']['public']['id'])){ // ADD GUEST USER
						$isGuest = true;
						$values['username'] = $_SESSION['address']['B']['email'] ;
						$values['password'] = session_id ();
						
						$res = $user_obj->Create($values);
						 
						if( $res['error'] ) {
							$_SESSION['error']= $res['error'];
							$_SESSION['post']= $_POST;
							header("Location: ".$_SERVER['HTTP_REFERER']."#error");
							die();
						} else {
							$cart_obj = new cart();
							$cart_obj->SetUserCart($res['id']);
							$_SESSION['user']['public'] = $res;
							$_POST['address']['B']['address_user_id'] = $res['id'];
							$_POST['address']['S']['address_user_id'] = $res['id'];
						}
					} else {
	    			$user_obj->UpdateDetails($values);
	    			$_SESSION['user']['public']['gname'] = $values['gname'];
	    			$_SESSION['user']['public']['surname'] = $values['surname'];
	    		}
	    		
	    		// SAVE BILLING AND SHIPPING ADDRESS
	    		$billID = $user_obj->InsertNewAddress(array_merge(array(
	    				'address_user_id' => $_SESSION['user']['public']['id']
	    		),$_SESSION['address']['B'] ) );
	    		$shipID = $billID;
	    		
	    		if (empty($_SESSION['address']['same_address'])) {
	    			$shipID = $user_obj->InsertNewAddress(array_merge(array(
	    					'address_user_id' => $_SESSION['user']['public']['id']
	    			),$_SESSION['address']['S'] ) );
	    		}
	    		
	    		if(is_null($billID) || is_null($shipID)){
	    			$_SESSION['error']= 'Error while saving billing/shipping address. Please try again, otherwise contact us by phone.';
	    			$_SESSION['post']= $_POST;
	    			header("Location: ".$_SERVER['HTTP_REFERER']."#error");
	    			die();
	    		}
	    		
	    		$cart_obj = new cart();
	    		$order_cartId = $cart_obj->cart_id;
	    		$orderNumber = $order_cartId.'-'.date("is");
	    	
	    		$ship_obj = new ShippingClass();
	    		
	    		$postage = $ship_obj->getPostageByAddressId($shipID);
	    		$methods = $postage['postage_name'];
	    		$shippingFee = floatval($postage['postage_price']);
// 	    		$methods = $ship_obj->getShippingMethods($cart_obj->NumberOfProductsOnCart());
// 	    		$shippingFee = floatval($methods["{$_POST['payment']['payment_shipping_method']}"]); 

	    		$totals = $cart_obj->CalculateTotal();
	    		$chargedAmount = $totals['total'] + $shippingFee;
	    		$gst = round(($totals['GST_Taxable'] + $shippingFee)/11, 2);
	    		$params = array(
	    				'payment_billing_address_id' => $billID,
	    				'payment_shipping_address_id' => $shipID,
	    				'payment_status' => 'A',
	    				'payment_transaction_no' => $orderNumber,
	    				'payment_cart_id' => $order_cartId,
	    				'payment_user_id' => $_SESSION['user']['public']['id'],
	    				'payment_subtotal' => $totals['subtotal'],
	    				'payment_discount' => $totals['discount'],
	    				'payment_shipping_fee' => $shippingFee,
    					'payment_shipping_method' => $methods,
    					'payment_shipping_comments' =>  $_SESSION['comments'],
    					'payment_payee_name' => $_POST['cc']['name'],
	    				'payment_charged_amount' => $chargedAmount,
	    				'payment_gst' => $gst,
	    				'payment_method' => 'Bank Transfer'
	    		);
	    		$pay_obj = new PayWay();
	    		$response = false;
	    		
	    		$paymentId = $pay_obj->StorePaymentRecord($params);
	    		
	    		$bankTransfer = 0;
	    		if($_POST['paymentmethod'] == 'bank_transfer'){
	    			$bankTransfer = 1;
	    		}
	    		/* $CCdata =  array('amount'=>$chargedAmount);
    			if(!empty($_POST['cc'])){
    				$CCdata = array_merge($CCdata, $_POST['cc']);
    			}
    			$pay_obj->PreparePayment($CCdata);
	    		
	    		try{
	    			$reponse = $pay_obj->Submit();
	    			$paymentId = $paypalObj->GetPaymentId();
	    		}catch(Exception $e){
	    			if ($error_msg = $pay_obj->GetErrorMessage()) {
	    				$_SESSION['error'] = $error_msg;
	    			} else {
	    				$_SESSION['error'] = 'Payment failed (on submit). Verify information and try again. ';
	    			}
	    			header('Location: '.$_SERVER['HTTP_REFERER'].'#error');
	    			exit;
	    		} */
	    		$reponse = true;
	    		if ($reponse){
		    		// PAYMENT SUCCESS
		    		$cart_obj->CloseCart();
		    		$pay_obj->SetOrderStatus($paymentId);
		    		$_SESSION['orderNumber'] = $orderNumber;
	    		
	    		  try{
	    		    // SEND CONFIRMATION EMAIL
	    		    $SMARTY->assign("user",$_SESSION['user']['public']);
	    		    $user_obj = new UserClass();
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
							$SMARTY->assign('bankTransfer',$bankTransfer);
							$SMARTY->assign('DOMAIN', "http://" . $GLOBALS['HTTP_HOST']);
							$COMP = json_encode($CONFIG->company);
							$SMARTY->assign('COMPANY', json_decode($COMP,TRUE));

							//COMMMENTED UNTIL GO LIVE TO PREVENT STORES GETTING TESTING EMAILS
							/* $bcc = $res[0]['location_bcc_recipient'];
							$to = empty($res[0]['location_order_recipient'])?"online@them.com.au":$res[0]['location_order_recipient'];
							*/							
	    		    $to = $_SESSION['user']['public']['email'];
	    		    //$bcc = 'apolo@them.com.au';
	    		    $from = (string) $CONFIG->company->name;
	    		    $fromEmail = 'noreply@' . str_replace ( "www.", "", $GLOBALS['HTTP_HOST'] );
	    		    $subject = 'Confirmation of your order';
	    		    $body= $SMARTY->fetch('email-confirmation.tpl');
	    		  	if($mailID = sendMail($to, $from, $fromEmail, $subject, $body, $bcc)){
		    		    	$pay_obj->SetInvoiceEmail($paymentId, $mailID);
	    		  	}
	    		  }catch(Exception $e){}
	    		  
    		    
    		    // SET GOOGLE ANALYTICS - ECOMMERCE
	    		  $affiliation = str_replace ( "www.", "", $GLOBALS['HTTP_HOST'] );
    		    $analytics = $cart_obj->getJSCartitemsByCartId_GA($order_cartId);
    		    $analytics .= "ga('ec:setAction', 'purchase', {
														  'id': '{$orderNumber}',
														  'affiliation': '{$affiliation}',
														  'revenue': '{$chargedAmount}',
														  'tax': '{$gst}',
														  'shipping': '{$shippingFee}',
														  'coupon': '{$order['cart_discount_code']}'  
														});
														";
    		    $_SESSION ['ga_ec'] = $analytics;
    		    
    		    //SET USED DISCOUNT CODE
    		    if ($order['cart_discount_code']) {
    		    	$cart_obj->SetUsedDiscountCode($order['cart_discount_code']);
    		    	$discountData = $cart_obj->GetDiscountData($order['cart_discount_code']);
    		    	if($discountData['discount_unlimited_use'] == '0'){
    		    		try{
    		    			// SEND NOTIFICATION EMAIL
    		    			$SMARTY->assign('user',$_SESSION['user']['public']);
    		    			$SMARTY->assign('discount',$discountData);
    		    			$buffer= $SMARTY->fetch('email-discount.tpl');
    		    			$to = "apolo@them.com.au";
    		    			$bcc = "";
    		    			$from = str_replace ( "www.", "", $GLOBALS['HTTP_HOST'] );
    		    			$fromEmail = 'noreply@' . str_replace ( "www.", "", $GLOBALS['HTTP_HOST'] );
    		    			$subject = 'A discount code has been used.';
    		    			$body = $buffer;
    		    			$mailID = sendMail($to, $from, $fromEmail, $subject, $body, $bcc);
    		    		}catch(Exception $e){}
    		    	}
    		    }
    		    
    		    // LOG OUT GUEST USER
    		    if($isGuest){
	    		    unset ( $_SESSION['user']['public'] );
	    		    //session_regenerate_id();
    		    }
    		    
    		    // OPEN NEW CART
    		    $cart_obj->CreateCart($_SESSION['user']['public']['id']);
    		    	
    		    unset ( $_SESSION['address'] );
    		    unset ( $_SESSION['comments'] );
    		    
    		    // REDIRECT TO THANK YOU PAGE
    		    header('Location: /thank-you-for-buying');
    		    exit;
    		    
    		  } else {
						if ($error_msg = $pay_obj->GetErrorMessage()) {
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
	die('@');
}else{
	header('Location: /404');
  die();
}