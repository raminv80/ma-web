<?php

if($_POST["action"]){
	switch ($_POST["action"]) {
		case 'ADDTOCART':
			$cart_obj = new cart();
			$response = $cart_obj->AddToCart($_POST["product_id"], $_POST["attr"], $_POST["quantity"], $_POST["price"]);
			$itemsCount = $cart_obj->NumberOfProductsOnCart();
			echo json_encode(array(
		    				"message"=>$response,
		    				"itemsCount"=> $itemsCount
	    				));
		    exit;
		    
	    case 'DeleteItem':
	    	$cart_obj = new cart();
	    	$response = $cart_obj->RemoveFromCart($_POST["cartitem_id"]);
                $total = $cart_obj->CalculateTotal();
	    	echo json_encode(array(
                                "response"=> $response,
                                "total"=>$total
                ));
	    	exit;

    	case 'updateCart':
    		$cart_obj = new cart();
    		$subtotals = $cart_obj->UpdateQtyCart($_POST["qty"]);
    		$total = $cart_obj->CalculateTotal();
                $itemsCount = $cart_obj->NumberOfProductsOnCart();
    		echo json_encode(array(
    				"subtotals"=>$subtotals,
    				"total"=>$total,
		    		"itemsCount"=> $itemsCount
    		));
    		exit;
    		
    	case 'placeOrder':
    		$cart_obj = new cart();
    		if ($_POST['same_address']) {
    			$res = $cart_obj->PlaceOrder($_POST["payment"], $_POST["address"][1]);
    		} else {
    			$res = $cart_obj->PlaceOrder($_POST["payment"], $_POST["address"][1], $_POST["address"][2]);
    		}
    		if ($res['error']) {
    			$_SESSION['error']= $res['error'];
    			$_SESSION['post']= $_POST;
    			header("Location: ".$_SERVER['HTTP_REFERER']."#error");
    		} else {
    			header("Location: /thank-you-for-buying");
    		}
    		
    		exit;
	    	
	    	// ==================== OLD STUFFS =====================
		case 'SetPromoCode':
		    $cart_obj = new cart();
		    $promocode = clean($_POST['CheckoutPromoCode']);
		    $cart_obj->AddPromoCode($promocode);
		    header("Location: /checkout");
		    exit;




	}
}else{
	die('');
}