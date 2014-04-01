<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'admin/includes/functions/admin-functions.php';
global $DBobject, $SMARTY;

if(checkToken('admin', $_POST["formToken"]) && !empty($_POST['email']) && !empty($_POST['email_id'])){
	
	/* 
	 * REBUILD THE INVOICE
	 * 
	$SMARTY->assign('user', $_POST["user"]);
	
	$sql = "SELECT * FROM tbl_payment WHERE payment_id = :id ";
	$res = $DBobject->wrappedSql( $sql, array(':id' => $_POST["payment_id"]) );
	$SMARTY->assign('payment',$res[0]);
	
	$sql = "SELECT * FROM tbl_address WHERE address_id = :id ";
	$res = $DBobject->wrappedSql ( $sql, array(':id' => $_POST["bill_ID"]) );
	$SMARTY->assign('billing',$res[0]);
	
	$sql = "SELECT * FROM tbl_address WHERE address_id = :id ";
	$res = $DBobject->wrappedSql ( $sql, array(':id' => $_POST["ship_ID"]) );
	$SMARTY->assign('shipping',$res[0]);
		
	$sql = "SELECT * FROM tbl_cart WHERE cart_id = :id AND cart_deleted IS NULL AND cart_id <> '0'";
	$res = $DBobject->wrappedSql ( $sql, array (":id" => $_POST["cart_id"]) );
	$SMARTY->assign('order',$res[0]);
		
	$cart_arr = array ();
	$sql = "SELECT 	cartitem_id, cartitem_cart_id, cartitem_product_id, cartitem_product_name, cartitem_product_price, cartitem_quantity, cartitem_subtotal, cartitem_product_gst
				FROM tbl_cartitem WHERE cartitem_cart_id = :id AND cartitem_deleted IS NULL AND cartitem_cart_id <> '0'";
	$res = $DBobject->wrappedSql ( $sql, array (":id" => $_POST["cart_id"]) );
	foreach ( $res as $p ) {
		$cart_arr [$p ['cartitem_id']] = $p;
		$sql = "SELECT 	cartitem_attr_id, cartitem_attr_cartitem_id, cartitem_attr_attribute_id, cartitem_attr_attr_value_id, cartitem_attr_attribute_name, cartitem_attr_attr_value_name
					FROM tbl_cartitem_attr
					WHERE cartitem_attr_cartitem_id	= :id AND cartitem_attr_deleted IS NULL AND cartitem_attr_cartitem_id <> '0'";
		$res2 = $DBobject->wrappedSql ( $sql, array (":id" => $p ['cartitem_id']) );
		$cart_arr [$p ['cartitem_id']] ['attributes'] = $res2;
	}
	$SMARTY->assign('orderItems', $cart_arr);
		
	$buffer= $SMARTY->fetch('email-confirmation.tpl'); 
	
	$to = $_POST['email'];
	$from = 'eShop';
	$fromEmail = 'noreply@cms.themserver.com';
	$subject = 'Confirmation of your order';
	$body = $buffer;
	
	$response = sendMail($to, $from, $fromEmail, $subject, $body);
	echo json_encode(array(
			"response" => $response
	));
	*
	*/
		
	
	$sql = "SELECT * FROM tbl_email_copy WHERE email_id = :id ";
	if($res = $DBobject->wrappedSql( $sql, array(':id' => $_POST["email_id"]))){
		$to = $_POST['email'];
		$from = 'eShop';
		$fromEmail = 'noreply@'. str_replace('www.', '', $_SERVER['HTTP_HOST']);
		$subject = $res[0]['email_subject'];
		$body = $res[0]['email_content'];
		
		$response = sendMail($to, $from, $fromEmail, $subject, $body);
		echo json_encode(array(
				"response" => $response
		));
	}
	
	
}
die ();






