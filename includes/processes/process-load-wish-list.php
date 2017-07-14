<?php
global $CONFIG, $SMARTY, $DBobject, $GA_ID, $REQUEST_URI;
try{
  $products = array();
  $cart_obj = new cart($_SESSION['user']['public']['id']);
  $prodArr = $cart_obj->GetWishList();
  
  if(!empty($prodArr)){
    $params = array();
    $whereArr = array();
    foreach($prodArr as $k => $p){
      $whereArr[] = "product_object_id = :pid{$k}";
      $params[":pid{$k}"] = $p;
    }
    $whereSql = 'AND ('. implode(' OR ', $whereArr) . ')';
    
    //LOAD PRODUCTS
    $prodObj = new ProductClass('', $CONFIG->product_page);
    $sql = "SELECT product_object_id, product_name, product_url, product_brand, product_meta_description FROM tbl_product
    WHERE product_deleted IS NULL AND product_published = 1 {$whereSql} ORDER BY product_order, product_name ";
    if($products = $DBobject->wrappedSql($sql, $params)){
      foreach($products as &$p){
        $p['general_details'] = $prodObj->GetProductGeneralDetails($p['product_object_id']);
      }
    }
  }
  
  $SMARTY->assign('products', $products);
  
}catch(exceptionCart $e) {
  $SMARTY->assign('error', $e->getMessage());
}

  
