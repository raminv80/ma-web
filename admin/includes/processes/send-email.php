<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'admin/includes/functions/admin-functions.php';
global $DBobject, $CONFIG, $SMARTY;

if(checkToken('admin', $_POST["formToken"]) && !empty($_POST['action']) && !empty($_POST['email']) ){
	$template = '';
	
	$to = $_POST['email'];
	$from = (string) $CONFIG->company->name;
	$fromEmail = (string) $CONFIG->company->email_from;

	switch ($_POST['action']){
		case 'UserPassword':
			$subject = 'Your account details';
			$SMARTY->assign("name",$_POST['name']);
			$SMARTY->assign("password",$_POST['password']);
			$SMARTY->assign("DOMAIN","http://".$_SERVER['HTTP_HOST']);
			$body= $SMARTY->fetch('email-admin-user.tpl');
			break;
		
		case 'OrderStatus':
			$template = 'email-order-status.tpl';
			switch($_POST['status']){
				//ADDITIONAL CUSTOM MESSAGE / TEMPLATE
				case '2'://CANCELLED
					$message = '';
					break;
				case '4'://SHIPPED
					$message = '';
					$SMARTY->assign('displayTrackingMsg',1);
					break;
			}
			if(!empty($template)){
				$sql = "SELECT * FROM tbl_status WHERE status_id = :id ";
				$res = $DBobject->wrappedSql ( $sql, array(':id' => $_POST["status"]) );
				$subject = 'You order has been ' . strtolower($res[0]['status_name']) . '.';
				$SMARTY->assign('message',"<p>$subject</p><p>$message</p>");
					
				$sql = "SELECT * FROM tbl_payment
					LEFT JOIN tbl_user ON user_id = payment_user_id
					LEFT JOIN tbl_cart ON cart_id = payment_cart_id
					WHERE payment_id = :id ";
				$order = $DBobject->wrappedSql( $sql, array(':id' => $_POST["payment_id"]) );
				$SMARTY->assign('order',$order[0]);
					
				$sql = "SELECT * FROM tbl_address WHERE address_id = :id ";
				$res = $DBobject->wrappedSql ( $sql, array(':id' => $order[0]["payment_billing_address_id"]) );
				$SMARTY->assign('billing',$res[0]);
					
				$sql = "SELECT * FROM tbl_address WHERE address_id = :id ";
				$res = $DBobject->wrappedSql ( $sql, array(':id' => $order[0]["payment_shipping_address_id"]) );
				$SMARTY->assign('shipping',$res[0]);
					
					
				$cart_arr = array ();
				$sql = "SELECT 	cartitem_id, cartitem_cart_id, cartitem_product_id, cartitem_product_name, cartitem_product_price, cartitem_quantity, cartitem_subtotal, cartitem_product_gst
					FROM tbl_cartitem WHERE cartitem_cart_id = :id AND cartitem_deleted IS NULL AND cartitem_cart_id <> '0'";
				$res = $DBobject->wrappedSql ( $sql, array (":id" => $order[0]["cart_id"]) );
				foreach ( $res as $p ) {
					$cart_arr [$p ['cartitem_id']] = $p;
					$sql = "SELECT 	cartitem_attr_id, cartitem_attr_cartitem_id, cartitem_attr_attribute_id, cartitem_attr_attr_value_id, cartitem_attr_attribute_name, cartitem_attr_attr_value_name
						FROM tbl_cartitem_attr
						WHERE cartitem_attr_cartitem_id	= :id AND cartitem_attr_deleted IS NULL AND cartitem_attr_cartitem_id <> '0'";
					$res2 = $DBobject->wrappedSql ( $sql, array (":id" => $p ['cartitem_id']) );
					$cart_arr [$p ['cartitem_id']] ['attributes'] = $res2;
				}
				$COMP = json_encode($CONFIG->company);
				$SMARTY->assign('COMPANY', json_decode($COMP,TRUE));
				$SMARTY->assign('orderItems', $cart_arr);
				$SMARTY->assign('DOMAIN', "http://" . $GLOBALS['HTTP_HOST']);
				$body = $SMARTY->fetch($template);
			}
			break;
	}
	if(!empty($to) && !empty($subject) && !empty($body)){
		$response = sendMail($to, $from, $fromEmail, $subject, $body);
		echo json_encode(array(
				"response" => $response
		));
		die ();
	}
}
echo json_encode(array(
		"response" => null
));
die ();






