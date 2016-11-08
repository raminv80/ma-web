<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;
$referer = parse_url($_SERVER['HTTP_REFERER']);
if($referer['host'] == $GLOBALS['HTTP_HOST']){
  switch($_POST['action']){
    case 'SaveFiltersInSession':
      if(!empty($_POST['listing_object_id'])){
        $_SESSION['tempvars']['filters'][$_POST['listing_object_id']] = $_POST['filters'];
        $success = true;
        $error = null;
        if(!empty($GA_ID)){
          sendGAEvent($GA_ID, 'category-filters', $_POST['listing_object_id'], $_POST['filters']);
        }
      }
      echo json_encode(array(
          'success' => $success,
          'error' => $error
      ));
      die();
      
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
        $popoverShopCart = $SMARTY->fetch('templates/ec_popover-shopping-cart.tpl');
        if(!empty($GA_ID)){
          $productGA = $cart_obj->getProductInfo_GA($_POST['product_id'], $_POST['attr'], $_POST['quantity'], 0, null, 0, $_POST['variant_id']);
          sendGAEnEcAction($GA_ID, 'add', $productGA);
        }
        
      }catch(exceptionCart $e){
        $error = $e->getMessage();
      }
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
      $popoverShopCart = $SMARTY->fetch('templates/ec_popover-shopping-cart.tpl');
      if(!empty($GA_ID)){
        sendGAEnEcAction($GA_ID, 'remove', $productGA);
      }
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
      $popoverShopCart = $SMARTY->fetch('templates/ec_popover-shopping-cart.tpl');
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
    
    case 'addProductWishList':
      $url = '/login';
      $success = null;
      if(!empty($GA_ID)){
        sendGAEvent($GA_ID, 'wish-list', 'attempt-add', $_POST['product_object_id']);
      }
      if(!empty($_SESSION['user']['public']['id'])){
        $url = null;
        $cart_obj = new cart($_SESSION['user']['public']['id']);
        try{
          if($cart_obj->AddProductWishList($_POST['product_object_id'])){
            $error = null;
            $success = true;
            if(!empty($GA_ID)){
              sendGAEvent($GA_ID, 'wish-list', 'added', $_POST['product_object_id']);
            }
          }
        }catch(exceptionCart $e){
          $error = $e->getMessage();
        }
      }
      echo json_encode(array(
          'error' => $error, 
          'success' => $success, 
          'url' => $url 
      ));
      die();
    
    case 'deleteProductWishList':
      $url = '/login';
      $success = null;
      if(!empty($GA_ID)){
        sendGAEvent($GA_ID, 'wish-list', 'attempt-remove', $_POST['product_object_id']);
      }
      if(!empty($_SESSION['user']['public']['id'])){
        $url = null;
        $cart_obj = new cart($_SESSION['user']['public']['id']);
        try{
          if($cart_obj->DeleteProductWishList($_POST['product_object_id'])){
            $error = null;
            $success = true;
            if(!empty($GA_ID)){
              sendGAEvent($GA_ID, 'wish-list', 'removed', $_POST['product_object_id']);
            }
          }
        }catch(exceptionCart $e){
          $error = $e->getMessage();
        }
      }
      echo json_encode(array(
          'error' => $error, 
          'success' => $success, 
          'url' => $url 
      ));
      die();
      
    case 'sendWishList':
      $url = '/login';
      $success = null;
      if(!empty($_SESSION['user']['public']['id'])){
        $url = null;
        $cart_obj = new cart($_SESSION['user']['public']['id']);
        try{
          $error = 'Your wish list is empty.';
          if($products = $cart_obj->GetWishListWithProds()){
            if(!empty($GA_ID)){
              sendGAEvent($GA_ID, 'wish-list', 'sent', $_SESSION['user']['public']['id']);
            }
            try{
              //Send notification
              $SMARTY->unloadFilter('output', 'trimwhitespace');
              $SMARTY->assign('products', $products);
              $SMARTY->assign('DOMAIN', "http://" . $_SERVER['HTTP_HOST']);
              $subject = 'Your MedicAlert wish list';
              $fromEmail = (string) $CONFIG->company->email_from;
              $to = $_SESSION['user']['public']['email'];
              $SMARTY->assign('user_name', $_SESSION['user']['public']['gname']);
              $COMP = json_encode($CONFIG->company);
              $SMARTY->assign('COMPANY', json_decode($COMP,TRUE));
              $from = (string) $CONFIG->company->name;
              $body = $SMARTY->fetch("email/wish-list.tpl");
              if($sent = sendMail($to, $from, $fromEmail, $subject, $body)){
                $error = null;
                $success = true;
              }
            }catch(Exception $e){
              $error = 'Unexpected error: your wish list cannot be sent.';
            }
            
          }
        }catch(exceptionCart $e){
          $error = $e->getMessage();
        }
      }
      echo json_encode(array(
          'error' => $error,
          'success' => $success,
          'url' => $url
      ));
      die();
    
    case 'applyDiscount':
      $cart_obj = new cart($_SESSION['user']['public']['id']);
      $res = $cart_obj->ApplyDiscountCode($_POST['discount_code']);
      if($res['error']){
        $_SESSION['notice'] = $res['error'];
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
        header('Location: /checkout');
        die();
      }
      $_SESSION['error'] = 'Invalid shipping method.';
      header('Location: ' . $_SERVER['HTTP_REFERER'] . '#form-error');
      die();
      
    
    case 'checkout2':
      $_SESSION['postageID'] = $_POST['postageID'];
      $_SESSION['address'] = $_POST['address'];
      $_SESSION['address']['B']['address_user_id'] = empty($_SESSION['user']['public']['id']) ? 0 : $_SESSION['user']['public']['id'];
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
      
      if(!empty($_POST['address']['wantpromo'])){
        try{
          require_once 'includes/createsend/csrest_subscribers.php';
          $wrap = new CS_REST_Subscribers('d5de22e15e29de7ada93174dbd839cd3', '060d24d9003a77b06b95e7c47691975b'); 
          $cs_result = $wrap->add(array(
              'EmailAddress' => $_POST['address']['B']['address_email'],
              'Name' => $_POST['address']['B']['address_name'],
              'CustomFields' => array(),
              "Resubscribe" => "true"
          ));
        }
        catch(Exception $e){}
      }
      
      if(!empty($GA_ID)){
        $cart_obj = new cart($_SESSION['user']['public']['id']);
        $productsGA = $cart_obj->getCartitemsByCartId_GA();
        sendGAEnEcCheckoutStep($GA_ID, '4', 'Payment', $productsGA);
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
      
      $user_obj = new UserClass();
      if(!empty($_SESSION['user']['public']['id'])){
        $loginMAFCheck = $user_obj->setSessionVars($_SESSION['user']['public']['maf']['token']);
        if(!$loginMAFCheck){
          $_SESSION['user']['public'] = null;
          header("Location: /login");
          die();
        }
      }
      
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
        
        //MAF - CHECK FOR DONATION
        $hasDonation = $cart_obj->hasProductInCart(217);
        
        $totals = $cart_obj->CalculateTotal();
        $chargedAmount = $totals['total'] + $shippingFee;
        $gst = $totals['GST'];
        $params = array(
            'payment_user_id' => (empty($_SESSION['user']['public']['id']) ? 0 : $_SESSION['user']['public']['id']),
            'payment_billing_address_id' => $billID,
            'payment_shipping_address_id' => $shipID,
            'payment_status' => 'P', 
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
        
        if(!empty($GA_ID)){
          sendGAEnEcCheckoutOptions($GA_ID, '4', $paymentMethod);
        }
        
        //require_once 'includes/classes/PayWay.php';
        //$pay_obj = new PayWay();
        //$paymentId = $pay_obj->StorePaymentRecord($params);
        
        $bankSettingsArr = array(
            'initPayment' => $params,
            'settings' => $CONFIG->payment_gateway->payway,
            'address' => $_SESSION['address']['B']
        );
        
        require_once 'includes/classes/Qvalent_Rest_PayWayAPI.php';
        $pay_obj = new Qvalent_REST_PayWayAPI($bankSettingsArr);
        $response = false;
        
        $CCdata = array(
            'amount' => $chargedAmount
        );
        if(!empty($_POST['cc'])){
          $CCdata = array_merge($CCdata, $_POST['cc']);
        }
        $pay_obj->PreparePayment($CCdata);
        
        try{
          $response = $pay_obj->Submit();
          $paymentId = $pay_obj->GetPaymentId();
        }
        catch(Exception $e){
          if($error_msg = $pay_obj->GetErrorMessage()){
            $_SESSION['error'] = $error_msg;
          } else{
            $_SESSION['error'] = 'Payment failed (on submit). Verify information and try again. '.$e;
          }
          header('Location: ' . $_SERVER['HTTP_REFERER'] . '#form-error');
          exit();
        }
          
        if($response){
          // PAYMENT SUCCESS
          
          //check if shopping cart has "MAF - Member service fee"
          $MAFSetActivePending = $cart_obj->hasProductInCart(225);
          
          $cart_obj->CloseCart();
          $pay_obj->SetOrderStatus($paymentId);
          $_SESSION['orderNumber'] = $orderNumber;
          
          //Init email details
          $SMARTY->unloadFilter('output', 'trimwhitespace');
          $SMARTY->assign('DOMAIN', "http://" . $GLOBALS['HTTP_HOST']);
          $COMP = json_encode($CONFIG->company);
          $SMARTY->assign('COMPANY', json_decode($COMP, TRUE));
          $from = (string)$CONFIG->company->name;
          $fromEmail = (string) $CONFIG->company->email_from;
          
          //Init user details
          $userArr = $_SESSION['user']['public'];
          
          //NEW USER          
          $hasMAFProd = $cart_obj->HasMAFProducts();
          //MAF - Create new member
          if(empty($userArr) && $hasMAFProd){
            $MAFMemberId = $user_obj->CreateMember($_SESSION['user']['new_user']);
            $user_obj->SetPaymentIdUserTemp($paymentId);
            if(empty($MAFMemberId)){
              //create guest user when failed
              $hasMAFProd = false;
              sendErrorMail('weberrors@them.com.au', $from, $fromEmail, 'Create member', "Member email:  {$_SESSION['user']['new_user']['email']} <br>". $user_obj->getErrorMsg());
            }else{
              saveInLog('member-create', 'external', $MAFMemberId, $_SESSION['user']['new_user']['state']);
              //Login MAF member
              if($user_obj->authenticate($MAFMemberId, $_SESSION['user']['new_user']['password'])){
                if($_SESSION['user']['public']['maf'] = $user_obj->getSessionVars()){
                  $_SESSION['user']['public']['id'] = $MAFMemberId;
                  $_SESSION['user']['public']['gname'] = $_SESSION['user']['public']['maf']['main']['user_firstname'];
                  $_SESSION['user']['public']['surname'] = $_SESSION['user']['public']['maf']['main']['user_lastname'];
                  $_SESSION['user']['public']['email'] = $_SESSION['user']['public']['maf']['main']['user_email'];
                  saveInLog('member-login', 'external', $_SESSION['user']['public']['id']);
                }
              }else{
                sendErrorMail('weberrors@them.com.au', $from, $fromEmail, 'Login after creation', "Member id:  {$MAFMemberId} <br>". $user_obj->getErrorMsg());
              }
              $userArr = array(
                  "id" => $MAFMemberId,
                  "gname" => $_SESSION['user']['new_user']['gname'],
                  "surname" => $_SESSION['user']['new_user']['surname'],
                  "email" => $_SESSION['user']['new_user']['email']
              );
              try{
                //Send welcome email
                $SMARTY->assign('user', $userArr);
                $to = $userArr['email'];
                $subject = 'MedicAlert Foundation Registration';
                $body = $SMARTY->fetch('email/welcome.tpl');
                sendMail($to, $from, $fromEmail, $subject, $body);
              }
              catch(Exception $e){}
              
              //Clean new user session variable
              $_SESSION['user']['new_user'] = null;
              
            }
          }
          //Create guest user
          if(empty($userArr) && !$hasMAFProd){ 
            $userArr = $user_obj->CreateGuest($_SESSION['user']['new_user']);
          
            //CHANGE USER_ID ONLY FOR MAF!!!!
            $userArr['id'] = $userArr['id'] * -1;
          }
          
          //SET THE CART_USER_ID
          $cart_obj->UpdateUserIdOfClosedCart($order_cartId, $userArr['id']);
          
          
          //SAVE BILLING AND SHIPPING ADDRESS
          $_SESSION['address']['B']['address_user_id'] = $userArr['id'];
          $billID = $user_obj->InsertNewAddress($_SESSION['address']['B']);
          $shipID = $billID;
          
          if(empty($_SESSION['address']['same_address'])){
            $_SESSION['address']['S']['address_user_id'] = $userArr['id'];
            $shipID = $user_obj->InsertNewAddress($_SESSION['address']['S']);
          }
          
          $params = array(
              'payment_user_id' => $userArr['id'],
              'payment_billing_address_id' => $billID,
              'payment_shipping_address_id' => $shipID
          );
          $pay_obj->SetUserAddressIds($params);
          
          try{
            // SEND CONFIRMATION EMAIL
            $SMARTY->assign('hasMAFProd', $hasMAFProd);
            $SMARTY->assign('hasDonation', $hasDonation);
            $billing = $user_obj->GetAddress($billID);
            $SMARTY->assign('billing', $billing);
            $shipping = $user_obj->GetAddress($shipID);
            $SMARTY->assign('shipping', $shipping);
            $order = $cart_obj->GetDataCart($order_cartId);
            $SMARTY->assign('order', $order);
            $discount = $cart_obj->GetDiscountData($order['cart_discount_code']);
            $SMARTY->assign('discount', $discount);
            $payment = $pay_obj->GetPaymentRecord($paymentId);
            $SMARTY->assign('payment', $payment);
            $orderItems = $cart_obj->GetDataProductsOnCart($order_cartId);
            $SMARTY->assign('orderItems', $orderItems);
            
            $to = $_SESSION['address']['B']['address_email'];
            $bcc = (string) $CONFIG->company->email_orders;
            $subject = 'Payment | Order confirmation';
            $body = $SMARTY->fetch('email/order-confirmation.tpl');
            if($mailID = sendMail($to, $from, $fromEmail, $subject, $body, $bcc)){
              $pay_obj->SetInvoiceEmail($paymentId, $mailID);
            }
          }
          catch(Exception $e){}
          
          if(!empty($GA_ID) && $hasDonation){
            sendGAEvent($GA_ID, 'donation', 'success', $_SESSION['user']['public']['id']);
          }
          
          //PROCESS PENDING UPDATES
          if(!empty($_SESSION['user']['public']['pending_update'])){
            $detailsArr = $_SESSION['user']['public']['pending_update'];
            //Pre-process array fields
            if(!empty($detailsArr['conditions'])){
              $detailsArr['conditions'] = $user_obj->formatProfileArrayField($detailsArr['conditions'], $_SESSION['user']['public']['maf']['update']['conditions']);
            }
            if(!empty($detailsArr['allergies'])){
              $detailsArr['allergies'] = $user_obj->formatProfileArrayField($detailsArr['allergies'], $_SESSION['user']['public']['maf']['update']['allergies']);
            }
            if(!empty($detailsArr['medications'])){
              $detailsArr['medications'] = $user_obj->formatProfileArrayField($detailsArr['medications'], $_SESSION['user']['public']['maf']['update']['medications']);
            }
          
            //Update profile
            if($user_obj->processUpdate($_SESSION['user']['public']['maf']['token'], $detailsArr)){
              saveInLog('member-profile-update', 'external', $_SESSION['user']['public']['id']);
              try{
                //Send notification
                $SMARTY->unloadFilter('output', 'trimwhitespace');
                $SMARTY->assign('DOMAIN', "http://" . $_SERVER['HTTP_HOST']);
                $subject = 'Update Member Profile';
                $fromEmail = (string) $CONFIG->company->email_from;
                $to = $detailsArr['user_email'];
                $SMARTY->assign('user_name', $detailsArr['user_firstname']);
                $COMP = json_encode($CONFIG->company);
                $SMARTY->assign('COMPANY', json_decode($COMP,TRUE));
                $from = (string) $CONFIG->company->name;
                $body = $SMARTY->fetch("email/profile-update.tpl");
                $sent = sendMail($to, $from, $fromEmail, $subject, $body);
              }catch(Exception $e){}
              
              try{
                //Create survey
                require_once 'includes/classes/survey-class.php';
                $surveyObj = new Survey();
                $surveyObj->CreateSurvey($_SESSION['user']['public']['id'], $_SESSION['user']['public']['email'], 2);
              }catch(Exception $e){}
              
            }else{
              sendErrorMail('weberrors@them.com.au', $from, $fromEmail, 'Update profile after payment (1)', "Member id:  {$_SESSION['user']['public']['id']} <br>". $user_obj->getErrorMsg());
            }
            $_SESSION['user']['public']['pending_update'] = null;

          //NO PROFILE UPDATE
          }else{
            try{
              //Create survey
              require_once 'includes/classes/survey-class.php';
              $surveyObj = new Survey();
              $surveyObj->CreateSurvey($_SESSION['user']['public']['id'], $_SESSION['user']['public']['email'], 1);
            }catch(Exception $e){}  
          }
          
          //PROCESS AUTO-RENEWAL
          $setAutoRenewal = false;
          if(!empty($_POST['autorenewal'])){
            if(!empty($GA_ID)){
              sendGAEvent($GA_ID, 'autorenewal-checkout', 'attempt', $_SESSION['user']['public']['id']);
            }
            $bankSettingsArr = array(
                'settings' => $CONFIG->payment_gateway->payway,
                'address' => $billing
            );
            $autorenewObj = new Qvalent_REST_PayWayAPI($bankSettingsArr);
            if(!empty($_POST['autopayment']) && $_POST['autopayment'] == 'dd'){
              $autorenewArr = $_POST['auto-dd'];
            }else{
              $autorenewArr = $_POST['auto-cc'];
            }
            $autorenewArr['method'] = $_POST['autopayment'];
            $autorenewObj->PreparePayment($autorenewArr);
            if($autorenewObj->CreateCustomerOnly()){
              $customerArr = $autorenewObj->GetBankCustomerRecord();
              $customerArr['user_id'] = $userArr['id'];
              $autorenewObj->StoreAutoRenew($customerArr);
              $setAutoRenewal = true;
              //Register for auto-renewal and change member status to "active pending"
              if(!$user_obj->setAutoRenewal($_SESSION['user']['public']['maf']['token'], $customerArr)){
                sendErrorMail('weberrors@them.com.au', $from, $fromEmail, 'Set auto-renewal (1)', "Member id:  {$userArr['id']} <br>". $user_obj->getErrorMsg());
                $setAutoRenewal = false;
              }
              if(!empty($GA_ID)){
                sendGAEvent($GA_ID, 'autorenewal-checkout', 'success', $_SESSION['user']['public']['id']);
              }
              try{
                //Send auto-renewal email
                $SMARTY->assign('user', $userArr);
                $to = $userArr['email'];
                $subject = 'Auto-renewal Subscription';
                $body = $SMARTY->fetch('email/autorenewal.tpl');
                sendMail($to, $from, $fromEmail, $subject, $body, $bcc);
              }
              catch(Exception $e){}
            }else{
              sendErrorMail('weberrors@them.com.au', $from, $fromEmail, 'Register for auto-renewal (1)', "Member id:  {$userArr['id']} <br>".print_r($billing, true).'<br>'. $autorenewObj->GetErrorMessage());
            }
          }
          
          //Change member status to "active pending"
          if($MAFSetActivePending && !empty($_SESSION['user']['public']['maf'] && !$setAutoRenewal)){
            if(!$user_obj->setPendingStatus($_SESSION['user']['public']['maf']['token'], $_SESSION['user']['public']['id'])){
              sendErrorMail('weberrors@them.com.au', $from, $fromEmail, 'Set active-pending (1)', "Member id:  {$userArr['id']} <br>". $user_obj->getErrorMsg());
            }
          }
          
          
          // SET GOOGLE ANALYTICS - ECOMMERCE
          if(!empty($GA_ID)){
            $productsGA = $cart_obj->getCartitemsByCartId_GA($order_cartId);
            sendGAEnEcCheckoutStep($GA_ID, '5', 'Checkout complete', $productsGA);
            $totalsGA = array(
                'id' => $orderNumber, 
                'total' => $chargedAmount, 
                'tax' => $gst, 
                'shipping' => $shippingFee, 
                'coupon' => $order['cart_discount_code'] 
            );
            sendGAEnEcPurchase($GA_ID, $totalsGA, $productsGA);
            
            try{
              $cartitemProdUIDsArr = array();
              $cartitemProdUIDs = '';
              foreach($orderItems as $oi){
                $cartitemProdUIDsArr[] = empty($oi['variant_uid']) ? "{$oi['cartitem_product_id']}-{$oi['cartitem_variant_id']}" : $oi['variant_uid'];
              }
              $cartitemProdUIDs = implode(',', "'". $cartitemProdUIDsArr ."'");
            }catch(Exception $e){}
            $_SESSION['conversionTracking'] = "<script>
              var CartitemProdUIDs = [{$cartitemProdUIDs}];
              var PaymentAmount = '{$chargedAmount}';
              var OrderNumber = '{$orderNumber}';
              </script>";
          }
          
          // SET USED DISCOUNT CODE
          if($order['cart_discount_code']){
            try{
              include_once 'includes/classes/voucher-class.php';
              $voucherObj = new Voucher();
              $voucherObj->RedeemVoucher($order['cart_discount_code']);
              $cart_obj->SetUsedDiscountCode($order['cart_discount_code']);
              $discountData = $cart_obj->GetDiscountData($order['cart_discount_code']);
              if($discountData['discount_unlimited_use'] == '0'){
                // SEND NOTIFICATION EMAIL
                $SMARTY->assign('user', $userArr);
                $SMARTY->assign('discount', $discountData);
                $buffer = $SMARTY->fetch('email-discount.tpl');
                $to = "apolo@them.com.au";
                $bcc = "";
                $subject = 'A discount code has been used.';
                $body = $buffer;
                $mailID = sendMail($to, $from, $fromEmail, $subject, $body, $bcc);
              }
            }catch(Exception $e){}
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
      $missingFields = true;
      
      //Check existence of particular fields and init custom process
      $isGiftCertificate = false;
      if($_POST['product_id'] == 213){ //Gift certificate
        if($_POST['sendtime'] == 'now'){
          $sendDate = '';
        }elseif($_POST['sendtime'] == 'another-day' && !empty($_POST['sendday']) && validateDate($_POST['sendday'])){
          $sendDate = date_format(date_create_from_format('d/m/Y', $_POST['sendday']), 'Y-m-d');
        }else{
          $_SESSION['error'] = "Error: Invalid date of birth (DD/MM/YYYY).";
          header('Location: ' . $_SERVER['HTTP_REFERER'] . '#form-error');
          die();
        }
        
        if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['rname']) && !empty($_POST['remail'])){
          $missingFields = false;
          $isGiftCertificate = true;
          $thankyouPage = '/thank-you-for-purchasing-a-gift-certificate';
        }
      
      }elseif($_POST['product_id'] == 217){ //Donation
        if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['lastname']) && !empty($_POST['postcode'])){
          $missingFields = false;
          $hasDonation = true;
          $thankyouPage = '/thank-you-for-your-donation';
        }
      }else{
        $thankyouPage = '/thank-you-for-purchasing';
      }
      
      //Has mandatory field: credit cart details, product_object_id, variant_id
      if($missingFields || !empty($_POST['honeypot']) || empty($_POST['timestamp']) || (time() - $_POST['timestamp']) < 4 || empty($_POST['product_id']) || empty($_POST['variant_id']) || empty($_POST['cc'])){
        $_SESSION['error'] = 'Your session has expired. Please try again, otherwise contact us by phone.';
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '#form-error');
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
          "address_name" => $_POST['name'] ,
          "address_surname" => $_POST['lastname'],
          "address_email" => $_POST['email'],
          "address_postcode" => (empty($_POST['postcode']) ? null : ' '. $_POST['postcode'])
      );
      
      //Selected payment method
      $paymentMethod = 'Credit card';
  
      $shippingFee = 0;
  
      $totals = $cart_obj->CalculateTotal();
      $chargedAmount = $totals['total'] + $shippingFee;
      $gst = $totals['GST'];
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
      
      $bankSettingsArr = array(
          'initPayment' => $params,
          'settings' => $CONFIG->payment_gateway->payway,
          'address' => $billingArr
      );
      
      if(!empty($GA_ID)){
        $productsGA = $cart_obj->getCartitemsByCartId_GA();
        //sendGAEnEcCheckoutStep($GA_ID, '4', 'Payment', $productsGA);
        //sendGAEnEcCheckoutOptions($GA_ID, '4', $paymentMethod);
      }
      
      require_once 'includes/classes/Qvalent_Rest_PayWayAPI.php';
      $pay_obj = new Qvalent_REST_PayWayAPI($bankSettingsArr);
      $response = false;
      
      $CCdata = array(
          'amount' => $chargedAmount
      );
      if(!empty($_POST['cc'])){
        $CCdata = array_merge($CCdata, $_POST['cc']);
      }
      $pay_obj->PreparePayment($CCdata);
      
      try{
        $response = $pay_obj->Submit();
        $paymentId = $pay_obj->GetPaymentId();
      }
      catch(Exception $e){
        if($error_msg = $pay_obj->GetErrorMessage()){
          $_SESSION['error'] = $error_msg;
        } else{
          $_SESSION['error'] = 'Payment failed (on submit). Verify information and try again. '.$e;
        }
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '#form-error');
        exit();
      }
      
      if($response){
        // PAYMENT SUCCESS
        $cart_obj->CloseCart();
        $pay_obj->SetOrderStatus($paymentId);
        $_SESSION['orderNumber'] = $orderNumber;
  
        //Init email details
        $SMARTY->unloadFilter('output', 'trimwhitespace');
        $SMARTY->assign('DOMAIN', "http://" . $GLOBALS['HTTP_HOST']);
        $COMP = json_encode($CONFIG->company);
        $SMARTY->assign('COMPANY', json_decode($COMP, TRUE));
        $from = (string)$CONFIG->company->name;
        $fromEmail = (string) $CONFIG->company->email_from;

        $user_obj = new UserClass();
        $userArr = $_SESSION['user']['public'];
        
        // Create guest user when empty
        if(empty($userArr)){
          $guestArr = array(
              "gname" => $billingArr['address_name'],
              "surname" => $billingArr['address_surname'],
              "email" => $billingArr['address_email']
          );
          $userArr = $user_obj->CreateGuest($guestArr);
        
          //CHANGE USER_ID ONLY FOR MAF!!!!
          $userArr['id'] = $userArr['id'] * -1;
        }
        
        //SET THE CART_USER_ID
        $cart_obj->UpdateUserIdOfClosedCart($order_cartId, $userArr['id']);
        
        $billingArr['address_user_id'] = $userArr['id'];
        
        $billID = $user_obj->InsertNewAddress($billingArr);
        
        $params = array(
            'payment_user_id' => $userArr['id'],
            'payment_billing_address_id' => $billID,
            'payment_shipping_address_id' => 0
        );
        $pay_obj->SetUserAddressIds($params);
        
        try{
          // SEND CONFIRMATION EMAIL
          $SMARTY->assign('isGiftCertificate', $isGiftCertificate);
          $SMARTY->assign('recipient_name', $_POST['rname']);
          $SMARTY->assign('recipient_email', $_POST['remail']);
          $SMARTY->assign('send_date', $sendDate);
          $SMARTY->assign('hasDonation', $hasDonation);
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
        
          $to = $userArr['email'];
          $bcc = (string) $CONFIG->company->email_orders;
          $subject = 'Payment | Order confirmation';
          $body = $SMARTY->fetch('email/order-confirmation.tpl');
          if($mailID = sendMail($to, $from, $fromEmail, $subject, $body, $bcc)){
            $pay_obj->SetInvoiceEmail($paymentId, $mailID);
          }
        }
        catch(Exception $e){}
        
        // SET GOOGLE ANALYTICS - ECOMMERCE
        if(!empty($GA_ID)){
          //sendGAEnEcCheckoutStep($GA_ID, '5', 'Checkout complete', $productsGA);
          $totalsGA = array(
              'id' => $orderNumber,
              'total' => $chargedAmount,
              'tax' => $gst,
              'shipping' => $shippingFee,
              'coupon' => $order['cart_discount_code']
          );
          sendGAEnEcPurchase($GA_ID, $totalsGA, $productsGA);
        
          try{
            $cartitemProdUIDsArr = array();
            $cartitemProdUIDs = '';
            foreach($orderItems as $oi){
              $cartitemProdUIDsArr[] = empty($oi['variant_uid']) ? "{$oi['cartitem_product_id']}-{$oi['cartitem_variant_id']}" : $oi['variant_uid'];
            }
            $cartitemProdUIDs = implode(',', "'". $cartitemProdUIDsArr ."'");
          }catch(Exception $e){}
          $_SESSION['conversionTracking'] = "<script>
            var CartitemProdUIDs = [{$cartitemProdUIDs}];
            var PaymentAmount = '{$chargedAmount}';
            var OrderNumber = '{$orderNumber}';
            </script>";
        }
        
        if(!empty($GA_ID) && $hasDonation){
          sendGAEvent($GA_ID, 'donation', 'success', $_SESSION['user']['public']['id']);
        }
        
        if($isGiftCertificate){
          if(!empty($GA_ID)){
            sendGAEvent($GA_ID, 'gift-certificate', 'success', $_SESSION['user']['public']['id']);
          }
          // CREATE GIFT CERTIFICATE
          try{
            include_once 'includes/classes/voucher-class.php';
            $voucherObj = new Voucher();
            $attempt = 0;
            $validCode = false;
            while($attempt < 80 && !$validCode){
              $attempt++;
              $codeStr = $voucherObj->GenerateVoucherCode();
              if(empty($cart_obj->GetDiscountData($codeStr))){
                $validCode = true;
              }
            }
            if($validCode){
              $startDate = empty($sendDate) ? date('Y-m-d') : $sendDate;
              $endDate = date('Y-m-d', strtotime($startDate . ' + 1 year'));
              $newVoucherArr = array(
                  "payment_id" => $paymentId,
                  "code" => $codeStr,
                  "name" => $_POST['name'],
                  "email" => $_POST['email'],
                  "recipientname" => $_POST['rname'],
                  "recipientemail" => $_POST['remail'],
                  "message" => $_POST['message'],
                  "anonymous" => (empty($_POST['anonymous']) ? 0 : 1),
                  "amount" => $chargedAmount,
                  "start_date" => $startDate,
                  "end_date" => $endDate
              );
              if($voucherId = $voucherObj->CreateVoucher($newVoucherArr)){
              
                $newDiscountArr = array(
                    "code" => $codeStr,
                    "name" => "Gift certificate",
                    "description" => "Gift certificate ($voucherId) - Order no. {$orderNumber}",
                    "amount" => $chargedAmount,
                    "start_date" => $startDate,
                    "end_date" => $endDate,
                    "fixed_time" => 1,
                    "isPublished" => 1,
                );
                if($discountId = $cart_obj->CreateDiscountCode($newDiscountArr)){
                  if(empty($sendDate)){
                    // SEND GIFT CERTIFICATE TO RECIPIENT
                    $to = $_POST['remail'];
                    $SMARTY->assign('name', $_POST['rname']);
                    $SMARTY->assign('sender_name', (empty($_POST['anonymous']) ? $_POST['name'] : ''));
                    $SMARTY->assign('code', $codeStr);
                    $SMARTY->assign('amount', $chargedAmount);
                    $SMARTY->assign('custom_message', $_POST['message']);
                    $subject = 'Someone has sent you a MedicAlert gift certificate';
                    $body = $SMARTY->fetch('email/gift-certificate.tpl');
                    $mailID_recipient = sendMail($to, $from, $fromEmail, $subject, $body);
                    
                    // SEND GIFT CERTIFICATE TO SENDER
                    $to = $_POST['email'];
                    $SMARTY->assign('sender_name', $_POST['name']);
                    $subject = 'Your MedicAlert gift certificate was sent';
                    $body = $SMARTY->fetch('email/confirmation-gift-certificate.tpl');
                    $mailID_sender = sendMail($to, $from, $fromEmail, $subject, $body);
                    
                    $voucherObj->SetVoucherEmailIds($mailID_recipient, $mailID_sender);
                  }
                }
              }
            }
            
          }
          catch(Exception $e){
            sendErrorMail('weberrors@them.com.au', $from, $fromEmail, 'Create voucher', "Payment id:  {$paymentId} <br>". $e);
          }
        }
        
        // OPEN NEW CART
        $cart_obj->CreateCart();
  
        $_SESSION['post'] = '';
        
        // REDIRECT TO THANK YOU PAGE
        header('Location: '. $thankyouPage);
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
      
      
    case 'quick-renew':
      $user_obj = new UserClass();
      if(!empty($_SESSION['user']['public']['id'])){
        $loginMAFCheck = $user_obj->setSessionVars($_SESSION['user']['public']['maf']['token']);
        if(!$loginMAFCheck){
          $_SESSION['user']['public'] = null;
          header("Location: /login");
          die();
        }
      }
      
      $cart_obj = new cart($_SESSION['user']['public']['id']);
      $order_cartId = $cart_obj->cart_id;
      $orderNumber = $order_cartId . '-' . date("is");
      $cart_obj->RemoveNonMembershipFeeCartitems();
      $cart_obj->RemoveDiscountCode();
      
      if(!empty($_SESSION['user']['public']['maf']) && !empty($cart_obj->NumberOfProductsOnCart())){
        $billID = 0;
        $shipID = 0;
    
        //Selected payment method
        $paymentMethod = 'Credit card';
    
        //Shipping
        $shippingFee = 0;
    
        $totals = $cart_obj->CalculateTotal();
        $chargedAmount = $totals['total'] + $shippingFee;
        $gst = $totals['GST'];
        $params = array(
            'payment_user_id' => (empty($_SESSION['user']['public']['id']) ? 0 : $_SESSION['user']['public']['id']),
            'payment_billing_address_id' => $billID,
            'payment_shipping_address_id' => $shipID,
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
    
        $billingArr = array(
            "address_user_id" => $_SESSION['user']['public']['id'],
            "address_name" => $_SESSION['user']['public']['gname'],
            "address_surname" => $_SESSION['user']['public']['surname'],
            "address_email" => $_SESSION['user']['public']['email'],
            "address_telephone" => (empty($_SESSION['user']['public']['maf']['main']['user_mobile']) ? str_replace(' ', '', $_SESSION['user']['public']['maf']['main']['user_phone_home']) : $_SESSION['user']['public']['maf']['main']['user_mobile']),
            "address_line1" => $_SESSION['user']['public']['maf']['main']['user_address'],
            "address_suburb" => $_SESSION['user']['public']['maf']['main']['user_suburb'],
            "address_state" => $_SESSION['user']['public']['maf']['main']['user_state_id'],
            "address_postcode" => $_SESSION['user']['public']['maf']['main']['user_postcode']
        );
        
        $bankSettingsArr = array(
            'initPayment' => $params,
            'settings' => $CONFIG->payment_gateway->payway,
            'address' => $billingArr
        );
        
        if(!empty($GA_ID)){
          $productsGA = $cart_obj->getCartitemsByCartId_GA();
          //sendGAEnEcCheckoutStep($GA_ID, '4', 'Payment', $productsGA);
          //sendGAEnEcCheckoutOptions($GA_ID, '4', $paymentMethod);
        }
    
        require_once 'includes/classes/Qvalent_Rest_PayWayAPI.php';
        $pay_obj = new Qvalent_REST_PayWayAPI($bankSettingsArr);
        $response = false;
    
        $CCdata = array(
            'amount' => $chargedAmount
        );
        if(!empty($_POST['cc'])){
          $CCdata = array_merge($CCdata, $_POST['cc']);
        }
        $pay_obj->PreparePayment($CCdata);
    
        try{
          $response = $pay_obj->Submit();
          $paymentId = $pay_obj->GetPaymentId();
        }
        catch(Exception $e){
          if($error_msg = $pay_obj->GetErrorMessage()){
            $_SESSION['error'] = $error_msg;
          } else{
            $_SESSION['error'] = 'Payment failed (on submit). Verify information and try again. '.$e;
          }
          header('Location: ' . $_SERVER['HTTP_REFERER'] . '#form-error');
          exit();
        }
    
        if($response){ // PAYMENT SUCCESS
        	
          $cart_obj->CloseCart();
          $pay_obj->SetOrderStatus($paymentId);
          $_SESSION['orderNumber'] = $orderNumber;
    
          //Init email details
          $SMARTY->unloadFilter('output', 'trimwhitespace');
          $SMARTY->assign('DOMAIN', "http://" . $GLOBALS['HTTP_HOST']);
          $COMP = json_encode($CONFIG->company);
          $SMARTY->assign('COMPANY', json_decode($COMP, TRUE));
          $from = (string)$CONFIG->company->name;
          $fromEmail = (string) $CONFIG->company->email_from;
    
          //Init user details
          $userArr = $_SESSION['user']['public'];
    
          //SET THE CART_USER_ID
          $cart_obj->UpdateUserIdOfClosedCart($order_cartId, $userArr['id']);
    
          $billingArr['address_user_id'] = $userArr['id'];
          
          $billID = $user_obj->InsertNewAddress($billingArr);
    
          $params = array(
              'payment_user_id' => $userArr['id'],
              'payment_billing_address_id' => $billID,
              'payment_shipping_address_id' => 0
          );
          $pay_obj->SetUserAddressIds($params);
    
          if(!empty($GA_ID)){
            sendGAEvent($GA_ID, 'quick-renew', 'success', $_SESSION['user']['public']['id']);
          }
          try{
            // SEND CONFIRMATION EMAIL
            $SMARTY->assign('hasMAFProd', $hasMAFProd);
            $billing = $user_obj->GetAddress($billID);
            $SMARTY->assign('billing', $billing);
            $order = $cart_obj->GetDataCart($order_cartId);
            $SMARTY->assign('order', $order);
            $discount = $cart_obj->GetDiscountData($order['cart_discount_code']);
            $SMARTY->assign('discount', $discount);
            $payment = $pay_obj->GetPaymentRecord($paymentId);
            $SMARTY->assign('payment', $payment);
            $orderItems = $cart_obj->GetDataProductsOnCart($order_cartId);
            $SMARTY->assign('orderItems', $orderItems);
    
            $to = $userArr['email'];
            $bcc = (string) $CONFIG->company->email_orders;
            $subject = 'Payment | Order confirmation';
            $body = $SMARTY->fetch('email/order-confirmation.tpl');
            if($mailID = sendMail($to, $from, $fromEmail, $subject, $body, $bcc)){
              $pay_obj->SetInvoiceEmail($paymentId, $mailID);
            }
          }
          catch(Exception $e){}
          
          //PROCESS PENDING UPDATES
          if(!empty($_SESSION['user']['public']['pending_update'])){
            $detailsArr = $_SESSION['user']['public']['pending_update'];
            //Pre-process array fields
            if(!empty($detailsArr['conditions'])){
              $detailsArr['conditions'] = $user_obj->formatProfileArrayField($detailsArr['conditions'], $_SESSION['user']['public']['maf']['update']['conditions']);
            }
            if(!empty($detailsArr['allergies'])){
              $detailsArr['allergies'] = $user_obj->formatProfileArrayField($detailsArr['allergies'], $_SESSION['user']['public']['maf']['update']['allergies']);
            }
            if(!empty($detailsArr['medications'])){
              $detailsArr['medications'] = $user_obj->formatProfileArrayField($detailsArr['medications'], $_SESSION['user']['public']['maf']['update']['medications']);
            }
            
            //Update profile
            if($user_obj->processUpdate($_SESSION['user']['public']['maf']['token'], $detailsArr)){
              saveInLog('member-profile-update', 'external', $_SESSION['user']['public']['id']);
              try{
                //Send notification
                $SMARTY->unloadFilter('output', 'trimwhitespace');
                $SMARTY->assign('DOMAIN', "http://" . $_SERVER['HTTP_HOST']);
                $subject = 'Update Member Profile';
                $fromEmail = (string) $CONFIG->company->email_from;
                $to = $detailsArr['user_email'];
                $SMARTY->assign('user_name', $detailsArr['user_firstname']);
                $COMP = json_encode($CONFIG->company);
                $SMARTY->assign('COMPANY', json_decode($COMP,TRUE));
                $from = (string) $CONFIG->company->name;
                $body = $SMARTY->fetch("email/profile-update.tpl");
                $sent = sendMail($to, $from, $fromEmail, $subject, $body);
              }catch(Exception $e){}
              
              try{
                //Create survey
                require_once 'includes/classes/survey-class.php';
                $surveyObj = new Survey();
                $surveyObj->CreateSurvey($_SESSION['user']['public']['id'], $_SESSION['user']['public']['email'], 2);
              }catch(Exception $e){}
              
            }else{
              sendErrorMail('weberrors@them.com.au', $from, $fromEmail, 'Update profile after payment (2)', "Member id:  {$_SESSION['user']['public']['id']} <br>". $user_obj->getErrorMsg());
            }  
            $_SESSION['user']['public']['pending_update'] = null;

          }else{
            try{
              //Create survey - when no profile updates
              require_once 'includes/classes/survey-class.php';
              $surveyObj = new Survey();
              $surveyObj->CreateSurvey($_SESSION['user']['public']['id'], $_SESSION['user']['public']['email'], 1);
            }catch(Exception $e){}
          }
    
          //PROCESS AUTO-RENEWAL
          $setAutoRenewal = false;
          if(!empty($_POST['autorenewal'])){
            if(!empty($GA_ID)){
              sendGAEvent($GA_ID, 'autorenewal-quick', 'attempt', $_SESSION['user']['public']['id']);
            }
            
            $autorenewObj = new Qvalent_REST_PayWayAPI($bankSettingsArr);
            if(!empty($_POST['autopayment']) && $_POST['autopayment'] == 'dd'){
              $autorenewArr = $_POST['auto-dd'];
            }else{
              $autorenewArr = $_POST['auto-cc'];
            }
            $autorenewArr['method'] = $_POST['autopayment'];
            $autorenewObj->PreparePayment($autorenewArr);
            if($autorenewObj->CreateCustomerOnly()){
              $customerArr = $autorenewObj->GetBankCustomerRecord();
              $customerArr['user_id'] = $userArr['id'];
              $autorenewObj->StoreAutoRenew($customerArr);
              $setAutoRenewal = true;
              //Register for auto-renewal and change member status to "active pending"
              if(!$user_obj->setAutoRenewal($_SESSION['user']['public']['maf']['token'], $customerArr)){
                sendErrorMail('weberrors@them.com.au', $from, $fromEmail, 'Set auto-renewal (2)', "Member id:  {$userArr['id']} <br>". $user_obj->getErrorMsg());
                $setAutoRenewal = false;
              }
              if(!empty($GA_ID)){
                sendGAEvent($GA_ID, 'autorenewal-quick', 'success', $_SESSION['user']['public']['id']);
              }
              try{
                //Send auto-renewal email
                $SMARTY->assign('user', $userArr);
                $to = $userArr['email'];
                $subject = 'Auto-renewal Subscription';
                $body = $SMARTY->fetch('email/autorenewal.tpl');
                sendMail($to, $from, $fromEmail, $subject, $body, $bcc);
              }
              catch(Exception $e){}
            }else{
              sendErrorMail('weberrors@them.com.au', $from, $fromEmail, 'Register for auto-renewal (2)', "Member id:  {$userArr['id']} <br>".print_r($billingArr, true).'<br>'. $autorenewObj->GetErrorMessage());
            }
          }
          
          //Change member status to "active pending"
          if(!$setAutoRenewal){
            if(!$user_obj->setPendingStatus($_SESSION['user']['public']['maf']['token'], $_SESSION['user']['public']['id'])){
              sendErrorMail('weberrors@them.com.au', $from, $fromEmail, 'Set active-pending (2)', "Member id:  {$userArr['id']} <br>". $user_obj->getErrorMsg());
            }
          }
          
          // SET GOOGLE ANALYTICS - ECOMMERCE
          if(!empty($GA_ID)){
            //sendGAEnEcCheckoutStep($GA_ID, '5', 'Checkout complete', $productsGA);
            $totalsGA = array(
                'id' => $orderNumber,
                'total' => $chargedAmount,
                'tax' => $gst,
                'shipping' => $shippingFee,
                'coupon' => $order['cart_discount_code']
            );
            sendGAEnEcPurchase($GA_ID, $totalsGA, $productsGA);
            
            try{
              $cartitemProdUIDsArr = array();
              $cartitemProdUIDs = '';
              foreach($orderItems as $oi){
                $cartitemProdUIDsArr[] = empty($oi['variant_uid']) ? "{$oi['cartitem_product_id']}-{$oi['cartitem_variant_id']}" : $oi['variant_uid'];
              }
              $cartitemProdUIDs = implode(',', "'". $cartitemProdUIDsArr ."'");
            }catch(Exception $e){}
            $_SESSION['conversionTracking'] = "<script>
              var CartitemProdUIDs = [{$cartitemProdUIDs}];
              var PaymentAmount = '{$chargedAmount}';
              var OrderNumber = '{$orderNumber}';
              </script>";
          }
    
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
      
      
    case 'auto-renewal':
      $_SESSION['error'] = '';
      $_SESSION['post'] = $_POST;
    
      $user_obj = new UserClass();
      if(!empty($_SESSION['user']['public']['id'])){
        $loginMAFCheck = $user_obj->setSessionVars($_SESSION['user']['public']['maf']['token']);
        if(!$loginMAFCheck){
          $_SESSION['user']['public'] = null;
          header("Location: /login");
          die();
        }
      }
      
      //Has mandatory field
      if(empty($_SESSION['user']['public']['maf']) || !empty($_POST['honeypot']) || empty($_POST['timestamp']) || (time() - $_POST['timestamp']) < 4 || empty($_POST['autopayment']) || (empty($_POST['auto-dd']) && empty($_POST['auto-cc'])) ){
        $_SESSION['error'] = 'Your session has expired. Please try again, otherwise contact us by phone.';
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '#form-error');
        die();
      }
      //user_phone_home
      $billingArr = array(
          "address_user_id" => $_SESSION['user']['public']['id'],
          "address_name" => $_SESSION['user']['public']['gname'],
          "address_surname" => $_SESSION['user']['public']['surname'],
          "address_email" => $_SESSION['user']['public']['email'],
          "address_telephone" => (empty($_SESSION['user']['public']['maf']['main']['user_mobile']) ? str_replace(' ', '', $_SESSION['user']['public']['maf']['main']['user_phone_home']) : $_SESSION['user']['public']['maf']['main']['user_mobile']),
          "address_line1" => $_SESSION['user']['public']['maf']['main']['user_address'],
          "address_suburb" => $_SESSION['user']['public']['maf']['main']['user_suburb'],
          "address_state" => $_SESSION['user']['public']['maf']['main']['user_state_id'],
          "address_postcode" => $_SESSION['user']['public']['maf']['main']['user_postcode']
      );
      
      $userArr = $_SESSION['user']['public'];
      
      $bankSettingsArr = array(
          'settings' => $CONFIG->payment_gateway->payway,
          'address' => $billingArr
      );
      
      //Init email details
      $SMARTY->unloadFilter('output', 'trimwhitespace');
      $SMARTY->assign('DOMAIN', "http://" . $GLOBALS['HTTP_HOST']);
      $COMP = json_encode($CONFIG->company);
      $SMARTY->assign('COMPANY', json_decode($COMP, TRUE));
      $from = (string)$CONFIG->company->name;
      $fromEmail = (string) $CONFIG->company->email_from;
      
      require_once 'includes/classes/Qvalent_Rest_PayWayAPI.php';
      $autorenewObj = new Qvalent_REST_PayWayAPI($bankSettingsArr);
      if(!empty($_POST['autopayment']) && $_POST['autopayment'] == 'dd'){
        $autorenewArr = $_POST['auto-dd'];
      }else{
        $autorenewArr = $_POST['auto-cc'];
      }
      $autorenewArr['method'] = $_POST['autopayment'];
      $autorenewObj->PreparePayment($autorenewArr);
      
      if(!empty($GA_ID)){
        sendGAEvent($GA_ID, 'autorenewal-checkout', 'attempt', $_SESSION['user']['public']['id']);
      }
      
      if($autorenewObj->CreateCustomerOnly()){
        $customerArr = $autorenewObj->GetBankCustomerRecord();
        $customerArr['user_id'] = $_SESSION['user']['public']['id'];
        $autorenewObj->StoreAutoRenew($customerArr);
        //Register for auto-renewal and change member status to "active pending"
        if(!$user_obj->setAutoRenewal($_SESSION['user']['public']['maf']['token'], $customerArr)){
          sendErrorMail('weberrors@them.com.au', $from, $fromEmail, 'Set auto-renewal (3)', "Member id:  {$userArr['id']} <br>". $user_obj->getErrorMsg());
        }
        if(!empty($GA_ID)){
          sendGAEvent($GA_ID, 'autorenewal-only', 'success', $_SESSION['user']['public']['id']);
        }
        try{
          //Send auto-renewal email
          $SMARTY->assign('user', $userArr);
          $to = $_SESSION['user']['public']['email'];
          $subject = 'Auto-renewal Subscription';
          $body = $SMARTY->fetch('email/autorenewal.tpl');
          $bcc = (string) $CONFIG->company->email_orders;
          sendMail($to, $from, $fromEmail, $subject, $body, $bcc);
        }
        catch(Exception $e){}
        
        // REDIRECT TO THANK YOU PAGE
        header('Location: /thank-you-for-registering-auto-renewal');
        die();
      
      } else{
        sendErrorMail('weberrors@them.com.au', $from, $fromEmail, 'Register for auto-renewal (3)', "Member id:  {$userArr['id']} <br>". print_r($billingArr, true).'<br>'. $autorenewObj->GetErrorMessage());
        if($error_msg = $autorenewObj->GetErrorMessage()){
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