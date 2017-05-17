<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;

// This is for abandoned carts for non-members

$redirect = '404';

if(!empty($_REQUEST['tk'])){
  
  // The token is made up of the following strings: 'TM-' . dechex($d['cart_id']) . '-'. dechex(time()) . '-' . dechex($d['user_id'])
  $tokenArr = explode('-', $_REQUEST['tk']);
  
  try{
    $redirect = 'products?utm_source=website&utm_medium=email&utm_campaign=abandoned-cart-non-member';
    
    // Validate the token
    if($tokenArr[0] == 'TM' && is_int(hexdec($tokenArr[1])) && is_int(hexdec($tokenArr[2])) && hexdec($tokenArr[2]) < time() && is_int(hexdec($tokenArr[3])) && empty($_SESSION['user']['public']['id'])){
      // Delete the current cart to avoid items duplication
      $cart_obj = new cart();
      $cart_obj->DeleteCart();
      $cart_obj->CreateCart();
      $cart_obj->MergeCarts(array(hexdec($tokenArr[1])), $cart_obj->cart_id);
      
      if(!empty($GA_ID)){
        sendGAEvent($GA_ID, 'campaign', 'click', 'abandoned-cart-non-member');
      }
      
      $redirect = 'shopping-cart?utm_source=website&utm_medium=email&utm_campaign=abandoned-cart-non-member';
    }
  }
  catch(exceptionCart $e){
    sendErrorMail('weberrors@them.com.au', $from, $fromEmail, 'Abandoned cart - get cart',  $e->getMessage());
  }
}

header('Location: /' . $redirect);
die();
