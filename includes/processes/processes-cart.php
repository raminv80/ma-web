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
      if(!empty($_POST['postcodefield'])){ // !!!!! MUST BE UPDATED !!!!!
        /* $_SESSION['smarty']['selectedShippingPostcode'] = $_POST['postcodefield'];
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
        } */
      }
      $cart_obj = new cart($_SESSION['user']['public']['id']);
      $shippable = $cart_obj->ShippableCartitems();
      $shipping_obj = new ShippingClass(count($shippable), $cart_obj->GetCurrentFreeShippingDiscountName());
      $methods = $shipping_obj->getShippingMethods();
      $_SESSION['shipping']['selectedMethod'] = '';
      $selectedMethod = unclean($_POST['selectedMethod']);
      if(array_key_exists($selectedMethod, $methods)){
        $_SESSION['shipping']['selectedMethod'] = $selectedMethod; 
        if(!empty($GA_ID)){
          $productsGA = $cart_obj->getCartitemsByCartId_GA();
          sendGAEnEcCheckoutStep($GA_ID, $_SESSION['shipping']['selectedMethod'], $productsGA);
        }
        header('Location: /checkout');
        die();
      }
      $_SESSION['error'] = 'Invalid shipping method.';
      header('Location: ' . $_SERVER['HTTP_REFERER'] . '#form-error');
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
      
      $loggedIn = (empty($_SESSION['user']['public']['id']) && empty($_SESSION['user']['new_user'])) ? false : true;
      
      $shippable = $cart_obj->ShippableCartitems();
      $shipping_obj = new ShippingClass(count($shippable), $cart_obj->GetCurrentFreeShippingDiscountName());
      $methods = $shipping_obj->getShippingMethods();
      $shippingValid = (!empty($_SESSION['shipping']['selectedMethod']) && array_key_exists($_SESSION['shipping']['selectedMethod'], $methods))? true : false;
      /* //Shipping based on postcode  
       $ship_obj = new ShippingClass();
       $postage = $ship_obj->getPostageByAddressId($shipID);
       $methods = $postage['postage_name'];
       $shippingFee = floatval($postage['postage_price']); */
      
      if($loggedIn && !empty($_SESSION['address']['B']) && $shippingValid && !empty($cart_obj->NumberOfProductsOnCart())){
        $billID = 0;
        $shipID = 0;
        
        //Selected payment method
        $paymentMethod = 'Credit card';
        
        //Shipping
        $shippingFee = floatval($methods[$_SESSION['shipping']['selectedMethod']]);
        
        $totals = $cart_obj->CalculateTotal();
        $chargedAmount = $totals['total'] + $shippingFee;
        $gst = round(($totals['GST_Taxable']) / 11, 2);
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
            'payment_shipping_method' => $_SESSION['shipping']['selectedMethod'], 
            'payment_shipping_comments' => $_SESSION['address']['comments'], 
            'payment_payee_name' => $_POST['cc']['name'], 
            'payment_charged_amount' => $chargedAmount, 
            'payment_gst' => $gst, 
            'payment_method' => $paymentMethod 
        );
        
        //require_once 'includes/classes/Qvalent_Rest_PayWayAPI.php';
        require_once 'includes/classes/PayWay.php';
        $pay_obj = new PayWay();
        $response = false;
        
        $paymentId = $pay_obj->StorePaymentRecord($params);
        
       /*  
          $CCdata = array('amount'=>$chargedAmount);
          if(!empty($_POST['cc'])){
          $CCdata = array_merge($CCdata, $_POST['cc']);
          }
          $pay_obj->PreparePayment($CCdata);
         
          try{
          $response = $pay_obj->Submit();
          $paymentId = $paypalObj->GetPaymentId();
          }catch(Exception $e){
          if ($error_msg = $pay_obj->GetErrorMessage()) {
         $_SESSION['error'] = $error_msg;
          } else {
          $_SESSION['error'] = 'Payment failed (on submit). Verify information and try again. ';
          }
          header('Location: '.$_SERVER['HTTP_REFERER'].'#form-error');
          exit;
          }
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
          $newMAFMember = $cart_obj->HasMAFProducts();
          //MAF - Create new member
          if(empty($userArr) && $newMAFMember){
            $MAFMemberId = $user_obj->CreateMember($_SESSION['user']['new_user']);
            if(empty($MAFMemberId)){
              //create guest user when failed
              $newMAFMember = false;
            }else{
              saveInLog('member-create', 'external', $MAFMemberId, $_SESSION['user']['new_user']['state']);
              $userArr = array(
                  "id" => $MAFMemberId,
                  "gname" => $_SESSION['user']['new_user']['gname'],
                  "surname" => $_SESSION['user']['new_user']['surname'],
                  "email" => $_SESSION['user']['new_user']['email']
              );
            }
          }
          //Create guest user
          if(empty($userArr) && !$newMAFMember){ 
            $userArr = $user_obj->CreateGuest($_SESSION['user']['new_user']);
          
            //CHANGE USER_ID ONLY FOR MAF!!!!
            $userArr['id'] = $userArr['id'] * -1;
          }
          
          //SET THE CART_USER_ID
          $cart_obj->UpdateUserIdOfClosedCart($order_cartId, $userArr['id']);
          
          
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
          
          $SMARTY->unloadFilter('output', 'trimwhitespace');
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
          $cart_obj->CreateCart();
          
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
      
      //Check existence of particular fields and init custom process
      $isGiftCertificate = false;
      if($_POST['product_id'] == 213){ //Gift certificate
        $isGiftCertificate = true;
      
      }elseif($_POST['product_id'] == 217){ //Donation
      
      }else{
      
      }
      
      //Has mandatory field: credit cart details, product_object_id, variant_id
      if(!empty($_POST['honeypot']) || empty($_POST['timestamp']) || (time() - $_POST['timestamp']) < 4 || empty($_POST['product_id']) || empty($_POST['variant_id']) || !empty($_POST['cc'])){
        $_SESSION['error'] = 'Your session has expired. Please try again, otherwise contact us by phone.';
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '#form-error-1');
        die();
      }
      
      //Clear shopping cart
      $cart_obj = new cart($_SESSION['user']['public']['id']);
      $cart_obj->DeleteCart();
      $cart_obj->CreateCart();
      
      $cart_obj->AddToCart($_POST['product_id'], $_POST['attr'], $_POST['price'], 1, null, $_POST['variant_id']);
      $itemsCount = $cart_obj->NumberOfProductsOnCart();
      if(empty($itemsCount)){
        $_SESSION['error'] = 'The selected item cannot be added to the shopping cart';
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '#form-error');
        die();
      }
      
      $order_cartId = $cart_obj->cart_id;
      $orderNumber = $order_cartId . '-' . date("is");
      $billID = 0;
      
      $billingArr = array(
          "address_user_id" => 0,
          "address_name" => $_POST['name'],
          "address_email" => $_POST['email']
      );
      
      //Selected payment method
      $paymentMethod = 'Credit card';
  
      $shippingFee = 0;
  
      $totals = $cart_obj->CalculateTotal();
      $chargedAmount = $totals['total'] + $shippingFee;
      $gst = round(($totals['GST_Taxable']) / 11, 2);
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
      /* if(!empty($_POST['cc'])){
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
      } */
      
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
              "gname" => $billingArr['address_name'],
              "surname" => '',
              "email" => $billingArr['address_email']
          );
          $userArr = $user_obj->CreateGuest($guestArr);
        
          //CHANGE USER_ID ONLY FOR MAF!!!!
          $userArr['id'] = $userArr['id'] * -1;
          
          //SET THE CART_USER_ID
          $cart_obj->UpdateUserIdOfClosedCart($order_cartId, $userArr['id']);
        }
        $billingArr['address_user_id'] = $userArr['id'];
        
        $billID = $user_obj->InsertNewAddress($billingArr);
        
        $params = array(
            'payment_user_id' => $userArr['id'],
            'payment_billing_address_id' => $billID,
            'payment_shipping_address_id' => $billID
        );
        $pay_obj->SetUserAddressIds($params);
        
        $SMARTY->unloadFilter('output', 'trimwhitespace');
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
          
          $to = $billingArr['address_email'];
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
        
        if($isGiftCertificate){
          // CREATE GIFT CERTIFICATE
          
          
          try{
            // SEND GIFT CERTIFICATE TO RECIPIENT
            $to = $BillingArr['address_email'];
            // $bcc = 'apolo@them.com.au';
            $from = (string)$CONFIG->company->name;
            $fromEmail = 'noreply@' . str_replace("www.", "", $GLOBALS['HTTP_HOST']);
            $subject = 'Confirmation of your order';
            $body = $SMARTY->fetch('email/gift-certificate.tpl');
            if($mailID = sendMail($to, $from, $fromEmail, $subject, $body, $bcc)){
              $pay_obj->SetInvoiceEmail($paymentId, $mailID);
            }
          }
          catch(Exception $e){}
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
  
        // OPEN NEW CART
        $cart_obj->CreateCart();
  
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