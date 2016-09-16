<?php
global $SMARTY, $DBobject, $CONFIG;
$referer = parse_url($_SERVER['HTTP_REFERER']);
if(true){
  require_once 'includes/classes/paypal-expresscheckout-class.php';
  switch($_REQUEST['action']){
    case 'REDIRECT':
      $cart_obj = new cart($_SESSION['user']['public']['id']);
      $itemNumber = $cart_obj->NumberOfProductsOnCart();
      if(empty($_SESSION['selectedShipping']) || $itemNumber == 0){
        $_SESSION['post'] = $_POST;
        $_SESSION['error'] = 'Session has expired';
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '#error');
        die();
      }
      
      $cartItems = array();
      $items = $cart_obj->GetDataProductsOnCart();
      foreach($items as $item){
        $cartItems[] = array(
            'itemName' => unclean($item['cartitem_product_name']), 
            'itemNumber' => $item['cartitem_product_uid'], 
            'itemDescription' => '', 
            'itemAmount' => $item['cartitem_product_price'], 
            'itemQty' => $item['cartitem_quantity'] 
        );
      }
      
      $order_cartId = $cart_obj->cart_id;
      $orderNumber = $order_cartId . '-' . date("is");
      $_SESSION['orderNumber'] = $orderNumber;
      
      $ship_obj = new ShippingClass();
      $methods = $ship_obj->getShippingMethods($itemNumber);
      $shippingFee = floatval($methods["{$_SESSION['selectedShipping']}"]);
      $shippingFee = empty($shippingFee)? 0 : $shippingFee;
      $totals = $cart_obj->CalculateTotal();
      $chargedAmount = $totals['total'] + $shippingFee;
      
      if($totals['discount'] > 0){
        $cartData = $cart_obj->GetDataCart();
        $cartItems[] = array(
            'itemName' => $cartData['cart_discount_description'], 
            'itemNumber' => '', 
            'itemDescription' => $cartData['cart_discount_code'], 
            'itemAmount' => $totals['discount'] * -1, 
            'itemQty' => 1 
        );
      }
      $CartTotals = array();
      $CartTotals["subtotal"] = $totals['total'];
      $CartTotals["shipping"] = $shippingFee;
      $CartTotals["total"] = $chargedAmount;
      $CartTotals["chargedAmount"] = $chargedAmount;
      
      $params = array(
          'payment_transaction_no' => $_SESSION['orderNumber'], 
          'payment_cart_id' => $order_cartId 
      );
      // die(var_dump($cartItems).var_dump($CartTotals));
      $pay_obj = new PayPal($params);
      $res = $pay_obj->SetExpressCheckout($cartItems, $CartTotals);
      if(!$res){
        die($pay_obj->GetErrorMessage());
      }
      break;
    
    default:
      if(!empty($_REQUEST['token'])){
        
        if(!empty($_SESSION['address']['B'])){
          $isGuest = false;
          $user_obj = new UserClass();
          $values = empty($_SESSION['address']['user'])? $_SESSION['user']['public'] : $_SESSION['address']['user'];
          $promo = 0;
          if($_SESSION['address']['wantpromo']){
            $promo = 1;
            try{
              require_once 'includes/createsend/csrest_subscribers.php';
              $wrap = new CS_REST_Subscribers('2053876846d5293897b007b26e626654', '060d24d9003a77b06b95e7c47691975b'); // !!!! UPDATE CREATESEND LIST CODE !!!!!
              $cs_result = $wrap->add(array(
                  'EmailAddress' => $values['email'], 
                  'Name' => $values['gname'], 
                  'CustomFields' => array(), 
                  "Resubscribe" => "true" 
              ));
            }
            catch(Exception $e){}
          }
          $values['want_promo'] = $promo;
          if(empty($_SESSION['user']['public']['id'])){ // ADD GUEST USER
            $isGuest = true;
            $values['username'] = $values['email'];
            $res = $user_obj->Create($values, true);
            if($res['error']){
              $_SESSION['error'] = $res['error'];
              $_SESSION['post'] = $_POST;
              header("Location: " . $_SERVER['HTTP_REFERER'] . "#error");
              die();
            } else{
              $cart_obj = new cart($_SESSION['user']['public']['id']);
              $_SESSION['user']['public'] = $res;
              $_POST['address']['B']['address_user_id'] = $res['id'];
              $_POST['address']['S']['address_user_id'] = $res['id'];
              if(!empty($res['password'])){
                try{
                  // SEND NEW ACCOUNT EMAIL
                  $SMARTY->assign("DOMAIN", 'http://' . $HTTP_HOST);
                  $COMP = json_encode($CONFIG->company);
                  $SMARTY->assign('COMPANY', json_decode($COMP, TRUE));
                  $SMARTY->assign("user_name", $res['gname']);
                  $SMARTY->assign("password", $res['password']);
                  
                  $buffer = $SMARTY->fetch('newmember-email.tpl');
                  $to = $res['email'];
                  $from = (string)$CONFIG->company->name;
                  $fromEmail = (string)$CONFIG->company->email_from;
                  $subject = 'Your new account details';
                  $body = $buffer;
                  $mailID = sendMail($to, $from, $fromEmail, $subject, $body);
                }
                catch(Exception $e){}
              }
            }
          }
          
          // SAVE BILLING AND SHIPPING ADDRESS
          
          $shipID = $user_obj->InsertNewAddress(array_merge(array(
              'address_user_id' => $_SESSION['user']['public']['id'] 
          ), $_SESSION['address']['S']));
          $billID = $shipID;
          if(empty($_SESSION['address']['same_address'])){
            $billID = $user_obj->InsertNewAddress(array_merge(array(
                'address_user_id' => $_SESSION['user']['public']['id'] 
            ), $_SESSION['address']['B']));
          }
          
          if(is_null($billID) || is_null($shipID)){
            $_SESSION['error'] = 'Error while saving billing/shipping address. Please try again, otherwise contact us by phone.';
            $_SESSION['post'] = $_POST;
            header("Location: " . $_SERVER['HTTP_REFERER'] . "#error");
            die();
          }
          
          $cart_obj = new cart($_SESSION['user']['public']['id']);
          $order_cartId = $cart_obj->cart_id;
          
          $ship_obj = new ShippingClass();
          
          $methods = $ship_obj->getShippingMethods($cart_obj->NumberOfProductsOnCart());
          $shippingFee = floatval($methods["{$_SESSION['selectedShipping']}"]);
          $totals = $cart_obj->CalculateTotal();
          $chargedAmount = $totals['total'] + $shippingFee;
          $gst = round(($totals['GST_Taxable'] + $shippingFee) / 11, 2);
          $params = array(
              'payment_billing_address_id' => $billID, 
              'payment_shipping_address_id' => $shipID, 
              'payment_transaction_no' => $_SESSION['orderNumber'], 
              'payment_cart_id' => $order_cartId, 
              'payment_user_id' => $_SESSION['user']['public']['id'], 
              'payment_subtotal' => $totals['subtotal'], 
              'payment_discount' => $totals['discount'], 
              'payment_shipping_fee' => $shippingFee, 
              'payment_shipping_method' => $_SESSION['selectedShipping'], 
              'payment_shipping_comments' => $_SESSION['comments'], 
              'payment_payee_name' => '', 
              'payment_charged_amount' => $chargedAmount, 
              'payment_gst' => $gst 
          );
          
          $pay_obj = new PayPal($params);
          $reponse = $pay_obj->Submit($_REQUEST['token']);
          $paymentId = $pay_obj->GetPaymentId();
          if($reponse){
            // PAYMENT SUCCESS
            $cart_obj->CloseCart($order_cartId);
            $pay_obj->SetOrderStatus($paymentId);
            
            try{
              // SEND CONFIRMATION EMAIL
              $SMARTY->assign("user", $_SESSION['user']['public']);
              $user_obj = new UserClass();
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
              
              $to = $_SESSION['user']['public']['email'];
              $from = (string)$CONFIG->company->name;
              $fromEmail = (string)$CONFIG->company->email_from;
              $bcc = (string)$CONFIG->company->email_orders;
              $subject = 'Confirmation of your order';
              $body = $SMARTY->fetch('email-confirmation.tpl');
              if($mailID = sendMail($to, $from, $fromEmail, $subject, $body, $bcc)){
                $pay_obj->SetInvoiceEmail($paymentId, $mailID);
              }
            }
            catch(Exception $e){}
            
            // SET GOOGLE ANALYTICS - ECOMMERCE
            $affiliation = str_replace("www.", "", $GLOBALS['HTTP_HOST']);
            $analytics = $cart_obj->getJSCartitemsByCartId_GA($order_cartId);
            $analytics .= "ga('ec:setAction', 'purchase', {
						'id': '{$_SESSION['orderNumber']}',
						'affiliation': '{$affiliation}',
						'revenue': '{$chargedAmount}',
						'tax': '{$gst}',
						'shipping': '{$shippingFee}',
						'coupon': '{$order['cart_discount_code']}'
					});
					";
            $_SESSION['ga_ec'] = $analytics;
            
            $_SESSION['marin_conversion'] = "<script type='text/javascript'>
                                              var _mTrack = _mTrack || [];
                                              _mTrack.push(['addTrans', {
                                                  currency :'AUD',
                                                  items : [{
                                                      orderId : '{$_SESSION['orderNumber']}',
                                                      convType : 'transaction',
                                                      price :    '{$chargedAmount}'
                                                  }]
                                              }]);
                                              _mTrack.push(['processOrders']);
                                              (function() {
                                                  var mClientId = '24962diq40463';
                                                  var mProto = (('https:' == document.location.protocol) ? 'https://' : 'http://');
                                                  var mHost = 'tracker.marinsm.com';
                                                  var mt = document.createElement('script'); mt.type = 'text/javascript'; mt.async = true; mt.src = mProto + mHost + '/tracker/async/' + mClientId + '.js';
                                                  var fscr = document.getElementsByTagName('script')[0]; fscr.parentNode.insertBefore(mt, fscr);
                                              })();
                                              </script>
                                      		    <noscript>
                                      		    <img width='1' height='1' src='https://tracker.marinsm.com/tp?act=2&cid=24962diq40463&script=no' />
                                      		    </noscript>";
            
            // SET USED DISCOUNT CODE
            if($order['cart_discount_code']){
              $cart_obj->SetUsedDiscountCode($order['cart_discount_code']);
              $discountData = $cart_obj->GetDiscountData($order['cart_discount_code']);
              if($discountData['discount_unlimited_use'] == '0'){
                try{
                  // SEND NOTIFICATION EMAIL
                  $SMARTY->assign('user', $_SESSION['user']['public']);
                  $SMARTY->assign('discount', $discountData);
                  $buffer = $SMARTY->fetch('email-discount.tpl');
                  $to = "apolo@them.com.au";
                  $bcc = "";
                  $from = str_replace("www.", "", $GLOBALS['HTTP_HOST']);
                  $fromEmail = (string)$CONFIG->company->email_from;
                  $subject = 'A discount code has been used.';
                  $body = $buffer;
                  $mailID = sendMail($to, $from, $fromEmail, $subject, $body, $bcc);
                }
                catch(Exception $e){}
              }
            }
            
            // LOG OUT GUEST USER
            if($isGuest){
              unset($_SESSION['user']['public']);
            }
            
            // OPEN NEW CART
            $cart_obj->CreateCart($_SESSION['user']['public']['id']);
            
            // REDIRECT TO THANK YOU PAGE
            header('Location: /thank-you-for-purchasing');
            exit();
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
        header('Location: /checkout#error');
      }
  }
  die();
} else{
  header('Location: /404');
  die();
}