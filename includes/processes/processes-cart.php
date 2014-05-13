<?php
global $SMARTY;
$referer = parse_url($_SERVER['HTTP_REFERER']);
if( $referer['host'] == $_SERVER['HTTP_HOST'] ){
	switch ($_POST['action']) {
		case 'ADDTOCART':
			$cart_obj = new cart();
			$storeId = $_SESSION['user']['public']['store_id'];
			$sql = "SELECT listing_name, location_phone FROM tbl_listing LEFT JOIN tbl_location ON listing_id = location_listing_id WHERE listing_object_id = :id AND listing_deleted IS NULL AND listing_published = 1";
			$params = array(":id"=>$storeId);
			$res = $DBobject->wrappedSql($sql,$params);
			$SMARTY->assign("storename",$res[0]['listing_name']);
			
			$response = $cart_obj->AddToCart($_POST['product_id'], $_POST['attr'], $_POST['quantity'], $_POST['price']);
			$itemsCount = $cart_obj->NumberOfProductsOnCart();
			$subtotal = $cart_obj->GetSubtotal();
			$productsOnCart = $cart_obj->GetDataProductsOnCart();
			$SMARTY->assign('productsOnCart',$productsOnCart);
			$SMARTY->assign('itemNumber',$itemsCount);
			$SMARTY->assign('subtotal', $subtotal);
			$estimate= $SMARTY->fetch('viewestimate.tpl'); 
			
			echo json_encode(array(
		    				'message' => $response,
		    				'itemsCount' => $itemsCount,
		    				'subtotal' => $subtotal,
								'ID' => $_POST['product_object_id'],
							  'myEstimate' => $estimate,
								'storename' => $res[0]['listing_name']
	    				));
		    exit;
		    
	    case 'DeleteItem':
	    	$cart_obj = new cart();
	    	$storeId = $_SESSION['user']['public']['store_id'];
	    	$sql = "SELECT listing_name, location_phone FROM tbl_listing LEFT JOIN tbl_location ON listing_id = location_listing_id WHERE listing_object_id = :id AND listing_deleted IS NULL AND listing_published = 1";
	    	$params = array(":id"=>$storeId);
	    	$res = $DBobject->wrappedSql($sql,$params);
	    	$SMARTY->assign("storename",$res[0]['listing_name']);
	    	
	    	$response = $cart_obj->RemoveFromCart($_POST['cartitem_id']);
            $totals = $cart_obj->CalculateTotal();
            $itemsCount = $cart_obj->NumberOfProductsOnCart();
            $cart = $cart_obj->GetDataCart();
            $productsOnCart = $cart_obj->GetDataProductsOnCart();
            $SMARTY->assign('productsOnCart',$productsOnCart);
            $SMARTY->assign('itemNumber',$itemsCount);
            $SMARTY->assign('cart',$cart);
            $estimate= $SMARTY->fetch('viewestimate.tpl'); 
	    	echo json_encode(array(
	    					'itemsCount' => $itemsCount,
                            'response'=> $response,
                            'totals'=>$totals,
							 'myEstimate' => $estimate 
            ));
	    	exit;

    	case 'updateCart':
    		$cart_obj = new cart();
    		$storeId = $_SESSION['user']['public']['store_id'];
    		$sql = "SELECT listing_name, location_phone FROM tbl_listing LEFT JOIN tbl_location ON listing_id = location_listing_id WHERE listing_object_id = :id AND listing_deleted IS NULL AND listing_published = 1";
    		$params = array(":id"=>$storeId);
    		$res = $DBobject->wrappedSql($sql,$params);
    		$SMARTY->assign("storename",$res[0]['listing_name']);
    		
    		$subtotals = $cart_obj->UpdateQtyCart($_POST['qty']);
    		$totals = $cart_obj->CalculateTotal();
            $itemsCount = $cart_obj->NumberOfProductsOnCart();
            $cart = $cart_obj->GetDataCart();
            $productsOnCart = $cart_obj->GetDataProductsOnCart();
            $SMARTY->assign('productsOnCart',$productsOnCart);
            $SMARTY->assign('itemNumber',$itemsCount);
            $SMARTY->assign('cart',$cart);
            $SMARTY->assign('',$cart);
            $estimate= $SMARTY->fetch('viewestimate.tpl'); 
    		echo json_encode(array(
		    		'itemsCount'=> $itemsCount,
    				'subtotals'=>$subtotals,
    				'totals'=>$totals,
					'myEstimate' => $estimate 
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
			$logged = false;
			if(!empty($_SESSION['user']['public']['id'])){
				$logged = true;
				$storeId = $_SESSION['user']['public']['store_id'];
				$sql = "SELECT listing_name, location_phone, location_email, location_order_recipient, location_bcc_recipient FROM tbl_listing LEFT JOIN tbl_location ON listing_id = location_listing_id WHERE listing_object_id = :id AND listing_deleted IS NULL AND listing_published = 1";
				$params = array(":id"=>$storeId);
				$res = $DBobject->wrappedSql($sql,$params);
				$storeName = $res[0]['listing_name'];
				$storePhone = $res[0]['location_phone'];
				$storeEmail = $res[0]['location_email'];
				
				$SMARTY->assign("storename",$storeName);
				$SMARTY->assign("storephone",$storePhone);
				
				
    			$pay_obj = new PayWay();
    			$response = false;
    			$error_msg = null;
	    		
	    		$cart_obj = new cart();
	    		$order_cartId = $cart_obj->cart_id;
	    	
	    		$totals = $cart_obj->CalculateTotal();
	    		$shippingFee = 0;
	    		$params = array(
	    				'payment_store_id' => $storeId,
	    				'payment_status' => 'P',
	    				'payment_transaction_no' => $order_cartId . '-' .  strtotime("now"),
	    				'payment_cart_id' => $order_cartId,
	    				'payment_user_id' => $_SESSION['user']['public']['id'],
	    				'payment_subtotal' => $totals['subtotal'],
	    				'payment_discount' => $totals['discount'],
	    				'payment_shipping_fee' => $shippingFee,
	    				'payment_charged_amount' => $totals['total'] + $shippingFee,
	    				'payment_gst' => ($totals['GST_Taxable'] + $shippingFee)/10
	    		);
	    		$paymentId = $pay_obj->StorePaymentRecord($params);
	    		
	    		// PAYMENT SUCCESS
	    		$cart_obj->CloseCart(null, $storeId);
	    		$pay_obj->SetOrderStatus($paymentId);
	    		
	    		  try{
	    		    // SEND CONFIRMATION EMAIL
	    		    $buffer= $SMARTY->fetch('confirmation-email.tpl');
	    		    $to = $_SESSION['user']['public']['email'];
	    		    
	    		    $from = 'Steeline';
	    		    $fromEmail = "noreply@" . str_replace ( "www.", "", $_SERVER ['HTTP_HOST'] );
	    		    $subject = 'Your quote has been submitted';
	    		    $body = $buffer;
	    		    if($mailID = sendMail($to, $from, $fromEmail, $subject, $body)){
	    		    	$pay_obj->SetInvoiceEmail($paymentId, $mailID);
	    		    }
	    		    
	    		   
	    		    
	    		    // EMAIL TO THE STORE
	    		    
	    		    $SMARTY->assign("user",$_SESSION['user']['public']);
	    		    
	    		    $user_obj = new UserClass();
	    		    $address = $user_obj->GetAddress($_SESSION['user']['public']['id']);
	    		    $SMARTY->assign("address",$address);
	    		    
	    		    $order = $cart_obj->GetDataCart($order_cartId);
	    		    $SMARTY->assign('order',$order);
	    		    
	    		    $payment = $pay_obj->GetPaymentRecord($paymentId);
							$SMARTY->assign('payment',$payment);
							
							$orderItems = $cart_obj->GetDataProductsOnCart($order_cartId);
							$SMARTY->assign('orderItems',$orderItems);

							//COMMMENTED UNTIL GO LIVE TO PREVENT STORES GETTING TESTING EMAILS
							/* $bcc = $res[0]['location_bcc_recipient'];
							$to = empty($res[0]['location_order_recipient'])?"online@them.com.au":$res[0]['location_order_recipient'];
							*/							
	    		    $to = 'apolo@them.com.au';
	    		    $bcc = '';
	    		    $subject = 'An estimate request has been submitted';
	    		    $body= $SMARTY->fetch('order-email.tpl');
	    		    sendMail($to, $from, $fromEmail, $subject, $body, $bcc);
	    		    
	    		  }catch(Exception $e){
	    		  	echo json_encode(array(
	    		  			'response'=>false,
	    		  			'logged'=>$logged,
	    		  			'error'=>$e
	    		  	));
	    		  	exit;
	    		  	
	    		  }
	    		  
	    			
	    			
	    			// OPEN NEW CART
	    			$cart_obj->CreateCart($_SESSION['user']['public']['id']);
  					
  					//THANK YOU
  					
  					$template= $SMARTY->fetch('thanks.tpl');
  					$estimate= $SMARTY->fetch('viewestimate.tpl');
  					echo json_encode(array(
  							'template'=>$template,
  							'myEstimate'=>$estimate,
  							'response'=>true,
  							'logged'=>$logged,
  							'storename' => $storeName
  					));
  					
  				} else {
  					echo json_encode(array('logged'=>$logged));
  				}
	}
	die();
}else{
	header('Location: /404');
    die();
}