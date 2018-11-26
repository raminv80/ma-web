<?php 
global $CONFIG, $SMARTY, $DBobject, $GA_ID, $REQUEST_URI;
try{
  $cartItemId = 0;
  if(isset($_GET['cid'])){
    $cartItemId = $_GET['cid'];
  }
  $is_user_product = false;
  $chk_product = "SELECT cartitem_id FROM tbl_cartitem LEFT JOIN tbl_payment ON cartitem_cart_id = payment_cart_id WHERE payment_user_id = :user_id AND payment_response_code = 'approved'";
  $purchased_products = $DBobject->wrappedSql($chk_product, array(":user_id" => $_SESSION['user']['public']['id']));
  foreach($purchased_products as $p){
    if($p['cartitem_id'] == $cartItemId){
      $is_user_product = true;
    }
  }
  $products = array();
  $cart_obj = new cart($_SESSION['user']['public']['id']);
  $ordered_item = array();
  
  if($is_user_product){
    //LOAD PRODUCTS
    $prodObj = new ProductClass('', $CONFIG->product_page);
    
    $sql = "SELECT * FROM tbl_cartitem LEFT JOIN tbl_product ON product_object_id = cartitem_product_id
          LEFT JOIN tbl_variant ON variant_id = cartitem_variant_id
        WHERE (cartitem_deleted IS NULL OR cartitem_deleted = '0000-00-00') 
        AND cartitem_id <> '0' AND cartitem_id = :id AND product_published = 1 
        AND (product_deleted IS NULL OR product_deleted = '0000-00-00') 
        AND (variant_deleted IS NULL OR variant_deleted = '0000-00-00')";
    $res = $DBobject->wrappedSql($sql, array(
        ":id" => $cartItemId
    ));
    foreach($res as $p){
      $ordered_item = $p;
      // ---------------- ATTRIBUTES SAVED IN tbl_cartitem_attr ----------------
      $sql = "SELECT * FROM tbl_cartitem_attr WHERE cartitem_attr_cartitem_id = :id 
      AND (cartitem_attr_deleted IS NULL OR cartitem_attr_deleted = '0000-00-00') 
      AND cartitem_attr_cartitem_id <> '0' ORDER BY cartitem_attr_order";
      $params = array(":id" => $p['cartitem_id']);
      $ordered_item['attributes'] = $DBobject->wrappedSql($sql, $params);
    }
  }
  $SMARTY->assign('ordered_item', $ordered_item);
  
}catch(exceptionCart $e) {
  $SMARTY->assign('error', $e->getMessage());
}


