<?php
if($_SERVER['REMOTE_ADDR'] == '150.101.230.130'){
  set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
  include_once 'includes/functions/functions.php';
  global $DBobject, $CONFIG;
  	
  try{
    $userArr = array(
        "id" => 123456,
        "gname" => 'Jane',
        "surname" => 'Doe',
        "email" => 'test@them.com.au'
    );
    $SMARTY->assign('user', $userArr);
    
    $sql = "SELECT * FROM tbl_payment LEFT JOIN tbl_cart ON cart_id = payment_cart_id WHERE payment_id = :id ";
    $order = $DBobject->wrappedSql( $sql, array(':id' => $_REQUEST["payment_id"]) );
    $SMARTY->assign('payment', $order[0]);
    $SMARTY->assign('order', $order[0]);
    	
    $sql = "SELECT * FROM tbl_address WHERE address_id = :id ";
    $res = $DBobject->wrappedSql ( $sql, array(':id' => $order[0]["payment_billing_address_id"]) );
    $SMARTY->assign('billing', $res[0]);
    	
    $sql = "SELECT * FROM tbl_address WHERE address_id = :id ";
    $res = $DBobject->wrappedSql ( $sql, array(':id' => $order[0]["payment_shipping_address_id"]) );
    $SMARTY->assign('shipping', $res[0]);

    $cart_obj = new cart();
    $orderItems = $cart_obj->GetDataProductsOnCart($order[0]["payment_cart_id"]);
    $SMARTY->assign('orderItems', $orderItems);
    

    $discount = $cart_obj->GetDiscountData($order[0]['cart_discount_code']);
    $SMARTY->assign('discount', $discount);
    
    $SMARTY->assign('DOMAIN', "http://" . $GLOBALS['HTTP_HOST']);
    $COMP = json_encode($CONFIG->company);
    $SMARTY->assign('COMPANY', json_decode($COMP, TRUE));
    $body = $SMARTY->fetch('email/order-confirmation.tpl');
    
    if(!empty($_REQUEST['email'])){
      $to = 'apolo@them.com.au';
      $from = (string)$CONFIG->company->name;
      $fromEmail = 'noreply@' . str_replace("www.", "", $GLOBALS['HTTP_HOST']);
      $subject = 'Test - email template';
      sendMail($to, $from, $fromEmail, $subject, $body, $bcc);
    }else{
      echo $body;
    }
  }
  catch(Exception $e){
    die(var_dump($e));
  }
}

die();