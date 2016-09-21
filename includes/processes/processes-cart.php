<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;
$referer = parse_url($_SERVER['HTTP_REFERER']);
if($referer['host'] == $GLOBALS['HTTP_HOST']){
  switch($_POST['action']){
    case 'ADDTOCART':
      try{
        $cart_obj = new cart($_SESSION['user']['public']['id']);
        $success = $cart_obj->AddToCart($_POST['product_id'], $_POST['attr'], $_POST['price'], $_POST['quantity'], null, $_POST['variant_id']);
        $itemsCount = $cart_obj->NumberOfProductsOnCart();
        $subtotal = $cart_obj->GetSubtotal();
        $productsOnCart = $cart_obj->GetDataProductsOnCart();
        
        //Resources to update the fron end view
        $SMARTY->assign('productsOnCart', $productsOnCart);
        $SMARTY->assign('itemNumber', $itemsCount);
        $SMARTY->assign('subtotal', $subtotal);
        $popoverShopCart = $SMARTY->fetch('templates/popover-shopping-cart.tpl');
        
      }catch(exceptionCart $e){
        $error = $e->getMessage();
      }
      
      //----------------- PENDING TO DO -------------
      /* $productGA = $cart_obj->getProductInfo_GA($_POST['product_id'], $_POST['attr'], $_POST['quantity'], $_POST['listing_id']);
      sendGAEnEcAction($GA_ID, 'add', $productGA); */
      
      echo json_encode(array(
          'success' => $success,
          'error' => $error,
          'itemsCount' => $itemsCount, 
          'subtotal' => $subtotal, 
          'url' => '/shopping-cart', 
          'popoverShopCart' => $popoverShopCart 
      ));
      die();
    
    case 'DeleteItem':
      $cart_obj = new cart($_SESSION['user']['public']['id']);
      $response = $cart_obj->RemoveFromCart($_POST['cartitem_id']);
      $productGA = $cart_obj->getProductInfoByCartItem_GA($_POST['cartitem_id']);
      $totals = $cart_obj->CalculateTotal();
      $itemsCount = $cart_obj->NumberOfProductsOnCart();
      $cart = $cart_obj->GetDataCart();
      $productsOnCart = $cart_obj->GetDataProductsOnCart();
      $SMARTY->assign('productsOnCart', $productsOnCart);
      $SMARTY->assign('itemNumber', $itemsCount);
      $SMARTY->assign('subtotal', $totals['subtotal']);
      $SMARTY->assign('cart', $cart);
      $popoverShopCart = $SMARTY->fetch('templates/popover-shopping-cart.tpl');
      sendGAEnEcAction($GA_ID, 'remove', $productGA);
      echo json_encode(array(
          'product' => $productGA, 
          'itemsCount' => $itemsCount, 
          'response' => $response, 
          'totals' => $totals, 
          'popoverShopCart' => str_replace(array(
              '\r\n', 
              '\r', 
              '\n', 
              '\t' 
          ), ' ', $popoverShopCart) 
      ));
      die();
    
    case 'updateCart':
      $cart_obj = new cart($_SESSION['user']['public']['id']);
      $updated_products = $cart_obj->UpdateQtyCart($_POST['qty']);
      $subtotals = $updated_products['subtotals'];
      $priceunits = $updated_products['priceunits'];
      $pricemodifier = $updated_products['pricemodifier'];
      $totals = $cart_obj->CalculateTotal();
      $itemsCount = $cart_obj->NumberOfProductsOnCart();
      $cart = $cart_obj->GetDataCart();
      $productsOnCart = $cart_obj->GetDataProductsOnCart();
      $SMARTY->assign('productsOnCart', $productsOnCart);
      $SMARTY->assign('itemNumber', $itemsCount);
      $SMARTY->assign('subtotal', $totals['subtotal']);
      $SMARTY->assign('cart', $cart);
      $popoverShopCart = $SMARTY->fetch('templates/popover-shopping-cart.tpl');
      echo json_encode(array(
          'itemsCount' => $itemsCount, 
          'subtotals' => $subtotals, 
          'pricemodifier' => $pricemodifier, 
          'priceunits' => $priceunits, 
          'totals' => $totals, 
          'popoverShopCart' => str_replace(array(
              '\r\n', 
              '\r', 
              '\n', 
              '\t' 
          ), ' ', $popoverShopCart) 
      ));
      die();
    case 'updatePostage':
      $ship_obj = new ShippingClass();
      echo json_encode(array(
          'postagefee' => $ship_obj->getPostageByPostcode($_POST['postcode']) 
      ));
      die();
    
    case 'addFavourite':
      $logged = false;
      $success = false;
      if(!empty($_SESSION['user']['public']['id'])){
        $logged = true;
        $cart_obj = new cart($_SESSION['user']['public']['id']);
        if($res = $cart_obj->AddFavourite($_SESSION['user']['public']['id'], $_POST['productObjId'])){
          $success = true;
        }
      }
      echo json_encode(array(
          'success' => $success, 
          'logged' => $logged 
      ));
      die();
    
    case 'deleteFavourite':
      $logged = false;
      if(!empty($_SESSION['user']['public']['id'])){
        $logged = true;
        $cart_obj = new cart($_SESSION['user']['public']['id']);
        $res = $cart_obj->DeleteFavourite($_SESSION['user']['public']['id'], $_POST['productObjId']);
      }
      echo json_encode(array(
          'error' => $res['error'], 
          'success' => $res['success'], 
          'logged' => $logged 
      ));
      die();
    
    case 'applyDiscount':
      $cart_obj = new cart($_SESSION['user']['public']['id']);
      $res = $cart_obj->ApplyDiscountCode($_POST['discount_code']);
      if($res['error']){
        $_SESSION['error'] = $res['error'];
        $_SESSION['reApplydiscount'] = ($res['reApplyAfterLogin'])? $_POST['discount_code'] : '';
        $_SESSION['post'] = $_POST;
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '#form-error');
      } else{
        header('Location: ' . $_SERVER['HTTP_REFERER']);
      }
      die();
    
    case 'checkout1':
      $_SESSION['smarty']['selectedShippingPostcode'] = $_POST['postcodefield'];
      $_SESSION['smarty']['selectedShippingFee'] = $_POST['shipFee'];
      $_SESSION['smarty']['selectedShipping'] = $_POST['shipMethod'];
      $_SESSION['smarty']['postageID'] = $_POST['postageID'];
      
      $sql = "SELECT * FROM tbl_postcode WHERE postcode_postcode = :postcode";
      $params = array(
          ":postcode" => $_POST['postcodefield'] 
      );
      $res = $DBobject->wrappedSql($sql, $params);
      if(!empty($res[0])){
        $_SESSION['smarty']['selectedShippingState'] = $res[0]['postcode_state'];
        if(count($res) == 1){
          $_SESSION['smarty']['selectedShippingSuburb'] = $res[0]['postcode_suburb'];
        }
      }
      $cart_obj = new cart($_SESSION['user']['public']['id']);
      $productsGA = $cart_obj->getCartitemsByCartId_GA();
      sendGAEnEcCheckoutStep($GA_ID, $_POST['shipMethod'], $productsGA);
      if($CONFIG->checkout->attributes()->guest != 'true' && empty($_SESSION['user']['public']['id'])){
        $_SESSION['redirect'] = "/checkout";
        header("Location: /login-register");
        die();
      }
      header('Location: /checkout');
      die();
    
    case 'checkout2':
      $_SESSION['postageID'] = $_POST['postageID'];
      $_SESSION['address'] = $_POST['address'];
      $bsum = $_POST['address']['B']['address_name'] . ' ';
      $bsum .= $_POST['address']['B']['address_surname'] . '<br />';
      $bsum .= $_POST['address']['B']['address_line1'] . '<br />';
      $bsum .= $_POST['address']['B']['address_suburb'] . ' ';
      $bsum .= $_POST['address']['B']['address_state'] . ' ';
      $bsum .= $_POST['address']['B']['address_postcode'] . '<br />';
      $bsum .= $_POST['address']['B']['address_email'] . '<br />';
      $bsum .= $_POST['address']['B']['address_telephone'] . '<br />';
      if($_POST['address']['same_address']){
        $ssum = '<span class="small">Same as billing address<br />';
      } else{
        $ssum = $_POST['address']['S']['address_name'] . ' ';
        $ssum .= $_POST['address']['S']['address_surname'] . '<br />';
        $ssum .= $_POST['address']['S']['address_line1'] . '<br />';
        $ssum .= $_POST['address']['S']['address_suburb'] . ' ';
        $ssum .= $_POST['address']['S']['address_state'] . ' ';
        $ssum .= $_POST['address']['S']['address_postcode'] . '<br />';
        $ssum .= $_POST['address']['S']['address_telephone'] . '<br />';
      }
      if(!empty($_SESSION['address']['comments'])){
        $ssum .= 'Shipping instructions: ' . $_SESSION['address']['comments'] . '<br />';
      } else{
        $ssum .= 'No shipping instructions <br />';
      }
      
      echo json_encode(array(
          'response' => true, 
          'billing' => $bsum, 
          'shipping' => $ssum, 
          'comments' => $_SESSION['address']['comments'] 
      ));
      die();
    
    case 'getShippingFees':
      $_SESSION['address'] = $_POST['address'];
      
      $ship_obj = new ShippingClass();
      $cart_obj = new cart($_SESSION['user']['public']['id']);
      
      $methods = $ship_obj->getShippingMethods($cart_obj->NumberOfProductsOnCart());
      
      echo json_encode(array(
          'shippingMethods' => $methods, 
          'billing' => $_POST['address']['B'], 
          'shipping' => $_POST['address']['S'], 
          'same' => $_POST['address']['same_address'] 
      ));
      die();
    
    case 'placeOrder':
      $cart_obj = new cart($_SESSION['user']['public']['id']);
      $order_cartId = $cart_obj->cart_id;
      $orderNumber = $order_cartId . '-' . date("is");
      
      if(!empty($_SESSION['address']['B']) && !empty($cart_obj->NumberOfProductsOnCart())){
        $billID = 0;
        $shipID = 0;
        
        //Indicator for creating new MAF member
        $createMAF = false;
        
        //Indicator for creating guest user
        $createGuest = false;
        
        //Selected payment method
        $paymentMethod = 'Credit card';
        
        $ship_obj = new ShippingClass();
        $postage = $ship_obj->getPostageByAddressId($shipID);
        $methods = $postage['postage_name'];
        $shippingFee = floatval($postage['postage_price']);
        // $methods = $ship_obj->getShippingMethods($cart_obj->NumberOfProductsOnCart());
        // $shippingFee = floatval($methods["{$_POST['payment']['payment_shipping_method']}"]);
        
        $totals = $cart_obj->CalculateTotal();
        $chargedAmount = $totals['total'] + $shippingFee;
        $gst = round(($totals['GST_Taxable'] + $shippingFee) / 11, 2);
        $params = array(
            'payment_user_id' => (empty($_SESSION['user']['public']['id']) ? 0 : $_SESSION['user']['public']['id']),
            'payment_billing_address_id' => $billID,
            'payment_shipping_address_id' => $shipID,
            'payment_status' => 'A', 
            'payment_transaction_no' => $orderNumber, 
            'payment_cart_id' => $order_cartId, 
            'payment_subtotal' => $totals['subtotal'], 
            'payment_discount' => $totals['discount'], 
            'payment_shipping_fee' => $shippingFee, 
            'payment_shipping_method' => $methods, 
            'payment_shipping_comments' => $_SESSION['address']['comments'], 
            'payment_payee_name' => $_POST['cc']['name'], 
            'payment_charged_amount' => $chargedAmount, 
            'payment_gst' => $gst, 
            'payment_method' => $paymentMethod 
        );
        
        require_once 'includes/classes/PayWay.php';
        $pay_obj = new PayWay();
        $response = false;
        
        $paymentId = $pay_obj->StorePaymentRecord($params);
        
        /*
         * $CCdata = array('amount'=>$chargedAmount);
         * if(!empty($_POST['cc'])){
         * $CCdata = array_merge($CCdata, $_POST['cc']);
         * }
         * $pay_obj->PreparePayment($CCdata);
         *
         * try{
         * $response = $pay_obj->Submit();
         * $paymentId = $paypalObj->GetPaymentId();
         * }catch(Exception $e){
         * if ($error_msg = $pay_obj->GetErrorMessage()) {
         * $_SESSION['error'] = $error_msg;
         * } else {
         * $_SESSION['error'] = 'Payment failed (on submit). Verify information and try again. ';
         * }
         * header('Location: '.$_SERVER['HTTP_REFERER'].'#form-error');
         * exit;
         * }
         */
        $response = true;
        if($response){
          // PAYMENT SUCCESS
          $cart_obj->CloseCart();
          $pay_obj->SetOrderStatus($paymentId);
          $_SESSION['orderNumber'] = $orderNumber;
          
          
          
          $userArr = $_SESSION['user']['public'];
          
          $user_obj = new UserClass();
          
          //NEW USER          
          if(empty($userArr) && $createMAF){//MAF - CREATE NEW MEMBER VIA API 
          
            
            
          }elseif(empty($userArr) && $createGuest){ // Create guest user when empty
            $guestArr = array(
                "gname" => $_POST['gname'],
                "surname" => $_POST['surname'],
                "email" => $_POST['email']
            );
            $userArr = $user_obj->CreateGuest($guestArr);
          
            //CHANGE USER_ID ONLY FOR MAF!!!!
            $userArr['id'] = $userArr['id'] * -1;
          
            //SET THE CART_USER_ID
            $cart_obj->UpdateUserIdOfClosedCart($order_cartId, $userArr['id']);
          }
          
          
          
          if($_SESSION['address']['wantpromo'] && FALSE){ // !!!! DISABLED !!!!!!!!!!!!!!!!!!!
            $promo = 1;
            try{
              require_once 'includes/createsend/csrest_subscribers.php';
              $wrap = new CS_REST_Subscribers('', '060d24d9003a77b06b95e7c47691975b'); // !!!! UPDATE CREATESEND LIST CODE !!!!!
              $cs_result = $wrap->add(array(
                  'EmailAddress' => $values['email'],
                  'Name' => $values['gname'] . ' ' . $values['surname'],
                  'CustomFields' => array(),
                  "Resubscribe" => "true"
              ));
            }
            catch(Exception $e){}
          }
         
          
          // SAVE BILLING AND SHIPPING ADDRESS
          $billID = $user_obj->InsertNewAddress(array_merge(array(
              'address_user_id' => $userArr['id']
          ), $_SESSION['address']['B']));
          $shipID = $billID;
          
          if(empty($_SESSION['address']['same_address'])){
            $shipID = $user_obj->InsertNewAddress(array_merge(array(
                'address_user_id' => $userArr['id']
            ), $_SESSION['address']['S']));
          }
          
          $params = array(
              'payment_user_id' => $userArr['id'],
              'payment_billing_address_id' => $billID,
              'payment_shipping_address_id' => $shipID
          );
          $pay_obj->SetUserAddressIds($params);
          
          try{
            // SEND CONFIRMATION EMAIL
            $billing = $user_obj->GetAddress($billID);
            $SMARTY->assign('billing', $billing);
            $shipping = $user_obj->GetAddress($shipID);
            $SMARTY->assign('shipping', $shipping);
            $order = $cart_obj->GetDataCart($order_cartId);
            $SMARTY->assign('order', $order);
            $payment = $pay_obj->GetPaymentRecord($paymentId);
            $SMARTY->assign('payment', $payment);
            $orderItems = $cart_obj->GetDataProductsOnCart($order_cartId);
            $SMARTY->assign('orderItems', $orderItems);
            $SMARTY->assign('DOMAIN', "http://" . $GLOBALS['HTTP_HOST']);
            $COMP = json_encode($CONFIG->company);
            $SMARTY->assign('COMPANY', json_decode($COMP, TRUE));
            
            // COMMMENTED UNTIL GO LIVE TO PREVENT STORES GETTING TESTING EMAILS
            /*
             * $bcc = $res[0]['location_bcc_recipient'];
             * $to = empty($res[0]['location_order_recipient'])?"online@them.com.au":$res[0]['location_order_recipient'];
             */
            $to = $_SESSION['address']['B']['address_email'];
            // $bcc = 'apolo@them.com.au';
            $from = (string)$CONFIG->company->name;
            $fromEmail = 'noreply@' . str_replace("www.", "", $GLOBALS['HTTP_HOST']);
            $subject = 'Confirmation of your order';
            $body = $SMARTY->fetch('email/order-confirmation.tpl');
            if($mailID = sendMail($to, $from, $fromEmail, $subject, $body, $bcc)){
              $pay_obj->SetInvoiceEmail($paymentId, $mailID);
            }
          }
          catch(Exception $e){
            die($e);
          }
          
          // SET GOOGLE ANALYTICS - ECOMMERCE
          if(!empty($GA_ID)){
            sendGAEnEcCheckoutOptions($GA_ID, $paymentMethod, '2');
            
            $totalsGA = array(
                'id' => $order_cartId, 
                'total' => $chargedAmount, 
                'tax' => $gst, 
                'shipping' => $shippingFee, 
                'coupon' => $order['cart_discount_code'] 
            );
            $productsGA = $cart_obj->getCartitemsByCartId_GA($order_cartId);
            sendGAEnEcPurchase($GA_ID, $totalsGA, $productsGA);
          }
          
          // SET USED DISCOUNT CODE
          if($order['cart_discount_code']){
            $cart_obj->SetUsedDiscountCode($order['cart_discount_code']);
            $discountData = $cart_obj->GetDiscountData($order['cart_discount_code']);
            if($discountData['discount_unlimited_use'] == '0'){
              try{
                // SEND NOTIFICATION EMAIL
                $SMARTY->assign('user', $userArr);
                $SMARTY->assign('discount', $discountData);
                $buffer = $SMARTY->fetch('email-discount.tpl');
                $to = "apolo@them.com.au";
                $bcc = "";
                $from = str_replace("www.", "", $GLOBALS['HTTP_HOST']);
                $fromEmail = 'noreply@' . str_replace("www.", "", $GLOBALS['HTTP_HOST']);
                $subject = 'A discount code has been used.';
                $body = $buffer;
                $mailID = sendMail($to, $from, $fromEmail, $subject, $body, $bcc);
              }
              catch(Exception $e){}
            }
          }
          
          // LOG OUT GUEST USER
          /* if($isGuest){
            unset($_SESSION['user']['public']);
          } */
          
          // OPEN NEW CART
          $cart_obj->CreateCart($_SESSION['user']['public']['id']);
          
          // REDIRECT TO THANK YOU PAGE
          header('Location: /thank-you-for-purchasing');
          die();
        } else{
          if($error_msg = $pay_obj->GetErrorMessage()){
            $_SESSION['error'] = $error_msg;
          } else{
            $_SESSION['error'] = 'Payment failed. Verify information and try again. ';
          }
        }
      } else{
        $_SESSION['error'] = 'Database Connection Error. Please try again, otherwise contact us by phone.';
      }
      
      $_SESSION['post'] = $_POST;
      header('Location: ' . $_SERVER['HTTP_REFERER'] . '#form-error');
      die();
      
      
    case 'quickcheckout':
      $_SESSION['error'] = '';
      $_SESSION['post'] = $_POST;
      
      $cart_obj = new cart($_SESSION['user']['public']['id']);
      $order_cartId = $cart_obj->cart_id;
      $orderNumber = $order_cartId . '-' . date("is");
      
      $billID = 0;
      $isGiftCertificate = false;
      
      //Has mandatory field: credit cart details, product_object_id, variant_id
      if(empty($_POST['honeypot']) && !empty($_POST['timestamp']) && (time() - $_POST['timestamp']) > 3 && !empty($_POST['product_object_id']) && !empty($_POST['variant_id']) && !empty($_POST['cc'])  && !empty($cart_obj->NumberOfProductsOnCart())){
        $_SESSION['error'] = 'Database Connection Error. Please try again, otherwise contact us by phone.';
      }
      
      //Check existence of particular fields and init custom process 
      if($_POST['product_object_id'] == 213){ //Gift certificate
        $isGiftCertificate = true;
        $_SESSION['error'] = 'FAKE ERROR';
        
      }elseif($_POST['product_object_id'] == 217){ //Donation
        
      }else{
        
      }
      if(!empty($_SESSION['error'])){
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '#form-error');
        die();
      }
      
      //Selected payment method
      $paymentMethod = 'Credit card';
  
      $shippingFee = 0;
  
      $totals = $cart_obj->CalculateTotal();
      $chargedAmount = $totals['total'] + $shippingFee;
      $gst = round(($totals['GST_Taxable'] + $shippingFee) / 11, 2);
      $params = array(
          'payment_user_id' => (empty($_SESSION['user']['public']['id']) ? 0 : $_SESSION['user']['public']['id']),
          'payment_billing_address_id' => $billID,
          'payment_shipping_address_id' => $billID,
          'payment_status' => 'P',
          'payment_transaction_no' => $orderNumber,
          'payment_cart_id' => $order_cartId,
          'payment_subtotal' => $totals['subtotal'],
          'payment_discount' => $totals['discount'],
          'payment_shipping_fee' => $shippingFee,
          'payment_shipping_method' => '',
          'payment_shipping_comments' => '',
          'payment_payee_name' => $_POST['cc']['name'],
          'payment_charged_amount' => $chargedAmount,
          'payment_gst' => $gst,
          'payment_method' => $paymentMethod
      );
      
      require_once 'includes/classes/PayWay.php';
      $pay_obj = new PayWay();
      $response = false;
  
      $paymentId = $pay_obj->StorePaymentRecord($params);
      $CCdata = array(
          'amount' => $chargedAmount 
      );
      if(!empty($_POST['cc'])){
        $CCdata = array_merge($CCdata, $_POST['cc']);
      }
      $pay_obj->PreparePayment($CCdata);
      
      try{
        $response = $pay_obj->Submit();
        $paymentId = $paypalObj->GetPaymentId();
      }
      catch(Exception $e){
        if($error_msg = $pay_obj->GetErrorMessage()){
          $_SESSION['error'] = $error_msg;
        } else{
          $_SESSION['error'] = 'Payment failed (on submit). Please verify the payment details and try again. ';
        }
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '#form-error');
        die();
      }
      
      $response = true;
      if($response){
        // PAYMENT SUCCESS
        $cart_obj->CloseCart();
        $pay_obj->SetOrderStatus($paymentId);
        $_SESSION['orderNumber'] = $orderNumber;
  

        $user_obj = new UserClass();
        $userArr = $_SESSION['user']['public'];
        
        // Create guest user when empty
        if(empty($userArr)){
          $guestArr = array(
              "gname" => $_POST['gname'],
              "surname" => $_POST['surname'],
              "email" => $_POST['email']
          );
          $userArr = $user_obj->CreateGuest($guestArr);
        
          //CHANGE USER_ID ONLY FOR MAF!!!!
          $userArr['id'] = $userArr['id'] * -1;
          
          //SET THE CART_USER_ID
          $cart_obj->UpdateUserIdOfClosedCart($order_cartId, $userArr['id']);
        }
        
        $billingArr = array(
            "address_user_id" => $_POST['gname'],
            "address_name" => $_POST['surname'],
            "address_email" => $_POST['email']
        );
        $billID = $user_obj->InsertNewAddress($billingArr);
        
        $params = array(
            'payment_user_id' => $userArr['id'],
            'payment_billing_address_id' => $billID,
            'payment_shipping_address_id' => $billID
        );
        $pay_obj->SetUserAddressIds($params);
        
        try{
          // SEND CONFIRMATION EMAIL
          $order = $cart_obj->GetDataCart($order_cartId);
          $billing = $user_obj->GetAddress($billID);
          $SMARTY->assign('billing', $billing);
          $SMARTY->assign('shipping', $billing);
          $SMARTY->assign('order', $order);
          $payment = $pay_obj->GetPaymentRecord($paymentId);
          $SMARTY->assign('payment', $payment);
          $orderItems = $cart_obj->GetDataProductsOnCart($order_cartId);
          $SMARTY->assign('orderItems', $orderItems);
          $SMARTY->assign('DOMAIN', "http://" . $GLOBALS['HTTP_HOST']);
          $COMP = json_encode($CONFIG->company);
          $SMARTY->assign('COMPANY', json_decode($COMP, TRUE));
          
          $to = $BillingArr['address_email'];
          // $bcc = 'apolo@them.com.au';
          $from = (string)$CONFIG->company->name;
          $fromEmail = 'noreply@' . str_replace("www.", "", $GLOBALS['HTTP_HOST']);
          $subject = 'Confirmation of your order';
          $body = $SMARTY->fetch('email/order-confirmation.tpl');
          if($mailID = sendMail($to, $from, $fromEmail, $subject, $body, $bcc)){
            $pay_obj->SetInvoiceEmail($paymentId, $mailID);
          }
        }
        catch(Exception $e){}
  
        // SET GOOGLE ANALYTICS - ECOMMERCE
        if(!empty($GA_ID)){
          sendGAEnEcCheckoutOptions($GA_ID, $paymentMethod, '2');
          $totalsGA = array(
              'id' => $order_cartId,
              'total' => $chargedAmount,
              'tax' => $gst,
              'shipping' => $shippingFee,
              'coupon' => $order['cart_discount_code']
          );
          $productsGA = $cart_obj->getCartitemsByCartId_GA($order_cartId);
          sendGAEnEcPurchase($GA_ID, $totalsGA, $productsGA);
        }
  
        // OPEN NEW CART
        $cart_obj->CreateCart($_SESSION['user']['public']['id']);
  
        $_SESSION['post'] = '';
        
        // REDIRECT TO THANK YOU PAGE
        header('Location: /thank-you-for-purchasing');
        die();
      } else{
        if($error_msg = $pay_obj->GetErrorMessage()){
          $_SESSION['error'] = $error_msg;
        } else{
          $_SESSION['error'] = 'Payment failed. Verify information and try again. ';
        }
      }
    
    
      
      header('Location: ' . $_SERVER['HTTP_REFERER'] . '#form-error');
      die();
  }
  die('@');
} else{
  header('Location: /404');
  die();
}