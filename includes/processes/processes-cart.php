<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include "includes/functions/functions.php";
$Action = strip_tags($_POST["Action"]);
if($Action){
	switch ($Action) {
		case 'ADDTOCART':
			$cart_obj = new cart();
			/* Required values for cart-item {product_category, product_id, product_name, product_price, product_qty, product_special,additional}*/
			if($_POST['trackcode']){
				$trackcode = $_POST['trackcode'];
			}
			$product_id = $_POST['product_id'];
			$attribute_id = $_POST['attribute_id'];
			$quantity = $_POST['quantity'];
			$category_id = $_POST['category_id'];
			
			$sql = "SELECT tbl_product.* FROM tbl_product 
						WHERE tbl_product.product_id = '".$product_id."' 
						AND tbl_product.product_deleted IS NULL";
			$row = $DBobject->wrappedSqlGet($sql);

			$pname = $row[0]['product_name'];
			$price = $row[0]['product_price'];
			
			if(!empty($category_id)){
				$category = $DBobject->GetAnyCell('product_category_name','tbl_product_category', 'product_category_id = "'.($category_id).'"');
				$pname = $category.' - '.$pname;
			}
			
			if(!empty($attribute_id)){
				$attribute = $DBobject->GetRow("tbl_attribute", "attribute_id = '{$attribute_id}'");
				//$pname = $pname." ({$attribute['attribute_name']})";
				if(!empty($attribute['attribute_price'])){
					$price = $attribute['attribute_price'];
				}
			}
		    $res = $cart_obj->AddToCart($product_id,$attribute_id,$pname,$category_id,$price,$quantity,$trackcode);
		    
			$cart_obj->LoadCart();
			$template = $SMARTY->fetch('shopping-cart.tpl');
		    
		    echo json_encode(array(".cart-wrapper"=>$template));
		    exit;
		case 'SetPromoCode':
		    $cart_obj = new cart();
		    $promocode = clean($_POST['CheckoutPromoCode']);
		    $cart_obj->AddPromoCode($promocode);
		    header("Location: /checkout");
		    exit;
		case 'SetQty4Item':
			$cart_obj = new cart();
			/* Required values for cart-item {product_category, product_id, product_name, product_price, product_qty, product_special,additional}*/
			$cartitem_id = $_POST["cartitem_id"];
			$qtys = $_POST["quantity"];
			$cart_obj->UpdateQuantity4Item($cartitem_id, $qtys);
			
			$cart_obj->LoadCart();
			$template = $SMARTY->fetch('shopping-cart.tpl');
		    
		    echo json_encode(array(".cart-wrapper"=>$template));
			//header("Location: /checkout");
			exit;
		case 'DeleteItem':
			$cart_obj = new cart();
			if($cart_obj->RemoveFromCart($_POST["cartitem_id"])){
				$cart_obj->LoadCart();
				$template = $SMARTY->fetch('shopping-cart.tpl');
			    
			    echo json_encode(array(".cart-wrapper"=>$template));
			}
			exit;
		case 'MakeAPayment':
			exit;
		case 'EmailOrder':
			
			$cart = new cart();
			$payment_cartid = $cart->GetCartID();
			$customer = array();
			$customer['name'] = $_POST['name'];
			$customer['email'] = $_POST['email'];
			$customer['company'] = $_POST['company'];
			$customer['tel_office'] = $_POST['tel_office'];
			$customer['tel_mobile'] = $_POST['tel_mobile'];
			$customer['notes'] = $_POST['notes'];
			$customer['method'] = $_POST['method'];
			$customer['address'] = $_POST['address'];
			$customer['store_id'] = $_POST['store'];
			$customer['time'] = $_POST['time'];
			$customer['date'] = $_POST['date'];
			$cart->LoadCart();
			$orderNumber = $payment_cartid."-".time();
			
			$store = $cart->LoadStore($customer['store_id']);
			$customer['store'] = $store['store_name'];
			$store_email = $store['store_email'];
			
			$SMARTY->assign('customer',$customer);
			$SMARTY->assign('ordernumber',$orderNumber);
			
			$mail_content = $SMARTY->fetch('checkout-email.tpl');
			
			$to = $store_email;
			$person = $_SESSION['user_params']["shipping"]["fullname"];
			$subject = "Order: Funk Coffee";
			$body = $mail_content;
			if(sendMail($to , 'Funk Coffee', 'noreply@funkcoffeefood.com.au', $subject,$body)){
				$to = $_POST['email'];
				sendMail($to , 'Funk Coffee', 'noreply@funkcoffeefood.com.au', $subject,$body);
				
				$cart->CloseCart($payment_cartid);
				$cart	=	new cart();
				
				$post_content = serialize($_POST);
				
				$sql="INSERT INTO tbl_form (form_date, form_data, form_action, form_name, form_email, form_sender_ip, form_post ) VALUES  
					(NOW(),'".clean($body)."','ORDER','".clean($person)."','".clean($store_email)."','".clean($_SERVER['REMOTE_ADDR'])."','".clean($post_content)."')";
				$DBobject->wrappedSqlInsert($sql);
				header("Location: /thank-you");
			}else{
				header("Location: /checkout");
			}
			exit;
	}
}else{
	header('Location: /catering');
}