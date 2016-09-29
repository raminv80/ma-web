<?php


class ShippingClass {
  protected $methods = array();
  protected $freeShippingStr;


  function __construct($qty = 0, $freeShippingStr = ''){
    $this->freeShippingStr = unclean($freeShippingStr);
    /* if(($qty > 0) && ($qty < 3)){
      $this->methods['Shipping (standard)'] = 4.95;
      $this->methods['Shipping (express)'] = 6.95;
    } elseif(($qty > 2) && ($qty < 8)){
      $this->methods['Shipping (standard)'] = 8.95;
      $this->methods['Shipping (express)'] = 10.95;
    } elseif($qty > 7){
      $this->methods['Shipping (standard)'] = 0.00;
    }else{
      $this->methods['Shipping (standard)'] = 0.00;
    }*/
    if($qty > 0){
      $this->methods['Postage & handling'] = 9.00;
    }else{
      $this->methods['Postage & handling'] = 0.00;
    }
  }

  
  function getShippingMethods(){
    return $this->verifyFreeShipping();
  }

  
  private function verifyFreeShipping(){
    if(!empty($this->freeShippingStr)){
      $newMethods = array();
      $hasFreeShip = false;
      foreach($this->methods as $method => $value){ 
        if($this->freeShippingStr == $method){
          $newMethods["$method"] = 0.00;
          $hasFreeShip = true;
        }
      }
    }
    if($hasFreeShip){
      return $newMethods;
    }
    return $this->methods;
  }


  function getPostageByPostcode($postcode){
    global $DBobject;
    
    $sql = "SELECT tbl_postage.* FROM tbl_postcode LEFT JOIN tbl_postage ON postcode_postcoderegion_id = postage_region_id WHERE postcode_postcode = :postcode";
    $params = array(
        ":postcode" => $postcode 
    );
    $res = $DBobject->wrappedSql($sql, $params);
    if(empty($res[0])){
      $sql = "SELECT * FROM tbl_postage WHERE postage_region_id = 0 AND postage_deleted IS NULL";
      $res = $DBobject->wrappedSql($sql);
    }
    return $res[0];
  }


  function getPostageByAddressId($addressId){
    global $DBobject;
    
    $sql = "SELECT address_postcode FROM tbl_address WHERE address_id = :id ";
    $res = $DBobject->wrappedSql($sql, array(
        ':id' => $addressId 
    ));
    $postage = $this->getPostageByPostcode($res[0]['address_postcode']);
    return $postage;
  }
}
	
