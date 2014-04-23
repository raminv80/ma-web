<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'admin/includes/functions/admin-functions.php';
global $DBobject;


if (!empty($_POST['from']) && !empty($_POST['to'])) {
	$fromDate = date("Y-m-d", strtotime( str_replace('/', '-', $_POST['from'])));
	$toDate = date("Y-m-d", strtotime( str_replace('/', '-', $_POST['to'])));
	
	$sql = "SELECT payment_created as 'Date', payment_transaction_no as 'Order', CONCAT( user_gname, ' ', user_surname) as Client, user_email as Email, payment_payee_name as 'Payee Name',  cartitem_product_name as 'Product Name', cartitem_product_price as 'Unit Price', cartitem_quantity as Quantity, payment_subtotal as Subtotal, payment_discount as Discount, payment_shipping_fee as 'Shipping Fee', payment_charged_amount as 'Total', payment_gst as 'Incl. GST', CONCAT('(To: ', b.address_name, ') ', b.address_line1, ', ', b.address_suburb, ', ', b.address_state, ', ', b.address_postcode, '. ', b.address_telephone) as 'Billing Address', CONCAT( '(To: ', s.address_name, ' ) ', s.address_line1, ', ', s.address_suburb, ', ', s.address_state, ', ', s.address_postcode, '. ', s.address_telephone) as 'Shipping Address', payment_shipping_method as 'Shipping Method', payment_shipping_comments as 'Shipping Comments' FROM `tbl_payment` LEFT JOIN tbl_user ON `payment_user_id` = user_id  LEFT JOIN tbl_cartitem ON `payment_cart_id` = cartitem_cart_id LEFT JOIN tbl_address as b ON `payment_billing_address_id` = b.address_id  LEFT JOIN tbl_address as s ON `payment_shipping_address_id` = s.address_id WHERE `payment_status` = 'P' AND DATE(payment_created) BETWEEN :from AND :to";
	$res = $DBobject->wrappedSql($sql, array(':from'=>$fromDate,':to'=>$toDate));

	$csv = AssociativeArrayToCSV($res);
				
	$filename='Paid_Orders_'.$_POST['from']. '_'. $_POST['to'] .'.csv';
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Content-Length: " . strlen($csv));
	header("Content-type: text/x-csv");
	header("Content-Disposition: attachment; filename=".$filename);
    echo $csv;
}

