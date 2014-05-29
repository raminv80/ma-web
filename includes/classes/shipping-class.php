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
    	
    	return $methods;
    }
}
	
