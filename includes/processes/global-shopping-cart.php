<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;
$cart_obj = new cart();
/* if(!empty($_SESSION['user']['public']['store_id'])){
  $storeId = $_SESSION['user']['public']['store_id'];
  $sql = "SELECT listing_name, location_phone FROM tbl_listing LEFT JOIN tbl_location ON listing_id = location_listing_id WHERE listing_object_id = :id AND listing_deleted IS NULL AND listing_published = 1";
  $params = array(":id"=>$storeId);
  $res = $DBobject->wrappedSql($sql,$params);
  $SMARTY->assign("storename",$res[0]['listing_name']);
} */
try{
  $cart = $cart_obj->GetDataCart(); 
  $SMARTY->assign('cart',$cart);
  $itemNumber = $cart_obj->NumberOfProductsOnCart();
  $SMARTY->assign('itemNumber',$itemNumber);
  $subtotal = $cart_obj->GetSubtotal();
  $SMARTY->assign('subtotal',$subtotal);
  $productsOnCart = $cart_obj->GetDataProductsOnCart();
  $SMARTY->assign('productsOnCart',$productsOnCart);
  if($CONFIG->checkout->attributes()->guest == 'true'){
    $SMARTY->assign('allowGuest',true);
  }
}catch(exceptionCart $e) {
  $SMARTY->assign('error', $e->getMessage());
}

  
