<?php

if($_POST["action"]){
	switch ($_POST["action"]) {
		case 'ADDTOCART':
			$cart_obj = new cart();
			$response = $cart_obj->AddToCart($_POST["product_id"], $_POST["attr"], $_POST["quantity"], $_POST["price"]);
			$itemsCount = $cart_obj->NumberOfProductsOnCart();
			$cart = $cart_obj->GetDataCart();
			$productsOnCart = $cart_obj->GetDataProductsOnCart();
			$SMARTY->assign('productsOnCart',$productsOnCart);
			$popoverShopCart= $SMARTY->fetch('templates/popover-shopping-cart.tpl');
			
			echo json_encode(array(
		    				"message" => $response,
		    				"itemsCount" => $itemsCount,
		    				"subtotal" => $cart['cart_subtotal'],
							"popoverShopCart" =>  str_replace(array("\r\n", "\r", "\n", "\t"), ' ', $popoverShopCart)
	    				));
		    exit;
		    
	    case 'DeleteItem':
	    	$cart_obj = new cart();
	    	$response = $cart_obj->RemoveFromCart($_POST["cartitem_id"]);
            $totals = $cart_obj->CalculateTotal();
            $itemsCount = $cart_obj->NumberOfProductsOnCart();
            $productsOnCart = $cart_obj->GetDataProductsOnCart();
            $SMARTY->assign('productsOnCart',$productsOnCart);
            $popoverShopCart= $SMARTY->fetch('templates/popover-shopping-cart.tpl');
	    	echo json_encode(array(
	    					"itemsCount" => $itemsCount,
                            "response"=> $response,
                            "totals"=>$totals,
							"popoverShopCart" =>  str_replace(array("\r\n", "\r", "\n", "\t"), ' ', $popoverShopCart)
            ));
	    	exit;

    	case 'updateCart':
    		$cart_obj = new cart();
    		$subtotals = $cart_obj->UpdateQtyCart($_POST["qty"]);
    		$totals = $cart_obj->CalculateTotal();
            $itemsCount = $cart_obj->NumberOfProductsOnCart();
            $productsOnCart = $cart_obj->GetDataProductsOnCart();
            $SMARTY->assign('productsOnCart',$productsOnCart);
            $popoverShopCart= $SMARTY->fetch('templates/popover-shopping-cart.tpl');
    		echo json_encode(array(
		    		"itemsCount"=> $itemsCount,
    				"subtotals"=>$subtotals,
    				"totals"=>$totals,
					"popoverShopCart" =>  str_replace(array("\r\n", "\r", "\n", "\t"), ' ', $popoverShopCart)
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
	    	
		case 'applyDiscount':
		    $cart_obj = new cart();
		    $res = $cart_obj->ApplyDiscountCode($_POST["discount_code"]);
		    if ($res['error']) {
		    	$_SESSION['error']= $res['error'];
		    	$_SESSION['post']= $_POST;
		    	header("Location: ".$_SERVER['HTTP_REFERER']."#error");
		    } else {
		    	header("Location: ".$_SERVER['HTTP_REFERER']);
		    }
		    exit;




	}
}else{
	die('');
}