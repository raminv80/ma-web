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
    
    

		function getPostageByPostcode($postcode){
			global $DBobject;
			
		 	$sql = "SELECT tbl_postage.* FROM tbl_postcode LEFT JOIN tbl_postage ON postcode_postcoderegion_id = postage_region_id WHERE postcode_postcode = :postcode";
      $params = array(":postcode"=>$postcode);
      $res = $DBobject->wrappedSql($sql,$params);
      if(empty($res[0])){
        $sql = "SELECT * FROM tbl_postage WHERE postage_region_id = 0 AND postage_deleted IS NULL";
        $res = $DBobject->wrappedSql($sql);
      }
			return $res[0];
		}
		
		
		function getPostageByAddressId($addressId){
			global $DBobject;
			
			$sql = "SELECT address_postcode FROM tbl_address WHERE address_id = :id ";
    	$res = $DBobject->wrappedSql ( $sql, array(':id' => $addressId) );
    	$postage = $this->getPostageByPostcode($res[0]['address_postcode']);
			return $postage;
		}
}
	
