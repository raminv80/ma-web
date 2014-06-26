<?php


class ShippingClass {

    function getShippingMethods($qty = 1){
    	$methods = array();
    	//(Standard: 2-7 days)
    	//(Express: next business day)
    	if(($qty > 0) && ($qty <3)){
    		$methods['Shipping (standard)'] = 4.95;
    		$methods['Shipping (express)'] = 6.95;
    		
    	}elseif(($qty > 2) && ($qty < 8)){
    		$methods['Shipping (standard)'] = 8.95;
    		$methods['Shipping (express)'] = 10.95;
    		
    	}elseif($qty > 7){
    		$methods['Shipping (standard)'] = 0.00;
    	}
    	
    	return $this->verifyFreeShipping($methods);
    }
    
    function verifyFreeShipping($methodsArr){
    	$cart_obj = new cart();
    	$currentCart = $cart_obj->GetDataCart();
    	$discountData = $cart_obj->GetDiscountData($currentCart['cart_discount_code']);
    	if(!empty($discountData['discount_shipping'])){
	    	$newMethods = array();
	    	$hasFreeShip = false;
	    	foreach($methodsArr as $method => $value){
		    	if($discountData['discount_shipping'] == $method ){
		    		$newMethods["$method"] = 0.00;
		    		$hasFreeShip = true;
		    	}
	    	}
    	}
    	if($hasFreeShip){
    		return $newMethods;
    	}
    	return $methodsArr;
    }
}
	
